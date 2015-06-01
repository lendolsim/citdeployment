<?php
namespace CitDeployment\Form;

use Zend\Form\Form;

use CitOrder\Model\Vcard;

class DeploymentProductUpdateForm extends Form
{
    public function __construct($name = null)
    {
        // we want to ignore the name passed
        parent::__construct('deployment_product');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', 'form-horizontal');
    	
    	$this->add(array(
    			'name' => 'config_center',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'ConfigCenter',
    			),
    	));

    	$this->add(array(
    			'name' => 'mode_config_clones',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'ModeConfigClones',
    			),
    	));

    	$this->add(array(
    			'name' => 'nom_hote',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'nomHote',
    			),
    	));

    	$this->add(array(
    			'name' => 'dhcp_activation',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'DHCP-Activation',
    			),
    	));

    	$this->add(array(
    			'name' => 'adresse_ip',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'AdresseIP',
    			),
    	));

    	$this->add(array(
    			'name' => 'masque_reseau',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'MasqueReseau',
    			),
    	));

    	$this->add(array(
    			'name' => 'passerelle',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'Passerelle',
    			),
    	));

    	$this->add(array(
    			'name' => 'nom_de_domaine',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'NomDeDomaine',
    			),
    	));

    	$this->add(array(
    			'name' => 'dns_primaire',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'DNSPrimaire',
    			),
    	));

    	$this->add(array(
    			'name' => 'dns_secondaire',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'DNSSecondaire',
    			),
    	));

    	$this->add(array(
    			'name' => 'smtp_activation',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMTP-Activation',
    			),
    	));

    	$this->add(array(
    			'name' => 'smtp_adresse',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMTP-Adresse',
    			),
    	));

    	$this->add(array(
    			'name' => 'smtp_courriel',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMTP-Courriel',
    			),
    	));

    	$this->add(array(
    			'name' => 'smtp_mot_de_passe',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMTP-MotDePasse',
    			),
    	));

    	$this->add(array(
    			'name' => 'ftp_activation',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'FTP-Activation',
    			),
    	));

    	$this->add(array(
    			'name' => 'ftp_adresse',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'FTP-Adresse',
    			),
    	));

    	$this->add(array(
    			'name' => 'ftp_chemin_serveur',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'FTP-CheminServeur',
    			),
    	));

    	$this->add(array(
    			'name' => 'ftp_login',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'FTP-Login',
    			),
    	));

    	$this->add(array(
    			'name' => 'ftp_mot_de_passe',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'FTP-MotDePasse',
    			),
    	));

    	$this->add(array(
    			'name' => 'smb_activation',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMB-Activation',
    			),
    	));

    	$this->add(array(
    			'name' => 'smb_adresse',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMB-Adresse',
    			),
    	));

    	$this->add(array(
    			'name' => 'smb_chemin_serveur',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMB-CheminServeur',
    			),
    	));

    	$this->add(array(
    			'name' => 'smb_login',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMB_Login',
    			),
    	));

    	$this->add(array(
    			'name' => 'smb_mot_de_passe',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'SMB-MotDePasse',
    			),
    	));

    	$this->add(array(
    			'name' => 'ldap_adresse',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'LDAP-Adresse',
    			),
    	));

    	$this->add(array(
    			'name' => 'ldap_chemin_acces',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'LDAP_CheminAcces',
    			),
    	));

    	$this->add(array(
    			'name' => 'ldap_user',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'LDAP-User',
    			),
    	));

    	$this->add(array(
    			'name' => 'ldap_mot_de_passe',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'LDAP-MotDePasse',
    			),
    	));

    	$this->add(array(
    			'name' => 'ntp_adresse',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'NTP-Adresse',
    			),
    	));

    	$this->add(array(
    			'name' => 'fax_mode',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'Fax-Mode',
    			),
    	));

    	$this->add(array(
    			'name' => 'fax_serveur',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'FAX-Serveur',
    			),
    	));

    	$this->add(array(
    			'name' => 'fax_numero',
    			'attributes' => array(
    					'type'  => 'text',
    					'size'  => '20',
    			),
    			'options' => array(
    					'label' => 'FAX-Numero',
    			),
    	));
    	 
    	$this->add(array(
    			'name' => 'comment',
    			'attributes' => array(
    					'type'  => 'textarea',
    					'rows' => 3,
    					'cols' => 20,
    					'placeholder' => 'Comment'
    			),
    			'options' => array(
    					'label' => 'Comment',
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

        $this->add(array(
        		'name' => 'id',
        		'attributes' => array(
        				'type'  => 'hidden',
        		),
        ));

        $this->add(array(
        		'name' => 'deployment_id',
        		'attributes' => array(
        				'type'  => 'hidden',
        		),
        ));

        $this->add(array(
        		'name' => 'order_product_id',
        		'attributes' => array(
        				'type'  => 'hidden',
        		),
        ));

        $this->add(array(
        		'name' => 'price',
        		'attributes' => array(
        				'type'  => 'hidden',
        		),
        ));
    }
}
