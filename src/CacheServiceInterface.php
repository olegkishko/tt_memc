<?php

declare(strict_types=1);

namespace tt_memc;

interface CacheServiceInterface
{
    public function get(string $key): mixed;
    
    public function set(string $key, mixed $data, int $flag = 0, int $expire = 3600): bool;
    
    public function delete(string $key): bool;
}