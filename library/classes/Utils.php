<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 * @author mercerjd
 */
class library_classes_Utils {
    //put your code here

    public static function getDb() {
        return Zend_Registry::get("database");
    }
    
    public static function dump($o) {
        echo '<pre>' . print_r($o, true) . '</pre>';
    }
}