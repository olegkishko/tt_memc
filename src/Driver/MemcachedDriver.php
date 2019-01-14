<?php

namespace tt_memc\Driver;


use tt_memc\Exception\ConnectionFailureException;

class MemcachedDriver implements CacheDriverInterface
{
    /**
     * @var mixed
     */
    private $conn;
    
    /**
     * @param string $host
     * @param int $port
     * @param int $timeout
     * @throws ConnectionFailureException
     */
    public function __construct(string $host = 'localhost', int $port = 11211, int $timeout = 60)
    {
        $this->connect($host, $port, $timeout);
    }
    
    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        $this->write("get {$key}\r\n");
        $response = $this->read();
    
        if (!preg_match('|^END|', $response) && preg_match('|^VALUE \w+ \d+ \d+|Umsi', $response)) {
            $data = unserialize($this->read());
            $this->read(); // read to end
            return $data;
        }
    
        return null;
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
        $data = serialize($data);
        $byte = strlen($data);
        $this->write("set {$key} {$flag} {$expire} {$byte}\r\n");
        $this->write("{$data}\r\n");
        $response = $this->read();
    
        return (preg_match('|^STORED|', $response)) ? true : false;
    }
    
    /**
     * @param string $key
     * @return bool
     */
    public function delete(string $key): bool
    {
        $this->write("delete {$key}\r\n");
        $response = $this->read();
    
        return (preg_match('|^DELETED|', $response)) ? true : false;
    }
    
    /**
     * @throws ConnectionFailureException
     */
    private function connect(string $host, int $port, int $timeout): void
    {
        $this->conn = fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$this->conn) {
            throw new ConnectionFailureException("Cannot connect with message: '{$errno}: {$errstr}'");
        }
    }
    
    /**
     *
     */
    private function close(): void
    {
        fclose($this->conn);
    }
    
    /**
     * @return mixed
     */
    private function read()
    {
        return trim(fgets($this->conn));
    }
    
    /**
     * @param string $message
     */
    private function write(string $message): void
    {
        fwrite($this->conn, $message);
    }
    
    public function __destruct()
    {
        $this->close();
    }
}