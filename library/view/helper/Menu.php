<?php

/**
 * Description of Menu
 *
 * @author enriqueareyan & mercerjd
 */
class library_view_helper_Menu {
    public static function Menu(){
        $rootpath = Zend_Registry::get("ROOTPATH");
        
        $menu_items = array(''                              =>  'Home',
                            'properties/properties/search'  =>  'Search Apartment',
                                        );
        //Get the session
        $session= Zend_Registry::get('session');
        //Check whethear the user is logged in	
        if(!$session->isLoggedIn){
            //$menu_items['members/index/signup']     = 'Signup';
            //$menu_items['members/index/login']      = 'Login';
        }else{
            $menu_items2 = array(  'properties/member/list'    =>  'List my properties',
                                            'properties/member/upload'  =>  'Upload a property',
                                            'properties/watchlists/property'=>  'Property watchlist',
                                            'properties/watchlists/feature'=>  'Feature watchlist',
                );
            $menu_items = array_merge($menu_items, $menu_items2);
            if($session->isAdmin) 
                $menu_items = array_merge ($menu_items, array('index/manage'=>  'Manage'));
            //$menu_items['members/index/logout']     =  'Logout';
        }
        $ret = "";
        foreach($menu_items as $url=>$item){
            $ret .= "<li>";
            if(is_array($item)){
                $ret .="$url"."";
                foreach($item as $url2=>$item2){
                    $ret .="<li><a href=\"$rootpath$url2\">$item2</a></li>";
                }
                $ret .="";
            }else{
                $ret .="<a href=\"$rootpath$url\">$item</a>";
            }
            $ret .= "</li>";      
        }
        return "<div id=\"menu\"><ul>$ret</ul></div>";
    }
}