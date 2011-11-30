<?php
/**
 * Cache.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\Cache;

/**
 * Cache
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
class Cache
{
    protected $namespace;

    static protected $storage;

    static public function registerStorage(Storage $storage)
    {
        static::$storage = $storage;
    }

    public function __construct($namespace = '_global_')
    {
        $this->namespace = $namespace;

        if (is_null(static::$storage)) {
            self::registerStorage(new Apc);
        }
    }

    public function has($cache_key)
    {
        return static::$storage->has($this->ns($cache_key));
    }

    public function set($cache_key, $value, $expire=0)
    {
        static::$storage->set($this->ns($cache_key), $value, $expire);
    }

    public function get($cache_key)
    {
        return static::$storage->get($this->ns($cache_key));
    }

    public function delete($cache_key)
    {
        static::$storage->delete($this->ns($cache_key));
    }

    protected function ns($cache_key)
    {
        return $this->namespace . '.' . $cache_key;
    }

    /**
     * shortcut function
     *
     * @param string cachekey
     * @param callback
     * @return mixed
     */
    static public function invoke($cache_key, $callback=null, $namespace = '_global_')
    {
        $cache = new static($namespace);

        if ($cache->has($cache_key)) {
            return $cache->get($cache_key);
        }

        if (is_callable($callback)) {
            $value = $callback();
            $cache->set($cache_key, $value);

            return $value;
        }

        return null;
    }
}

