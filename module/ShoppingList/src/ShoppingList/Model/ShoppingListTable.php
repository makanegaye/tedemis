<?php

namespace ShoppingList\Model;
use Zend\Db\TableGateway\AbstractTableGateway,
    Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet,
    Zend\Db\Sql\Select,
    Zend\Db\Sql\Where;

class ShoppingListTable extends AbstractTableGateway
{
    protected $table = 'tedemis_shopping_list';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new ShoppingList());
        $this->initialize();
    }
    
    /**
     * Get all rows
     * 
     * @param Select $select
     * @return type 
     */
    public function fetchAll(Select $select = null) {
        if (null === $select)
            $select = new Select();
        $select->from($this->table);
        $resultSet = $this->selectWith($select);
        $resultSet->buffer();
        return $resultSet;
    }
    
    public function getAllInArray()
    {
        $resultSet = $this->select();
        $t_ShoppingLists = array();
        foreach ($resultSet as $ShoppingList) {
            $t_ShoppingLists[] = $ShoppingList;
        }
        return $t_ShoppingLists;        
    }
    
    /**
     * Get a row by id
     * 
     * @param type $id
     * @return type
     * @throws \Exception 
     */
    public function getShoppingList($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('id_shopping_list' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    /**
     * Save a row into the shopping list
     * 
     * @param ShoppingList $ShoppingList
     * @return type
     * @throws \Exception 
     */
    public function saveShoppingList(ShoppingList $ShoppingList)
    {
        $data = array(
            'id_category' => $ShoppingList->id_category,
            'date' => $ShoppingList->date,
            'quantity' => $ShoppingList->quantity,
            'product_name' => $ShoppingList->product_name,
            'b_bought' => $ShoppingList->b_bought,
        );
        $id = (int)$ShoppingList->id_shopping_list;
        
        if ($id == 0) {
            $this->insert($data);
            return $this->adapter->getDriver()->getLastGeneratedValue();
        } else {
            if ($this->getShoppingList($id)) {
                $this->update($data, array('id_shopping_list' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    /**
     * Add a new row
     * 
     * @param type $date
     * @param type $product_name
     * @param type $quantity
     * @param type $id_category 
     */
    public function addShoppingList($date, $product_name, $quantity, $id_category)
    {
        $data = array(
            'id_category' => $id_category,
            'date' => $date,
            'quantity' => $quantity,
            'product_name' => $product_name,
        );
        $this->insert($data);
    }
    
    /**
     * Update a row
     * 
     * @param type $id
     * @param type $date
     * @param type $quantity
     * @param type $product_name
     * @param type $b_bought
     * @param type $id_category 
     */
    public function updateShoppingList($id, $date, $quantity, $product_name, $b_bought, $id_category)
    {
        $data = array(
            'id_category' => $id_category,
            'date' => $date,
            'quantity' => $quantity,
            'product_name' => $product_name,
            'b_bought' => $b_bought,
        );
        $this->update($data, array('id_shopping_list' => $id));
    }
    
    /**
     * Change status
     * 
     * @param type $id 
     */
    public function saveAsBought($id) {
        $data = array(
            'b_bought' => 1,
        );
        $this->update($data, array('id_shopping_list' => $id));
    }
    
    /**
     * Remove a row
     * 
     * @param type $id 
     */
    public function deleteShoppingList($id)
    {
        $this->delete(array('id_shopping_list' => $id));
    }
    
}