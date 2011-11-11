<?php
/**
 * Apc.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\Cache;

/**
 * Apc
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
class Apc implements Storage
{
    /**
     * Checks if APC key exists
     *
     * @param   string  $id
     * @return  bool
     **/
    public function has($id)
    {
        return \apc_exists($id);
    }

    /**
     * Fetch a stored variable from the cache
     *
     * @param   string  $id
     * @return  string
     **/
    public function get($id)
    {
        $result = \apc_fetch($id, $success);

        return $success ? $result : null;
    }

    /**
     * Cache a variable in the data store
     *
     * @param   string  $id
     * @param   string  $value
     * @param   int     $expire
     * @return  string  $value
     **/
    public function set($id, $value, $expire=0)
    {
        \apc_store($id, $value, $expire);

        return $value;
    }

    /**
     * Removes a stored variable from the cache
     *
     * @param   string  $id
     * @return  void
     **/
    public function delete($id)
    {
        \apc_delete($id);
    }
}

