<?php

/**
 * Description of Delete
 *
 * @author enriqueareyan
 */
 class library_forms_Delete extends library_forms_Base
      {
          public function init()
          {
              parent::init();
              $submit = new Zend_Form_Element_Submit('Delete');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              //Add regular fields
              $this->addElements(array(
                  $submit
              ));                 
          }
}