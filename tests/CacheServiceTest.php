<?php

namespace tt_memcTest;

use PHPUnit\Framework\TestCase;
use tt_memc\CacheService;
use tt_memc\CacheServiceInterface;
use tt_memc\Driver\InMemoryDriver;

class CacheServiceTest extends TestCase
{
    /**
     * @var CacheServiceInterface
     */
    private $service;
    
    public function setUp()
    {
        $this->service = new CacheService(new InMemoryDriver());
    }
    
    public function testCanSetValueByKey()
    {
        $this->assertTrue($this->service->set('key', 'data'));
    }
    
    public function testCanGetValueByKey()
    {
        // int
        $this->service->set($key = 'int', $data = 100);
        $this->assertEquals($data, $cache = $this->service->get($key));
        $this->assertIsInt($cache);
        
        // string
        $this->service->set($key = 'string', $data = 'hello');
        $this->assertEquals($data, $cache = $this->service->get($key));
        $this->assertIsString($cache);
        
        // array
        $this->service->set($key = 'array', $data = [0 => 'a', 1 => 'b']);
        $this->assertEquals($data, $cache = $this->service->get($key));
        $this->assertIsArray($cache);
        
        // object
        $this->service->set($key = 'object', $data = new \stdClass());
        $this->assertEquals($data, $cache = $this->service->get($key));
        $this->assertIsObject($cache);
        
        // returns null
        $this->assertNull($this->service->get('unknown'));
    }
    
    public function testCanDeleteValueByKey()
    {
        $this->service->set($key = 'key', $data = 'data');
        
        $this->assertTrue($this->service->delete($key));
        $this->assertNull($this->service->get($key));
        $this->assertFalse($this->service->delete($key));
    }
}
