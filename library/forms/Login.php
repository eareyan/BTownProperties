<?php

/**
 * Description of Login
 *
 * @author enriqueareyan and mercerjd
 */
class library_forms_Login extends library_forms_Base
{
  public function init()
  {
          parent::init();
      $username = new Zend_Form_Element_Text('username');
      $username->setLabel('E-mail:')
                   ->addValidator('EmailAddress')
                   ->setRequired(true);
      $password = new Zend_Form_Element_Password('password');
      $password->setLabel('Password:')
                   ->setRequired(true);
      $submit = new Zend_Form_Element_Submit('Enter');
      $submit->setValue('Login into the system')
             ->setDecorators(array(
                 array('ViewHelper',
                 array('helper' => 'formSubmit'))
             ));
      /* Features Checkbox */
      $this->addDisplayGroup(array($username,$password), "loginInfo",
                             array('legend'=>'Login'));              
      
      /* Submit button */
      $this->addDisplayGroup(array($submit), "submitInfo");

  }
}