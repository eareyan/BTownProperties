<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FileUploadElement
 *
 * @author mercerjd
 */

class library_classes_PhotoUploadElement  extends Zend_Form_Element_File {

    public function isValid($value, $context = null)
    {
        $name = $this->getName();
        $vals = isset($_FILES[$name]) ? $_FILES[$name] : null;
        
        if($this->isRequired() && $vals) {
            // $vals is $_FILES[name]
            if($vals['error']!=0) {
                $this->addError('Photo not uploaded!');
                return false;
            }
        }        
        
        return parent::isValid($value, $context);
    }

   public function setDecorators(array $decorators)
   {
      parent::setDecorators(array(array('File'), array('Errors')));
   }
   
}

?>
