<?php

namespace ShoppingList\Model;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArraySerializableInterface;

class ShoppingList implements ArraySerializableInterface
{
    /**
     * Primary key
     * @var Integer 
     */
    protected $id_shopping_list;
    
    /**
     * Category
     * @var Integer 
     */
    protected $id_category;
    
    /**
     * Date to buy
     * @var Date(YYYY-MM-DD) 
     */
    protected $date;
    
    /**
     * Product name
     * @var String 
     */
    protected $product_name;
    
    /**
     * The quantity to buy
     * @var Integer 
     */
    protected $quantity;
    
    /**
     * Status of the row in the shopping list
     * @var Boolean 
     */
    protected $b_bought;
    
    public function exchangeArray(array $data)
    {
        $this->id_shopping_list = (isset($data['id_shopping_list'])) ? $data['id_shopping_list'] : null;
        $this->id_category = (isset($data['id_category'])) ? $data['id_category'] : 0;
        $this->date = (isset($data['date'])) ? $data['date'] : "0000-00-00";
        $this->quantity = (isset($data['quantity'])) ? $data['quantity'] : 0;
        $this->product_name = (isset($data['product_name'])) ? $data['product_name'] : null;
        $this->b_bought = (isset($data['b_bought'])) ? $data['b_bought'] : false;
    }
 
    public function getArrayCopy()
    {
        $data = array(
            'id_shopping_list' => $this->id_shopping_list,
            'id_category' => $this->id_category,
            'date' => $this->date,
            'quantity' => $this->quantity,
            'product_name' => $this->product_name,
            'b_bought' => $this->b_bought,
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
    
    /**
     * Test if the product was bought
     * @return type 
     */
    public function isBought() {
        return $this->b_bought;
    }
}
