<?php

return array(
    'controllers' => array(
        'invokables' => array(
        	'CitDeployment\Controller\Site' => 'CitDeployment\Controller\SiteController',
        	'CitDeployment\Controller\SiteContact' => 'CitDeployment\Controller\SiteContactController',
        	'CitDeployment\Controller\Stock' => 'CitDeployment\Controller\StockController',
        	'CitDeployment\Controller\Deployment' => 'CitDeployment\Controller\DeploymentController',
        	'CitDeployment\Controller\DeploymentProduct' => 'CitDeployment\Controller\DeploymentProductController',
        	'CitDeployment\Controller\DeploymentWithdrawal' => 'CitDeployment\Controller\DeploymentWithdrawalController',
        ),
    ),
 
    'router' => array(
        'routes' => array(
            'index' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'CitOrder\Controller\Site',
                        'action'     => 'index',
                    ),
                ),
          		'may_terminate' => true,
	       		'child_routes' => array(
	                'index' => array(
	                    'type' => 'segment',
	                    'options' => array(
	                        'route' => '/index',
	                    	'defaults' => array(
	                    		'action' => 'index',
	                        ),
	                    ),
	                ),
	       		),
            ),
        	'deployment' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/deployment',
        						'defaults' => array(
        								'controller' => 'CitDeployment\Controller\Deployment',
        								'action'     => 'index',
        						),
        				),
        				'may_terminate' => true,
        				'child_routes' => array(
        						'index' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index',
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'add' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add',
        										'defaults' => array(
        												'action' => 'add',
        										),
        								),
        						),
        						'detail' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/detail[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'detail',
        										),
        								),
        						),
        						'transfer' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/transfer[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'transfer',
        										),
        								),
        						),
        						'validate' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/validate[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'validate',
        										),
        								),
        						),
        						'manage' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/manage[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'manage',
        										),
        								),
        						),
        				),
        		),
        	'deploymentProduct' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/deployment-product',
        						'defaults' => array(
        								'controller' => 'CitDeployment\Controller\DeploymentProduct',
        								'action'     => 'index',
        						),
        				),
        				'may_terminate' => true,
        				'child_routes' => array(
        						'index' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'detail' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/detail[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'detail',
        										),
        								),
        						),
        						'update' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/update[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'update',
        										),
        								),
        						),
        				),
        		),
        	'deploymentWithdrawal' => array(
        				'type'    => 'segment',
        				'options' => array(
        						'route'    => '/deployment-withdrawal',
        						'defaults' => array(
        								'controller' => 'CitDeployment\Controller\DeploymentWithdrawal',
        								'action'     => 'index',
        						),
        				),
        				'may_terminate' => true,
        				'child_routes' => array(
        						'index' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/index[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'index',
        										),
        								),
        						),
        						'add' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/add[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'add',
        										),
        								),
        						),
        						'detail' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/detail[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'detail',
        										),
        								),
        						),
        						'update' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/update[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'update',
        										),
        								),
        						),
        						'delete' => array(
        								'type' => 'segment',
        								'options' => array(
        										'route' => '/delete[/:id]',
        										'constraints' => array(
        												'id'     => '[0-9]*',
        										),
        										'defaults' => array(
        												'action' => 'delete',
        										),
        								),
        						),
        				),
        		),

        	
        ),
    ),
		
    'view_manager' => array(
    	'strategies' => array(
    			'ViewJsonStrategy',
    	),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',       // On défini notre doctype
        'not_found_template'       => 'error/404',   // On indique la page 404
        'exception_template'       => 'error/index', // On indique la page en cas d'exception
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
        ),
        'template_path_stack' => array(
            'cit-deployment' => __DIR__ . '/../view',
        ),
    ),
/*	'service_manager' => array(
		'factories' => array(
				'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
		),
	),*/
	'translator' => array(
		'locale' => 'fr_FR',
		'translation_file_patterns' => array(
			array(
				'type'     => 'phparray',
				'base_dir' => __DIR__ . '/../language',
				'pattern'  => '%s.php',
			),
	       	array(
	            'type' => 'phpArray',
	            'base_dir' => './vendor/zendframework/zendframework/resources/languages/',
	            'pattern'  => 'fr/Zend_Validate.php',
	        ),
 		),
	),
);
