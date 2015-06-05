<?php
/**
 * Model to connect with the Has_Features' table in the DB
 *
 * @author enriqueareyan
 */
class library_models_hasfeature extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'HasFeature';
    /* Table primary key name*/    
    protected $_primary = 'propertyId';
}