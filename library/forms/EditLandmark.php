<?php

/**
 * Description of Member
 *
 * @author enriqueareyan & mercerjd
 */
class library_forms_EditLandmark extends library_forms_Base {
    public function init()
    {
        parent::init();
        $this->initSection1();
        $this->setDefaultDecorators();     
    }

    protected function initSection1(){            
        $name = new Zend_Form_Element_Text('name');
        $name->setLabel('Name:')->setRequired(true);

        $lat = new Zend_Form_Element_Text('lat');
        $lat->setLabel('Lat:')->setRequired(true);

        $lng = new Zend_Form_Element_Text('lng');
        $lng->setLabel('Lng:');

        $update = new Zend_Form_Element_Submit('Update');
        $update->setDecorators(array(
            array('ViewHelper',
            array('helper' => 'formSubmit'))
        ));

        $this->addDisplayGroup(array($name, $lat, $lng, $update), "landmark",
            array('legend'=>'Landmark'));
    }
}
