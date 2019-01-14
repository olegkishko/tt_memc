<?php

namespace tt_memc;


interface CacheServiceInterface
{
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);
    
    /**
     * @param string $key
     * @param mixed $data
     * @param int $flag
     * @param int $expire
     * @return bool
     */
    public function set(string $key, $data, int $flag = 0, int $expire = 3600): bool;
    
    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool;
}