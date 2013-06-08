<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
    }

    public function indexAction() {

        $form = new Zend_Form('POST');

        $form->addElement('text', 'first_name', array(
            'Label' => 'First Name',
            'Required' => true
        ));

        $form->addElement('text', 'last_name', array(
            'Label' => 'Last Name',
            'Required' => true
        ));

        $form->addElement('text', 'username', array(
            'Label' => 'User Name',
            'Required' => true
        ));

        $form->addElement('password', 'password', array(
            'Label' => 'Password',
            'Required' => true,
        ));

        $form->addElement('text', 'email', array(
            'Label' => 'Email Address',
            'Required' => true,
        ));

        //you need to understand how SELECT html field works when form is submitted
        $form->addElement('submit', 'Add');

        if ($this->_request->isPost()) {




            if ($form->isValid($_POST)) {
//nothing here when the form is valid,


                $user = Tuberation_Model_User::getNewInstance(); // call to the STATIC method of getNewIntance, which is defined in parent class
                //now album is an object, whose properties are table column names in db
                $user->first_name = $_POST['first_name'];
                $user->last_name = $_POST['last_name'];
                $user->password = $_POST['password'];
                $user->user_name = $_POST['username'];
                $user->email = $_POST['email'];
                $user->save(); //call to this method to save 
                $this->_redirect('/index/signin');
            } else {


                $form->populate($_POST);
            }
        }


        $this->view->signup_form = $form;
    }

    public function signinAction() {

        $form = new Zend_Form('POST');

        $form->addElement('text', 'user_name', array(
            'Label' => 'User Name',
            'Required' => true
        ));

        $form->addElement('password', 'password', array(
            'Label' => 'Password',
            'Required' => true
        ));

        $form->addElement('submit', 'Add');



        if ($this->_request->isPost()) {


            if ($form->isValid($_POST)) {


                $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter(), 'user', 'user_name', 'password'); // second is indentity and 3rd is credential

                $authAdapter->setIdentity($_POST['user_name']);
                $authAdapter->setCredential($_POST['password']);

                $auth = Zend_Auth::getInstance(); // getinstance will give an object
                $result = $auth->authenticate($authAdapter);
                if ($result->isValid()) {
                    //log in successful
                    $data = $authAdapter->getResultRowObject(null, 'password');
                    $auth->getStorage()->write($data);

                    $this->_redirect('/member');
                } else {
                    $this->view->fail = true;
                }
            }
        }
        $this->view->signin = $form;
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect('/index/signin');
    }

    public function signupAction() {
        
        if ($_POST['username']) {
            
            $username = mysql_real_escape_string($_POST['username']);
            $email = mysql_real_escape_string($_POST['email']);
            $password = mysql_real_escape_string($_POST['password']);
            $confirmpassword = mysql_real_escape_string($_POST['conpassword']);
            
            $signup = new My_Validation();
            $valemail = $signup->validateemail($email);
            $valusername = $signup->validateusername($username);
            $valpassword = $signup->validatepassword($password, $confirmpassword);
            $checkemail = $signup->checkemail($email);
            
            
            if ($valemail && $valusername && $valpassword && $checkemail) {

                $user = College_Model_User::getNewInstance(); // call to the STATIC method of getNewIntance, which is defined in parent class
                //now album is an object, whose properties are table column names in db
                $user->password = $password;
                $user->username = $username;
                $user->email = $email;
                $user->save(); //call to this method to save 
                
                $send_mail = new My_sendMail();              
                $code = $send_mail->gen_random(); 
                $encode = md5($email);
                $redirect = 'http://college/index/verify?code='.$code;
                $send_mail->send_confirm_code($code, $email,$redirect);
                
                $param = array('username'=>$username,'encode'=>$encode);
                $this->redirect('/index/verify?=');
                $this->_helper->redirector('verify','index',$param);
                
            }else{
                $this->view->username = $username;
                $this->view->email = $email;
                $this->view->password = $password;
                $this->view->conpassword = $confirmpassword;
                $this->view->error_msg = $signup->data;
                //echo json_encode($signup->data);
                //var_dump($signup->data);
                //exit();
                
            }
        }//if post
    }
    
    public function verifyAction(){
        fsdf;
    }

}



