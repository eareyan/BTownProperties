<?php
/**
 * Model to connect with the Nearby's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_nearby extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Nearby';
    /* Table primary key name*/    
    protected $_primary = 'propertyId';
}