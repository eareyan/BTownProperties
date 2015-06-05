<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of States
 *
 * @author mercerjd
 */
class library_classes_States {
    //put your code here

    public static function StateDropdown() {
        $db = Zend_Registry::get("DB");
        $sql = "SELECT * FROM State";
        $result = $db->fetchAll($sql);
        return $result;
    }
    
}



?>
