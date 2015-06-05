<?php

/**
 * Description of Base
 *
 * @author enriqueareyan and mercerjd
 */

class library_forms_Base extends Zend_Form
{
    public function init($viewScript=null)
    {
        parent::init();
     
    }
    
    public function setDefaultDecorators($viewScript=null) {

        $this->setDecorators(array(
            'FormElements',
            'Form'
        ));

        $this->setDisplayGroupDecorators(array(
            'FormElements',
            'Fieldset'
        ));

        $this->setElementDecorators(array(
            'ViewHelper',
            'Errors',
            'Label'
        ), array('Search'), false);
        
        if($viewScript) {        
            $this->setDecorators(array(
               array('ViewScript', array('viewScript' => $viewScript))
            ));    
        }        
    }
}