<?php
/**
 * Model to connect with the excludeproperties's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_contact extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Contact';
    /* Table primary key name*/    
    protected $_primary = 'senderId';
}