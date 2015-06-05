<?php

/**
 * Description of Member
 *
 * @author enriqueareyan
 */
 class library_forms_Contact extends library_forms_Base
      {
          public function init()
          {
              parent::init();
              
              $comment = new Zend_Form_Element_Textarea('comment');
              $comment->setLabel('Comment:')
                      ->setAttrib('cols', 40)
                      ->setAttrib('rows', 10)
                   ->setRequired(true);

              
              $submit = new Zend_Form_Element_Submit('Send');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              //Add regular fields
              $this->addElements(array($submit));
              /* Basic user info */
              $this->addDisplayGroup(array($comment), "basicInfo",
                                     array('legend'=>'Basic Information'));
              /* Submit button */
              $this->addDisplayGroup(array($submit), "submitInfo");
          }
}
