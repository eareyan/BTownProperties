<?php
/**
 * Model to connect with the Has_Features' table in the DB
 *
 * @author enriqueareyan
 */
class library_models_hasproperty extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'HasProperty';
    /* Table primary key name*/    
    protected $_primary = 'memberId';
}