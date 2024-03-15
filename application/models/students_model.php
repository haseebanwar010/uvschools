<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Students_model extends CI_Model {


    function __construct() {
        parent::__construct();
    }
    public function getStudentByID($id){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $ci = $this->db;
        // $ci->select('students_'.$school_id.'.*,classes.name as classname,batches.name as batchname,a.country_name,b.country_name as nationality,s.group_name,d.name as discount')
        //     ->select('date_format(sh_students_'.$school_id.'.dob,"%d/%m/%Y") as dobn' ,false)
        //     ->select('date_format(sh_students_'.$school_id.'.joining_date,"%d/%m/%Y") as adm_date' ,false);
        // $ci->from('students_'.$school_id.'');
        // $ci->join('sh_student_class_relation cr','cr.student_id = students_'.$school_id.'.id','left');
        // $ci->join('classes','classes.id = students_'.$school_id.'.class_id')->join('batches','batches.id=students_'.$school_id.'.batch_id');
        // $ci->join('sh_countries a','a.id = students_'.$school_id.'.country','left');
        // $ci->join('sh_countries b','b.id = students_'.$school_id.'.nationality','left');
        // $ci->join('sh_subject_groups s','s.id = students_'.$school_id.'.subject_group_id','left');
        // $ci->join('sh_fee_discount d','d.id = students_'.$school_id.'.discount_id','left');

        // $ci->where('cr.deleted_at', NULL)->where('students_'.$school_id.'.id', $id);
        // $query =  $ci->get();
        //code added by sheraz to show multiple discounts in student view
        // $data = $query->row();
        $sql = "Select 
            u.*,
            date_format(u.dob,'%d/%m/%Y') as dobn,
            date_format(u.joining_date,'%d/%m/%Y') as adm_date, 
            rl.student_id as id,
            rl.class_id,
            rl.batch_id,
            rl.subject_group_id,
            rl.academic_year_id,
            rl.discount_id,
            sg.group_name,
            g.guardian_id,
            uu.name as father_name,
            c.name as classname, 
            b.name as batchname,
            a.country_name,
            d.country_name as nationality, 
            uuu.name as teacher_name 
            FROM
            sh_users u 
            LEFT JOIN sh_student_guardians g ON u.id=g.student_id and g.deleted_at is null 
            LEFT JOIN sh_users uu On uu.id=g.guardian_id 
            LEFT JOIN sh_student_class_relation rl ON u.id=rl.student_id 
            INNER JOIN sh_classes c ON rl.class_id=c.id
            INNER JOIN sh_batches b ON rl.batch_id=b.id
            LEFT JOIN sh_subject_groups sg on rl.subject_group_id = sg.id
            LEFT JOIN sh_countries a on a.id=u.country
            LEFT JOIN sh_countries d on d.id=u.nationality
            INNER JOIN sh_users uuu ON uuu.id=b.teacher_id
            WHERE rl.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." AND  rl.deleted_at is NULL AND u.id='$id'";
        
        $query = $this->db->query($sql);
        $student = $query->result();
        $data = $student[0];
        $discount_name = array();
        if ($data->discount_id != "") {
            $discount_array = explode(",", $data->discount_id);
            foreach ($discount_array as $key => $did) {
                $ci->select('name');
                $ci->from('sh_fee_discount');
                $ci->where('id',$did);
                $query2 = $ci->get();
                $d_name = $query2->row();
                if($d_name)
                {
                    array_push($discount_name, $d_name->name);
                }
            } 
            $discount_names = implode(", ", $discount_name);
            $data->discount = $discount_names;   
        }

        return $data;
    }
    
    public function getSpecificStudentForEdit($student_id){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $query = "select 
            u.id as student_id, 
            u.avatar as image2,
            u.religion as religion,
            u.name as firstname,
            u.gender as gender,
            u.dob as dob,
            u.ic_number,
            u.blood as blood,
            u.birthplace as birthPlace,
            u.nationality as nationality,
            u.language as language,
            u.email as email,
            u.contact as phone,
            u.country as country,
            u.city as city,
            u.address as address,
            u.mother_name,
            u.mother_phone,
            u.mother_ic,
            cr.class_id as course,
            cr.batch_id as batch,
            u.rollno as rollno,
            u.joining_date as adm_date,
            cr.subject_group_id as group_id,
            uu.id as parentId,
            uu.avatar as image3,
            uu.name as pName,
            uu.gender as pGender,
            uu.dob as pDob,
            uu.occupation as pOccupation,
            uu.income as pIncome,
            uu.email as pEmail,
            uu.contact as pPhone,
            uu.u_phone_number as u_phone_number,
            uu.parent_phone_code as parent_phone_code,
            uu.address as pStreet,
            uu.ic_number as pIdNumber,
            uu.country as pCountry,
            uu.city as pCity,
            sg.relation as pRelation,
            sg.id as student_guardian_id,
            sg.deleted_at
            from sh_users u
            left join sh_student_class_relation cr ON cr.student_id=u.id 
            left join sh_student_guardians sg ON sg.student_id=u.id 
            left join sh_users uu ON sg.guardian_id=uu.id 
            where u.id=" . $student_id . " and cr.academic_year_id='$academic_year_id' and cr.deleted_at is NULL";
        $res = $this->db->query($query);
        $result=$res->row();
        
        if($result)
        {
            $find_cnumber=$this->db->select('u_phone_number,parent_phone_code')->from('sh_users')->where('id',$result->student_id)->get()->row();
            if($find_cnumber)
            {
                $result->u_phone_number=$find_cnumber->u_phone_number;
                $result->parent_phone_code=$find_cnumber->parent_phone_code;
            }
            else
            {
                $result->u_phone_number="";
                $result->parent_phone_code="";
            }
            
            $cid=(int) $result->nationality;
		    $re=$this->db->select('id,country_code')->from('sh_countries')->where('id',$cid)->get()->row();
		    if($re)
		    {
		        $result->nationality=$re->country_code;
		    }
		    else
		    {
		        $result->nationality="";
		    }
        }
        return $result;
    }
    
    public function getAllStudents($where){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $sql_old = "Select u.*,sg.group_name,g.guardian_id,uu.name as father_name,c.name as class_name, b.name as batch_name, group_concat(t.name) as teacher_name, t.health_status from "
        . "sh_students_".$school_id." u "
        . "inner join sh_classes c on u.class_id=c.id "
        . "inner join sh_batches b on u.batch_id=b.id "
        . "inner join sh_users t on find_in_set(t.id, b.teacher_id) "
        . "left join sh_subject_groups sg on u.subject_group_id = sg.id "
        . "LEFT JOIN sh_student_guardians g ON u.id=g.student_id and g.deleted_at is null "
        . "LEFT JOIN sh_users uu On uu.id=g.guardian_id "
        . "Where u.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." and ".$where." group by u.id order by u.name";
        
        
        
        $sql = "Select 
            u.*,
            rl.student_id as id,
            rl.class_id,
            rl.batch_id,
            rl.subject_group_id,
            rl.academic_year_id,
            rl.discount_id,
            sg.group_name,
            g.guardian_id,
            uu.name as father_name,
            c.name as class_name, 
            b.name as batch_name, 
            uuu.name as teacher_name 
            FROM
            sh_users u 
            LEFT JOIN sh_student_guardians g ON u.id=g.student_id and g.deleted_at is null 
            LEFT JOIN sh_users uu On uu.id=g.guardian_id 
            LEFT JOIN sh_student_class_relation rl ON u.id=rl.student_id 
            INNER JOIN sh_classes c ON rl.class_id=c.id
            INNER JOIN sh_batches b ON rl.batch_id=b.id
            LEFT JOIN sh_subject_groups sg on rl.subject_group_id = sg.id
            INNER JOIN sh_users uuu ON uuu.id=b.teacher_id
            WHERE rl.academic_year_id = ".$this->session->userdata("userdata")["academic_year"]." AND ". $where ." order by u.name";
        
        $query = $this->db->query($sql);
        $students = $query->result();
        $students = array_values($students);
        return $students;
    }
    
    public function getTestRecord(){
        return $this->db->select('*')->from('settings')->get()->result();
    }
}