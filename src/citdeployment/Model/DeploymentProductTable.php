<?php
namespace CitDeployment\Model;

use Zend\Db\TableGateway\TableGateway;
use Zend\Session\Container;

class DeploymentProductTable
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
    	$data['deployment_id'] = $entity->deployment_id;
		$data['order_product_id'] = $entity->order_product_id;
		$data['options'] = $entity->options;
		$data['config_center'] = $entity->config_center;
		$data['mode_config_clones'] = $entity->mode_config_clones;
		$data['nom_hote'] = $entity->nom_hote;
		$data['dhcp_activation'] = $entity->dhcp_activation;
		$data['adresse_ip'] = $entity->adresse_ip;
		$data['masque_reseau'] = $entity->masque_reseau;
		$data['passerelle'] = $entity->passerelle;
		$data['nom_de_domaine'] = $entity->nom_de_domaine;
		$data['dns_primaire'] = $entity->dns_primaire;
		$data['dns_secondaire'] = $entity->dns_secondaire;
		$data['smtp_activation'] = $entity->smtp_activation;
		$data['smtp_adresse'] = $entity->smtp_adresse;
		$data['smtp_courriel'] = $entity->smtp_courriel;
		$data['smtp_mot_de_passe'] = $entity->smtp_mot_de_passe;
		$data['ftp_activation'] = $entity->ftp_activation;
		$data['ftp_adresse'] = $entity->ftp_adresse;
		$data['ftp_chemin_serveur'] = $entity->ftp_chemin_serveur;
		$data['ftp_login'] = $entity->ftp_login;
		$data['ftp_mot_de_passe'] = $entity->ftp_mot_de_passe;
		$data['smb_activation'] = $entity->smb_activation;
		$data['smb_adresse'] = $entity->smb_adresse;
		$data['smb_chemin_serveur'] = $entity->smb_chemin_serveur;
		$data['smb_login'] = $entity->smb_login;
		$data['smb_mot_de_passe'] = $entity->smb_mot_de_passe;
		$data['ldap_adresse'] = $entity->ldap_adresse;
		$data['ldap_chemin_acces'] = $entity->ldap_chemin_acces;
		$data['ldap_user'] = $entity->ldap_user;
		$data['ldap_mot_de_passe'] = $entity->ldap_mot_de_passe;
		$data['ntp_adresse'] = $entity->ntp_adresse;
		$data['fax_mode'] = $entity->fax_mode;
		$data['fax_serveur'] = $entity->fax_serveur;
		$data['fax_numero'] = $entity->fax_numero;
		$data['comment'] = $entity->comment;
		$data['availability_date'] = $entity->availability_date;
		$data['provisional_date'] = $entity->provisional_date;
		$data['actual_date'] = $entity->actual_date;
		$data['connection_date'] = $entity->connection_date;
		$data['status'] = $entity->status;
				
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
