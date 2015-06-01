<?php
namespace CitDeployment\Form;

use Zend\Form\Form;

use CitOrder\Model\Vcard;

class DeploymentForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('deployment');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
    }
    
    public function addElements(){


    	$this->add(
    			array(
    					'type' => 'Zend\Form\Element\Date',
    					'name' => 'connection_date',
    					'options' => array(
    							'label' => 'Connection date',
    					),
    					'attributes' => array(
    							'id' => 'connection_date',
    							'min' => '2010-01-01',
    							'max' => '2999-01-01',
    							'step' => '1',
    							//'type'  => 'hidden',
    					)
    			));
    	//$this->get('connection_date')->setValue(date('Y-m-d'));
    	//$this->get('connection_date')->setAttribute('disabled', 'disabled');

    	$this->add(
    			array(
    					'type' => 'Zend\Form\Element\Date',
    					'name' => 'availability_date',
    					'options' => array(
    							'label' => 'Availability date',
    					),
    					'attributes' => array(
    							'id' => 'availability_date',
    							'min' => '2010-01-01',
    							'max' => '2999-01-01',
    							'step' => '1',
    							//'type'  => 'hidden',
    					)
    			));
    	//$this->get('availability_date')->setValue(date('Y-m-d'));
    	 

    	$this->add(
    			array(
    					'type' => 'Zend\Form\Element\Date',
    					'name' => 'provisional_date',
    					'options' => array(
    							'label' => 'Provisional date',
    					),
    					'attributes' => array(
    							'id' => 'provisional_date',
    							'min' => '2010-01-01',
    							'max' => '2999-01-01',
    							'step' => '1',
    							//'type'  => 'hidden',
    					)
    			));
    	//$this->get('provisional_date')->setValue(date('Y-m-d'));
    	

    	$this->add(array(
    			'type' => 'Zend\Form\Element\Select',
    			'name' => 'deployment_Status',
    			'options' => array(
    					'label' => 'Deployment Status',
    					'empty_option'  => '--- Please choose ---',
    					'value_options' => array(
    							'non mis à jour' => 'non mis à jour',
    							'incomplet' => 'incomplet',
    							'complet' => 'complet'
    					)
    			),

    	));

     




        
        $this->add(array(
			'name' => 'submit',
 			'attributes' => array(
				'type'  => 'submit',
				'value' => 'update',
				'id' => 'submit',
			),
		));
        
        // Champs cachés
        $this->add(
            array(
                'name' => 'csrf',
                'type' => 'Csrf',
                'options' => array(
                    'csrf_options' => array(
                        'timeout' => 600
                    )
                )
            )
        );


    }
}
