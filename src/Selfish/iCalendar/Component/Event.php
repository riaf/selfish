<?php
/**
 * Event.php
 *
 * @package     Selfish
 * @author      Keisuke SATO <sato@crocos.co.jp>
 * @version     $Id$
 * @license     The BSD License
 */

namespace Selfish\iCalendar\Component;

/**
 * Event
 *
 * @author  Keisuke SATO <sato@crocos.co.jp>
 * @todo    Timezone 対応
 * @todo    X-HOGE 系のパラメータ対応
 */
class Event implements ComponentInterface
{
    protected $organizer;
    protected $contact;
    protected $start_at;
    protected $end_at;
    protected $rule;
    protected $location;
    protected $transparent = 'OPAQUE';
    protected $sequence = 0;
    protected $uid;
    protected $created_at;
    protected $categories;
    protected $summary;
    protected $description;
    protected $priority = 0;
    protected $class = 'PUBLIC';
    protected $related_to;

    public function getOrganizer()
    {
        return $this->organizer;
    }

    public function setOrganizer($organizer)
    {
        $this->organizer = $organizer;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    public function getStartAt()
    {
        return ($this->start_at instanceof \DateTime)
            ? str_replace(' ', 'T', $this->start_at->setTimezone(
                new DateTimeZone('Asia/Tokyo'))->format('Ymd His')
            ) : $this->start_at;
    }

    public function setStartAt($start_at)
    {
        $this->start_at = ($start_at instanceof \DateTime)
            ? $start_at : new \DateTime($start_at);
    }

    public function getEndAt()
    {
        return ($this->end_at instanceof \DateTime)
            ? str_replace(' ', 'T', $this->end_at->setTimezone(
                new DateTimeZone('Asia/Tokyo'))->format('Ymd His')
            ) : $this->end_at;
    }

    public function setEndAt($end_at)
    {
        $this->end_at = ($end_at instanceof \DateTime)
            ? $end_at : new \DateTime($end_at);
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function setRule($rule)
    {
        $this->rule = $rule;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function setLocation($location)
    {
        $this->location = $location;
    }

    public function getTransparent()
    {
        return $this->transparent;
    }

    public function setTransparent($transparent)
    {
        $this->transparent = $transparent;
    }

    public function getSequence()
    {
        return $this->sequence;
    }

    public function setSequence($sequence)
    {
        $this->sequence = $sequence;
    }

    public function getUid()
    {
        return $this->uid;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function getCreatedAt()
    {
        return ($this->created_at instanceof \DateTime)
            ? str_replace(' ', 'T', $this->created_at->setTimezone(
                new DateTimeZone('Asia/Tokyo'))->format('Ymd His')
            ) : $this->created_at;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at = ($created_at instanceof \DateTime)
            ? $created_at : new \DateTime($created_at);
    }

    public function getSummary()
    {
        return $this->summary;
    }

    public function setSummary($summary)
    {
        $this->summary = $summary;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function setPriority($priority)
    {
        $this->priority = $priority;
    }

    public function getClass()
    {
        return $this->class;
    }

    public function setClass($class)
    {
        $this->class = $class;
    }

    public function getRelatedTo()
    {
        return $this->related_to;
    }

    public function setRelatedTo($related_to)
    {
        $this->related_to = $related_to;
    }

    public function getComponentName()
    {
        return 'VEVENT';
    }

    public function getComponentElement()
    {
        $element = new Element($this->getComponentName());

        $attr_names = array(
            'organizer'   => 'ORGANIZER',
            'contact'     => 'CONTACT',
            'start_at'    => 'DTSTART;TZID=Japan',
            'end_at'      => 'DTEND;TZID=Japan',
            'rule'        => 'RRULE',
            'location'    => 'LOCATION',
            'transparent' => 'TRANSP',
            'sequence'    => 'SEQUENCE',
            'uid'         => 'UID',
            'created_at'  => 'DTSTAMP',
            'categories'  => 'CATEGORIES',
            'summary'     => 'SUMMARY',
            'description' => 'DESCRIPTION',
            'priority'    => 'PRIORITY',
            'class'       => 'CLASS',
            'related_to'  => 'RELATED-TO',
        );

        foreach ($attr_names as $name => $key) {
            $method_name = sprintf('get%s', implode('', array_map('ucfirst', explode('_', $name))));

            if (!method_exists($this, $method_name) || $this->{$name} === null) {
                continue;
            }

            switch($name) {
                default:
                    $element->setAttribute($key, $this->{$method_name}());
            }
        }

        return $element;
    }
}
