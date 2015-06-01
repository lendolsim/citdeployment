<?php
namespace CitDeployment;

use CitDeployment\Model\Deployment;
use CitDeployment\Model\DeploymentTable;
use CitDeployment\Model\DeploymentProduct;
use CitDeployment\Model\DeploymentProductTable;
use CitDeployment\Model\DeploymentProductOption;
use CitDeployment\Model\DeploymentProductOptionTable;
use CitDeployment\Model\DeploymentWithdrawal;
use CitDeployment\Model\DeploymentWithdrawalTable;

use CitCore\Model\GenericTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Authentication\Storage;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\EventManager\EventInterface;
use Zend\Validator\AbstractValidator;

class Module //implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'CitDeployment\Model\DeploymentTable' =>  function($sm) {
                	$tableGateway = $sm->get('DeploymentTableGateway');
                	$table = new DeploymentTable($tableGateway);
                	return $table;
                },
                'DeploymentTableGateway' => function ($sm) {
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	$resultSetPrototype = new ResultSet();
                	$resultSetPrototype->setArrayObjectPrototype(new Deployment());
                	return new TableGateway('deployment', $dbAdapter, null, $resultSetPrototype);
                },
                'CitDeployment\Model\DeploymentProductTable' =>  function($sm) {
                	$tableGateway = $sm->get('DeploymentProductTableGateway');
                	$table = new DeploymentProductTable($tableGateway);
                	return $table;
                },
                'DeploymentProductTableGateway' => function ($sm) {
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	$resultSetPrototype = new ResultSet();
                	$resultSetPrototype->setArrayObjectPrototype(new DeploymentProduct());
                	return new TableGateway('deployment_product', $dbAdapter, null, $resultSetPrototype);
                },
                'CitDeployment\Model\DeploymentWithdrawalTable' =>  function($sm) {
                	$tableGateway = $sm->get('DeploymentWithdrawalTableGateway');
                	$table = new DeploymentWithdrawalTable($tableGateway);
                	return $table;
                },
                'DeploymentWithdrawalTableGateway' => function ($sm) {
                	$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                	$resultSetPrototype = new ResultSet();
                	$resultSetPrototype->setArrayObjectPrototype(new DeploymentWithdrawal());
                	return new TableGateway('deployment_withdrawal', $dbAdapter, null, $resultSetPrototype);
                },
               
            ),
        );
    }
    
    public function onBootstrap(EventInterface $e)
    {
    	$serviceManager = $e->getApplication()->getServiceManager();
    
    	// Set the translator for default validation messages
    	$translator = $serviceManager->get('translator');
    	AbstractValidator::setDefaultTranslator($translator);
    }
}
