<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Page
 *
 * @author kthant
 */
class Ezy_Page extends Zend_Controller_Action {
    //put your code here
    public function init(){
        $this->view->errors = array();
    }
    
    public function addCssFile($url, $media = 'all', $conditionalStylesheet = null, $extras = array()){
        $url = parse_url($url);
        if(!isset($url['host']))
        {
            $url = $this->view->frontend()->url().'/'.ltrim($url['path'], '/');
        }
        
        $this->view->headLink()->appendStylesheet($url, $media, $conditionalStylesheet, $extras);
    }

    public function redirect($action, $controller = null, $module = null, $params = array()){
        $this->_helper->redirector($action, $controller, $module, $params);
    }

    public function setTitle($title){
        $this->view->headTitle($title);
    }
}
?>
