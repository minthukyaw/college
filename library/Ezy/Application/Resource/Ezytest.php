<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Ezytest
 *
 * @author kthant
 */
class Ezy_Application_Resource_Ezytest extends Zend_Application_Resource_ResourceAbstract {
    //put your code here
    public function init()
    {
        $options = $this->getOptions();
        if(isset($options['settings']) && is_array($options['settings'])){
            Ezy_Test::setSettings($options['settings']);
            Ezy_Test_Browser::setSettings($options['settings']);
        }
    }
}
?>
