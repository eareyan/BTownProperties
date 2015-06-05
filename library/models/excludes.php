<?php
/**
 * Model to connect with the excludeproperties's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_excludes extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Excludes';
    /* Table primary key name*/    
    protected $_primary = 'memberId';
}