<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Timetable extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function assign_teacher(){
        $this->load->view("timetable/assign_teacher_subject");
    }

    public function show() {

       $edit_time_table = false;
       $arr = $this->session->userdata("userdata")['persissions'];
           $array = json_decode($arr);
           if (isset($array)) {
               foreach ($array as $key => $value) {
                   if (in_array('timetable-edit', array($value->permission)) && $value->val == 'true') {
                       $edit_time_table = true;
                   }
               }
           }
       $data["edit_time_table"] = $edit_time_table;
       $this->load->view("timetable/show", $data);
   }

   public function showTimeTable_parent() {

       $edit_time_table = false;
       $arr = $this->session->userdata("userdata")['persissions'];
           $array = json_decode($arr);
           if (isset($array)) {
               foreach ($array as $key => $value) {
                   if (in_array('timetable-edit', array($value->permission)) && $value->val == 'true') {
                       $edit_time_table = true;
                   }
               }
           }
       $data["edit_time_table"] = $edit_time_table;
       $this->load->view("timetable/show_parent", $data);
   }
   public function showTimeTable_student() {

    $edit_time_table = false;
    $arr = $this->session->userdata("userdata")['persissions'];
        $array = json_decode($arr);
        if (isset($array)) {
            foreach ($array as $key => $value) {
                if (in_array('timetable-edit', array($value->permission)) && $value->val == 'true') {
                    $edit_time_table = true;
                }
            }
        }
    $data["edit_time_table"] = $edit_time_table;
    $this->load->view("timetable/show_student", $data);
}

    public function getSubjectsWiseTimeTable() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $days_of_week = $this->getDaysOfWeek();
        $periods = $this->admin_model->dbSelect("*,time_format(start_time,'%h:%i %p') as start_time2,time_format(end_time,'%h:%i %p') as end_time2", "periods", " school_id='$school_id' AND class_id='$request->class_id' AND batch_id='$request->batch_id' AND deleted_at IS NULL ORDER BY start_time ");

        if (count($periods) === 0) {
            $data = array("status" => "error", "message" => lang('periods_not_set'));
            echo json_encode($data);
            exit;
        }

        $final_arr = array();
        foreach ($days_of_week as $day) {
            foreach ($periods as $p) {
                $array = array(
                    "period_id" => $p->id,
                    "start_time" => $p->start_time,
                    "end_time" => $p->end_time,
                    "class_id" => $request->class_id,
                    "batch_id" => $request->batch_id,
                    "is_break" => $p->is_break,
                    "period_order" => $p->title,
                    "timetable_id" => NULL,
                    "teacher_id" => NULL,
                    "teacher_name" => NULL,
                    "day_of_week" => $day,
                    "sub_id" => NULL,
                    "room_no" => NULL,
                    "sub_name" => NULL,
                    "sub_code" => NULL
                );
                $final_arr[$day][$p->id] = $array;
            }
        }

        $daysofweekstring = null;
        foreach ($days_of_week as $_d) {
            $daysofweekstring .= "'" . $_d . "',";
        }


        $sql = "SELECT "
                . "p.id as period_id, "
                . "p.start_time as start_time, "
                . "p.end_time as end_time, "
                . "p.class_id as class_id, "
                . "p.batch_id as batch_id, "
                . "p.is_break as is_break, "
                . "p.title as period_order, "
                . "t.id as timetable_id, "
                . "t.day_of_week as day_of_week, "
                . "t.subject_id as sub_id, "
                . "t.room_no as room_no, "
                . "s.name as sub_name, "
                . "s.code as sub_code, "
                . "asn.teacher_id as teacher_id, "
                . "u.name as teacher_name "
                . "FROM sh_periods p "
                . "LEFT JOIN sh_timetable_new t ON p.id=t.period_id "
                . "INNER JOIN sh_subjects s ON t.subject_id=s.id "
                . "LEFT JOIN sh_assign_subjects asn ON t.subject_id=asn.subject_id and p.class_id = asn.class_id and p.batch_id = asn.batch_id "
                . "LEFT JOIN sh_users u ON u.id=asn.teacher_id "
                . "WHERE "
                . "p.school_id='$school_id' "
                . "AND p.class_id='$request->class_id' "
                . "AND p.batch_id='$request->batch_id' "
                . "AND p.deleted_at IS NULL "
                . "AND t.day_of_week in (" . rtrim($daysofweekstring, ",") . ") "
                . "ORDER BY p.start_time ASC ";

        $dbTimetables = $this->admin_model->dbQuery($sql);

        foreach ($dbTimetables as $tb) {
            $index1 = $tb->day_of_week;
            $index2 = $tb->period_id;
            $final_arr[$index1][$index2] = $tb;
        }

        foreach ($final_arr as $key => $value) {
            $i = 0;
            foreach ($value as $row_key => $row_value) {
                $final_arr[lang($key)][$i++] = $row_value;
                unset($final_arr[$key]);
                unset($final_arr[$key][$row_key]);
            }
        }

        $data = array("status" => "success", "message" => "", "periods" => $periods, "timetables" => $final_arr);
        echo json_encode($data);
    }

    public function getDaysOfWeek() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        //$days_of_week =  $this->admin_model->dbSelect("working_days","school"," id='$school_id' ")[0]->working_days; array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
        $days_of_week = array();
        $dddd = json_decode($this->admin_model->dbSelect("working_days", "school", " id='$school_id' ")[0]->working_days);
        foreach ($dddd as $row) {

            if ($row->val == 'true') {
                array_push($days_of_week, strtolower($row->label));
            }
        }
        //print_r($days_of_week);
        //die();
        $sorted_days_of_week = array();
        $start_day_of_week = strtolower($this->admin_model->dbSelect("start_day_of_the_week", "school", " id='$school_id' AND deleted_at=0 ")[0]->start_day_of_the_week);
        $index = array_search($start_day_of_week, $days_of_week);
        $loop1 = count($days_of_week) - $index;

        for ($i = 0; $i < $loop1; $i++) {
            array_push($sorted_days_of_week, $days_of_week[$index + $i]);
        }

        for ($j = 0; $j < $index; $j++) {
            array_push($sorted_days_of_week, $days_of_week[$j]);
        }

        //print_r($sorted_days_of_week);
        //die();
        return $sorted_days_of_week;
    }

    public function save() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
    $day = ucfirst($request->day_of_week);

        $data = array(
            'period_id' => $request->peroid_id,
            'day_of_week' => $request->day_of_week,
            'subject_id' => $request->subject_id,
            'room_no' => $request->room_no,
            'academic_year_id' => $this->session->userdata("userdata")['academic_year']
        );

        $res = $this->common_model->insert("sh_timetable_new", $data);
        if ($res > 0) {
        $period_name = $this->db->select('title')->from('sh_periods')->where('id', $request->peroid_id)->get()->row()->title;
        $subject_name = $this->db->select('name')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->name;
        $batch_id = $this->db->select('batch_id')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->batch_id;
        $batch_name = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
        $class_id = $this->db->select('class_id')->from('sh_batches')->where('id', $batch_id)->get()->row()->class_id;
        $class_name = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
        $teacher_ids = array();
        $teacher = $this->db->select('teacher_id')->from('sh_assign_subjects')->where('subject_id',$request->subject_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('deleted_at is null')->get()->row();
        if($teacher){
            $teacher_ids[] = $teacher->teacher_id;
        }
        $ts = $this->db->select('teacher_id')->from('sh_batches')->where('id',$batch_id)->get()->row()->teacher_id;
        $ts = explode(",", $ts);
        $teacher_ids = array_merge($teacher_ids, $ts);
        $teacher_ids = array_unique($teacher_ids);
        $notification['id'] = $teacher_ids;
        $notification['keyword'] = 'timetable_added';
        $notification['data'] = array('day' => $day, 'subject' => $subject_name, 'class' => $class_name, 'section' => $batch_name, 'period' => $period_name, 'room' => $request->room_no);
        $data = array("status" => "success", "message" => lang('time_save'), "notifications" => $notification);
            echo json_encode($data);
        }
    }

    public function update() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        if ($this->session->userdata("userdata")['role_id'] == 4) {
            $edit_time_table = false;
            $arr = $this->session->userdata("userdata")['persissions'];
            $array = json_decode($arr);
            if (isset($array)) {
                foreach ($array as $key => $value) {
                    if (in_array('timetable-edit', array($value->permission)) && $value->val == 'true') {
                        $edit_time_table = true;
                    }
                }
            }
            if ($edit_time_table == true) {
            $old_data = $this->db->select('room_no, subject_id')->from('sh_timetable_new')->where('id', $request->timetable_id)->get()->row();
            $old_subject_id = $old_data->subject_id;
            $old_room_no = $old_data->room_no;
            $day = ucfirst($request->day_of_week);

                $data = array(
                    "period_id" => $request->peroid_id,
                    "day_of_week" => $request->day_of_week,
                    "room_no" => $request->room_no,
                    "subject_id" => $request->subject_id
                );

                $res = $this->common_model->update_where("sh_timetable_new", array("id" => $request->timetable_id), $data);

            $notifications = array();

            if($old_subject_id == $request->subject_id  && $old_room_no != $request->room_no){
                $period_name = $this->db->select('title')->from('sh_periods')->where('id', $request->peroid_id)->get()->row()->title;
                $subject_name = $this->db->select('name')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->name;
                $batch_id = $this->db->select('batch_id')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->batch_id;
                $batch_name = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
                $class_id = $this->db->select('class_id')->from('sh_batches')->where('id', $batch_id)->get()->row()->class_id;
                $class_name = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
                $teacher_ids = array();
                $teacher = $this->db->select('teacher_id')->from('sh_assign_subjects')->where('subject_id',$request->subject_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('deleted_at is null')->get()->row();
                if($teacher){
                    $teacher_ids[] = $teacher->teacher_id;
                }
                $ts = $this->db->select('teacher_id')->from('sh_batches')->where('id',$batch_id)->get()->row()->teacher_id;
                $ts = explode(",", $ts);
                $teacher_ids = array_merge($teacher_ids, $ts);
                $teacher_ids = array_unique($teacher_ids);
                $temp['id'] = $teacher_ids;
                $temp['keyword'] = 'timetable_changed_room';
                $temp['data'] = array('day' => $day, 'subject' => $subject_name, 'class' => $class_name, 'section' => $batch_name, 'period' => $period_name, 'room' => $request->room_no);
                $notifications[] = $temp;
            }else if ($old_subject_id != $request->subject_id){
                $period_name = $this->db->select('title')->from('sh_periods')->where('id', $request->peroid_id)->get()->row()->title;
                $old_subject_name = $this->db->select('name')->from('sh_subjects')->where('id', $old_subject_id)->get()->row()->name;
                $subject_name = $this->db->select('name')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->name;
                $batch_id = $this->db->select('batch_id')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->batch_id;
                $batch_name = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
                $class_id = $this->db->select('class_id')->from('sh_batches')->where('id', $batch_id)->get()->row()->class_id;
                $class_name = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
                $old_teacher_ids = array();
                $teacher_ids = array();
                $teacher = $this->db->select('teacher_id')->from('sh_assign_subjects')->where('subject_id',$request->subject_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('deleted_at is null')->get()->row();
                if($teacher){
                    $teacher_ids[] = $teacher->teacher_id;
                }
                $old_teacher = $this->db->select('teacher_id')->from('sh_assign_subjects')->where('subject_id',$old_subject_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('deleted_at is null')->get()->row();
                if($old_teacher){
                    $old_teacher_ids[] = $old_teacher->teacher_id;
                }
                $teacher_ids[] = $this->db->select('teacher_id')->from('sh_batches')->where('id',$batch_id)->get()->row()->teacher_id;
                $old_teacher_ids[] = $this->db->select('teacher_id')->from('sh_batches')->where('id',$batch_id)->get()->row()->teacher_id;
                $teacher_ids = array_unique($teacher_ids);
                $old_teacher_ids = array_unique($old_teacher_ids);
                $temp['id'] = $old_teacher_ids;
                $temp['keyword'] = 'timetable_removed';
                $temp['data'] = array('day' => $day, 'subject' => $old_subject_name, 'class' => $class_name, 'section' => $batch_name, 'period' => $period_name);
                $notifications[] = $temp;

                $temp1['id'] = $teacher_ids;
                $temp1['keyword'] = 'timetable_added';
                $temp1['data'] = array('day' => $day, 'subject' => $subject_name, 'class' => $class_name, 'section' => $batch_name, 'period' => $period_name, 'room' => $request->room_no);
                $notifications[] = $temp1;
            }


                if ($res) {
                $response = array("status" => "success", "message" => lang('time_update'), "notifications" => $notifications);
                    echo json_encode($response);
                }
            } else {
                $response = array("status" => "error", "message" => lang('error_no_permission'));
                echo json_encode($response);
            }
        } else if ($this->session->userdata("userdata")['role_id'] == 1) {
        $old_data = $this->db->select('room_no, subject_id')->from('sh_timetable_new')->where('id', $request->timetable_id)->get()->row();
        $old_subject_id = $old_data->subject_id;
        $old_room_no = $old_data->room_no;
        $day = ucfirst($request->day_of_week);
            $data = array(
                "period_id" => $request->peroid_id,
                "day_of_week" => $request->day_of_week,
                "room_no" => $request->room_no,
                "subject_id" => $request->subject_id
            );

            $res = $this->common_model->update_where("sh_timetable_new", array("id" => $request->timetable_id), $data);
        $notifications = array();
        if($old_subject_id == $request->subject_id  && $old_room_no != $request->room_no){
            $period_name = $this->db->select('title')->from('sh_periods')->where('id', $request->peroid_id)->get()->row()->title;
            $subject_name = $this->db->select('name')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->name;
            $batch_id = $this->db->select('batch_id')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->batch_id;
            $batch_name = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
            $class_id = $this->db->select('class_id')->from('sh_batches')->where('id', $batch_id)->get()->row()->class_id;
            $class_name = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
            $teacher_ids = array();
            $teacher = $this->db->select('teacher_id')->from('sh_assign_subjects')->where('subject_id',$request->subject_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('deleted_at is null')->get()->row();
            if($teacher){
                $teacher_ids[] = $teacher->teacher_id;
            }
            $ts = $this->db->select('teacher_id')->from('sh_batches')->where('id',$batch_id)->get()->row()->teacher_id;
            $ts = explode(",", $ts);
            $teacher_ids = array_merge($teacher_ids, $ts);
            $teacher_ids = array_unique($teacher_ids);
            $temp['id'] = $teacher_ids;
            $temp['keyword'] = 'timetable_changed_room';
            $temp['data'] = array('day' => $day, 'subject' => $subject_name, 'class' => $class_name, 'section' => $batch_name, 'period' => $period_name, 'room' => $request->room_no);
            $notifications[] = $temp;
        }else if ($old_subject_id != $request->subject_id){
            $period_name = $this->db->select('title')->from('sh_periods')->where('id', $request->peroid_id)->get()->row()->title;
            $old_subject_name = $this->db->select('name')->from('sh_subjects')->where('id', $old_subject_id)->get()->row()->name;
            $subject_name = $this->db->select('name')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->name;
            $batch_id = $this->db->select('batch_id')->from('sh_subjects')->where('id', $request->subject_id)->get()->row()->batch_id;
            $batch_name = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
            $class_id = $this->db->select('class_id')->from('sh_batches')->where('id', $batch_id)->get()->row()->class_id;
            $class_name = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
            $old_teacher_ids = array();
            $teacher_ids = array();
            $teacher = $this->db->select('teacher_id')->from('sh_assign_subjects')->where('subject_id',$request->subject_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('deleted_at is null')->get()->row();
            if($teacher){
                $teacher_ids[] = $teacher->teacher_id;
            }
            $old_teacher = $this->db->select('teacher_id')->from('sh_assign_subjects')->where('subject_id',$old_subject_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->where('deleted_at is null')->get()->row();
            if($old_teacher){
                $old_teacher_ids[] = $old_teacher->teacher_id;
            }
            $teacher_ids[] = $this->db->select('teacher_id')->from('sh_batches')->where('id',$batch_id)->get()->row()->teacher_id;
            $old_teacher_ids[] = $this->db->select('teacher_id')->from('sh_batches')->where('id',$batch_id)->get()->row()->teacher_id;
            $teacher_ids = array_unique($teacher_ids);
            $old_teacher_ids = array_unique($old_teacher_ids);
            $temp['id'] = $old_teacher_ids;
            $temp['keyword'] = 'timetable_removed';
            $temp['data'] = array('day' => $day, 'subject' => $old_subject_name, 'class' => $class_name, 'section' => $batch_name, 'period' => $period_name);
            $notifications[] = $temp;

            $temp1['id'] = $teacher_ids;
            $temp1['keyword'] = 'timetable_added';
            $temp1['data'] = array('day' => $day, 'subject' => $subject_name, 'class' => $class_name, 'section' => $batch_name, 'period' => $period_name, 'room' => $request->room_no);
            $notifications[] = $temp1;
        }
            if ($res) {
            $response = array("status" => "success", "message" => lang('time_update'), "notifications" => $notifications);
                echo json_encode($response);
            }
        }
    }

    public function getSubjects() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*", "subjects", " school_id='$school_id' AND class_id='$request->class_id' AND batch_id='$request->batch_id' AND deleted_at IS NULL ");
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

    public function getSubjectsWiseTimeTableForParent() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);



        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id,subject_group_id,academic_year_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        $subject_group_id = 0;
        $academic_year_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
            $subject_group_id = $classbatcharray[0]->subject_group_id;
            $academic_year_id = $classbatcharray[0]->academic_year_id;
        }
        
        $subject_id = $this->admin_model->dbSelect("subjects","subject_groups"," class_id='$class_id' AND batch_id='$batch_id' AND id='$subject_group_id' AND deleted_at IS NULL AND school_id='$school_id' ");

        $subjects = $this->admin_model->dbSelect("id","subjects"," class_id='$class_id' AND batch_id='$batch_id' AND id IN (".$subject_id[0]->subjects.") AND deleted_at IS NULL AND school_id='$school_id' ");

        $subjects_new = array();
        if(count($subjects) > 0){
            foreach($subjects as $s){
                array_push($subjects_new, $s->id);
            }
        }
        
        $days_of_week = $this->getDaysOfWeek();
        $periods = $this->admin_model->dbSelect("*,time_format(start_time,'%h:%i %p') as start_time2,time_format(end_time,'%h:%i %p') as end_time2", "periods", " school_id='$school_id' AND class_id='$class_id' AND academic_year_id='$academic_year_id' AND batch_id='$batch_id' AND deleted_at IS NULL ORDER BY start_time ");
        
        if (count($periods) === 0) {
            $data = array("status" => "error", "message" => lang('periods_not_set'));
            echo json_encode($data);
            exit;
        }

        $final_arr = array();
        foreach ($days_of_week as $day) {
            foreach ($periods as $p) {
                $array = array(
                    "period_id" => $p->id,
                    "start_time" => $p->start_time,
                    "end_time" => $p->end_time,
                    "class_id" => $class_id,
                    "batch_id" => $batch_id,
                    "is_break" => $p->is_break,
                    "period_order" => $p->title,
                    "timetable_id" => NULL,
                    "teacher_id" => NULL,
                    "teacher_name" => NULL,
                    "day_of_week" => $day,
                    "sub_id" => NULL,
                    "room_no" => NULL,
                    "sub_name" => NULL,
                    "sub_code" => NULL
                );
                $final_arr[$day][$p->id] = $array;
            }
        }

        $daysofweekstring = null;
        foreach ($days_of_week as $_d) {
            $daysofweekstring .= "'" . $_d . "',";
        }


        $sql = "SELECT "
                . "p.id as period_id, "
                . "p.start_time as start_time, "
                . "p.end_time as end_time, "
                . "p.class_id as class_id, "
                . "p.batch_id as batch_id, "
                . "p.is_break as is_break, "
                . "p.title as period_order, "
                . "t.id as timetable_id, "
                . "t.day_of_week as day_of_week, "
                . "t.subject_id as sub_id, "
                . "t.room_no as room_no, "
                . "s.name as sub_name, "
                . "s.code as sub_code, "
                . "asn.teacher_id as teacher_id, "
                . "u.name as teacher_name "
                . "FROM sh_periods p "
                . "LEFT JOIN sh_timetable_new t ON p.id=t.period_id "
                . "INNER JOIN sh_subjects s ON t.subject_id=s.id "
                . "LEFT JOIN sh_assign_subjects asn ON t.subject_id=asn.subject_id and p.class_id = asn.class_id and p.batch_id = asn.batch_id "
                . "LEFT JOIN sh_users u ON u.id=asn.teacher_id "
                . "WHERE "
                . "p.school_id='$school_id' "
                . "AND p.class_id='$class_id' "
                . "AND p.batch_id='$batch_id' "
                . "AND p.deleted_at IS NULL "
                . "AND t.day_of_week in (" . rtrim($daysofweekstring, ",") . ") "
                . "ORDER BY p.start_time ASC ";

        $dbTimetables = $this->admin_model->dbQuery($sql);

        foreach ($dbTimetables as $tb) {
            $index1 = $tb->day_of_week;
            $index2 = $tb->period_id;
            $final_arr[$index1][$index2] = $tb;
        }

        foreach ($final_arr as $key => $value) {
            $i = 0;
            foreach ($value as $row_key => $row_value) {
                $final_arr[lang($key)][$i++] = $row_value;
                unset($final_arr[$key]);
                unset($final_arr[$key][$row_key]);
            }
        }

        if(count($final_arr) > 0){
            foreach($final_arr as $key=>$fr){
                foreach($fr as $key2=>$row){
                    if(isset($row->sub_id)){
                        if(!in_array($row->sub_id, $subjects_new)){
                            $arr = array(
                                "batch_id" => $row->batch_id,
                                "class_id" => $row->batch_id,
                                "day_of_week" => $row->day_of_week,
                                "end_time" => $row->end_time,
                                "is_break" => "Y",
                                "period_id" => null,
                                "period_order" => "Break",
                                "room_no" => null,
                                "start_time" => $row->start_time,
                                "sub_code" => null,
                                "sub_id" => null,
                                "sub_name" => null,
                                "teacher_id" => null,
                                "teacher_name" => null,
                                "timetable_id" => null
                            );
                            $final_arr[$key][$key2] = $arr;
                            //unset($final_arr[$key][$key2]);
                        }
                    }
                }
            }
        }

        $data = array("status" => "success", "message" => "", "periods" => $periods, "timetables" => $final_arr);
        echo json_encode($data);
    }

    public function getSubjectsWiseTimeTableForStudent() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // print_r($request);die();

        $school_id = $this->session->userdata("userdata")["sh_id"];
        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id,subject_group_id,academic_year_id","students_".$school_id," id='$request->student_id' ");
         
        $class_id = 0;
        $batch_id = 0;
        $subject_group_id = 0;
        $academic_year_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
            $subject_group_id = $classbatcharray[0]->subject_group_id;
            $academic_year_id = $classbatcharray[0]->academic_year_id;
        }
        
        $subject_id = $this->admin_model->dbSelect("subjects","subject_groups"," class_id='$class_id' AND batch_id='$batch_id' AND id='$subject_group_id' AND deleted_at IS NULL AND school_id='$school_id' ");
      
        $subjects = $this->admin_model->dbSelect("id","subjects"," class_id='$class_id' AND batch_id='$batch_id' AND id IN (".$subject_id[0]->subjects.") AND deleted_at IS NULL AND school_id='$school_id' ");
         
        $subjects_new = array();
        if(count($subjects) > 0){
            foreach($subjects as $s){
                array_push($subjects_new, $s->id);
            }
        }
        
        $days_of_week = $this->getDaysOfWeek();
        $periods = $this->admin_model->dbSelect("*,time_format(start_time,'%h:%i %p') as start_time2,time_format(end_time,'%h:%i %p') as end_time2", "periods", " school_id='$school_id' AND class_id='$class_id' AND academic_year_id='$academic_year_id' AND batch_id='$batch_id' AND deleted_at IS NULL ORDER BY start_time ");
        
        if (count($periods) === 0) {
            $data = array("status" => "error", "message" => lang('periods_not_set'));
            echo json_encode($data);
            exit;
        }

        $final_arr = array();
        foreach ($days_of_week as $day) {
            foreach ($periods as $p) {
                $array = array(
                    "period_id" => $p->id,
                    "start_time" => $p->start_time,
                    "end_time" => $p->end_time,
                    "class_id" => $class_id,
                    "batch_id" => $batch_id,
                    "is_break" => $p->is_break,
                    "period_order" => $p->title,
                    "timetable_id" => NULL,
                    "teacher_id" => NULL,
                    "teacher_name" => NULL,
                    "day_of_week" => $day,
                    "sub_id" => NULL,
                    "room_no" => NULL,
                    "sub_name" => NULL,
                    "sub_code" => NULL
                );
                $final_arr[$day][$p->id] = $array;
            }
        }

        $daysofweekstring = null;
        foreach ($days_of_week as $_d) {
            $daysofweekstring .= "'" . $_d . "',";
        }


        $sql = "SELECT "
                . "p.id as period_id, "
                . "p.start_time as start_time, "
                . "p.end_time as end_time, "
                . "p.class_id as class_id, "
                . "p.batch_id as batch_id, "
                . "p.is_break as is_break, "
                . "p.title as period_order, "
                . "t.id as timetable_id, "
                . "t.day_of_week as day_of_week, "
                . "t.subject_id as sub_id, "
                . "t.room_no as room_no, "
                . "s.name as sub_name, "
                . "s.code as sub_code, "
                . "asn.teacher_id as teacher_id, "
                . "u.name as teacher_name "
                . "FROM sh_periods p "
                . "LEFT JOIN sh_timetable_new t ON p.id=t.period_id "
                . "INNER JOIN sh_subjects s ON t.subject_id=s.id "
                . "LEFT JOIN sh_assign_subjects asn ON t.subject_id=asn.subject_id and p.class_id = asn.class_id and p.batch_id = asn.batch_id "
                . "LEFT JOIN sh_users u ON u.id=asn.teacher_id "
                . "WHERE "
                . "p.school_id='$school_id' "
                . "AND p.class_id='$class_id' "
                . "AND p.batch_id='$batch_id' "
                . "AND p.deleted_at IS NULL "
                . "AND t.day_of_week in (" . rtrim($daysofweekstring, ",") . ") "
                . "ORDER BY p.start_time ASC ";

        $dbTimetables = $this->admin_model->dbQuery($sql);

        foreach ($dbTimetables as $tb) {
            $index1 = $tb->day_of_week;
            $index2 = $tb->period_id;
            $final_arr[$index1][$index2] = $tb;
        }

        foreach ($final_arr as $key => $value) {
            $i = 0;
            foreach ($value as $row_key => $row_value) {
                $final_arr[lang($key)][$i++] = $row_value;
                unset($final_arr[$key]);
                unset($final_arr[$key][$row_key]);
            }
        }

        if(count($final_arr) > 0){
            foreach($final_arr as $key=>$fr){
                foreach($fr as $key2=>$row){
                    if(isset($row->sub_id)){
                        if(!in_array($row->sub_id, $subjects_new)){
                            $arr = array(
                                "batch_id" => $row->batch_id,
                                "class_id" => $row->batch_id,
                                "day_of_week" => $row->day_of_week,
                                "end_time" => $row->end_time,
                                "is_break" => "Y",
                                "period_id" => null,
                                "period_order" => "Break",
                                "room_no" => null,
                                "start_time" => $row->start_time,
                                "sub_code" => null,
                                "sub_id" => null,
                                "sub_name" => null,
                                "teacher_id" => null,
                                "teacher_name" => null,
                                "timetable_id" => null
                            );
                            $final_arr[$key][$key2] = $arr;
                            //unset($final_arr[$key][$key2]);
                        }
                    }
                }
            }
        }

        $data = array("status" => "success", "message" => "", "periods" => $periods, "timetables" => $final_arr);
        echo json_encode($data);
    }



}
