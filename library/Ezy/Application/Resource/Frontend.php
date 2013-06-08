<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Layout
 *
 * @author kthant
 */
class Ezy_Application_Resource_Frontend extends Zend_Application_Resource_Layout{
    //put your code here

    public function init()
    {
        $this->getBootstrap()->bootstrap('view');
        $options = $this->getOptions();
        if(!isset($options['theme'])){
            throw new Zend_Application_Resource_Exception('No theme name is supplied');
        }
        
        $options['layoutPath'] = APPLICATION_PATH . "/../public/themes/".$options['theme'];
        if(!file_exists($options['layoutPath'])){
            throw new Zend_Application_Resource_Exception('Theme given is not found');
        }

        $this->setOptions($options);
        if(!defined('THEME_BASE_URL'))
            define('THEME_BASE_URL', '/themes/'.$options['theme']);

        $view = $this->getLayout()->getView();
        $view->frontend($this->getOptions());

        if(file_exists($options['layoutPath'].'/views'))
        {
            $view->setScriptPath(APPLICATION_PATH.'/views/scripts');
            $view->addScriptPath($options['layoutPath'].'/views');
        }
        //now call to Zend layout resource plugin
         return parent::init();
    }
    
}
?>
