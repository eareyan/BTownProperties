<?php

/**
 * Description of Member
 *
 * @author enriqueareyan
 */
 class library_forms_Review extends library_forms_Base
      {
          public function init()
          {
              parent::init();
              
              $rating = new Zend_Form_Element_Select('rating');
              $rating->setLabel('Rating:')
                      ->setMultiOptions(array(''=>'',1=>1,2=>2,3=>3,4=>4,5=>5,6=>6,7=>7,8=>8,9=>9,10=>10))
                      ->setRequired(true);
                   
              
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
              $this->addDisplayGroup(array($rating,$comment), "basicInfo",
                                     array('legend'=>'Basic Information'));
              /* Submit button */
              $this->addDisplayGroup(array($submit), "submitInfo");
          }
}
