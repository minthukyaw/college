<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MainController
 *
 * @author Minthukyaw
 */
class MainController extends Zend_Controller_Action {
    
    public function init() {
        /* Initialize action controller here */
        $this->view->mainactive = 'active';
    }
    
    public function listpostsAction(){
        $getposts = new My_sql_mainSql();
        $result = $getposts->listposts();      
        $this->view->getposts = $result;
    }
}

?>
