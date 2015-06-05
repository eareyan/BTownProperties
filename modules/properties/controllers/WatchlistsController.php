<?php

/**
 * This Controller handles the properties' watchlist related logic.
 *
 * @author enriqueareyan & mercerjd
 */
class Properties_WatchlistsController extends modules_default_controllers_BaseController{
    public function init(){
        //Call parent init function
        parent::init();
        //All of the actions of this controller requires that the user be logged in
        parent::checkRequireLogin();
    }
    /*
     * Given a property id, we check if it exists in the database.
     * If it does not exists, throws an exception
     */
    protected function checkPropertyExists($property_id){
        //Make sure the property exists
        $model_property = new library_models_property;
        $property = $model_property->select()->where('propertyId = '.$property_id)->query()->fetchAll();
        if(!isset($property[0])){
            throw new Exception('The property '.$property_id.', does not exists');
        }
    }
    /*
     *  Functionality to add a property to a watchlist (SF 2.7) 
     */     
    public function addAction(){
        //Get the property id that we want to add to the watchlist
        $property_id = intval($this->_getParam('id'));
        $this->view->property_id = $property_id;
        //Make sure the property exists
        $this->checkPropertyExists($property_id);        
        /* add it to the watchlist */
        //initialize model
        $model_watchlistproperties = new library_models_watchesproperty;
        //make sure the property is not already in the watch list
        $result = $model_watchlistproperties->select()->where('memberId = '.$this->memberData->memberId.' AND propertyId = '.$property_id)->query()->fetchAll();
        if(isset($result[0])){
            //Report the success of the property deletion to the view
            $this->_flashMessenger->addMessage("The property is already in your watchlist");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL")."properties/watchlists/property");                            
        }
        //add property
        $model_watchlistproperties->insert(array('memberId'=>$this->memberData->memberId,'propertyId'=>$property_id));
        //Report the success of the property deletion to the view
        $this->_flashMessenger->addMessage("The property has been added to your watchlist successfully");
        $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL")."properties/watchlists/property");                
    }
    /*
     *  Functionality to remove a property to a watchlist (SF 2.7) 
     */     
    public function removeAction(){
        $property_id = intval($this->_getParam('id'));
        //Make sure the property exists
        $this->checkPropertyExists($property_id);         
        //Remove from watchlist     
        $model_watchlistproperties = new library_models_watchesproperty;
        $row = $model_watchlistproperties->delete('memberId = '.$this->memberData->memberId.' AND propertyId = '.$property_id);
        if($row > 0){
            //Report the success of the property deletion to the view
            $this->_flashMessenger->addMessage("The property has been successfully removed  from your watchlist ");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL")."properties/watchlists/property");        
        }
    }

    /*
     * View property watchlist
     */
    public function propertyAction(){
        //Find the id of the properties being watched
        $memberId = $this->memberData->memberId;
        $sql =  "SELECT * FROM Property p, WatchesProperty w
                 WHERE p.propertyId=w.propertyId AND w.memberId='$memberId'";
        //Check if the user wants a particular order
        if(!is_null($this->_getParam('order'))){
            $sql .= " ORDER BY ".$this->_getParam('order');
        }          

        $propertyList = Zend_Registry::get('DB')->fetchAll($sql);       
        $this->view->result = Zend_Paginator::factory($propertyList);

        /*Set the paginator number*/
        $this->view->result->setCurrentPageNumber($this->_getParam('page'));        
    }

    /*
     * View feature watchlist
     */
    public function featureAction(){
        //Get member Id
        $memberId = $this->memberData->memberId;
        //Initialize featrue watchlist form
        $featureWatchlistForm = new library_forms_WatchFeatures();
        $this->view->form = $featureWatchlistForm;
        //See if the user has clicked submit
        if ($this->getRequest()->isPost() && $featureWatchlistForm->isValid($_POST)) {
            /* Save the register to the database*/
            //Obtain form values
            $features = $featureWatchlistForm->getValues();
            //Obtain the properties' model
            $model_watchesfeature = new library_models_watchesfeature;
            //Add the id of the member to the data
            $features['memberId'] = $memberId;
            //Insert the property register
            $model_watchesfeature->delete('memberId = '.$memberId);
            //Foreach feature just added
            foreach($features['features'] as $feature) {
                $model_watchesfeature->insert(array('memberId'=>$memberId, 'featureId'=>$feature));
            }
            //Report the success of the upload to the view
            $this->_flashMessenger->addMessage("The features have been updated successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."properties/watchlists/feature");                    
        }
        //Find the id of the properties being watched
        $memberId = $this->memberData->memberId;
        $sql =  "SELECT p.* FROM Property p, WatchesFeature w, HasFeature h
                 WHERE p.propertyId=h.propertyId AND w.featureId=h.featureId AND w.memberId='$memberId'";
        //Check if the user wants a particular order
        if(!is_null($this->_getParam('order'))){
            $sql .= " ORDER BY ".$this->_getParam('order');
        }         
        $propertyList = Zend_Registry::get('DB')->fetchAll($sql);       
        $this->view->result = Zend_Paginator::factory($propertyList);
        /*Set the paginator number*/
        $this->view->result->setCurrentPageNumber($this->_getParam('page'));        
    }
}