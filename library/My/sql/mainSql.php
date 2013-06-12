<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of mainSql
 *
 * @author Minthukyaw
 */
class My_sql_mainSql {
    
    public function listposts(){
        
        $db = Zend_Db_Table::getDefaultAdapter(); 
        $sql = "SELECT wall.poster,wall.post,wall.created,user.first_name,user.last_name,user.profile_pic
                FROM wall,user
                WHERE wall.poster = user.id and poster = wall_owner
                Order by created DESC";
        $result = $db->fetchAll($sql);
        return $result;
    }
}


