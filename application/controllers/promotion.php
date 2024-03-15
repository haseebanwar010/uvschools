<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Promotion extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function index(){
        $this->load->view("promotion/index");
    }
    
    public function getAcademicYears(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*","academic_years"," school_id='$school_id' AND deleted_at IS NULL AND is_active<>'Y' ");
        echo json_encode($data);
    }
    
    public function getStudents(){
        
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $exam_sessions = $this->admin_model->dbSelect("*", "exams", " school_id='$school_id' AND deleted_at IS NULL AND academic_year_id=$request->academic_year_id ");
        $data = array();
        foreach($exam_sessions as $e){
            $data[$e->id] = array();
            $data[$e->id]["data"] = $this->getStudentsMajorSheet($request, $e->id);
            $data[$e->id]["name"] = $e->title;
        }
        
        $arr = array();
        foreach($data as $dd){
            foreach($dd["data"] as $key=>$d){
                $arr[$key] = array();
                $arr[$key]["student_id"] = $d["student_id"];
                $arr[$key]["student_name"] = $d["student_name"];
                $arr[$key]["rollno"] = $d["rollno"];
                $arr[$key]["student_avatar"] = $d["student_avatar"];
                $arr[$key]["guardian_name"] = $d["guardian_name"];
                $arr[$key]["dob"] = $d["dob"];
                $arr[$key]["is_promoted"] = $d["is_promoted"];
                $arr[$key]["exams"] = array();
            }
        }
        
        foreach($data as $ddd){
            $exam_name = $ddd["name"];
            foreach($ddd["data"] as $dddd){
                $r = array("exam_name"=> $exam_name,"result"=> $dddd["result"]);
                array_push($arr[$dddd["student_id"]]["exams"], $r);
            }
        }
        echo json_encode($arr);
    }
     
    public function getStudentsMajorSheet($request, $exam_id) {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $selected_academic_year = $request->academic_year_id;
        $data = array();

        $sql1 = "SELECT u.id as student_id,u.rollno, cr.subject_group_id, sg.subjects as grouped_subjects, "
            . "u.name as student_name,u.dob, u.avatar as student_avatar, uu.name as guardian_name, "
            . "u.rollno, cr.class_id, cr.batch_id FROM sh_student_guardians gg "
            . "LEFT JOIN sh_users u ON gg.student_id=u.id "
            . "LEFT JOIN sh_users uu ON uu.id=gg.guardian_id "
            . "INNER JOIN sh_student_class_relation cr ON u.id=cr.student_id "
            . "LEFT JOIN sh_subject_groups sg ON cr.subject_group_id=sg.id "
            . "WHERE "
            . "cr.class_id=$request->class_id "
            . "AND cr.batch_id=$request->batch_id "
            . "AND u.school_id=$school_id "
            . "AND cr.deleted_at is NULL AND ( u.name LIKE '%".$request->searchKey."%' OR u.rollno LIKE '%".$request->searchKey."%' ) ORDER BY u.name";

        /*$sql1_1 = "SELECT u.id as student_id, cr.subject_group_id, sg.subjects as grouped_subjects, 
            u.name as student_name,u.dob, u.avatar as student_avatar, uu.name as guardian_name , u.rollno, cr.class_id, cr.batch_id
            FROM sh_student_shifts sf LEFT JOIN sh_users u ON u.id=sf.student_id 
            LEFT JOIN sh_student_guardians gg ON gg.student_id=u.id
            INNER JOIN sh_users uu ON uu.id=gg.guardian_id
            INNER JOIN sh_student_class_relation cr ON u.id=cr.student_id 
            LEFT JOIN sh_subject_groups sg ON cr.subject_group_id=sg.id 
            LEFT JOIN sh_marksheets m on m.exam_id=".$exam_id." and m.student_id=u.id 
            WHERE 
            sf.class_id=$request->class_id 
            AND sf.batch_id=$request->batch_id 
            AND m.exam_id=$exam_id 
            AND u.school_id=$school_id 
            AND sf.deleted_at IS NULL 
            AND u.deleted_at=0 
            AND m.deleted_at is NULL
            AND cr.academic_year_id = ".$selected_academic_year;*/
        
        $sql2 = "SELECT id as subject_id, name as subject_name FROM sh_subjects WHERE school_id='$school_id' AND class_id='$request->class_id' AND batch_id='$request->batch_id' AND academic_year_id='$selected_academic_year' AND deleted_at IS NULL ";
        $sql3 = "SELECT e.id as exam_id,e.title as title, ed.id as exam_detail_id, e.title as examname, ed.subject_id,ed.total_marks, ed.passing_marks,ed.start_time, ed.end_time, ed.exam_date, ed.class_id, ed.batch_id FROM sh_exams e LEFT JOIN sh_exam_details ed ON e.id=ed.exam_id WHERE e.deleted_at IS NULL AND ed.deleted_at IS NULL AND e.id='$exam_id' AND e.school_id='$school_id' AND e.academic_year_id='$selected_academic_year' ";
        $students = $this->admin_model->dbQuery($sql1);

        // foreach ($students as $key => $std) {
        //     $results = $this->db->query("SELECT * FROM sh_student_class_relation WHERE student_id='$std->student_id' AND academic_year_id='$active_academic_year_id' AND deleted_at is NULL")->result();
        //     print_r($results); die();
        // }

        //$students_from_shift_table = $this->admin_model->dbQuery($sql1_1);
        $students_from_shift_table = array();
        $subjects = $this->admin_model->dbQuery($sql2);
        $exams = $this->admin_model->dbQuery($sql3);
        $data = array("status" => "error", "data" => array(), "message" => "No exams detail found");
        if (count($exams) > 0) {
            if ($exams[0]->exam_detail_id == NULL) {
                $data = array("status" => "error", "data" => array(), "message" => "No exams detail found");
            } else {
                $array2 = array();
                $exam_detail_ids = array();
                array_push($exam_detail_ids, $exam_id);

                $student_ids = array();
                foreach ($subjects as $key => $s) {
                    $subjects[$key]->exams = array();
                    foreach ($exams as $exam) {
                        if ($s->subject_id == $exam->subject_id && $exam->exam_id == $exam_id) {
                            $array = (object) array(
                                'exam_id' => $exam->exam_id,
                                'exam_name' => $exam->title,
                                'exam_detail_id' => $exam->exam_detail_id,
                                'total_marks' => $exam->total_marks,
                                'passing_marks' => $exam->passing_marks,
                                'start_time' => $exam->start_time,
                                'end_time' => $exam->end_time,
                                'exam_date' => $exam->exam_date,
                                'obtained_marks' => NULL,
                                'marksheet_status' => NULL
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
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = $marks[$marks_index]->obtained_marks;
                                $students[$key]->subjects[$key2]->exams[0]->remarks = $marks[$marks_index]->remarks;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = $marks[$marks_index]->status;
                                if (($marks[$marks_index]->teacher_remark != null && $marks[$marks_index]->teacher_remark_id != null) || (!empty($marks[$marks_index]->teacher_remark) && !empty($marks[$marks_index]->teacher_remark_id))) {
                                    $teacher_remark = $marks[$marks_index]->teacher_remark;
                                    $teacher_remark_id = $marks[$marks_index]->teacher_remark_id;
                                }
                            } else {
                                $students[$key]->subjects[$key2]->exams[0]->obtained_marks = null;
                                $students[$key]->subjects[$key2]->exams[0]->remarks = null;
                                $students[$key]->subjects[$key2]->exams[0]->marksheet_status = null;
                                $teacher_remark = null;
                                $teacher_remark_id = null;
                                
                            }
                        } else {
                            $array222 = (object) array(
                                'exam_id' => $exam_id,
                                'exam_detail_id' => NULL,
                                'total_marks' => NULL,
                                'passing_marks' => NULL,
                                'start_time' => NULL,
                                'end_time' => NULL,
                                'exam_date' => NULL,
                                'obtained_marks' => NULL,
                                'remarks' => NULL,
                                'marksheet_status' => NULL
                            );
                            array_push($students[$key]->subjects[$key2]->exams, $array222);
                        }
                    }
                    $is_shifted = check_student_shifted($students_from_shift_table, $students[$key]->student_id, $exam_id, $request->batch_id);
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

        //----------- Start::All subjects marks added or not ------------//
        foreach ($data["data"] as $keey1 => $d) {
            $data["data"][$keey1]->is_all_subjects_marks_added = 'true';
        }
        //----------- End::All subjects marks added or not ------------//
        
        
        //----------- Start::Obtained total marks ------------//
        foreach ($data["data"] as $kkk => $ss) {
            $obtained_total = 0;
            foreach ($ss->subjects as $sub) {
                $obtained_total = $sub->exams[0]->obtained_marks;
                $data["data"][$kkk]->obtained_total += intval($obtained_total);
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
        $passing_rules = $this->admin_model->dbSelect("*", "passing_rules", " class_id='$request->class_id' AND batch_id='$request->batch_id' AND school_id='$school_id' AND exam_id='$exam_id' AND academic_year_id='$selected_academic_year' AND deleted_at IS NULL ");
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
                // $data["data"][$kk]->obtained_total = $data["data"][$kk]->obtained_total . "/" . $exam_total_total;
                
                // echo $data["data"][$kk]->obtained_total;
                // echo ' '.$exam_total_total;
                // die;
                
                // die; 
                if ($exam_total_total != 0) {
                    $data["data"][$kk]->percentage = round((($data["data"][$kk]->obtained_total) * 100 ) / $exam_total_total, 2);
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

        $active_academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $students_in_active_academic_years = $this->admin_model->dbSelect("student_id","student_class_relation"," school_id='$school_id' AND academic_year_id='$active_academic_year_id'");
        $new_students_in_active_academic_years = array();
        foreach($students_in_active_academic_years as $ssdd){
            array_push($new_students_in_active_academic_years, $ssdd->student_id);
        }
        
        $yasir_new_array = array();
        foreach($data["data"] as $yyyyy){
            $yasir_new_array[$yyyyy->student_id] = array();
            $yasir_new_array[$yyyyy->student_id]["is_promoted"] = false;
            if(in_array($yyyyy->student_id, $new_students_in_active_academic_years)){
                $yasir_new_array[$yyyyy->student_id]["is_promoted"] = true;
            }
            $yasir_new_array[$yyyyy->student_id]["student_id"] = $yyyyy->student_id;
            $yasir_new_array[$yyyyy->student_id]["student_name"] = $yyyyy->student_name;
            $yasir_new_array[$yyyyy->student_id]["rollno"] = $yyyyy->rollno;
            $yasir_new_array[$yyyyy->student_id]["student_avatar"] = $yyyyy->student_avatar;
            $yasir_new_array[$yyyyy->student_id]["guardian_name"] = $yyyyy->guardian_name;
            $yasir_new_array[$yyyyy->student_id]["dob"] = $yyyyy->dob;
            if($yyyyy->result == "") {
                $yasir_new_array[$yyyyy->student_id]["result"] = "-";
            } else {
                $yasir_new_array[$yyyyy->student_id]["result"] = $yyyyy->result;
            }
        }
        return $yasir_new_array;
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
        //$data = $this->admin_model->dbSelect("*", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL ");
        echo json_encode($data);
    }
    
    public function getActiveAcademicYearClasses() {
        $active_academic_year_id = $this->session->userdata("userdata")["academic_year"];
        //-------------------------
        $data = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            if (count(login_user()->t_data->classes) > 0) {
                $data = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ") AND academic_year_id = ".$active_academic_year_id." ORDER BY name asc ");
            }
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=".login_user()->user->sh_id." AND academic_year_id = ".$active_academic_year_id." AND deleted_at is null ORDER BY name asc ");
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND academic_year_id = ".$active_academic_year_id." AND deleted_at IS NULL ORDER BY name asc ");
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
    
    public function getActiveAcademicYearClassBatches() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $active_academic_year_id = $this->session->userdata("userdata")["academic_year"];
        
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
            $query = "  school_id=" . login_user()->user->sh_id . " AND class_id='$request->class_id' AND academic_year_id=".$active_academic_year_id." AND deleted_at IS NULL ";
        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND academic_year_id=".$active_academic_year_id." AND deleted_at IS NULL ";
        }

        $where_part .= " ORDER BY name ASC  ";
        $data = $this->admin_model->dbSelect("*", "batches", $query . $where_part);
        // print_r($this->db->last_query()); die();
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
    
    public function getSubjectgroups(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year_id = $this->session->userdata("userdata")["academic_year"];
        
        $data = $this->admin_model->dbSelect("*","subject_groups"," academic_year_id='$active_academic_year_id' AND class_id='$request->class_id' AND batch_id='$request->batch_id' AND school_id='$school_id' AND deleted_at IS NULL ");
        echo json_encode($data);
    }
    
    public function promoteStudents(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $active_academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $class_id = $request->filter->class_id;
        $batch_id = $request->filter->batch_id;
        $reason = $request->filter->reason;
        $subject_group_id = $request->filter->subject_group_id;

        $previous_academic_year = $request->selected_academic_year;

        
        $response = array();
        foreach($request->students as $std){
            $res = $this->admin_model->dbSelect("*","student_class_relation"," student_id='$std' AND class_id='$class_id' AND batch_id='$batch_id' AND subject_group_id='$subject_group_id' AND academic_year_id='$active_academic_year_id' AND school_id='$school_id' AND deleted_at IS NULL ");
            if(count($res) > 0){
                array_push($response, array("status"=>"error","message"=>$std." - ".lang("lbl_student_already_promoted")));
            } else {
                $discount_id = $this->db->select('discount_id')->from('sh_student_class_relation')->where('student_id', $std)->where('academic_year_id', $previous_academic_year)->get()->row()->discount_id;
                $data = array(
                    "student_id"=>$std,
                    "class_id"=>$class_id,
                    "batch_id"=>$batch_id,
                    "subject_group_id"=>$subject_group_id,
                    "academic_year_id"=>$active_academic_year_id,
                    "school_id"=>$school_id,
                    "discount_id"=> $discount_id
                );
                $id = $this->admin_model->dbInsert("sh_student_class_relation",$data);
                if($id>0){
                    $d = array(
                        "shift_date"=>date("Y-m-d H;i:s"),
                        "student_id"=>$std,
                        "class_id"=>$class_id,
                        "batch_id"=>$batch_id,
                        "tag"=>"is_transferred",
                        "reason"=>$reason,
                        "academic_year_id"=>$this->session->userdata("userdata")["academic_year"]
                    );
                    $this->admin_model->dbInsert("sh_student_shifts",$d);
                    array_push($response, array("status"=>"success","message"=>$std." - ".lang("lbl_student_promoted_successfully")));
                }
            }
        }
        echo json_encode($response);
    }
    
}
