<?php
/**
 * ORM.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish;

/**
 * ORM
 *
 * @todo    ちゃんと作る。insert した後に id 入れたりして欲しいもんね
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
class ORM
{
    /**
     * insertHelper
     *
     * @param   object  $object
     * @return  array   ($sql, $parameters)
     */
    static public function insertHelper($object, array $skip_columns = array())
    {
        list($table, $columns, $parameters) = static::entityInfo($object);

        $columns = array_diff($columns, $skip_columns);

        foreach ($skip_columns as $skip_column) {
            unset($parameters[$skip_column]);
        }

        $sql = sprintf(
            'INSERT INTO `%s` (`%s`) VALUES (%s)',
            $table,
            implode('`, `', $columns),
            implode(', ', array_map(function($name){
                return ':' . $name;
            }, $columns))
        );

        return array($sql, $parameters);
    }

    /**
     * insertHelper
     *
     * @param   object  $object
     * @return  array   ($sql, $parameters)
     */
    static public function updateHelper($object, $primary_keys)
    {
        list($table, $columns, $parameters) = static::entityInfo($object);

        if (!is_array($primary_keys)) {
            $primary_keys = array($primary_keys);
        }

        $eqjoin = function ($name) {
            return sprintf('%s = :%s', $name, $name);
        };

        $sql = sprintf(
            'UPDATE `%s` SET %s WHERE %s',
            $table,
            implode(', ', array_map($eqjoin, array_diff($columns, $primary_keys))),
            implode(' AND ', array_map($eqjoin, $primary_keys))
        );

        return array($sql, $parameters);
    }

    static protected function entityInfo($object)
    {
        if (!is_object($object)) {
            throw new \RuntimeException('$object is not Object');
        }

        if ($object instanceof ORM\Entity) {
            $columns = $object->getColumns();
            $table = $object->getTable();
        } else {
            $columns = static::fetchPropertyNames($object);
            $table = get_class($object);
            $table = preg_replace('/^([A-Z])/e', "strtolower('\$1')", $table);
            $table = preg_replace('/([A-Z])/e', "'_' . strtolower('\$1')", $table);
        }

        $parameters = static::fetchParameters($object, $columns);

        return array($table, $columns, $parameters);
    }

    static protected function fetchPropertyNames($object)
    {
        $reflect = new \ReflectionClass($object);
        $props = $reflect->getProperties();

        $names = array();
        foreach ($props as $prop) {
            $names[] = $prop->getName();
        }

        return $names;
    }

    static protected function fetchParameters($object, $columns)
    {
        $reflect = new \ReflectionClass($object);
        $parameters = array();

        foreach ($columns as $column) {
            $method = 'get' . implode('', array_map('ucfirst', explode('_', $column)));

            if (method_exists($object, $method)) {
                $parameters[$column] = $object->$method();
            } else {
                $parameters[$column] = $reflect->getProperty($column)->getValue($object);
            }
        }

        return $parameters;
    }
}

