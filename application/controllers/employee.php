<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Employee extends CI_Controller {

    function __construct() {
        parent::__construct();
        
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }
    
    public function add() {
        $data["departments"] = $this->admin_model->dbSelect("*", "departments", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND role_id =".EMPLOYEE_ROLE_ID." AND deleted_at=0 ");
        //$data["categories"] = $this->admin_model->dbSelect("*", "role_categories", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND role_id =2 AND deleted_at=0 ");
        $data["countries"] = $this->common_model->countries();
        $school_country=$this->db->select('sh_school.country,sh_countries.country_code as schools_country_code')->from('sh_school')->join('sh_countries', 'sh_school.country = sh_countries.country_name')->where('sh_school.id',$this->session->userdata("userdata")["sh_id"])->get()->row();
        if($school_country)
        {
            $data["school_country_code"]=$school_country->schools_country_code;
        }
        else
        {
            $data["school_country_code"]="";
        }
        $data["timezones"] = $this->common_model->time_zone_list();
        $this->load->view("employee/add", $data);
    }

    public function save() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $result = $this->common_model->email_exit($request->email)->result();
        if (count($result) > 0) {
            echo json_encode(array("status" => "error", "message" => lang('lbl_email_exist')));
        }
        else if(to_mysql_date($request->dob)=="error" || to_mysql_date($request->joining_date)=="error"){
            echo json_encode(array("status" => "error", "message" => lang('invalid_date')));
        }
         else {
            if ($request->avatar == "default") {
                $image = "profile.png";
            } else {
                $image = explode("uploads/user/",$this->save_image($request->avatar))[1];
            }
            
        $db_country=0;
        $selected_country=$this->db->select('id')->from('sh_countries')->where('country_code',$request->country)->get()->row();
        if($selected_country)
        {
            $db_country=$selected_country->id;
        }
    
        
            $token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));
            $data = array(
                'role_id' => EMPLOYEE_ROLE_ID,
                'role_category_id' => $request->category,
                'school_id' => $this->session->userdata("userdata")["sh_id"],
                'department_id' => decrypt($request->department),
                'bank_id' => "",
                'language' => $request->language,
                'name' => $request->name,
                'job_title' => $request->job_title,
                'email' => $request->email,
                'password' => md5($request->password),
                'contact' => $request->contact,
                'office_phone' => "",
                'mobile_phone' => $request->contact,
                'u_phone_number' => $request->u_phone_number,
                'parent_phone_code' => $request->parent_phone_code,
                'fax' => $request->fax,
                'nationality' => $request->nationality,
                'address' => "",
                // 'country' => $request->country,
                'country' => $db_country,
                'city' => $request->city,
                'qualification' => $request->qualification,
                'experience_duration' => $request->experience_duration,
                'ic_number' => $request->ic,
                'passport_number' => $request->passport,
                'token' => $token,
                'recovery_key' => "",
                'experience_info' => $request->experience_info,
                'dob' => to_mysql_date($request->dob),
                'avatar' => $image,
                'address'=>$request->street,
                'joining_date'=> to_mysql_date($request->joining_date),
                'email_verified' => 'Y',
                'marital_status' => $request->marital_status,
                'gender' => $request->gender,
                'status' => '0',
                'deleted_at' => 0,
                'permissions' => json_encode($request->permissions),
                'rollno' => $request->emp_id,
                'basic_salary' => $request->basic_salary
            );
            $res = $this->common_model->insert("users", $data);
            if ($res) {
                //$token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));
                // Send An Activation email to New Registred Admin
            //   $link = base_url() .$this->session->userdata('userdata')['sh_url']. '/login/activation/' . $token;
            //   $subject = 'Account Activation';
            //   $data = array(
            //       "dear_sir" => lang('tmp_dear_sir'),
            //       "msg" => lang('tmp_info'),
            //       "thanks" => lang('tmp_thanks'),
            //       "poweredBy" => lang('tmp_power'),
            //       "unsub" => lang('tmp_unsub'),
            //       "link" => $link,
            //       "email" => $request->email,
            //       "password" => $request->password
            //   );
            //   $message = $this->load->view('email_templates/account_activation2.php', $data, TRUE);
            //   $this->email_modal->emailSend($request->email, $message, $subject, "employee-signup");
               //end of send email
               echo json_encode(array("status" => "success", "message" => lang('new_employee')));
            }
        }
    }

    function save_image($base64_string) {
        $image_parts = explode(";base64,", $base64_string);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = 'uploads/user/'.uniqid() . '.'.$image_type;
        file_put_contents($file, $image_base64);
        return $file;
    }

    function getRoleCategories() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = $this->admin_model->dbSelect("*", "role_categories", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND role_id=" . $request->role_id . " AND deleted_at=0 ");
        echo json_encode($data);
    }

    public function all() {

        if (isset($_GET) && $_GET) {
            $request = $_GET;
        } else {
            $request = NULL;
        }
        
        $UserData = $this->session->userdata('userdata');
        $data['categories'] = $this->employee_model->getCategories($UserData['sh_id']);
        $data['departments'] = $this->employee_model->getDepartments($UserData['sh_id']);
        $data['employees'] = $this->employee_model->getAll($UserData['sh_id'], $request);
        $this->load->view('employee/index', $data);
    }

    public function getCategories() {
        // echo 'fadfd'; exit;
        $UserData = $this->session->userdata('userdata');
        $department_id = decrypt($this->input->post('department'));
        $data["categories"] = $this->admin_model->dbSelect("*","role_categories"," department_id='$department_id' AND school_id=".$UserData['sh_id']." AND deleted_at=0 ");
        $categories = $this->load->view('employee/categories.php', $data, TRUE);
        echo $categories;
    }

    function getDepartmentsByCatID() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = $this->admin_model->dbSelect("*", "departments", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND role_category_id=" . $request->id . " AND deleted_at=0 ");
        echo json_encode($data);
    }

    public function edit() {
        
        $id = $_GET['id'];
        $id = decrypt($id);
        $id= (int) $id;
        $exist =  $this->db->select('name')->from('sh_users')->where('school_id', $this->session->userdata("userdata")["sh_id"])->where('deleted_at', '0')->where('id',$id)->get();
        // print_r($exist);die;
        if($exist->num_rows < 1){
            // return redirect()->to($_SERVER['HTTP_REFERER']);
            redirect('employee/all');
        }
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_attachments');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('user_id',$id);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('title,file');
        $xcrud->fields('title,file');
        $xcrud->label('title',lang('lbl_title_attachments'))->label('file',lang('lbl_file'));
        $xcrud->change_type('file','file','',array());
        $xcrud->after_upload('checkExtension');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->pass_var('user_id', $id);
        $xcrud->replace_remove('soft_delete');
        $xcrud->table_name(lang('lbl_attachments'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_title();
        $data['attachments']=$xcrud->render();

        $data['employee'] = $this->employee_model->getEmployeByIDEdit($id);
        $data['countries'] = $this->common_model->countries();
        $data["categories"] = $this->admin_model->dbSelect("*", "role_categories", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND role_id =2 AND deleted_at=0 ");
        //$data['departments'] = $this->employee_model->getDepartments($data['employee']->school_id);
        $data["departments"] = $this->admin_model->dbSelect("*", "departments", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND role_id =".EMPLOYEE_ROLE_ID." AND deleted_at=0 ");
        $data["emp_permissions"] = json_decode($this->admin_model->dbSelect(" permissions ", 'users', " id=$id ")[0]->permissions);
        $data['banks'] = $this->bank_model->getUserBankDetail($id);
        $this->load->view('employee/edit', $data);
    }

    public function update() {
        $data = $this->input->post();
        

        $id = $data["id"];
        
        if(isset($data["email"])){
            $email = $data["email"];
            $record = $this->admin_model->dbSelect("*","users"," email='$email' AND id<>$id AND deleted_at = 0");
        }
        
        if(isset($data["email"]) && count($record) > 0) {
            //can not-update
            $this->session->set_flashdata('error-image', '<div class="alert alert-danger alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button>'.lang('lbl_email_exist').'</div>');
            redirect('employee/edit?id='. encrypt($data["id"]));
        } else {
            //can update
            if(!empty($this->input->post("joining_date"))){
             $data["joining_date"] = to_mysql_date($this->input->post("joining_date"));
            }
            if(!empty($this->input->post("dob"))){
                 $data["dob"] = to_mysql_date($this->input->post("dob"));
            }
            if($this->input->post("department_id")){
                $data["department_id"] = decrypt($this->input->post("department_id"));
                $data["role_category_id"] = decrypt($this->input->post("role_category_id"));
            }
            $id = $this->input->post('id');
            $old_data = $this->db->select('email,token')->from('sh_users')->where('id',$id)->get()->row();
            $new_email = $this->input->post('email');
            if($old_data->email != $new_email){
                $data2 = $this->common_model->get_where("sh_users", "email", "'".$new_email."'")->result();
                if(count($data2)==0){
                    $link = base_url() .$this->session->userdata('userdata')['sh_url']. '/login/activation/' . $old_data->token;
                    $subject = 'Account Activation';
                    $data3 = array(
                     "dear_sir" => lang('tmp_dear_sir'),
                     "msg" => lang('tmp_info'),
                     "thanks" => lang('tmp_thanks'),
                     "poweredBy" => lang('tmp_power'),
                     "unsub" => lang('tmp_unsub'),
                     "link" => $link,
                     "email" => $new_email,
                     "password" => 'uvschool'
                 );
                    $message = $this->load->view('email_templates/account_activation2.php', $data3, TRUE);
                    $this->email_modal->emailSend($new_email, $message, $subject, "email-change");
                    $data['password'] = md5('uvschool');
                    $data['email_verified'] = 'N';
                }
            }

            unset($data['avatar2']);
            
            
            $db_country=0;
            $selected_country=$this->db->select('id')->from('sh_countries')->where('country_code',$data['country'])->get()->row();
            if($selected_country)
            {
                $data['country']=$selected_country->id;
            }
            
            $db_number_only=NULL;
            $final_arr_contact=explode(' ',$data['contact']);
            if(sizeof($final_arr_contact) > 0)
            {
                foreach($final_arr_contact as $ar_con)
                {
                    $db_number_only.=$ar_con;
                }
            }
            else
            {
                $db_number_only=$data['contact'];
            }
            
            
            $db_pnumber=NULL;
            $final_arr_pnumber=explode(' ',$data['onlynumber']);
            if(sizeof($final_arr_pnumber) > 0)
            {
                foreach($final_arr_pnumber as $ar_num)
                {
                    $db_pnumber.=$ar_num;
                }
            }
            else
            {
                $db_pnumber=$data['onlynumber'];
            }
            
            
            if($db_number_only=="" || $db_number_only==NULL)
            {
                $db_pnumber="";
            }
            
            $data['contact']=$db_pnumber;
            $data['u_phone_number']=$db_number_only;
            
            unset($data['onlynumber']);
            unset($data['emp_c_number']);

            $this->common_model->editRecord('id', $id, 'users', $data);
            if($this->input->post("avatar2") != null){
                $fname = explode("uploads/user/",$this->save_image($this->input->post("avatar2")))[1];
                $result["avatar"] = $fname;
                $this->common_model->editRecord('id', $id, 'users', $result);
            } 
            $this->session->set_flashdata('success-image', '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button>'.lang('employee_update').' </div>');
            redirect('employee/all');
        }
        
    }

    public function getDepartmentsByCatIDEdit() {
        // echo 'fadfd'; exit;
        $school_id = $this->input->post('school_id');
        $category_id = $this->input->post('category');
        $data['departments'] = $this->employee_model->getDepartments($school_id, $category_id);
        $departments = $this->load->view('employee/departments_edit.php', $data, TRUE);
        echo $departments;
    }

    public function updatePermissions() {
        $id = $this->input->post('employee_id');
        $permissions = json_decode($this->admin_model->dbSelect(" permissions ", 'users', " id=$id ")[0]->permissions);
        foreach ($permissions as $key => $val) {
            $val->val = $this->input->post($key) ? "true" : "false";
        }
        $this->common_model->update_where("sh_users", array("id" => $id), array("permissions" => json_encode($permissions)));
        $this->session->set_flashdata("emp_selected_tab", "permissions");
        $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('emp_permission')));
        
        
        
        //$oldValues = $this->session->userdata("userdata");
        //$oldValues["permissions"] = json_encode($permissions);
        //$this->session->set_userdata("userdata", $oldValues);
        
        redirect($_SERVER['HTTP_REFERER']);
    }

    public function delete() {
        $id = decrypt($this->input->post("id"));
        $this->common_model->update_where("sh_users", array("id" => $id), array("deleted_at" => 1));
        $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('emp_delete')));
        echo "success";
        //redirect("employee/all","refresh");
    }
    
     public function changePassword(){

        $current_password = md5($this->input->post('current_password'));
        $password=$this->input->post('password');
        $confirm_password=$this->input->post('confirm_password');

        $user_id = $this->input->post('emp_id');

        $db_password=$this->common_model->getPassword($user_id);

        $response["success"]=true;

        if(strlen($password)<8){
            $response["success"]=false;
            $response["error"] = lang('length_error');
        }else if($password!=$confirm_password){
            $response["success"]=false;
            $response["error"] = lang('match_error');
        }
        else{
            $this->common_model->changePassword($user_id,md5($password));
        }

        echo json_encode($response);

    }
    
    public function view(){
        $id = $_GET['id'];
        $id = decrypt($id);
        $id= (int) $id;

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id );
        $valid_result=$this->db->get()->row();
        
        if($valid_result)
        {
        }
        else
        {
            redirect('employee/all');
        }
        
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_attachments');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('user_id',$id);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('title,file');
        $xcrud->fields('title,file');
        $xcrud->label('title',lang('lbl_title_attachments'))->label('file',lang('lbl_file'));
        $xcrud->change_type('file','file','',array());
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->pass_var('user_id', $id);
        $xcrud->replace_remove('soft_delete');
        $xcrud->table_name(lang('lbl_attachments'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->unset_print();
        $xcrud->unset_csv();
        $xcrud->unset_title();
        $data['attachments']=$xcrud->render();

        $data['employee'] = $this->employee_model->getEmployeByID($id);
        $data['countries'] = $this->common_model->countries();
        $data["categories"] = $this->admin_model->dbSelect("*", "role_categories", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND role_id =".EMPLOYEE_ROLE_ID." AND deleted_at=0 ");
        $data['departments'] = $this->employee_model->getDepartments($data['employee']->school_id, $data['employee']->role_category_id);
        $data["emp_permissions"] = json_decode($this->admin_model->dbSelect(" permissions ", 'users', " id=$id ")[0]->permissions);
        $data['banks'] = $this->bank_model->getUserBankDetail($id);
        $this->load->view('employee/view', $data);
    }
    
    function getCategoriesByDepartmentID(){
        $UserData = $this->session->userdata('userdata');
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $department_id = decrypt($request->department);
        $data = $this->admin_model->dbSelect("*","role_categories"," department_id=$department_id AND school_id=".$UserData['sh_id']." AND deleted_at=0 ");
        echo json_encode($data);
    }
    
    public function getDepartments(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["departments"] = $this->employee_model->getDepartments($school_id);
        echo json_encode($data);
    }
    
    public function getCategories2() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $UserData = $this->session->userdata('userdata');
        $department_id = $request->id;
        $data["categories"] = $this->admin_model->dbSelect("*","role_categories"," department_id='$department_id' AND school_id=".$UserData['sh_id']." AND deleted_at=0 ");
        echo json_encode($data);
    }
    
    function getDepartmentWithCategoryFilter(){
        $UserData = $this->session->userdata('userdata');
        $cat = "";
        $query = null;
        $dpt = decrypt($this->input->post("department"));
        if($this->input->post("department") == "all"){
            $query = " sh_users.school_id=".$UserData['sh_id']." AND sh_users.role_id=4 AND sh_users.deleted_at=0 ";
        } else {
            if($this->input->post("category") == "all"){
                $query = " sh_users.school_id=".$UserData['sh_id']." AND sh_users.role_id=4 AND sh_users.deleted_at=0 AND sh_users.department_id='$dpt' ";
            } else {
                $cat = decrypt($this->input->post("category"));
                $query = " sh_users.school_id=".$UserData['sh_id']." AND sh_users.role_id=4 AND sh_users.deleted_at=0 AND sh_users.role_category_id='$cat' AND sh_users.department_id='$dpt' ";
            }
        }
        
        $data["employees"] = $this->admin_model->dbSelect("sh_users.*, a.country_name as country,d.name as department_name, c.category as category_name ","users LEFT JOIN sh_countries a ON sh_users.country = a.id left join sh_departments d on d.id = sh_users.department_id left join sh_role_categories c on c.id = sh_users.role_category_id ", $query);
        $employees = $this->load->view('employee/filterDepartments.php', $data, TRUE);
        echo $employees;
    }

    public function getSalaryTypes() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data["salaryTypes"] = $this->admin_model->dbSelect("*","salary_types"," school_id='$school_id' AND deleted_at is NULL");
        echo json_encode($data);
    }

    public function getAdmins(){
     $school_id = $this->session->userdata("userdata")["sh_id"];
     $sqly = "";

     $admin = "Select id, name from sh_users where role_id = 1 AND school_id= ".$school_id." AND deleted_at = 0";

     $emp = "Select id, name, permissions from sh_users where role_id = 4 AND school_id= ".$school_id." AND deleted_at = 0";

     $paid_by = $this->admin_model->dbQuery($admin);

     
     $emp1 = $this->admin_model->dbQuery($emp);

     foreach ($emp1 as $value) {
         
         $array = json_decode($value->permissions);
         if (isset($array)) {
          
            foreach ($array as $key => $value1) {
//payroll-pay
                if (in_array('payroll-pay', array($value1->permission)) && $value1->val == 'true') {
                    unset($value->permissions);
                    
                    array_push($paid_by, $value);
                }
                
            }
        }

        
    }
    
    $data["paid_by"] = $paid_by;


    echo json_encode($data); 
}
}
