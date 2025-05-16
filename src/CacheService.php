<?php

declare(strict_types=1);

namespace tt_memc;

use tt_memc\Driver\CacheDriverInterface;

class CacheService implements CacheServiceInterface
{
    public function __construct(
        private readonly CacheDriverInterface $driver,
    ) {
    }
    
    public function get(string $key): mixed
    {
        return $this->driver->get($key);
    }
    
    public function set(string $key, mixed $data, int $flag = 0, int $expire = 3600): bool
    {
        return $this->driver->set($key, $data, $flag, $expire);
    }
    
    public function delete(string $key): bool
    {
        return $this->driver->delete($key);
    }
}