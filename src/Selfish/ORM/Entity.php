<?php
/**
 * Entity.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\ORM;

/**
 * Entity
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
interface Entity
{
    public function getTable();
    public function getColumns();
}

