<?php

declare(strict_types=1);

namespace App\Core;

use App\Models\User;

class Auth
{
    /**
     * Giriş yap
     */
    public static function login(User $user): void
    {
        Session::regenerate(); // Session fixation koruması

        $_SESSION['user'] = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ];
    }

    /**
     * Çıkış yap
     */
    public static function logout(): void
    {
        Session::destroy();
    }

    /**
     * Giriş yapılmış mı?
     */
    public static function check(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Giriş yapan kullanıcı bilgisi
     */
    public static function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Kullanıcı ID
     */
    public static function id(): ?int
    {
        return $_SESSION['user']['id'] ?? null;
    }

    /**
     * Admin mi?
     */
    public static function isAdmin(): bool
    {
        return ($_SESSION['user']['role'] ?? '') === 'admin';
    }
}