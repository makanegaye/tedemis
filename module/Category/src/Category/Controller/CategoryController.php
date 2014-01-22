<?php

namespace Category\Controller;

use Zend\Mvc\Controller\AbstractActionController, 
    Zend\View\Model\ViewModel,
    Category\Model\Category,
    Category\Form\CategoryForm;

class CategoryController extends AbstractActionController
{
    
    protected $categoryTable;
    
    public function indexAction() {
         return new ViewModel(array(
            'categories' => $this->getCategoryTable()->fetchAll(),
        ));
    }
    
    /**
     * Adding a category
     * @return type 
     */
    public function addAction() {
    	try {
	        $form = new CategoryForm();
	        $form->get('submit')->setAttribute('label', 'Add');
	        $request = $this->getRequest();
	        //Check the request
	        if ($request->isPost()) {
	            $category = new Category();
	            //Loading form
	            $form->setData($request->getPost());
	
	            //Control fields
	            if ($form->isValid()) {
	                $category->exchangeArray($form->getData());
	                $this->getCategoryTable()->saveCategory($category);
	                //Redirection to list of categories
	                return $this->redirect()->toRoute('category');
	            }
	        }
        } catch(\Exception $e) {
        	return $this->redirect()->toRoute('shoppinglist', array('action'=>'add'));
        }
        return array('form' => $form);
    }
    
    /**
     * Use to display edit form
     * @return type 
     */
    public function editAction() {
    	try {
	        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
	        //Redirect to add form if id not exists
	        if (!$id) {
	            return $this->redirect()->toRoute('category', array('action'=>'add'));
	        }
	        
	        //Set infos
	        $category = $this->getCategoryTable()->getCategory($id);
	        $form = new CategoryForm();
	        
	        //Loading form
	        $form->bind($category);
	        $form->get('submit')->setAttribute('label', 'Edit');
	        $request = $this->getRequest();
	        // Check the request
	        if ($request->isPost()) {
	            $form->setData($request->getPost());
	            // Checking fields
	            if ($form->isValid()) {
	                $category->update_dt = date("Y-m-d H:i:s");
	                $this->getCategoryTable()->saveCategory($category);
	                // Redirect to the list of categories
	                return $this->redirect()->toRoute('category');
	            }
	        }
        } catch(\Exception $e) {
        	return $this->redirect()->toRoute('shoppinglist', array('action'=>'add'));
        }
        return array(
            'id_category' => $id,
            'form' => $form,
        );
    }
    
    /**
     * Use to delete a category
     * @return type 
     */
    public function deleteAction() {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
        if (!$id) {
            return $this->redirect()->toRoute('category');
        }
 
        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'Non');
            if ($del == 'Oui') {
                $id = (int)$request->getPost('id');
                $this->getCategoryTable()->deleteCategory($id);
            }
 
            //Redirect to categories
            return $this->redirect()->toRoute('category');
        }
        return array(
            'id_category' => $id,
            'category' => $this->getCategoryTable()->getCategory($id)
        );
    }
    
    /**
     * Get access to category DB table
     */
    public function getCategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('category-table');
        }
        return $this->categoryTable;
    }
}
