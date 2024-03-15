<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Forms extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        check_user_permissions();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login/index"));
        }
    }
    
    public function all(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $sql = "SELECT temp.*,cat.name as form_category, cat.tag as form_category_type FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.school_id='$school_id' AND temp.deleted_at IS NULL ";
        $data["templates"] = $this->admin_model->dbQuery($sql);
        $this->load->view("forms/view",$data);
    }
    
    public function show(){
        $id = $_GET["id"];
        $sql = "SELECT temp.*,cat.name as form_category FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id=cat.id WHERE temp.id='$id' ";
        $data["template"] = $this->admin_model->dbQuery($sql)[0];
        
        
        //$data["form_name"] = "abc";
        //$data["form_category"] = "def";
              
        
        
        $rendered_form_html = render_universal_tags($data["template"],$data["template"]->html);
        
        $data["html"] =  $rendered_form_html;
                
        //$data["template"] = $this->admin_model->dbSelect("*","templates"," id='$id' ")[0];
        $this->load->view("forms/show",$data);
    }
    
    public function create(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["formCategories"] = $this->admin_model->dbSelect("*","form_categories"," school_id='$school_id' AND deleted_at IS NULL AND tag IS NULL ");
        $this->load->view("forms/form",$data);
    }
    
    public function save(){
        $title = $this->input->post("title");
        $form_category_id = $this->input->post("category_id");
        $html = $this->input->post("html");
        
        $data = array("name"=>$title,"is_custom"=>'No',"html"=>$html,"school_id"=>$this->session->userdata('userdata')['sh_id'],"form_category_id"=>$form_category_id);
        
        $res = $this->common_model->insert('sh_templates', $data);
        if($res){
            $this->session->set_flashdata('alert', array("status" => "success", "message" => "New form template created successfully."));
            redirect("forms/all","refresh");
        }
    }
    
    public function category_create(){
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_form_categories');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name');
        $xcrud->fields('name');
        $xcrud->label('name', lang('lbl_name'));
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->replace_remove('form_category_delete');
        $xcrud->table_name(lang('lbl_form_categories'));
        $xcrud->load_view("view", "customview.php");
        
        
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_title();
        $data["form_categories"] = $xcrud->render();
        $this->load->view("forms/categories",$data);
    }
    
    public function delete(){
        $id = $this->input->post("id");
        $record = $this->admin_model->dbSelect("*","templates"," id='$id' ")[0];
        if($record->is_custom === 'Yes'){
            $this->session->set_flashdata('alert', array("status" => "danger", "message" => "System template you con't delete."));
        }else{
            $this->common_model->update_where("sh_templates", array("id" => $id), array("deleted_at" => date("Y-m-d h:i:s")));
            $this->session->set_flashdata('alert', array("status" => "success", "message" => "Form template deleted successfully."));
        }
        echo "success";
    }
    
    public function edit(){
        $id = $_GET["id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["formCategories"] = $this->admin_model->dbSelect("*","form_categories"," school_id='$school_id' AND deleted_at IS NULL AND tag IS NULL ");
        
        $sql = "SELECT temp.*,cat.name as form_category, cat.tag as form_category_type FROM sh_templates temp INNER JOIN sh_form_categories cat ON temp.form_category_id = cat.id WHERE temp.id='$id'";
        $data["template"] = $this->admin_model->dbQuery($sql)[0]; 
        //$data["template"] = $this->admin_model->dbSelect("*","templates"," id='$id' ")[0];
        $this->load->view("forms/edit",$data);
    }
    
    public function update(){
        if($this->input->post("type") != 'is_system'){
            $id = $this->input->post("id");
            $title = $this->input->post("title");
            $category_id = $this->input->post("category_id");
            $html = $this->input->post("html");

            $data = array("name"=>$title,"form_category_id"=>$category_id,"html"=>$html);
            $res = $this->common_model->update_where("sh_templates",array("id"=>$id),$data);
            if($res){
                $this->session->set_flashdata('alert', array("status" => "success", "message" => "Form templated updated successfully"));
                redirect("forms/all","refresh");
            }
        } else {
            $this->session->set_flashdata('alert', array("status" => "danger", "message" => "System category form you could not edit."));
            redirect("forms/all","refresh");
        }
    }
}