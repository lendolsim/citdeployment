<?php
namespace CitDeployment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;

class DeploymentTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    
    public function getAdapter()
    {
    	return $this->tableGateway->getAdapter();
    }
    
    public function getSelect()
    {
		$select = new \Zend\Db\Sql\Select();
	    $select->from($this->tableGateway->getTable());
    	return $select;
    }

    public function selectWith($select)
    {
//    	throw new \Exception($select->getSqlString($this->getAdapter()->getPlatform()));
    	return $this->tableGateway->selectWith($select);
    }
    
    public function selectWithAsArray($select)
    {
    	$statement = $this->tableGateway->getSql()->prepareStatementForSqlObject($select);
    	$resultSet = $statement->execute();
    	return $resultSet;
    }

    public function fetchDistinct($column)
    {
		$select = new \Zend\Db\Sql\Select();
    	$select->from($this->tableGateway->getTable())
			   ->columns(array($column))
    		   ->quantifier(\Zend\Db\Sql\Select::QUANTIFIER_DISTINCT);
		return $this->tableGateway->selectWith($select);
    }
    
    public function get($id, $column = 'id')
    {
    	$id  = (int) $id;
    	$rowset = $this->tableGateway->select(array($column => $id));
    	$row = $rowset->current();
    	if (!$row) {
    		throw new \Exception("Could not find row $id");
    	}
    	return $row;
    }
    
    public function save($entity, $instance_id, $user_id)
    {
       	$data = array();

    	// Specific
    	$data['order_id'] = $entity->order_id;
       	$data['responsible_id'] = $entity->responsible_id;
		$data['approver_id'] = $entity->approver_id;
		$data['tech_responsible_id'] = $entity->tech_responsible_id;
		$data['deployment_responsible_id'] = (int) $entity->deployment_responsible_id;
		$data['site_id'] = $entity->site_id;
		$data['order_date'] = $entity->order_date;
		$data['identifier'] = $entity->identifier;
		$data['caption'] = $entity->caption;
/*		$data['description'] = $entity->description;
		$data['nb_people'] = (int) $entity->nb_people;
		$data['surface'] = (float) $entity->surface;
		$data['nb_floors'] = (int) $entity->nb_floors;*/
		$data['comment'] = $entity->comment;
		$data['issue_date'] = ($entity->issue_date) ? $entity->issue_date : null;
		$data['retraction_limit'] = $entity->retraction_limit;
		$data['retraction_date'] = ($entity->retraction_date) ? $entity->retraction_date : null;
		//		$data['initial_hoped_delivery_date'] = $entity->initial_hoped_delivery_date;
		$data['current_hoped_delivery_date'] = $entity->current_hoped_delivery_date;
		$data['management_date'] = $entity->management_date;
		$data['expected_delivery_date'] = ($entity->expected_delivery_date) ? $entity->expected_delivery_date : null;
		$data['actual_delivery_date'] = ($entity->actual_delivery_date) ? $entity->actual_delivery_date : null;
		$data['finalized_order_date'] = ($entity->finalized_order_date) ? $entity->finalized_order_date : null;
		$data['status'] = $entity->status;
/*		$data['availability_date'] = $entity->availability_date;
		$data['provisional_date'] = $entity->provisional_date;
		$data['connection_date'] = $entity->connection_date;
		$data['deployment_Status'] = $entity->deployment_Status;*/
			
		$data['instance_id'] = $instance_id;
		$data['update_time'] = date("Y-m-d H:i:s");
		$data['update_user'] = $user_id;
        $id = (int)$entity->id;
        if ($id == 0) {
        	$data['creation_time'] = date("Y-m-d H:i:s");
        	$data['creation_user'] = $user_id;
        	$this->tableGateway->insert($data);
        	return $this->getAdapter()->getDriver()->getLastGeneratedValue();
        } else {
            if ($this->get($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function delete($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }

    public function multipleDelete($where)
    {
        $this->tableGateway->delete($where);
    }
}
