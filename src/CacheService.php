<?php

namespace tt_memc;


use tt_memc\Driver\CacheDriverInterface;

class CacheService implements CacheServiceInterface
{
    /**
     * @var CacheDriverInterface
     */
    private $driver;
    
    /**
     * @param CacheDriverInterface $driver
     */
    public function __construct(CacheDriverInterface $driver)
    {
        $this->driver = $driver;
    }
    
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        return $this->driver->get($key);
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
        return $this->driver->set($key, $data, $flag, $expire);
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        return $this->driver->delete($key);
    }
}