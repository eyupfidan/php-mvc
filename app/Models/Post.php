<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\ORM;

class Post extends ORM
{
    protected static string $table = 'posts';
    protected static array $fillable = ['user_id', 'title', 'body'];

    /**
     * Post sahibini getir
     */
    public function user(): ?User
    {
        return User::find($this->user_id);
    }

    /**
     * Bu kullanıcının postu mu?
     */
    public function belongsTo(int $userId): bool
    {
        return $this->user_id === $userId;
    }
}