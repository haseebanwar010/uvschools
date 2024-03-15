<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Monitoring extends CI_Controller
{

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

	public function index(){
        $this->load->view("monitoring/academic");
    }

    public function fetchAcademicMonitoringReport(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $request->academic_year_id;
        $class_id = $request->class_id;
        $date = to_mysql_date($request->date);
        
        $records = $this->getTimetableOfDayForAllBatches($class_id, $academic_year_id);
        $message = "";
        foreach($records as $tb){
            foreach($tb->batches as $bth){
                $bth->sm_summary = array();
                $bth->sp_summary = array();
                $bth->is_attendance_marked = 'false';
                $request->batch_id = $bth->id;
                //Code for attendance monitoring
                $new_request = $request;
                $attendance_data = $this->fetchStudentsAttendance($new_request);
                if(isset($attendance_data["message"])){
                    $records = array();
                    $message = $attendance_data["message"];
                    break;
                }
                if(count($attendance_data["students_marked"]) > 0){
                    $bth->is_attendance_marked = 'true';
                }
                
                $where_part="";
                if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
                    $where_part =  "AND sh_subjects.id IN (". implode(',', login_user()->t_data->subjects) .") ";
                } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
        
                } 
                else if(login_user()->user->role_id == ADMIN_ROLE_ID){
        
                } else if(login_user()->user->role_id == PARENT_ROLE_ID){
        
                } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
        
                }
                
                $bth->subjects = $this->admin_model->dbSelect("*","subjects"," class_id='$class_id' AND batch_id='$bth->id' AND deleted_at IS NULL AND academic_year_id='$academic_year_id' AND school_id='$school_id' $where_part");
                foreach($bth->subjects as $sub){
                    $sub->teacher_info = array();
                    $sql = "SELECT id,avatar,name FROM sh_users WHERE id=(SELECT teacher_id FROM sh_assign_subjects WHERE class_id='$class_id' AND batch_id='$bth->id' AND subject_id='$sub->id' AND school_id='$school_id' AND deleted_at IS NULL)";
                    $d = $this->admin_model->dbQuery($sql);
                    if(count($d) > 0){
                        $sub->teacher_info = $d[0];
                    }
                    $sub->sm_summary = array("assignment_count"=>0,"classwork_count"=>0,"homework_count"=>0,"studymaterial_count"=>0,"data"=>array());
                    $sub->sp_summary = array();
                    $bth_id = $bth->id;
                    $sub_id = $sub->id;
                    $obj  = $this->admin_model->filter_monitring($school_id,$class_id,$bth_id,$date,$sub_id);
                     
                   
                    
                    //$sql = "SELECT * FROM sh_study_material WHERE school_id='$school_id' AND class_id='$class_id' AND batch_id='$bth->id' AND subject_id='$sub->id' AND delete_status=0 AND date_format(created_at,'%Y-%m-%d')='$date'";
                    $sql2 = "SELECT * FROM sh_syllabus_weeks sw LEFT JOIN sh_syllabus_week_details swd ON sw.id=syllabus_week_id AND swd.deleted_at IS NULL WHERE sw.class_id='$class_id' AND sw.batch_id='$bth->id' AND sw.subject_id='$sub->id' AND sw.deleted_at IS NULL AND sw.school_id='$school_id' AND sw.academic_year_id='$academic_year_id' AND date_format(swd.updated_at,'%Y-%m-%d')='$date' AND swd.status='Done' ";
                    
                    $obj2 = $this->admin_model->dbQuery($sql2);
                    if(count($obj) > 0){
                        foreach($obj as $ob){
                            switch($ob->content_type){
                                case "Assignment":
                                    $sub->sm_summary["assignment_count"]++;
                                    break;
                                case "Homework":
                                    $sub->sm_summary["homework_count"]++;
                                    break;
                                case "Classwork":
                                    $sub->sm_summary["classwork_count"]++;
                                    break;
                                case "Study Material":
                                    $sub->sm_summary["studymaterial_count"]++;
                                    break;
                            }
                            $sub->sm_summary["data"][] = clone $ob;
                            $bth->sm_summary[] = clone $ob;
                        }
                    }
                    if(count($obj2) > 0){
                        foreach($obj2 as $ob2){
                            $sub->sp_summary[] = clone $ob2;
                            $bth->sp_summary[] = clone $ob2;
                        }
                    }
                }
            }
        }

        if(empty($message) || $message == ""){
            $response = array("status"=>"success","message"=>$message,"data"=>$records);
        } else {
            $response = array("status"=>"error","message"=>$message,"data"=>$records);
        }
        echo json_encode($response);
    }

    public function fetchStudentsAttendance($request) {
        
        $data = array();
        $data["students_marked"] = array();
        $data["students_pending"] = array();
        $data["students"] = array();
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $date_now = date("Y-m-d");
        $date_provided = date('Y-m-d', strtotime(str_replace('/', '-', $request->date)));
        
        $academic_year = $this->db->select('start_date,end_date')->from('sh_academic_years')->where('id',$request->academic_year_id)->where('deleted_at is null')->get()->row();
        if(!empty($academic_year)){
            $start_date = date('Y-m-d',strtotime($academic_year->start_date));
            $end_date = date('Y-m-d',strtotime($academic_year->end_date));
        }
        
        if(empty($academic_year)){
            $data["message"] = lang('no_academic_year');
        }else if($date_provided < $start_date || $date_provided > $end_date){
            $data["message"] = lang('date_not_in_academic');
        }else if ($date_provided > $date_now) {
            $data["message"] = lang('you_cant_use_future_dates_for_monitoring');
        } else {
            $is_current_day_off = "false";
            $day = date("l", strtotime(to_mysql_date($request->date)));
            $working_days = json_decode($this->admin_model->dbSelect("working_days", "school", " id=$school_id ")[0]->working_days);
            
            foreach ($working_days as $val) {
                if ($val->label == $day && $val->val == "false") {
                    $is_current_day_off = $val;
                    break;
                }
            }
            
            if ($is_current_day_off == "false" || empty($is_current_day_off)) {
                // check attendance already marked ?
                $query1 = "SELECT 
                    u.id, 
                    u.name, 
                    u.rollno,
                    u.class_id, 
                    u.batch_id, 
                    t.status, 
                    cls.name as class_name,
                    cls.id as old_class_id,
                    btch.name as batch_name,
                    btch.id as old_batch_id,
                    bth.id as new_batch_id,
                    bth.name as new_batch,
                    cl.name as new_class,
                    cl.id as new_class_id,
                    t.comment 
                    FROM 
                    sh_students_".$school_id." u LEFT JOIN sh_classes cls ON cls.id=u.class_id  
                    LEFT JOIN sh_batches btch ON btch.id = u.batch_id 
                    LEFT JOIN sh_attendance t ON u.id = t.user_id 
                    LEFT JOIN sh_classes cl on t.class_id = cl.id 
                    LEFT JOIN sh_batches bth on t.batch_id = bth.id WHERE ";
                $where2 = " t.date = '" . $date_provided . "' AND u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $request->class_id . " AND u.batch_id=" . $request->batch_id . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
                $students_one = $this->admin_model->dbQuery($query1 . $where2);
                foreach ($students_one as $temp_std) {
                    if ($temp_std->old_class_id != $temp_std->new_class_id || $temp_std->old_batch_id != $temp_std->new_batch_id) {
                        $temp_std->message = lang('attendance_marked_student');
                        $temp_std->message = str_replace('{x}', $temp_std->new_class, $temp_std->message);
                        $temp_std->message = str_replace('{y}', $temp_std->new_batch, $temp_std->message);
                    } else {
                        $temp_std->message = "";
                    }
                }
                $query1 = "SELECT u.id, u.name, u.rollno,u.class_id, u.batch_id, cls.name as class_name, btch.name as batch_name, 'Present' as `status` from sh_students_".$school_id." u LEFT JOIN sh_classes cls ON cls.id = u.class_id LEFT JOIN sh_batches btch ON btch.id = u.batch_id WHERE ";
                $where2 = " u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $request->class_id . " AND u.batch_id=" . $request->batch_id . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
                $students_two = $this->admin_model->dbQuery($query1 . $where2 . " AND NOT EXISTS(SELECT null FROM sh_attendance t WHERE t.user_id = u.id AND t.date = '" . $date_provided . "') ");
                $students = array_merge($students_one, $students_two);
                $data["students_marked"] = $students_one;
                $data["students_pending"] = $students_two;
                $data["students"] = $students;
            } else if (isset($is_current_day_off->label)) {
                $data["message"] = $is_current_day_off->label . " " . lang('not_working_day');
            }
        }
        return $data;
    }

    public function getTimetableOfDayForAllBatches($class_id, $academic_year_id) {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $current_day_of_week = strtolower(date("l"));
        $sql = "SELECT 
            COALESCE(bth.academic_year_id,'break') as academic_year_id,
            tbl.day_of_week,
            cls.id as class_id,
            bth.id as batch_id,
            sub.id as subject_id,
            tbl.id as timetable_id, 
            tbl.period_id, 
            sub.name as subject, 
            tbl.room_no, 
            time_format(p.start_time,'%h:%i %p') as start_time, 
            time_format(p.end_time,'%h:%i %p') as end_time, 
            p.title, 
            p.is_break, 
            cls.name as classname, 
            bth.name as batchname 
            FROM 
            sh_periods p 
            LEFT JOIN sh_timetable_new tbl ON tbl.period_id=p.id AND tbl.day_of_week = '".$current_day_of_week."'
            INNER JOIN sh_classes cls ON p.class_id=cls.id AND p.class_id='$class_id'
            INNER JOIN sh_batches bth ON p.batch_id=bth.id
            LEFT JOIN sh_subjects sub ON tbl.subject_id=sub.id 
            WHERE p.school_id=$school_id 
            AND bth.academic_year_id=$academic_year_id 
            AND p.deleted_at IS NULL 
            AND tbl.deleted_at IS NULL 
            ORDER BY p.start_time ";
        $result1 = $this->admin_model->dbQuery($sql);
        
        $working_days = json_decode($this->admin_model->dbSelect("working_days", "school", " id=$school_id ")[0]->working_days);
        $is_current_day_off = false;
        foreach ($working_days as $val) {
            if (strcasecmp($val->label, $current_day_of_week) == 0 && $val->val == "false") {
                $is_current_day_off = true;
                break;
            }
        }
        if ($is_current_day_off == true) {
          $result1 = array();
        }
        
        foreach ($result1 as $key => $res) {
            $sql = "SELECT name from sh_users WHERE id=(SELECT teacher_id FROM sh_assign_subjects WHERE class_id='$res->class_id' AND batch_id='$res->batch_id' AND subject_id='$res->subject_id' AND school_id='$school_id' AND deleted_at IS NULL) ";
            $res1 = $this->admin_model->dbQuery($sql);
            $sql1 = "SELECT name from sh_users WHERE id=(SELECT assistant_id FROM sh_assign_subjects WHERE class_id='$res->class_id' AND batch_id='$res->batch_id' AND subject_id='$res->subject_id' AND school_id='$school_id' AND deleted_at IS NULL) ";
            $res2 = $this->admin_model->dbQuery($sql1);
            $teachername = '';
            $assistantname = '';
            if (count($res1) > 0) {
                $teachername = $res1[0]->name;
            }
            if (count($res2) > 0) {
                $assistantname = $res2[0]->name;
            }
            $result1[$key]->teacher_name = $teachername;
            $result1[$key]->assistant_name = $assistantname;
        }

        $classesWithBatches = $this->admin_model->dbSelect("*", "classes", " id='$class_id' ");
        
        foreach ($classesWithBatches as $key => $cls) {
          $batches = $this->admin_model->dbSelect("*", "batches", " class_id='$cls->id' AND school_id='$school_id' AND academic_year_id='$academic_year_id' AND deleted_at IS NULL ");
          $classesWithBatches[$key]->batches = $batches;
        }

        foreach ($classesWithBatches as $key => $val) {
          foreach ($val->batches as $bb) {
            $bb->timetables = array();
            foreach ($result1 as $k => $t) {
              if ($bb->id == $t->batch_id) {
                array_push($bb->timetables, $t);
              }
            }
          }
        }
        return $classesWithBatches;
      }
}