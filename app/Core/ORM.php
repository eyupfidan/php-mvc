<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class ORM
{
    protected static string $table = '';
    protected static array $fillable = [];
    protected array $attributes = [];

    protected static function db(): PDO
    {
        return Database::getInstance();
    }

    /**
     * Tüm kayıtlar
     */
    public static function all(): array
    {
        $stmt = self::db()->query("SELECT * FROM " . static::$table);
        return $stmt->fetchAll();
    }

    /**
     * ID ile bul
     */
    public static function find(int $id): ?static
    {
        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch();

        return $data ? self::mapToInstance($data) : null;
    }

    /**
     * Tek alan ile bul
     */
    public static function findBy(string $column, mixed $value): ?static
    {
        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE {$column} = ? LIMIT 1");
        $stmt->execute([$value]);
        $data = $stmt->fetch();

        return $data ? self::mapToInstance($data) : null;
    }

    /**
     * Koşula göre çoklu kayıt
     */
    public static function where(string $column, mixed $value): array
    {
        $stmt = self::db()->prepare("SELECT * FROM " . static::$table . " WHERE {$column} = ?");
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    /**
     * Yeni kayıt
     */
    public static function create(array $data): ?static
    {
        $data = array_intersect_key($data, array_flip(static::$fillable));

        if (empty($data)) {
            return null;
        }

        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));

        $stmt = self::db()->prepare("INSERT INTO " . static::$table . " ({$columns}) VALUES ({$placeholders})");
        $stmt->execute(array_values($data));

        return static::find((int) self::db()->lastInsertId());
    }

    /**
     * Güncelle
     */
    public function update(array $data): bool
    {
        $data = array_intersect_key($data, array_flip(static::$fillable));

        if (empty($data)) {
            return false;
        }

        $set = implode(' = ?, ', array_keys($data)) . ' = ?';
        $values = array_values($data);
        $values[] = $this->attributes['id'];

        $stmt = self::db()->prepare("UPDATE " . static::$table . " SET {$set} WHERE id = ?");
        $result = $stmt->execute($values);

        if ($result) {
            $this->attributes = array_merge($this->attributes, $data);
        }

        return $result;
    }

    /**
     * Sil
     */
    public function delete(): bool
    {
        $stmt = self::db()->prepare("DELETE FROM " . static::$table . " WHERE id = ?");
        return $stmt->execute([$this->attributes['id']]);
    }

    /**
     * Data'yı instance'a çevir
     */
    private static function mapToInstance(array $data): static
    {
        $instance = new static();
        $instance->attributes = $data;
        return $instance;
    }

    public function __get(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set(string $name, mixed $value): void
    {
        $this->attributes[$name] = $value;
    }

    public function toArray(): array
    {
        return $this->attributes;
    }
}