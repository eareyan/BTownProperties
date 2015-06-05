<?php

/**
 * This class encapsulates the logic to show a property
 *
 * @author enriqueareyan
 */
class library_view_helper_DisplayProperties {
    
    public static $url = array();
    
    public static function orderLink($orderLink,$field,$order){
        if($order == 'ASC'){
            $img = "<img src='".Zend_Registry::get('ROOTURL')."images/up-arrow-icon.jpg'/>";
        }else{
            $img = "<img src='".Zend_Registry::get('ROOTURL')."images/down-arrow-icon.jpg'/>";            
        }
        if(isset(self::$url['contro_name']) && self::$url['contro_name'] == 'properties' && isset(self::$url['action_name']) && self::$url['action_name'] == 'search'){
            return "<a onclick=\"document.forms['propertySearchForm'].action = '{$orderLink}$field $order#propertiesList';document.forms['propertySearchForm'].submit(); return false;\" href=\"#\">$img</a>";
        }else{
            return "<a href=\"{$orderLink}$field $order#propertiesList\">$img</a>";    
        }
    }
    /*
     * This function encapsulates the view logic for displaying a list of properties.
     * Currently, it is being used in the home page, list my properties, property's watchlist,
     * feature's watchlist and search properties.
     */
    public static function DisplayProperties($properties,$obj,array $options = null){
    $ret = "";
    if (count($properties)){
            self::$url = $obj->URL_Mapping;
            $orderLink =    Zend_Registry::get('ROOTURL').
                            $obj->URL_Mapping['module_name'].'/'.
                            $obj->URL_Mapping['contro_name'].'/'.
                            $obj->URL_Mapping['action_name'].'/order/';
            $ret .= $obj->paginationControl($properties, Zend_Paginator::getDefaultScrollingStyle(),Zend_View_Helper_PaginationControl::getDefaultViewPartial(),$obj->URL_Mapping);
            $ret .= "
            <a name=\"propertiesList\"></a>
            <table id=\"displayProperties\">
                <thead>
                    <tr>
                        <td>Name        ".self::orderLink($orderLink,'name','ASC').self::orderLink($orderLink,'name','DESC')                ."</td>
                        <td>Description ".self::orderLink($orderLink,'description','ASC').self::orderLink($orderLink,'description','DESC')  ."</td>
                        <td>Address     ".self::orderLink($orderLink,'address1','ASC').self::orderLink($orderLink,'address1','DESC')        ."</td>
                        <td>Price       ".self::orderLink($orderLink,'price','ASC').self::orderLink($orderLink,'price','DESC')              ."</td>
                        <td>Type        ".self::orderLink($orderLink,'type','ASC').self::orderLink($orderLink,'type','DESC')                ."</td>
                        <td>Bedrooms    ".self::orderLink($orderLink,'bedrooms','ASC').self::orderLink($orderLink,'bedrooms','DESC')        ."</td>
                        <td>Bathrooms   ".self::orderLink($orderLink,'baths','ASC').self::orderLink($orderLink,'baths','DESC')              ."</td>";
            if(isset($options['memberId'])){
                $ret .= "<td>Options</td>";
            }
            $ret .="</tr>
                </thead>
                <tbody>";
            foreach ($properties as $item){
                $ret .="<tr>
                    <td><a href='".Zend_Registry::get('ROOTURL')."properties/properties/view/id/".$item['propertyId']."'>".$item['name']."</a></td>
                    <td>".$item['description']."</td>
                    <td>".library_classes_LatLng::formatGeoAddress($item)."</td>
                    <td>$".number_format($item['price'])."</td>
                    <td>".$item['type']."</td>
                    <td>".$item['bedrooms']."</td>
                    <td>".$item['baths']."</td>";
                    if(isset($options['memberId'])){
                        $ret .= "<td>
                                    <ul>
                                        <li><a href='".Zend_Registry::get('ROOTURL')."properties/member/edit/id/".$item['propertyId']."'>Edit</a></li>
                                        <li><a href='".Zend_Registry::get('ROOTURL')."properties/member/delete/id/".$item['propertyId']."'>Delete</a></li>
                                    </ul>
                                 </td>";
                    }
                    if(!isset($options['hideLinks']) && isset($options['showWatchlist'])){
                        if($options['showWatchlist']){
                            $ret .= "<td>
                                        <ul>
                                            <li><a href='".Zend_Registry::get('ROOTURL')."properties/watchlists/add/id/".$item['propertyId']."'>Add to WatchList</a></li>
                                        </ul>
                                    </td>";
                        }else{
                            $ret .= "<td>
                                        <ul>
                                            <li><a href='".Zend_Registry::get("ROOTURL")."properties/watchlists/remove/id/".$item['propertyId']."'>Remove from WatchList</a></li>
                                        </ul>
                                    </td>";
                        }
                    }
                $ret .="
                </tr>";
            }
            $ret .="
                </tbody>
            </table>".
            $obj->paginationControl($properties, Zend_Paginator::getDefaultScrollingStyle(),Zend_View_Helper_PaginationControl::getDefaultViewPartial(),$obj->URL_Mapping);
        }else{
            $ret = "<div class='info'>There are no properties with this criteria</div>";
        }
        return $ret;
    }
}
