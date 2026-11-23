# Panduan Menjalankan Project dengan Docker

## One-Command Setup

1. **Clone repository ini**:
   ```bash
   git clone <URL_REPOSITORY>
   cd magang
   ```

2. **Jalankan Docker Compose**:
   ```bash
   docker compose up -d --build
   ```

3. **Selesai!**
   Container akan otomatis:
   - Menyiapkan environment PHP 8.2 & Nginx
   - Menjalankan container database MySQL 8.0 & menunggu sampai siap
   - Melakukan setup file `.env` & generate `APP_KEY`
   - Menghubungkan symlink storage (`php artisan storage:link`)
   - Menjalankan migrasi database (`php artisan migrate --seed`) otomatis

---

## 🌐 Akses Aplikasi

| Layanan | URL Akses | Keterangan / Kredensial Default |
|---|---|---|
| **Web Portal Magang** | [http://localhost:8082](http://localhost:8082) | Aplikasi utama |
| **phpMyAdmin** | [http://localhost:8081](http://localhost:8081) | User: `root` / Pass: `root_secret_password` |
| **MySQL Port** | `localhost:3307` | Database: `magangbps`, User: `magang_user`, Pass: `magang_secret` |

### 🔑 Akun Default Awal (Hasil Seeder):
- **Username**: `admin`
- **Password**: `admin123`
- **Role**: `admin`

---

## 🛠️ Perintah Berguna Lainnya

- **Melihat status container**:
  ```bash
  docker compose ps
  ```

- **Melihat log container aplikasi**:
  ```bash
  docker compose logs -f app
  ```

- **Menjalankan perintah Artisan di dalam container**:
  ```bash
  docker compose exec app php artisan <perintah>
  # Contoh:
  docker compose exec app php artisan route:list
  ```

- **Menjalankan migrate fresh ulang + seed**:
  ```bash
  docker compose exec app php artisan migrate:fresh --seed
  ```

- **Menghentikan container**:
  ```bash
  docker compose down
  ```

- **Menghentikan dan menghapus volume database**:
  ```bash
  docker compose down -v
  ```
