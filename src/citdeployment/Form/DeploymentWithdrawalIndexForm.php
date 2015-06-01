<?php
namespace CitDeployment\Form;

use Zend\Form\Form;

class DeploymentWithdrawalIndexForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('deployment_withdrawal_index');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
    }
    
    public function addElements($deploymentWithdrawals)
    {
        foreach($deploymentWithdrawals as $deploymentWithdrawal) {

	        $this->add(array(
	        		'name' => 'product'.$deploymentWithdrawal->id,
	        		'type' => 'Checkbox',
	        		'attributes' => array(
	        				'id' => 'product'.$deploymentWithdrawal->id,
	        		),
	        		'options' => array(
	        				'label' => NULL,
	        				'use_hidden_element' => true,
	        				'checked_value' => '1',
	        				'unchecked_value' => '0'
	        		)
	        ));
        }

        $this->add(array(
        		'name' => 'check_all',
        		'type' => 'Checkbox',
        		'attributes' => array(
        				'id' => 'check_all',
        				'onchange' => 'checkAll()'
        		),
        		'options' => array(
        				'label' => NULL,
        				'use_hidden_element' => true,
        				'checked_value' => '1',
        				'unchecked_value' => '0'
        		)
        ));
        


        $this->add(
        		array(
        				'type' => 'Zend\Form\Element\Date',
        				'name' => 'withdrawal_date',
        				'options' => array(
        						'label' => 'withdrawal_date',
        				),
        				'attributes' => array(
        						'id' => 'withdrawal_date',
        						'min' => '2010-01-01',
        						'max' => '2999-01-01',
        						'step' => '1',
        				)
        		)
        );

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
        				)
        		)
        );

        $this->add(
        		array(
        				'type' => 'Zend\Form\Element\Date',
        				'name' => 'actual_date',
        				'options' => array(
        						'label' => 'Actual delivery',
        				),
        				'attributes' => array(
        						'id' => 'actual_date',
        						'min' => '2010-01-01',
        						'max' => '2999-01-01',
        						'step' => '1',
        				)
        		)
        );
        
        $this->add(array(
    			'name' => 'status',
    			'type' => 'Select',
    			'attributes' => array(
    				'id'    => 'status',    			
    			),
           		 'options' => array(
                	'label' => 'status',
            		'empty_option'  => '--- Please choose ---',
                'value_options' => array(
                    'Non démarré' =>'Non démarré',
                    'En cours'=>'En cours',
                    'Terminé'=>'Terminé'
                ),
            ),
	    ));
      //  $this->get('hoped_delivery_date')->setValue(date('Y-m-d'));

        
        
        
        $this->add(array(
			'name' => 'update_withdrawal_date',
 			'attributes' => array(
				'type'  => 'submit',
				'value' => 'Set withdrawal date',
				'id' => 'update_withdrawal_date',
			),
		));

        $this->add(array(
        		'name' => 'update_provisional_date',
        		'attributes' => array(
        				'type'  => 'submit',
        				'value' => 'Set provisional date',
        				'id' => 'update_provisional_date',
        		),
        ));

        $this->add(array(
        		'name' => 'update_actual_date',
        		'attributes' => array(
        				'type'  => 'submit',
        				'value' => 'Set actual delivery',
        				'id' => 'update_actual_date',
        		),
        ));
        
        $this->add(array(
        		'name' => 'update_status',
        		'attributes' => array(
        				'type'  => 'submit',
        				'value' => 'Set status',
        				'id' => 'update_status',
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

        $this->add(array(
        		'name' => 'id',
        		'attributes' => array(
        				'type'  => 'hidden',
        		),
        ));

      
    }
}
