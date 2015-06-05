<?php
/**
 * Model to connect with the Properties' table in the DB
 *
 * @author enriqueareyan
 */
class library_models_property extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Property';
    /* Table primary key name*/    
    protected $_primary = 'propertyId';
}