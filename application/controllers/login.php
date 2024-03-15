<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if ($this->session->userdata("userdata")) {
            redirect(site_url("dashboard"));
        }
    }

    public function index($url = FALSE) {
        if($url!='')
        {
            $school = $this->common_model->get_where("sh_school", "url", "'" . $url . "'")->result();
            if (count($school) > 0) {
                $data['logo'] = $school[0]->logo;
                $data['id'] = $school[0]->id;
                $data['name'] = $school[0]->name;
                $data['sh_url'] = $school[0]->url;
                $this->load->view("login", $data);
            } else { 
                redirect(site_url());
            }
        }
        else
        {
            redirect(site_url());
        }

    }
    
    
    //Script start for handling old local server data
    public function addspec_script($limit,$offset)
    {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $results = $this->db->query("SELECT * FROM sh_study_material WHERE school_id='$school_id' AND storage_type=1 AND delete_status=0 ORDER BY id ASC LIMIT $limit OFFSET $offset")->result();
        
        
        foreach($results as $outerkey => $result)
        {
            $live_filename=array();
            $live_fileurl=array();
            $live_implode_filenames='';
            $live_implode_fileurls='';
            
            
            $live_c_name='';
            $live_b_name='';
            
            $c_name = $this->db->query("SELECT name FROM sh_classes where id='$result->class_id'")->row();
            
            $b_name = $this->db->query("SELECT name FROM sh_batches where id='$result->batch_id'")->row();

            
            $allfiles = explode(',',$result->files);
            if($allfiles[0]=='' || $allfiles[0]==null)
            {
            }
            else
            {
                
                foreach($allfiles as $innerkey => $singlefile)
                {
                    $file_extension=explode('.',$singlefile);
                    $ext=end($file_extension);
                    
                    $oldpath='uploads/study_material/'.$singlefile;
                    $replacepath='uploads/study_material/'.$singlefile;
                    
                    if(sizeof($b_name)==0)
                    {
                        $live_b_name='randombatch';
                    }
                    else
                    {
                        $live_b_name=$b_name->name;
                    }
                    
                    if(sizeof($c_name)==0)
                    {
                        $live_c_name='randomclass';
                    }
                    else
                    {
                        $live_c_name=$c_name->name;
                    }
                    
                    
                    $newname=$live_c_name.$live_b_name.'_'.time().'.'.$ext;
                    usleep(1000000);
                    $newpath='uploads/study_material/'.$newname;
                    
                    if(file_exists($oldpath))
                    {
                        rename($replacepath,$newpath);
                        
                        $live_filename[]=$newname;
                        $live_fileurl[]=base_url().$newpath;
                    }
                }
                
                $live_implode_filenames=implode(',',$live_filename);
                $live_implode_fileurls=implode(',',$live_fileurl);
                
                $this->db->query("UPDATE sh_study_material SET files='$live_implode_filenames', file_names='$live_implode_filenames', filesurl='$live_implode_fileurls' WHERE id = '$result->id'");
            }
        }
        return true;
    }
    //Script end for handling old local server data

    public function signup() {
        $data["countries"] = $this->common_model->countries();
        $data["timezones"] = $this->common_model->time_zone_list();
        $this->load->view("signup", $data);
    }

    public function test() {
        $this->email_modal->sendEmails();
    }

    public function auth() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $data = $this->common_model->admin_login2($request->email, $request->password, $request->sh_id);
        if ($data) {
            if ($data->email_verified != 'Y') {
                echo json_encode(array("status" => "error", "message" => lang("lbl_verify_email")));
            } else {
                if ($data->deleted_at == 1) {
                    echo json_encode(array("status" => "error", "message" => lang("lbl_user_deleted")));
                } else {
                    $valid = $this->licenceCalcultor($data->start_date, $data->end_date);
                    if ($valid) {
                        if($data->status == '0'){
                        if($data->permissions != ""){
                            $temp = json_decode($data->permissions);
                            foreach ($temp as $key => $value) {
                                $temp[$key]->label = ""; 
                            }
                            $data->permissions = json_encode($temp);
                        }


                        $userdata = array(
                            "user_id" => $data->user_id,
                            "name" => $data->name,
                            "email" => $data->email,
                            "contact" => $data->contact,
                            "language" => $data->language,
                            "avatar" => $data->avatar,
                            "email_verified" => $data->email_verified,
                            "status" => $data->status,
                            "role_id" => $data->role_id,
                            "role_name" => $data->role_name,
                            "deleted_at" => $data->deleted_at,
                            "created_at" => $data->created_at,
                            "updated_at" => $data->updated_at,
                            "token" => $data->token,
                            "sh_id" => $data->school_id,
                            "sh_url" => $data->sh_url,
                            "sh_name" => $data->sh_name,
                            "sh_logo" => $data->sh_logo,
                            "sh_address" => $data->sh_address,
                            "sh_phone" => $data->sh_phone,
                            //google drive enable
                            "sh_enable_gd" => $data->sh_enable_gd,
                            "licence_type" => $data->licence_type,
                            "start_date" => encrypt($data->start_date),
                            "end_date" => encrypt($data->end_date),
                            "licence_type" => $data->licence_type,
                            "teacher_dept_id" => $data->teacher_dept_id,
                            "accounts_dept_id" => $data->accounts_dept_id,
                            "currency_symbol" => $data->currency_symbol,
                            "remaining_days" => $this->countRemainingDays($data->end_date),
                            "theme_color" => $data->theme_color,
                            "persissions" => $data->permissions,
                            "time_zone" => $data->time_zone,
                            "academic_year" => $data->academic_year,
                            "academic_year_name" => $data->academic_year_name,
                            "side_bar" => true,
                            "department_id" => $data->department_id,
                            "public_key" => $request->password,
                            "last_login" => time()
                            
                        );
                        
                        $this->session->set_userdata(array("userdata" => $userdata));
                        
                        $schl_id = $this->session->userdata("userdata")["sh_id"];
                        
                        $user_timezone=$this->db->select('time_zone')->from('sh_school')->where('id',$schl_id)->get()->row();
                        if($user_timezone->time_zone!='')
                        {
                            date_default_timezone_set($user_timezone->time_zone);
                        }
                        
                        // saveActivity(array("user_id"=>$data->user_id,"tag"=>"login","datetime"=>date("Y-m-d h:i:s")));
                        saveActivity(array("user_id"=>$data->user_id,"tag"=>"login","login_datetime"=>date("Y-m-d h:i:s")));
                        
                        // $scriptresultdb=$this->db->query("SELECT script_enable FROM sh_school where id='$schl_id'")->row();
                        
                        // $script_status=$scriptresultdb->script_enable;
                            
                        // if($script_status==0)
                        // {
                            // $results = $this->db->query("SELECT * FROM sh_study_material WHERE school_id='$schl_id' AND storage_type=1 AND delete_status=0")->result();
                            // $res_sizeof=sizeof($results);
                            // $rescount=floor($res_sizeof/2);
                            // $rescountzx=0;
                            
                            // while($rescountzx <= $res_sizeof)
                            // {
                            //     $this->addspec_script($rescount,$rescountzx);
                            //     $rescountzx=$rescountzx+$rescount;
                            //     $rescount=$rescount+$rescount;
                            // }
                            
                            // // $pos_result=$this->addspec_script();
                            // if($pos_result)
                            // {
                            //     $this->db->where('id', $schl_id);
                            //     $this->db->update('sh_school', array('script_enable' => 1));
                            // }
                        // }
                        
                        $language = strtolower($data->language);
                        if($language != "english" && $language != "arabic"){
                            $language = "english";
                        }
                        else if($language== "english")
                        {
                            $language = "english";
                        }
                        else if($language== "arabic")
                        {
                            $language = "arabic";
                        }
                        else
                        {
                            $language = "english";
                        }

                        $this->session->set_userdata('site_lang', $language);

                        if($data->role_id == 3){
                            $this->db->where('session_id', $data->current_session_id)->delete('sh_ci_sessions');
                            $this->db->set('current_session_id', $this->session->userdata("session_id"))->where('id', $data->user_id)->update('sh_users');
                        }



                        echo json_encode(array("status" => "success", "message" => lang("lbl_valid_license")));
                        } else if($data->status == '1'){
                            echo json_encode(array("status" => "error", "message" => lang("your_account_has_been_disabled")));
                        }
                    } else {
                        echo json_encode(array("status" => "error", "message" => lang("lbl_exp_license")));
                    }
                }
            }
        } else {
            echo json_encode(array("status" => "error", "message" => lang("lbl_invalid_user_or_password")));
        }
    }

    public function licenceCalcultor($startDate, $endDate) {
        $currentDate = date('Y-m-d');
        $contractDateBegin = date('Y-m-d', strtotime($startDate));
        $contractDateEnd = date('Y-m-d', strtotime($endDate));

        if (($currentDate >= $contractDateBegin) && ($currentDate <= $contractDateEnd)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function register_old() {


        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $school = array(
            "name" => $request->schoolname,
            "url" => $request->schoolurl,
            "address" => '',
            "country" => $request->country,
            "time_zone" => $request->timezone
        );

        //check school url and email exists
        $schoolUrl = "'" . $request->schoolurl . "'";
        $userEmail = "'" . $request->email . "'";
        $phone = "'" . $request->phone . "'";
        $data = $this->common_model->get_where("sh_school", "url", $schoolUrl)->result();
        $data2 = $this->common_model->get_where("sh_users", "email", $userEmail)->result();
        $data3 = $this->common_model->get_where("sh_users", "mobile_phone", $phone)->result();

        if (count($data) > 0) {
            //if school url already exists
            echo json_encode(array("status" => "error", "message" => lang('lbl_school_url_exist')));
        } else if (count($data2) > 0) {
            //if school email already exists
            echo json_encode(array("status" => "error", "message" => lang('lbl_email_exist')));
        } else if (count($data3) > 0) {
            //if school email already exists
            echo json_encode(array("status" => "error", "message" => "Mobile number already exist."));
        } else {
            $token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));
            $school_id = $this->common_model->insert("school", $school);
            if($this->session->userdata('site_lang')=='arabic'){
             $lang = 'arabic';
         }else{
             $lang = 'english';
         }
         $user = array(
             "name" => $request->yourname,
             "school_id" => $school_id,
             "email" => $request->email,
             "password" => md5($request->password),
             "contact" => '',
             "mobile_phone" => $request->phone,
             "avatar" => '',
             "email_verified" => 'N',
             "token" => $token,
             "avatar" => 'profile.png',
             "language" => $lang
         );
         $this->admin_model->dbInsert("users", $user);

        // Insert Data to License Table In database
         $startDate = date("Y-m-d");
         $endDate = date('Y-m-d', strtotime("+14 days"));

         $license = array(
            "school_id" => $school_id,
            "licence_key" => $token,
            "start_date" => $startDate,
            "end_date" => $endDate,
            "amount_paid" => 0,
            "payment_status" => 'pending',
            "licence_type" => 'trial'
        );
         $licenseID = $this->admin_model->dbInsert("license", $license);

            // Send An Activation email to New Registred Admin
         $link = base_url() . $request->schoolurl . '/login/activation/' . $token;
         $subject = 'Account Activation';
         $data = array(
            "dear_sir" => lang('tmp_dear_sir'),
            "msg" => lang('tmp_info'),
            "thanks" => lang('tmp_thanks'),
            "poweredBy" => lang('tmp_power'),
            "unsub" => lang('tmp_unsub'),
            "link" => $link,
            "school_name" => $request->schoolname
        );
         $message = $this->load->view('email_templates/account_activation.php', $data, TRUE);
         if ($licenseID) {
            $this->email_modal->emailSend($request->email, $message, $subject, "school-signup");
                //------- Start::School configuration ----//
            save_school_system_templates($school_id);
            make_default_view($school_id);
                /*$formCategoryData = array("name"=>"System Forms","tag"=>"is_system","school_id"=>$school_id);
                $formCategoryId = $this->admin_model->dbInsert("form_categories", $formCategoryData);
                $template1 = array(
                    "name"=>"Fee Receipt English",
                    "is_custom"=>"Yes",
                    "tag"=>"single_fee",
                    "html"=> system_fee_template(),                    
                    "school_id"=>$school_id,
                    "language_id"=>1,
                    "form_category_id"=>$formCategoryId
                );
                $template2 = array(
                    "name"=>"Fee Receipt Arabic",
                    "is_custom"=>"Yes",
                    "tag"=>"single_fee",
                    "html"=> system_fee_template_arabic(),                    
                    "school_id"=>$school_id,
                    "language_id"=>2,
                    "form_category_id"=>$formCategoryId
                );
                $this->admin_model->dbInsert("templates", $template1);
                $this->admin_model->dbInsert("templates", $template2);*/
                //------- End::School configuration ----//
                
                echo json_encode(array("status" => "success", "message" => lang('lbl_school_created_successfully')));
            }
        }
    }
    // salman add new registration page for email validation first

public function register() {


        // $postdata = file_get_contents("php://input");
        // $request = json_decode($postdata);
        // print_r($request);
        // die();
        $school = array(
            "name" => $_POST['yourname'],
            "url" => $_POST['schoolurl'],
            "address" => '',
            "country" => $_POST['country'],
            "time_zone" => $_POST['timezone']
        );

        //check school url and email exists
        $schoolUrl = "'" . $_POST['schoolurl'] . "'";
        $userEmail = "'" . $_POST['email'] . "'";
        $phone = $_POST['phone'];
        
        $fcontact_number="";
        $array_ofPhone=explode(' ',$phone);
        
        if(sizeof($array_ofPhone) > 0)
        {
            foreach($array_ofPhone as $ar_phone)
            {
                $fcontact_number.=$ar_phone;
            }
        }
        
        
        $data = $this->common_model->get_where("sh_school", "url", $schoolUrl)->result();
        $data2 = $this->common_model->get_where("sh_users", "email", $userEmail)->result();
        $data3 = $this->common_model->get_where("sh_users", "mobile_phone", "'".$phone."'")->result();

        if (count($data) > 0) {
            //if school url already exists
            echo json_encode(array("status" => "error", "message" => lang('lbl_school_url_exist')));
        } else if (count($data2) > 0) {
            //if school email already exists
            echo json_encode(array("status" => "error", "message" => lang('lbl_email_exist')));
        } else if (count($data3) > 0) {
            //if school email already exists
            echo json_encode(array("status" => "error", "message" => "Mobile number already exist."));
        } else {
            $token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));
            $school_id = $this->common_model->insert("school", $school);
            if($this->session->userdata('site_lang')=='arabic'){
             $lang = 'arabic';
         }else{
             $lang = 'english';
         }
         $user = array(
             "name" => $_POST['yourname'],
             "school_id" => $school_id,
             "email" => $_POST['email'],
             "password" => md5($_POST['password']),
             "contact" => '',
            //  "mobile_phone" => $_POST['phone'],
             "mobile_phone" => $fcontact_number,
             "avatar" => '',
             "email_verified" => 'Y',
             "token" => $token,
             "avatar" => 'profile.png',
             "language" => $lang
         );
         $this->admin_model->dbInsert("users", $user);

        // Insert Data to License Table In database
         $startDate = date("Y-m-d");
         $endDate = date('Y-m-d', strtotime("+14 days"));

         $license = array(
            "school_id" => $school_id,
            "licence_key" => $token,
            "start_date" => $startDate,
            "end_date" => $endDate,
            "amount_paid" => 0,
            "payment_status" => 'pending',
            "licence_type" => 'trial'
        );
         $licenseID = $this->admin_model->dbInsert("license", $license);

            // Send An Activation email to New Registred Admin
         $link = base_url() . $_POST['schoolurl'] . '/login/activation/' . $token;
         $subject = 'Account Activation';
         $data = array(
            "dear_sir" => lang('tmp_dear_sir'),
            "msg" => lang('tmp_info'),
            "thanks" => lang('tmp_thanks'),
            "poweredBy" => lang('tmp_power'),
            "unsub" => lang('tmp_unsub'),
            "link" => $link,
            "school_name" => $_POST['schoolname']
        );
         $message = $this->load->view('email_templates/account_activation.php', $data, TRUE);
         if ($licenseID) {
            $this->email_modal->emailSend($_POST['email'], $message, $subject, "school-signup");
            $send= $this->email_modal->testingEmail();
                //------- Start::School configuration ----//
            save_school_system_templates($school_id);
            make_default_view($school_id);
               
                
                echo json_encode(array("status" => "success", "message" => lang('lbl_school_created_successfully')));
            }
        }
    }
    //Verify Email
    public function VerifyEmail(){
        
        $email = $_POST['email'];
        
        $user_email = $this->db->select('email')->from('sh_users')->where('email', $email)->get()->row();
        $school_email = $this->db->select('email')->from('sh_school')->where('email', $email)->get()->row();
        
        if(($user_email && $email == $user_email->email) || ($school_email && $email == $school_email->email)){
            $data = array("status" => 'error','code' => 'Email Already Exist');
            echo json_encode($data);
            die();
        }else{
        if($email){  
            $code = rand(100000,999999);
            $subject = 'Email Verification Code';
            $data = array(
                "dear_sir" => "Dear Sir/Madam",
                "msg" => "Please Verify your email by entering this code! This code expires after 5 minutes",
                "thanks" => "- Thanks (UVSchools Team)",
                "poweredBy" => "Powered by united-vision.net",
                "code" => $code
            );
         
            $message = $this->load->view('email_templates/verify_email', $data, TRUE);
          
            $this->email_modal->sendEmailsForTesting($email, $message, $subject, "Verify Email");
            
            $data = array("status" => 'success','code' => $code);
          
            echo json_encode($data);
            die();
        }else{
             $code = '';
            $data = array("status" => 'error','code' => $code);
            echo json_encode($data);
            die();
        }

    }
    }
    // Activate Newly Created Account Through An Activation Email
    // public function activation($school, $activationID) {
    public function activation($school, $activate) {
        $getInfo = $this->admin_model->dbSelect("id", "users", " token='$activate' ")[0];
        //$getInfo = $this->common_model->getUserInfo($activationID);
        $data = array("email_verified" => "Y");
        $where = array("id" => $getInfo->id);
        $this->common_model->update_where("users", $where, $data);
        $this->session->set_flashdata('success_activation', '<div class="alert alert-success">Your account has been successfully activated. You can login now.</div>');
        redirect(site_url($school . '/login'));
    }

    // Count remaining days of licence 
    public function countRemainingDays($end) {
        $date1 = date("Y-m-d");
        $date2 = $end;

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        return array("years" => $years, "months" => $months, "days" => $days);
    }

    ///////// BY UMAR 19-12-2017 ////////////////////
    public function recovery() {
        $email = $this->input->post('email');
        $response['success'] = false;
        $data2 = $this->db->select('id')->from('sh_users')->where('email',$email)->where('deleted_at','0')->get()->row();
        if ($data2) {
            $school = $this->db->select('url')->from('sh_school')->join('sh_users','sh_users.school_id=sh_school.id')->where('sh_users.email',$email)->get()->row()->url;
            $response['success'] = true;
            $recovery_key = md5(uniqid('myschool_unitedvision' . date("H:i:s")));

            $this->common_model->insertRecovery($email, $recovery_key);
            $link = base_url() . $school . '/login/reset/' . $recovery_key;

            $subject = lang('recovery_subject');
            $data = array(
                "dear_sir" => lang('tmp_dear_sir'),
                "msg" => lang('recovery_info'),
                "thanks" => lang('tmp_thanks'),
                "poweredBy" => lang('tmp_power'),
                "unsub" => lang('tmp_unsub'),
                "link" => $link
            );

            $message = $this->load->view('email_templates/password_recovery.php', $data, TRUE);

            $this->email_modal->emailSend($email, $message, $subject, "recovery");
        }

        echo json_encode($response);
    }

    public function reset($school, $key) {

        $formattedkey = "'" . $key . "'";
        $data["key"] = $key;
        $data["school"] = $school;
        $data2 = $this->common_model->get_where("sh_users", "recovery_key", $formattedkey)->result();

        if (count($data2) == 0 || empty($key)) {
            redirect($school . '/login');
        } else {
            $this->load->view('new-password', $data);
        }
    }

    public function resetPassword() {
        $password = $this->input->post('password');
        $confirmpassword = $this->input->post('confirmpassword');
        $key = $this->input->post('key');

        $response["success"] = true;
        if (strlen($password) < 8) {
            $response["success"] = false;
            $response["error"] = lang('length_error');
        } elseif ($password != $confirmpassword) {
            $response["error"] = lang('match_error');
            $response["success"] = false;
        } else {

            $this->common_model->resetPassword($password, $key);
        }
        echo json_encode($response);
    }

    public function resendEmail(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $this->common_model->update_where("emails", array("r_email"=>$request->email), array("state"=>"pending"));
        echo json_encode(array("status" => "success", "message" => "Email resend successfully"));
    }
}
