<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{

    protected function initxxx(){
        parent::init();

        // Set up some values for easy access
        Zend_Registry::set("BASEPATH", '/cgi-pub/mercerjd/');
        
        $resource = $this->getPluginResource('db');
        $db = $resource->getDbAdapter();
        Zend_Registry::set("database", $db);        
    }

    protected function _initRequest()
    {
		$autoloader = Zend_Loader_Autoloader::getInstance();
		/*Se registra el namespace modules para poder tener modulos*/
		$autoloader->registerNamespace('modules_');
		$autoloader->registerNamespace('library_');
		
		/*Configurar el registry*/
		$registry = new Zend_Registry(array());
		Zend_Registry::setInstance($registry);
		
		/*Configurar la sesion*/
		$session_namespace = new Zend_Session_Namespace('STT_PaginaWeb_Session');
		$session_namespace->setExpirationSeconds(3600); //expira en 1 hora
		Zend_Registry::set('session', $session_namespace);
		
		/* Utilizar el nombre controls.phtml como defecto para el control del paginador. El paginador sera tipo google por defecto */
		Zend_View_Helper_PaginationControl::setDefaultViewPartial('controls.phtml');
		Zend_Paginator::setDefaultScrollingStyle('Elastic');
		Zend_Paginator::setDefaultItemCountPerPage(10);	
    }
    
    protected function _initDatabase() {
        // Set up some values for easy access
        Zend_Registry::set("ROOTPATH", '/cgi-pub/mercerjd/');
        Zend_Registry::set("ROOTURL", 'http://www.cs.indiana.edu/cgi-pub/mercerjd/');
        Zend_Registry::set("CSSPATH", '/cgi-pub/mercerjd/css/');
        Zend_Registry::set("JSPATH", '/cgi-pub/mercerjd/js/');
        Zend_Registry::set("APP_PATH", '/l/cgi/mercerjd/cgi-pub/');
        Zend_Registry::set("DEBUG", true);
        
        $resource = $this->getPluginResource('db');
        $db = $resource->getDbAdapter();
        Zend_Registry::set("DB", $db);        
        return $db;
    }
}
