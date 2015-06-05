<?php
/**
 * Model to connect with the Features table in the DB
 *
 * @author enriqueareyan
 */
class library_models_feature extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Feature';
    /* Table primary key name*/    
    protected $_primary = 'featureId';
}