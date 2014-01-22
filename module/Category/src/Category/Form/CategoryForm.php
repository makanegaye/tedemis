<?php

namespace Category\Form;
use Zend\Form\Form,
    Zend\Form\FieldsetInterface;

class CategoryForm extends Form {
    public function __construct() {
        parent::__construct();
        // Form's name
        $this->setName('category');
        // REQUEST MODE
        $this->setAttribute('method', 'post');
        //Définition des champs
        $this->add(array('name' => 'id_category', 'attributes' => array('type' => 'hidden', ), ));
        $this->add(array('name' => 'name', 'attributes' => array('type' => 'text', ), 'options' => array('label' => 'Nom', ), ));
        //Action
        $this->add(array('name' => 'submit', 'attributes' => array('type' => 'submit', 'value' => 'OK', 'id' => 'submitbutton', ), ));
  }
}
