<?php
/**
 * Model to connect with the Word's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_word extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'Word';
    /* Table primary key name*/    
    protected $_primary = 'wordId';
}
