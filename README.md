# tt_memc

[![Build Status](https://travis-ci.org/olegkishko/tt_memc.svg?branch=master)](https://travis-ci.org/olegkishko/tt_memc)


Test task: implementation the library for Memcache

## Installation
 
### Composer

`composer require olegkishko/tt_memc`

## Usage

```php
use tt_memc\CacheService;
use tt_memc\Driver\MemcachedDriver;

$cache = new CacheService(new MemcachedDriver());

// set values
$cache->set('int', 100);
$cache->set('string', 'Hello');
$cache->set('array', [0 => 'a']);
$cache->set('object', new stdClass());

// get values
var_dump($cache->get('int'));
var_dump($cache->get('string'));
var_dump($cache->get('array'));
var_dump($cache->get('object'));

// delete values
$cache->delete('int');
$cache->delete('string');
$cache->delete('array');
$cache->delete('object');
```
