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
class Ezy_View_Helper_EzyInfo extends Zend_View_Helper_Abstract {
    //put your code here
    public function ezyInfo($header = null, array $messages = array()){
        
        $output = '<div class="knob_info"><span class="knob_close" title="Close"></span>';
        $output .= $header == NULL ? '' : $header;
        if(!empty($messages)){
            $output .= '<ul class="ezy_info">';
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
