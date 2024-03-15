<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    function __construct() {
        parent::__construct();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
    }

    public function index() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        //-------------------------
        $where = "";
        $view = "";
        $data = array();
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            //Load teacher dashboard
            $view = "dashboard/teacher";
        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            //Load employee dashboard
            $view = "dashboard/employee";
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {

            //----- Count number of users only role base -----//
            $sql = "SELECT count(id) as count, role_id as role_id FROM sh_users WHERE school_id='$school_id' AND deleted_at=0 GROUP BY role_id ORDER BY role_id ";
            $data["users"] = $this->admin_model->dbQuery($sql);

            //----- Five unread messages -----//
            $data["inbox"] = $this->messages_model->getFirstFiveConversations(login_user()->user->user_id);

            //----- Student Attendance -----//
            $sql2 = "SELECT 
                atn.class_id, cls.name as classname,
                sum(if( atn.status = 'Present', 1, 0 ) ) AS present,  
                sum(if( atn.status = 'Absent', 1, 0 ) ) AS absent, 
                sum(if( atn.status = 'Leave', 1, 0 ) ) AS leav,
                sum(if( atn.status = 'Late', 1, 0 ) ) AS late
                FROM `sh_attendance` atn 
                INNER JOIN sh_classes cls ON cls.id=atn.class_id 
                WHERE 
                atn.date='" . date('Y-m-d') . "'
                AND atn.school_id='$school_id'
                GROUP BY atn.class_id 
                ORDER BY cls.id";

            //----- Employee Attendance -----//
            $sql5 = "SELECT 
                dpt.id as department_id,dpt.name as departmentname, 
                sum(if( atn.status = 'Present', 1, 0 ) ) AS present, 
                sum(if( atn.status = 'Absent', 1, 0 ) ) AS absent, 
                sum(if( atn.status = 'Leave', 1, 0 ) ) AS leav, 
                sum(if( atn.status = 'Late', 1, 0 ) ) AS late 
                FROM `sh_attendance` atn 
                INNER JOIN sh_users ur ON ur.id=atn.user_id 
                LEFT JOIN sh_departments dpt ON ur.department_id=dpt.id
                WHERE 
                atn.date='" . date('Y-m-d') . "' 
                AND atn.school_id='$school_id'
                AND dpt.deleted_at=0
                GROUP BY dpt.id ORDER BY dpt.id";

            //----- Fees -----//
            /* $sql3 = "SELECT 
              col.*,
              std.name as name,
              cls.name classname,
              ftype.name as feetypename,
              sum(if( col.status = '1', paid_amount, 0 ) ) AS paid,
              sum(if( col.status = '0', paid_amount, 0 ) ) AS un_paid,
              sum(if( col.status = '1', feetype_amount, 0 ) ) AS total_payable
              FROM `sh_fee_collection` col
              INNER JOIN sh_users std ON col.student_id=std.id
              INNER JOIN sh_classes cls ON std.class_id=cls.id
              INNER JOIN sh_fee_types ftype ON col.feetype_id=ftype.id
              WHERE ftype.due_date='".date('Y-m-d')."'
              AND col.school_id='$school_id' AND col.deleted_at IS NULL
              GROUP BY std.class_id";

              echo "<pre />";
              print_r($data["fees"] = $this->admin_model->dbQuery($sql3));
              die(); */

            //----- Timetable of the day -----//
            $sql4 = "SELECT "
                    . "cls.name,"
                    . "cls.id as class_id,"
                    . "bth.name,"
                    . "bth.id as batch_id,"
                    . "tbl.day_of_week, "
                    . "p.title,p.start_time, "
                    . "p.end_time, "
                    . "p.is_break,"
                    . "acy.name as academicYear, "
                    . "sub.name as subjectName "
                    . "FROM "
                    . "sh_classes cls "
                    . "LEFT JOIN sh_batches bth ON cls.id=bth.class_id "
                    . "LEFT JOIN sh_periods p ON bth.id=p.batch_id "
                    . "INNER JOIN sh_academic_years acy ON bth.academic_year_id=acy.id "
                    . "LEFT JOIN sh_timetable_new tbl On p.id=tbl.period_id "
                    . "INNER JOIN sh_subjects sub ON tbl.subject_id=sub.id "
                    . "WHERE cls.school_id='$school_id' "
                    . "AND cls.deleted_at IS NULL "
                    . "AND p.deleted_at IS NULL "
                    . "AND acy.is_active='Y' "
                    . "AND sub.deleted_at IS NULL "
                    . "AND tbl.deleted_at IS NULL "
                    . "AND tbl.day_of_week='" . strtolower(date("l")) . "' "
                    . "ORDER BY cls.id,bth.id,p.id";

            //$sql5 = "Select cls.id as class_id, cls.name as classname, bth.id as batch_id, bth.name as batchname from sh_classes cls LEFT JOIN sh_batches bth ON cls.id=bth.class_id WHERE cls.deleted_at IS NULL AND bth.deleted_at IS NULL AND cls.school_id='$school_id' ";
            $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id='$school_id' AND deleted_at IS NULL ");
            $data["batches"] = $this->admin_model->dbSelect("*", "batches", " school_id='$school_id' AND deleted_at IS NULL ");
            $data["emp_attendances"] = $this->admin_model->dbQuery($sql5);
            $data["timetable_days"] = $this->getDaysOfWeek();
            $data["timetables"] = $this->admin_model->dbQuery($sql4);
            $data["attendances"] = $this->admin_model->dbQuery($sql2);

            //-------------- Student Attendance Graph ------------//
            $std_attendance_graph = array();
            for($i=1; $i <= 12; $i++){
                $sql6 = "SELECT cls.name, ("
                    . "SELECT COUNT(sh_attendance.id) FROM sh_attendance LEFT JOIN sh_users u ON u.id=sh_attendance.user_id WHERE sh_attendance.date "
                    . "BETWEEN '2018-".$i."-01' AND '2018-".($i+1)."-01' AND sh_attendance.class_id = atn.class_id AND sh_attendance.deleted_at IS NULL AND u.role_id=".STUDENT_ROLE_ID
                    . " ) as count "
                    . "FROM "
                    . "sh_classes cls LEFT JOIN sh_attendance atn ON cls.id = atn.class_id "
                    . "WHERE "
                    . "cls.school_id=$school_id "
                    . "AND atn.deleted_at IS NULL "
                . " GROUP BY cls.name ORDER BY cls.id";
                $yy = $this->admin_model->dbQuery($sql6);
                $arr = array();
                //$dateObj   = DateTime::createFromFormat('!m', $i);
                //$monthName = $dateObj->format('M');
                $arr["period"] = date("Y")."-".$i;
                foreach($yy as $val){
                    $arr[$val->name] = $val->count;
                }
                array_push($std_attendance_graph, $arr);
            } 
            
            $gClasses = array();
            foreach($data["classes"] as $cls){
                array_push($gClasses, $cls->name);
            }
            $data["g"] = $std_attendance_graph;
            $data["gClasses"] = $gClasses;
            $data["gLineColors"] = getColors(count($data["classes"]));
            //---------- End student Attendance Graph -----------//
            
            $view = "dashboard/admin";
        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            //Load parent dashboard
            $view = "dashboard/parent";
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            //Load student dashboard
            $view = "dashboard/student";
        }
        //-------------------------
        //echo '<pre>';  print_r($this->session->all_userdata()); exit;
        $this->load->view($view, $data);
    }

    public function getDaysOfWeek() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $days_of_week = array("monday", "tuesday", "wednesday", "thrusday", "friday", "saturday", "sunday");
        $sorted_days_of_week = array();
        $start_day_of_week = strtolower($this->admin_model->dbSelect("*", "school", " id='$school_id' AND deleted_at=0 ")[0]->start_day_of_the_week);
        $index = array_search($start_day_of_week, $days_of_week);
        $loop1 = count($days_of_week) - $index;

        for ($i = 0; $i < $loop1; $i++) {
            array_push($sorted_days_of_week, $days_of_week[$index + $i]);
        }

        for ($j = 0; $j < $index; $j++) {
            array_push($sorted_days_of_week, $days_of_week[$j]);
        }
        return $sorted_days_of_week;
    }

}
