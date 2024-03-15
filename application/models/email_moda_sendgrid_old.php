<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Email_modal extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->library('email');
    }

    /////////// Common Email Function Using Sendgrid credentials ///////////////
    public function emailSend($userEmail, $message, $subject, $link = NULL) {
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

}
