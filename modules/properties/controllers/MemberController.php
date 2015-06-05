<?php

/**
 * This Controller handles the memebr's properties related logic.
 *
 * @author enriqueareyan & mercerjd
 */
class Properties_MemberController extends modules_default_controllers_BaseController{
    /*
     * Make sure the user is logeed in
     */
    public function init(){
        //Call parent init function
        parent::init();
        //All of the actions of this controller requires that the user be logged in
        parent::checkRequireLogin();
    }
    /*
     * Just list the properties of the member currently logged in
     */
    public function listAction(){
        /*
         * In the index we are just going to show the properties
         */
        //Check if we are giving an order. If not, order by most recent property by default.
        if(!is_null($this->_getParam('order'))){
            $order = $this->_getParam('order');
        }else{
            $order = 'propertyId DESC';
        }
        //Initialize the property's model
        $property_model = new library_models_property;
        //Fetch this member's properties
        $select = $property_model->select()->where('memberID = ' . $this->memberData->memberId)->order($order);
        $adapter = new Zend_Paginator_Adapter_DbSelect($select);
        //Initialize paginator for results
        $paginator = new Zend_Paginator($adapter);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
        $this->view->paginator = $paginator;
        $this->view->displayPropertiesOptions = array('memberId'=>$this->memberData->memberId);
    }
    /*
     *  Functionality for a member to Upload a property. (SF 2.3) 
     */
    public function uploadAction(){

        /* Initialize the property form*/
       $form_property = new library_forms_PropertyEdit;
       $this->view->formProperty = $form_property;       
       /* This logic indicates that the user hit the submit button */
       if ($this->getRequest()->isPost() && $form_property->isValid($_POST)) {

            $uid = uniqid();
            $rvals = $this->doPhotoUpload($uid);
            $tmpFileName = $rvals[0];
           
           /* Save the register to the database*/
            //Obtain form values
            $property = $form_property->getValues();
            //Obtain the properties' model
            $model_property = new library_models_property;
            //Filter the properties register to get property data and features data separately
            $data = $this->filterPropertyFields($property);
            //Add the id of the member to the data
            $data['property_data']['memberId'] = $this->memberData->memberId;

            //Get GeoCode data
            $geoobj = new library_classes_LatLng(library_classes_LatLng::formatGeoAddress($data['property_data']));
            $data['property_data']['lat'] = $geoobj->lat;
            $data['property_data']['lng'] = $geoobj->lng;
            //Insert the property register
            $id_property = $model_property->insert($data['property_data']);

            rename($tmpFileName, $rvals[1].'p'.$id_property.'_'.$rvals[2]);
            
            //Insert the properties features in the hasfeature table
            $model_hasfeature = new library_models_hasfeature;
            foreach($data['features_data'] as $key=>$featureId){
                $model_hasfeature->insert(array('propertyId'=>$id_property,'featureId'=>$featureId));
            }
            //Report the success of the upload to the view
            $this->_flashMessenger->addMessage("The property: ".$data['property_data']['name'].", has been added successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."properties/member/list");        
       }
    }
    /*
     * Given an array (key,value) it returns an array whose key are the only defined as valid.
     */
    protected function filterPropertyFields(array $property){
        /* These are the valid name of the property on the database*/
        $valid_fields = array('name','description','address1','address2','city','state','zipcode','price','type','bedrooms','baths','photo');
        $return_property = array();
        $return_features = array();
        /* Filter out the valid names of the properties fields*/
        foreach($valid_fields as $i=>$field_name){
            if(isset($property[$field_name])){
                $return_property[$field_name] = $property[$field_name];
                unset($property[$field_name]);
            }
        }
        /* Return both pieces of information */
        return array('property_data'=>$return_property,'features_data'=>$property['features']);
    }
    /*
     *  Functionality for a member to Edit a property. (SF 2.3) 
     */
    public function editAction(){
    /*
     *  Functionality for a member to Delete a property. (SF 2.3) 
     */
       //Make sure the parameters is an integer
       $id_property = intval($this->_getParam("id"));
       //Make sure that the user is deleting one of his properties
       $this->checkPropertyId($id_property);
        /* Initialize the property form*/
       $form_property = new library_forms_PropertyEdit();
       $this->view->formProperty = $form_property;
       $model_property = new library_models_property;
       $property_data = $model_property->select()->where('propertyId = '.$id_property.' AND memberId = '.$this->memberData->memberId)->query()->fetchAll();
       /*
        * Fetch the property's features
        */
       $model_hasfeature = new library_models_hasfeature;
       $property_features = $model_hasfeature->select()->where('propertyId = '.$id_property)->query()->fetchAll();
       foreach($property_features as $i=>$feature){
           //Add the property's features to the data of the property so that it is displayed in the form
           $property_data[0]['features'][] = $feature['featureId'];
       }
        //$this->view->photoPath = $data['property_data']['photo'];
        $this->view->data = $property_data;

        //Set the properties values
       $form_property->setDefaults($property_data[0]);
       if ($this->getRequest()->isPost() && $form_property->isValid($_POST)) {


            $this->doPhotoUpload($property_data[0]['propertyId']);
    
            //Obtain form values
            $property = $form_property->getValues();
            //Obtain the properties' model
            $model_property = new library_models_property;
            //Filter the properties register to get property data and features data separately
            $data = $this->filterPropertyFields($property);

            //Update the property
            //Get GeoCode data
            $geoobj = new library_classes_LatLng(library_classes_LatLng::formatGeoAddress($data['property_data']));
            $data['property_data']['lat'] = $geoobj->lat;
            $data['property_data']['lng'] = $geoobj->lng;
            
            $model_property->update($data['property_data'],'propertyId = '.$id_property.' AND memberId = '.$this->memberData->memberId);
            //Update the features
            $model_hasfeature = new library_models_hasfeature;
            //Delete old features
            $model_hasfeature->delete('propertyId = '.$id_property);
            //Insert new features
            foreach($data['features_data'] as $key=>$featureId){
                $model_hasfeature->insert(array('propertyId'=>$id_property,'featureId'=>$featureId));
            }
            //Report the success of the property deletion to the view
            $this->_flashMessenger->addMessage("The property has been updated successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."properties/member/list");        

            
        }
    }
    public function deleteAction(){
       //Make sure the parameters is an integer
       $id_property = intval($this->_getParam("id"));
       //Make sure that the user is deleting one of his properties
       $this->checkPropertyId($id_property);
        /* Initialize the delete form*/
       $form_delete = new library_forms_Delete;
       $this->view->formDelete = $form_delete;
       if ($this->getRequest()->isPost() && $form_delete->isValid($_POST)) {
            //Delete the property
            $model_property = new library_models_property;
            $model_property->delete('propertyId = '.$id_property.' AND memberId = '.$this->memberData->memberId);
            //Report the success of the property deletion to the view
            $this->_flashMessenger->addMessage("The property has been deleted successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."properties/member/list");        
       }
    }
    /*
     *  Functionality to contact a member about a property. (SF 2.6) 
     */
    public function contactAction(){
        /*
         * Get Parameters
         */
        $property_id = intval($this->_getParam('id'));
        $this->view->propertyId = $property_id;
        $form_contact = new library_forms_Contact;
        $this->view->formContact = $form_contact;
        /*
         * Check the property exists
         */
        $property_model = new library_models_property;
        $property = $property_model->select()->where('propertyId = '.$property_id)->query()->fetchAll();
        if(!isset($property[0])){
            throw new Exception("The property with id ".$property_id.", does not exists");
        }else{
            $property = $property[0];
        }

        /*
         * Check that this property is not of this this member. Member cannot review their own property.
         */

        // Commented out - jay 11/29
        /*
        try{
            $this->checkPropertyId($property_id);
            throw new Exception("You cannot contact yourself!");
        }catch(library_classes_BTownException $e){
            //If the program reaches this portion, it means that this member does not own this property, so it is ok to continue
        }
        */
        
        /*
         * Check that this user has not already contacted this member about this property
         */
        $model_contact = new library_models_contact;
        $owner_id       =   $property['memberId'];
        $this_member    =   $this->memberData->memberId;
        $property_id    =   $property['propertyId'];
        
        $data = $model_contact->select()->where("senderId = ".$this_member." AND recipientId = ".$owner_id." AND propertyId = ".$property_id)->query()->fetchAll();
        if(isset($data[0])){
            throw new Exception("You already contacted this member about this property");
        }
        
       if ($this->getRequest()->isPost() && $form_contact->isValid($_POST)) {
           /*Member hitted sumbit button, save contact*/
            //Obtain form values
            $contact = $form_contact->getValues();
            //Save contact
            $model_contact->insert(array('senderId'     =>  $this_member,
                                         'recipientId'  =>  $owner_id,
                                         'propertyId'   =>  $property_id,
                                         'comment'      =>  $contact['comment']));
            //Report the success of the property deletion to the view
            $this->_flashMessenger->addMessage("Your message has been sent correctly!");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."properties/member/list");        
            
       }
        
    }   
    /*
     *  Functionality to review a property. (SF 2.8) 
     */
    public function reviewAction(){
        
        /*
         * Get Parameters
         */
        $property_id = intval($this->_getParam('id'));
        $this->view->propertyId = $property_id;
        $form_review = new library_forms_Review;
        $this->view->formReview = $form_review;
        /*
         * Check that the property exists
         */
        $property_model = new library_models_property;
        $property = $property_model->select()->where('propertyId = '.$property_id)->query()->fetchAll();
        if(!isset($property[0])){
            throw new Exception("The property with id ".$property_id.", does not exists");
        }else{
            $property = $property[0];
        }        
        /*
         * Check that this property is not of this this member. Member cannot review their own property.
         */
        
        // Commented out - Jay 11/29
        /*
        try{
            $this->checkPropertyId($property_id);
            throw new Exception("You cannot review your own property");
        }catch(library_classes_BTownException $e){
            //If the program reaches this portion, it means that this member does not own this property, so it is ok to continue
        }         
         */

        /*
         * Check that this user has not already reviewed this property
         */
        $model_review = new library_models_review;
        $data = $model_review->select()->where("memberId = ".$this->memberData->memberId." AND propertyId = ".$property_id)->query()->fetchAll();
        if(isset($data[0])){
            throw new Exception("You already reviewed this property");
        }
        if ($this->getRequest()->isPost() && $form_review->isValid($_POST)) {
            //Obtain form values
            $review = $form_review->getValues();
            //Attach memerId and propertyId to the form's data
            $review['memberId']     =   $this->memberData->memberId;
            $review['propertyId']   =   $property_id; 
            //Filter the message 
            $review['comment'] = library_classes_BadWordFilter::Filter($review['comment']);
            $review['comment'] = $review['comment']['filtered_message'];
            //Insert the Revie
            $model_review->insert($review);
            //Report the success of the review
            $this->_flashMessenger->addMessage("Your review has been sent correctly!");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."properties/member/list");                    
       }
    }
    
    private function doPhotoUpload($propertyId) {
        if(!$_FILES['photo']['name']) return '';
        
        // Set upload destination
        $uploadDir = Zend_Registry::get("APP_PATH") . 'photos/';                            
        $thumbnailDir = Zend_Registry::get("APP_PATH") . 'thumbnails/';                            

        $upload = new Zend_File_Transfer_Adapter_Http();
        $upload->setDestination($uploadDir);

        // Rename uploaded file using Zend Framework
        $name = $_FILES['photo']['name'];
        $renameFile = 'p' . $propertyId . '_'.$name;
        $fullFilePath = $uploadDir . $renameFile;
        //$fullFilePath = $uploadDir . $name;
        $filterFileRename = new Zend_Filter_File_Rename(array('target' => $fullFilePath, 'overwrite' => true));
        $filterFileRename->filter($fullFilePath);
        $upload->addFilter($filterFileRename);
        
        try {
            // upload received file
            $upload->receive();
        } catch (Zend_File_Transfer_Exception $e) {
            $e->getMessage();
        }
        /*

        // Resize image & make the thumbnail
        $filterImageSize = new ZendX_Filter_ImageSize();
        $output = $filterImageSize->setHeight(400) 
            ->setWidth(400)
            ->setOverwriteMode(ZendX_Filter_ImageSize::OVERWRITE_ALL)
            ->setThumnailDirectory($thumbnailDir)
            ->setType('jpeg')
            ->filter($filterFileRename);             

        var_dump($fullFilePath, $filterFileRename);
        */
        
        return array($fullFilePath, $uploadDir, $name);
    }
    
}