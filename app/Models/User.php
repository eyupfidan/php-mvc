<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\ORM;

class User extends ORM
{
    protected static string $table = 'users';
    protected static array $fillable = ['name', 'email', 'password', 'role'];

    /**
     * Şifre doğrula
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    /**
     * Admin mi?
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}