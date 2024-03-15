<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Syllabus extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function add() {
        $this->load->view("syllabus/add");
    }

    public function viewStudyPlanForParent() {
        $this->load->view("syllabus/viewStudyPlanParent");
    }
    
    function getSubjects(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_subjects.id IN (". implode(',', login_user()->t_data->subjects) .") ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 

        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){

        } else if(login_user()->user->role_id == PARENT_ROLE_ID){

        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){

        }

        $data = $this->admin_model->dbSelect("*","subjects"," class_id='$request->class_id' AND batch_id='$request->batch_id' AND deleted_at IS NULL AND school_id='$school_id' ".$where_part);
        echo json_encode($data);
    }
    
    function getSyllabus(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $res = $this->admin_model->dbSelect("*","request_log"," class_id='$request->class_id' AND batch_id='$request->batch_id' AND subject_id='$request->subject_id' AND type='$request->type' AND school_id='$school_id' AND deleted_at IS NULL and marked = 'N' ");
        $can_syllabus_edit = false;
        $request_id = NULL;
        $request_status = NULL;
        if(count($res) > 0){
            //request found
            $can_syllabus_edit = $res[0]->status=='draft' || $res[0]->status=='not-approved' ? true:false;
            $request_id = $res[0]->id;
            $request_status = $res[0]->status;
        } else {
            //request not found
            $record = array(
                "user_id"=>$this->session->userdata("userdata")["user_id"],
                "school_id"=>$school_id,
                "type"=>'syllabus',
                "class_id"=>$request->class_id,
                "batch_id"=>$request->batch_id,
                "subject_id"=>$request->subject_id
            );
            $id = $this->admin_model->dbInsert("request_log", $record);
            $can_syllabus_edit = true;
            $request_id = $id;
            $request_status = "draft";
        }
        
        $working_days_json = $this->admin_model->dbSelect("working_days","school"," id='$school_id' ")[0]->working_days;
        $var = json_decode($working_days_json);
        $not_working_days = array();
        foreach($var as $val){
            if($val->val == 'false'){
                array_push($not_working_days, $val->label);
            }
        }
        
        $weeks = $this->admin_model->dbSelect("*", "syllabus_weeks", " class_id='$request->class_id' AND batch_id='$request->batch_id' AND subject_id='$request->subject_id' AND school_id='$school_id' AND deleted_at IS NULL ");
        $sql = "SELECT "
            . "wk.id as week_id,"
            . "wkd.id as week_detail_id, "
            . "s.name as subject_name, "
            . "wk.name as weekname, "
            . "wk.start_date, "
            . "wk.end_date, "
            . "wkd.date, "
            . "wkd.edit, "
            . "wkd.topic, "
            . "wkd.status, "
            . "wkd.comments "
            . "FROM "
            . "sh_syllabus_weeks wk "
            . "INNER JOIN sh_syllabus_week_details wkd ON wk.id=wkd.syllabus_week_id "
            . "LEFT JOIN sh_subjects s ON s.id=wk.subject_id "
            . "WHERE wk.class_id='$request->class_id' "
            . "AND wk.batch_id='$request->batch_id' "
            . "AND wk.subject_id='$request->subject_id' "
            . "AND wk.school_id='$school_id' "
            . "AND wk.deleted_at IS NULL ";
        $weeks_details = $this->admin_model->dbQuery($sql);
        $ydata = array();
        //print_r($weeks_details); die();
        
        foreach($weeks as $w){
            $arr = array();
            $ydata[$w->id]["id"] = $w->id;
            $ydata[$w->id]["week"] = $w->name;
            $ydata[$w->id]["start_date"] = $w->start_date;
            $ydata[$w->id]["end_date"] = $w->end_date;
            $ydata[$w->id]["class_id"] = $w->class_id;
            $ydata[$w->id]["batch_id"] = $w->batch_id;
            $ydata[$w->id]["subject_id"] = $w->subject_id;
            
            $index=0;
            foreach($weeks_details as $d){
                if($w->id == $d->week_id){
                    $arra = array(
                        "week_detail_id"=>$d->week_detail_id,
                        "date"=>$d->date,
                        "day"=>date('l', strtotime($d->date)),
                        "topic"=>$d->topic,
                        "status"=>$d->status,
                        "comments"=>$d->comments,
                        "is_working_day"=>true,
                        "edit"=>$d->edit
                    );
                    $arr[$index++] =  $arra;
                }
            }
            
            $saved_dates = array();
            foreach($arr as $t){
                array_push($saved_dates, $t["date"]);
            }
            $dates = $this->dateDays($ydata[$w->id]["start_date"], $ydata[$w->id]["end_date"]);
            $not_saved_dates = array_diff($dates, $saved_dates);
            foreach($not_saved_dates as $n){
                
                $is_working_day = true;
                if(in_array(date('l', strtotime($n)), $not_working_days)){
                    $is_working_day = false;
                }
                
                
                $dumyArray = array(
                    "week_detail_id"=>NULL,
                    "date"=>$n,
                    "day"=>date('l', strtotime($n)),
                    "subject"=>NULL,
                    "status"=>NULL,
                    "comments"=>NULL,
                    "is_working_day"=>$is_working_day
                );
                $arr[$index++] = $dumyArray;
            }
            $ydata[$w->id]["data"] = $arr;
        }
        foreach($ydata as $key => $value){
           array_multisort( array_column($ydata[$key]['data'], "date"), SORT_ASC, $ydata[$key]['data'] );
        }
        $yydata["syllabus"] = $ydata;
        $yydata["can_syllabus_edit"] = $can_syllabus_edit;
        $yydata["request_id"] = $request_id;
        $yydata["reqeust_status"] = $request_status;
        echo json_encode($yydata);
    }
    
    function dateDays($start, $end){
        $period = new DatePeriod(
            new DateTime($start),
            new DateInterval('P1D'),
            new DateTime($end)
        );
        $arr = array();
        foreach ($period as $value) {
            array_push($arr, $value->format('Y-m-d'));
        }
        array_push($arr, $end);
        return $arr;
    }
    
    function getNextWorkingDay($last, $days){
        $next = null;
        if($last == null){
            $next = $days[0];
        } else if($last != null){
            for($i=0; $i<count($days); $i++){
                if($last == $days[$i]){
                    if($i == count($days)-1){
                        $next = $days[0];
                    } else {
                        $next = $days[$i+1];
                    }
                }
            }
        }
        return $next;
    }
    
    function saveWeek(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $startDate = strtotime(str_replace('/', '-', $request->startDate));
        $endDate = strtotime(str_replace('/', '-', $request->endDate));
        
        if($startDate > $endDate){
            $data = array("status"=>"error","message"=>lang("startdate_cant_lessthan"));
        } else { 
            $data = array();
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $active_academic_year = $this->admin_model->dbSelect("id","academic_years"," school_id='$school_id' AND is_active='Y' ")[0]->id;


            $where  = " class_id='$request->class_id' "
                . "AND batch_id='$request->batch_id' "
                . "AND subject_id='$request->subject_id' "
                . "AND school_id='$school_id' "
                . "AND academic_year_id='$active_academic_year' "
                . "AND deleted_at IS NULL ";
            $sql = "select count(case when '".to_mysql_date($request->startDate)."' < end_date and start_date < '".to_mysql_date($request->endDate)."' then 1 end) as overlap from sh_syllabus_weeks where" .$where;
            $is_record_exists = $this->admin_model->dbQuery($sql)[0]->overlap;
            if($is_record_exists > 0){
                $data = array("status"=>"error","message"=>lang('duplicate_week'));
            }else{
                $data2 = array("name"=>$request->name, "start_date"=> to_mysql_date($request->startDate), "end_date"=>to_mysql_date($request->endDate), "academic_year_id"=>$active_academic_year, "school_id"=>$school_id, "class_id"=>$request->class_id, "batch_id"=>$request->batch_id, "subject_id"=>$request->subject_id);
                $inserted_id = $this->common_model->insert("sh_syllabus_weeks",$data2);
                if($inserted_id > 0){
                    $data = array("status"=>"success","message"=>lang("new_week_success"));
                }else {
                    $data = array("status"=>"error","message"=>lang("new_week_error"));
                }
            }
        }
        echo json_encode($data);
    }
    
    function getWorkingdays(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("working_days","school"," id='$school_id'")[0]->working_days;
        echo $data;
    }
    
    function saveWeekDetail(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $comment = NULL;
        if($request->comment !== NULL){
            $comment = $request->comment;
        }
        $data = array(
            "date"=>$request->selectedDate,
            "syllabus_week_id"=>$request->selectedWeekId,
            "topic"=>$request->topic,
            "comments"=>$comment,
            "status"=>$request->status,
            "school_id"=>$school_id
        );
        $res = $this->admin_model->dbInsert("sh_syllabus_week_details", $data);
        if($res > 0){
            $response = array("status"=>"success","message"=>lang("syl_day_success"));
            echo json_encode($response);
        }
    }
    
    function changeSyllabusStatus(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->update_where("sh_syllabus_week_details",array("id"=>$request->id),array("status"=>$request->status));
        $response = array();
        if($res){
            $response = array("status"=>"success","message"=>lang("syl_update"));
        }
        echo json_encode($response);
    }
    
    function addCommentAndChangeStatus(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->update_where("sh_syllabus_week_details",array("id"=>$request->id),array("status"=>$request->status,"comments"=>$request->comment));
        if($res){
            $response = array("status"=>"success","message"=>lang('syllabus_update'));
            echo json_encode($response);
        }
    }
    
    function updateWeekDetails(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $data = array("topic"=>$request->topic,"status"=>$request->status,"comments"=>$request->comments,"edit"=>'No');
        $res = $this->common_model->update_where("sh_syllabus_week_details",array("id"=>$request->week_detail_id), $data);
        $response = array();
        if($res){
            $response = array("status"=>"success","message"=>lang("lbl_update_message_for_day_of_syllabus"));
        } else {
            $response = array("status"=>"success","message"=>lang("lbl_error_message_for_day_of_syllabus"));
        }
        echo json_encode($response);
    }
    
    function reqForApprovalSyls(){
        $response = array();
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $state = $request->state;
        $request_id = $request->id;
        $status = $request->status;
        $reason = $request->reason;
        
        $res = $this->common_model->update_where("sh_request_log",array("id"=>$request_id),array("status"=>$status,"edit_status"=>"not_edit", "edit_reason"=> $reason,"user_id" => $this->session->userdata("userdata")["user_id"], "marked" => "N" , 'request_time' => date('Y-m-d H:i:s'), "syllabus_state" => $state ));
        if($res){
            $response = array("status"=>"success","message"=>lang('req_admin'));
        }
        
        
        
        echo json_encode($response);
        /*$this->db->set('edit','Requested')->where('id',$request)->update('sh_syllabus_week_details');
        $user_id = $this->session->userdata("userdata")["user_id"];
        $sh_id = $this->session->userdata("userdata")["sh_id"];
        $this->db->insert('sh_request_log',array('user_id'=>$user_id,'school_id'=>$sh_id,'type'=>'syllabus','request_id'=>$request));*/
        
    }
    
    function deleteSyllabusOfDay(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->delete("id",$request->week_detail_id,"sh_syllabus_week_details");
        //$this->common_model->update_where("sh_syllabus_week_details",array("id"=>$request->week_detail_id),array("deleted_at"=>date('Y-m-d h:i:s')));
        $response = array("status"=>"success","message"=>lang('syllabus_delete'));
        echo json_encode($response);
    }
    
    function deleteSyllabusOfWeek(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->delete("id",$request->id,"sh_syllabus_weeks");
        //$this->common_model->update_where("sh_syllabus_week_details",array("id"=>$request->week_detail_id),array("deleted_at"=>date('Y-m-d h:i:s')));
        $response = array("status"=>"success","message"=>lang('week_delete'));
        echo json_encode($response);
    }
    
    function updateWeek(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $this->admin_model->dbSelect("id","academic_years"," school_id='$school_id' AND is_active='Y' ")[0]->id;
        
        $where  = " id NOT IN ($request->id) "
            . "AND class_id=$request->class_id "
            . "AND batch_id=$request->batch_id "
            . "AND subject_id=$request->subject_id "
            . "AND school_id=$school_id "
            . "AND academic_year_id=$active_academic_year "
            . "AND deleted_at IS NULL ";
        $sql = "select count(case when '".to_mysql_date($request->start_date)."' < end_date and start_date < '".to_mysql_date($request->end_date)."' then 1 end) as overlap from sh_syllabus_weeks where" .$where;
        $is_overlap = $this->admin_model->dbQuery($sql)[0]->overlap;
        
        $response = array();
        if(!$is_overlap) {
            $data = array("name"=>$request->week,"start_date"=> to_mysql_date($request->start_date),"end_date"=> to_mysql_date($request->end_date));
            $res = $this->common_model->update_where("sh_syllabus_weeks",array("id"=>$request->id), $data);
            if($res){
                $response = array("status"=>"success","message"=>lang('week_update'));
            }
        } else{
            $response = array("status"=>"error","message"=>lang('duplicate_week'));
        }
        echo json_encode($response);
    }

    function getSchoolAdmins(){
       $school_id = $this->session->userdata("userdata")["sh_id"];
       $response['new_ids'] = array();
       $ids = $this->admin_model->dbSelect("id","users"," school_id='$school_id' AND role_id=1 AND deleted_at=0 AND status = '0' ");
       if($ids){
           foreach($ids as $id){
               array_push($response['new_ids'], $id->id);
           }
           $response['sender'] = $this->session->userdata("userdata")["name"];
       }
       echo json_encode($response);
    }

    function getAllGuardians(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $response['new_ids'] = array();
        $response['sender'] = null;
        
        $students = $this->admin_model->dbSelect("GROUP_CONCAT(id) as ids","students_".$school_id," class_id='$request->class_id' AND batch_id='$request->batch_id' ");
        if(count($students) > 0){
            $students = $students[0]->ids;
        }
        $parents = $this->admin_model->dbSelect("GROUP_CONCAT(guardian_id) as ids","student_guardians"," student_id in ($students) AND deleted_at IS NULL ");
        if(count($parents) > 0){
            $response['new_ids'] = explode(",",$parents[0]->ids);
            $response['sender'] = $this->session->userdata("userdata")["name"];
        }
        echo json_encode($response);
     }

    function getStudentGuardians(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $student_id = $request->student_id;

        $response['new_ids'] = array();
        $response['sender'] = null;
        $response['receiver_id'] = 0;
        $ids = $this->admin_model->dbSelect("guardian_id as id","student_guardians"," student_id='$student_id' AND deleted_at IS NULL ");
        if($ids){
            foreach($ids as $id){
                array_push($response['new_ids'], $id->id);
            }
            $response['sender'] = $this->session->userdata("userdata")["name"];
            $response['receiver_id'] = $this->session->userdata("userdata")["user_id"];
        }
        echo json_encode($response);
     }
    
    function copySyllabus(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $active_academic_year = $this->session->userdata("userdata")["academic_year"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $data = array("name"=>$request->what->week,
            "start_date"=>$request->what->start_date,
            "end_date"=>$request->what->end_date,
            "academic_year_id"=>$active_academic_year,
            "school_id"=>$school_id,
            "class_id"=>$request->where->class_id,
            "batch_id"=>$request->where->batch_id,
            "subject_id"=>$request->where->subject_id
        );
        $week_id = $this->common_model->insert("sh_syllabus_weeks",$data);
        
        foreach($request->what->data as $dd){
            if(!empty($dd->topic)){
                $d = array(
                    "date"=>$dd->date,
                    "syllabus_week_id"=>$week_id,
                    "topic"=>$dd->topic,
                    "status"=>$dd->status,
                    "school_id"=>$school_id,
                    "comments"=>isset($dd->comments)?$dd->comments:NULL
                );
                $this->common_model->insert("sh_syllabus_week_details",$d);
            }
        }
        
        $response = array("status"=>"success","message"=>"Study plan copy successfully!");
        echo json_encode($response);
    }

    public function getChilds()
    {
        $user_id = $this->session->userdata("userdata")["user_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $materials=array();

        $childrens = $this->db->select('student_id,s.name')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->where('guardian_id', $user_id)->get()->result();
        
        foreach($childrens as $key => $child)
        {
            $student = $this->db->select('s.class_id, s.batch_id, s.subject_group_id')->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->where('s.id', $child->student_id)->get()->row();
            if($student)
            {
                    // $b_n = $this->db->select('name')->from('sh_batches')->where('school_id' , $school_id)->where('class_id' , $student->class_id)->where('id' , $student->batch_id)->where('deleted_at is NULL' , NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row();
                    // echo '<pre>';
                    // print_r($b_n);
                    // die;
                    // echo json_encode('die');
                    // die;
                    // $b_name = $b_n->name;
                    
                    if($student->subject_group_id != "" && $student->subject_group_id != null && $student->subject_group_id != 0)
                    {
                        $subject_ids = $this->db->select('subjects')->from('sh_subject_groups')->where('id', $student->subject_group_id)->where('deleted_at is NULL' , NULL)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->get()->row();
                        // echo $this->db->last_query();
                        // die;
                        // echo json_encode('die');
                        // die;
                        if(!empty($subject_ids))
                        {
                            $subject_ids=$subject_ids->subjects;
                            $subject_ids = explode(",", $subject_ids);
                            // $data["subjects"] = $this->study_model->getSubjects($student->class_id, $student->batch_id, $subject_ids);
                            foreach($subject_ids as $key => $value)
                            {
                                $subject_ids[$key] = $this->db->select('code')->from('sh_subjects')->where('id', $value)->where('deleted_at is NULL',NULL)->get()->row()->code;
                            }
                            $materials[] = $this->study_model->filter_studentmaterial($school_id, $student->class_id, $student->batch_id, '', '', $subject_ids,'');
                        }
                        // else
                        // {
                        //     echo 'hello';
                        //     die;
                        // }
                    }
                    else
                    {
                        
                    }
            }
        }
        

        $material_record=array();
        // $totalsize_is=sizeof($materials[0]);
        
        if(sizeof($materials) > 0)
        {
            $material_record=$materials[0];
        
            unset($materials[0]);
            $materials=array_values($materials);
            
            
            foreach($materials as $key => $material)
            {
                foreach($material as $key => $mat)
                {
                    $material_record[]=$mat;
                }
            }
            
    
            foreach($material_record as $key => $mat_record)
            {
                $material_record[$key]['files'] = explode(",", $mat_record['files']);
                $material_record[$key]['filesurl'] = explode(",", $mat_record['filesurl']);
                $material_record[$key]['file_names'] = explode(",", $mat_record['file_names']);
                $material_record[$key]['thumbnail_links'] = explode(",", $mat_record['thumbnail_links']);
            }
        }
        
        $data['materials'] = $material_record;
        $data['student_ids'] = $childrens;

        echo json_encode($data);
    }

    public function getBatchesForParent(){

        $school_id = $this->session->userdata("userdata")["sh_id"];

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $student = $request->student_id;
        


        
        if ($request->student_id != "") {
            $query = "  school_id=" . login_user()->user->sh_id . "AND id=".$request->student_id." AND deleted_at IS NULL ";

        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND id=".$request->student_id." AND deleted_at IS NULL ";

        }

      

        $data["batches"] = $this->db->select('batch_id,b.name')->from('students_'.$school_id.' s')->join('sh_batches b','s.batch_id=b.id')->where('s.id',$student)->get()->result();
        
       
        echo json_encode($data);
    }

    function getSubjectsForParent(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $subjects=array();
        
        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id,subject_group_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        $subject_group_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
            $subject_group_id = $classbatcharray[0]->subject_group_id;
        }
      

    $subject_id = $this->admin_model->dbSelect("subjects","subject_groups"," class_id='$class_id' AND batch_id='$batch_id' AND id='$subject_group_id' AND deleted_at IS NULL AND school_id='$school_id' ");
    if(sizeof($subject_id) > 0)
    {
        $subjects = $this->admin_model->dbSelect("name,id","subjects"," class_id='$class_id' AND batch_id='$batch_id' AND id IN (".$subject_id[0]->subjects.") AND deleted_at IS NULL AND school_id='$school_id' ");
    }
    
     
        echo json_encode($subjects);
    }

        function getSyllabusForParent(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }

        $res = $this->admin_model->dbSelect("*","request_log"," class_id='$class_id' AND batch_id='$batch_id' AND subject_id='$request->subject_id' AND type='$request->type' AND school_id='$school_id' AND deleted_at IS NULL and marked = 'N' ");
        $can_syllabus_edit = false;
        $request_id = NULL;
        $request_status = NULL;
        if(count($res) > 0){
            //request found
            $can_syllabus_edit = $res[0]->status=='draft' || $res[0]->status=='not-approved' ? true:false;
            $request_id = $res[0]->id;
            $request_status = $res[0]->status;
        } else {
            //request not found
            $record = array(
                "user_id"=>$this->session->userdata("userdata")["user_id"],
                "school_id"=>$school_id,
                "type"=>'syllabus',
                "class_id"=>$class_id,
                "batch_id"=>$batch_id,
                "subject_id"=>$request->subject_id
            );
            $id = $this->admin_model->dbInsert("request_log", $record);
            $can_syllabus_edit = true;
            $request_id = $id;
            $request_status = "draft";
        }
        
        $working_days_json = $this->admin_model->dbSelect("working_days","school"," id='$school_id' ")[0]->working_days;
        $var = json_decode($working_days_json);
        $not_working_days = array();
        foreach($var as $val){
            if($val->val == 'false'){
                array_push($not_working_days, $val->label);
            }
        }
        
        $weeks = $this->admin_model->dbSelect("*", "syllabus_weeks", " class_id='$class_id' AND batch_id='$batch_id' AND subject_id='$request->subject_id' AND school_id='$school_id' AND deleted_at IS NULL ");
        $sql = "SELECT "
            . "wk.id as week_id,"
            . "wkd.id as week_detail_id, "
            . "s.name as subject_name, "
            . "wk.name as weekname, "
            . "wk.start_date, "
            . "wk.end_date, "
            . "wkd.date, "
            . "wkd.edit, "
            . "wkd.topic, "
            . "wkd.status, "
            . "wkd.comments "
            . "FROM "
            . "sh_syllabus_weeks wk "
            
            . "INNER JOIN sh_syllabus_week_details wkd ON wk.id=wkd.syllabus_week_id "
            . "LEFT JOIN sh_subjects s ON s.id=wk.subject_id "
            . "WHERE wk.class_id='$class_id' "
            . "AND wk.batch_id='$batch_id' "
            . "AND wk.subject_id='$request->subject_id' "
            . "AND wk.school_id='$school_id' "
           
            . "AND wk.deleted_at IS NULL ";
            // $q = $this->db->last_query($sql);
            // die($q);
        $weeks_details = $this->admin_model->dbQuery($sql);
        $ydata = array();
        //print_r($weeks_details); die();
        
        foreach($weeks as $w){
            $arr = array();
            $ydata[$w->id]["id"] = $w->id;
            $ydata[$w->id]["week"] = $w->name;
            $ydata[$w->id]["start_date"] = $w->start_date;
            $ydata[$w->id]["end_date"] = $w->end_date;
            $ydata[$w->id]["class_id"] = $w->class_id;
            $ydata[$w->id]["batch_id"] = $w->batch_id;
            $ydata[$w->id]["subject_id"] = $w->subject_id;
            
            $index=0;
            foreach($weeks_details as $d){
                if($w->id == $d->week_id){
                    $arra = array(
                        "week_detail_id"=>$d->week_detail_id,
                        "date"=>$d->date,
                        "day"=>date('l', strtotime($d->date)),
                        "topic"=>$d->topic,
                        "status"=>$d->status,
                        "comments"=>$d->comments,
                        "is_working_day"=>true,
                        "edit"=>$d->edit
                    );
                    $arr[$index++] =  $arra;
                }
            }
            
            $saved_dates = array();
            foreach($arr as $t){
                array_push($saved_dates, $t["date"]);
            }
            $dates = $this->dateDays($ydata[$w->id]["start_date"], $ydata[$w->id]["end_date"]);
            $not_saved_dates = array_diff($dates, $saved_dates);
            foreach($not_saved_dates as $n){
                
                $is_working_day = true;
                if(in_array(date('l', strtotime($n)), $not_working_days)){
                    $is_working_day = false;
                }
                
                
                $dumyArray = array(
                    "week_detail_id"=>NULL,
                    "date"=>$n,
                    "day"=>date('l', strtotime($n)),
                    "subject"=>NULL,
                    "status"=>NULL,
                    "comments"=>NULL,
                    "is_working_day"=>$is_working_day
                );
                $arr[$index++] = $dumyArray;
            }
            $ydata[$w->id]["data"] = $arr;
        }
        foreach($ydata as $key => $value){
           array_multisort( array_column($ydata[$key]['data'], "date"), SORT_ASC, $ydata[$key]['data'] );
        }
        $yydata["syllabus"] = $ydata;
        $yydata["can_syllabus_edit"] = $can_syllabus_edit;
        $yydata["request_id"] = $request_id;
        $yydata["reqeust_status"] = $request_status;
        echo json_encode($yydata);
    }
}