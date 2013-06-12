<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TableFactory
 *
 * @author Minthukyaw
 */
class My_TableFactory {
    //put your code here
    
    public static function getTable($tableName, array $config){
        $tbl = new Zend_Db_Table($tableName, new Zend_Db_Table_Definition(
                    array($tableName => $config)
               ));
        
        return $tbl;
    }
}

