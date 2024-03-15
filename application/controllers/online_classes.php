<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Online_classes extends CI_Controller {

	function __construct() {
		parent::__construct();
		if (!$this->session->userdata("userdata")) {
			redirect(site_url("login"));
		}
	}


	function index(){
		$user = $this->session->userdata("userdata");
		if($user["role_id"] == 1 || $user["department_id"] == get_teacher_dept_id()){
			$this->load->view('online_classes');
		}else if($user["role_id"] == 3){
			$this->load->view('online_classes_student');
		}else{
			redirect("dashboard");
		}
	}

	function getClassStatus(){
		$postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $data["class_id"] = $request->class_id;
        $batch_id = $data["batch_id"] = $request->batch_id;
        // $subject_id = $data["subject_id"] = $request->subject_id;
        $user = $this->session->userdata("userdata")["name"];
        $class_batch = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name." - ".$this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;

        $row = $this->db->select('class_name,started_by')->from('sh_online_classes')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('status', 'ongoing')->where('deleted_at is null')->get()->row();

        if($row){
        	$data["class_found"] = true;
        	$data["class_name"] = $row->class_name;
	        $data["user_name"] = $user;
	        $data["subject_name"] = $class_batch;
	        $data["started_by"] = $row->started_by;
        }else{
        	$data["class_found"] = false;
        	$data["class_name"] = "";
        	$data["user_name"] = "";
	        $data["subject_name"] = $class_batch;
        }

        echo json_encode($data);
	}

	function startClass(){
		$postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_id = $data["class_id"] = $request->class_id;
        $batch_id = $data["batch_id"] = $request->batch_id;
        // $subject_id = $data["subject_id"] = $request->subject_id;
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $user_id = $this->session->userdata("userdata")["user_id"];
        $user = $this->session->userdata("userdata")["name"];
        $randomClassName = bin2hex(random_bytes(10)).mt_rand().rand(10,100);

        $row = $this->db->select('*')->from('sh_online_classes')->where('class_id', $class_id)->where('batch_id', $batch_id)->where('deleted_at is null')->get()->row();

        if($row){
        	$this->db->where('id', $row->id)->update('sh_online_classes', array("class_name" => $randomClassName,"status" => "ongoing", "started_by" => $user_id));
        	//$randomClassName = $row->class_name;
        }else{

	        $c_data = array("class_id" => $class_id,
	    					"batch_id" => $batch_id,
	    					"started_by" => $user_id,
	    					"school_id" => $school_id,
	    					"class_name" => $randomClassName);
	        $this->db->insert('sh_online_classes', $c_data);
        }
        

        $class_batch = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name." - ".$this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;

        $data["class_name"] = $randomClassName;
        $data["user_name"] = $user;
        $data["subject_name"] = $class_batch;
        $data["started_by"] = $user_id;
       

        echo json_encode($data);

	}


	function endClass(){
		$postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_name = $request->class_name;

        $c_data = array("status" => "done");
        $this->db->where('class_name', $class_name)->update('sh_online_classes', $c_data);
        $data["success"] = true;
        echo json_encode($data);

	}

	function checkClasses(){
		$user_id = $this->session->userdata("userdata")["user_id"];
		$school_id = $this->session->userdata("userdata")["sh_id"];
		// $student = $this->db->select('s.id, s.class_id, s.batch_id, subjects, u.name as student_name')->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->join('sh_subject_groups sg', 'sg.id = s.subject_group_id', 'left')->where('s.id', $user_id)->get()->row();

		$student = $this->db->select('s.id, s.class_id, s.batch_id, u.name as student_name, concat(c.name," - ",b.name) as class_batch', false)->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->join('sh_classes c', 'c.id = s.class_id')->join('sh_batches b', 'b.id = s.batch_id')->where('s.id', $user_id)->get()->row();

		if($student){
			// if($student->subjects == null || $student->subjects == ""){
			// 	$student->subjects = $this->db->select('group_concat(id) as subjects')->from('sh_subjects')->where('class_id', $student->class_id)->where('batch_id', $student->batch_id)->where('deleted_at is null')->get()->row()->subjects;
			// }

			// $subjects = explode(",", $student->subjects);

			// $class = $this->db->select('s.name as subject,class_name,u.name as teacher')->from('sh_online_classes o')->join('sh_subjects s', 's.id = o.subject_id')->join('sh_users u','u.id = o.started_by')->where('o.class_id', $student->class_id)->where('o.batch_id', $student->batch_id)->where_in('subject_id', $subjects)->get()->row();

			$class = $this->db->select('class_name,u.name as teacher')->from('sh_online_classes o')->join('sh_users u','u.id = o.started_by')->where('o.class_id', $student->class_id)->where('o.batch_id', $student->batch_id)->where('o.status', 'ongoing')->where('o.deleted_at is null')->get()->row();


			if($class){
				$data["class_found"] = true;
				$data["class_name"] = $class->class_name;
				$data["teacher"] = $class->teacher;
				$data["subject"] = $student->class_batch;
				$data["student_name"] = $student->student_name;
			}else{
				$data["class_found"] = false;
			}
		}else{
			$data["class_found"] = false;
		}

		echo json_encode($data);

	}

}