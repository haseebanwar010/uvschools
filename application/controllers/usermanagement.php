<?php 

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Usermanagement extends CI_Controller {

	function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function index(){
        // $ci = & get_instance();
        //   $sess_role_id = $ci->session->userdata("userdata")['role_id'];
        //   $arr = $ci->session->userdata("userdata")['persissions'];
        //   $array = json_decode($arr);
        //   print_r($array);die();
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $school_id = $this->session->userdata("userdata")["sh_id"];
        if (login_user()->user->role_id == ADMIN_ROLE_ID || login_user()->user->role_id == EMPLOYEE_ROLE_ID) {
            //----- Count number of users only role base -----//
            $sql = "SELECT count(u.id) as count, role_id as role_id FROM sh_users u WHERE u.school_id='$school_id' AND u.deleted_at='0' AND role_id <> " . STUDENT_ROLE_ID . " GROUP BY role_id ORDER BY role_id ";
            $users = $this->admin_model->dbQuery($sql);
            $data['parent_count'] = $data['student_count'] = $data['employee_count'] = 0;
            foreach ($users as $user) {
                if ($user->role_id == PARENT_ROLE_ID) {
                  $data['parent_count'] = $user->count;
                } else if ($user->role_id == EMPLOYEE_ROLE_ID) {
                  $data['employee_count'] = $user->count;
                }
            }

            $student_count = $this->db->select('count(u.id) as total_students')->from('sh_users u')->join('sh_student_class_relation cr','cr.student_id=u.id','left')->join('sh_classes c','c.id=cr.class_id','inner')->where('cr.deleted_at',NULL)->where('cr.academic_year_id',$academic_year_id)->where('u.school_id',$school_id)->get()->row();
            // print_r($this->db->last_query()); die();
            $data['students'] = array();
            $data['parents'] = array();
            $data['employees'] = array();
            $data['student_count'] = $student_count->total_students;

            $this->load->view('settings/user_management', $data);
        }
    }

    public function getUsers($role_id){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $data = array();
        if($role_id == STUDENT_ROLE_ID) {
            $sql = "SELECT s.*,c.name as class_name, b.name as batch_name, IFNULL(uu.name,'') as guardian_name, IFNULL(uu.contact,'') as guardian_contact, IFNULL(g.relation,'') as guardian_relation, IFNULL(cc.country_name,'') as nationality, (SELECT datetime FROM sh_activities WHERE user_id=s.id AND tag='login' ORDER BY id DESC LIMIT 1) as login_time, (SELECT datetime FROM sh_activities WHERE user_id=s.id AND tag='logout' ORDER BY id DESC LIMIT 1) as logout_time FROM sh_students_$school_id s INNER JOIN sh_classes c ON s.class_id=c.id INNER JOIN sh_batches b ON s.batch_id=b.id LEFT JOIN sh_student_guardians g ON s.id=g.id LEFT JOIN sh_users uu ON g.guardian_id=uu.id LEFT JOIN sh_activities a ON s.id=a.user_id LEFT JOIN sh_countries cc ON s.nationality=cc.id WHERE s.school_id='$school_id' AND s.deleted_at='0' GROUP BY s.id";
            $data = $this->admin_model->dbQuery($sql);
        } else if($role_id == EMPLOYEE_ROLE_ID) {
            $sql = "SELECT u.id,
                u.role_id as role_id, 
                IFNULL(u.avatar,null) as avatar, 
                IFNULL(u.name,'') as name, 
                IFNULL(u.gender,'') as gender, 
                IFNULL(u.dob,'') as dob, 
                IFNULL(u.blood,'') as blood, 
                IFNULL(u.birthplace,'') as birthplace, 
                IFNULL(cc.country_name,'') as nationality, 
                IFNULL(u.religion,'') as religion,
                IFNULL(u.job_title,'') as job_title, 
                IFNULL(u.city,'') as city, 
                IFNULL(c.id,'') as role_category_id,
                IFNULL(u.address,'') as address, 
                IFNULL(u.email,'') as email, 
                IFNULL(u.contact,'') as contact, 
                u.status, 
                c.category as role_category, 
                d.name as title_department, 
                (SELECT datetime FROM sh_activities WHERE user_id=u.id AND tag='login' ORDER BY id DESC LIMIT 1) as login_time, 
                (SELECT datetime FROM sh_activities WHERE user_id=u.id AND tag='logout' ORDER BY id DESC LIMIT 1) as logout_time 
                FROM sh_users u 
                LEFT JOIN sh_role_categories c ON u.role_category_id=c.id 
                LEFT JOIN sh_departments d ON u.department_id=d.id 
                LEFT JOIN sh_countries cc ON u.nationality=cc.id  
                WHERE 
                u.role_id=".EMPLOYEE_ROLE_ID." 
                AND u.school_id=$school_id 
                AND u.deleted_at=0 
                GROUP BY u.id";
            $data = $this->admin_model->dbQuery($sql);
            foreach($data as $d){
                $d->guardian_name = null;
            }
        } else if($role_id == PARENT_ROLE_ID) {
            $sql = "SELECT u.id,
                u.role_id as role_id, 
                IFNULL(u.avatar,null) as avatar, 
                IFNULL(u.name,'') as name, 
                IFNULL(u.gender,'') as gender, 
                IFNULL(u.dob,'') as dob, 
                IFNULL(u.blood,'') as blood, 
                IFNULL(u.birthplace,'') as birthplace, 
                IFNULL(c.country_name,'') as nationality, 
                IFNULL(u.religion,'') as religion, 
                IFNULL(u.city,'') as city, 
                IFNULL(u.address,'') as address, 
                IFNULL(u.email,'') as email, 
                IFNULL(u.contact,'') as contact, 
                u.status, 
                (SELECT datetime FROM sh_activities WHERE user_id=u.id AND tag='login' ORDER BY id DESC LIMIT 1) as login_time, 
                (SELECT datetime FROM sh_activities WHERE user_id=u.id AND tag='logout' ORDER BY id DESC LIMIT 1) as logout_time 
                FROM sh_users u
                LEFT JOIN sh_countries c ON u.nationality=c.id  
                WHERE 
                u.role_id=".PARENT_ROLE_ID." 
                AND u.school_id=$school_id 
                AND u.deleted_at=0 
                GROUP BY u.id";
            $data = $this->admin_model->dbQuery($sql);
        }
        echo json_encode($data);
    }

}



?>