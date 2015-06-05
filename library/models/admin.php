<?php
/**
 * Model to connect with the excludeproperties's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_admin extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Admin';
    /* Table primary key name*/    
    protected $_primary = 'id';
}