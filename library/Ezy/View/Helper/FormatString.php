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
class Ezy_View_Helper_FormatString extends Zend_View_Helper_Abstract{
    //put your code here

    public function formatString($str, $default = 'n.a.'){
        if(empty($str)){
            return $default;
        }
        else
            return $this->view->escape($str);
    }
}
?>
