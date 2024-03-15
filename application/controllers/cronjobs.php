<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Cronjobs extends CI_Controller {

	function __construct() {
		parent::__construct();
	}

	public function sendEmails(){
		//$this->email_modal->sendEmails();
		$this->email_modal->testingEmail();
	}

	public function testingEmail(){
		$this->email_modal->testingEmail();
	}
	
}