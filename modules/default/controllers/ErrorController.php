<?php
/**
 * This Controller take care of any Exception that might be thrown by the application
 *
 * @author enriqueareyan
 */
class ErrorController extends modules_default_controllers_BaseController{
    /*
     * This action is automatically called when an exception is thrown but not cached 
     * in any other part of the system.
     */    
    public function errorAction(){
        //Call parent init function
        parent::init();
        //Get Error handler
        $errors = $this->_getParam('error_handler');
        $exception = $errors->exception;
        if(Zend_Registry::get('DEBUG')){
            echo "<pre>";print_r($exception);echo "</pre>";
        }
        //$request=$this->getRequest();
        //$this->view->module = $request->getModuleName();
        //Check the type of error that ocurred and show a message accordingly.
        switch ($errors->type) {
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            $this->view->Mensaje = 'This page or controller does not exists';
            break;
        case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
            $this->view->Mensaje = 'This page or action does not exists';
            break;
        default:
            $this->view->Mensaje = $exception->getMessage();
        }
    }
}