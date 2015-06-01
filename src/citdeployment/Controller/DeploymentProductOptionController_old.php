<?php
namespace CitDeployment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CitCore\Controller\Functions;
use CitDeployment\Model\DeploymentProductOption;
use CitDeployment\Form\DeploymentProductOptionForm;
use Zend\Session\Container;
use Zend\Http\Client;
use Zend\Http\Request;

class DeploymentProductOptionController extends AbstractActionController
{
	public $routes;
	protected $instanceTable;
	protected $userTable;
	protected $userRoleTable;
	protected $userRoleLinkerTable;
	protected $vcardTable;

	protected $productTable;
	protected $productOptionTable;
	protected $productOptionMatrixTable;
	protected $deploymentTable;
	protected $deploymentProductTable;
	protected $deploymentProductOptionTable;
	protected $siteTable;
	
	public function indexAction()
	{
	
		
		// Check the presence of the id parameter (order)
		$id = (int) $this->params()->fromRoute('id', 0);
		if (!$id) {
			return $this->redirect()->toRoute('deployment');
		}
		// Retrieve the current user
		$current_user = Functions::getUser($this);
		
		// Retrieve the allowed routes
		$allowedRoutes = Functions::getAllowedRoutes($this);
		
		// Retrieve the user's instance
		$instance_id = Functions::getInstanceId($this);
		
    	$select = $this->getDeploymentProductOptionTable()->getSelect()
    		->join('md_product_option', 'deployment_product_option.product_option_id = md_product_option.id', array('caption'), 'left')
    		->where(array('deployment_product_id' => $id));
    		
    	$cursor = $this->getDeploymentProductTable()->selectWith($select);
    	
    	$orderProductOptions = array();
    	foreach ($cursor as $orderProductOption) {
    	
    		$orderProductOptions[$orderProductOption->id] = $orderProductOption;
    	}
    	
    	
    	// Return the link list
    	return new ViewModel(array(
    		'current_user' => $current_user,
    		'allowedRoutes' => $allowedRoutes,
    		'orderProductOptions' => $orderProductOptions,
    		'id' => $id
    	));
	}

	
	
	
	public function getDeploymentTable()
	{
		if (!$this->deploymentTable) {
			$sm = $this->getServiceLocator();
			$this->deploymentTable = $sm->get('CitDeployment\Model\DeploymentTable');
		}
		return $this->deploymentTable;
	}
	
	public function getDeploymentProductTable()
	{
		if (!$this->deploymentProductTable) {
			$sm = $this->getServiceLocator();
			$this->deploymentProductTable = $sm->get('CitDeployment\Model\DeploymentProductTable');
		}
		return $this->deploymentProductTable;
	}
	
	public function getProductTable()
	{
		if (!$this->productTable) {
			$sm = $this->getServiceLocator();
			$this->productTable = $sm->get('CitMasterData\Model\ProductTable');
		}
		return $this->productTable;
	}
	
    public function getProductOptionTable()
    {
    	if (!$this->productOptionTable) {
    		$sm = $this->getServiceLocator();
    		$this->productOptionTable = $sm->get('CitMasterData\Model\ProductOptionTable');
    	}
    	return $this->productOptionTable;
    }

    public function getOrderTable()
    {
    	if (!$this->orderTable) {
    		$sm = $this->getServiceLocator();
    		$this->orderTable = $sm->get('CitOrder\Model\OrderTable');
    	}
    	return $this->orderTable;
    }
    
    public function getOrderProductTable()
    {
    	if (!$this->orderProductTable) {
    		$sm = $this->getServiceLocator();
    		$this->orderProductTable = $sm->get('CitOrder\Model\OrderProductTable');
    	}
    	return $this->orderProductTable;
    }
    
    public function getDeploymentProductOptionTable()
    {
    	if (!$this->deploymentProductOptionTable) {
    		$sm = $this->getServiceLocator();
    		$this->deploymentProductOptionTable = $sm->get('CitDeployment\Model\DeploymentProductOptionTable');
    	}
    	return $this->deploymentProductOptionTable;
    }

    public function getProductOptionMatrixTable()
    {
    	if (!$this->productOptionMatrixTable) {
    		$sm = $this->getServiceLocator();
    		$this->productOptionMatrixTable = $sm->get('CitMasterData\Model\ProductOptionMatrixTable');
    	}
    	return $this->productOptionMatrixTable;
    }
    
    public function getSiteTable()
    {
    	if (!$this->siteTable) {
    		$sm = $this->getServiceLocator();
    		$this->siteTable = $sm->get('CitCommande\Model\SiteTable');
    	}
    	return $this->siteTable;
    }

    public function getInstanceTable()
    {
    	if (!$this->instanceTable) {
    		$sm = $this->getServiceLocator();
    		$this->instanceTable = $sm->get('CitCore\Model\InstanceTable');
    	}
    	return $this->instanceTable;
    }
    
    public function getUserTable()
    {
    	if (!$this->userTable) {
    		$sm = $this->getServiceLocator();
    		$this->userTable = $sm->get('CitUser\Model\UserTable');
    	}
    	return $this->userTable;
    }
    
    public function getUserRoleTable()
    {
    	if (!$this->userRoleTable) {
    		$sm = $this->getServiceLocator();
    		$this->userRoleTable = $sm->get('CitUser\Model\UserRoleTable');
    	}
    	return $this->userRoleTable;
    }
    
    public function getUserRoleLinkerTable()
    {
    	if (!$this->userRoleLinkerTable) {
    		$sm = $this->getServiceLocator();
    		$this->userRoleLinkerTable = $sm->get('CitUser\Model\UserRoleLinkerTable');
    	}
    	return $this->userRoleLinkerTable;
    }
    
    public function getVcardTable()
    {
    	if (!$this->vcardTable) {
    		$sm = $this->getServiceLocator();
    		$this->vcardTable = $sm->get('CitContact\Model\VcardTable');
    	}
    	return $this->vcardTable;
    }
    
    public function getVcardPropertyTable()
    {
    	if (!$this->vcardPropertyTable) {
    		$sm = $this->getServiceLocator();
    		$this->vcardPropertyTable = $sm->get('CitContact\Model\VcardPropertyTable');
    	}
    	return $this->vcardPropertyTable;
    }
}
