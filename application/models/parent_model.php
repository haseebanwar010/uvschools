<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Parent_model extends CI_Model {

	function __construct() {
		parent::__construct();


	}

	function getAll(){
		$academic_year_id = $this->session->userdata("userdata")["academic_year"];
		// $query = $this->db->select('u.*,count(u2.id) as children')
		// 		->from('sh_users u')
		// 		->join('sh_student_guardians sg', 'u.id = sg.guardian_id', 'left')
		// 		->join('sh_students_'.$this->session->userdata("userdata")["sh_id"].' u2','sg.student_id = u2.id')
		// 		->where('u.role_id',PARENT_ROLE_ID)
		// 		->where('u.deleted_at',0)
		// 		->where("u.school_id",$this->session->userdata('userdata')['sh_id'])
		// 		->where('sg.deleted_at is null')->group_by('u.id')->get();

		$query = $this->db->select('u.*,count(u2.id) as children')
				->from('sh_users u')
				->join('sh_student_guardians sg', 'u.id = sg.guardian_id', 'left')
				->join('sh_users u2','sg.student_id = u2.id')
				->join('sh_student_class_relation cr','cr.student_id = sg.student_id', 'left')
				->join('sh_classes c','cr.class_id = c.id', 'inner')
				->join('sh_batches b','cr.batch_id = b.id', 'inner')
				->where('u.role_id',PARENT_ROLE_ID)
				->where('u.deleted_at',0)
				->where("u.school_id",$this->session->userdata('userdata')['sh_id'])
				->where("cr.deleted_at",NULL)
				->where("cr.academic_year_id",$academic_year_id)
				->where('sg.deleted_at is null')->group_by('u.id')->get();

		$result = $query->result();
		$p_ids = array_map (function($value){
			return $value->id;
		} , $result);
		$p_ids[] = 0;
		$query2 = $this->db->select('u.*,0 as children',false)->from('sh_users u')->where('u.role_id',PARENT_ROLE_ID)->where('u.deleted_at',0)->where("u.school_id",$this->session->userdata('userdata')['sh_id'])->where_not_in('id', $p_ids)->get();
		$result2 = $query2->result();
		$result = array_merge($result,$result2);	
		
		return $result;
	}

	function getParentByIDEdit($id){
		$query = $this->db->select('*')->from('sh_users')->where('id',$id)->get()->row();
		if($query)
		{
		    $cid=(int) $query->country;
		    $result=$this->db->select('id,country_code')->from('sh_countries')->where('id',$cid)->get()->row();
		    if($result)
		    {
		        $query->country=$result->country_code;
		    }
		    else
		    {
		        $query->country="";
		    }
		}
		return $query;
		
	}

	function getParentByID($id){
		$query = $this->db->select('sh_users.*,a.country_name as country')->from('sh_users')->join('sh_countries a','a.id=sh_users.country','left')->where('sh_users.id',$id)->get();
		return $query->row();
	}

	function getUserProfileDetailStudent($student_id)
	{
		$school_id = $this->session->userdata("userdata")["sh_id"];
		$guardian_id = $this->session->userdata("userdata")["user_id"];
		// print_r($user_id);
		// die();
		$query = $this->db->select('g.relation, s.name,dob,rollno,avatar,joining_date,contact,religion,email,address,blood,ic_number,passport_number,gender,birthplace,c.name as class_name, b.name as batch_name')->from('sh_students_'.$school_id.' s')->join('sh_classes c','s.class_id=c.id')->join('sh_batches b','s.batch_id=b.id')->join('sh_student_guardians g','g.student_id = s.id')->where('s.id',$student_id)->get();
		

		return $query->row();


		

	}


}