<?php
namespace CitDeployment\Controller;

use Date;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CitCore\Controller\Functions;
use CitDeployment\Model\Deployment;
use CitDeployment\Model\DeploymentTable;
use CitDeployment\Form\DeploymentTransferForm;
use CitDeployment\Form\DeploymentManagementForm;
use CitDeployment\Form\DeploymentValidationForm;
use CitOrder\Form\SiteStockForm;
use Zend\Session\Container;
use Zend\Http\Client;
use Zend\Http\Request;
use Zend\db\sql\Where;
use CitDeployment\Model\CitDeployment\Model;

class DeploymentController extends AbstractActionController
{
	public $routes;
	protected $instanceTable;
	protected $userTable;
	protected $userRoleTable;
	protected $userRoleLinkerTable;
	protected $vcardTable;
	protected $vcardPropertyTable;
	
	protected $orderTable;
	protected $DeploymentTable;
   	protected $deploymentProductTable;
   	protected $deploymentProductOptionTable;
   	protected $orderProductTable;
   	protected $orderProductOptionTable;
   	protected $productTable;
   	protected $siteTable;
   	protected $siteContactTable;
   	
   	public function indexAction()
    {
    	// Retrieve the current user
    	$current_user = Functions::getUser($this);
    	 
    	// Retrieve the current role
    	$current_role = Functions::getRole($this);
    	 
    	// Retrieve the allowed routes
    	$allowedRoutes = Functions::getAllowedRoutes($this);

    	// Retrieve the user's instance
    	$instance_id = Functions::getInstanceId($this);
    	
    	// Prepare the SQL request
    	$currentPage = $this->params()->fromQuery('page', 1);
    	$major = $this->params()->fromQuery('major', NULL);
    	if (!$major) $major = 'id';
    	$dir = $this->params()->fromQuery('dir', NULL);
    	if (!$dir) $dir = 'ASC';
    	$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
    	$select = $this->getDeploymentTable()->getSelect()
    		->join('order_site', 'deployment.site_id = order_site.id', array('site_caption' => 'caption'), 'left')
    		->join('order', 'deployment.order_id = order.id', array('order_caption' => 'caption'), 'left')
    		->join('user', 'deployment.responsible_id = user.user_id', array('delegatee_id', 'delegation_begin', 'delegation_end'), 'left')
    		->order(array($major.' '.$dir, 'identifier'));
    		
       	$where = new Where();
    	/*$where
    		->equalTo('responsible_id', $current_user->user_id)
    		->or
    		->nest
    			->equalTo('delegatee_id', $current_user->user_id)
    			->and
    			->lessThanOrEqualTo('delegation_begin', date('Y-m-d'))
    			->and
    			->greaterThanOrEqualTo('delegation_end', date('Y-m-d'))
    		->unnest;    */
    	$select->where($where);
    	
    	$paginator = new \Zend\Paginator\Paginator(new \Zend\Paginator\Adapter\DbSelect($select, $adapter));
    	$paginator->setCurrentPageNumber($currentPage);
    	$paginator->setDefaultItemCountPerPage(30);

    	// Return the link list
    	return new ViewModel(array(
    		'current_user' => $current_user,
    		'allowedRoutes' => $allowedRoutes,
    		'title' => 'Deployment',
			'current_role' => $current_role,
    		'major' => $major,
    		'dir' => $dir,
    		'deployments' => $paginator
    	));
    }

    protected function setHeader($deployment)
    {
    	// Retrieve the responsible
    	$responsible = $this->getUserTable()->get($deployment->responsible_id);
    	$contact = $this->getVcardTable()->get($responsible->contact_id);
    	$responsible->n_fn = $contact->n_fn;
    
    	// Retrieve the approver
    	if ($deployment->approver_id) {
    		$approver = $this->getUserTable()->get($deployment->approver_id);
    		$contact = $this->getVcardTable()->get($approver->contact_id);
    		$approver->n_fn = $contact->n_fn;
    	}
    	else $approver = null;

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
    
    	// Retrieve the order products
    	$select = $this->getDeploymentProductTable()->getSelect()
    		->where(array('deployment_id' => $deployment->id));
    	$cursor = $this->getDeploymentProductTable()->selectWith($select);
    	$deploymentProducts = array();
    	foreach ($cursor as $deploymentProduct) $deploymentProducts[$deploymentProduct->id] = $deploymentProduct;
    
    	// Prepare the form header
    	return array(
    			'responsible_n_fn' => array('label' => 'Responsible', 'value' => $responsible->n_fn),
    			'approver_n_fn' => array('label' => 'Approver', 'value' => $approver->n_fn),
    			'tech_responsible_n_fn' => array('label' => 'Technical responsible', 'value' => ($techResponsible) ? $techResponsible->n_fn : null),
    			'deployment_responsible_n_fn' => array('label' => 'Deployment responsible', 'value' => ($deploymentResponsible) ? $deploymentResponsible->n_fn : null),
    			'site_caption' => array('label' => 'Site', 'value' => $site->caption),
    			'status' => array('label' => 'Status', 'value' => $deployment->status),
    			'identifier' => array('label' => 'Identifier', 'value' => $deployment->identifier),
    			'caption' => array('label' => 'Caption', 'value' => $deployment->caption),
    			'order_date' => array('label' => 'Order date', 'value' => $deployment->order_date),
    			'product_count' => array('label' => 'Products', 'value' => count($deploymentProducts)),
    			'comment' => array('label' => 'Comment', 'value' => $deployment->comment),
    	);
    }

    public function detailAction()
    {
    	$id = (int) $this->params()->fromRoute('id', 0);
    	if (!$id) {
    		return $this->redirect()->toRoute('order');
    	}
    	// Retrieve the current user
    	$current_user = Functions::getUser($this);
    
    	// Retrieve the allowed routes
    	$allowedRoutes = Functions::getAllowedRoutes($this);
    
    	// Retrieve the user's instance
    	$instance_id = Functions::getInstanceId($this);
    
    	// Retrieve the order
    	$deployment = $this->getDeploymentTable()->get($id);
    
    	return array(
    			'current_user' => $current_user,
    			'allowedRoutes' => $allowedRoutes,
    			'title' => 'Order',
    			'header' => $this->setHeader($deployment),
    			'deployment' => $deployment,
    	);
    }
    
    public function transferAction()
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
    	
    	// Retrieve the deployment
    	$deployment = $this->getDeploymentTable()->get($id);

    	$header = $this->setHeader($deployment);
    	 
		// Process the form
    	$form = new DeploymentTransferForm();
    	$form->bind($deployment);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setData($request->getPost());

    		if ($form->isValid()) {

    			// Update the entity with the data from the valid form and update it in the database
    			$deployment->tech_responsible_id = $current_user->user_id;
    			$deployment->status = 'Transféré';
    			if ($deployment->comment) $deployment->comment .= PHP_EOL.PHP_EOL;
    			$deployment->comment .= Date('d/m/Y').' ('.$current_user->n_fn.') :'.PHP_EOL.$form->get('new_comment')->getValue();
       			$this->getDeploymentTable()->save($deployment, $instance_id, $current_user->user_id);

       			// Notify the deployment responsibles (mailing-list)
       			$config = $this->getServiceLocator()->get('config');
       			$settings = $config['citUserSettings'];
				$deploymentResponsibleAddress = $settings['deploymentResponsibleAddress'];
       			$settings = $config['citCoreSettings'];
				$domainName = $settings['domainName'];       			
       			Functions::envoiMail(
       					$this->getServiceLocator(),
       					$deploymentResponsibleAddress,
       					'La commande : '.
       					$deployment->identifier.
       					' a été complétée',
       					'Equipements d\'impression : Commande complétée',
       					NULL, NULL);
       			
    			// Redirect
    			return $this->redirect()->toRoute('deployment');
    		}
    	}
	    return array(
	    	'current_user' => $current_user,
	    	'allowedRoutes' => $allowedRoutes,
    		'title' => 'Deployment',
	    	'header' => $header,
    		'form' => $form,
    		'id' => $id,
    	);
    }

    public function validateAction()
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
    
    	// Retrieve the deployment
    	$deployment = $this->getDeploymentTable()->get($id);
    
    	$header = $this->setHeader($deployment);
    
    	// Process the form
    	$form = new DeploymentValidationForm();
    	$form->bind($deployment);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$validate = $request->getPost('validate', 'No');
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    
    			// "Validate" button case
    			if ($validate != 'No') {
    				$deployment->status = 'Validé';
    				$subText = 'validé';
    			}
    			// "Reject" button case
    			else {
    				$deployment->status = 'A compléter';
    				$subText = 'rejeté';
    			}
    			if ($deployment->comment) $deployment->comment .= PHP_EOL.PHP_EOL;
    			$deployment->comment .= Date('d/m/Y').' ('.$current_user->n_fn.') :'.PHP_EOL.$form->get('new_comment')->getValue();
    			$this->getDeploymentTable()->save($deployment, $instance_id, $current_user->user_id);
    
    			// Notifications
    			$config = $this->getServiceLocator()->get('config');
    			$settings = $config['citCoreSettings'];
    			$domainName = $settings['domainName'];
    			$text = 'Le déploiement de la commande : '.$deployment->identifier.' a été '.$subText;
    
    			// Technical responsible
    			Functions::envoiMail(
    			$this->getServiceLocator(),
    			$header['tech_responsible_n_fn']['value'],
    			$text,
    			'Equipements d\'impression : Statut de déploiement de commande',
    			NULL, NULL);
    
    			// Redirect
    			return $this->redirect()->toRoute('deployment');
    		}
    	}
    	return array(
    			'current_user' => $current_user,
    			'allowedRoutes' => $allowedRoutes,
    			'title' => 'Deployment',
    			'header' => $header,
    			'form' => $form,
    			'id' => $id,
    	);
    }
    
    public function manageAction()
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
    	 
    	// Retrieve the deployment
    	$deployment = $this->getDeploymentTable()->get($id);
    
    	$header = $this->setHeader($deployment);
    
    	// Process the form
    	$form = new DeploymentManagementForm();
    	$form->bind($deployment);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$manage = $request->getPost('manage', 'No');
    		$form->setData($request->getPost());
    
    		if ($form->isValid()) {
    
				// "Manage" button case
    			if ($manage != 'No') {
	    			$deployment->deployment_responsible_id = $current_user->user_id;
					$deployment->status = 'Pris en compte';
	    			$subText = 'pris en compte';
    			}
				// "Reject" button case
    			else {
    				$deployment->status = 'A compléter';
	    			$subText = 'rejeté';
    			}
    			if ($deployment->comment) $deployment->comment .= PHP_EOL.PHP_EOL;
    			$deployment->comment .= Date('d/m/Y').' ('.$current_user->n_fn.') :'.PHP_EOL.$form->get('new_comment')->getValue();
    			$this->getDeploymentTable()->save($deployment, $instance_id, $current_user->user_id);
    
    			// Notifications
    			$config = $this->getServiceLocator()->get('config');
    			$settings = $config['citCoreSettings'];
    			$domainName = $settings['domainName'];
    			$text = 'Le déploiement de la commande : '.$deployment->identifier.' a été '.$subText;

    			// Technical responsible
    			Functions::envoiMail(
    			$this->getServiceLocator(),
    			$header['tech_responsible_n_fn']['value'],
    			$text,
    			'Equipements d\'impression : Commande complétée',
    			NULL, NULL);

	    		if ($deployment->status == 'Prise en compte') {
	    			// Order responsible
	    			Functions::envoiMail(
		    			$this->getServiceLocator(),
	    				$header['responsible_n_fn']['value'],
						$text,
	    				'Equipements d\'impression : Commande complétée',
	    				NULL, NULL);
	
	    			// Approver
	    			Functions::envoiMail(
		    			$this->getServiceLocator(),
		    			$header['approver_n_fn']['value'],
		    			$text,
		    			'Equipements d\'impression : Commande complétée',
		    			NULL, NULL);
	    		}
    			 
    			// Redirect
    			return $this->redirect()->toRoute('deployment');
    		}
    	}
    	return array(
    			'current_user' => $current_user,
    			'allowedRoutes' => $allowedRoutes,
    			'title' => 'Deployment',
    			'header' => $header,
    			'form' => $form,
    			'id' => $id,
    	);
    }
    
    public function getDeploymentTable()
    {
    	if (!$this->DeploymentTable) {
    		$sm = $this->getServiceLocator();
    		$this->DeploymentTable = $sm->get('CitDeployment\Model\DeploymentTable');
    	}
    	return $this->DeploymentTable;
    }
    public function getDeploymentProductTable()
    {
    	if (!$this->deploymentProductTable) {
    		$sm = $this->getServiceLocator();
    		$this->deploymentProductTable = $sm->get('CitDeployment\Model\DeploymentProductTable');
    	}
    	return $this->deploymentProductTable;
    }
    public function getDeploymentProductOptionTable()
    {
    	if (!$this->deploymentProductOptionTable) {
    		$sm = $this->getServiceLocator();
    		$this->deploymentProductOptionTable = $sm->get('CitDeployment\Model\DeploymentProductOptionTable');
    	}
    	return $this->deploymentProductOptionTable;
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

    public function getOrderProductOptionTable()
    {
    	if (!$this->orderProductOptionTable) {
    		$sm = $this->getServiceLocator();
    		$this->orderProductOptionTable = $sm->get('CitOrder\Model\OrderProductOptionTable');
    	}
    	return $this->orderProductOptionTable;
    }
    
    public function getSiteTable()
    {
    	if (!$this->siteTable) {
    		$sm = $this->getServiceLocator();
    		$this->siteTable = $sm->get('CitOrder\Model\SiteTable');
    	}
    	return $this->siteTable;
    }

    public function getSiteContactTable()
    {
    	if (!$this->siteContactTable) {
    		$sm = $this->getServiceLocator();
    		$this->siteContactTable = $sm->get('CitOrder\Model\SiteContactTable');
    	}
    	return $this->siteContactTable;
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
