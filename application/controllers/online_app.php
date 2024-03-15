<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Online_app extends CI_Controller {

	public function index(){
	    $user_id=NULL;
	    $auth_token=NULL;

        if(isset($_GET['rollno']) && $_GET['rollno']!='')
        {
            $user_id = $_GET['rollno'];
        }
        else
        {
            echo 'Roll Number is required!';die;
        }
		
        if(isset($_GET['api_token']) && $_GET['api_token']!='')
        {
            $auth_token = $_GET['api_token'];
        }
        else
        {
            echo 'API Token is required!';die;
        }
		

		$result = $this->db->select('school_id,id')->from('users')->where('rollno', $user_id)->where('api_token',$auth_token)->get()->result();
		if($result)
		{
			$school_id =  $result[0]->school_id;
			$u_id =  $result[0]->id;

			$student = $this->db->select('s.id, s.class_id, s.batch_id, u.name as student_name, concat(c.name," - ",b.name) as class_batch', false)->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->join('sh_classes c', 'c.id = s.class_id')->join('sh_batches b', 'b.id = s.batch_id')->where('s.id', $u_id)->get()->row();


			if($student)
			{
                
                
				$class = $this->db->select('class_name,u.name as teacher')->from('sh_online_classes o')->join('sh_users u','u.id = o.started_by')->where('o.class_id', $student->class_id)->where('o.batch_id', $student->batch_id)->where('o.status', 'ongoing')->where('o.deleted_at is null')->get()->row();
				if($class)
				{
					$data["class_found"] = true;
					$data["class_name"] = $class->class_name;
					$data["teacher"] = $class->teacher;
					$data["subject"] = $student->class_batch;
					$data["student_name"] = $student->student_name;
					$data["showJoinBtn"] = true;
				}
				else
				{
					echo 'Class not found!';die;
				}
			}
			else
			{
				echo 'Class not found!';die;
			}
			
		    $this->load->view('new_app_online_class_student', $data);	
		    
		}
		else
		{
			echo 'Invalid user!';die;
		}
	}

	function newAppCheckClasses($UID = false){


		$student = $this->db->select('s.id, s.class_id, s.batch_id, u.name as student_name, concat(c.name," - ",b.name) as class_batch', false)->from('sh_students_'.$school_id.' s')->join('sh_users u', 'u.id = s.id')->join('sh_classes c', 'c.id = s.class_id')->join('sh_batches b', 'b.id = s.batch_id')->where('s.id', $user_id)->get()->row();

		if($student){

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