<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Contact
 *
 * @author mercerjd
 */
class library_forms_MemberContact extends Zend_Form //library_form_Form 
{
    public function init()
    {
        parent::init();
        
        // Dojo-enable the form:
        Zend_Dojo::enableForm($this);
        
        $this->setAction('/resource/process')->setMethod('get');
        $this->setAttribs(array('id'=>'memberContactForm'));

        // From
        $from = new Zend_Form_Element_Text('from');
        $from->setLabel('From:');
        $this->addElement($from);        

        // To
        $to = new Zend_Form_Element_Text('to');
        $to->setLabel('To:');
        $this->addElement($to);        

        // Subject
        $subject = new Zend_Form_Element_Text('subject');
        $subject->setLabel('Subject:');
        $this->addElement($subject);        

        // Message
        $this->addElement(
            'SimpleTextarea',
            'message',
            array(
                'label'    => 'Message:',
                'required' => true,
                'style'    => 'width: 40em; height: 20em;',
            )
        );

        
        // Submit button
        $submit = new Zend_Form_Element_Submit('Send');
        $submit->setDecorators(array(
                 array('ViewHelper',
                 array('helper' => 'formSubmit'))
             ));
        $this->addElement($submit);        
    }
}

?>
