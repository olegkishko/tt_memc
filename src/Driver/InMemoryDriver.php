<?php

declare(strict_types=1);

namespace tt_memc\Driver;

class InMemoryDriver implements CacheDriverInterface
{
    private array $store = [];
    
    public function get(string $key): mixed
    {
        if (isset($this->store[$key])) {
            return unserialize($this->store[$key], ['allowed_classes' => true]);
        }

        return null;
    }
    
    public function set(string $key, mixed $data, int $flag = 0, int $expire = 3600): bool
    {
        $this->store[$key] = serialize($data);

        return true;
    }
    
    public function delete(string $key): bool
    {
        if (isset($this->store[$key])) {
            unset($this->store[$key]);

            return true;
        }

        return false;
    }
}