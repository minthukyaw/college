<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of PrintString
 *
 * @author kthant
 */
class Ezy_View_Helper_PrintString extends Zend_View_Helper_Abstract{
    //put your code here

    public function printString($str, $default = 'n.a.'){
        if(empty($str)){
            echo $default;
        }
        else
            echo $this->view->escape($str);
    }
}
?>
