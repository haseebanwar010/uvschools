<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
    
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
include_once FCPATH."quickstart.php";
   
class study_material extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }
    
    //Script for handling old local server data
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
                
                $result=$this->db->query("UPDATE sh_study_material SET files='$live_implode_filenames', file_names='$live_implode_filenames', filesurl='$live_implode_fileurls' WHERE id = '$result->id'");
                if($result)
                {
                    echo 'Script run succesfully!';
                }
                else
                {
                    echo 'Something went wrong while running script, please try again!';
                }
            }
        }
    }

    public function convert_video($data) {
        $vdonewPath = $data[0];
        $vdodata = $data[1];
        $vdonewName = $vdodata['orig_name'];
        $vdonewPath = $vdonewPath . '/' . $vdonewName;
        $directory_path = $vdodata['file_path']; //Video directory location
        $directory_path_full = $vdonewPath;  //Video directory location with file name
        // exec("ffmpeg -i ".$vdonewPath." -vcodec libx265 -crf 24 -preset ultrafast ".$directory_path . $vdodata['raw_name']);
        exec("ffmpeg -y -i ".$vdonewPath." -vf scale=480:-1 -c:v libx265 -crf 28 -preset ultrafast -c:a copy ".$directory_path . $vdodata['raw_name']);
        exec("cmd.exe");
//        if ($data['file_ext'] != '.MP4' && $data['file_ext'] != '.mp4') {
//            unlink($directory_path_full);                      //Removing orignal video
//        }
        return "done";
    }
    
    function upload() {
        //google drive
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $sh_enable_gd = $this->session->userdata("userdata")["sh_enable_gd"];
        $data['enable_gd']=$sh_enable_gd;
        $data['credentials_fileid']='credentials_'.$school_id.'.json';
        
        $role_id = $this->session->userdata("userdata")["role_id"];
        if($role_id == 1 || $role_id == 4){
            $this->load->view('study_material/upload',$data);
        }else  if($role_id == 2){
            redirect(site_url('study_material/parent_download'));
        }else if($role_id == 3){
            redirect(site_url('study_material/student_download'));
        }
    }

    public function upload_new(){
        $this->load->view('study_material/student_class_activities');
    }

    function download() {
        $this->load->view('study_material/download');
    }
    function parent_download(){
        $this->load->view('study_material/parent_download');
    }

    function student_download(){
        $this->load->view('study_material/student_download');
    }

    function getSubjectsForStudent(){
        $student_id = $this->session->userdata("userdata")["user_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $student = $this->db->query("SELECT s.class_id, s.batch_id, s.subject_group_id, sh_batches.name as batch_name FROM sh_students_$school_id s left JOIN sh_batches ON sh_batches.id = s.batch_id WHERE  s.id='$student_id' ")->result();
        // $student = $this->db->select('s.class_id, s.batch_id, s.subject_group_id')->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->where('s.id', $user_id)->get()->row();

        // //zafar
        // $b_n = $this->db->select('name')->from('sh_batches')->where('school_id' , $school_id)->where('class_id' , $student->class_id)->where('id' , $student->batch_id)->where('deleted_at is NULL' , NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row();
        // $b_name = $b_n->name;

        if(count($student) > 0){
            if($student[0]->subject_group_id != "" && $student[0]->subject_group_id != null && $student[0]->subject_group_id != 0){
                $subject_ids = $this->db->select('subjects')->from('sh_subject_groups')->where('id', $student[0]->subject_group_id)->get()->row()->subjects;
                $subject_ids = explode(",", $subject_ids);
                $data["subjects"] = $this->study_model->getSubjects($student[0]->class_id, $student[0]->batch_id, $subject_ids);
                foreach ($subject_ids as $key => $value) {
                    $subject_ids[$key] = $this->db->select('code')->from('sh_subjects')->where('id', $value)->where('deleted_at is NULL',NULL)->get()->row()->code;
                }
                
                $materials = $this->study_model->filter_studentmaterial($school_id, $student[0]->class_id, $student[0]->batch_id, '', '', $subject_ids,'');
            }else{
                $data["subjects"] = $this->study_model->getSubjects($student[0]->class_id, $student[0]->batch_id);
                $materials = $this->study_model->filter_studentmaterial($school_id, $student[0]->class_id, $student[0]->batch_id, '', '','','');
            }
            //Azeem remove future assignments, not show before date
            foreach ($materials as $key => $value) {
                
                if($value["uploaded_at"] > date("Y-m-d") && $value["content_type"] == "Assignment"){
                    unset($materials[$key]);
                }
                //zafar
                $materials[$key]['batch_name'] = $student[0]->batch_name;
            }
            $materials = array_values($materials);
            
            $data['materials'] = $materials;


        }else{
            $data["subjects"] = array();
            $data["materials"] = array();
        }
        for ($i = 0; $i < count($data['materials']); $i++) {
            $data['materials'][$i]['files'] = explode(",", $data['materials'][$i]['files']);
        }
       
        echo json_encode($data);
    }

    function getSubjects() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // echo '<pre>';
        // print_r($request);
        // die;
        // echo json_encode('die');
        // die;
        $class_id = $request->class;
        $batch_id = $request->section;

        $subject_ids = false;
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $subject_ids =  login_user()->t_data->subjects;
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 

        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){

        } else if(login_user()->user->role_id == PARENT_ROLE_ID){

        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){

        }
        // echo '<pre>';
        // print_r($subject_ids);
        // die;
        // echo json_encode('die');
        // die; 
        if(!$subject_ids){
            $response["subjects"] = $this->study_model->getSubjects($class_id, $batch_id);
        }else{
            $response["subjects"] = $this->study_model->getSubjects($class_id, $batch_id, $subject_ids);
        }
        
        echo json_encode($response);
    }

    function getMaterials() {
        // $school_id = $this->session->userdata("userdata")["sh_id"];
        // echo $school_id;
        // die;
        // echo json_encode('die');
        // die;
        $filter_batchname='';
        $filter_subjectname='';
        //-------------------------
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            // $where_part =  " AND sh_batches.id IN (". implode(',', login_user()->t_data->batches) .") order by sh_study_material.uploaded_at desc";
            $where_part =  " order by sh_study_material.uploaded_at desc";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part =  " order by sh_study_material.uploaded_at desc";
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part =  " order by sh_study_material.uploaded_at desc";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part =  " order by sh_study_material.uploaded_at desc";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part =  " order by sh_study_material.uploaded_at desc";
        }
        //-------------------------
        $user =$this->session->userdata("userdata")["user_id"];
       
        $role_id = $this->session->userdata("userdata")["role_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $date =date('Y-m-d');

        if($role_id == 4)
        {
            $sss=login_user()->t_data->subjects;
            $bbb=login_user()->t_data->batches;
            $ccc=login_user()->t_data->classes;
            
            // echo ' s <pre>';
            // print_r($sss);
                        
            // echo ' b <pre>';
            // print_r($bbb);
                        
            // echo ' c <pre>';
            // print_r($ccc);
            
            // die;
            // echo json_encode('die');
            // die;
            
            
            $admin_id=$this->db->select('id')->from('sh_users')->where('school_id',$school_id)->where('role_id',1)->where('deleted_at',0)->get()->row()->id;
            $assigned_subjects=array();
            $final_assig_batches=array();
            $firstwhere="";
            $secondwhere="";
            $assigned_rec=$this->db->select('class_id,batch_id')->from('sh_assign_subjects')->where('school_id',$school_id)->where('deleted_at is NULL',NULL)->where("(teacher_id=$user OR assistant_id=$user)")->get()->result();
            
            
            if(!empty($assigned_rec) && sizeof($assigned_rec) >=1)
            {
                $matched_batches=$this->db->select('id')->from('sh_batches')->where('school_id',$school_id)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->where('deleted_at is NULL',NULL)->get()->result();
                foreach($matched_batches as $skey => $matched_batch)
                {
                    foreach($assigned_rec as $key => $assig_rec)
                    {
                        if($assig_rec->batch_id==$matched_batch->id)
                        {
                            $final_assig_batches[$key]['class_id']=$assig_rec->class_id;
                            $final_assig_batches[$key]['batch_id']=$assig_rec->batch_id;
                        }
                    }
                    
                }
                
                $final_assig_batches=array_values($final_assig_batches);
                $final_assig_batches = array_unique($final_assig_batches,SORT_REGULAR);
            }
            else
            {
                $firstwhere="AND (sh_study_material.uploaded_by = '$user')";
            }
            
            
            // echo '<pre>';
            // print_r($assigned_rec);
            // echo '<pre>';
            // print_r($final_assig_batches);
            // echo '<pre>';
            // print_r($final_assig_batches);
            // die;
            // echo json_encode('die');
            // die;
            if(sizeof($final_assig_batches) >= 1)
            {
                $firstwhere="AND (";
                foreach($final_assig_batches as $key => $assig_rec)
                {
                    $assig_subject_codes=$this->db->select('id,code,class_id,batch_id')->from('sh_subjects')->where('school_id',$school_id)->where('class_id',$assig_rec['class_id'])->where('batch_id',$assig_rec['batch_id'])->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->where('deleted_at is NULL',NULL)->get()->result();
                    
                    if(sizeof($assig_subject_codes) >= 1)
                    {
                        foreach($assig_subject_codes as $ass_subjCode)
                        {
                            // $coun_assigRec=$this->db->select('*')->from('sh_assign_subjects')->where('school_id',$school_id)->where('teacher_id',$user)->where('class_id',$ass_subjCode->class_id)->where('batch_id',$ass_subjCode->batch_id)->where('subject_id',$ass_subjCode->id)->count_all_results();
                            $coun_assigRec=$this->db->select('*')->from('sh_assign_subjects')->where('school_id',$school_id)->where("(teacher_id=$user OR assistant_id=$user)")->where('class_id',$ass_subjCode->class_id)->where('batch_id',$ass_subjCode->batch_id)->where('subject_id',$ass_subjCode->id)->count_all_results();
                            if($coun_assigRec >= 1)
                            {
                                $assigned_subjects[]=$ass_subjCode;
                            }
                        }
                        
                    }
                    
                    if($key==0)
                    {
                        $firstwhere .="FIND_IN_SET('".$assig_rec['batch_id']."',sh_study_material.batch_id)";
                    }
                    else if($key > 0)
                    {
                        $firstwhere .="OR FIND_IN_SET('".$assig_rec['batch_id']."',sh_study_material.batch_id)";
                    }
                }
                
                
                $firstwhere .=")";
            }
            
            // echo '<pre>';
            // print_r($assigned_subjects);
            // die;
            // echo json_encode('die');
            // die;
            
            if(sizeof($assigned_subjects) >= 1)
            {

                $secondwhere ="AND (";
                foreach($assigned_subjects as $skey => $ass_sub)
                {
                    // if(sizeof($ass_sub) >= 1)
                    // {
                        // foreach($ass_sub as $key => $ass_s)
                        // {
                            // if($skey==0 && $key==0)
                            if($skey==0)
                            {
                                $secondwhere .="FIND_IN_SET('$ass_sub->code',sh_study_material.subject_code)";
                            }
                            // else if($key > 0)
                            else if($skey > 0)
                            {
                                $secondwhere .="OR FIND_IN_SET('$ass_sub->code',sh_study_material.subject_code)";
                            }
                        // }
                    // }
                }
                $secondwhere .=")";
            }
            
            
            // echo $firstwhere.$secondwhere;
            // die;
            // echo json_encode('die');
            // die;
            
            
            $query = "SELECT "
            . "sh_study_material.*,"
            . "sh_classes.name as class_name,"
            . "sh_batches.name as batch_name,"
            . "sh_subjects.name as subject_name, "
            . "date_format(sh_study_material.uploaded_at,'%d/%m/%Y') as uploaded_time, sh_users.name "
            . "From sh_study_material "
            . "Inner join sh_classes ON sh_study_material.class_id = sh_classes.id "
            . "left JOIN sh_batches ON sh_study_material.batch_id = sh_batches.id "
            . "left JOIN sh_subjects ON sh_study_material.subject_id = sh_subjects.id "
            . "Left JOIN sh_users ON sh_study_material.uploaded_by = sh_users.id "
            . "WHERE sh_study_material.delete_status=0 AND sh_study_material.uploaded_at = '$date'"
            . $firstwhere .$secondwhere
            . "AND sh_study_material.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";
            
            // echo $query.$where_part;
            // die;
            // echo json_encode('die');
            // die;
    
        }
        else if($role_id != 1 && $role_id != 4)
        {
            $admin_id=$this->db->select('id')->from('sh_users')->where('school_id',$school_id)->where('role_id',1)->where('deleted_at',0)->get()->row()->id;
            
            
            $query = "SELECT "
            . "sh_study_material.*,"
            . "sh_classes.name as class_name,"
            . "sh_batches.name as batch_name,"
            . "sh_subjects.name as subject_name, "
            . "date_format(sh_study_material.uploaded_at,'%d/%m/%Y') as uploaded_time, sh_users.name "
            . "From sh_study_material "
            . "Inner join sh_classes ON sh_study_material.class_id = sh_classes.id "
            . "left JOIN sh_batches ON sh_study_material.batch_id = sh_batches.id "
            . "left JOIN sh_subjects ON sh_study_material.subject_id = sh_subjects.id "
            . "Left JOIN sh_users ON sh_study_material.uploaded_by = sh_users.id "
            . "WHERE sh_study_material.delete_status=0 AND sh_study_material.uploaded_at = '$date' AND (sh_study_material.uploaded_by = '$user' OR sh_study_material.uploaded_by = '$admin_id')"
            . "AND sh_study_material.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";
    
        }
        else
        {
            $query = "SELECT "
            . "sh_study_material.*,"
            . "sh_classes.name as class_name,"
            . "sh_batches.name as batch_name,"
            . "sh_subjects.name as subject_name, "
            . "date_format(sh_study_material.uploaded_at,'%d/%m/%Y') as uploaded_time, sh_users.name "
            . "From sh_study_material "
            . "Inner join sh_classes ON sh_study_material.class_id = sh_classes.id "
            . "left JOIN sh_batches ON sh_study_material.batch_id = sh_batches.id "
            . "left JOIN sh_subjects ON sh_study_material.subject_id = sh_subjects.id "
            . "Left JOIN sh_users ON sh_study_material.uploaded_by = sh_users.id "
            . "WHERE sh_study_material.delete_status=0 AND sh_study_material.uploaded_at = '$date' "
            . "AND sh_study_material.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";
    
        }
        // echo $query.$where_part;
        // die;
        // echo json_encode('die');
        // die;

        $response = $this->admin_model->dbQuery($query.$where_part);
        
        foreach ($response as $key=>$val) {
            $response[$key]->files = explode(",", $val->files);
            // google drive getting 
            $response[$key]->filesurl = explode(",", $val->filesurl);
            $response[$key]->file_names = explode(",", $val->file_names);
            $response[$key]->thumbnail_links = explode(",", $val->thumbnail_links);
            
            
            $response[$key]->filterbatch_name = $filter_batchname;
            $response[$key]->filtersubject_name = $filter_subjectname;
            
            
            $files = $val->files;
            $old_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $old_files[] = $temp;
                }
            }
            
            $response[$key]->old_files = $old_files;

            $response[$key]->content_type = lang($this->createSlug($response[$key]->content_type));
        }

        $data["materials"] = $response;
        $data["today"] = date("d/m/Y");
        
        echo json_encode($data);
    }

    function getNewMaterials() {

        //-------------------------
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_assignments.batch_ids IN (". implode(',', login_user()->t_data->batches) .") order by sh_assignments.published_date desc";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part =  " order by sh_assignments.published_date desc";
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part =  " order by sh_assignments.published_date desc";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part =  " order by sh_assignments.published_date desc";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part =  " order by sh_assignments.published_date desc";
        }
        //-------------------------

        $query = "SELECT "
        . "sh_assignments.*,"
        . "sh_classes.name as class_name,"
        //. "sh_batches.name as batch_name,"
        . "sh_subjects.name as subject_name, "
        . "date_format(sh_assignments.published_date,'%d/%m/%Y') as uploaded_time, sh_users.name "
        . "From sh_assignments "
        . "Inner join sh_classes ON sh_assignments.class_id = sh_classes.id "
        //. "left JOIN sh_batches ON sh_assignments.batch_ids = sh_batches.id "
        . "left JOIN sh_subjects ON sh_assignments.subject_id = sh_subjects.id "
        . "Left JOIN sh_users ON sh_assignments.uploaded_by = sh_users.id "
        . "WHERE sh_assignments.deleted_status=0 AND sh_assignments.content_type='Assignment' "
        . "AND sh_assignments.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";



        $response = $this->admin_model->dbQuery($query.$where_part);
        
        foreach ($response as $key => $val) {
            $batch_id = explode(",", $val->batch_ids);
            $batch = array();
            foreach ($batch_id as $key => $b_id) {
                $name = $this->db->query("SELECT name FROM sh_batches WHERE id='$b_id'")->result();
                if(sizeof($name) >=1)
                {
                 array_push($batch, $name[0]->name);   
                }
                
             } 
             $b_name = implode(",", $batch);
             $val->sections = $b_name;
        } 
        

        foreach ($response as $key=>$val) {
            $response[$key]->files = explode(",", $val->files);
            $files = $val->files;
            $old_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $old_files[] = $temp;
                }
            }
            
            $response[$key]->old_files = $old_files;
            $response[$key]->filesurl=explode(',',$response[$key]->filesurl);
            $response[$key]->thumbnail_links=explode(',',$response[$key]->thumbnail_links);
            $response[$key]->file_names=explode(',',$response[$key]->file_names);
            $response[$key]->fileids=explode(',',$response[$key]->fileids);

            $response[$key]->content_type = lang($this->createSlug($response[$key]->content_type));
        }

        $data["materials"] = $response;

        echo json_encode($data);
    }

    function getNewHomework() {

        //-------------------------
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND batch_ids IN (". implode(',', login_user()->t_data->batches) .") order by sh_assignments.published_date desc";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part =  " order by sh_assignments.published_date desc";
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part =  " order by sh_assignments.published_date desc";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part =  " order by sh_assignments.published_date desc";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part =  " order by sh_assignments.published_date desc";
        }
        //-------------------------

        $query = "SELECT "
        . "sh_assignments.*,"
        . "sh_classes.name as class_name,"
        //. "sh_batches.name as batch_name,"
        . "sh_subjects.name as subject_name, "
        . "date_format(sh_assignments.published_date,'%d/%m/%Y') as uploaded_time, sh_users.name "
        . "From sh_assignments "
        . "Inner join sh_classes ON sh_assignments.class_id = sh_classes.id "
        //. "left JOIN sh_batches ON sh_assignments.batch_ids = sh_batches.id "
        . "left JOIN sh_subjects ON sh_assignments.subject_id = sh_subjects.id "
        . "Left JOIN sh_users ON sh_assignments.uploaded_by = sh_users.id "
        . "WHERE sh_assignments.deleted_status=0 AND sh_assignments.content_type='Homework' "
        . "AND sh_assignments.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";



        $response = $this->admin_model->dbQuery($query.$where_part);
        
        foreach ($response as $key => $val) {
            $batch_id = explode(",", $val->batch_ids);
            $batch = array();
            foreach ($batch_id as $key => $b_id) {
                $name = $this->db->query("SELECT name FROM sh_batches WHERE id='$b_id'")->result();
                array_push($batch, $name[0]->name);
             } 
             $b_name = implode(",", $batch);
             $val->sections = $b_name;
        } 
        

        foreach ($response as $key=>$val) {
            $response[$key]->files = explode(",", $val->files);
            $files = $val->files;
            $old_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $old_files[] = $temp;
                }
            }
            
            $response[$key]->old_files = $old_files;
            $response[$key]->filesurl=explode(',',$response[$key]->filesurl);
            $response[$key]->thumbnail_links=explode(',',$response[$key]->thumbnail_links);
            $response[$key]->file_names=explode(',',$response[$key]->file_names);
            $response[$key]->fileids=explode(',',$response[$key]->fileids);

            $response[$key]->content_type = lang($this->createSlug($response[$key]->content_type));
        }

        $data["materials"] = $response;

        //print_r($data); die();

        echo json_encode($data);
    }


    public static function createSlug($str, $delimiter = '_'){

              $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
              return $slug;

          } 

    public function getToday(){
        $data["today"] = date("d/m/Y");
        echo json_encode($data);
    }

    function filter() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $batch_id = $request->section;
        $selected_date =   to_mysql_date($request->date);
        if ($batch_id == "all") {
            $batch_id = 0;
        }
        $subject_id = $request->subject;
        $type = $request->type;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $response['materials'] = $this->study_model->filter($school_id, $class_id, $batch_id, $selected_date, $subject_id, $type);
        for ($i = 0; $i < count($response['materials']); $i++) {
            $response['materials'][$i]['files'] = explode(",", $response['materials'][$i]['files']);
            $files = $response['materials'][$i]['files'];
            $old_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $old_files[] = $temp;
                }
            }
            
            $response['materials'][$i]['old_files'] = $old_files;
        }
        echo json_encode($response);
    }

    function filter_studymaterialGeneral() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $batch_id = $request->section;
        if($request->date) 
        $selected_date =   to_mysql_date($request->date);
        else $selected_date = '';
        if($request->type) $type = $request->type;
        else $type = '';
        if($request->subject) $subject_id =$request->subject;
        else $subject_id = '';
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $filter_batchname='';
        $filter_subjectname='';
        
        if ($batch_id == "all" && $subject_id == "all")
        {
            $query = $this->db->select('id')->from('sh_batches')->where('school_id' , $school_id)->where('class_id' , $class_id)->where('deleted_at IS NULL', NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->result();
            $id=array();
            foreach($query as $row)
            {
                $id[] = intval($row->id);
                // $q = $this->db->query("select code from sh_subjects where school_id = 29 and class_id = $class_id and batch_id = $row->id");
                // $res = $q->result();
                // foreach($res as $r)
                // {
                //     $subject_code[] = $r->code;
                // } 
            }
            $batch_id = implode(',' , $id);
            $response['materials'] = $this->study_model->filter_material_general($school_id, $class_id, $batch_id, $selected_date,$subject_id,  $type,$user_id);
        }
        else if($batch_id == "all" && $subject_id != "all") 
        {
            
            $query = $this->db->select('id')->from('sh_batches')->where('school_id' , $school_id)->where('class_id' , $class_id)->where('deleted_at IS NULL', NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->result();
            $id=array();
            foreach($query as $row)
            {
                $id[] = $row->id;
            }
            $batch_id = implode(',' , $id);
            
                
            if($subject_id!='')
            {
                $s_n = $this->db->distinct('name')->from('sh_subjects')->where('school_id' , $school_id)->where('class_id' , $class_id)->where('id' , $subject_id)->where('deleted_at is NULL' , NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row();
                if(!empty($s_n))
                {
                    $filter_subjectname = $s_n->name;
                }
                
                if($this->session->userdata("userdata")["role_id"]!=1)
                {
                    $all_userdata=$this->db->distinct('batch_id')->from('sh_assign_subjects')->where('school_id',$school_id)->where('class_id',intval($class_id))->where("(teacher_id='$user_id' OR assistant_id='$user_id')")->where('deleted_at is NULL',NULL)->group_by('batch_id')->get()->result();
                    $u_dataManage_batchids=array();
                    $u_dataManage_subjectids=array();
                    foreach($all_userdata as $u_data)
                    {
                        $u_dataManage_batchids[]=$u_data->batch_id;
                        $u_dataManage_subjectids[]=$u_data->subject_id;
                    }
                    $batch_id=implode(',',$u_dataManage_batchids);
                }
                else
                {
                    $all_userdata=$this->db->select('*')->from('sh_batches')->where('school_id',$school_id)->where('class_id',$class_id)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->where('deleted_at is NULL',NULL)->get()->result();
                    $u_dataManage_batchids=array();
                    foreach($all_userdata as $u_data)
                    {
                        $u_dataManage_batchids[]=$u_data->id;
                    }
                    $batch_id=implode(',',$u_dataManage_batchids);
                }
            }
            else
            {
                if($this->session->userdata("userdata")["role_id"]!=1)
                {
                    $all_userdata=$this->db->distinct('batch_id')->from('sh_assign_subjects')->where('school_id',$school_id)->where('class_id',intval($class_id))->where("(teacher_id='$user_id' OR assistant_id='$user_id')")->where('deleted_at is NULL',NULL)->group_by('batch_id')->get()->result();
                    $u_dataManage_batchids=array();
                    $u_dataManage_subjectids=array();
                    foreach($all_userdata as $u_data)
                    {
                        $u_dataManage_batchids[]=$u_data->batch_id;
                        $u_dataManage_subjectids[]=$u_data->subject_id;
                    }
                    $batch_id=implode(',',$u_dataManage_batchids);
                }
                else
                {
                    $all_userdata=$this->db->select('*')->from('sh_batches')->where('school_id',$school_id)->where('class_id',$class_id)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->where('deleted_at is NULL',NULL)->get()->result();
                    $u_dataManage_batchids=array();
                    foreach($all_userdata as $u_data)
                    {
                        $u_dataManage_batchids[]=$u_data->id;
                    }
                    $batch_id=implode(',',$u_dataManage_batchids);
                }
            }
            
            $response['materials'] = $this->study_model->filter_material_general($school_id, $class_id, $batch_id, $selected_date,$subject_id,  $type,$user_id);
            // echo '<pre>';
            // print_r($response['materials']);
            // die;
            
            // echo $this->db->last_query();
            // die;
            // echo json_encode('die');
            // die;
        }
        else if ($batch_id != "all" && $subject_id == "all")
        {
        
            // $q = $this->db->query("select code from sh_subjects where school_id = 29 and class_id = $class_id and batch_id = $batch_id");
            // $res = $q->result();
            // foreach($res as $r)
            // {
            //     $subject[] = $r->code;
            // } 
            // $subject_id = implode(',' ,$subject);
            $b_n = $this->db->select('name')->from('sh_batches')->where('school_id' , $school_id)->where('class_id' , $class_id)->where('id' , $batch_id)->where('deleted_at is NULL' , NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row();
            if(!empty($b_n))
            {
                $filter_batchname = $b_n->name;
            }
    
            $response['materials'] = $this->study_model->filter_material_general($school_id, $class_id, $batch_id, $selected_date,$subject_id,  $type,$user_id);
        }
        else 
        {
            
            $b_n = $this->db->select('name')->from('sh_batches')->where('school_id' , $school_id)->where('class_id' , $class_id)->where('id' , $batch_id)->where('deleted_at is NULL' , NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row();
            if(!empty($b_n))
            {
                $filter_batchname = $b_n->name;
            }
            
            if($subject_id!='')
            {
                $s_n = $this->db->distinct('name')->from('sh_subjects')->where('school_id' , $school_id)->where('class_id' , $class_id)->where('id' , $subject_id)->where('deleted_at is NULL' , NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row();
                if(!empty($s_n))
                {
                    $filter_subjectname = $s_n->name;
                }
            }
            else
            {
                if($this->session->userdata("userdata")["role_id"]!=1)
                {
                    $all_userdata=$this->db->select('*')->from('sh_assign_subjects')->where('school_id',$school_id)->where('batch_id',$batch_id)->where('class_id',intval($class_id))->where("(teacher_id='$user_id' OR assistant_id='$user_id')")->where('deleted_at is NULL',NULL)->get()->result();
                    
                    $u_dataManage_batchids=array();
                    $u_dataManage_subjectids=array();
                    foreach($all_userdata as $u_data)
                    {
                        $u_dataManage_batchids[]=$u_data->batch_id;
                        $u_dataManage_subjectids[]=$u_data->subject_id;
                    }
                    $batch_id=implode(',',$u_dataManage_batchids);
                }
            }
            
            $response['materials'] = $this->study_model->filter_material_general($school_id, $class_id, $batch_id, $selected_date,$subject_id,  $type,$user_id);
        }
   

        for ($i = 0; $i < count($response['materials']); $i++) {
            $response['materials'][$i]['files'] = explode(",", $response['materials'][$i]['files']);
            $files = $response['materials'][$i]['files'];
            $old_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $old_files[] = $temp;
                }
            }
            
            // if($response['materials'][$i]['filesurl'])
            // {
                
            // }
            
            $response['materials'][$i]['old_files'] = $old_files;
            // google drive getting 
            $response['materials'][$i]['filesurl'] = explode(",", $response['materials'][$i]['filesurl']);
            $response['materials'][$i]['file_names'] = explode(",", $response['materials'][$i]['file_names']);
            $response['materials'][$i]['thumbnail_links'] = explode(",", $response['materials'][$i]['thumbnail_links']);
            
            
            $response['materials'][$i]['filterbatch_name'] = $filter_batchname;
            $response['materials'][$i]['filtersubject_name'] = $filter_subjectname;
        }
        
        // echo '<pre>';
        // print_r($response['materials']);
        // die;
        // echo json_encode('die');
        // die;
        
        echo json_encode($response);
    }

    public function filter_assignments(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject_id = $request->subject;

        $from_date = $to_date = "";
       
        if(isset($request->f_date)){
            $from_date =   to_mysql_date($request->f_date);
        }if(isset($request->t_date)){
            $to_date =   to_mysql_date($request->t_date);
        }
        
        
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $where_part = "";

        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part .=  " AND sh_assignments.batch_ids IN (". implode(',', login_user()->t_data->batches) .") ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part .=  "";
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part .=  "";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part .=  "";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part .=  "";
        }

        $subject_code = "";
        if($subject_id != 0 && $subject_id != ""){
            
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }

        $query = "SELECT "
        . "sh_assignments.*,"
        . "sh_classes.name as class_name,"
        //. "sh_batches.name as batch_name,"
        . "sh_subjects.name as subject_name, "
        . "date_format(sh_assignments.published_date,'%d/%m/%Y') as uploaded_time, sh_users.name "
        . "From sh_assignments "
        . "Inner join sh_classes ON sh_assignments.class_id = sh_classes.id "
        //. "left JOIN sh_batches ON sh_assignments.batch_ids = sh_batches.id "
        . "left JOIN sh_subjects ON sh_assignments.subject_id = sh_subjects.id "
        . "Left JOIN sh_users ON sh_assignments.uploaded_by = sh_users.id "
        . "WHERE sh_assignments.deleted_status=0 AND sh_assignments.content_type='Assignment' "
        . "AND sh_assignments.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";

        if($class_id != ""){
            $where_part .= " AND sh_assignments.class_id='$class_id' ";     
        }
        if($batch_id != ""){
            $where_part .= " AND FIND_IN_SET('$batch_id',sh_assignments.batch_ids) ";
        }
        if($subject_code != ""){
            $where_part .= " AND sh_assignments.subject_code='$subject_code' ";
        }
        if ($from_date != "" && $to_date == "") {
            $where_part .= " AND sh_assignments.published_date='$from_date' ";
        }
        if ($from_date == "" && $to_date != "") {
            $where_part .= " AND sh_assignments.published_date='$to_date' ";
        }
        if($from_date != "" && $to_date != ""){
            $where_part .= " AND sh_assignments.published_date between '$from_date' AND '$to_date' ";
        }

        $where_part .= " order by sh_assignments.created_at desc ";

        $response = $this->admin_model->dbQuery($query.$where_part);



        foreach ($response as $key => $val) {
            $batch_id = explode(",", $val->batch_ids);
            $batch = array();
            foreach ($batch_id as $key => $b_id) {
                $name = $this->db->query("SELECT name FROM sh_batches WHERE id='$b_id'")->result();
                if(sizeof($name) >=1)
                {
                    array_push($batch, $name[0]->name);
                }
             }
             
             $b_name = implode(",", $batch);
             $val->sections = $b_name;
        }
        

        foreach ($response as $key=>$val) {
            $response[$key]->files = explode(",", $val->files);
            $files = $val->files;
            $old_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $old_files[] = $temp;
                }
            }
            
            $response[$key]->old_files = $old_files;

            $response[$key]->content_type = lang($this->createSlug($response[$key]->content_type));
        }

        $data["materials"] = $response;

        echo json_encode($data);

    }

    public function filter_homework(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject_id = $request->subject;
        $from_date = $to_date = "";
       
        if(isset($request->f_date)){
            $from_date =   to_mysql_date($request->f_date);
        }if(isset($request->t_date)){
            $to_date =   to_mysql_date($request->t_date);
        }
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $where_part = "";

        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part .=  " AND sh_assignments.batch_ids IN (". implode(',', login_user()->t_data->batches) .") ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part .=  "";
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part .=  "";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part .=  "";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part .=  "";
        }

        $subject_code = "";
        if($subject_id != 0 && $subject_id != ""){
            
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }

        $query = "SELECT "
        . "sh_assignments.*,"
        . "sh_classes.name as class_name,"
        //. "sh_batches.name as batch_name,"
        . "sh_subjects.name as subject_name, "
        . "date_format(sh_assignments.published_date,'%d/%m/%Y') as uploaded_time, sh_users.name "
        . "From sh_assignments "
        . "Inner join sh_classes ON sh_assignments.class_id = sh_classes.id "
        //. "left JOIN sh_batches ON sh_assignments.batch_ids = sh_batches.id "
        . "left JOIN sh_subjects ON sh_assignments.subject_id = sh_subjects.id "
        . "Left JOIN sh_users ON sh_assignments.uploaded_by = sh_users.id "
        . "WHERE sh_assignments.deleted_status=0 AND sh_assignments.content_type='Homework' "
        . "AND sh_assignments.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";

        if($class_id != ""){
            $where_part .= " AND sh_assignments.class_id='$class_id' ";     
        }
        if($batch_id != ""){
            $where_part .= " AND FIND_IN_SET('$batch_id',sh_assignments.batch_ids) ";
        }
        if($subject_code != ""){
            $where_part .= " AND sh_assignments.subject_code='$subject_code' ";
        }
        if ($from_date != "" && $to_date == "") {
            $where_part .= " AND sh_assignments.published_date='$from_date' ";
        }
        if ($from_date == "" && $to_date != "") {
            $where_part .= " AND sh_assignments.published_date='$to_date' ";
        }
        if($from_date != "" && $to_date != ""){
            $where_part .= " AND sh_assignments.published_date between '$from_date' AND '$to_date' ";
        }

        $where_part .= " order by sh_assignments.created_at desc ";



        $response = $this->admin_model->dbQuery($query.$where_part);

        foreach ($response as $key => $val) {
            $batch_id = explode(",", $val->batch_ids);
            $batch = array();
            foreach ($batch_id as $key => $b_id) {
                $name = $this->db->query("SELECT name FROM sh_batches WHERE id='$b_id'")->result();
                array_push($batch, $name[0]->name);
             } 
             $b_name = implode(",", $batch);
             $val->sections = $b_name;
        } 
        

        foreach ($response as $key=>$val) {
            $response[$key]->files = explode(",", $val->files);
            $files = $val->files;
            $old_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $old_files[] = $temp;
                }
            }
            
            $response[$key]->old_files = $old_files;

            $response[$key]->content_type = lang($this->createSlug($response[$key]->content_type));
        }

        $data["materials"] = $response;

        echo json_encode($data);
    }

    function filter_parent() {
        $postdata = file_get_contents("php://input");

        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $subject_id = $request->subject_id;
        $type = $request->type;

        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }

        $response['materials'] = $this->study_model->filter_parentmaterial($school_id, $class_id, $batch_id, $subject_id, $type);
        for ($i = 0; $i < count($response['materials']); $i++) {
            $response['materials'][$i]['files'] = explode(",", $response['materials'][$i]['files']);
        }
        echo json_encode($response);
    }

    function filter_student() {
        $postdata = file_get_contents("php://input");

        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $selected_date='';
        $subject_id = '';
        $type = '';
        
        if(isset($request->subject_id)) $subject_id = $request->subject_id;
        else $subject_id = '';
        if(isset($request->type)) $type = $request->type;
        else $type = '';
        if(isset($request->date) && $request->date != '')
        {
            $selected_date = $request->date;
            $myDateTime = DateTime::createFromFormat('d/m/Y', $selected_date);
            $selected_date = $myDateTime->format('Y-m-d');
        } 
        else $selected_date = '';
        

        
        $user_id = $this->session->userdata("userdata")["user_id"];

        $student = $this->db->select('s.class_id, s.batch_id, s.subject_group_id')->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->where('s.id', $user_id)->get()->row();
        
        //zafar
        $b_n = $this->db->select('name')->from('sh_batches')->where('school_id' , $school_id)->where('class_id' , $student->class_id)->where('id' , $student->batch_id)->where('deleted_at is NULL' , NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row();
        $b_name = $b_n->name;
        
        
        if($student){

            $materials = $this->study_model->filter_studentmaterial($school_id, $student->class_id, $student->batch_id, $subject_id, $type, '', $selected_date);
                //Azeem remove future assignments, not show before date
            foreach ($materials as $key => $value) {
                
                if($value["uploaded_at"] > date("Y-m-d") && $value["content_type"] == "Assignment"){
                    unset($materials[$key]);
                }
                //zafar
                $materials[$key]['batch_name'] = $b_name;
            }
            $materials = array_values($materials); // reindex of array

            $response['materials'] = $materials;
        }else{
            $response['materials'] = array();
        }

        
        for ($i = 0; $i < count($response['materials']); $i++) {
            $response['materials'][$i]['files'] = explode(",", $response['materials'][$i]['files']);
        }
        echo json_encode($response);
    }

    function filter_student_all_assignment() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $student_id = $this->session->userdata("userdata")["user_id"];
        $current_date = date('Y-m-d');
        $response = $this->db->query("SELECT a.*,c.name as class_name,sb.name as subject_name,u.name as teacher_name,u.id as teacher_id,u.avatar FROM sh_assignments a LEFT JOIN sh_classes c ON a.class_id=c.id LEFT JOIN sh_subjects sb ON a.subject_id=sb.id LEFT JOIN sh_users as u ON a.uploaded_by=u.id WHERE FIND_IN_SET('$student_id',a.student_ids) AND a.school_id='$school_id' AND a.content_type='Assignment' AND a.deleted_status='0' AND a.published_date <= '$current_date' ORDER BY a.created_at desc")->result();
        
        foreach ($response as $res) {

            $re = $this->db->query("SELECT * FROM sh_submit_material WHERE material_id='$res->id' AND student_id='$student_id'")->result();
            //print_r($re[0]->obtained_marks); die();
            $count = count($re);
            if ($count > 0 ) {
                $res->status = "Submitted";
                if ($re[0]->obtained_marks == "") {
                    $res->obtained_marks = "Waiting";
                } else {
                    $res->obtained_marks = $re[0]->obtained_marks;
                } 
            } else {
                $res->status = "Due";
                $res->obtained_marks = "Submit Your Assignment First";
            }
        }

        for ($i = 0; $i < count($response); $i++) {
            $response[$i]->files = explode(",", $response[$i]->files);
        }
        echo json_encode($response);
    }

    function filter_student_all_homework(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $student_id = $this->session->userdata("userdata")["user_id"];
        $current_date = date('Y-m-d');

        $response = $this->db->query("SELECT a.*,c.name as class_name,sb.name as subject_name,u.name as teacher_name,u.id as teacher_id,u.avatar FROM sh_assignments a LEFT JOIN sh_classes c ON a.class_id=c.id LEFT JOIN sh_subjects sb ON a.subject_id=sb.id LEFT JOIN sh_users as u ON a.uploaded_by=u.id WHERE FIND_IN_SET('$student_id',a.student_ids) AND a.school_id='$school_id' AND a.content_type='Homework' AND a.deleted_status='0' AND a.published_date <= '$current_date' ORDER BY a.created_at desc")->result();
        
        foreach ($response as $res) {

            $re = $this->db->query("SELECT * FROM sh_submit_material WHERE material_id='$res->id' AND student_id='$student_id'")->result();
           
            $count = count($re);
            if ($count > 0 ) {
                $res->status = "Submitted";
                if ($re[0]->obtained_marks == "") {
                    $res->obtained_marks = "Waiting";
                } else {
                    $res->obtained_marks = $re[0]->obtained_marks;
                } 
            } else {
                $res->status = "Due";
                $res->obtained_marks = "Submit Your Assignment First";
            }
        }

        for ($i = 0; $i < count($response); $i++) {
            $response[$i]->files = explode(",", $response[$i]->files);
        }
        echo json_encode($response);
    }

    function filter_student_assignment() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $student_id = $this->session->userdata("userdata")["user_id"];
        $subject_id = $request->subject_id;
        $current_date = date('Y-m-d');

        $batch_name = $this->db->query("SELECT s.batch_id,b.name as section FROM sh_students_$school_id s LEFT JOIN sh_batches b ON s.batch_id=b.id WHERE  s.id='$student_id' ")->result();

        $section = $batch_name[0]->section; 

        $subject = $this->db->query("SELECT code FROM sh_subjects WHERE id='$subject_id' AND school_id='$school_id' AND deleted_at is null")->result();

        $subject_code = $subject[0]->code;

        $response = $this->db->query("SELECT a.*,c.name as class_name,sb.name as subject_name,u.name as teacher_name,u.id as teacher_id,u.avatar FROM sh_assignments a LEFT JOIN sh_classes c ON a.class_id=c.id LEFT JOIN sh_subjects sb ON a.subject_id=sb.id LEFT JOIN sh_users as u ON a.uploaded_by=u.id WHERE FIND_IN_SET('$student_id',a.student_ids) AND a.school_id='$school_id' AND a.subject_code='$subject_code' AND a.content_type='Assignment' AND a.deleted_status='0' AND a.published_date <= '$current_date' ORDER BY a.created_at desc")->result();
        
        foreach ($response as $res) {
            //$res->section = $section;

            $re = $this->db->query("SELECT * FROM sh_submit_material WHERE material_id='$res->id' AND student_id='$student_id'")->result();
           
            $count = count($re);
            if ($count > 0 ) {
                $res->status = "Submitted";
                if ($re[0]->obtained_marks == "") {
                    $res->obtained_marks = "Waiting";
                } else {
                    $res->obtained_marks = $re[0]->obtained_marks;
                } 
            } else {
                $res->status = "Due";
                $res->obtained_marks = "Submit Your Assignment First";
            }
        }

        for ($i = 0; $i < count($response); $i++) {
            $response[$i]->files = explode(",", $response[$i]->files);
        }


        //print_r($response); die();

        echo json_encode($response);

        
    }

    function filter_student_homework() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $student_id = $this->session->userdata("userdata")["user_id"];
        $subject_id = $request->subject_id;
        $current_date = date('Y-m-d');

        $batch_name = $this->db->query("SELECT s.batch_id,b.name as section FROM sh_students_$school_id s LEFT JOIN sh_batches b ON s.batch_id=b.id WHERE  s.id='$student_id' ")->result();

        $section = $batch_name[0]->section; 

        $subject = $this->db->query("SELECT code FROM sh_subjects WHERE id='$subject_id' AND school_id='$school_id' AND deleted_at is null")->result();

        $subject_code = $subject[0]->code;

        $response = $this->db->query("SELECT a.*,c.name as class_name,sb.name as subject_name,u.name as teacher_name,u.avatar FROM sh_assignments a LEFT JOIN sh_classes c ON a.class_id=c.id LEFT JOIN sh_subjects sb ON a.subject_id=sb.id LEFT JOIN sh_users as u ON a.uploaded_by=u.id WHERE FIND_IN_SET('$student_id',a.student_ids) AND a.school_id='$school_id' AND a.subject_code='$subject_code' AND a.content_type='Homework' AND a.deleted_status='0' AND a.published_date <= '$current_date' ORDER BY a.created_at desc")->result();
        
        foreach ($response as $res) {
            $re = $this->db->query("SELECT * FROM sh_submit_material WHERE material_id='$res->id' AND student_id='$student_id'")->result();
            //print_r($re[0]->obtained_marks); die();
            $count = count($re);
            if ($count > 0 ) {
                $res->status = "Submitted";
                if ($re[0]->obtained_marks == "") {
                    $res->obtained_marks = "Waiting";
                } else {
                    $res->obtained_marks = $re[0]->obtained_marks;
                } 
            } else {
                $res->status = "Due";
                $res->obtained_marks = "Submit Your Assignment First";
            }
        }

        for ($i = 0; $i < count($response); $i++) {
            $response[$i]->files = explode(",", $response[$i]->files);
        }
        echo json_encode($response);

        
    }

    function getDownloadMaterials() {

        $filter_batchname='';
        $filter_subjectname='';
            
        //-------------------------
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_batches.id IN (". implode(',', login_user()->t_data->batches) .") order by sh_study_material.uploaded_at desc";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part =  " order by sh_study_material.uploaded_at desc";
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part =  " order by sh_study_material.uploaded_at desc";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part =  " order by sh_study_material.uploaded_at desc";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part =  " order by sh_study_material.uploaded_at desc";
        }
        //-------------------------
       $user =$this->session->userdata("userdata")["user_id"];
       $role_id = $this->session->userdata("userdata")["role_id"];
       $school_id = $this->session->userdata("userdata")["sh_id"];
       $date =date('Y-m-d'); 
        
        if($role_id != 1)
        {
            $admin_id=$this->db->select('id')->from('sh_users')->where('school_id',$school_id)->where('role_id',1)->where('deleted_at',0)->get()->row()->id;
            
            $query = "SELECT "
            . "sh_study_material.*,"
            . "sh_classes.name as class_name,"
            . "sh_batches.name as batch_name,"
            . "sh_subjects.name as subject_name, "
            . "date_format(sh_study_material.uploaded_at,'%d/%m/%Y') as uploaded_time, sh_users.name "
            . "From sh_study_material "
            . "Inner join sh_classes ON sh_study_material.class_id = sh_classes.id "
            . "left JOIN sh_batches ON sh_study_material.batch_id = sh_batches.id "
            . "left JOIN sh_subjects ON sh_study_material.subject_id = sh_subjects.id "
            . "Left JOIN sh_users ON sh_study_material.uploaded_by = sh_users.id "
            . "WHERE sh_study_material.delete_status=0 AND sh_study_material.uploaded_at = '$date' AND (sh_study_material.uploaded_by = '$user' OR sh_study_material.uploaded_by = '$admin_id')"
            . "AND sh_study_material.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";
    
        }
        else
        {
            $query = "SELECT "
            . "sh_study_material.*,"
            . "sh_classes.name as class_name,"
            . "sh_batches.name as batch_name,"
            . "sh_subjects.name as subject_name, "
            . "date_format(sh_study_material.uploaded_at,'%d/%m/%Y') as uploaded_time, sh_users.name "
            . "From sh_study_material "
            . "Inner join sh_classes ON sh_study_material.class_id = sh_classes.id "
            . "left JOIN sh_batches ON sh_study_material.batch_id = sh_batches.id "
            . "left JOIN sh_subjects ON sh_study_material.subject_id = sh_subjects.id "
            . "Left JOIN sh_users ON sh_study_material.uploaded_by = sh_users.id "
            . "WHERE sh_study_material.delete_status=0 AND sh_study_material.uploaded_at = '$date' "
            . "AND sh_study_material.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";
    
        }
       

        $response = $this->admin_model->dbQuery($query.$where_part);
        
        foreach ($response as $key=>$val) {
            $response[$key]->files = explode(",", $val->files);
            // google drive getting
            $response[$key]->filesurl = explode(",", $val->filesurl);
            $response[$key]->file_names = explode(",", $val->file_names);
            $response[$key]->thumbnail_links = explode(",", $val->thumbnail_links);
            
            $response[$key]->filterbatch_name = $filter_batchname;
            $response[$key]->filtersubject_name = $filter_subjectname;
        }
        
        echo json_encode($response);
    }

    function deleteMaterial() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $rowid = $request->deleteId;
        $result=$this->study_model->getdeletedfilesId($rowid);

        if($result->storage_type==2)
        {
            $sch_id=$this->session->userdata("userdata");

            $sch_id=$sch_id['sh_id'];
            $gd_tokenfile='token_'.$sch_id.'.json';
            $client = getClient($sch_id,$gd_tokenfile);

            if(file_exists($gd_tokenfile))
            {
                $accessToken = json_decode(file_get_contents($gd_tokenfile), true);
                $client->setAccessToken($accessToken);
                $service = new Google_Service_Drive($client);

                $Ids=explode(',',$result->fileids);
                
                if($Ids[0]!='')
                {
                    foreach($Ids as $id)
                    {
                        $service->files->delete($id);
                    }
                }

                $this->study_model->deleteMaterial($rowid);
                $response['deleted'] = true;
                $response['message'] = lang('study_deleted_msg');
                echo json_encode($response);
            }
        }
        elseif($result->storage_type==1)
        {
            $nameof_files=explode(',',$result->files);
            foreach($nameof_files as $deleting_file)
            {
                if($deleting_file){
                $filepath='uploads/study_material/'.$deleting_file;
                unlink($filepath);
                }
            }
            $this->study_model->deleteMaterial($rowid);
            $response['deleted'] = true;
            $response['message'] = lang('study_deleted_msg');
            echo json_encode($response);
        }
        
    }
    
    function deleteAssignment() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->deleteId;
        $rowid = $id;
        
        $result=$this->study_model->get_AssigdeletedfilesId($id);
        if($result->storage_type==2)
        {
            $sch_id=$this->session->userdata("userdata");

            $sch_id=$sch_id['sh_id'];
            $gd_tokenfile='token_'.$sch_id.'.json';
            $client = getClient($sch_id,$gd_tokenfile);

            if(file_exists($gd_tokenfile))
            {
                $accessToken = json_decode(file_get_contents($gd_tokenfile), true);
                $client->setAccessToken($accessToken);
                $service = new Google_Service_Drive($client);

                $Ids=explode(',',$result->fileids);
                
                if($Ids[0]!='')
                {
                    foreach($Ids as $id)
                    {
                        $service->files->delete($id);
                    }
                }

                $this->study_model->deleteAssignment($rowid);
                $response['deleted'] = true;
                $response['message'] = lang('study_deleted_msg');
                echo json_encode($response);
            }
        }
        elseif($result->storage_type==1)
        {
            $this->study_model->deleteAssignment($id);
            $response['deleted'] = true;
            $response['message'] = lang('study_deleted_msg');
            echo json_encode($response);
        }
    }

    public function upload_attachments() {
        $names = array();
        $new_names = array();
        // echo '<pre>';
        // echo sizeof($_FILES);
        // print_r($_FILES);
        // die;
        // echo json_encode('die');
        // die;
        for ($i = 0; $i < count($_FILES['file']['name']); $i++)
        {
            $names[] = $_FILES['file']['name'][$i];
            $file_detail=pathinfo($_FILES['file']['name'][$i]);
            $up_filename=$file_detail['filename'];
            $up_ext=$file_detail['extension'];
            
            
            // $_FILES['file']['name'][$i] = str_replace(" ", "-", time() . '_' . $_FILES['file']['name'][$i]);
            $_FILES['file']['name'][$i] = str_replace(" ", "-", $up_filename. '_' .time().'.'.$up_ext);
                                          
            $new_names[] = $_FILES['file']['name'][$i];
        }
        if (isset($_FILES['file'])) {
            if (!file_exists('./uploads/study_material')) {
                mkdir('./uploads/study_material', 0777, true);
            }
            $uploaddir = './uploads/study_material/';
            for ($i = 0; $i < count($_FILES['file']['name']); $i++) {
                $uploadfile = $uploaddir . $_FILES['file']['name'][$i];
                if (move_uploaded_file($_FILES['file']['tmp_name'][$i], $uploadfile)) {
                    $config['image_library'] = 'gd2';
                    $config['source_image'] = $uploadfile;
                    $data = getimagesize($config['source_image']);
                    $width = $data[0];
                    $height = $data[1];
                    $config['create_thumb'] = false;
                    $config['maintain_ratio'] = TRUE;
                    $config['width'] = $width - 1;
                    $config['height'] = $height - 1;
                    $config['quality'] = '30%';  
                    $this->image_lib->initialize($config);
                    $this->image_lib->resize();

                    
                } else {

                }
            }
        }
        $data = array();
        for($y = 0; $y < count($names); $y++){
            $temp["name"] = $names[$y];
            $temp["new_name"] = $new_names[$y];
            $data[] = $temp;

        }
        echo json_encode($data);
    }

    public function resize_videos(){
        $row = $this->db->select('id, name')->from('sh_resize_videos')->where('resized', 'N')->get()->row();
        if($row){
            $uploaddir = './uploads/study_material/';
            $video[0] = $uploaddir;
            $u_data = array();
            $u_data['orig_name'] = $row->name;
            $u_data['file_path'] = $uploaddir;
            $u_data['raw_name'] = 'C'.$row->name;
            $video[1] = $u_data;
            $convertVdo = $this->convert_video($video);
            if($convertVdo == "done" && file_exists($uploaddir.'C'.$row->name)){
                unlink($uploaddir.$row->name);
                rename($uploaddir.'C'.$row->name, $uploaddir.$row->name);
                $this->db->set('resized', 'Y')->where('id', $row->id)->update('sh_resize_videos');
            }else{
                $this->db->set('resized', 'Y')->set('error', 'error')->where('id', $row->id)->update('sh_resize_videos');
            }
            
        }
        
    }


public function do_upload($fileName, $dirName) {
    if (is_array($_FILES) && isset($_FILES['image']['name'])) {
        $dir = './uploads/' . $dirName . '/' . "images";
        if (file_exists($dir) === false) {
            mkdir($dir, 0777, true);
        }
        $config['upload_path'] = $dir;
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '300';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['file_name'] = time();
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload($fileName)) {
            $error = array('error' => $this->upload->display_errors());
            print_r('Error in Uploading Images ' . $error);
        } else {
            $data = array('upload_data' => $this->upload->data());
            $imgData[] = array($data, $dir);
            $this->resizeImage($imgData);
            return $imgData;
        }
    } elseif (is_array($_FILES) && isset($_FILES['video']['name'])) {
        $dir = './uploads/' . $dirName . '/' . "videos";
        if (file_exists($dir) === false) {
            mkdir($dir, 0777, true);
        }
        $config['upload_path'] = $dir;
        $config['allowed_types'] = 'avi|flv|wmv|mpeg|mp3|mp4';
        $config['max_size'] = '5000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['file_name'] = time();
        $this->load->library('upload', $config);
        if (!$this->upload->do_upload($fileName)) {
            $error = array('error' => $this->upload->display_errors());
            print_r($error);
        } else {
            $data = $this->upload->data();
            $dat = array($dir, $data);
            $convertVdo = $this->convert_video($dat);
            var_dump($convertVdo);
            return $dat;
        }
    }
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
        $files_1 = $request->files;

        $subject_code = "";
        $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject)->get()->row();
        if($subject_row){
            $subject_code = $subject_row->code;
        }

        $files = "";
        
        if($files_1){
            $files = array();
            foreach ($files_1 as $value) {
                $files[] = $value->new_name;
            }
            $files = array_unique($files);
           
            $files=implode(",", $files);
        }
        
        $details = $request->text;
        $random_id = "__yasir".generateRandomString(10);
        if(strpos($details, 'target="_blank"') !== false){
            $details .= '<script>
                $(document).ready(function(){
                    $(".'.$random_id.'").magnificPopup({
                        type: "iframe",
                        mainClass: "mfp-fade",
                        removalDelay: 0,
                        preloader: false,
                        fixedContentPos: false
                    });
                });
            </script>';
            $details = str_replace('target="_blank"', 'class="'.$random_id.'"', $details);
            $details = str_replace('rel="nofollow"', '', $details);
        }
        
        if($request->uploaded_at  == ''){
            $uploaded_at = date("Y-m-d");
        }else{
            $uploaded_at = to_mysql_date($request->uploaded_at);
            if($uploaded_at == '0000-00-00'){
                $uploaded_at = date("Y-m-d");
            }
        }
        $uploaded_by = $this->session->userdata("userdata")["user_id"];

        if($batch_id == "all") $batch_id = 0;
        if($subject == "all") $subject = 0;
       
        $this->study_model->newMaterial($title, $content_type, $class_id, $batch_id, $subject, $files, $school_id, $details,$uploaded_by,$uploaded_at, $subject_code);
        $response['part'] = $this->study_model->getStudentsAndParents($class_id,$batch_id,$school_id);
        
        $response["message"] = lang('study_uploaded_msg');
        $response["sender"] = $this->session->userdata("userdata")["name"];

        echo json_encode($response);
    }


    public function newAssignment(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        //print_r($request);die();
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $type = $request->type;
        $title = $request->title;
        $date = $request->uploaded_at;
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject_id = $request->subject;
        $student_id = $request->students;
        $marks = $request->marks;
        $details = $request->details;
        $material_details = $request->material_details;
        // $files_1 = $request->files;
        $sort_files_1=array_unique($request->files,SORT_REGULAR);
        $files_1=array_values($sort_files_1);
        $content_type="Assignments";
        
        if($type=="Homework")
        {
            $content_type="Homework";
        }
        
        $second_parent_child="Class Activities";
        
        //google drive
        $storage_type=1;
        $third_parentfolderid = null;
        $sh_enable_gd = $this->session->userdata("userdata")["sh_enable_gd"];
        $credentialsfile='credentials_'.$school_id.'.json';
        if(file_exists($credentialsfile) && $sh_enable_gd==1)
        {
            $storage_type=2;
        }
        else
        {
            $storage_type=1;
        }
        
        
        if($storage_type==2)
        {
            $cre_file='credentials_'.$school_id.'.json';
            if(file_exists($cre_file))
            {
                $gd_tokenfile='token_'.$school_id.'.json';
                $client = getClient($school_id,$gd_tokenfile);

                if(file_exists($gd_tokenfile))
                {
                    $accessToken = json_decode(file_get_contents($gd_tokenfile), true);
                    $client->setAccessToken($accessToken);
                    $service = new Google_Service_Drive($client);
                    $newPermission = new Google_Service_Drive_Permission();
                    
                    $folder_name="School Data";
                    $folder_list = $this->check_folder_exists($folder_name,$client);
                    
                    $schl_id = $this->session->userdata("userdata")["sh_id"];
                    $folder_id = null;
                    $second_parentfolderid = null;
                    

                    // if folder does not exists
                    if( count( $folder_list ) == 0 )
                    {
                        //First Parent Folder
                        $upper_folder = new Google_Service_Drive_DriveFile(array('name' => $folder_name,'mimeType' => 'application/vnd.google-apps.folder'));
                        $result = $service->files->create( $upper_folder );
                        if( isset( $result['id'] ) && !empty( $result['id'] ) )
                        {
                            $folder_id = $result['id'];
                            $this->db->where('id', $schl_id);
                            $this->db->update('sh_school', array('parent_folderid' => $folder_id));
                        }
                    }
                    else
                    {
                        $folder_id=$folder_list[0]->id;
                        // $this->db->select('parent_folderid');
                        // $this->db->from('sh_school');
                        // $this->db->where('id',$schl_id);
                        // $que_res = $this->db->get()->row();
                        // $folder_id=$que_res->parent_folderid;
                    }
                    
                    //Second Parent Folder
                    $validate_sndparent_existence=$this->check_folder_exists($second_parent_child,$client);
                    
                    if(count($validate_sndparent_existence) == 0)
                    {
                        $folderMetadata = new Google_Service_Drive_DriveFile(array('name' => $second_parent_child,'mimeType' => 'application/vnd.google-apps.folder','parents' => array($folder_id)));
                        $res_scndfolder = $service->files->create($folderMetadata, array('fields' => 'id'));
                        $second_parentfolderid = $res_scndfolder->id;
                        
                        $this->db->where('id', $schl_id);
                        $this->db->update('sh_school', array('child_folderid' => $second_parentfolderid));
                    }
                    else
                    {
                        $second_parentfolderid=$validate_sndparent_existence[0]->id;
                    }
                    
                    
                    //Third parent folder
                    $validate_last_child=$this->check_folder_exists($content_type,$client,$second_parentfolderid);
                    if(count($validate_last_child) == 0)
                    {
                        $th_folderMetadata = new Google_Service_Drive_DriveFile(array('name' => $content_type,'mimeType' => 'application/vnd.google-apps.folder','parents' => array($second_parentfolderid)));
                        $res_thirdfolder = $service->files->create($th_folderMetadata, array('fields' => 'id'));
                        $third_parentfolderid = $res_thirdfolder->id;
                    }
                    else
                    {
                        $third_parentfolderid=$validate_last_child[0]->id;
                    }
                }
                else
                {
                    $response['uploaded']='false';
                    $response["message"] = "Please integrage google drive from settings!";
                    $response["sender"] = $this->session->userdata("userdata")["name"];
                    echo json_encode($response);
                    die;
                }
            }
            else
            {
                $response['uploaded']='false';
                $response["message"] = "Please contact your support team, for google drive account integration!";
                $response["sender"] = $this->session->userdata("userdata")["name"];
                echo json_encode($response);
                die;
            }
        }
        
        $files = array();
        // google drive files
        $all_fileurls = array();
        $allfileurls="";
        
        $all_thumbnail_links=array();
        $allthumbnail_links="";

        $origional_fnames=array();
        $all_fnames="";

        $fileIds=array();
        $all_fileIds="";


        $subject_code = "";
        $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
        if($subject_row){
            $subject_code = $subject_row->code;
        }

        $batches = implode(',', $batch_id);
        $student_ids = implode(',', $student_id);

        $files = "";
        
        if($files_1)
        {
            $files = array();
            foreach ($files_1 as $value)
            {
                
                $files[] = $value->new_name;
                
                if($storage_type==1)
                {
                    $all_fileurls[] = base_url().'uploads/study_material/'.$value->new_name;
                    $origional_fnames[]=$value->name;
                }
                else if($storage_type==2)
                {
                    /////////////////////////////////////////////////////
                    $file = new Google_Service_Drive_DriveFile();
                    $file->setName($value->new_name); 
                    $file->setParents(array($third_parentfolderid));
                    $newnames=explode('.',$value->new_name);
                    $newnames=$newnames[count($newnames)-2];

                    $file_path = base_url().'uploads/study_material/'.$value->new_name;

                    $arrContextOptions=array(
                        "ssl"=>array(
                            "verify_peer"=>false,
                            "verify_peer_name"=>false,
                        ),
                    );
                
                    $gdresult = $service->files->create(
                        $file,
                        array(
                            'data' => file_get_contents($file_path,false, stream_context_create($arrContextOptions)),
                            'mimeType' => 'application/octet-stream',
                        )
                    );

                    $fileIds[]=$gdresult->id;

                    //set email of account, that will have access to file.
                    // $newPermission->setEmailAddress('test@gmail.com');

                    //Must be user or group, if you pass email adress
                    // user | group | domain | anyone
                    $newPermission->setType('anyone');
                    $newPermission->setRole('reader');
                    $service->permissions->create($gdresult->id, $newPermission);
                    

                    $sfile = $service->files->get($gdresult->id,array('fields' => 'webViewLink, hasThumbnail, thumbnailLink, iconLink'));
                    
                    $all_fileurls[]=$sfile->webViewLink;
                    
                    $al='https://drive.google.com/thumbnail?authuser=0&sz=w320&id='.$gdresult->id;
                   
                    $all_thumbnail_links[]=$al;

                    $origional_fnames[]=$value->name;
                    /////////////////////////////////////////////////////
                }
                
            }
            
            $all_fileurls=array_unique($all_fileurls);
            $allfileurls=implode(',',$all_fileurls);

            $all_thumbnail_links=array_unique($all_thumbnail_links);
            $allthumbnail_links=implode(',',$all_thumbnail_links);


            $origional_fnames=array_unique($origional_fnames);
            $all_fnames=implode(',',$origional_fnames);


            $fileIds=array_unique($fileIds);
            $all_fileIds=implode(',',$fileIds);
            
            $files = array_unique($files);
            $files=implode(",", $files);
        }

        if($request->uploaded_at  == ''){
            $uploaded_at = date("Y-m-d");
        }else{
            $uploaded_at = to_mysql_date($request->uploaded_at);
            if($uploaded_at == '0000-00-00'){
                $uploaded_at = date("Y-m-d");
            }
        }
        $uploaded_by = $this->session->userdata("userdata")["user_id"];
        $due_date = to_mysql_date($request->due_date);
        
        $this->study_model->newAssignment($title, $type, $class_id, $batches, $subject_id, $storage_type, $files, $allfileurls, $allthumbnail_links, $all_fnames, $all_fileIds, $school_id, $details, $material_details,$uploaded_by,$uploaded_at, $subject_code, $marks, $due_date, $student_ids);


        // $response['part'] = $this->study_model->getParentsMultiple($student_ids);
        // array_push($response['part'], 0);
        $response['part'] = explode(',', $student_ids);
        array_merge($response['part'],$student_id);
        
        
        $response['uploaded']='true';
        $response["message"] = lang('study_uploaded_msg');
        $response["sender"] = $this->session->userdata("userdata")["name"];
        echo json_encode($response);
        

    }

    
    function check_folder_exists($folder_name,$client,$parent=FALSE)
    {
        $sch_id=$this->session->userdata("userdata");
        $sch_id=$sch_id['sh_id'];
        
        $service = new Google_Service_Drive($client);
    
        if($parent!='')
        {
            $parameters['q'] = "mimeType='application/vnd.google-apps.folder' and name='$folder_name' and parents='$parent' and trashed=false";
            $files = $service->files->listFiles($parameters);
        
            $op = [];
            foreach( $files as $k => $file ){
                $op[] = $file;
            }
        
            return $op;
        }
        else
        {
            $parameters['q'] = "mimeType='application/vnd.google-apps.folder' and name='$folder_name' and trashed=false";
            $files = $service->files->listFiles($parameters);
        
            $op = [];
            foreach( $files as $k => $file ){
                $op[] = $file;
            }
        
            return $op;
        }
        
    }


    // add by zafar for improvements by Haseeb for google drive 26/11/2020
    public function newMaterial_general() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $title = $request->title;
        $content_type = $request->type;
        $class_id = $request->class;
        $batch_id = $request->section;
        $storage_type=1;

        //google drive
        $sh_enable_gd = $this->session->userdata("userdata")["sh_enable_gd"];
        $credentialsfile='credentials_'.$school_id.'.json';
        if(file_exists($credentialsfile) && $sh_enable_gd==1)
        {
            $storage_type=2;
        }
        else
        {
            $storage_type=1;
        }
        // $storage_type = $request->storage_type;
        $subject = $request->subject;
        $files_1 = $request->files;
        $sort_files_1=array_unique($request->files,SORT_REGULAR);
        $files_1=array_values($sort_files_1);

       
        $subject_code = "";
        $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject)->get()->row();
        if($subject_row){
            $subject_code = $subject_row->code;
        }

        $files = "";
        // google drive files
        $all_fileurls = array();
        $allfileurls="";
        
        $all_thumbnail_links=array();
        $allthumbnail_links="";
        
        $all_icon_links=array();
        $allicon_links="";

        $origional_fnames=array();
        $all_fnames="";

        $fileIds=array();
        $all_fileIds="";
        
        if($files_1){
            $files = array();
            foreach ($files_1 as $value)
            {
                $files[] = $value->new_name;

                if($storage_type==1)
                {
                    $all_fileurls[] = base_url().'uploads/study_material/'.$value->new_name;
                    $origional_fnames[]=$value->name;
                    
                    
                    // $fsource_path = './uploads/study_material/'.$value->new_name;
                    // $ftarget_path = './uploads/study_material/thumbnails/';
                     
                    // $config['image_library'] = 'gd2';
                    // $config['source_image'] = $fsource_path;
                    // $config['new_image'] = $ftarget_path;
                    // $width = 150;
                    // $height = 150;
                    // $config['create_thumb'] = TRUE;
                    // $config['maintain_ratio'] = TRUE;
                    // $config['width'] = $width;
                    // $config['height'] = $height;
                    // $config['quality'] = '100%';  
                    // $this->image_lib->initialize($config);
                    // $this->image_lib->resize();
                  
                    // if ($this->image_lib->resize())
                    // {
                    //     $thumb_file_link=base_url().'uploads/study_material/thumbnails/'.$value->new_name;
                    //     $all_thumbnail_links[]=$thumb_file_link;
                    // }
                    // else
                    // { 
                    //     $response['uploaded']='false';
                    //     $response["message"] = "Something went wrong, please try again!";
                    //     $response["sender"] = $this->session->userdata("userdata")["name"];
                    //     echo json_encode($response);
                    //     die; 
                    // }
                    
                }
                else if($storage_type==2)
                {
                    $sch_id=$this->session->userdata("userdata");
                    $sch_id=$sch_id['sh_id'];


                    $cre_file='credentials_'.$sch_id.'.json';
                    if(file_exists($cre_file))
                    {
                        $gd_tokenfile='token_'.$sch_id.'.json';
                        $client = getClient($sch_id,$gd_tokenfile);
    
                        if(file_exists($gd_tokenfile))
                        {
                            $accessToken = json_decode(file_get_contents($gd_tokenfile), true);
                            $client->setAccessToken($accessToken);
                            $service = new Google_Service_Drive($client);
                            $newPermission = new Google_Service_Drive_Permission();
                            
                            
                            $folder_name="School Data";
                            $folder_list = $this->check_folder_exists($folder_name,$client);
                            
                            $schl_id = $this->session->userdata("userdata")["sh_id"];
                            $folder_id = null;
                            $second_parentfolderid = null;

                            // if folder does not exists
                            if( count( $folder_list ) == 0 )
                            {
                                //First Parent Folder
                                $upper_folder = new Google_Service_Drive_DriveFile(array('name' => $folder_name,'mimeType' => 'application/vnd.google-apps.folder'));
                                $result = $service->files->create( $upper_folder );
                                
                                
                                if( isset( $result['id'] ) && !empty( $result['id'] ) )
                                {
                                    $folder_id = $result['id'];
                                    
                                    
                                    $this->db->where('id', $schl_id);
                                    $this->db->update('sh_school', array('parent_folderid' => $folder_id));
                                    
                                }
                                
                            }
                            else
                            {
                                $this->db->select('parent_folderid');
                                $this->db->from('sh_school');
                                $this->db->where('id',$schl_id);
                                $que_res = $this->db->get()->row();
                                $folder_id=$que_res->parent_folderid;
                            }
                            
                            
                            //Second Parent Folder
                            $validate_sndparent_existence=$this->check_folder_exists($content_type,$client);
                            
                            if(count($validate_sndparent_existence) == 0)
                            {
                                $folderMetadata = new Google_Service_Drive_DriveFile(array('name' => $content_type,'mimeType' => 'application/vnd.google-apps.folder','parents' => array($folder_id)));
                                $res_scndfolder = $service->files->create($folderMetadata, array('fields' => 'id'));
                                $second_parentfolderid = $res_scndfolder->id;
                                
                                $this->db->where('id', $schl_id);
                                $this->db->update('sh_school', array('child_folderid' => $second_parentfolderid));
                            }
                            else
                            {
                                $second_parentfolderid=$validate_sndparent_existence[0]->id;
                                // $this->db->select('child_folderid');
                                // $this->db->from('sh_school');
                                // $this->db->where('id',$schl_id);
                                // $que_res = $this->db->get()->row();
                                // $second_parentfolderid=$que_res->child_folderid;
                            }
                            
                            
                            $file = new Google_Service_Drive_DriveFile();
                            $file->setName($value->new_name); 
                            $file->setParents(array($second_parentfolderid));
                            $newnames=explode('.',$value->new_name);
                            $newnames=$newnames[count($newnames)-2];
    
                            $file_path = base_url().'uploads/study_material/'.$value->new_name;
    
                            $arrContextOptions=array(
                                "ssl"=>array(
                                    "verify_peer"=>false,
                                    "verify_peer_name"=>false,
                                ),
                            );
                        
                            $gdresult = $service->files->create(
                                $file,
                                array(
                                    'data' => file_get_contents($file_path,false, stream_context_create($arrContextOptions)),
                                    'mimeType' => 'application/octet-stream',
                                )
                            );
    
                            $fileIds[]=$gdresult->id;
    
                            //set email of account, that will have access to file.
                            // $newPermission->setEmailAddress('test@gmail.com');
    
                            //Must be user or group, if you pass email adress
                            // user | group | domain | anyone
                            $newPermission->setType('anyone');
                            $newPermission->setRole('reader');
                            $service->permissions->create($gdresult->id, $newPermission);
                            
    
                            $sfile = $service->files->get($gdresult->id,array('fields' => 'webViewLink, hasThumbnail, thumbnailLink, iconLink'));
                            
                            $all_fileurls[]=$sfile->webViewLink;
                            
                            $al='https://drive.google.com/thumbnail?authuser=0&sz=w320&id='.$gdresult->id;
                           
                            $all_thumbnail_links[]=$al;
                            
                            $all_icon_links[]=$sfile->iconLink;
    
                            $origional_fnames[]=$value->name;
                            
                        
                           
    
                        }
                        else
                        {
                            $response['uploaded']='false';
                            $response["message"] = "Please integrage google drive from settings!";
                            $response["sender"] = $this->session->userdata("userdata")["name"];
                            echo json_encode($response);
                            die;
                        }
                    }
                    else
                    {
                        $response['uploaded']='false';
                        $response["message"] = "Please contact your support team, for google drive account integration!";
                        $response["sender"] = $this->session->userdata("userdata")["name"];
                
                        echo json_encode($response);
                        die;
                    }

                }
            }

            $all_fileurls=array_unique($all_fileurls);
            $allfileurls=implode(',',$all_fileurls);

            $all_thumbnail_links=array_unique($all_thumbnail_links);
            $allthumbnail_links=implode(',',$all_thumbnail_links);
            
            $all_icon_links=array_unique($all_icon_links);
            $allicon_links=implode(',',$all_icon_links);


            $origional_fnames=array_unique($origional_fnames);
            $all_fnames=implode(',',$origional_fnames);


            $fileIds=array_unique($fileIds);
            $all_fileIds=implode(',',$fileIds);

            $files = array_unique($files);
            // foreach($files as $f){
            //     if (strpos($f, '.avi') !== false || strpos($f, '.flv') !== false || strpos($f, '.wmv') !== false || strpos($f, '.mov') !== false || strpos($f, '.mp4') !== false) {
            //         $f_data = array("name" => $f, "school_id" => $school_id);
            //         $this->db->insert('sh_resize_videos', $f_data);
            //     }
            // }
            $files=implode(",", $files);
        }
        
        $details = $request->text;
        $random_id = "__yasir".generateRandomString(10);
        if(strpos($details, 'target="_blank"') !== false){
            $details .= '<script>
                $(document).ready(function(){
                    $(".'.$random_id.'").magnificPopup({
                        type: "iframe",
                        mainClass: "mfp-fade",
                        removalDelay: 0,
                        preloader: false,
                        fixedContentPos: false
                    });
                });
            </script>';
            $details = str_replace('target="_blank"', 'class="'.$random_id.'"', $details);
            $details = str_replace('rel="nofollow"', '', $details);
        }
        
        if($request->uploaded_at  == ''){
            $uploaded_at = date("Y-m-d");
        }else{
            $uploaded_at = to_mysql_date($request->uploaded_at);
            if($uploaded_at == '0000-00-00'){
                $uploaded_at = date("Y-m-d");
            }
        }
        $uploaded_by = $this->session->userdata("userdata")["user_id"];

        if($batch_id=='all' && $subject == 'all')
        {
            
                $batch_idss = $this->db->select("id")->from('sh_batches')->where('class_id' , $class_id)->get()->result();
                foreach($batch_idss as $b)
                {
                    $b_id[] = $b->id;
                    $subject_ids = $this->db->select("id,code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $b->id)->get()->result();
                    foreach($subject_ids as $s)
                {
                    $s_code[] = $s->code;
                    $s_ids[] = $s->id;
                } 
                }
                
                $subject_code = implode(',' , $s_code);
                $subject = implode(',' , $s_ids);
                $batch_id = implode(',' , $b_id);

                $this->study_model->newMaterial($title, $content_type, $class_id, $batch_id, $subject, $storage_type, $files, $all_fnames, $allfileurls, $all_fileIds, $allthumbnail_links, $allicon_links, $school_id, $details,$uploaded_by,$uploaded_at, $subject_code );
                $response['part'] = $this->study_model->getStudentsAndParents($class_id,$batch_id,$school_id);
          
                $response['uploaded']='true';
                $response["message"] = lang('study_uploaded_msg');
                $response["sender"] = $this->session->userdata("userdata")["name"];
        }
        else if($batch_id=='all' && $subject != 'all')
        {
                $batch_idss = $this->db->select("id")->from('sh_batches')->where('class_id' , $class_id)->get()->result();
                foreach($batch_idss as $b)
                {
                    $b_ids[] = $b->id;
                    $s_c = $this->db->select("code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $b->id)->where('id' , $subject)->get()->result();
                    foreach($s_c as $cc)
                    {
                        $c_s = $cc->code;
                    }
                    $subject_idss = $this->db->select("id")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $b->id)->where('code' , $c_s)->get()->result();
                    foreach($subject_idss as $s)
                    {
                        $s_ids[] = $s->id;
                    } 
            

                    $subject_code = $c_s;
                   $subject = implode(',' , $s_ids);
                }
                $batch_id = implode(',' , $b_ids);
                $this->study_model->newMaterial($title, $content_type, $class_id, $batch_id, $subject, $storage_type, $files, $all_fnames, $allfileurls, $all_fileIds, $allthumbnail_links, $allicon_links, $school_id, $details,$uploaded_by,$uploaded_at, $subject_code);
                $response['part'] = $this->study_model->getStudentsAndParents($class_id,$batch_id,$school_id);
        
                $response['uploaded']='true';
                $response["message"] = lang('study_uploaded_msg');
                $response["sender"] = $this->session->userdata("userdata")["name"];
        }

        else if($batch_id!='all' && $subject == 'all')
        {   
                $subject_codes = $this->db->select("id,code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $batch_id)->get()->result();
                foreach($subject_codes as $s)
                {
                    $s_code[] = $s->code;
                    $s_ids[] = $s->id;
                } 
                $subject_code = implode(',' , $s_code);
                $subject = implode(',' , $s_ids);
                $this->study_model->newMaterial($title, $content_type, $class_id, $batch_id, $subject, $storage_type, $files, $all_fnames, $allfileurls, $all_fileIds, $allthumbnail_links, $allicon_links, $school_id, $details,$uploaded_by,$uploaded_at, $subject_code);
                $response['part'] = $this->study_model->getStudentsAndParents($class_id,$batch_id,$school_id);
         
                $response['uploaded']='true';
                $response["message"] = lang('study_uploaded_msg');
                $response["sender"] = $this->session->userdata("userdata")["name"];
        }
        else
        {
            $this->study_model->newMaterial($title, $content_type, $class_id, $batch_id, $subject, $storage_type, $files, $all_fnames, $allfileurls, $all_fileIds, $allthumbnail_links, $allicon_links, $school_id, $details,$uploaded_by,$uploaded_at, $subject_code);
            $response['part'] = $this->study_model->getStudentsAndParents($class_id,$batch_id,$school_id);
      
            $response['uploaded']='true';
            $response["message"] = lang('study_uploaded_msg');
            $response["sender"] = $this->session->userdata("userdata")["name"];
        }
        
    
       
        

        echo json_encode($response);
    }

    public function zip() {
        
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $files = $request->files;
        $slug = url_title($request->title);
        $id = $request->id;
        $file_name = $slug . '_' . $id . ".zip";


        // if (file_exists(FCPATH . "uploads/zip_files/" . $file_name)) {

            
        // } else {

        //     foreach ($files as $file) {
        //         $this->zip->read_file(FCPATH . 'uploads/study_material/' . $file);
        //     }

        //     $this->zip->archive(FCPATH . 'uploads/zip_files/' . $file_name);
        // }

        // $response["path"] = base_url() . "uploads/zip_files/" . $file_name;
        $paths = array();

        foreach ($files as $file) {
            $paths[$file] = base_url() . "uploads/study_material/" . $file;
        }
        $response["paths"] = $paths;
        echo json_encode($response);
    }

    public function zip_parent() {
        
        $postdata = file_get_contents("php://input");
        
        $request = json_decode($postdata);

        $files = $request->files;
        $slug = url_title($request->title);
        $id = $request->id;
       
        $file_name = $slug . '_' . $id . ".zip";


        // if (file_exists(FCPATH . "uploads/zip_files/" . $file_name)) {

            
        // } else {

        //     foreach ($files as $file) {
        //         $this->zip->read_file(FCPATH . 'uploads/study_material/' . $file);
        //     }

        //     $this->zip->archive(FCPATH . 'uploads/zip_files/' . $file_name);
        // }

        // $response["path"] = base_url() . "uploads/zip_files/" . $file_name;
        $paths = array();

        foreach ($files as $file) {
            $paths[$file] = base_url() . "uploads/study_material/" . $file;
        }
        $response["paths"] = $paths;
        echo json_encode($response);
    }

    public function updateAssignmnet() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $total_marks = $request->marks;
        $id = $request->editId;
        $editrowid=$id;
        $title = $request->title;
        $content_type = $request->type;
        $class_id = $request->class;
        $batch_ids = implode(",", $request->section);
        $student_ids = implode(",", $request->students);
        $subject = $request->subject;
        $material_details = $request->material_details;
        $details = $request->details;
        $due_date = to_mysql_date($request->due_date);
        $published_date = to_mysql_date($request->uploaded_at);
        
        $finalcontent_type="Assignments";
        
        if($content_type=='Homework')
        {
            $finalcontent_type="Homework";
        }
        
        $second_parent_child="Class Activities";
        $third_parentfolderid = null;
        
        $result=$this->study_model->get_AssigdeletedfilesId($id);

        if($result->storage_type==1)
        {
            ////////////////////////////
            $files_1 = $request->old_files;
            $sort_files_2=array_unique($request->files,SORT_REGULAR);
            $files_2=array_values($sort_files_2);
            // $files_2 = $request->files;
    
            $files_1 = array_merge($files_1,$files_2);
    
            $files = "";
    
            if($files_1){
                $files = array();
                foreach ($files_1 as $value) {
                    $files[] = $value->new_name;
                }
                $files = array_unique($files);
                $files=implode(",", $files);
            }
            
            $data = array(
                'title' => $title,
                'content_type' => $content_type,
                'class_id' => $class_id,
                'batch_ids' => $batch_ids,
                'subject_id' => $subject,
                'details' => $details,
                'published_date' => $published_date,
                'due_date' => $due_date,
                'student_ids' => $student_ids,
                'total_marks' => $total_marks,
                'material_details' => $material_details,
                'files' => $files
            );
            
            $this->study_model->updateAssignmnet($id, $data);
            $response['updated'] = true;
            $response['message'] = lang('study_updated_msg');
            echo json_encode($response); 
            ////////////////////////////
        }
        else if($result->storage_type==2)
        {
            $files_1 = $request->old_files;
            $sort_files_2=array_unique($request->files,SORT_REGULAR);
            $files_2=array_values($sort_files_2);
            
            $allfiles_on=array_merge($files_1,$files_2);
            
            $old_dbfiles=explode(',',$result->files);
            $old_gdfileids=explode(',',$result->fileids);

            $gd_deleteids=array();


            $exp_dbfileids=explode(',',$result->fileids);
            $exp_dbfiles_names=explode(',',$result->files);
            $exp_dborg_fnames=explode(',',$result->file_names);
            $exp_dball_fileurls=explode(',',$result->filesurl);
            $exp_dball_filethumbs=explode(',',$result->thumbnail_links);


            $fileids=array();
            $all_fileurls=array();
            $origional_fnames=array();
            $files_newnames=array();
            $all_thumbnail_links=array();

            $allthumbnail_links="";
            $newfile_stringids="";
            $newfile_stringurls="";
            $newfile_string_orgnames="";
            $newfile_string_newnames="";

            $sch_id=$this->session->userdata("userdata");
            $sch_id=$sch_id['sh_id'];
            $gd_tokenfile='token_'.$sch_id.'.json';
            $client = getClient($sch_id,$gd_tokenfile);
            
            
            if(file_exists($gd_tokenfile))
            {
                $accessToken = json_decode(file_get_contents($gd_tokenfile), true);
                $client->setAccessToken($accessToken);
                $service = new Google_Service_Drive($client);
                $newPermission = new Google_Service_Drive_Permission();
                $new_uploadedfile = new Google_Service_Drive_DriveFile();
                
                if(sizeof($files_1) > 0)
                {
                    // foreach($old_dbfiles as $key => $oldfb_file)
                    // {
                            foreach($files_1 as $skey => $file_1)
                            {
                                foreach($old_dbfiles as $key => $oldfb_file)
                                {
                                    if($oldfb_file!=$files_1[$skey]->name)
                                    {
                                        $gd_deleteids[]=$old_gdfileids[$key];
                                    }
                                    elseif($oldfb_file==$files_1[$skey]->name)
                                    {
                                        // $fileids[]=$exp_dbfileids[$key];
                                        // $files_newnames[]=$exp_dbfiles_names[$key];
                                        // $origional_fnames[]=$exp_dborg_fnames[$key];
                                        // $all_fileurls[]=$exp_dball_fileurls[$key];
                                        // $all_thumbnail_links[]=$exp_dball_filethumbs[$key];
                                    }

                                }
                            }

                    // }
                }
                else
                {
                    $gd_deleteids=$old_gdfileids;
                }
                
                $gd_deleteids=array_unique($gd_deleteids,SORT_REGULAR);
                $gd_deleteids=array_values($gd_deleteids);
                if(sizeof($gd_deleteids) > 0)
                {
                    foreach($gd_deleteids as $key => $gd_deleteid)
                    {
                        if(isset($gd_deleteid[$key]))
                        {
                            $service->files->delete($gd_deleteid);
                        }
                    }
                }
                
                
                $folder_name="School Data";
                $folder_list = $this->check_folder_exists($folder_name,$client);
                
                $schl_id = $this->session->userdata("userdata")["sh_id"];
                $folder_id = null;
                $second_parentfolderid = null;
                

                // if folder does not exists
                if( count( $folder_list ) == 0 )
                {
                    //First Parent Folder
                    $upper_folder = new Google_Service_Drive_DriveFile(array('name' => $folder_name,'mimeType' => 'application/vnd.google-apps.folder'));
                    $result = $service->files->create( $upper_folder );
                    if( isset( $result['id'] ) && !empty( $result['id'] ) )
                    {
                        $folder_id = $result['id'];
                        $this->db->where('id', $schl_id);
                        $this->db->update('sh_school', array('parent_folderid' => $folder_id));
                    }
                }
                else
                {
                    $folder_id=$folder_list[0]->id;
                    // $this->db->select('parent_folderid');
                    // $this->db->from('sh_school');
                    // $this->db->where('id',$schl_id);
                    // $que_res = $this->db->get()->row();
                    // $folder_id=$que_res->parent_folderid;
                }
                
                
                //Second Parent Folder
                $validate_sndparent_existence=$this->check_folder_exists($second_parent_child,$client);
                
                if(count($validate_sndparent_existence) == 0)
                {
                    $folderMetadata = new Google_Service_Drive_DriveFile(array('name' => $second_parent_child,'mimeType' => 'application/vnd.google-apps.folder','parents' => array($folder_id)));
                    $res_scndfolder = $service->files->create($folderMetadata, array('fields' => 'id'));
                    $second_parentfolderid = $res_scndfolder->id;
                    
                    $this->db->where('id', $schl_id);
                    $this->db->update('sh_school', array('child_folderid' => $second_parentfolderid));
                }
                else
                {
                    $second_parentfolderid=$validate_sndparent_existence[0]->id;
                }
                
                
                //Third parent folder
                $validate_last_child=$this->check_folder_exists($finalcontent_type,$client,$second_parentfolderid);
                if(count($validate_last_child) == 0)
                {
                    $th_folderMetadata = new Google_Service_Drive_DriveFile(array('name' => $finalcontent_type,'mimeType' => 'application/vnd.google-apps.folder','parents' => array($second_parentfolderid)));
                    $res_thirdfolder = $service->files->create($th_folderMetadata, array('fields' => 'id'));
                    $third_parentfolderid = $res_thirdfolder->id;
                }
                else
                {
                    $third_parentfolderid=$validate_last_child[0]->id;
                }
                
                
                ///////////////////////////////////////
                if(sizeof($allfiles_on)>0)
                {
                    foreach($allfiles_on as $new_gdfiles)
                    {
                        $new_uploadedfile->setName($new_gdfiles->new_name);
                        $new_uploadedfile->setParents(array($third_parentfolderid));

                        $file_path = base_url().'uploads/study_material/'.$new_gdfiles->new_name;

                        $arrContextOptions=array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        );
                    
                        $gdresult = $service->files->create(
                            $new_uploadedfile,
                            array(
                                'data' => file_get_contents($file_path,false, stream_context_create($arrContextOptions)),
                                'mimeType' => 'application/octet-stream',
                            )
                        );

                        $fileids[]=$gdresult->id;


                        $al='https://drive.google.com/thumbnail?authuser=0&sz=w320&id='.$gdresult->id;
                           
                        $all_thumbnail_links[]=$al;
                        

                        //set email of account, that will have access to file.
                        // $newPermission->setEmailAddress('test@gmail.com');

                        //Must be user or group, if you pass email adress
                        // user | group | domain | anyone
                        $newPermission->setType('anyone');
                        $newPermission->setRole('reader');
                        $service->permissions->create($gdresult->id, $newPermission);
                        

                        $sfile = $service->files->get($gdresult->id,array('fields' => 'webViewLink'));

                        $all_fileurls[]=$sfile->webViewLink;

                        $origional_fnames[]=$new_gdfiles->name;

                        $files_newnames[]=$new_gdfiles->new_name;


                    }

                }
                
                
                $newfile_string_newnames=implode(',',$files_newnames);

                $newfile_stringids=implode(',',$fileids);

                $newfile_stringurls=implode(',',$all_fileurls);

                $newfile_string_orgnames=implode(',',$origional_fnames);

                $allthumbnail_links=implode(',',$all_thumbnail_links);
                

                $data = array(
                    'title' => $title,
                    'content_type' => $content_type,
                    'class_id' => $class_id,
                    'batch_ids' => $batch_ids,
                    'subject_id' => $subject,
                    'details' => $details,
                    'published_date' => $published_date,
                    'due_date' => $due_date,
                    'student_ids' => $student_ids,
                    'total_marks' => $total_marks,
                    'material_details' => $material_details,
                    'files' => $newfile_string_newnames,
                    'fileids' => $newfile_stringids,
                    'filesurl' => $newfile_stringurls,
                    'file_names' => $newfile_string_orgnames,
                    'thumbnail_links' => $allthumbnail_links
                );
                
                $this->study_model->updateAssignmnet($id, $data);
                $response['updated'] = true;
                $response['message'] = lang('study_updated_msg');
                echo json_encode($response);
                ///////////////////////////////////////
                
            }
            else
            {
                
            }
            
        }




        
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

        $details = $request->text;
        $random_id = "__yasir".generateRandomString(10);
        if(strpos($details, 'target="_blank"') !== false){
            $details .= '<script>
                $(document).ready(function(){
                    $(".'.$random_id.'").magnificPopup({
                        type: "iframe",
                        mainClass: "mfp-fade",
                        removalDelay: 0,
                        preloader: false,
                        fixedContentPos: false
                    });
                });
            </script>';
            $details = str_replace('target="_blank"', 'class="'.$random_id.'"', $details);
            $details = str_replace('rel="nofollow"', '', $details);
        }

        $files_1 = $request->old_files;
        $files_2 = $request->files;

        $files_1 = array_merge($files_1,$files_2);

        $files = "";

        if($files_1){
            $files = array();
            foreach ($files_1 as $value) {
                $files[] = $value->new_name;
            }
            $files = array_unique($files);
            $files=implode(",", $files);
        }
        
        $data = array(
            'title' => $title,
            'content_type' => $content_type,
            'class_id' => $class_id,
            'batch_id' => $batch_id,
            'subject_id' => $subject,
            'details' => $details,
            'files' => $files
        );


        $this->study_model->updateMaterial($id, $data);
        $response['updated'] = true;
        $response['message'] = lang('study_updated_msg');


        echo json_encode($response);
    }
    
    public function updateMaterial_general() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $id = $request->editId;
        //google drive edit 
        $editrowid=$id;
        $title = $request->title;
        $content_type = $request->type;
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject = $request->subject;

        $details = $request->text;
        $random_id = "__yasir".generateRandomString(10);
        if(strpos($details, 'target="_blank"') !== false){
            $details .= '<script>
                $(document).ready(function(){
                    $(".'.$random_id.'").magnificPopup({
                        type: "iframe",
                        mainClass: "mfp-fade",
                        removalDelay: 0,
                        preloader: false,
                        fixedContentPos: false
                    });
                });
            </script>';
            $details = str_replace('target="_blank"', 'class="'.$random_id.'"', $details);
            $details = str_replace('rel="nofollow"', '', $details);
        }


        $result=$this->study_model->getdeletedfilesId($id);

        if($result->storage_type==1)
        {
            $files_1 = $request->old_files;
            $sort_files_2=array_unique($request->files,SORT_REGULAR);
            $files_2=array_values($sort_files_2);
            // $files_2 = $request->files;
            
            

            $files_1 = array_merge($files_1,$files_2);
    
            $files = "";
    
            if($files_1)
            {
                $files = array();
                foreach ($files_1 as $value) {
                    $files[] = $value->new_name;
                }
                $files = array_unique($files);
                $files=implode(",", $files);
            }

            if($batch_id == 'all' && $subject == 'all')
            {
                
                    $batch_idss = $this->db->select("id")->from('sh_batches')->where('class_id' , $class_id)->get()->result();
                    foreach($batch_idss as $b)
                    {
                        $b_id[] = $b->id;
                        $this->db->distinct();
                        $subject_codes = $this->db->select("id,code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $b->id)->get()->result();
                        foreach($subject_codes as $s)
                        {
                            $s_code[] = $s->code;
                            $s_ids[] = $s->id;
                        } 
                    }
                    
                    $subject_code = implode(',' , $s_code);
                    $subject = implode(',' , $s_ids);
                    $batch_id = implode(',' , $b_id);
                    $data = array(
                        'title' => $title,
                        'content_type' => $content_type,
                        'class_id' => $class_id,
                        'batch_id' => $batch_id,
                        'subject_id' => $subject,
                        'subject_code' => $subject_code,
                        'details' => $details,
                        'files' => $files
                    );
            
            }
            else if($batch_id == 'all' && $subject != 'all')
            {
                $batch_idss = $this->db->select("id")->from('sh_batches')->where('class_id' , $class_id)->get()->result();
                foreach($batch_idss as $b)
                {
                    $b_ids[] = $b->id;

                    $subject_codes = $this->db->select("code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $b->id)->where('id', $subject)->get()->result();
                    foreach($subject_codes as $code)
                    {
                        $subject_code = $code->code;
                    }
                }
                
                $batch_id = implode(',' , $b_ids);
                $data = array(
                    'title' => $title,
                    'content_type' => $content_type,
                    'class_id' => $class_id,
                    'batch_id' => $batch_id,
                    'subject_id' => $subject,
                    'subject_code' => $subject_code,
                    'details' => $details,
                    'files' => $files
                );
        
            }
                
            else if($batch_id != 'all' && $subject == 'all')
            {
                
                $this->db->distinct();
                $subject_codes = $this->db->select("id,code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $batch_id)->get()->result();
                foreach($subject_codes as $s)
                {
                    $s_code[] = $s->code;
                    $s_ids[] = $s->id;
                } 
                $subject_code = implode(',' , $s_code);
                   $subject = implode(',' , $s_ids);
                   $data = array(
                    'title' => $title,
                    'content_type' => $content_type,
                    'class_id' => $class_id,
                    'batch_id' => $batch_id,
                    'subject_id' => $subject,
                    'subject_code' => $subject_code,
                    'details' => $details,
                    'files' => $files
                );
            }

            else
            {
                $subject_codes = $this->db->select("code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $batch_id)->where('id', $subject)->get()->result();
                foreach($subject_codes as $code)
                {
                    $subject_code = $code->code;
                }
                    $data = array(
                        'title' => $title,
                        'content_type' => $content_type,
                        'class_id' => $class_id,
                        'batch_id' => $batch_id,
                        'subject_id' => $subject,
                        'subject_code' => $subject_code,
                        'details' => $details,
                        'files' => $files
                    );
            
            }
          

            $this->study_model->updateMaterial($id, $data);
            $response['updated'] = true;
            $response['message'] = lang('study_updated_msg');


            echo json_encode($response);
            die;
            
            // $data = array(
            //     'title' => $title,
            //     'content_type' => $content_type,
            //     'class_id' => $class_id,
            //     'batch_id' => $batch_id,
            //     'subject_id' => $subject,
            //     'subject_code' => $subject_code,
            //     'details' => $details,
            //     'files' => $files
            // );
    
    
            // $this->study_model->updateMaterial($id, $data);
            // $response['updated'] = true;
            // $response['message'] = lang('study_updated_msg');
            // echo json_encode($response);
            // die;
        }
        else if($result->storage_type==2)
        {
            
            $files_1 = $request->old_files;
            $sort_files_2=array_unique($request->files,SORT_REGULAR);
            $files_2=array_values($sort_files_2);
            // $files_2 = $request->files;


            $allfiles_on=array_merge($files_1,$files_2);

            // echo '<pre>';
            //     print_r($files_1);
            //     print_r($files_2);

            //     print_r($allfiles_on);


            $old_dbfiles=explode(',',$result->files);

            $old_gdfileids=explode(',',$result->fileids);

            $gd_deleteids=array();


            $exp_dbfileids=explode(',',$result->fileids);
            $exp_dbfiles_names=explode(',',$result->files);
            $exp_dborg_fnames=explode(',',$result->file_names);
            $exp_dball_fileurls=explode(',',$result->filesurl);
            $exp_dball_filethumbs=explode(',',$result->thumbnail_links);



            $fileids=array();
            $all_fileurls=array();
            $origional_fnames=array();
            $files_newnames=array();
            $all_thumbnail_links=array();

            $allthumbnail_links="";
            $newfile_stringids="";
            $newfile_stringurls="";
            $newfile_string_orgnames="";
            $newfile_string_newnames="";

            $sch_id=$this->session->userdata("userdata");
            $sch_id=$sch_id['sh_id'];
            $gd_tokenfile='token_'.$sch_id.'.json';
            $client = getClient($sch_id,$gd_tokenfile);

            if(file_exists($gd_tokenfile))
            {
                
                $accessToken = json_decode(file_get_contents($gd_tokenfile), true);
                $client->setAccessToken($accessToken);
                $service = new Google_Service_Drive($client);
                $newPermission = new Google_Service_Drive_Permission();
                $new_uploadedfile = new Google_Service_Drive_DriveFile();

                // echo json_encode('die');die;

                if(sizeof($files_1) > 0)
                {
                    // foreach($old_dbfiles as $key => $oldfb_file)
                    // {
                            foreach($files_1 as $skey => $file_1)
                            {
                                foreach($old_dbfiles as $key => $oldfb_file)
                                {
                                    if($oldfb_file!=$files_1[$skey]->name)
                                    {
                                        $gd_deleteids[]=$old_gdfileids[$key];
                                    }
                                    elseif($oldfb_file==$files_1[$skey]->name)
                                    {
                                        // $fileids[]=$exp_dbfileids[$key];
                                        // $files_newnames[]=$exp_dbfiles_names[$key];
                                        // $origional_fnames[]=$exp_dborg_fnames[$key];
                                        // $all_fileurls[]=$exp_dball_fileurls[$key];
                                        // $all_thumbnail_links[]=$exp_dball_filethumbs[$key];
                                    }

                                }
                            }

                    // }
                }
                else
                {
                    $gd_deleteids=$old_gdfileids;
                }

                
                
                $gd_deleteids=array_unique($gd_deleteids,SORT_REGULAR);
                $gd_deleteids=array_values($gd_deleteids);
                if(sizeof($gd_deleteids) > 0)
                {
                    foreach($gd_deleteids as $key => $gd_deleteid)
                    {
                        if(isset($gd_deleteid[$key]))
                        {
                            $service->files->delete($gd_deleteid);
                        }
                    }
                }

                
                
                
                $folder_name="School Data";
                $folder_list = $this->check_folder_exists($folder_name,$client);
                
                $schl_id = $this->session->userdata("userdata")["sh_id"];
                $folder_id = null;
                $second_parentfolderid = null;

                // if folder does not exists
                if( count( $folder_list ) == 0 )
                {
                    //First Parent Folder
                    $upper_folder = new Google_Service_Drive_DriveFile(array('name' => $folder_name,'mimeType' => 'application/vnd.google-apps.folder'));
                    $result = $service->files->create( $upper_folder );
                    
                    if( isset( $result['id'] ) && !empty( $result['id'] ) )
                    {
                        $folder_id = $result['id'];
                        
                        $this->db->where('id', $schl_id);
                        $this->db->update('sh_school', array('parent_folderid' => $folder_id));
                    }
                    
                }
                else
                {
                    $this->db->select('parent_folderid');
                    $this->db->from('sh_school');
                    $this->db->where('id',$schl_id);
                    $que_res = $this->db->get()->row();
                    $folder_id=$que_res->parent_folderid;
                }
                
                
                //Second Parent Folder
                $validate_sndparent_existence=$this->check_folder_exists($content_type,$client);
                
                if(count($validate_sndparent_existence) == 0)
                {
                    $folderMetadata = new Google_Service_Drive_DriveFile(array('name' => $content_type,'mimeType' => 'application/vnd.google-apps.folder','parents' => array($folder_id)));
                    $res_scndfolder = $service->files->create($folderMetadata, array('fields' => 'id'));
                    $second_parentfolderid = $res_scndfolder->id;
                    
                    $this->db->where('id', $schl_id);
                    $this->db->update('sh_school', array('child_folderid' => $second_parentfolderid));
                }
                else
                {
                    $second_parentfolderid=$validate_sndparent_existence[0]->id;
                    // $this->db->select('child_folderid');
                    // $this->db->from('sh_school');
                    // $this->db->where('id',$schl_id);
                    // $que_res = $this->db->get()->row();
                    // $second_parentfolderid=$que_res->child_folderid;
                }
                
                

                if(sizeof($allfiles_on)>0)
                {
                    foreach($allfiles_on as $new_gdfiles)
                    {
                        $new_uploadedfile->setName($new_gdfiles->new_name);
                        $new_uploadedfile->setParents(array($second_parentfolderid));

                        $file_path = base_url().'uploads/study_material/'.$new_gdfiles->new_name;

                        $arrContextOptions=array(
                            "ssl"=>array(
                                "verify_peer"=>false,
                                "verify_peer_name"=>false,
                            ),
                        );
                    
                        $gdresult = $service->files->create(
                            $new_uploadedfile,
                            array(
                                'data' => file_get_contents($file_path,false, stream_context_create($arrContextOptions)),
                                'mimeType' => 'application/octet-stream',
                            )
                        );

                        $fileids[]=$gdresult->id;


                        $al='https://drive.google.com/thumbnail?authuser=0&sz=w320&id='.$gdresult->id;
                           
                        $all_thumbnail_links[]=$al;
                        

                        //set email of account, that will have access to file.
                        // $newPermission->setEmailAddress('test@gmail.com');

                        //Must be user or group, if you pass email adress
                        // user | group | domain | anyone
                        $newPermission->setType('anyone');
                        $newPermission->setRole('reader');
                        $service->permissions->create($gdresult->id, $newPermission);
                        

                        $sfile = $service->files->get($gdresult->id,array('fields' => 'webViewLink'));

                        $all_fileurls[]=$sfile->webViewLink;

                        $origional_fnames[]=$new_gdfiles->name;

                        $files_newnames[]=$new_gdfiles->new_name;


                    }
                
                
                    // foreach ($files_2 as $allnew_namedfile)
                    // {
                    //     $files_newnames[] = $allnew_namedfile->new_name;
                    // }
                }

                // echo '<pre>';
                // print_r($fileids);
                // print_r($files_newnames);
                // print_r($origional_fnames);
                // print_r($all_fileurls);
                // print_r($all_thumbnail_links);
                // die;
                
                // echo json_encode('die');die;

                $newfile_string_newnames=implode(',',$files_newnames);

                $newfile_stringids=implode(',',$fileids);

                $newfile_stringurls=implode(',',$all_fileurls);

                $newfile_string_orgnames=implode(',',$origional_fnames);

                // $all_thumbnail_links=array_unique($all_thumbnail_links);

                $allthumbnail_links=implode(',',$all_thumbnail_links);


            if($batch_id == 'all' && $subject == 'all')
            {
                
                    $batch_idss = $this->db->select("id")->from('sh_batches')->where('class_id' , $class_id)->get()->result();
                    foreach($batch_idss as $b)
                    {
                        $b_id[] = $b->id;
                        $this->db->distinct();
                        $subject_codes = $this->db->select("id,code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $b->id)->get()->result();
                        foreach($subject_codes as $s)
                    {
                        $s_code[] = $s->code;
                        $s_ids[] = $s->id;
                    } 
                    }
                    
                    $subject_code = implode(',' , $s_code);
                    $subject = implode(',' , $s_ids);
                    $batch_id = implode(',' , $b_id);

                $updatingdata = array(
                    'title' => $title,
                    'content_type' => $content_type,
                    'class_id' => $class_id,
                    'batch_id' => $batch_id,
                    'subject_id' => $subject,
                    'subject_code' => $subject_code,
                    'details' => $details,
                    'files' => $newfile_string_newnames,
                    'fileids' => $newfile_stringids,
                    'filesurl' => $newfile_stringurls,
                    'file_names' => $newfile_string_orgnames,
                    'thumbnail_links' => $allthumbnail_links,
                );
            
            }
            else if($batch_id == 'all' && $subject != 'all')
            {
                $batch_idss = $this->db->select("id")->from('sh_batches')->where('class_id' , $class_id)->get()->result();
                foreach($batch_idss as $b)
                {
                    $b_ids[] = $b->id;
                }
                $subject_codes = $this->db->select("code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $b->id)->where('id', $subject)->get()->result();
                foreach($subject_codes as $code)
                {
                    $subject_code = $code->code;
                }
                $batch_id = implode(',' , $b_ids);

                $updatingdata = array(
                    'title' => $title,
                    'content_type' => $content_type,
                    'class_id' => $class_id,
                    'batch_id' => $batch_id,
                    'subject_id' => $subject,
                    'subject_code' => $subject_code,
                    'details' => $details,
                    'files' => $newfile_string_newnames,
                    'fileids' => $newfile_stringids,
                    'filesurl' => $newfile_stringurls,
                    'file_names' => $newfile_string_orgnames,
                    'thumbnail_links' => $allthumbnail_links,
                );
        
            }
                
            else if($batch_id != 'all' && $subject == 'all')
            {
                
                $this->db->distinct();
                $subject_codes = $this->db->select("id,code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $batch_id)->get()->result();
                foreach($subject_codes as $s)
                {
                    $s_code[] = $s->code;
                    $s_ids[] = $s->id;
                } 
                $subject_code = implode(',' , $s_code);
                $subject = implode(',' , $s_ids);

                $updatingdata = array(
                    'title' => $title,
                    'content_type' => $content_type,
                    'class_id' => $class_id,
                    'batch_id' => $batch_id,
                    'subject_id' => $subject,
                    'subject_code' => $subject_code,
                    'details' => $details,
                    'files' => $newfile_string_newnames,
                    'fileids' => $newfile_stringids,
                    'filesurl' => $newfile_stringurls,
                    'file_names' => $newfile_string_orgnames,
                    'thumbnail_links' => $allthumbnail_links,
                );
            }

            else
            {
                $subject_codes = $this->db->select("code")->from('sh_subjects')->where('class_id' , $class_id)->where('batch_id', $batch_id)->where('id', $subject)->get()->result();
                foreach($subject_codes as $code)
                {
                    $subject_code = $code->code;
                }

                $updatingdata = array(
                    'title' => $title,
                    'content_type' => $content_type,
                    'class_id' => $class_id,
                    'batch_id' => $batch_id,
                    'subject_id' => $subject,
                    'subject_code' => $subject_code,
                    'details' => $details,
                    'files' => $newfile_string_newnames,
                    'fileids' => $newfile_stringids,
                    'filesurl' => $newfile_stringurls,
                    'file_names' => $newfile_string_orgnames,
                    'thumbnail_links' => $allthumbnail_links,
                );
            
            }




        
        
                $this->study_model->updateMaterial($editrowid, $updatingdata);
                $response['updated'] = true;
                $response['message'] = lang('study_updated_msg');
                echo json_encode($response); 
            }
        }
    }
    // new added for google drive
    
    public function book_shop(){

        
        $this->load->view('study_material/book_shop');
        
    }

    public function get_bookshop(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class = $request->class;
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_book_shop');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL');
        if($class!='all'){
            $xcrud->where('class_id',$class);
        }
        $xcrud->relation('class_id','sh_classes','id','name','deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('title,class_id, price, availability,picture');
        $xcrud->fields('title,class_id, price, availability,link,picture');
        $xcrud->label('class_id',lang('lbl_class'))->label('title',lang('lbl_tbl_title'))->label('availability',lang('lbl_availability'))->label('picture',lang('lbl_picture'))->label('price',lang('lbl_price'))->label('link',lang('lbl_link'));
        $xcrud->change_type('picture','image','',array('height'=>300));
        $xcrud->column_callback('price','add_currency');
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->before_insert('remove_link');
        $xcrud->before_update('remove_link_update');
        $xcrud->replace_remove('soft_delete');
        $xcrud->table_name(lang('lbl_book_shop'));
        $xcrud->load_view("view", "customview.php");
        $xcrud->button(prep_url('{link}'),lang('lbl_download'),'fa fa-download','btn-primary btn-circle text-white',array('target'=>'_blank'),array('link','!=',''));       
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

        $response["bookshop"] = $xcrud->render();
        echo json_encode($response);
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

    public function getSubjectsForAssignmentEdit(){
        $school_id = $this->session->userdata('userdata')["sh_id"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $sections = $request->section;

        $subject_ids = false;
        $data = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $subject_ids =  login_user()->t_data->subjects;
            $subject = implode(',', $subject_ids);
            if (count($subject_ids) > 0 && count($request->section) > 0) {

                $data = $this->db->query("SELECT s.* FROM sh_subjects as s WHERE s.school_id='$school_id' AND s.batch_id IN ($sections) AND s.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND s.deleted_at IS NULL AND s.class_id=$class_id AND s.id IN ($subject) GROUP by s.code")->result();
            }
        }else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $data = $this->db->query("SELECT s.* FROM sh_subjects as s WHERE s.school_id='$school_id' AND s.batch_id IN ($sections) AND s.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND s.deleted_at IS NULL AND s.class_id=$class_id GROUP by s.code")->result();
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){

        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){

        }

        echo json_encode($data);

    }

    public function getSubjectsForAssignments(){
        $school_id = $this->session->userdata('userdata')["sh_id"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $sections = implode(',',$request->section);

        $subject_ids = false;
        $data = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $subject_ids =  login_user()->t_data->subjects;
            $subject = implode(',', $subject_ids);
            if (count($subject_ids) > 0 && count($request->section) > 0) {

                $data = $this->db->query("SELECT s.* FROM sh_subjects as s WHERE s.school_id='$school_id' AND s.batch_id IN ($sections) AND s.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND s.deleted_at IS NULL AND s.class_id=$class_id AND s.id IN ($subject) GROUP by s.code")->result();
            }
        }else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $data = $this->db->query("SELECT s.* FROM sh_subjects as s WHERE s.school_id='$school_id' AND s.batch_id IN ($sections) AND s.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND s.deleted_at IS NULL AND s.class_id=$class_id GROUP by s.code")->result();
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){

        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){

        }
        
        echo json_encode($data);
    }

    public function getStusentsForAssignmentsEdit(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $school_id = $this->session->userdata('userdata')["sh_id"];

        if ($request->section != "") {
            $students = $this->db->query("SELECT s.*,sg.subjects as subject_ids,b.name as section_name FROM sh_students_".$school_id." as s INNER JOIN sh_batches b ON s.batch_id=b.id INNER JOIN sh_subject_groups sg ON s.subject_group_id=sg.id WHERE s.school_id='$school_id' AND s.batch_id IN (".$request->section.") AND s.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND s.deleted_at='0' AND s.class_id=$class_id")->result();
        } else {
            $students = $this->db->query("SELECT s.*,b.name as section_name FROM sh_students_".$school_id." as s INNER JOIN sh_batches b ON s.batch_id=b.id WHERE s.school_id='$school_id' AND s.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND s.deleted_at='0' AND s.class_id=$class_id")->result();
        }

        $sub = $this->db->select("code")->from('sh_subjects')->where('id', $request->subject)->get()->row();
        $code = $sub->code;

        $subject_codes = array();
        $data = array();
        foreach ($students as $key => $std) {
            $subject_id = explode(',',$std->subject_ids);
            foreach ($subject_id as $subject) {
                $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject)->get()->row();
                array_push($subject_codes, $subject_row->code);
            }
            
             if (in_array($code, $subject_codes)) {
                 array_push($data,  $std);

             }
        }
        echo json_encode($data);
    }

    public function getStusentsForAssignments(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $school_id = $this->session->userdata('userdata')["sh_id"];
        if (count($request->section) > 0) {
            $students = $this->db->query("SELECT s.*,sg.subjects as subject_ids,b.name as section_name FROM sh_students_".$school_id." as s INNER JOIN sh_batches b ON s.batch_id=b.id INNER JOIN sh_subject_groups sg ON s.subject_group_id=sg.id WHERE s.school_id='$school_id' AND s.batch_id IN (".implode(',',$request->section).") AND s.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND s.deleted_at='0' AND s.class_id=$class_id")->result();
        } else {
            $students = $this->db->query("SELECT s.*,b.name as section_name FROM sh_students_".$school_id." as s INNER JOIN sh_batches b ON s.batch_id=b.id WHERE s.school_id='$school_id' AND s.academic_year_id=(Select id from sh_academic_years Where school_id='$school_id' AND is_active='Y') AND s.deleted_at='0' AND s.class_id=$class_id")->result();
        }

        $sub = $this->db->select("code")->from('sh_subjects')->where('id', $request->subject)->get()->row();
        $code = $sub->code;

        $subject_codes = array();
        $data = array();
        foreach ($students as $key => $std) {
            $subject_id = explode(',',$std->subject_ids);
            foreach ($subject_id as $subject) {
                $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject)->get()->row();
                array_push($subject_codes, $subject_row->code);
            }
            
             if (in_array($code, $subject_codes)) {
                 array_push($data,  $std);

             }
        }
        echo json_encode($data);
        
    }

    public function student_class_activities(){
        $this->load->view('study_material/student_class_activities');
    }

    public function submitAssignment() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $material_id = $request->details->id;
        $date = date("Y-m-d");
        $submitted_details = $request->text;
        $files_1 = $request->files;
        $student_id = $this->session->userdata("userdata")["user_id"];
        

        $files = "";
        
        if($files_1){
            $files = array();
            foreach ($files_1 as $value) {
                $files[] = $value->new_name;
            }
        }

        //$submitted_files = implode(",", $files);

        //$data = array(
          //  "material_id" => $material_id,
            //"student_id" => $student_id,
            //"date" => $date,
            //"submitted_files" => $submitted_files,
           // "submitted_details" => $submitted_details
        //);

        if ($files) {

            $submitted_files = implode(",", $files);
            $data = array(
                "material_id" => $material_id,
                "student_id" => $student_id,
                "date" => $date,
                "submitted_files" => $submitted_files,
                "submitted_details" => $submitted_details
            );

        } else {

            $data = array(
                "material_id" => $material_id,
                "student_id" => $student_id,
                "date" => $date,
                "submitted_files" => "",
                "submitted_details" => $submitted_details
            );
        }

        $res = $this->admin_model->dbInsert("sh_submit_material", $data);
        $response["part"] = explode(',', $request->details->teacher_id);
        $response["message"] = "Material Submitted";
        $response["sender"] = $this->session->userdata("userdata")["name"];
        echo json_encode($response);
    }
    
    public function class_work(){
        $this->load->view('study_material/class_work');
    }

    public function currentDateSubmittedAss(){
        $student_id = $this->session->userdata("userdata")["user_id"];
        $current_date = date("Y-m-d");

        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_assignments.batch_ids IN (". implode(',', login_user()->t_data->batches) .") order by sh_assignments.created_at desc";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part =  " order by sh_assignments.created_at desc";
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part =  " order by sh_assignments.created_at desc";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part =  " order by sh_assignments.created_at desc";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part =  " order by sh_assignments.created_at desc";
        }
        //-------------------------

        $assignments = '';

        $query = "SELECT "
        . "sh_assignments.*,"
        . "sh_classes.name as class_name,"
        //. "sh_batches.name as batch_name,"
        // . "sh_subjects.name as subject_name, "
        . "date_format(sh_assignments.published_date,'%d/%m/%Y') as uploaded_time, sh_users.name "
        . "From sh_assignments "
        . "Inner join sh_classes ON sh_assignments.class_id = sh_classes.id "
        //. "left JOIN sh_batches ON sh_assignments.batch_ids = sh_batches.id "
        // . "left JOIN sh_subjects ON sh_assignments.subject_id = sh_subjects.id "
        . "Left JOIN sh_users ON sh_assignments.uploaded_by = sh_users.id "
        . "WHERE sh_assignments.deleted_status=0 AND sh_assignments.content_type='Assignment' "
        . "AND sh_assignments.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";



        $assignments = $this->admin_model->dbQuery($query.$where_part);

        $students = array();
        $data = array();
        $where_part1 = "";
        $where_sub_mat = "";
        $where_std = "";

        if($assignments){
            foreach ($assignments as $key1 => $assgn) {
            
                $student_ids = explode(',', $assgn->student_ids);

                foreach ($student_ids as $key2 => $std) {

                    if($std){
                       $query = "SELECT "
                        . "std.id as student_id, std.name, std.avatar, std.gender,std.subject_group_id, std.rollno, uu.name as guardian_name, gd.relation as guardian_ralation, std.batch_id as student_batch_id, "
                        . "sh_classes.name as class_name, "
                        . "sh_batches.name as batch_name,"
                        . "sh_subjects.name as subject_name"
                        . " FROM sh_students_". login_user()->user->sh_id." as std" 
                        . " LEFT JOIN sh_student_class_relation std_cls_rel ON std_cls_rel.student_id = std.id AND std_cls_rel.deleted_at is NULL"
                        . " INNER JOIN sh_classes ON std_cls_rel.class_id = sh_classes.id AND sh_classes.deleted_at is NULL AND sh_classes.academic_year_id =".$this->session->userdata("userdata")["academic_year"]." "
                        . " LEFT JOIN sh_batches ON sh_batches.id =  std_cls_rel.batch_id AND sh_batches.deleted_at is NULL AND std_cls_rel.academic_year_id =".$this->session->userdata("userdata")["academic_year"]." "
                        . " INNER JOIN sh_subjects ON sh_subjects.code = '$assgn->subject_code' AND sh_subjects.deleted_at is NULL "
                        . " LEFT JOIN sh_student_guardians gd ON gd.student_id = std.id AND gd.deleted_at is NULL"
                        . " LEFT JOIN sh_users uu ON uu.id = gd.guardian_id AND uu.deleted_at = 0"
                        . " WHERE std.deleted_at=0 AND std.id = $std ";
                            
                        $record_std = $this->admin_model->dbQuery($query. $where_std);
                        if($record_std){
                            $data1[$key1][$key2]["student"] = $record_std[0];
                        }else{
                            $data1[$key1][$key2]["student"] = "";
                        }


                        $query1 = "SELECT "
                        . "asgn.*,"
                        . "sh_classes.name as class_name, "
                        . "sh_batches.name as batch_name,"
                        . "su.name as student_name,"
                        . "su.avatar as student_avatar,"
                        . "tu.name as teacher_name,"
                        . "tu.avatar as teacher_avatar,"
                        . "sub_mat.id as submit_id,sub_mat.student_id, sub_mat.date, sub_mat.submitted_files, sub_mat.submitted_details, sub_mat.obtained_marks, sub_mat.remarks, sub_mat.viewed, date_format(sub_mat.created_at,'%Y-%m-%d') as submitted_date"
                        . " FROM sh_assignments as asgn "
                        . " INNER JOIN sh_classes ON asgn.class_id = sh_classes.id "
                        . "LEFT JOIN sh_batches ON sh_batches.id = '$assgn->batch_ids' "
                        . "LEFT JOIN sh_submit_material sub_mat ON sub_mat.material_id = asgn.id AND sub_mat.student_id = $std $where_sub_mat "
                        . " LEFT JOIN sh_users tu ON tu.id = asgn.uploaded_by AND tu.deleted_at = 0" 
                        . " LEFT JOIN sh_users su ON su.id = sub_mat.student_id AND su.deleted_at = 0" 

                        . " WHERE FIND_IN_SET($std, asgn.student_ids) AND asgn.batch_ids = '$assgn->batch_ids' AND asgn.deleted_status=0 AND asgn.content_type='Assignment' AND asgn.class_id= '$assgn->class_id' AND asgn.subject_code='$assgn->subject_code' AND asgn.school_id=". login_user()->user->sh_id. " AND sub_mat.date='$current_date' ORDER BY asgn.created_at desc ";

                        $data1[$key1][$key2]["assignments"] = $this->admin_model->dbQuery($query1.$where_part1);


                    }
                }
            } 
        

        $response = array();

        foreach ($data1 as $key => $data) {

            if (count($data) == '0') {
                echo json_encode($data); die();
            }

            for ($i = 0; $i < count($data[0]['assignments']); $i++) {
                $data[0]['assignments'][$i]->files = explode(",", $data[0]['assignments'][$i]->files);
                $data[0]['assignments'][$i]->submitted_files = explode(",", $data[0]['assignments'][$i]->submitted_files);
            }

            foreach ($data as $key => $dat) { 

                if ($dat['student'] == "") {
                    unset($data[$key]);
                } 
            }

            foreach ($data as $key => $dta) {

                $allCount=0;
                $data[$key]['student']->submit_count=0;
                foreach ($dta['assignments'] as $key1 => $dt) {
                    $allCount++;
                    $data[$key]['student']->allCount=$allCount;
                    if ($dt->obtained_marks != "" || $dt->remarks != "") {
                     $data[$key]['student']->submit_count=++$data[$key]['student']->submit_count;
                 } 
             }
         }

         foreach ($data as $key => $det) {
            foreach ($det['assignments'] as $key2 => $de) {
                if ($de->submitted_date){
                    if($de->due_date >= $de->submitted_date){
                       $de->{"status"} = 'submitted'; 
                   }else{
                    $de->{"status"} = 'late'; 
                    }
                } else{
                $de->{"status"} = 'not_submit';

                } 
            }
        }

        foreach ($data as $key => $value) {
            if (count($value['assignments']) == 0 ) {
                unset($data[$key]);
            }
        }
         
        $data3 = array_values($data);
        foreach ($data3 as $dp) {
            if ($dp) {
                array_push($response, $dp);   
            }   
        }
        
    }   

    $duplicate_keys = array();
    $tmp = array();       

    foreach ($response as $key => $val){
        if (is_object($val['student']))
            $val['student'] = (array)$val['student'];

        if (!in_array($val['student'], $tmp))
            $tmp[] = $val['student'];
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key){
        unset($response[$key]);
    }
        echo json_encode($response);

    }
}


    public function currentDateSubmittedHom(){
        $student_id = $this->session->userdata("userdata")["user_id"];
        $current_date = date("Y-m-d");

        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_assignments.batch_ids IN (". implode(',', login_user()->t_data->batches) .") order by sh_assignments.created_at desc";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part =  " order by sh_assignments.created_at desc";
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part =  " order by sh_assignments.created_at desc";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part =  " order by sh_assignments.created_at desc";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part =  " order by sh_assignments.created_at desc";
        }
        //-------------------------

        $assignments = '';

        $query = "SELECT "
        . "sh_assignments.*,"
        . "sh_classes.name as class_name,"
        //. "sh_batches.name as batch_name,"
        // . "sh_subjects.name as subject_name, "
        . "date_format(sh_assignments.published_date,'%d/%m/%Y') as uploaded_time, sh_users.name "
        . "From sh_assignments "
        . "Inner join sh_classes ON sh_assignments.class_id = sh_classes.id "
        //. "left JOIN sh_batches ON sh_assignments.batch_ids = sh_batches.id "
        // . "left JOIN sh_subjects ON sh_assignments.subject_id = sh_subjects.id "
        . "Left JOIN sh_users ON sh_assignments.uploaded_by = sh_users.id "
        . "WHERE sh_assignments.deleted_status=0 AND sh_assignments.content_type='Homework' "
        . "AND sh_assignments.school_id=". login_user()->user->sh_id. " AND sh_classes.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ";



        $assignments = $this->admin_model->dbQuery($query.$where_part);

        $students = array();
        $data = array();
        $where_part1 = "";
        $where_sub_mat = "";
        $where_std = "";
        if($assignments){
            foreach ($assignments as $key1 => $assgn) {

                $student_ids = explode(',', $assgn->student_ids);

                foreach ($student_ids as $key2 => $std) {

                    if($std){
                       $query = "SELECT "
                        . "std.id as student_id, std.name, std.avatar, std.gender,std.subject_group_id, std.rollno, uu.name as guardian_name, gd.relation as guardian_ralation, std.batch_id as student_batch_id, "
                        . "sh_classes.name as class_name, "
                        . "sh_batches.name as batch_name,"
                        . "sh_subjects.name as subject_name"
                        . " FROM sh_students_". login_user()->user->sh_id." as std" 
                        . " LEFT JOIN sh_student_class_relation std_cls_rel ON std_cls_rel.student_id = std.id AND std_cls_rel.deleted_at is NULL"
                        . " INNER JOIN sh_classes ON std_cls_rel.class_id = sh_classes.id AND sh_classes.deleted_at is NULL AND sh_classes.academic_year_id =".$this->session->userdata("userdata")["academic_year"]." "
                        . " LEFT JOIN sh_batches ON sh_batches.id =  std_cls_rel.batch_id AND sh_batches.deleted_at is NULL AND std_cls_rel.academic_year_id =".$this->session->userdata("userdata")["academic_year"]." "
                        . " INNER JOIN sh_subjects ON sh_subjects.code = '$assgn->subject_code' AND sh_subjects.deleted_at is NULL "
                        . " LEFT JOIN sh_student_guardians gd ON gd.student_id = std.id AND gd.deleted_at is NULL"
                        . " LEFT JOIN sh_users uu ON uu.id = gd.guardian_id AND uu.deleted_at = 0"
                        . " WHERE std.deleted_at=0 AND std.id = $std ";
                            
                        $record_std = $this->admin_model->dbQuery($query. $where_std);
                        if($record_std){
                            $data1[$key1][$key2]["student"] = $record_std[0];
                        }else{
                            $data1[$key1][$key2]["student"] = "";
                        }


                        $query1 = "SELECT "
                        . "asgn.*,"
                        . "sh_classes.name as class_name, "
                        . "sh_batches.name as batch_name,"
                        . "su.name as student_name,"
                        . "su.avatar as student_avatar,"
                        . "tu.name as teacher_name,"
                        . "tu.avatar as teacher_avatar,"
                        . "sub_mat.id as submit_id,sub_mat.student_id, sub_mat.date, sub_mat.submitted_files, sub_mat.submitted_details, sub_mat.obtained_marks, sub_mat.remarks, sub_mat.viewed, date_format(sub_mat.created_at,'%Y-%m-%d') as submitted_date"
                        . " FROM sh_assignments as asgn "
                        . " INNER JOIN sh_classes ON asgn.class_id = sh_classes.id "
                        . "LEFT JOIN sh_batches ON sh_batches.id = '$assgn->batch_ids' "
                        . "LEFT JOIN sh_submit_material sub_mat ON sub_mat.material_id = asgn.id AND sub_mat.student_id = $std $where_sub_mat "
                        . " LEFT JOIN sh_users tu ON tu.id = asgn.uploaded_by AND tu.deleted_at = 0" 
                        . " LEFT JOIN sh_users su ON su.id = sub_mat.student_id AND su.deleted_at = 0" 

                        . " WHERE FIND_IN_SET($std, asgn.student_ids) AND asgn.batch_ids = '$assgn->batch_ids' AND asgn.deleted_status=0 AND asgn.content_type='Homework' AND asgn.class_id= '$assgn->class_id' AND asgn.subject_code='$assgn->subject_code' AND asgn.school_id=". login_user()->user->sh_id. " AND sub_mat.date='$current_date' ORDER BY asgn.created_at desc ";

                        $data1[$key1][$key2]["Homeworks"] = $this->admin_model->dbQuery($query1.$where_part1);

                    }
                }
            }

        
        $response = array();

        foreach ($data1 as $key => $data) {

            for ($i = 0; $i < count($data[0]['Homeworks']); $i++) {
                $data[0]['Homeworks'][$i]->files = explode(",", $data[0]['Homeworks'][$i]->files);
                $data[0]['Homeworks'][$i]->submitted_files = explode(",", $data[0]['Homeworks'][$i]->submitted_files);
            }

            foreach ($data as $key => $dat) { 

                if ($dat['student'] == "") {
                    unset($data[$key]);
                } 
            }

            foreach ($data as $key => $dta) {

                $allCount=0;
                $data[$key]['student']->submit_count=0;
                foreach ($dta['Homeworks'] as $key1 => $dt) {
                    $allCount++;
                    $data[$key]['student']->allCount=$allCount;
                    if ($dt->obtained_marks != "" || $dt->remarks != "") {
                     $data[$key]['student']->submit_count=++$data[$key]['student']->submit_count;
                 } 
             }
         }

         foreach ($data as $key => $det) {
            foreach ($det['Homeworks'] as $key2 => $de) {
                if ($de->submitted_date){
                    if($de->due_date >= $de->submitted_date){
                       $de->{"status"} = 'submitted'; 
                   }else{
                    $de->{"status"} = 'late'; 
                    }
                } else{
                $de->{"status"} = 'not_submit';

                } 
            }
        }

        foreach ($data as $key => $value) {
            if (count($value['Homeworks']) == 0 ) {
                unset($data[$key]);
            }
        }

        $data3 = array_values($data);
       
        foreach ($data3 as $dp) {
            if ($dp) {
                array_push($response, $dp);   
            }   
        } 
       
        
    }   


    $duplicate_keys = array();
    $tmp = array();       

    foreach ($response as $key => $val){
        if (is_object($val['student']))
            $val['student'] = (array)$val['student'];

        if (!in_array($val['student'], $tmp))
            $tmp[] = $val['student'];
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key){
        unset($response[$key]);
    }

        echo json_encode($response);

    }
}

    public function filter_submitted_assignments1(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $searchKey = $due = '';
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject_id = $request->subject;
        if (!isset($request->to)) {
            $request->to = "";
        }
        if (!isset($request->from)) {
            $request->from = "";
        }



       $subject_code = "";
        if($subject_id != 0 && $subject_id != ""){
            
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }
        if(isset($request->due)){
            $due = $request->due;
        }

        $school_id = $this->session->userdata('userdata')["sh_id"];

        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_batches.id IN (". implode(',', login_user()->t_data->batches) .") ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part =  "";
        }else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part =  "";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part =  "";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part =  "";
        }
        //-------------------------

        $assignments = '';

        $current_date = date('Y-m-d');
        if($class_id != '' && $batch_id != '' && $subject_id != ''){
            if ($request->from != "" && $request->to == "") {

                $from_date = to_mysql_date($request->from);
                $where_part .=  " AND asgn.published_date='$from_date' ";

            }
            if ($request->from == "" && $request->to != "") {

                $to_date = to_mysql_date($request->to);
                $where_part .=  " AND asgn.published_date='to_date' ";

            }
            if ($request->from != "" && $request->to != "") {

                $from_date = to_mysql_date($request->from);
                $to_date = to_mysql_date($request->to);

                $where_part .= " AND asgn.published_date between '$from_date' AND '$to_date' ";

            }
            
        $query = "SELECT "
            . "asgn.*,"
            . "sh_classes.name as class_name, "
            . "sh_batches.name as batch_name"
            //. "sub_mat.id as submit_id,sub_mat.student_id, sub_mat.date, sub_mat.submitted_files, sub_mat.submitted_details, sub_mat.obtained_marks, sub_mat.remarks"
            //. "date_format(sh_study_material.uploaded_at,'%d/%m/%Y') as uploaded_time, sh_users.name "
            . " FROM sh_assignments as asgn "
            . " INNER JOIN sh_classes ON asgn.class_id = sh_classes.id "
            . "LEFT JOIN sh_batches ON sh_batches.id =  $batch_id "
            //. " INNER JOIN sh_subjects ON sh_subjects.id = ass.subject_id "
            //. "LEFT JOIN sh_submit_material sub_mat ON sub_mat.material_id = asgn.id"
            . " WHERE FIND_IN_SET($batch_id, asgn.batch_ids) AND asgn.deleted_status=0 AND asgn.content_type='Assignment' AND asgn.class_id= $class_id AND asgn.subject_code='$subject_code' AND asgn.school_id=". login_user()->user->sh_id. " ";

                $assignments = $this->admin_model->dbQuery($query.$where_part);
        } 

        $students = array();
        $data = array();
        $where_part1 = "";
        $where_sub_mat = "";
        $where_std = "";

        if ($request->from != "" && $request->to == "") {

                $from_date = to_mysql_date($request->from);
                $where_part1 .=  " AND asgn.published_date='$from_date' ";

            }
            if ($request->from == "" && $request->to != "") {

                $to_date = to_mysql_date($request->to);
                $where_part1 .=  " AND asgn.published_date='to_date' ";

            }
            if ($request->from != "" && $request->to != "") {

                $from_date = to_mysql_date($request->from);
                $to_date = to_mysql_date($request->to);

                $where_part1 .= " AND asgn.published_date between '$from_date' AND '$to_date' ";

            }


        // if($due){
        //     $where_sub_mat .= " AND sub_mat.obtained_marks = NULL ";
         
        // }
        
        if(isset($request->searchKey) && !empty($request->searchKey)){
            $searchKey = $request->searchKey;
            $where_std .= " AND (std.name LIKE '%". $request->searchKey ."%' OR std.rollno LIKE '%". $request->searchKey ."%') "; 
        }
            
        if($assignments){
            foreach ($assignments as $key1 => $assgn) {

                $student_ids = explode(',', $assgn->student_ids);

                foreach ($student_ids as $key2 => $std) {

                    if($std){
                       $query = "SELECT "
                        . "std.id as student_id, std.name, std.avatar, std.gender,std.subject_group_id, std.rollno, uu.name as guardian_name, gd.relation as guardian_ralation, std.batch_id as student_batch_id, "
                        . "sh_classes.name as class_name, "
                        . "sh_batches.name as batch_name,"
                        . "sh_subjects.name as subject_name"
                        . " FROM sh_students_". login_user()->user->sh_id." as std" 
                        . " LEFT JOIN sh_student_class_relation std_cls_rel ON std_cls_rel.student_id = std.id AND std_cls_rel.deleted_at is NULL"
                        . " INNER JOIN sh_classes ON std_cls_rel.class_id = sh_classes.id AND sh_classes.deleted_at is NULL AND sh_classes.academic_year_id =".$this->session->userdata("userdata")["academic_year"]." "
                        . " LEFT JOIN sh_batches ON sh_batches.id =  std_cls_rel.batch_id AND sh_batches.deleted_at is NULL AND std_cls_rel.academic_year_id =".$this->session->userdata("userdata")["academic_year"]." "
                        . " INNER JOIN sh_subjects ON sh_subjects.code = '$subject_code' AND sh_subjects.deleted_at is NULL "
                        . " LEFT JOIN sh_student_guardians gd ON gd.student_id = std.id AND gd.deleted_at is NULL"
                        . " LEFT JOIN sh_users uu ON uu.id = gd.guardian_id AND uu.deleted_at = 0"
                        . " WHERE std.deleted_at=0 AND std.id = $std ";
                            
                        $record_std = $this->admin_model->dbQuery($query. $where_std);
                        if($record_std){
                            $data[$key2]["student"] = $record_std[0];
                        }else{
                            $data[$key2]["student"] = "";
                        }


                         $query1 = "SELECT "
                        . "asgn.*,"
                        . "sh_classes.name as class_name, "
                        . "sh_batches.name as batch_name,"
                        . "su.name as student_name,"
                        . "su.avatar as student_avatar,"
                        . "tu.name as teacher_name,"
                        . "tu.avatar as teacher_avatar,"
                        . "sub_mat.id as submit_id,sub_mat.student_id, sub_mat.date, sub_mat.submitted_files, sub_mat.submitted_details, sub_mat.obtained_marks, sub_mat.remarks, sub_mat.viewed, date_format(sub_mat.created_at,'%Y-%m-%d') as submitted_date"
                        . " FROM sh_assignments as asgn "
                        . " INNER JOIN sh_classes ON asgn.class_id = sh_classes.id "
                        . "LEFT JOIN sh_batches ON sh_batches.id =  $batch_id "
                        . "LEFT JOIN sh_submit_material sub_mat ON sub_mat.material_id = asgn.id AND sub_mat.student_id = $std $where_sub_mat "
                        . " LEFT JOIN sh_users tu ON tu.id = asgn.uploaded_by AND tu.deleted_at = 0" 
                        . " LEFT JOIN sh_users su ON su.id = sub_mat.student_id AND su.deleted_at = 0" 

                        . " WHERE FIND_IN_SET($std, asgn.student_ids) AND asgn.batch_ids = $batch_id AND asgn.deleted_status=0 AND asgn.content_type='Assignment' AND asgn.class_id= $class_id AND asgn.subject_code='$subject_code' AND asgn.school_id=". login_user()->user->sh_id. " ";

                        $data[$key2]["assignments"] = $this->admin_model->dbQuery($query1.$where_part1);

                    }
                  
                }

            } 
        }

        if (count($data) == '0') {
            echo json_encode($data); die();
        }

        for ($i = 0; $i < count($data[0]['assignments']); $i++) {
            $data[0]['assignments'][$i]->files = explode(",", $data[0]['assignments'][$i]->files);
            $data[0]['assignments'][$i]->submitted_files = explode(",", $data[0]['assignments'][$i]->submitted_files);
        }

        foreach ($data as $key => $dat) { 

            if ($dat['student'] == "") {
                unset($data[$key]);
            } 
        }
        
        foreach ($data as $key => $dta) {

            $allCount=0;
            $data[$key]['student']->submit_count=0;
            foreach ($dta['assignments'] as $key1 => $dt) {
                $allCount++;
                $data[$key]['student']->allCount=$allCount;
                if ($dt->obtained_marks != "" || $dt->remarks != "") {
                   $data[$key]['student']->submit_count=++$data[$key]['student']->submit_count;
                } 
            }
        }

        if ($due)
        {
            foreach ($data as $key => $d)
            {
                
                if ($d['student']->allCount == $d['student']->submit_count)
                {
                    unset($data[$key]);
                } 
            } 
        }  

        $current_date == date("Y-m-d");

        foreach ($data as $key => $det) {
            foreach ($det['assignments'] as $key2 => $de) {
                if ($de->submitted_date){
                    if($de->due_date >= $de->submitted_date){
                         $de->{"status"} = 'submitted'; 
                    }else{
                        $de->{"status"} = 'late'; 
                    }
                } else{
                    $de->{"status"} = 'not_submit';
                   
            } 
        }
    }


    $duplicate_keys = array();
    $tmp = array();       

    foreach ($data as $key => $val){
        if (is_object($val['student']))
            $val['student'] = (array)$val['student'];

        if (!in_array($val['student'], $tmp))
            $tmp[] = $val['student'];
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key){
        unset($data[$key]);
    }

    foreach ($data as $key => $sub) {
        if ($sub['student']->student_batch_id != $batch_id) {
            unset($data[$key]);
        }
    }

        echo json_encode(array_values($data));
    }

    public function filter_submitted_homework() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $searchKey = $due = '';
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject_id = $request->subject;
        if (!isset($request->to)) {
            $request->to = "";
        }
        if (!isset($request->from)) {
            $request->from = "";
        }

        $subject_code = "";
        if($subject_id != 0 && $subject_id != ""){
            
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }

        
        // if(isset($request->done)){
        //     $done = $request->done;
        // }
        if(isset($request->due)){
            $due = $request->due;
        }

        $school_id = $this->session->userdata('userdata')["sh_id"];

        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_batches.id IN (". implode(',', login_user()->t_data->batches) .") ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            $where_part =  "";
        }else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $where_part =  "";
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            $where_part =  "";
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            $where_part =  "";
        }
        //-------------------------

        $assignments = '';
        $current_date = date('Y-m-d');


        if($class_id != '' && $batch_id != '' && $subject_id != ''){
            if ($request->from != "" && $request->to == "") {

                $from_date = to_mysql_date($request->from);
                $where_part .=  " AND asgn.published_date='$from_date' ";

            }
            if ($request->from == "" && $request->to != "") {

                $to_date = to_mysql_date($request->to);
                $where_part .=  " AND asgn.published_date='$to_date' ";

            }
            if ($request->from != "" && $request->to != "") {

                $from_date = to_mysql_date($request->from);
                $to_date = to_mysql_date($request->to);

                $where_part .= " AND asgn.published_date between '$from_date' AND '$to_date' ";

            }
            
        $query = "SELECT "
            . "asgn.*,"
            . "sh_classes.name as class_name, "
            . "sh_batches.name as batch_name"
            //. "sub_mat.id as submit_id,sub_mat.student_id, sub_mat.date, sub_mat.submitted_files, sub_mat.submitted_details, sub_mat.obtained_marks, sub_mat.remarks"
            //. "date_format(sh_study_material.uploaded_at,'%d/%m/%Y') as uploaded_time, sh_users.name "
            . " FROM sh_assignments as asgn "
            . " INNER JOIN sh_classes ON asgn.class_id = sh_classes.id "
            . "LEFT JOIN sh_batches ON sh_batches.id =  $batch_id "
            //. " INNER JOIN sh_subjects ON sh_subjects.id = ass.subject_id "
            //. "LEFT JOIN sh_submit_material sub_mat ON sub_mat.material_id = asgn.id"
            . " WHERE FIND_IN_SET($batch_id, asgn.batch_ids) AND asgn.deleted_status=0 AND asgn.content_type='Homework' AND asgn.class_id= $class_id AND asgn.subject_code='$subject_code' AND asgn.school_id=". login_user()->user->sh_id. " ";

                $Homeworks = $this->admin_model->dbQuery($query.$where_part);
            //print_r($assignments);
        }
        

        $students = array();
        $data = array();
        $where_part1 = "";
        $where_sub_mat = "";
        $where_std = "";
        
        if ($request->from != "" && $request->to == "") {
            
            $from_date = to_mysql_date($request->from);
            $where_part1 .=  " AND asgn.published_date='$from_date' ";

        }
        if ($request->from == "" && $request->to != "") {
         
            $to_date = to_mysql_date($request->to);
            $where_part1 .=  " AND asgn.published_date='to_date' ";

        }
        if ($request->from != "" && $request->to != "") {
            
            $from_date = to_mysql_date($request->from);
            $to_date = to_mysql_date($request->to);
            $where_part1 .= " AND asgn.published_date between '$from_date' AND '$to_date' ";

        }
        
        if(isset($request->searchKey) && !empty($request->searchKey)){
            $searchKey = $request->searchKey;
            $where_std .= " AND (std.name LIKE '%". $request->searchKey ."%' OR std.rollno LIKE '%". $request->searchKey ."%') "; 
        }
            
        if($Homeworks){
            foreach ($Homeworks as $key1 => $assgn) {

                $student_ids = explode(',', $assgn->student_ids);

                foreach ($student_ids as $key2 => $std) {

                    if($std){
                       $query = "SELECT "
                        . "std.id as student_id, std.name, std.avatar, std.gender,std.subject_group_id, std.rollno, uu.name as guardian_name, gd.relation as guardian_ralation, std.batch_id as student_batch_id, "
                        . "sh_classes.name as class_name, "
                        . "sh_batches.name as batch_name,"
                        . "sh_subjects.name as subject_name"
                        . " FROM sh_students_". login_user()->user->sh_id." as std" 
                        . " LEFT JOIN sh_student_class_relation std_cls_rel ON std_cls_rel.student_id = std.id AND std_cls_rel.deleted_at is NULL"
                        . " INNER JOIN sh_classes ON std_cls_rel.class_id = sh_classes.id AND sh_classes.deleted_at is NULL AND sh_classes.academic_year_id =".$this->session->userdata("userdata")["academic_year"]." "
                        . " LEFT JOIN sh_batches ON sh_batches.id =  std_cls_rel.batch_id AND sh_batches.deleted_at is NULL AND std_cls_rel.academic_year_id =".$this->session->userdata("userdata")["academic_year"]." "
                        . " INNER JOIN sh_subjects ON sh_subjects.code = '$subject_code' AND sh_subjects.deleted_at is NULL "
                        . " LEFT JOIN sh_student_guardians gd ON gd.student_id = std.id AND gd.deleted_at is NULL"
                        . " LEFT JOIN sh_users uu ON uu.id = gd.guardian_id AND uu.deleted_at = 0"
                        . " WHERE std.deleted_at=0 AND std.id=$std ";
                            
                        $record_std = $this->admin_model->dbQuery($query. $where_std);
                        if($record_std){
                            $data[$key2]["student"] = $record_std[0];
                        }else{
                            $data[$key2]["student"] = "";
                        }


                         $query1 = "SELECT "
                        . "asgn.*,"
                        . "sh_classes.name as class_name, "
                        . "sh_batches.name as batch_name,"
                        . "su.name as student_name,"
                        . "su.avatar as student_avatar,"
                        . "tu.name as teacher_name,"
                        . "tu.avatar as teacher_avatar,"
                        . "sub_mat.id as submit_id,sub_mat.student_id, sub_mat.date, sub_mat.submitted_files, sub_mat.submitted_details, sub_mat.obtained_marks, sub_mat.remarks, sub_mat.viewed, date_format(sub_mat.created_at,'%Y-%m-%d') as submitted_date"
                        . " FROM sh_assignments as asgn "
                        . " INNER JOIN sh_classes ON asgn.class_id = sh_classes.id "
                        . "LEFT JOIN sh_batches ON sh_batches.id =  $batch_id "
                        . "LEFT JOIN sh_submit_material sub_mat ON sub_mat.material_id = asgn.id AND sub_mat.student_id = $std $where_sub_mat "
                        . " LEFT JOIN sh_users tu ON tu.id = asgn.uploaded_by AND tu.deleted_at = 0" 
                        . " LEFT JOIN sh_users su ON su.id = sub_mat.student_id AND su.deleted_at = 0" 

                        . " WHERE FIND_IN_SET($std, asgn.student_ids) AND asgn.batch_ids = $batch_id AND asgn.deleted_status=0 AND asgn.content_type='Homework' AND asgn.class_id= $class_id AND asgn.subject_code='$subject_code' AND asgn.school_id=". login_user()->user->sh_id. " ";

                        $data[$key2]["Homeworks"] = $this->admin_model->dbQuery($query1.$where_part1);
                        //print_r($this->db-last_query());die();

                    }
                  
                }
            }
        }

        if (count($data) == '0') {
            echo json_encode($data); die();
        }

        for ($i = 0; $i < count($data[0]['Homeworks']); $i++) {
            $data[0]['Homeworks'][$i]->files = explode(",", $data[0]['Homeworks'][$i]->files);
            $data[0]['Homeworks'][$i]->submitted_files = explode(",", $data[0]['Homeworks'][$i]->submitted_files);
        }

        foreach ($data as $key => $dat) { 

            if ($dat['student'] == "") {
                unset($data[$key]);
            } 
        }
        
        foreach ($data as $key => $dta) {

            $allCount=0;
            $data[$key]['student']->submit_count=0;
            foreach ($dta['Homeworks'] as $key1 => $dt) {
                $allCount++;
                $data[$key]['student']->allCount=$allCount;
                if ($dt->obtained_marks != "" || $dt->remarks != "") {
                   $data[$key]['student']->submit_count=++$data[$key]['student']->submit_count;
                } 
            }
        }

        if ($due) 
        {
            foreach ($data as $key => $d)
            {
            
                if ($d['student']->allCount == $d['student']->submit_count)
                {
                    unset($data[$key]);
                } 
            } 
        }  

        $current_date == date("Y-m-d");

        foreach ($data as $key => $det) {
            foreach ($det['Homeworks'] as $key2 => $de) {
                if ($de->submitted_date){
                    if($de->due_date >= $de->submitted_date){
                         $de->{"status"} = 'submitted'; 
                    }else{
                        $de->{"status"} = 'late'; 
                    }
                } else{
                    $de->{"status"} = 'not_submit';
                   
            } 
        }
    }


    $duplicate_keys = array();
    $tmp = array();       

    foreach ($data as $key => $val){
        if (is_object($val['student']))
            $val['student'] = (array)$val['student'];

        if (!in_array($val['student'], $tmp))
            $tmp[] = $val['student'];
        else
            $duplicate_keys[] = $key;
    }

    foreach ($duplicate_keys as $key){
        unset($data[$key]);
    }

    foreach ($data as $key => $sub) {
        if ($sub['student']->student_batch_id != $batch_id) {
            unset($data[$key]);
        }
    }
    

        echo json_encode(array_values($data));
    }

    public function set_viewed_assignment_homework(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        //print_r($request);die();
        $this->db->where('id', $request->assignment_id);
        return $this->db->update('sh_submit_material', array('viewed' => true));
    }

    

    public function update_batches()
    {
        $result=$this->db->select('*')->from('sh_study_material')->where('delete_status',0)->get()->result();
        foreach($result as $res)
        {
            if(strlen($res->batch_id) > 6)
            {
                $academicid=$this->db->select('id')->from('sh_academic_years')->where('school_id',$res->school_id)->where('is_active','Y')->get()->row();
                $rec=$this->db->select('id')->from('sh_batches')->where('school_id',$res->school_id)->where('class_id',$res->class_id)->where('academic_year_id',$academicid->id)->where(array('deleted_at' => NULL))->get()->result();
                $b=array();
                foreach($rec as $b_id)
                {
                    $b[]=$b_id->id;
                }
                $b=implode(',',$b);
                $this->db->where('id', $res->id)->update('sh_study_material', array('batch_id' => $b));
            }
        }
    }
    
    public function update_subjectcodes()
    {
        $result=$this->db->select('*')->from('sh_study_material')->where('delete_status',0)->get()->result();
        foreach($result as $res)
        {
            if($res->batch_id!='')
            {
                if(strlen($res->batch_id) > 6)
                {
                    $academicid=$this->db->select('id')->from('sh_academic_years')->where('school_id',$res->school_id)->where('is_active','Y')->get()->row();
                    $allbatch=explode(',',$res->batch_id);
                    $exc=array();
                    $sub_codes=array();
                    foreach($allbatch as $b_id)
                    {
                        $rec=$this->db->select('code')->from('sh_subjects')->where('school_id',$res->school_id)->where('class_id',$res->class_id)->where('academic_year_id',$academicid->id)->where('batch_id',$b_id)->where(array('deleted_at' => NULL))->get()->result();
                        $exc[]=$rec;
                    }
                   
                    if(sizeof($exc) > 0)
                    {
                        foreach($exc as $ex)
                        {
                            if(sizeof($ex) > 0)
                            {
                                foreach($ex as $e)
                                {
                                    $sub_codes[]=$e->code;
                                }
                            }
                        }
                    }
                    $sub_codes=array_unique($sub_codes);
                    $sub_codes=implode(',',$sub_codes);
                    $this->db->where('id', $res->id)->update('sh_study_material', array('subject_code' => $sub_codes));
                }
            }
        }
    }

 public function SaveAssignmentMark(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->submit_id;

        $data = array(
            'obtained_marks' => $request->obtained_marks,
            'remarks' => $request->remarks
        );

        $res = $this->study_model->AssignmentMarked($id, $data);

        $response['message'] = "Assignment marked succesfully";             

        echo json_encode($response);
    }

    public function SaveHomeworkMark(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->submit_id;

        $data = array(
            'obtained_marks' => $request->obtained_marks,
            'remarks' => $request->remarks
        );

        $res = $this->study_model->AssignmentMarked($id, $data);

        $response['message'] = "Homework marked succesfully";             

        echo json_encode($response);
    }    


    public function getMaterilasForStudentDashboard(){
        $user_id = $this->session->userdata("userdata")["user_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $student = $this->db->select('s.class_id, s.batch_id, s.subject_group_id')->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->where('s.id', $user_id)->get()->row();

        $b_n = $this->db->select('name')->from('sh_batches')->where('school_id' , $school_id)->where('class_id' , $student->class_id)->where('id' , $student->batch_id)->where('deleted_at' , null)->get()->result();
        foreach($b_n as $b)
        {
            $b_name = $b->name;
        }
        if($student){
            if($student->subject_group_id != "" && $student->subject_group_id != null && $student->subject_group_id != 0){
                $subject_ids = $this->db->select('subjects')->from('sh_subject_groups')->where('id', $student->subject_group_id)->get()->row()->subjects;
                $subject_ids = explode(",", $subject_ids);
                foreach ($subject_ids as $key => $value) {
                    $subject_ids[$key] = $this->db->select('code')->from('sh_subjects')->where('id', $value)->get()->row()->code;
                }
                $materials = $this->study_model->studentmaterialfordashboard($school_id, $student->class_id, $student->batch_id, '', '', $subject_ids,'');
            }else{
                $materials = $this->study_model->studentmaterialfordashboard($school_id, $student->class_id, $student->batch_id, '', '','');
            }
            //Azeem remove future assignments, not show before date
            foreach ($materials as $key => $value) {
                
                if($value["uploaded_at"] > date("Y-m-d") && $value["content_type"] == "Assignment"){
                    unset($materials[$key]);
                }
                $materials[$key]['batch_name'] = $b_name;
            }
            $materials = array_values($materials);
            $data['materials'] = $materials;


        }else{
            $data["materials"] = array();
        }
        for ($i = 0; $i < count($data['materials']); $i++) {
            $data['materials'][$i]['files'] = explode(",", $data['materials'][$i]['files']);
        }
       
        echo json_encode($data);
    }
        
        
}