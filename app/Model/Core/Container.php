<?php

declare(strict_types=1);

namespace App\Core;

use Closure;
use RuntimeException;

final class Container
{
    /** @var array<string, Closure(self):mixed> */
    private array $bindings = [];

    /** @var array<string, mixed> */
    private array $instances = [];

    public function bind(string $id, Closure $factory): void
    {
        $this->bindings[$id] = $factory;
    }

    public function set(string $id, mixed $instance): void
    {
        $this->instances[$id] = $instance;
    }

    public function get(string $id): mixed
    {
        if (array_key_exists($id, $this->instances)) {
            return $this->instances[$id];
        }

        if (!array_key_exists($id, $this->bindings)) {
            throw new RuntimeException(sprintf('Service "%s" is not registered.', $id));
        }

        $this->instances[$id] = ($this->bindings[$id])($this);

        return $this->instances[$id];
    }
}
