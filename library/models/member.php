<?php
/**
 * Model to connect with the Members' table in the DB
 *
 * @author enriqueareyan
 */
class library_models_member extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Member';
    /* Table primary key name*/    
    protected $_primary = 'memberId';
}