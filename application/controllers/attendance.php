<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function show() {
        $this->load->view("attendance/attendance");
    }

    public function getClasses() {
        //-------------------------
        $data = "";
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            
            if (count(login_user()->t_data->classes) > 0) {
                //print_r('in count');
                $data = $this->admin_model->dbSelect("*", "classes", " id IN (" . implode(',', login_user()->t_data->classes) . ") AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." ORDER BY name asc ");
            }
            //print_r('out condition');die();
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=".login_user()->user->sh_id." AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." AND deleted_at is null ORDER BY name asc ");
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." AND deleted_at IS NULL ORDER BY name asc ");
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            
        }
        //-------------------------
        //$data = $this->admin_model->dbSelect("*", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL ");
        echo json_encode($data);
    }

    public function getChilds()
    {
        $user_id = $this->session->userdata("userdata")["user_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        
        $childrens = $this->db->select('student_id,s.name')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->where('guardian_id', $user_id)->get()->result();
        $data['student_ids'] = $childrens;

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
            $query = "  school_id=" . login_user()->user->sh_id . " AND class_id='$request->class_id' AND academic_year_id=(Select id from sh_academic_years Where school_id=" . login_user()->user->sh_id . " AND is_active='Y') AND deleted_at IS NULL ";
        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND academic_year_id=(Select id from sh_academic_years Where school_id=" . login_user()->user->sh_id . " AND is_active='Y') AND deleted_at IS NULL ";
        }

        $where_part .= " ORDER BY name ASC  ";
        $data = $this->admin_model->dbSelect("*", "batches", $query . $where_part);
        echo json_encode($data);
    }

    public function fetchStudentsAttendance() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $date_now = date("Y-m-d");
        $date_provided = date('Y-m-d', strtotime(str_replace('/', '-', $request->date)));
        $academic_year = $this->db->select('start_date,end_date')->from('sh_academic_years')->where('school_id',$this->session->userdata("userdata")["sh_id"])->where('is_active','Y')->where('deleted_at is null')->get()->row();
        if(!empty($academic_year)){
            $start_date = date('Y-m-d',strtotime($academic_year->start_date));
            $end_date = date('Y-m-d',strtotime($academic_year->end_date));
        }
        if(empty($academic_year)){
            $data["message"] = lang('no_academic_year');
            $data["students"] = null;
        }else if($date_provided < $start_date || $date_provided > $end_date){
            $data["message"] = lang('date_not_in_academic');
            $data["students"] = null;
        }else if ($date_provided > $date_now) {
            $data["message"] = lang('future_attendance');
            $data["students"] = null;
        } else {
            $school_id = $this->session->userdata('userdata')['sh_id'];
            if (!(isset($request->flag) && $request->flag == true)) {
                $request->date = to_mysql_date($request->date);
            }
            $day = date("l", strtotime($request->date));
            $working_days = json_decode($this->admin_model->dbSelect("working_days", "school", " id=$school_id ")[0]->working_days);
            // print_r($working_days);

            foreach ($working_days as $val) {
                if ($val->label == $day && $val->val == "false") {
                    $is_current_day_off = $val;
                    break;
                }
            }

            $data = array();

            if (!isset($is_current_day_off) || empty($is_current_day_off)) {
                // check attendance already marked ?

                // $where = " date='" . $request->date . "' AND school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL AND user_id in (select id from sh_students_".$this->session->userdata("userdata")["sh_id"]." where class_id=" . $request->class_id . " AND batch_id=" . $request->batch_id . " and deleted_at = 0 )";

                $where = " date='" . $request->date . "' AND school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL AND user_id in (select student_id from sh_student_class_relation where class_id=" . $request->class_id . " AND batch_id=" . $request->batch_id . " and deleted_at is NULL )";                

                $exists = $this->admin_model->dbSelect("*", "attendance", $where);
                if (count($exists) > 0) {

                    $request_exists = $this->admin_model->dbSelect("*", "request_log", " type='attendance' AND class_id='$class_id' AND batch_id='$batch_id' AND date = '$request->date' AND school_id='$school_id' AND deleted_at IS NULL and marked = 'N'");
                    if (count($request_exists) == 0) {
                        $request_log_data = array('type' => 'attendance', 'class_id' => $class_id, 'batch_id' => $batch_id, 'date' => $request->date, 'school_id' => $school_id);
                        $this->db->insert('sh_request_log', $request_log_data);
                    }
                    $data["message"] = lang('attendance_marked');
                    $data['edit'] = 'draft';
                    $data["disable"] = "TRUE";
                } else {
                    $this->db->delete('sh_request_log', array('type' => 'attendance', 'class_id' => $class_id, 'batch_id' => $batch_id, 'date' => $request->date, 'school_id' => $school_id));
                }
                // $query1 = "SELECT u.id, u.name, u.rollno,u.class_id, u.batch_id, t.status, cls.name as class_name,cls.id as old_class_id ,btch.name as batch_name,btch.id as old_batch_id,bth.id as new_batch_id,bth.name as new_batch,cl.name as new_class,cl.id as new_class_id,t.comment from sh_students_".$this->session->userdata("userdata")["sh_id"]." u LEFT JOIN sh_classes cls ON cls.id=u.class_id  LEFT JOIN sh_batches btch ON btch.id = u.batch_id LEFT JOIN sh_attendance t ON u.id = t.user_id LEFT JOIN sh_classes cl on t.class_id = cl.id LEFT JOIN sh_batches bth on t.batch_id = bth.id WHERE ";

                // $where2 = " t.date = '" . $request->date . "' AND u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $request->class_id . " AND u.batch_id=" . $request->batch_id . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ORDER BY u.name ";

                $query1 = "SELECT 
                            cr.student_id as id, 
                            u.name, 
                            u.rollno,
                            cr.class_id, 
                            cr.batch_id, 
                            t.status, 
                            cls.name as class_name,
                            cls.id as old_class_id ,
                            btch.name as batch_name,
                            btch.id as old_batch_id,
                            bth.id as new_batch_id,
                            bth.name as new_batch,
                            cl.name as new_class,
                            cl.id as new_class_id,
                            t.comment
                            from sh_student_class_relation cr
                            LEFT JOIN sh_users u ON u.id=cr.student_id  
                            LEFT JOIN sh_classes cls ON cls.id=cr.class_id 
                            LEFT JOIN sh_batches btch ON btch.id = cr.batch_id 
                            LEFT JOIN sh_attendance t ON cr.student_id = t.user_id 
                            LEFT JOIN sh_classes cl on t.class_id = cl.id 
                            LEFT JOIN sh_batches bth on t.batch_id = bth.id 
                            WHERE t.date = '" . $request->date . "' AND u.role_id=3 AND cr.class_id=" . $request->class_id . " AND cr.batch_id=" . $request->batch_id . " AND cr.deleted_at is NULL AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ORDER BY u.name";

                $students_one = $this->admin_model->dbQuery($query1);            

                foreach ($students_one as $temp_std) {
                    if ($temp_std->old_class_id != $temp_std->new_class_id || $temp_std->old_batch_id != $temp_std->new_batch_id) {
                        $temp_std->message = lang('attendance_marked_student');
                        $temp_std->message = str_replace('{x}', $temp_std->new_class, $temp_std->message);
                        $temp_std->message = str_replace('{y}', $temp_std->new_batch, $temp_std->message);
                    } else {
                        $temp_std->message = "";
                    }
                }

                // $query1 = "SELECT u.id, u.name, u.rollno,u.class_id, u.batch_id, cls.name as class_name, btch.name as batch_name, 'Present' as `status` from sh_students_".$this->session->userdata("userdata")["sh_id"]." u LEFT JOIN sh_classes cls ON cls.id = u.class_id LEFT JOIN sh_batches btch ON btch.id = u.batch_id WHERE ";
                // $where2 = " u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $request->class_id . " AND u.batch_id=" . $request->batch_id . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
                // $students_two = $this->admin_model->dbQuery($query1 . $where2 . " AND NOT EXISTS(SELECT null FROM sh_attendance t WHERE t.user_id = u.id AND t.date = '" . $request->date . "') ORDER BY u.name ");
                // print_r($this->db->last_query()); die();

                $query1 = "SELECT 
                        cr.student_id as id,
                        u.name, 
                        u.rollno,
                        cr.class_id, 
                        cr.batch_id, 
                        cls.name as class_name, 
                        btch.name as batch_name, 
                        'Present' as `status` 
                        from sh_student_class_relation cr
                        LEFT JOIN sh_users u ON u.id = cr.student_id 
                        LEFT JOIN sh_classes cls ON cls.id = cr.class_id 
                        LEFT JOIN sh_batches btch ON btch.id = cr.batch_id 
                        WHERE u.role_id=3 AND cr.class_id=" . $request->class_id . " AND cr.batch_id=" . $request->batch_id . " AND cr.deleted_at is NULL AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " AND NOT EXISTS(SELECT null FROM sh_attendance t WHERE t.user_id = cr.student_id AND t.date = '" . $request->date . "') ORDER BY u.name";

                $students_two = $this->admin_model->dbQuery($query1);
                $students = array_merge($students_one, $students_two);


                $data["students_marked"] = $students_one;
                $data["students_pending"] = $students_two;
                $data["students"] = $students;


                $date = $request->date;
                $result = $this->admin_model->dbSelect("*", "request_log", " type='attendance' AND class_id='$class_id' AND batch_id='$batch_id' AND date = '$date' AND school_id='$school_id' AND deleted_at IS NULL and marked = 'N' ");


                if (count($result) > 0) {
                    if (isset($result[0]->status) && $result[0]->status == 'draft') {
                        $data['edit'] = $result[0]->status;
                        $data["disable"] = "TRUE";
                        $data["message"] = lang('attendance_marked');
                    } else if (isset($result[0]->status) && $result[0]->status == 'inprocess') {
                        $data['edit'] = $result[0]->status;
                        $data["disable"] = "TRUE";
                        $data["message"] = lang('attendance_marked');
                    } else if (isset($result[0]->status) && $result[0]->status == 'approved') {
                        $data["message"] = lang('attendance_marked');
                        $data['edit'] = $result[0]->status;
                        $data["disable"] = "FALSE";
                    } else if (isset($result[0]->status) && $result[0]->status == 'not-approved') {
                        $data['edit'] = $result[0]->status;
                        $data["message"] = lang('attendance_marked');
                        $data["disable"] = "TRUE";
                    }
                }
            } else if (isset($is_current_day_off->label)) {

                $data["message"] = $is_current_day_off->label . " " . lang('not_working_day');
            }
        }
        echo json_encode($data);
    }

    public function save() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $school_id = $this->session->userdata("userdata")["sh_id"];
        $class_id = $request[0]->class_id;
        $batch_id = $request[0]->batch_id;
        $date = to_mysql_date($request[0]->date);

        $result2 = $this->admin_model->dbSelect("*", "request_log", " type='attendance' AND class_id='$class_id' AND batch_id='$batch_id' AND date = '$date' AND school_id='$school_id' AND deleted_at IS NULL and marked = 'N' ");

        if (count($result2) > 0) {
            $sql_2 = "UPDATE sh_request_log SET status = 'draft' WHERE  id= " . $result2[0]->id . " ";

            $result = $this->common_model->query($sql_2);
            $data["message"] = lang('new_attendance');
            $data["status"] = "success";
            $this->common_model->saveAttendance($request);
        } else {
            //request not found
            $record = array(
                "user_id" => $this->session->userdata("userdata")["user_id"],
                "school_id" => $school_id,
                "type" => 'attendance',
                "class_id" => $request[0]->class_id,
                "batch_id" => $request[0]->batch_id,
                "date" => $date
            );
            $id = $this->admin_model->dbInsert("request_log", $record);
            $result3 = $this->common_model->saveAttendance($request);

            if ($result3 && $id) {
                $data["message"] = lang('new_attendance');
                $data["status"] = "success";
            } else {
                $data["message"] = lang('att_not_marked');
                $data["status"] = "error";
            }
        }
        echo json_encode($data);
    }

    public function report() {
        $from = '2018-01-01';
        $to = '2018-01-31';
        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_students_".$this->session->userdata("userdata")["sh_id"]." u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=3 AND t.class_id=11 AND t.batch_id=7 AND t.deleted_at is null GROUP by u.name ";
        $data["attendance"] = $this->admin_model->dbQuery($sql);

        $this->load->view("attendance/report", $data);
    }

    public function parent_report()
    {
        $from = '2018-01-01';
        $to = '2018-01-31';
        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_students_".$this->session->userdata("userdata")["sh_id"]." u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=3 AND t.class_id=11 AND t.batch_id=7 AND t.deleted_at is null GROUP by u.name ";

        $data["attendance"] = $this->admin_model->dbQuery($sql);
        $this->load->view("attendance/parent_report", $data);
    }
    public function student_report()
    {
        $this->load->view("attendance/student_report");
    }

    public function generate_report() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $search_by_name = $request->name;
        $month = $request->month;
        $arr = explode("to", $month);
        $from_arr = explode("/", $arr[0]);
        $to_arr = explode("/", $arr[1]);
        $from = trim($from_arr[0] . "-" . $from_arr[1] . "-" . $from_arr[2]);
        $to = trim($to_arr[0] . "-" . $to_arr[1] . "-" . $to_arr[2]);

        //$academic_year_id = $request->academic_year_id;
        //$current_academic_year = $this->admin_model->dbSelect("start_date", "academic_years", " id='$academic_year_id' ")[0]->start_date;
        //$year = (new DateTime($current_academic_year))->format("Y");
        //$from = $year . "-" . $month . "-01";
        //$to = date("Y-m-t", strtotime($from));

        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_users u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=" . STUDENT_ROLE_ID . " AND t.class_id='$class_id' AND t.batch_id='$batch_id' AND u.name like '%".$search_by_name."%' AND t.deleted_at is null GROUP by u.name ";
        $data["att"] = $this->admin_model->dbQuery($sql);

        if(count($data["att"]) != 0){
        foreach ($data["att"] as $att) {
            $attends_dates = explode(",", $att->attendance);

            $dayss = array();
            $statuss = array();
            for ($i = 0; $i < count($attends_dates); $i++) {
                $date_and_status = explode("=>", $attends_dates[$i]);
                $date = $date_and_status[0];
                $status = lang('lbl_'.strtolower($date_and_status[1]));;
                $date_day = explode("-", $date)[2];
                $statuss[$date_day] = $status;
                array_push($dayss, $date_day);
            }

            $attendace = array();
            $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
            $data["to"] = (new DateTime($to))->format("d");

            for ($ii = $data["from"]; $ii <= $data["to"]; $ii++) {
                $ii = ($ii < 10 ? '0' . $ii : $ii);
                if (array_key_exists($ii, $statuss)) {
                    $attendace["'" . $ii . "'"] = $statuss[$ii];
                } else {
                    $attendace["'" . $ii . "'"] = "-";
                }
            }
            $att->attendance = $attendace;
        }
        }else{
            $students = $this->db->select("name,id")->from('sh_students_'.$this->session->userdata("userdata")["sh_id"])->where('class_id',$class_id)->where('batch_id',$batch_id)->where('role_id',STUDENT_ROLE_ID)->like('name', $search_by_name)->where('deleted_at',0)->get()->result();
            if(count($students) != 0){
                $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
                $data["to"] = (new DateTime($to))->format("d");
                for($i = $data["from"] ; $i <= $data["to"] ; $i++){
                    $i = ($i < 10 ? '0' . $i : $i);
                    $attendance["'" . $i . "'"] = "-";
                }


                foreach ($students as $val) {
                    $val->attendance = $attendance;
                }

                $data["att"] = $students;
            }
        }
        echo json_encode($data);
        //echo $this->load->view("attendance/report_filter", $data, true);
    }



    /* Attendance Report For Parent  */

    public function generate_reportForParent() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $student_id = $request->student_id;
        // print_r($student_id);
        // die();
        $month = $request->month;
        $arr = explode("to", $month);
        $from_arr = explode("/", $arr[0]);
        $to_arr = explode("/", $arr[1]);
        $from = trim($from_arr[0] . "-" . $from_arr[1] . "-" . $from_arr[2]);
        $to = trim($to_arr[0] . "-" . $to_arr[1] . "-" . $to_arr[2]);

        //$academic_year_id = $request->academic_year_id;
        //$current_academic_year = $this->admin_model->dbSelect("start_date", "academic_years", " id='$academic_year_id' ")[0]->start_date;
        //$year = (new DateTime($current_academic_year))->format("Y");
        //$from = $year . "-" . $month . "-01";
        //$to = date("Y-m-t", strtotime($from));

        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_users u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=" . STUDENT_ROLE_ID . " AND t.user_id='$student_id' AND t.deleted_at is null GROUP by u.name ";


        $data["att"] = $this->admin_model->dbQuery($sql);


        if(count($data["att"]) != 0){
        foreach ($data["att"] as $att) {
            $attends_dates = explode(",", $att->attendance);

            $dayss = array();
            $statuss = array();
            for ($i = 0; $i < count($attends_dates); $i++) {
                $date_and_status = explode("=>", $attends_dates[$i]);
                $date = $date_and_status[0];
                $status = lang('lbl_'.strtolower($date_and_status[1]));;
                $date_day = explode("-", $date)[2];
                $statuss[$date_day] = $status;
                array_push($dayss, $date_day);
            }

            $attendace = array();
            $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
            $data["to"] = (new DateTime($to))->format("d");

            for ($ii = $data["from"]; $ii <= $data["to"]; $ii++) {
                $ii = ($ii < 10 ? '0' . $ii : $ii);
                if (array_key_exists($ii, $statuss)) {
                    $attendace["'" . $ii . "'"] = $statuss[$ii];
                } else {
                    $attendace["'" . $ii . "'"] = "-";
                }
            }
            $att->attendance = $attendace;
        }
        }else{
            $students = $this->db->select("name,id")->from('sh_students_'.$this->session->userdata("userdata")["sh_id"])->where('id',$student_id)->where('role_id',STUDENT_ROLE_ID)->where('deleted_at',0)->get()->result();
            if(count($students) != 0){
                $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
                $data["to"] = (new DateTime($to))->format("d");
                for($i = $data["from"] ; $i <= $data["to"] ; $i++){
                    $i = ($i < 10 ? '0' . $i : $i);
                    $attendance["'" . $i . "'"] = "-";
                }


                foreach ($students as $val) {
                    $val->attendance = $attendance;
                }

                $data["att"] = $students;
            }
        }
        echo json_encode($data);
        //echo $this->load->view("attendance/report_filter", $data, true);
    }


    public function generate_reportForStudent() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // print_r($request); die();
        $student_id = $request->id;
        
        $month = strval($request->month);
        // print_r($month);
        // die();
        $arr = explode("to", $month);
        $from_arr = explode("/", $arr[0]);
        $to_arr = explode("/", $arr[1]);
        $from = trim($from_arr[0] . "-" . $from_arr[1] . "-" . $from_arr[2]);
        $to = trim($to_arr[0] . "-" . $to_arr[1] . "-" . $to_arr[2]);

        //$academic_year_id = $request->academic_year_id;
        //$current_academic_year = $this->admin_model->dbSelect("start_date", "academic_years", " id='$academic_year_id' ")[0]->start_date;
        //$year = (new DateTime($current_academic_year))->format("Y");
        //$from = $year . "-" . $month . "-01";
        //$to = date("Y-m-t", strtotime($from));

        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_students_".$this->session->userdata("userdata")["sh_id"]." u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=" . STUDENT_ROLE_ID . " AND t.user_id='$student_id' AND t.deleted_at is null GROUP by u.name ";


        $data["att"] = $this->admin_model->dbQuery($sql);


        if(count($data["att"]) != 0){
        foreach ($data["att"] as $att) {
            $attends_dates = explode(",", $att->attendance);

            $dayss = array();
            $statuss = array();
            for ($i = 0; $i < count($attends_dates); $i++) {
                $date_and_status = explode("=>", $attends_dates[$i]);
                $date = $date_and_status[0];
                $status = lang('lbl_'.strtolower($date_and_status[1]));;
                $date_day = explode("-", $date)[2];
                $statuss[$date_day] = $status;
                array_push($dayss, $date_day);
            }

            $attendace = array();
            $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
            $data["to"] = (new DateTime($to))->format("d");

            for ($ii = $data["from"]; $ii <= $data["to"]; $ii++) {
                $ii = ($ii < 10 ? '0' . $ii : $ii);
                if (array_key_exists($ii, $statuss)) {
                    $attendace["'" . $ii . "'"] = $statuss[$ii];
                } else {
                    $attendace["'" . $ii . "'"] = "-";
                }
            }
            $att->attendance = $attendace;
        }
        }else{
            $students = $this->db->select("name,id")->from('sh_students_'.$this->session->userdata("userdata")["sh_id"])->where('id',$student_id)->where('role_id',STUDENT_ROLE_ID)->where('deleted_at',0)->get()->result();
            if(count($students) != 0){
                $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
                $data["to"] = (new DateTime($to))->format("d");
                for($i = $data["from"] ; $i <= $data["to"] ; $i++){
                    $i = ($i < 10 ? '0' . $i : $i);
                    $attendance["'" . $i . "'"] = "-";
                }


                foreach ($students as $val) {
                    $val->attendance = $attendance;
                }

                $data["att"] = $students;
            }
        }
        echo json_encode($data);
        //echo $this->load->view("attendance/report_filter", $data, true);
    }

    /* End Attendance Report For Parent */




    public function employee() {
        $this->load->view("attendance/employee");
    }

    public function fetchEmployeesAttendance() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $request->department_id = ($request->department_id == 'all') ? 0 : $request->department_id;
        $request->category_id = ($request->category_id == 'all') ? 0 : $request->category_id;
        $school_id = $this->session->userdata('userdata')['sh_id'];
        $date = to_mysql_date($request->date);
        $date_now = date("Y-m-d");
        $date_provided = date('Y-m-d', strtotime(str_replace('/', '-', $request->date)));
        $academic_year = $this->db->select('start_date,end_date')->from('sh_academic_years')->where('school_id',$this->session->userdata("userdata")["sh_id"])->where('is_active','Y')->where('deleted_at is null')->get()->row();
        if(!empty($academic_year)){
            $start_date = date('Y-m-d',strtotime($academic_year->start_date));
            $end_date = date('Y-m-d',strtotime($academic_year->end_date));
        }
        

        $query1 = "";
        $where2 = "";
        $employee_one = array();
        $employee_two = array();
        if(empty($academic_year)){
            $data["message"] = lang('no_academic_year');
            $data["students"] = null;
        }else if($date_provided < $start_date || $date_provided > $end_date){
            $data["message"] = lang('date_not_in_academic');
            $data["students"] = null;
        }else if ($date_provided > $date_now) {
            $data["message"] = lang('future_attendance');
            $data["employees"] = null;
        } else {
            if (isset($request->flag)) {
                $dyr = explode("-", $request->date);
                $request->date = $dyr[2] . "/" . $dyr[1] . "/" . $dyr[0];
            }

            $is_current_day_off = array();
            $dyr = explode("/", $request->date);
            $formated_date = $dyr[2] . "-" . $dyr[1] . "-" . $dyr[0];
            $day = date("l", strtotime($formated_date));

            $working_days = json_decode($this->admin_model->dbSelect("working_days", "school", " id=$school_id ")[0]->working_days);
            foreach ($working_days as $val) {
                if ($val->label == $day && $val->val == "false") {
                    $is_current_day_off = $val;
                    break;
                }
            }

            $data = array();

            if ($is_current_day_off == null || empty($is_current_day_off) || !isset($is_current_day_off)) {
                // check attendance already marked ?
                if($request->department_id == 0){
                    $sql = "SELECT a.* FROM `sh_attendance` a left join `sh_users` u on u.id = a.user_id WHERE a.date='" . to_mysql_date($request->date) . "' AND u.school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND u.role_id=" . EMPLOYEE_ROLE_ID . " AND a.deleted_at is null";
                }else if($request->category_id == 0){
                    $sql = "SELECT a.* FROM `sh_attendance` a left join `sh_users` u on u.id = a.user_id WHERE a.date='" . to_mysql_date($request->date) . "' AND u.school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND u.role_id=" . EMPLOYEE_ROLE_ID . " AND u.department_id ='$request->department_id' AND a.deleted_at is null";
                }else{
                $sql = "SELECT a.* FROM `sh_attendance` a left join `sh_users` u on u.id = a.user_id WHERE a.date='" . to_mysql_date($request->date) . "' AND u.school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND u.role_id=" . EMPLOYEE_ROLE_ID . " AND u.department_id='$request->department_id' AND u.role_category_id='$request->category_id' AND a.deleted_at is null";
                }
                
                $exists = $this->admin_model->dbQuery($sql);
                if (count($exists) > 0) {
                    

                    //------------------- Start::Query # 01 -----------------//
                    $query1 = "SELECT u.id, u.name, u.job_title, t.status, 'yes' as 'marked', t.comment from sh_users u LEFT JOIN sh_attendance t ON u.id = t.user_id WHERE ";
                    $where2 = " t.date = '" . to_mysql_date($request->date) . "' AND u.role_id=" . EMPLOYEE_ROLE_ID . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
                    if (isset($request->category_id) && $request->category_id != 0) {
                        $where2 .= " AND u.role_category_id=" . $request->category_id;
                    }
                    if (isset($request->department_id) && $request->department_id != 0) {
                        $where2 .= " AND u.department_id=" . $request->department_id;
                    }
                    $employee_one = $this->admin_model->dbQuery($query1 . $where2);
                    //------------------- End::Query # 01 -----------------//
                    //-------set attendance retake request----------//
                    $result = $this->admin_model->dbSelect("*", "request_log", " type='emp_attendance' AND date = '$date_provided' AND school_id='$school_id' AND department_id='$request->department_id' AND category_id='$request->category_id' AND deleted_at IS NULL and marked = 'N' ");
                    if (count($result) > 0) {
                        $data["message"] = "*Attendance for this date had already been marked.";
                        if (isset($result[0]->status) && $result[0]->status == 'draft') {
                            $data['edit'] = $result[0]->status;
                            $data["disable"] = "TRUE";
                            $data["message"] = lang('attendance_marked');
                        } else if (isset($result[0]->status) && $result[0]->status == 'inprocess') {
                            $data['edit'] = $result[0]->status;
                            $data["disable"] = "TRUE";
                            $data["message"] = lang('attendance_marked');
                        } else if (isset($result[0]->status) && $result[0]->status == 'approved') {
                            $data["message"] = lang('attendance_marked');
                            $data['edit'] = $result[0]->status;
                            $data["disable"] = "FALSE";
                        } else if (isset($result[0]->status) && $result[0]->status == 'not-approved') {
                            $data['edit'] = $result[0]->status;
                            $data['edit'] = $result[0]->status;
                            $data["message"] = lang('attendance_marked');
                            $data["disable"] = "TRUE";
                        }
                    }
                    //---------------------------------------------//
                } else {
                    $data["message"] = "";
                }

                //------------------- Start::Query # 02 -----------------//
                $query1 = "SELECT u.id, u.name, u.job_title, 'Present' as `status`, 'no' as 'marked' from sh_users u WHERE ";
                $where2 = " u.role_id=" . EMPLOYEE_ROLE_ID . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
                if (isset($request->category_id) && $request->category_id != 'all') {
                    $where2 .= " AND u.role_category_id=" . $request->category_id;
                }
                if (isset($request->department_id) && $request->department_id != 'all') {
                    $where2 .= " AND u.department_id=" . $request->department_id;
                }
                $final_query = $query1 . $where2 . " AND NOT EXISTS(SELECT null FROM sh_attendance t WHERE t.user_id = u.id AND t.date = '" . to_mysql_date($request->date) . "') ";
                $employee_two = $this->admin_model->dbQuery($final_query);

                $employees = array_merge($employee_one, $employee_two);

                //------------------- End::Query # 02 -----------------//

                $data["employees"] = $employees;
                $data["marked"] = count($employee_two);
            } else {
                $data["message"] = $is_current_day_off->label . " " . lang('not_working_day');
                $data["employees"] = null;
            }
        }

        echo json_encode($data);
    }

    public function saveEmployee() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $filter = $request->filter;
        $request = $request->data;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $date = to_mysql_date($filter->date);

        $department_id = ($filter->department_id == 'all') ? 0 : $filter->department_id;
        $category_id = ($filter->category_id == 'all') ? 0 : $filter->category_id;

        $result2 = $this->admin_model->dbSelect("*", "request_log", " type='emp_attendance' AND department_id='$department_id' AND category_id='$category_id' AND date = '$date' AND school_id='$school_id' AND deleted_at IS NULL and marked = 'N' ");
        if (count($result2) > 0) {
            $sql_2 = "UPDATE sh_request_log SET status = 'draft' WHERE  id= " . $result2[0]->id . " ";
            $result = $this->common_model->query($sql_2);
            $data["message"] = lang('new_attendance');
            $data["status"] = "success";
            $this->common_model->saveEmployeeAttendance($request);
        } else {
            if($department_id == 0){
                $record = array(
                    "user_id" => $this->session->userdata("userdata")["user_id"],
                    "school_id" => $school_id,
                    "type" => 'emp_attendance',
                    "date" => $date,
                    "department_id" => 0,
                    "category_id" => 0
                );

                $this->admin_model->dbInsert("request_log", $record);
                $departments = $this->db->select('id')->from('sh_departments')->where('school_id', $school_id)->where('deleted_at',0)->get()->result();
                foreach($departments as $dep){

                    $record = array(
                        "user_id" => $this->session->userdata("userdata")["user_id"],
                        "school_id" => $school_id,
                        "type" => 'emp_attendance',
                        "date" => $date,
                        "department_id" => $dep->id,
                        "category_id" => 0
                    );

                    $this->admin_model->dbInsert("request_log", $record);
                    
                    
                    $categories = $this->db->select('id')->from('sh_role_categories')->where('department_id', $dep->id)->where('deleted_at',0)->get()->result();

                    foreach ($categories as $cat) {
                        $check_exist = $this->db->select('id')->from('sh_request_log')->where('department_id',$dep->id)->where('category_id',$cat->id)->where('date',$date)->where('deleted_at is null')->get()->result();
                        if(!$check_exist){
                            $record = array(
                                "user_id" => $this->session->userdata("userdata")["user_id"],
                                "school_id" => $school_id,
                                "type" => 'emp_attendance',
                                "date" => $date,
                                "department_id" => $dep->id,
                                "category_id" => $cat->id
                            );

                            $this->admin_model->dbInsert("request_log", $record);
                        }
                    }
                }
            }else{
                if($category_id == 0){
                    $record = array(
                        "user_id" => $this->session->userdata("userdata")["user_id"],
                        "school_id" => $school_id,
                        "type" => 'emp_attendance',
                        "date" => $date,
                        "department_id" => $department_id,
                        "category_id" => 0
                    );

                    $this->admin_model->dbInsert("request_log", $record);
                    $categories = $this->db->select('id')->from('sh_role_categories')->where('department_id', $department_id)->where('deleted_at',0)->get()->result();
                    foreach ($categories as $cat) {
                        $check_exist = $this->db->select('id')->from('sh_request_log')->where('department_id',$department_id)->where('category_id',$cat->id)->where('date',$date)->where('deleted_at is null')->get()->result();
                        if(!$check_exist){
                            $record = array(
                                "user_id" => $this->session->userdata("userdata")["user_id"],
                                "school_id" => $school_id,
                                "type" => 'emp_attendance',
                                "date" => $date,
                                "department_id" => $department_id,
                                "category_id" => $cat->id
                            );

                            $this->admin_model->dbInsert("request_log", $record);
                        }
                        
                    }

                }else{
                    $record = array(
                        "user_id" => $this->session->userdata("userdata")["user_id"],
                        "school_id" => $school_id,
                        "type" => 'emp_attendance',
                        "date" => $date,
                        "department_id" => $department_id,
                        "category_id" => $category_id
                    );
                    $this->admin_model->dbInsert("request_log", $record);
                }
            }
            $result3 = $this->common_model->saveEmployeeAttendance($request);
            if ($result3) {
                $data["message"] = lang('new_attendance');
                $data["status"] = "success";
            } else {
                $data["message"] = lang('att_not_marked');
                $data["status"] = "error";
            }
        }

        $data = array("status" => "success", "message" => lang('new_attendance'));
        echo json_encode($data);
    }

    public function inProcessEmpAttendance() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $date = $request->date;
        $school_id = $this->session->userdata('userdata')['sh_id'];

        $attExist = $this->admin_model->dbSelect("*", "request_log", "type = 'emp_attendance' AND department_id='$request->department_id' AND category_id='$request->category_id' AND school_id=" . $school_id . " AND date='" . to_mysql_date($date) . "' and marked = 'N' ");
        if (count($attExist) > 0) {
            //request found
            $r_id = $attExist[0]->id;
            $sql1 = "UPDATE sh_request_log SET status = 'inprocess', request_time = '".date("Y-m-d H:i:s")."' , edit_reason= '".$request->reason ."' WHERE   id = " . $attExist[0]->id . "";
            $result = $this->common_model->query($sql1);
            $data['edit'] = 'inprocess';
            $data["message"] = lang('edit_attendance');
            $data["status"] = 'success';
            $data["disable"] = "TRUE";
            $data["r_id"] = $r_id;
        } else {
            $data["message"] = lang('edit_not_admin');
            $data["status"] = 'error';
            $data["disable"] = "TRUE";
        }
        echo json_encode($data);
    }

    public function emp_report() {
        $from = '2018-01-01';
        $to = '2018-01-31';
        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_users u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=4 AND t.deleted_at is null and u.deleted_at=0 GROUP by u.name ";
        $data["attendance"] = $this->admin_model->dbQuery($sql);
        $this->load->view("attendance/report_Employee");
    }

    public function generate_employee_report() {

        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $month = $request->month;
        $arr = explode("to", $month);
        $from_arr = explode("/", $arr[0]);
        $to_arr = explode("/", $arr[1]);
        $from = trim($from_arr[0] . "-" . $from_arr[1] . "-" . $from_arr[2]);
        $to = trim($to_arr[0] . "-" . $to_arr[1] . "-" . $to_arr[2]);

        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_users u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=" . EMPLOYEE_ROLE_ID . " AND t.deleted_at is null and u.deleted_at=0 AND u.school_id=" . login_user()->user->sh_id . " GROUP by u.name ";
        $data["att"] = $this->admin_model->dbQuery($sql);
        if(count($data["att"]) != 0){
        foreach ($data["att"] as $att) {
            $attends_dates = explode(",", $att->attendance);

            $dayss = array();
            $statuss = array();
            for ($i = 0; $i < count($attends_dates); $i++) {
                $date_and_status = explode("=>", $attends_dates[$i]);
                $date = $date_and_status[0];
                    $status = lang('lbl_'.strtolower($date_and_status[1]));
                $date_day = explode("-", $date)[2];
                $statuss[$date_day] = $status;
                array_push($dayss, $date_day);
            }

            $attendace = array();
            $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
            $data["to"] = (new DateTime($to))->format("d");

            for ($ii = $data["from"]; $ii <= $data["to"]; $ii++) {
                $ii = ($ii < 10 ? '0' . $ii : $ii);
                if (array_key_exists($ii, $statuss)) {
                    $attendace["'" . $ii . "'"] = $statuss[$ii];
                } else {
                    $attendace["'" . $ii . "'"] = "-";
                }
            }
            $att->attendance = $attendace;
        }
        }else{
            $employees = $this->db->select("name,id")->from('sh_users')->where('school_id',$this->session->userdata('userdata')['sh_id'])->where('role_id',EMPLOYEE_ROLE_ID)->where('deleted_at',0)->get()->result();
            if(count($employees) != 0){
                $data["from"] = explode("0", (new DateTime($from))->format("d"))[1];
                $data["to"] = (new DateTime($to))->format("d");
                for($i = $data["from"] ; $i <= $data["to"] ; $i++){
                    $i = ($i < 10 ? '0' . $i : $i);
                    $attendance["'" . $i . "'"] = "-";
                }


                foreach ($employees as $val) {
                    $val->attendance = $attendance;
                }

                $data["att"] = $employees;
            }
        }
        echo json_encode($data);
        //echo $this->load->view("attendance/report_filter", $data, true);
    }

    public function inProcessAttendance() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $date = $request->date;
        $school_id = $this->session->userdata('userdata')['sh_id'];
        $user_id = $this->session->userdata('userdata')['user_id'];

        $attExist = $this->admin_model->dbSelect("*", "request_log", "class_id= " . $class_id . " AND batch_id=" . $batch_id . " AND school_id=" . $school_id . " AND date='" . to_mysql_date($date) . "' and marked = 'N' ");

        if (count($attExist) > 0) {
            //request found
            $r_id = $attExist[0]->id;
            $sql1 = "UPDATE sh_request_log SET status = 'inprocess', request_time = '".date("Y-m-d H:i:s")."', edit_reason= '".$request->reason ."', user_id = " . $user_id . " WHERE  class_id= " . $class_id . " AND batch_id=" . $batch_id . " AND school_id=" . $school_id . " AND date='" . to_mysql_date($date) . "' and marked = 'N' ";

            $result = $this->common_model->query($sql1);
            $data['edit'] = 'inprocess';
            $data["message"] = lang('edit_attendance');
            $data["status"] = 'success';
            $data["disable"] = "TRUE";
            $data["r_id"] = $r_id;
        } else {
            $data["message"] = lang('edit_not_admin');
            $data["status"] = 'error';
            $data["disable"] = "TRUE";
        }
        echo json_encode($data);
    }

    function count_academic_year_months() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $array = $this->admin_model->dbSelect("*", "academic_years", " school_id='$school_id' AND is_active='Y' AND deleted_at IS NULL ");
        $academic_year = NULL;
        if (count($array) > 0) {
            $academic_year = $array[0];
        }

        $dateStart = new DateTime($academic_year->start_date);
        $dateFin = new DateTime($academic_year->end_date);
        $firstDay = $dateStart->format('Y/m/d');
        $lastDay = $dateStart->format('Y/m/t');
        $totalMonths = $dateStart->diff($dateFin)->m + ($dateStart->diff($dateFin)->y * 12);
        $result = [];
        for ($i = 0; $i <= $totalMonths; $i++) {
            if ($i != 0) {
                $dateStart->modify('first day of next month');
                $firstDay = $dateStart->format('Y/m/d');
                $lastDay = $dateStart->format('Y/m/t');
            }

            $nextDate = explode('/', $firstDay);

            $totalDays = cal_days_in_month(CAL_GREGORIAN, $nextDate[1], $nextDate[2]);
            if ($i == 0) {
                $totalDays -= $dateStart->format('d');
            } else if ($i == $totalMonths) {
                $totalDays = $dateFin->format('d');
            }

            $result["$firstDay to $lastDay"] = date("F", strtotime($firstDay)) . ' - ' . date("Y", strtotime($firstDay));
        }
        echo json_encode($result);
    }

    function resquestReason(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $response = $this->admin_model->dbSelect("edit_reason", "request_log", " id=' $request->log_id'")[0];
    
        echo json_encode($response);
    }

    function responseReason(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $response = $this->admin_model->dbSelect("response", "request_log", " id=' $request->log_id'")[0];

        echo json_encode($response);
    }
    
    public function getClassBatchesForAssignmnet() 
    {
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
            $query = "  school_id=" . login_user()->user->sh_id . " AND class_id='$request->class_id' AND academic_year_id=(Select id from sh_academic_years Where school_id=" . login_user()->user->sh_id . " AND is_active='Y') AND deleted_at IS NULL ";
        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND academic_year_id=(Select id from sh_academic_years Where school_id=" . login_user()->user->sh_id . " AND is_active='Y') AND deleted_at IS NULL ";
        }

        $where_part .= " ORDER BY name ASC  ";
        $data = $this->admin_model->dbSelect("*", "batches", $query . $where_part);
        echo json_encode($data);
    }
    
}
