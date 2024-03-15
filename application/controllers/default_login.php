<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Methods: GET, OPTIONS, POST");
    
class Default_login extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if ($this->session->userdata("userdata")) {
            redirect(site_url("dashboard"));
        }
    }

    function index() {
        $this->load->view("default_login");
    }
    
    
    //Script start for handling old local server data
    public function addspec_script()
    {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $results = $this->db->query("SELECT * FROM sh_study_material WHERE school_id='$school_id' AND storage_type=1 AND delete_status=0")->result();
        
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
                // if($result)
                // {
                //     echo 'Script run succesfully!';
                // }
                // else
                // {
                //     echo 'Something went wrong while running script, please try again!';
                // }
            }
        }
        return true;
    }
    //Script end for handling old local server data

    public function auth() {

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $data = $this->common_model->admin_login2_default($request->email, $request->password);
        // echo '<pre>';  print_r($data); exit;
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
                            //enable drive
                            "sh_enable_gd" => $data->sh_enable_gd,
                            "licence_type" => $data->licence_type,
                            "start_date" => encrypt($data->start_date),
                            "end_date" => encrypt($data->end_date),
                            "licence_type" => $data->licence_type,
                            "teacher_dept_id" => $data->teacher_dept_id,
                            "currency_symbol" => $data->currency_symbol,
                            "remaining_days" => $this->countRemainingDays($data->end_date),
                            "theme_color" => $data->theme_color,
                            "persissions" => $data->permissions,
                            "time_zone" => $data->time_zone,
                            "academic_year" => $data->academic_year,
                            "academic_year_name" => $data->academic_year_name,
                            "side_bar" => true,
                            "department_id" => $data->department_id
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
                            // $pos_result=$this->addspec_script();
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

                        //print_r($this->session->userdata("userdata"));die();
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

    public function countRemainingDays($end) {
        $date1 = date("Y-m-d");
        $date2 = $end;

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365 * 60 * 60 * 24));
        $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
        $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $months * 30 * 60 * 60 * 24) / (60 * 60 * 24));

        return array("years" => $years, "months" => $months, "days" => $days);
    }

}
