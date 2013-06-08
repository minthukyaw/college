<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Util
 *
 * @author kthant
 */
class Ezy_Util {
    //put your code here
    /*
     * Perform like jQuery's extend functionality
     * @param - default array values
     * @param - options array values
     */
    public static function extend(array $default, array $options){

        foreach($default as $key => $value){
            if(is_numeric($key) && !in_array($value, $options)){
                $options[] = $value;
            }
            else if(!isset($options[$key])){
                $options[$key] = $value;
            }
            else if(is_array($value) && is_array($options[$key])){
                //Array key already exists in options and the current value is array, then check for children down there recursively
                $options[$key] = self::extend($value, $options[$key]);
            }
        }

        return $options;
    }
}
?>
