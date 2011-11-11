<?php
/**
 * Loader.php
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 * @package Selfish
 * @license The BSD License
 * @version $Id$
 */

namespace Selfish;

if (!class_exists('\\SplClassLoader', false)) {
    if ($path = stream_resolve_include_path('SplClassLoader.php')) {
        require_once $path;
    } else {
        throw new \Exception('SplClassLoader required');
    }
}

/**
 * Loader
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
class Loader
{
    /**
     * register autoloader
     *
     * @return void
     */
    static public function register()
    {
        $loader = new \SplClassLoader('Selfish', __DIR__ . '/..');
        $loader->register();
    }
}

