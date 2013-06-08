<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EzyPage
 *
 * @author kthant
 */
class Ezy_View_Helper_EzyWarning extends Zend_View_Helper_Abstract {
    //put your code here
    public function ezyWarning($header = null, array $messages = array()){
        
        $output = '<div class="knob_warning"><span class="knob_close" title="Close"></span>';
        $output .= $header == NULL ? '' : $header;
        if(!empty($messages)){
            $output .= '<ul class="ezy_warning">';
            foreach($messages as $msg){
                $output .= '<li>'.$msg.'</li>';
            }

            $output .= '</ul>';
        }
        $output .= '</div>';
        return $output;
    }
}
?>
