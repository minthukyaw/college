<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of member_sql
 *
 * @author Minthukyaw
 */
class My_sql_memberSql {
    
    public function status_post($poster,$wall_owner,$status_post){
        $time = time();
        $db = Zend_Db_Table::getDefaultAdapter();        
        $data = array(
        'created'      => $time,
        'poster' => $poster,
        'wall_owner'      => $wall_owner,
        'post' => $status_post
        );
     
        $db->insert('wall', $data);
    }
    
    public function getProfilePost($id){
        
        $db = Zend_Db_Table::getDefaultAdapter();
        
        $query = "SELECT u.profile_pic, u.first_name, u.last_name, u.id,owner.first_name ownerfname, owner.last_name ownerlname, owner.profile_pic ownerprofilepic, wall.poster, wall.post, wall.created, wall.wall_owner 
                  FROM wall join user as u On wall.poster = u.id and wall_owner = $id 
                  JOIN user as owner on wall.wall_owner=owner.id 
                  ORDER by created DESC";
        
        $result = $db->fetchAll($query);
            
        return $result;
    }
    
    public function setprofileimageinfo($id,$pro_img_url,$user_id){
        
       $tblUser = My_TableFactory::getTable('user', array(
           'primary' => 'id'
       ));
       
       
       $result = $tblUser->find($user_id);
       if($result->count() == 1){
           $userRecord = $result->current();
           $userRecord->profile_pic = $pro_img_url;
           $userRecord->profile_pic_id = $id;
           $userRecord->save();
       }
       
    }
    
    public function getmyinfo($user_id){
        $db = Zend_Db_Table::getDefaultAdapter();
        $sql = $db->select()
                ->from('user')
                ->columns('*')
                ->where('id=?',$user_id);
        
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll();
        return $result;
    }
    
    
}