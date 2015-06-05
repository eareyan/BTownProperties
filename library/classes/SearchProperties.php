<?php

/**
 * This class encapsulates the logic related to properties
 *
 * @author mercerjd and eareyan
 */
class library_classes_SearchProperties {
    
    public static function filterFields(array $values){
        $valid_values = array('searchTerm','landmark','miles','type','maxPrice','bedrooms','baths');
        foreach($valid_values as $index=>$field_name){
            if(!isset($values[$field_name])){
                $values[$field_name] = "";
            }            
        }
        return $values;
    }
    
    public static function search(array $values){
        
        /*
         * Filter the values of the search
         */
        $values = library_classes_SearchProperties::filterFields($values);

        $searchTerm = $values['searchTerm'] ? '%'.$values['searchTerm'].'%' : '%%';
        $landmark    = $values['landmark'];
        $miles      = $values['miles'];
        $type       = $values['type'];                        
        $maxPrice   = $values['maxPrice'];                        
        $bedrooms   = $values['bedrooms'];                        
        $baths      = $values['baths'];
        if(isset($values['features'])){
            $features = $values['features'];
            $featureSql = array();
            foreach($features as $feature) $featureSql[] = "h.featureId='$feature'";
            $featureSql = '(h.propertyId=p.propertyId AND ' . implode(' AND ', $featureSql) . ')';
        }
        
        /* Turn on output buffering */

        ob_start();
?>

SELECT p.* FROM Property p
WHERE (name LIKE '<?=$searchTerm?>' OR description LIKE '<?=$searchTerm?>' OR address1 LIKE '<?=$searchTerm?>' OR address2 LIKE '<?=$searchTerm?>' OR city LIKE '<?=$searchTerm?>' OR state LIKE '<?=$searchTerm?>')
<?php if($landmark!=0 && $miles!=0): ?>
AND (DIST(p.propertyId, <?=$landmark?>) <= <?=$miles?>)
<?php endif;?>
<?php if($type): ?>
AND (p.type = '<?=$type?>')
<?php endif;?>
<?php if($maxPrice): ?>
AND (p.price <= '<?=$maxPrice?>')
<?php endif;?>
<?php if($bedrooms): ?>
AND (p.bedrooms <= '<?=$bedrooms?>')
<?php endif;?>
<?php if($baths): ?>
AND (p.baths = '<?=$baths?>')
<?php endif;?>
<?php if(isset($features)): ?>
AND EXISTS
(
    SELECT h.propertyId FROM HasFeature h
    WHERE <?=$featureSql?>
)
<?php endif;?>

<?php 
/*
 * Check if the user is logged in
 */
 $session= Zend_Registry::get('session');
 if($session->isLoggedIn){
     $memberData = $session->memberData; 
 }
?>
<?php 
    /*
     * If the user has logged in, do not show those properties that are not in the DB
     */
if(isset($memberData)): ?>
AND p.propertyId NOT IN
(
    SELECT e.propertyId FROM Excludes e
    WHERE e.memberId=<?=$memberData->memberId?>
)
<?php endif;?>
<?php if(isset($values['order']) && $values['order']!= ''):?>
ORDER BY <?=$values['order']?>
<?php endif;?>
<?php
            $sql = ob_get_clean();
            
            //library_classes_Utils::dump($sql);
            
            /* Get the DB resources */
            $dbAdapter = Zend_Registry::get('DB');
            
            return $dbAdapter->fetchAll($sql);
    }
    
}