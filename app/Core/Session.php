<?php

declare(strict_types=1);

namespace App\Core;

class Session
{
    private static bool $started = false;

    /**
     * Session başlat
     */
    public static function start(): void
    {
        if (self::$started) {
            return;
        }

        if (session_status() === PHP_SESSION_NONE) {
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_httponly', '1');

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }

            session_start();
        }

        self::$started = true;
    }

    /**
     * Session değeri al
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Session değeri yaz
     */
    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Session değeri var mı?
     */
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Session değeri sil
     */
    public static function forget(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Session ID'yi yenile (fixation koruması)
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * Session'ı tamamen yok et
     */
    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
        self::$started = false;
    }
}