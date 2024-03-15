<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Assignsubjects extends CI_Controller {

    

    public function index() {
        $this->session->sess_destroy("userdata");
        redirect(site_url('login'));
    }
    
    public function show(){
        $this->load->view("assignsubjects/show");
    }
    
    public function getSubjsThrs(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $sql = "SELECT 
            ass.*,
            s.id as subject_id_orginal,
            s.name,
            u.name as teacher_name,
            u.avatar as teacher_avatar, 
            uu.name as assistant_name,
            uu.avatar as assistant_avatar
            FROM  sh_subjects s 
            LEFT JOIN sh_assign_subjects ass ON s.id = ass.subject_id and s.class_id = ass.class_id and s.batch_id = ass.batch_id
            LEFT JOIN sh_users u ON ass.teacher_id=u.id 
            LEFT JOIN sh_users uu ON ass.assistant_id=uu.id 
            WHERE s.class_id = '$request->class_id' 
            AND s.batch_id = '$request->batch_id' 
            AND s.deleted_at IS NULL 
            AND s.school_id='$school_id'
            AND s.academic_year_id='$academic_year_id' ";
        $subjects = $this->admin_model->dbQuery($sql);
        //print_r($result); die();
        // $sql2 = "Select u.name as thr_name, sh.* from sh_assign_subjects sh INNER JOIN sh_users u ON sh.teacher_id=u.id Where sh.school_id='$school_id' AND sh.class_id='$request->class_id' AND sh.batch_id='$request->batch_id' AND sh.deleted_at IS NULL AND u.deleted_at = 0";
        // $isExists = $this->admin_model->dbQuery($sql2);
        
        //$subjects = $this->admin_model->dbSelect("*", "subjects", " school_id='$school_id' AND class_id='$request->class_id' AND batch_id='$request->batch_id' AND deleted_at IS NULL ");
        //$sql = "SELECT * FROM sh_assign_subjects s INNER JOIN sh_users u ON s.teacher_id=u.id INNER JOIN sh_users uu ON s.assistant_id=uu.id WHERE s.batch_id='$request->batch_id' AND s.class_id='$request->class_id'";
        //$subjects = $this->db->select('s.*,a.teacher_id,u.name,u.avatar')->from('sh_subjects s')->join('sh_assign_subjects a','s.id = a.subject_id','left')->join('sh_users u','FIND_IN_SET(u.id , a.teacher_id)','left')->where('s.batch_id', $request->batch_id)->where('s.class_id',$request->class_id)->where('a.batch_id',$request->batch_id)->group_by('s.id')->get()->result();

        /*foreach($subjects as $ub){
            $ub->teacher_id = explode(",",$ub->teacher_id);
            $ub->names = explode(",",$ub->names);
            $ub->avatars = explode(",",$ub->avatars);
        }*/

        $teacher_dept_id = get_teacher_dept_id();
        
        $sql = "Select u.id as id,u.name as name,d.id as dept_id,d.name as dept_name from sh_users u inner join sh_departments d on u.department_id=d.id Where u.school_id='$school_id' AND u.status='0' AND u.deleted_at=0 AND u.role_id=".EMPLOYEE_ROLE_ID." AND d.id='$teacher_dept_id' order by name";
        $teachers = $this->admin_model->dbQuery($sql);
        
        $data = array("teachers"=>$teachers,"subjects"=>$subjects);
        echo json_encode($data);
    }
    
    public function save(){
        $school_id = $this->session->userdata('userdata')['sh_id'];
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $class_name = $this->admin_model->dbSelect("name","classes"," id='$request->class_id' AND deleted_at IS NULL ")[0]->name;
        $batch_name = $this->admin_model->dbSelect("name","batches"," id='$request->batch_id' AND deleted_at IS NULL ")[0]->name;
        $subject_name = $this->admin_model->dbSelect("name","subjects"," id='$request->subject_id' AND deleted_at IS NULL ")[0]->name;
        
        $sql = "SELECT s.*,u.name as teacher_name, uu.name assistant_name FROM sh_assign_subjects s LEFT JOIN sh_users u ON s.teacher_id=u.id LEFT JOIN sh_users uu ON s.assistant_id=uu.id WHERE s.class_id='$request->class_id' AND s.batch_id='$request->batch_id' AND s.subject_id='$request->subject_id' AND s.school_id='$school_id' AND s.deleted_at IS NULL";
        $record = $this->admin_model->dbQuery($sql);
        if(count($record) > 0){
            // subject already assigned
            $name = "";
            if($request->option == 1) {
                $sql = "UPDATE sh_assign_subjects SET teacher_id=$request->user_id WHERE class_id=$request->class_id AND batch_id=$request->batch_id AND subject_id=$request->subject_id AND deleted_at IS NULL";
                $name = $record[0]->teacher_name;
            } else if($request->option == 2){
                $sql = "UPDATE sh_assign_subjects SET assistant_id=$request->user_id WHERE class_id=$request->class_id AND batch_id=$request->batch_id AND subject_id=$request->subject_id AND deleted_at IS NULL";
                $name = $record[0]->assistant_name;
            }
            $this->db->query($sql);
            
            $temp1['id'][0] = $request->user_id;
            $temp1['keyword'] = 'subject_assigned_name';
            $names1 = array('class'=>$class_name,'section'=>$batch_name,'subject'=>$subject_name,'teacher'=>$name);
            $temp1['data'] = $names1;
            $notifications[] = $temp1;
            $response = array("status"=>"success","message"=>"Subject assignment updated successfully!","notifications"=>$notifications);
        } else {
            // subject not assigned yet
            $temp['id'][0] = $request->user_id;
            $temp['keyword'] = 'subject_assigned';
            $names = array('class'=>$class_name,'section'=>$batch_name,'subject'=>$subject_name);
            $temp['data'] = $names;
            $notifications[] = $temp;
            if($request->option == 1){
                $data = array('school_id' => $school_id, 'teacher_id' => $request->user_id, 'subject_id' => $request->subject_id, 'class_id' => $request->class_id, 'batch_id' => $request->batch_id);
            } else if($request->option == 2){
                $data = array('school_id' => $school_id, 'assistant_id' => $request->user_id, 'subject_id' => $request->subject_id, 'class_id' => $request->class_id, 'batch_id' => $request->batch_id);
            }
            $this->common_model->insert("sh_assign_subjects", $data);
            $response = array("status"=>"success","message"=>"Subject assign successfully!","notifications"=>$notifications);
        }
        //$this->db->replace('sh_assign_subjects', $data1);
        //$notifications = $this->common_model->saveSubjectAssingments($request);
        //$notifications = array();
        //$data = array("status"=>"success","message"=>"Subject assignment successfully","notifications"=>$notifications);
        echo json_encode($response);
        
    }
}