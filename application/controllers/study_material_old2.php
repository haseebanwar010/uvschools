<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Study_material extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login/index"));
        }
        check_user_permissions();
    }

    function upload() {
        $this->load->view('study_material/upload');
    }

    function download() {
        $this->load->view('study_material/download');
    }

    function getSubjects() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $batch_id = $request->section;

        $response["subjects"] = $this->study_model->getSubjects($class_id, $batch_id);
        echo json_encode($response);
    }

    function getMaterials() {

        //-------------------------
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_batches.id IN (". implode(',', login_user()->t_data->batches) .") ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 

        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){

        } else if(login_user()->user->role_id == PARENT_ROLE_ID){

        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){

        }
        //-------------------------

        $query = "SELECT "
        . "sh_study_material.*,"
        . "sh_classes.name as class_name,"
        . "sh_batches.name as batch_name,"
        . "sh_subjects.name as subject_name, "
        . "date_format(sh_study_material.created_at,'%d/%m/%Y') as uploaded_time "
        . "From sh_study_material "
        . "Inner join sh_classes ON sh_study_material.class_id = sh_classes.id "
        . "left JOIN sh_batches ON sh_study_material.batch_id = sh_batches.id "
        . "INNER JOIN sh_subjects ON sh_study_material.subject_id = sh_subjects.id "
        . "WHERE sh_study_material.delete_status=0 "
        . "AND sh_study_material.school_id=". login_user()->user->sh_id. " ";



        $response = $this->admin_model->dbQuery($query.$where_part);
        
        foreach ($response as $key=>$val) {
            $response[$key]->files = explode(",", $val->files);
        }
        
        echo json_encode($response);
    }

    function filter() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $batch_id = $request->section;
        if ($batch_id == "all") {
            $batch_id = 0;
        }
        $subject_id = $request->subject;
        $type = $request->type;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $response['materials'] = $this->study_model->filter($school_id, $class_id, $batch_id, $subject_id, $type);
        for ($i = 0; $i < count($response['materials']); $i++) {
            $response['materials'][$i]['files'] = explode(",", $response['materials'][$i]['files']);
        }
        echo json_encode($response);
    }

    function getDownloadMaterials() {

        //-------------------------
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_batches.id IN (". implode(',', login_user()->t_data->batches) .") ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 

        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){

        } else if(login_user()->user->role_id == PARENT_ROLE_ID){

        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){

        }
        //-------------------------

        
        $query = "SELECT "
        . "sh_study_material.*,"
        . "sh_classes.name as class_name,"
        . "sh_batches.name as batch_name,"
        . "sh_subjects.name as subject_name,"
        . "date_format(sh_study_material.created_at,'%d/%m/%Y') as uploaded_time "
        . "FROM sh_study_material "
        . "INNER JOIN sh_classes ON sh_study_material.class_id = sh_classes.id "
        . "LEFT JOIN sh_batches ON sh_study_material.batch_id = sh_batches.id "
        . "INNER JOIN sh_subjects ON sh_study_material.subject_id = sh_subjects.id "
        . "WHERE sh_study_material.delete_status=0 AND sh_study_material.school_id=". login_user()->user->sh_id. " ";

        $response = $this->admin_model->dbQuery($query.$where_part);
        
        foreach ($response as $key=>$val) {
            $response[$key]->files = explode(",", $val->files);
        }
        
        echo json_encode($response);
    }

    function deleteMaterial() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->deleteId;
        $this->study_model->deleteMaterial($id);
        $response['deleted'] = true;
        $response['message'] = lang('study_deleted_msg');
        echo json_encode($response);
    }

    public function upload_attachments() {

        for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
            $_FILES['file']['name'][$i] = time() . '_' . $_FILES['file']['name'][$i];
        }
        $attach_names = "";
        if (isset($_FILES['file'])) {
            $attach_names = implode(",", $_FILES['file']['name']);
            if (!file_exists('./uploads/study_material')) {
                mkdir('./uploads/study_material', 0777, true);
            }
            $uploaddir = './uploads/study_material/';
            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
                $uploadfile = $uploaddir . $_FILES['file']['name'][$i];
                if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadfile)) {

                } else {

                }
            }
        }
        echo $attach_names;
    }

    public function newMaterial() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $title = $request->title;
        $content_type = $request->type;
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject = $request->subject;
        $files = $request->files;
        $details = $request->text;

        if ($batch_id == "all") {
            $this->study_model->newMaterial($title, $content_type, $class_id, 0, $subject, $files, $school_id, $details);
        } else {
            $this->study_model->newMaterial($title, $content_type, $class_id, $batch_id, $subject, $files, $school_id, $details);
        }
        $response['part'] = $this->study_model->getStudentsAndParents($class_id,$batch_id,$school_id);
        
        $response["message"] = lang('study_uploaded_msg');
        $response["sender"] = $this->session->userdata("userdata")["name"];

        echo json_encode($response);
    }

    public function zip() {

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $files = $request->files;
        $slug = url_title($request->title);
        $id = $request->id;
        $file_name = $slug . '_' . $id . ".zip";


        if (file_exists(FCPATH . "uploads/zip_files/" . $file_name)) {

            $path = file_get_contents(base_url() . "uploads/zip_files/" . $file_name);
        } else {

            foreach ($files as $file) {
                $this->zip->read_file(FCPATH . 'uploads/study_material/' . $file);
            }

            $this->zip->archive(FCPATH . 'uploads/zip_files/' . $file_name);
        }

        $response["path"] = base_url() . "uploads/zip_files/" . $file_name;
        echo json_encode($response);
    }

    public function updateMaterial() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->editId;
        $title = $request->title;
        $content_type = $request->type;
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject = $request->subject;
        $response['updated'] = false;
        if ($title == "") {
            $response['message'] = lang('material_title_req');
        } else if ($content_type == "") {
            $response['message'] = lang('material_type_req');
        } else if ($class_id == "") {
            $response['message'] = lang('class_req');
        } else if ($batch_id == "") {
            $response['message'] = lang('section_req');
        } else if ($subject == "") {
            $response['message'] = lang('subject_req');
        } else {
            $data = array(
                'title' => $title,
                'content_type' => $content_type,
                'class_id' => $class_id,
                'batch_id' => $batch_id,
                'subject_id' => $subject
            );


            $this->study_model->updateMaterial($id, $data);
            $response['updated'] = true;
            $response['message'] = lang('study_updated_msg');
        }


        echo json_encode($response);
    }

    public function book_shop(){

        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_book_shop');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        $xcrud->relation('class_id','sh_classes','id','name','deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('title,class_id, price, availability,picture');
        $xcrud->fields('title,class_id, price, availability,picture');
        $xcrud->label('class_id',lang('lbl_class'))->label('title',lang('lbl_tbl_title'))->label('availability',lang('lbl_availability'))->label('picture',lang('lbl_picture'))->label('price',lang('lbl_price'));
        $xcrud->change_type('picture','image','',array('height'=>300));
        $xcrud->column_callback('price','add_currency');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->replace_remove('soft_delete');
        $xcrud->table_name(lang('lbl_book_shop'));
        $xcrud->load_view("view", "customview.php");


        $xcrud->unset_print();
        $xcrud->unset_csv();

        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $xcrud->unset_add()->unset_edit()->unset_remove();
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){



        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $xcrud->unset_add()->unset_edit()->unset_remove();
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $xcrud->unset_add()->unset_edit()->unset_remove();
        }


        $data["book_shop"] = $xcrud->render();
        $this->load->view('study_material/book_shop',$data);
        
    }

    public function sidebar(){
        $sidebar = $this->session->userdata('userdata')["side_bar"];
        if($sidebar){
            $oldValues = $this->session->userdata("userdata");
            $oldValues["side_bar"] = false;
            $this->session->set_userdata("userdata",$oldValues);
        }
        else{
            $oldValues = $this->session->userdata("userdata");
            $oldValues["side_bar"] = true;
            $this->session->set_userdata("userdata",$oldValues);
        }
        echo $this->session->userdata('userdata')["side_bar"];
    }
}