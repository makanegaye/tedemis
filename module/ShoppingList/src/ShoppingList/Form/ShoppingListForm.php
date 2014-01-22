<?php

namespace ShoppingList\Form;

use Zend\Form\Form,
    Zend\Form\Element,
    Zend\Form\FieldsetInterface;

class ShoppingListForm extends Form {
    public function __construct($t_categories, $add = false) {
        parent::__construct();
        // Build categories list
        $selectCategories = new Element\Select('id_category');
        $selectCategories->setLabel('Choose a category');
        $selectCategories->setValueOptions($t_categories);
        
        // Build bought list
        $selectBought = new Element\Select('b_bought');
        $selectBought->setLabel('Bought');
        $selectBought->setValueOptions(array('0'=>'No', '1'=>'Yes'));
        $selectBought->setValue(0);
        $selectBought->setEmptyOption("");
        
        // Form name
        $this->setName('shoppinglist');
        //Send POST
        $this->setAttribute('method', 'post');
        // Fields
        $this->add(array('name' => 'id_shopping_list', 'attributes' => array('type' => 'hidden',),));
        $this->add(array('name' => 'product_name', 'attributes' => array('required' => 'required'), 'type' => 'Zend\Form\Element\Text', 'options' => array('label' => "Product's name",),));
        $this->add(array('name' => 'date', 'attributes' => array('required' => 'required'), 'type' => 'Zend\Form\Element\Date', 'options' => array('label' => 'Date',),));
        $this->add(array('name' => 'quantity', 'type' => 'Zend\Form\Element\Number', 'attributes' => array('min' => '1', 'step' => '1',),'options' => array('label' => 'Quantity',),));        
        $this->add($selectCategories);
        $this->add($selectBought);

        // Actions
        $this->add(array('name' => 'submit', 'attributes' => array('type' => 'submit', 'value' => 'OK', 'id' => 'submitbutton',),));
    }
}
