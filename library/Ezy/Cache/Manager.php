<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Manager
 *
 * @author kthant
 */
class Ezy_Cache_Manager extends  Zend_Cache_Manager {
    //put your code here

    private static $_instance = null; //singleton instance
    
    public static function getInstance(){
        return self::$_instance;
    }
    
    public static function setInstance(Zend_Cache_Manager $manager)
    {
        self::$_instance = $manager;
    }
}
?>
