<?php
/**
 * iCalendar.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\iCalendar;

/**
 * iCalendar
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
class iCalendar
{
    const CRLF = "\r\n";
    const MIME_TYPE = 'text/calendar';

    protected $calscale = 'GREGORIAN';
    protected $prodid = '-//github.com/riaf/Selfish/iCalendar';
    protected $version = '2.0';
    protected $method;

    protected $components = array();

    public function getCalscale()
    {
        return $this->calscale;
    }

    public function setCalscale($calscale)
    {
        $this->calscale = $calscale;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function addComponent(Component\ComponentInterface $component)
    {
        $this->components[] = $component;
    }

    public function render()
    {
        $root = new Component\Element('VCALENDAR');
        foreach (array('calscale', 'prodid', 'version', 'method') as $attr_names) {
            if ($this->{$attr_names}) {
                $root->setAttribute($attr_names, $this->{$attr_names});
            }
        }

        foreach ($this->components as $component) {
            $root->addChild($component->getComponentElement());
        }

        return $root->__toString() . static::CRLF;
    }
}
