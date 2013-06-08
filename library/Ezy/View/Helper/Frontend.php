<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Frontend
 *
 * @author kthant
 */
class Ezy_View_Helper_Frontend extends Zend_View_Helper_Abstract {
    //put your code here
    private static $_options = array();
    private static $_default = array('distribution' => array(
                                    'method' => 'round_robin',
                                    'oscillation' => 10
                               ));

    private static $_registers = array();
    
    
    public function frontend(array $options = null){

        if($options != null){
            self::$_options = Ezy_Util::extend(self::$_default, $options);
            $servers = self::$_options['servers'];
            self::$_options['servers'] = array();
            foreach($servers as $key => $server){
                self::$_options['servers'][] = $server;
            }
            self::$_options['server_count'] = count(self::$_options['servers']);

            if(isset(self::$_options['headTitle'])){
                $this->view->headTitle(self::$_options['headTitle']);
            }
        }
        return $this;
    }

    public function url(){
        $serverIndex = $this->{self::$_options['distribution']['method']}();
        return self::$_options['servers'][$serverIndex];
    }

    public function themeUrl(){
        return $this->url().'/themes/'.self::$_options['theme'];
    }

    public function getJsUrl(){
        return $this->url().'/js';
    }

    private function round_robin(){
        if(!isset(self::$_registers['round_robin'])){
            self::$_registers['round_robin'] = array('count' => 0);
        }

        $serverIndex = (int) (self::$_registers['round_robin']['count'] / self::$_options['distribution']['oscillation']);
        $serverIndex = $serverIndex % self::$_options['server_count'];
        self::$_registers['round_robin']['count']++;
        return $serverIndex;
    }

    private function plain(){
        return 0;
    }
}
?>
