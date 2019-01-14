<?php

namespace tt_memc\Driver;


class InMemoryDriver implements CacheDriverInterface
{
    /**
     * @var array
     */
    private $store = [];
    
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        if (isset($this->store[$key])) {
            return unserialize($this->store[$key]);
        } else {
            return null;
        }
    }
    
    /**
     * @param string $key
     * @param mixed $data
     * @param int $flag
     * @param int $expire
     * @return bool
     */
    public function set(string $key, $data, int $flag = 0, int $expire = 3600): bool
    {
        $this->store[$key] = serialize($data);
        return true;
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        if (isset($this->store[$key])) {
            unset($this->store[$key]);
            return true;
        } else {
            return false;
        }
    }
}