<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    include_once FCPATH."quickstart.php";
class Settings extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }
    public function page_settings() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $page_temp = $this->admin_model->dbSelect("*","pagetemplate", " school_id='$school_id' AND deleted_at IS NULL ");
        $html = "";
        $css = "";
        if(count($page_temp) > 0){
            $html = str_replace('\n', '', json_decode($page_temp[0]->page_settings));
            $html = str_replace('\\', '', $html);
            $css = str_replace('\n', '', json_decode($page_temp[0]->pageStyle));
            $css = str_replace('\\', '', $css);

            $html = substr($html, 0, -6);
            $html = $html."<style>".$css."</style></div>";
            

            //print_r($css);die();
        } else {
            $school_page_template = array("school_id"=>$school_id, "page_settings"=>json_encode(page_default_template()), "language"=>1);
            $inserted_id = $this->common_model->insert("sh_pagetemplate",$school_page_template);
            $dd = $this->admin_model->dbSelect("*","pagetemplate"," id='$inserted_id' ");
            $html = str_replace('\n', '', json_decode($dd[0]->page_settings));
            $html = str_replace('\\', '', $html);

            
             // $html = json_decode($dd[0]->page_settings);
           
        }
       
        $data['html'] = $html;
        $data['css'] = $css;
        //print_r($css);
        $this->load->view("settings/page_settings", $data);
    }

    public function page_settings2(){
        $this->load->view("settings/page_settings2");
    }

    public function evaluation(){

        if ($this->session->userdata("userdata")["role_id"] == EMPLOYEE_ROLE_ID) {
           
            $arr = $this->session->userdata("userdata")["persissions"];
            $array = json_decode($arr);
            if (isset($array)) {
                $evaladd = 0;
                $evaledit = 0;
                $evaldelete = 0;
                foreach ($array as $key => $value) {
                    if (in_array('settings-evaladd', array($value->permission)) && $value->val == 'true') {
                        $evaladd = 1;
                    } if (in_array('settings-evaledit', array($value->permission)) && $value->val == 'true') {
                        $evaledit = 1;
                    }if (in_array('settings-evaledelete', array($value->permission)) && $value->val == 'true') {
                        $evaldelete = 1;
                    }
                }
            }
        }
        // evaluation terms xcrud start
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_evaluation_terms');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->where("academic_year_id", $academic_year_id);
        $xcrud->pass_var('school_id', $school_id);
        $xcrud->pass_var('academic_year_id', $academic_year_id);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('term_name');
        $xcrud->fields('term_name');
        $xcrud->label('term_name', lang('lbl_term_name'));
        $xcrud->replace_remove('soft_delete');
        $xcrud->load_view("view", "customview.php");
        $xcrud->unset_title();
        $xcrud->unset_search();
        $xcrud->unset_limitlist();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $data["evaluation_terms"] = $xcrud->render();
        // evaluation terms xcrud end

        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_evaluations');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->where("academic_year_id", $academic_year_id);
        $xcrud->relation('classes', 'sh_classes', 'id', 'name', "sh_classes.deleted_at IS NULL AND sh_classes.school_id='" . $school_id . "' AND academic_year_id = ".$academic_year_id,'',true);
        $xcrud->relation('term_id', 'sh_evaluation_terms', 'id', 'term_name', "sh_evaluation_terms.deleted_at IS NULL AND sh_evaluation_terms.school_id='" . $school_id . "' AND sh_evaluation_terms.academic_year_id = ".$academic_year_id);
        $xcrud->pass_var('school_id', $school_id);
        $xcrud->pass_var('academic_year_id', $academic_year_id);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('evaluation_name, type, start_date, end_date, term_id, classes');
        $xcrud->fields('evaluation_name, type, start_date, end_date, term_id, classes');
        $xcrud->label('evaluation_name', lang('evaluation_name'))->label('type', lang('lbl_type'))->label('start_date', lang('start_date'))->label('end_date', lang('end_date'))->label('classes', lang('lbl_classes'))->label('term_id', lang('evaluation_terms'));
        $xcrud->replace_remove('soft_delete');
        $xcrud->before_insert('check_evaluation');
        $xcrud->before_update('check_evaluation_update');
        $xcrud->load_view("view", "customview.php");
        $xcrud->unset_title();
        $xcrud->unset_search();
        $xcrud->unset_limitlist();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        if(isset($evaladd)){
            if($evaladd == '0'){
                $xcrud->unset_add();
            }
        }
        if(isset($evaledit)){
            if($evaledit == '0'){
                $xcrud->unset_edit();
            }
        }
        if(isset($evaldelete)){
            if($evaldelete == '0'){
                $xcrud->unset_remove();
            }
        }
        $data["evaluation"] = $xcrud->render();

        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_evaluation_categories');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->where("academic_year_id", $academic_year_id);
        $xcrud->relation('evaluation_id', 'sh_evaluations', 'id', 'evaluation_name', "sh_evaluations.deleted_at IS NULL AND sh_evaluations.school_id='" . $school_id . "' AND sh_evaluations.academic_year_id = ".$academic_year_id);
        $xcrud->pass_var('school_id', $school_id);
        $xcrud->pass_var('academic_year_id', $academic_year_id);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('category_name, evaluation_id');
        $xcrud->fields('category_name, evaluation_id');
        $xcrud->label('category_name', lang('th_cat_name'))->label('evaluation_id', lang('evaluation_name'));
        $xcrud->replace_remove('soft_delete');
        $xcrud->load_view("view", "customview.php");
        $xcrud->unset_title();
        $xcrud->unset_search();
        $xcrud->unset_limitlist();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        if(isset($evaladd)){
            if($evaladd == '0'){
                $xcrud->unset_add();
            }
        }
        if(isset($evaledit)){
            if($evaledit == '0'){
                $xcrud->unset_edit();
            }
        }
        if(isset($evaldelete)){
            if($evaldelete == '0'){
                $xcrud->unset_remove();
            }
        }
        $data["category"] = $xcrud->render();


        $this->load->view('settings/evaluation',$data);
    }

    public function general() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        //google drive
        $sh_enable_gd = $this->session->userdata("userdata")["sh_enable_gd"];
        $logged_roleid = $this->session->userdata("userdata")["role_id"];
        
        $data["countries"] = $this->common_model->get_where("sh_countries", "*")->result();
        $data["departments"] = $this->admin_model->dbSelect("*", "departments", " deleted_at=0 AND school_id='$school_id' ");
        $data["generalSettings"] = $this->common_model->get_where("sh_school", "id", $school_id)->result();
        if($data["generalSettings"])
        {
            $data["default_country_code"]=$data["generalSettings"][0]->country_code;
        }
        else
        {
            $data["default_country_code"]="";
        }
        
        // $def_country_code=$this->db->select('country_code')->from('sh_countries')->where('country_name',$data["generalSettings"][0]->country)->get()->row();
        // if($def_country_code)
        // {
        //     $data["default_country_code"]=$def_country_code->country_code;
        // }
        // else
        // {
        //     $data["default_country_code"]="";
        // }
        
        $data["working_days"] = json_decode($data["generalSettings"][0]->working_days);
        $data["timezones"] = $this->common_model->time_zone_list();
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_school');
        $xcrud->where("id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at', 0);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('emp_reg, std_reg, fee_receipt, payroll_receipt');
        $xcrud->fields('emp_reg, std_reg, fee_receipt, payroll_receipt');
        $xcrud->label('emp_reg', lang('emp_reg'))->label('std_reg', lang('std_reg'))->label('fee_receipt', lang('fee_receipt'))->label('payroll_receipt', lang('payroll_receipt'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->unset_add();
        $xcrud->unset_title();
        $xcrud->unset_search();
        $xcrud->unset_limitlist();
        $xcrud->unset_remove();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $data["auto_numbers"] = $xcrud->render();
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_school_currencies');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
        $xcrud->relation('currency_id', 'sh_currency', 'id', array('symbol','name'),'','','',' - ');
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('currency_id,is_default');
        $xcrud->fields('currency_id,is_default');
        $xcrud->before_insert('checkCurrency');
        $xcrud->before_update('checkCurrencyUpdate');
        $xcrud->after_insert('updateCurrency');
        $xcrud->after_update('updateCurrency');
        $xcrud->after_remove('updateCurrencyDelete');
        $xcrud->column_callback('is_default', 'primaryiconcurrency');
        $xcrud->label('currency_id', lang('Currency'));
        $xcrud->label('is_default', lang('is_default'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->replace_remove('soft_delete');
        $xcrud->unset_title();
        $xcrud->unset_search();
        $xcrud->unset_limitlist();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $data["school_currencies"] = $xcrud->render();
        // google drive
        $data['logged_roleid']=$logged_roleid;
        $data['enable_gd']=$sh_enable_gd;
        $data['credentials_fileid']='credentials_'.$school_id.'.json';
        
        $this->load->view("settings/general-settings", $data);
    }

    public function saveGeneralInfo() {
        $form_data = $this->input->post();
        unset($form_data['url']);
        
        $form_data['country_code']=$form_data['def_coun_code'];
        unset($form_data['def_coun_code']);
        unset($form_data['full_school_number']);
        
        $db_number_only=NULL;
        $final_arr_contact=explode(' ',$form_data['phone']);
        if(sizeof($final_arr_contact) > 0)
        {
            foreach($final_arr_contact as $ar_con)
            {
                $db_number_only.=$ar_con;
            }
        }
        else
        {
            $db_number_only=$form_data['phone'];
        }
        
        $db_pnumber=NULL;
        $final_arr_pnumber=explode(' ',$form_data['school_number']);
        if(sizeof($final_arr_pnumber) > 0)
        {
            foreach($final_arr_pnumber as $ar_num)
            {
                $db_pnumber.=$ar_num;
            }
        }
        else
        {
            $db_pnumber=$form_data['school_number'];
        }
        
        
        
        $form_data['phone']=$db_pnumber;
        $form_data['u_phone_number']=$db_number_only;
        
        unset($form_data['school_number']);


        $school_id = $this->session->userdata("userdata")["sh_id"];
        $workingdays = $this->admin_model->dbSelect("working_days", "school", " id='$school_id' ");
        if (isset($_FILES["fileToUpload"])) {
            if (!empty($_FILES["fileToUpload"]["name"])) {
                $form_data["logo"] = $_FILES["fileToUpload"]["name"];
                if ($this->upload()) {
                    $oldValues = $this->session->userdata("userdata");
                    $oldValues["sh_logo"] = $form_data["logo"];
                    $this->session->set_userdata("userdata", $oldValues);
                    $this->common_model->update_where("sh_school", array("id" => $this->session->userdata("userdata")["sh_id"]), $form_data);
                    $this->session->set_flashdata("selected_tab", "general");
                }
            } else {
                $this->common_model->update_where("sh_school", array("id" => $this->session->userdata("userdata")["sh_id"]), $form_data);
                $this->session->set_flashdata("selected_tab", "general");
                $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('school_info_update')));
            }
            $oldValues = $this->session->userdata("userdata");
            $oldValues["sh_name"] = $form_data["name"];
            $this->session->set_userdata("userdata", $oldValues);
            redirect("settings/general", "refresh");
        } else {
            if ($this->input->post("address") !== FALSE) {
                $this->session->set_flashdata("selected_tab", "address");
                $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('address_update')));
            } else {
                $userinputs = $this->input->post("working_days");
                $form_data["financial_year_start_date"] = to_mysql_date($this->input->post("financial_year_start_date"));
                $form_data["financial_year_end_date"] = to_mysql_date($this->input->post("financial_year_end_date"));

                if (strtotime($form_data["financial_year_end_date"]) < strtotime($form_data["financial_year_start_date"])) {
                    $this->session->set_flashdata("selected_tab", "others");
                    $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('invalid_date')));
                    redirect("settings/general", "refresh");
                }

                $obj = json_decode($workingdays[0]->working_days);

                foreach ($obj as $key => $object) {
                    if (in_array($object->label, $userinputs)) {
                        $object->val = 'true';
                        //unset($obj[$key]);
                    } else {
                        $object->val = 'false';
                    }
                }

                $form_data["working_days"] = json_encode($obj);
                $oldValues = $this->session->userdata("userdata");
                $oldValues["theme_color"] = $form_data["theme_color"];
                $oldValues["time_zone"] = $this->input->post('time_zone');
                // $oldValues["teacher_dept_id"] = $form_data["teacher_dept_id"];
                // $oldValues["accounts_dept_id"] = $form_data["accounts_dept_id"];
                $this->session->set_userdata("userdata", $oldValues);
                $this->session->set_flashdata("selected_tab", "others");
                $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('other_update')));
            }
            //$this->common_model->update("sh_school", $form_data, $this->session->userdata("userdata")["sh_id"]);
            $this->common_model->update_where("sh_school", array("id" => $this->session->userdata("userdata")["sh_id"]), $form_data);
            redirect("settings/general", "refresh");
        }
    }

    public function upload() {
        $target_dir = "uploads/logos/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                $this->session->set_flashdata('alert', array("status" => "error", "message" => lang('file_image') . $check["mime"] . "."));
                return false;
            } else {
                $this->session->set_flashdata('alert', array("status" => "error", "message" => lang('file_not_image')));
                return false;
            }
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 1000000) {
            $this->session->set_flashdata('alert', array("status" => "error", "message" => lang('file_large')));
            return false;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $this->session->set_flashdata('alert', array("status" => "error", "message" => lang('file_not_allowed')));
            return false;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            chmod($target_file, 0755);
            unlink($target_file);
        }

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('image_success')));
            return true;
        } else {
            $this->session->set_flashdata('alert', array("status" => "error", "message" => lang('file_error')));
            return false;
        }
    }

    /////////// By Shahzaib 21-12-2017 ///////////////////
    public function hr() {
        $UserData = $this->session->userdata('userdata');
        //$data['roles'] = $this->common_model->getUserRoles();
        $data['departments'] = $this->admin_model->dbSelect("*", "departments", " role_id=4 AND school_id=" . $UserData['sh_id'] . " AND deleted_at=0 ");
        $data['role_categories'] = $this->common_model->getRoleCategories($UserData['sh_id']);

        $this->load->view('hr-settings/index', $data);
    }

    public function initDeperments() {
        $school_id = $this->session->userdata('userdata')['sh_id'];
        $data['departments'] = $this->admin_model->dbSelect("*", "departments", " role_id=4 AND school_id=" . $school_id . " AND deleted_at=0 ");
        echo json_encode($data);
    }

    public function initCategories() {
        $school_id = $this->session->userdata('userdata')['sh_id'];
        $role_id = '4';

        $this->db->select('c.id as rid , c.*, d.name as department_name');
        $this->db->from('role_categories c');
        $this->db->join('departments d', 'd.id = c.department_id', 'left');
        $this->db->where('c.school_id', $school_id);
        $this->db->where('c.role_id', $role_id);
        $this->db->where('c.deleted_at', 0);
        $this->db->where('d.deleted_at', 0);
        $this->db->order_by('department_name');
        $data['categories'] = $this->db->get()->result();
        echo json_encode($data);
        // return $query->result();
    }

    public function addCategory() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // print_r($request);die();
        $data = array(
            'school_id' => $this->session->userdata('userdata')['sh_id'],
            'role_id' => EMPLOYEE_ROLE_ID,
            'category' => $request->category,
            'department_id' => $request->department_id,
            'default_permissions' => json_encode($request->permissions)
        );
        $insert_id = $this->common_model->insert("role_categories", $data);
        if ($insert_id) {
            $data = array("status" => "success", "message" => lang('msg_cat_added'));
            echo json_encode($data);
        }
    }

    public function removeCategory() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $this->db->set('deleted_at', '1');
        $this->db->where('id', $request->id);
        $result = $this->db->update('sh_role_categories');
        if ($result) {
            $data = array('status' => 'success', 'message' => lang('msg_cat_deleted'));
            echo json_encode($data);
        }
    }

    public function editCategory() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $result = $this->common_model->getRoleCategoriesById($request->id)->row();
        echo json_encode($result);
    }

    public function editCategorySucess() {
        $cat_id = $this->input->post('cat_id');
        $data['category'] = $this->input->post('category');
        //$data['role_id'] = $this->input->post('role_id');
        $data['department_id'] = $this->input->post('department_id');
        $this->common_model->editRecord('id', $cat_id, 'role_categories', $data);
        echo 'updated';
    }

    /////////// BY YASIR 21-12-2017 ///////////
    public function saveDepartment() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $res = $this->admin_model->dbSelect("*", "departments", " code='" . $request->code . "' AND deleted_at=0 AND school_id='" . $this->session->userdata('userdata')["sh_id"] . "' ");
        if (count($res) > 0) {
            echo json_encode(array("status" => "error", "message" => lang('dept_exist')));
        } else {
            $type = $request->type;

            $data = array("school_id" => $this->session->userdata("userdata")["sh_id"], "name" => $request->name, "code" => $request->code, "role_id" => EMPLOYEE_ROLE_ID, "type" => $type);
            $res = $this->admin_model->dbInsert("departments", $data);
            $insert_id = $this->db->insert_id();
            if($type == "teacher"){
                $this->db->set('type', 'other')->where('school_id', $school_id)->where('type', 'teacher')->where('id <>', $insert_id )->update('sh_departments');
                $this->db->set("teacher_dept_id", $insert_id)->where('id', $school_id)->update('sh_school');
                $oldValues = $this->session->userdata("userdata");
                $oldValues["teacher_dept_id"] = $insert_id;
                $this->session->set_userdata("userdata", $oldValues);
            }else if($type == "accounts"){
                $this->db->set('type', 'other')->where('school_id', $school_id)->where('type', 'accounts')->where('id <>', $insert_id )->update('sh_departments');
                $this->db->set("accounts_dept_id", $insert_id)->where('id', $school_id)->update('sh_school');
                $oldValues = $this->session->userdata("userdata");
                $oldValues["accounts_dept_id"] = $insert_id;
                $this->session->set_userdata("userdata", $oldValues);
            }
            if ($res > 0) {
                echo json_encode(array("status" => "success", "message" => lang('msg_department_created_successfully')));
            } else {
                echo json_encode(array("status" => "error", "message" => lang('msg_department_create_error')));
            }
            $this->session->set_flashdata("hr_selected_tab", "department");
        }
    }

    public function deleteDepartment() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $this->common_model->update_where("sh_departments", array("id" => $request->id), array("deleted_at" => 1));
        $data = array('status' => 'success', 'message' => lang('msg_department_delete'));
        echo json_encode($data);
    }

    public function getDepartment() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = $this->common_model->get_where("sh_departments", "id", $request->id)->row();
        echo json_encode($data);
    }

    public function updateDepartment() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $id = $request->id;
        $type = $request->type;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $check = $this->db->select('id')->from('sh_departments')->where('code', $request->code)->where('id <>', $id)->where('school_id', $school_id)->get()->row();
        if($check){
            $data = array('status' => 'error', 'message' => lang('dept_exist'));
        }else{
            $where = array('id' => $request->id);
            $data = array("name" => $request->name, "code" => $request->code, "type" => $type);
            $result = $this->common_model->update_where("sh_departments", $where, $data);
            if($type == "teacher"){
                $this->db->set('type', 'other')->where('school_id', $school_id)->where('type', 'teacher')->where('id <>', $id )->update('sh_departments');
                $this->db->set("teacher_dept_id", $id)->where('id', $school_id)->update('sh_school');
                $oldValues = $this->session->userdata("userdata");
                $oldValues["teacher_dept_id"] = $id;
                $this->session->set_userdata("userdata", $oldValues);
            }else if($type == "accounts"){
                $this->db->set('type', 'other')->where('school_id', $school_id)->where('type', 'accounts')->where('id <>', $id )->update('sh_departments');
                $this->db->set("accounts_dept_id", $id)->where('id', $school_id)->update('sh_school');
                $oldValues = $this->session->userdata("userdata");
                $oldValues["accounts_dept_id"] = $id;
                $this->session->set_userdata("userdata", $oldValues);
            }
            if ($result) {
                $data = array('status' => 'success', 'message' => lang('dept_update'));
            }
        }
        
        echo json_encode($data);
    }

    public function getCategory() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $this->db->select('d.id as department_id, d.name, c.id, c.category, c.role_id, c.school_id, c.default_permissions as permissions');
        $this->db->from('sh_departments d');
        $this->db->join('sh_role_categories c', 'd.id = c.department_id');
        $this->db->where('c.id', $request->id);
        $this->db->where('d.deleted_at = 0');
        $this->db->where('c.deleted_at = 0');
        $data = $this->db->get()->row();
        if ($data->permissions != null) {
            $data->permissions = json_decode($data->permissions);
            foreach ($data->permissions as $val) {
                $val->label = lang($val->permission);
            }
        }


        echo json_encode($data);
    }

    public function updateCategory() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $this->db->set("department_id", $request->department_id);
        $this->db->set("category", $request->category);
        $this->db->set("default_permissions", json_encode($request->permissions));
        $this->db->where('id', $request->id);
        $result = $this->db->update('sh_role_categories');
        if ($result) {
            $data = array('status' => 'success', 'message' => lang('msg_cat_updated'));
        }
        echo json_encode($data);
    }

    public function academic() {

        $params = $this->input->get();
        $data["selected_tab"] = "";
        $data["tab_batches_selected_class_id"] = "all";
        $data["tab_subjects_selected_class_id"] = "all";
        $data["tab_subjects_selected_batch_id"] = "all";
        $data["tab_periods_selected_class_id"] = "all";
        $data["tab_periods_selected_batch_id"] = "all";
        $data["tab_subject_groups_class_id"] = "all";
        $data["tab_subject_groups_batch_id"] = "all";

        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_academic_years');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        //$xcrud->relation('id', 'sh_schools', 'school_id', 'academic_years', 'deletet_at=0 AND school_id=' . $this->session->userdata("userdata")["sh_id"]);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name, start_date, end_date, is_active');
        $xcrud->fields('name, start_date, end_date, is_active');
        $xcrud->label('name', lang('lbl_academic_year'))->label('start_date', lang('start_date'))->label('end_date', lang('end_date'))->label('is_active', lang('is_active'));
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->replace_remove('soft_delete');
        $xcrud->before_remove('before_delete_academic_year');
        $xcrud->table_name(lang('lbl_academic_year'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->before_insert('deleteOtherPrimary');
        $xcrud->after_insert('update_academic_year');
        $xcrud->after_remove('update_academic_year_delete');
        $xcrud->before_update('deleteOtherPrimary_update');
        $xcrud->after_update('update_academic_year');
        $xcrud->column_callback('is_active', 'primaryicon');
        $xcrud->button('settings/setup_academic_year',"Setup","fa fa-cog","btn-primary btn-circle","",array("is_active","=","Y"));
        $xcrud->order_by("start_date");
        //$xcrud->unset_remove();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_title();
        $data["academic_years"] = $xcrud->render();

        /* Periods */
        $periods = xcrud_get_instance();
        $periods->table('sh_periods');
        $periods->show_primary_ai_field(false);

        //$periods->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);

        $periods->columns('title,start_time,end_time,class_id,batch_id,is_break');
        $periods->fields('title,start_time,end_time,class_id,batch_id,is_break');

        $periods->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"].' and academic_year_id = '.$this->session->userdata("userdata")["academic_year"]);
        $periods->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"].' and academic_year_id = '.$this->session->userdata("userdata")["academic_year"], '', '', '', '', 'class_id', 'class_id');
        $periods->label('class_id', lang('lbl_class'))->label('batch_id', lang('lbl_batch'));
        $periods->label('title', lang('period_title'))->label('start_time', lang('start_time'));
        $periods->label('end_time', lang('end_time'))->label('is_break', lang('break'));
        $periods->order_by('start_time', 'asc');
        $periods->replace_remove('soft_delete');
        $periods->load_view("view", "customview.php");
        $periods->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $periods->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $periods->table_name('Periods');
        $periods->before_insert('checkOverlap');
        $periods->replace_insert('periodsAdd');
        $periods->before_update('section_check');
        //$periods->unset_remove();
        $periods->unset_print();
        $periods->unset_csv();
        $periods->unset_title();

        $periods->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);

        if ($params !== null) {
            if ($params["tab"] === "tab_periods") {
                $data["selected_tab"] = $params["tab"];
                $data["tab_periods_selected_class_id"] = $params["class_id"];
                $data["tab_periods_selected_batch_id"] = $params["batch_id"];
                if ($params["class_id"] != "all") {
                    if ($params["batch_id"] == "all") {
                        $data2 = $this->admin_model->dbSelect("id", "batches", " class_id=" . $params['class_id'] . " ");
                        $array = array();
                        foreach ($data2 as $value) {
                            $array[] = $value->id;
                        }

                        if (count($array) > 0) {
                            $periods->where('batch_id', $array);
                        } else {
                            $periods->where('batch_id', "0");
                        }
                    } else {
                        $periods->where('batch_id', $params["batch_id"]);
                    }
                }
            }
        }

        $data["periods"] = $periods->render();
        /* End of periods */

        /*         * **** classes xcrud ******** */
        $classes = xcrud_get_instance();
        $classes->table('sh_classes');
        $classes->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
        $classes->relation('level_id', 'sh_class_levels', 'id', 'level_name', 'deleted_at IS NULL AND school_id=' . $this->session->userdata("userdata")["sh_id"]);
        $classes->show_primary_ai_field(false);
        $classes->columns('name, code, grading_type,level_id');
        $classes->fields('name, code, grading_type,level_id');
        $classes->label('name', lang('class_name'));
        $classes->label('code', lang('class_code'));
        $classes->label('level_id', lang('level'));
        $classes->label('grading_type', lang('lbl_grading_type'));
        $classes->replace_remove('soft_delete');
        $classes->before_remove('before_delete_classes');
        $classes->load_view("view", "customview.php");
        $classes->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $classes->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $classes->before_insert('check_academic_year');
        $classes->table_name(lang('lbl_classes'));
        //$classes->unset_remove();
        $classes->unset_print();
        $classes->unset_csv();
        $classes->unset_title();
        $data["classes"] = $classes->render();



        /*         * ******* Batches xcrud *********** */
        $batches = xcrud_get_instance('batchesx');
        $batches->table('sh_batches');
        $batches->join('class_id', 'sh_classes', 'id');
        $batches->join('academic_year_id', 'sh_academic_years', 'id');
        $batches->where("school_id", $this->session->userdata("userdata")["sh_id"])->where("sh_batches.deleted_at IS NULL AND sh_classes.deleted_at IS NULL AND sh_academic_years.deleted_at IS NULL AND sh_academic_years.is_active = 'Y'");

        $batches->show_primary_ai_field(false);
        $batches->columns('sh_batches.name,class_id,teacher_id');
        $batches->fields('name,class_id,teacher_id');
        $batches->relation('class_id', 'sh_classes', 'id', 'name', "sh_classes.deleted_at IS NULL AND sh_classes.school_id='" . $this->session->userdata("userdata")["sh_id"] . "' AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        $batches->relation('teacher_id', 'sh_users', 'id', 'name', "sh_users.deleted_at = 0 AND sh_users.school_id='" . $this->session->userdata("userdata")["sh_id"] . "' AND department_id = '" . $this->session->userdata("userdata")["teacher_dept_id"] . "' and role_id=" . EMPLOYEE_ROLE_ID, '', true, '', '', '', '');
        $batches->label('name', lang('lbl_batch'));
        $batches->label('class_id', lang('lbl_class'));
        $batches->label('teacher_id', lang('lbl_teacher'));
        $batches->disabled('class_id', 'edit');
        $batches->replace_remove('soft_delete');
        $batches->before_remove('before_delete_batches');
        $batches->load_view("view", "customview.php");
        $batches->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $batches->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $batches->table_name(lang('all_batches'));
        $batches->replace_insert('addSection');
        //$batches->unset_remove();
        $batches->unset_print();
        $batches->unset_csv();
        $batches->unset_title();
        if ($params !== null) {
            if ($params["tab"] === "tab_batches") {
                $data["selected_tab"] = "tab_batches";
                $data["tab_batches_selected_class_id"] = $params["class_id"];
                if ($params["class_id"] != "all") {
                    $batches->where('class_id', $params["class_id"]);
                }
            }
        }

        $data["batches"] = $batches->render();

        /*         * **************** Subjects xcurd ****************** */
        $subjects = xcrud_get_instance();
        $subjects->table('sh_subjects');
        $subjects->join('batch_id', 'sh_batches', 'id');
        $subjects->join('class_id', 'sh_classes', 'id');
        $subjects->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('sh_subjects.deleted_at IS NULL and sh_batches.deleted_at IS NULL and sh_classes.deleted_at IS NULL ')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);

        $subjects->show_primary_ai_field(false);
        $subjects->columns('name,code,class_id,batch_id,weekly_classes');
        $subjects->fields('name,code,class_id,batch_id,weekly_classes');
        $subjects->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]." and academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        $subjects->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"].' and academic_year_id = '.$this->session->userdata("userdata")["academic_year"], '', '', '', '', 'class_id', 'class_id');
        $subjects->label('class_id', lang('lbl_class'));
        $subjects->label('name', lang('lbl_subject'))->label('batch_id', lang('lbl_batch'))->label('code', lang('lbl_code'))->label('weekly_classes', lang('lbl_weekly_classes'));
        $subjects->before_remove('before_delete_subjects');
        $subjects->replace_remove('soft_delete');
        $subjects->load_view("view", "customview.php");
        $subjects->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $subjects->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $subjects->table_name(lang('all_subjects'));
        $subjects->before_insert('section_count');
        $subjects->replace_insert('subjectsAdd');
        $subjects->before_update('section_check_subject');
        $subjects->unset_print();
        //$subjects->unset_remove();
        $subjects->unset_csv();
        $subjects->unset_title();

        if ($params !== null) {
            if ($params["tab"] === "tab_subjects") {
                $data["selected_tab"] = $params["tab"];
                $data["tab_subjects_selected_class_id"] = $params["class_id"];
                $data["tab_subjects_selected_batch_id"] = $params["batch_id"];
                if ($params["class_id"] != "all") {
                    if ($params["batch_id"] == "all") {
                        $subjects->where('class_id', $params["class_id"]);
                    } else {
                        $subjects->where('class_id', $params["class_id"]);
                        $subjects->where('batch_id', $params["batch_id"]);
                    }
                }
            }
        }

        $data["subjects"] = $subjects->render();
        $class_levels = xcrud_get_instance();
        $class_levels->table('sh_class_levels');
        $class_levels->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        $class_levels->show_primary_ai_field(false);
        $class_levels->columns('level_name,description');
        $class_levels->fields('level_name,description');
        $class_levels->label('level_name', lang('level_name'));
        $class_levels->label('description', lang('description'));
        $class_levels->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $class_levels->replace_remove('soft_delete');
        //$class_levels->before_remove('before_delete_class_level');
        $class_levels->table_name(lang('class_levels'));
        $class_levels->load_view("view", "customview.php");
        $class_levels->unset_print();
        //$class_levels->unset_remove();
        $class_levels->unset_csv();
        $class_levels->unset_title();
        $data["class_levels"] = $class_levels->render();

        $subject_groups = xcrud_get_instance();
        $subject_groups->table('sh_subject_groups');
        $subject_groups->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
        if ($params !== null) {
            if ($params["tab"] === "tab_subject_groups") {
                $data["selected_tab"] = $params["tab"];
                $data["tab_subject_groups_class_id"] = $params["class_id"];
                $data["tab_subject_groups_batch_id"] = $params["batch_id"];
                if ($params["class_id"] != "all") {
                    if ($params["batch_id"] == "all") {
                        $subject_groups->where("class_id", $params["class_id"]);
                    } else {
                        $subject_groups->where("class_id", $params["class_id"])->where("batch_id", $params["batch_id"]);
                    }
                }
            }
        }
        $subject_groups->show_primary_ai_field(false);
        $subject_groups->columns('class_id,batch_id,group_name,subjects');
        $subject_groups->fields('class_id,batch_id,group_name,subjects');
        $subject_groups->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"].' and academic_year_id = '.$this->session->userdata("userdata")["academic_year"]);
        $subject_groups->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"].' and academic_year_id = '.$this->session->userdata("userdata")["academic_year"], '', '', '', '', 'class_id', 'class_id');
        $subject_groups->relation('subjects', 'sh_subjects', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"].' and academic_year_id = '.$this->session->userdata("userdata")["academic_year"], '', true, '', '', 'batch_id', 'batch_id');
        $subject_groups->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $subject_groups->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $subject_groups->label('class_id', lang('lbl_class'));
        $subject_groups->label('batch_id', lang('lbl_batch'));
        $subject_groups->label('group_name', lang('subject_group_name'));
        $subject_groups->label('subjects', lang('lbl_subjects'));
        $subject_groups->replace_remove('soft_delete');
        //$subject_groups->before_remove('before_delete_subject_group');
        $subject_groups->before_insert('new_subject_group');
        $subject_groups->before_update('new_subject_group_update');
        $subject_groups->table_name(lang('subject_groups'));
        $subject_groups->load_view("view", "customview.php");
        $subject_groups->unset_print();
        //$subject_groups->unset_remove();
        $subject_groups->unset_csv();
        $subject_groups->unset_title();
        $data["subject_groups"] = $subject_groups->render();

        $this->load->view("settings/academic-settings", $data);
    }

    public function getPeriods() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $periods = xcrud_get_instance();
        $periods->table('sh_periods');
        $periods->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        if ($request->batch_id != "all") {
            $periods->where('batch_id', $request->batch_id);
            $periods->show_primary_ai_field(false);

            //$periods->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);

            $periods->columns('start_time,end_time,class_id,batch_id,is_break');
            $periods->fields('start_time,end_time,class_id,batch_id,is_break');

            $periods->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
            $periods->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'class_id', 'class_id');

            $periods->replace_remove('soft_delete');
            $periods->load_view("view", "customview.php");
            $periods->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
            $periods->table_name(lang('filter_periods'));
            $periods->unset_print();
            $periods->unset_csv();
            echo $periods->render();
        } else if ($request->class_id != "all") {
            $data = $this->admin_model->dbSelect("id", "batches", " class_id='$request->class_id' ");
            $array = array();
            foreach ($data as $value) {
                $array[] = $value->id;
            }
            if (count($array) > 0) {
                $periods->where('batch_id', $array);
                $periods->show_primary_ai_field(false);

                $periods->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);

                $periods->columns('start_time,end_time,class_id,batch_id,is_break');
                $periods->fields('start_time,end_time,class_id,batch_id,is_break');

                $periods->replace_remove('soft_delete');
                $periods->load_view("view", "customview.php");
                $periods->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
                $periods->table_name(lang('filter_periods'));
                $periods->unset_print();
                $periods->unset_csv();
                echo $periods->render();
            } else {
                echo lang('no_record');
            }
        }
    }

    public function getSchoolClasses() {
        $data = $this->admin_model->dbSelect("*", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        echo json_encode($data);
    }

    public function getClassBatches() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        if($request->id == ""){
            $request->id = -1;
        }
        if (($request->id != "all" and $request->id != "") OR $request->id == 0) {
            $query = " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND class_id=$request->id AND academic_year_id=(Select id from sh_academic_years Where school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND is_active='Y') AND deleted_at IS NULL ";
            //$subjects->where('batch_id', $request->batch_id);
        } else {
            $query = " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND academic_year_id=(Select id from sh_academic_years Where school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND is_active='Y') AND deleted_at IS NULL ";
        }

        $data = $this->admin_model->dbSelect("*", "batches", $query);
        echo json_encode($data);
    }

    public function getClassBatchesAndDiscounts() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        if (($request->id != "all" and $request->id != "") OR $request->id == 0) {
            $query = " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND class_id=$request->id AND academic_year_id=(Select id from sh_academic_years Where school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND is_active='Y') AND deleted_at IS NULL ";
        } else {
            $query = " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND academic_year_id=(Select id from sh_academic_years Where school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND is_active='Y') AND deleted_at IS NULL ";
        }

        $data['batches'] = $this->admin_model->dbSelect("*", "batches", $query);
        echo json_encode($data);
    }

    public function getSubjects() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $subjects = xcrud_get_instance();
        $subjects->table('sh_subjects');
        $subjects->join('batch_id', 'sh_batches', 'id');
        $subjects->join('class_id', 'sh_classes', 'id');
        $subjects->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('sh_subjects.deleted_at IS NULL and sh_batches.deleted_at IS NULL and sh_classes.deleted_at IS NULL ')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
        if ($request->batch_id != "all") {
            $subjects->where('batch_id', $request->batch_id);
            $subjects->show_primary_ai_field(false);

            //$subjects->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);

            $subjects->columns('name,code,class_id,batch_id,weekly_classes');
            $subjects->fields('name,code,class_id,batch_id,weekly_classes');

            $subjects->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
            $subjects->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'class_id', 'class_id');
            $subjects->label('class_id', lang('lbl_class'));

            $subjects->label('name', lang('lbl_subject'))->label('batch_id', lang('lbl_batch'))->label('code', lang('lbl_code'))->label('weekly_classes', lang('lbl_weekly_classes'));
            $subjects->replace_remove('soft_delete');
            $subjects->load_view("view", "customview.php");
            $subjects->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
            $subjects->table_name(lang('filter_subjects'));
            $subjects->before_insert('section_count');
            $subjects->replace_insert('subjectsAdd');
            $subjects->before_update('section_check_subject');
            $subjects->unset_print();
            $subjects->unset_csv();
            echo $subjects->render();
        } else if ($request->class_id != "all") {
            $data = $this->admin_model->dbSelect("id", "batches", " class_id='$request->class_id' ");
            $array = array();
            foreach ($data as $value) {
                $array[] = $value->id;
            }
            if (count($array) > 0) {
                $subjects->where('batch_id', $array);
                $subjects->show_primary_ai_field(false);

                $subjects->columns('name,code,class_id,batch_id,weekly_classes');
                $subjects->fields('name,code,class_id,batch_id,weekly_classes');


                $subjects->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
                $subjects->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'class_id', 'class_id');
                $subjects->label('name', lang('lbl_subject'))->label('batch_id', lang('lbl_batch'))->label('code', lang('lbl_code'))->label('weekly_classes', lang('lbl_weekly_classes'));
                $subjects->replace_remove('soft_delete');
                $subjects->load_view("view", "customview.php");
                $subjects->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
                $subjects->table_name(lang('filter_subjects'));
                $subjects->before_insert('section_count');
                $subjects->replace_insert('subjectsAdd');
                $subjects->before_update('section_check_subject');
                $subjects->unset_print();
                $subjects->unset_csv();
                echo $subjects->render();
            } else {
                echo lang('no_record');
            }
        }
    }

    public function getClassBatch() {

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $batches = xcrud_get_instance('batchesx');
        $batches->table('sh_batches');
        $batches->join('class_id', 'sh_classes', 'id');
        $batches->join('academic_year_id', 'sh_academic_years', 'id');
        $batches->where("school_id", $this->session->userdata("userdata")["sh_id"])->where("sh_batches.deleted_at IS NULL and sh_classes.deleted_at IS NULL and sh_academic_years.deleted_at IS NULL and sh_academic_years.is_active = 'Y'");

        if ($request->id != "all") {
            $batches->where('class_id', $request->id);
        }

        $batches->show_primary_ai_field(false);

        $batches->relation('class_id', 'sh_classes', 'id', 'name', "sh_classes.deleted_at IS NULL AND sh_classes.school_id='" . $this->session->userdata("userdata")["sh_id"] . "'");
        $batches->relation('academic_year_id', 'sh_academic_years', 'id', 'name', "sh_academic_years.deleted_at IS NULL AND sh_academic_years.is_active='Y' AND sh_academic_years.school_id='" . $this->session->userdata("userdata")["sh_id"] . "'");

        $batches->columns('sh_batches.name,class_id,name,academic_year_id,start_date,end_date');
        $batches->fields('name,class_id,academic_year_id,start_date,end_date');
        $batches->label('name', lang('lbl_batch'));
        $batches->label('class_id', lang('lbl_class'));
        $batches->label('academic_year_id', lang('lbl_academic_year'));
        $batches->label('start_date', lang('start_date'));
        $batches->label('end_date', lang('end_date'));
        $batches->replace_remove('soft_delete');
        $batches->load_view("view", "customview.php");
        $batches->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $batches->table_name(lang('lbl_batches'));
        $batches->replace_insert('addSection');
        $batches->unset_print();
        $batches->unset_csv();

        $data["batches"] = $batches->render();

        //                . " <script src='assets/xcrud/plugins/jquery-ui/jquery-ui.min.js'></script>
        //<script src='assets/xcrud/plugins/timepicker/jquery-ui-timepicker-addon.js'> </script> <script type='text/javascript'>
        //        jQuery(document).on('xcrudafterrequest',function(event,container){
        //            if(Xcrud.current_task == 'edit')
        //            {
        //            
        //        var datepicker_config = {
        //            changeMonth: true,
        //            changeYear: true,
        //            showSecond: false,
        //            controlType: 'select',
        //            dateFormat:'dd/mm/yy',
        //            timeFormat: 'hh:mm tt'
        //        };
        //                
        //                //$('.xcrud-datepicker').hide();
        //                $('.xcrud-datepicker').datepicker(datepicker_config);
        //                
        //            }
        //        });
        //        </script> ";



        echo json_encode($data);
    }

    public function exam() {
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_grades');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');

        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name, percent_from, percent_upto');
        $xcrud->fields('name, percent_from, percent_upto');
        $xcrud->label('name', lang('lbl_grade_name'))->label('percent_from', lang('lbl_percent_from'))->label('percent_upto', lang('lbl_percent_upto'));
        $xcrud->order_by('percent_from', 'desc');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->table_name(lang('lbl_grades'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->before_insert('checkValidation');
        $xcrud->before_update('checkValidationUpdate');
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_title();
        $data["grades"] = $xcrud->render();

        $this->load->view('settings/exam_settings', $data);
    }

    public function setup_academic_year(){
        $this->load->view('settings/setup_academic_year');
    }

    public function get_years(){
        $data["found"] = false;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->db->select('id,name')->from('sh_academic_years')->where('school_id',$school_id)->where('is_active','Y')->where('deleted_at is null')->get()->row();
        if(count(get_object_vars($academic_year_id)) > 0){
            $data["academic_year_id"] = $academic_year_id->id;
            $data["academic_year"] = $academic_year_id->name;
            $data["found"] = true;
            $data["classes"] = $this->db->select('id')->from('sh_classes')->where('school_id',$school_id)->where('academic_year_id',$academic_year_id->id)->where('deleted_at is null')->count_all_results();
            $data["batches"] = $this->db->select('id')->from('sh_batches')->join('sh_classes','sh_batches.class_id = sh_classes.id')->where('sh_batches.school_id',$school_id)->where('sh_batches.academic_year_id',$academic_year_id->id)->where('sh_classes.deleted_at is null')->where('sh_batches.deleted_at is null')->count_all_results();
            $data["fee_types"] = $this->db->select('id')->from('sh_fee_types')->join('sh_classes','sh_fee_types.class_id = sh_classes.id')->where('sh_fee_types.school_id',$school_id)->where('sh_fee_types.academic_year_id',$academic_year_id->id)->where('sh_fee_types.deleted_at is null')->where('sh_classes.deleted_at is null')->count_all_results();
            $data["subjects"] = $this->db->select('id')->from('sh_subjects')->join('sh_classes','sh_classes.id = sh_subjects.class_id')->join('sh_batches','sh_batches.id = sh_subjects.batch_id')->where('sh_subjects.school_id',$school_id)->where('sh_subjects.academic_year_id',$academic_year_id->id)->where('sh_subjects.deleted_at is null')->where('sh_classes.deleted_at is null')->where('sh_batches.deleted_at is null')->count_all_results();
            $data["teachers"] = $this->db->select('sa.id')->from('sh_assign_subjects sa')->join('sh_classes c','c.id = sa.class_id')->join('sh_batches b','b.id = sa.batch_id and b.class_id = sa.class_id')->join('sh_subjects s','s.id = sa.subject_id and s.batch_id = sa.batch_id')->join('sh_users u','u.id = sa.teacher_id')->where('sa.school_id',$school_id)->where('sa.deleted_at is null')->where('c.academic_year_id',$this->session->userdata("userdata")["academic_year"])->where('c.deleted_at is null')->where('b.deleted_at is null')->where('s.deleted_at is null')->where('u.deleted_at',0)->count_all_results();
            $data["subject_groups"] = $this->db->select('id')->from('sh_subject_groups')->join('sh_batches','sh_batches.id = sh_subject_groups.batch_id')->where('sh_subject_groups.school_id',$school_id)->where('sh_subject_groups.academic_year_id',$academic_year_id->id)->where('sh_subject_groups.deleted_at is null')->where('sh_batches.deleted_at is null')->count_all_results();
            $data["periods"] = $this->db->select('id')->from('sh_periods')->join('sh_batches','sh_periods.batch_id = sh_batches.id and sh_periods.class_id = sh_batches.class_id')->where('sh_periods.school_id',$school_id)->where('sh_periods.academic_year_id',$academic_year_id->id)->where('sh_periods.deleted_at is null')->where('sh_batches.deleted_at is null')->count_all_results();
            $data["timetables"] = $this->db->select('id')->from('sh_timetable_new t')->join('sh_periods p','t.period_id = p.id')->join('sh_batches b','p.batch_id = b.id and p.class_id = b.class_id')->join('sh_subjects s','t.subject_id = s.id')->where('t.academic_year_id',$academic_year_id->id)->where('p.academic_year_id',$academic_year_id->id)->where('t.deleted_at is null')->where('p.deleted_at is null')->where('s.deleted_at is null')->where('b.deleted_at is null')->count_all_results();
        }
        $data["years"] = $this->db->select('id,name')->from('sh_academic_years')->where('deleted_at is null')->where('school_id',$school_id)->where('is_active','N')->order_by('start_date','desc')->get()->result();
        echo json_encode($data);
    }

    public function shift_data(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class = $request->class;
        $batch = $request->batch;
        $fee = $request->fee;
        $subject = $request->subject;
        $subject_group = $request->subject_group;
        $period = $request->period;
        $timetable = $request->timetable;
        $teacher = $request->teacher;
        $year = $request->year;
        $academic_year_id = $this->db->select('id,name')->from('sh_academic_years')->where('school_id',$school_id)->where('is_active','Y')->where('deleted_at is null')->get()->row()->id;

        $old_classes = $this->db->select('*')->from('sh_classes')->where('school_id',$school_id)->where('academic_year_id',$year)->where('deleted_at is null')->get()->result();

        foreach ($old_classes as $c) {
            $c->academic_year_id = $academic_year_id;
            $old_class_id = $c->id;
            unset($c->id);
            unset($c->created_at);
            unset($c->updated_at);
            unset($c->deleted_at);
            $this->db->insert('sh_classes',$c);
            $new_class_id = $this->db->insert_id();

            if($batch){
                $old_batches = $this->db->select('*')->from('sh_batches')->where('class_id',$old_class_id)->where('academic_year_id',$year)->where('deleted_at is null')->get()->result();
                foreach ($old_batches as $b) {
                    $b->academic_year_id = $academic_year_id;
                    $old_batch_id = $b->id;
                    $b->class_id = $new_class_id;
                    unset($b->id);
                    unset($b->created_at);
                    unset($b->updated_at);
                    unset($b->deleted_at);
                    $this->db->insert('sh_batches',$b);
                    $new_batch_id = $this->db->insert_id();
                    $subject_relation = array();
                    if($subject){
                        $old_subjects = $this->db->select('*')->from('sh_subjects')->where('class_id',$old_class_id)->where('batch_id',$old_batch_id)->where('academic_year_id',$year)->where('deleted_at is null')->get()->result();
                        foreach($old_subjects as $s){
                            $s->academic_year_id = $academic_year_id;
                            $old_subject_id = $s->id;
                            $s->class_id = $new_class_id;
                            $s->batch_id = $new_batch_id;
                            unset($s->id);
                            unset($s->created_at);
                            unset($s->updated_at);
                            unset($s->deleted_at);
                            $this->db->insert('sh_subjects',$s);
                            $new_subject_id = $this->db->insert_id();
                            $map = (object) array('old_subject' => $old_subject_id, 'new_subject' => $new_subject_id);
                            array_push($subject_relation, $map);
                            if($subject_group){
                                $new_subject_groups = $this->db->select('*')->from('sh_subject_groups')->where('class_id',$new_class_id)->where('batch_id',$new_batch_id)->where('deleted_at is null')->get()->result();
                                if(count($new_subject_groups) > 0){
                                    foreach($new_subject_groups as $nsg){
                                        $subjects = explode(",", $nsg->subjects);
                                        foreach ($subjects as $key=>$sc) {
                                            if($sc == $old_subject_id){
                                                $subjects[$key] = $new_subject_id;
                                            }
                                        }
                                        $nsg->subjects = implode(",", $subjects);
                                        $this->db->where('id',$nsg->id)->update('sh_subject_groups',$nsg);
                                    }
                                }else{
                                    $existed_subject_groups = $this->db->select('*')->from('sh_subject_groups')->where('class_id',$old_class_id)->where('batch_id',$old_batch_id)->where('deleted_at is null')->get()->result();
                                    foreach ($existed_subject_groups as $esg) {
                                        $esg->academic_year_id = $academic_year_id;
                                        $esg->class_id = $new_class_id;
                                        $esg->batch_id = $new_batch_id;
                                        $subjects = explode(",", $esg->subjects);
                                        foreach ($subjects as $key=>$sc) {
                                            if($sc == $old_subject_id){
                                                $subjects[$key] = $new_subject_id;
                                            }
                                        }
                                        $esg->subjects = implode(",", $subjects);
                                        unset($esg->id);
                                        unset($esg->created_at);
                                        unset($esg->updated_at);
                                        unset($esg->deleted_at);
                                        $this->db->insert('sh_subject_groups',$esg);
                                    }

                                }
                            }
                            if($teacher){
                                $old_teacher_assignments = $this->db->select('ta.*')->from('sh_assign_subjects ta')->join('sh_users u','u.id = ta.teacher_id')->where('class_id',$old_class_id)->where('batch_id',$old_batch_id)->where('subject_id',$old_subject_id)->where('ta.deleted_at is null')->where('u.deleted_at',0)->get()->result();
                                foreach($old_teacher_assignments as $ta){
                                    $ta->class_id = $new_class_id;
                                    $ta->batch_id = $new_batch_id;
                                    $ta->subject_id = $new_subject_id;
                                    unset($ta->id);
                                    unset($ta->created_at);
                                    unset($ta->created_at);
                                    unset($ta->created_at);
                                    $this->db->insert('sh_assign_subjects',$ta);
                                }
                            }
                        }
                    }

                    if($period){
                        $new_periods = $this->db->select('*')->from('sh_periods')->where('class_id',$old_class_id)->where('batch_id',$old_batch_id)->where('deleted_at is null')->get()->result();
                        foreach ($new_periods as $p) {
                            $old_period_id = $p->id;
                            $p->academic_year_id = $academic_year_id;
                            $p->class_id = $new_class_id;
                            $p->batch_id = $new_batch_id;
                            unset($p->id);
                            unset($p->created_at);
                            unset($p->updated_at);
                            unset($p->deleted_at);
                            $this->db->insert('sh_periods',$p);
                            $new_period_id = $this->db->insert_id();
                            if($timetable){
                                $old_timetables = $this->db->select('*')->from('sh_timetable_new')->where('period_id',$old_period_id)->where('deleted_at is null')->get()->result();
                                foreach($old_timetables as $t){
                                    $t->academic_year_id = $academic_year_id;
                                    $t->period_id = $new_period_id;
                                    $temp_subject = $t->subject_id;
                                    unset($t->id);
                                    unset($t->created_at);
                                    unset($t->updated_at);
                                    unset($t->deleted_at);
                                    $new_timetable_subject = 0;
                                    foreach($subject_relation as $sr){
                                        if($sr->old_subject == $temp_subject){
                                            $new_timetable_subject = $sr->new_subject;
                                        }
                                    }

                                    if($new_timetable_subject != 0){
                                        $t->subject_id = $new_timetable_subject;
                                        $this->db->insert('sh_timetable_new',$t);
                                    }
                                }
                            }
                        }
                    }
                }
            }

            if($fee){
                $old_fees = $this->db->select('*')->from('sh_fee_types')->where('class_id',$old_class_id)->where('academic_year_id',$year)->where('deleted_at is null')->get()->result();
                foreach ($old_fees as $f) {
                    $f->academic_year_id = $academic_year_id;
                    $f->class_id = $new_class_id;
                    unset($f->id);
                    unset($f->created_at);
                    unset($f->updated_at);
                    unset($f->deleted_at);
                    $this->db->insert('sh_fee_types',$f);
                }
            }
        }

        $data["message"] = "Data imported successfully";
        echo json_encode($data);
    }

    public function revert_academic_year(){
        $academic_year_id = $this->input->post("id");;
        $timestamp = date('Y-m-d H:i:s');
        $this->db->set('deleted_at',$timestamp)->where('academic_year_id',$academic_year_id)->update('sh_classes');
        $this->db->set('deleted_at',$timestamp)->where('academic_year_id',$academic_year_id)->update('sh_fee_types');
        $this->db->set('deleted_at',$timestamp)->where('academic_year_id',$academic_year_id)->update('sh_batches');
        $this->db->set('deleted_at',$timestamp)->where('academic_year_id',$academic_year_id)->update('sh_subjects');
        $this->db->set('deleted_at',$timestamp)->where('academic_year_id',$academic_year_id)->update('sh_subject_groups');
        $this->db->set('deleted_at',$timestamp)->where('academic_year_id',$academic_year_id)->update('sh_periods');
        $this->db->set('deleted_at',$timestamp)->where('academic_year_id',$academic_year_id)->update('sh_timetable_new');
        echo "success";
    }
    
    //added by sheraz
    public function user_management(){
        //check_user_permissions();
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        if (login_user()->user->role_id == ADMIN_ROLE_ID || login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            //----- Count number of users only role base -----//
            $sql = "SELECT count(u.id) as count, role_id as role_id FROM sh_users u WHERE u.school_id='$school_id' AND u.deleted_at='0' AND role_id <> " . STUDENT_ROLE_ID . " GROUP BY role_id ORDER BY role_id ";
            $users = $this->admin_model->dbQuery($sql);
            $data['parent_count'] = $data['student_count'] = $data['employee_count'] = 0;
            foreach ($users as $user) {
                if ($user->role_id == PARENT_ROLE_ID) {
                  $data['parent_count'] = $user->count;
                } else if ($user->role_id == EMPLOYEE_ROLE_ID) {
                  $data['employee_count'] = $user->count;
                }
            }

            $student_count = $this->db->select('count(id) as total_students')->from('sh_students_' . $school_id)->get()->row();
            $data['students'] = array();
            $data['parents'] = array();
            $data['employees'] = array();
            $data['student_count'] = $student_count->total_students;

            $this->load->view('settings/user_management', $data);
        }
    }

    public function getUsers($role_id){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        
        $data = array();
        if($role_id == STUDENT_ROLE_ID) {
            // $sql = "SELECT s.*,c.name as class_name,b.name as batch_name,uu.name as guardian_name,uu.contact as guardian_contact, g.relation as guardian_relation,cc.country_name as nationality, ac_login.login_datetime as login_time, ac_login.logout_datetime as logout_time FROM sh_students_$school_id s LEFT JOIN sh_classes c ON s.class_id=c.id LEFT JOIN sh_batches b ON s.batch_id=b.id LEFT JOIN sh_student_guardians g ON s.id=g.student_id LEFT JOIN sh_users uu ON g.guardian_id=uu.id LEFT JOIN sh_countries cc ON s.nationality=cc.id LEFT JOIN sh_activities ac_login ON ac_login.user_id=s.id WHERE s.school_id=$school_id AND s.deleted_at='0' GROUP BY s.id";
            $sql = "SELECT 
                    u.*,
                    cr.class_id,
                    cr.batch_id,
                    cr.subject_group_id,
                    cr.discount_id,
                    cr.academic_year_id,
                    c.name as class_name,
                    b.name as batch_name,
                    uu.name as guardian_name,
                    uu.contact as guardian_contact, 
                    g.relation as guardian_relation,
                    cc.country_name as nationality, 
                    ac_login.login_datetime as login_time, 
                    ac_login.logout_datetime as logout_time 
                    FROM sh_users u
                    LEFT JOIN sh_student_class_relation cr ON cr.student_id=u.id 
                    INNER JOIN sh_classes c ON cr.class_id=c.id 
                    LEFT JOIN sh_batches b ON cr.batch_id=b.id 
                    LEFT JOIN sh_student_guardians g ON cr.student_id=g.student_id 
                    LEFT JOIN sh_users uu ON g.guardian_id=uu.id 
                    LEFT JOIN sh_countries cc ON u.nationality=cc.id 
                    LEFT JOIN sh_activities ac_login ON ac_login.user_id=u.id 
                    WHERE cr.school_id=$school_id AND cr.deleted_at is NULL AND cr.academic_year_id=$academic_year_id  GROUP BY u.id";
            //$sql = "SELECT s.*,c.name as class_name,b.name as batch_name,uu.name as guardian_name,uu.contact as guardian_contact, g.relation as guardian_relation,cc.country_name as nationality FROM sh_students_$school_id s LEFT JOIN sh_classes c ON s.class_id=c.id LEFT JOIN sh_batches b ON s.batch_id=b.id LEFT JOIN sh_student_guardians g ON s.id=g.student_id LEFT JOIN sh_users uu ON g.guardian_id=uu.id LEFT JOIN sh_countries cc ON s.nationality=cc.id WHERE s.school_id=$school_id AND s.deleted_at='0' GROUP BY s.id";
            //$sql = "SELECT s.*,c.name as class_name, b.name as batch_name, IFNULL(uu.name,'') as guardian_name, IFNULL(uu.contact,'') as guardian_contact, IFNULL(g.relation,'') as guardian_relation, IFNULL(cc.country_name,'') as nationality, (SELECT datetime FROM sh_activities WHERE user_id=s.id AND tag='login' ORDER BY id DESC LIMIT 1) as login_time, (SELECT datetime FROM sh_activities WHERE user_id=s.id AND tag='logout' ORDER BY id DESC LIMIT 1) as logout_time FROM sh_students_$school_id s INNER JOIN sh_classes c ON s.class_id=c.id INNER JOIN sh_batches b ON s.batch_id=b.id LEFT JOIN sh_student_guardians g ON s.id=g.student_id LEFT JOIN sh_users uu ON g.guardian_id=uu.id LEFT JOIN sh_activities a ON s.id=a.user_id LEFT JOIN sh_countries cc ON s.nationality=cc.id WHERE s.school_id='$school_id' AND s.deleted_at='0' GROUP BY s.id";
            $data = $this->admin_model->dbQuery($sql);
            
            foreach($data as $key => $d)
            {
                $rec=$this->db->select('*')->from('sh_activities')->where('user_id',$d->id)->order_by('user_id','desc')->limit(1)->get()->row();
                if(!empty($rec))
                {
                    $data[$key]->login_time=$rec->login_datetime;
                    $data[$key]->logout_time=$rec->logout_datetime;
                }
            }
        } else if($role_id == EMPLOYEE_ROLE_ID) {
            $sql = "SELECT u.id,
                u.role_id as role_id, 
                IFNULL(u.avatar,null) as avatar, 
                IFNULL(u.name,'') as name, 
                IFNULL(u.gender,'') as gender, 
                IFNULL(u.dob,'') as dob, 
                IFNULL(u.blood,'') as blood, 
                IFNULL(u.birthplace,'') as birthplace, 
                IFNULL(cc.country_name,'') as nationality, 
                IFNULL(u.religion,'') as religion,
                IFNULL(u.job_title,'') as job_title, 
                IFNULL(u.city,'') as city, 
                IFNULL(c.id,'') as role_category_id,
                IFNULL(u.address,'') as address, 
                IFNULL(u.email,'') as email, 
                IFNULL(u.contact,'') as contact, 
                u.status, 
                c.category as role_category, 
                d.name as title_department,
                ac_login.login_datetime as login_time,
                ac_login.logout_datetime as logout_time
                FROM sh_users u 
                LEFT JOIN sh_role_categories c ON u.role_category_id=c.id 
                LEFT JOIN sh_departments d ON u.department_id=d.id 
                LEFT JOIN sh_countries cc ON u.nationality=cc.id 
                LEFT JOIN sh_activities ac_login ON u.id=ac_login.user_id
                WHERE 
                u.role_id=".EMPLOYEE_ROLE_ID." 
                AND u.school_id=$school_id 
                AND u.deleted_at=0 
                GROUP BY u.id";
               
            $data = $this->admin_model->dbQuery($sql);
            
            
            foreach($data as $key => $d)
            {
                $d->guardian_name = null;
                $rec=$this->db->select('*')->from('sh_activities')->where('user_id',$d->id)->order_by('user_id','desc')->limit(1)->get()->row();
                if(!empty($rec))
                {
                    $data[$key]->login_time=$rec->login_datetime;
                    $data[$key]->logout_time=$rec->logout_datetime;
                }
            }
        } else if($role_id == PARENT_ROLE_ID) {
            $sql = "SELECT u.id,
                u.role_id as role_id, 
                IFNULL(u.avatar,null) as avatar, 
                IFNULL(u.name,'') as name, 
                IFNULL(u.gender,'') as gender, 
                IFNULL(u.dob,'') as dob, 
                IFNULL(u.blood,'') as blood, 
                IFNULL(u.birthplace,'') as birthplace, 
                IFNULL(c.country_name,'') as nationality, 
                IFNULL(u.religion,'') as religion, 
                IFNULL(u.city,'') as city, 
                IFNULL(u.address,'') as address, 
                IFNULL(u.email,'') as email, 
                IFNULL(u.contact,'') as contact, 
                u.status,
                ac_login.login_datetime as login_time,
                ac_login.logout_datetime as logout_time
                FROM sh_users u
                LEFT JOIN sh_countries c ON u.nationality=c.id
                LEFT JOIN sh_activities ac_login ON ac_login.user_id=u.id
                WHERE 
                u.role_id=".PARENT_ROLE_ID." 
                AND u.school_id=$school_id 
                AND u.deleted_at=0 
                GROUP BY u.id";
            $data = $this->admin_model->dbQuery($sql);
            
            foreach($data as $key => $d)
            {
                $rec=$this->db->select('*')->from('sh_activities')->where('user_id',$d->id)->order_by('user_id','desc')->limit(1)->get()->row();
                if(!empty($rec))
                {
                    $data[$key]->login_time=$rec->login_datetime;
                    $data[$key]->logout_time=$rec->logout_datetime;
                }
            }
        }

        echo json_encode($data);
    }
    
    public function online_admissions(){
        $this->load->view("settings/online_admissions.php");
    }
    public function new_page_settings(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_lp_gallery_images');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->show_primary_ai_field(false);
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->columns('image, status');
    $xcrud->fields('image, status');
    $xcrud->label('image', lang('lbl_image'))->label('status', lang('lbl_status'));
    $xcrud->change_type('image','image','',array('width'=>450, 'height'=>450, 'crop'=>true));
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    $data['gallery'] = $xcrud->render();


    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_lp_teachers');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->show_primary_ai_field(false);
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->columns('name, designation, description, image, status');
    $xcrud->fields('name, designation, description, image, status');
    $xcrud->label('name', lang('lbl_name'))->label('designation', lang('lbl_designation'))->label('description', lang('description'))->label('image', lang('lbl_image'))->label('status', lang('lbl_status'));
    $xcrud->change_type('image','image','',array('width'=>370, 'height'=>526, 'crop'=>true));
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    $data['teachers'] = $xcrud->render();


    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_lp_classes');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->show_primary_ai_field(false);
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->columns('class_name, description, status');
    $xcrud->fields('class_name, description, status');
    $xcrud->label('class_name', lang('class_name'))->label('description', lang('description'))->label('status', lang('lbl_status'));
        //$xcrud->change_type('image','image','',array('height'=>300));
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    $data['classes'] = $xcrud->render();


    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_lp_slider');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->show_primary_ai_field(false);
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->columns('title, sub_title, description, status, image');
    $xcrud->fields('title, sub_title, description, status, image');
    $xcrud->label('title', lang('lbl_tbl_title'))->label('description', lang('description'))->label('sub_title', lang('sub_title'))->label('status', lang('lbl_status'))->label('image', lang('lbl_image'));
    $xcrud->change_type('image','image','',array('width'=>1920, 'height'=>772, 'crop'=>true));
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    $data['slider'] = $xcrud->render();


    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_lp_background_images');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->show_primary_ai_field(false);
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->columns('background, status, image');
    $xcrud->fields('background, status, image');
    $xcrud->label('background', lang('background'))->label('status', lang('lbl_status'))->label('image', lang('lbl_image'));
    $xcrud->change_type('image','image','',array('width'=>1920, 'height'=>772, 'crop'=>true));
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    $data['background'] = $xcrud->render();


    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_lp_video');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->show_primary_ai_field(false);
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->columns('link, title, description, status, image');
    $xcrud->fields('link, title, description, status, image');
    $xcrud->label('link', lang('youtube_video_link'))->label('title', lang('lbl_tbl_title'))->label('status', lang('lbl_status'))->label('image', lang('thumbnail'))->label('description', lang('description'));
    $xcrud->change_type('image','image','',array('height'=>600, 'width'=>434));
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    $data['video'] = $xcrud->render();


    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_lp_social_links');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->show_primary_ai_field(false);
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->columns('social_site, link, status');
    $xcrud->fields('social_site, link, status');
    $xcrud->label('social_site', lang('social_site'))->label('link', lang('lbl_link'))->label('status', lang('lbl_status'));
        //$xcrud->change_type('image','image','',array('height'=>300));
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    $data['social'] = $xcrud->render();


    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_lp_news');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->show_primary_ai_field(false);
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->columns('title, description, status');
    $xcrud->fields('title, description, status');
    $xcrud->label('title', lang('lbl_tbl_title'))->label('description', lang('description'))->label('status', lang('lbl_status'));
        //$xcrud->change_type('image','image','',array('height'=>300));
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    $data['news'] = $xcrud->render();

    $this->load->view("settings/new_page_settings.php", $data);
    
    }

    public function updateStats(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $res = $this->db->query("SELECT * FROM sh_lp_stats WHERE school_id='$school_id' AND deleted_at is NULL")->result();
        //print_r($res[0]->id); die();

        if (count($res) == 0){
            $data = array(
                'school_id' =>$school_id,
                'total_students' =>$request->students,
                'total_classes' =>$request->classes,
                'total_employees' =>$request->emp,
                'total_bus'=>$request->bus
            );
            $this->common_model->insert("sh_lp_stats", $data);
            $response = array("status"=>"success","message"=>"School statistics updated successfully");
            echo json_encode($response);
        } else {
            $data = array(
                'school_id' =>$school_id,
                'total_students' =>$request->students,
                'total_classes' =>$request->classes,
                'total_employees' =>$request->emp,
                'total_bus'=>$request->bus
            );
            $this->common_model->update("sh_lp_stats", $data);
            $response = array("status"=>"success","message"=>"School statistics updated successfully");
            echo json_encode($response);
        }
        
    }

    public function showStats(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $res = $this->db->query("SELECT * FROM sh_lp_stats WHERE school_id='$school_id' AND deleted_at is NULL")->result();
        if (count($res) > 0) {
            $data = array(
                'students'=>$res[0]->total_students,
                'classes'=>$res[0]->total_classes,
                'employees'=>$res[0]->total_employees,
                'bus'=>$res[0]->total_bus
            );
            $response = array("data"=>$data);
            echo json_encode($response);
        }
    }

public function email_settings()
{

    
    $this->load->view("settings/email_settings");
}

public function getEmails()
{
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = $this->session->userdata("userdata")["sh_id"];
    // echo $request->id; die();
    $data['emails'] = $this->db->select('*')->from('sh_emails')->where('school_id', $id)->get()->result();
    echo json_encode($data);
}


    public function resendEmail(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $em= $this->db->select('*')->from('sh_emails')->where('id', $request->id)->get()->result();
        foreach($em as $email)
        {
            $ema = $email->r_email;
        }
        // print_r($ema);die();
        $this->common_model->update_where("emails", array("r_email"=>$ema), array("state"=>"pending"));
        echo json_encode(array("status" => "success", "message" => "Email resend successfully"));
    } 
    
    //google drive settings
    public function enable_googledrive()
    {
        $sch_id=$this->session->userdata("userdata");
        $sch_id=$sch_id['sh_id'];
        $gd_tokenfile='token_'.$sch_id.'.json';

        $client = getClient($sch_id,$gd_tokenfile);
        $url = $client->createAuthUrl();
        header("Location:".$url);
    }

    public function generate_token()
    {
      if(isset($_GET['code']) && !empty($_GET['code']))
      {
        $sch_id=$this->session->userdata("userdata");
        $sch_id=$sch_id['sh_id'];
        $gd_tokenfile='token_'.$sch_id.'.json';
        $code=$_GET['code'];
  
        $client = getClient($sch_id,$gd_tokenfile);
        $access_token=$client->fetchAccessTokenWithAuthCode($code);
        $client->setAccessToken($access_token);
  
        if(!file_exists($gd_tokenfile))
        { 
          $fp = fopen($gd_tokenfile, 'w');
          fwrite($fp, json_encode($client->getAccessToken()));
          fclose($fp);
          chmod($gd_tokenfile, 0755); 
        }
        $this->db->where('id', $sch_id);
        $this->db->update('sh_school', array('enable_gd' => 1));

        $sess_changdata=array();
        $sess_changdata=$this->session->userdata("userdata");
        $sess_changdata['sh_enable_gd']=1;
        $this->session->set_userdata(array("userdata" => $sess_changdata));
        
        $this->session->set_flashdata('alert', array("status" => "success", "message" => 'Google Drive integrated successfully!'));
        redirect("settings/google_drive", "refresh");
      }
    }

    public function upload_credentialsfile()
    {
        $school_id = $this->session->userdata("userdata")["sh_id"];

        if (isset($_FILES["gd_credentials"]))
        {
            if (!empty($_FILES["gd_credentials"]["name"]))
            {
                $target_file_c = basename($_FILES["gd_credentials"]["name"]);
                $imageFileType_c = strtolower(pathinfo($target_file_c, PATHINFO_EXTENSION));
                
                $_FILES["gd_credentials"]["name"]='credentials_'.$school_id.'.json';
                
                $target_file = basename($_FILES["gd_credentials"]["name"]);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                
                 

                // Allow certain file formats
                if ($imageFileType_c!='json') {
                    $this->session->set_flashdata('alert', array("status" => "alert", "message" => 'Only Google Drive JSON file is allowed'));
                    redirect("settings/google_drive", "refresh");
                }
        
                // Check if file already exists
                if (file_exists($target_file)) {
                    chmod($target_file, 0755);
                    unlink($target_file);
                }
                // echo $target_file;
                // die;

                $sess_changdata=array();
                $sess_changdata=$this->session->userdata("userdata");
                $sess_changdata['sh_enable_gd']=0;
                $this->session->set_userdata(array("userdata" => $sess_changdata));
        
                if (move_uploaded_file($_FILES["gd_credentials"]["tmp_name"], $target_file)) {
                    $this->session->set_flashdata('alert', array("status" => "success", "message" => 'File uploaded successfully!'));
                    // $this->session->set_flashdata("selected_tab", "enable_googledrive");
                    redirect("settings/google_drive", "refresh");
                } else {
                    $this->session->set_flashdata('alert', array("status" => "alert", "message" => 'Error! please try again'));
                    redirect("settings/google_drive", "refresh");
                }
            } 
            
        }
        else
        {
            $this->session->set_flashdata('alert', array("status" => "alert", "message" => 'Something went wrong, please try again! '));
            redirect("settings/google_drive", "refresh");
        }
    }

    public function googledrive_guide()
    {
        $this->load->view("googledrive_userguide/index.php");
    }
    
    public function remove_credentialsfile()
    {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $cre_file = 'credentials_'.$school_id.'.json';
        if(file_exists($cre_file))
        {
            $del_file=unlink($cre_file);
            if($del_file)
            {
                $this->session->set_flashdata('alert', array("status" => "success", "message" => 'Integration file removed successfully!'));
                redirect("settings/google_drive", "refresh");
            }
        }
        else
        {
            $this->session->set_flashdata('alert', array("status" => "error", "message" => 'Integration file is not exist!'));
            redirect("settings/google_drive", "refresh");
        }
    }
    public function googledrive_disable()
    {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $cre_file = 'credentials_'.$school_id.'.json';
        $tok_file = 'token_'.$school_id.'.json';
        $res=$this->common_model->update_where("sh_school", array("id"=>$school_id), array("enable_gd"=>0));
        if($res)
        {
            $file1=unlink($cre_file);
            $file2=unlink($tok_file);
            if($file1 && $file2)
            {
                $sess_changdata=array();
                $sess_changdata=$this->session->userdata("userdata");
                $sess_changdata['sh_enable_gd']=0;
                $this->session->set_userdata(array("userdata" => $sess_changdata));

                // $this->session->set_flashdata("selected_tab", "enable_googledrive");
                $this->session->set_flashdata('alert', array("status" => "success", "message" => 'Google Drive disable successfully!'));
            }
            else
            {
                $this->session->set_flashdata('alert', array("status" => "alert", "message" => 'Something went wrong please try again!'));
                // $this->session->set_flashdata("selected_tab", "enable_googledrive");
            }
            
        }
        else
        {
            $this->session->set_flashdata('alert', array("status" => "alert", "message" => 'Something went wrong please try again!'));
            // $this->session->set_flashdata("selected_tab", "enable_googledrive");
        }
        redirect("settings/google_drive", "refresh");
    }
    
    public function googledrive_integration()
    {
        $this->session->set_flashdata("selected_tab", "enable_googledrive");
        redirect("settings/general", "refresh");
    }
    
    public function google_drive()
    {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $sh_enable_gd = $this->session->userdata("userdata")["sh_enable_gd"];
        $logged_roleid = $this->session->userdata("userdata")["role_id"];
        $data['logged_roleid']=$logged_roleid;
        $data['enable_gd']=$sh_enable_gd;
        $data['credentials_fileid']='credentials_'.$school_id.'.json';
        $this->load->view("settings/googledrive_settings",$data);
    }
    
   //google drive settings

  public function updateThemeSettings(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = array(
                'heading_font_size' =>$request->heading_size,
                'heading_color' =>$request->heading_color,
                'sub_heading_font_size' =>$request->sub_heading_size,
                'sub_heading_color'=>$request->sub_heading_color,
                'description_font_size'=>$request->description_size,
                'description_color'=>$request->description_color
            );
            
            $this->common_model->update_where("sh_lp_slider", array("school_id" => $school_id), $data);
            
            $response = array("status"=>"success","message"=>"Slider theme updated successfully");
            echo json_encode($response); 
    }

    public function showTheme(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $res = $this->db->query("SELECT * FROM sh_lp_slider WHERE school_id='$school_id' AND deleted_at is NULL")->result();
        
        if (count($res) > 0) {
            $data = array(
                'heading_font_size'=>$res[0]->heading_font_size,
                'heading_color'=>$res[0]->heading_color,
                'sub_heading_font_size'=>$res[0]->sub_heading_font_size,
                'sub_heading_color'=>$res[0]->sub_heading_color,
                'description_font_size'=>$res[0]->description_font_size,
                'description_color'=>$res[0]->description_color
            );
            $response = array("data"=>$data);
            echo json_encode($response);
        }
    }
    // for create view force if view not create or delete due to any reason
    public function make_default_view($school_id){
    $ci = & get_instance();
    $ci->db->query("create view sh_students_".$school_id." AS
        select u.*,cr.class_id,cr.batch_id,cr.subject_group_id,cr.academic_year_id,cr.discount_id from sh_users u inner join sh_student_class_relation cr on u.id = cr.student_id where cr.academic_year_id = (select id from sh_academic_years where school_id = ".$school_id." and is_active = 'Y' and deleted_at is null) and u.role_id = 3 and u.deleted_at = 0 and u.school_id = ".$school_id."  and cr.deleted_at is null");
    }


    
}
