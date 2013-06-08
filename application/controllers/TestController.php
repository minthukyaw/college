<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestController
 *
 * @author Minthukyaw
 */
class TestController extends Zend_Controller_Action {
    //put your code here
    
    public function indexAction(){
        
    }
    
    public function helloAction(){
        
         $this->view->name = "Min";
    }
}

?>
