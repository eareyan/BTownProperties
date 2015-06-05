<?php

/**
 * This Controller handles all the member's related logic.
 *
 * @author enriqueareyan & mercerjd
 */
class Members_IndexController extends modules_default_controllers_BaseController {
    /*
     *  Functionality for Visitor Sign-Up. (SF 2.1) 
     */
    public function signupAction(){ 
        //Initialize the login form
        $formMember = new library_forms_Member();
        //Pass the form to the view
        $this->view->formMember = $formMember;    
        //check to see if the user is trying to login
        if($this->getRequest()->isPost() && $formMember->isValid($_POST)) {   
            /* Save the register to the database*/
            //Obtain form values
            $member = $formMember->getValues();
            echo "<pre style='color:green'>"; print_r($member);echo "</pre>";
            $model_member = new library_models_member;
            //Insert the property register
            $id_member = $model_member->insert($member); 
            $this->_flashMessenger->addMessage("Welcome ".$member['fname'].", you have been signed up!... Now you can <a href='".Zend_Registry::get("ROOTURL")."members/index/login'>login</a> into your private account...");
            $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL"));            
        }     
    }
    /*
     *  Functionality for Visitor Login. (SF 2.2) 
     */
    public function loginAction(){
        //Initialize the login form
        $formLogin = new library_forms_Login();
        //Pass the form to the view
        $this->view->formLogin = $formLogin;    
        //check to see if the user is trying to login
        if($this->getRequest()->isPost() && $formLogin->isValid($_POST)) {
            //Get the database
            $dbAdapter = $this->getInvokeArg('bootstrap')->getResource('db');
            //Set up the auth object
            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
            $authAdapter->setTableName('Member')
                        ->setIdentityColumn('email')
                        ->setCredentialColumn('password')
                        ->setIdentity($formLogin->getValue('username'))
                        ->setCredential($formLogin->getValue('password'));
            $result = $authAdapter->authenticate();
            //See if the login is valid
            if($result->isValid()){
                //Get the object with the user information
                $user_data = $authAdapter->getResultRowObject();                
		$session = Zend_Registry::get('session');
                //Set the isLoggedIn flag to true in the session
		$session->isLoggedIn = true;
 
                //Set the isAdmin flag based on Admin table                
                $sql = "SELECT * FROM Admin WHERE memberId=".$user_data->memberId;
                $result = Zend_Registry::get('DB')->fetchAll($sql);                
                $session->isAdmin = (count($result)>0);

                //Store the memeber data for later use
		$session->memberData = $user_data;
                $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL"));
                    
                
                /*
                 * This is just for debugging purposes
                 */
                //echo "LOGIN IS VALID";
                //echo "<pre>"; print_r($user_data); echo "</pre>";
            }else{
                echo "<pre style='color:red'>LOGIN IS NOT VALID</pre>";
            }
        }
    }
    /*
     *  Functionality for Visitor Logout. (SF 2.2) 
     */
    public function logoutAction(){
        /*
         * We  won't need a view for logout. Instead, this function will destroy 
         * the session and redirect to the home page with a logout message.
         */
        //Disable view and layout
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        //Logout the user, meaning, destroy the session
        Zend_Session::destroy(true);
        //got to the index and report the logout
        $this->_helper->getHelper('Redirector')->gotoUrl(Zend_Registry::get("ROOTURL")."index/index/logout/1");        
    }
    /*
     *  Functionality for viewing a member's properties.
     */
    public function viewpropertiesAction(){}    
    
}