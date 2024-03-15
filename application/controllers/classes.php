<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Classes extends CI_Controller
{

	public function index(){
		{
        $this->load->helper('xcrud');
        $xcrud = xcrud_get_instance();
        $xcrud->table('classes');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"]);
        $xcrud->relation('id','sh_schools','school_id','classes');
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name, code, grading_type');
        $xcrud->fields('name, code, grading_type');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->table_name('Classes');
        //$xcrud->unset_list();

        
        
		//$xcrud->load_view('create', "my_custom_create_form.php"); 

        $data["table"] = $xcrud->render();
        

        $this->load->view('classes/index',$data);

        
        //$this->load->view('welcome_message', $data);
    }


	}



	}