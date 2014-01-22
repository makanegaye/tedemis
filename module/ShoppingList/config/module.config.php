<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'shoppinglist/shoppinglist' => 'ShoppingList\Controller\ShoppingListController'
        ),
    ),
    'controller' => array(
        'classes' => array(
            'shoppinglist/shoppinglist' => 'ShoppingList\Controller\ShoppingListController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'shoppinglist' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/shoppinglist[/:action][/:id][/page/:page][/order_by/:order_by][/:order]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+',
                        'order_by' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'order' => 'ASC|DESC',
                    ),
                    'defaults' => array(
                        'controller' => 'shoppinglist/shoppinglist',
                        'action' => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'template_map' => array( 
            'paginator-slide-shoppinglist' => __DIR__ . '/../view/layout/slidePaginator.phtml',
        ),
    ),
);