<?php
 
 class library_forms_WatchFeatures extends library_forms_Base
  {
      public function init()
      {
        parent::init();            
        $this->initGroup3();
        $this->initButtons();
        $this->setDefaultDecorators();     
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

            $watchedFeatures = $this->fetchWatchedFeatures();
            $features->setValue($watchedFeatures);
            //echo '<pre>' . print_r($watchedFeatures, true) . '</pre>';

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
      
        protected function fetchWatchedFeatures(){
            $session= Zend_Registry::get('session');
            $memberId = $session->memberData->memberId;
            
            $model_watchesfeature = new library_models_watchesfeature;
            $watchedfeatures = $model_watchesfeature->select()->where("memberId=$memberId")->query()->fetchAll();

            $r = array();
            foreach($watchedfeatures as $row) $r[] = $row['featureId'];
            return $r;
        }

  }
