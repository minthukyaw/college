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
class Ezy_View_Helper_Error extends Zend_View_Helper_Abstract {
    //put your code here
    public function error($msg = null){
        if($msg == null){
            if(is_array($this->view->errors) && !empty($this->view->errors)){
                echo '<ul class="ezy_error">';
                foreach($this->view->errors as $error){
                    echo '<li>'.$error.'</li>';
                }
                echo '</ul>';
            }
        }
        else{
            echo '<p class="ezy_error">'.$msg.'</p>';
        }
    }
}
?>
