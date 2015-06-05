<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ValidPhoto
 *
 * @author mercerjd
 */
class library_validate_ValidPhoto extends Zend_Validate_Abstract {
    //put your code here

    public $aaa = 'aaa';
    public $bbb = 'bbb';
        
    const NO_PHOTO  = 'noPhoto';
    const MSG_OOPS1 = 'oops1';
    const MSG_OOPS2 = 'oops2';
    const MSG_OOPS3 = 'oops3';

    protected $_messageVariables = array(
        'min' => 'aaa',
        'max' => 'bbb'
    );
 
    protected $_messageTemplates = array(
        self::NO_PHOTO => 'No photo selected.',
        self::MSG_OOPS1 => "'%value%' is not numeric",
        self::MSG_OOPS2 => "'%value%' must be at least '%min%'",
        self::MSG_OOPS3 => "'%value%' must be no more than '%max%'"
    );
 
    public function isValid($value, $context = null)
    {
        // $context has the values of $_FILES + other validator attributes
        library_classes_Utils::dump($value);
        library_classes_Utils::dump($context);

        $this->_error(self::NO_PHOTO);
        return false;
    }
}

?>
