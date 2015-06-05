<?php
 
 class library_forms_Feature extends library_forms_Base
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

              $submit = new Zend_Form_Element_Submit('Submit');
              $submit->setDecorators(array(
                     array('ViewHelper',
                     array('helper' => 'formSubmit'))
                 ));

            $this->addDisplayGroup(array($name, $submit), "feature",
                array('legend'=>'Add Feature'));
          }
      }
