<?php

/**
 * This Controller handles the logic related to showing the documentation of the system.
 *
 * @author enriqueareyan
 */
class DocumentationController extends modules_default_controllers_BaseController{
    /*
     * Dummy, empty index action.
     */     
    public function indexAction(){}
    /*
     * Given a name of o file, received by parameters, this function checks
     * whethear the file actually exists and download it.
     */      
    public function downloadAction(){
        //These are the files that are downloadable.
        $pathToDocs = RAIZ. "/docs";
        $param_to_file = array( "milestone1"                => $pathToDocs . "/milestone 1/group13-milestone1.pdf",
                                "milestone2"                => $pathToDocs . "/milestone 2/group13-milestone2.pdf",
                                "milestone2-revision1"      => $pathToDocs . "/milestone 2/group13-milestone2-comments.pdf",
                                "milestone2-presentation"   => $pathToDocs . "/milestone 2/group13-milestone2-presentation.pptx");
        $file = $param_to_file[$this->_getParam('file')];
        //If we are being ask for a file that is not in the previous array, throw an Exception.
        if(is_null($file)){
            throw new Exception("Unknown file");
        }
        //Disable view and layout
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        //The file is in the previous array. So, fetch it and force the download.
        if (file_exists($file) && is_file($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($file));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            ob_clean();
            flush();
            readfile($file);
            exit;
        }else{
            echo "<pre style='color:red'>The file does not exists</pre>";
        }    
    }
}