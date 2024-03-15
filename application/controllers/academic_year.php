<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Academic_year extends CI_Controller
{

	public function index(){
		{
        $this->load->helper('xcrud');
        $xcrud = xcrud_get_instance();
        $xcrud->table('academic_years');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        $xcrud->relation('id','sh_schools','school_id','academic_years');
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name, start_date, end_date');
        $xcrud->fields('name, start_date, end_date');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->table_name('Academic Year');
        //$xcrud->unset_list();
        //$xcrud->load_view('create', "my_custom_create_form.php"); 
        $data["table"] = $xcrud->render();
        $this->load->view('academic_year/index',$data);
        //$this->load->view('welcome_message', $data);
    }


	}



	}