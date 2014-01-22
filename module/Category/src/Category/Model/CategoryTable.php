<?php

namespace Category\Model;
use Zend\Db\TableGateway\AbstractTableGateway,
    Zend\Db\Adapter\Adapter,
    Zend\Db\ResultSet\ResultSet;

class CategoryTable extends AbstractTableGateway
{
    protected $table = 'tedemis_category';
    
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Category());
        $this->initialize();
    }
    
    /**
     * Get all rows
     * @return type 
     */
    public function fetchAll()
    {
        $resultSet = $this->select();
        return $resultSet;        
    }
    
    /**
     * Get category by id
     * @param type $id
     * @return type
     * @throws \Exception 
     */
    public function getCategory($id)
    {
        $id = (int) $id;
        $rowset = $this->select(array('id_category' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }
    
    /**
     * Save a category
     * @param Category $category
     * @throws \Exception 
     */
    public function saveCategory(Category $category)
    {
        // Build data
        $data = array(
            'id_category' => $category->id_category,
            'name' => $category->name,
        );
        $id = (int)$category->id_category;
        
        // Insert or update
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getCategory($id)) {
                $this->update($data, array('id_category' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }
    
    /**
     * Add new category
     * @param type $name 
     */
    public function addCategory($name)
    {
        $data = array(
            'name' => $name,
        );
        $this->insert($data);
    }
    
    /**
     * Update a category
     * @param type $id
     * @param type $name 
     */
    public function updateCategory($id, $name)
    {
        $data = array(
            'name' => $name,
        );
        $this->update($data, array('id_category' => $id));
    }
    
    /**
     * Remove category
     * @param type $id 
     */
    public function deleteCategory($id)
    {
        $this->delete(array('id_category' => $id));
    }
    
    /**
     * Get gategories name into array
     * @return type 
     */
    public function getCategoriesNameToValue() {
        $categories = $this->fetchAll();
        $t_categories = array();
        foreach($categories as $category) {
            $t_categories[$category->id_category] = $category->name;
        }
        return $t_categories;
    }
}