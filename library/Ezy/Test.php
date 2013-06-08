<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Test
 *
 * @author kthant
 */
abstract class Ezy_Test extends Zend_Test_PHPUnit_ControllerTestCase{
    //put your code here
    private static $_settings = array();

    public static function setSettings(array $settings){
        self::$_settings = $settings;
    }

    protected function get($key){
        if(isset(self::$_settings[$key])){
            return self::$_settings[$key];
        }
        return false;
    }
    
}
?>
