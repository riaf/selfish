<?php
/**
 * Timezone.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\iCalendar\Component;

/**
 * Timezone
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 * @todo    ちゃんと書く
 */
class Timezone implements ComponentInterface
{
    public function getComponentName()
    {
        return 'VTIMEZONE';
    }

    public function getComponentElement()
    {
        $element = new Element($this->getComponentName());
        $element->setAttribute('TZID', 'Japan');

        $standard = new Element('STANDARD');
        $standard->setAttribute('DTSTART', '19390101T000000');
        $standard->setAttribute('TZOFFSETFROM', '+0900');
        $standard->setAttribute('TZOFFSETTO', '+0900');
        $standard->setAttribute('TZNAME', 'JST');

        $element->addChild($standard);

        return $element;
    }
}
