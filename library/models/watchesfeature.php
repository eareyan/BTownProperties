<?php
/**
 * Model to connect with the Watchlistfeatures's table in the DB
 *
 * @author enriqueareyan
 */
class library_models_watchesfeature extends Zend_Db_Table_Abstract{
    /* Table name*/    
    protected $_name = 'WatchesFeature';
    /* Table primary key name*/    
    protected $_primary = 'memberId';
}