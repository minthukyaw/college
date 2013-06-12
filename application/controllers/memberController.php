<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of memberController
 *
 * @author Minthukyaw
 */
class memberController extends Zend_Controller_Action {

    private $user_record;

    public function init() {
        $this->view->memberactive = 'active';
        parent::init();
    }

    public function profileAction() {

        $user = new My_getUserInfo();
        $userinfo = $user->login_userinfo();
        $id = $_GET['id'];
        if (!empty($id)) {
            if ($userinfo->id === $id) {
                $this->myProfile();
                
            } else {
                $this->otherProfile($id);
                
            }
        } else {
            $this->myProfile();
        }
    }

    public function mystatusAction() {

        $status_post = mysql_real_escape_string($_POST['status_post']);
        $receip_id = $_POST['wall_owner'];
        
        if (!empty($status_post)) {
            $user = new My_getUserInfo();
            $userinfo = $user->login_userinfo();
            $poster = $userinfo->id;
            $wall_owner = $receip_id;
            $post = new My_sql_memberSql();
            $post->status_post($poster, $wall_owner, $status_post);
            $this->_redirect('/member/profile?id=' . $receip_id);
        }
    }

    public function myProfile() {

        $user = new My_getUserInfo();
        $sql = new My_sql_memberSql();
        $username = $user->my_username();
        $userinfo = $user->login_userinfo();
        $status = $sql->getProfilePost($userinfo->id);
        $myinfo = $sql->getmyinfo($userinfo->id);
        
        $this->view->info = $myinfo;
        $this->view->myinfo = $userinfo;
        $this->view->myname = $username;
        $this->view->mystatus_post = $status;       
        $this->_helper->viewRenderer->setRender('/myprofile');
    }

    public function otherProfile() {
        
        $user = new My_getUserInfo();
        $status = new My_sql_memberSql();
        $id = $_GET['id'];
        $userinfo = $user->get_Otherinfo($id);

        $username = $user->get_otherusername($id);
        $status = $status->getProfilePost($id);
        
        $this->view->userinfo = $userinfo;
        $this->view->username = $username;
        $this->view->status_post = $status;
        $this->_helper->viewRenderer->setRender('/profile');
    }
    
    public function uploadprofileimageAction(){
        $user = new My_getUserInfo();
        $userinfo = $user->login_userinfo();
        $user_id = $userinfo->id;
        $temp = $_FILES["pro_img"]["tmp_name"];
        $upload_picture = new My_uploadPhoto();
        list($id,$pro_img_url) = $upload_picture->uploadToFlickr($temp);
        $insert = new My_sql_memberSql();
        $insert->setprofileimageinfo($id,$pro_img_url,$user_id);
        $this->_redirect('/member/profile');
    }    

}

