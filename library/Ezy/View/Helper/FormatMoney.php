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
class Ezy_View_Helper_FormatMoney extends Zend_View_Helper_Abstract{
    //put your code here

    public function formatMoney($str, $default = 'n.a.', $decimals = 2, $dec_point = '.', $thousands_sep = ','){
        if(!is_numeric($str)){
            return $default;
        }
        else
        {
            if($str >= 0)
                return '$'.$this->view->escape(number_format($str, $decimals, $dec_point, $thousands_sep));
            else
                return '-$'.$this->view->escape(number_format(abs($str), $decimals, $dec_point, $thousands_sep));
        }
    }
}
?>
