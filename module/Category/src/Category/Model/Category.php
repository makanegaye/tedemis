<?php

namespace Category\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArraySerializableInterface;

class Category implements ArraySerializableInterface
{
    /**
     * Primary key, identifiant of a category
     * @var Integer 
     */
    protected $id_category;
    /**
     * Name of the category
     * @var String 
     */
    protected $name;
    
    public function exchangeArray(array $data)
    {
        $this->id_category = (isset($data['id_category'])) ? $data['id_category'] : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
    }
 
    public function getArrayCopy()
    {
        $data = array(
            'id_category' => $this->id_category,
            'name' => $this->name,
        );
        return $data;
    }
 
    public function toArray()
    {
        return $this->getArrayCopy();
    }
    
    public function __set($name, $value) {
        $this->$name = $value;
    }

    public function __get($name) {
        return $this->$name;
    }
}
