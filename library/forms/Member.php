<?php

/**
 * Description of Member
 *
 * @author enriqueareyan & mercerjd
 */
 class library_forms_Member extends library_forms_Base
      {
          public function init($label='Sign Up!', $manage=false)
          {
              parent::init();
              
              $name = new Zend_Form_Element_Text('fname');
              $name->setLabel('First Name:')->setRequired(true);

              $last_name = new Zend_Form_Element_Text('lname');
              $last_name->setLabel('Last Name:')->setRequired(true);

              $email = new Zend_Form_Element_Text('email');
              $email->setLabel('Email:')
                    ->setRequired(true)
                    ->addValidator('EmailAddress');

              $dob = new Zend_Form_Element_Text('dob');
              $dob->setLabel('Date of Birth:')->setRequired(true);

              $nickname = new Zend_Form_Element_Text('nickname');
              $nickname->setLabel('Nickname:')->setRequired(true);
              
              if($manage){
                  $password = new Zend_Form_Element_Text('password');
                  $password->setLabel('Password:')
                          ->setRequired(true)
                          ->addValidator('stringLength', false, array('min'=>6,'max'=>20));
              }
              else {
                  $password = new Zend_Form_Element_Password('password');
                  $password->setLabel('Password:')
                          ->setRequired(true)
                          ->addValidator('stringLength', false, array('min'=>6,'max'=>20));
              }

              $phone = new Zend_Form_Element_Text('phone');
              $phone->setLabel('Phone:')->setRequired(true);
              
              $submit = new Zend_Form_Element_Submit('Sign Up!');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
              $submit->setLabel($label);
              //Add regular fields
              $this->addElements(array(
                  $submit
              ));
              
              /* Basic user info */
              if($manage) {
                  $manager = new Zend_Form_Element_Checkbox('manager');
                  $manager->setLabel('Manager:');
                  $this->addDisplayGroup(array($name,$last_name,$dob,$nickname,$phone,$manager), "basicInfo",
                                         array('legend'=>'Basic Information'));
              }
              else {
                  $this->addDisplayGroup(array($name,$last_name,$dob,$nickname,$phone), "basicInfo",
                                         array('legend'=>'Basic Information'));
              }

              /* Login info */
              $this->addDisplayGroup(array($email,$password), "loginInfo",
                                     array('legend'=>'Login Information'));
              /* Submit button */
              $this->addDisplayGroup(array($submit), "submitInfo");
          }
}
