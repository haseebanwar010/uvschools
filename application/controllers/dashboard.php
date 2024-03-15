<?php

if (!defined('BASEPATH'))
  exit('No direct script access allowed');

class Dashboard extends CI_Controller {

  function __construct() {
    parent::__construct();
    if (!$this->session->userdata("userdata")) {
      redirect(site_url("login"));
    }
    check_user_permissions();
    
  }

  public function index() {
    $school_id = $this->session->userdata("userdata")["sh_id"];
    //echo 'db connecion';
    // $res = $this->db->select('*')->from('users')->get()->result();
    // print_r($res);die;
    //$res = $this->admin_model->getTestRecord();
    // $res = $this->student_model->getTestRecord();
    //print_r($res);die;
        //-------------------------
    $where = "";
    $view = "";
    $data = array();
    if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {

      $sql2 = "SELECT 
      b.class_id, cls.name as classname,b.name as batchname,b.id as batchid
      FROM `sh_classes` cls
      RIGHT JOIN sh_batches b ON cls.id=b.class_id 
      WHERE b.school_id='$school_id' and
      b.deleted_at is null and
      cls.academic_year_id = " . $this->session->userdata("userdata")["academic_year"] . "
      and find_in_set('" . login_user()->user->user_id . "',b.teacher_id)
      order by cls.name,b.name";
      $data["attendances"] = $this->admin_model->dbQuery($sql2);
      foreach ($data["attendances"] as $key => $temp) {
        $sql3 = "SELECT 
        sum(if( atn.status = 'Present', 1, 0 ) ) AS present,  
        sum(if( atn.status = 'Absent', 1, 0 ) ) AS absent, 
        sum(if( atn.status = 'Leave', 1, 0 ) ) AS leav,
        sum(if( atn.status = 'Late', 1, 0 ) ) AS late
        FROM `sh_attendance` atn 
        
        WHERE 
        atn.date='" . date('Y-m-d') . "'
        AND atn.school_id='$school_id'
        AND atn.batch_id = " . $temp->batchid . "
        AND atn.class_id = " . $temp->class_id . "
        GROUP BY atn.class_id ";
        $result = $this->admin_model->dbQuery($sql3);
        if (count($result) > 0) {
          $data["attendances"][$key]->present = $result[0]->present;
          $data["attendances"][$key]->absent = $result[0]->absent;
          $data["attendances"][$key]->leav = $result[0]->leav;
          $data["attendances"][$key]->late = $result[0]->late;
        } else {
          $data["attendances"][$key]->present = 0;
          $data["attendances"][$key]->absent = 0;
          $data["attendances"][$key]->leav = 0;
          $data["attendances"][$key]->late = 0;
        }
      }

      $sql4 = "SELECT distinct "
      . "cls.name,u.name as teacher_name,u1.name as assistant_name,"
      . "cls.id as class_id,"
      . "bth.name,"
      . "bth.id as batch_id,"
      . "tbl.day_of_week, "
      . "p.title,time_format(p.start_time,'%h:%i %p') as start_time, "
      . "time_format(p.end_time,'%h:%i %p') as end_time, "
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
      . "LEFT JOIN sh_assign_subjects asn ON sub.id=asn.subject_id "
      . "LEFT JOIN sh_users u ON u.id=asn.teacher_id "
      . "LEFT JOIN sh_users u1 ON u1.id=asn.assistant_id "
      . "WHERE cls.school_id='$school_id' "
      . "AND cls.deleted_at IS NULL "
      . "AND p.deleted_at IS NULL "
      . "AND acy.is_active='Y' "
      . "AND sub.deleted_at IS NULL "
      . "AND tbl.deleted_at IS NULL "
      . "AND tbl.day_of_week='" . strtolower(date("l")) . "' "
      . "ORDER BY cls.id,bth.id,p.start_time ASC ";

      $classes_sql = "SELECT distinct cls.id, cls.name from sh_classes cls join sh_batches bth on cls.id = bth.class_id where find_in_set(" . login_user()->user->user_id . ",bth.teacher_id )  and bth.deleted_at is null and cls.academic_year_id = " . $this->session->userdata("userdata")["academic_year"] . " order by cls.name";

      $data["classes"] = $this->admin_model->dbQuery($classes_sql);
      $data["batches"] = $this->admin_model->dbSelect("*", "batches", " school_id='$school_id' AND deleted_at IS NULL AND find_in_set(" . login_user()->user->user_id . ",teacher_id) ");
      $data["timetables"] = $this->admin_model->dbQuery($sql4);

            //-------------- Student Attendance Graph ------------//
      $std_attendance_graph = array();
//            for ($i = 1; $i <= 12; $i++) {
//                $sql6 = "SELECT cls.name, ("
//                        . "SELECT COUNT(sh_attendance.id) FROM sh_attendance LEFT JOIN sh_users u ON u.id=sh_attendance.user_id WHERE sh_attendance.date "
//                        . "BETWEEN '2018-" . $i . "-01' AND '2018-" . ($i + 1) . "-01' AND sh_attendance.class_id = atn.class_id AND sh_attendance.deleted_at IS NULL AND u.role_id=" . STUDENT_ROLE_ID
//                        . " ) as count "
//                        . "FROM "
//                        . "sh_classes cls LEFT JOIN sh_attendance atn ON cls.id = atn.class_id "
//                        . "WHERE "
//                        . "cls.school_id=$school_id "
//                        . "AND atn.deleted_at IS NULL "
//                        . " GROUP BY cls.name ORDER BY cls.id";
//                $yy = $this->admin_model->dbQuery($sql6);
//                $arr = array();
//                //$dateObj   = DateTime::createFromFormat('!m', $i);
//                //$monthName = $dateObj->format('M');
//                $arr["period"] = date("Y") . "-" . $i;
//                foreach ($yy as $val) {
//                    $arr[$val->name] = $val->count;
//                }
//                array_push($std_attendance_graph, $arr);
//            }

      $gClasses = array();
//            foreach ($data["classes"] as $cls) {
//                array_push($gClasses, $cls->name);
//            }
      $data["g"] = $std_attendance_graph;
      $data["gClasses"] = $gClasses;
      $data["gLineColors"] = getColors(count($data["classes"]));
            //---------- End student Attendance Graph -----------//
            //Load teacher dashboard
      $view = "dashboard/teacher";
      
      
    //google drive
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $sh_enable_gd = $this->session->userdata("userdata")["sh_enable_gd"];
    $data['enable_gd']=$sh_enable_gd;
    $data['credentials_fileid']='credentials_'.$school_id.'.json';
      
    } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            //Load employee dashboard
      if(get_acountant_dept_id() == login_user()->user->department_id){
        $view = "dashboard/accounts";
        
      } else {
        $view = "dashboard/employee";
        
      }
      
    } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {

            //----- Count number of users only role base -----//
      $sql = "SELECT count(u.id) as count, role_id as role_id FROM sh_users u WHERE u.school_id='$school_id' AND u.deleted_at=0 AND role_id <> " . STUDENT_ROLE_ID . " GROUP BY role_id ORDER BY role_id ";
      $users = $this->admin_model->dbQuery($sql);
      $data['parent_count'] = $data['student_count'] = $data['employee_count'] = 0;
      foreach ($users as $user) {
        if ($user->role_id == PARENT_ROLE_ID) {
          $data['parent_count'] = $user->count;
        } else if ($user->role_id == EMPLOYEE_ROLE_ID) {
          $data['employee_count'] = $user->count;
        }
      }
      //$student_count = $this->db->select('count(id) as total_students')->from('sh_students_' . $school_id)->get()->row();
        $where = " rl.deleted_at is NULL AND u.school_id=" . $school_id . " AND u.role_id=" . STUDENT_ROLE_ID . " ";

        $stud_new = $this->students_model->getAllStudents($where);
      
        $data['student_count'] = count($stud_new);
      //$data['student_count'] = $student_count->total_students;

            //----- Five unread messages -----//
      $data["inbox"] = $this->messages_model->getFirstFiveConversations(login_user()->user->user_id);
      $data['countUnread'] = $this->messages_model->countUnread(login_user()->user->user_id);

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
            /* $sql4 = "SELECT "
              . "cls.name,u.name as teacher_name,"
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
              . "LEFT JOIN sh_assign_subjects asn ON sub.id=asn.subject_id "
              . "LEFT JOIN sh_users u ON u.id=asn.teacher_id "
              . "WHERE cls.school_id='$school_id' "
              . "AND cls.deleted_at IS NULL "
              . "AND p.deleted_at IS NULL "
              . "AND acy.is_active='Y' "
              . "AND sub.deleted_at IS NULL "
              . "AND tbl.deleted_at IS NULL "
              . "AND tbl.day_of_week='" . strtolower(date("l")) . "' "
              . "ORDER BY cls.id,bth.id,p.id";
              $sql5 = "Select cls.id as class_id, cls.name as classname, bth.id as batch_id, bth.name as batchname from sh_classes cls LEFT JOIN sh_batches bth ON cls.id=bth.class_id WHERE cls.deleted_at IS NULL AND bth.deleted_at IS NULL AND cls.school_id='$school_id' ";
              $sql7 = "SELECT "
              ."log.*, u.name as user_name, cls.name as class_name, btch.name as batch_name "
              ."FROM"
              ." sh_request_log log "
              ." JOIN  sh_classes cls ON cls.id = log.class_id "
              ." JOIN  sh_batches btch ON btch.id = log.batch_id "
              ."JOIN  sh_users u ON u.id = log.user_id "
              . " WHERE log.school_id='$school_id' "
              . " AND log.deleted_at IS NULL "
              . " ORDER BY log.created_at"; */

              $data["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id='$school_id' AND academic_year_id=" . $this->session->userdata("userdata")["academic_year"] . " AND deleted_at IS NULL ");
              $data["batches"] = $this->admin_model->dbSelect("*", "batches", " school_id='$school_id' AND academic_year_id=" . $this->session->userdata("userdata")["academic_year"] . " AND deleted_at IS NULL ");
              $data["emp_attendances"] = $this->admin_model->dbQuery($sql5);
              $data["timetable_days"] = $this->getDaysOfWeek();
              $data["timetables"] = $this->getTimetableOfDayForAllBatches();
              $data["attendances"] = $this->admin_model->dbQuery($sql2);

            //-------------- Student Attendance Graph ------------//
              $std_attendance_graph = array();
//            for ($i = 1; $i <= 12; $i++) {
//                $sql6 = "SELECT cls.name, ("
//                        . "SELECT COUNT(sh_attendance.id) FROM sh_attendance LEFT JOIN sh_users u ON u.id=sh_attendance.user_id WHERE sh_attendance.date "
//                        . "BETWEEN '2018-" . $i . "-01' AND '2018-" . ($i + 1) . "-01' AND sh_attendance.class_id = atn.class_id AND sh_attendance.deleted_at IS NULL AND u.role_id=" . STUDENT_ROLE_ID
//                        . " ) as count "
//                        . "FROM "
//                        . "sh_classes cls LEFT JOIN sh_attendance atn ON cls.id = atn.class_id "
//                        . "WHERE "
//                        . "cls.school_id=$school_id "
//                        . "AND atn.deleted_at IS NULL "
//                        . " GROUP BY cls.name ORDER BY cls.id";
//                $yy = $this->admin_model->dbQuery($sql6);
//                $arr = array();
//                //$dateObj   = DateTime::createFromFormat('!m', $i);
//                //$monthName = $dateObj->format('M');
//                $arr["period"] = date("Y") . "-" . $i;
//                foreach ($yy as $val) {
//                    $arr[$val->name] = $val->count;
//                }
//                array_push($std_attendance_graph, $arr);
//            }

              $gClasses = array();
//            foreach ($data["classes"] as $cls) {
//                array_push($gClasses, $cls->name);
//            }
              $data["g"] = $std_attendance_graph;
              $data["gClasses"] = $gClasses;
              $data["gLineColors"] = getColors(count($data["classes"]));
            //---------- End student Attendance Graph -----------//
            //---------- Start Approval Requests -----------//
              $sql = "SELECT r.*,cls.name as classname, bth.name as batchname, u.name as username FROM sh_request_log r "
              . "LEFT JOIN sh_users u ON r.user_id=u.id "
              . "LEFT JOIN sh_classes cls ON r.class_id=cls.id "
              . "LEFT JOIN sh_batches bth ON r.batch_id=bth.id "
              . "WHERE r.deleted_at IS NULL AND (r.status = 'edit-request' OR r.status = 'inprocess') "
              . "AND r.school_id='$school_id' ";
              $data["requests"] = $this->admin_model->dbQuery($sql);
            //$data["requests"] = $this->admin_model->dbSelect("*","request_log"," deleted_at IS NULL AND school_id='$school_id' ");
            //---------- End Approval Requests -----------//

              $data["dueFees"] = $this->getDueFees();

              $view = "dashboard/admin";
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            //Load parent dashboard
              $view = "dashboard/parent_new";
            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
              $student_id = $this->session->userdata("userdata")["user_id"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $student = $this->db->query("SELECT s.class_id, s.batch_id, s.subject_group_id, sh_batches.name as batch_name FROM sh_students_$school_id s left JOIN sh_batches ON sh_batches.id = s.batch_id WHERE  s.id='$student_id' ")->result();
        if(count($student) > 0){
            if($student[0]->subject_group_id != "" && $student[0]->subject_group_id != null && $student[0]->subject_group_id != 0){
                $subject_ids = $this->db->select('subjects')->from('sh_subject_groups')->where('id', $student[0]->subject_group_id)->get()->row()->subjects;
                $subject_ids = explode(",", $subject_ids);
                foreach ($subject_ids as $key => $value) {
                    $subject_ids[$key] = $this->db->select('code')->from('sh_subjects')->where('id', $value)->get()->row()->code;
                }
                $materials = $this->study_model->studentmaterialfordashboard($school_id, $student[0]->class_id, $student[0]->batch_id, '', '', $subject_ids,'');
            }else{
                $materials = $this->study_model->studentmaterialfordashboard($school_id, $student[0]->class_id, $student[0]->batch_id, '', '','','');
            }
            //Azeem remove future assignments, not show before date
            foreach ($materials as $key => $value) {
                if($value["uploaded_at"] > date("Y-m-d") && $value["content_type"] == "Assignment"){
                    unset($materials[$key]);
                }
                $materials[$key]['batch_name'] = $student[0]->batch_name;
            }
            
            $materials = array_values($materials);
            $data['materials'] = $materials;
            
            $online_class = $this->db->select('started_by')->from('online_classes')->where('class_id', $student[0]->class_id)->where('batch_id', $student[0]->batch_id)->where('status','ongoing')->get()->result();
            
            if($online_class){
                 $data['online_class'] = true;
            }else{
                 $data['online_class'] = false;
            }
            
        } else{
            $data["materials"] = array();
        }
        for ($i = 0; $i < count($data['materials']); $i++) {
            $data['materials'][$i]['files'] = explode(",", $data['materials'][$i]['files']);
        }
      
            //Load student dashboard
              $view = "dashboard/student";
            } 
        //-------------------------
        // $this->output->enable_profiler(TRUE);

            $this->load->view($view, $data);
          }
          
        public function googledrivestatus()
        {
            //google drive
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $sh_enable_gd = $this->session->userdata("userdata")["sh_enable_gd"];
            $credentials_fileid = 'credentials_'.$school_id.'.json';
            $response=false;
            if(file_exists($credentials_fileid) && $sh_enable_gd==1)
            {
                $response=false; 
            }
            else
            {
                $response=false;
            }
            echo json_encode($response);
        }

          public function getTimetableOfDayForAllBatches() {
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
            LEFT JOIN sh_timetable_new tbl ON tbl.period_id=p.id and tbl.day_of_week = '".$current_day_of_week."'
            INNER JOIN sh_classes cls ON p.class_id=cls.id 
            INNER JOIN sh_batches bth ON p.batch_id=bth.id
            LEFT JOIN sh_subjects sub ON tbl.subject_id=sub.id 
            WHERE 
            p.school_id=" . $school_id . " "
            . "AND bth.academic_year_id=(SELECT id FROM sh_academic_years WHERE school_id=" . $school_id . " AND deleted_at IS NULL AND is_active='Y') "
            . " AND p.deleted_at is null AND tbl.deleted_at is null  order by p.start_time ";
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

            $classesWithBatches = $this->admin_model->dbSelect("*", "classes", " school_id='$school_id' AND deleted_at IS NULL AND academic_year_id = " . $this->session->userdata("userdata")["academic_year"]);
            $getAcademicYear = $this->admin_model->dbSelect("id", "academic_years", " school_id='$school_id' AND is_active='Y' ");
            $active_academic_year = 0;
            if (count($getAcademicYear) > 0) {
              $active_academic_year = $getAcademicYear[0]->id;
            }

            foreach ($classesWithBatches as $key => $cls) {
              $batches = $this->admin_model->dbSelect("*", "batches", " class_id='$cls->id' AND school_id='$school_id' AND academic_year_id='$active_academic_year' AND deleted_at IS NULL ");
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

          public function getDueFees() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        // $result = $this->db->query("select (total_fee - total_paid) as total_due from (select count(u.id) as total_fee, (select count(*) from sh_fee_collection fc inner join sh_fee_types ft on fc.feetype_id = ft.id WHERE fc.school_id = " . $school_id . " and fc.deleted_at is null and ft.deleted_at is null and ft.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." and ft.due_date < curdate()) as total_paid from sh_students_".$school_id." u left join sh_fee_types ft on u.class_id = ft.class_id where u.deleted_at = 0 and u.role_id = " . STUDENT_ROLE_ID . " and u.school_id = " . $school_id . " and ft.deleted_at is null and ft.due_date < curdate()) as temp")->row();
            $this->db->select('count(*) as total_due_fees');
            $this->db->from('sh_fee_types ft');
            $this->db->JOIN('sh_classes cls', 'ft.class_id = cls.id', 'INNER');
            $this->db->JOIN('sh_students_' . $school_id . ' u', 'u.class_id = cls.id', 'INNER');
            $this->db->where('cls.academic_year_id', $academic_year_id);
            $this->db->where('cls.deleted_at is null');
            $this->db->where('ft.deleted_at is null');
            $this->db->where('ft.due_date < curdate()');
            $this->db->where('u.deleted_at =', '0');
            $this->db->where('u.role_id', '3');
            $this->db->where('ft.school_id', $school_id);
            $this->db->where('cls.school_id', $school_id);
            $this->db->where('u.school_id', $school_id);
            $total_due_fees = $this->db->get()->row()->total_due_fees;
        // get paid due fees
            $this->db->select('count(*) as paid_due_fees');
            $this->db->from('sh_fee_collection f');
            $this->db->join('sh_students_' . $school_id . ' u', 'u.id = f.student_id', 'inner');
            $this->db->join('sh_fee_types ft', 'ft.id = f.feetype_id', 'inner');
            $this->db->where('f.deleted_at is null');
            $this->db->where('ft.deleted_at is null');
            $this->db->where('ft.due_date < curdate()');
            $this->db->where('ft.academic_year_id', $academic_year_id);
            $this->db->where('f.status', '1');
            $paid_due_fees = $this->db->get()->row()->paid_due_fees;
        // get due fees
            $due_fees = $total_due_fees - $paid_due_fees;

            $response = array("unpaid_count" => $due_fees);
            return $response;
          }

          public function getDueFeesOld() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $students = $this->admin_model->dbSelect("*", "users", " school_id='$school_id' AND role_id=" . STUDENT_ROLE_ID . " AND deleted_at=0 ");
            $totalFeetypes2 = $this->admin_model->dbSelect("id,name,description,class_id,amount,due_date", "fee_types", " school_id='$school_id' AND deleted_at IS NULL AND due_date < '" . date('Y-m-d') . "' ");
            $indivialStdPaidFees2 = $this->admin_model->dbSelect("*", "fee_collection", " school_id='$school_id' AND deleted_at IS NULL ");

            $data = array();
            $totalUnpaidCount = 0;
            foreach ($students as $std) {
              $totalFeetypes = array_filter($totalFeetypes2, function($arrayValue) use($std) {
                return $arrayValue->class_id == $std->class_id;
              });
              if (count($totalFeetypes) > 0) {
                $feetypeIds = array();
                foreach ($totalFeetypes as $ft) {
                  array_push($feetypeIds, $ft->id);
                }
                $std->feetypeIds = $feetypeIds;
                $notPaidedFeesInfo = array();
                $paidedFeeIds = array();
                $indivialStdPaidFees = array_filter($indivialStdPaidFees2, function($arrayValue) use($std) {
                  return ($arrayValue->student_id == $std->id) && (in_array($arrayValue->feetype_id, $std->feetypeIds, true));
                });
                foreach ($indivialStdPaidFees as $ss) {
                  array_push($paidedFeeIds, $ss->feetype_id);
                }
                $diff = array_diff($feetypeIds, $paidedFeeIds);
                foreach ($totalFeetypes as $tt) {
                  foreach ($diff as $d) {
                    if ($d == $tt->id) {
                      array_push($notPaidedFeesInfo, $tt);
                    }
                  }
                }
                $totalUnpaidCount += count($notPaidedFeesInfo);
                array_push($data, array("std_id" => $std->id, "name" => $std->name, "class_id" => $std->class_id, "batch_id" => $std->batch_id, "dueFees" => $notPaidedFeesInfo));
              } else {
                $totalFeetypes = array();
              }
            }
            $response = array("unpaid_count" => $totalUnpaidCount, "details" => $data);
            return $response;
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

          public function change_db_collation() {
            $this->load->database();
            $this->db->query("ALTER DATABASE `" . $this->db->database . "` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci");
            $arr = $this->db->query('show tables')->result_array();
            foreach ($arr as $a) {
              $this->db->query('ALTER TABLE ' . $a['Tables_in_' . $this->db->database] . ' CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci');
            }
            echo "Collation changed.";
          }

          // getting events from sh_events for calendar

          public function getTodayEventsDshboard()
          {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $current_day_of_week = strtolower(date("l"));
            $role_id = $this->session->userdata("userdata")["role_id"];
            $academic_year =  $this->session->userdata("userdata")["academic_year"];
          
            if($role_id == 1 || $role_id == 4){
              $sql = 'SELECT * from sh_events where start=DATE_FORMAT(CURRENT_DATE(), "%Y-%m-%d") AND school_id='.$school_id.' AND deleted_at IS NULL ';

              $records = $this->admin_model->dbQuery($sql);
              $data["current_day_of_week"] = $current_day_of_week;
              $data["month"] = date("M");
              $data["day"] = date("d");
              $data["records"] = $records;
              echo json_encode($data);
            }
          
            else if($role_id == 2){
             
              $sql = 'SELECT * from sh_events where start=DATE_FORMAT(CURRENT_DATE(), "%Y-%m-%d") AND mode="public" AND school_id='.$school_id.' AND deleted_at IS NULL ';
             
              $records = $this->admin_model->dbQuery($sql);
              $data["current_day_of_week"] = $current_day_of_week;
              $data["month"] = date("M");
              $data["day"] = date("d");
              $data["records"] = $records;
              echo json_encode($data);
            }
          }

          public function processRequest($status, $id, $type, $date = false) {
            $this->common_model->update_where("request_log", array("id" => $id), array("status" => $status));
            if ($type == 'syllabus') {
              if ($status == 'approved') {
                $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit"));
                //$this->common_model->update_where("syllabus_week_details", array("id"=>$request_id), array("edit"=>"Yes"));
              } else if ($status == 'not-approved') {
                $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit"));
                //$this->common_model->update_where("syllabus_week_details", array("id"=>$request_id), array("edit"=>"No"));
              }
            } else if ($type == 'attendance') {
              $sql = "SELECT * FROM sh_request_log WHERE id= " . $id . "";
              $result = $this->admin_model->dbQuery($sql);
              if (count($result) > 0) {
                if ($status == 'approved') {
                  $this->common_model->update_where("request_log", array("id" => $id), array("status" => "approved", "edit_status" => "can_edit"));
                } else if ($status == 'not-approved') {
                  $this->common_model->update_where("request_log", array("id" => $id), array("status" => "not-approved", "edit_status" => "not_edit"));
                }
              }
            }

            redirect($_SERVER['HTTP_REFERER'], "refresh");
          }

          public function fetchReqeustDetails() {
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);

            $response = array();
            if ($request->type == 'attendance') {
              $response = $this->admin_model->dbSelect("*", "attendance", " class_id='$request->class_id' AND batch_id='$request->batch_id' AND date='$request->date' ");
            } else if ($request->type == 'syllabus') {
              $response = $this->admin_model->dbSelect("*", "syllabus_week_details", " id='$request->request_id' ");
            }
            echo json_encode($response);
          }

          public function get_study_plan_statictics() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
            $studyplanstatictics = array();
            $data_array = array();

            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {

              $t_classes = implode(",",login_user()->t_data->classes);
              $t_batches = implode(",",login_user()->t_data->batches);
              $t_subjects = implode(",",login_user()->t_data->subjects);
              
              $sql = "Select c.id as class_id, b.id as batch_id, c.name as class_name, b.name as batch_name, c.level_id, b.teacher_id from sh_classes c LEFT JOIN sh_batches b ON c.id=b.class_id WHERE c.id IN ($t_classes) AND b.id IN ($t_batches)";
              $subjects = $this->admin_model->dbSelect("*", "subjects", " id in ($t_subjects) ");
              $syllabus_week_with_details = $this->admin_model->dbQuery("SELECT * FROM sh_syllabus_weeks w LEFT JOIN sh_syllabus_week_details d ON w.id=d.syllabus_week_id WHERE w.school_id='$school_id' AND w.academic_year_id='$academic_year_id' AND w.deleted_at IS NULL AND d.deleted_at IS NULL");
              $classes_batches = $this->admin_model->dbQuery($sql);
              
              foreach ($classes_batches as $cb) {
                $subs = $this->find_subjects($subjects, $cb->class_id, $cb->batch_id);
                foreach ($subs as $s) {
                  $syllabus = $this->find_syllabus($syllabus_week_with_details, $s->id, $s->class_id, $s->batch_id);
                  $s->syllabus = $syllabus;
                }
                $cb->subjects = $subs;
              }
              
              $sql_overall = "select 
              d.status as label,
              count(*) as value
              from sh_classes c 

              left join sh_batches b on c.id=b.class_id 
              left join sh_subjects s on b.id=s.batch_id 
              left join sh_syllabus_weeks w ON w.class_id=c.id AND w.batch_id=b.id AND w.subject_id=s.id 
              left join sh_syllabus_week_details d ON w.id=d.syllabus_week_id 

              WHERE 

              c.school_id=$school_id
              AND c.id IN ($t_classes)
              AND b.id IN ($t_batches)
              AND s.id IN ($t_subjects)
              AND c.deleted_at is null 
              AND b.deleted_at is null 
              AND s.deleted_at is null 
              AND w.deleted_at is null 
              AND d.deleted_at is null 
              AND c.academic_year_id=$academic_year_id
              AND b.academic_year_id=$academic_year_id
              AND s.academic_year_id=$academic_year_id
              AND w.academic_year_id=$academic_year_id
              GROUP BY d.status";
              
              $data_array["all"] = $classes_batches;
              
              $data_array["overall"] = array(
                array("label"=>"Done","value"=>0),
                array("label"=>"Pending","value"=>0),
                array("label"=>"Skip","value"=>0),
                array("label"=>"Partially Done","value"=>0),
                array("label"=>"Reschedule","value"=>0)
              );
              if(count($this->admin_model->dbQuery($sql_overall))>0){
                $data_array["overall"] = $this->admin_model->dbQuery($sql_overall);
                if(count($data_array["overall"]) > 1){
                  array_shift($data_array["overall"]);
                }
                if(is_null($data_array["overall"][0]->label)){
                  $data_array["overall"][0]->label = "Label";
                  $data_array["overall"][0]->value = 0;
                }
              }
              
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
              $sql = "Select c.id as class_id, b.id as batch_id, c.name as class_name, b.name as batch_name, c.level_id, b.teacher_id from sh_classes c LEFT JOIN sh_batches b ON c.id=b.class_id WHERE c.school_id='$school_id' AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND c.academic_year_id='$academic_year_id'";
              $subjects = $this->admin_model->dbSelect("*", "subjects", " school_id='$school_id' AND academic_year_id='$academic_year_id' AND deleted_at IS NULL ");
              $syllabus_week_with_details = $this->admin_model->dbQuery("SELECT * FROM sh_syllabus_weeks w LEFT JOIN sh_syllabus_week_details d ON w.id=d.syllabus_week_id WHERE w.school_id='$school_id' AND w.academic_year_id='$academic_year_id' AND w.deleted_at IS NULL AND d.deleted_at IS NULL");
              $classes_batches = $this->admin_model->dbQuery($sql);
              foreach ($classes_batches as $cb) {
                $subs = $this->find_subjects($subjects, $cb->class_id, $cb->batch_id);
                foreach ($subs as $s) {
                  $syllabus = $this->find_syllabus($syllabus_week_with_details, $s->id, $s->class_id, $s->batch_id);
                  $s->syllabus = $syllabus;
                }
                $cb->subjects = $subs;
              }
              $data_array["all"] = $classes_batches;
              
              $sql_overall = "select 
              d.status as label,
              count(*) as value
              from sh_classes c 

              left join sh_batches b on c.id=b.class_id 
              left join sh_subjects s on b.id=s.batch_id 
              left join sh_syllabus_weeks w ON w.class_id=c.id AND w.batch_id=b.id AND w.subject_id=s.id 
              left join sh_syllabus_week_details d ON w.id=d.syllabus_week_id 

              WHERE 

              c.school_id=$school_id 
              AND c.deleted_at is null 
              AND b.deleted_at is null 
              AND s.deleted_at is null 
              AND w.deleted_at is null 
              AND d.deleted_at is null 
              AND c.academic_year_id=$academic_year_id
              AND b.academic_year_id=$academic_year_id
              AND s.academic_year_id=$academic_year_id
              AND w.academic_year_id=$academic_year_id
              GROUP BY d.status";
              
              $data_array["overall"] = array(
                array("label"=>"Done","value"=>0),
                array("label"=>"Pending","value"=>0),
                array("label"=>"Skip","value"=>0),
                array("label"=>"Partially Done","value"=>0),
                array("label"=>"Reschedule","value"=>0)
              );
              if(count($this->admin_model->dbQuery($sql_overall))>0){
                $data_array["overall"] = $this->admin_model->dbQuery($sql_overall);
                if(count($data_array["overall"]) > 1){
                  array_shift($data_array["overall"]);
                }
                if(is_null($data_array["overall"][0]->label)){
                  $data_array["overall"][0]->label = "Label";
                  $data_array["overall"][0]->value = 0;
                }

                foreach ($data_array["overall"] as $key => $value) {
                  $data_array["overall"][$key]->label = lang($this->createSlug($data_array["overall"][$key]->label));
                }

              }
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            }else if (login_user()->user->role_id == STUDENT_ROLE_ID) { }
            
            echo json_encode($data_array);
          }

          public static function createSlug($str, $delimiter = '_'){

            $slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', iconv('UTF-8', 'ASCII//TRANSLIT', $str))))), $delimiter));
            return $slug;

          } 
          public function get_fee_summary() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
            $classes = array();
            $overall = array();

            $final_array = array();

            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {

            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
              if(get_acountant_dept_id() == login_user()->user->department_id){
                // get data for accountant
                $classes = $this->db->select('id,name')->from('sh_classes')->where('school_id', $school_id)->where('academic_year_id', $academic_year_id)->where('deleted_at is null')->get()->result();
                $total_paid = 0;
                $total_partial = 0;
                $total_due = 0;
                foreach ($classes as $cls) {
                  $cls->batches = $this->db->select('id,name')->from('sh_batches')->where('class_id', $cls->id)->where('deleted_at is null')->get()->result();
                  foreach ($cls->batches as $b) {
                    $b->class_id = $cls->id;
                    $b->class_name = $cls->name;
                    $b->full_name = $cls->name . "-" . $b->name;
                    
                  // get student ids
                    $this->db->select('id');
                    $this->db->from('sh_students_'.$school_id);
                    $this->db->where('class_id',$cls->id);
                    $this->db->where('batch_id',$b->id);
                    $students = $this->db->get()->result();
                  // get total students count
                    $this->db->select('count(*) as total_students');
                    $this->db->from('sh_students_'.$school_id);
                    $this->db->where('class_id',$cls->id);
                    $this->db->where('batch_id',$b->id);
                    $b->total_students = $this->db->get()->row()->total_students;
                    
                  // get paid, partial and due students
                    $b->total_paid = 0;
                    $b->total_due = 0;
                    $b->total_partial = 0;
                    foreach($students as $std){
                      $temp = $this->db->select('count(*) as due')->from('sh_fee_types ft')->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.student_id = '.$std->id.' and fc.deleted_at is null','left')->where('ft.class_id',$cls->id)->where('ft.deleted_at is null')->where('ft.due_date < curdate()')->where('fc.id is null')->get()->row()->due;
                      if($temp > 0){
                        $b->total_due++;
                        $total_due++;
                      }else{
                        $temp = $this->db->select('count(*) as partial')->from('sh_fee_types ft')->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.student_id = '.$std->id.' and fc.deleted_at is null and fc.status= "1"','left')->where('ft.class_id',$cls->id)->where('ft.deleted_at is null')->where('ft.due_date < curdate()')->where('fc.id is null')->get()->row()->partial;
                        if($temp > 0){
                          $b->total_partial++;
                          $total_partial++;
                        }else{
                          $b->total_paid++;
                          $total_paid++;
                        }
                      }

                    }
                    

                    
                    

                    
                  }
                  
                }
                $overall = new stdClass();
                $overall->total_paid = $total_paid;
                $overall->total_due = $total_due;
                $overall->total_partial = $total_partial;

                $overall->full_name = lang('lbl_overall');
              } else {
                // get data for simple employee
              }
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
              $classes = $this->db->select('id,name')->from('sh_classes')->where('school_id', $school_id)->where('academic_year_id', $academic_year_id)->where('deleted_at is null')->get()->result();
              $total_paid = 0;
              $total_partial = 0;
              $total_due = 0;
              foreach ($classes as $cls) {
                $cls->batches = $this->db->select('id,name')->from('sh_batches')->where('class_id', $cls->id)->where('deleted_at is null')->get()->result();
                foreach ($cls->batches as $b) {
                  $b->class_id = $cls->id;
                  $b->class_name = $cls->name;
                  $b->full_name = $cls->name . "-" . $b->name;
                  
                  // get student ids
                  $this->db->select('id');
                  $this->db->from('sh_students_'.$school_id);
                  $this->db->where('class_id',$cls->id);
                  $this->db->where('batch_id',$b->id);
                  $students = $this->db->get()->result();
                  // get total students count
                  $this->db->select('count(*) as total_students');
                  $this->db->from('sh_students_'.$school_id);
                  $this->db->where('class_id',$cls->id);
                  $this->db->where('batch_id',$b->id);
                  $b->total_students = $this->db->get()->row()->total_students;
                  
                  // get paid, partial and due students
                  $b->total_paid = 0;
                  $b->total_due = 0;
                  $b->total_partial = 0;
                  foreach($students as $std){
                    $temp = $this->db->select('count(*) as due')->from('sh_fee_types ft')->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.student_id = '.$std->id.' and fc.deleted_at is null','left')->where('ft.class_id',$cls->id)->where('ft.deleted_at is null')->where('ft.due_date < curdate()')->where('fc.id is null')->get()->row()->due;
                    if($temp > 0){
                      $b->total_due++;
                      $total_due++;
                    }else{
                      $temp = $this->db->select('count(*) as partial')->from('sh_fee_types ft')->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.student_id = '.$std->id.' and fc.deleted_at is null and fc.status= "1"','left')->where('ft.class_id',$cls->id)->where('ft.deleted_at is null')->where('ft.due_date < curdate()')->where('fc.id is null')->get()->row()->partial;
                      if($temp > 0){
                        $b->total_partial++;
                        $total_partial++;
                      }else{
                        $b->total_paid++;
                        $total_paid++;
                      }
                    }

                  }
                  

                  
                  

                  
                }
                
              }
              $overall = new stdClass();
              $overall->total_paid = $total_paid;
              $overall->total_due = $total_due;
              $overall->total_partial = $total_partial;

              $overall->full_name = lang('lbl_overall');
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

            }


            $data["classes"] = $classes;
            $data["overall"] = $overall;
            echo json_encode($data);
          }

          public function find_subjects($subjects, $class_id, $batch_id) {
            $subs = array();
            foreach ($subjects as $sub) {
              if ($sub->class_id == $class_id && $sub->batch_id == $batch_id) {
                array_push($subs, $sub);
              }
            }
            return $subs;
          }

          public function find_syllabus($data, $subject_id, $class_id, $batch_id) {
            $syllabus = array("data" => array(), "counts" => array());
            $done_count = 0;
            $pending_count = 0;
            $skip_count = 0;
            $partially_done_count = 0;
            $reschedule_count = 0;
            $totally_done_count = 0;

            foreach ($data as $d) {
              if ($d->subject_id == $subject_id && $d->class_id == $class_id && $d->batch_id == $batch_id) {
                switch ($d->status) {
                  case "Done":
                  $done_count++;
                  break;
                  case "Pending":
                  $pending_count++;
                  break;
                  case "Skip":
                  $skip_count++;
                  break;
                  case "Partially Done":
                  $partially_done_count++;
                  break;
                  case "Reschedule":
                  $reschedule_count++;
                  break;
                  default:
                }
                array_push($syllabus["data"], $d);
              }
            }
            $done = new stdClass();
            $done->name = lang('lbl_done');
            $done->count = $done_count;

            $pending = new stdClass();
            $pending->name = lang('lbl_pending');
            $pending->count = $pending_count;

            $skip = new stdClass();
            $skip->name = lang('lbl_skip');
            $skip->count = $skip_count;

            $partially_done = new stdClass();
            $partially_done->name = lang('partially_done');
            $partially_done->count = $partially_done_count;

            $reschedule = new stdClass();
            $reschedule->name = lang('reschedule');
            $reschedule->count = $reschedule_count;

            $count = array(
              "done" => $done,
              "pending" => $pending,
              "skip" => $skip,
              "partially_done" => $partially_done,
              "reschedule" => $reschedule
            );
            $syllabus["counts"] = $count;
            return $syllabus;
          }

          public function get_today_emp_attendance() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            
            $data = array();
            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {

            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {

            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
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

              $data["all"] = $this->admin_model->dbQuery($sql5);
              $sql6 = "SELECT 
              coalesce(sum(if( atn.status = 'Present', 1, 0 ) ), 0) AS present, 
              coalesce(sum(if( atn.status = 'Absent', 1, 0 ) ), 0) AS absent, 
              coalesce(sum(if( atn.status = 'Leave', 1, 0 ) ), 0) AS leav, 
              coalesce(sum(if( atn.status = 'Late', 1, 0 ) ), 0) AS late 
              FROM `sh_attendance` atn 
              INNER JOIN sh_users ur ON ur.id=atn.user_id 
              LEFT JOIN sh_departments dpt ON ur.department_id=dpt.id
              WHERE 
              atn.date='" . date('Y-m-d') . "' 
              AND atn.school_id='$school_id' AND dpt.deleted_at=0";
              
              $data["overall"] = $this->admin_model->dbQuery($sql6)[0];

              $e_count = $this->db->select('count(id) as e_count')->from('sh_users')->where('school_id',$school_id)->where('role_id',4)->where('deleted_at',0)->get()->row()->e_count;
              $data["overall"]->unknown = $e_count - ($data["overall"]->present + $data["overall"]->absent + $data["overall"]->leav + $data["overall"]->late);
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

            }
            
            echo json_encode($data);
          }
          
          public function get_today_std_attendance() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            
            $data = array();
            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
              $t_classes = implode(",",login_user()->t_data->classes);
              $t_batches = implode(",",login_user()->t_data->batches);
              
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
              AND class_id IN ($t_classes) AND batch_id IN ($t_batches)
              GROUP BY atn.class_id 
              ORDER BY cls.id";
              $data["all"] = $this->admin_model->dbQuery($sql2);
              
              $sql_2 = "SELECT 
              coalesce(sum(if( atn.status = 'Present', 1, 0 ) ), 0) AS present,  
              coalesce(sum(if( atn.status = 'Absent', 1, 0 ) ), 0) AS absent, 
              coalesce(sum(if( atn.status = 'Leave', 1, 0 ) ), 0) AS leav,
              coalesce(sum(if( atn.status = 'Late', 1, 0 ) ), 0) AS late
              FROM `sh_attendance` atn 
              INNER JOIN sh_classes cls ON cls.id=atn.class_id 
              WHERE 
              atn.date='" . date('Y-m-d') . "'
              AND atn.school_id='$school_id' AND class_id IN ($t_classes) AND batch_id IN ($t_batches) ";
              
              $d_ddd = login_user()->t_data->classes;
              $d_ddd2 = login_user()->t_data->batches;
              
              $data["overall"] = $this->admin_model->dbQuery($sql_2)[0];
              $std_count = $this->db->select('count(id) as std_count')->from('sh_students_'.$school_id)->where_in('class_id',$d_ddd)->where_in('batch_id',$d_ddd2)->get()->row()->std_count;
              $data["overall"]->unknown = $std_count - ($data["overall"]->present + $data["overall"]->absent + $data["overall"]->leav + $data["overall"]->late);
              
              
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {

            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            //----- Students Attendance -----//
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

              $data["all"] = $this->admin_model->dbQuery($sql2);
              
              $sql_2 = "SELECT 
              coalesce(sum(if( atn.status = 'Present', 1, 0 ) ), 0) AS present,  
              coalesce(sum(if( atn.status = 'Absent', 1, 0 ) ), 0) AS absent, 
              coalesce(sum(if( atn.status = 'Leave', 1, 0 ) ), 0) AS leav,
              coalesce(sum(if( atn.status = 'Late', 1, 0 ) ), 0) AS late
              FROM `sh_attendance` atn 
              INNER JOIN sh_classes cls ON cls.id=atn.class_id 
              WHERE 
              atn.date='" . date('Y-m-d') . "'
              AND atn.school_id='$school_id'";

              $data["overall"] = $this->admin_model->dbQuery($sql_2)[0];
              $std_count = $this->db->select('count(id) as std_count')->from('sh_students_'.$school_id)->get()->row()->std_count;
              $data["overall"]->unknown = $std_count - ($data["overall"]->present + $data["overall"]->absent + $data["overall"]->leav + $data["overall"]->late);
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

            }
            
            echo json_encode($data);
          }
          
          public function get_emp_academic_wise_graph(){
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $data = array();
            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
              $acy = $this->admin_model->dbSelect("*", "academic_years", " school_id='$school_id' AND is_active='Y' AND deleted_at IS NULL ");
              $months = array();
              $acy_start_date = "";
              $acy_end_date = "";
              if(count($acy) > 0){
                $acy_start_date = $acy[0]->start_date;
                $acy_end_date = $acy[0]->end_date;
                $months = count_months_between_two_dates($acy_start_date, $acy_end_date)["months"];
              }
              
              $sql = "SELECT 
              SUM(CASE WHEN t.status='Present' THEN 1 ELSE 0 END) as Present,
              SUM(CASE WHEN t.status='Late' THEN 1 ELSE 0 END) as Late, 
              SUM(CASE WHEN t.status='Leave' THEN 1 ELSE 0 END) as LeaveStatus, 
              SUM(CASE WHEN t.status='Absent' THEN 1 ELSE 0 END) as Absent,
              year(t.date) as year,
              month(t.date) as month,
              DATE_FORMAT(t.date, '%b-%y') as monthyear 
              FROM 
              sh_users u JOIN sh_attendance t ON t.user_id=u.id AND u.role_id=4 
              WHERE 
              u.deleted_at=0 AND t.deleted_at is null
              AND u.school_id='$school_id' 
              AND t.date BETWEEN '$acy_start_date' AND '$acy_end_date' 
              GROUP BY year(t.date),month(t.date)";
              
              $data = $this->admin_model->dbQuery($sql);
              $data_months = array();
              if(count($data)>0){
                foreach($data as $d){
                  array_push($data_months, $d->month.','.$d->year);
                }
              }
              $diff = array_diff($months, $data_months);
              if(count($diff) > 0){
                foreach($diff as $m){
                  $month = explode(",", $m)[0];
                  $year = explode(",", $m)[1];
                  $obj = (object)array();
                  $obj->Present = 0;
                  $obj->Late = 0;
                  $obj->LeaveStatus = 0;
                  $obj->Absent = 0;
                  $obj->year = $year;
                  $obj->month = $month;
                  $obj->monthyear = date('M-y', strtotime($year.'-' . $month . '-01'));
                  array_push($data, $obj);
                }
              }
              usort($data, function($a, $b)
              {
                return strcmp($a->year.sprintf('%02d', $a->month),$b->year.sprintf('%02d', $b->month));
              }
            );
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            }
            echo json_encode($data);
          }
          
          public function get_std_academic_wise_graph(){
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $data = array();
            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
              $acy = $this->admin_model->dbSelect("*", "academic_years", " school_id='$school_id' AND is_active='Y' AND deleted_at IS NULL ");
              
              $months = array();
              $acy_start_date = "";
              $acy_end_date = "";
              if(count($acy) > 0){
                $acy_start_date = $acy[0]->start_date;
                $acy_end_date = $acy[0]->end_date;
                $months = count_months_between_two_dates($acy_start_date, $acy_end_date)["months"];
              }

              $sql = "SELECT 
              SUM(CASE WHEN t.status='Present' THEN 1 ELSE 0 END) as Present,
              SUM(CASE WHEN t.status='Late' THEN 1 ELSE 0 END) as Late, 
              SUM(CASE WHEN t.status='Leave' THEN 1 ELSE 0 END) as LeaveStatus, 
              SUM(CASE WHEN t.status='Absent' THEN 1 ELSE 0 END) as Absent,
              year(t.date) as year,
              month(t.date) as month, 
              DATE_FORMAT(t.date, '%b-%y') as monthyear  
              FROM 
              sh_users u JOIN sh_attendance t ON t.user_id=u.id AND u.role_id=3 
              WHERE 
              u.deleted_at=0 AND t.deleted_at is null
              AND u.school_id='$school_id' 
              AND t.date BETWEEN '$acy_start_date' AND '$acy_end_date' 
              GROUP BY year(t.date),month(t.date)";
              
              $data = $this->admin_model->dbQuery($sql);
              $data_months = array();
              if(count($data)>0){
                foreach($data as $d){
                  array_push($data_months, $d->month.','.$d->year);
                }
              }
              
              
              $diff = array_diff($months, $data_months);
              if(count($diff) > 0){
                foreach($diff as $m){
                  $month = explode(",", $m)[0];
                  $year = explode(",", $m)[1];
                  $obj = (object)array();
                  $obj->Present = 0;
                  $obj->Late = 0;
                  $obj->LeaveStatus = 0;
                  $obj->Absent = 0;
                  $obj->year = $year;
                  $obj->month = $month;
                  $obj->monthyear = date('M-y', strtotime($year.'-' . $month . '-01'));
                  array_push($data, $obj);
                }
              }
              usort($data, function($a, $b)
              {
                return strcmp($a->year.sprintf('%02d', $a->month),$b->year.sprintf('%02d', $b->month));
              }
            );
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            }
            echo json_encode($data);
          }

       public function get_std_academic_wise_graph_forparent(){

            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $std_id = $request->student_id;
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $user_id = $this->session->userdata("userdata")["user_id"];
            $data = array();
            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
              
              // $childrens = $this->db->select('student_id')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->join('sh_classes c', 's.class_id = c.id')->join('sh_batches b', 's.batch_id = b.id')->where('guardian_id', $user_id)->get()->result();
              // print_r($childrens);
              // die();

              $acy = $this->admin_model->dbSelect("*", "academic_years", " school_id='$school_id' AND is_active='Y' AND deleted_at IS NULL ");
              
              $months = array();
              $acy_start_date = "";
              $acy_end_date = "";
              if(count($acy) > 0){
                $acy_start_date = $acy[0]->start_date;
                $acy_end_date = $acy[0]->end_date;
                $months = count_months_between_two_dates($acy_start_date, $acy_end_date)["months"];
              }

              $sql = "SELECT 
              SUM(CASE WHEN t.status='Present' THEN 1 ELSE 0 END) as Present,
              SUM(CASE WHEN t.status='Late' THEN 1 ELSE 0 END) as Late, 
              SUM(CASE WHEN t.status='Leave' THEN 1 ELSE 0 END) as LeaveStatus, 
              SUM(CASE WHEN t.status='Absent' THEN 1 ELSE 0 END) as Absent,
              year(t.date) as year,
              month(t.date) as month, 
              DATE_FORMAT(t.date, '%b-%y') as monthyear  
              FROM 
              sh_users u JOIN sh_attendance t ON t.user_id=u.id AND u.role_id=3 
              WHERE 
              u.deleted_at=0 AND t.deleted_at is null
              AND u.school_id='$school_id'
              AND u.id='$std_id' 
              AND t.date BETWEEN '$acy_start_date' AND '$acy_end_date' 
              GROUP BY year(t.date),month(t.date)";
              
              $data = $this->admin_model->dbQuery($sql);
              $data_months = array();
              if(count($data)>0){
                foreach($data as $d){
                  array_push($data_months, $d->month.','.$d->year);
                }
              }
              
              
              $diff = array_diff($months, $data_months);
              if(count($diff) > 0){
                foreach($diff as $m){
                  $month = explode(",", $m)[0];
                  $year = explode(",", $m)[1];
                  $obj = (object)array();
                  $obj->Present = 0;
                  $obj->Late = 0;
                  $obj->LeaveStatus = 0;
                  $obj->Absent = 0;
                  $obj->year = $year;
                  $obj->month = $month;
                  $obj->monthyear = date('M-y', strtotime($year.'-' . $month . '-01'));
                  array_push($data, $obj);
                }
              }
              usort($data, function($a, $b)
              {
                return strcmp($a->year.sprintf('%02d', $a->month),$b->year.sprintf('%02d', $b->month));
              }
            );
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            }
            echo json_encode($data);
          }


      public function get_study_plan_statictics_forparent() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $student = $request->student_id;
            $studyplanstatictics = array();
            $data_array = array();
            $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
            $class_id = 0;
            $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }

            if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {

              
              
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
              
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

              $sql = "Select c.id as class_id, b.id as batch_id, c.name as class_name, b.name as batch_name, c.level_id, b.teacher_id from sh_classes c LEFT JOIN sh_batches b ON c.id=b.class_id WHERE c.school_id='$school_id' AND c.id='$class_id' AND b.id='$batch_id' AND c.deleted_at IS NULL AND b.deleted_at IS NULL AND c.academic_year_id='$academic_year_id'";
              $subjects = $this->admin_model->dbSelect("*", "subjects", " school_id='$school_id' AND academic_year_id='$academic_year_id' AND deleted_at IS NULL ");
              $syllabus_week_with_details = $this->admin_model->dbQuery("SELECT * FROM sh_syllabus_weeks w LEFT JOIN sh_syllabus_week_details d ON w.id=d.syllabus_week_id WHERE w.school_id='$school_id' AND w.academic_year_id='$academic_year_id' AND w.deleted_at IS NULL AND d.deleted_at IS NULL");
              $classes_batches = $this->admin_model->dbQuery($sql);
              
              foreach ($classes_batches as $cb) {
                $subs = $this->find_subjects($subjects, $cb->class_id, $cb->batch_id);
                foreach ($subs as $s) {
                  $syllabus = $this->find_syllabus($syllabus_week_with_details, $s->id, $s->class_id, $s->batch_id);
                  $s->syllabus = $syllabus;
                }
                $cb->subjects = $subs;
              }
              $data_array["all"] = $classes_batches;
              
              $sql_overall = "select 
              d.status as label,
              count(*) as value
              from sh_classes c 

              left join sh_batches b on c.id=b.class_id 
              left join sh_subjects s on b.id=s.batch_id 
              left join sh_syllabus_weeks w ON w.class_id=c.id AND w.batch_id=b.id AND w.subject_id=s.id 
              left join sh_syllabus_week_details d ON w.id=d.syllabus_week_id 

              WHERE 

              c.school_id=$school_id
              AND c.id=$class_id
              AND b.id=$batch_id 
              AND c.deleted_at is null 
              AND b.deleted_at is null 
              AND s.deleted_at is null 
              AND w.deleted_at is null 
              AND d.deleted_at is null 
              AND c.academic_year_id=$academic_year_id
              AND b.academic_year_id=$academic_year_id
              AND s.academic_year_id=$academic_year_id
              AND w.academic_year_id=$academic_year_id
              GROUP BY d.status";
              
              $data_array["overall"] = array(
                array("label"=>"Done","value"=>0),
                array("label"=>"Pending","value"=>0),
                array("label"=>"Skip","value"=>0),
                array("label"=>"Partially Done","value"=>0),
                array("label"=>"Reschedule","value"=>0)
              );

                
              if(count($this->admin_model->dbQuery($sql_overall))>0){
                $data_array["overall"] = $this->admin_model->dbQuery($sql_overall);
                  // print_r($data_array["overall"]);
                  // die();
                if(count($data_array["overall"]) > 0){
                  $data_array["overall"];
                 

                }
                if(is_null($data_array["overall"][0]->label)){
                  $data_array["overall"][0]->label = "Label";
                  $data_array["overall"][0]->value = 0;
                }


                foreach ($data_array["overall"] as $key => $value) {
                  $data_array["overall"][$key]->label = lang($this->createSlug($data_array["overall"][$key]->label));
                }

              }
            }else if (login_user()->user->role_id == STUDENT_ROLE_ID) { }
            
            echo json_encode($data_array); 
          }
          
      public function get_fee_summary_forparent() {
        
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $student_id = $request->student_id;
        $class = array();
        $sql = "SELECT c.id, c.name FROM sh_classes c INNER JOIN sh_students_".$school_id." s ON c.id=s.class_id WHERE s.id='$student_id' AND c.deleted_at IS NULL ";
        $class = $this->admin_model->dbQuery($sql);
        $overall = array();
        $final_array = array();


        //Total paid
        //SELECT count(*) as total_paid FROM `sh_fee_collection` INNER JOIN sh_fee_types ON sh_fee_collection.feetype_id=sh_fee_types.id WHERE sh_fee_collection.student_id="2647" AND sh_fee_collection.deleted_at IS NULL AND sh_fee_types.academic_year_id=20

              $total_paid = 0;
              $total_partial = 0;
              $total_due = 0;
              foreach ($class as $cls) {
                $cls->batches = $this->db->select('id,name')->from('sh_batches')->where('class_id', $cls->id)->where('deleted_at is null')->get()->result();
                foreach ($cls->batches as $b) {
                  $b->class_id = $cls->id;
                  $b->class_name = $cls->name;
                  $b->full_name = $cls->name . "-" . $b->name;
                  
                  // get student ids
                  /*$this->db->select('id');
                  $this->db->from('sh_students_'.$school_id);
                  $this->db->where('class_id',$cls->id);
                  $this->db->where('batch_id',$b->id);
                  $students = $this->db->get()->result();
                  // get total students count
                  $this->db->select('count(*) as total_students');
                  $this->db->from('sh_students_'.$school_id);
                  $this->db->where('class_id',$cls->id);
                  $this->db->where('batch_id',$b->id);
                  $b->total_students = $this->db->get()->row()->total_students;*/

                  $students = array("id"=>$student_id);
                  
                  

                    $fees = $this->db->select('id')->from('sh_fee_types')->where('class_id', $cls->id)->where('deleted_at is null')->get()->result();

                    $due = 0;
                    $paid = 0;
                    $partial = 0;

                    foreach ($fees as $f) {
                     $temp = $this->db->select('id')->from('sh_fee_collection')->where('feetype_id', $f->id)->where('student_id', $student_id)->where('status','1')->where('deleted_at is null')->get()->row();
                     if($temp){
                      $paid++;
                     }else{
                      $temp = $this->db->select('id')->from('sh_fee_collection')->where('feetype_id', $f->id)->where('student_id', $student_id)->where('status','2')->where('deleted_at is null')->get()->row();
                      if($temp){
                        $partial++;
                      }
                        $temp = $this->db->select('id')->from('sh_fee_types')->where('id', $f->id)->where('due_date < curdate()')->get()->row();
                        if($temp){
                          $due++;
                        }
                     }
                    }
                    
                    // $due = $this->db->select('count(*) as due')->from('sh_fee_types ft')->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.student_id = '.$student_id.' and fc.deleted_at is null and status = "1"','left')->where('ft.class_id',$cls->id)->where('ft.deleted_at is null')->where('ft.due_date < curdate()')->where('fc.id is null')->get()->row()->due;

                    // $paid = $this->db->select('count(*) as paid')->from('sh_fee_types ft')->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.student_id = '.$student_id.' and fc.deleted_at is null and fc.status = "1"','left')->where('ft.class_id',$cls->id)->where('ft.deleted_at is null')->where('fc.id is not null')->get()->row()->paid;

                    // $partial = $this->db->select('count(*) as partial')->from('sh_fee_types ft')->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.student_id = '.$student_id.' and fc.deleted_at is null and fc.status= "2"')->where('ft.class_id',$cls->id)->where('ft.deleted_at is null')->get()->row()->partial;

                    
                    
                    

                  
                  

                  
                  

                  
                }
                
              }
              $overall = new stdClass();
              $overall->total_paid = $paid;
              $overall->total_due = $due;
              $overall->total_partial = $partial;

              $overall->full_name = lang('lbl_overall');
              //print_r($overall);die();

            /*if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {

            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
              
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
              
            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {

          $sql = "SELECT c.id, c.name FROM sh_classes c INNER JOIN sh_students_".$school_id." s ON c.id=s.class_id WHERE s.id='$student_id' AND c.deleted_at IS NULL ";
            $classes = $this->admin_model->dbQuery($sql);

            //$classes = $this->db->select('id,name')->from('sh_classes')->where('school_id', $school_id)->where('academic_year_id', $academic_year_id)->where('deleted_at is null')->get()->result();
              $total_paid = 0;
              $total_partial = 0;
              $total_due = 0;
              
              foreach ($classes as $cls) {
 
                $sql = "SELECT c.id, c.name FROM sh_batches c INNER JOIN sh_students_".$school_id." s ON c.id=s.batch_id WHERE s.id='$student_id' AND c.deleted_at IS NULL ";
                $cls->batches = $this->admin_model->dbQuery($sql);

      
                foreach ($cls->batches as $b) {
                  $b->class_id = $cls->id;
                  $b->class_name = $cls->name;
                  $b->full_name = $cls->name . "-" . $b->name;
                  
                  // get student ids
                  $this->db->select('id');
                  $this->db->from('sh_students_'.$school_id);
                  $this->db->where('class_id',$cls->id);
                  $this->db->where('batch_id',$b->id);
                  $this->db->where('id',$student_id);
                  $students = $this->db->get()->result();

                  // get total students count
                  $this->db->select('count(*) as total_students');
                  $this->db->from('sh_students_'.$school_id);
                  $this->db->where('id',$student_id);
                  $this->db->where('class_id',$cls->id);
                  $this->db->where('batch_id',$b->id);
                  $b->total_students = $this->db->get()->row()->total_students;
                  
                  // get paid, partial and due students
                  $b->total_paid = 0;
                  $b->total_due = 0;
                  $b->total_partial = 0;
                  foreach($students as $std){
                    $temp = $this->db->select('count(*) as due')
                    ->from('sh_fee_types ft')
                    ->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.deleted_at is null','left')
                    ->where('fc.student_id' , $student_id)
                    ->where('ft.class_id',$cls->id)
                    ->where('ft.deleted_at is null')
                    ->where('ft.due_date < curdate()')->get()->result();
                   if($temp > 0){
                      $total_due = $temp[0]->due;
                    }
                    $temp2 = $this->db->select('count(*) as partial')
                      ->from('sh_fee_types ft')
                      ->join('sh_fee_collection fc','ft.id = fc.feetype_id and fc.deleted_at is null and fc.status= "1"','left')
                      ->where('fc.student_id' , $student_id)
                      ->where('ft.class_id',$cls->id)
                      ->where('ft.deleted_at is null')
                      ->where('ft.due_date < curdate()')
                      ->where('fc.id is null')->get()->row()->partial;
                      if($temp2 > 0){
                        $total_partial = $temp2;
                      }else{
                        $b->total_paid++;
                        $total_paid++;
                      }
                  }
                }
                
              }
              $overall = new stdClass();
              $overall->total_paid = $total_paid;
              $overall->total_due = $total_due;
              $overall->total_partial = $total_partial;

              $overall->full_name = lang('lbl_overall');

            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

            }*/

            //$data["classes"] = $classes;
            $data["overall"] = $overall;
            echo json_encode($data);
          

        }

       public function get_today_std_attendance_parentportal() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $student = $request->student_id;
            // print_r($student);
            // die();
            $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id","students_".$school_id," id='$request->student_id' ");
            $class_id = 0;
            $batch_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
        }

       $data = array();
            
             
              
              
           if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {

            } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            //----- Students Attendance -----//
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
              AND atn.user_id = '$student'
              GROUP BY atn.class_id 
              ORDER BY cls.id";

              $data["all"] = $this->admin_model->dbQuery($sql2);
              // echo $this->db->last_query();
              // die();
              $sql_2 = "SELECT 
              coalesce(sum(if( atn.status = 'Present', 1, 0 ) ), 0) AS present,  
              coalesce(sum(if( atn.status = 'Absent', 1, 0 ) ), 0) AS absent, 
              coalesce(sum(if( atn.status = 'Leave', 1, 0 ) ), 0) AS leav,
              coalesce(sum(if( atn.status = 'Late', 1, 0 ) ), 0) AS late
              FROM `sh_attendance` atn 
              INNER JOIN sh_classes cls ON cls.id=atn.class_id 
              WHERE 
              atn.date='" . date('Y-m-d') . "'
              AND atn.school_id='$school_id'
              AND atn.user_id ='$student'";

              $data["overall"] = $this->admin_model->dbQuery($sql_2)[0];
              //$std_count = $this->db->select('count(id) as std_count')->from('sh_students_'.$school_id)->get()->row()->std_count;
              //$data["overall"]->unknown = $std_count - ($data["overall"]->present + $data["overall"]->absent + $data["overall"]->leav + $data["overall"]->late);
            } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
              

            } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {

            }
            
            echo json_encode($data);
          }       

          public function reset_forms($school_id){
            $this->common_model->update_where("sh_form_categories",array("school_id" => $school_id),array("deleted_at" => date('Y-m-d h:i:s')));
            $this->common_model->update_where("sh_templates",array("school_id" => $school_id),array("deleted_at" => date('Y-m-d h:i:s')));
            save_school_system_templates($school_id);
          }   

          public function getAnnouncements() {
            $school_id = $this->session->userdata("userdata")["sh_id"];
            $academic_year = $this->session->userdata("userdata")["academic_year"];
            $date = date("Y-m-d");
            if (login_user()->user->role_id == ADMIN_ROLE_ID) {
              $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'admin') AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' ");
              echo json_encode($data);
            } else if (login_user()->user->role_id == STUDENT_ROLE_ID ) {
              $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'students') AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' ");
              echo json_encode($data);
            } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID ) {
              $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'employees') AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' ");
              echo json_encode($data);
            } else if (login_user()->user->role_id == PARENT_ROLE_ID ) {
              $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'parents') AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' ");
              echo json_encode($data);
            } else if (login_user()->user->role_id == TEACHER_ROLE_ID ) {
              $data =  $this->admin_model->dbSelect( "*", "announcements", " status = 'Active' AND (level = 'all' OR level = 'teachers') AND ('$date' BETWEEN from_date AND to_date) AND school_id='$school_id' AND academic_year_id='$academic_year' ");
              echo json_encode($data);
            }
          }
}
