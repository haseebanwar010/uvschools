<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -  
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function index()
    {
        $this->load->helper('xcrud');
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_departments');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where("deleted_at",0)->where("role_id",EMPLOYEE_ROLE_ID);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('role_id, name');
        $xcrud->relation('role_id','sh_roles','id','name');
        $xcrud->fields('role_id, name');
        $xcrud->table_name('Departments');
        //$xcrud->unset_list();
        
        echo $xcrud->render();
        
        //$this->load->view('welcome_message', $data);
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
