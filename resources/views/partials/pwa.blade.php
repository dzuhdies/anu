<link rel="manifest" href="/manifest.webmanifest">
<link rel="icon" type="image/png" sizes="192x192" href="/icons/icon-192.png">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<link rel="apple-touch-icon" href="/icons/icon-180.png">
<meta name="theme-color" content="#0284c7">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<meta name="apple-mobile-web-app-title" content="Magang BPS Kota Malang">

<!-- PWA Pull to Refresh Indicator Styles -->
<style>
    #pwaPullIndicator {
        position: fixed;
        top: 14px;
        left: 50%;
        transform: translate(-50%, -75px);
        width: 42px;
        height: 42px;
        border-radius: 9999px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15), 0 1px 4px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(226, 232, 240, 0.9);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 99999;
        pointer-events: none;
        transition: transform 0.2s cubic-bezier(0.2, 0.8, 0.2, 1), opacity 0.2s ease;
        opacity: 0;
    }
    #pwaPullIndicator.pwa-pulling {
        transition: none;
    }
    #pwaPullIndicator.pwa-refreshing {
        transform: translate(-50%, 18px) !important;
        opacity: 1 !important;
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    #pwaPullIcon {
        color: #0d9488;
        font-size: 14px;
        transition: transform 0.1s linear, color 0.15s ease;
    }
    .pwa-spin {
        animation: pwaSpinAnim 0.75s linear infinite !important;
    }
    @keyframes pwaSpinAnim {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>

<!-- Floating Pull-to-Refresh Indicator Element -->
<div id="pwaPullIndicator" aria-hidden="true">
    <i id="pwaPullIcon" class="fa-solid fa-arrow-down"></i>
</div>

<script>
    (() => {
        // 1. Service Worker Registration & Updates
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/service-worker.js', { scope: '/' })
                    .then((reg) => {
                        // Periksa pembaruan berkala
                        reg.addEventListener('updatefound', () => {
                            const newWorker = reg.installing;
                            if (newWorker) {
                                newWorker.addEventListener('statechange', () => {
                                    if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
                                        console.log('Versi baru aplikasi terdeteksi.');
                                    }
                                });
                            }
                        });
                    })
                    .catch((error) => console.error('Service worker gagal didaftarkan:', error));
            });
        }

        // 2. Global PWA Refresh Function
        window.pwaRefreshPage = function() {
            const indicator = document.getElementById('pwaPullIndicator');
            const icon = document.getElementById('pwaPullIcon');

            if (indicator && icon) {
                indicator.classList.remove('pwa-pulling');
                indicator.classList.add('pwa-refreshing');
                icon.className = 'fa-solid fa-rotate-right pwa-spin';
                icon.style.color = '#ff6b00';
            }

            if ('serviceWorker' in navigator && navigator.serviceWorker.controller) {
                navigator.serviceWorker.controller.postMessage({ action: 'skipWaiting' });
            }

            setTimeout(() => {
                window.location.reload();
            }, 300);
        };

        // 3. Universal Mobile Pull-to-Refresh Gesture
        let touchStartY = 0;
        let touchStartX = 0;
        let isPulling = false;
        let activeScrollEl = null;
        let pullDist = 0;

        const getScrollTop = (el) => {
            if (!el || el === document.body || el === document.documentElement) {
                return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            }
            return el.scrollTop || 0;
        };

        const findScrollContainer = (target) => {
            let el = target;
            while (el && el !== document.body && el !== document.documentElement) {
                const style = window.getComputedStyle(el);
                const overflowY = style.overflowY;
                if ((overflowY === 'auto' || overflowY === 'scroll') && el.scrollHeight > el.clientHeight) {
                    return el;
                }
                el = el.parentElement;
            }
            return window;
        };

        window.addEventListener('touchstart', (e) => {
            if (e.touches.length !== 1) return;

            const targetTag = e.target.tagName;
            if (targetTag === 'INPUT' || targetTag === 'TEXTAREA' || targetTag === 'SELECT') {
                touchStartY = 0;
                return;
            }

            activeScrollEl = findScrollContainer(e.target);
            const currentScroll = getScrollTop(activeScrollEl);

            if (currentScroll <= 3) {
                touchStartY = e.touches[0].clientY;
                touchStartX = e.touches[0].clientX;
                isPulling = false;
                pullDist = 0;
            } else {
                touchStartY = 0;
            }
        }, { passive: true });

        window.addEventListener('touchmove', (e) => {
            if (!touchStartY || e.touches.length !== 1) return;

            const currentY = e.touches[0].clientY;
            const currentX = e.touches[0].clientX;
            const deltaY = currentY - touchStartY;
            const deltaX = currentX - touchStartX;

            // Pastikan gesture mengarah ke bawah vertikal
            if (deltaY > 10 && Math.abs(deltaY) > Math.abs(deltaX)) {
                const currentScroll = getScrollTop(activeScrollEl);
                if (currentScroll <= 0) {
                    isPulling = true;
                    pullDist = Math.min(Math.pow(deltaY, 0.82) * 1.4, 90);

                    const indicator = document.getElementById('pwaPullIndicator');
                    const icon = document.getElementById('pwaPullIcon');

                    if (indicator && icon && pullDist > 12) {
                        indicator.classList.add('pwa-pulling');
                        indicator.style.transform = `translate(-50%, ${pullDist - 48}px)`;
                        indicator.style.opacity = Math.min((pullDist - 12) / 30, 1);

                        const rotation = Math.min((pullDist / 65) * 180, 180);
                        icon.style.transform = `rotate(${rotation}deg)`;

                        if (pullDist >= 65) {
                            icon.style.color = '#ff6b00';
                        } else {
                            icon.style.color = '#0d9488';
                        }
                    }
                } else {
                    isPulling = false;
                }
            }
        }, { passive: true });

        window.addEventListener('touchend', () => {
            if (isPulling) {
                const indicator = document.getElementById('pwaPullIndicator');
                const icon = document.getElementById('pwaPullIcon');

                if (pullDist >= 65) {
                    window.pwaRefreshPage();
                } else if (indicator && icon) {
                    indicator.classList.remove('pwa-pulling');
                    indicator.style.transform = '';
                    indicator.style.opacity = '';
                    icon.style.transform = '';
                    icon.style.color = '';
                }
            }
            touchStartY = 0;
            isPulling = false;
            pullDist = 0;
        }, { passive: true });

        // 4. PWA Installation Handler
        let installPrompt = null;

        const updateInstallButtonState = () => {
            const installBtn = document.getElementById('pwaInstallButton');
            const installArea = document.getElementById('pwaInstallArea');

            if (installPrompt) {
                if (installBtn) {
                    installBtn.hidden = false;
                    installBtn.style.display = '';
                }
                if (installArea) {
                    installArea.classList.remove('hidden');
                    installArea.style.display = '';
                }
            } else {
                if (installBtn) {
                    installBtn.hidden = true;
                }
                if (installArea) {
                    installArea.classList.add('hidden');
                }
            }
        };

        const setupInstallHandler = () => {
            const installBtn = document.getElementById('pwaInstallButton');
            if (installBtn) {
                installBtn.addEventListener('click', async () => {
                    if (!installPrompt) return;

                    installPrompt.prompt();
                    await installPrompt.userChoice;
                    installPrompt = null;
                    updateInstallButtonState();
                });
            }
            updateInstallButtonState();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupInstallHandler);
        } else {
            setupInstallHandler();
        }

        window.addEventListener('beforeinstallprompt', (event) => {
            event.preventDefault();
            installPrompt = event;
            updateInstallButtonState();
        });

        window.addEventListener('appinstalled', () => {
            installPrompt = null;
            updateInstallButtonState();
        });
    })();
</script>
