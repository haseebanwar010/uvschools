<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Settings extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login/index"));
        }
        check_user_permissions();
    }

    public function general() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["countries"] = $this->common_model->get_where("sh_countries", "*")->result();
        $data["departments"] = $this->admin_model->dbSelect("*","departments"," deleted_at=0 AND school_id='$school_id' ");
        $data["generalSettings"] = $this->common_model->get_where("sh_school", "id", $school_id)->result();
        $data["working_days"]= json_decode($data["generalSettings"][0]->working_days);
        $data["timezones"] = $this->common_model->time_zone_list();
        $data['symbols'] = $this->admin_model->dbSelect('*','currency'," 1 ");
        $this->load->view("settings/general-settings", $data);
    }
    
    public function saveGeneralInfo() {
        $form_data = $this->input->post(); 
        $school_id   = $this->session->userdata("userdata")["sh_id"];
        $workingdays = $this->admin_model->dbSelect("working_days","school"," id='$school_id' ");   
        if (isset($_FILES["fileToUpload"])) {
            if (!empty($_FILES["fileToUpload"]["name"])) {
                $form_data["logo"] = $_FILES["fileToUpload"]["name"];
                if ($this->upload()) {
                    $oldValues = $this->session->userdata("userdata");
                    $oldValues["sh_logo"] = $form_data["logo"];
                    $this->session->set_userdata("userdata",$oldValues);
                    $this->common_model->update("sh_school", $form_data, $this->session->userdata("userdata")["sh_id"]);
                    $this->session->set_flashdata("selected_tab", "general");
                }
            } else {
                $this->common_model->update("sh_school", $form_data, $this->session->userdata("userdata")["sh_id"]);
                $this->session->set_flashdata("selected_tab", "general");
                $this->session->set_flashdata('alert', array("status" => "success", "message" => "School info update successfully."));
            }
            $oldValues = $this->session->userdata("userdata");
            $oldValues["sh_name"] = $form_data["name"];
            $this->session->set_userdata("userdata",$oldValues);
            redirect("settings/general", "refresh");
        } else {
            if ($this->input->post("address") !== FALSE) {
                $this->session->set_flashdata("selected_tab", "address");
                $this->session->set_flashdata('alert', array("status" => "success", "message" => "Address settings update successfully."));
            } else {
                $userinputs = $this->input->post("working_days");
                $form_data["financial_year_start_date"] = to_mysql_date($this->input->post("financial_year_start_date"));
                $form_data["financial_year_end_date"] = to_mysql_date($this->input->post("financial_year_end_date"));
                $obj = json_decode($workingdays[0]->working_days);

                foreach($obj as $key => $object) {
                    if(in_array($object->label,$userinputs)) {
                        $object->val = 'true';
                //unset($obj[$key]);
                    }
                    else{
                        $object->val = 'false';
                    }
                }

                $form_data["working_days"] = json_encode($obj);
                $symbol_code = $this->input->post('currency_symbol');
                $sym = $this->admin_model->dbSelect("symbol","currency"," code='$symbol_code' ")[0]->symbol;
                
                $oldValues = $this->session->userdata("userdata");
                $oldValues["theme_color"] = $form_data["theme_color"];
                $oldValues["currency_symbol"] = $sym;
                $oldValues["time_zone"] = $this->input->post('time_zone');
                $this->session->set_userdata("userdata",$oldValues);
                $this->session->set_flashdata("selected_tab", "others");
                $this->session->set_flashdata('alert', array("status" => "success", "message" => "Other information update successfully."));
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
                $this->session->set_flashdata('alert', array("status" => "error", "message" => "File is an image - " . $check["mime"] . "."));
                return false;
            } else {
                $this->session->set_flashdata('alert', array("status" => "error", "message" => "File is not an image."));
                return false;
            }
        }

        // Check file size
        if ($_FILES["fileToUpload"]["size"] > 1000000) {
            $this->session->set_flashdata('alert', array("status" => "error", "message" => "Sorry, your file is too large."));
            return false;
        }
        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            $this->session->set_flashdata('alert', array("status" => "error", "message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed."));
            return false;
        }

        // Check if file already exists
        if (file_exists($target_file)) {
            chmod($target_file, 0755);
            unlink($target_file);
        }

        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $this->session->set_flashdata('alert', array("status" => "success", "message" => "Image updated successfully."));
            return true;
        } else {
            $this->session->set_flashdata('alert', array("status" => "error", "message" => "Sorry, there was an error uploading your file."));
            return false;
        }
    }

    /////////// By Shahzaib 21-12-2017 ///////////////////
    public function hr() {
        $UserData = $this->session->userdata('userdata');
        //$data['roles'] = $this->common_model->getUserRoles();
        $data['departments'] = $this->admin_model->dbSelect("*", "departments", " role_id=4 AND school_id=" . $UserData['sh_id'] . " AND deleted_at=0 ");
        $data['role_categories'] = $this->common_model->getRoleCategories($UserData['sh_id']);
        //echo "<pre />";
        //print_r($data['role_categories']);
        //die();
        //$data["departments"] = $this->admin_model->dbSelect("*","departments"," deleted_at=0 AND  school_id='".$this->session->userdata('userdata')['sh_id']."'");
        $this->load->view('hr-settings/index', $data);
    }

    public function addCategory() {
        $data['school_id'] = $this->input->post('school_id');
        $data['category'] = $this->input->post('category');
        $data['department_id'] = $this->input->post('department_id');
        $data['role_id'] = EMPLOYEE_ROLE_ID;
        $insert_id = $this->common_model->insert("role_categories", $data);
        if ($insert_id) {
            echo $insert_id;
        }
    }

    public function removeCategory() {
        $cat_id = $this->input->post('cat_id');
        $data['deleted_at'] = 1;
        $this->common_model->editRecord('id', $cat_id, 'role_categories', $data);
    }

    public function editCategory() {
        $cat_id = $this->input->post('cat_id');
        $result = $this->common_model->getRoleCategoriesById($cat_id);
        print_r(json_encode($result));
    }

    public function editCategorySucess() {
        $cat_id = $this->input->post('cat_id');
        $data['category'] = $this->input->post('category');
        $data['role_id'] = $this->input->post('role_id');
        $this->common_model->editRecord('id', $cat_id, 'role_categories', $data);
        echo 'updated';
    }

    /////////// BY YASIR 21-12-2017 ///////////
    public function saveDepartment() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->admin_model->dbSelect("*", "departments", " code='" . $request->code . "' AND deleted_at=0 AND school_id='" . $this->session->userdata('userdata')["sh_id"] . "' ");
        if (count($res) > 0) {
            echo json_encode(array("status" => "error", "message" => "Department with this code already exist!"));
        } else {
            $data = array("school_id" => $this->session->userdata("userdata")["sh_id"], "name" => $request->name, "code" => $request->code, "role_id" => EMPLOYEE_ROLE_ID);
            $res = $this->admin_model->dbInsert("departments", $data);
            if ($res > 0) {
                echo json_encode(array("status" => "success", "message" => lang('msg_department_created_successfully')));
            } else {
                echo json_encode(array("status" => "error", "message" => lang('msg_department_create_error')));
            }
            $this->session->set_flashdata("hr_selected_tab", "department");
        }
    }

    public function deleteDepartment() {
        $id = $this->input->post("id");
        $this->common_model->update_where("sh_departments", array("id" => $id), array("deleted_at" => 1));
        $this->session->set_flashdata("hr_selected_tab", "department");
        echo "success";
        //redirect("settings/hr","refresh");
    }

    public function getDepartment() {
        $id = $this->input->post("id");
        $data = $this->common_model->get_where("sh_departments", "id", $id)->row();
        echo json_encode($data);
    }

    public function updateDepartment() {
        $id = $this->input->post("id");
        $name = $this->input->post("name");
        $code = $this->input->post("code");
        $where = array("id" => $id);
        $data = array("name" => $name, "code" => $code);
        $this->common_model->update_where("sh_departments", $where, $data);
        $this->session->set_flashdata('alert', array("status" => "success", "message" => "Department updated successfully"));
        $this->session->set_flashdata("hr_selected_tab", "department");
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function academic() {

        $params = $this->input->get();
        $data["selected_tab"] = "";
        $data["tab_batches_selected_class_id"] = "all";
        $data["tab_subjects_selected_class_id"] = "all";
        $data["tab_subjects_selected_batch_id"] = "all";
        $data["tab_periods_selected_class_id"] = "all";
        $data["tab_periods_selected_batch_id"] = "all";

        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_academic_years');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        $xcrud->relation('id', 'sh_schools', 'school_id', 'academic_years', 'deletet_at=0 AND school_id=' . $this->session->userdata("userdata")["sh_id"]);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name, start_date, end_date, is_active');
        $xcrud->fields('name, start_date, end_date, is_active');
        $xcrud->label('name', lang('lbl_name'))->label('start_date', lang('start_date'))->label('end_date', lang('end_date'))->label('is_active',lang('is_active'));
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->replace_remove('soft_delete');
        $xcrud->table_name(lang('lbl_academic_year'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->before_insert('deleteOtherPrimary');
        $xcrud->before_update('deleteOtherPrimary');
        $xcrud->column_callback('is_active', 'primaryicon');
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $data["academic_years"] = $xcrud->render();

        /* Periods */
        $periods = xcrud_get_instance();
        $periods->table('sh_periods');
        $periods->show_primary_ai_field(false);
        
        //$periods->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
        
        $periods->columns('title,start_time,end_time,class_id,batch_id,is_break');
        $periods->fields('title,start_time,end_time,class_id,batch_id,is_break');
        
        $periods->relation('class_id','sh_classes','id','name','deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
        $periods->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"],'','','','','class_id','class_id');
        $periods->label('class_id',lang('lbl_class'))->label('batch_id',lang('lbl_batch'));
        $periods->label('title',lang('lbl_tbl_title'))->label('start_time',lang('start_time'));
        $periods->label('end_time',lang('end_time'))->label('is_break',lang('break'));
        $periods->order_by('start_time','asc');
        $periods->replace_remove('soft_delete');
        $periods->load_view("view", "customview.php");
        $periods->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $periods->table_name('Periods');
        $periods->before_insert('checkOverlap');
        $periods->replace_insert('periodsAdd');
        $periods->before_update('section_check');
        $periods->unset_print();
        $periods->unset_csv();
        $periods->unset_title();
        
        $periods->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        
        if($params !== null){
            if($params["tab"] === "tab_periods"){
                $data["selected_tab"] = $params["tab"];
                $data["tab_periods_selected_class_id"] = $params["class_id"];
                $data["tab_periods_selected_batch_id"] = $params["batch_id"];
                if ($params["class_id"] != "all") {
                    if ($params["batch_id"] == "all") {
                        $data2 = $this->admin_model->dbSelect("id", "batches", " class_id=".$params['class_id']." ");
                        $array = array();
                        foreach ($data2 as $value) {
                            $array[] = $value->id;
                        }

                        if (count($array) > 0) {
                            $periods->where('batch_id', $array);
                        }else {
                            $periods->where('batch_id', "0");
                        }
                    }else {
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
        $classes->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        $classes->relation('id', 'sh_schools', 'school_id', 'classes', 'deletet_at IS NULL AND school_id=' . $this->session->userdata("userdata")["sh_id"]);
        $classes->show_primary_ai_field(false);
        $classes->columns('name, code, grading_type');
        $classes->fields('name, code, grading_type');
        $classes->label('name',lang('lbl_name'));
        $classes->label('code',lang('lbl_code'));
        $classes->label('grading_type',lang('lbl_grading_type'));
        $classes->replace_remove('soft_delete');
        $classes->load_view("view", "customview.php");
        $classes->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $classes->table_name(lang('lbl_classes'));
        $classes->unset_print();
        $classes->unset_csv();
        $data["classes"] = $classes->render();

        
        
        /* ******** Batches xcrud *********** */
        $batches = xcrud_get_instance('batchesx');
        $batches->table('sh_batches');
        $batches->join('class_id','sh_classes','id');
        $batches->join('academic_year_id','sh_academic_years','id');
        $batches->where("school_id", $this->session->userdata("userdata")["sh_id"])->where("sh_batches.deleted_at IS NULL AND sh_classes.deleted_at IS NULL AND sh_academic_years.deleted_at IS NULL AND sh_academic_years.is_active = 'Y'");
        
        $batches->show_primary_ai_field(false);
        $batches->columns('sh_batches.name,class_id,academic_year_id,teacher_id,start_date,end_date');
        $batches->fields('name,class_id,academic_year_id,teacher_id,start_date,end_date');
        $batches->relation('class_id', 'sh_classes', 'id', 'name', "sh_classes.deleted_at IS NULL AND sh_classes.school_id='" . $this->session->userdata("userdata")["sh_id"] . "'");
        $batches->relation('academic_year_id', 'sh_academic_years', 'id', 'name', "sh_academic_years.deleted_at IS NULL AND sh_academic_years.school_id='" . $this->session->userdata("userdata")["sh_id"] . "'");
        $batches->relation('teacher_id','sh_users','id','name',"sh_users.deleted_at = 0 AND sh_users.school_id='" . $this->session->userdata("userdata")["sh_id"] . "' AND department_id = '".$this->session->userdata("userdata")["teacher_dept_id"]."'");
        $batches->label('name',lang('lbl_batch'));
        $batches->label('class_id',lang('lbl_class'));
        $batches->label('academic_year_id',lang('lbl_academic_year'));
        $batches->label('start_date',lang('start_date'));
        $batches->label('end_date',lang('end_date'))->label('teacher_id',lang('lbl_teacher'));
        $batches->replace_remove('soft_delete');
        $batches->load_view("view", "customview.php");
        $batches->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $batches->table_name(lang('all_batches'));
        $batches->replace_insert('addSection');
        $batches->unset_print();
        $batches->unset_csv();
        $batches->unset_title();
        if($params !== null){
            if($params["tab"] === "tab_batches"){
                $data["selected_tab"] = "tab_batches";
                $data["tab_batches_selected_class_id"] = $params["class_id"];
                if ($params["class_id"] != "all") {
                    $batches->where('class_id', $params["class_id"]);
                }   
            }
        }
        
        $data["batches"] = $batches->render();

        /****************** Subjects xcurd ****************** */
        $subjects = xcrud_get_instance();
        $subjects->table('sh_subjects');
        $subjects->join('batch_id','sh_batches','id');
        $subjects->join('class_id','sh_classes','id');
        $subjects->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('sh_subjects.deleted_at IS NULL and sh_batches.deleted_at IS NULL and sh_classes.deleted_at IS NULL ');
        
        $subjects->show_primary_ai_field(false);
        $subjects->columns('name,code,class_id,batch_id,weekly_classes');
        $subjects->fields('name,code,class_id,batch_id,weekly_classes');
        $subjects->relation('class_id','sh_classes','id','name','deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
        $subjects->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"],'','','','','class_id','class_id');
        $subjects->label('class_id',lang('lbl_class'));
        $subjects->label('name', lang('lbl_subject'))->label('batch_id', lang('lbl_batch'))->label('code', lang('lbl_code'))->label('weekly_classes', lang('lbl_weekly_classes'));
        $subjects->replace_remove('soft_delete');
        $subjects->load_view("view", "customview.php");
        $subjects->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $subjects->table_name(lang('all_subjects'));
        $subjects->before_insert('section_count');
        $subjects->replace_insert('subjectsAdd');
        $subjects->before_update('section_check');
        $subjects->unset_print();
        $subjects->unset_csv();
        $subjects->unset_title();

        if($params !== null){
            if($params["tab"] === "tab_subjects"){
                $data["selected_tab"] = $params["tab"];
                $data["tab_subjects_selected_class_id"] = $params["class_id"];
                $data["tab_subjects_selected_batch_id"] = $params["batch_id"];
                if ($params["class_id"] != "all") {
                    if ($params["batch_id"] == "all") {
                        $data2 = $this->admin_model->dbSelect("id", "batches", " class_id=".$params['class_id']." ");
                        $array = array();
                        foreach ($data2 as $value) {
                            $array[] = $value->id;
                        }
                        if (count($array) > 0) {
                            $subjects->where('batch_id', $array);
                        } else {
                           $subjects->where('batch_id', "0");
                       }
                   }else {
                    $subjects->where('batch_id', $params["batch_id"]);
                }
            }
        }
    }

    $data["subjects"] = $subjects->render();
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

            $periods->relation('class_id','sh_classes','id','name','deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
            $periods->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"],'','','','','class_id','class_id');

            $periods->replace_remove('soft_delete');
            $periods->load_view("view", "customview.php");
            $periods->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
            $periods->table_name('Filter Periods');
            $periods->unset_print();
            $periods->unset_csv();
            echo $periods->render();
        } else if($request->class_id != "all") {
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
                $periods->table_name('Filter Periods');
                $periods->unset_print();
                $periods->unset_csv();
                echo $periods->render();
            } else {
                echo lang('no_record');
            }
        }
    }

    public function getSchoolClasses() {
        $data = $this->admin_model->dbSelect("*", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL ");
        echo json_encode($data);
    }

    public function getClassBatches() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        if ($request->id != "all") {
            $query = " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND class_id=$request->id AND academic_year_id=(Select id from sh_academic_years Where school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND is_active='Y') AND deleted_at IS NULL ";
                //$subjects->where('batch_id', $request->batch_id);
        } else {
            $query = " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND academic_year_id=(Select id from sh_academic_years Where school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND is_active='Y') AND deleted_at IS NULL ";
        }

        $data = $this->admin_model->dbSelect("*", "batches", $query);
        echo json_encode($data);
    }

    public function getSubjects() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $subjects = xcrud_get_instance();
        $subjects->table('sh_subjects');
        $subjects->join('batch_id','sh_batches','id');
        $subjects->join('class_id','sh_classes','id');
        $subjects->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('sh_subjects.deleted_at IS NULL and sh_batches.deleted_at IS NULL and sh_classes.deleted_at IS NULL ');
        if ($request->batch_id != "all") {
            $subjects->where('batch_id', $request->batch_id);
            $subjects->show_primary_ai_field(false);

                //$subjects->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);

            $subjects->columns('name,code,class_id,batch_id,weekly_classes');
            $subjects->fields('name,code,class_id,batch_id,weekly_classes');

            $subjects->relation('class_id','sh_classes','id','name','deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
            $subjects->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"],'','','','','class_id','class_id');
            $subjects->label('class_id',lang('lbl_class'));

            $subjects->label('name', lang('lbl_subject'))->label('batch_id', lang('lbl_batch'))->label('code', lang('lbl_code'))->label('weekly_classes', lang('lbl_weekly_classes'));
            $subjects->replace_remove('soft_delete');
            $subjects->load_view("view", "customview.php");
            $subjects->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
            $subjects->table_name(lang('filter_subjects'));
            $subjects->replace_insert('subjectsAdd');
            $subjects->before_update('section_check');
            $subjects->unset_print();
            $subjects->unset_csv();
            echo $subjects->render();
        } else if($request->class_id != "all") {
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


                $subjects->relation('class_id','sh_classes','id','name','deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
                $subjects->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"],'','','','','class_id','class_id');
                $subjects->label('name', lang('lbl_subject'))->label('batch_id', lang('lbl_batch'))->label('code', lang('lbl_code'))->label('weekly_classes', lang('lbl_weekly_classes'));
                $subjects->replace_remove('soft_delete');
                $subjects->load_view("view", "customview.php");
                $subjects->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
                $subjects->table_name(lang('filter_subjects'));
                $subjects->replace_insert('subjectsAdd');
                $subjects->before_update('section_check');
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
        $batches->join('class_id','sh_classes','id');
        $batches->join('academic_year_id','sh_academic_years','id');
        $batches->where("school_id", $this->session->userdata("userdata")["sh_id"])->where("sh_batches.deleted_at IS NULL and sh_classes.deleted_at IS NULL and sh_academic_years.deleted_at IS NULL and sh_academic_years.is_active = 'Y'");

        if ($request->id != "all") {
            $batches->where('class_id', $request->id);
        }

        $batches->show_primary_ai_field(false);

        $batches->relation('class_id', 'sh_classes', 'id', 'name', "sh_classes.deleted_at IS NULL AND sh_classes.school_id='" . $this->session->userdata("userdata")["sh_id"] . "'");
        $batches->relation('academic_year_id', 'sh_academic_years', 'id', 'name', "sh_academic_years.deleted_at IS NULL AND sh_academic_years.is_active='Y' AND sh_academic_years.school_id='" . $this->session->userdata("userdata")["sh_id"] . "'");

        $batches->columns('sh_batches.name,class_id,name,academic_year_id,start_date,end_date');
        $batches->fields('name,class_id,academic_year_id,start_date,end_date');
        $batches->label('name',lang('lbl_batch'));
        $batches->label('class_id',lang('lbl_class'));
        $batches->label('academic_year_id',lang('lbl_academic_year'));
        $batches->label('start_date',lang('start_date'));
        $batches->label('end_date',lang('end_date'));
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

    public function exam(){
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_grades');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');

        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name, percent_from, percent_upto');
        $xcrud->fields('name, percent_from, percent_upto');
        $xcrud->label('name', lang('lbl_grade_name'))->label('percent_from', lang('lbl_percent_from'))->label('percent_upto', lang('lbl_percent_upto'));
        $xcrud->order_by('percent_from','desc');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->table_name(lang('lbl_grades'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->before_insert('checkValidation');
        $xcrud->before_update('checkValidationUpdate');
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $data["grades"] = $xcrud->render();

        $this->load->view('settings/exam_settings',$data);
    }

}
