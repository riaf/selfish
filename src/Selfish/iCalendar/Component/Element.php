<?php
/**
 * Element.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\iCalendar\Component;

use Selfish\iCalendar\iCalendar;

/**
 * Element
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 */
class Element
{
    protected $name;
    protected $attributes = array();
    protected $children = array();

    public function __construct($name = null)
    {
        $this->setName($name);
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function setAttribute($name, $value)
    {
        $this->attributes[strtoupper($name)] = $value;
    }

    public function getAttribute($name)
    {
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    public function removeAttribute($name)
    {
        if (isset($this->attributes[$name])) {
            unset($this->attributes[$name]);
        }
    }

    public function addChild(Element $element)
    {
        $this->children[] = $element;
    }

    public function __toString()
    {
        $lines = array();
        $lines[] = sprintf('BEGIN:%s', $this->name);

        foreach ($this->attributes as $name => $value) {
            $lines[] = sprintf('%s:%s', $name, $value);
        }

        foreach ($this->children as $child) {
            if ($child instanceof Element) {
                $lines[] = $child->__toString();
            }
        }

        $lines[] = sprintf('END:%s', $this->name);

        return $this->folding(implode(iCalendar::CRLF, $lines));
    }

    protected function folding($lines)
    {
        $lines = array_map(function($v){
            return str_replace(array("\r\n", "\n", "\r"), '\\n', $v);
        }, explode(iCalendar::CRLF, $lines));

        $new_lines = array();

        foreach ($lines as $line) {
            $count = 0;
            foreach (str_split($line, 75) as $spline) {
                if (empty($spline)) {
                    continue;
                }

                if ($count == 0) {
                    $new_lines[] = $spline;
                } else {
                    $new_lines[] = ' ' . $spline;
                }

                $count++;
            }
        }

        return implode(iCalendar::CRLF, $new_lines);
    }
}
