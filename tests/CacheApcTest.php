<?php

use Selfish\Cache\Cache;

class CacheApcTest extends PHPUnit_Framework_TestCase
{
    const NS = '_global_';

    protected function setUp()
    {
        if (!extension_loaded('apc') || !ini_get('apc.enable_cli')) {
            $this->markTestSkipped('The apc extension is not available. (call with: php -d apc.enable_cli=1)');
        }
    }

    public function testSetAndGet()
    {
        $cache = new Cache('tests1');

        // integer
        $key = 'number';
        $value = mt_rand();

        $cache->set($key, $value);
        $this->assertEquals(true, $cache->has($key));
        $this->assertEquals($value, $cache->get($key));
        $cache->delete($key);

        // string
        $key = 'string';
        $value = 'test string';

        $cache->set($key, $value);
        $this->assertEquals(true, $cache->has($key));
        $this->assertEquals($value, $cache->get($key));
        $cache->delete($key);

        // array
        $key = 'string';
        $value = array(1, 2, 'test string');

        $cache->set($key, $value);
        $this->assertEquals(true, $cache->has($key));
        $cached_value = $cache->get($key);
        $this->assertEquals(3, count($cached_value));
        $this->assertEquals(1, $cached_value[0]);
        $this->assertEquals('test string', $cached_value[2]);
        $cache->delete($key);
    }

    public function testShortcutMethod()
    {
        // default
        $value = Cache::invoke('cache_key', function() {
            return mt_rand(0, 123456789);
        });

        $this->assertEquals($value, apc_fetch($this->ns('cache_key')));

        // custom namespace
        $namespace = '_tests_';

        $value = Cache::invoke('cache_key', function() {
            return mt_rand(0, 123456789);
        }, $namespace);

        $this->assertEquals($value, apc_fetch($this->ns('cache_key', $namespace)));
    }

    protected function ns($name, $namespace = self::NS)
    {
        return $namespace . '.' . $name;
    }
}

