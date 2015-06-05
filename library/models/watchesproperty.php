<?php
/**
 * Model to connect with the Watchlistproperties's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_watchesproperty extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'WatchesProperty';
    /* Table primary key name*/    
    protected $_primary = 'memberId';
}