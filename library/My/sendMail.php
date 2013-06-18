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
    
    public function send_confirm_code($email,$redirect,$encode){
        
        $tr = new Zend_Mail_Transport_Smtp('smtp.gmail.com', array(
	'auth' => 'login',
	'username' => 'minthukyaw@gmail.com',
	'password' => 'bullshit0363',
	'ssl' => 'ssl',
	'port' => 465)
        );
        
        Zend_Mail::setDefaultTransport($tr);
              
        $message = "<h5>Thank for registering. Click the following link to verify your account</h5>
                    <a href=".$redirect.">".$redirect."</a>";
                    
        $mail = new Zend_Mail();
        $mail->setBodyHtml($message);
        $mail->addTo($email);
        $mail->setSubject("confirmation Code");
        $mail->setFrom('minthukyaw@gmail.com');
        $mail->send();
        
        $new_row = College_Model_Verify::getNewInstance();
        $new_row->email = $email;
        $new_row->code  = $encode;
        $new_row->save();
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
    
    public function pagination($page, $lastpage, $targetpage, $adjacents = 3) {
    $prev = $page - 1;
    $next = $page + 1;
    $lpm1 = $lastpage - 1;
    

    $pagination = "";
    if ($lastpage > 1) {
        $pagination .= "<div class=\"pagination \">";
        $pagination.="<ul>";
        //previous button
        if ($page > 1)
            $pagination.= "<a href=\"".sprintf ($targetpage,$prev)."\"> previous</a>";
        else
            $pagination.= "<span class=\"disabled current\"> previous</span>";

        //pages	
        if ($lastpage < 7 + ($adjacents * 2)) { //not enough pages to bother breaking it up
            for ($counter = 1; $counter <= $lastpage; $counter++) {
                if ($counter == $page)
                    $pagination.= "<span class=\"current\">$counter</span>";
                else
                    $pagination.= "<a href=\"".sprintf ($targetpage,$counter)."\">$counter</a>";
            }
        }
        elseif ($lastpage > 5 + ($adjacents * 2)) { //enough pages to hide some
            //close to beginning; only hide later pages
            if ($page < 1 + ($adjacents * 2)) {
                for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"".sprintf ($targetpage,$counter)."\">$counter</a>";
                }
                $pagination.= "<span>...</span>";
                $pagination.= "<a href=\"".sprintf ($targetpage,$lpm1)."\">$lpm1</a>";
                $pagination.= "<a href=\"".sprintf ($targetpage,$lastpage)."\">$lastpage</a>";
                
            }
            //in middle; hide some front and some back
            elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                $pagination.= "<a href=\"".sprintf ($targetpage,1)."\">1</a>";
                $pagination.= "<a href=\"".sprintf ($targetpage,2)."\">2</a>";
                $pagination.= "<span>...</span>";
                for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"".sprintf ($targetpage,$counter)."\">$counter</a>";
                }
                $pagination.= "<span>...</span>";
                $pagination.= "<a href=\"".sprintf ($targetpage,$lpm1)."\">$lpm1</a>";
                $pagination.= "<a href=\"".sprintf ($targetpage,$lastpage)."\">$lastpage</a>";
            }
            //close to end; only hide early pages
            else {
                $pagination.= "<a href=\"".sprintf ($targetpage,1)."\">1</a>";
                $pagination.= "<a href=\"".sprintf ($targetpage,2)."\">2</a>";
                $pagination.= "<span>...</span>";
                for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination.= "<span class=\"current\">$counter</span>";
                    else
                        $pagination.= "<a href=\"".sprintf ($targetpage,$counter)."\">$counter</a>";
                }
            }
        }

        //next button
        if ($page < $counter - 1)
            $pagination.= "<a href=\"".sprintf ($targetpage,$next)."\">next </a>";
        else
            $pagination.= "<span class=\"disabled current\">next </span>";
        $pagination.= "</div>\n";
    }
    
    return $pagination;
    }
    
}

?>
