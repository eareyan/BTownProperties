<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of loginStatus
 *
 * @author enriqueareyan and mercerjd
 */
class library_view_helper_LoginStatus{
    
    public static function LoginStatus2(){
        //Get the session
        $session= Zend_Registry::get('session');

        //Check whethear the user is logged in	
        if($session->isLoggedIn){
            return "<p>Hi ".$session->memberData->fname.", you are logged in as: ".$session->memberData->email."</p>";            
        }
        else {
        
        }

    }
    
    public static function LoginStatus(){
        $session= Zend_Registry::get('session');
        //Check whethear the user is logged in	

        ob_start();
        
        if($session->isLoggedIn){
?>
<div id="loginStatus">    
    <p class="p2">
        Logged in as: <?=$session->memberData->email ?>
        <span>
            <a href="<?=Zend_Registry::get("ROOTPATH")?>members/index/logout">Logout</a>
        </span>
    </p>
</div>   

<?php
        }else{
?>
<div id="loginStatus">    
    <p class="p2">
        <span>Not a member?
            <a href="<?=Zend_Registry::get("ROOTPATH")?>members/index/signup">Sign-Up</a>
            Or <a href="<?=Zend_Registry::get("ROOTPATH")?>members/index/login">Login</a>
        </span>
    </p>
</div>
<?php
        }

   return ob_get_clean();     
        
   }

}