<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Applications extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    function all() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
       $sql = "SELECT r.*,uu.name as response_by,st.name as student_name, st.rollno,date_format(request_time,'%h:%i %p %d %b %Y') as request_time, date_format(r.updated_at,'%h:%i %p %d %b %Y') as updated_at, cls.name as classname,sub.name as subjectname, bth.name as batchname, dep.name as depart_name, u.name as username, cat.category,exam.title, paper_name FROM sh_request_log r "
            . "LEFT JOIN sh_users u ON r.user_id=u.id "
            . "LEFT JOIN sh_users uu ON r.response_by=uu.id "
            . "LEFT JOIN sh_users st ON r.student_id=st.id "
            . "LEFT JOIN sh_classes cls ON r.class_id=cls.id "
            . "LEFT JOIN sh_batches bth ON r.batch_id=bth.id "
            . "LEFT JOIN sh_subjects sub ON r.subject_id=sub.id "
            . "LEFT JOIN sh_role_categories cat ON r.category_id=cat.id "
            . "LEFT JOIN sh_exam_details ex_detail ON r.exam_detail_id=ex_detail.id "
            . "LEFT JOIN sh_online_exam_details online_ex_detail ON r.online_exam_detail_id=online_ex_detail.id "
            . "LEFT JOIN sh_exams exam ON exam.id=ex_detail.exam_id "
            . "LEFT JOIN sh_departments dep ON r.department_id=dep.id ";
        //-------------------------
        $where = "";
        $where1 = "";
        
        if (login_user()->user->role_id == EMPLOYEE_ROLE_ID && get_teacher_dept_id() == login_user()->user->teacher_dept_id) {

            $where = "WHERE r.deleted_at IS NULL  "
            . "AND r.school_id='$school_id' AND (r.user_id = '".login_user()->user->user_id."' or (r.class_id in (".implode(",", login_user()->t_data->classes).") OR r.type = 'emp_attendance' or r.type= 'syllabus' or r.batch_id in (".implode(",", login_user()->t_data->batches).") or r.subject_id in (".implode(",", login_user()->t_data->subjects).")) and r.type in ("."'".implode("','", login_user()->req_types)."'".")) AND (r.status = 'edit-request' OR r.status = 'inprocess') order by r.updated_at desc ";

            $where1 = "WHERE  r.marked = 'Y' AND r.school_id = '$school_id' AND (r.user_id = '".login_user()->user->user_id."' or r.response_by = '".login_user()->user->user_id."'or (r.class_id in (".implode(",", login_user()->t_data->classes).") or r.batch_id in (".implode(",", login_user()->t_data->batches).") or r.subject_id in (".implode(",", login_user()->t_data->subjects).")) and r.type in ("."'".implode("','", login_user()->req_types)."'".")) order by r.updated_at desc";

        } else if (login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            $where = "WHERE r.deleted_at IS NULL AND (r.status = 'edit-request' OR r.status = 'inprocess') "
            . "AND r.school_id='$school_id' AND r.type in ("."'".implode("','", login_user()->req_types)."'".") order by r.updated_at desc ";

            $where1 = "WHERE  r.marked = 'Y' AND r.school_id = '$school_id' AND r.type in ("."'".implode("','", login_user()->req_types)."'".") order by r.updated_at desc";
        } else if (login_user()->user->role_id == ADMIN_ROLE_ID) {
           
            $where = "WHERE r.deleted_at IS NULL AND (r.status = 'edit-request' OR r.status = 'inprocess') "
            . "AND r.school_id='$school_id' order by r.updated_at desc";

            $where1 .="WHERE  r.marked = 'Y' AND r.school_id = '$school_id' order by r.updated_at desc";

        } else if (login_user()->user->role_id == PARENT_ROLE_ID) {
            
        } else if (login_user()->user->role_id == STUDENT_ROLE_ID) {
            
        }

        $data["requests"] = $this->admin_model->dbQuery($sql.$where);

        foreach($data["requests"] as $r){
            if($r->type == "online_exam_edit" || $r->type == "online_exam_retake"){
                $attempts = 0;
                $check_attempts = $this->db->select('attempts')->from('sh_online_attempts')->where('student_id', $r->student_id)->where('paper_id', $r->online_exam_detail_id)->get()->row();
                if($check_attempts){
                    $attempts = $check_attempts->attempts;
                }

                $r->attempts = $attempts;
            }
        }

        // echo $this->db->last_query();
        // die();

        



        // $sql1 = "SELECT r.*,date_format(request_time,'%h:%i %p %d %b %Y') as request_time,cls.name as classname,sub.name as subjectname, bth.name as batchname, dep.name as depart_name, u.name as username, cat.category,exam.title FROM sh_request_log r "
        //     . "LEFT JOIN sh_users u ON r.user_id=u.id "
        //     . "LEFT JOIN sh_classes cls ON r.class_id=cls.id "
        //     . "LEFT JOIN sh_batches bth ON r.batch_id=bth.id "
        //     . "LEFT JOIN sh_subjects sub ON r.subject_id=sub.id "
        //     . "LEFT JOIN sh_role_categories cat ON r.category_id=cat.id "
        //     . "LEFT JOIN sh_exam_details ex_detail ON r.exam_detail_id=ex_detail.id "
        //     . "LEFT JOIN sh_exams exam ON exam.id=ex_detail.exam_id "
        //     . "LEFT JOIN sh_departments dep ON r.department_id=dep.id ";
            

        $data["log_history"] = $this->admin_model->dbQuery($sql.$where1);
        // echo '<pre>';
        // print_r($data['requests']);
        // echo '</pre>';die();
        $this->load->view('all_applications', $data);
    }

}
