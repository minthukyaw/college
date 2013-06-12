<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of getUserInfo
 *
 * @author Minthukyaw
 */
class My_getUserInfo {
    
    
    public function login_userinfo(){
         
        $auth = Zend_Auth::getInstance();
        $this->user_record = $auth->getIdentity();
        $userinfo = $this->user_record;
        return $userinfo;
         
    }

    public function my_username(){
        
        $auth = Zend_Auth::getInstance();
        $this->user_record = $auth->getIdentity();   
        $fname = $this->user_record->first_name;
        $lname = $this->user_record->last_name;
        $username = array($fname,$lname);
        $username = implode(" ", $username);
        return $username;
    }
    
    public function get_otherinfo($id){
      
        $db = Zend_Db_Table::getDefaultAdapter();
        $select = $db->select()
                ->from('user',array('first_name','last_name','email','id','profile_pic'))
                ->where('id=?',$id);
        
        $stmt = $select->query();
        $result = $stmt->fetchAll();
        return $result;
                
    }
    
    public function get_otherusername($id){
        
        $user_info = $this->get_otherinfo($id);
        $fname = ($user_info[0]['first_name']);
        $lname = ($user_info[0]['last_name']);        
        $username = array($fname,$lname);
        $username = implode(" ", $username);
        return $username;
        
    }
    
}


