<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_modal extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('email');
    }

    /////////// Common Email Function Using Sendgrid credentials ///////////////
    public function emailSendOld($userEmail, $message, $subject, $link = NULL) {
        require_once APPPATH . 'third_party/PHPMailer/PHPMailerAutoload.php';
        $to = $userEmail;
        $mail = new PHPMailer;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );

        /*$mail->isSMTP(TRUE);                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.sendgrid.net';                       // Specify main and backup server
        $mail->SMTPAuth = false;                               // Enable SMTP authentication
        $mail->SMTPDebug = 2;
        $mail->CharSet = 'UTF-8';
        $mail->Username = 'apikey';                   // SMTP username
        $mail->Password = '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';               // SMTP password
        //$mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->Port = 25;                                    //Set the SMTP port number - 587 for authenticated TLS
        //$mail->Port = 465;                                    //Set the SMTP port number - 587 for authenticated TLS
        $mail->setFrom('info@united-vision.com', 'United Vision');     //Set who the message is to be sent from
        $mail->addAddress($to);  // Add a recipient
        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;
        $json["status"] = true;
        $mail->send();*/
        
        $mail->isSMTP(TRUE);                                      // Set mailer to use SMTP
        $mail->Host = 'united-vision.net';                       // Specify main and backup server
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->SMTPDebug=0;       
        $mail->CharSet = 'UTF-8';
        $mail->Username = 'mirza.yasir@united-vision.net';                   // SMTP username
        $mail->Password = 'P@ssw0rd';               // SMTP password
        $mail->SMTPSecure = 'ssl';                            // Enable encryption, 'ssl' also accepted
        $mail->Port = 465;                                    //Set the SMTP port number - 587 for authenticated TLS
        $mail->setFrom('mirza.yasir@united-vision.net', 'United Vision');     //Set who the message is to be sent from
        $mail->addAddress($to);  // Add a recipient
        $mail->WordWrap = 50;                                 // Set word wrap to 50 characters
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body = $message;
        $mail->AltBody = $message;
        $json["status"] = true;
        $mail->send();
        
    }

    public function emailSend($userEmail, $message, $subject, $tag){
        if($this->session->userdata("userdata")){
            $data = array('r_email'=>$userEmail,
                        'tag'=>$tag,
                        'message'=>$message,
                        'subject'=>$subject,
                        'school_id'=>$this->session->userdata('userdata')['sh_id'],
                        'user_id'=>$this->session->userdata('userdata')['user_id']);
        }
        else{
            $data = array('r_email'=>$userEmail,
                        'tag'=>$tag,
                        'message'=>$message,
                        'subject'=>$subject,
                        'school_id'=>0,
                        'user_id'=>0);
        }
        
        $this->db->insert('sh_emails',$data);

        
    }

    public function testingEmail(){
        $this->load->helper('email');
        require APPPATH . 'third_party/sendgrid/vendor/autoload.php';

        $query = $this->db->select('*')->from('sh_emails')->where('r_email <>', '')->where_in('state',array('pending','error'))->get();
        $emails = $query->result();
       
        foreach ($emails as $email_detail) {
         
            if (valid_email($email_detail->r_email)){
                
                $email = new \SendGrid\Mail\Mail(); 
                $email->setFrom("uvschools-noreply@uvschools.com", "UVSchools");
                $email->setSubject($email_detail->subject);
                $email->addTo($email_detail->r_email);
                $email->addContent("text/plain", $email_detail->message);
                $email->addContent("text/html", $email_detail->message);
                $sendgrid = new \SendGrid('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');
    
                try {
                    $response = $sendgrid->send($email);
                    //print $response->statusCode() . "\n";
                    //print_r($response->headers());
                   //print $response->body() . "\n";
                    $this->db->where('id',$email_detail->id)->update('sh_emails',array('state'=>'sent', 'time_sent' => date("Y:m:d h:i:s")));
                } catch (Exception $e) {
                    $this->db->where('id',$email_detail->id)->update('sh_emails',array('state'=>'error', 'time_sent' => date("Y:m:d h:i:s")));
                    echo 'Caught exception: '. $e->getMessage() ."\n";
                }
            }
        }
    }

    public function sendEmails(){
        $this->load->helper('email');
        $url = 'https://sendgrid.com/api/mail.send.json';
        
        $query = $this->db->select('*')->from('sh_emails')->where('r_email <>', '')->where_in('state',array('pending','error'))->get();
        $emails = $query->result();
        
        foreach ($emails as $email) {
            
            if (valid_email($email->r_email)){
                $params = array(
                    'api_user'  => 'unitedvision',
                    'api_key'   => 'UvSendgrid@#3030',
                    'to'        => $email->r_email,
                    'subject'   => $email->subject,
                    'html'      => $email->message,
                    'text'      => $email->message,
                    'from'      => 'uvschools-noreply@uvschools.com',
                    'fromname'  => 'UVSchools'
                );
                $session = curl_init($url);
                // Tell curl to use HTTP POST
                curl_setopt ($session, CURLOPT_POST, true);
                // Tell curl that this is the body of the POST
                curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
                // Tell curl not to return headers, but do return the response
                curl_setopt($session, CURLOPT_HEADER, false);
                curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        
                // obtain response
                $response = curl_exec($session);
                
                curl_close($session);
                if(json_decode($response)->message=="success"){
                    $this->db->where('id',$email->id)->update('sh_emails',array('state'=>'sent', 'time_sent' => date("Y:m:d h:i:s")));
                }
                else{
                    $this->db->where('id',$email->id)->update('sh_emails',array('state'=>'error', 'time_sent' => date("Y:m:d h:i:s")));
                }
            }
        }
    
    }
    
    //// Additional Email Function For Testing ////
    public function emailSend2($email, $password, $link) {

        $this->load->library('email');

        $this->email->initialize(array(
            'protocol' => 'smtp',
            'smtp_host' => 'smtp.sendgrid.net',
            'smtp_user' => 'apikey',
            'smtp_pass' => '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',
            'smtp_port' => 25,
            'crlf' => "\r\n",
            'newline' => "\r\n"
        ));

        $this->email->from('info@united-vision.com', 'Shahzaib');
        $this->email->to('shahzaib.ch7.sc@gmail.com');
        //$this->email->cc('another@another-example.com');
        //$this->email->bcc('them@their-example.com');
        $this->email->subject('Email Test');
        $this->email->message('Testing the email class.');
        $this->email->send();

        echo $this->email->print_debugger();
    }

    // direct send for email for Recovery password
    public function sendEmailsRecovery($userEmail, $message, $subject, $tag){

        require APPPATH . 'third_party/sendgrid/vendor/autoload.php';
       
            $email = new \SendGrid\Mail\Mail(); 
            $email->setFrom("uvschools-noreply@uvschools.com", "UVSchools");
            $email->setSubject($subject);
            $email->addTo($userEmail);
            $email->addContent("text/plain", $message);
            $email->addContent("text/html", $message);
           
            $sendgrid = new \SendGrid('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');

            try {
                $response = $sendgrid->send($email);
              
               
            } catch (Exception $e) {
                
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
      
    }
       public function sendEmailsForTesting($userEmail, $message, $subject, $tag){

        require APPPATH . 'third_party/sendgrid/vendor/autoload.php';
       
            $email = new \SendGrid\Mail\Mail(); 
            $email->setFrom("uvschools-noreply@uvschools.com", "UVSchools");
            $email->setSubject($subject);
            $email->addTo($userEmail);
            $email->addContent("text/plain", $message);
            $email->addContent("text/html", $message);
           
            $sendgrid = new \SendGrid('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');

            try {
                $response = $sendgrid->send($email);
              
               
            } catch (Exception $e) {
                
                echo 'Caught exception: '. $e->getMessage() ."\n";
            }
      
    }
}
