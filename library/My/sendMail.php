<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Send_Mail
 *
 * @author Minthukyaw
 */
class My_sendMail {
    
    public function send_confirm_code($code,$email,$redirect){
        
        $tr = new Zend_Mail_Transport_Smtp('smtp.gmail.com', array(
	'auth' => 'login',
	'username' => 'minthukyaw@gmail.com',
	'password' => 'bullshit0363',
	'ssl' => 'ssl',
	'port' => 465)
        );
        
        Zend_Mail::setDefaultTransport($tr);
        
        $sql = College_Model_User::getInstance();
        
        
        
        $message = "<h5>Thank for registering. Click the following link to verify your account</h5>
                    <p>".$redirect."</p>
                    <p> your confirmation code is:".$code."</p>";
        $mail = new Zend_Mail();
        $mail->setBodyHtml($code);
        $mail->addTo($email);
        $mail->setSubject("confirmation Code");
        $mail->setFrom('minthukyaw@gmail.com');
        $mail->send();
    }
    
    public function gen_random(){
        $random_string_length = 5;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $string = '';
        for ($i = 0; $i < $random_string_length; $i++) {
            $string .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $string;
    }
    
    
    
}

?>
