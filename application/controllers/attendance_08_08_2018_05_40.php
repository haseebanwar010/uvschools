<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Attendance extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login/index"));
        }
        check_user_permissions();
    }

    public function show() {
        $this->load->view("attendance/attendance");
    }

    public function getClasses() {
        
        //-------------------------
        $data = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $data = $this->admin_model->dbSelect("*", "classes", " id IN (".implode(',',login_user()->t_data->classes) . ")");
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            $data = $this->admin_model->dbSelect("*", "classes", " school_id=" . login_user()->user->sh_id . " AND deleted_at IS NULL ");
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            
        }
        //-------------------------
        
        //$data = $this->admin_model->dbSelect("*", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL ");
        echo json_encode($data);
    }

    public function getClassBatches() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        //-------------------------
        $where_part = "";
        if(login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() ==  login_user()->user->teacher_dept_id){
            $where_part =  " AND id IN (". implode(',', login_user()->t_data->batches) .") ";
        } else if(login_user()->user->role_id == EMPLOYEE_ROLE_ID){ 
            
        } 
        else if(login_user()->user->role_id == ADMIN_ROLE_ID){
            
        } else if(login_user()->user->role_id == PARENT_ROLE_ID){
            
        } else if(login_user()->user->role_id == STUDENT_ROLE_ID){
            
        }
        //-------------------------
        
        if ($request->class_id != "") {
            $query = "  school_id=" . login_user()->user->sh_id . " AND class_id='$request->class_id' AND academic_year_id=(Select id from sh_academic_years Where school_id=" . login_user()->user->sh_id . " AND is_active='Y') AND deleted_at IS NULL ";
        } else {
            $query = " school_id=" . login_user()->user->sh_id . " AND academic_year_id=(Select id from sh_academic_years Where school_id=" . login_user()->user->sh_id . " AND is_active='Y') AND deleted_at IS NULL ";
        }
        
        $data = $this->admin_model->dbSelect("*", "batches", $query . $where_part);
        echo json_encode($data);
    }

    public function fetchStudentsAttendance() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // print_r($request);
        $class_id = $request->class_id;
        $batch_id = $request->batch_id;

        $school_id = $this->session->userdata('userdata')['sh_id'];

                    if(isset($request->flag) && $request->flag == true){
                        $day = date("l", strtotime($request->date));
                    }else{
                        $is_current_day_off = array();
                        $dyr = explode("/", $request->date);
                        $formated_date = $dyr[2]."-".$dyr[1]."-".$dyr[0];
                        $day = date("l", strtotime($formated_date));
                        
                    }
        
        $working_days = json_decode($this->admin_model->dbSelect("working_days","school"," id=$school_id ")[0]->working_days);
         // print_r($working_days);
        
                        foreach($working_days as $val) {
                            if($val->label == $day && $val->val == "false"){
                                $is_current_day_off = $val;
                                break;
                            }
                        }
        
        $data = array();
        
       if(!isset($is_current_day_off) || empty($is_current_day_off) ){
         // check attendance already marked ?
            if(isset($request->flag) && $request->flag == true){

                $where = " class_id=" . $request->class_id . " AND batch_id=" . $request->batch_id . " AND date='" . $request->date . "' AND school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL ";
             }
            else{
                $where = " class_id=" . $request->class_id . " AND batch_id=" . $request->batch_id . " AND date='" . to_mysql_date($request->date) . "' AND school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL ";
            }

            $exists = $this->admin_model->dbSelect("*", "attendance", $where);
                if (count($exists) > 0) {

                    $data["message"] = "*Attendance for this date had already been marked.";
                    $data['edit'] = 'draft';
                    $data["disable"] = "TRUE";
                }
                    $query1 = "SELECT u.id, u.name, u.rollno,u.class_id, u.batch_id, t.status, cls.name as class_name, btch.name as batch_name from sh_users u LEFT JOIN sh_classes cls ON cls.id=u.class_id  LEFT JOIN sh_batches btch ON btch.id = u.batch_id LEFT JOIN sh_attendance t ON u.id = t.user_id WHERE ";    

                if(isset($request->flag) && $request->flag == true){
                        $where2 = " t.date = '" . $request->date . "' AND u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $request->class_id . " AND u.batch_id=" . $request->batch_id . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
                    }else{
                       $where2 = " t.date = '" . to_mysql_date($request->date) . "' AND u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $request->class_id . " AND u.batch_id=" . $request->batch_id . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
                    }

                $students = $this->admin_model->dbQuery($query1 . $where2);
                if($students){
                    $data["students"] = $students;
                }else{
                    $query1 = "SELECT u.id, u.name, u.rollno,u.class_id, u.batch_id, cls.name as class_name, btch.name as batch_name, 'Present' as `status` from sh_users u LEFT JOIN sh_classes cls ON cls.id = u.class_id LEFT JOIN sh_batches btch ON btch.id = u.batch_id WHERE ";
                    $where2 = " u.role_id=" . STUDENT_ROLE_ID . " AND u.class_id=" . $request->class_id . " AND u.batch_id=" . $request->batch_id . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
                     $students = $this->admin_model->dbQuery($query1 . $where2);
                     $data["students"] = $students;
                }

                if(isset($request->flag) && $request->flag == true){
                        $date = $request->date;
                        $result = $this->admin_model->dbSelect("*","request_log"," type='attendance' AND class_id='$class_id' AND batch_id='$batch_id' AND date = '$date' AND school_id='$school_id' AND deleted_at IS NULL ");
                   }else{
                        $date = to_mysql_date($request->date);
                        $result = $this->admin_model->dbSelect("*","request_log"," type='attendance' AND class_id='$class_id' AND batch_id='$batch_id' AND date = '$date' AND school_id='$school_id' AND deleted_at IS NULL ");
                     }   

                if(count($result) > 0 ){
                    if(isset($result[0]->status) && $result[0]->status == 'draft'){
                        $data['edit'] = $result[0]->status;
                        $data["disable"] = "TRUE";
                        $data["message"] = "*Attendance for this date had already been marked.";
                    }else if(isset($result[0]->status) && $result[0]->status == 'inprocess'){
                         $data['edit'] = $result[0]->status;
                        $data["disable"] = "TRUE";
                        $data["message"] = "*Attendance for this date had already been marked.";
                    }else if(isset($result[0]->status) && $result[0]->status == 'approved'){
                        $data["message"] = "*Attendance for this date had already been marked.";
                        $data['edit'] = $result[0]->status;
                    }else if(isset($result[0]->status) && $result[0]->status == 'not-approved'){
                        $data['edit'] = $result[0]->status;
                        $data['edit'] = $result[0]->status;
                        $data["message"] = "*Attendance for this date had already been marked.";
                        $data["disable"] = "TRUE";
                    }
                
                }
                
        }else if(isset($is_current_day_off->label)){

            $data["message"] = $is_current_day_off->label." is not working day";
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

        $result2 = $this->admin_model->dbSelect("*","request_log"," type='attendance' AND class_id='$class_id' AND batch_id='$batch_id' AND date = '$date' AND school_id='$school_id' AND deleted_at IS NULL ");
        
            if(count($result2) > 0){
                    $sql_2 = "UPDATE sh_request_log SET status = 'draft' WHERE  id= ".$result2[0]->id." ";

                    $result = $this->common_model->query($sql_2);
                    $data["message"] = "Attendance marked successfully.";
                    $data["status"] = "success";
            }else{
                 //request not found
                $record = array(
                    "user_id"=>$this->session->userdata("userdata")["user_id"],
                    "school_id"=>$school_id,
                    "type"=>'attendance',
                    "class_id"=>$request[0]->class_id,
                    "batch_id"=>$request[0]->batch_id,
                    "date"=>$date
                );
                $id = $this->admin_model->dbInsert("request_log", $record);
                $result3 = $this->common_model->saveAttendance($request);

                    if($result3 && $id){
                        $data["message"] = "Attendance marked successfully.";
                        $data["status"] = "success";
                    }else{
                        $data["message"] = "Attendance not marked.";
                        $data["status"] = "error";
                    }
            }
         echo json_encode($data);
    }

    public function report() {
        $from = '2018-01-01';
        $to = '2018-01-31';
        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_users u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=3 AND t.class_id=11 AND t.batch_id=7 AND t.deleted_at is null GROUP by u.name ";
        $data["attendance"] = $this->admin_model->dbQuery($sql);

        $this->load->view("attendance/report", $data);
    }

    public function generate_report() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $month = $request->month;
        $academic_year_id = $request->academic_year_id;

        $current_academic_year = $this->admin_model->dbSelect("start_date", "academic_years", " id='$academic_year_id' ")[0]->start_date;
        $year = (new DateTime($current_academic_year))->format("Y");
        $from = $year . "-" . $month . "-01";
        $to = date("Y-m-t", strtotime($from));

        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_users u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=" . STUDENT_ROLE_ID . " AND t.class_id='$class_id' AND t.batch_id='$batch_id' AND t.deleted_at is null GROUP by u.name ";
        $data["att"] = $this->admin_model->dbQuery($sql);
        
        foreach($data["att"] as $att){
            $attends_dates = explode(",", $att->attendance);
            
            $dayss = array();
            $statuss = array();
            for ($i = 0; $i < count($attends_dates); $i++) {
                $date_and_status = explode("=>", $attends_dates[$i]);
                $date = $date_and_status[0];
                $status = $date_and_status[1];
                $date_day = explode("-", $date)[2];
                $statuss[$date_day] = $status;
                array_push($dayss, $date_day);
            }
            
            $attendace = array();
            $data["from"] = explode("0",(new DateTime($from))->format("d"))[1];
            $data["to"] = (new DateTime($to))->format("d");
            
            for($ii=$data["from"]; $ii<=$data["to"]; $ii++){
                $ii = ($ii < 10 ? '0'.$ii : $ii);
                if(array_key_exists($ii, $statuss)){
                    $attendace["'".$ii."'"] = $statuss[$ii];
                } else {
                    $attendace["'".$ii."'"] = "-";
                }
            }
            $att->attendance = $attendace;
        }
        echo json_encode($data);
        //echo $this->load->view("attendance/report_filter", $data, true);
    }

    public function employee() {
        $this->load->view("attendance/employee");
    }
    
    public function fetchEmployeesAttendance() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $school_id = $this->session->userdata('userdata')['sh_id'];
        
        $is_current_day_off = array();
        $dyr = explode("/", $request->date);
        $formated_date = $dyr[2]."-".$dyr[1]."-".$dyr[0];
        $day = date("l", strtotime($formated_date));
        
        $working_days = json_decode($this->admin_model->dbSelect("working_days","school"," id=$school_id ")[0]->working_days);
        foreach($working_days as $val) {
            if($val->label == $day && $val->val == "false"){
                $is_current_day_off = $val;
                break;
            }
        }
        
        $data = array();
        if($is_current_day_off == null || empty($is_current_day_off) || !isset($is_current_day_off)){
            // check attendance already marked ?
            $sql = "SELECT a.* FROM `sh_attendance` a left join `sh_users` u on u.id = a.user_id WHERE a.date='" . to_mysql_date($request->date) . "' AND u.school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND u.role_id=" . EMPLOYEE_ROLE_ID . " AND a.deleted_at is null";
            $exists = $this->admin_model->dbQuery($sql);
            $query1 = "SELECT u.id, u.name, u.job_title, 'Present' as `status` from sh_users u WHERE ";
            $where2 = " u.role_id=" . EMPLOYEE_ROLE_ID . " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
            if (count($exists) > 0) {
                $data["message"] = lang('attendance_marked');
                $query1 = "SELECT u.id, u.name, u.job_title, t.status from sh_users u LEFT JOIN sh_attendance t ON u.id = t.user_id WHERE ";
                $where2 = " t.date = '" . to_mysql_date($request->date) . "' AND u.role_id=" . EMPLOYEE_ROLE_ID .  " AND u.deleted_at=0 AND u.school_id=" . $this->session->userdata('userdata')['sh_id'] . " ";
            } else {
                $data["message"] = "";
            }
            $students = $this->admin_model->dbQuery($query1 . $where2);
            $data["employees"] = $students;
        } else {
            $data["message"] = $is_current_day_off->label." ".lang('not_working_day');
            $data["employees"] = null;
        }
        
        echo json_encode($data);
    }
    
    public function saveEmployee() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $res = $this->common_model->saveEmployeeAttendance($request);
        if ($res) {
            $data = array("status" => "success", "message" => lang('new_attendance'));
            echo json_encode($data);
        }
    }
    
    public function report_employee() {
        $from = '2018-01-01';
        $to = '2018-01-31';
        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_users u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=4 AND t.deleted_at is null GROUP by u.name ";
        $data["attendance"] = $this->admin_model->dbQuery($sql);
        $this->load->view("attendance/report_Employee");
    }
    
    public function generate_employee_report() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $month = $request->month;
//        $sql = " SELECT id from sh_academic_years )";
        $academic_year_id = $this->admin_model->dbSelect("id", "academic_years", " school_id=" . login_user()->user->sh_id . " AND deleted_at IS NULL AND is_active = 'Y'");
        
        $id = $academic_year_id[0]->id;
//        print_r($academic_year_id[0]->id);
//        die();
        $current_academic_year = $this->admin_model->dbSelect("start_date", "academic_years", "id= $id ");
//        print_r($current_academic_year);
//        die();
        $year = (new DateTime($current_academic_year[0]->start_date))->format("Y");
        $from = $year . "-" . $month . "-01";
        $to = date("Y-m-t", strtotime($from));

        $sql = " SELECT u.name,u.id as id, GROUP_CONCAT(concat(t.date,'=>',t.status) SEPARATOR ',') as attendance "
                . "FROM sh_users u left join `sh_attendance` t on u.id = t.user_id WHERE t.date BETWEEN '" . $from . "' AND '" . $to . "' AND "
                . "u.role_id=" . EMPLOYEE_ROLE_ID . " AND t.deleted_at is null GROUP by u.name ";
        $data["att"] = $this->admin_model->dbQuery($sql);
        
        foreach($data["att"] as $att){
            $attends_dates = explode(",", $att->attendance);
            
            $dayss = array();
            $statuss = array();
            for ($i = 0; $i < count($attends_dates); $i++) {
                $date_and_status = explode("=>", $attends_dates[$i]);
                $date = $date_and_status[0];
                $status = $date_and_status[1];
                $date_day = explode("-", $date)[2];
                $statuss[$date_day] = $status;
                array_push($dayss, $date_day);
            }
            
            $attendace = array();
            $data["from"] = explode("0",(new DateTime($from))->format("d"))[1];
            $data["to"] = (new DateTime($to))->format("d");
            
            for($ii=$data["from"]; $ii<=$data["to"]; $ii++){
                $ii = ($ii < 10 ? '0'.$ii : $ii);
                if(array_key_exists($ii, $statuss)){
                    $attendace["'".$ii."'"] = $statuss[$ii];
                } else {
                    $attendace["'".$ii."'"] = "-";
                }
            }
            $att->attendance = $attendace;
        }
        echo json_encode($data);
        //echo $this->load->view("attendance/report_filter", $data, true);
    }
    
    public function inProcessAttendance(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);

        $class_id = $request->class_id;
        $batch_id = $request->batch_id;
        $date = $request->date;
        $school_id = $this->session->userdata('userdata')['sh_id'];
            
         $attExist = $this->admin_model->dbSelect("*", "request_log", "class_id= ".$class_id." AND batch_id=".$batch_id." AND school_id=".$school_id." AND date='".to_mysql_date($date)."' ");
         
        if(count($attExist) > 0){
            //request found
            $sql1 = "UPDATE sh_request_log SET status = 'inprocess' WHERE  class_id= ".$class_id." AND batch_id=".$batch_id." AND school_id=".$school_id." AND date='".to_mysql_date($date)."'";
                $result = $this->common_model->query($sql1);
                $data['edit'] = 'inprocess';
                $data["message"] = lang('edit_attendance');
                $data["status"]='success';
                $data["disable"] = "TRUE";
            }

        else{
            $data["message"] = lang('edit_not_admin');
            $data["status"]='error';
            $data["disable"] = "TRUE";
        }  
        echo json_encode($data);
    }
}
