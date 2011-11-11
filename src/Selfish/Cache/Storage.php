<?php
/**
 * Storage.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\Cache;

/**
 * Storage
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
interface Storage
{
    public function has($name);
    public function get($name);
    public function set($name, $value, $expire=0);
    public function delete($name);
}

