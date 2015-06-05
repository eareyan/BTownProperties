<?php
 
function foo($a) {
    return array($a['landmarkId'], $a['name']);
}

class library_forms_PropertySearch extends library_forms_Base
{
    public function init()
    {
        parent::init();

        $this->setAction('/resource/process')->setMethod('get');
        $this->setAttribs(array('id'=>'propertySearchForm'));

        $this->initGroup1();
        $this->initGroup2();
        $this->initGroup3();
        $this->initButtonGroup();        

        $this->setDefaultDecorators();

    }
    
    public function initGroup1() {
        
        // Search term
        $searchTerm = new Zend_Form_Element_Text('searchTerm');
        $searchTerm->setLabel('Search:');
        //$searchTerm->setAttrib('class','oink');
        $this->addElement($searchTerm);        

        // Property type
        $propertyType = $this->createElement('radio','type');
        $propertyType->setLabel('Type:')
                     ->addMultiOptions(array(
                        'Rental' => 'Rental',
                        'Sublet' => 'Sublet'))
                     ->setSeparator('&nbsp;');
        $this->addElement($propertyType);                

        // Get distances
        $sql = "SELECT * FROM Distance";
        $options = Zend_Registry::get('DB')->fetchPairs("SELECT distance, distance FROM Distance ORDER BY distanceId"); 
        array_unshift($options, '-----');

        // Create Distance dropdown
        $miles = $this->createElement('select','miles');
        $miles->setLabel('Within:')->addMultiOptions($options);
        $this->addElement($miles);
        
        // Get the list of landmarks
        $sql = "SELECT * FROM Landmark";
        $options = Zend_Registry::get('DB')->fetchPairs("SELECT landmarkId, name FROM Landmark ORDER BY name"); 
        $options = Zend_Registry::get('DB')->fetchAll("SELECT landmarkId, name FROM Landmark ORDER BY name"); 
        
        $opts = array();
        $opts['0'] = '-----';
        foreach($options as $i=>$a) {
            $opts[$a['landmarkId']] = $a['name'];
        }
        
        //echo '<pre>' . print_r($opts, true) . '</pre>';
        
        // Create Landmark dropdown
        $landmark = $this->createElement('select','landmark');
        $landmark->setLabel('miles of:')->addMultiOptions($opts);
        
        $this->addElement($landmark);
        
        
        // add group
        $addDisplayGroup = $this->addDisplayGroup(array('searchTerm', 'type', 'miles', 'landmark'), 'group1');        
    }

    public function initGroup2() {
        
        // Price
        $maxPrice = new Zend_Form_Element_Text('maxPrice');
        $maxPrice->setLabel('Max Price:');
        $this->addElement($maxPrice);        

        // Bedrooms
        $bedrooms = new Zend_Form_Element_Text('bedrooms');
        $bedrooms->setLabel('Bedrooms:');
        $this->addElement($bedrooms);        

        // Baths
        $baths = new Zend_Form_Element_Text('baths');
        $baths->setLabel('Baths:');
        $this->addElement($baths);        
        
        // add group
        $this->addDisplayGroup(array('maxPrice', 'bedrooms', 'baths'), 'group2');


    }    

    public function initGroup3() {

        // Get the list of features
        $sql = "SELECT * FROM Feature ORDER BY name";
        $featureList = Zend_Registry::get('DB')->fetchAll($sql);

        // Checkboxes of features
        $features = new Zend_Form_Element_MultiCheckbox('features');
        $features->setLabel('Features:');        
        $features->setSeparator('&nbsp;');
        foreach($featureList as $feature) {
            $features->addMultiOption($feature['featureId'], $feature['name']);                
        }
        $this->addElement($features);
        
        // add group
        $this->addDisplayGroup(array('features'), 'group3');
    }

    public function initButtonGroup() {
        // Submit button
        $submit = new Zend_Form_Element_Submit('Search');
        $submit->setDecorators(array(
                 array('ViewHelper',
                 array('helper' => 'formSubmit'))
             ));
        $this->addElement($submit);

        // add group
        $this->addDisplayGroup(array('Search'), 'buttonGroup');
    }    
}
