<?php
namespace CitDeployment\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class Deployment implements InputFilterAwareInterface
{
    public $id;
    public $order_id;
    public $responsible_id;
    public $approver_id;
    public $tech_responsible_id;
    public $deployment_responsible_id;
	public $site_id;
	public $order_date;
	public $identifier;
	public $caption;
/*	public $description;
    public $nb_people;
    public $surface;
    public $nb_floors;*/
    public $comment;
    public $issue_date;
	public $retraction_limit;
    public $retraction_date;
    //	public $initial_hoped_delivery_date;
	public $current_hoped_delivery_date;
	public $management_date;
	public $expected_delivery_date;
	public $actual_delivery_date;
	public $finalized_order_date;
    public $status;
/*    public $availability_date;
    public $provisional_date;
    public $connection_date;
    public $deployment_Status;*/

    // Additional field (not in database)
    public $site_caption;
/*    public $delegatee_id;
    public $delegation_begin;
    public $delegation_end;*/
    
    protected $inputFilter;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->order_id = (isset($data['order_id'])) ? $data['order_id'] : null;
        $this->responsible_id = (isset($data['responsible_id'])) ? $data['responsible_id'] : null;
        $this->approver_id = (isset($data['approver_id'])) ? $data['approver_id'] : null;
        $this->tech_responsible_id = (isset($data['tech_responsible_id'])) ? $data['tech_responsible_id'] : null;
        $this->deployment_responsible_id = (isset($data['deployment_responsible_id'])) ? $data['deployment_responsible_id'] : null;
        $this->site_id = (isset($data['site_id'])) ? $data['site_id'] : null;
        $this->order_date = (isset($data['order_date'])) ? $data['order_date'] : null;
        $this->identifier = (isset($data['identifier'])) ? $data['identifier'] : null;
        $this->caption = (isset($data['caption'])) ? $data['caption'] : null;
/*        $this->description = (isset($data['description'])) ? $data['description'] : null;
        $this->nb_people = (isset($data['nb_people'])) ? $data['nb_people'] : null;
        $this->surface = (isset($data['surface'])) ? $data['surface'] : null;
        $this->nb_floors = (isset($data['nb_floors'])) ? $data['nb_floors'] : null;*/
        $this->comment = (isset($data['comment'])) ? $data['comment'] : null;
        $this->issue_date = (isset($data['issue_date'])) ? $data['issue_date'] : null;
        $this->retraction_limit = (isset($data['retraction_limit'])) ? $data['retraction_limit'] : null;
        $this->retraction_date = (isset($data['retraction_date'])) ? $data['retraction_date'] : null;
        //        $this->initial_hoped_delivery_date = (isset($data['initial_hoped_delivery_date'])) ? $data['initial_hoped_delivery_date'] : null;
        $this->current_hoped_delivery_date = (isset($data['current_hoped_delivery_date'])) ? $data['current_hoped_delivery_date'] : null;
        $this->management_date = (isset($data['management_date'])) ? $data['management_date'] : null;
        $this->expected_delivery_date = (isset($data['expected_delivery_date'])) ? $data['expected_delivery_date'] : null;
        $this->actual_delivery_date = (isset($data['actual_delivery_date'])) ? $data['actual_delivery_date'] : null;
        $this->finalized_order_date = (isset($data['finalized_order_date'])) ? $data['finalized_order_date'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
/*        $this->availability_date = (isset($data['availability_date'])) ? $data['availability_date'] : null;
        $this->provisional_date = (isset($data['provisional_date'])) ? $data['provisional_date'] : null;
        $this->connection_date = (isset($data['connection_date'])) ? $data['connection_date'] : null;
        $this->deployment_Status = (isset($data['deployment_Status'])) ? $data['deployment_Status'] : null;*/
        
        $this->site_caption = (isset($data['site_caption'])) ? $data['site_caption'] : null;
        $this->delegatee_id = (isset($data['delegatee_id'])) ? $data['delegatee_id'] : null;
        $this->delagation_begin = (isset($data['delegation_begin'])) ? $data['delegation_begin'] : null;
        $this->delagation_end = (isset($data['delegation_end'])) ? $data['delegation_end'] : null;
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
            		'name'     => 'caption',
            		'required' => false,
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
            		'name'     => 'description',
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
            		'name'     => 'identifier',
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
            		'name'     => 'surface',
            		'required' => false,
            		 
            )));
            
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'nb_people',
            		'required' => false,
            		 
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'nb_floors',
            		'required' => false,
            		 
            )));

            $inputFilter->add($factory->createInput(array(
            		'name'     => 'current_hoped_delivery_date',
            		'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'management_date',
            		'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'expected_delivery_date',
            		'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'actual_delivery_date',
            		'required' => false,
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'finalized_order_date',
            		'required' => false,
            ))); 
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'order_id',
            		'required' => false,
            		 
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
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'deployment_Status',
            		'required' => false,
            		 
            )));
            $inputFilter->add($factory->createInput(array(
            		'name'     => 'deployment_responsible_id',
            		'required' => false,
            )));         
            
            $this->inputFilter = $inputFilter;
        }
                
        return $this->inputFilter;
    }
}
    