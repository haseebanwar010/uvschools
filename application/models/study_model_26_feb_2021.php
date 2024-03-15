<?php

class Study_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function getClasses($school_id) {
        $query = $this->db->select('*')->from('sh_classes')->where('school_id', $school_id)
        ->where('deleted_at is null', null, false)->order_by("name", "asc")->get();
        return $query->result_array();
    }

    public function getBatches($class_id) {
        $query = $this->db->select('*')->from('sh_batches')->where('class_id', $class_id)
        ->where('deleted_at is null', null, false)->order_by("name", "asc")->get();
        return $query->result_array();
    }

    public function getSubjects($class_id, $batch_id, $subjects = false) {
        // echo '<pre>';
        // print_r($class_id);
        // print_r($batch_id);
        // print_r($subjects);
        // die;
        // echo json_encode('die');
        // die;
        if($subjects){
            if ($batch_id != "all") {
                $query = $this->db->select('sh_subjects.*,sh_batches.name as batch_name')->from('sh_subjects')->where('sh_subjects.class_id', $class_id)->where('batch_id', $batch_id)->join('sh_batches', 'sh_subjects.batch_id = sh_batches.id')
                ->where('sh_subjects.deleted_at is null', null, false)->where('sh_batches.deleted_at is null', null, false)->where_in('sh_subjects.id',$subjects)->order_by("sh_subjects.name", "asc")->get();
            } else {
                $query = $this->db->select('sh_subjects.*,sh_batches.name as batch_name,code')->from('sh_subjects')->where('sh_subjects.class_id', $class_id)->join('sh_batches', 'sh_subjects.batch_id = sh_batches.id')
                ->where('sh_subjects.deleted_at is null', null, false)->where('sh_batches.deleted_at is null', null, false)->where_in('sh_subjects.id',$subjects)->group_by('code')->order_by("sh_subjects.name", "asc")->get();
            }
        }else{
            if ($batch_id != "all") {
                $query = $this->db->select('sh_subjects.*,sh_batches.name as batch_name')->from('sh_subjects')->where('sh_subjects.class_id', $class_id)->where('batch_id', $batch_id)->join('sh_batches', 'sh_subjects.batch_id = sh_batches.id')
                ->where('sh_subjects.deleted_at is null', null, false)->where('sh_batches.deleted_at is null', null, false)->order_by("sh_subjects.name", "asc")->get();
            } else {
                $query = $this->db->select('sh_subjects.*,sh_batches.name as batch_name')->from('sh_subjects')->where('sh_subjects.class_id', $class_id)->join('sh_batches', 'sh_subjects.batch_id = sh_batches.id')
                ->where('sh_subjects.deleted_at is null', null, false)->where('sh_batches.deleted_at is null', null, false)->group_by('code')->order_by("sh_subjects.name", "asc")->get();
            }
        }

        return $query->result_array();
    }

    // public function newMaterial($title, $content_type, $class_id, $batch_id, $subject, $files, $school_id, $details, $uploaded_by, $uploaded_at, $subject_code) {
    //     $data = array(
    //         'title' => $title,
    //         'content_type' => $content_type,
    //         'class_id' => $class_id,
    //         'batch_id' => $batch_id,
    //         'subject_id' => $subject,
    //         'files' => $files,
    //         'school_id' => $school_id,
    //         'details' => $details,
    //         'uploaded_by' => $uploaded_by,
    //         'uploaded_at' => $uploaded_at,
    //         'subject_code' => $subject_code
    //     );

    //     $this->db->insert('sh_study_material', $data);
    // }
    
    public function newMaterial($title, $content_type, $class_id, $batch_id, $subject, $storage_type, $files, $all_fnames, $allfileurls, $all_fileIds, $allthumbnail_links, $allicon_links, $school_id, $details, $uploaded_by,$uploaded_at, $subject_code) {
        $data = array(
            'title' => $title,
            'content_type' => $content_type,
            'class_id' => $class_id,
            'batch_id' => $batch_id,
            'subject_id' => $subject,
            'files' => $files,
            'file_names' => $all_fnames,
            'filesurl' => $allfileurls,
            'fileids' => $all_fileIds,
            'thumbnail_links' => $allthumbnail_links,
            'icon_links' => $allicon_links,
            'storage_type' => $storage_type,
            'school_id' => $school_id,
            'details' => $details,
            'uploaded_by' => $uploaded_by,
            'uploaded_at' => $uploaded_at,
            'subject_code' => $subject_code
        );

        $this->db->insert('sh_study_material', $data);
    }
    
    public function deleteMaterial($id) {
        $data = array('delete_status' => 1);
        $this->db->where('id', $id)->update('sh_study_material', $data);
    }
    
    public function getdeletedfilesId($id)
    {
        $result=$this->db
        ->select('storage_type,fileids,files,file_names,filesurl,thumbnail_links')
        ->from('sh_study_material')
        ->where('id',$id)
        ->get()->row();
        return $result;
    }    
    public function get_AssigdeletedfilesId($id)
    {
        $result=$this->db
        ->select('storage_type,fileids,files,file_names,filesurl,thumbnail_links')
        ->from('sh_assignments')
        ->where('id',$id)
        ->get()->row();
        return $result;
    }
    public function newAssignment($title, $type, $class_id, $batches, $subject_id, $files, $school_id, $details, $material_details,$uploaded_by,$uploaded_at, $subject_code, $marks, $due_date, $student_ids) {
        $data = array(
            'title' => $title,
            'content_type' => $type,
            'class_id' => $class_id,
            'batch_ids' => $batches,
            'subject_id' => $subject_id,
            'files' => $files,
            'school_id' => $school_id,
            'details' => $details,
            'uploaded_by' => $uploaded_by,
            'published_date' => $uploaded_at,
            'subject_code' => $subject_code,
            'total_marks' => $marks,
            'due_date' => $due_date,
            'material_details' => $material_details,
            'student_ids' => $student_ids
        );

        $this->db->insert('sh_assignments', $data);
    }

    // public function deleteMaterial($id) {
    //     $data = array('delete_status' => 1);
    //     $this->db->where('id', $id)->update('sh_study_material', $data);
    // }

    public function deleteAssignment($id) {
        $data = array('deleted_status' => 1);
        $this->db->where('id', $id)->update('sh_assignments', $data);
    }

    public function getParentsMultiple($student_ids){
        $student_ids = explode(",",$student_ids);
        $temp = $this->db->select('guardian_id')->from('sh_student_guardians')->where_in('student_id',$student_ids)->get()->result_array();
        $arr = array_map (function($value){
            return $value['guardian_id'];
        } , $temp);
        
        return ($arr);

    }
    public function getStudentsAndParents($class_id,$batch_id,$school_id){
        if($batch_id == "all"){
            $temp = $this->db->select('id')->from('students_'.$school_id)->where('class_id',$class_id)->where('school_id',$school_id)->get()->result_array();
        }
        else{
            $temp = $this->db->select('id')->from('students_'.$school_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('school_id',$school_id)->get()->result_array();
        }

        $arr = array_map (function($value){
            return $value['id'];
        } , $temp);
        array_push($arr, 0);
        

        $temp = $this->db->select('guardian_id')->from('sh_student_guardians')->where_in('student_id',$arr)->get()->result_array();
        $arr2 = array_map (function($value){
            return $value['guardian_id'];
        } , $temp);

        return array_merge($arr,$arr2);
    }

    public function updateAssignmnet($id, $data) {
        $this->db->where('id', $id)->update('sh_assignments', $data);
    }

    public function updateMaterial($id, $data) {
        $this->db->where('id', $id)->update('sh_study_material', $data);
    }

    public function getMaterials($school_id) {

        $query = $this->db
        ->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')
        ->select('date_format(sh_study_material.created_at,"%d/%m/%Y") as uploaded_time', false)
        ->from('sh_study_material')
        ->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')
        ->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')
        ->join('sh_subjects', 'sh_study_material.subject_id = sh_subjects.id')
        ->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')
        ->where('sh_study_material.delete_status', 0)
        ->where('sh_study_material.school_id', $school_id)
        ->get();
        return $query->result_array();
    }

    public function getDownloadMaterials($school_id) {
        $query = $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.created_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.subject_id = sh_subjects.id')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->get();
        return $query->result_array();
    }

    public function filter($school_id, $class_id, $batch_id, $selected_date, $subject_id, $type) {
        $subject_code = "";
        if($subject_id != 0 && $subject_id != ""){
            
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }

        $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.subject_code = sh_subjects.code', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->where("sh_classes.academic_year_id", $this->session->userdata("userdata")["academic_year"]);
        if($class_id != ""){
            $this->db->where('sh_study_material.class_id', $class_id);
            
        }
        if($batch_id != "" && $batch_id != 0){
            $this->db->where_in('sh_study_material.batch_id', array($batch_id, 0));
        }
        if($subject_code != ""){
            $this->db->where('(sh_subjects.class_id = '.$class_id." or sh_study_material.subject_code = '')");
            $this->db->where_in('sh_study_material.subject_code', array($subject_code, ""));
        }

        if($type != ""){
            $this->db->where('sh_study_material.content_type', $type);
        }

        if($selected_date != "" && $selected_date != "error"){
            $this->db->where('sh_study_material.uploaded_at',$selected_date);
        }
        

        $query = $this->db->group_by('sh_study_material.id')->order_by('sh_study_material.uploaded_at','desc')->get();
        // last_query();
        return $query->result_array();
    }
    
    //added by zafar
    public function filter_material_general($school_id, $class_id, $batch_id, $selected_date, $subject_id, $type, $user_id) {
        
        // echo ' schoolid '.$school_id;
        // echo ' class_id '.$class_id;
        // echo ' batch_id '.$batch_id;
        // echo ' selected_date '.$selected_date;
        // echo ' subject_id '.$subject_id;
        // echo ' type '.$type;
        // echo ' user_id '.$user_id;
        // die;
        // echo json_encode('die');
        // die;
        $subjteacher_id="";
        $all_subjteach=array();
        
        $headteacher_id="";
        $all_headteach=array();
        
        $ahoa_arr=array();
        
        $admin_id=$this->db->select('id')->from('sh_users')->where('school_id',$school_id)->where('role_id',1)->where('deleted_at',0)->get()->row()->id;
        
        if($this->session->userdata("userdata")["role_id"]!=1)
        {
            $assistent_data=$this->db->select('*')->from('sh_assign_subjects')->where('school_id',$school_id)->where('class_id',intval($class_id))->where("batch_id IN (".$batch_id.")",NULL, false)->where('assistant_id',$user_id)->where('deleted_at is NULL',NULL)->get()->result();
            $testquery_count=$this->db->select('*')->from('sh_assign_subjects')->where('school_id',$school_id)->where('class_id',intval($class_id))->where("batch_id IN (".$batch_id.")",NULL, false)->where('assistant_id',$user_id)->where('deleted_at is NULL',NULL)->count_all_results();
            if($testquery_count >= 1)
            {
                foreach($assistent_data as $key => $ass_teacher)
                {
                    if($ass_teacher->teacher_id!='' && $ass_teacher->teacher_id!=0)
                    {
                        $all_subjteach[]=$ass_teacher->teacher_id;
                    }
                }
                $subjteacher_id=implode(',',$all_subjteach);
            }
                    
                    
            $headteacher_data=$this->db->select('*')->from('sh_assign_subjects')->where('school_id',$school_id)->where('class_id',intval($class_id))->where("batch_id IN (".$batch_id.")",NULL, false)->where('teacher_id',$user_id)->where('deleted_at is NULL',NULL)->get()->result();
            // echo '<pre>';
            // print_r($headteacher_data);
            // die;
            // echo json_encode('die');
            // die;
            if(sizeof($headteacher_data) >= 1)
            {
                foreach($headteacher_data as $key => $headteach_assig)
                {
                    if($headteach_assig->assistant_id!='' && $headteach_assig->assistant_id!=0)
                    {
                        $all_headteach[]=$headteach_assig->assistant_id;
                    }
                    $ahoa_arr[]=$headteach_assig->subject_id;
                }
                $headteacher_id=implode(',',$all_headteach);
            }
            
        }

        
        // $head_count=$this->db->select('*')->from('sh_assign_subjects')->where('school_id',$school_id)->where('class_id',intval($class_id))->where("batch_id IN (".$batch_id.")",NULL, false)->where('teacher_id',$user_id)->where('deleted_at is NULL',NULL)->count_all_results();

        // echo '<pre>';
        // print_r($ahoa_arr);
        // die;
        // echo json_encode('die');
        // die;
        
        $role_id = $this->session->userdata("userdata")["role_id"];
        $subject_code ='';
        $subject_ids_ofcodes ='';
        if($subject_id == '' || $subject_id == 'all')
        {
            $ahoo_value=implode(',',$ahoa_arr);
            $storage_val=array();
            $storage_val_ids=array();
            
            if($this->session->userdata("userdata")["role_id"]!=1)
            {
                // $ahoooo=$this->db->distinct('code,id')->from('sh_subjects')->where("id IN (".$ahoo_value.")",NULL, false)->where('school_id',$school_id)->where('class_id',intval($class_id))->where("batch_id IN (".$batch_id.")",NULL, false)->where('deleted_at is NULL',NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->group_by('code')->get()->result();
                $ahoooo=$this->db->select('*')->from('sh_subjects')->where("id IN (".$ahoo_value.")",NULL, false)->where('school_id',$school_id)->where('class_id',intval($class_id))->where("batch_id IN (".$batch_id.")",NULL, false)->where('deleted_at is NULL',NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->result();
                // echo $this->db->last_query();
                // echo '<pre>';
                // print_r($ahoooo);
                // die;
                // echo json_encode('die');
                // die;
                foreach($ahoooo as $rec_aho)
                {
                    $storage_val[]=$rec_aho->code;
                    $storage_val_ids[]=$rec_aho->id;
                }
                $subject_code = implode(',',$storage_val);
                $subject_ids_ofcodes = implode(',',$storage_val_ids);
            }
            else
            {
                $ahoooo=$this->db->select('*')->from('sh_subjects')->where('school_id',$school_id)->where('class_id',$class_id)->where("batch_id IN (".$batch_id.")",NULL, false)->where('deleted_at is NULL',NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->result();
                foreach($ahoooo as $rec_aho)
                {
                    $storage_val[]=$rec_aho->code;
                    $storage_val_ids[]=$rec_aho->id;
                }
                $subject_code = implode(',',$storage_val);
                $subject_ids_ofcodes = implode(',',$storage_val_ids);
            }
            
        }
        else if($subject_id != 'all')
        {
            //   $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            //     if($subject_row){
            //         $subject_code = $subject_row->code;
            //     }
            if(strlen($batch_id) >= 5)
            {
                $ahoo_value=implode(',',$ahoa_arr);
                $storage_val=array();
                $storage_val_ids=array();
                
                if($this->session->userdata("userdata")["role_id"]!=1)
                {
                    $ahoooo=$this->db->select('*')->from('sh_subjects')->where("id IN (".$ahoo_value.")",NULL, false)->where('school_id',$school_id)->where('class_id',intval($class_id))->where("batch_id IN (".$batch_id.")",NULL, false)->where('deleted_at is NULL',NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->result();
                    foreach($ahoooo as $rec_aho)
                    {
                        $storage_val[]=$rec_aho->code;
                        $storage_val_ids[]=$rec_aho->id;
                    }
                    $subject_code = implode(',',$storage_val);
                    $subject_ids_ofcodes = implode(',',$storage_val_ids);
                }
                else
                {
                    $ahoooo=$this->db->select('*')->from('sh_subjects')->where('school_id',$school_id)->where('class_id',$class_id)->where("batch_id IN (".$batch_id.")",NULL, false)->where('deleted_at is NULL',NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->result();
                    foreach($ahoooo as $rec_aho)
                    {
                        $storage_val[]=$rec_aho->code;
                        $storage_val_ids[]=$rec_aho->id;
                    }
                    $subject_code = implode(',',$storage_val);
                    $subject_ids_ofcodes = implode(',',$storage_val_ids);
                }
            }
            else if(strlen($batch_id) < 5)
            {
                $subject_ids_ofcodes=$subject_id;
            }
        
        } 
        // else if($subject_id == 'all'){
        //     $subject_code = '';
        // }
        else {
            $subject_code =''; 
        }


        // $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.subject_code = sh_subjects.code', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', intval($school_id))->where("sh_classes.academic_year_id", intval($this->session->userdata("userdata")["academic_year"]));
        $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.subject_id = sh_subjects.id', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', intval($school_id))->where("sh_classes.academic_year_id", intval($this->session->userdata("userdata")["academic_year"]));
        
        if($class_id != ""){
            $this->db->where('sh_study_material.class_id', intval($class_id));
            
        }
        if($batch_id != "" && $batch_id != 0){
            // $this->db->where_in('sh_study_material.batch_id', $batch_id);
            // $this->db->where_in('sh_study_material.batch_id', $batch_id, FALSE);
            // $this->db->where("sh_study_material.batch_id IN (".$batch_id.")",NULL, false);
            // $this->db->where("FIND_IN_SET($batch_id, sh_study_material.batch_id)");
            $batch_id=explode(',',$batch_id);
            $batch_where='';
            foreach($batch_id as $key => $bid)
            {
                if($key==0)
                {
                    $batch_where .="FIND_IN_SET('".$bid."', sh_study_material.batch_id)";
                }
                else
                {
                    $batch_where .=" OR FIND_IN_SET('".$bid."', sh_study_material.batch_id) ";
                }
            }
            $this->db->where("$batch_where");
        }
        
        // if($this->session->userdata("userdata")["role_id"]==1)
        // {
        //     if($subject_code !='')
        //     {
        //         // $this->db->where_in('sh_study_material.subject_code', $subject_code);
        //         $this->db->where("FIND_IN_SET('".$subject_code."', sh_study_material.subject_code)");
        //     }
        // }
                
        // if($this->session->userdata("userdata")["role_id"]!=1)
        // {
            if($subject_ids_ofcodes !='')
            {
                $subject_ids_ofcodes=explode(',',$subject_ids_ofcodes);
                $subject_idsWhere='';
                // $this->db->where_in('sh_study_material.subject_code', $subject_code);
                // $this->db->where("FIND_IN_SET('".$subject_ids_ofcodes."', sh_study_material.subject_id)");
                foreach($subject_ids_ofcodes as $key => $innercodes)
                {
                    if(sizeof($subject_ids_ofcodes) > 1)
                    {
                        if($key==0)
                        {
                            $subject_idsWhere .="( FIND_IN_SET('".$innercodes."', sh_study_material.subject_id)";
                        }
                        else if($key==sizeof($subject_ids_ofcodes)-1)
                        {
                            $subject_idsWhere .=" OR FIND_IN_SET('".$innercodes."', sh_study_material.subject_id) )";
                        }
                        else
                        {
                            $subject_idsWhere .=" OR FIND_IN_SET('".$innercodes."', sh_study_material.subject_id) ";
                        }
                    }
                    else if(sizeof($subject_ids_ofcodes) == 1)
                    {
                        $subject_idsWhere .="( FIND_IN_SET('".$innercodes."', sh_study_material.subject_id) )";
                    }

                }
                $this->db->where("$subject_idsWhere");
                // $this->db->where("sh_study_material.subject_id IN (".$subject_ids_ofcodes.")",NULL, false);
            }
        // }
        

        if($type != ""){
            $this->db->where('sh_study_material.content_type', $type);
        }

        if($selected_date != "" && $selected_date != "error"){
            $this->db->where('sh_study_material.uploaded_at',$selected_date);
        }
        
        if($role_id!=1)
        {
            if($user_id!="")
            {
                if($subjteacher_id!='')
                {
                    $this->db->where("(sh_study_material.uploaded_by=$user_id OR sh_study_material.uploaded_by=$admin_id OR sh_study_material.uploaded_by IN (".$subjteacher_id."))");
                }
                else
                {
                    if($headteacher_id!='')
                    {
                        $this->db->where("(sh_study_material.uploaded_by=$user_id OR sh_study_material.uploaded_by=$admin_id OR sh_study_material.uploaded_by IN (".$headteacher_id."))");
                    }
                    else
                    {
                        // $this->db->where('sh_study_material.uploaded_by',$user_id);
                        $this->db->where("(sh_study_material.uploaded_by=$user_id OR sh_study_material.uploaded_by=$admin_id)");
                    }
                }
                
            }
        }
        
        $query = $this->db->group_by('sh_study_material.id')->order_by('sh_study_material.uploaded_at','desc')->get()->result_array();
        // echo $this->db->last_query();
        // die;
        // echo json_encode('die');
        // die;
        
        // echo '<pre>';
        // print_r($query); 
        // die;
        // echo json_encode('die');
        // die;
        
        return $query;
    }


    public function filter_parentmaterial($school_id, $class_id, $batch_id, $subject_id, $type) {

        $subject_code = "";
        if($subject_id != 0 && $subject_id != ""){
            
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }
        // $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.subject_code = sh_subjects.code', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->where("sh_classes.academic_year_id", $this->session->userdata("userdata")["academic_year"]);
        $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.class_id = sh_subjects.class_id AND sh_subjects.code="'.$subject_code.'"', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->where("sh_classes.academic_year_id", $this->session->userdata("userdata")["academic_year"]);
        if($class_id != ""){
            $this->db->where('sh_study_material.class_id', $class_id);
            
        }
        if($batch_id != "" && $batch_id != 0){
            // $this->db->where_in('sh_study_material.batch_id', array($batch_id, 0));
            $this->db->where("FIND_IN_SET($batch_id, sh_study_material.batch_id)");
        }
        if($subject_code != ""){
            // $this->db->where('sh_subjects.class_id', $class_id);
            // $this->db->where('sh_study_material.subject_code', $subject_code);
            $this->db->where("FIND_IN_SET('".$subject_code."', sh_study_material.subject_code)");
        }

        if($type != ""){
            $this->db->where('sh_study_material.content_type', $type);
        }

        if(isset($selected_date) && $selected_date != "" && $selected_date != "error"){
            $this->db->where('sh_study_material.uploaded_at',$selected_date);
        }
        

        $query = $this->db->group_by('sh_study_material.id')->order_by('sh_study_material.uploaded_at','desc')->get()->result_array();
        return $query;
    }


    public function filter_studentmaterial($school_id, $class_id, $batch_id, $subject_id, $type, $subject_codes, $selected_date) {
                    // filter_studentmaterial($school_id, $student->class_id, $student->batch_id, '', '', $subject_ids);
        // echo ' schooid '.$school_id;
        // echo ' $class_id '.$class_id;
        // echo ' $batch_id '.$batch_id;
        // echo ' $subject_id '.$subject_id;
        // echo ' $type '.$type;
        // echo ' $subject_codes '.$subject_codes;
        // echo ' $selected_date '.$selected_date;
        // die;
        // echo json_encode('die');
        // die;

        $subject_code = "";
        
        if($subject_id != 0 && $subject_id != ""){
            
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }
        
        // $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.subject_code = sh_subjects.code', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->where("sh_classes.academic_year_id", $this->session->userdata("userdata")["academic_year"]);
        $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.class_id = sh_subjects.class_id AND sh_subjects.code="'.$subject_code.'"', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->where("sh_classes.academic_year_id", $this->session->userdata("userdata")["academic_year"]);
        if($class_id != ""){
            $this->db->where('sh_study_material.class_id', $class_id);
            
        }
        if($batch_id != "" && $batch_id != 0){
            // $this->db->where_in('sh_study_material.batch_id', array($batch_id));
            // $this->db->where("sh_study_material.batch_id IN (".$batch_id.")",NULL, false); 
            // $this->db->like('sh_study_material.batch_id', $batch_id);
            $this->db->where("FIND_IN_SET($batch_id, sh_study_material.batch_id)");
        }
        if($subject_code != ""){
            // $this->db->where('sh_subjects.class_id', intval($class_id));
            // $this->db->where('sh_study_material.subject_code', $subject_code);
            $this->db->where("FIND_IN_SET('".$subject_code."', sh_study_material.subject_code)");
        }

        if($type != ""){
            $this->db->where('sh_study_material.content_type', $type);
        }

        if(isset($selected_date) && $selected_date != "" && $selected_date != "error"){
            $this->db->where('sh_study_material.uploaded_at',$selected_date);
        }


        

        //$query = $this->db->group_by('sh_study_material.id')->order_by('sh_study_material.uploaded_at','desc')->get();
        $query = $this->db->group_by('sh_study_material.id')->order_by('sh_study_material.uploaded_at','desc')->get()->result_array();
        
        if($subject_codes!='')
        {
            // $this->db->where("(sh_study_material.subject_code IN  ('" . implode( "','", $subject_codes ) . "') or sh_study_material.subject_code is null or sh_study_material.subject_code = '')");
            // $this->db->like("(sh_study_material.subject_code in  ('" . implode( "','", $subject_codes ) . "') or sh_study_material.subject_code is null or sh_study_material.subject_code = '')");
            
            // $subject_codes=implode(',',$subject_codes);
            
            foreach($subject_codes as $subject_co)
            {
                $sub_name = "";
               $sub_name=$this->db->select("name")->from('sh_subjects')->where('code', $subject_co)->get()->row()->name;
               
            //   echo $sub_name;
              
               foreach($query as $key => $que)
               {
                //   echo '<pre>';
                //   print_r($que['subject_code']);
                   
                   $sub_c=explode(',',$que['subject_code']);
                   
                //   echo'<pre>';
                //   print_r($sub_c);
                   
                   if(in_array($subject_co,$sub_c))
                   {
                      $query[$key]['subject_name']=$sub_name;
                    //   echo $query[$key]['subject_name'];
                       
                   }
               }
            }
            
            
        }
        
        
        // echo '<pre>';
        // print_r($query);
        // die;
        
        // echo 'hasee ';
        // echo $this->db->last_query();
        // die;
        // echo json_encode('die');
        // die;
        return $query;
    }

    public function filter2($school_id, $class_id, $batch_id, $subject_id,  $subject_code, $type, $selected_date) {
        if ($batch_id == 0) {
            $query = $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')
            ->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)
            ->from('sh_study_material')
            ->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')
            ->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')
            ->join('sh_subjects', 'sh_study_material.subject_id = sh_subjects.id')
            ->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')
            ->where('sh_study_material.delete_status', 0)
            ->where('sh_study_material.school_id', $school_id)
            ->where('sh_study_material.class_id', $class_id)
            ->where('sh_study_material.batch_id', $batch_id)
            // ->where('sh_study_material.subject_id', $subject_id)
            ->where("FIND_IN_SET('sh_study_material.subject_code', '$subject_code')")
             ->or_where('sh_study_material.uploaded_at',$selected_date)
             ->where('sh_study_material.content_type', $type)
            ->order_by('sh_study_material.uploaded_at','desc')
            ->get()->result_array();
            echo "<pre>";
        echo $this->db->last_query();
        echo "</pre>";
        die;
        } else {
            $query = $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')
            ->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)
            ->from('sh_study_material')
            ->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')
            ->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')
            ->join('sh_subjects', 'sh_study_material.subject_id = sh_subjects.id')
            ->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')
            ->where('sh_study_material.delete_status', 0)
            ->where('sh_study_material.school_id', $school_id)
            ->where('sh_study_material.class_id', $class_id)
            ->where_in('sh_study_material.batch_id', array($batch_id, 0))
            ->where('sh_study_material.content_type', $type)
            ->where("FIND_IN_SET('sh_study_material.subject_code','".$subject_code."')")
            ->or_where('sh_study_material.uploaded_at',$selected_date)
            ->order_by('sh_study_material.uploaded_at','desc')
            ->get()->result_array();
        //     echo "<pre>";
        //     echo "second";
        // echo $this->db->last_query();
        // echo "</pre>";
        // die;
        }
        
        return $query;
    }
    
     public function AssignmentMarked($id, $data){
        $res = $this->db->where('id', $id)->update('sh_submit_material', $data);
        return $res;
    }

    public function filter_study_material_app($school_id, $class_id, $batch_id, $subject_id, $subject_codes, $type, $selected_date) {
                                        // $school_id, $class_id, $batch_id, $subject_id,  $subject_code, $type, $selected_date
                    // filter_studentmaterial($school_id, $student->class_id, $student->batch_id, '', '', $subject_ids);
                    
         $academic_year = $this->db->select('id')->from('sh_academic_years')->where('school_id', $school_id)->where('is_active ="Y" ')->where('deleted_at is NULL')->get()->result_array();
         
         if($academic_year){
            $academic_year_id = $academic_year[0]['id']; 
         }
        $subject_code = "";
        
        if($subject_id != 0 && $subject_id != ""){
            
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->get()->row();
            
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }
        
        // $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.subject_code = sh_subjects.code', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->where("sh_classes.academic_year_id", $this->session->userdata("userdata")["academic_year"]);
        $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.class_id = sh_subjects.class_id AND sh_subjects.code="'.$subject_code.'"', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->where("sh_classes.academic_year_id", $academic_year_id);
        if($class_id != ""){
            $this->db->where('sh_study_material.class_id', $class_id);
            
        }
        if($batch_id != "" && $batch_id != 0){
            // $this->db->where_in('sh_study_material.batch_id', array($batch_id));
            // $this->db->where("sh_study_material.batch_id IN (".$batch_id.")",NULL, false); 
            // $this->db->like('sh_study_material.batch_id', $batch_id);
            $this->db->where("FIND_IN_SET($batch_id, sh_study_material.batch_id)");
        }
        if($subject_code != ""){
            // $this->db->where('sh_subjects.class_id', intval($class_id));
            // $this->db->where('sh_study_material.subject_code', $subject_code);
            $this->db->where("FIND_IN_SET('".$subject_code."', sh_study_material.subject_code)");
        }

        if($type != ""){
            $this->db->where('sh_study_material.content_type', $type);
        }

        if(isset($selected_date) && $selected_date != "" && $selected_date != "error"){
            $this->db->where('sh_study_material.uploaded_at',$selected_date);
        }


        

        //$query = $this->db->group_by('sh_study_material.id')->order_by('sh_study_material.uploaded_at','desc')->get();
        $query = $this->db->group_by('sh_study_material.id')->order_by('sh_study_material.uploaded_at','desc')->get()->result_array();
        
        // echo '<pre>';
        // print_r($this->db->last_query());
        // die;
        
        if($subject_codes!='')
        {
            // $this->db->where("(sh_study_material.subject_code IN  ('" . implode( "','", $subject_codes ) . "') or sh_study_material.subject_code is null or sh_study_material.subject_code = '')");
            // $this->db->like("(sh_study_material.subject_code in  ('" . implode( "','", $subject_codes ) . "') or sh_study_material.subject_code is null or sh_study_material.subject_code = '')");
            
            // $subject_codes=implode(',',$subject_codes);
            
            // foreach($subject_codes as $subject_co)
            // {
            //     $sub_name = "";
            //   $sub_name=$this->db->select("name")->from('sh_subjects')->where('code', $subject_co)->get()->row()->name;
               
            // //   echo $sub_name;
              
            //   foreach($query as $key => $que)
            //   {
            //     //   echo '<pre>';
            //     //   print_r($que['subject_code']);
                   
            //       $sub_c=explode(',',$que['subject_code']);
                   
            //     //   echo'<pre>';
            //     //   print_r($sub_c);
                   
            //       if(in_array($subject_co,$sub_c))
            //       {
            //           $query[$key]['subject_name']=$sub_name;
            //         //   echo $query[$key]['subject_name'];
                       
            //       }
            //   }
            // }
            
            
        }
        
        
        // echo '<pre>';
        // print_r($query);
        // die;
        
        // echo 'hasee ';
        // echo $this->db->last_query();
        // die;
        // echo json_encode('die');
        // die;
        return $query;
    }
    public function studentmaterialfordashboard($school_id, $class_id, $batch_id, $subject_id, $type, $subject_codes,$selected_date) {
        $date = date("Y-m-d");
        $subject_code = "";
        if($subject_id != 0 && $subject_id != ""){
            $subject_row = $this->db->select("code")->from('sh_subjects')->where('id', $subject_id)->where('school_id', $school_id)->where('class_id', $class_id)->where('batch_id', $batch_id)->get()->row();
            if($subject_row){
                $subject_code = $subject_row->code;
            }
        }
        $this->db->select('sh_study_material.*,sh_classes.name as class_name,sh_batches.name as batch_name,sh_subjects.name as subject_name,sh_users.name')->select('date_format(sh_study_material.uploaded_at,"%d/%m/%Y") as uploaded_time', false)->from('sh_study_material')->join('sh_classes', 'sh_study_material.class_id = sh_classes.id')->join('sh_batches', 'sh_study_material.batch_id = sh_batches.id', 'left')->join('sh_subjects', 'sh_study_material.subject_code = sh_subjects.code', 'left')->join('sh_users', 'sh_study_material.uploaded_by = sh_users.id', 'left')->where('sh_study_material.delete_status', 0)->where('sh_study_material.school_id', $school_id)->where('sh_study_material.uploaded_at', $date)->where("sh_classes.academic_year_id", $this->session->userdata("userdata")["academic_year"]);
        if($class_id != ""){
            $this->db->where('sh_study_material.class_id', $class_id);
        }
        if($batch_id != ""){
            $this->db->like('sh_study_material.batch_id', $batch_id);
        }
        if($subject_code != ""){
            $this->db->where('sh_subjects.class_id', $class_id);
            $this->db->where('sh_study_material.subject_code', $subject_code);
        }
        if($type != ""){
            $this->db->where('sh_study_material.content_type', $type);
        }
        if(isset($selected_date) && $selected_date != "" && $selected_date != "error"){
            $this->db->where('sh_study_material.uploaded_at',$selected_date);
        }
        if($subject_codes){
            $this->db->like("(sh_study_material.subject_code in  ('" . implode( "','", $subject_codes ) . "') or sh_study_material.subject_code is null or sh_study_material.subject_code = '')");
        }
        $query = $this->db->group_by('sh_study_material.id')->order_by('sh_study_material.uploaded_at','desc')->get();
        return $query->result_array();
    }
}

?>