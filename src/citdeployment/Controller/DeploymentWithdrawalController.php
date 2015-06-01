<?php
namespace CitDeployment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CitCore\Controller\Functions;
use CitDeployment\Model\Deployment;
use CitDeployment\Model\DeploymentWithdrawal;
use CitDeployment\Form\DeploymentWithdrawalIndexForm;
use Zend\Session\Container;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\Memory\Value;

class DeploymentWithdrawalController extends AbstractActionController
{
	public $routes;
	protected $instanceTable;
	protected $userTable;
	protected $userRoleTable;
	protected $userRoleLinkerTable;
	protected $vcardTable;

	protected $deploymentTable;
	protected $orderWithdrawalTable;
	protected $deploymentWithdrawalTable;
	protected $siteTable;
	protected $siteStockTable;
	protected $stockTable;
	protected $orderTable;
//	protected $deploymentWithdrawal;
   	public function indexAction()
    {
    	
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('deployment');
    	}
    	// Retrieve the current user
    	$current_user = Functions::getUser($this);
    	
    	// Retrieve the current role
    	$current_role = Functions::getRole($this);
    	
    	// Retrieve the allowed routes
    	$allowedRoutes = Functions::getAllowedRoutes($this);
    	
    	// Retrieve the user's instance
    	$instance_id = Functions::getInstanceId($this);
    	 
    	// Retrieve the deployment
    	$deployment = $this->getDeploymentTable()->get($id);
    	
    	// Retrieve the order
    	//$order = $this->getOrderTable()->get($deployment->order_id);
    	
    	// Retrieve the responsible
    	$responsible = $this->getUserTable()->get($deployment->responsible_id);
    	$contact = $this->getVcardTable()->get($responsible->contact_id);
    	$responsible->n_fn = $contact->n_fn;
    	
    	// Prepare the SQL request
    	$major = $this->params()->fromQuery('major', NULL);
    	if (!$major) $major = 'caption';
    	$dir = $this->params()->fromQuery('dir', NULL);
    	if (!$dir) $dir = 'ASC';
    	$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $select = $this->getDeploymentWithdrawalTable()->getSelect()
    		->join('deployment', 'deployment_withdrawal.deployment_id = deployment.id', array('site_id', 'identifier'), 'left')
    		->join('order_withdrawal', 'deployment_withdrawal.order_withdrawal_id = order_withdrawal.id', array(), 'left')
    		->join('order_stock', 'order_withdrawal.stock_id = order_stock.id', array('caption', 'brand', 'model', 'identifier', 'serial_number'), 'left')
        	->where(array('deployment_id' => $id))
    		->order(array($major.' '.$dir, 'caption', 'id'));
    	   $cursor = $this->getDeploymentWithdrawalTable()->selectWith($select);
    	
    	$deploymenWithdrawals = array();
    	foreach ($cursor as $deploymenWithdrawal) $deploymenWithdrawals[$deploymenWithdrawal->id] = $deploymenWithdrawal;
    	
    	$form = new DeploymentWithdrawalIndexForm();
    	$form->addElements($deploymenWithdrawals);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setData($request->getPost());
    		 
    		// Update the selected rows
    		foreach ($deploymenWithdrawals as $deploymentWithdrawal) {
    	
    			if ($form->get('product'.$deploymentWithdrawal->id)->getValue()) { // Row selected
    			
    				// withdrawal_date
    				$action = $request->getPost('update_withdrawal_date', 'Cancel');
    				if ($action == $this->getServiceLocator()->get('translator')->translate('Set withdrawal date')) {
    						
    				
    					
    					$deploymentWithdrawal->withdrawal_date = $form->get('withdrawal_date')->getValue();
    					$this->getDeploymentWithdrawalTable()->save($deploymentWithdrawal, $current_user->user_id, $instance_id);
    				}
    					
    				// provisional datee
    				$action = $request->getPost('update_provisional_date', 'Cancel');
    				if ($action == $this->getServiceLocator()->get('translator')->translate('Set provisional date')) {

    				    $deploymentWithdrawal->provisional_date = $form->get('provisional_date')->getValue();
    					$this->getDeploymentWithdrawalTable()->save($deploymentWithdrawal, $current_user->user_id, $instance_id);
    				
    				}
    				 
    				// actual_date
    				$action = $request->getPost('update_actual_date', 'Cancel');
    				if ($action == $this->getServiceLocator()->get('translator')->translate('Set actual delivery')) {
    				
    					$deploymentWithdrawal->actual_date = $form->get('actual_date')->getValue();
    					$this->getDeploymentWithdrawalTable()->save($deploymentWithdrawal, $current_user->user_id, $instance_id);
    				
    				}
    				
    				
    				// actual_date
    				
    				$action = $request->getPost('update_status', 'Cancel');
    				if ($action == $this->getServiceLocator()->get('translator')->translate('Set status')) {
    				
    					$deploymentWithdrawal->status = $form->get('status')->getValue();
    					$this->getDeploymentWithdrawalTable()->save($deploymentWithdrawal, $current_user->user_id, $instance_id);
    				
    				}
    				
			 
    			}
    		}
    		// Redirect to the index
    		return $this->redirect()->toRoute('deploymentWithdrawal/index', array('id' => $id));
  	}
    	return array(
    			'current_user' => $current_user,
    			'current_role' => $current_role,
    			'allowedRoutes' => $allowedRoutes,
    			'major' => $major,
    			'dir' => $dir,
    			
    			'responsible' => $responsible,
    			'deployment' => $deployment,
    			'deploymenWithdrawals' => $deploymenWithdrawals,
    			'form' => $form
    	);
  	
    }


    
    protected function setHeader($deploymentWithdrawal)
    {
    	// Retrieve the deployment
    	$deployment = $this->getDeploymentTable()->get($deploymentWithdrawal->deployment_id);
    	$order = $this->getOrderTable()->get($deployment->order_id);
    	// Retrieve the technical responsible
    	$responsible = $this->getUserTable()->get($deployment->responsible_id);
    	$contact = $this->getVcardTable()->get($responsible->contact_id);
    	$responsible->n_fn = $contact->n_fn;
    
    	// Retrieve the technical responsible
    	if ($deployment->tech_responsible_id) {
    		$techResponsible = $this->getUserTable()->get($deployment->tech_responsible_id);
    		$contact = $this->getVcardTable()->get($techResponsible->contact_id);
    		$techResponsible->n_fn = $contact->n_fn;
    	}
    	else $techResponsible = null;
    
    	// Retrieve the deployment responsible
    	if ($deployment->deployment_responsible_id) {
    		$deploymentResponsible = $this->getUserTable()->get($deployment->deployment_responsible_id);
    		$contact = $this->getVcardTable()->get($deploymentResponsible->contact_id);
    		$deploymentResponsible->n_fn = $contact->n_fn;
    	}
    	else $deploymentResponsible = null;
    
    	// Retrieve the order site
    	$site = $this->getSiteTable()->get($deployment->site_id);
    
    	// Retrieve the order product
    	$orderWithdrawal = $this->getOrderWithdrawalTable()->get($deploymentWithdrawal->order_withdrawal_id);
    	$orderStock = $this->getStockTable()->get($orderWithdrawal->stock_id);
    
    	// Prepare the form header
    	return array(
    			'site_caption' => array('label' => 'Site', 'value' => $site->caption),
    			'responsible_n_fn' => array('label' => 'Responsible', 'value' => $responsible->n_fn),
    			'tech_responsible_n_fn' => array('label' => 'Technical responsible', 'value' => ($techResponsible) ? $techResponsible->n_fn : null),
    			'deployment_responsible_n_fn' => array('label' => 'Deployment responsible', 'value' => ($deploymentResponsible) ? $deploymentResponsible->n_fn : null),    			
    			'status' => array('label' => 'Deployment status', 'value' => $deployment->status),
    			'identifier' => array('label' => 'Deployment identifier', 'value' => $deployment->identifier),
    			'caption' => array('label' => 'Deployment caption', 'value' => $deployment->caption),
    			'Date commande' => array('label' => 'Date commande', 'value' => $order->order_date),
    			
    			'product_caption' => array('label' => 'Caption', 'value' => $orderStock->caption),
    			'brand' => array('label' => 'Product brand', 'value' => $orderStock->brand),
    			'model' => array('label' => 'Product model', 'value' => $orderStock->model),
    			'identifier' => array('label' => 'Identifier', 'value' => $orderStock->identifier),
    			'serial_number' => array('label' => 'Serial number', 'value' => $orderStock->serial_number),
    			
    			'provisional_date' => array('label' => 'Provisional date', 'value' => $deploymentWithdrawal->provisional_date),
    			'actual_date' => array('label' => 'Actual date', 'value' => $deploymentWithdrawal->actual_date),
    			'withdrawal_date' => array('label' => 'Withdrawal date', 'value' => $deploymentWithdrawal->withdrawal_date),
    			'Withdrawal status' => array('label' => 'Withdrawal status', 'value' => $deploymentWithdrawal->status),
    			'comment' => array('label' => 'Comment', 'value' => $deploymentWithdrawal->comment),
    			
    	);
    }
    
    
    
    
    
    public function detailAction()
    {
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
    
    	// Retrieve the deployment product
    	$deploymentWithdrawal = $this->getdeploymentWithdrawalTable()->get($id);
    
    	return array(
    			'current_user' => $current_user,
    			'allowedRoutes' => $allowedRoutes,
    			'title' => 'Deployment Withdrawal',
    			'header' => $this->setHeader($deploymentWithdrawal),
    			'deploymentWithdrawal' => $deploymentWithdrawal,
    	);
    }
    
    
    public function getStockTable()
    {
    	if (!$this->stockTable) {
    		$sm = $this->getServiceLocator();
    		$this->stockTable = $sm->get('CitOrder\Model\StockTable');
    	}
    	return $this->stockTable;
    }
    
    public function getOrderWithdrawalTable()
    {
    	if (!$this->orderWithdrawalTable) {
    		$sm = $this->getServiceLocator();
    		$this->orderWithdrawalTable = $sm->get('CitOrder\Model\OrderWithdrawalTable');
    	}
    	return $this->orderWithdrawalTable;
    }
    
    public function getDeploymentTable()
    {
    	if (!$this->deploymentTable) {
    		$sm = $this->getServiceLocator();
    		$this->deploymentTable = $sm->get('CitDeployment\Model\DeploymentTable');
    	}
    	return $this->deploymentTable;
    }

    public function getDeploymentWithdrawalTable()
    {
    	if (!$this->deploymentWithdrawalTable) {
    		$sm = $this->getServiceLocator();
    		$this->deploymentWithdrawalTable = $sm->get('CitDeployment\Model\DeploymentWithdrawalTable');
    	}
    	return $this->deploymentWithdrawalTable;
    }

    public function getSiteTable()
    {
    	if (!$this->siteTable) {
    		$sm = $this->getServiceLocator();
    		$this->siteTable = $sm->get('CitOrder\Model\SiteTable');
    	}
    	return $this->siteTable;
    }
    
    public function getSiteStockTable()
    {
    	if (!$this->siteStockTable) {
    		$sm = $this->getServiceLocator();
    		$this->siteStockTable = $sm->get('CitOrder\Model\SiteStockTable');
    	}
    	return $this->siteStockTable;
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
    
    public function getOrderTable()
    {
    	if (!$this->orderTable) {
    		$sm = $this->getServiceLocator();
    		$this->orderTable = $sm->get('CitOrder\Model\OrderTable');
    	}
    	return $this->orderTable;
    }
    
    
}
