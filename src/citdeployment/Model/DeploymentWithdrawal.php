<?php
namespace CitDeployment\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;


class DeploymentWithdrawal implements InputFilterAwareInterface
{
    public $id;
    public $deployment_id;
    public $order_withdrawal_id;
    public $comment;
    public $provisional_date;
    public $actual_date;
    public $withdrawal_date;
    public $status;
    
    // Additional field (not in database)
    public $caption;
    public $brand;
    public $model;
    public $identifier;
    public $serial_number;
    public $building;
    public $floor;
    public $place;
    
    protected $inputFilter;

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function exchangeArray($data)
    {
        $this->id = (isset($data['id'])) ? $data['id'] : null;
        $this->deployment_id = (isset($data['deployment_id'])) ? $data['deployment_id'] : null;
        $this->order_withdrawal_id = (isset($data['order_withdrawal_id'])) ? $data['order_withdrawal_id'] : null;
        $this->comment = (isset($data['comment'])) ? $data['comment'] : null;
        $this->provisional_date = (isset($data['provisional_date'])) ? $data['provisional_date'] : null;
        $this->actual_date = (isset($data['actual_date'])) ? $data['actual_date'] : null;
        $this->withdrawal_date = (isset($data['withdrawal_date'])) ? $data['withdrawal_date'] : null;
        $this->status = (isset($data['status'])) ? $data['status'] : null;
        
        $this->caption = (isset($data['caption'])) ? $data['caption'] : null;
        $this->brand = (isset($data['brand'])) ? $data['brand'] : null;
        $this->model = (isset($data['model'])) ? $data['model'] : null;
        $this->identifier = (isset($data['identifier'])) ? $data['identifier'] : null;
        $this->serial_number = (isset($data['serial_number'])) ? $data['serial_number'] : null;
        $this->building = (isset($data['building'])) ? $data['building'] : null;
        $this->floor = (isset($data['floor'])) ? $data['floor'] : null;
        $this->place = (isset($data['place'])) ? $data['place'] : null;
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
            		'name'     => 'comment',
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
            								'max'      => 2047,
            						),
            				),
            		),
            )));
            
            $this->inputFilter = $inputFilter;
        }
                        
        return $this->inputFilter;
    }
}
    