<?php

namespace ShoppingList\Controller;

use Zend\Mvc\Controller\AbstractActionController, 
    Zend\View\Model\ViewModel,
    ShoppingList\Model\ShoppingList,
    ShoppingList\Form\ShoppingListForm,
    Zend\Db\Sql\Select,
    Zend\Db\Sql\Where,
    Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\Iterator as paginatorIterator;

class ShoppingListController extends AbstractActionController
{
    
    protected $shoppinglistTable;
    protected $categoryTable;


    public function indexAction() {
        $select = new Select();
        $where = new Where();
        $order_by = $this->params()->fromRoute('order_by') ?
                $this->params()->fromRoute('order_by') : 'date';
        $order = $this->params()->fromRoute('order') ?
                $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        
        
        $shoppinglist = $this->getShoppingListTable()->fetchAll($select->where($where)->order($order_by . ' ' . $order));
        $itemsPerPage = 10;

        $shoppinglist->current();

        $paginator = new Paginator(new paginatorIterator($shoppinglist));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(10);

        return new ViewModel(array(
            'order_by' => $order_by,
            'order' => $order,
            'page' => $page,
            'paginator' => $paginator,
            'issetPagination' => $this->params()->fromRoute('page') OR $this->params()->fromRoute('order_by'),
            'document_root' => $this->getRequest()->getServer("DOCUMENT_ROOT"),
        ));
    }
    
    public function dayAction() {
        $select = new Select();
        $where = new Where();
        $where->equalTo("date", date("Y-m-d"));
        $order_by = $this->params()->fromRoute('order_by') ?
                $this->params()->fromRoute('order_by') : 'date';
        $order = $this->params()->fromRoute('order') ?
                $this->params()->fromRoute('order') : Select::ORDER_ASCENDING;
        $page = $this->params()->fromRoute('page') ? (int) $this->params()->fromRoute('page') : 1;
        
        
        $shoppinglist = $this->getShoppingListTable()->fetchAll($select->where($where)->order($order_by . ' ' . $order));
        $itemsPerPage = 10;

        $shoppinglist->current();

        $paginator = new Paginator(new paginatorIterator($shoppinglist));
        $paginator->setCurrentPageNumber($page)
                ->setItemCountPerPage($itemsPerPage)
                ->setPageRange(10);

        return new ViewModel(array(
            'order_by' => $order_by,
            'order' => $order,
            'page' => $page,
            'paginator' => $paginator,
            'issetPagination' => $this->params()->fromRoute('page') OR $this->params()->fromRoute('order_by'),
            'document_root' => $this->getRequest()->getServer("DOCUMENT_ROOT"),
        ));
    }
    
    public function addAction() {
    	try {
	        $t_categories = $this->getCategoryTable()->getCategoriesNameToValue();
	        $form = new ShoppingListForm($t_categories, true);
	        $form->get('submit')->setAttribute('label', 'Add');
	        $request = $this->getRequest();
	        // Check request Mode
	        if ($request->isPost()) {
	            $ShoppingList = new ShoppingList();
	            //Loading form
	            $t_post = $request->getPost();
	            $t_post["b_bought"] = 0;
	            $form->setData($t_post);
	            //Control fields
	            if ($form->isValid()) {
	                $ShoppingList->exchangeArray($form->getData());
	                $id_shopping_list = $this->getShoppingListTable()->saveShoppingList($ShoppingList);
	
	                //Redirection vers la liste des ShoppingLists
	                return $this->redirect()->toRoute('shoppinglist');
	            }
	        }
        } catch(\Exception $e) {
        	return $this->redirect()->toRoute('shoppinglist');
        }
        return array('form' => $form);
    }
    
    /**
     * Edit action, use to edit a row in the shopping list
     * @return type 
     */
    public function editAction() {
    	try {
	        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id');
	        //Redirect to add form if id not exists
	        if (!$id) {
	            return $this->redirect()->toRoute('shoppinglist', array('action'=>'add'));
	        }
	        $t_categories = $this->getCategoryTable()->getCategoriesNameToValue();
	        //Loadind shopping list
	        $ShoppingList = $this->getShoppingListTable()->getShoppingList($id);
	        $form = new ShoppingListForm($t_categories);
	        //Loading Form
	        $form->bind($ShoppingList);
	        $form->get('submit')->setAttribute('label', 'Edit');
	        $request = $this->getRequest();
	        //Check post request
	        if ($request->isPost()) {
	            $form->setData($request->getPost());
	            //Fields control
	            if ($form->isValid()) {
	                $this->getShoppingListTable()->saveShoppingList($ShoppingList);
	                
	                return $this->redirect()->toRoute('shoppinglist');
	            }
	        }
    	} catch(\Exception $e) {
    		return $this->redirect()->toRoute('shoppinglist', array('action'=>'add'));
    	}
        return array(
            'id_shopping_list' => $id,
            'form' => $form,
        );
    }
    
    /**
     * Delete list of products by ajax
     * @param array list of products 
     * @return type 
     */
    public function ajaxDeleteAction() {        
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $list = $request->getPost('list');
            if (is_array($list) && count($list) > 0) {
                foreach($list as $id_shopping_list) {
                    $this->getShoppingListTable()->deleteShoppingList($id_shopping_list);
                }
                $t_return["result"] = "OK";
            } else {
                $t_return["result"] = "KO_LIST";
            }
            $response->setContent(\Zend\Json\Json::encode($t_return));
        }
        return $response;
    }
    
    /**
     * Set as bought list of products by ajax
     * @param array list of products 
     * @return type 
     */
    public function ajaxBoughtAction() {        
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $list = $request->getPost('list');
            if (is_array($list) && count($list) > 0) {
                foreach($list as $id_shopping_list) {
                    $this->getShoppingListTable()->saveAsBought($id_shopping_list);
                }
                $t_return["result"] = "OK";
            } else {
                $t_return["result"] = "KO_LIST";
            }
            $response->setContent(\Zend\Json\Json::encode($t_return));
        }
        return $response;
    }
    
    public function getShoppingListTable() {
        if (!$this->shoppinglistTable) {
            $sm = $this->getServiceLocator();
            $this->shoppinglistTable = $sm->get('shoppinglist-table');
        }
        return $this->shoppinglistTable;
    }
    
    public function getCategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('category-table');
        }
        return $this->categoryTable;
    }
}
