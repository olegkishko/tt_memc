<?php

declare(strict_types=1);

namespace tt_memc\Driver;

use tt_memc\Exception\ConnectionFailureException;

class MemcachedDriver implements CacheDriverInterface
{
    private const string DEFAULT_HOST = 'localhost';
    private const int DEFAULT_PORT = 11211;
    private const int DEFAULT_TIMEOUT = 60;

    private const string RESPONSE_STORED = 'STORED';
    private const string RESPONSE_DELETED = 'DELETED';
    private const string RESPONSE_END = 'END';

    private mixed $conn;
    
    /**
     * @throws ConnectionFailureException
     */
    public function __construct(
        string $host = self::DEFAULT_HOST,
        int $port = self::DEFAULT_PORT,
        int $timeout = self::DEFAULT_TIMEOUT,
    ) {
        $this->connect($host, $port, $timeout);
    }
    
    public function get(string $key): mixed
    {
        $this->write(sprintf("get %s\r\n", $key));
        $response = $this->read();
    
        if (!str_starts_with($response, self::RESPONSE_END)
            && preg_match('|^VALUE \w+ \d+ \d+|Umi', $response)
        ) {
            $data = unserialize($this->read(), ['allowed_classes' => true]);
            $this->read(); // read to end

            return $data;
        }
    
        return null;
    }
    
    public function set(string $key, mixed $data, int $flag = 0, int $expire = 3600): bool
    {
        $data = serialize($data);
        $byte = strlen($data);
        $this->write(sprintf("set %s %s %s %s\r\n", $key, $flag, $expire, $byte));
        $this->write(sprintf("%s\r\n", $data));
        $response = $this->read();
    
        return str_starts_with($response, self::RESPONSE_STORED);
    }
    
    public function delete(string $key): bool
    {
        $this->write(sprintf("delete %s\r\n", $key));
        $response = $this->read();
    
        return str_starts_with($response, self::RESPONSE_DELETED);
    }
    
    /**
     * @throws ConnectionFailureException
     */
    private function connect(string $host, int $port, int $timeout): void
    {
        $this->conn = fsockopen($host, $port, $errno, $errstr, $timeout);
        if (!$this->conn) {
            throw new ConnectionFailureException(sprintf('Cannot connect with message: ""%s: %s"', $errno, $errstr));
        }
    }
    
    private function close(): void
    {
        fclose($this->conn);
    }
    
    private function read(): string
    {
        return trim(fgets($this->conn));
    }
    
    private function write(string $message): void
    {
        fwrite($this->conn, $message);
    }
    
    public function __destruct()
    {
        $this->close();
    }
}