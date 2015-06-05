<?php
/**
 * Model to connect with the Review's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_review extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Review';
    /* Table primary key name*/    
    protected $_primary = 'memberId';
}