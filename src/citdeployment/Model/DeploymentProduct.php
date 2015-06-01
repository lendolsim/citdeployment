<?php
namespace CitDeployment\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class DeploymentProduct implements InputFilterAwareInterface
{
    public $id;
    public $deployment_id;
    public $order_product_id;
    public $options;
    public $config_center;
	public $mode_config_clones;
	public $nom_hote;
	public $dhcp_activation;
	public $adresse_ip;
	public $masque_reseau;
	public $passerelle;
	public $nom_de_domaine;
	public $dns_primaire;
	public $dns_secondaire;
	public $smtp_activation;
	public $smtp_adresse;
	public $smtp_courriel;
	public $smtp_mot_de_passe;
	public $ftp_activation;
	public $ftp_adresse;
	public $ftp_chemin_serveur;
	public $ftp_login;
	public $ftp_mot_de_passe;
	public $smb_activation;
	public $smb_adresse;
	public $smb_chemin_serveur;
	public $smb_login;
	public $smb_mot_de_passe;
	public $ldap_adresse;
	public $ldap_chemin_acces;
	public $ldap_user;
	public $ldap_mot_de_passe;
	public $ntp_adresse;
	public $fax_mode;
	public $fax_serveur;
	public $fax_numero;
	public $comment;
	public $availability_date;
	public $provisional_date;
	public $actual_date;
	public $connection_date;
	public $status;
	
	// Additionnal fields (not in database)
	public $n_fn;
	public $caption;
	public $brand;
	public $model;
	public $option_price;
	public $price;
	public $contact_id;
	public $hoped_delivery_date;
	public $building;
	public $floor;
	public $department;
	
    protected $inputFilter;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->deployment_id = (isset($data['deployment_id'])) ? $data['deployment_id'] : null;
        $this->order_product_id = (isset($data['order_product_id'])) ? $data['order_product_id'] : null;
        $this->options = (isset($data['options'])) ? $data['options'] : null;
        $this->config_center = (isset($data['config_center'])) ? $data['config_center'] : null;
        $this->mode_config_clones = (isset($data['mode_config_clones'])) ? $data['mode_config_clones'] : null;
        $this->nom_hote = (isset($data['nom_hote'])) ? $data['nom_hote'] : null;
        $this->dhcp_activation = (isset($data['dhcp_activation'])) ? $data['dhcp_activation'] : null;
        $this->adresse_ip = (isset($data['adresse_ip'])) ? $data['adresse_ip'] : null;
        $this->masque_reseau = (isset($data['masque_reseau'])) ? $data['masque_reseau'] : null;
        $this->passerelle = (isset($data['passerelle'])) ? $data['passerelle'] : null;
        $this->nom_de_domaine = (isset($data['nom_de_domaine'])) ? $data['nom_de_domaine'] : null;
        $this->dns_primaire = (isset($data['dns_primaire'])) ? $data['dns_primaire'] : null;
        $this->dns_secondaire = (isset($data['dns_secondaire'])) ? $data['dns_secondaire'] : null;
        $this->smtp_activation = (isset($data['smtp_activation'])) ? $data['smtp_activation'] : null;
        $this->smtp_adresse = (isset($data['smtp_adresse'])) ? $data['smtp_adresse'] : null;
        $this->smtp_courriel = (isset($data['smtp_courriel'])) ? $data['smtp_courriel'] : null;
        $this->smtp_mot_de_passe = (isset($data['smtp_mot_de_passe'])) ? $data['smtp_mot_de_passe'] : null;
        $this->ftp_activation = (isset($data['ftp_activation'])) ? $data['ftp_activation'] : null;
        $this->ftp_adresse = (isset($data['ftp_adresse'])) ? $data['ftp_adresse'] : null;
        $this->ftp_chemin_serveur = (isset($data['ftp_chemin_serveur'])) ? $data['ftp_chemin_serveur'] : null;
        $this->ftp_login = (isset($data['ftp_login'])) ? $data['ftp_login'] : null;
        $this->ftp_mot_de_passe = (isset($data['ftp_mot_de_passe'])) ? $data['ftp_mot_de_passe'] : null;
        $this->smb_activation = (isset($data['smb_activation'])) ? $data['smb_activation'] : null;
        $this->smb_adresse = (isset($data['smb_adresse'])) ? $data['smb_adresse'] : null;
        $this->smb_chemin_serveur = (isset($data['smb_chemin_serveur'])) ? $data['smb_chemin_serveur'] : null;
        $this->smb_login = (isset($data['smb_login'])) ? $data['smb_login'] : null;
        $this->smb_mot_de_passe = (isset($data['smb_mot_de_passe'])) ? $data['smb_mot_de_passe'] : null;
        $this->ldap_adresse = (isset($data['ldap_adresse'])) ? $data['ldap_adresse'] : null;
        $this->ldap_chemin_acces = (isset($data['ldap_chemin_acces'])) ? $data['ldap_chemin_acces'] : null;
        $this->ldap_user = (isset($data['ldap_user'])) ? $data['ldap_user'] : null;
        $this->ldap_mot_de_passe = (isset($data['ldap_mot_de_passe'])) ? $data['ldap_mot_de_passe'] : null;
        $this->ntp_adresse = (isset($data['ntp_adresse'])) ? $data['ntp_adresse'] : null;
        $this->fax_mode = (isset($data['fax_mode'])) ? $data['fax_mode'] : null;
        $this->fax_serveur = (isset($data['fax_serveur'])) ? $data['fax_serveur'] : null;
        $this->fax_numero = (isset($data['fax_numero'])) ? $data['fax_numero'] : null;
        $this->comment = (isset($data['comment'])) ? $data['comment'] : null;
        $this->availability_date = (isset($data['availability_date'])) ? $data['availability_date'] : null;
        $this->provisional_date = (isset($data['provisional_date'])) ? $data['provisional_date'] : null;
        $this->actual_date = (isset($data['actual_date'])) ? $data['actual_date'] : null;
        $this->connection_date = (isset($data['connection_date'])) ? $data['connection_date'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        
        $this->order_caption = (isset($data['order_caption'])) ? $data['order_caption'] : null;
        $this->n_fn = (isset($data['n_fn'])) ? $data['n_fn'] : null;
        $this->caption = (isset($data['caption'])) ? $data['caption'] : null;
        $this->brand = (isset($data['brand'])) ? $data['brand'] : null;
        $this->model = (isset($data['model'])) ? $data['model'] : null;
        $this->option_price = (isset($data['option_price'])) ? $data['option_price'] : null;
        $this->price = (isset($data['price'])) ? $data['price'] : null;
        $this->contact_id = (isset($data['contact_id'])) ? $data['contact_id'] : null;
        $this->hoped_delivery_date = (isset($data['hoped_delivery_date'])) ? $data['hoped_delivery_date'] : null;
        $this->building = (isset($data['building'])) ? $data['building'] : null;
        $this->floor = (isset($data['floor'])) ? $data['floor'] : null;
        $this->department = (isset($data['department'])) ? $data['department'] : null;
    }

 public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception("Not used");
    }

   public function getInputFilter()
    {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
            		'name'     => 'csrf',
            		'required' => false,
            )));

            $inputFilter->add($factory->createInput(array(
            		'name'     => 'building',
            		'required' => FALSE,
            		'filters'  => array(
            				array('name' => 'StripTags'),
            				array('name' => 'StringTrim'),
            		),
            		'validators' => array(
            				array(
            						'name'    => 'StringLength',
            						'options' => array(
            								'encoding' => 'UTF-8',
            								'min'      => 1,
            								'max'      => 255,
            						),
            				),
            		),
            )));

            $inputFilter->add($factory->createInput(array(
            		'name'     => 'floor',
            		'required' => FALSE,
            		'filters'  => array(
            				array('name' => 'StripTags'),
            				array('name' => 'StringTrim'),
            		),
            		'validators' => array(
            				array(
            						'name'    => 'StringLength',
            						'options' => array(
            								'encoding' => 'UTF-8',
            								'min'      => 1,
            								'max'      => 255,
            						),
            				),
            		),
            )));

            $inputFilter->add($factory->createInput(array(
            		'name'     => 'department',
            		'required' => FALSE,
            		'filters'  => array(
            				array('name' => 'StripTags'),
            				array('name' => 'StringTrim'),
            		),
            		'validators' => array(
            				array(
            						'name'    => 'StringLength',
            						'options' => array(
            								'encoding' => 'UTF-8',
            								'min'      => 1,
            								'max'      => 255,
            						),
            				),
            		),
            )));

            $inputFilter->add($factory->createInput(array(
            		'name'     => 'comment',
            		'required' => FALSE,
            		'filters'  => array(
            				array('name' => 'StripTags'),
            				array('name' => 'StringTrim'),
            		),
            		'validators' => array(
            				array(
            						'name'    => 'StringLength',
            						'options' => array(
            								'encoding' => 'UTF-8',
            								'min'      => 1,
            								'max'      => 2047,
            						),
            				),
            		),
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'availability_date',
            		'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'provisional_date',
            		'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'connection_date',
            		'required' => false,
            )));      
            
            $this->inputFilter = $inputFilter;
        }
                
        return $this->inputFilter;
    }
}
    