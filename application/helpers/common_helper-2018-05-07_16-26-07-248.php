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

function insert($table, $data) {
    $CI = &get_instance();
    $CI->db->insert($table, $data);
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
    $query = "SELECT * FROM sh_assign_subjects WHERE teacher_id='$id' AND deleted_at IS NULL";
    $ci = & get_instance();
    $ci->load->model("admin_model");
    $res = $ci->admin_model->dbQuery($query);
    $clss = array();
    $bats = array();
    $subjs = array();
    foreach($res as $val){
        array_push($bats, $val->batch_id);
        array_push($clss, $val->class_id);
        array_push($subjs, $val->subject_id);
    }
    $clss = array_unique($clss);
    $bats = array_unique($bats);
    $subjs = array_unique($subjs);
    
    $data = array("classes"=>$clss,"batches"=>$bats,"subjects"=>$subjs);
    return $data;
}

function get_teacher_dept_id(){
    $ci = & get_instance();
    $ci->load->model("admin_model");
    $school_id = $ci->session->userdata('userdata')['sh_id'];
    return $teacher_dept_id = $ci->admin_model->dbSelect("teacher_dept_id","school"," id='$school_id' ")[0]->teacher_dept_id;
}


function teacher_role_data(){
    $ci = & get_instance();
    $user_id = $ci->session->userdata("userdata")["user_id"];
    $login_user_role_id = $ci->session->userdata("userdata")["role_id"];
    $school_id = $ci->session->userdata("userdata")["sh_id"];
    $login_user_employee_dept_id = $ci->session->userdata("userdata")["teacher_dept_id"];
    
  
    $resp = array();
    
    
        
  
    return (object)$resp;
        
}

function login_user(){
    $ci = & get_instance();
    $id = $ci->session->userdata("userdata")["user_id"];
    $dd = get_subjs_batches_classes($id);
    $ddd = $ci->session->userdata("userdata");
    $arr = array();
    $arr["user"] = (object)$ddd;
    $arr["t_data"] = (object)$dd;
    return (object)$arr;
}

function to_mysql_date($date){
    //'12/12/2018
    
    if(empty($date)){
        return "";
    }
    
   $myDateTime = DateTime::createFromFormat('d/m/Y', $date);
   return $myDateTime->format('Y-m-d');


    
}
function to_html_date($date){
    //'2012-12-01
    if(empty($date)){
        return "";
    }
    
    $myDateTime = DateTime::createFromFormat('Y-m-d', $date);
   return $myDateTime->format('d/m/Y');
}


function check_user_permissions(){
    
    $ci = & get_instance();
    $arr = $ci->session->userdata("userdata")['persissions'];
    $array = json_decode($arr);
    //unset label from session array
    if(isset($array)){   
    foreach ($array as $key => $value){
            unset($array[$key]->label);
        }
    } 
    // fetch controller and method from url     
    $controller = $ci->router->fetch_class();
    $method = $ci->router->fetch_method();
    // set url to json obejct to compare with session array
    $per = (object) array("permission"=>$controller.'-'.$method,"val"=>"true"); 
    
    //var_dump($per);
    //die();
    // check permissions
    //$permit = 1;
    if(isset($array)){
        $permit = 1;
        foreach ($array as $key => $value) {
            $permit = 1;
           if( $per->permission != $value->permission && $per->val != $value->val){
               $permit = 0;
               break;
           }
        }
     }
     
    if(isset($permit)){
        if($permit == 0){
            redirect(site_url("dashboard"));
        }
    }        
}


function render_universal_tags($form,$html){
    $ci = & get_instance();
    $school_id = $ci->session->userdata("userdata")["sh_id"];
    $logo = 'uploads/logos/'.$ci->session->userdata("userdata")["sh_logo"];
    $tags = array();
    $tags["date"] = date("d/m/Y");
    $tags["time"] = date("h:i:s");
    $tags["school_name"] = $ci->session->userdata("userdata")["sh_name"]; //froms ession
    $tags["school_email"] = $ci->session->userdata("userdata")["email"]; //froms ession
    $tags["school_phone"] = $ci->session->userdata("userdata")["contact"]; //froms ession
    $tags["school_address"] = $ci->session->userdata("userdata")["sh_phone"]; //froms ession
    $tags["school_website"] = $ci->session->userdata("userdata")["sh_url"]; //froms ession
    $tags["school_country"] = $ci->admin_model->dbSelect("country","school"," id='$school_id' ")[0]->country; //froms ession
    $tags["school_city"] = $ci->admin_model->dbSelect("city","school"," id='$school_id' ")[0]->city; //froms ession
    $tags["school_logo"] = "<img src='$logo' width='100'/>";
    $tags["logged_in_user"] =  $ci->session->userdata("userdata")["name"];
    $tags["form_title"] = $form->name ;
    $tags["form_category"] = $form->form_category ;
    
    
    //school_name
    //school_email
    //school_phone
    //school_address
    //school_website
    //school_country
    //school_city
    //date
    //time
    //school_logo
    //logged_in_user
    //form_title
    //form_category
    
    
    
    
    //find all keys in $tags array 
    //loop through the keys str_replace("{key}", $tags[key] , $rendered_html)
    $rendered_html = $html;
    
    foreach ($tags as $key=>$value) {
       $rendered_html = str_replace("{".$key."}", $tags[$key] , $rendered_html);
        
    }    
    
    return $rendered_html;
    
    
    
}

function render_fee_tags($data,$html){
    $ci = & get_instance();
    $html->html = render_universal_tags($html, $html->html);
    $tags = array();
    $tags["student_name"] = $data["data"]->student_name;
    $tags["father_name"] = $data["data"]->guardian_name;
    $tags["class_name"] = $data["data"]->class_name;
    $tags["section_name"] = $data["data"]->batch_name;
    $tags["phone_number"] = $data["data"]->mobile_phone;
    $tags["student_rollno"] = $data["data"]->rollno;
    $tags["discounted_amount"] = $data["data"]->discount_amount;
    $tags["discount_type"] = $data["data"]->discount_type;
    $tags["fee_type"] = $data["data"]->ftype;
    $tags["fee_collected_by"] = $data["data"]->collector_name;
    $tags["fee_status"] =  $data["data"]->status?'Paid':'Unpaid';
    $tags["fee_amount"] = $data["data"]->amount;
    $tags["fee_paid_date"] = $data["data"]->created_at;
    $tags["discount_percentage"] = $data["data"]->discount_percentage;
    $tags["fee_paid_amount"] = $data["data"]->paid_amount;
    
    
    //school_name
    //school_email
    //school_phone
    //school_address
    //school_website
    //school_country
    //school_city
    //date
    //time
    //school_logo
    //logged_in_user
    //form_title
    //form_category
    
    
    
    
    //find all keys in $tags array 
    //loop through the keys str_replace("{key}", $tags[key] , $rendered_html)
    $rendered_html = $html->html;
    
    foreach ($tags as $key=>$value) {
       $rendered_html = str_replace("{".$key."}", $tags[$key] , $rendered_html);
        
    }    
    
    return $rendered_html;
    
    
    
}