<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Validation
 *
 * @author Minthukyaw
 */
class My_Validation {

    public $data = array();
    
    public function validateemail($email) {
        
        $validator = new Zend_Validate_EmailAddress();
        $isvalid = TRUE;

        if ($validator->isValid($email)) {
            $allowed = array('baruchmail.cuny.edu');

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $domain = array_pop(explode('@', $email));
                if (!in_array($domain, $allowed)) {
                    $this->data[] = "only baruchmail is allowed";
                    $isvalid = FALSE;
                } else {
                    $isvalid = TRUE;
                }
            }
        } else {
            // email is invalid; print the reasons
            $this->data[] = 'enter a valid email, dude!';
            $isvalid = FALSE;
        }

        return $isvalid;
    }

    public function validateusername($username) {
        $isvalid = TRUE;
        //$valid = preg_match('/[A-Z][a-z]\d/', $username);
        if (strlen($username) >= 2) {
            $isvalid = TRUE;
        }else{
            $this->data[]='dude, your name is that short?';
            $isvalid = FALSE;
        }
        return $isvalid;
    }

    public function validatepassword($password, $compassword) {

        $isvalid = TRUE;
        if ($password == $compassword) {

            if (strlen($password) < 8) {
                $this->data[]='man!password should not be less than 8';
                $isvalid = FALSE;
            }
        }else{
            $this->data[]='confirm your password, dude! passwords should be same';
            $isvalid = FALSE;
        }

        return $isvalid;
    }
    
    public function checkemail($email){
        
        $isvalid = true;
        $albums = new College_Model_User();
        
        $a = $albums->search2('email', array('email = ? ' => $email));
        if($a['email']['email'] == $email){
            $this->data[]='this email address is already registered';
            $isvalid = FALSE;
        }
        return $isvalid;
    }
}
