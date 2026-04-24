<?php

declare(strict_types=1);

namespace App\Models;

final class User
{
    public function __construct(
        public string $id,
        public string $username,
        public string $passwordHash,
        public string $createdAt
    ) {
    }

    /**
     * @param array<string, mixed> $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            (string) ($data['id'] ?? ''),
            (string) ($data['username'] ?? ''),
            (string) ($data['passwordHash'] ?? ''),
            (string) ($data['createdAt'] ?? '')
        );
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'passwordHash' => $this->passwordHash,
            'createdAt' => $this->createdAt,
        ];
    }
}
