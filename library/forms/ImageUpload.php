<?php

class library_forms_ImageUpload extends Zend_Form 
{ 
    public function __construct($options = null) 
    { 
        parent::__construct($options);

        $this->setName('upload');
        $this->setAttrib('enctype', 'multipart/form-data');
        
        $photo = new library_classes_PhotoUploadElement('photo');
        $photo->setLabel('Photo')->setRequired(true);

        $submit = new Zend_Form_Element_Submit('submit');
        $submit->setLabel('Upload Photo');
        
        $this->addElements(array($photo, $submit));
    } 
} 

?>