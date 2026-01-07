<?php

declare(strict_types=1);

namespace App\Core;

class Validator
{
    private array $errors = [];
    private array $data = [];
    private static ?array $tables = null;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Veritabanındaki tabloları al (cache'li)
     */
    private static function getTables(): array
    {
        if (self::$tables === null) {
            $stmt = Database::getInstance()->query("SHOW TABLES");
            self::$tables = $stmt->fetchAll(\PDO::FETCH_COLUMN);
        }
        return self::$tables;
    }

    /**
     * Validasyon çalıştır
     */
    public function validate(array $rules): bool
    {
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);

            foreach ($rulesArray as $rule) {
                $this->applyRule($field, $rule);
            }
        }

        return empty($this->errors);
    }

    /**
     * Kuralı uygula
     */
    private function applyRule(string $field, string $rule): void
    {
        $value = $this->data[$field] ?? null;
        $param = null;

        if (str_contains($rule, ':')) {
            [$rule, $param] = explode(':', $rule, 2);
        }

        match ($rule) {
            'required' => $this->required($field, $value),
            'email' => $this->email($field, $value),
            'min' => $this->min($field, $value, (int) $param),
            'max' => $this->max($field, $value, (int) $param),
            'confirmed' => $this->confirmed($field, $value),
            'unique' => $this->unique($field, $value, $param),
            default => null
        };
    }

    private function required(string $field, mixed $value): void
    {
        if ($value === null || $value === '') {
            $this->errors[$field] = "{$field} alanı zorunludur.";
        }
    }

    private function email(string $field, mixed $value): void
    {
        if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "Geçerli bir email adresi giriniz.";
        }
    }

    private function min(string $field, mixed $value, int $min): void
    {
        if ($value && strlen($value) < $min) {
            $this->errors[$field] = "{$field} en az {$min} karakter olmalıdır.";
        }
    }

    private function max(string $field, mixed $value, int $max): void
    {
        if ($value && strlen($value) > $max) {
            $this->errors[$field] = "{$field} en fazla {$max} karakter olmalıdır.";
        }
    }

    private function confirmed(string $field, mixed $value): void
    {
        $confirmField = $this->data[$field . '_confirmation'] ?? null;

        if ($value !== $confirmField) {
            $this->errors[$field] = "{$field} alanları eşleşmiyor.";
        }
    }

    private function unique(string $field, mixed $value, string $table): void
    {
        if (!$value) {
            return;
        }

        // Tablo DB'de var mı?
        if (!in_array($table, self::getTables(), true)) {
            throw new \InvalidArgumentException("Geçersiz tablo: {$table}");
        }

        // Field regex kontrolü
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $field)) {
            throw new \InvalidArgumentException("Geçersiz alan adı: {$field}");
        }

        $stmt = Database::getInstance()->prepare("SELECT id FROM {$table} WHERE {$field} = ? LIMIT 1");
        $stmt->execute([$value]);

        if ($stmt->fetch()) {
            $this->errors[$field] = "Bu {$field} zaten kullanılıyor.";
        }
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function firstError(): ?string
    {
        return $this->errors ? array_values($this->errors)[0] : null;
    }
}