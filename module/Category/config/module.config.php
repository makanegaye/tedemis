<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'category/category' => 'Category\Controller\CategoryController'
        ),
    ),
    'controller' => array(
        'classes' => array(
            'category/category' => 'Category\Controller\CategoryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'category' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/category[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'category/category',
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
    ),
);