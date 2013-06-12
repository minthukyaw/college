<?php

class IndexController extends Zend_Controller_Action {

    public function init() {
        /* Initialize action controller here */
        $this->view->indexactive = 'active';
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

        if ($this->_request->isPost()) {
            
            $authAdapter = new Zend_Auth_Adapter_DbTable(Zend_Db_Table::getDefaultAdapter(), 'user', 'email', 'password'); // second is indentity and 3rd is credential
            $authAdapter->setIdentity($_POST['email']);
            $authAdapter->setCredential($_POST['password']);

            $auth = Zend_Auth::getInstance(); // getinstance will give an object
            $result = $auth->authenticate($authAdapter);
            if ($result->isValid()) {
                //log in successful
                $data = $authAdapter->getResultRowObject(null, 'password');
                $auth->getStorage()->write($data);
                $this->_redirect('/member/profile');
            } else {
                $this->view->fail = 'invalid credential';
            }           
        }
    }

    public function logoutAction() {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect('/index/signin');
    }

    public function signupAction() {
        
        if ($_POST['fname']) {
            
            $fname = mysql_real_escape_string($_POST['fname']);
            $lname = mysql_real_escape_string($_POST['lname']);
            $email = mysql_real_escape_string($_POST['email']);
            $password = mysql_real_escape_string($_POST['password']);
            $confirmpassword = mysql_real_escape_string($_POST['conpassword']);
            
            $username = array($fname,$lname);
            $username = implode(" ", $username);
            
            $signup = new My_Validation();
            $valemail = $signup->validateemail($email);
            $valfname = $signup->validateusername($fname);
            $vallname = $signup->validateusername($lname);
            $valpassword = $signup->validatepassword($password, $confirmpassword);
            $checkemail = $signup->checkemail($email);
            
            
            if ($valemail && $valfname && $vallname && $valpassword && $checkemail) {

                $user = College_Model_User::getNewInstance(); // call to the STATIC method of getNewIntance, which is defined in parent class
                //now album is an object, whose properties are table column names in db
                $user->password = $password;
                $user->first_name = $fname;
                $user->last_name = $lname;
                $user->email = $email;
                $user->save(); //call to this method to save 
                $id = $user->id;
                
                $send_mail = new My_sendMail();              
                $code = $send_mail->gen_random(); 
                $encode = md5($code);
                $redirect = 'http://college/index/verify?code='.$encode.'&email='.$email.'&id='.$id;
                $send_mail->send_confirm_code($email,$redirect,$encode);
                
                
                $param = array('username'=>$username);
                $this->_helper->redirector('thanks','index',$param);
                
            }else{
                $this->view->fname = $fname;
                $this->view->lname = $lname;
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
    
    public function verifyAction() {
        $email = $_GET['email'];
        $code  = $_GET['code'];
        $id    = $_GET['id'];
        
        $db = College_Model_Verify::getDefaultAdapter();
        $get_check = $db->select()
                ->from('verify','id')
                ->where('email=?',$email)
                ->where('code=?',$code);                       
        $checkrequest = $db->fetchRow($get_check);
        $checkrequest = ($checkrequest['id']);
        
        if($checkrequest != NULL){
            $user = College_Model_User::getInstance($id);
            $user->status = 1;
            $user->save();
            
            $delete = College_Model_Verify::getInstance($checkrequest);
            $delete->delete();
            
            $this->autologinAction($id);
            $this->_redirect('/member/profile');           
            
        }else{
            $this->view->fail = "Sorry! We Don't See Any Record Of Your Registration :(";
        }
        
    }
    
    public function thanksAction() {
        
        $username = $this->_getParam('username');       
        $this->view->username = $username;
    }
    
    public function autologinAction($id){
        
        $auth = Zend_Auth::getInstance();
        $storage = $auth->getStorage();
        $user = College_Model_User::getInstance($id);
        $storage->write($user);     
    }

}



