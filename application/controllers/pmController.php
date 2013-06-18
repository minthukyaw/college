<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pm
 *
 * @author Minthukyaw
 */

class pmController extends Zend_Controller_Action {

    public function inboxAction(){
        
        $user = new My_getUserInfo();
        $userinfo = $user->login_userinfo();
        $user_id = $userinfo->id;
        $username = $user->my_username();
        $data = array();
        $this->view->username = $username; 
        
        $count = new My_sql_pmsql();
        $count = $count->getInboxCount($user_id);
         
        if ($count > 0) {

            $targetpage = "/pm/inbox?page=%d";
            $adjacents = 3;
            $limit = 1;
            $lastpage = ceil($count / $limit);

            $page = empty($_GET['page']) ? 1 : $_GET['page'];
            $start = ($page - 1) * $limit;
            $this->view->targetpage = $targetpage;
            $this->view->lastpage = $lastpage;
            $this->view->page = $page;
            $message = new My_sql_pmsql();
            $this->view->result = $message->getInboxMessages($start, $limit, $user_id);
            $this->view->count = $count;
            $sss = new My_pagination();
            $aaa = $sss->pagination($page, $lastpage, $targetpage);
            $this->view->pagination = $aaa;
        }  else {
            $this->view->mesage = "You don't have any message yet!";
        }
        
    }
    
    public function newmessagesendAction(){
        if($_POST){
        
            $title = mysql_real_escape_string($_POST['title']);
        $body  = mysql_real_escape_string($_POST['post']);
        $receip_id = $_POST['receip_id'];
        
        $user = new My_getUserInfo();
        $user = $user->login_userinfo();
        $user_id = $user->id;
        
        $message = new My_sql_pmsql();
        $message->newMessageSend($user_id,$title,$body,$receip_id);
        
        echo json_encode(array('success'=>'your message had sent successfully'));
        exit();
        }
       
    }
    
    public function sentAction(){
        
        $user = new My_getUserInfo();
        $userinfo = $user->login_userinfo();
        $user_id = $userinfo->id;
        $data = array();
        $username = $user->my_username();
        $message = new My_sql_pmsql();
        $count = $message->getSentMessageCount($user_id);
        
        if ($count > 0) {
            $targetpage = "/pm/sent?page=%d";
            $adjacents = 3;
            $limit = 2;
            $lastpage = ceil($count / $limit);            
            $page = empty($_GET['page']) ? 1 : $_GET['page'];
            $start = ($page - 1) * $limit;
            
            $sentmessage = new My_sql_pmsql();
            $sent_list = $sentmessage->sentMessage($user_id, $start, $limit);
                       
            $thread_ids = array();
           // $sent_itmes = array();
            foreach ($sent_list as $row) {
                $thread_ids[] = $row['id'];
               // $sent_items[] = $row;
            };
            
            $is_read = $sentmessage->checkIsRead($thread_ids, $user_id);
            $is_reads = array();
            foreach ($is_read as $row) {
                $is_reads[$row['thread_id']] = (int) $row['status'];
            };
            
            $sss = new My_pagination();
            $aaa = $sss->pagination($page, $lastpage, $targetpage);
            $this->view->pagi = $aaa;
            $this->view->sentmessages = $sent_list;
            $this->view->targetpage = $targetpage;
            $this->view->page = $page;
            $this->view->lastpage= $lastpage;
            $this->view->is_read = $is_reads;
            $this->view->count = $count;
            $this->view->username = $username;
            
        }
        
    }
    
    public function messagereadAction(){
        $user = new My_getUserInfo();
        $user = $user->login_userinfo();
        $user_id = $user->id;       
        $thread_id = $_GET['id'];
        $this->view->thread_id = $thread_id;
        $message = new My_sql_pmsql();
        $read_message = $message->getmessageRead($thread_id);
        $this->view->read_message = $read_message;
        $updateMessageStatus = $message->updateMessageStatus($user_id, $thread_id);
        $paser = 1;
        $this->view->paser = $paser;
        
    }
    
    public function replymessageAction(){
        
        $user = new My_getUserInfo();
        $username = $user->my_username();
        $user_id = $user->login_userinfo();
        $user_id = $user_id->id;
        
        $post 		= ($_POST['post']);
        $post           = strip_tags($post);
        $thread_id      = ($_POST['id']);
        
	$message = new My_sql_pmsql();
        $message->replyMessage($user_id,$username,$post, $thread_id);
        $this->_redirect('pm/messageread?id='.$thread_id);
	
    }
    
    public function composemsgAction(){
        
    }
    
    public function toautoAction() {
        $db = Zend_Db_Table::getDefaultAdapter();
        $q = strtolower($_GET["term"]);
        $search = $db->select()->distinct();
        $search->from('user', array('first_name'))
                ->where("`first_name` Like ?", $q . '%');

        $result = $db->fetchAll($search);
        echo json_encode($result);
        exit();
    }

    
}
