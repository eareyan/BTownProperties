<?php
/**
 * Model to connect with the Landmark's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_landmark extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Landmark';
    /* Table primary key name*/    
    protected $_primary = 'landmarkId';
}