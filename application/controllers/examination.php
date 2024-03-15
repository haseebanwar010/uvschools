<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Examination extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    function add() {
        $params = $this->input->get();
        $data["selected_tab"] = "";
        $data["tab_subjects_selected_class_id"] = "all";
        $data["tab_subjects_selected_batch_id"] = "all";
        $data["tab_rules_selected_class_id"] = "all";
        $data["tab_rules_selected_batch_id"] = "all";

        $exams = xcrud_get_instance();
        $exams->table('sh_exams');
        $exams->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
        // $exams->relation('academic_year_id', 'sh_academic_years', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]);
        $exams->show_primary_ai_field(false);
        $exams->columns('title,start_date,end_date');
        $exams->fields('title,start_date,end_date');
        $exams->label('title', lang('lbl_exam_session'))->label('start_date', lang('start_date'))->label('end_date', lang('end_date'))->label('academic_year_id', lang('lbl_academic_year'));
        $exams->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $exams->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $exams->load_view("view", "customview.php");
        $exams->unset_print();
        $exams->before_insert('check_exam_session');
        $exams->before_update('check_exam_session_update');
        $exams->replace_remove('soft_delete');
        $exams->unset_csv();
        $exams->unset_title();
        $exams->table_name(lang('lbl_exam_session'));
        $data["exams_new"] = $exams->render();

        $exam_activities = xcrud_get_instance();
        $exam_activities->table('sh_exam_activities');
        $exam_activities->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
        $exam_activities->show_primary_ai_field(false);
        $exam_activities->columns('activity_name,class_id,batch_id,subject_ids');
        $exam_activities->fields('activity_name,class_id,batch_id,subject_ids');
        $exam_activities->change_type('subject_ids','multiselect','','');
        $exam_activities->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        $exam_activities->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'class_id', 'class_id');
        $exam_activities->relation('subject_ids', 'sh_subjects', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', true, '', '', 'batch_id', 'batch_id');
        $exam_activities->label('class_id', lang('lbl_class'))->label('batch_id', lang('lbl_batch'))->label('subject_ids', lang('lbl_subjects'))->label('activity_name', lang('activity_name'));
        $exam_activities->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $exam_activities->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $exam_activities->before_insert('check_activity');
        $exam_activities->load_view("view", "customview.php");
        $exam_activities->unset_print();
        $exam_activities->replace_remove('soft_delete');
        $exam_activities->unset_csv();
        $exam_activities->unset_title();
        $data["exams_activities"] = $exam_activities->render();
        
        $exam_details = xcrud_get_instance();
        $exam_details->table('sh_exam_details');
        $exam_details->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('academic_year_id',$this->session->userdata("userdata")["academic_year"]);
        $exam_details->columns('exam_id,class_id,batch_id,subject_id,exam_date,start_time,end_time,type,total_marks,passing_marks');
        $exam_details->fields('exam_id,class_id,batch_id,subject_id,exam_date,start_time,end_time,type,total_marks,passing_marks');
        $exam_details->relation('exam_id', 'sh_exams', 'id', 'title', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        $exam_details->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        $exam_details->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'class_id', 'class_id');
        $exam_details->relation('subject_id', 'sh_subjects', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'batch_id', 'batch_id');
        $exam_details->label('exam_id', lang('lbl_exam_session'))->label('class_id', lang('lbl_class'))->label('batch_id', lang('lbl_batch'));
        $exam_details->label('subject_id', lang('lbl_subject'))->label('start_time', lang('start_time'))->label('end_time', lang('end_time'));
        $exam_details->label('total_marks', lang('total_marks'));
        $exam_details->label('exam_date', lang('lbl_exam_date'));
        $exam_details->label('passing_marks', lang('lbl_passing_marks'));
        $exam_details->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $exam_details->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $exam_details->load_view("view", "customview.php");
        $exam_details->unset_print();
        $exam_details->replace_remove('soft_delete');
        $exam_details->unset_csv();
        $exam_details->unset_title();
        $exam_details->table_name(lang('lbl_exam_details'));
        $exam_details->before_insert('check_exam_details');
        $exam_details->before_update('check_exam_details_update');



        $passing_rules = xcrud_get_instance();
        $passing_rules->table('sh_passing_rules');
        $passing_rules->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL AND academic_year_id = '.$this->session->userdata("userdata")["academic_year"]);
        $passing_rules->columns('exam_id,class_id,batch_id,minimum_subjects,operator,minimum_percentage');
        $passing_rules->fields('exam_id,class_id,batch_id,operator,minimum_subjects,minimum_percentage');
        $passing_rules->relation('exam_id', 'sh_exams', 'id', 'title', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        $passing_rules->relation('class_id', 'sh_classes', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"]." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]);
        $passing_rules->relation('batch_id', 'sh_batches', 'id', 'name', 'deleted_at IS NULL and school_id = ' . $this->session->userdata("userdata")["sh_id"], '', '', '', '', 'class_id', 'class_id');
        $passing_rules->label('exam_id', lang('lbl_exam_session'))->label('class_id', lang('lbl_class'))->label('batch_id', lang('lbl_batch'));
        $passing_rules->label('operator', lang('operator_rule'))->label('minimum_subjects', lang('minimum_subjects_pass'))->label('minimum_percentage', lang('minimum_percentage'));
        $passing_rules->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $passing_rules->pass_var('academic_year_id', $this->session->userdata("userdata")["academic_year"]);
        $passing_rules->before_insert('passing_rules_insert');
        $passing_rules->before_update('passing_rules_update');
        $passing_rules->load_view("view", "customview.php");
        $passing_rules->unset_print();
        $passing_rules->replace_remove('soft_delete');
        $passing_rules->unset_csv();
        $passing_rules->unset_title();


        if ($params != null && $params["tab"] === "exam_details") {
            $data["selected_tab"] = $params["tab"];
            $data["tab_subjects_selected_class_id"] = $params["class_id"];
            $data["tab_subjects_selected_batch_id"] = $params["batch_id"];
            if ($params["class_id"] != "all") {
                if ($params["batch_id"] != "all") {
                    $exam_details->where('class_id', $params["class_id"]);
                    $exam_details->where('batch_id', $params["batch_id"]);
                    $exam_details->unset_add(false);
                    $exam_details->pass_default('class_id', $params["class_id"]);
                    $exam_details->pass_default('batch_id', $params["batch_id"]);
                }
                else{
                    $exam_details->pass_default('class_id', $params["class_id"]);
                    $data2 = $this->admin_model->dbSelect("id", "batches", " class_id=" . $params['class_id'] . " ");
                    $array = array();
                    foreach ($data2 as $value) {
                        $array[] = $value->id;
                    }
                    if (count($array) > 0) {
                        $exam_details->where('batch_id', $array);
                    } else {
                        $exam_details->where('batch_id', "0");
                    }
                }
            }
        } else if ($params != null && $params["tab"] === "passing_rules") {
            $data["selected_tab"] = $params["tab"];
            $data["tab_rules_selected_class_id"] = $params["class_id"];
            $data["tab_rules_selected_batch_id"] = $params["batch_id"];
            if ($params["class_id"] != "all") {
                if ($params["batch_id"] != "all") {
                    $passing_rules->where('class_id', $params["class_id"]);
                    $passing_rules->where('batch_id', $params["batch_id"]);
                    $passing_rules->unset_add(false);
                    $passing_rules->pass_default('class_id', $params["class_id"]);
                    $passing_rules->pass_default('batch_id', $params["batch_id"]);
                }
                else{
                    $passing_rules->pass_default('class_id', $params["class_id"]);
                    $passing_rules->where('class_id', $params["class_id"]);
                }
            }
        }

        $data["exam_details"] = $exam_details->render();
        $data["passing_rules"] = $passing_rules->render();
        $this->load->view('exam/index', $data);
    }

    function marks() {
        $this->load->view("exam/marksheet");
    }

    function getSubjects() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        if (!isset($request->academic_year_id)) {
            $request->academic_year_id = $this->session->userdata("userdata")["academic_year"];
        }
        
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND sh_subjects.id IN (". implode(',', login_user()->t_data->subjects) .") AND academic_year_id='$request->academic_year_id' ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 

        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){

        } else if(login_user()->user->role_id == PARENT_ROLE_ID){

        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){

        }

        $data = $this->admin_model->dbSelect("*", "subjects", " class_id='$request->class_id' AND batch_id='$request->batch_id' AND deleted_at IS NULL AND school_id='$school_id' ".$where_part);
        echo json_encode($data);
    }

    function getExams() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        //$active_academic_year = $this->admin_model->dbSelect("id", "academic_years", " school_id='$school_id' AND deleted_at IS NULL AND is_active='Y' ")[0]->id;
        $active_academic_year = $request->academic_year_id;
        $sql = "SELECT * FROM sh_exams e "
            . "LEFT JOIN sh_exam_details d ON e.id=d.exam_id "
            . "WHERE "
            . "d.class_id='$request->class_id' "
            . "AND e.academic_year_id='$active_academic_year' "
            . "AND d.batch_id='$request->batch_id' "
            . "AND d.subject_id='$request->subject_id' "
            . "AND d.school_id='$school_id' "
            . "AND e.deleted_at IS NULL "
            . "AND d.deleted_at IS NULL ";
        $data = $this->admin_model->dbQuery($sql);
        echo json_encode($data);
    }

    function getSchoolExams() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $active_academic_year = $request->academic_year_id;
        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        //$active_academic_year = $this->session->userdata("userdata")["academic_year"];
        $sql = "SELECT * FROM sh_exams WHERE academic_year_id='$active_academic_year' AND school_id='$school_id' AND deleted_at IS NULL ";
        $data = $this->admin_model->dbQuery($sql);
        echo json_encode($data);
    }
    //new method added by sheraz
    function getSchoolExamsForActiveYear() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        //$active_academic_year = $request->academic_year_id;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $this->session->userdata("userdata")["academic_year"];
        $sql = "SELECT * FROM sh_exams WHERE academic_year_id='$active_academic_year' AND school_id='$school_id' AND deleted_at IS NULL ";
        $data = $this->admin_model->dbQuery($sql);
        echo json_encode($data);
    }

    function fetchStudents() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $subject_id = $request->subject_id;
        $exam_detail_id = $request->exam_detail_id;
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $where = " class_id=" . $class_id . " AND batch_id=" . $batch_id . " AND school_id=" . $school_id . " AND exam_detail_id=" . $exam_detail_id . " AND deleted_at IS NULL ";
        $exists = $this->admin_model->dbSelect("*", "marksheets", $where);
        $passing_marks = 'NULL';
        $exam_total_marks = 'NULL';
        $exam_type = 'NULL';
        $res = $this->admin_model->dbSelect("*", "exam_details", " id='$exam_detail_id' AND deleted_at IS NULL ");
        if (count($res) > 0) {
            $passing_marks = $res[0]->passing_marks;
            $exam_total_marks = $res[0]->total_marks;
            $exam_type = $res[0]->type;
            $activities = $res[0]->activities;
            $totoal_activity_marks = $res[0]->totoal_activity_marks;
            $exam_passing_marks = $res[0]->exam_passing_marks;
            $total_exam_marks = $res[0]->total_exam_marks;
        }
        $sql = '';
        if (count($exists) > 0) {
            $data["message"] = lang('marksheet_exists');
            $data["exist"] = true;
            
            $sql_old = "SELECT "
                . "u.id,u.subject_group_id as group_id,u.name,u.avatar as student_avatar, "
                . "u.rollno,u.class_id, u.batch_id, m.status, m.obtained_marks, "
                . "m.remarks FROM "
                . "sh_students_".$school_id." u "
                . "LEFT JOIN sh_marksheets m ON u.id = m.student_id AND m.exam_detail_id =  " . $exam_detail_id . " "
                . "WHERE u.role_id=" . STUDENT_ROLE_ID . " "
                . "AND u.class_id=" . $class_id . " "
                . "AND u.batch_id=" . $batch_id . " "
                . "AND u.deleted_at=0 "
                . "AND u.school_id=" . $school_id . " ";
            
            $sql_new = "SELECT 
                u.id,
                cr.subject_group_id as group_id,
                u.name,
                u.avatar as student_avatar, 
                u.rollno,
                cr.class_id, 
                cr.batch_id, 
                m.status, 
                m.obtained_marks, 
                m.total_obtained_marks, 
                m.grade,
                m.activities,
                m.remarks,
                m.total_grade 
                FROM 
                sh_users u INNER JOIN sh_student_class_relation cr ON u.id=cr.student_id 
                LEFT JOIN sh_marksheets m ON u.id = m.student_id AND m.exam_detail_id='$exam_detail_id' 
                WHERE 
                u.role_id=".STUDENT_ROLE_ID."
                AND cr.deleted_at is NULL
                AND cr.deleted_at IS NULL
                AND cr.class_id='$class_id'
                AND cr.batch_id='$batch_id'
                AND cr.academic_year_id='$request->academic_year_id'  
                AND u.school_id=".$school_id;
            $students = $this->admin_model->dbQuery($sql_new);
           
            // updated by sheraz 21 june 2021
            /////////////////////////////////
            if ($students[0]->grade == null) {
                foreach ($students as $value) {
                    
                if ($value->activities != null) {
                    $activities = json_decode($value->activities);
                    if($activities != 'null'){
                        foreach ($activities as $key => $activity) {
                            if (isset($activity->$key->obtained_marks) && $activity->$key->obtained_marks != null) {
                                $activity->$key->obtained_marks = (int) $activity->$key->obtained_marks;
                            }
                        }
                    }
                } else if ($value->activities == NULL) {
                    if($activities){
                        $activities = json_decode($activities);
                        foreach ($activities as $key => $activity) {
                            if (isset($activity->$key->obtained_marks) && $activity->$key->obtained_marks != null) {
                                $activity->$key->obtained_marks = 0;
                            }
                        }
                    }
                }
                if ($value->obtained_marks != null) {
                    $value->obtained_marks = (int) $value->obtained_marks;
                } else if ($value->obtained_marks == null) {
                    $value->obtained_marks = 0;
                }
                }
            }
            /////////////////////////////////
            
            // if ($students[0]->grade == null) {
            //     foreach ($students as $value) {
            //     if ($value->activities != 'null') {
            //         $activities = json_decode($value->activities);
            //         if($activities){
            //             foreach ($activities as $key => $activity) {
            //                 if (isset($activity->$key->obtained_marks) && $activity->$key->obtained_marks !=  null) {
            //                     $activity->$key->obtained_marks = (int) $activity->$key->obtained_marks;
            //                 }
            //             }
            //         }
            //     }   
            //     if ($value->obtained_marks != null) {
            //         $value->obtained_marks = (int) $value->obtained_marks;
            //     }
            //     }    
            // }
            

        } else {
            //old query, updated by sheraz 17 june 2021
            // $sql = "SELECT u.id,u.subject_group_id as group_id,u.avatar as student_avatar, u.name, u.rollno,u.class_id, u.batch_id from sh_students_".$school_id." u WHERE u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $class_id . " AND u.batch_id=" . $batch_id . " AND u.deleted_at=0 AND u.school_id=" . $school_id . " ";
            
            $sql = "SELECT
                u.id,
                cr.subject_group_id as group_id,
                u.name,
                u.avatar as student_avatar,
                u.rollno,
                cr.class_id,
                cr.batch_id
                FROM
                sh_users u INNER JOIN sh_student_class_relation cr ON u.id=cr.student_id
                WHERE
                u.role_id=".STUDENT_ROLE_ID."
                AND cr.deleted_at is NULL
                AND cr.deleted_at IS NULL
                AND cr.class_id='$class_id'
                AND cr.batch_id='$batch_id'
                AND cr.academic_year_id='$request->academic_year_id'
                AND u.school_id=".$school_id;
            
            $data["message"] = "";
            $data["exist"] = false;
            $students = $this->admin_model->dbQuery($sql);
            foreach ($students as $value) {
                $value->obtained_marks = null;
                $value->total_obtained_marks = null;
                $value->grade = null;
            }
        }
        $result = $this->admin_model->dbSelect("*", "request_log", " type='mark_sheet' AND class_id='$class_id' AND batch_id='$batch_id' AND subject_id = '$subject_id' AND exam_detail_id = '$exam_detail_id' AND school_id='$school_id' AND deleted_at IS NULL and marked = 'N' ");
        if (count($result) > 0) {
            if (isset($result[0]->status) && $result[0]->status == 'draft') {
                $data['edit'] = $result[0]->status;
                $data["disable"] = "TRUE";
            } else if (isset($result[0]->status) && $result[0]->status == 'inprocess') {
                $data['edit'] = $result[0]->status;
                $data["disable"] = "TRUE";
            } else if (isset($result[0]->status) && $result[0]->status == 'approved') {
                $data['edit'] = $result[0]->status;
                $data["disable"] = "FALSE";
            } else if (isset($result[0]->status) && $result[0]->status == 'not-approved') {
                $data['edit'] = $result[0]->status;
                $data["disable"] = "TRUE";
            }
        }

        foreach ($students as $sss) {

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
            
        }

        $exam_id = ($res[0]->exam_id);

        $exam = $this->admin_model->dbSelect('title', 'exams', "id='$exam_id'");
        $class = $this->admin_model->dbSelect('name', 'classes', "id='$class_id'");
        $batch = $this->admin_model->dbSelect('name', 'batches', "id=' $batch_id'");

        $data["exam_name"] = $exam[0]->title;
        $data["class_name"] = $class[0]->name;
        $data["batch_name"] = $batch[0]->name;
        $data['total_marks'] = $res[0]->total_marks;
        $data['exam_date'] = $res[0]->exam_date;
        $data['start_time'] = $res[0]->start_time;
        $data['end_time'] = $res[0]->start_time;

        $data["students"] = $students;
        $data["passing_marks"] = $passing_marks;
        $data["exam_type"] = $exam_type;
        if (gettype($activities) == 'array') {
            $activities = json_encode($activities);   
        }
        $data["activities"] = json_decode($activities);
        $data["totoal_activity_marks"] = $totoal_activity_marks;
        $data["total_exam_marks"] = $total_exam_marks;
        $data["exam_passing_marks"] = $exam_passing_marks;
        
        if (isset($activities)) {
            foreach ($students as $key => $student) {
                if (!isset($student->activities)) {
                    if (gettype($activities) == 'array') {
                        $student->activities = json_encode($activities);
                    }
                    $student->activities = json_decode($activities);
                    foreach ($student->activities as $key1 => $activiy) {
                        $firstkey='obtained_marks'.$key;
                        $student->activities[$key1]->$firstkey = "";
                    }   
                } else {
                    if (gettype($student->activities) == 'array') {
                        $student->activities = json_encode($activities);
                    }
                    $student->activities = json_decode($student->activities);
                }
            }    
        }

        echo json_encode($data);
    }

    public function inProcessMarkSheet() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $subject_id = $request->subject_id;
        $exam_detail_id = $request->exam_detail_id;
        $school_id = $this->session->userdata('userdata')['sh_id'];

        $mark_sheetExist = $this->admin_model->dbSelect("*", "request_log", "class_id= " . $class_id . " AND batch_id=" . $batch_id . " AND school_id=" . $school_id . " AND subject_id=" . $subject_id . " AND exam_detail_id=" . $exam_detail_id . "  and marked = 'N'");
        if (count($mark_sheetExist) > 0) {
            //request found
            $r_id = $mark_sheetExist[0]->id;
            $sql1 = "UPDATE sh_request_log SET status = 'inprocess', request_time = '" . date('Y-m-d H:i:s') . "', edit_reason= '" . $request->reason . "' WHERE  class_id= " . $class_id . " AND batch_id=" . $batch_id . " AND school_id=" . $school_id . " AND subject_id=" . $subject_id . " AND exam_detail_id=" . $exam_detail_id . "  and marked = 'N'";
            $result = $this->common_model->query($sql1);
            $data['edit'] = 'inprocess';
            $data["message"] = lang('edit_attendance');
            $data["status"] = 'success';
            $data["disable"] = "TRUE";
            $data["r_id"] = $r_id;
        } else {
            //request not found
            // $record = array(
            //     "user_id" => $this->session->userdata("userdata")["user_id"],
            //     "school_id" => $school_id,
            //     "type" => 'mark_sheet',
            //     "class_id" => $class_id,
            //     "batch_id" => $batch_id,
            //     "subject_id" => $subject_id,
            //     "exam_detail_id" => $exam_detail_id,
            //     "status" => 'inprocess',
            //     "edit_reason" => $request->reason
            // );
            // $r_id = $this->admin_model->dbInsert("request_log", $record);
            $data["r_id"] = $r_id;
            $data["message"] = lang('edit_not_admin');
            $data["status"] = 'error';
            $data["disable"] = "TRUE";
        }

        echo json_encode($data);
    }

    function save() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $class_id = $request[0]->class_id;
        $batch_id = $request[0]->batch_id;
        $subject_id = $request[0]->subject_id;
        $exam_detail_id = $request[0]->exam_detail_id;
        $result2 = $this->admin_model->dbSelect("*", "request_log", " type='mark_sheet' AND class_id='$class_id' AND batch_id='$batch_id' AND subject_id='$subject_id' AND exam_detail_id = '$exam_detail_id' AND school_id='$school_id' AND deleted_at IS NULL  and marked = 'N'");
        if (count($result2) > 0) {
            $sql_2 = "UPDATE sh_request_log SET status = 'draft' WHERE  id= " . $result2[0]->id . " ";
            $result = $this->common_model->query($sql_2);

            $res = $this->common_model->saveMarksheet($request);
            if ($res) {
                $message = lang('mark_sheet_save');
                $data = array("status" => "success", "message" => $message);
                echo json_encode($data);
            }
        } else {
            $record = array(
                "user_id" => $this->session->userdata("userdata")["user_id"],
                "school_id" => $school_id,
                "type" => 'mark_sheet',
                "class_id" => $class_id,
                "batch_id" => $batch_id,
                "subject_id" => $subject_id,
                "exam_detail_id" => $exam_detail_id
            );
            $id = $this->admin_model->dbInsert("request_log", $record);
            $res = $this->common_model->saveMarksheet($request);
            if ($res) {
                $message = lang('mark_sheet_save');
                $data = array("status" => "success", "message" => $message);
                echo json_encode($data);
            }
        }
    }

    public function majorSheet() {
        $this->load->view("exam/majorsheet");
    }

    public function majorSheetForParent(){
        $data["response"] = array("status"=>"success","message"=>"Result card module temporary <strong class='text-danger'>OFF</strong> it will be <strong class='text-success'>ON</strong> after school permission.");
        $this->load->view("exam/majorSheetForParent", $data);
    }

    public function getStudentsForMajorSheet() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];

        //$active_academic_year = $this->admin_model->dbSelect("id", "academic_years", " school_id='$school_id' AND deleted_at IS NULL AND is_active='Y' ")[0]->id;
        $active_academic_year = $request->academic_year_id;
        $data = array();

        $sql1 = "SELECT "
                . "u.id as student_id, "
                . "u.subject_group_id, "
                . "sg.subjects as grouped_subjects, "
                . "u.name as student_name, "
                . "u.avatar as student_avatar, "
                . "u.rollno, "
                . "u.class_id, "
                . "u.batch_id "
                . "FROM "
                . "sh_students_".$school_id." u "
                . "LEFT JOIN sh_subject_groups sg ON u.subject_group_id=sg.id "
                . "WHERE "
                . "u.class_id=$request->class_id "
                . "AND u.batch_id=$request->batch_id "
                . "AND u.school_id=$school_id "
                . "AND u.deleted_at=0";
        
        $sql1_new = "SELECT "
                . "u.id as student_id, "
                . "cr.subject_group_id, "
                . "sg.subjects as grouped_subjects, "
                . "u.name as student_name, "
                . "u.avatar as student_avatar, "
                . "u.rollno, "
                . "cr.class_id, "
                . "cr.batch_id "
                . "FROM "
                . "sh_users u INNER JOIN sh_student_class_relation cr ON u.id=cr.student_id " 
                . "LEFT JOIN sh_subject_groups sg ON cr.subject_group_id=sg.id "
                . "WHERE "
                . "cr.class_id=$request->class_id "
                . "AND cr.batch_id=$request->batch_id "
                . "AND cr.deleted_at IS NULL "
                . "AND u.school_id=$school_id "
                . "AND cr.deleted_at is NULL";

        $sql1_1 = "SELECT "
                . "u.id as student_id, "
                . "cr.subject_group_id, "
                . "sg.subjects as grouped_subjects, "
                . "u.name as student_name, "
                . "u.avatar as student_avatar, "
                . "u.rollno, "
                . "cr.class_id, "
                . "cr.batch_id "
                . "FROM "
                . "sh_student_shifts sf LEFT JOIN sh_users u ON u.id=sf.student_id "
                . "INNER JOIN sh_student_class_relation cr on u.id = cr.student_id "
                . "LEFT JOIN sh_subject_groups sg ON cr.subject_group_id=sg.id "
                . "LEFT JOIN sh_marksheets m on m.exam_id = " . $request->exam_id . " and m.student_id = u.id "
                . "WHERE "
                . "sf.class_id=$request->class_id "
                . "AND sf.batch_id=$request->batch_id "
                . "AND m.exam_id=$request->exam_id "
                . "AND u.school_id=$school_id "
                . "AND cr.deleted_at IS NULL "
                . "AND cr.academic_year_id=$active_academic_year "
                . "AND sf.deleted_at IS NULL "
                . "AND cr.deleted_at is NULL "
                . "AND m.deleted_at is NULL";

        //$sql1_old = "SELECT id as u.student_id, name as u.student_name, u.avatar as student_avatar, u.rollno, u.class_id, u.batch_id FROM sh_users u RIGHT JOIN sh_student_shift sf ON sf.student_id = u.id WHERE u.school_id='$school_id' AND u.class_id='$request->class_id' AND u.batch_id='$request->batch_id' AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID . " ";
        $sql2 = "SELECT id as subject_id, name as subject_name FROM sh_subjects WHERE school_id='$school_id' AND class_id='$request->class_id' AND batch_id='$request->batch_id' AND deleted_at IS NULL ";
        $sql3 = "SELECT e.id as exam_id,e.title as title, ed.id as exam_detail_id, e.title as examname, ed.subject_id, ed.type, ed.total_marks, ed.passing_marks,ed.start_time, ed.end_time, ed.exam_date, ed.class_id, ed.batch_id FROM sh_exams e LEFT JOIN sh_exam_details ed ON e.id=ed.exam_id WHERE e.deleted_at IS NULL AND ed.deleted_at IS NULL AND e.id='$request->exam_id' AND e.school_id='$school_id' AND e.academic_year_id='$active_academic_year' ";
        $students = $this->admin_model->dbQuery($sql1_new . " union " . $sql1_1);
        $students_from_shift_table = $this->admin_model->dbQuery($sql1_1);
//        $students = array_merge($students,$students_from_shift_table);
        $subjects = $this->admin_model->dbQuery($sql2);
        $exams = $this->admin_model->dbQuery($sql3);

        if (count($exams) > 0) {
            if ($exams[0]->exam_detail_id == NULL) {
                $data = array("status" => "error", "data" => array(), "message" => "No exams detail found");
            } else {
                $array2 = array();
                $exam_detail_ids = array();
                array_push($exam_detail_ids, $request->exam_id);
                $student_ids = array();
                foreach ($subjects as $key => $s) {
                    $subjects[$key]->exams = array();
                    foreach ($exams as $exam) {
                        if ($s->subject_id == $exam->subject_id && $exam->exam_id == $request->exam_id) {
                            $array = (object) array(
                                        'exam_id' => $exam->exam_id,
                                        'exam_name' => $exam->title,
                                        'exam_detail_id' => $exam->exam_detail_id,
                                        'total_marks' => $exam->total_marks,
                                        'passing_marks' => $exam->passing_marks,
                                        'type' => $exam->type,
                                        'start_time' => $exam->start_time,
                                        'end_time' => $exam->end_time,
                                        'exam_date' => $exam->exam_date,
                                        'obtained_marks' => NULL,
                                        'marksheet_status' => NULL,
                                        'grade' => NULL
                            );
                            array_push($subjects[$key]->exams, $array);
                            array_push($exam_detail_ids, $exam->exam_detail_id);
                        }
                    }
                }

                foreach ($students as $index => $std) {
                    array_push($student_ids, $std->student_id);
                    $students[$index]->subjects = $subjects;
                }

                $student_ids_string = 0;
                if (count($student_ids) > 0) {
                    $student_ids_string = implode(',', $student_ids);
                }

                $sql4 = "SELECT "
                        . "sh_marksheets.*,"
                        . "sh_remarks_and_positions.remark as teacher_remark, "
                        . "sh_remarks_and_positions.id as teacher_remark_id "
                        . "FROM sh_marksheets "
                        . "LEFT JOIN sh_remarks_and_positions ON sh_marksheets.student_id=sh_remarks_and_positions.student_id "
                        . "AND sh_marksheets.exam_id=sh_remarks_and_positions.exam_id "
                        . "WHERE sh_marksheets.exam_detail_id "
                        . "IN (" . implode(',', $exam_detail_ids) . ") "
                        . "AND sh_marksheets.deleted_at IS NULL AND sh_remarks_and_positions.deleted_at is null ";
                $marks = $this->admin_model->dbQuery($sql4);

                $array = array();
                foreach ($students as $key => $value) {
                    $teacher_remark = null;
                    $teacher_remark_id = null;
                    foreach ($value->subjects as $key2 => $value2) {
                        if (count($value2->exams) > 0) {
                            $marks_index = find_marks($value->student_id, $value2->exams[0]->exam_detail_id, $marks);
                            if ($marks_index != -1) {
                                // print_r($marks[$marks_index]->activities); die();
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = $marks[$marks_index]->obtained_marks;
                                $students[$key]->subjects[$key2]->exams[0]->total_obtained_marks = $marks[$marks_index]->total_obtained_marks;
                                $students[$key]->subjects[$key2]->exams[0]->activities = json_decode($marks[$marks_index]->activities);
                                $students[$key]->subjects[$key2]->exams[0]->total_grade = $marks[$marks_index]->total_grade;
                                $students[$key]->subjects[$key2]->exams[0]->remarks = $marks[$marks_index]->remarks;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = $marks[$marks_index]->status;

                                if (($marks[$marks_index]->teacher_remark != null && $marks[$marks_index]->teacher_remark_id != null) || (!empty($marks[$marks_index]->teacher_remark) && !empty($marks[$marks_index]->teacher_remark_id))) {
                                    $teacher_remark = $marks[$marks_index]->teacher_remark;
                                    $teacher_remark_id = $marks[$marks_index]->teacher_remark_id;
                                }

                            } else {
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = null;
                                $students[$key]->subjects[$key2]->exams[0]->total_obtained_marks = null;
                                $students[$key]->subjects[$key2]->exams[0]->activities = null;
                                $students[$key]->subjects[$key2]->exams[0]->total_grade = null;
                                $students[$key]->subjects[$key2]->exams[0]->remarks = null;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = null;
                                $teacher_remark = null;
                                $teacher_remark_id = null;
                                
                            }
                        } else {
                            $array222 = (object) array(
                                        'exam_id' => $request->exam_id,
                                        'exam_detail_id' => NULL,
                                        'total_marks' => NULL,
                                        'passing_marks' => NULL,
                                        'start_time' => NULL,
                                        'type' => NULL,
                                        'end_time' => NULL,
                                        'exam_date' => NULL,
                                        'obtained_marks' => NULL,
                                        'remarks' => NULL,
                                        'marksheet_status' => NULL
                            );
                            array_push($students[$key]->subjects[$key2]->exams, $array222);
                        }
                    }
                    $is_shifted = check_student_shifted($students_from_shift_table, $students[$key]->student_id, $request->exam_id, $request->batch_id);
                    $students[$key]->is_shifted = $is_shifted;
                    $students[$key]->teacher_remark = $teacher_remark;
                    $students[$key]->teacher_remark_id = $teacher_remark_id;
                    $students[$key]->position = null;
                    $students[$key]->obtained_total = null;
                    $students[$key]->result = null;
                    array_push($array, json_encode($value));
                }

                foreach ($array as $val) {
                    array_push($array2, json_decode($val));
                }
                if ($student_ids_string == 0) {
                    $data = array("status" => "error", "data" => $array2, "message" => lang("no_record"));
                } else {
                    $data = array("status" => "success", "data" => $array2, "message" => "data found");
                }
            }
        }
    
        //-------- handle subjects groups -------//
        if(count($data["data"]) > 0){
            $subject_group = array();
            foreach($data["data"] as $d){
                $subject_group = explode(",", $d->grouped_subjects);
                foreach($d->subjects as $ys){
                    if(!in_array($ys->subject_id, $subject_group)){
                        $ys->exams[0]->exam_detail_id = NULL;
                        $ys->exams[0]->total_marks = NULL;
                        $ys->exams[0]->passing_marks = NULL;
                        $ys->exams[0]->start_time = NULL;
                        $ys->exams[0]->type = NULL;
                        $ys->exams[0]->end_time = NULL;
                        $ys->exams[0]->exam_date = NULL;
                        $ys->exams[0]->obtained_marks = NULL;
                        $ys->exams[0]->total_obtained_marks = NULL;
                        $ys->exams[0]->remarks = NULL;
                        $ys->exams[0]->marksheet_status = NULL;
                        //$ys->exams[0]->total_marks = '-';
                        //$ys->exams[0]->obtained_marks = '-';
                    }
                }
            }
        }
        
        //----------- Start::All subjects marks added or not ------------//
        foreach ($data["data"] as $keey1 => $d) {
            $data["data"][$keey1]->is_all_subjects_marks_added = 'true';
            /* foreach ($d->subjects as $u) {
              if ($u->exams[0]->obtained_marks == null) {
              $data["data"][$keey1]->is_all_subjects_marks_added = 'false';
              break;
              }
              } */
        }
        //----------- End::All subjects marks added or not ------------//
        
        
        //----------- Start::Obtained total marks ------------//
        foreach ($data["data"] as $kkk => $ss) {
            $obtained_total = 0;
            foreach ($ss->subjects as $sub) {
                if($sub->exams[0]->type == 'number') {
                // check total obtained update condition by sheraz
                    if ($sub->exams[0]->total_obtained_marks == null) {
                        $obtained_total = $sub->exams[0]->obtained_marks;    
                    } else {
                        $obtained_total = $sub->exams[0]->total_obtained_marks;
                    }
                    
                    $data["data"][$kkk]->obtained_total += intval($obtained_total);
                }
            }
        }
        //----------- End::Obtained total marks ------------//

        //----------- Start::Exam total marks ------------//
        foreach ($data["data"] as $kkkk => $ss) {
            $data["exam_total_marks"] = null;
            $exam_total_total = 0;
            foreach ($ss->subjects as $sub) {
                $exam_total_total = $sub->exams[0]->total_marks;
                $data["exam_total_marks"] += intval($exam_total_total);
            }
            break;
        }
        //----------- End::Exam total marks ------------//
        
        //----------- Start::Result Pass or Fail According to Rules ------------//
        $passing_rules = $this->admin_model->dbSelect("*", "passing_rules", " class_id='$request->class_id' AND batch_id='$request->batch_id' AND school_id='$school_id' AND exam_id='$request->exam_id' AND deleted_at IS NULL ");
        $passing_rules_obj = null;
        if (count($passing_rules) > 0) {
            $passing_rules_obj = $passing_rules[0];
        }

        if (is_null($passing_rules_obj)) {
            foreach ($data["data"] as $kk => $ss) {
                $data["data"][$kk]->result = "";
            }
        } else {

            foreach ($data["data"] as $kk => $ss) {
                $grouped_subjects = null;
                if (!is_null($ss->grouped_subjects)) {
                    $grouped_subjects = explode(",", $ss->grouped_subjects);
                }

                $number_of_subjects_passed = 0;
                $exam_total_total = 0;
                foreach ($ss->subjects as $sub) {
                    if ($sub->exams[0]->marksheet_status == 'Pass') {
                        if (!is_null($grouped_subjects)) {
                            if (in_array($sub->subject_id, $grouped_subjects)) {
                                $exam_total_total += intval($sub->exams[0]->total_marks);
                            }
                        } else if (is_null($grouped_subjects)) {
                            $exam_total_total += intval($sub->exams[0]->total_marks);
                        }
                        $number_of_subjects_passed++;
                    } else {
                        if (!is_null($grouped_subjects)) {
                            if (in_array($sub->subject_id, $grouped_subjects)) {
                                $exam_total_total += intval($sub->exams[0]->total_marks);
                            }
                        } else if (is_null($grouped_subjects)) {
                            $exam_total_total += intval($sub->exams[0]->total_marks);
                        }
                    }
                }

                $data["data"][$kk]->result = lang("fail");
                $obtained_percentage = "";
                if ($exam_total_total != 0) {
                    $obtained_percentage = $ss->obtained_total * 100 / $exam_total_total;
                }
                if ($passing_rules_obj->operator == "AND") {
                    if ($number_of_subjects_passed >= $passing_rules_obj->minimum_subjects && $obtained_percentage >= $passing_rules_obj->minimum_percentage) {
                        $data["data"][$kk]->result = lang("pass");
                    }
                } else if ($passing_rules_obj->operator == "OR") {
                    if ($number_of_subjects_passed >= $passing_rules_obj->minimum_subjects || $obtained_percentage >= $passing_rules_obj->minimum_percentage) {
                        $data["data"][$kk]->result = lang("pass");
                    }
                }
                $data["data"][$kk]->obtained_total_old = $data["data"][$kk]->obtained_total;
                $data["data"][$kk]->obtained_total = $data["data"][$kk]->obtained_total . "/" . $exam_total_total;
                if ($exam_total_total != 0) {
                    $data["data"][$kk]->percentage = round((intval($data["data"][$kk]->obtained_total) * 100 ) / intval($exam_total_total), 2);
                } else {
                    $data["data"][$kk]->percentage = "";
                }
                
            }
        }
        //----------- Start::Result Pass or Fail According to Rules ------------//
        
        //----------- Start::Calculate position ------------//
        $arr = array();
        $arr2 = array();
        foreach ($data["data"] as $sk => $ssd) {
            if ($ssd->result == lang("pass")) {
                array_push($arr, $ssd->percentage);
            }
        }
        //---------By Umar---------//
        foreach ($data["data"] as $sk1 => $ssd1) {
            if ($ssd1->result == lang("fail")) {
                array_push($arr2, $ssd1->percentage);
            }
        }


        rsort($arr);
        $old_unique = array_unique($arr);
        $unique = array();
        foreach ($old_unique as $uuu) {
            array_push($unique, $uuu);
        }
        rsort($arr2);
        $old_unique2 = array_unique($arr2);
        $unique2 = array();
        foreach ($old_unique2 as $uuu2) {
            array_push($unique2, $uuu2);
        }





        foreach ($data["data"] as $k1 => $std1) {
            if ($std1->result == lang("pass")) {
                if (in_array($std1->percentage, $unique)) {
                    $position_key = array_search($std1->percentage, $unique);
                    $data["data"][$k1]->position = $position_key + 1;
                    $data["data"][$k1]->percentage .= "%";
                }
            }
        }

        //----------- End::Calculate position ------------//
        foreach ($data["data"] as $key => $val) {
            $data["data"][$key]->new_position = $this->position_string($val->position);
        }

        //-----------Code by Umar-------------//
        foreach ($data["data"] as $k1 => $std1) {
            if ($std1->result == lang("fail")) {
                if (in_array($std1->percentage, $unique2)) {
                    $position_key = array_search($std1->percentage, $unique2);
                    $data["data"][$k1]->position = $position_key + 1000;
                    $data["data"][$k1]->percentage .= "%";
                }
            }
        }
        $this->savePosition($data["data"], $school_id, $request->exam_id);
        echo json_encode($data);
    }

    public function position_string($i) {
        if (empty($i) || is_null($i)) {
            return "";
        }
        $j = $i % 10;
        $k = $i % 100;
        if ($j == 1 && $k != 11) {
            return $i . "st";
        }
        if ($j == 2 && $k != 12) {
            return $i . "nd";
        }
        if ($j == 3 && $k != 13) {
            return $i . "rd";
        }
        return $i . "th";
    }

    public function savePosition($data, $school_id, $exam_id) {
        foreach ($data as $std) {
            if ($std->new_position != null || !empty($std)) {
                $student_id = $std->student_id;
                $res = $this->admin_model->dbSelect("*", "remarks_and_positions", " student_id='$student_id' AND exam_id='$exam_id' AND school_id='$school_id' AND deleted_at IS NULL ");
                if (count($res) > 0) {
                    $this->common_model->update_where("sh_remarks_and_positions", array("student_id" => $student_id, "exam_id" => $exam_id), array("position" => $std->new_position));
                } else {
                    $array_data = array('student_id' => $student_id, 'exam_id' => $exam_id, 'remark' => NULL, 'position' => $std->new_position, 'school_id' => $school_id);
                    $this->common_model->insert("sh_remarks_and_positions", $array_data);
                }
            }
        }
    }

    function get_teacher_of_class() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $teacher_id_object = $this->admin_model->dbSelect("teacher_id", "assign_subjects", " class_id='$request->class_id' AND batch_id='$request->batch_id' AND subject_id='$request->subject_id' AND school_id='$school_id' AND deleted_at IS NULL ");
        $teacher_id = null;
        $teacher_name = null;
        if (count($teacher_id_object) > 0) {
            $teacher_id = $teacher_id_object[0]->teacher_id;
            $teacher_name_object = $this->admin_model->dbSelect("name", "users", " id='$teacher_id' AND deleted_at=0 ");
            if (count($teacher_name_object) > 0) {
                $teacher_name = $teacher_name_object[0]->name;
            }
        }
        echo json_encode(array('teacher_name' => $teacher_name));
    }

    function save_teacher_remarks() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $res = $this->common_model->update_where("sh_remarks_and_positions", array("student_id" => $request->student_id, "exam_id" => $request->exam_id, "school_id" => $school_id), array("remark" => $request->remark));
        //$data = array("student_id"=>$request->student_id,"exam_id"=>$request->exam_id, "remark"=>$request->remark, "school_id"=>$school_id);
        //$res = $this->common_model->insert("sh_remarks_and_positions",$data);
        if ($res) {
            echo json_encode(array("status" => "success", "message" => "Teacher remarks added successfully!"));
        } else {
            echo json_encode(array("status" => "danger", "message" => "Teacher remarks could not add."));
        }
    }

    function update_teacher_remarks() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->update_where("sh_remarks_and_positions", array("id" => $request->id), array("remark" => $request->remark));
        if ($res) {
            echo json_encode(array("status" => "success", "message" => "Teacher remarks updated successfully!"));
        } else {
            echo json_encode(array("status" => "danger", "message" => "Teacher remarks could not update."));
        }
    }

    function update_marks() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $student_id = $request->student_id;
        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $subjects = $request->subjects;
        foreach ($subjects as $val) {
            if ($val->is_subject == true) {
                $activities = json_encode($val->exams[0]->activities);
                $exam_id = $val->exams[0]->exam_id;
                $exam_detail_id = $val->exams[0]->exam_detail_id;
                $new_marks = $val->exams[0]->new_marks;

                $obtain_activity_marks = 0;
                foreach ($val->exams[0]->activities as $key2 => $value) {
                    $obtain_activity_marks += $value->$key2->obtained_marks;
                }
                $total_obtained_marks = $new_marks + $obtain_activity_marks;

                if (isset($val->exams[0]->new_grade)) {
                    $new_grade = $val->exams[0]->new_grade;
                } else {
                    $new_grade = "";
                }
                
                $passing_marks = $val->exams[0]->passing_marks;
                $status = "Pass";
                if ($total_obtained_marks < $passing_marks) {
                    $status = "Fail";
                }
                $new_remarks = $val->exams[0]->new_remarks;
                if (is_null($new_remarks)) {
                    $new_remarks = '';
                }

                $data = array(
                    'exam_id' => $exam_id,
                    'exam_detail_id' => $exam_detail_id,
                    'obtained_marks' => $new_marks,
                    'total_obtained_marks' => $total_obtained_marks,
                    'total_grade' => $new_grade,
                    'status' => $status,
                    'student_id' => $student_id,
                    'school_id' => $school_id,
                    'class_id' => $class_id,
                    'batch_id' => $batch_id,
                    'remarks' => $new_remarks,
                    'activities' => $activities
                );

                if($exam_detail_id != null){
                    $this->db->replace('sh_marksheets', $data);
                }

                

            }
        }
    }

    public function delete_marks() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $student_id = $request->student_id;
        $exam_id = $request->exam_id;
        $this->db->set('deleted_at', date('Y-m-d H:i:s'));
        $this->db->where('student_id', $student_id);
        $this->db->where('exam_id', $exam_id);
        $this->db->update('sh_marksheets');

        $this->db->where('student_id', $student_id);
        $this->db->where('exam_id', $exam_id);
        $this->db->delete('sh_remarks_and_positions');
    }
    
    public function getClasses() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        //-------------------------
        $data = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            if (count(login_user()->t_data->classes) > 0) {
                $data = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ") AND academic_year_id = ".$request->academic_year_id." ORDER BY name asc ");
            }
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=".login_user()->user->sh_id." AND academic_year_id = ".$request->academic_year_id." AND deleted_at is null ORDER BY name asc ");
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND academic_year_id = ".$request->academic_year_id." AND deleted_at IS NULL ORDER BY name asc ");
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            
        }
        //-------------------------
        
        echo json_encode($data);
    }
    
    public function getClassBatches() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

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
        $data = $this->admin_model->dbSelect("*", "batches", $query . $where_part);
        echo json_encode($data);
    }
    
    public function getAcademicYears(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $data = $this->admin_model->dbSelect("*","academic_years"," school_id='$school_id' AND deleted_at IS NULL ");
        $academic_year_id = "-1";
        if(isset($this->session->userdata("userdata")["academic_year"]) && !empty($this->session->userdata("userdata")["academic_year"])){
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        }
        $response = array('current_academic_year_id'=>$academic_year_id,"data"=>$data);
        echo json_encode($response);
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

    function getSchoolExamsForParent() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year = $request->academic_year_id;
       
        $sql = "SELECT * FROM sh_exams WHERE academic_year_id='$active_academic_year' AND school_id='$school_id' AND deleted_at IS NULL ";
        
        $data = $this->admin_model->dbQuery($sql);
        echo json_encode($data);
    }

    
    public function getStudentsForMajorSheetForParent() {

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
        
        //$active_academic_year = $this->admin_model->dbSelect("id", "academic_years", " school_id='$school_id' AND deleted_at IS NULL AND is_active='Y' ")[0]->id;
        $active_academic_year = $request->academic_year_id;

        $data["data"] = array();

        $sql1 = "SELECT "
                . "u.id as student_id, "
                . "u.subject_group_id, "
                . "sg.subjects as grouped_subjects, "
                . "u.name as student_name, "
                . "u.avatar as student_avatar, "
                . "u.rollno, "
                . "u.class_id, "
                . "u.batch_id "
                . "FROM "
                . "sh_students_".$school_id." u "
                . "LEFT JOIN sh_subject_groups sg ON u.subject_group_id=sg.id "
                . "WHERE "
                . "u.class_id=$class_id "
                . "AND u.batch_id=$batch_id "
                . "AND u.id=$request->student_id "
                . "AND u.school_id=$school_id "
                . "AND u.deleted_at=0";
                //die($sql1);

        $sql1_new = "SELECT "
                . "u.id as student_id, "
                . "cr.subject_group_id, "
                . "sg.subjects as grouped_subjects, "
                . "u.name as student_name, "
                . "u.avatar as student_avatar, "
                . "u.rollno, "
                . "cr.class_id, "
                . "cr.batch_id "
                . "FROM "
                . "sh_users u INNER JOIN sh_student_class_relation cr ON u.id=cr.student_id " 
                . "LEFT JOIN sh_subject_groups sg ON cr.subject_group_id=sg.id "
                . "WHERE "
                . "cr.class_id=$class_id "
                . "AND cr.batch_id=$batch_id "
                . "AND u.id=$request->student_id "
                . "AND u.school_id=$school_id "
                . "AND u.deleted_at=0";

        $sql1_1 = "SELECT "
                . "u.id as student_id, "
                . "cr.subject_group_id, "
                . "sg.subjects as grouped_subjects, "
                . "u.name as student_name, "
                . "u.avatar as student_avatar, "
                . "u.rollno, "
                . "cr.class_id, "
                . "cr.batch_id "
                . "FROM "
                . "sh_student_shifts sf LEFT JOIN sh_users u ON u.id=sf.student_id "
                . "INNER JOIN sh_student_class_relation cr on u.id = cr.student_id "
                . "LEFT JOIN sh_subject_groups sg ON cr.subject_group_id=sg.id "
                . "LEFT JOIN sh_marksheets m on m.exam_id = " . $request->exam_id . " and m.student_id = u.id "
                . "WHERE "
                . "sf.class_id=$class_id "
                . "AND sf.batch_id=$batch_id "
                . "AND m.exam_id=$request->exam_id "
                . "AND u.id=$request->student_id "
                . "AND u.school_id=$school_id "
                . "AND cr.academic_year_id=$active_academic_year "
                . "AND sf.deleted_at IS NULL "
                . "AND u.deleted_at=0 "
                . "AND m.deleted_at is NULL";

        //$sql1_old = "SELECT id as u.student_id, name as u.student_name, u.avatar as student_avatar, u.rollno, u.class_id, u.batch_id FROM sh_users u RIGHT JOIN sh_student_shift sf ON sf.student_id = u.id WHERE u.school_id='$school_id' AND u.class_id='$request->class_id' AND u.batch_id='$request->batch_id' AND u.deleted_at=0 AND u.role_id=" . STUDENT_ROLE_ID . " ";
        //sheraz testing
        $sql2 = "SELECT id as subject_id, name as subject_name FROM sh_subjects WHERE school_id='$school_id' AND class_id='$class_id' AND batch_id='$batch_id' AND deleted_at IS NULL ";
        $sql3 = "SELECT e.id as exam_id,e.title as title, ed.id as exam_detail_id, ed.published as published, e.title as examname, ed.subject_id, ed.type, ed.total_marks, ed.passing_marks,ed.start_time, ed.end_time, ed.exam_date, ed.class_id, ed.batch_id FROM sh_exams e LEFT JOIN sh_exam_details ed ON e.id=ed.exam_id WHERE e.deleted_at IS NULL AND ed.deleted_at IS NULL AND e.id='$request->exam_id' AND e.school_id='$school_id' AND e.academic_year_id='$active_academic_year' AND ed.published='yes' ";
        $students = $this->admin_model->dbQuery($sql1_new . " union " . $sql1_1);
        $students_from_shift_table = $this->admin_model->dbQuery($sql1_1);
//        $students = array_merge($students,$students_from_shift_table);
        $subjects = $this->admin_model->dbQuery($sql2);
        $exams = $this->admin_model->dbQuery($sql3);
        // print_r($exams);
        if (count($exams) > 0) {
            if ($exams[0]->exam_detail_id == NULL) {
                $data = array("status" => "error", "data" => array(), "message" => "No exams detail found");
            } else {
                $array2 = array();
                $exam_detail_ids = array();
                array_push($exam_detail_ids, $request->exam_id);
                $student_ids = array();
                foreach ($subjects as $key => $s) {
                    $subjects[$key]->exams = array();
                    foreach ($exams as $exam) {
                        if ($s->subject_id == $exam->subject_id && $exam->exam_id == $request->exam_id) {
                            $array = (object) array(
                                        'exam_id' => $exam->exam_id,
                                        'exam_name' => $exam->title,
                                        'exam_detail_id' => $exam->exam_detail_id,
                                        'total_marks' => $exam->total_marks,
                                        'passing_marks' => $exam->passing_marks,
                                        'type' => $exam->type,
                                        'start_time' => $exam->start_time,
                                        'end_time' => $exam->end_time,
                                        'exam_date' => $exam->exam_date,
                                        'obtained_marks' => NULL,
                                        'marksheet_status' => NULL,
                                        'grade' => NULL
                            );
                            array_push($subjects[$key]->exams, $array);
                            array_push($exam_detail_ids, $exam->exam_detail_id);
                        }
                    }
                }

                foreach ($students as $index => $std) {
                    array_push($student_ids, $std->student_id);
                    $students[$index]->subjects = $subjects;
                }

                $student_ids_string = 0;
                if (count($student_ids) > 0) {
                    $student_ids_string = implode(',', $student_ids);
                }

                $sql4 = "SELECT "
                        . "sh_marksheets.*,"
                        . "sh_remarks_and_positions.remark as teacher_remark, "
                        . "sh_remarks_and_positions.id as teacher_remark_id, "
                        . "sh_remarks_and_positions.position "
                        . "FROM sh_marksheets "
                        . "LEFT JOIN sh_remarks_and_positions ON sh_marksheets.student_id=sh_remarks_and_positions.student_id "
                        . "AND sh_marksheets.exam_id=sh_remarks_and_positions.exam_id "
                        . "WHERE sh_marksheets.exam_detail_id "
                        . "IN (" . implode(',', $exam_detail_ids) . ") "
                        . "AND sh_marksheets.deleted_at IS NULL AND sh_remarks_and_positions.deleted_at is null ";
                $marks = $this->admin_model->dbQuery($sql4);
                $array = array();
                foreach ($students as $key => $value) {
                    $teacher_remark = null;
                    $teacher_remark_id = null;
                    $position = null;
                    foreach ($value->subjects as $key2 => $value2) {
                        if (count($value2->exams) > 0) {
                            $marks_index = find_marks($value->student_id, $value2->exams[0]->exam_detail_id, $marks);
                            if ($marks_index != -1) {
                                // print_r($marks[$marks_index]->activities); die();
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = $marks[$marks_index]->obtained_marks;
                                $students[$key]->subjects[$key2]->exams[0]->total_obtained_marks = $marks[$marks_index]->total_obtained_marks;
                                $students[$key]->subjects[$key2]->exams[0]->activities = json_decode($marks[$marks_index]->activities);
                                $students[$key]->subjects[$key2]->exams[0]->total_grade = $marks[$marks_index]->total_grade;
                                $students[$key]->subjects[$key2]->exams[0]->remarks = $marks[$marks_index]->remarks;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = $marks[$marks_index]->status;
                                if (($marks[$marks_index]->teacher_remark_id != null && $marks[$marks_index]->position != null) || (!empty($marks[$marks_index]->teacher_remark_id) && !empty($marks[$marks_index]->position))) {
                                    if($marks[$marks_index]->teacher_remark != null || !empty($marks[$marks_index]->teacher_remark)){
                                        $teacher_remark = "";
                                    } else{
                                        $teacher_remark = $marks[$marks_index]->teacher_remark;
                                    }
                                    $teacher_remark_id = $marks[$marks_index]->teacher_remark_id;
                                    $position = $marks[$marks_index]->position;
                                }
                            } else {
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = null;
                                $students[$key]->subjects[$key2]->exams[0]->total_obtained_marks = null;
                                $students[$key]->subjects[$key2]->exams[0]->activities = null;
                                $students[$key]->subjects[$key2]->exams[0]->total_grade = null;
                                $students[$key]->subjects[$key2]->exams[0]->remarks = null;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = null;
                                $teacher_remark = null;
                                $teacher_remark_id = null;
                                $position = null;
                                
                            }
                        } else {
                            $array222 = (object) array(
                                'exam_id' => $request->exam_id,
                                'exam_detail_id' => NULL,
                                'total_marks' => NULL,
                                'passing_marks' => NULL,
                                'start_time' => NULL,
                                'type' => NULL,
                                'end_time' => NULL,
                                'exam_date' => NULL,
                                'obtained_marks' => NULL,
                                'remarks' => NULL,
                                'marksheet_status' => NULL
                            );
                            array_push($students[$key]->subjects[$key2]->exams, $array222);
                        }
                    }
                    $is_shifted = check_student_shifted($students_from_shift_table, $students[$key]->student_id, $request->exam_id, $batch_id);
                    $students[$key]->is_shifted = $is_shifted;
                    $students[$key]->teacher_remark = $teacher_remark;
                    $students[$key]->teacher_remark_id = $teacher_remark_id;
                    $students[$key]->position = $position;
                    $students[$key]->new_position = $position;
                    $students[$key]->obtained_total = null;
                    $students[$key]->result = null;
                    array_push($array, json_encode($value));
                }

                foreach ($array as $val) {
                    array_push($array2, json_decode($val));
                }

                if ($student_ids_string == 0) {
                    $data = array("status" => "error", "data" => $array2, "message" => lang("no_record"));
                } else {
                    $data = array("status" => "success", "data" => $array2, "message" => "data found");
                }
            }
        }

        //----------- Start::All subjects marks added or not ------------//
        
        
        foreach ($data["data"] as $keey1 => $d) {
            $data["data"][$keey1]->is_all_subjects_marks_added = 'true';
            /* foreach ($d->subjects as $u) {
              if ($u->exams[0]->obtained_marks == null) {
              $data["data"][$keey1]->is_all_subjects_marks_added = 'false';
              break;
              }
              } */
        }
        //----------- End::All subjects marks added or not ------------//
        
        
        //----------- Start::Obtained total marks ------------//
        foreach ($data["data"] as $kkk => $ss) {
            $obtained_total = 0;
            foreach ($ss->subjects as $sub) {
                if($sub->exams[0]->type == 'number') {
                // check total obtained update condition by sheraz
                    if ($sub->exams[0]->total_obtained_marks == null) {
                        $obtained_total = $sub->exams[0]->obtained_marks;    
                    } else {
                        $obtained_total = $sub->exams[0]->total_obtained_marks;
                    }
                    
                    $data["data"][$kkk]->obtained_total += intval($obtained_total);
                }
            }
        }
        //----------- End::Obtained total marks ------------//

        //----------- Start::Exam total marks ------------//
        foreach ($data["data"] as $kkkk => $ss) {
            $data["exam_total_marks"] = null;
            $exam_total_total = 0;
            foreach ($ss->subjects as $sub) {
                $exam_total_total = $sub->exams[0]->total_marks;
                $data["exam_total_marks"] += intval($exam_total_total);
            }
            break;
        }
        //----------- End::Exam total marks ------------//
        
        //----------- Start::Result Pass or Fail According to Rules ------------//
        $passing_rules = $this->admin_model->dbSelect("*", "passing_rules", " class_id='$class_id' AND batch_id='$batch_id' AND school_id='$school_id' AND exam_id='$request->exam_id' AND deleted_at IS NULL ");
        $passing_rules_obj = null;
        if (count($passing_rules) > 0) {
            $passing_rules_obj = $passing_rules[0];
        }

        if (is_null($passing_rules_obj)) {
            foreach ($data["data"] as $kk => $ss) {
                $data["data"][$kk]->result = "";
            }
        } else {

            foreach ($data["data"] as $kk => $ss) {
                $grouped_subjects = null;
                if (!is_null($ss->grouped_subjects)) {
                    $grouped_subjects = explode(",", $ss->grouped_subjects);
                }

                $number_of_subjects_passed = 0;
                $exam_total_total = 0;
                foreach ($ss->subjects as $sub) {
                    if ($sub->exams[0]->marksheet_status == 'Pass') {
                        if (!is_null($grouped_subjects)) {
                            if (in_array($sub->subject_id, $grouped_subjects)) {
                                $exam_total_total += intval($sub->exams[0]->total_marks);
                            }
                        } else if (is_null($grouped_subjects)) {
                            $exam_total_total += intval($sub->exams[0]->total_marks);
                        }
                        $number_of_subjects_passed++;
                    } else {
                        if (!is_null($grouped_subjects)) {
                            if (in_array($sub->subject_id, $grouped_subjects)) {
                                $exam_total_total += intval($sub->exams[0]->total_marks);
                            }
                        } else if (is_null($grouped_subjects)) {
                            $exam_total_total += intval($sub->exams[0]->total_marks);
                        }
                    }
                }

                $data["data"][$kk]->result = lang("fail");
                $obtained_percentage = "";
                if ($exam_total_total != 0) {
                    $obtained_percentage = $ss->obtained_total * 100 / $exam_total_total;
                }
                if ($passing_rules_obj->operator == "AND") {
                    if ($number_of_subjects_passed >= $passing_rules_obj->minimum_subjects && $obtained_percentage >= $passing_rules_obj->minimum_percentage) {
                        $data["data"][$kk]->result = lang("pass");
                    }
                } else if ($passing_rules_obj->operator == "OR") {
                    if ($number_of_subjects_passed >= $passing_rules_obj->minimum_subjects || $obtained_percentage >= $passing_rules_obj->minimum_percentage) {
                        $data["data"][$kk]->result = lang("pass");
                    }
                }
                $data["data"][$kk]->obtained_total_old = $data["data"][$kk]->obtained_total;
                $data["data"][$kk]->obtained_total = $data["data"][$kk]->obtained_total . "/" . $exam_total_total;
                if ($exam_total_total != 0) {
                    $data["data"][$kk]->percentage = round((intval($data["data"][$kk]->obtained_total) * 100 ) / intval($exam_total_total), 2);
                } else {
                    $data["data"][$kk]->percentage = "";
                }
                
            }
        }
        //----------- Start::Result Pass or Fail According to Rules ------------//
        
        //----------- Start::Calculate position ------------//
        /*$arr = array();
        $arr2 = array();
        foreach ($data["data"] as $sk => $ssd) {
            if ($ssd->result == lang("pass")) {
                array_push($arr, $ssd->percentage);
            }
        }
        foreach ($data["data"] as $sk1 => $ssd1) {
            if ($ssd1->result == lang("fail")) {
                array_push($arr2, $ssd1->percentage);
            }
        }


        rsort($arr);
        $old_unique = array_unique($arr);
        $unique = array();
        foreach ($old_unique as $uuu) {
            array_push($unique, $uuu);
        }
        rsort($arr2);
        $old_unique2 = array_unique($arr2);
        $unique2 = array();
        foreach ($old_unique2 as $uuu2) {
            array_push($unique2, $uuu2);
        }
        foreach ($data["data"] as $k1 => $std1) {
            if ($std1->result == lang("pass")) {
                if (in_array($std1->percentage, $unique)) {
                    $position_key = array_search($std1->percentage, $unique);
                    $data["data"][$k1]->position = $position_key + 1;
                    $data["data"][$k1]->percentage .= "%";
                }
            }
        }

        foreach ($data["data"] as $key => $val) {
            $data["data"][$key]->new_position = $this->position_string($val->position);
        }

        foreach ($data["data"] as $k1 => $std1) {
            if ($std1->result == lang("fail")) {
                if (in_array($std1->percentage, $unique2)) {
                    $position_key = array_search($std1->percentage, $unique2);
                    $data["data"][$k1]->position = $position_key + 1000;
                    $data["data"][$k1]->percentage .= "%";
                }
            }
        }
        $this->savePosition($data["data"], $school_id, $request->exam_id);*/
        // print_r($data); die();
        echo json_encode($data);
    }
    
    public function getChilds_examintaions() {
        $user_id = $this->session->userdata("userdata")["user_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $academic_year_id = $request->academic_year_id;
        $childrens = $this->db->select('student_id,s.name')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->join('sh_academic_years ac','s.academic_year_id=ac.id')->where('guardian_id', $user_id)->get()->result();
        $data['student_ids'] = $childrens;
        echo json_encode($data);
    }

    public function getActivities(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class;
        $batch_id = $request->section;
        $subject_id = $request->subject;
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $data = $this->db->query("SELECT * FROM sh_exam_activities WHERE class_id='$class_id' AND school_id='$school_id' AND batch_id='$batch_id' AND FIND_IN_SET('$subject_id', subject_ids) AND deleted_at is NULL")->result();
        
        echo json_encode($data);

    }
    
     public function getActivitiesEdit(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $exam_detail_id = $request->id;
        $school_id = $this->session->userdata("userdata")["sh_id"];

        $data = $this->db->query("SELECT activities FROM sh_exam_details WHERE id='$exam_detail_id' AND school_id='$school_id' AND deleted_at is NULL")->result();
        $data = json_decode($data[0]->activities);
        echo json_encode($data);

    }
    
    public function saveExamDetails() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];

        if (count($request->activities) > 0 ) {
            $total = 0;
            foreach ($request->activities as $activity) {
                $total+=$activity->marks;
            }    
        }

        $res = $this->db->query("SELECT * FROM sh_exam_details WHERE exam_id='$request->title' AND class_id='$request->class' AND batch_id='$request->section' AND subject_id='$request->subject' AND academic_year_id='$academic_year_id' AND deleted_at is NULL")->result();
        
        if (count($res) > 0) {
            echo json_encode(array("status" => "error", "message" => lang('exist_exam')));
        } else {

            if (strtotime($request->start_time) > strtotime($request->end_time) ) {
                echo json_encode(array("status" => "error", "message" => lang('time_error')));   
            } elseif ($request->passing_marks > $request->total_marks ) {
                echo json_encode(array("status" => "error", "message" => lang('passing_marks_error')));
            } elseif ($request->total_exam_marks != "" && $request->total_activity_marks != "" && (($request->total_exam_marks + $request->total_activity_marks) != $request->total_marks ) ) {
                echo json_encode(array("status" => "error", "message" => lang('total_marks_error')));
            } elseif (isset($total) && $total != $request->total_activity_marks) {
                echo json_encode(array("status" => "error", "message" => lang('activity_marks_error')));
            } else {
                $data = array(
                    'exam_id' => $request->title,
                    'class_id' => $request->class,
                    'batch_id' => $request->section,
                    'subject_id' => $request->subject,
                    'type' => $request->type,
                    'school_id' => $school_id,
                    'exam_date' => to_mysql_date($request->date),
                    'start_time' => $request->start_time,
                    'end_time' => $request->end_time,
                    'total_marks' => $request->total_marks,
                    'passing_marks' => $request->passing_marks,
                    'academic_year_id' => $academic_year_id,
                    'activities' => json_encode($request->activities),
                    'totoal_activity_marks' => $request->total_activity_marks,
                    'total_exam_marks' => $request->total_exam_marks,
                    'exam_passing_marks' => $request->passing_marks
                    // 'exam_passing_marks' => $request->exam_passing_marks
                );

                $res = $this->common_model->insert("exam_details", $data);
                if ($res) {
                    echo json_encode(array("status" => "success", "message" => lang('exam_added')));
                }

            }
        }

            
    }

    public function getExamDetails(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];

        $data = $this->db->query("SELECT ed.*,c.name as class_name,e.title as exam_title,b.name as batch_name,s.name as subject_name FROM sh_exam_details ed LEFT JOIN sh_subjects s ON ed.subject_id=s.id LEFT JOIN sh_batches b ON ed.batch_id=b.id LEFT JOIN sh_exams e ON ed.exam_id=e.id LEFT JOIN sh_classes c ON ed.class_id=c.id WHERE ed.school_id='$school_id' AND ed.academic_year_id='$academic_year_id' AND ed.deleted_at is NULL")->result();
        
        echo json_encode($data);

    }

    public function deleteExamDetails(){
        $postdata = file_get_contents("php://input");
        $id = json_decode($postdata);

        $this->db->query("UPDATE `sh_exam_details` SET deleted_at=NOW() WHERE id='$id'");

        $response['deleted'] = true;
        $response['message'] = lang('delete_exam_details');
        echo json_encode($response);

    }

    public function viewExamDetails(){
        $postdata = file_get_contents("php://input");
        $id = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];

        $data = $this->db->query("SELECT ed.*,c.name as class_name,e.title as exam_title,b.name as batch_name,s.name as subject_name FROM sh_exam_details ed LEFT JOIN sh_subjects s ON ed.subject_id=s.id LEFT JOIN sh_batches b ON ed.batch_id=b.id LEFT JOIN sh_exams e ON ed.exam_id=e.id LEFT JOIN sh_classes c ON ed.class_id=c.id WHERE ed.school_id='$school_id' AND ed.academic_year_id='$academic_year_id' AND ed.id='$id' AND ed.deleted_at is NULL")->result();

        if (count($data) > 0 && $data[0]->activities != "") {
            $activities = json_decode($data[0]->activities, true);
        }

        if (isset($activities)) {
            $response['activities'] = $activities;   
        } else {
            $response['activities'] = array();
        }

        $response['data'] = $data;

        echo json_encode($response);
    }

    public function editExamDetails(){
        $postdata = file_get_contents("php://input");
        $id = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];

        $data = $this->db->query("SELECT ed.*,c.name as class_name,e.title as exam_title,b.name as batch_name,s.name as subject_name FROM sh_exam_details ed LEFT JOIN sh_subjects s ON ed.subject_id=s.id LEFT JOIN sh_batches b ON ed.batch_id=b.id LEFT JOIN sh_exams e ON ed.exam_id=e.id LEFT JOIN sh_classes c ON ed.class_id=c.id WHERE ed.school_id='$school_id' AND ed.academic_year_id='$academic_year_id' AND ed.id='$id' AND ed.deleted_at is NULL")->result();

        if (count($data) > 0 && $data[0]->activities != "") {
            $activities = json_decode($data[0]->activities, true);
        }

        if (isset($activities)) {
            $response['activities'] = $activities;   
        } else {
            $response['activities'] = array();
        }

        $response['data'] = $data;

        echo json_encode($response);
    }

    public function updateExamDetails(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;

        if (count($request->activities) > 0 ) {
            $total = 0;
            foreach ($request->activities as $activity) {
                $total+=$activity->marks;
            }    
        }
        if ($request->passing_marks > $request->total_marks ) {
                echo json_encode(array("status" => "error", "message" => lang('passing_marks_error')));
            } elseif ($request->total_exam_marks != "" && $request->total_activity_marks != "" && (($request->total_exam_marks + $request->total_activity_marks) != $request->total_marks ) ) {
                echo json_encode(array("status" => "error", "message" => lang('total_marks_error')));
            } elseif (isset($total) && $total != $request->total_activity_marks) {
                echo json_encode(array("status" => "error", "message" => lang('activity_marks_error')));
            } else {
                $data = array(
                    "exam_id" => $request->title,
                    "class_id" => $request->class,
                    "batch_id" => $request->section,
                    "subject_id" => $request->subject,
                    "type" => $request->type,
                    "exam_date" => $request->date,
                    "start_time" => $request->start_time,
                    "end_time" => $request->end_time,
                    "total_marks" => $request->total_marks,
                    "passing_marks" => $request->passing_marks,
                    "activities" => json_encode($request->activities),
                    "totoal_activity_marks" => $request->total_activity_marks,
                    "total_exam_marks" => $request->total_exam_marks,
                    "exam_passing_marks" => $request->exam_passing_marks
                );

                $res = $this->common_model->update_where("sh_exam_details", array("id" => $id), $data);

                if ($res) {
                    $response['updated'] = true;
                    $response['message'] = lang('update_exam_details');   
                }

                echo json_encode($response);
            }

    }

    public function search_examDetails () {
        $request = $this->input->post("formData");
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $class_id = $request['class_id'];
        $batch_id = $request['batch_id'];

        $data = $this->db->query("SELECT ed.*,c.name as class_name,e.title as exam_title,b.name as batch_name,s.name as subject_name FROM sh_exam_details ed LEFT JOIN sh_subjects s ON ed.subject_id=s.id LEFT JOIN sh_batches b ON ed.batch_id=b.id LEFT JOIN sh_exams e ON ed.exam_id=e.id LEFT JOIN sh_classes c ON ed.class_id=c.id WHERE ed.school_id='$school_id' AND ed.academic_year_id='$academic_year_id' AND ed.class_id='$class_id' AND ed.batch_id='$batch_id' AND ed.deleted_at is NULL")->result();
        
        echo json_encode($data);
    }

    public function publish_result() {
        $this->load->view("exam/publish_result");
    }

    public function publishExams() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $class_id = $request->class_id;
        $batch_id = $request->batch_id;

        $data = $this->db->query("SELECT ed.*,c.name as class_name,e.title as exam_title,b.name as batch_name,s.name as subject_name FROM sh_exam_details ed LEFT JOIN sh_subjects s ON ed.subject_id=s.id LEFT JOIN sh_batches b ON ed.batch_id=b.id LEFT JOIN sh_exams e ON ed.exam_id=e.id LEFT JOIN sh_classes c ON ed.class_id=c.id WHERE ed.school_id='$school_id' AND ed.academic_year_id='$academic_year_id' AND ed.class_id='$class_id' AND ed.batch_id='$batch_id' AND ed.deleted_at is NULL")->result();

        echo json_encode($data);
    }

    public function publish_result1() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;

        $res = $this->common_model->update_where("sh_exam_details", array("id" => $id), array("published" => 'yes'));

        if ($res) {
            echo json_encode(array("status" => "success", "message" => "Result Published Successfully!"));
        } else {
            echo json_encode(array("status" => "danger", "message" => "Result Not Published!"));
        }

    }

    public function unpublish_result() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $id = $request->id;

        $res = $this->common_model->update_where("sh_exam_details", array("id" => $id), array("published" => 'no'));

        if ($res) {
            echo json_encode(array("status" => "success", "message" => "Result Unpublished Successfully!"));
        } else {
            echo json_encode(array("status" => "danger", "message" => "Result Not Unpublished!"));
        }

    }

}
