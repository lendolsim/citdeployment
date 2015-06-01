<?php
namespace CitDeployment\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use CitCore\Controller\Functions;
use CitDeployment\Model\Deployment;
use CitDeployment\Model\DeploymentProduct;
use CitOrder\Model\Product;
use CitDeployment\Form\DeploymentProductAddForm;
use CitDeployment\Form\DeploymentProductIndexForm;
use CitDeployment\Form\DeploymentProductUpdateForm;
use Zend\Session\Container;
use Zend\Http\Client;
use Zend\Http\Request;

class DeploymentProductController extends AbstractActionController
{
	public $routes;
	protected $instanceTable;
	protected $userTable;
	protected $userRoleTable;
	protected $userRoleLinkerTable;
	protected $vcardTable;

	protected $deploymentTable;
	protected $deploymentProductTable;
	protected $deploymentProductOptionTable;
	protected $orderTable;
	protected $orderProductTable;
	protected $productTable;
	protected $siteTable;
   	
   	public function indexAction()
    {
        // Check the presence of the id parameter (Deployment)
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
    	$order = $this->getOrderTable()->get($deployment->order_id);
    	 
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
    	$select = $this->getDeploymentProductTable()->getSelect()
    		->join('deployment', 'deployment_product.deployment_id = deployment.id', array('site_id', 'identifier'), 'left')
    		->join('order_product', 'deployment_product.order_product_id = order_product.id', array('building', 'floor', 'department'), 'left')
    		->join('md_product', 'order_product.product_id = md_product.id', array('caption', 'brand', 'model'), 'left')
    		->where(array('deployment_id' => $id))
    		->order(array($major.' '.$dir, 'caption', 'id'));
    	$cursor = $this->getDeploymentProductTable()->selectWith($select);

    	$deploymentProducts = array();
    	foreach ($cursor as $deploymentProduct) $deploymentProducts[$deploymentProduct->id] = $deploymentProduct;

    	$form = new DeploymentProductIndexForm();
    	$form->addElements($deploymentProducts);
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setData($request->getPost());
    	
    		// Update the selected rows
    		foreach ($deploymentProducts as $deploymentProduct) {
    				
    			if ($form->get('product'.$deploymentProduct->id)->getValue()) { // Row selected
    	
    				// availability date
    				$action = $request->getPost('update_availability_date', 'Cancel');
    				if ($action == $this->getServiceLocator()->get('translator')->translate('Set availability date')) {
    					
    					$deploymentProduct->availability_date = $form->get('availability_date')->getValue();
    					$this->getDeploymentProductTable()->save($deploymentProduct, $current_user->user_id, $instance_id);
    				}
    					
    				// provisional datee
    				$action = $request->getPost('update_provisional_date', 'Cancel');
    				if ($action == $this->getServiceLocator()->get('translator')->translate('Set provisional date')) {
    					
    					$deploymentProduct2 = $this->getDeploymentProductTable()->get($deploymentProduct->id);
    					$deploymentProduct2->deployment_id=$id;
    					$deploymentProduct2->provisional_date = $form->get('provisional_date')->getValue();
    					$this->getDeploymentProductTable()->save($deploymentProduct2, $current_user->user_id, $instance_id);
    				}
    	
    				// connection date
    				$action = $request->getPost('update_connection_date', 'Cancel');
    				if ($action == $this->getServiceLocator()->get('translator')->translate('Set connection date')) {
    					$deploymentProduct2 = $this->getDeploymentProductTable()->get($deploymentProduct->id);
    					$deploymentProduct2->deployment_id=$id;
    					$deploymentProduct2->connection_date = $form->get('connection_date')->getValue();
    					$this->getDeploymentProductTable()->save($deploymentProduct2, $current_user->user_id, $instance_id);
    				}
    	
    			}
    		}    		 
    		// Redirect to the index
    		return $this->redirect()->toRoute('deploymentProduct/index', array('id' => $id));
    	}
    	return array(
    			'current_user' => $current_user,
    			'current_role' => $current_role,
    			'allowedRoutes' => $allowedRoutes,
    			'major' => $major,
    			'dir' => $dir,
    			'responsible' => $responsible,
    			'order' => $order,
    			'deployment' => $deployment,
    			'deploymentProducts' => $deploymentProducts,
    			'form' => $form
    	);
    }
    
    protected function setHeader($deploymentProduct)
    {
    	// Retrieve the deployment
    	$deployment = $this->getDeploymentTable()->get($deploymentProduct->deployment_id);
    	
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
    	$orderProduct = $this->getOrderProductTable()->get($deploymentProduct->order_product_id);
    	$product = $this->getProductTable()->get($orderProduct->product_id);
    	 
    	// Prepare the form header
    	return array(
    			'responsible_n_fn' => array('label' => 'Responsible', 'value' => $responsible->n_fn),
    			'tech_responsible_n_fn' => array('label' => 'Technical responsible', 'value' => ($techResponsible) ? $techResponsible->n_fn : null),
    			'deployment_responsible_n_fn' => array('label' => 'Deployment responsible', 'value' => ($deploymentResponsible) ? $deploymentResponsible->n_fn : null),
    			'site_caption' => array('label' => 'Site', 'value' => $site->caption),
    			'status' => array('label' => 'Status', 'value' => $deployment->status),
    			'identifier' => array('label' => 'Identifier', 'value' => $deployment->identifier),
    			'product_caption' => array('label' => 'Caption', 'value' => $deploymentProduct->caption),
    			'brand' => array('label' => 'Product brand', 'value' => $product->brand),
    			'model' => array('label' => 'Product model', 'value' => $product->model),
    			'options' => array('label' => 'Options', 'value' => $deploymentProduct->options),
    			'building' => array('label' => 'Building', 'value' => $orderProduct->building),
    			'floor' => array('label' => 'Floor', 'value' => $orderProduct->floor),
    			'department' => array('label' => 'Department', 'value' => $orderProduct->department),
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
    
    	// Retrieve the deployment product
    	$deploymentProduct = $this->getDeploymentProductTable()->get($id);
    
    	return array(
    			'current_user' => $current_user,
    			'allowedRoutes' => $allowedRoutes,
    			'title' => 'Order',
    			'header' => $this->setHeader($deploymentProduct),
    			'deploymentProduct' => $deploymentProduct,
    	);
    }
    
    public function updateAction()
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
    		
    	// Retrieve the order row
    	$deploymentProduct = $this->getDeploymentProductTable()->get($id);
/*    	$product = $this->getProductTable()->get($deploymentProduct->product_id);
    	$deploymentProduct->caption = $product->caption;*/
    
    	// Retrieve the deployment
    	$deployment = $this->getDeploymentTable()->get($deploymentProduct->deployment_id);

    	// Retrieve the order
    	$order = $this->getOrderTable()->get($deployment->order_id);

    	
    	// Retrieve the site
    	$site = $this->getSiteTable()->get($deployment->site_id);
    
    	// Create the form object and initialize it from the existing entity
    	$form = new DeploymentProductUpdateForm();
    	$form->bind($deploymentProduct);
    	$form->get('submit')->setValue('Update');

    	$header = $this->setHeader($deploymentProduct);
    	 
    	// Set the form filters and hydrate it with the data from the request
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setInputFilter($deploymentProduct->getInputFilter());
    		$form->setData($request->getPost());
    
    		// Update the entity with the data from the valid form and update it in the database
    		if ($form->isValid()) {
    			
			   	$deploymentProduct->status = 'Complété';
    			$this->getDeploymentProductTable()->save($deploymentProduct, $instance_id, $current_user->user_id);

    			// Update the deployment status 
    			$select = $this->getDeploymentProductTable()->getSelect()
	    			->where(array('deployment_id' => $deployment->id));
    			$cursor = $this->getDeploymentProductTable()->selectWith($select);
    			$status = 'Complété';
    			foreach ($cursor as $row) if ($row->status == 'A compléter') $status = 'A compléter';
    			if ($status == 'Complété' && $deployment->status == 'A compléter') {
    				$deployment->status = 'Complété';
    				$this->getDeploymentTable()->save($deployment, $instance_id, $current_user->user_id);
    			}    			 
    			// Redirect to the index
    			return $this->redirect()->toRoute('deploymentProduct/index', array('id' => $deployment->id));
    		}
    	}
    	return array(
    			'current_user' => $current_user,
    			'allowedRoutes' => $allowedRoutes,
    			'title' => 'Order',
    			'form' => $form,
    			'id' => $id,
  				'header' => $header,
//    			'deployment' => $deployment,
    			'deploymentProduct' => $deploymentProduct,
    	);
    }
    
    public function deleteAction()
    {
		// Check the presence of the id parameter for the entity to delete
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
    	
    	// Retrieve the order product row
    	$orderProduct = $this->getOrderProductTable()->get($id);
    	 
    	// Retrieve the order
    	$order = $this->getOrderTable()->get($orderProduct->order_id);

    	// Retrieve the product
    	$product = $this->getProductTable()->get($orderProduct->product_id);
    	 
    	// Retrieve the user validation from the post
    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$del = $request->getPost('del', 'No');
    
			// And delete the entity from the database in the "yes" case
    		if ($del == $this->getServiceLocator()->get('translator')->translate('Yes')) {
    			$id = (int) $request->getPost('id');
    			$this->getOrderProductTable()->delete($id);
    		}
    
    		// Redirect to the index
    		return $this->redirect()->toRoute('orderProduct/index', array('id' => $order->id));
    	}
    
    	return array(
    		'current_user' => $current_user,
    		'allowedRoutes' => $allowedRoutes,
    		'title' => 'Order',
    		'id' => $id,
    		'order' => $order,
    		'product' => $product
    	);
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
    
    public function getProductTable()
    {
    	if (!$this->productTable) {
    		$sm = $this->getServiceLocator();
    		$this->productTable = $sm->get('CitMasterData\Model\ProductTable');
    	}
    	return $this->productTable;
    }

    public function getSiteTable()
    {
    	if (!$this->siteTable) {
    		$sm = $this->getServiceLocator();
    		$this->siteTable = $sm->get('CitOrder\Model\SiteTable');
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
