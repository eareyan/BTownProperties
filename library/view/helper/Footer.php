<?php

/**
 * Description of Menu
 *
 * @author mercerjd
 */
class library_view_helper_Footer {
    public static function Footer(){
        $rootpath = Zend_Registry::get("ROOTPATH");
        ob_start();
?>
        <div id="footer">
            &nbsp;&nbsp; &nbsp;&nbsp;<a href="<?=$rootpath?>index/aboutus">About Us</a></p>
        </div>

<?php
        return ob_get_clean();
    }
}