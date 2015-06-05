<?php
/**
 * Index of the application
 *
 * @author enriqueareyan & mercerjd
 */
class IndexController extends modules_default_controllers_BaseController{
    /*
     *  Application's Index.
     */
    public function indexAction(){

        /*
         * Check if we need to show the logout message
         */
        if(!is_null($this->_getParam("logout"))){
            $this->view->loggedOut = intval($this->_getParam("logout"));
        }
        /*
         * In the index we are just going to show all the properties 
         */
        $options = array();
        if(!is_null($this->_getParam("order"))){
            $options['order'] = $this->_getParam("order");
        }
        $this->view->paginator = Zend_Paginator::factory(library_classes_SearchProperties::search($options)); 
        $this->view->paginator->setCurrentPageNumber($this->_getParam('page'));
        $this->checkRequireLogin($forceLogin = false);
        if(isset($this->memberData)){
            $this->view->DisplayPropertiesOptions = array('showWatchlist'=>true);
        }
    }
    /*
     * About Us
     */
    public function aboutusAction(){
        
    }

    /*
     * Admin
     */
    public function manageAction(){        
        
    }
    
    /*** FEATURES **********************************************/

    public function managefeaturesAction(){
        $featureForm = new library_forms_Feature();
        $this->view->form = $featureForm;
        
        $model_feature = new library_models_feature;
        $features = $model_feature->fetchAll();        

        if($this->getRequest()->isPost() && $featureForm->isValid($_POST)) {
            $vals = $featureForm->getValues();
            $name = $vals['name'];
            $data = $model_feature->select()->where("name = '$name'")->query()->fetchAll();

            if(count($data)> 0) {
                $this->_flashMessenger->addMessage("The feature already exists");
                $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL").'index/managefeatures');                       
            }
            else {
                $model_feature->insert(array('name'=>$name));
                $this->_flashMessenger->addMessage("The feature has been added successfully");
                $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."index/managefeatures");                      
            }
        }
                
        $model_feature = new library_models_feature;
        $features = $model_feature->fetchAll();        
        
        $this->view->paginator = Zend_Paginator::factory($features); 
        $this->view->paginator->setCurrentPageNumber($this->_getParam('page'));
    }
    
    public function addfeatureAction(){        
        
    }
    
    public function deletefeatureAction(){        
       //Make sure the parameters is an integer
       $id = intval($this->_getParam("id"));

       /* Initialize the delete form*/
       $form_delete = new library_forms_Delete;
       $this->view->formDelete = $form_delete;
       if ($this->getRequest()->isPost() && $form_delete->isValid($_POST)) {
            //Delete the feature
            $model_feature = new library_models_feature;
            $model_feature->delete('featureId = '.$id);
            
            // We also need to update HasFeature table
            $model = new library_models_hasfeature;
            $model->delete('featureId = '.$id);            
            
            //Report the success of the property deletion to the view
            $this->_flashMessenger->addMessage("The feature has been deleted successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."index/managefeatures");        
       }
    }
    
    /*** MEMBERS **********************************************/

    public function managemembersAction(){        
        if($this->getRequest()->isPost() && $memberForm->isValid($_POST)) {
            //var_dump($_POST);            
        }
                
        $model_member = new library_models_member;
        $members = $model_member->fetchAll();        
        
        $this->view->paginator = Zend_Paginator::factory($members); 
        $this->view->paginator->setCurrentPageNumber($this->_getParam('page'));
    }    
    
    public function editmemberAction(){        
        //Make sure the parameters is an integer
        $id = intval($this->_getParam("id"));

        // Set up the form
        $memberForm = new library_forms_EditMember();

        // Fill in default data from table
        $model_member = new library_models_member;
        $data = $model_member->select()->where('memberId = '.$id)->query()->fetchAll();

        // Find out if is admin
        $model_admin = new library_models_admin;
        $records = $model_admin->select()->where('memberId = '.$id)->query()->fetchAll();
        $isManager = (count($records) > 0);
        
        $memberForm->setDefaults($data[0]);
        if($isManager) $memberForm->manager->setValue(1);
        
        $this->view->form = $memberForm; 

        if($this->getRequest()->isPost() && $memberForm->isValid($_POST)) {
            
            /* Save the register to the database*/
            //Obtain form values
            $member = $memberForm->getValues();
            $isManager = ($member['manager']==1);

            echo "<pre style='color:green'>"; print_r($member);echo "</pre>";

            unset($member['manager']); 
            
            //Update the property register
            $model_member->update($member,'memberId='.$id); 

            $model_admin->delete('memberId = '.$id);
            if($isManager) $model_admin->insert(array('memberId'=>$id)); 
            
           $this->_flashMessenger->addMessage("The member has been updated successfully");
           $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL").'index/managemembers');            
        }
    }

    public function deletememberAction(){        
       //Make sure the parameters is an integer
       $id_property = intval($this->_getParam("id"));

       /* Initialize the delete form*/
       $form_delete = new library_forms_Delete;
       $this->view->formDelete = $form_delete;
       if ($this->getRequest()->isPost() && $form_delete->isValid($_POST)) {
            //Delete the member
            $model_member = new library_models_member;
            $model_member->delete('memberId = '.$id_property);
            //Report the success of the property deletion to the view
            $this->_flashMessenger->addMessage("The member has been deleted successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."index/managelandmarks");        
       }
    }
    
    /*** LANDMARKS **********************************************/

    public function managelandmarksAction(){        
        $landmarkForm = new library_forms_Landmark();
        $this->view->form = $landmarkForm;

        if($this->getRequest()->isPost() && $landmarkForm->isValid($_POST)) {
            /* Save the register to the database*/
            //Obtain form values
            $landmark = $landmarkForm->getValues();
            $geoobj = new library_classes_LatLng(library_classes_LatLng::formatGeoAddress($landmark));

            $vals = array();
            $vals['name'] = $landmark['name'];
            $vals['description'] = $landmark['name'];
            $vals['lat'] = $geoobj->lat;
            $vals['lng'] = $geoobj->lng;
            
            echo "<pre style='color:green'>"; print_r($vals);echo "</pre>";
            $model_landmark = new library_models_landmark;
            //Insert the property register
            $id_landmark = $model_landmark->insert($vals); 

            $this->_flashMessenger->addMessage("The landmark has been added successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL").'index/managelandmarks');            
        }
                
        $model_landmark = new library_models_landmark;
        $landmarks = $model_landmark->fetchAll();        
        
        $this->view->paginator = Zend_Paginator::factory($landmarks); 
        $this->view->paginator->setCurrentPageNumber($this->_getParam('page'));
    }

    public function editlandmarkAction(){        
        //Make sure the parameters is an integer
        $id = intval($this->_getParam("id"));

        // Set up the form
        $landmarkForm = new library_forms_EditLandmark();

        // Fill in default data from table
        $model_landmark = new library_models_landmark;
        $data = $model_landmark->select()->where('landmarkId = '.$id)->query()->fetchAll();
        $landmarkForm->setDefaults($data[0]);
        $this->view->form = $landmarkForm; 
        
        if($this->getRequest()->isPost() && $landmarkForm->isValid($_POST)) {
            /* Save the register to the database*/
            //Obtain form values
            $landmark = $landmarkForm->getValues();
            $landmark['description'] = $landmark['name'] ;
            echo "<pre style='color:green'>"; print_r($landmark);echo "</pre>";

            //Insert the property register
            $id_landmark = $model_landmark->update($landmark,'landmarkId='.$id); 

            $this->_flashMessenger->addMessage("The landmark has been updated successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL").'index/managelandmarks');            
        }        
    }

    public function deletelandmarkAction(){
       //Make sure the parameters is an integer
       $id_property = intval($this->_getParam("id"));

       /* Initialize the delete form*/
       $form_delete = new library_forms_Delete;
       $this->view->formDelete = $form_delete;
       if ($this->getRequest()->isPost() && $form_delete->isValid($_POST)) {
            //Delete the landmark
            $model_landmark = new library_models_landmark;
            $model_landmark->delete('landmarkId = '.$id_property);
            //Report the success of the property deletion to the view
            $this->_flashMessenger->addMessage("The landmark has been deleted successfully");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get('ROOTURL')."index/manage");        
       }        
    }
    
}
