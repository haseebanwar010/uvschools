<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Online_admission extends CI_Controller {

    public function index() {
        if(isset($_GET["tag"]) && $_GET["tag"]!='')
        {
            $url = $_GET["tag"];   
            $result = $this->admin_model->dbSelect("id,logo,language","school"," url='$url' ");
    		if(count($result) > 0){
    			$admins = $this->admin_model->dbSelect("id","users"," school_id='".$result[0]->id."' AND role_id=".ADMIN_ROLE_ID." AND deleted_at=0 ");
    			$language = "english";
    			if(count($admins) > 0){
    				$res = $this->admin_model->dbSelect("language","users"," id=".$admins[0]->id." ");
    				if(count($res) > 0){
    					$language = $res[0]->language;
    				}
    			}
    			$data["school_id"] = $result[0]->id;
    			$data['logo'] = $result[0]->logo;
    			$data['language'] = $language;
    			
    			$sql = "SELECT s.*,t.id as attachement_id, t.file, t.type FROM sh_online_admission_settings s LEFT JOIN sh_online_admission_settings_attachments t ON s.id=t.online_admission_settings_id WHERE s.school_id='".$data["school_id"]."' AND s.deleted_at IS NULL AND t.deleted_at IS NULL ";
    			$obj = $this->admin_model->dbQuery($sql);
    			if(count($obj) > 0) {
    				$new_data = new stdClass();
    				$new_data->id = $obj[0]->id;
    				$new_data->school_id = $obj[0]->school_id;
    				$new_data->academic_year_id = $obj[0]->academic_year_id;
    				$new_data->description = $obj[0]->description;
    				$new_data->attachments = array();
    				if(count($obj) >= 1){
    					foreach($obj as $d){
    						if($d->attachement_id != null) {
    							$arr = array(
    								"id" => $d->attachement_id,
    								"file" => $d->file,
    								"type" => $d->type
    							);
    							array_push($new_data->attachments, $arr);
    						}	
    					}
    				}
    				$obj = $new_data;
    			}
    			$data["term_conditions"] = $obj;
    			
    			$school_country=$this->db->select('sh_school.country,sh_countries.country_code as schools_country_code')->from('sh_school')->join('sh_countries', 'sh_school.country = sh_countries.country_name')->where('sh_school.id',$this->session->userdata("userdata")["sh_id"])->get()->row();
                if($school_country)
                {
                    $data["school_country_code"]=$school_country->schools_country_code;
                }
                else
                {
                    $data["school_country_code"]="";
                }
    			
    			$this->load->view("online_admission", $data);
    		} else {
    			return redirect('/');
    		}
        }
        else
        {
          return redirect('/');
        }
		

    }
    
	public function getAcademicYears(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $data = $this->admin_model->dbSelect("*","academic_years"," school_id='$school_id' AND deleted_at IS NULL ");
        $academic_year_id = "-1";
        if(isset($this->session->userdata("userdata")["academic_year"]) && !empty($this->session->userdata("userdata")["academic_year"])){
            $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        }
        $response = array('current_academic_year_id'=>$academic_year_id,"data"=>$data);
        echo json_encode($response);
    }

    public function getCountries() {
        $res = $this->admin_model->dbSelect("*", "countries", " 1 ");
        echo json_encode($res);
    }


    public function getClasses(){
    	$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$academic_year_id = 0;
		$academicyeararr = $this->admin_model->dbSelect("*","academic_years"," school_id='$request->school_id' AND is_active='Y' AND deleted_at IS NULL ");
		if(count($academicyeararr) > 0){
			$academic_year_id = $academicyeararr[0]->id;
		}
		$res = $this->admin_model->dbSelect("*", "classes", " school_id='$request->school_id' AND academic_year_id='$academic_year_id' ");
    	echo json_encode($res);
    }

    public function insertData(){
    	$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		
		if($request->selectedOption == "opt1" || $request->selectedOption == "opt2"){
			$schoolurl = $this->admin_model->dbSelect("url","school"," id='$request->school_id' ")[0]->url;
			$result = $this->admin_model->dbSelect("*", "online_admissions", "parent_email='$request->parent_email' AND std_email='$request->child_email' AND std_full_name='$request->child_name' AND std_gender='$request->child_gender' ");
			$response = array();
			if (count($result) > 0 ) {
				$response = array("status"=>"danger","message"=> lang('online_admission_status'));
			} else { 
				$parent_image = "profile.png";
				$child_image = null;
				$academic_year_id = 0;
				$academicyeararr = $this->admin_model->dbSelect("*","academic_years"," school_id='$request->school_id' AND is_active='Y' AND deleted_at IS NULL ");
				if(count($academicyeararr) > 0){
					$academic_year_id = $academicyeararr[0]->id;
				}
				if(!empty($request->child_avatar)) {
					$filename2 = save_image($request->child_avatar);
					$arr = explode("/", $filename2);
					$child_image = $arr[2];
				} 

				/*if(empty($request->parent_id) || $request->parent_id == ""){
					if(!empty($request->avatar)) {
						$filename = save_image($request->avatar);
						$arr = explode("/", $filename);
						$parent_image = $arr[2];
					}
				} else {
					$parent_image = $request->avatar;
				}*/

				$rollno = "";
				$is_rollno_exists = false;
				do{
					$rollno = "std".$request->school_id.generateRandomString(4);
					$arr = $this->admin_model->dbSelect("*","users","rollno");
					if(count($arr) > 0){
						$is_rollno_exists = false;
					} else {
						$is_rollno_exists = true;
					}
				} while ($is_rollno_exists);
				$data = array(
					'parent_avatar' => $parent_image,
					'std_avatar' => $child_image,
					'parent_name' => $request->parent_name,
					'parent_gender' => $request->parent_gender,
					'parent_occupation' => $request->parent_occupation,
					'parent_income' => $request->parent_income,
					'parent_email' => $request->parent_email,
					'parent_phone_no' => $request->parent_phone_no,
					'u_phone_number' => $request->parent_phone_only,
					'parent_phone_code' => $request->parent_phone_code,
					'parent_ic_no' => $request->parent_ic_number,
					'parent_country_id' => $request->parent_country_id,
					'parent_city' => $request->parent_city,
					'second_parent_name' => $request->s_g_name,
					'second_parent_relation' => $request->s_g_relation,
					'second_parent_phone_no' => $request->s_g_phone_no,
					'second_phone_number' => $request->s_g_phone_only,
					's_g_phone_code' => $request->s_g_phone_code,
					'std_religion' => $request->child_religion,
					'std_full_name' => $request->child_name,
					'std_gender' => $request->child_gender,
					'std_dob' => to_mysql_date($request->child_dob),
					'std_blood_group' => $request->child_blood_group,
					'std_birth_place' => $request->child_birth_place,
					'std_country_id' => $request->child_nationality_id,
					'std_language' => $request->child_language,
					'std_nic' => $request->child_nic,
					'std_class_id' => $request->child_class_id,
					'std_email' => $request->child_email,
					'std_phone_no' => $request->child_phone_no,
					'std_phone_number' => $request->child_phone_only,
					'child_phone_code' => $request->child_phone_code,
					'std_city' => $request->child_city,
					'std_address' => $request->child_address,
					'school_id' => $request->school_id,
					'academic_year_id' => $academic_year_id,
					'std_rollno' => $rollno,
					"is_terms_and_conditions_agreed" => $request->is_terms_and_conditions_agreed
				);
				if($request->selectedOption == "opt1"){
					$data['parent_street'] = $request->parent_street;
					$data['parent_dob'] = to_mysql_date($request->parent_dob);
				} else if($request->selectedOption == "opt2"){
					$data['parent_dob'] = $request->parent_dob;
				}
				$school_name = $this->db->select('name')->from('sh_school')->where('id', $request->school_id)->get()->row()->name;
				$response = $this->admin_model->dbInsert("sh_online_admissions", $data);
				$token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));
				if(empty($request->parent_id) || $request->parent_id == "") {
					$res = $this->admin_model->dbSelect("*", "users", " email='$request->parent_email' ");
					if (count($res) == 0){
						$parent_info = array(
							'avatar' => $parent_image,
							'school_id' => $request->school_id,
							'name' => $request->parent_name,
							'gender' => $request->parent_gender,
							'dob' => isset($request->parent_dob)?to_mysql_date($request->parent_dob):"",
							'occupation' => $request->parent_occupation,
							'income' => $request->parent_income,
							'email' => $request->parent_email,
							'contact' => $request->parent_phone_no,
							'mobile_phone' => $request->parent_phone_no,
							'office_phone' => $request->parent_phone_no,
							'u_phone_number' => $request->parent_phone_only,
							'parent_phone_code' => $request->parent_phone_code,
							'ic_number' => $request->parent_ic_number,
							'country' => $request->parent_country_id,
							'nationality' => $request->parent_country_id,
							'city' => $request->parent_city,
							'guardian2_name' => $request->s_g_name,
							'guardian2_relation' => $request->s_g_relation,
							'guardian2_contact' => $request->s_g_phone_no,
							'guardain2_phone_number' => $request->s_g_phone_only,
							's_g_phone_code' => $request->s_g_phone_code,
							'password' => md5("uvschools"),
							'role_id' => 2,
							'token' => $token,
							'recovery_key' => $token,
							'permissions' => json_encode($request->permissions),
							'email_verified' => 'Y'
						);
						$this->admin_model->dbInsert("sh_users", $parent_info);
					}
					$link = base_url() . $schoolurl . '/login/activation/' . $token;
					$subject = 'Account Activation';
					$sdata = array(
						"dear_sir" => lang('tmp_dear_sir'),
						"msg" => lang('tmp_info'),
						"thanks" => lang('tmp_thanks'),
						"poweredBy" => lang('tmp_power'),
						"unsub" => lang('tmp_unsub'),
						"link" => $link,
						"school_name" => $school_name
					);
					$message = $this->load->view('email_templates/account_activation.php', $sdata, TRUE);
					$this->email_modal->emailSend($request->parent_email, $message, $subject, "parent-signup-onlineadmission");
				}
				$response = array("status"=>"success","message"=>lang("online_admission_application_send"));
			}
		}
		echo json_encode($response);
	}
	
	public function getChildPendingApplication(){
		$email = $this->session->userdata("userdata")["email"];
		$data = $this->admin_model->dbSelect("*","online_admissions"," parent_email='$email' AND deleted_at IS NULL AND is_admit='no' ");
		echo json_encode($data);
	}

	public function get_student_online_form(){
		$id = $this->input->post("id");
		$sql = "SELECT oa.parent_avatar,
			oa.parent_name,
			oa.parent_gender,
			oa.parent_dob,
			oa.parent_occupation,
			oa.parent_income,
			oa.parent_email,
			oa.parent_phone_no,
			oa.parent_street,
			oa.parent_ic_no,
			pc.country_name as parent_country_name,
			oa.parent_city,
			oa.second_parent_name as secondparentname,
			oa.second_parent_relation as secondparentrelation,
			oa.second_parent_phone_no as secondparentphoneno,
			oa.std_avatar,
			oa.std_religion,
			oa.std_full_name,
			oa.std_gender,
			oa.std_dob,
			oa.std_blood_group,
			oa.std_birth_place,
			oa.std_language,
			oa.std_nic,
			sc.country_name as std_country_name,
			c.name as class_name,
			oa.std_email,
			oa.std_phone_no,
			oa.std_city,
			oa.std_address
			FROM sh_online_admissions oa 
			LEFT JOIN sh_countries pc ON oa.parent_country_id=pc.id 
			LEFT JOIN sh_countries sc ON oa.std_country_id=sc.id 
			INNER JOIN sh_classes c ON oa.std_class_id=c.id 
			WHERE oa.id='$id' ";
		$data = $this->admin_model->dbQuery($sql);
		$direction = "style='margin-left: 20%; text-align: left;'";
		if($this->session->userdata("site_lang") != 'english'){
			$direction = "style='margin-right: 20%; text-align: right;'";
		} 
		$response = "<div class='row result_card_container' ".$direction."><div class='col-md-12'><h4 class='box-title'>".lang("parent_personal_details")."</h4><hr style='border: 1px solid;'/></div>";
		foreach($data[0] as $key=>$d){
			if($key == 'parent_avatar'){
				if(is_null($d) || empty($d) || $d == ""){
					$response .= "<div class='col-md-6'><div class='form-group'><img src='uploads/user/profile.png' width='100px'' /></div></div>";
				} else {
					$response .= "<div class='col-md-6'><div class='form-group'><img src='uploads/user/".$d."' width='100px'' /></div></div>";
				}
			} else if($key == 'std_avatar'){
				$response .= "<div class='col-md-12'><h4 class='box-title'>".lang("child_personal_details")."</h4><hr style='border: 1px solid;'/></div>";
				if(is_null($d) || empty($d) || $d == ""){
					$response .= "<div class='col-md-6'><div class='form-group'><img src='uploads/user/profile.png' width='100px'' /></div></div>";
				} else {
					$response .= "<div class='col-md-6'><div class='form-group'><img src='uploads/user/".$d."' width='100px'' /></div></div>";
				}
			} else {
				if($key == 'secondparentname'){
					$response .= "<div class='col-md-12'><h4 class='box-title'>".lang("secondary_guardian_details")."</h4><hr style='border: 1px solid;'/></div>";
				} else if($key == 'std_email'){
					$response .= "<div class='col-md-12'><h4 class='box-title'>".lang("contact_details")."</h4><hr style='border: 1px solid;'/></div>";
				}
				$response .= "<div class='col-md-6'><div class='form-group'><label>".$key."</label><p>".$d."</p></div></div>";
			}
		}
		$response .= "</div>";
		$tags["parent_gender"] = lang("lbl_gender");
		$tags["parent_name"] = lang("lbl_parent");
		$tags["parent_dob"] = lang("lbl_dob");
		$tags["parent_occupation"] = lang("lbl_occupation");
		$tags["parent_income"] = lang("lbl_income");
		$tags["parent_email"] = lang("imp_parent_email");
		$tags["parent_phone_no"] = lang("imp_parent_contact");
		$tags["parent_street"] = lang("lbl_street");
		$tags["parent_ic_no"] = lang("heading_ic_number");
		$tags["parent_city"] = lang("imp_city");
		$tags["secondparentname"] = lang("lbl_parent");
		$tags["secondparentrelation"] = lang("lbl_relation");
		$tags["secondparentphoneno"] = lang("imp_parent_contact");
		$tags["std_religion"] = lang("imp_std_religion");
		$tags["std_full_name"] = lang("lbl_full_name");
		$tags["std_gender"] = lang("lbl_gender");
		$tags["std_dob"] = lang("lbl_dob");
		$tags["std_blood_group"] = lang("blood_group");
		$tags["std_birth_place"] = lang("birth_place");
		$tags["std_language"] = lang("imp_std_lang");
		$tags["std_nic"] = lang("national_number");
		$tags["std_email"] = lang("lbl_email");
		$tags["std_phone_no"] = lang("lbl_phone");
		$tags["std_city"] = lang("lbl_city");
		$tags["std_address"] = lang("imp_address");
		//$tags["status"] = "Admission Status";
		//$tags["is_admit"] = "Admit";
		$tags["parent_country_name"] = lang("lbl_country");
		$tags["std_country_name"] = lang("lbl_country");
		$tags["class_name"] = lang("lbl_class");
		foreach ($tags as $key => $value) {
			$response = str_replace($key, $tags[$key], $response);
		}
		echo $response;
	}

	public function admit_students(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$school_id = $this->session->userdata("userdata")["sh_id"];
		$academic_year_id = $this->session->userdata("userdata")["academic_year"];

		$response = array();
		$data = array();
		foreach($request->student_ids as $student_id) {
			$rollno = $this->admin_model->dbSelect("*","online_admissions"," id='$student_id' ")[0]->std_rollno;
			$result = $this->admin_model->dbSelect("*", "users", " rollno='$rollno' AND role_id=" . STUDENT_ROLE_ID . " AND school_id='$school_id' AND deleted_at = 0 ");
			$result3 = 0;
			
			$adm = to_mysql_date($request->admission_date);
			$date_now = date("Y-m-d");
			if ($adm > $date_now) {
				$result3 = 1;
			}

			if (count($result) > 0) {
				$data[] = array("status" => "error", "message" => lang('student_exist'));
			} else if ($result3 > 0) {
				$data[] = array("status" => "error", "message" => lang('adm_date_error'));
			} else {

				$record = $this->admin_model->dbSelect("*","online_admissions"," id='$student_id' ")[0];
				$emailExists = 0;
				if(!empty($record->std_email)){
					$emailExists = count($this->admin_model->dbSelect("*","users"," email='$record->std_email' "));
				}
				
				if($emailExists > 0){
					$data[] = array("status" => "error", "message" => lang('student_email'));
				} else {
					$avatar = 'profile.png';
					if(!is_null($record->std_avatar)){
						$avatar = $record->std_avatar;
					}
					$joining_date = to_mysql_date($request->admission_date);
					if($joining_date == ""){
						$joining_date = date("Y-m-d");
					} 

					$student = array(
						'address' => $record->std_address,
						'avatar' => $avatar,
						'password' => md5("default"),
						'school_id' => $school_id,
						'birthplace' => $record->std_birth_place,
						'blood' => $record->std_blood_group,
						'city' => $record->std_city,
						'dob' => $record->std_dob,
						'email' => $record->std_email,
						'name' => $record->std_full_name,
						'gender' => $record->std_gender,
						'language' => $record->std_language,
						'nationality' => $record->std_country_id,
						'contact' => $record->std_phone_no,
						'mobile_phone' => $record->std_phone_no,
						'u_phone_number' => $record->std_phone_number,
						'parent_phone_code' => $record->child_phone_code,
						'country'=> $record->std_country_id,
						'religion' => $record->std_religion,
						'rollno' => $rollno,
						'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
						'recovery_key' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
						'role_id' => STUDENT_ROLE_ID,
						// 'discount_id' => empty($request->discount_id)?0:$request->discount_id,
						'email_verified' => 'Y',
						'office_phone' => '',
						'joining_date' => $joining_date,
						'ic_number' => $record->std_nic
					);
					$std_id = $this->common_model->insert('sh_users', $student);
					$guardian_id = $this->admin_model->dbSelect("id","users"," email='$record->parent_email' ")[0]->id;
					$std_guradian_relation = array(
						'student_id' => $std_id,
						'guardian_id' => $guardian_id,
						'relation' => $record->second_parent_relation
					);
					$this->common_model->insert('sh_student_guardians', $std_guradian_relation);

					//----- Start::student class relationship for record ------//
					$std_class_relation_data = array(
						"student_id"=>$std_id,
						"class_id"=>$request->class_id,
						"batch_id"=>$request->batch_id,
						"subject_group_id"=>($request->subject_group_id == "") ? null : $request->subject_group_id,
						"academic_year_id"=>$academic_year_id,
						"school_id"=>$school_id,
						'discount_id' => empty($request->discount_id)?0:$request->discount_id
					);
					$this->common_model->insert("sh_student_class_relation", $std_class_relation_data);
					//----- End::student class relationship for record ------//

					$student_shift_data = array(
						'student_id' => $std_id,
						'class_id' => $request->class_id,
						'batch_id' => $request->batch_id,
						'academic_year_id' => $academic_year_id
					);
					$this->db->insert('sh_student_shifts', $student_shift_data);

					$this->common_model->update_where("sh_online_admissions", array("id"=>$student_id), array("is_admit"=>"yes"));
					//Send mail to student email address for account verification
					if ($std_id > 0 && $record->std_email != '') {
						//$token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));
						$link = base_url() . $this->session->userdata('userdata')['sh_url'] . '/login/activation/' . $student["token"];
						$subject = 'Account Activation';
						$data2 = array(
							"dear_sir" => lang('tmp_dear_sir'),
							"msg" => lang('tmp_info'),
							"thanks" => lang('tmp_thanks'),
							"poweredBy" => lang('tmp_power'),
							"unsub" => lang('tmp_unsub'),
							"link" => $link,
							"email" => $record->std_email ,
							"password" => "default"
						);
						$message = $this->load->view('email_templates/account_activation2.php', $data2, TRUE);
						$this->email_modal->emailSend($record->std_email, $message, $subject, "student-signup");
					}
					$data[] = array("status" => "success", "message" => "Student admit to school successfully!");
				}
			}
		}
		$response = array("status" => "success", "message"=>"student shifted successfully", "data"=>$data);
		echo json_encode($response);
	}


	public function isParentExists(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);

		$email = $request->email;
		$school_id = $request->school_id;
		$res = $this->admin_model->dbSelect("*","users"," email='$email' AND deleted_at=0 AND role_id=".PARENT_ROLE_ID." AND school_id='$school_id' ");
		if(count($res) > 0){
			$parent_id = $res[0]->id;
			$sql = "SELECT s.*, c.name as class_name, b.name as batch_name FROM sh_students_".$school_id." s INNER JOIN sh_classes c ON s.class_id=c.id INNER JOIN sh_batches b ON s.batch_id=b.id WHERE s.id IN (SELECT student_id FROM sh_student_guardians WHERE guardian_id=$parent_id AND deleted_at IS NULL) AND s.deleted_at=0 AND s.role_id=".STUDENT_ROLE_ID." ";
			$childrens = $this->admin_model->dbQuery($sql);
			$res[0]->childrens = $childrens;
			$response = array("status"=>"danger","message"=>lang("parent_email_already_exisit_choose_another_one"), "data"=>$res[0]);
		} else {
			$response = array("status"=>"success","message"=>"no data found");
		}
		echo json_encode($response);
	}

	public function checkParentEmailIsExists(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$array = $this->admin_model->dbSelect("*","users"," email='$request->email' AND deleted_at=0 AND role_id=".PARENT_ROLE_ID." ");
		$response = array();
		if(count($array) > 0){
			$array[0]->dob = to_html_date($array[0]->dob);
			$response = array("status"=>"error","message"=>"Parent Already Exists!", "data"=> $array[0]);
		} else {
			$response = array("status"=>"success","message"=>"Welcome! As a new parent!", "data"=> array());
		}
		echo json_encode($response);
	}

	public function get_application(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$sql = "SELECT o.* FROM sh_online_admissions o INNER JOIN sh_countries pc ON o.parent_country_id=pc.id INNER JOIN sh_countries sc ON o.std_country_id=sc.id INNER JOIN sh_classes c ON o.std_class_id=c.id INNER JOIN sh_school s ON o.school_id=s.id INNER JOIN sh_academic_years a ON o.academic_year_id=a.id WHERE o.id='$request->id' ";
		$data = $this->admin_model->dbQuery($sql);
		if(count($data) > 0){
			$data = $data[0];
			$data->parent_income = intval($data->parent_income);
			$data->parent_dob = to_html_date($data->parent_dob);
			$data->std_dob = to_html_date($data->std_dob);
		}
		
		echo json_encode($data);
	}

	public function update_application(){
		$postdata = file_get_contents("php://input");
		$request = (array)json_decode($postdata);
		if($request["is_image_uploaded"]){
			$filename = save_image($request["std_avatar"]);
			$arr = explode("/", $filename);
			$request["std_avatar"] = $arr[2];
		}
		unset($request["is_image_uploaded"]);
		$request["parent_dob"] = to_mysql_date($request["parent_dob"]);
		$request["std_dob"] = to_mysql_date($request["std_dob"]);
		$where = array("id"=>$request["id"]);
		$data = $request;
		$this->common_model->update_where("sh_online_admissions",$where, $data);
		echo "success";
	}

	public function softDelete(){
		$where = array("id"=>$this->input->post("id"));
		$data = array("deleted_at"=> date("Y-m-d h:i:s"));
		$this->common_model->update_where("sh_online_admissions",$where,$data);
		echo "success";
	}

	public function upload(){
        if ( !empty( $_FILES ) ) {
            $tempPath = $_FILES['file']['tmp_name'];
            $arr = explode(".", $_FILES["file"]["name"]);
            $extension = $arr[count($arr) - 1];
            // $newfilename = $_FILES['file']['name'].'.'.$extension;
            // $uploadPath = 'uploads/attachment/'.$newfilename;
            // move_uploaded_file( $tempPath, $uploadPath );
            // $answer = array( 'status' => 'success', 'uploaded_file_name' => $newfilename );
            // $json = json_encode( $answer );
            // echo $json;
            
            if($extension == 'pdf' || $extension == 'jpg' || $extension == 'doc' || $extension == 'docx' || $extension == 'txt' || $extension == 'png' || $extension == 'jpeg'){

			      	// $newfilename = $_FILES['file']['name'].'.'.$extension;
			      	$newfilename = $_FILES['file']['name'];
			      	// print_r($newfilename);die();
			    	$uploadPath = 'uploads/attachment/'.$newfilename;
			    	move_uploaded_file( $tempPath, $uploadPath );
			    	$answer = array( 'status' => 'success', 'uploaded_file_name' => $newfilename );
			    	$json = json_encode( $answer );
			    	echo $json;

		     	}
		      	else{
		      		$answer = array( 'status' => 'error', 'message' => "Sorry Not allowed!" );
		            $json = json_encode( $answer );
		            echo $json; die();
		      	}
		      	
        } else {
            $answer = array( 'status' => 'error', 'uploaded_file_name' => "" );
            $json = json_encode( $answer );
            echo $json;
        }
	}
	
	public function save_settings(){
		$postdata = file_get_contents("php://input");
		$request = json_decode($postdata);
		$school_id = $this->session->userdata("userdata")["sh_id"];
		$academic_year_id = $this->session->userdata("userdata")["academic_year"];
		$response = "";

		$exists = $this->admin_model->dbSelect("*","online_admission_settings", " school_id='$school_id' AND deleted_at IS NULL ");
		if(count($exists) > 0){
			$this->common_model->update_where("sh_online_admission_settings",array("school_id"=>$school_id),array("description"=>$request->description,"updated_by"=>$this->session->userdata("userdata")["user_id"]));
			if(count($request->attachments) > 0){
				$res = $this->common_model->update_where("sh_online_admission_settings_attachments",array("school_id"=>$school_id),array("deleted_at" => date("Y-m-d h:i:s")));
				if($res){
					foreach($request->attachments as $att){
						$arr = array(
							"online_admission_settings_id" => $exists[0]->id,
							"file" => $att,
							"type" => explode(".", $att)[1],
							"school_id" => $school_id
						);
						$this->admin_model->dbInsert("sh_online_admission_settings_attachments", $arr);
					}
				}	
			}
			$response = array("status"=>"success","message"=>"School terms & conditions has been updated successfully!");
		} else {
			$settings = array(
				"school_id" => $school_id,
				"academic_year_id" => $academic_year_id,
				"description" => $request->description,
				"updated_by" => $this->session->userdata("userdata")["user_id"]
			);
			$settings_id = $this->admin_model->dbInsert("sh_online_admission_settings", $settings);
			foreach($request->attachments as $att){
				$arr = array(
					"online_admission_settings_id" => $settings_id,
					"file" => $att,
					"type" => explode(".", $att)[1],
					"school_id" => $school_id
				);
				$this->admin_model->dbInsert("sh_online_admission_settings_attachments", $arr);
			}
			$response = array("status"=>"success","message"=>"School terms & conditions has been saved successfully!");
		}
		echo json_encode($response);
	}

	public function get_online_admission_settings() {
		$school_id = $this->session->userdata("userdata")["sh_id"];
		$academic_year_id = $this->session->userdata("userdata")["academic_year"];
		$sql = "SELECT s.*, t.id as attachement_id, t.file, t.type FROM sh_online_admission_settings s LEFT JOIN sh_online_admission_settings_attachments t ON s.id=t.online_admission_settings_id WHERE s.school_id='$school_id' AND s.deleted_at IS NULL AND t.deleted_at IS NULL ";
		$data = $this->admin_model->dbQuery($sql);
		if(count($data) > 0){
			$new_data = new stdClass();
			$new_data->id = $data[0]->id;
			$new_data->school_id = $data[0]->school_id;
			$new_data->academic_year_id = $data[0]->academic_year_id;
			$new_data->description = $data[0]->description;
			$new_data->attachments = array();
			if(count($data) >= 1){
				foreach($data as $d){
					if($d->attachement_id != null){
						$arr = array(
							"id" => $d->attachement_id,
							"file" => $d->file,
							"type" => $d->type
						);
						array_push($new_data->attachments, $arr);
					}	
				}
			}
			$data = $new_data;
		}
		echo json_encode(array("status"=>"success", "message"=>"data found!", "data"=>$data));
	}

// 	public function softDeleteSettings(){
// 		$where = array("school_id"=>$this->input->post("id"));
// 		$data = array("deleted_at"=> date("Y-m-d h:i:s"));
// 		$this->common_model->update_where("sh_online_admission_settings",$where,$data);
// 		$this->common_model->update_where("sh_online_admission_settings_attachments",$where,$data);
// 		echo "success";
// 	}
    
    public function softDeleteSettings(){
		$where = array("school_id"=>$this->input->post("id"));
		$data = array("deleted_at"=> date("Y-m-d h:i:s"));
		
		$school_id = $this->session->userdata("userdata")["sh_id"];
		$academic_year_id = $this->session->userdata("userdata")["academic_year"];
		$sql = "SELECT s.*, t.id as attachement_id, t.file, t.type FROM sh_online_admission_settings s LEFT JOIN sh_online_admission_settings_attachments t ON s.id=t.online_admission_settings_id WHERE s.school_id='$school_id' AND s.deleted_at IS NULL AND t.deleted_at IS NULL ";
		$data2 = $this->admin_model->dbQuery($sql);
			foreach($data2 as $d){
				if($d->attachement_id != null){
					unlink("uploads/attachment/$d->file");
				}	
			}
		$this->common_model->update_where("sh_online_admission_settings",$where,$data);
		$this->common_model->update_where("sh_online_admission_settings_attachments",$where,$data);
		echo "success";
	}
}