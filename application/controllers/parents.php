<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parents extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function add() {
        $data["countries"] = $this->common_model->countries();
        $school_country=$this->db->select('sh_school.country,sh_countries.country_code as schools_country_code')->from('sh_school')->join('sh_countries', 'sh_school.country = sh_countries.country_name')->where('sh_school.id',$this->session->userdata("userdata")["sh_id"])->get()->row();
        if($school_country)
        {
            $data["school_country_code"]=$school_country->schools_country_code;
        }
        else
        {
            $data["school_country_code"]="";
        }
        $data["timezones"] = $this->common_model->time_zone_list();
        $this->load->view('parent/add', $data);
    }

    public function save() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        
        $result1 = $this->db->select('id')->from('sh_users')->where('email',$request->pEmail)->where('deleted_at',0)->get()->result();
        $result2 = $this->db->select('id')->from('sh_users')->where('contact',"'".$request->pPhone."'")->where('deleted_at',0)->get()->result();
        if (count($result1) > 0 && $request->pEmail != '') {
            echo json_encode(array("status" => "error", "message" => lang('guardian_email')));
        } else if (count($result2) > 0 && $request->pPhone != '') {
            echo json_encode(array("status" => "error", "message" => lang('phone_number_exist')));
        } else {
            if ($request->pAvatar == null) {
                $avatar = "profile.png";
            } else {
                $avatar = explode('uploads/user/', save_image($request->pAvatar))[1];
            }
            
            $db_country=0;
            $selected_country=$this->db->select('id')->from('sh_countries')->where('country_code',$request->pCountry)->get()->row();
            if($selected_country)
            {
                $db_country=$selected_country->id;
            }

            

            $parent = array(
                'avatar' => $avatar,
                'city' => $request->pCity,
                // 'country' => $request->pCountry,
                'country' => $db_country,
                'password' => md5("default"),
                'school_id' => $this->session->userdata("userdata")["sh_id"],
                'dob' => to_mysql_date($request->pDob),
                'email' => $request->pEmail,
                'gender' => $request->pGender,
                'ic_number' => $request->pIdNumber,
                'income' => $request->pIncome,
                'name' => $request->pName,
                'occupation' => $request->pOccupation,
                'contact' => $request->pPhone,
                'u_phone_number' => $request->u_phone_number,
                'parent_phone_code' => $request->parent_phone_code,
                'address' => $request->pStreet,
                'email_verified' => 'Y',
                'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
                'role_id' => PARENT_ROLE_ID,
                'guardian2_name' => $request->pName2,
                'guardian2_relation' => $request->relation,
                'guardian2_contact' => $request->pPhone2
            );
            $guardian_id = $this->common_model->insert('sh_users', $parent);


            //Send mail to student email address for account verification
            // Send mail to parent/ guardian email for account verification
            // if ($guardian_id > 0 && $request->pEmail != '') {

            //     $link = base_url() . $this->session->userdata('userdata')['sh_url'] . '/login/activation/' . $parent["token"];

            //     $subject = 'Account Activation';
            //     $data = array(
            //         "dear_sir" => lang('tmp_dear_sir'),
            //         "msg" => lang('tmp_info'),
            //         "thanks" => lang('tmp_thanks'),
            //         "poweredBy" => lang('tmp_power'),
            //         "unsub" => lang('tmp_unsub'),
            //         "link" => $link,
            //         "email" => $request->pEmail,
            //         "password" => "default"
            //     );
            //     $message = $this->load->view('email_templates/account_activation2.php', $data, TRUE);
            //     $this->email_modal->emailSend($request->pEmail, $message, $subject, "parent-signup");
            // }

            //dispaly success message after created new student
            echo json_encode(array("status" => "success", "message" => lang('new_parent')));
        }
    }

    public function all() {
        $data["parents"] = $this->parent_model->getAll();
        $this->load->view('parent/all', $data);
    }

    public function delete() {
        $id = decrypt($this->input->post("id"));
        $this->common_model->update_where("sh_users", array("id" => $id), array("deleted_at" => 1));
        $this->db->set('deleted_at',date('Y-m-d H:i:s'))->where('guardian_id',$id)->update('sh_student_guardians');
        $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('parent_delete')));
        echo "success";
        //redirect("employee/all","refresh");
    }

    public function view($id) {
        $id = decrypt($id);
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_attachments');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at IS NULL')->where('user_id', $id);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('title,file');
        $xcrud->fields('title,file');
        $xcrud->label('title', lang('lbl_tbl_title'))->label('file', lang('lbl_file'));
        $xcrud->change_type('file', 'file', '', array());
        $xcrud->pass_var('school_id', $this->session->userdata("userdata")["sh_id"]);
        $xcrud->pass_var('user_id', $id);
        $xcrud->replace_remove('soft_delete');
        $xcrud->table_name(lang('lbl_attachments'));
        $xcrud->load_view("view", "customview.php");
        $data['attachments'] = $xcrud->render();
        $data['parent'] = $this->parent_model->getParentByID($id);
        
        $children = array();
        $childs = $this->admin_model->dbSelect("*","student_guardians"," guardian_id=$id AND deleted_at IS NULL ");
        foreach($childs as $child){

            // $sql = "SELECT u.*,c.name as class_name, b.name as batch_name FROM sh_students_".$this->session->userdata("userdata")["sh_id"]." u INNER JOIN sh_batches b ON u.batch_id=b.id INNER JOIN sh_classes c ON u.class_id=c.id WHERE u.id='$child->student_id' AND u.deleted_at=0 ";

            $sql = "SELECT
                    u.*,
                    cr.student_id as id,
                    cr.class_id,
                    cr.batch_id,
                    c.name as class_name, 
                    b.name as batch_name 
                    FROM sh_users u
                    LEFT JOIN sh_student_class_relation cr ON cr.student_id=u.id 
                    INNER JOIN sh_batches b ON cr.batch_id=b.id 
                    INNER JOIN sh_classes c ON cr.class_id=c.id 
                    WHERE u.id='$child->student_id' AND cr.academic_year_id='$academic_year_id' AND cr.deleted_at is NULL ";
            
            $child_student = $this->admin_model->dbQuery($sql);

            if(count($child_student) > 0){
                array_push($children, $child_student[0]);
            }
            
        }
    
        $data['children'] = $children;
        $this->load->view('parent/view', $data);
    }

    public function edit($id) {
        $data["parent_id"] = decrypt($id);

        $data["countries"] = $this->common_model->countries();
        $data["selected_tab"] = 'personal';

        $this->load->view("parent/edit", $data);
    }

    public function getParent() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $data = $this->parent_model->getParentByIDEdit($request->parent_id);
        $data->dob = ($data->dob=='0000-00-00')?"":to_html_date($data->dob);
        echo json_encode($data);
    }

    public function update() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        if($request->u_phone_number=='' || $request->u_phone_number==NULL)
        {
            $request->contact="";
        }

        $result1 = $this->db->select('id')->from('sh_users')->where('email',$request->email)->where('deleted_at',0)->where('id <>',$request->id)->get()->result();
        $result2 = $this->db->select('id')->from('sh_users')->where('contact',$request->contact)->where('deleted_at',0)->where('id <>',$request->id)->get()->result();
        if (count($result1) > 0 && $request->email != '') {
            echo json_encode(array("status" => "error", "message" => lang('guardian_email')));
        } else if (count($result2) > 0 && $request->contact != '') {
            echo json_encode(array("status" => "error", "message" => lang('phone_number_exist')));
        }else{
            if (array_key_exists("chgParentImage", $request)) {
                $avatar = explode('uploads/user/', save_image($request->avatar))[1];
            } else {
                $avatar = $request->avatar;
            }
        
        $db_country=0;
        $selected_country=$this->db->select('id')->from('sh_countries')->where('country_code',$request->country)->get()->row();
        if($selected_country)
        {
            $db_country=$selected_country->id;
        }    
        
        $db_contact=NULL;
        $final_arr_contact=explode(' ',$request->contact);
        if(sizeof($final_arr_contact) > 0)
        {
            foreach($final_arr_contact as $ar_con)
            {
                $db_contact.=$ar_con;
            }
        }
        else
        {
            $db_contact=$request->contact;
        }
                
        $db_pnumber=NULL;
        $final_arr_pnumber=explode(' ',$request->u_phone_number);
        if(sizeof($final_arr_pnumber) > 0)
        {
            foreach($final_arr_pnumber as $ar_num)
            {
                $db_pnumber.=$ar_num;
            }
        }
        else
        {
            $db_pnumber=$request->u_phone_number;
        }
        
        
        if($db_pnumber=='' || $db_pnumber==NULL)
        {
            $db_contact="";
        }
        
            $parent = array(
                'avatar' => $avatar,
                'city' => $request->city,
                // 'country' => $request->country,
                'country' => $db_country,
                'school_id' => $this->session->userdata("userdata")["sh_id"],
                'dob' => to_mysql_date($request->dob),
                'email' => $request->email,
                'gender' => $request->gender,
                'ic_number' => $request->ic_number,
                'income' => $request->income,
                'name' => $request->name,
                'occupation' => $request->occupation,
                // 'contact' => $request->contact,
                'contact' => $db_contact,
                // 'u_phone_number' => $request->u_phone_number,
                'u_phone_number' => $db_pnumber,
                'parent_phone_code' => $request->parent_phone_code,
                'address' => $request->address,
                'guardian2_name' => $request->guardian2_name,
                'guardian2_relation' => $request->guardian2_relation,
                'guardian2_contact' => $request->guardian2_contact
            );

            $id = $request->id;
            $old_data = $this->db->select('email,token')->from('sh_users')->where('id',$id)->get()->row();
            $new_email = $request->email;
            if($old_data->email != $new_email && $new_email != ''){
                $data2 = $this->common_model->get_where("sh_users", "email", "'".$new_email."'")->result();
                if(count($data2)==0){
                    $link = base_url() .$this->session->userdata('userdata')['sh_url']. '/login/activation/' . $old_data->token;
                    $subject = 'Account Activation';
                    $data3 = array(
                     "dear_sir" => lang('tmp_dear_sir'),
                     "msg" => lang('tmp_info'),
                     "thanks" => lang('tmp_thanks'),
                     "poweredBy" => lang('tmp_power'),
                     "unsub" => lang('tmp_unsub'),
                     "link" => $link,
                     "email" => $new_email,
                     "password" => 'uvschool'
                 );
                    $message = $this->load->view('email_templates/account_activation2.php', $data3, TRUE);
                    $this->email_modal->emailSend($new_email, $message, $subject, "email-change");
                    $parent['password'] = md5('uvschool');
                    $parent['email_verified'] = 'N';
                }
            }


            $res1 = $this->common_model->update_where("sh_users", array("id" => $request->id), $parent);

            if ($res1) {
                echo json_encode(array("status" => "success", "message" => lang('parent_update')));
            } else {
                $this->session->set_flashdata('alert', array("status" => "success", "message" => lang('parent_not_update')));
            }
        }
    }

   public function child_list()
   {
     $this->load->view('parent/view_childlist');
   }
    public function get_parent_childlist()
  {
        
        $user_id = $this->session->userdata("userdata")["user_id"];

        $school_id = $this->session->userdata("userdata")["sh_id"];

        $childrens = $this->db->select('student_id,s.name,dob,rollno,status,gender,contact,c.name as class_name,b.name as batch_name,avatar')->from('sh_student_guardians sg')->join('sh_students_'.$school_id.' s', 'sg.student_id = s.id')->join('sh_classes c', 's.class_id = c.id')->join('sh_batches b', 's.batch_id = b.id')->where('guardian_id', $user_id)->get()->result();
        
       
        $data['student_ids'] = $childrens;
        

        echo json_encode($data);
}
  public function student_details($student_id)
  {
    
    $student_id = $student_id;
    $data['details'] = $this->parent_model->getUserProfileDetailStudent($student_id);
    
        
   

     $this->load->view('parent/student_details', $data);
    //echo $student_id;
  }

   public function getDaysOfWeek() {
        $school_id = $this->session->userdata("userdata")["sh_id"];
        //$days_of_week =  $this->admin_model->dbSelect("working_days","school"," id='$school_id' ")[0]->working_days; array("monday", "tuesday", "wednesday", "thursday", "friday", "saturday", "sunday");
        $days_of_week = array();
        $dddd = json_decode($this->admin_model->dbSelect("working_days", "school", " id='$school_id' ")[0]->working_days);
        foreach ($dddd as $row) {

            if ($row->val == 'true') {
                array_push($days_of_week, strtolower($row->label));
            }
        }
        //print_r($days_of_week);
        //die();
        $sorted_days_of_week = array();
        $start_day_of_week = strtolower($this->admin_model->dbSelect("start_day_of_the_week", "school", " id='$school_id' AND deleted_at=0 ")[0]->start_day_of_the_week);
        $index = array_search($start_day_of_week, $days_of_week);
        $loop1 = count($days_of_week) - $index;

        for ($i = 0; $i < $loop1; $i++) {
            array_push($sorted_days_of_week, $days_of_week[$index + $i]);
        }

        for ($j = 0; $j < $index; $j++) {
            array_push($sorted_days_of_week, $days_of_week[$j]);
        }

        //print_r($sorted_days_of_week);
        //die();
        return $sorted_days_of_week;
    }


  public function getTimetableOfDayForAllBatches() {
            $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);



        

        $school_id = $this->session->userdata("userdata")["sh_id"];
        $classbatcharray = $this->admin_model->dbSelect("class_id,batch_id,subject_group_id,academic_year_id","students_".$school_id," id='$request->student_id' ");
        $class_id = 0;
        $batch_id = 0;
        $subject_group_id = 0;
        $academic_year_id = 0;
        if(count($classbatcharray) > 0){
            $class_id = $classbatcharray[0]->class_id;
            $batch_id = $classbatcharray[0]->batch_id;
            $subject_group_id = $classbatcharray[0]->subject_group_id;
            $academic_year_id = $classbatcharray[0]->academic_year_id;
        }
        
        $subject_id = $this->admin_model->dbSelect("subjects","subject_groups"," class_id='$class_id' AND batch_id='$batch_id' AND id='$subject_group_id' AND deleted_at IS NULL AND school_id='$school_id' ");

        $subjects = $this->admin_model->dbSelect("id","subjects"," class_id='$class_id' AND batch_id='$batch_id' AND id IN (".$subject_id[0]->subjects.") AND deleted_at IS NULL AND school_id='$school_id' ");

        $subjects_new = array();
        if(count($subjects) > 0){
            foreach($subjects as $s){
                array_push($subjects_new, $s->id);
            }
        }
        
        $days_of_week = $this->getDaysOfWeek();
        $periods = $this->admin_model->dbSelect("*,time_format(start_time,'%h:%i %p') as start_time2,time_format(end_time,'%h:%i %p') as end_time2", "periods", " school_id='$school_id' AND class_id='$class_id' AND academic_year_id='$academic_year_id' AND batch_id='$batch_id' AND deleted_at IS NULL ORDER BY start_time ");
        
        if (count($periods) === 0) {
            $data = array("status" => "error", "message" => lang('periods_not_set'));
            echo json_encode($data);
            exit;
        }

        $final_arr = array();
        foreach ($days_of_week as $day) {
            foreach ($periods as $p) {
                $array = array(
                    "period_id" => $p->id,
                    "start_time" => $p->start_time,
                    "end_time" => $p->end_time,
                    "class_id" => $class_id,
                    "batch_id" => $batch_id,
                    "is_break" => $p->is_break,
                    "period_order" => $p->title,
                    "timetable_id" => NULL,
                    "teacher_id" => NULL,
                    "teacher_name" => NULL,
                    "day_of_week" => $day,
                    "sub_id" => NULL,
                    "room_no" => NULL,
                    "sub_name" => NULL,
                    "sub_code" => NULL
                );
                $final_arr[$day][$p->id] = $array;
            }
        }

        $daysofweekstring = null;
        foreach ($days_of_week as $_d) {
            $daysofweekstring .= "'" . $_d . "',";
        }


        $sql = "SELECT "
                . "p.id as period_id, "
                . "p.start_time as start_time, "
                . "p.end_time as end_time, "
                . "p.class_id as class_id, "
                . "p.batch_id as batch_id, "
                . "p.is_break as is_break, "
                . "p.title as period_order, "
                . "t.id as timetable_id, "
                . "t.day_of_week as day_of_week, "
                . "t.subject_id as sub_id, "
                . "t.room_no as room_no, "
                . "s.name as sub_name, "
                . "s.code as sub_code, "
                . "asn.teacher_id as teacher_id, "
                . "u.name as teacher_name "
                . "FROM sh_periods p "
                . "LEFT JOIN sh_timetable_new t ON p.id=t.period_id "
                . "INNER JOIN sh_subjects s ON t.subject_id=s.id "
                . "LEFT JOIN sh_assign_subjects asn ON t.subject_id=asn.subject_id and p.class_id = asn.class_id and p.batch_id = asn.batch_id "
                . "LEFT JOIN sh_users u ON u.id=asn.teacher_id "
                . "WHERE "
                . "p.school_id='$school_id' "
                . "AND p.class_id='$class_id' "
                . "AND p.batch_id='$batch_id' "
                . "AND p.deleted_at IS NULL "
                . "AND t.day_of_week in (" . rtrim($daysofweekstring, ",") . ") "
                . "ORDER BY p.start_time ASC ";

        $dbTimetables = $this->admin_model->dbQuery($sql);
         

        foreach ($dbTimetables as $tb) {
            $index1 = $tb->day_of_week;

            $index2 = $tb->period_id;

            $final_arr[$index1][$index2] = $tb;

        }

        foreach ($final_arr as $key => $value) {
            $i = 0;
            foreach ($value as $row_key => $row_value) {
                $final_arr[lang($key)][$i++] = $row_value;
                unset($final_arr[$key]);
                unset($final_arr[$key][$row_key]);
            }
        }

        if(count($final_arr) > 0){
            foreach($final_arr as $key=>$fr){
                foreach($fr as $key2=>$row){
                    if(isset($row->sub_id)){
                        if(!in_array($row->sub_id, $subjects_new)){
                            $arr = array(
                                "batch_id" => $row->batch_id,
                                "class_id" => $row->batch_id,
                                "day_of_week" => $row->day_of_week,
                                "end_time" => $row->end_time,
                                "is_break" => "Y",
                                "period_id" => null,
                                "period_order" => "Break",
                                "room_no" => null,
                                "start_time" => $row->start_time,
                                "sub_code" => null,
                                "sub_id" => null,
                                "sub_name" => null,
                                "teacher_id" => null,
                                "teacher_name" => null,
                                "timetable_id" => null
                            );
                            $final_arr[$key][$key2] = $arr;
                            //unset($final_arr[$key][$key2]);
                        }
                    }
                }
            }
        }


        $current_day_of_week = strtolower(date("l"));
        $final_arr2 = array();
        foreach($final_arr as $key=>$val){
            if(strtolower($key) == $current_day_of_week){
                $final_arr2 = $val;
            }
        }
        
        $data = array("status" => "success", "message" => "", "periods" => $periods, "timetables" => $final_arr2);
        echo json_encode($data);
          }


          public function resetPassword(){
            $postdata = file_get_contents("php://input");
            $request = json_decode($postdata);
            $parent_id = $request->parent_id;

            $res = $this->common_model->update_where("sh_users",array("id" => $parent_id), array("password" => md5('default')));

            if ($res) {
                $data = array("status" => "success", "message" => lang('parent_reset_password'));
            } else {
                $data = array("status" => "danger", "message" => lang('parent_reser_error'));
            }

            echo json_encode($data);

          }


}
  