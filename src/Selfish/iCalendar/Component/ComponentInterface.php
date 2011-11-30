<?php
/**
 * ComponentInterface.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\iCalendar\Component;

/**
 * ComponentInterface
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
interface ComponentInterface
{
    public function getComponentName();
    public function getComponentElement();
}
