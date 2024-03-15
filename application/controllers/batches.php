<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Batches extends CI_Controller
{

	public function index(){
		{
        $this->load->helper('xcrud');
        $xcrud = xcrud_get_instance();
        $xcrud->table('batches');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"]);
        
        $xcrud->show_primary_ai_field(false);
        $xcrud->column_name('class_id','Class')->column_name('name','Batch')->column_name('academic_year_id','Academic Year');

        $xcrud->relation('class_id','classes','id','name');
        $xcrud->relation('academic_year_id','academic_years','id','name');
        $xcrud->label('class_id','Class')->label('name','Batch Name')->label('academic_year_id','Academic Year');
        $xcrud->columns('batches.name,class_id,name,academic_year_id,start_date,end_date');
        $xcrud->fields('name,class_id,academic_year_id,start_date,end_date');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->table_name('Batches');
        

        
        
		

        $data["table"] = $xcrud->render();
        

        $this->load->view('batches/index',$data);

        
       
    }


	}



	}