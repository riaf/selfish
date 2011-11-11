<?php

use Selfish\ORM;
use Selfish\ORM\Entity;

class ORMTest extends PHPUnit_Framework_TestCase
{
    public function testInsertHelper()
    {
        $date = new DateTime();

        // simple entity
        $entity = new SampleEntity;
        $entity->setName('Hoge');
        $entity->date = $date->format('Y-m-d H:i:s');

        list($sql, $parameters) = ORM::insertHelper($entity, array('id'));
        $this->assertEquals('INSERT INTO `sample_entity` (`name`, `date`) VALUES (:name, :date)', $sql);
        $this->assertEquals(2, count($parameters));
        $this->assertEquals(false, isset($parameters['id']));
        $this->assertEquals('Hoge', $parameters['name']);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $parameters['date']);

        // implements Entity
        $entity = new SampleEntity2;
        $entity->name = 'Fuga';
        $entity->date = $date->format('Y-m-d H:i:s');

        list($sql, $parameters) = ORM::insertHelper($entity, array('id'));
        $this->assertEquals('INSERT INTO `sample2` (`name`, `date`) VALUES (:name, :date)', $sql);
        $this->assertEquals(2, count($parameters));
        $this->assertEquals(false, isset($parameters['id']));
        $this->assertEquals('Fuga', $parameters['name']);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $parameters['date']);
    }

    public function testUpdateHelper()
    {
        $date = new DateTime();

        // simple entity
        $entity = new SampleEntity;
        $entity->setName('Hoge');
        $entity->date = $date->format('Y-m-d H:i:s');

        list($sql, $parameters) = ORM::updateHelper($entity, array('id'));
        $this->assertEquals('UPDATE `sample_entity` SET name = :name, date = :date WHERE id = :id', $sql);
        $this->assertEquals(3, count($parameters));
        $this->assertEquals(5, $parameters['id']);
        $this->assertEquals('Hoge', $parameters['name']);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $parameters['date']);

        // implements Entity
        $entity = new SampleEntity2;
        $entity->id = 3;
        $entity->name = 'Fuga';
        $entity->date = $date->format('Y-m-d H:i:s');

        list($sql, $parameters) = ORM::updateHelper($entity, array('id'));
        $this->assertEquals('UPDATE `sample2` SET name = :name, date = :date WHERE id = :id', $sql);
        $this->assertEquals(3, count($parameters));
        $this->assertEquals(3, $parameters['id']);
        $this->assertEquals('Fuga', $parameters['name']);
        $this->assertEquals($date->format('Y-m-d H:i:s'), $parameters['date']);
    }
}

class SampleEntity
{
    private $id = 5;
    protected $name;
    public $date;

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }
}

class SampleEntity2 implements Entity
{
    public $id;
    public $name;
    public $date;

    public function getTable()
    {
        return 'sample2';
    }

    public function getColumns()
    {
        return array(
            'id',
            'name',
            'date',
        );
    }
}

