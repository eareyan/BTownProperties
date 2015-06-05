<?php

/**
 * Description of LatLng
 *
 * @author mercerjd
 */
class library_classes_LatLng {
  public function __construct($address) {
    $this->geodata = $this->geocode($address);
    $this->lat = $this->geodata->results[0]->geometry->location->lat;
    $this->lng = $this->geodata->results[0]->geometry->location->lng;
  }
  
  public function __get($name) {
    $location = $this->geodata->results[0]->geometry->location;
    switch($name) {
    case 'lat':     return $location->lat;
    case 'lng':     return $location->lng;
    case 'latlng':  return "$this->lat,$this->lng";
    case 'latlng2': return $this->geodata->results[0]->formatted_address;
    default:        return parent::__get($name);
    }
  }
  
  public function __toString() {
    return "$this->lat,$this->lng";
  }
  
  public function distanceTo($to, $units='imperial') {
    $url = 'http://maps.googleapis.com/maps/api/distancematrix/json';
    $args = array('origins'=>$this->latlng, 'destinations'=>$to, 'units'=>$units, 'sensor'=>'false');
    $data = json_decode(self::API_call($url, $args));
    return $data->rows[0]->elements[0]->distance->text;
  }
  
  public static function geocode($address) {
    $url = 'http://maps.googleapis.com/maps/api/geocode/json';
    return json_decode(self::API_call($url, array('address'=>$address, 'sensor'=>'false')));
  }
  
  public static function API_call($url, $args) {
    $args = http_build_query($args);
    $api = "$url?$args";
    return file_get_contents($api);
  }
  
  public static function dump($o) { echo '<pre>' . print_r($o, true) . '</pre>'; }
   /*
     * Given an array of values for the address of a property, return
     * a properly formatted string for the geo object
     */
    public static function formatGeoAddress($add){
        $ret = $add['address1'];
        if(isset($add['address2']) && $add['address2'] != ""){
            $ret .= ", ". $add['address2'];
        }
        $ret .= ", ". $add['city'] . ", " . $add['state'] . " " . $add['zipcode'];
        return $ret;
    }  

    public static function formatGeoAddress2($add){
        $ret = $add['address1'];
        if(isset($add['address2']) && $add['address2'] != ""){
            $ret .= "<br/>". $add['address2'];
        }
        $ret .= "<br/>". $add['city'] . ", " . $add['state'] . " " . $add['zipcode'];
        return $ret;
    }  
    
}