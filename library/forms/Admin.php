<?php
 
 class library_forms_Admin extends library_forms_Base
      {
          public function init()
          {
            parent::init();

            $this->initSection1();
            $this->setDefaultDecorators();     
         }

          protected function initSection1(){            
            $name = new Zend_Form_Element_Text('name');
            $name->setLabel('Name:')->setRequired(true);

            $address1 = new Zend_Form_Element_Text('address1');
            $address1->setLabel('Address 1:')->setRequired(true);

            $address2 = new Zend_Form_Element_Text('address2');
            $address2->setLabel('Address 2:');

            $city = new Zend_Form_Element_Text('city');
            $city->setLabel('City:')->setRequired(true);

            $state = new Zend_Form_Element_Text('state');
            $state->setLabel('State:')->setRequired(true);

            $zipcode = new Zend_Form_Element_Text('zipcode');
            $zipcode->setLabel('Zip Code:')->setRequired(true);

              $submit = new Zend_Form_Element_Submit('Submit');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
            /* Basic property info */
            $this->addDisplayGroup(array($name,$address1,$address2,$city,$state,$zipcode, $submit), "landmark",
                array('legend'=>'Add Landmark'));
          }
      }
