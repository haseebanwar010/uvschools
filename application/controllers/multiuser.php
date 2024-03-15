<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class multiuser extends CI_Controller
{
	
	public function index(){
		
        $this->load->view('detailedpage/multiuser');
       // echo "multiuser"
		
	}
	public function announcements(){
		
        $this->load->view('detailedpage/announcements');
       // echo "multiuser"
		
	}
	public function dbookshop(){
		
        $this->load->view('detailedpage/dbookshop');
       // echo "multiuser"
		
	}
	public function class_batches(){
		
        $this->load->view('detailedpage/class_batches');
       // echo "multiuser"
		
	}
	public function daccounts(){
		
        $this->load->view('detailedpage/daccounts');
       // echo "multiuser"
		
	}
	public function dattendance(){
		
        $this->load->view('detailedpage/dattendance');
       // echo "multiuser"
		
	}
	public function dexamination(){
		
        $this->load->view('detailedpage/dexamination');
       // echo "multiuser"
		
	}
	public function dtimetable(){
		
        $this->load->view('detailedpage/dtimetable');
       // echo "multiuser"
		
	}
	public function fee_collection(){
		
        $this->load->view('detailedpage/fee_collection');
       // echo "multiuser"
		
	}
	public function messaging(){
		
        $this->load->view('detailedpage/messaging');
       // echo "multiuser"
		
	}
	public function multilanguage(){
		
        $this->load->view('detailedpage/multilanguage');
       // echo "multiuser"
		
	}
	public function onlineadmission(){
		
        $this->load->view('detailedpage/onlineadmission');
       // echo "multiuser"
		
	}
	public function payroll(){
		
        $this->load->view('detailedpage/payroll');
       // echo "multiuser"
		
	}
	public function dreports(){
		
        $this->load->view('detailedpage/dreports');
       // echo "multiuser"
		
	}
	public function studymaterial(){
		
        $this->load->view('detailedpage/studymaterial');
       // echo "multiuser"
		
	}
	public function studyplan(){
		
        $this->load->view('detailedpage/studyplan');
       // echo "multiuser"
		
	}
	public function dtrash(){
		
        $this->load->view('detailedpage/dtrash');
       // echo "multiuser"
		
	}
	public function user_managment(){
		
        $this->load->view('detailedpage/user_managment');
       // echo "multiuser"
		
	}
	public function app_store(){
		
        $this->load->view('detailedpage/app_store');
       // echo "multiuser"
		
	}
	




	public function demo(){
		$this->load->view('uv-landing/index');
	}
	
}