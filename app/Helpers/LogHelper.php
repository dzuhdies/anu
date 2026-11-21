<?php

namespace App\Helpers;

use App\Models\LogActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\MessageBag;

class LogHelper
{
    /**
     * Resolve IPv4 address from request or convert IPv6 to deterministic IPv4.
     */
    public static function getIpv4(?Request $request = null): string
    {
        $request = $request ?? request();
        if (!$request) {
            return '127.0.0.1';
        }

        // Candidates to check
        $candidates = [];

        // 1. Cloudflare Pseudo IPv4 header
        if ($cfPseudo = $request->header('CF-Pseudo-IPv4') ?: $request->server('HTTP_CF_PSEUDO_IPV4')) {
            $candidates[] = trim($cfPseudo);
        }

        // 2. CF-Connecting-IP
        if ($cfConnecting = $request->header('CF-Connecting-IP') ?: $request->server('HTTP_CF_CONNECTING_IP')) {
            $candidates[] = trim($cfConnecting);
        }

        // 3. X-Forwarded-For (can be comma-separated list: client, proxy1, proxy2...)
        if ($xff = $request->header('X-Forwarded-For') ?: $request->server('HTTP_X_FORWARDED_FOR')) {
            $ips = explode(',', $xff);
            foreach ($ips as $ip) {
                $candidates[] = trim($ip);
            }
        }

        // 4. Other standard proxy headers
        if ($xRealIp = $request->header('X-Real-IP') ?: $request->server('HTTP_X_REAL_IP')) {
            $candidates[] = trim($xRealIp);
        }
        if ($clientIp = $request->header('Client-IP') ?: $request->server('HTTP_CLIENT_IP')) {
            $candidates[] = trim($clientIp);
        }

        // 5. Standard request IP
        $candidates[] = $request->ip();

        // Pass 1: Look for any candidate that is already a valid IPv4
        foreach ($candidates as $cand) {
            if (!$cand) continue;
            // Handle IPv4-mapped IPv6 (::ffff:192.168.1.1)
            if (str_starts_with($cand, '::ffff:')) {
                $sub = substr($cand, 7);
                if (filter_var($sub, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    return $sub;
                }
            }
            if (filter_var($cand, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                return $cand;
            }
        }

        // Pass 2: Fallback - pick the first valid IP (which might be IPv6) and convert to IPv4
        foreach ($candidates as $cand) {
            if (!$cand) continue;
            if ($cand === '::1' || $cand === 'localhost') {
                return '127.0.0.1';
            }
            if (filter_var($cand, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
                return self::convertIpv6ToIpv4($cand);
            }
        }

        return '127.0.0.1';
    }

    /**
     * Deterministically map an IPv6 address to Carrier-Grade NAT IPv4 (100.64.0.0/10).
     */
    public static function convertIpv6ToIpv4(string $ipv6): string
    {
        if ($ipv6 === '::1' || $ipv6 === 'localhost') {
            return '127.0.0.1';
        }
        if (str_starts_with($ipv6, '::ffff:')) {
            $sub = substr($ipv6, 7);
            if (filter_var($sub, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                return $sub;
            }
        }

        $hash = sprintf('%u', crc32($ipv6));
        $octet2 = 64 + (($hash >> 16) & 63); // 64 - 127
        $octet3 = ($hash >> 8) & 255;        // 0 - 255
        $octet4 = $hash & 255;               // 0 - 255

        return "100.{$octet2}.{$octet3}.{$octet4}";
    }

    /**
     * General logging method
     *
     * @param Request|null $request
     * @param string $aksi
     * @param mixed $keterangan
     * @param string $status 'berhasil'|'gagal'|'salah_isi'|'warning'
     * @param string|null $customUser
     * @param string|null $customRole
     */
    public static function log($request, $aksi, $keterangan = null, $status = 'berhasil', ?string $customUser = null, ?string $customRole = null)
    {
        $req = $request instanceof Request ? $request : request();
        $ip = self::getIpv4($req);

        $currentUser = Auth::user();
        $username = $customUser ?: ($currentUser ? $currentUser->username : 'Guest');
        $role = $customRole ?: ($currentUser ? $currentUser->role : 'guest');

        if (is_array($keterangan)) {
            $keterangan = json_encode($keterangan, JSON_UNESCAPED_UNICODE);
        }

        return LogActivity::create([
            'user'        => $username,
            'role'        => $role,
            'status'      => $status,
            'action'      => $aksi,
            'description' => $keterangan,
            'ip_address'  => $ip,
            'user_agent'  => $req ? $req->userAgent() : null,
        ]);
    }

    /**
     * Log successful operations
     */
    public static function success($request, $aksi, $keterangan = null, ?string $customUser = null, ?string $customRole = null)
    {
        return self::log($request, $aksi, $keterangan, 'berhasil', $customUser, $customRole);
    }

    /**
     * Log failure / error operations
     */
    public static function fail($request, $aksi, $keterangan = null, ?string $customUser = null, ?string $customRole = null)
    {
        return self::log($request, $aksi, $keterangan, 'gagal', $customUser, $customRole);
    }

    /**
     * Log invalid input / validation errors
     */
    public static function invalid($request, $aksi, $errors, ?string $customUser = null, ?string $customRole = null)
    {
        $desc = '';
        if ($errors instanceof ValidationException) {
            $messages = [];
            foreach ($errors->errors() as $field => $fieldMessages) {
                $messages[] = "{$field}: " . implode(', ', $fieldMessages);
            }
            $desc = 'Salah isi input: [' . implode('; ', $messages) . ']';
        } elseif ($errors instanceof MessageBag) {
            $messages = [];
            foreach ($errors->toArray() as $field => $fieldMessages) {
                $messages[] = "{$field}: " . implode(', ', $fieldMessages);
            }
            $desc = 'Salah isi input: [' . implode('; ', $messages) . ']';
        } elseif (is_array($errors)) {
            $messages = [];
            foreach ($errors as $k => $v) {
                $valStr = is_array($v) ? implode(', ', $v) : (string)$v;
                $messages[] = is_numeric($k) ? $valStr : "{$k}: {$valStr}";
            }
            $desc = 'Salah isi input: [' . implode('; ', $messages) . ']';
        } else {
            $desc = (string)$errors;
        }

        return self::log($request, $aksi, $desc, 'salah_isi', $customUser, $customRole);
    }
}
