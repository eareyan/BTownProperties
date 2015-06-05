<?php

/**
 * This Controller handles the properties' related logic.
 *
 * @author enriqueareyan & mercerjd
 */
class Properties_PropertiesController extends modules_default_controllers_BaseController{
    /*
     *  Functionality to search for a property. It should work for both members and visitors (SF 2.5) 
     */    
    public function searchAction(){
        /* Initialize the properties' search form*/
        $form = new library_forms_PropertySearch();
        
        $this->view->form = $form;
        
        /* Check to see if the user hitted the search button*/        
        if($form->isValid($_POST)) {
            /*Get the values from the form*/
            $values = $form->getValues();
            //Check if we are giving an order.
            if(!is_null($this->_getParam('order'))){
                $values['order'] = $this->_getParam('order');
            }            
            /*Fetch results*/
            $this->view->result = Zend_Paginator::factory(library_classes_SearchProperties::search($values));
            /*Set the paginator number*/
            $this->view->result->setCurrentPageNumber($this->_getParam('page'));
            $this->checkRequireLogin($forceLogin = false);
            if(isset($this->memberData)){
                $this->view->DisplayPropertiesOptions = array('showWatchlist'=>true);
            }
            
        }
    }
    /*
     *  Functionality to view a single property. It should work for both members and visitors (SF 2.4) 
     */    
    public function viewAction(){
        //fileter parameter to avoid SQL injection
        $property_id = intval($this->_getParam("id"));
        //Initialize property's model
        $property_model = new library_models_property;
        //Fetch for the property
        $property_data = $property_model->find($property_id)->toArray();
        
        
        //Check if the property exists
        if(is_null($property_data[0])){
            throw new Exception('The property with ID:'.$property_id. ', does not exists.');
        }else{
            //Pass property to the view
            $this->view->property_data = $property_data[0];
            //Fetch the Features
            $sql_features =  "SELECT f.name FROM HasFeature hf, Feature f
                     WHERE hf.propertyId=".$property_data[0]['propertyId']." AND hf.featureId=f.featureId";
            $featuresList = Zend_Registry::get('DB')->fetchAll($sql_features);
            $this->view->features_list = $featuresList;
            //Fetch the Reviews
            $sql_reviews =  "SELECT m.nickname,r.rating,r.comment FROM Review r,Member m
                     WHERE r.propertyId=".$property_data[0]['propertyId']." AND r.memberId = m.memberId";
            $reviewList = Zend_Registry::get('DB')->fetchAll($sql_reviews);
            $this->view->reviews_list = $reviewList;
        }
        try{
            $this->checkPropertyId($property_id);
            $this->view->allow_edit = true;
        }catch(Exception $e){
            $this->view->allow_edit = false;
        }
        //Check if the user is logged in, but not throw exception
        $this->checkRequireLogin($forceLogin = false);
        if(isset($this->memberData)){
            //This means that the user is logged in, therefore, allow contact and review
            $this->view->memberOptions = true;
        }
    }
}