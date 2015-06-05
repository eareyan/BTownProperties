<?php
/**
 * This is the Base Controller of the application. 
 * All other Controllers must extend this Controller.
 *
 * @author enriqueareyan & mercerjd
 */
class modules_default_controllers_BaseController extends Zend_Controller_Action{    

    /*
     *  Initialization function, gets called by all other controllers.
     */    
    public function init(){
        //Load most common view Helpers
        $this->_flashMessenger = $this->_helper->FlashMessenger;
        $this->view->messages = $this->_flashMessenger;	
        //Add new routes for views
        $this->view->addScriptPath(LIB.'/library/view/helper/');
        $this->view->addHelperPath('library/view/helper/', 'library_view_helper');
        //Make URL mapping accesible
        $this->getURLMapping();

        $this->_helper->layout->setLayout('layout');        
        
        
        
    }
    /*
     * This procedure checks whether the user has logged in. If the optional flag  
     * $forceLogin is true (which it is by default), it redirects to the
     * index controller in case the user has not logged in. It will also give 
     * an appropiate error message
     */
    public function checkRequireLogin($forceLogin = true){
        //Get Session
        $session= Zend_Registry::get('session');
        //Check whether the user is logged in	
        if(!$session->isLoggedIn && $forceLogin){
            //If the user is not log in, give him a message and redirect him
            $this->_flashMessenger->addMessage("In order to access this page you need to log into the system");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL"));
        }else{
            //There is a session, so put the data of the member where it is accesible
            $this->memberData = $session->memberData;            
        }
    }
    /*
     * Given a property id, check if it belong to the user currently conected
     */
    public function checkPropertyId($propertyId){
        /* 
         * Check to see if the member is logged in. If it is not, throw an exception.
         * If it is logedin, then check if he owns the propertyId. If he doesn't,
         * throw an exception
         */
        $session= Zend_Registry::get('session');
        if(isset($session->memberData->memberId)){
            //Initialize the property model
            $model_property = new library_models_property;
            //Try to fetch the property id belonging to this member
            $property = $model_property->select()->where('propertyId = '.$propertyId.' AND memberId = '.$session->memberData->memberId)->query()->fetchAll();
            //If the query does not returns something, then it means this person does not owns this property
            if(empty($property)){
                throw new Exception('You cannot modify this property');
            }
        }else{
            throw new Exception('You cannot modify this property.');
        }
    }
    /*
     * Dump data of a variable object
     */
    public function dump($o) {
        echo '<pre>' . print_r($o, true) . '</pre>';
    }
    /*
     * This function determins the module, controller and action name
     * and make them accesible for the view.
     */
    public function getURLMapping(){
        $front_Controller = $this->getFrontController();
        $URL_Mapping = array('module_name' => $front_Controller->getRequest()->getModuleName(),
                             'contro_name' => $front_Controller->getRequest()->getControllerName(),
                             'action_name' => $front_Controller->getRequest()->getActionName());
        $this->view->URL_Mapping = $URL_Mapping;        
    }
}