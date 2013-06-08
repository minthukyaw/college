<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cachemanager
 *
 * @author kthant
 */
class Ezy_Application_Resource_Cachemanager extends Zend_Application_Resource_Cachemanager{
    //put your code here
     /**
     * Initialize Cache_Manager
     *
     * @return Zend_Cache_Manager
     */
    public function init()
    {
        $manager = $this->getCacheManager();
        Ezy_Cache_Manager::setInstance($manager);

        if(isset($_GET['CLEAR_CACHE']) && $_GET['allowme'] == 1){
            $cache = Ezy_Cache_Manager::getInstance()->getCache($_GET['CLEAR_CACHE']);
            $cache->clean(Zend_Cache::CLEANING_MODE_ALL);
        }
        
        return $manager;
    }
}
?>
