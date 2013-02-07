<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'GDS\Controller\Entrepot' 	=> 'GDS\Controller\EntrepotController',
            'GDS\Controller\Produit' 	=> 'GDS\Controller\ProduitController',
            'GDS\Controller\Stock' 		=> 'GDS\Controller\StockController',
        ),
    ),
	// The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            'entrepot' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/entrepot[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'GDS\Controller\Entrepot',
                        'action'     => 'index',
                    ),
                ),
            ),
			'produit' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/produit[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'GDS\Controller\Produit',
                        'action'     => 'index',
                    ),
                ),
            ),
			'stock' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/stock[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'GDS\Controller\Stock',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'entrepot' => __DIR__ . '/../view',
            'produit' => __DIR__ . '/../view',
            'stock' => __DIR__ . '/../view',
        ),
    ),
);