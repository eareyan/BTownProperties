<?php
 
 class library_forms_PropertyEdit extends library_forms_Base
      {
          public function init()
          {
            parent::init();


            $this->initSection1();
            $this->initSection2();
            //$this->initSection3();
            $this->initGroup3();
            $this->initButtons();

            $this->setDefaultDecorators();     
            
            $this->addElementPrefixPath('library_decorators', 'library/decorators/', 'decorator');

            if(0) {
                $this->setElementDecorators(array(
                    'ViewHelper',
                    'ElementWrapper',
                    'Label'
                ));            
            }
          }

          protected function initSection1(){            
            /* Photo Upload */
            $photo = new library_classes_PhotoUploadElement('photo');
            $photo->setDestination(Zend_Registry::get("APP_PATH") . 'photos/');
            $photo->setMaxFileSize(8388608);
            $photo->addValidator('Extension', false, 'jpg,jpeg,png,gif');
            $photo->setLabel('Photo');
            
            $name = new Zend_Form_Element_Text('name');
            $name->setLabel('Name:')->setRequired(true);

            $description = new Zend_Form_Element_Textarea('description');
            $description->setLabel('Description:')
                  ->setAttribs(array('cols'=>35,'rows'=>15))->setRequired(true);

            $address1 = new Zend_Form_Element_Text('address1');
            $address1->setLabel('Address 1:')->setRequired(true);

            $address2 = new Zend_Form_Element_Text('address2');
            $address2->setLabel('Address 2:');

            $city = new Zend_Form_Element_Text('city');
            $city->setLabel('City:')->setRequired(true);

            $state = new Zend_Form_Element_Text('state');
            $state->setLabel('State:')->setRequired(true);

            $zipcode = new Zend_Form_Element_Text('zipcode');
            $zipcode->setLabel('Zip Code:')->setRequired(true);

            /* Basic property info */
            $this->addDisplayGroup(array($photo, $name,$description,$address1,$address2,$city,$state,$zipcode), "location",
                array('legend'=>'Location'));
          }
          
          protected function initSection2(){
              $price = new Zend_Form_Element_Text('price');
              $price->setLabel('Price:')
                    ->setAttrib('size', 8);
                      //->addValidator('Float');

              $type = new Zend_Form_Element_Select('type');
              $type->setLabel('Type:')
                   ->addMultiOption('Rental','Rental')
                   ->addMultiOption('Sublet','Sublet');
              
              $bedrooms = new Zend_Form_Element_Select('bedrooms');
              $bedrooms->setLabel('Bedrooms:')
                       ->addMultiOptions(array(0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20))
                       ->addValidator('Int');

              $baths = new Zend_Form_Element_Select('baths');
              $baths->setLabel('Baths:')
                    ->addMultiOptions(array('1'=>1,'1.5'=>'1.5','2'=>2,'2.5'=>'2.5','3'=>3,'3.5'=>'3.5','4'=>4,'4.5'=>'4.5','5'=>5,'5.5'=>'5.5','6'=>6,'6.5'=>'6.5','7'=>7,'7.5'=>'7.5','8'=>8,'8.5'=>'8.5','9'=>9,'9.5'=>'9.5','10'=>10,'10.5'=>'10.5'));
                    //->addValidator('Float');

             /* Basic property info */
             $this->addDisplayGroup(array($price,$type,$bedrooms,$baths), "details",
                array('legend'=>'Details'));

              
          }
          
          protected function initSection3(){
              $features_checkboxes = array();
              //Add dynamic fields (those in the feature table)
              foreach($this->fetchFeatures() as $key=>$elements){
                  $features_checkboxes[] = $elements;
              }
              /* Features Checkbox */
              $this->addDisplayGroup($features_checkboxes, "featuresInfo",
                                     array('legend'=>'Features'));              
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

            protected function initButtons(){
              $submit = new Zend_Form_Element_Submit('Upload');
              $submit->setDecorators(array(
                         array('ViewHelper',
                         array('helper' => 'formSubmit'))
                     ));
                            
              /* Submit button */
              $this->addDisplayGroup(array($submit), "submitInfo");              
          }
          
          protected function fetchFeatures(){
              $model_feature = new library_models_feature;
              $features = $model_feature->select()->distinct()->query()->fetchAll();
              foreach($features as $key=>$row){
                   $field = new Zend_Form_Element_Checkbox('feature_'.$row["featureId"]);
                   $field->setLabel($row["name"]);
                   $feature_checkbox[] = $field;
              }
              return $feature_checkbox;
          }
      }
