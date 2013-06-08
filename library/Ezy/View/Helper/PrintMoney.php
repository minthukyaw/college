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
class Ezy_View_Helper_PrintMoney extends Zend_View_Helper_Abstract{
    //put your code here

    public function printMoney($str, $default = 'n.a.', $decimals = 2, $dec_point = '.', $thousands_sep = ','){
        if(!is_numeric($str)){
            echo $default;
        }
        else
        {
            if($str >= 0)
                echo '$'.$this->view->escape(number_format($str, $decimals, $dec_point, $thousands_sep));
            else
                echo '-$'.$this->view->escape(number_format(abs($str), $decimals, $dec_point, $thousands_sep));
        }
    }
}
?>
