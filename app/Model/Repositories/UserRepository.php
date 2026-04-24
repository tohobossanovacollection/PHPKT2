<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\User;
use RuntimeException;

final class UserRepository
{
    public function __construct(private string $jsonPath)
    {
    }

    /**
     * @return array<int, User>
     */
    public function all(): array
    {
        if (!is_file($this->jsonPath)) {
            return [];
        }

        $content = file_get_contents($this->jsonPath);
        if ($content === false || trim($content) === '') {
            return [];
        }

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            return [];
        }

        return array_values(array_map(static fn (array $item): User => User::fromArray($item), $decoded));
    }

    public function findByUsername(string $username): ?User
    {
        $normalized = mb_strtolower(trim($username));

        foreach ($this->all() as $user) {
            if (mb_strtolower($user->username) === $normalized) {
                return $user;
            }
        }

        return null;
    }

    public function save(User $user): void
    {
        $list = $this->all();
        $list[] = $user;
        $this->saveAll($list);
    }

    /**
     * @param array<int, User> $list
     */
    private function saveAll(array $list): void
    {
        $dir = dirname($this->jsonPath);
        if (!is_dir($dir) && !mkdir($dir, 0777, true) && !is_dir($dir)) {
            throw new RuntimeException('Không thể tạo thư mục lưu người dùng.');
        }

        $serialized = array_map(static fn (User $item): array => $item->toArray(), $list);
        $json = json_encode($serialized, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            throw new RuntimeException('Không thể encode dữ liệu người dùng.');
        }

        if (file_put_contents($this->jsonPath, $json) === false) {
            throw new RuntimeException('Không thể lưu dữ liệu người dùng.');
        }
    }
}
