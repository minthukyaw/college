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
        $user_id = $_GET['user_id'];
        $username = $user->get_username();
        $data = array();
        $this->view->username = $username; 
        $total_count = $this->queryCount($user_id);
        $data['total_count'] = $total_count;

        if ($total_count > 0) {

            $targetpage = "/pm/inbox?page=%d";
            $adjacents = 3;
            $limit = 5;
            $lastpage = ceil($total_count / $limit);

            $page = empty($_GET['page']) ? 1 : $_GET['page'];
            $start = ($page - 1) * $limit;
            $data['targetpage'] = $targetpage;
            $data['lastpage'] = $lastpage;
            $data['page'] = $page;
            $data['result'] = $this->getInboxMessage($start, $limit, $user_id);
        }
        $this->load->view('/pm/inbox', $data);
    }
    
    public function queryCount($me){        
        $db = Zend_Db_Table::getDefaultAdapter();        
        $count_query = "SELECT COUNT(pm_threads.id) as TOTAL FROM pm_lookup  JOIN pm_threads ON
                        pm_threads.id = pm_lookup.thread_id and
                        pm_lookup.user_id = %d and
                        ((pm_threads.num_posts > 1) OR (pm_threads.num_posts = 1 AND pm_threads.creator <> %d))";
       
        $result = mysql_query(sprintf($count_query, $me, $me, $me));
        $count=  count($db->fetchAll($result));
        return $count;
    }
    
    public function getInboxMessage($start,$limit,$user_id) {
        
    }
}
