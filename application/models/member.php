<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of member
 *
 * @author Minthukyaw
 */
class Application_Model_DbTable_Member extends Zend_Db_Table_Abstract
{
    
    
    
    public function status_post($poster,$post_owner,$status_post){
        $db = Zend_Db_Table::getDefaultAdapter(); 
        $post_query = "INSERT INTO post (poster,post_owner,post,time) VALUES ($poster,$post_owner,$status_post,now());";
        $result = mysql_query($count_query);       
        
    }
}