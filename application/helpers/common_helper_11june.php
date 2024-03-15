<?php

function format_date($date) {
    return date("m-d-Y", strtotime($date));
}

function filter_string($str) {
    $str = str_replace("_", " ", $str);
    $str = str_replace("[]", "", $str);
    $str = ucwords($str);
    return $str;
} 

function last_query(){
    $ci = & get_instance();
    echo $ci->db->last_query();
    die();
}

function insert($table, $data) {
    $CI = &get_instance();
    $CI->db->insert($table, $data);
}

function check_student_shifted_multiple_exam_report($shifted_students, $id, $exam_id, $batch_id){
    $ci = & get_instance();
    $result = $ci->db->select('id')->from('sh_marksheets')->where('student_id',$id)->where('exam_detail_id not in (select id from sh_exam_details where batch_id IN ('. $batch_id.') and exam_id='.$exam_id.') and obtained_marks is not null and deleted_at is null and exam_id = '.$exam_id)->get()->result();
    return (count($result));
}

function edit($columName, $where, $tbl_name, $data) {
    $CI = &get_instance();
    $CI->common_model->editRecord($columName, $where, $tbl_name, $data);
}

function encrypt($id) {
    return urlencode(base64_encode($id));
}

function decrypt($id) {
    return base64_decode(urldecode($id));
}

function get_media_products($product_id, $media_category) {

    $ci = & get_instance();

    $ci->load->database();
    $ci->db->select('supplier_products.*,media.*');
    $ci->db->from("supplier_products");
    $ci->db->join("media", "supplier_products.product_id = media.module_id");
    $ci->db->where("media.module", "products");
    $ci->db->where("media.module_id", $product_id);
    $ci->db->where("media.category", $media_category);

    $query = $ci->db->get();
    /* echo $this->db->last_query();
    exit; */

    if ($query->num_rows() > 0) {
        $result = $query->row();
        return $result;
    }
}

function page_slug($text) {
    $text = preg_replace('~[^\\pL\d]+~u', '-', $text);
    $text = trim($text, '-');
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = strtolower($text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}

function pages($id = 0) {
    $CI = &get_instance();
    $result = $CI->pages_model->pages($id);
    return $result;
}

function getImage($id) {
    $CI = &get_instance();
    $result = $CI->banners_model->getImage($id);

    return $result;
}

function getJsonValue($key, $json) {
    if ($json == "") {
        return $json;
    }
    $data = json_decode($json);
    return $data->$key;
}

function sendemailtoadministrator($email_message, $to, $from, $email_subject) {
    $CI = &get_instance();
    $config = array(
        'charset' => 'utf-8',
        'wordwrap' => TRUE,
        'mailtype' => 'html'
    );
    $CI->load->library('email', $config);
    $CI->email->initialize($config);
    $CI->email->from($from, 'PIRC');
    $CI->email->to($to);
    $CI->email->subject($email_subject);
    $CI->email->message($email_message);
    $CI->email->send();
    $CI->email->print_debugger();
}

function save_image($base64_string) {
    $image_parts = explode(";base64,", $base64_string);
    $image_type_aux = explode("image/", $image_parts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($image_parts[1]);
    $file = 'uploads/user/' . uniqid() . '.' . $image_type;
    file_put_contents($file, $image_base64);
    return $file;
}

function checkLicense() {
    $ci = & get_instance();
    if (!empty($ci->session->userdata("userdata"))) {
        $ci->load->database();
        $ci->db->select('start_date,end_date');
        $ci->db->from('sh_license');
        $ci->db->where(array("school_id" => $ci->session->userdata("userdata")["sh_id"]));
        $query = $ci->db->get();
        if (!licenceCalcultor($query->row()->start_date, $query->row()->end_date)) {
            $ci->session->sess_destroy("userdata");
        }
    }
}

function licenceCalcultor($startDate, $endDate) {
    $currentDate = date('Y-m-d');
    $contractDateBegin = date('Y-m-d', strtotime($startDate));
    $contractDateEnd = date('Y-m-d', strtotime($endDate));

    if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

function dateInBetween($begin, $end, $paymentDate) {
    //$paymentDate = date('Y-m-d');
    $paymentDate = date('Y-m-d', strtotime($paymentDate));
    //echo $paymentDate; // echos today! 
    $contractDateBegin = date('Y-m-d', strtotime($begin));
    $contractDateEnd = date('Y-m-d', strtotime($end));

    if (($paymentDate > $contractDateBegin) && ($paymentDate < $contractDateEnd)) {
        return 1;
    } else {
        return 0;
    }
}

function dateInBetweenOrEqual($begin, $end, $paymentDate) {
    //$paymentDate = date('Y-m-d');
    $paymentDate = date('Y-m-d', strtotime($paymentDate));
    //echo $paymentDate; // echos today! 
    $contractDateBegin = date('Y-m-d', strtotime($begin));
    $contractDateEnd = date('Y-m-d', strtotime($end));

    if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)) {
        return 1;
    } else {
        return 0;
    }
}

function allDates($start, $end) {
    $array = array();
    $period = new DatePeriod(
        new DateTime($start), new DateInterval('P1D'), new DateTime($end)
    );
    foreach ($period as $value) {
        array_push($array, $value->format('Y-m-d'));
    }
    array_push($array, $end);
    return $array;
}

function sendNotificationViaPusher($msg_key, $recipient, $url, $data) {
    require_once 'vendor/autoload.php';
    $options = array(
        'cluster' => 'ap2',
        'encrypted' => true
    );
    $pusher = new Pusher\Pusher(
        '9bf1a37f0210a046cca3', '7181ca6eace86bc2f5bf', '478546', $options
    );
    $message = lang($msg_key);
    $msg_data = json_decode($data, TRUE);

    foreach ($msg_data as $d => $vv) {
        $message = str_replace("{{" . $d . "}}", '"' . $vv . '"', $message);
    }
    $msg = $message;
    $length = count($recipient);
    for ($i = 0; $i < $length; $i++) {
        $rec = $recipient[$i];
        $pusher->trigger('mychanal-' . $rec, 'my-event', $msg);
    }
}

function get_subjs_batches_classes($id) {
    $ci = & get_instance();
    $query = "SELECT * FROM sh_assign_subjects ass INNER JOIN sh_subjects sub on ass.subject_id = sub.id and ass.batch_id = sub.batch_id WHERE ass.deleted_at IS NULL AND sub.deleted_at is NULL AND sub.academic_year_id = ".$ci->session->userdata("userdata")["academic_year"]." AND (ass.teacher_id='$id' OR ass.assistant_id='$id')";
    

    $batches = $ci->db->select('id')->from('sh_batches')->where('find_in_set('.$id.',teacher_id) > ','0')->where('academic_year_id',$ci->session->userdata("userdata")["academic_year"])->where('deleted_at is null')->get()->result();
    
    $classes = $ci->db->select('class_id')->from('sh_batches')->where('find_in_set('.$id.',teacher_id) > ','0')->where('academic_year_id',$ci->session->userdata("userdata")["academic_year"])->where('deleted_at is null')->get()->result();
    $ci->load->model("admin_model");
    $res = $ci->admin_model->dbQuery($query);
    //     echo '<pre>';
    // print_r($res);
    // die;
    
    // echo 'assigned subjects <pre>';
    // print_r($res);
    // echo 'batches <pre>';
    // print_r($batches);
    // echo 'clas <pre>';
    // print_r($classes);
    // die;
    
    
    $clss = array();
    $bats = array();
    $bats_assigned = array();
    $subjs = array();
    foreach ($res as $val) {
        array_push($bats, $val->batch_id);
        array_push($clss, $val->class_id);
        array_push($subjs, $val->subject_id);
    }
    foreach ($batches as $b) {
        array_push($bats,$b->id);
        array_push($bats_assigned, $b->id);
    }
    array_push($bats_assigned, -1);
    // echo '<pre>';
    // print_r($bats);
    // print_r($bats_assigned);
    // die;
    // get subject of batches to which user is assigned
    
    $teacher_class_subjects = $ci->db->select('id')->from('sh_subjects')->where_in('batch_id', $bats_assigned)->where('deleted_at is NULL')->get()->result();
    
    
    // $teacher_class_subjects = $this->db->select('sh_subjects.*,sh_batches.name as batch_name,code')->from('sh_subjects')->where('sh_subjects.class_id', $class_id)->join('sh_batches', 'sh_subjects.batch_id = sh_batches.id')
    //             ->where('sh_subjects.deleted_at is null', null, false)->where('sh_batches.deleted_at is null', null, false)->where_in('sh_subjects.id',$subjects)->group_by('code')->order_by("sh_subjects.name", "asc")->get()->result();
    
    // echo $ci->db->last_query();
    // die;
    foreach($teacher_class_subjects as $tc){
        array_push($subjs, $tc->id);
    }
    foreach ($classes as $c) {
        array_push($clss,$c->class_id);
    }
    $clss = array_unique($clss);
    $bats = array_unique($bats);
    $subjs = array_unique($subjs);
    // array_push($bats, -1);
    // array_push($subjs, -1);
    // array_push($clss, -1);
    $data = array("classes" => $clss, "batches" => $bats, "subjects" => $subjs);
    return $data;
}

function get_teacher_dept_id() {
    $ci = & get_instance();
    $ci->load->model("admin_model");
    $school_id = $ci->session->userdata('userdata')['sh_id'];
    return $teacher_dept_id = $ci->admin_model->dbSelect("teacher_dept_id", "school", " id='$school_id' ")[0]->teacher_dept_id;
}

function get_acountant_dept_id() {
    $ci = & get_instance();
    $ci->load->model("admin_model");
    $school_id = $ci->session->userdata('userdata')['sh_id'];
    $arr = $ci->admin_model->dbSelect("accounts_dept_id", "school", " id='$school_id' ");
    $accounts_dept_id = -1;
    if(count($arr)>0){
        $accounts_dept_id = $arr[0]->accounts_dept_id;
    }
    return $accounts_dept_id;
}

function teacher_role_data() {
    $ci = & get_instance();
    $user_id = $ci->session->userdata("userdata")["user_id"];
    $login_user_role_id = $ci->session->userdata("userdata")["role_id"];
    $school_id = $ci->session->userdata("userdata")["sh_id"];
    $login_user_employee_dept_id = $ci->session->userdata("userdata")["teacher_dept_id"];


    $resp = array();




    return (object) $resp;
}

function login_user() {
    $ci = & get_instance();
    $id = $ci->session->userdata("userdata")["user_id"];
    $dd = get_subjs_batches_classes($id);
    $p = $ci->db->select('permissions')->from('sh_users')->where('id', $id)->get()->row()->permissions;
    // $p = $ci->session->userdata("userdata")['persissions'];
    $permissions = json_decode($p);
    $per = array();
    if($permissions != ""){
        foreach ($permissions as $value) {
            if ($value->permission == "applications-student" && $value->val == 'true') {
                $per[] = "attendance";
            }else if ($value->permission == "applications-employee" && $value->val == 'true'){
                $per[] = "emp_attendance";
            }else if ($value->permission == "applications-studyplan" && $value->val == 'true'){
                $per[] = "syllabus";
            }else if ($value->permission == "applications-marksheet" && $value->val == 'true'){
                $per[] = "mark_sheet";
            }
        }
    }
    $ddd = $ci->session->userdata("userdata");
    $ddd["teacher_dept_id"] = $ci->db->select('department_id')->from('sh_users')->where('id',$id)->get()->row()->department_id;
    $arr = array();
    $arr["user"] = (object) $ddd;
    $arr["t_data"] = (object) $dd;
    $arr["req_types"] = $per;
    return (object) $arr;
}

function to_mysql_date($date) {
    //'12/12/2018

    if (empty($date)) {
        return "";
    }

    $myDateTime = DateTime::createFromFormat('d/m/Y', $date);
    if (gettype($myDateTime) == 'boolean') {
        return "error";
    }


    return $myDateTime->format('Y-m-d');
}

function to_html_date($date) {
    //'2012-12-01
    if (empty($date) || $date =='0000-00-00') {
        return "";
    }

    $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
    return $myDateTime->format('d/m/Y');
}

function countRemainingDays($end) {
    $date1 = date("Y-m-d");
    $date2 = $end;

    $diff = abs(strtotime($date2) - strtotime($date1));

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

    return array("years" => $years, "months" => $months, "days" => $days);
}

function check_user_permissions() {

    $ci = & get_instance();
    if (login_user()->user->role_id == ADMIN_ROLE_ID) {
        $end_date = $ci->db->select('end_date')->from('sh_license')->where('school_id', $ci->session->userdata("userdata")['sh_id'])->get()->row()->end_date;
        $rem = countRemainingDays($end_date);
        $oldValues = $ci->session->userdata("userdata");
        $oldValues["remaining_days"] = $rem;
        $ci->session->set_userdata("userdata", $oldValues);
    }
    // $temp_perm = $ci->db->select('permissions')->where('id',$ci->session->userdata("userdata")['user_id'])
    //             ->from('sh_users')->get()->row()->permissions;
    $maintenance_msg = $ci->db->select('message')->from('sh_settings')->where('name', 'maintenance')->get()->row()->message;
    $old = $ci->session->userdata("userdata");
    $sql = "Select "
    . "rol.name as role_name,"
    . "u.id as user_id,u.*,"
    . "sh.url as sh_url, "
    . "sh.name as sh_name, "
    . "sh.logo as sh_logo, "
    . "sh.theme_color as theme_color, "
    . "sh.address as sh_address, "
    . "sh.phone as sh_phone, "
    . "sh.teacher_dept_id as teacher_dept_id, "
    . "sh.time_zone as time_zone, "
    . "c.symbol as currency_symbol, "
    . "li.* From sh_users u "
    . "Inner Join sh_school sh ON u.school_id=sh.id "
    . "Inner Join sh_roles rol ON u.role_id=rol.id "
    . "Left Join sh_school_currencies sc ON sh.id = sc.school_id and is_default = 'yes' and sc.deleted_at is null "
    . "Left Join sh_currency c ON sc.currency_id = c.id "
    . "Left Join sh_license li "
    . "ON u.school_id=li.school_id Where u.id = " . $ci->session->userdata("userdata")['user_id'];
    //echo $sql; exit;
    $academic_year = $ci->db->select('id,name')->from('sh_academic_years')->where('is_active','Y')->where('school_id',$ci->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->get()->row();
    $academic_year_name = "-";
    $academic_year_id = 0;
    if($academic_year){
        $academic_year_name = $academic_year->name;
        $academic_year_id = $academic_year->id;
    }
    $query = $ci->db->query($sql);
    $data = $query->row();
    $old["academic_year_name"] = $academic_year_name;
    $old["academic_year"] = $academic_year_id;
    
    if($data->permissions != ""){
        $temp = json_decode($data->permissions);
        foreach ($temp as $key => $value) {
            $temp[$key]->label = ""; 
        }
        $data->permissions = json_encode($temp);
    }
    
    
    $old['persissions'] = $data->permissions;
    $old['name'] = $data->name;
    $old['sh_name'] = $data->sh_name;
    $old['sh_logo'] = $data->sh_logo;
    $old['teacher_dept_id'] = $data->teacher_dept_id;
    $old['currency_symbol'] = $data->currency_symbol;
    $old['theme_color'] = $data->theme_color;
    $old['time_zone'] = $data->time_zone;
    $old['maintenance_msg'] = $maintenance_msg;
    $ci->session->set_userdata("userdata", $old);
    $arr = $ci->session->userdata("userdata")['persissions'];

    $array = json_decode($arr);
    //unset label from session array
    if (isset($array)) {
        foreach ($array as $key => $value) {
            unset($array[$key]->label);
        }

        // || ($value->permission == 'manage-changeEmpStatus'  && $value->val == 'true') || ( $value->permission == 'manage-viewStduents'  && $value->val == 'true') || ($value->permission == 'manage-changeStdStatus' && $value->val == 'true') || ( $value->permission == 'manage-viewGuardians' && $value->val == 'true') || ( $value->permission == 'manage-changeGuardianStatus' && $value->val == 'true')



        // add index for usermanagement
        $arr1 = array('permission' => 'usermanagement-index', 'val' => 'false');
        foreach ($array as $key => $value) {
           
            if (($value->permission == 'manage-viewEmployees' && $value->val == 'true') || ($value->permission == 'manage-changeEmpStatus' && $value->val == 'true') || ($value->permission == 'manage-viewStduents' && $value->val == 'true')  || ($value->permission == 'manage-changeStdStatus' && $value->val == 'true') || ($value->permission == 'manage-viewGuardians' && $value->val == 'true')  || ($value->permission == 'manage-changeGuardianStatus' && $value->val == 'true')  )
            {
                $arr1 = array('permission' => 'usermanagement-index', 'val' => 'true');
                break;
            }
            
        }
        $array[] = (object) $arr1;
        $arr2 = array('permission' => 'dashboard-index', 'val' => 'true');
        //array_push($array,$arr);
        $array[] = (object) $arr2;
       // print_r($array);die();
    }
    // fetch controller and method from url
    $controller = $ci->router->fetch_class();
    $method = $ci->router->fetch_method();
    // set url to json obejct to compare with session array
    $per = (object) array("permission" => $controller . '-' . $method, "val" => "true");
//print_r($array);die();
    // $permit = 1;
    // if(isset($array)){
    //     if(in_array($per, $array)){
    //     $permit = 1;
    //     }else{
    //         $permit = 0;
    //     }
    // }
    //$permit = 1;
    if (isset($array)) {
        $permit = 1;
        foreach ($array as $key => $value) {
            $permit = 1;
            if ($per->permission == $value->permission && $per->val != $value->val) {
                $permit = 0;
                break;
            }
        }
    }


   // check user permission allowd

    if (isset($permit)){
        if ($permit == 0) {
            $ci->session->set_flashdata('alert_no_permission', '<div class="alert alert-danger alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">Ã—</button>Sorry! You Don' ."'". 't have Permission. </div>');
            redirect(site_url("dashboard"));
        }
    }
}

function render_universal_tags($form, $html) {
    $ci = & get_instance();
    $school_id = $ci->session->userdata("userdata")["sh_id"];
    $logo = base_url().'uploads/logos/' . $ci->session->userdata("userdata")["sh_logo"];
    $tags = array();
    $tags["date"] = date("d/m/Y");
    $tags["time"] = date("h:i:s");
    $tags["school_name"] = $ci->session->userdata("userdata")["sh_name"]; //froms ession
    $tags["school_email"] = $ci->session->userdata("userdata")["email"]; //froms ession
    $tags["school_phone"] = $ci->session->userdata("userdata")["contact"]; //froms ession
    $tags["school_address"] = $ci->session->userdata("userdata")["sh_phone"]; //froms ession
    $tags["school_website"] = $ci->session->userdata("userdata")["sh_url"]; //froms ession
    $tags["school_country"] = $ci->admin_model->dbSelect("country", "school", " id='$school_id' ")[0]->country; //froms ession
    $tags["school_city"] = $ci->admin_model->dbSelect("city", "school", " id='$school_id' ")[0]->city; //froms ession
    $tags["school_logo"] = "<img src='$logo' width='100'/>";
    $tags["logged_in_user"] = $ci->session->userdata("userdata")["name"];
    $tags["form_title"] = $form->name;
    $tags["form_category"] = $form->form_category;

    //find all keys in $tags array 
    //loop through the keys str_replace("{key}", $tags[key] , $rendered_html)
    $rendered_html = $html;

    foreach ($tags as $key => $value) {
        $rendered_html = str_replace("{" . $key . "}", $tags[$key], $rendered_html);
    }

    return $rendered_html;
}

function getUserFontSize() {
    $ci = & get_instance();
    $user_id = $ci->session->userdata("userdata")["user_id"];
    $result = $ci->admin_model->dbSelect("font_size", "users", " id='$user_id' ")[0]->font_size;
    return $result;
}

function render_payroll_tags($data, $html, $lang_id){
    $html->html = render_universal_tags($html, $html->html);
    $total_paid=0;
    //echo "<pre/>"; print_r($data); die();
    
    $tags = array();
    $tags["salary_slip_heading"] = lang("lbl_salary_slip_heading");
    $tags["salary_month_year"] = date('F Y',strtotime(explode(" ",$data[count($data)-1]->created_at)[0]));
    $tags["salary_name"] = $data[count($data)-1]->salary_name;
    $tags["employee_name"] = $data[count($data)-1]->employee_name;
    $tags["employee_category"] = $data[count($data)-1]->category_name;
    $tags["basic_salary"] = $data[count($data)-1]->salary_amount;
    $tags["allowances"] = $data[count($data)-1]->total_amount - ($data[count($data)-1]->salary_amount + $data[count($data)-1]->deduction_amount);
    $tags["total_paid"] = $total_paid = $data[count($data)-1]->salary_amount + $data[count($data)-1]->total_amount - ($data[count($data)-1]->salary_amount + $data[count($data)-1]->deduction_amount);
    $tags["deductions"] = $data[count($data)-1]->deduction_amount;
    $tags["total_deductions"] = $data[count($data)-1]->deduction_amount;
    $tags["payment_date"] = to_html_date(explode(" ",$data[count($data)-1]->created_at)[0]);
    $tags["employee_department"] = $data[count($data)-1]->department_name;
    $tags["payment_mode"] = $data[count($data)-1]->mode;
    $tags["net_payment"] = $data[count($data)-1]->amount_paid;
    $tags["confidentiality_note"] = lang("confidentiality_note");

    $rendered_html = $html->html;
    foreach ($tags as $key => $value) {
        $rendered_html = str_replace("{" . $key . "}", $tags[$key], $rendered_html);
    }
    return $rendered_html;
}

function render_fee_tags($data, $html, $lang_id) {
    $ci = & get_instance();
    $html->html = render_universal_tags($html, $html->html);
    $tags = array();
    $tags["student_name"] = $data["data"]->student_name;
    $tags["father_name"] = $data["data"]->guardian_name;
    
    if(isset($data["data"]->fee_in_paid_class_name) && isset($data["data"]->fee_in_paid_batch_name)){
        $tags["class_name"] = $data["data"]->fee_in_paid_class_name;
        $tags["section_name"] = $data["data"]->fee_in_paid_batch_name;
    } else {
        $tags["class_name"] = $data["data"]->class_name;
        $tags["section_name"] = $data["data"]->batch_name;
    }
    
    $tags["fee_card"] = render_fee_details($data, $lang_id);
    $tags["fee_receipt_sr_no"] = format_receipt_no($data["data"]->receipt_no);
    $tags["phone_number"] = $data["data"]->mobile_phone;
    $tags["student_rollno"] = $data["data"]->rollno;
    // feedback update 
    $tags["discounted_amount"] = $data['data']->feetype_amount-$data["data"]->discount_amount;
    //$tags["discounted_amount"] = $data["data"]->discount_amount;
    // feedback update 
    $tags["discount_type"] = $data["data"]->discount_type;
    $tags["fee_type"] = $data["data"]->ftype;
    $tags["fee_collected_by"] = $data["data"]->collector_name;
    $tags["fee_status"] = $data["data"]->status ? 'Paid' : 'Unpaid';
    $tags["fee_amount"] = $data['data']->amount;
    $tags["fee_paid_date"] = $data["data"]->created_at;
    if($data["data"]->feetype_amount == 0){
        $tags["discount_percentage"] = 0;
    }else{
        $tags["discount_percentage"] = 100-($data["data"]->discount_amount*100/$data["data"]->feetype_amount);
        $tags["discount_percentage"] = number_format((float)$tags["discount_percentage"], 2, '.', '');
        // $tags['discount_percentage'] = $data['data']->discount_percentage;
    }
    
    $tags["fee_paid_amount"] = $data["data"]->paid_amount;
    $tags["payment_mode"] = $data["data"]->mode;
    $tags["student_profile"] = "<img src='uploads/user/" . $data['data']->avatar . "' alt='uploads/user/profile.png' width='100'/>";

    $rendered_html = $html->html;
    foreach ($tags as $key => $value) {
        $rendered_html = str_replace("{" . $key . "}", $tags[$key], $rendered_html);
    }
    return $rendered_html;
}

function format_receipt_no($no){
    return "#" . str_pad($no, 6, '0', STR_PAD_LEFT);
}

function render_fee_details($data, $lang_id){
    $ci = & get_instance();
    
    //-----------------------------------------//
    //        Start::Pervious Fee Record       //
    //-----------------------------------------//
    
    $class_id = $data["data"]->class_id;
    $school_id = $data["data"]->school_id;
    $student_id = $data["data"]->student_id;
    $feetypes = $ci->admin_model->dbSelect("*", "fee_types", " school_id='$school_id' AND class_id='$class_id' AND deleted_at IS NULL ");
    $sql = "SELECT 
    COALESCE(c.id,'NULL') as fee_collectiton_id, 
    c.discount_id as discount_id, 
    c.discount_amount as discount_amount, 
    ft.id as feetype_id, 
    COALESCE(c.feetype_amount,0) as feetype_amount, 
    ft.name as feetype, 
    ft.due_date as due_date, 
    COALESCE(DATE_FORMAT(c.created_at,'%d/%m/%Y'), 'NULL') as paid_date,
    COALESCE(c.paid_amount,0) as paid_amount,
    COALESCE(c.status, '-') as status, 
    COALESCE(c.mode, NULL) as mode, 
    ft.amount as amount,
    cc.name as class_name,
    bb.name as batch_name

    FROM 
    sh_fee_types ft 
    LEFT JOIN sh_fee_collection c ON c.feetype_id=ft.id 
    LEFT JOIN sh_classes cc ON c.class_id=cc.id
    LEFT JOIN sh_batches bb ON c.batch_id=bb.id
    
    WHERE 
    ft.school_id='$school_id' 
    AND c.deleted_at IS NULL 
    AND ft.deleted_at IS NULL 
    AND c.student_id='$student_id'";
    
    $collected_fees = $ci->admin_model->dbQuery($sql);
    
    $paid_feetype_names = array();
    foreach ($collected_fees as $c) {
        array_push($paid_feetype_names, $c->feetype);
    }

    foreach ($feetypes as $feetype) {
        if (!in_array($feetype->name, $paid_feetype_names)) {
            $array = array(
                "fee_collectiton_id" => 'NULL',
                "discount_id" => 'NULL',
                "discount_amount" => 'NULL',
                "feetype_id" => $feetype->id,
                "feetype_amount" => 0,
                "feetype" => $feetype->name,
                "due_date" => to_html_date($feetype->due_date),
                "paid_date" => "",
                "paid_amount" => 0,
                "status" => 0,
                "mode" => NULL,
                "amount" => $feetype->amount,
                "class_name" => NULL,
                "batch_name" => NULL
            );
            array_push($collected_fees, (object)$array);
        }
    }
    
    $fee_card = "";
    if($lang_id == 1) {
        $fee_card .= "<table class='table table-bordered text-center' style='font-size:14px; font-family:sans-serif;'><tr>";
        $fee_card .= "<th class='text-center'>Fee Types</th>";
        $fee_card .= "<th class='text-center'>Fee Amount</th>";
        $fee_card .= "<th class='text-center'>Paid Date</th>";
        $fee_card .= "<th class='text-center'>Status</th>";
        $fee_card .= "<th class='text-center'>Discount %Age</th>";
        $fee_card .= "<th class='text-center'>Paid Amount</th>";
        $fee_card .= "<th class='text-center'>Class / Section</th>";
        $fee_card .= "</tr>";
    } else if($lang_id == 2){
        $fee_card .= "<table class='table table-bordered text-center' style='font-size:14px; font-family:sans-serif; direaction: rtl;'><tr>";
        $fee_card .= "<th class='text-center'>نوع الرسوم</th>";
        $fee_card .= "<th class='text-center'>مبلغ الرسوم</th>";
        $fee_card .= "<th class='text-center'>تاريخ الدفع</th>";
        $fee_card .= "<th class='text-center'>الحالة</th>";
        $fee_card .= "<th class='text-center'>نسبة الخصم</th>";
        $fee_card .= "<th class='text-center'>المبلغ المدفوع</th>";
        $fee_card .= "<th class='text-center'>فئة / القسم</th>";
        $fee_card .= "</tr>";
    }
    
    $prev_collected_feetype_id = null;
    $prev_balance = null;
    foreach ($collected_fees as $f){
        $fee_card .= "<tr>";
        $fee_card .= "<td>".$f->feetype."</td>";
        $fee_card .= "<td>".$f->amount." ". $ci->session->userdata("userdata")["currency_symbol"]."</td>";
        $fee_card .= "<td>".$f->paid_date."</td>";
        
        if($f->status == 1){
            $fee_card .= "<td>".lang("lbl_paid")."</td>";
        } else if($f->status == 0){
            $fee_card .= "<td>".lang("lbl_pending")."</td>";
        } else if($f->status == 2){
            $fee_card .= "<td>".lang("lbl_fee_partially_paid")."</td>";
        } else {
            $fee_card .= "<td>".lang("lbl_unpaid")."</td>";
        }
        
        $vvv = 0;
        if($f->discount_amount != 0 && !is_null($f->discount_amount)){
            //$vvv = $f->discount_amount*100/$f->feetype_amount;
            // fee update feeback sheraz 
            $vvv = 100-($f->discount_amount*100/$f->feetype_amount);
            $vvv = number_format((float)$vvv, 2, '.', '');
        }
        $fee_card .= "<td>".$vvv."%</td>";
        $fee_card .= "<td>".$f->paid_amount." ". $ci->session->userdata("userdata")["currency_symbol"]."</td>";
        $fee_card .= "<td>".$f->class_name." / ". $f->batch_name."</td>";
        
        
        //$balance = calculateBalance($collected_fees, $f->feetype_id);
        /*if(!is_null($f->discount_amount) && $f->discount_amount != 0)
        { 
            $balance = $f->paid_amount - ($f->feetype_amount - (($f->discount_amount*100/$f->feetype_amount)*$f->feetype_amount/100));
        }*/
        //$fee_card .= "<td>".$balance."</td>";
        $fee_card .= "</tr>";
    }
    
    $fee_card .= "</table>";
    return $fee_card;
    
    //-----------------------------------------//
    //        End::Pervious Fee Record         //
    //-----------------------------------------//
    
    
}

function calculateBalance($collections, $id){
    $balance = 0;
    $paid_amount = 0;
    $total_fee_amount = 0;
    $discount_amount = null;
    foreach($collections as $col){
        if($id == $col->feetype_id){
            $total_fee_amount = $col->feetype_amount;
            $discount_amount = $col->discount_amount;
            $paid_amount = $paid_amount + $col->paid_amount;
        }
    }
    
    if(!is_null($discount_amount) && $discount_amount != 0) { 
        $balance = $paid_amount - ($total_fee_amount - (($discount_amount*100/$total_fee_amount)*$total_fee_amount/100));
    }
    
    return $balance;
}

function render_resultcard($data) {
    $ci = & get_instance();
    $logo_name = $ci->session->userdata("userdata")["sh_logo"];
    $school_id = $ci->session->userdata("userdata")["sh_id"];

    $guardian_name = NULL;
    $sql2 = "SELECT u.name as guardian_name FROM sh_users u LEFT JOIN sh_student_guardians grd ON grd.guardian_id=u.id WHERE student_id=" . $data->student_id;
    $res = $ci->admin_model->dbQuery($sql2);
    if (count($res) > 0) {
        $guardian_name = $res[0]->guardian_name;
    }

    $class_name = NULL;
    $class_array = $ci->admin_model->dbSelect("name", "classes", " id='$data->class_id' AND deleted_at IS NULL ");
    if (count($class_array) > 0) {
        $class_name = $class_array[0]->name;
    }

    $batch_name = NULL;
    $batch_array = $ci->admin_model->dbSelect("name", "batches", " id='$data->batch_id' AND deleted_at IS NULL ");
    if (count($batch_array) > 0) {
        $batch_name = $batch_array[0]->name;
    }

    $pass_fail_status = 'Pass';
    foreach ($data->subjects as $sub) {
        if ($sub->exams[0]->marksheet_status == 'Fail') {
            $pass_fail_status = 'Fail';
            break;
        }
    }

    $active_academic_year_array = $ci->admin_model->dbSelect("*", "academic_years", " school_id='$school_id' AND deleted_at IS NULL AND is_active='Y' ");
    $active_academic_year = array();
    if (count($active_academic_year_array) > 0) {
        $active_academic_year = $active_academic_year_array[0];
    }

    $exam_name = null;
    foreach ($data->subjects as $sub) {
        if (isset($sub->exams[0]->exam_name)) {
            $exam_name = $sub->exams[0]->exam_name;
        }
    }

    $logo = "<img src='" . base_url() . "uploads/logos/" . $logo_name . "' alt='logo' width='120px' style='margin-top:5px; margin-bottom:5px;'/>";
    $profile_url = base_url() . "uploads/user/" . $data->student_avatar;
    $student_profile = "<img src='" . $profile_url . "' width='100px' style='border:2px inset;'/>";
    $html = '<link href="' . base_url() . 'assets/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">';
    $html .= '<div class="container" style="font-size:12px; padding-left:25px; padding-right:25px; border:3px solid black; font-family: calbri;">';
    $html .= '<div class="col-md-4" style="display:inline-block;"></div>'
    . '<div class="col-md-4 text-center" style="display:inline-block;">' . $logo . '</div>'
    . '<h3 class="text-center">' . $ci->session->userdata("userdata")["sh_name"] . '</h3>
    <h4 class="text-center"><u>Examination ' . $exam_name . '</u></h4>
    <h4 class="text-center"><u>' . $active_academic_year->name . '</u></h4>
    <table style="width:100%;">
    <tr>
    <td>
    <table width="100%">
    <tr>
    <td><strong>Student Name</strong></td><td><u>' . $data->student_name . '</u></td>
    <td><strong>Roll No.</strong></td><td><u>' . $data->student_id . '</u></td>
    </tr>
    <tr>
    <td><strong>Father Name</strong></td><td><u>' . $guardian_name . '</u></td>
    <td><strong>Class / Section</strong></td><td><u>' . $class_name . '-' . $batch_name . '</u></td>
    </tr>
    <tr>
    <td><strong>Result</strong></td><td><u>' . $data->result . '</u></td>
    <td><strong>Position</strong></td><td><u>' . $data->position . '</u></td>
    </tr>
    </table>
    </td>
    <td style="padding:15px;" align="right">' . $student_profile . '</td>
    </tr>
    </table>

    <table style="border:1px solid black" border="1px solid" width="100%">
    <tbody>
    <tr>
    <td style="text-align: center;" colspan="6"><strong>Result</strong></td>
    </tr>
    <tr>
    <td style="text-align: center;"><strong>Subjects</strong></td>
    <td style="text-align: center;"><strong>Total Marks</strong></td>
    <td style="text-align: center;"><strong>Obtain Marks</strong></td>
    <td style="text-align: center;"><strong>Grade</strong></td>
    <td style="text-align: center;"><strong>Status</strong></td>
    <td style="text-align: center;"><strong>Remarks</strong></td>
    </tr>';

    $grades_array = $ci->admin_model->dbSelect("*", "grades", " school_id='$school_id' AND deleted_at IS NULL ORDER BY percent_upto desc ");
    $grades = array();
    if (count($grades_array) > 0) {
        $grades = $grades_array;
    }

    foreach ($data->subjects as $sub) {
        $status = "";
        if ($sub->exams[0]->marksheet_status == "Fail") {
            $status = "F";
        } else if ($sub->exams[0]->marksheet_status == "Pass") {
            $status = "P";
        }
        $html .= '<td style="text-align: center;">' . $sub->subject_name . '</td>
        <td style="text-align: center;">' . $sub->exams[0]->total_marks . '</td>
        <td style="text-align: center;">' . $sub->exams[0]->obtained_marks . '</td>';

        $subject_percentage = null;
        if ($sub->exams[0]->total_marks != 0) {
            $subject_percentage = ($sub->exams[0]->obtained_marks * 100) / $sub->exams[0]->total_marks;
        }
        $sub_grade = NULL;
        foreach ($grades as $g) {
            if ($subject_percentage > $g->percent_from && $subject_percentage < $g->percent_upto) {
                $sub_grade = $g->name;
            }
        }
        $html .= '<td style="text-align: center;">' . $sub_grade . '</td>';
        $html .= '<td style="text-align: center;">' . $status . '</td>
        <td style="text-align: center;">' . $sub->exams[0]->remarks . '</td>
        </tr>';
    }

    $exam_total_marks = 0;
    foreach ($data->subjects as $sub) {
        $exam_total_marks += $sub->exams[0]->total_marks;
    }

    $exam_obtained_total_marks = 0;
    foreach ($data->subjects as $sub) {
        $exam_obtained_total_marks += $sub->exams[0]->obtained_marks;
    }

    $html .= '<tr>
    <td style="text-align: center;"><strong>Total</strong></td>
    <td style="text-align: center;">' . $exam_total_marks . '</td>
    <td style="text-align: center;" colspan="4">' . $exam_obtained_total_marks . '</td>
    </tr>';

    $percentage = '';
    if ($exam_total_marks != 0) {
        $percentage = ($exam_obtained_total_marks * 100) / $exam_total_marks;
    }


    $html .= '</tbody></table>';

    $html .= '<p></p><table style="border:1px solid black" border="1px solid" width="100%">
    <tr>
    <td>&nbsp;&nbsp;&nbsp;<strong>Pass Percentage:</strong>&nbsp;&nbsp;<span>40%</span></td>
    <td>&nbsp;&nbsp;&nbsp;<strong>Obtained Percentage: </strong>&nbsp;&nbsp;<span>' . $percentage . '%</span></td>
    </tr>
    <tr>
    <td>&nbsp;&nbsp;&nbsp;<strong>Attendance:</strong> <span>Pending</span></td>
    <td>&nbsp;&nbsp;&nbsp;<strong>Class Activity:</strong>&nbsp;&nbsp;<span>Active</span></td>
    </tr>
    <tr>
    <td colspan="2">&nbsp;&nbsp;&nbsp;<strong>Conduct:</strong> <span>Good</span></td>
    </tr>
    <tr>
    <td class="text-center">
    <h5><u>Grading System</u></h5>';
    $html .= '<table width="100%">';
    if (count($grades) > 0) {
        foreach ($grades as $g) {
            $html .= '<tr class="text-center"><td>' . $g->percent_from . '</td><td>-</td><td>' . $g->percent_upto . '</td><td>%</td><td>' . $g->name . '</td><td class="text-left">' . $g->description . '</td></tr>';
        }
    } else {
        $html .= '<tr><td colspan="6">' . lang("no_record") . '</td></tr>';
    }
    $html .= '</table>';

    $html .= '</td>
    <td style="max-width:50%; width:50%;">
    <table width="100%" border="1px solid black;" style="border:0px;">
    <tr><td><h5><i>Class Teacher`s Remarks</i></h5></td></td></tr>
    <tr style="height:85px; vertical-align:top;"><td><i>' . $data->teacher_remark . '</i></td></tr>
    <tr class="text-center"><td><h5><b><i>Keep it up</i></b></h5></td></tr>
    <tr>
    <td>
    <span><i>Signature of Class Teacher:</i></span>
    <br/><span>Date:</span>
    </td>
    </tr>
    </table>
    </td>
    </tr>
    </table>';

    $html .= "<br/><br/><table width='100%'><tr><td>";
    $html .= "<span><b>Sec. Head Sign:</b><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></span><br/>";
    $html .= "<span><b>Parent's Sign</b><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></span><br/>";
    $html .= "</td>";


    $html .= "<td class='text-center'>
    <b><label>PRINCIPAL</label></b><br/>
    <span><b>Date:&nbsp;&nbsp;&nbsp;" . to_html_date($data->subjects[0]->exams[0]->exam_date) . "</b></span>
    </td></table>";

    $html .= "<br/></div>";
    return $html;
}

function render_resultcard_tags_api($data, $html) {
    
    $ci = & get_instance();
    
    $form = $data["template"];
    $data2 = $data["data"];
    // $school_id = $ci->session->userdata("userdata")["sh_id"];
    $school_id = $data2['school_id'];
    $logo = base_url().'uploads/logos/'.$data2['school_logo'];
    // $logo = base_url().'uploads/logos/' . $ci->session->userdata("userdata")["sh_logo"];
    
    $selected_academic_year_id = $data["data"]["academic_year_id"];
    $min_passing_percentage = "";
    $yas_exam_id = $ci->admin_model->dbSelect("id","exams"," id='".$data2['exam_id']."' ")[0]->id;
    // $rules = $ci->admin_model->dbSelect("*","passing_rules", " class_id=".$data2['class_id']." AND batch_id=".$data2['batch_id']." AND exam_id=$yas_exam_id AND school_id=$school_id AND deleted_at IS NULL ");
    $rules = $ci->admin_model->dbSelect("*","passing_rules", " class_id=".$data2['class_id']." AND batch_id=".$data2['batch_id']." AND exam_id=$yas_exam_id AND school_id AND deleted_at IS NULL ");

    if(count($rules) > 0){
        $min_passing_percentage = $rules[0]->minimum_percentage;
    }

    $tags = array();
    $tags["date"] = date("d/m/Y");
    $tags["time"] = date("h:i:s");
    $tags["school_name"] = $data2["school_name"]; //froms ession
    $tags["school_email"] = $data2["school_email"]; //froms ession
    $tags["school_phone"] = $data2["school_phone"]; //froms ession
    $tags["school_address"] = $data2["school_address"]; //froms ession
    $tags["school_website"] = $data2["school_url"]; //froms ession
    $tags["school_country"] = $data2["school_country"]; //froms ession
    $tags["school_city"] = $data2["school_city"]; //froms ession
    $tags["school_logo"] = "<img src='$logo' id='school-logo' style='width:120px;'/>";
    // $tags["logged_in_user"] = $ci->session->userdata("userdata")["name"];
    $tags["logged_in_user"] = 'Test Example';
    $tags["form_title"] = $form->name;
    $tags["form_category"] = isset($form->form_category) ? $form->form_category:"NULL";
    $tags["student_name"] = $data2["student_name"];
    $tags["father_name"] = $data2["father_name"];
    $tags["class_name"] = $data2["class_name"];
    $tags["section_name"] = $data2["section_name"];
    $tags["phone_number"] = $data2["mobile_phone"];
    $tags["student_rollno"] = $data2["rollno"];
    $tags["student_profile"] = "<img id='profile-img' src='".base_url()."uploads/user/" . $data2["avatar"] . "' alt='".base_url()."uploads/user/profile.png' style='border:2px solid #222222;' width='140px'/>";
    $tags["exam_name"]=$data2["exam_name"];
    $tags["passing_percentage"]= $min_passing_percentage."%";
    $tags["attendance"]="OK";
    $tags["class_activity"]="Active";
    $tags["conduct"]="Good";
    $tags["class_teacher_remarks"]=$data2["class_teacher_remarks"];
    $tags["position"]=$data2["position"];
    
    $active_academic_year_array = $ci->admin_model->dbSelect("*", "academic_years", " id='$selected_academic_year_id' ");
    $active_academic_year = array();
    if (count($active_academic_year_array) > 0) {
        $active_academic_year = $active_academic_year_array[0]->name;
    }
    $tags["session_name"]=$active_academic_year;
    
    $grades_array = $ci->admin_model->dbSelect("*", "grades", " school_id='$school_id' AND deleted_at IS NULL ORDER BY percent_upto desc ");
    $grades = array();
    if (count($grades_array) > 0) {
        $grades = $grades_array;
    }
    
    //--------------Exam detail table----------------//
    $result_details = "";
    $exam_total_marks = 0;
    $exam_obtained_total_marks = 0;
    
    // echo '<pre>';
    // print_r($data["data"]["details"]);
    // die;
    foreach($data["data"]["details"] as $d) {
        if($d->total_obtained_marks != null || !empty($d->total_obtained_marks)) { 
            $exam_total_marks += intval($d->total_marks);
            if($d->type == 'number') {
                $exam_obtained_total_marks += intval($d->obtained_marks);
            } else {
                $exam_obtained_total_marks += intval($d->total_obtained_marks);
            }
            //------------------------Subject Percentage-------------------------//
            $sub_grade = NULL;
            $subject_percentage = null;
            if ($d->total_marks != 0) {
                // $subject_percentage = round(($d->obtained_marks * 100) / $d->total_marks);
                if ($d->total_obtained_marks == null) {
                    $subject_percentage = round(($d->obtained_marks * 100) / $d->total_marks);
                    $d->total_obtained_marks = $d->obtained_marks;    
                } else {
                    $subject_percentage = round(($d->total_obtained_marks * 100) / $d->total_marks);
                }
            }
            foreach ($grades as $g) {
                if ($subject_percentage >= $g->percent_from && $subject_percentage <= $g->percent_upto) {
                    $sub_grade = $g->name;
                }
            }
            //------------------------------------------------------------------//
            if($d->type == 'number'){
                $result_details .= '<tr><td style="text-align: center;">'.$d->subjnect_name.'</td>
                <td style="text-align: center;">'.$d->total_marks.'</td>';
                $result_details .= '<td>';
                $result_details .= '<span style="display:block; float:left; padding-left:5px;">Written Marks</span><span style="display:block; float:right; padding-right:5px;">'.$d->obtained_marks.'/'.$d->total_written_marks.'</span><br>';
                if (isset($d->activities)) {
                    foreach ($d->activities as $key => $ac) {
                        if(isset($ac->$key->obtained_marks) && $ac->$key->obtained_marks != null){
                            $result_details .= '<span style="display:block; float:left; padding-left:5px;">'.$ac->activity_name.'</span><span style="display:block; float:right; padding-right:5px;">'.$ac->$key->obtained_marks.'/'.$ac->marks.'</span></br>';
                        }
                    }
                }
                $result_details .= '</td>';
                $result_details .= '<td style="text-align: center;">'.$d->total_obtained_marks.'</td>
                <td style="text-align: center;">'.$sub_grade.'</td>
                <td style="text-align: center;">'.lang(strtolower($d->status)).'</td>
                <td style="text-align: center;">'.$d->remarks.'</td>
                </tr>';
            }else{
                $result_details .= '<tr><td style="text-align: center;">'.$d->subjnect_name.'</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: center;">'.$d->grade.'</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: center;">'.$d->remarks.'</td>
                </tr>';
            }
        }
    }
    $tags["exam_total_marks"] = $exam_total_marks;
    $tags["exam_obtained_total_marks"] = $exam_obtained_total_marks;
    
    $percentage = "";
    if ($exam_total_marks != 0) {
        $percentage = ($exam_obtained_total_marks * 100) / $exam_total_marks;
    }
    //---------------------------------------------------------//
    
    //-------------------Calculate pass/fail status--------------------//
    $pass_fail_status = null;
    if(count($rules)>0){
        $number_of_subjects_passed = 0;
        $pass_fail_status = lang('fail');
        foreach ($data["data"]["details"] as $sub) {
            if ($sub->status == "Pass") {
                $number_of_subjects_passed++;
            }
        }
        if ($rules[0]->operator == "AND") {
            if($number_of_subjects_passed >= $rules[0]->minimum_subjects && $percentage >= $rules[0]->minimum_percentage) {
                $pass_fail_status = lang("pass");
            }
        } else if($rules[0]->operator == "OR") {
            if($number_of_subjects_passed >= $rules[0]->minimum_subjects || $percentage >= $rules[0]->minimum_percentage) {
                $pass_fail_status = lang("pass");
            }
        }
    } else {
        $pass_fail_status = lang('pass');
        foreach ($data["data"]["details"] as $sub) {
            if ($sub->status == "Fail") {
                $pass_fail_status = lang('fail');
                break;
            }
        }
    }
    $tags["result_status"]=$pass_fail_status;
    //----------------------------------------------------------------//
    
    $tags["result_details"]=$result_details;
    $tags["obtained_percentage"]=intval($percentage)."%";
    
    //-------------------------------------------//
    $grading_table = "";
    //$grading_table .= '<table width="100%"><tr><td colspan="6" class="text-center"><h4><b><u>'.lang("lbl_grading_system").'</u></b></h4></td></tr>';
    if (count($grades) > 0) {
        foreach ($grades as $g) {
            $grading_table .= '<tr class="text-center"><td>' . $g->percent_from . '</td><td>-</td><td>' . $g->percent_upto . '</td><td>%</td><td>' . $g->name . '</td><td class="text-left">' . $g->description . '&nbsp;&nbsp;&nbsp;</td></tr>';
        }
    } else {
        $grading_table .= '<tr><td colspan="6">' . lang("no_record") . '</td></tr>';
    }
    //$grading_table .= '</table>';
    $tags["grading_details"]=$grading_table;
    //-------------------------------------------//
    
    $rendered_html = $html->html;
    foreach ($tags as $key => $value) {
        $rendered_html = str_replace("{" . $key . "}", $tags[$key], $rendered_html);
    }

    return $rendered_html;
}

function render_resultcard_tags($data, $html) {
    $ci = & get_instance();
    //Condition added to show without details coulmn in result card
     // sheraz update 09-06-2021
    //  print_r($data["data"]["details"][0]->activities);die;
    if(count($data["data"]["details"][0]->activities) == 0) {
        $data['template']->html = str_replace('<td style="text-align: center; width: 16.3757%;"><strong>Details</strong></td>',"",$data['template']->html);
    }
     // sheraz update 09-06-2021
    $school_id = $ci->session->userdata("userdata")["sh_id"];
    $logo = base_url().'uploads/logos/' . $ci->session->userdata("userdata")["sh_logo"];
    $form = $data["template"];
    $data2 = $data["data"];
    $selected_academic_year_id = $data["data"]["academic_year_id"];
    $min_passing_percentage = "";
    $yas_exam_id = $ci->admin_model->dbSelect("id","exams"," id='".$data2['exam_id']."' ")[0]->id;
    // $rules = $ci->admin_model->dbSelect("*","passing_rules", " class_id=".$data2['class_id']." AND batch_id=".$data2['batch_id']." AND exam_id=$yas_exam_id AND school_id=$school_id AND deleted_at IS NULL ");
    $rules = $ci->admin_model->dbSelect("*","passing_rules", " class_id=".$data2['class_id']." AND batch_id=".$data2['batch_id']." AND exam_id=$yas_exam_id AND school_id AND deleted_at IS NULL ");
    
    if(count($rules) > 0){
        $min_passing_percentage = $rules[0]->minimum_percentage;
    }

    $tags = array();
    $tags["date"] = date("d/m/Y");
    $tags["time"] = date("h:i:s");
    $tags["school_name"] = $ci->session->userdata("userdata")["sh_name"]; //froms ession
    $tags["school_email"] = $ci->session->userdata("userdata")["email"]; //froms ession
    $tags["school_phone"] = $ci->session->userdata("userdata")["contact"]; //froms ession
    $tags["school_address"] = $ci->session->userdata("userdata")["sh_phone"]; //froms ession
    $tags["school_website"] = $ci->session->userdata("userdata")["sh_url"]; //froms ession
    $tags["school_country"] = $ci->admin_model->dbSelect("country", "school", " id='$school_id' ")[0]->country; //froms ession
    $tags["school_city"] = $ci->admin_model->dbSelect("city", "school", " id='$school_id' ")[0]->city; //froms ession
    $tags["school_logo"] = "<div class='top_header_logo'><img src='$logo' id='school-logo' style='width:120px;'/></div>";
    // $tags["school_logo"] = "<div class='top_header_logo'><img src='". base_url()."uploads/logos/" . $ci->session->userdata("userdata")["sh_logo"] ."' id='school-logo' style='width:120px;'/></div>";
    $tags["logged_in_user"] = $ci->session->userdata("userdata")["name"];
    $tags["form_title"] = $form->name;
    $tags["form_category"] = isset($form->form_category) ? $form->form_category:"NULL";
    $tags["student_name"] = $data2["student_name"];
    $tags["father_name"] = $data2["father_name"];
    $tags["class_name"] = $data2["class_name"];
    $tags["section_name"] = $data2["section_name"];
    $tags["phone_number"] = $data2["mobile_phone"];
    $tags["student_rollno"] = $data2["rollno"];
    $tags["student_profile"] = "<img id='profile-img' src='".base_url()."uploads/user/" . $data2["avatar"] . "' alt='".base_url()."uploads/user/profile.png' style='border:2px solid #222222;' width='140px'/>";
    $tags["exam_name"]=$data2["exam_name"];
    $tags["passing_percentage"]= $min_passing_percentage."%";
    $tags["attendance"]="OK";
    $tags["class_activity"]="Active";
    $tags["conduct"]="Good";
    $tags["class_teacher_remarks"]=$data2["class_teacher_remarks"];
    $tags["position"]=$data2["position"];
    
    $active_academic_year_array = $ci->admin_model->dbSelect("*", "academic_years", " id='$selected_academic_year_id' ");
    $active_academic_year = array();
    if (count($active_academic_year_array) > 0) {
        $active_academic_year = $active_academic_year_array[0]->name;
    }
    $tags["session_name"]=$active_academic_year;
    
    $grades_array = $ci->admin_model->dbSelect("*", "grades", " school_id='$school_id' AND deleted_at IS NULL ORDER BY percent_upto desc ");
    $grades = array();
    if (count($grades_array) > 0) {
        $grades = $grades_array;
    }
    
    //--------------Exam detail table----------------//
    $result_details = "";
    $exam_total_marks = 0;
    $exam_obtained_total_marks = 0;
    foreach($data["data"]["details"] as $d) {
        if($d->total_obtained_marks != 'null' || !empty($d->total_obtained_marks)) { 
            $exam_total_marks += intval($d->total_marks);
            if($d->type == 'number') {
                if ($d->total_obtained_marks == null) {
                    $exam_obtained_total_marks += intval($d->obtained_marks);    
                } else {
                    $exam_obtained_total_marks += intval($d->total_obtained_marks);
                }
            }
            //------------------------Subject Percentage-------------------------//
            $sub_grade = NULL;
            $subject_percentage = null;
            if ($d->total_marks != 0) {
                if ($d->total_obtained_marks == null) {
                    $subject_percentage = round(($d->obtained_marks * 100) / $d->total_marks);
                    $d->total_obtained_marks = $d->obtained_marks;    
                } else {
                    $subject_percentage = round(($d->total_obtained_marks * 100) / $d->total_marks);
                }
            }
            foreach ($grades as $g) {
                if ($subject_percentage >= $g->percent_from && $subject_percentage <= $g->percent_upto) {
                    $sub_grade = $g->name;
                }
            }
            //------------------------------------------------------------------//
            if($d->type == 'number'){
                $result_details .= '<tr><td style="text-align: center;">'.$d->subjnect_name.'</td>
                <td style="text-align: center;">'.$d->total_marks.'</td>';
                // sheraz update 09-06-2021
                if (isset($d->activities) && count($d->activities) > 0) {
                    $result_details .= '<td>';
                    $result_details .= '<span style="display:block; float:left; padding-left:5px;">Written Marks</span><span style="display:block; float:right; padding-right:5px;">'.$d->obtained_marks.'/'.$d->total_written_marks.'</span><br>';
                    foreach ($d->activities as $key => $ac) {
                        if(isset($ac->$key->obtained_marks) && $ac->$key->obtained_marks != null){
                            $result_details .= '<span style="display:block; float:left; padding-left:5px;">'.$ac->activity_name.'</span><span style="display:block; float:right; padding-right:5px;">'.$ac->$key->obtained_marks.'/'.$ac->marks.'</span></br>';
                            $result_details .= '</td>';
                        }
                    }
                } else {
                    // $result_details .= '<td>';
                    //$result_details .= '<span style="text-align: center;">'.$d->obtained_marks.'</span>';
                    // $result_details .= '</td>';
                }
               // $result_details .= '</td>';
               // sheraz update 09-06-2021

                $result_details .= '<td style="text-align: center;">'.$d->total_obtained_marks.'</td>
                <td style="text-align: center;">'.$sub_grade.'</td>
                <td style="text-align: center;">'.lang(strtolower($d->status)).'</td>
                <td style="text-align: center;">'.$d->remarks.'</td>
                </tr>';
            }else{
                $result_details .= '<tr><td style="text-align: center;">'.$d->subjnect_name.'</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: center;">'.$d->grade.'</td>
                <td style="text-align: center;">-</td>
                <td style="text-align: center;">'.$d->remarks.'</td>
                </tr>';
            }
        }
    }
    $tags["exam_total_marks"] = $exam_total_marks;
    $tags["exam_obtained_total_marks"] = $exam_obtained_total_marks;
    
    $percentage = "";
    if ($exam_total_marks != 0) {
        $percentage = ($exam_obtained_total_marks * 100) / $exam_total_marks;
    }
    //---------------------------------------------------------//
    
    //-------------------Calculate pass/fail status--------------------//
    $pass_fail_status = null;
    if(count($rules)>0){
        $number_of_subjects_passed = 0;
        $pass_fail_status = lang('fail');
        foreach ($data["data"]["details"] as $sub) {
            if ($sub->status == "Pass") {
                $number_of_subjects_passed++;
            }
        }
        if ($rules[0]->operator == "AND") {
            if($number_of_subjects_passed >= $rules[0]->minimum_subjects && $percentage >= $rules[0]->minimum_percentage) {
                $pass_fail_status = lang("pass");
            }
        } else if($rules[0]->operator == "OR") {
            if($number_of_subjects_passed >= $rules[0]->minimum_subjects || $percentage >= $rules[0]->minimum_percentage) {
                $pass_fail_status = lang("pass");
            }
        }
    } else {
        $pass_fail_status = lang('pass');
        foreach ($data["data"]["details"] as $sub) {
            if ($sub->status == "Fail") {
                $pass_fail_status = lang('fail');
                break;
            }
        }
    }
    $tags["result_status"]=$pass_fail_status;
    //----------------------------------------------------------------//
    
    $tags["result_details"]=$result_details;
    $tags["obtained_percentage"]=intval($percentage)."%";
    
    //-------------------------------------------//
    $grading_table = "";
    //$grading_table .= '<table width="100%"><tr><td colspan="6" class="text-center"><h4><b><u>'.lang("lbl_grading_system").'</u></b></h4></td></tr>';
    if (count($grades) > 0) {
        foreach ($grades as $g) {
            $grading_table .= '<tr class="text-center"><td>' . $g->percent_from . '</td><td>-</td><td>' . $g->percent_upto . '</td><td>%</td><td>' . $g->name . '</td><td class="text-left">' . $g->description . '&nbsp;&nbsp;&nbsp;</td></tr>';
        }
    } else {
        $grading_table .= '<tr><td colspan="6">' . lang("no_record") . '</td></tr>';
    }
    //$grading_table .= '</table>';
    $tags["grading_details"]=$grading_table;
    //-------------------------------------------//
    
    $rendered_html = $html->html;
    foreach ($tags as $key => $value) {
        $rendered_html = str_replace("{" . $key . "}", $tags[$key], $rendered_html);
    }

    return $rendered_html;
}

function getColors($count) {
    $colors = array(
        "#000000", "#ffc0cb", "#008080", "#ff0000", "#ffd700", "#40e0d0", "#00ffff", "#ff7373", "#0000ff", "#ffa500",
        "#b0e0e6", "#7fffd4", "#cccccc", "#800080", "#333333", "#fa8072", "#00ff00", "#ffb6c1", "#003366", "#468499",
        "#f6546a", "#20b2aa", "#800000", "#ffff00", "#f08080", "#ff6666", "#666666", "#66cdaa", "#00ced1", "#ff00ff",
        "#008000", "#088da5", "#8b0000", "#660066", "#0e2f44", "#990000", "#ffff66", "#00ff7f", "#3399ff", "#8a2be2",
        "#66cccc", "#ff4040", "#a0db8e", "#cc0000", "#ccff00", "#000080", "#31698a", "#191970", "#0099cc", "#6dc066"
    );
    $arr = array();
    for ($i = 0; $i <= $count; $i++) {
        array_push($arr, $colors[$i]);
    }
    return $arr;
}

function countExpiry($end) {
    $date1 = date("Y-m-d");
    $date2 = $end;

    $diff = abs(strtotime($date2) - strtotime($date1));

    $years = floor($diff / (365 * 60 * 60 * 24));
    $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
    $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

    return array("years" => $years, "months" => $months, "days" => $days);
}

function find_marks($student_id, $exam_detail_id, $marks) {

    foreach ($marks as $key => $m) {
        if ($exam_detail_id == $m->exam_detail_id && $student_id == $m->student_id) {


            return $key;
        }
    }

    return -1;
}

function max_attribute_in_array($array, $prop) {
    return max(array_map(function($o) use($prop) {
        if($o->result == 'Pass') {
            return $o->$prop;
        } else {
            return false;
        }
    }, $array));
}

function system_result_card_template(){
    return '<!DOCTYPE html><html><head></head><body>
<div class="result_card_container" style="direction: ltr;">
<p align="center">{school_logo}</p>
<h3 id="school-name" class="text-center" style="text-align: center;"><strong>{school_name}</strong></h3>
<h5 id="exam-name" class="text-center" style="text-align: center;">Examination {exam_name}</h5>
<h5 id="session-name" class="text-center" style="text-align: center;"><span style="text-decoration: underline;">{session_name}</span></h5>
<table id="std-info-table" style="width: 100%;">
<tbody>
<tr>
<td><strong>Student Name</strong></td>
<td><u>{student_name}</u></td>
<td><strong>Roll No.</strong></td>
<td><u>{student_rollno}</u></td>
<td style="text-align: right;" colspan="1" rowspan="3">{student_profile}</td>
</tr>
<tr>
<td><strong>Father Name</strong></td>
<td><u>{father_name}</u></td>
<td><strong>Class / Section</strong></td>
<td><u>{class_name}-{section_name}</u></td>
</tr>
<tr>
<td><strong>Result</strong></td>
<td><u>{result_status}</u></td>
<td><strong>Position</strong></td>
<td><u>{position}</u></td>
</tr>
</tbody>
</table>
<table id="result-marks-table" style="border: 1px solid black; margin-top: 15px; font-size:10px;" border="1px solid" width="100%">
<tbody>
<tr>
<td style="text-align: center; width: 90%;" colspan="7"><strong>Result</strong></td>
</tr>
<tr>
<td style="text-align: center; width: 13%;"><strong>Subjects</strong></td>
<td style="text-align: center; width: 18.6243%;"><strong>Total Marks</strong></td>
<td style="text-align: center; width: 16.3757%;"><strong>Details</strong></td>
<td style="text-align: center; width: 11%;"><strong>Total Obtain Marks</strong></td>
<td style="text-align: center; width: 9%;"><strong>Grade</strong></td>
<td style="text-align: center; width: 9%;"><strong>Status</strong></td>
<td style="text-align: center; width: 13%;"><strong>Remarks</strong></td>
</tr>
<tr class="hidden_row">
<td style="width: 90%;" colspan="7">{result_details}</td>
</tr>
<tr>
<td style="text-align: center; width: 13%;"><strong>Total</strong></td>
<td style="text-align: center; width: 18.6243%;">{exam_total_marks}</td>
<td style="text-align: center; width: 16.3757%;">&nbsp;</td>
<td style="text-align: center; width: 42%;" colspan="4">{exam_obtained_total_marks}</td>
</tr>
</tbody>
</table>
<table id="other-info-table" style="border: 1px solid black; margin-top: 15px;font-size:10px;" border="1px solid; " width="100%">
<tbody>
<tr>
<td>&nbsp;&nbsp;&nbsp;<strong>Pass Percentage:</strong>&nbsp;&nbsp;{passing_percentage}</td>
<td>&nbsp;&nbsp;&nbsp;<strong>Obtained Percentage: </strong>&nbsp;&nbsp;{obtained_percentage}</td>
</tr>
<tr>
<td>&nbsp;&nbsp;&nbsp;<strong>Attendance:</strong> {attendance}</td>
<td>&nbsp;&nbsp;&nbsp;<strong>Class Activity:</strong> &nbsp;&nbsp;{class_activity}</td>
</tr>
<tr>
<td colspan="2">&nbsp;&nbsp;&nbsp;<strong>Conduct:</strong> {conduct}</td>
</tr>
<tr>
<td class="text-center">
<table id="grading-table" width="100%">
<tbody>
<tr>
<td class="text-center" colspan="6">
<h4><strong><u>Grading System</u></strong></h4>
</td>
</tr>
<tr class="hidden_row">
<td colspan="6">{grading_details}</td>
</tr>
</tbody>
</table>
</td>
<td style="max-width: 50%; width: 50%;">
<table id="teacher-remarks-table" class="table table-default" style="margin-bottom: 0;">
<tbody>
<tr>
<td style="padding: 5px 5px 0px 15px;">
<h5><em style="box-sizing: inherit;">Class Teacher`s Remarks</em></h5>
</td>
</tr>
<tr>
<td id="teacher-remark-place" style="height: 60px; max-height: 80px;">{class_teacher_remarks}</td>
</tr>
<tr>
<td style="padding: 5px 5px 0px 15px;">
<h5><span style="box-sizing: inherit; font-weight: bolder;"> <em style="box-sizing: inherit;">Keep it up</em></span></h5>
</td>
</tr>
<tr>
<td><em>Signature of Class Teacher:</em>&nbsp;<br />Date:</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody>
</table>
<table id="footer-table" width="100%">
<tbody>
<tr>
<td><strong>Sec. Head Sign:</strong><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br /><strong>Parent`s Sign</strong><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
<td class="text-center"><strong><label>PRINCIPAL</label></strong><br /><strong>Date:&nbsp;&nbsp;&nbsp;{date}</strong></td>
</tr>
</tbody>
</table>
</div>
</body></html>';    
}

function system_result_card_template_arabic(){
    return '<div class="result_card_container" style="direction:rtl;">
    <p align="center" id="school-logo">{school_logo}</p>
    <h4 id="school-name" class="text-center" style="text-align: center;">{school_name}</h4>
    <h5 id="exam-name" class="text-center" style="text-align: center;">فحص {exam_name}</h5>
    <h5 id="session-name" class="text-center" style="text-align: center;"><span style="text-decoration: underline;">{session_name}</span></h5>
    <table style="width: 100%;" id="std-info-table">
    <tbody>
    <tr>
    <td><strong>أسم الطالب</strong></td>
    <td><u>{student_name}</u></td>
    <td><strong>رقم الطالب</strong></td>
    <td><u>{student_rollno}</u></td>
    <td colspan="1" rowspan="3" style="text-align:left;">{student_profile}</td>
    </tr>
    <tr>
    <td><strong>اسم الأب</strong></td>
    <td><u>{father_name}</u></td>
    <td><strong>الصف /القسم</strong></td>
    <td><u>{class_name}-{section_name}</u></td>
    </tr>
    <tr>
    <td><strong>النتيجة</strong></td>
    <td><u>{result_status}</u></td>
    <td><strong>الترتيب</strong></td>
    <td><u>{position}</u></td>
    </tr>
    </tbody>
    </table>

    <table id="result-marks-table" style="border:1px solid black; margin-top:15px; font-size:10px;" border="1px solid" width="100%">
    <tr><td style="text-align: center;" colspan="7"><strong>النتيجة</strong></td></tr>
    <tr>
    <td style="text-align: center;"><strong>اسم المادة</strong></td>
    <td style="text-align: center;"><strong>الدرجة الكاملة</strong></td>
    <td style="text-align: center;"><strong>تفاصيل</strong></td>
    <td style="text-align: center;"><strong>درجة الطالب</strong></td>
    <td style="text-align: center;"><strong>التقدير</strong></td>
    <td style="text-align: center;"><strong>الحالة</strong></td>
    <td style="text-align: center;"><strong>ملاحظات</strong></td>
    </tr>
    <tr class="hidden_row"><td colspan="6">{result_details}</td></tr>
    <tr>
    <td style="text-align: center;"><strong>المجموع</strong></td>
    <td style="text-align: center;">{exam_total_marks}</td>
    <td style="text-align: center;" colspan="4">{exam_obtained_total_marks}</td>
    </tr>
    </table>

    <table id="other-info-table" style="border: 1px solid black; margin-top:15px; font-size:10px" border="1px solid" width="100%"><tbody><tr>
    <td>&nbsp;&nbsp;&nbsp;<strong>نسبة النجاح:</strong>&nbsp;&nbsp;{passing_percentage}</td><td>&nbsp;&nbsp;&nbsp;<strong>نسبة نجاح الطالب: </strong>&nbsp;&nbsp;{obtained_percentage}</td>
    </tr><tr><td>&nbsp;&nbsp;&nbsp;<strong>حضور الطالب:</strong> {attendance}</td><td>&nbsp;&nbsp;&nbsp;<strong>المشاركة في الصف:</strong>&nbsp;&nbsp;{class_activity}</td>
    </tr><tr><td colspan="2">&nbsp;&nbsp;&nbsp;<strong>سلوك الطالب:</strong> {conduct}</td></tr>
    <tr>
    <td class="text-center">
    <table id="grading-table" width="100%"><tr><td colspan="6" class="text-center"><h4><b><u>نظام الدرجات</u></b></h4></td></tr>
    <tr><td colspan="6" class="hidden_row">{grading_details}</td></tr>
    </table>
    </td>
    <td style="max-width: 50%; width: 50%;"><table id="teacher-remarks-table" class="table table-default" style="margin-bottom:0;"><tbody><tr><td style="padding:5px 5px 0px 15px;">
    <h5><em style="box-sizing: inherit;">ملاحظات معلم الصف</em></h5></td></tr><tr><td  id="teacher-remark-place" style="height: 60px; max-height:80px;">{class_teacher_remarks}</td></tr>
    <tr><td style="padding:5px 5px 0px 15px;;"><h5><span style="box-sizing: inherit; font-weight: bolder;"><em style="box-sizing: inherit;">أبقه مرتفعا</em></span></h5>
    </td></tr><tr><td><em>توقيع معلم الصف:</em><span>&nbsp;</span><br/><span>التاريخ:</span></td></tr></tbody></table></td></tr></tbody></table>
    <table width="100%" id="footer-table"><tbody><tr>
    <td><strong>توقيع رئيس القسم:</strong><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u><br /><strong>توقيع ولي الامر</strong><u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</u></td>
    <td class="text-center"><strong><label>المدير</label></strong><br /><strong>التاريخ:&nbsp;&nbsp;&nbsp;{date}</strong></td></tr></tbody></table>
    </div>';    
}

function system_fee_template(){
    return '<div id="fee_card_container" style="direction:ltr;"><h1 style="text-align: center;"><span style="text-decoration: underline;"><strong>Fee template</strong></span></h1>
    <p style="text-align: center;"><strong>School LOGO: </strong><span style="text-decoration: underline;">{school_logo}</span></p>
    <p style="text-align: center;"><strong>Student name</strong> : <span style="text-decoration: underline;">{student_name}</span></p>
    <p style="text-align: center;"><strong>Fathername : </strong><span style="text-decoration: underline;">{father_name}</span></p>
    <p style="text-align: center;"><strong>class: </strong><span style="text-decoration: underline;">{class_name}</span></p>
    <p style="text-align: center;"><strong>Section: </strong><span style="text-decoration: underline;">{section_name}</span></p>
    <p style="text-align: center;"><strong>Phone Number: </strong><span style="text-decoration: underline;">{phone_number}</span></p>
    <p style="text-align: center;"><strong>Student Roll No: </strong><span style="text-decoration: underline;">{student_rollno}</span></p>
    <p style="text-align: center;"><strong>Fee Discounted Amount: </strong><span style="text-decoration: underline;">{discounted_amount}</span></p>
    <p style="text-align: center;"><strong>Fee Type: </strong><span style="text-decoration: underline;">{fee_type}</span></p>
    <p style="text-align: center;"><strong>Fee Collected By: </strong><span style="text-decoration: underline;">{fee_collected_by}</span></p>
    <p style="text-align: center;"><strong>Fee Status: </strong><span style="text-decoration: underline;">{fee_status}</span></p>
    <p style="text-align: center;"><strong>Fee Amount: </strong><span style="text-decoration: underline;">{fee_amount}</span></p>
    <p style="text-align: center;"><strong>Fee Paid Date: </strong><span style="text-decoration: underline;">{fee_paid_date}</span></p>
    <p style="text-align: center;"><strong>Fee Discount Percentage: </strong><span style="text-decoration: underline;">{discount_percentage}</span></p>
    <p style="text-align: center;"><strong>Fee Paid Amount: </strong><span style="text-decoration: underline;">{fee_paid_amount}</span></p>
    </div>';
}

function system_fee_template_arabic(){
    return '<!DOCTYPE html><head></head><body>
    <div id="fee_card_container"><table style="direction: rtl;" width="100%"><tbody><tr><td style="width: 150px;">{school_logo}</td>
    <td style="text-align: center;"><h1><span style="text-decoration: underline;"><strong>{school_name}</strong></span></h1></td></tr>
    </tbody></table><p>&nbsp;</p><table width="100%" style="direction: rtl;"><tbody><tr><td style="width: 120px;">{student_profile}</td><td style="vertical-align: top;">
    <table width="100%" cellspacing="10px" cellpadding="10px"><tbody><tr><td style="padding: 8px;"><strong>اسم الطالب</strong></td>
    <td style="padding: 8px;">{student_name}</td><td style="padding: 8px;"><strong>اسم ولي االمر</strong></td>
    <td style="padding: 8px;">{father_name}</td></tr><tr><td style="padding: 8px;"><strong>الصف </strong></td>
    <td style="padding: 8px;">{class_name}</td><td style="padding: 8px;"><strong>القسم</strong></td>
    <td style="padding: 8px;">{section_name}</td></tr><tr><td style="padding: 8px;"><strong>رقم الطالب</strong></td>
    <td style="padding: 8px;">{student_rollno}</td><td style="padding: 8px;"><strong>رقم الهاتف.</strong></td>
    <td style="padding: 8px;">{phone_number}</td></tr><tr><td style="padding: 8px;"><strong>رقم الايصال</strong></td>
    <td style="padding: 8px; text-align: left;" colspan="3"><strong>
    <span style="color: #2b2b2b; font-family: Poppins, sans-serif; background-color: #ffffff; outline: 0px !important;">{fee_receipt_sr_no}</span>
    </strong></td></tr></tbody></table></td></tr></tbody></table>
    <p>&nbsp;&nbsp;{fee_card}&nbsp; &nbsp; &nbsp; &nbsp;
    <span style="background-color: #ffffff; color: #2b2b2b; font-family: Poppins, sans-serif; font-weight: 600;">{school_name}</span></p>
    <table style="direction: rtl;" width="100%"><tbody><tr><td><strong>جمع بواسطة:</strong></td><td>
    <span style="background-color: #ffffff; color: #626262; font-weight: bold; text-align: center;">تسلم رسم تسلسلي لا</span></td>
    <td style="text-align: left;"><strong>تاريخ دفع الرسوم</strong></td></tr><tr>
    <td><span style="text-decoration: underline;">{fee_collected_by}</span></td>
    <td><span style="text-decoration: underline;">{fee_receipt_sr_no}</span></td>
    <td style="text-align: left;"><span style="text-decoration: underline;">{fee_paid_date}</span></td></tr></tbody>
    </table></div></body></html>';
}

function save_school_system_templates($school_id){
    $ci = & get_instance();
    $form_category_data = array("name"=>"System Forms","tag"=>"is_system","school_id"=>$school_id);
    $form_category_id = $ci->admin_model->dbInsert("form_categories", $form_category_data);
    
    $result_card_temp_english = array("name"=>"Result Card English","is_custom"=>"Yes","language_id"=>1,"tag"=>"result_card","html"=> system_result_card_template(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
    $result_card_temp_arabic = array("name"=>"Result Card Arabic","is_custom"=>"Yes","language_id"=>2,"tag"=>"result_card","html"=> system_result_card_template_arabic(),"school_id"=>$school_id,"form_category_id"=>$form_category_id);
    $fee_receipt_temp_english = array("name"=>"Fee Receipt English","is_custom"=>"Yes","tag"=>"single_fee","html"=> system_fee_template(),"school_id"=>$school_id,"language_id"=>1,"form_category_id"=>$form_category_id);
    $fee_receipt_temp_arabic = array("name"=>"Fee Receipt Arabic","is_custom"=>"Yes","tag"=>"single_fee","html"=> system_fee_template_arabic(),"school_id"=>$school_id,"language_id"=>2,"form_category_id"=>$form_category_id);
    $payroll_form_english = array("name"=>"Payroll Form English","is_custom"=>"Yes","tag"=>"payroll","html"=> system_payroll_template_english(),"school_id"=>$school_id,"language_id"=>1,"form_category_id"=>$form_category_id);
    $payroll_form_arabic = array("name"=>"Payroll Form Arabic","is_custom"=>"Yes","tag"=>"payroll","html"=> system_payroll_template_arabic(),"school_id"=>$school_id,"language_id"=>2,"form_category_id"=>$form_category_id);
    $std_evaluation_form_arabic = array("name"=>"Student Evaluation Form Arabic","is_custom"=>"Yes","tag"=>"student_evaluation_card","html"=> system_student_evaluation_template_arabic(),"school_id"=>$school_id,"language_id"=>2,"form_category_id"=>$form_category_id);
    $std_evaluation_form_english = array("name"=>"Student Evaluation Form English","is_custom"=>"Yes","tag"=>"student_evaluation_card","html"=> system_student_evaluation_template_english(),"school_id"=>$school_id,"language_id"=>1,"form_category_id"=>$form_category_id);
    $ci->admin_model->dbInsert("templates", $fee_receipt_temp_english);
    $ci->admin_model->dbInsert("templates", $fee_receipt_temp_arabic);
    $ci->admin_model->dbInsert("templates",$result_card_temp_english);
    $ci->admin_model->dbInsert("templates",$result_card_temp_arabic);
    $ci->admin_model->dbInsert("templates",$payroll_form_english);
    $ci->admin_model->dbInsert("templates",$payroll_form_arabic);
    $ci->admin_model->dbInsert("templates",$std_evaluation_form_arabic);
    $ci->admin_model->dbInsert("templates",$std_evaluation_form_english);
    $school_page_template = array("school_id"=>$school_id, "page_settings"=>page_default_template());
    $ci->admin_model->dbInsert("pagetemplate",$school_page_template);
}

function make_default_view($school_id){
    $ci = & get_instance();
    $ci->db->query("create view sh_students_".$school_id." AS
        select u.*,cr.class_id,cr.batch_id,cr.subject_group_id,cr.academic_year_id,cr.discount_id from sh_users u inner join sh_student_class_relation cr on u.id = cr.student_id where cr.academic_year_id = (select id from sh_academic_years where school_id = ".$school_id." and is_active = 'Y' and deleted_at is null) and u.role_id = 3 and u.deleted_at = 0 and u.school_id = ".$school_id."  and cr.deleted_at is null");
}

function check_student_shifted($shifted_students, $id, $exam_id, $batch_id){
    $ci = & get_instance();
    $result = $ci->db->select('id')->from('sh_marksheets')->where('student_id',$id)->where('exam_detail_id not in (select id from sh_exam_details where batch_id = '. $batch_id.' and exam_id='.$exam_id.') and obtained_marks is not null and deleted_at is null and exam_id = '.$exam_id)->get()->result();
    return (count($result));
}

function get_previous_academic_year($academic_year){
    $ci = & get_instance();
    $school_id = $ci->session->userdata("userdata")["sh_id"];
    $d = $ci->admin_model->dbSelect("start_date, end_date","academic_years", " id='$academic_year' ")[0];
    $sql = "SELECT * FROM sh_academic_years WHERE start_date < '".$d->start_date."' AND school_id='$school_id' AND deleted_at IS NULL ";
    $academic_years = $ci->admin_model->dbQuery($sql);
    return $academic_years;
}

function count_months_between_two_dates($start_date, $end_date) {
    $dateStart = new DateTime($start_date);
    $dateFin = new DateTime($end_date);
    $firstDay = $dateStart->format('Y/m/d');
    $lastDay = $dateStart->format('Y/m/t');
    $totalMonths = $dateStart->diff($dateFin)->m + ($dateStart->diff($dateFin)->y * 12);
    $result = array();
    $result["data"] = array();
    $result["months"] = array();
    for ($i = 0; $i <= $totalMonths; $i++) {
        $obj = (object)array();
        if ($i != 0) {
            $dateStart->modify('first day of next month');
            $firstDay = $dateStart->format('Y/m/d');
            $lastDay = $dateStart->format('Y/m/t');
        }

        $nextDate = explode('/', $firstDay);

        $totalDays = cal_days_in_month(CAL_GREGORIAN, $nextDate[1], $nextDate[2]);
        if ($i == 0) {
            $totalDays -= $dateStart->format('d');
        } else if ($i == $totalMonths) {
            $totalDays = $dateFin->format('d');
        }

        $obj->start_date = $firstDay;
        $obj->end_date = $lastDay;
        $obj->month = date("n", strtotime($firstDay));
        $obj->year = date("Y", strtotime($firstDay));
        array_push($result["data"], $obj);
        array_push($result["months"], $obj->month.','.$obj->year);
    }
    return $result;
}

function system_payroll_template_english(){
    return '<!DOCTYPE html>
    <head></head>
    <body>
    <div style="border-style: double; padding: 15px; width: 100%;">
    <div style="border: 1px solid; width: 100%;">
    <table width="100%" style="border-bottom: 1px solid;">
    <tr style="text-align:center;">
    <td style="border-right: 1px solid; width:33%;">{school_logo}</td>
    <td style="border-right: 1px solid; width:34%;">{salary_slip_heading}</td>
    <td style="width:33%;"><h3>{salary_name}</h3></td>
    </tr>
    </table>
    <table width="100%" style="border-bottom: 1px solid;">
    <tr style="text-align:center;">
    <td style="border-right: 1px solid; width:50%;"><strong>Name : </strong>{employee_name}</td>
    <td>
    <p style="padding:0; margin:10px 0px 0px 0px;"><span>Title : </span><span>{employee_category}</span></p>
    <p><span>Department : </span><span>{employee_department}</span></p>
    </td>
    </tr>
    </table>
    <table width="100%" style="border-bottom: 1px solid;">
    <tr style="text-align: center;">
    <td style="border-right: 1px solid; width: 33%;"><strong>Description</strong></td>
    <td style="border-right: 1px solid; width: 34%;"><strong>Earnings</strong></td>
    <td style="width: 33%;"><strong>Deductions</strong></td>
    </tr>
    </table>
    <table width="100%" style="border-bottom: 1px solid;">
    <tr>
    <td style="width:33%; padding-left: 8px;" class="border-right">
    <span>Basic Salary</span>
    <br/>
    <span>Allowances</span>
    </td>
    <td style="text-align: center; width: 34%;" class="border-right">
    <span>{basic_salary}</span>
    <br/>
    <span>{allowances}</span>
    </td>
    <td style="text-align: center; width: 33%;">
    {deductions}
    </td>
    </tr>
    </table>
    <table style="width: 100%; border-bottom:1px solid; ">
    <tr>
    <td style="width: 33%; padding-left: 8px;" class="border-right"><strong>Total</strong></td>
    <td style="text-align: center; width: 34%;" class="border-right"><strong>{total_paid}</strong></td>
    <td style="text-align: center; width: 33%;"><strong>{total_deductions}</strong></td>
    </tr>
    </table>
    
    <table width="100%">
    <tr>
    <td colspan="2" style="width:50%; text-align: center; border-right: 1px solid;">
    <p style="padding:0; margin:10px 0px 0px 0px;">
    <strong>Payment Date:</strong>
    <span>{payment_date}</span>
    </p>
    <p>
    <strong>Payment Mode:</strong>
    <span>{payment_mode}</span>
    </p>
    </td>
    <td colspan="2">
    <table width="100%"  style="text-align: center;">
    <tr>
    <td><strong>Net Pay</strong></td>
    </tr>
    <tr>
    <td><strong>{net_payment}</strong></td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </div>
    </div>
    <p style="padding: 15px;">
    <b>CONFIDENTIALITY NOTE:</b> <small>{confidentiality_note}</small>
    </p>
    </body>
    </html>';
}

function system_payroll_template_arabic(){
    return '<!DOCTYPE html>
    <head></head>
    <body style="direction: rtl;">
    <div style="border-style: double; padding: 15px; width: 100%;">
    <div style="border: 1px solid; width: 100%;">
    <table width="100%" style="border-bottom: 1px solid;">
    <tr style="text-align:center;">
    <td style="border-left: 1px solid; width:33%;">{school_logo}</td>
    <td style="border-left: 1px solid; width:34%;">{salary_slip_heading}</td>
    <td style="width:33%;"><h3>{salary_name}</h3></td>
    </tr>
    </table>
    <table width="100%" style="border-bottom: 1px solid;">
    <tr style="text-align:center;">
    <td style="border-left: 1px solid; width:50%;"><strong>اسم : </strong>{employee_name}</td>
    <td>
    <p style="padding:0; margin:10px 0px 0px 0px;"><span>عنوان : </span><span>{employee_category}</span></p>
    <p><span>قسم، أقسام : </span><span>{employee_department}</span></p>
    </td>
    </tr>
    </table>
    <table width="100%" style="border-bottom: 1px solid;">
    <tr style="text-align: center;">
    <td style="border-left: 1px solid; width: 33%;"><strong>وصف</strong></td>
    <td style="border-left: 1px solid; width: 34%;"><strong>أرباح</strong></td>
    <td style="width: 33%;"><strong>الخصومات</strong></td>
    </tr>
    </table>
    <table width="100%" style="border-bottom: 1px solid;">
    <tr>
    <td style="width:33%; padding-right: 8px;" class="border-left">
    <span>راتب اساسي</span>
    <br/>
    <span>البدلات</span>
    </td>
    <td style="text-align: center; width: 34%;" class="border-left">
    <span>{basic_salary}</span>
    <br/>
    <span>{allowances}</span>
    </td>
    <td style="text-align: center; width: 33%;">
    {deductions}
    </td>
    </tr>
    </table>
    <table style="width: 100%; border-bottom:1px solid; ">
    <tr>
    <td style="width: 33%; padding-right: 8px;" class="border-left"><strong>مجموع</strong></td>
    <td style="text-align: center; width: 34%;" class="border-left"><strong>{total_paid}</strong></td>
    <td style="text-align: center; width: 33%;"><strong>{total_deductions}</strong></td>
    </tr>
    </table>
    
    <table width="100%">
    <tr>
    <td colspan="2" style="width:50%; text-align: center; border-left: 1px solid;">
    <p style="padding:0; margin:10px 0px 0px 0px;">
    <strong>يوم الدفع او الاستحقاق:</strong>
    <span>{payment_date}</span>
    </p>
    <p>
    <strong>طريقة الدفع:</strong>
    <span>{payment_mode}</span>
    </p>
    </td>
    <td colspan="2">
    <table width="100%"  style="text-align: center;">
    <tr>
    <td><strong>صافي الأجر</strong></td>
    </tr>
    <tr>
    <td><strong>{net_payment}</strong></td>
    </tr>
    </table>
    </td>
    </tr>
    </table>
    </div>
    </div>
    <p style="padding: 15px;">
    <b>ملاحظة: السرية:</b> <small>{confidentiality_note}</small>
    </p>
    </body>
    </html>';
}

function system_student_evaluation_template_arabic(){
    return '<!DOCTYPE html>
    <head></head>
    <body style="direction: rtl;">
    <div class="result_card_container arabic" style="direction: rtl;">
    <table style="width: 100%" border="0px">
    <tbody>
    <tr>
    <td>{school_logo}</td>
    <td style="text-align:center;">
    <h2><b>{title_student_evaluation_report}</b> ({evaluation_type})</h2>
    <h3><b>{school_name}</b></h3>
    </td>
    </tr>
    </tbody>
    </table>
    <br>
    <table style="width: 100%" border="0px">
    <tbody>
    <tr>
    <th>اسم</th>
    <td class="text-left" style="width: 250px;">{student_name}</td>
    <th>لفة رقم</th>
    <td class="text-left">{student_rollno}</td>
    <td rowspan="2" style="width: 100px;">{student_profile}</td>
    </tr>
    <tr>
    <th>صف دراسي</th>
    <td class="text-left">{class_name}</td>
    <th>الجزء</th>
    <td class="text-left">{section_name}</td>
    </tr>
    </tbody>
    </table>
    <h3 class="text-left"><u><b>تقييم موضوع الطالب</b></u></h3>
    <table class="subject-evaluation-table" style="width:100%;" border="1px">
    <tbody>
    <tr>
    <th class="text-center">نشاط</th>
    {student_subjects}
    </tr>
    <tr>
    {student_evaluations}
    </tr>
    <tr>
    <th class="pl-2">التقييم الشامل</th>
    <td class="text-center" colspan="{colspan}">{overall_evaluation}</td>
    </tr>
    </tbody>
    </table>
    <h3 class="text-left"><u><b>تقييم انحدار الطالب</b></u></h3>
    <table class="school-evaluation-table" style="width: 100%; text-align:center;" border="1px">
    <tbody>
    <tr>
    <th style="text-align:center;">نشاط</th>
    <th style="text-align:center;">تقييم</th>
    <th style="text-align:center;">على كل التقييم</th>
    </tr>
    <tr>
    <td class="text-left">{student_evaluation_activity}</td>
    <td>{student_evaluation}</td>
    <td>{student_overall_evaluation}</td>
    </tr>
    </tbody>
    </table>
    <br/>
    <table style="width: 100%; text-align:center;" border="0px">
    <tbody>
    <tr>
    <th style="text-align:center;">التقييم النهائي</th>
    <th style="text-align:center;">ملاحظات المعلم</th>
    </tr>
    <tr>
    <td style="font-size: 26px; font-weight: bold;">{final_evaluation}</td>
    <td></td>
    </tr>
    </tbody>
    </table>
    </div>
    </body>
    </html>';
}

function system_student_evaluation_template_english(){
    return '<!DOCTYPE html>
    <head></head>
    <body style="direction: ltr;">
    <div class="result_card_container other" style="direction: ltr;">
    <table style="width: 100%" border="0px">
    <tbody>
    <tr>
    <td>{school_logo}</td>
    <td style="text-align:center;">
    <h2><b>Student Evaluation Report</b> ({evaluation_type})</h2>
    <h3><b>{school_name}</b></h3>
    </td>
    </tr>
    </tbody>
    </table>
    <br>
    <table style="width: 100%" border="0px">
    <tbody>
    <tr>
    <th>Name</th>
    <td class="text-left" style="width: 250px;">{student_name}</td>
    <th >Roll No</th>
    <td class="text-left">{student_rollno}</td>
    <td rowspan="2" style="width: 100px;">{student_profile}</td>
    </tr>
    <tr>
    <th>Class</th>
    <td class="text-left">{class_name}</td>
    <th>Section</th>
    <td class="text-left">{section_name}</td>
    </tr>
    </tbody>
    </table>
    <h3 class="text-left"><u><b>Student Subject Evaluation</b></u></h3>
    <table class="subject-evaluation-table" style="width:100%;" border="1px">
    <tbody>
    <tr>
    <th class="text-center">Activity</th>
    {student_subjects}
    </tr>
    <tr>
    {student_evaluations}
    </tr>
    <tr>
    <th class="pl-2">Overall Evaluation</th>
    <td class="text-center" colspan="{colspan}">{overall_evaluation}</td>
    </tr>
    </tbody>
    </table>
    <h3 class="text-left"><u><b>Student Decipline Evaluation</b></u></h3>
    <table class="school-evaluation-table" style="width: 100%; text-align:center;" border="1px">
    <tbody>
    <tr>
    <th style="text-align:center;">Activity</th>
    <th style="text-align:center;">Evaluation</th>
    <th style="text-align:center;">Over All Evaluation</th>
    </tr>
    <tr>
    <td class="text-left">{student_evaluation_activity}</td>
    <td>{student_evaluation}</td>
    <td>{student_overall_evaluation}</td>
    </tr>
    </tbody>
    </table>
    <br/>
    <table style="width: 100%; text-align:center;" border="0px">
    <tbody>
    <tr>
    <th style="text-align:center;">Final Evaluation</th>
    <th style="text-align:center;">Teacher Remarks</th>
    </tr>
    <tr>
    <td style="font-size: 26px; font-weight: bold;">{final_evaluation}</td>
    <td></td>
    </tr>
    </tbody>
    </table>
    </div>
    </body>
    </html>';
}

function render_student_evaluation_card_tags($data, $html, $lang_id){
    $html->html = render_universal_tags($html, $html->html);
    
    $student_subjects = "";
    $student_evaluations = "";
    
    foreach($data["subjects"] as $sub){
        $student_subjects .= '<th class="text-center">'.$sub->name.'</th>';
    }
    
    foreach($data["students"] as $key=>$std){
        $student_evaluations = "";
        $student_evaluation = "";
        $student_evaluation_activity = "";
        foreach($std->activities as $key2=>$act){
            $student_evaluations .= '<tr>
            <td class="text-left pl-2">'.$act->category_name.'</td>';   
            foreach($data["subjects"] as $key3=>$dd) {
                if(count($std->evaluations[$key3]->report) > 0) {
                    $stars = "";
                    switch($std->evaluations[$key3]->report[$key2]){
                        case 1:
                        $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                        break;
                        case 2:
                        $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                        break;
                        case 3:
                        $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                        break;
                        case 4:
                        $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                        break;
                        case 5:
                        $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                        break;
                    }
                    $student_evaluations .= '<td>'.$stars.'</td>';
                } else if(count($std->evaluations[$key3]->report) == 0){
                    $student_evaluations .= '<td>-</td>';
                }
            }
            $student_evaluations .= '</tr>';
        }
        
        $student_evaluation_activity .= "<table border='1' width='100%' style='border: 0px;'>";
        foreach($std->activities2 as $key3=>$act2){
            $student_evaluation_activity .= "<tr><td style='padding-left: 5px;'>".$act2->category_name."</td></tr>";
        }
        $student_evaluation_activity .= "</table>";
        if(isset($std->evaluations2)){
        $student_evaluation .= "<table align='center' border='1' width='100%' style='border: 0px;'>";
        foreach($std->evaluations2[0]->report as $r){
            $stars = "";
            switch($r){
                case 1:
                $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                break;
                case 2:
                $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                break;
                case 3:
                $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                break;
                case 4:
                $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                break;
                case 5:
                $stars = "<img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
                break;
                default: 
                $stars = "&nbsp;";
            }
            $student_evaluation .= "<tr><td>".$stars."</td></tr>";
        }
        $student_evaluation .= "</table>";
    }
        
        $final_avg2_stars = "";
        $final_avg2_original = round($std->final_avg2,2);
        switch(intval($std->final_avg2)){
            case 1:
            $final_avg2_stars = "<span>".$final_avg2_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            case 2:
            $final_avg2_stars = "<span>".$final_avg2_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            case 3:
            $final_avg2_stars = "<span>".$final_avg2_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            case 4:
            $final_avg2_stars = "<span>".$final_avg2_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            case 5:
            $final_avg2_stars = "<span>".$final_avg2_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            default:
            $final_avg2_stars = $final_avg2_original;
        }

        $f_avg_number_stars = "";
        $f_avg_number_original = round($std->f_avg_number,2);
        switch(intval($std->f_avg_number)){
            case 1:
            $f_avg_number_stars = "<span>".$f_avg_number_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            case 2:
            $f_avg_number_stars = "<span>".$f_avg_number_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            case 3:
            $f_avg_number_stars = "<span>".$f_avg_number_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            case 4:
            $f_avg_number_stars = "<span>".$f_avg_number_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
            case 5:
            $f_avg_number_stars = "<span>".$f_avg_number_original."</span><br/><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' /><img class='star' src='".base_url()."assets/images/fill-star.png' width='15' height='15' />";
            break;
        }

        $tags["colspan"] = count($data["subjects"]);
        $tags["evaluation_type"] = $data["evaluation"];
        $tags["title_student_evaluation_report"] = lang("lbl_student_valuation_report");
        $tags["student_profile"] = "<img src='".base_url()."uploads/user/" . $std->student_avatar . "' alt='uploads/user/profile.png' width='100'/>";
        $tags["student_name"] = $std->name;
        $tags["class_name"] = $std->class_name;
        $tags["section_name"] = $std->batch_name;
        $tags["student_rollno"] = $std->rollno;
        $tags["student_subjects"] = $student_subjects;
        $tags["student_evaluations"] = $student_evaluations;
        $tags["student_evaluation_activity"] = $student_evaluation_activity;
        $tags["student_evaluation"] = $student_evaluation;
        //$tags["student_overall_evaluation"] = isset($std->final_avg2)?$std->final_avg2."":"0";
        $tags["student_overall_evaluation"] = $final_avg2_stars;
        $tags["overall_evaluation"] = $std->final_avg_number;
        //$tags["final_evaluation"] = isset($std->f_avg_number)?$std->f_avg_number.'':'0';
        $tags["final_evaluation"] = $f_avg_number_stars;

        $rendered_html = $html->html;
        foreach ($tags as $key => $value) {
            $rendered_html = str_replace("{" . $key . "}", $tags[$key], $rendered_html);
        }
        $std->html = $rendered_html;
    }
    
    return $data;
}

function hex2rgba($color, $opacity = false) {
 
    $default = 'rgb(0,0,0)';
    
    //Return default if no color provided
    if(empty($color))
      return $default; 
  
    //Sanitize $color if "#" is provided 
  if ($color[0] == '#' ) {
    $color = substr( $color, 1 );
}

        //Check if color has 6 or 3 characters and get values
if (strlen($color) == 6) {
    $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
} elseif ( strlen( $color ) == 3 ) {
    $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
} else {
    return $default;
}

        //Convert hexadec to rgb
$rgb =  array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
if($opacity){
    if(abs($opacity) > 1)
        $opacity = 1.0;
    $output = 'rgba('.implode(",",$rgb).','.$opacity.')';
} else {
    $output = 'rgb('.implode(",",$rgb).')';
}

        //Return rgb(a) color string
return $output;
}

function page_default_template(){
    return '<div id="azeem">
            
            <div data-gjs="navbar" class="navbar">
                <div class="navbar-container">
                    <a href="/" class="navbar-brand"><img id="i0vg3" src="uploads/logos/uvs.png"/></a>
                    <div id="i0pas" class="navbar-burger">
                        <div class="navbar-burger-line">
                        </div>
                        <div class="navbar-burger-line">
                        </div>
                        <div class="navbar-burger-line">
                        </div>
                    </div>
                    <div data-gjs="navbar-items" class="navbar-items-c">
                        <nav data-gjs="navbar-menu" class="navbar-menu">
                            <a href="#home_landing" class="navbar-menu-link">Home</a>
                            <a href="#principlemessage_school" class="navbar-menu-link">School</a>
                            <a href="#" class="navbar-menu-link">News</a>
                            <a href="#Team_school" class="navbar-menu-link">Team</a>
                            <a href="#contct" class="navbar-menu-link">Contact Us</a>
                            <a href="#why_choose_us" class="navbar-menu-link">About</a>
                        </nav>
                    </div>
                </div>
                <header class="header-banner" id="home_landing">
                    <div class="container-width">
                        <div class="logo-container">
                            <div class="logo" style="width: 160px;">UVSCHOOLS</div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="lead-title">Build your templates without coding</div>
                        <div class="sub-lead-title">All text blocks could be edited easily with double clicking on it. You can create new text blocks with the command from the left panel</div>
                        <div class="lead-btn">Admissions</div>
                    </div>
                </header>
                <!-- <img id="ibcor" src="https://localhost/uv/myschool2/uploads/default_landing/mainSlid.jpg"/> -->
            </div>

            <section class="schl-sect" id="principlemessage_school"><div class="container-width">
                    <div class="am-container">
                        <div class="am-content">
                            <div class="am-pre">Why Choose Us</div>
                            <div class="am-title">Manage your images with Asset Manager</div>
                            <div class="am-desc">You can create image blocks with the command from the left panel and edit them with double click</div>
                            <div class="am-post">Image uploading is not allowed in this demo</div>
                        </div>
                        <img class="img-principle" onmousedown="return false" src="uploads/default_landing/Choose.jpg"/>
                    </div>
            </section>

            <section class="am-sect" id="why_choose_us" style="height: 418px;">
                <div class="overlay padding-120">
                    <div class="container-width">
                        <div class="am-container">
                            <img class="img-phone" onmousedown="return false" src="uploads/default_landing/choose_students.jpeg"/>
                            <div class="am-content">
                                <div class="supheading" style="color: red;">Why Choose Us</div>
                                <div class="am-title" style="color: white;">Manage your images with Asset Manager</div>
                                <div class="am-desc"><p class="text-white" style="color: white;">Dolor sit amet, dolor gravida placerat liberolorem ipsum dolor consectetur adipiscing elit, sed do eiusmod. Dolor sit amet consectetuer adipiscing elit, sed diam nonummy nibh euismod. Praesent interdum est gravida vehicula est node maecenas loareet morbi a dosis luctus novum est praesent. Praesent interdum est gravida vehicula est node maecenas loareet morbi a dosis luctus novum est praesent.</p></div>
                                <div class="am-post" style="color: white;">More benefit nonummy nibh euismod. Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bdg-sect" id="Team_school">
                <div class="container-width">
                    <h1 class="bdg-title">Meet Our Teachers</h1>
                    <div class="badges">
                        <div class="badge">
                            <div class="badge-header"></div>
                            <img class="badge-avatar" src="assets/img/team1.jpg">
                            <div class="badge-body">
                                <div class="badge-name">Adam Smith</div>
                                <div class="badge-role">CEO</div>
                                <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>
                            </div>
                            <div class="badge-foot">
                                <span class="badge-link">f</span>
                                <span class="badge-link">t</span>
                                <span class="badge-link">ln</span>
                            </div>
                        </div>
                        <div class="badge">
                            <div class="badge-header"></div>
                            <img class="badge-avatar" src="img/team2.jpg">
                            <div class="badge-body">
                                <div class="badge-name">John Black</div>
                                <div class="badge-role">Software Engineer</div>
                                <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>
                            </div>
                            <div class="badge-foot">
                                <span class="badge-link">f</span>
                                <span class="badge-link">t</span>
                                <span class="badge-link">ln</span>
                            </div>
                        </div>
                        <div class="badge">
                            <div class="badge-header"></div>
                            <img class="badge-avatar" src="img/team3.jpg">
                            <div class="badge-body">
                                <div class="badge-name">Jessica White</div>
                                <div class="badge-role">Web Designer</div>
                                <div class="badge-desc">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore ipsum dolor sit</div>
                            </div>
                            <div class="badge-foot">
                                <span class="badge-link">f</span>
                                <span class="badge-link">t</span>
                                <span class="badge-link">ln</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="footer-under">
                <div class="container-width">
                    <div class="footer-container">
                        <div class="form-sub">
                            <div class="foot-form-cont">
                                <div class="footer-title">About Us</div>
                                <div class="foot-form-desc">Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet. Lorem ipsum dolor sit amet, consectetuer adipiscing elit, sed diam nonummy.</div>
                            </div>
                        </div>

                        <div class=".footer-item" id="contct">
                            <div class="footer-title">Contact Info</div>
                            <ul class="list-info">
                                <li>
                                    <div class="info-icon">
                                        <span class="fa fa-map-marker"></span>
                                    </div>
                                    <div class="info-text">99 S.t Jomblo Park Pekanbaru 28292. Indonesia</div> 
                                </li>
                                <li>
                                    <div class="info-icon">
                                        <span class="fa fa-phone"></span>
                                    </div>
                                    <div class="info-text">(0761) 654-123987</div>
                                </li>
                                <li>
                                    <div class="info-icon">
                                        <span class="fa fa-envelope"></span>
                                    </div>
                                    <div class="info-text">info@yoursite.com</div>
                                </li>
                                <li>
                                    <div class="info-icon">
                                        <span class="fa fa-clock-o"></span>
                                    </div>
                                    <div class="info-text">Mon - Sat 09:00 - 17:00</div>
                                </li>
                            </ul>
                        </div>
                        <div class=".footer-item">
                            <div class="footer-title">Useful Links</div>
                            <ul class="list-info">
                                <li>
                                    <a href="#home_landing" class="navbar-menu-link">HOME</a>
                                </li>
                                <li>
                                    <a href="#principlemessage_school" class="navbar-menu-link">Our School</a>
                                </li>
                                <li>
                                    <a href="#home_landing" class="navbar-menu-link">News</a>
                                </li>
                                <li>
                                    <a href="#why_choose_us" class="navbar-menu-link">ABOUT</a>
                                </li>
                            </ul>
                        </div>

                        <div class="clearfix"></div>


                    </div>
                </div>
                <div class="copyright">
                    <div class="container-width">
                        <div class="made-with">
                            Powered By United Vision Pvt. Ltd.
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </footer>

            <div class="rizwan" style="width: auto; height: 40px; background-color: #16ede5;;">
                <div class="ftex" style="color: white;">Copyright 2020 © <span style="color: white;">UV SCHOOLS Template</span>. Designed by <span style="color: white;">United Vision Pvt. Ltd.</span></div>
            </div>
            <style>
                /* blog */
                page_load
                {
                  max-width:auto;
                    margin:0 auto;
                    width:auto;  
                }
                .section-heading {
                    font-size: 40px;
                    line-height: 1em;
                    margin-bottom: 20px;
                    /*padding-top: 0; */
                    margin-top: 0;
                    padding-bottom: 5px;
                    position: relative;
                    color: #FD4D40; 
                }
                .section-heading.light {
                    color: #FD4D40; 
                }
                .section-heading.light span {
                    color: #ffffff; 
                }
                .section-heading.light:after {
                    background: #ffffff;
                }
                .section-heading.text-center {
                    text-align: center; 
                }
                .section-heading.text-center:after {
                    margin-left: auto;
                    margin-right: auto; 
                }
                @media (max-width: 767px) {
                    .section-heading {
                        font-size: 32px; 
                    } 
                }
                .content-wrap {
                    padding: 80px 0; 
                }
                .bgi-cover-center {
                    background-size: cover;
                    background-position: center; 
                }
                .rs-news-1 {
                    position: relative;
                    -webkit-box-shadow: 4px 5px 30px rgba(0, 0, 0, 0);
                    -moz-box-shadow: 4px 5px 30px rgba(0, 0, 0, 0);
                    box-shadow: 4px 5px 30px rgba(0, 0, 0, 0);
                    padding-bottom: 20px;
                    overflow: hidden;
                    -webkit-border-radius: 15px;
                    -moz-border-radius: 15px;
                    -ms-border-radius: 15px;
                    border-radius: 15px;
                    border-bottom: 2px solid #FF7300;
                    background-color: #f8f8f8;
                }
                .rs-news-1 .meta-category {
                    position: absolute;
                    top: 20px;
                    left: 0;
                    padding: 5px 20px;
                    background-color: #FD4D40;
                    color: #ffffff;
                    z-index: 2; }
                .rs-news-1 .media-box {
                    width: 100%;
                    margin-bottom: 20px;
                    position: relative;
                    z-index: 1;
                    overflow: hidden;
                    background-color: rgba(253, 77, 64, 0.6); }
                .rs-news-1 .media-box img {
                    -webkit-transition: 0.4s all linear;
                    transition: 0.4s all linear; }
                .rs-news-1 .media-box:before {
                    content: "\f0c1";
                    font-family: FontAwesome;
                    position: absolute;
                    top: 40%;
                    left: 50%;
                    -webkit-transform: translate(-50%, -50%);
                    -ms-transform: translate(-50%, -50%);
                    transform: translate(-50%, -50%);
                    width: 50px;
                    height: 50px;
                    text-align: center;
                    z-index: 9;
                    color: #ffffff;
                    font-size: 30px;
                    opacity: 0;
                    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
                    filter: alpha(opacity=0);
                    -webkit-transition: all ease .25s;
                    transition: all ease .25s; }
                .rs-news-1 .body-box {
                    position: relative;
                    padding: 10px 20px; }
                .rs-news-1 .body-box .title a {
                    font-size: 18px;
                    font-weight: 700;
                    color: #FD4D40; }
                .rs-news-1 .body-box .title a:hover {
                    color: #F1C22E; }
                .rs-news-1 .body-box .title {
                    margin-bottom: 10px;
                    font-size: 18px;
                    font-weight: 700;
                    color: #FD4D40; }
                .rs-news-1 .body-box .meta-date {
                    font-size: 13px;
                    margin-bottom: 20px;
                    color: #F1C22E; }
                .rs-news-1:hover .body-box:before {
                    -webkit-transform: scale(1, 1);
                    -moz-transform: scale(1, 1);
                    -ms-transform: scale(1, 1);
                    -o-transform: scale(1, 1);
                    transform: scale(1, 1); }
                .rs-news-1:hover {
                    -webkit-box-shadow: 4px 5px 40px rgba(0, 0, 0, 0.2);
                    -moz-box-shadow: 4px 5px 40px rgba(0, 0, 0, 0.2);
                    box-shadow: 4px 5px 40px rgba(0, 0, 0, 0.2); }
                .rs-news-1:hover .media-box img {
                    opacity: 0.4;
                    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=40)";
                    filter: alpha(opacity=40);
                    -webkit-transform: scale(1.05, 1.05);
                    -moz-transform: scale(1.05, 1.05);
                    -ms-transform: scale(1.05, 1.05);
                    -o-transform: scale(1.05, 1.05);
                    transform: scale(1.05, 1.05);
                    -webkit-transition-timing-function: ease-out;
                    transition-timing-function: ease-out;
                    -webkit-transition-duration: 250ms;
                    transition-duration: 250ms; }
                .rs-news-1:hover .media-box:before {
                    top: 50%;
                    opacity: 1;
                    -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)";
                    filter: alpha(opacity=100); }
                .rs-news-1.no-shadow {
                    -webkit-box-shadow: none;
                    -moz-box-shadow: none;
                    box-shadow: none; }
                /* end blog */
                .ftex {
                    margin-bottom: 0;
                    padding-top: 10px;
                    text-align: center;
                }
                /* Our Team css */
                .bdg-sect{
                    padding-top:100px;
                    padding-bottom:100px;
                    font-family: Helvetica, serif;
                    background-color: #fafafa;
                }
                .bdg-title{
                    text-align: center;
                    font-size: 2em;
                    margin-bottom: 55px;
                    color: #555555;
                }
                .badges{
                    padding:20px;
                    display: flex;
                    justify-content: space-around;
                    align-items: flex-start;
                    flex-wrap: wrap;
                }
                .badge{
                    width: 290px;
                    font-family: Helvetica, serif;
                    background-color: white;
                    margin-bottom:30px;
                    box-shadow: 0 2px 2px 0 rgba(0, 0, 0, 0.2);
                    border-radius: 3px;
                    font-weight: 100;
                    overflow: hidden;
                    text-align: center;
                }
                .badge-header{
                    height: 115px;
                    background-image:url("//grapesjs.com/img/bg-gr-v.png"), url("//grapesjs.com/img/work-desk.jpg");
                    background-position:left top, center center;
                    background-attachment:scroll, fixed;
                    overflow: hidden;
                }
                .blurer{
                    filter: blur(5px);
                }
                .badge-name{
                    font-size: 1.4em;
                    margin-bottom: 5px;
                }
                .badge-role{
                    color: #777;
                    font-size: 1em;
                    margin-bottom: 25px;
                }
                .badge-desc{
                    font-size: 0.85rem;
                    line-height: 20px;
                }
                .badge-avatar{
                    width:100px;
                    height:100px;
                    border-radius: 100%;
                    border: 5px solid #fff;
                    box-shadow: 0 1px 1px 0 rgba(0, 0, 0, 0.2);
                    margin-top: -75px;
                    position: relative;
                }
                .badge-body{
                    margin: 35px 10px;
                }
                .badge-foot{
                    color:#fff;
                    background-color:rgba(146, 39, 143, 0.8);
                    padding-top:13px;
                    padding-bottom:13px;
                    display: flex;
                    justify-content: center;
                }
                .badge-link{
                    height: 35px;
                    width: 35px;
                    line-height: 35px;
                    font-weight: 700;
                    background-color: #fff;
                    color: #a290a5;
                    display: block;
                    border-radius: 100%;
                    margin: 0 10px;
                }

                /* our team css end */
                .clearfix{ clear:both}
                .list-info {
                    margin: 0;
                    padding: 0;
                }
                .button,
                .sub-btn{
                    width: 100%;
                    margin: 15px 0;
                    background-color: #785580;
                    border: none;
                    color:#fff;
                    border-radius: 2px;
                    padding: 7px 10px;
                    font-size: 1em;
                    cursor: pointer;
                }
                .sub-btn:hover{
                    background-color: #91699a;
                }
                .sub-btn:active{
                    background-color: #573f5c;
                }
                .foot-list li .info-icon
                {
                    display: table-cell;
                    color: #F1C22E;
                }
                .footer-item li .info-text {
                    margin-left: 30px; 
                    padding-left: 20px;
                    display: table-cell;
                    vertical-align: top;
                }
                .footer-title {
                    font-size: 24px;
                    padding: 20px 0 30px 0;
                    margin-bottom: 0;
                    position: relative;
                    color: #F1C22E;
                    font-family: "Pacifico", cursive;
                }
                .foot-list {
                    float: left;
                    width: 200px;
                }
                .foot-list-title {
                    font-weight: 400;
                    margin-bottom: 10px;
                    padding: 0.5em 0;
                }
                .foot-list-item {
                    color: rgba(238, 238, 238, 0.8);
                    font-size: 0.8em;
                    padding: 0.5em 0;
                }
                .foot-list-item:hover {
                    color: rgba(238, 238, 238, 1);
                }
                .foot-form-cont{
                    width: 300px;
                    float: right;
                }
                .foot-form-title{
                    color: rgba(255,255,255,0.75);
                    font-weight: 400;
                    margin-bottom: 10px;
                    padding: 0.5em 0;
                    text-align: right;
                    font-size: 2em;
                }
                .foot-form-desc{
                    font-size: 0.8em;
                    color: rgba(255,255,255,0.55);
                    line-height: 20px;
                    text-align: right;
                    margin-bottom: 15px;
                }
                .footer-container{
                    display: flex;
                    flex-wrap: wrap;
                    align-items: stretch;
                    justify-content: space-around;
                }
                .footer-under{
                    /*background-color: #312833;*/
                    padding-bottom: 50px;
                    padding-top: 50px;
                    /*min-height: 500px;*/
                    color:#eee;
                    position: relative;
                    font-weight: 100;
                    font-family: Helvetica,serif;
                    background-image: url("uploads/default_landing/bg_footer.jpg");
                    background-position: left top;
                    background-repeat: no-repeat;
                    background-attachment: fixed;
                    background-size: cover;
                    /*background-image: url("uploads/default_landing/bg_footer.jpg");*/
                }
                .am-admission{
                    padding-top: 100px;
                    padding-bottom: 100px;
                    font-family: Helvetica, serif;
                    background-color: #16ede5;

                }
                .supheading{
                    font-size: 24px;
                    font-weight: 700;
                    color: #555555;
                }
                .supheading:before, .supheading:after {
                    content: " - "; }
                .s-heading
                {
                    font-size: 40px;
                    line-height: 1em;
                    margin-bottom: 20px;
                    /* padding-top: 0; */
                    margin-top: 0;
                    padding-bottom: 5px;
                    position: relative;
                    color: #FD4D40;
                }

                .am-sect .overlay {
                    background-color: rgba(146,39,143,.8);
                }
                .img-principle{
                    float: left;
                    width: 360px;
                    height: 360px;
                }
                .schl-sect{
                    padding-top: 100px;
                    padding-bottom: 100px;
                    font-family: Helvetica, serif;

                }
                .am-sect{
                    /*padding-top: 100px;
                    padding-bottom: 100px;*/
                    font-family: Helvetica, serif;
                    background-image: url("uploads/default_landing/why_choose_background.jpg");
                }
                .am-container{
                    display: flex;
                    flex-wrap: wrap;
                    align-items: center;
                    justify-content: space-around;
                }
                .img-phone{
                    float: left;
                    width: 360px;
                    height: 360px;
                }
                .am-content{
                    float:left;
                    padding:7px;
                    width: 490px;
                    color: #444;
                    font-weight: 100;
                    margin-top: 50px;
                    /*transform: rotateX(0deg) rotateY(-20deg) rotateZ(0deg) scaleX(1) scaleY(1) scaleZ(1);*/
                }
                .am-pre{
                    padding:7px;
                    color:#b1b1b1;
                    font-size:15px;
                }
                .am-title{
                    padding:7px;
                    font-size:25px;
                    font-weight: 400;
                }
                .am-desc{
                    padding:7px;
                    font-size:17px;
                    line-height:25px;
                }
                .am-post{
                    padding:7px;
                    line-height:25px;
                    font-size:13px;
                }
                .header-banner{
                    padding-top: 35px;
                    padding-bottom: 100px;
                    color: #ffffff;
                    font-family: Helvetica, serif;
                    font-weight: 100;
                    background-image:url("uploads/default_landing/mainSlid.jpg");
                    background-attachment:scroll, scroll;
                    background-position:left top, center center;
                    /*background-repeat:repeat-y, no-repeat;*/
                    background-size: contain, cover;
                }
                .container-width{
                    width: 90%;
                    max-width: 1150px;
                    margin: 0 auto;
                }
                .logo-container{
                    float: left;
                    width: 50%;
                }
                .lead-title{
                    margin: 150px 0 30px 0;
                    font-size: 40px;
                }
                .sub-lead-title{
                    max-width: 650px;
                    line-height:30px;
                    margin-bottom:30px;
                    color: #c6c6c6;
                }
                .lead-btn{
                    margin-top: 15px;
                    padding:10px;
                    width:190px;
                    min-height:30px;
                    font-size:20px;
                    text-align:center;
                    letter-spacing:3px;
                    line-height:30px;
                    background-color:#d983a6;
                    border-radius:5px;
                    transition: all 0.5s ease;
                    cursor: pointer;
                }
                .lead-btn:hover{
                    background-color:#ffffff;
                    color:#4c114e;
                }
                .lead-btn:active{
                    background-color:#4d114f;
                    color:#fff;
                }
                .logo{
                    background-color: #fff;
                    border-radius: 5px;
                    width: 130px;
                    padding: 10px;
                    min-height: 30px;
                    text-align: center;
                    line-height: 30px;
                    color: #4d114f;
                    font-size: 23px;
                }        
                #ibcor{
                    color:black;
                    width:100%;
                }
                .navbar-items-c{
                    display:inline-block;
                    float:right;
                }
                .navbar{
                    background-color:#16ede5;
                    color:#ddd;
                    min-height:50px;
                    width:100%;
                }
                .navbar-container{
                    max-width:auto;
                    margin:0 auto;
                    width:auto;
                }
                .navbar-container::after{
                    content:"";
                    clear:both;
                    display:block;
                }
                .navbar-brand{
                    vertical-align:top;
                    display:inline-block;
                    padding:5px;
                    min-height:50px;
                    min-width:50px;
                    color:inherit;
                    text-decoration:none;
                }
                .navbar-menu{
                    padding:10px 0;
                    display:block;
                    float:right;
                    margin:0 50px 0 0;
                }
                .navbar-menu-link{
                    font-family : Arial Black;
                    margin:0;
                    color:#fff;
                    text-decoration:none;
                    display:inline-block;
                    padding:10px 15px;
                }
                .navbar-burger{
                    margin:10px 0;
                    width:45px;
                    padding:5px 10px;
                    display:none;
                    float:right;
                    cursor:pointer;
                }
                .navbar-burger-line{
                    padding:1px;
                    background-color:white;
                    margin:5px 0;
                }
                #i0vg3{
                    color:black;
                    width:200px;
                    height:50px;
                }
                .row{
                    display:flex;
                    justify-content:flex-start;
                    align-items:stretch;
                    flex-wrap:nowrap;
                    padding:10px;
                }
                .cell{
                    min-height:75px;
                    flex-grow:1;
                    flex-basis:100%;
                }
                #ig3sm{
                    color:black;
                    margin:-20px 0 0 0;
                    height:550px;
                    width:100%;
                    background-color:none;
                    border:0 black black;
                }
                #i6ll4{
                    min-height:auto;
                }
                @media (max-width: 768px){
                    .navbar-burger{
                        display:block;
                    }
                    .navbar-items-c{
                        display:none;
                        width:100%;
                    }
                    .navbar-menu{
                        width:100%;
                    }
                    .navbar-menu-link{
                        display:block;
                    }
                    .row{
                        flex-wrap:wrap;
                    }
                }
            </style>
        </div>';
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
    
    // added by sheraz
    function saveActivity($data){
        $ci = & get_instance();
        $ci->admin_model->dbInsert("activities",$data);
    }