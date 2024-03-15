<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Students extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function show() {

        //-------------------------
        $where = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            if(count(login_user()->t_data->classes) > 0){
                $data["classes"] = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ")");
//                $where = "u.school_id=" . login_user()->user->sh_id . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID . " AND u.batch_id IN (" . implode(',', login_user()->t_data->batches) . ")";
//                $data["students"] = $this->students_model->getAllStudents($where);
            } else {
                $data["students"] = array();
            }
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id = ".login_user()->user->sh_id . " AND deleted_at is null AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
//                $where = "u.school_id=" . login_user()->user->sh_id . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID ;
//                $data["students"] = $this->students_model->getAllStudents($where);
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
//            $where = "u.school_id=" . login_user()->user->sh_id . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID . " ";
//            $data["students"] = $this->students_model->getAllStudents($where);
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

        }
        //-------------------------

        $data["students"] = array();
        $this->load->view("students/index", $data);
    }

    public function add() {
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
        $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        $data["url"] = strtoupper($this->session->userdata("userdata")["sh_url"]);
        $this->load->view("students/form", $data);
    }

    public function get_subject_groups(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $batch_id = $request->batch_id;
        $data["groups"] = $this->db->select('id,group_name')->from('sh_subject_groups')->where('batch_id',$batch_id)->get()->result();
        echo json_encode($data);
    }

    public function view($id) {
        $id = decrypt($id);
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
        $data['attachments']=$xcrud->render();

        $parent_id = $this->db->select('guardian_id')->from('sh_student_guardians')->where('student_id',$id)->where('deleted_at is null')->get()->row();
        $data["parent"] = [];
        if(!empty($parent_id)){
            $parent_id = $parent_id->guardian_id;
            // $data["parent"] = $this->admin_model->dbSelect("*","users","id=$parent_id")[0];
            $data["parent"] = $this->admin_model->dbSelect("*","users","id=$parent_id");
        }

        $history = $this->db->select('c.name as class_name,b.name as batch_name,reason,tag,ay.name as academic_year_name')->select("date_format(shift_date,'%d %b %Y') as shiftDate",false)->select("date_format(joining_date,'%d %b %Y') as adm_date",false)->from('sh_student_shifts h')->join('sh_classes c','c.id = h.class_id')->join('sh_batches b','b.id = h.batch_id')->join('sh_academic_years ay','h.academic_year_id=ay.id','left')->join('sh_users u','h.student_id = u.id')->where('student_id',$id)->get()->result();
        $i = 1;
        foreach ($history as $value) {
            if($i == 1){
                $message = lang('add_history');
                $message = str_replace('{x}' , $value->class_name, $message);
                $message = str_replace('{y}' , $value->batch_name, $message);
                $value->description = $message;
            }else{
                if($value->tag == "is_shifted"){
                    $message = lang('shift_history');
                    $message = str_replace('{x}' , $value->class_name, $message);
                    $message = str_replace('{y}' , $value->batch_name, $message);
                    $value->description = $message;
                } else {
                    //$message = "Student transfer to academic year ".$value->academic_year_name ." successfully.";
                    $message = lang("transfer_history");
                    $message = str_replace('{x}' , $value->class_name, $message);
                    $message = str_replace('{y}' , $value->batch_name, $message);
                    $message = str_replace('{z}' , $value->academic_year_name, $message);
                    $value->description = $message;
                }
                
            }
            $i++; 
        }

        $data['history'] = $history;

        $data['student'] = $this->students_model->getStudentByID($id);
        $this->load->view('students/view', $data);
    }

    public function temp() {
       $school_id = $_GET["school_id"];
       $students = $this->db->select('*')->from('sh_users')->where('role_id',STUDENT_ROLE_ID)->where("school_id", $school_id)->get()->result();

       foreach ($students as $std) {
           $data = array('shift_date'=>$std->created_at,'student_id'=>$std->id,'class_id'=>$std->class_id,'batch_id'=>$std->batch_id);
           $this->db->insert('sh_student_shifts',$data);
       }

   }

   public function save() {
    $postdata = file_get_contents("php://input");

    $request = json_decode($postdata);
    
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $url = strtoupper($this->session->userdata("userdata")["sh_url"]);
    $result = $this->admin_model->dbSelect("*", "users", " rollno='$url$request->rollno' AND role_id=" . STUDENT_ROLE_ID . " AND school_id='$school_id' AND deleted_at = 0 ");
    $result2 = 0;
    $result3 = 0;

    if ($request->email != '') {
        $result2 = $this->common_model->email_exit($request->email)->result();
        $result2 = count($result2);
    }

    $adm = to_mysql_date($request->adm_date);
    $date_now = date("Y-m-d");
    if ($adm > $date_now) {
        $result3 = 1;
    }


    if (count($result) > 0) {
        echo json_encode(array("status" => "error", "message" => lang('student_exist')));
    } else if ($result2 > 0) {
        echo json_encode(array("status" => "error", "message" => lang('student_email')));
    } else if ($result3 > 0) {
        echo json_encode(array("status" => "error", "message" => lang('adm_date_error')));
    } else {

        if ($request->avatar == null) {
            $avatar = "profile.png";
        } else {
            $avatar = explode('uploads/user/', save_image($request->avatar))[1];
        }
        $joining_date = to_mysql_date($request->adm_date);
        if($joining_date == ""){
            $joining_date = date("Y-m-d");
        }
        
        $db_country=0;
        $selected_country=$this->db->select('id')->from('sh_countries')->where('country_code',$request->nationality)->get()->row();
        if($selected_country)
        {
            $db_country=$selected_country->id;
        }

        $student = array(
            'address' => $request->address,
            'avatar' => $avatar,
            //'batch_id' => $request->batch,
            'password' => md5("default"),
            'school_id' => $this->session->userdata("userdata")["sh_id"],
            'birthplace' => $request->birthPlace,
            'blood' => $request->blood,
            'city' => $request->city,
            // 'country' => $request->country,
            //'class_id' => $request->course,
            'dob' => to_mysql_date($request->dob),
            'email' => $request->email,
            'name' => $request->firstname,
            'gender' => $request->gender,
            'language' => $request->language,
            // 'nationality' => $request->nationality,
            'nationality' => $db_country,
            'contact' => $request->phone,
            'u_phone_number' => $request->u_phone_number,
            'parent_phone_code' => $request->parent_phone_code,
            'religion' => $request->religion,
            'rollno' => $url.$request->rollno,
            'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
            'role_id' => STUDENT_ROLE_ID,
            // 'discount_id' => $request->discount_id,
            'email_verified' => 'Y',
            'joining_date' => $joining_date,
            'ic_number' => $request->ic_number,
            'mother_name' => $request->mother_name,
            'mother_phone' => $request->mother_phone,
            'mother_ic' => $request->mother_ic

            // 'mother_phone_code' => $request->mother_phone_code

            // 'health_other_notes' => $request->health_other_notes
            //'subject_group_id' => ($request->subject_group == "") ? null : $request->subject_group
        );

        $std_id = $this->common_model->insert('sh_users', $student); 
         
        $guardian_id = $request->guardian[0];
        $std_guradian_relation = array(
            'student_id' => $std_id,
            'guardian_id' => $guardian_id,
            'relation' => $request->relation
        );
        $this->common_model->insert('sh_student_guardians', $std_guradian_relation);

        //----- Start::student class relationship for record ------//
        $academic_year_arr = $this->admin_model->dbSelect("id","academic_years"," school_id='$school_id' AND is_active='Y' AND deleted_at IS NULL ");
        $active_academic_year_id = null;
        if(count($academic_year_arr)>0){
            $active_academic_year_id = $academic_year_arr[0]->id;
        }
        $std_class_relation_data = array(
            "student_id"=>$std_id,
            "class_id"=>$request->course,
            "batch_id"=>$request->batch,
            "subject_group_id"=>($request->subject_group == "") ? null : $request->subject_group,
            "academic_year_id"=>$active_academic_year_id,
            "school_id"=>$school_id
            //'discount_id' => $request->discount_id
        );
        $this->common_model->insert("sh_student_class_relation", $std_class_relation_data);
        //----- End::student class relationship for record ------//

        $student_shift_data = array('student_id' => $std_id,
            'class_id' => $request->course,
            'batch_id' => $request->batch);
        $this->db->insert('sh_student_shifts', $student_shift_data);

        //Send mail to student email address for account verification
        if ($std_id > 0 && $request->email != '') {
                //$token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));

            $link = base_url() . $this->session->userdata('userdata')['sh_url'] . '/login/activation/' . $student["token"];
            $subject = 'Account Activation';
            $data = array(
                "dear_sir" => lang('tmp_dear_sir'),
                "msg" => lang('tmp_info'),
                "thanks" => lang('tmp_thanks'),
                "poweredBy" => lang('tmp_power'),
                "unsub" => lang('tmp_unsub'),
                "link" => $link,
                "email" => $request->email,
                "password" => "default"
            );
            $message = $this->load->view('email_templates/account_activation2.php', $data, TRUE);
            $this->email_modal->emailSend($request->email, $message, $subject, "student-signup");
        }

        // Send mail to parent/ guardian email for account verification
        //dispaly success message after created new student
        echo json_encode(array("status" => "success", "message" => lang('new_student')));
    }
}

public function getClassBatchesStudentsOld() {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];

    $where = " u.deleted_at=0 AND u.school_id=" . $school_id . " AND u.role_id=" . STUDENT_ROLE_ID . " ";

    if ($request->class_id != 'all') {
        $where .= " AND u.class_id=" . $request->class_id . " ";
    }
    if ($request->batch_id != 'all') {
        $where .= " AND u.batch_id=" . $request->batch_id . " ";
    }
    if ($request->status != 'all') {
        $where .= " AND u.status=" . $request->status . " ";
    }

        //$where = "u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID.";";
    $data["students"] = $this->students_model->getAllStudents($where);

        //$data["students"] = $this->admin_model->dbSelect("*", "users", $where);
    $filteredStudents = $this->load->view("students/filteredStudents", $data, true);
    echo $filteredStudents;
}

public function getClassBatchesStudents() {

    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $school_id = $this->session->userdata("userdata")["sh_id"];

        //-------------------------
    $where = " u.status='0' AND ";
    if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {

        $where = " rl.deleted_at is NULL AND u.school_id=" . $school_id . " AND u.role_id=" . STUDENT_ROLE_ID . " AND rl.batch_id IN (" . implode(',', login_user()->t_data->batches) . ") ";
        if ($request->class_id != 'all') {
            $where .= " AND rl.class_id=" . $request->class_id . " ";
        }
        if ($request->batch_id != 'all') {
            $where .= " AND rl.batch_id=" . $request->batch_id . " ";
        }
        if ($request->status != 'all') {
            $where .= " AND u.status=" . $request->status . " ";
        }

            //$data["classes"] = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ")");
            //$where = "u.school_id=" . login_user()->user->sh_id . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID . " AND u.batch_id IN (" . implode(',', login_user()->t_data->batches) . ") ";
    } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
        $where = " rl.deleted_at is NULL AND u.school_id=" . $school_id . " AND u.role_id=" . STUDENT_ROLE_ID . " ";
        if ($request->class_id != 'all') {
            $where .= " AND rl.class_id=" . $request->class_id . " ";
        }
        if ($request->batch_id != 'all') {
            $where .= " AND rl.batch_id=" . $request->batch_id . " ";
        }
        if ($request->status != 'all') {
            $where .= " AND u.status=" . $request->status . " ";
        }
    } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
        $where = " rl.deleted_at is NULL AND u.school_id=" . $school_id . " AND u.role_id=" . STUDENT_ROLE_ID . " ";
        if ($request->class_id != 'all') {
            $where .= " AND rl.class_id=" . $request->class_id . " ";
        }
        if ($request->batch_id != 'all') {
            $where .= " AND rl.batch_id=" . $request->batch_id . " ";
        }
        if ($request->status != 'all') {
            $where .= " AND u.status=" . $request->status . " ";
        }
    } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

    } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

    }

        //-------------------------
        //$where = "u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID.";";
    $data["students"] = $this->students_model->getAllStudents($where);


    $filteredStudents = 'null';
    if(count($data["students"]) > 0){
        $filteredStudents = $this->load->view("students/filteredStudents", $data, true); 
        // $this->load->view("students/filteredStudents", $data, true);
    }
    

    echo $filteredStudents;
        //$data["students"] = $this->admin_model->dbSelect("*", "users", $where);

}

public function edit($id) {
    $data["student_id"] = decrypt($id);
    $data["url"] = strtoupper($this->session->userdata("userdata")["sh_url"]);
    $g_id = $this->db->select('guardian_id')->from('sh_student_guardians')->where('student_id',decrypt($id))->where('deleted_at is null')->get()->row();
    if(!empty($g_id))
    {
        if ($g_id->guardian_id != "" && $g_id->guardian_id != 0)
        {
            $data['guardian_id'] = $g_id->guardian_id;
            $guardian_name = $this->admin_model->dbSelect("name", "users", "id = '$g_id->guardian_id'");
    
            // $guardian_name = $this->admin_model->dbSelect("name", "users", "id = '$g_id->guardian_id'")[0]->name;
            if(!empty($guardian_name))
            {
                $guardian_name=$guardian_name[0]->name;
            }
            else
            {
                $guardian_name='';
            }
            
            $data['guardian_name'] = $guardian_name;
        
        }
        else
        {
            $data['guardian_id'] = 0;
            $data['guardian_name']= "";
        }
    }
    else
    {
        $data['guardian_id'] = 0;
        $data['guardian_name']= "";
    }

    $data["class_id"] = $this->db->select('class_id')->from('sh_student_class_relation')->where('student_id', decrypt($id))->where('deleted_at', NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row()->class_id;
    $data["countries"] = $this->common_model->countries();
    $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
    // print_r("<pre>");
    // print_r($data); die();
    $this->load->view("students/edit", $data);
}

public function getDiscounts() {
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $data = $this->admin_model->dbSelect("*", "fee_discount", " school_id='$school_id' AND deleted_at IS NULL ");
    echo json_encode($data);
}

public function changeStudentPassword()
{
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $data1 = array(
        'password' => md5($request->password)
    );
    $pass = $this->db->select('password')->from('sh_users')->where('id', $request->student_id)->get()->row();
    // $pas = md5($pass);
    if ($request->password == $request->confirm_password && (object)$data1 != $pass)
    {
        $data1 = array(
            'password' => md5($request->password)
        );
        $this->db->where('id', $request->student_id);
        $this->db->update('sh_users' , $data1);
        $data['message'] = 1;
    }
    else if ($request->password == $request->confirm_password && (object)$data1 == $pass)
    {
        $data['message'] = 2;
    }
    else {
        $data['message'] = 3;
    }
    
    // $name = $this->db->select('name')->from('sh_users')->where('id', $request->student_id)->get()->row()->name;
    echo json_encode($data);
    
}
public function changeStudentPassword2()
{
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $data1 = array(
        'password' => md5($request->c_password)
    );
    $data2 = array(
        'password' => md5($request->n_password)
    );
    $pass = $this->db->select('password')->from('sh_users')->where('id', $request->id)->get()->row();
    if((object)$data1 == $pass && (object)$data2 != $pass && $request->n_password == $request->co_password)
    {
        $this->db->where('id', $request->id);
        $this->db->update('sh_users' , $data2);
        $data['message'] = 1;
    }
    else if((object)$data1 != $pass )
    {
        $data['message'] = 2;
    }
    else if((object)$data2 == $pass )
    {
        $data['message'] = 3;
    }
    else 
    {

        $data['message'] = 4;
    }
    echo json_encode($data);
}

public function getStudent() {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $data = $this->students_model->getSpecificStudentForEdit($request->student_id);
    $data->dob = to_html_date($data->dob);
    $data->adm_date = to_html_date($data->adm_date);
    $data->groups = $this->db->select('id,group_name')->from('sh_subject_groups')->where('batch_id', $data->batch)->where('deleted_at is NULL')->get()->result();
    $str = $data->rollno;
    $url = strtoupper($this->session->userdata("userdata")["sh_url"]);
    if (substr($str, 0, strlen($url)) == $url) {
        $str = substr($str, strlen($url));
    } 
    $data->rollno = $str;
    echo json_encode($data);
}

public function update() {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];

    if (array_key_exists("chgStdImage", $request)) {
        $avatar = explode('uploads/user/', save_image($request->image2))[1];
    } else {
        $avatar = $request->image2;
    }

    $dob = NULL;
    $adm_date = NULL;
    if ($request->dob != '' || !empty($request->dob)) {
        $dob = to_mysql_date($request->dob);
    }
    if ($request->adm_date != '' || !empty($request->adm_date)) {
        $adm_date = to_mysql_date($request->adm_date);
    }

    $url = strtoupper($this->session->userdata("userdata")["sh_url"]);
    
    
    $db_country=0;
    $selected_country=$this->db->select('id')->from('sh_countries')->where('country_code',$request->nationality)->get()->row();
    if($selected_country)
    {
        $db_country=$selected_country->id;
    } 
    
    $db_contact=NULL;
    $final_arr_contact=explode(' ',$request->phone);
    if(sizeof($final_arr_contact) > 0)
    {
        foreach($final_arr_contact as $ar_con)
        {
            $db_contact.=$ar_con;
        }
    }
    else
    {
        $db_contact=$request->phone;
    }
            
    $db_pnumber=NULL;
    $final_arr_pnumber=explode(' ',$request->u_phone_number);
    if(sizeof($final_arr_pnumber) > 0)
    {
        foreach($final_arr_pnumber as $ar_num)
        {
            $db_pnumber.=$ar_num;
        }
    }
    else
    {
        $db_pnumber=$request->u_phone_number;
    }
    
    if($db_pnumber=="" || $db_pnumber==NULL)
    {
        $db_contact="";
    }

    $student = array(
        'address' => $request->address,
        'avatar' => $avatar,
        'school_id' => $school_id,
        'birthplace' => $request->birthPlace,
        'blood' => $request->blood,
        'city' => $request->city,
        'country' => $request->country,
        'dob' => $dob,
        'email' => $request->email,
        'name' => $request->firstname,
        'gender' => $request->gender,
        'language' => $request->language,
        // 'nationality' => $request->nationality,
        'nationality' => $db_country,
        // 'contact' => $request->phone,
        'contact' => $db_contact,
        'u_phone_number' => $db_pnumber,
        'parent_phone_code' => $request->parent_phone_code,
        'religion' => $request->religion,
        'rollno' => $url.$request->rollno,
        // 'discount_id' => $request->discount_id,
        'joining_date' => $adm_date,
        'ic_number' => $request->ic_number,
        'mother_name' => $request->mother_name,
        'mother_phone' => $request->mother_phone,
        'mother_ic' => $request->mother_ic   
    );

    $student_class_data = array('class_id'=>$request->course,'batch_id'=>$request->batch,'subject_group_id' => ($request->group == "") ? null : $request->group);

    $std_guradian_relation = array(
        'student_id' => $request->student_id,
        'guardian_id' => $request->guardian[0],
        'relation' => $request->pRelation,
        'deleted_at' => null
    );

    $school_id = $this->db->select('school_id')->from('sh_users')->where('id', $request->student_id)->get()->row()->school_id;

    $result = $this->admin_model->dbSelect("*", "users", " rollno='$url$request->rollno' AND role_id=" . STUDENT_ROLE_ID . " AND school_id='$school_id' AND id <> '$request->student_id' AND deleted_at=0 ");

    if (count($result) > 0) {
        echo json_encode(array("status" => "error", "message" => lang('student_id_exist')));
    } else if ($adm_date > date("Y-m-d")) {
        echo json_encode(array("status" => "error", "message" => lang('adm_date_error')));
    } else {
        $batch_id = $this->db->select('batch_id')->where('student_id', $request->student_id)->where('deleted_at', NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->from('sh_student_class_relation')->get()->row()->batch_id;
        $res1 = $this->common_model->update_where("sh_users", array("id" => $request->student_id), $student);
        $res3 = $this->common_model->update_where("sh_student_class_relation", array("student_id" => $request->student_id,"academic_year_id" => $this->session->userdata("userdata")["academic_year"]), $student_class_data);
        if ($res1) {
            if ($batch_id != $request->batch) {
                $this->db->set('batch_id', $request->batch)->set('migrated', 'Y')->where('user_id', $request->student_id)->update('sh_attendance');
            }
        }

        $res2 = $this->common_model->update_where("sh_student_guardians", array("id" => $request->student_guardian_id), $std_guradian_relation);
        if ($res1 && $res2) {
            echo json_encode(array("status" => "success", "message" => lang('student_update')));
        } else {
            $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('student_not_update')));
        }
    }
}

public function delete() {
    $id = decrypt($this->input->post("id"));
    $academic_year_id = $this->session->userdata("userdata")["academic_year"];
    $current_time = date("Y-m-d H:i:s");
    $result = $this->db->select('id')->from('sh_student_class_relation')->where('student_id',$id)->where('deleted_at is null')->get()->result();
    $this->common_model->update_where("sh_student_class_relation", array("student_id" => $id,"academic_year_id"=>$academic_year_id), array("deleted_at"=>$current_time));
    $this->common_model->update_where("sh_users", array("id" => $id), array("deleted_at"=>1));
    // $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('student_deleted')));
    echo "success";
        //redirect("employee/all","refresh");
}

public function shift() {
    $data["classes"] = [];
    if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
        if (count(login_user()->t_data->classes) > 0) {
            $data["classes"] = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ")");
        }
    } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
        $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
    } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
        $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND deleted_at IS NULL AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
    } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

    } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

    }
    $this->load->view('students/shift', $data);
}

public function getShiftStudents() {
    $inputs = $this->input->post("formData");
    $school_id = $this->session->userdata("userdata")["sh_id"];

        //-------------------------
    $where = "";
    if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {

        $where = " rl.deleted_at is NULL AND u.school_id=" . $school_id . " AND u.role_id=" . STUDENT_ROLE_ID . " AND rl.batch_id IN (" . implode(',', login_user()->t_data->batches) . ") ";
        if ($inputs['class_id'] != 'all') {
            $where .= " AND rl.class_id=" . $inputs['class_id'] . " ";
        }
        if ($inputs['batch_id'] != 'all') {
            $where .= " AND rl.batch_id=" . $inputs['batch_id'] . " ";
        }
    } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
        $where = " rl.deleted_at is NULL AND u.school_id=" . $school_id . " AND u.role_id=" . STUDENT_ROLE_ID . " ";
        if ($inputs['class_id'] != 'all') {
            $where .= " AND rl.class_id=" . $inputs['class_id'] . " ";
        }
        if ($inputs['batch_id'] != 'all') {
            $where .= " AND rl.batch_id=" . $inputs['batch_id'] . " ";
        }
    } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
        $where = " rl.deleted_at is NULL AND u.school_id=" . $school_id . " AND u.role_id=" . STUDENT_ROLE_ID . " ";
        if ($inputs['class_id'] != 'all') {
            $where .= " AND rl.class_id=" . $inputs['class_id'] . " ";
        }
        if ($inputs['batch_id'] != 'all') {
            $where .= " AND rl.batch_id=" . $inputs['batch_id'] . " ";
        }
    } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

    } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

    }
        //-------------------------
        //$where = "u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID.";";
    $students = $this->students_model->getAllStudents($where);
    $i = 1;
    foreach ($students as $std) {
        if (!isset($std->misc))
            $std->misc = new stdClass();
        $std->misc->sr_no = $i++;
        $std->misc->id = $std->id;
        $std->new_id = encrypt($std->id);
    }

        //$data["students"] = $this->admin_model->dbSelect("*", "users", $where);
        // $filteredStudents = $this->load->view("students/shiftStudents", $data, true);
    echo json_encode($students);
}

function getNewClasses() {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $class_id = $request->class_id;
    $batch_id = $request->batch_id;
    $level_id = $this->db->select('level_id')->from('sh_classes')->where('id', $class_id)->get()->row()->level_id;
    $batches = $this->db->select('id')->from('sh_batches')->where('class_id', $class_id)->where('id <>', $batch_id)->where('deleted_at is null')->get()->result();
    if (count($batches) == 0) {
        if ($level_id == 0) {
            $classes = new stdClass();
        } else {
            $classes = $this->db->select('id,name')->from('sh_classes')->where('level_id', $level_id)->where('school_id', $school_id)->where('deleted_at is null')->where('id <>', $class_id)->get()->result();
        }
    } else {
        if ($level_id == 0) {
            $classes = $this->db->select('id,name')->from('sh_classes')->where('id', $class_id)->get()->result();
        } else {
            $classes = $this->db->select('id,name')->from('sh_classes')->where('level_id', $level_id)->where('school_id', $school_id)->where('deleted_at is null')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->result();
        }
    }
    $class_name = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
    $batch_name = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
    $count = $request->count;
    if ($count == 1) {
        $message = lang('shifting_student');
        $message = str_replace('{n}', $request->count, $message);
        $message = str_replace('{x}', $class_name, $message);
        $message = str_replace('{y}', $batch_name, $message);
    } else {
        $message = lang('shifting_students');
        $message = str_replace('{n}', $request->count, $message);
        $message = str_replace('{x}', $class_name, $message);
        $message = str_replace('{y}', $batch_name, $message);
    }
    $data["level_msg"] = "";
    if ($level_id == 0) {
        $data["level_msg"] = lang('class_group_error');
    }
    $data["classes"] = $classes;
    $data["message"] = $message;
    echo json_encode($data);
}

function shiftStudents() {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);

    $class_id = $request->class_id;
    $batch_id = $request->batch_id;
    $students = $request->students;
    $reason = $request->reason;

    foreach ($students as $student) {
        $data = array('class_id' => $class_id,
            'batch_id' => $batch_id,
            'student_id' => $student,
            'academic_year_id' => $this->session->userdata("userdata")["academic_year"],
            'reason' => $reason);

        $res=$this->db->set('class_id', $class_id)->set('batch_id', $batch_id)->set('subject_group_id', NULL)->where('student_id', $student)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->update('sh_student_class_relation');
        $this->db->insert('sh_student_shifts', $data);
    }
    $class_name = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
    $batch_name = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
    if (count($students) == 1) {
        $data["message"] = lang('shift_success');
        $data["message"] = str_replace('{n}', count($students), $data["message"]);
        $data["message"] = str_replace('{x}', $class_name, $data["message"]);
        $data["message"] = str_replace('{y}', $batch_name, $data["message"]);
    } else {
        $data["message"] = lang('shifts_success');
        $data["message"] = str_replace('{n}', count($students), $data["message"]);
        $data["message"] = str_replace('{x}', $class_name, $data["message"]);
        $data["message"] = str_replace('{y}', $batch_name, $data["message"]);
    }

    echo json_encode($data);
}

public function evaluate(){
    $this->load->view('students/evaluate');
}

public function getEvaluationTerms(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $academic_year_id = $request->academic_year_id;

    $data["terms"] = $this->db->select('id, term_name')->from('sh_evaluation_terms')->where('academic_year_id', $academic_year_id)->where('deleted_at is null')->get()->result();

    echo json_encode($data);
}

public function report_card(){
    $this->load->view('students/report_card');
}

public function report_cardForParent(){
    $this->load->view('students/childEvaluation');
}

 public function getChilds()
    {
        $user_id = $this->session->userdata("userdata")["user_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $academic_year_id = $request->academic_year_id;
        


        $childrens = $this->db->select('student_id,s.name')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->join('sh_academic_years ac','s.academic_year_id=ac.id')->where('guardian_id', $user_id)->where('academic_year_id',$academic_year_id)->get()->result();
        
        $data['student_ids'] = $childrens;

        echo json_encode($data);
    }

 public function getClassBatchesandEvaluationForParent() {
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $student = $request->student_id;
        
        //$where_part = "";
         

        
        if ($request->student_id != "") {
            $query = "  school_id=" . login_user()->user->sh_id . " AND id='$request->student_id' AND academic_year_id=".$request->academic_year_id." AND deleted_at IS NULL ";

        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND academic_year_id=".$request->academic_year_id." AND deleted_at IS NULL ";

        }

      // $childrens = $this->db->select('student_id,s.name,dob,rollno,c.name as class_name,b.name as batch_name')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->join('sh_classes c', 's.class_id = c.id')->join('sh_batches b', 's.batch_id = b.id')->where('guardian_id', $user_id)->get()->result();

        $data["batches"] = $this->db->select('batch_id,b.name')->from('students_'.$school_id.' s')->join('sh_batches b','s.batch_id=b.id')->where('s.id',$student)->get()->result();
       
        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }

        $data["evaluations"] = $this->db->select('id,evaluation_name,type')->from('sh_evaluations')->where("find_in_set(".$class_id.", classes)")->where("deleted_at is null")->get()->result();

        //$data["batches"] = $this->db->select("batch_id", "students_".$school_id, $query . $where_part)->get()->result();
        //die($data["batches"]);
        

        // $data["evaluations"] = $this->db->select('id,evaluation_name')->from('sh_evaluations')->where("deleted_at is null")->get()->result();
        // $data["evaluations"] = $this->db->select('id,evaluation_name')->from('sh_evaluations')->where("find_in_set(".$request->student_id.", student_id)")->where("deleted_at is null")->get()->result();
        echo json_encode($data);
    }

public function getClassBatchesandEvaluation() {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $term_id = $request->term_id;

        //-------------------------
    $where_part = "";
    if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
        $where_part = " AND id IN (" . implode(',', login_user()->t_data->batches) . ") ";
    } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {

    } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {

    } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

    } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

    }
        //-------------------------

    if ($request->class_id != "") {
        $query = "  school_id=" . login_user()->user->sh_id . " AND class_id='$request->class_id' AND academic_year_id=".$request->academic_year_id." AND deleted_at IS NULL ";
    } else {
        $query = " school_id=" . login_user()->user->sh_id . " AND academic_year_id=".$request->academic_year_id." AND deleted_at IS NULL ";
    }

    $where_part .= " ORDER BY name ASC  ";
    $data["batches"] = $this->admin_model->dbSelect("*", "batches", $query . $where_part);
    $data["evaluations"] = $this->db->select('id,evaluation_name,type')->from('sh_evaluations')->where("find_in_set(".$request->class_id.", classes)")->where('term_id', $term_id)->where("deleted_at is null")->get()->result();
    echo json_encode($data);
}

public function getEvaluationsByTerm() {
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $term_id = $request->term_id;

    $data["evaluations"] = $this->db->select('id,evaluation_name,type')->from('sh_evaluations')->where("find_in_set(".$request->class_id.", classes)")->where('term_id', $term_id)->where("deleted_at is null")->get()->result();
    echo json_encode($data);
}

public function getEvaluationCategories(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $data = array();
    if(isset($request->evaluation_id)){
        $evaluation_id = $request->evaluation_id;

        $data = $this->db->select('id,category_name')->from('sh_evaluation_categories')->where('evaluation_id', $evaluation_id)->where("deleted_at is null")->get()->result();
    }

    echo json_encode($data);
}

public function getStudentForEvaluation(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $class_id = $request->class_id;
    $batch_id = $request->batch_id;
    $subject_id = $request->subject_id;
    $evaluation_id = $request->evaluation_type;
    $type = $request->type;
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $subject = "";
    if($type == 'subject'){
        $subject = $this->db->select('name')->from('sh_subjects')->where('id', $subject_id)->get()->row()->name;
    }


    $evaluation = $this->db->select('evaluation_name')->from('sh_evaluations')->where('id', $evaluation_id)->get()->row()->evaluation_name;


    $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id);
    if($type == 'subject'){
        $this->db->where('subject_id', $subject_id);
    }
    
    $reports = $this->db->where('evaluation_id', $evaluation_id)->get()->result();



    $categories = $this->db->select('id,category_name')->from('sh_evaluation_categories')->where('evaluation_id', $evaluation_id)->where("deleted_at is null")->get()->result();

    foreach ($categories as $c) {
        $c->stars = 0;
    }

    if($reports){
        // $sql = "SELECT u.id,u.subject_group_id as group_id,u.avatar as student_avatar, u.name, u.rollno,u.class_id, u.batch_id from sh_students_".$school_id." u WHERE u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $class_id . " AND u.batch_id=" . $batch_id . " AND u.deleted_at=0 AND u.school_id=" . $school_id . " ";
        $sql = "SELECT scr.student_id as id,
                scr.subject_group_id as group_id,
                u.avatar as student_avatar,
                u.name, 
                u.rollno,
                scr.class_id, 
                scr.batch_id 
                from sh_student_class_relation scr 
                left join sh_users u ON scr.student_id = u.id
                WHERE u.role_id=" . STUDENT_ROLE_ID . " 
                AND scr.class_id=" . $class_id . " 
                AND scr.batch_id=" . $batch_id . " 
                AND scr.deleted_at  is NULL 
                AND u.school_id=" . $school_id . " ";

        $students = $this->admin_model->dbQuery($sql);
        foreach ($students as $sss) {

            if($type == 'subject'){
                if ($sss->group_id != null || !empty($sss->group_id)) {
                    $subjects = $this->admin_model->dbSelect("subjects", "subject_groups", " id=$sss->group_id ")[0]->subjects;
                    $subjects_array = explode(",", $subjects);
                    if (in_array($subject_id, $subjects_array)) {
                        $sss->is_read = 'true';
                    } else {
                        $sss->is_read = 'false';
                    }
                } else if ($sss->group_id == null || empty($sss->group_id)) {
                    $sss->is_read = 'true';
                }
            }else{
                $sss->is_read = 'true';
            }


            $new_categories = array();

            foreach ($categories as $c) {
                $temp = new stdClass();
                $temp->id = $c->id;
                $temp->category_name = $c->category_name;
                $temp->stars = $this->getStars($reports, $sss->id, $c->id);
                $new_categories[] = $temp;
            }

            $sss->report = $new_categories;
            $sss->subject_id = $subject_id;
            $sss->evaluation_id = $evaluation_id;



        }
        $is_db = true;


    }else{

        $sql = "SELECT scr.student_id as id,
                scr.subject_group_id as group_id,
                u.avatar as student_avatar,
                u.name, 
                u.rollno,
                scr.class_id, 
                scr.batch_id 
                from sh_student_class_relation scr 
                left join sh_users u ON scr.student_id = u.id
                WHERE u.role_id=" . STUDENT_ROLE_ID . " 
                AND scr.class_id=" . $class_id . " 
                AND scr.batch_id=" . $batch_id . " 
                AND scr.deleted_at  is NULL 
                AND u.school_id=" . $school_id . " ";

        $students = $this->admin_model->dbQuery($sql);

        foreach ($students as $sss) {
            if($type == 'subject'){
                if ($sss->group_id != null || !empty($sss->group_id)) {
                    $subjects = $this->admin_model->dbSelect("subjects", "subject_groups", " id=$sss->group_id ")[0]->subjects;
                    $subjects_array = explode(",", $subjects);
                    if (in_array($subject_id, $subjects_array)) {
                        $sss->is_read = 'true';
                    } else {
                        $sss->is_read = 'false';
                    }
                } else if ($sss->group_id == null || empty($sss->group_id)) {
                    $sss->is_read = 'true';
                }
            }else{
                $sss->is_read = 'true';
            }



            $sss->report = $categories;
            $sss->subject_id = $subject_id;
            $sss->evaluation_id = $evaluation_id;
            $sss->type = $type;

        }
        $is_db = false;

    }




    $data["students"] = $students;
    $data["evaluation"] = $evaluation;
    $data["is_category"] = count($categories) > 0; 
    $data["subject"] = $subject;
    $data["is_db"] = $is_db;
    $data["type"] = $type;

    echo json_encode($data);


}

public function saveEvaluation(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $evaluation_data = $request->data;
    $method = $request->method;
    $type = $request->type;

    foreach ($evaluation_data as $ev) {
        $student_id = $ev->id;
        $class_id = $ev->class_id;
        $batch_id = $ev->batch_id;
        $subject_id = $ev->subject_id;
        $evaluation_id = $ev->evaluation_id;

        if($type != 'subject'){
            $subject_id = 0;
        }

        foreach ($ev->report as $rep) {
            $data = array("student_id" => $student_id,
                "class_id" => $class_id,
                "batch_id" => $batch_id,
                "evaluation_id" => $evaluation_id,
                "category_id" => $rep->id,
                "stars" => $rep->stars,
                "school_id" => $school_id,
                "subject_id" => $subject_id);
            
            $this->db->replace('sh_student_report', $data);
        }
    }
    $data = array();
    if($method){
        $data["msg"] = lang('evaluation_changed');
    }else{
        $data["msg"] = lang('evaluation_saved');
    }

    echo json_encode($data);

}

public function getStars($reports, $student_id, $category_id){
    $stars = 0;
    foreach($reports as $rep) {
        if ($rep->student_id == $student_id && $rep->category_id == $category_id) {
            $stars = $rep->stars;
            break;
        }
    }
    return $stars;
}

public function getStarsSubjectWise($reports, $student_id, $category_id, $subject_id){
    $stars = 0;
    foreach($reports as $rep) {
        if ($rep->student_id == $student_id && $rep->category_id == $category_id && $rep->subject_id == $subject_id) {
            $stars = $rep->stars;
            break;
        }
    }
    return $stars;
}

public function getStudentsForReportCard(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $class_id = $request->class_id;
    $batch_id = $request->batch_id;
    $evaluation_id = $request->evaluation_type;
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $term_id = $request->term_id;

    $grades =  array( array("stars" => 1, "legend" => "Weak"),
        array("stars" => 2, "legend" => "Fair"),
        array("stars" => 3, "legend" => "Good"),
        array("stars" => 4, "legend" => "Excellent"),
        array("stars" => 5, "legend" => "Exceptional"));

    $sql = "SELECT 
            scr.student_id as id,
            scr.subject_group_id as group_id,
            u.avatar as student_avatar, 
            u.name, u.rollno,scr.class_id, 
            scr.batch_id 
            from sh_student_class_relation scr
            left join sh_users u on u.id=scr.student_id 
            WHERE u.role_id=" . STUDENT_ROLE_ID . " 
            AND scr.class_id=" . $class_id . " 
            AND scr.batch_id=" . $batch_id . " 
            AND scr.deleted_at is NULL 
            AND u.school_id=" . $school_id . " ";

    $students = $this->admin_model->dbQuery($sql);



    if($evaluation_id == "all"){
        
        $evaluation1 = $this->db->select('id')->from('sh_evaluations')->where("find_in_set(".$class_id.", classes)")->where('type', 'subject')->where('term_id', $term_id)->where('deleted_at is null')->get()->row()->id;
        $evaluation2 = $this->db->select('id')->from('sh_evaluations')->where("find_in_set(".$class_id.", classes)")->where('type', 'non-subject')->where('term_id', $term_id)->where('deleted_at is null')->get()->row()->id;
        $allsubjects1 = $this->db->select('id,name')->from('sh_subjects')->where('batch_id', $batch_id)->get()->result();
        $type1 = "subject";
        $type2 = "non-subject";
        $allsubjects2 = array();
        $dummy_subject = new stdClass();
        $dummy_subject->id = 0;
        $dummy_subject->name = lang('lbl_evaluation');
        $allsubjects2[] = $dummy_subject;

        $reports = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation1)->get()->result();
        $reports2 = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation2)->get()->result();






        $categories = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation1)->where("deleted_at is null")->get()->result();
        $categories2 = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation2)->where("deleted_at is null")->get()->result();
        if($categories){
            $temp = new stdClass();
            $temp->id = "";
            $temp->db = "false";
            $temp->category_name = "Summary of Evaluation";
            $categories[] = $temp;
        }

        
            $temp = new stdClass();
            $temp->id = "";
            $temp->db = "false";
            $temp->category_name = "Summary of Evaluation";
            $categories2[] = $temp;

        foreach ($students as $sss) {
            $his_subjects = 0;
            $his_subjects2 = 0;
            $avg_total = 0;
            $avg_total2 = 0;

            $sss->activities = $categories;
            $sss->activities2 = $categories2;

            $evaluations = array();
            $evaluations2 = array();

            foreach ($allsubjects1 as $sub) {

                $single_subject = new stdClass();
                $single_subject->report = array();
                if ($sss->group_id != null || !empty($sss->group_id)) {
                    $subjects = $this->admin_model->dbSelect("subjects", "subject_groups", " id=$sss->group_id ")[0]->subjects;
                    $subjects_array = explode(",", $subjects);
                    if (in_array($sub->id, $subjects_array)) {
                        $single_subject->is_read = 'true';
                    } else {
                        $single_subject->is_read = 'false';
                    }
                } else if ($sss->group_id == null || empty($sss->group_id)) {
                    $single_subject->is_read = 'true';
                }

                if($single_subject->is_read == 'true'){
                    $total = 0;
                    $his_subjects++;
                    foreach ($categories as $cat) {

                        if($cat->db == "true"){
                            $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports, $sss->id, $cat->id, $sub->id);
                            $total += $temp1;
                        }else{
                            $average_for_subject = round($total/(count($categories) - 1 ));
                            $single_subject->report[] = $average_for_subject;
                            $avg_total += $average_for_subject;
                        }

                    }
                }




                $evaluations[] = $single_subject;
            }

            foreach ($allsubjects2 as $sub) {

                $single_subject = new stdClass();
                $single_subject->report = array();
                
                $single_subject->is_read = 'true';
                $total = 0;
                $his_subjects2++;
                foreach ($categories2 as $cat) {

                    if($cat->db == "true"){

                        $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports2, $sss->id, $cat->id, $sub->id);
                        $total += $temp1;
                    }else{
                        $average_for_subject = 0;
                        if(count($categories2) > 1){
                            $average_for_subject = round($total/(count($categories2) - 1 ));
                        }
                        $single_subject->report[] = $average_for_subject;
                        $avg_total2 += $total;
                    }

                }

                



                $evaluations2[] = $single_subject;
            }

            $overall_categories = count($categories2) - 1;
            
            $sss->evaluations = $evaluations;
            $sss->evaluations2 = $evaluations2;
            $f_avg_number = 0;
            $f_avg = 0;
            $f_grade = 0;
            if($his_subjects == 0){
                $sss->final_avg_number = 0;
                $sss->final_avg = 0;
                $sss->grade = "";
            }else{
                $sss->final_avg_number = $avg_total/$his_subjects;
                $f_avg_number += $avg_total/$his_subjects;
                $sss->final_avg = round($avg_total/$his_subjects);
                $f_avg += $avg_total/$his_subjects;
                $sss->legend = $this->getLegend($grades, $sss->final_avg);
            }

            if($overall_categories == 0){
                $sss->final_avg_number2 = 0;
                $sss->final_avg2 = 0;
                $sss->grade2 = "";
            }else{
                $sss->final_avg_number2 = $avg_total2/$overall_categories;
                $f_avg_number += $avg_total2/$overall_categories;
                $sss->final_avg2 = round($avg_total2/$overall_categories);
                $f_avg += $avg_total2/$overall_categories;
                $sss->legend2 = $this->getLegend($grades, $sss->final_avg2);
            }



            $f_grade = $this->getLegend($grades, round($f_avg/2));

            $sss->f_avg_number = $f_avg_number/2;
            $sss->f_avg = round($f_avg/2);
            $sss->f_grade = $f_grade;




        }

        $span1 = count($categories);
        if($span1 == 0) { $span1 = 1; }
        $span2 = count($categories2);
        if($span2 == 0) { $span2 = 1; }

        $overall_span = count($categories);
        if($overall_span == 0){
            $overall_span = 1;
        }else{
            $overall_span -= 1;
        }

        $overall_span2 = count($categories2);
        if($overall_span2 == 0){
            $overall_span2 = 1;
        }else{
            $overall_span2 -= 1;
        }

        $non_subject_span = count($allsubjects1) + 2;

        


        $data["students"] = $students;
        $data["subjects"] = $allsubjects1;
        $data["subjects2"] = $allsubjects2;
        $data["evaluation"] = lang('lbl_all');
        $data["span"] = $span1;
        $data["name_span"] = $span1 + 1;
        $data["span2"] = $span2;
        $data["non_subject_span"] = $non_subject_span;
        $data["overall_span"] = $overall_span;
        $data["overall_span2"] = $overall_span2;
        $data["all_evaluation"] = true;







    }else{

        $evaluation = $this->db->select('evaluation_name')->from('sh_evaluations')->where('id', $evaluation_id)->get()->row()->evaluation_name;

        $type = $this->db->select('type')->from('sh_evaluations')->where('id', $evaluation_id)->get()->row()->type;
        if($type == 'subject'){
            $allsubjects = $this->db->select('id,name')->from('sh_subjects')->where('batch_id', $batch_id)->get()->result();
        }else{
            $allsubjects = array();
            $dummy_subject = new stdClass();
            $dummy_subject->id = 0;
            $dummy_subject->name = lang('lbl_evaluation');
            $allsubjects[] = $dummy_subject;
        }

        $reports = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation_id)->get()->result();

        $categories = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation_id)->where("deleted_at is null")->get()->result();

        if($categories){
            $temp = new stdClass();
            $temp->id = "";
            $temp->db = "false";
            $temp->category_name = "Summary of Evaluation";
            $categories[] = $temp;
        }



        foreach ($students as $sss) {

            $his_subjects = 0;
            $avg_total = 0;

            $sss->activities = $categories;

            $evaluations = array();

            foreach ($allsubjects as $sub) {

                $single_subject = new stdClass();
                $single_subject->report = array();
                if($type == 'subject'){
                    if ($sss->group_id != null || !empty($sss->group_id)) {
                        $subjects = $this->admin_model->dbSelect("subjects", "subject_groups", " id=$sss->group_id ")[0]->subjects;
                        $subjects_array = explode(",", $subjects);
                        if (in_array($sub->id, $subjects_array)) {
                            $single_subject->is_read = 'true';
                        } else {
                            $single_subject->is_read = 'false';
                        }
                    } else if ($sss->group_id == null || empty($sss->group_id)) {
                        $single_subject->is_read = 'true';
                    }
                }else{
                    $single_subject->is_read = 'true';
                }

                if($single_subject->is_read == 'true'){
                    $total = 0;
                    $his_subjects++;
                    foreach ($categories as $cat) {

                        if($cat->db == "true"){
                            $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports, $sss->id, $cat->id, $sub->id);
                            $total += $temp1;
                        }else{
                            $average_for_subject = round($total/(count($categories) - 1 ));
                            $single_subject->report[] = $average_for_subject;
                            $avg_total += $average_for_subject;
                        }

                    }
                }




                $evaluations[] = $single_subject;
            }
            $sss->evaluations = $evaluations;
            if($his_subjects == 0){
                $sss->final_avg_number = 0;
                $sss->final_avg = 0;
                $sss->grade = "";
            }else{
                $sss->final_avg_number = $avg_total/$his_subjects;
                $sss->final_avg = round($avg_total/$his_subjects);
                $sss->legend = $this->getLegend($grades, $sss->final_avg);
            }




        }

        $span = count($categories);
        if($span == 0) { $span = 1; }

        $overall_span = count($categories);
        if($overall_span == 0){
            $overall_span = 1;
        }else{
            $overall_span -= 1;
        }


        $data["students"] = $students;
        $data["evaluation"] = $evaluation;
        $data["subjects"] = $allsubjects;
        $data["span"] = $span;
        $data["overall_span"] = $overall_span;
        $data["all_evaluation"] = false;

    }

    echo json_encode($data);


}

function getLegend($grades, $star) {
   foreach ($grades as $key => $val) {
       if ($val['stars'] == $star) {
           return $val['legend'];
       }
   }
   return "";
}

public function getChildForParentReportCard(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $evaluation_id = $request->evaluation_type;
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $grades =  array( array("stars" => 1, "legend" => "Weak"),
        array("stars" => 2, "legend" => "Fair"),
        array("stars" => 3, "legend" => "Good"),
        array("stars" => 4, "legend" => "Excellent"),
        array("stars" => 5, "legend" => "Exceptional"));

       

         $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }

        $sql = "SELECT u.id,u.subject_group_id as group_id,u.avatar as student_avatar, u.name, u.rollno,u.class_id, u.batch_id from sh_students_".$school_id." u WHERE u.role_id=" . STUDENT_ROLE_ID . " AND u.id=" . $request->student_id .  " AND u.deleted_at=0 AND u.school_id=" . $school_id . " ";

        $students = $this->admin_model->dbQuery($sql);

    if($evaluation_id == "all"){
        
        $evaluation1 = $this->db->select('id')->from('sh_evaluations')->where("find_in_set(".$class_id.", classes)")->where('type', 'subject')->where('deleted_at is null')->get()->row()->id;
        $evaluation2 = $this->db->select('id')->from('sh_evaluations')->where("find_in_set(".$class_id.", classes)")->where('type', 'non-subject')->where('deleted_at is null')->get()->row()->id;
        $allsubjects1 = $this->db->select('id,name')->from('sh_subjects')->where('batch_id', $batch_id)->get()->result();
        $type1 = "subject";
        $type2 = "non-subject";
        $allsubjects2 = array();
        $dummy_subject = new stdClass();
        $dummy_subject->id = 0;
        $dummy_subject->name = lang('lbl_evaluation');
        $allsubjects2[] = $dummy_subject;

        $reports = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation1)->get()->result();
        $reports2 = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation2)->get()->result();






        $categories = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation1)->where("deleted_at is null")->get()->result();
        $categories2 = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation2)->where("deleted_at is null")->get()->result();
        if($categories){
            $temp = new stdClass();
            $temp->id = "";
            $temp->db = "false";
            $temp->category_name = "Summary of Evaluation";
            $categories[] = $temp;
        }

        if($categories2){
            $temp = new stdClass();
            $temp->id = "";
            $temp->db = "false";
            $temp->category_name = "Summary of Evaluation";
            $categories2[] = $temp;
        }

        foreach ($students as $sss) {
            $his_subjects = 0;
            $his_subjects2 = 0;
            $avg_total = 0;
            $avg_total2 = 0;

            $sss->activities = $categories;
            $sss->activities2 = $categories2;

            $evaluations = array();
            $evaluations2 = array();

            foreach ($allsubjects1 as $sub) {

                $single_subject = new stdClass();
                $single_subject->report = array();
                if ($sss->group_id != null || !empty($sss->group_id)) {
                    $subjects = $this->admin_model->dbSelect("subjects", "subject_groups", " id=$sss->group_id ")[0]->subjects;
                    $subjects_array = explode(",", $subjects);
                    if (in_array($sub->id, $subjects_array)) {
                        $single_subject->is_read = 'true';
                    } else {
                        $single_subject->is_read = 'false';
                    }
                } else if ($sss->group_id == null || empty($sss->group_id)) {
                    $single_subject->is_read = 'true';
                }

                if($single_subject->is_read == 'true'){
                    $total = 0;
                    $his_subjects++;
                    foreach ($categories as $cat) {

                        if($cat->db == "true"){
                            $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports, $sss->id, $cat->id, $sub->id);
                            $total += $temp1;
                        }else{
                            $average_for_subject = round($total/(count($categories) - 1 ));
                            $single_subject->report[] = $average_for_subject;
                            $avg_total += $average_for_subject;
                        }

                    }
                }




                $evaluations[] = $single_subject;
            }

            foreach ($allsubjects2 as $sub) {

                $single_subject = new stdClass();
                $single_subject->report = array();
                
                $single_subject->is_read = 'true';
                $total = 0;
                $his_subjects2++;
                foreach ($categories2 as $cat) {

                    if($cat->db == "true"){

                        $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports2, $sss->id, $cat->id, $sub->id);
                        $total += $temp1;
                    }else{
                        $average_for_subject = round($total/(count($categories) - 1 ));
                        $single_subject->report[] = $average_for_subject;
                        $avg_total2 += $average_for_subject;
                    }

                }

                



                $evaluations2[] = $single_subject;
            }
            
            $sss->evaluations = $evaluations;
            $sss->evaluations2 = $evaluations2;
            $f_avg_number = 0;
            $f_avg = 0;
            $f_grade = 0;
            if($his_subjects == 0){
                $sss->final_avg_number = 0;
                $sss->final_avg = 0;
                $sss->grade = "";
            }else{
                $sss->final_avg_number = $avg_total/$his_subjects;
                $f_avg_number += $avg_total/$his_subjects;
                $sss->final_avg = round($avg_total/$his_subjects);
                $f_avg += $avg_total/$his_subjects;
                $sss->legend = $this->getLegend($grades, $sss->final_avg);
            }

            if($his_subjects2 == 0){
                $sss->final_avg_number2 = 0;
                $sss->final_avg2 = 0;
                $sss->grade2 = "";
            }else{
                $sss->final_avg_number2 = $avg_total2/$his_subjects2;
                $f_avg_number += $avg_total2/$his_subjects2;
                $sss->final_avg2 = round($avg_total2/$his_subjects2);
                $f_avg += $avg_total2/$his_subjects2;
                $sss->legend2 = $this->getLegend($grades, $sss->final_avg2);
            }



            $f_grade = $this->getLegend($grades, round($f_avg/2));

            $sss->f_avg_number = $f_avg_number/2;
            $sss->f_avg = round($f_avg/2);
            $sss->f_grade = $f_grade;




        }

        $span1 = count($categories);
        if($span1 == 0) { $span1 = 1; }
        $span2 = count($categories2);
        if($span2 == 0) { $span2 = 1; }

        $overall_span = count($categories);
        if($overall_span == 0){
            $overall_span = 1;
        }else{
            $overall_span -= 1;
        }

        $overall_span2 = count($categories2);
        if($overall_span2 == 0){
            $overall_span2 = 1;
        }else{
            $overall_span2 -= 1;
        }

        $non_subject_span = count($allsubjects1) + 2;

        


        $data["students"] = $students;
        $data["subjects"] = $allsubjects1;
        $data["subjects2"] = $allsubjects2;
        $data["evaluation"] = "All";
        $data["span"] = $span1;
        $data["name_span"] = $span1 + 1;
        $data["span2"] = $span2;
        $data["non_subject_span"] = $non_subject_span;
        $data["overall_span"] = $overall_span;
        $data["overall_span2"] = $overall_span2;
        $data["all_evaluation"] = true;







    }else{

        $evaluation = $this->db->select('evaluation_name')->from('sh_evaluations')->where('id', $evaluation_id)->get()->row()->evaluation_name;

        $type = $this->db->select('type')->from('sh_evaluations')->where('id', $evaluation_id)->get()->row()->type;
        if($type == 'subject'){
            $allsubjects = $this->db->select('id,name')->from('sh_subjects')->where('batch_id', $batch_id)->get()->result();
        }else{
            $allsubjects = array();
            $dummy_subject = new stdClass();
            $dummy_subject->id = 0;
            $dummy_subject->name = lang('lbl_evaluation');
            $allsubjects[] = $dummy_subject;
        }

        $reports = $this->db->select('*')->from('sh_student_report')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('evaluation_id', $evaluation_id)->get()->result();

        $categories = $this->db->select('id,"true" as db ,category_name',false)->from('sh_evaluation_categories')->where('evaluation_id', $evaluation_id)->where("deleted_at is null")->get()->result();

        if($categories){
            $temp = new stdClass();
            $temp->id = "";
            $temp->db = "false";
            $temp->category_name = "Summary of Evaluation";
            $categories[] = $temp;
        }



        foreach ($students as $sss) {

            $his_subjects = 0;
            $avg_total = 0;

            $sss->activities = $categories;

            $evaluations = array();

            foreach ($allsubjects as $sub) {

                $single_subject = new stdClass();
                $single_subject->report = array();
                if($type == 'subject'){
                    if ($sss->group_id != null || !empty($sss->group_id)) {
                        $subjects = $this->admin_model->dbSelect("subjects", "subject_groups", " id=$sss->group_id ")[0]->subjects;
                        $subjects_array = explode(",", $subjects);
                        if (in_array($sub->id, $subjects_array)) {
                            $single_subject->is_read = 'true';
                        } else {
                            $single_subject->is_read = 'false';
                        }
                    } else if ($sss->group_id == null || empty($sss->group_id)) {
                        $single_subject->is_read = 'true';
                    }
                }else{
                    $single_subject->is_read = 'true';
                }

                if($single_subject->is_read == 'true'){
                    $total = 0;
                    $his_subjects++;
                    foreach ($categories as $cat) {

                        if($cat->db == "true"){
                            $single_subject->report[] = $temp1 = $this->getStarsSubjectWise($reports, $sss->id, $cat->id, $sub->id);
                            $total += $temp1;
                        }else{
                            $average_for_subject = round($total/(count($categories) - 1 ));
                            $single_subject->report[] = $average_for_subject;
                            $avg_total += $average_for_subject;
                        }

                    }
                }




                $evaluations[] = $single_subject;
            }
            $sss->evaluations = $evaluations;
            if($his_subjects == 0){
                $sss->final_avg_number = 0;
                $sss->final_avg = 0;
                $sss->grade = "";
            }else{
                $sss->final_avg_number = $avg_total/$his_subjects;
                $sss->final_avg = round($avg_total/$his_subjects);
                $sss->legend = $this->getLegend($grades, $sss->final_avg);
            }




        }

        $span = count($categories);
        if($span == 0) { $span = 1; }

        $overall_span = count($categories);
        if($overall_span == 0){
            $overall_span = 1;
        }else{
            $overall_span -= 1;
        }


        $data["students"] = $students;
        $data["evaluation"] = $evaluation;
        $data["subjects"] = $allsubjects;
        $data["span"] = $span;
        $data["overall_span"] = $overall_span;
        $data["all_evaluation"] = false;

    }

        echo json_encode($data);  }

    public function online_admissions(){
        $this->load->view("students/online_admissions");
    }

    public function getOnlineAdmissions(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*","online_admissions"," school_id='$school_id' AND deleted_at IS NULL ");
        echo json_encode($data);
    }

    public function getOnlineAdmissions2(){
        $where = "";
        if($this->input->post("class_id") !== null){
            $class_id = $this->input->post("class_id");
            $searchBy = $this->input->post("searchBy");
            if($class_id != 'all'){
                $where .= " AND std_class_id='$class_id' ";
            }
            if(!empty($searchBy) || $searchBy !== " "){
                $where .= "AND std_full_name like '$searchBy%' ";
            }
        }
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*","online_admissions"," school_id='$school_id' AND deleted_at IS NULL ".$where." ");
        echo json_encode($data);
    }

    // added by sheraz
    public function statusDeactivate(){
        $id = $this->input->post("id");
        $this->common_model->update_where("sh_users", array("id" => $id), array("status"=>'1'));
        $response = array("status"=> "success", "message"=> lang("user_deactivated"));
        echo json_encode($response);
      }

      public function statusActivate(){
        $id = $this->input->post("id");
        $this->common_model->update_where("sh_users", array("id" => $id), array("status"=>'0'));
        $response = array("status"=> "success", "message"=> lang("user_activated"));
        echo json_encode($response);
      }

      public function userDeactivate(){
        $id = $this->input->post("id");
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $this->common_model->update_where("sh_users", array("id" => $id), array("status"=>'0'));
        $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('user_deactivated')));
        echo "success";
      }

      public function userActivate(){
        $id = $this->input->post("id");
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $this->common_model->update_where("sh_users", array("id" => $id), array("status"=>'1'));
        $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('user_activated')));
        echo "success";
      }
}
