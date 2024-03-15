<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import extends CI_Controller {

    //public $dataa = array();

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    function index() {
        $this->load->view("import/index");
    }

    function is_Exist() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        //print_r($request);
        $sh_url = strtolower($this->session->userdata("userdata")["sh_url"]);
        if(strtolower(substr($request->RollNo, 0, strlen($sh_url))) === $sh_url){
            $roll_no = $request->RollNo;
        }else{
            $roll_no = strtoupper($sh_url.$request->RollNo);
        }
        $email = $request->Email;
        //AND email <> ''
        $where1 = " role_id = 3 AND email = '$email' AND school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at=0 ";
        $data1 = $this->admin_model->dbSelect("*", "users", $where1);
        $where2 = " role_id = 3 AND rollno = '$roll_no' AND rollno <> '' AND school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at=0 ";
        $data2 = $this->admin_model->dbSelect("*", "users", $where2);
        if (count($data1) > 0 || count($data2) > 0) {
            $data['is_exist'] = "1";
        } else {
            $data['is_exist'] = "0";
        }
        echo json_encode($data);
    }

    function show() {
        
        $ext = explode('.', $_FILES['csv']['name']);
        
        if($ext['1'] != 'csv'){
            $this->session->set_flashdata('import_error', 'File is not Valid. Please Upload Valid CSV File Only');
            header("Location: index");
            die();
        }
        
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $academic_year_id = $this->session->userdata("userdata")["academic_year"];
        $class_error = false;
        $class_id = 0;
        $relations = array('father', 'mother', 'uncle', 'brother', 'grandfather', 'grandmother');
        $countries = $this->common_model->countries();
        $country_names = array();
        $country_ids = array();
        foreach ($countries as $c) {
            $country_names[] = $c->country_name;
            $country_ids[] = $c->id;
        }
        $genders = array('male','female');
        $groups = array('A+','B+','AB+','O+','A-','B-','AB-','O-');
        $arr = array();
        $keys = array();
        $newArray = array();
        $file = $_FILES['csv']['tmp_name'];
        if (($handle = fopen($file, 'r')) !== FALSE) {
            $i = 0;
            while (($lineArray = fgetcsv($handle, 4000, ",", "'")) !== FALSE) {
                for ($j = 0; $j < count($lineArray); $j++) {
                    $arr[$i][$j] = $lineArray[$j];
                }
                $i++;
            }
            fclose($handle);
        }
        $data = $arr;
        $count = count($data) - 1;
        //Use first row for names  
        $labels = array_shift($data);
        $labels[0] = 'Sr';
        foreach ($labels as $label) {
            $keys[] = $label;
        }
        // Add Ids, just in case we want them later
        $keys[] = 'id';

        for ($i = 0; $i < $count; $i++) {
            $data[$i][] = $i;
        }






        // Bring it all together


        for ($j = 0; $j < $count; $j++) {
            $d = array_combine($keys, $data[$j]);
            $newArray[$j] = $d;
        }

        

        foreach ($newArray as $key => $value) {

            $message = array();

            // student data
            if (empty($value['First_Name']) || $value['First_Name'] == null) {
                $message[] = "First Name is missing";
            }

            if (empty($value['Last_Name']) || $value['Last_Name'] == null) {
                $message[] = "Last Name is missing";
            }

            if (empty($value['Gender']) || $value['Gender'] == null) {
                $message[] = "Gender is missing";
            }else{
                $newArray[$key]['Gender2'] = $newArray[$key]['Gender'];
                $newArray[$key]['Gender'] = $gender = strtolower(str_replace(' ', '', $value['Gender']));
                if(!in_array($gender, $genders)){
                    $message[] = "Gender is not correct";
                }
            }


            // if (empty($value['DOB']) || $value['DOB'] == null) {
            //     $message[] = "DOB is missing";
            // }else {
            //     $temp = explode('/', $value['DOB']);
            //     $temp[0] = sprintf("%02d", $temp[0]);
            //     if(isset($temp[1])){
            //         $temp[1] = sprintf("%02d", $temp[1]);
            //     }
            //     $newArray[$key]['DOB'] = $value['DOB'] = implode('/', $temp);
            //     if(!$this->validateDate($value['DOB'])){
            //         $message[] = "Student DOB format is not correct. Should be dd/mm/yyyy";
            //     }
                
            // }

            

            // if (empty($value['Blood_Group']) || $value['Blood_Group'] == null) {
            //     // $message[] = "Blood Group is missing";
            // }else{
            //     $newArray[$key]['Blood_Group'] = strtoupper(str_replace(' ', '', $value['Blood_Group']));
            //     if(!in_array($newArray[$key]['Blood_Group'], $groups)){
            //         $message[] = "Blood group is not correct";
            //     }
            // }

            // if (empty($value['Birth_Place']) || $value['Birth_Place'] == null) {
            //     $message[] = "Birth Place is missing";
            // }

            // if (empty($value['Religion']) || $value['Religion'] == null) {
            //     $message[] = "Religion is missing";
            // }

            if (empty($value['Nationality']) || $value['Nationality'] == null) {
                $message[] = "Nationality is missing";
            }else{
                $newArray[$key]['Nationality2'] = $value['Nationality'];
                if(!in_array(ucfirst($value['Nationality']), $country_names)){
                    $message[] = "Student nationality name is not correct";
                }else{
                    $newArray[$key]['Nationality'] = $country_ids[array_search(ucfirst($value['Nationality']), $country_names)];
                }
            }

            // if (empty($value['Language']) || $value['Language'] == null) {
            //     $message[] = "Language is missing";
            // }

            if (empty($value['Contact']) || $value['Contact'] == null) {
                $message[] = "Contact is missing";
            }

            // if (empty($value['Email']) || $value['Email'] == null) {
            //     $message[] = "Email is missing";
            // }else if(!filter_var($value['Email'], FILTER_VALIDATE_EMAIL)){
            //     $message[] = "Student email format is not correct";
            // }else{
            //     $std_email = $this->db->select('id')->from('sh_users')->where('email',$value['Email'])->where('school_id !=',$school_id)->where('deleted_at',0)->get()->row();
            //     if($std_email){
            //         $message[] = "Student email exist in some other school";
            //     }
            // }
            
            //updated by Azeem Student email not required
            
            if (empty($value['Email'])) {
                $message[] = "Email is missing";
            }else{
                $std_email = $this->db->select('id')->from('sh_users')->where('email',$value['Email'])->where('school_id !=',$school_id)->where('deleted_at',0)->get()->row();
                if($std_email){
                    $message[] = "Student email already exist";
                }
            }
            
            if (empty($value['Country']) || $value['Country'] == null) {
                $message[] = "Country is missing";
            }else{
                $newArray[$key]['Country2'] = $value['Country'];
                if(!in_array(ucfirst($value['Country']), $country_names)){
                    $message[] = "Student country name is not correct";
                }else{
                    $newArray[$key]['Country'] = $country_ids[array_search(ucfirst($value['Country']), $country_names)];
                }
            }

            if (empty($value['City']) || $value['City'] == null) {
                $message[] = "City is missing";
            }

            if (empty($value['Address']) || $value['Address'] == null) {
                $message[] = "Address is missing";
            }


            if (empty($value['Class']) || $value['Class'] == null) {
                $message[] = "Class is missing";            
            }else{
                $check_class = $this->db->select('id')->from('sh_classes')->where('name',$value['Class'])->where('academic_year_id',$academic_year_id)->get()->row();
                if($check_class){
                    $class_id = $check_class->id;
                    $newArray[$key]["class_id"] = $class_id;
                }else{
                    $message[] = "Class is not correct";
                    $class_error = true;
                }
            }

            if (empty($value['Section']) || $value['Section'] == null) {
                $message[] = "Section is missing";
            }else if($class_error){

            }else{
                $check_section = $this->db->select('id')->from('sh_batches')->where('name',$value['Section'])->where('class_id',$class_id)->get()->row();
                if(!$check_section){
                    $message[] = "Section is not correct";
                }else{
                    $newArray[$key]["section_id"] = $check_section->id;
                }
            }

            if (empty($value['RollNo']) || $value['RollNo'] == null) {
                $message[] = "Student ID is missing";
            }


            // parent data
            if (empty($value['Parent_Name']) || $value['Parent_Name'] == null) {
                $message[] = "Parent Name is missing";
            }

            if (empty($value['NIC']) || $value['NIC'] == null) {
                $message[] = "NIC is missing";
            }

            if (empty($value['Parent_Gender']) || $value['Parent_Gender'] == null) {

                $message[] = "Parent Gender is missing";
            }else{
                $newArray[$key]['Parent_Gender2'] = $newArray[$key]['Parent_Gender'];
                $newArray[$key]['Parent_Gender'] = $p_gender = strtolower(str_replace(' ', '', $value['Parent_Gender']));
                if(!in_array($p_gender, $genders)){
                    $message[] = "Parent gender is not correct";
                }
            }

            if (empty($value['Relation']) || $value['Relation'] == null) {

                $message[] = "Relation is missing";
            }else{
                $newArray[$key]['Relation2'] = $newArray[$key]['Relation'];
                $newArray[$key]['Relation'] = $relation = strtolower(str_replace(' ', '', $value['Relation']));
                if(!in_array($relation, $relations)){
                    $message[] = "Relation is not correct";
                }
            }

            

            if (empty($value['Parent_DOB']) || $value['Parent_DOB'] == null) {

                // $message[] = "Parent DOB is missing";
            }else {
                $temp = explode('/', $value['Parent_DOB']);
                $temp[0] = sprintf("%02d", $temp[0]);
                if(isset($temp[1])){
                    $temp[1] = sprintf("%02d", $temp[1]);
                }
                $newArray[$key]['Parent_DOB'] = $value['Parent_DOB'] = implode('/', $temp);
                if(!$this->validateDate($value['Parent_DOB'])){
                    $message[] = "Parent DOB format is not correct. Should be dd/mm/yyyy";
                }
                
            }

            

            // if (empty($value['Parent_Occupation']) || $value['Parent_Occupation'] == null) {

            //     $message[] = "Parent Occupation is missing";
            // }

            // if (empty($value['Parent_Income']) || $value['Parent_Income'] == null) {

            //     $message[] = "Parent Income is missing";
            // }

            if (empty($value['Parent_Email']) || $value['Parent_Email'] == null) {

                $message[] = "Parent Email is missing";
            }else if(!filter_var($value['Parent_Email'], FILTER_VALIDATE_EMAIL)){
                $message[] = "Parent email format is not correct";
            }else{
                $p = $this->db->select('id')->from('sh_users')->where('email',$value['Email'])->where('school_id !=',$school_id)->where('deleted_at',0)->get()->row();
                if($p){
                    $message[] = "Parent email exist in some other school";
                }
                $parent = $this->db->select('id')->from('sh_users')->where('email',$value['Parent_Email'])->where('deleted_at',0)->get()->row();
                if($parent){
                    $newArray[$key]['parentIdResponse'] = 0;
                    $newArray[$key]['parentId'] = $parent->id;
                }else{
                    $newArray[$key]['parentIdResponse'] = 1;
                    $newArray[$key]['parentId'] = "";
                }
            }


            // if (empty($value['Parent_Contact']) || $value['Parent_Contact'] == null) {

            //     $message[] = "Parent Contact is missing";
            // }

            // if (empty($value['IC_Number']) || $value['IC_Number'] == null) {

            //     $message[] = "IC Number is missing";
            // }

            // if (empty($value['Parent_Address']) || $value['Parent_Address'] == null) {

            //     $message[] = "Parent Address is missing";
            // }

            // if (empty($value['Parent_City']) || $value['Parent_City'] == null) {

            //     $message[] = "Parent City is missing";
            // }

            if (empty($value['Parent_Country']) || $value['Parent_Country'] == null) {

                $message[] = "Parent Country is missing";
            }else{
                $newArray[$key]['Parent_Country2'] = $value['Parent_Country'];
                if(!in_array(ucfirst($value['Parent_Country']), $country_names)){
                    $message[] = "Parent country name is not correct";
                }else{
                    $newArray[$key]['Parent_Country'] = $country_ids[array_search(ucfirst($value['Parent_Country']), $country_names)];
                }
            }

            

            if (empty($value['Admission_Date']) || $value['Admission_Date'] == null) {

                // $message[] = "Admission Date is missing";
            }else {
                $temp = explode('/', $value['Admission_Date']);
                $temp[0] = sprintf("%02d", $temp[0]);
                if(isset($temp[1])){
                    $temp[1] = sprintf("%02d", $temp[1]);
                }
                $newArray[$key]['Admission_Date'] = $value['Admission_Date'] = implode('/', $temp);
                if(!$this->validateDate($value['Admission_Date'])){
                    $message[] = "Admission Date format is not correct. Should be dd/mm/yyyy";
                }
                
            }


            $newArray[$key]['is_exist'] = 0;
            // check if student already exist
            if(empty($message)){
                $sh_url = strtolower($this->session->userdata("userdata")["sh_url"]);
                if(strtolower(substr($value['RollNo'], 0, strlen($sh_url))) === $sh_url){
                    $roll_no = $value['RollNo'];
                }else{
                    $roll_no = strtoupper($sh_url.$value['RollNo']);
                }
                $email = $value['Email'];
                $p_email = $value['Parent_Email'];
                $where1 = " role_id = 3 AND email = '$email' AND email <> '' AND school_id=" . $school_id . " AND deleted_at=0 ";
                $data1 = $this->admin_model->dbSelect("id", "users", $where1);
                $where1_1 = " role_id != 3 AND email = '$email' AND email <> '' AND school_id=" . $school_id . " AND deleted_at=0 ";
                $data1_1 = $this->admin_model->dbSelect("id", "users", $where1_1);
                if($data1_1){
                    $message[] = "Student email is assigned to some non student";
                }
                $where2 = " role_id = 3 AND rollno = '$roll_no' AND rollno <> '' AND school_id=" . $school_id . " AND deleted_at=0 ";
                $data2 = $this->admin_model->dbSelect("id", "users", $where2);
                if ($data1 && $data2) {
                    $id_1 = $data1[0]->id;
                    $id_2 = $data2[0]->id;
                    if($id_1 == $id_2){
                        $newArray[$key]['is_exist'] = "1";
                        $newArray[$key]["student_id"] = $id_1;
                    }else{
                        $message[] = "Student email and roll no is of two different students";
                    }
                    
                } else {
                    if($data1){
                        $message[] = "Student email exist but roll no is not correct";
                    }else if($data2){
                        $message[] = "Student roll no exist but email is not correct";
                    }
                    $newArray[$key]['is_exist'] = "0";
                }

                $pp = $this->db->select('id')->from('sh_users')->where('role_id !=',2)->where('email',$p_email)->where('school_id',$school_id)->where('deleted_at',0)->get()->row();

                if($pp){
                    $message[] = "Guardian email is assigned to someone who is not a guardian";
                }

                $parent = $this->db->select('id')->from('sh_users')->where('role_id',2)->where('email',$p_email)->where('school_id',$school_id)->where('deleted_at',0)->get()->row();

                $newArray[$key]["parent_id"] = "not_exist";

                if($parent){
                    $newArray[$key]["parent_id"] = $parent->id;
                }

            }

            

            $newArray[$key]['errors'] = $message;

            if(empty($message)){
                $newArray[$key]['any_error'] = false;
            }else{
                $newArray[$key]['any_error'] = true;
            }



        }


        // echo "<pre>";
        // print_r($newArray[0]);
        // die();


        

        


        $dataa["import_json"] = htmlspecialchars(json_encode($newArray, JSON_UNESCAPED_UNICODE));
        $dataa["countries"] = $countries;
        $dataa["classes"] = $this->admin_model->dbSelect("*", "classes", " school_id=" . $this->session->userdata("userdata")["sh_id"] . " AND deleted_at IS NULL AND academic_year_id =".$this->session->userdata("userdata")["academic_year"]);




        $this->load->view("import/show", $dataa);
    }

    function parentId() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $email = $request->email;

        $res = $this->db->select('id')->from('sh_users')->where('email', $email)->where('deleted_at', 0)->get()->row();

        echo json_encode($res);
    }


    public function process(){
        $postdata = file_get_contents("php://input");
        $all_students = json_decode($postdata);

        $data_return["row_ids"] = [];
        foreach($all_students as $request){
            $data_return["row_ids"][] = $request->id;
        if($request->is_exist != "1"){
            $res = $this->db->select('id')->from('sh_users')->where('email', $request->Parent_Email)->where('deleted_at', 0)->get()->row();
            if($res){
                $request->parentId = $res->id;
                $request->parentIdResponse = 0;
            }else{
                $request->parentId = "";
                $request->parentIdResponse = 1;
            }
            

            if (!isset($request->Admission_Date)) {
                $request->Admission_Date = '';
            }

            $parent_id = $request->parentId;


            $checkEmailExist = 0;
            $sh_url = strtolower($this->session->userdata("userdata")["sh_url"]);
            //if(strtolower(substr($value['RollNo'], 0, strlen($sh_url))) === $sh_url){
            if(strtolower(substr($request->RollNo, 0, strlen($sh_url))) === $sh_url){                
                $roll_no = $request->RollNo;
            }else{
                $roll_no = strtoupper($sh_url.$request->RollNo);
            }

            $result = $this->common_model->email_exit($request->Email)->result();
            $id_check = $this->db->select('*')->from('users')->where('rollno', $roll_no)->where('school_id', $this->session->userdata("userdata")["sh_id"])->where('deleted_at',0)->get()->result();
            if (count($result) > 0 && !empty($request->Email)) {
                $checkEmailExist = 1;
                echo json_encode(array("status" => "error", "message" => "Student email (" . $request->Email . ") already exists."));
            } else if (count($id_check) > 0 && !empty($roll_no)) {
                $checkEmailExist = 1;
                echo json_encode(array("status" => "error", "message" => "Student ID (" . $roll_no . ") already exists."));
            } else if (!isset($guardian_id)) {



                $result = $this->common_model->email_exit($request->Parent_Email)->result();
                if (count($result) > 0 && empty($request->Parent_Email) && $request->Parent_Email != '') {
                    $checkEmailExist = 1;
                    echo json_encode(array("status" => "error", "message" => "Guardian email (" . $request->Parent_Email . ") already exists."));
                }
            }



            if ($checkEmailExist == 0) {
            // set default profile image
                if (!isset($request->avatar)) {
                    $request->avatar = 'default.png';
                }
                if ($request->DOB == '') {
                    $std_dob = NULL;
                } else {
                    $std_dob = (new DateTime())->createFromFormat('d/m/Y', $request->DOB)->format("Y-m-d");
                }

                if ($request->Admission_Date == '') {
                    $std_adm = date("Y-m-d");
                } else {
                    $std_adm = (new DateTime())->createFromFormat('d/m/Y', $request->Admission_Date)->format("Y-m-d");
                }
                $sh_url = strtolower($this->session->userdata("userdata")["sh_url"]);
                if(strtolower(substr($request->RollNo, 0, strlen($sh_url))) === $sh_url){
                    $roll_no = $request->RollNo;
                }else{
                    $roll_no = strtoupper($sh_url.$request->RollNo);
                }
            // create student object to save in database
                $student = array(
                    'address' => $request->Address,
//          'avatar' => explode('uploads/user/', save_image($request->avatar))[1],

                    'password' => md5("default"),
                    'school_id' => $this->session->userdata("userdata")["sh_id"],
                    'birthplace' => $request->Birth_Place,
                    'blood' => $request->Blood_Group,
                    'city' => $request->City,
                    'country' => $request->Country,
                    'dob' => $std_dob,
                    'joining_date' => $std_adm,
                    'email' => $request->Email,
                    'name' => $request->First_Name . " " . $request->Last_Name,
                    'gender' => $request->Gender,
                    'language' => $request->Language,
                    'nationality' => $request->Nationality,
                    'contact' => $request->Contact,
                    'religion' => $request->Religion,
                    'rollno' => $roll_no,
                    'email_verified' => 'Y',
                    'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
                    'role_id' => STUDENT_ROLE_ID,
                    'ic_number' => $request->NIC
                );



            // parent already exist add new stdudent with existing parent...

                if ($parent_id != "") {

                    $std_id = $this->common_model->insert('sh_users', $student);
                    $student_class_data = array('class_id' => $request->class_id,
                        'batch_id' => $request->section_id,
                        'school_id' => $this->session->userdata("userdata")["sh_id"],
                        'academic_year_id' => $this->session->userdata("userdata")["academic_year"],
                        'student_id' => $std_id
                    );
                    $std_guradian_relation = array(
                        'student_id' => $std_id,
                        'guardian_id' => $parent_id,
                        'relation' => $request->Relation
                    );
                    $this->common_model->insert('sh_student_class_relation',$student_class_data);
                    $this->common_model->insert('sh_student_guardians', $std_guradian_relation);
                } else {

                // parent not exist add new student with parent

                    $std_id = $this->common_model->insert('sh_users', $student);
                    $student_class_data = array('class_id' => $request->class_id,
                        'batch_id' => $request->section_id,
                        'school_id' => $this->session->userdata("userdata")["sh_id"],
                        'academic_year_id' => $this->session->userdata("userdata")["academic_year"],
                        'student_id' => $std_id
                    );
                    $this->common_model->insert('sh_student_class_relation',$student_class_data);
                    if ($request->Parent_DOB == '') {
                        $par_dob = NULL;
                    } else {
                        $par_dob = (new DateTime())->createFromFormat('d/m/Y', $request->Parent_DOB)->format("Y-m-d");
                    }

                    $parent = array(
//                    'avatar' => explode('uploads/user/', save_image($request->pAvatar))[1],
                        'city' => $request->Parent_City,
                        'country' => $request->Parent_Country,
                        'password' => md5("default"),
                        'school_id' => $this->session->userdata("userdata")["sh_id"],
                        'dob' => $par_dob,
                        'email' => $request->Parent_Email,
                        'gender' => $request->Parent_Gender,
                        'ic_number' => $request->IC_Number,
                        'income' => $request->Parent_Income,
                        'name' => $request->Parent_Name,
                        'occupation' => $request->Parent_Occupation,
                        'contact' => $request->Parent_Contact,
                        'address' => $request->Parent_Contact,
                        'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
                        'role_id' => PARENT_ROLE_ID
                    );
                    $guardian_id = $this->common_model->insert('sh_users', $parent);
                    if (!$guardian_id) {
                        $guardian_id = 0;
                    }
                    $std_guradian_relation = array(
                        'student_id' => $std_id,
                        'guardian_id' => $guardian_id,
                        'relation' => $request->Relation
                    );
                    $this->common_model->insert('sh_student_guardians', $std_guradian_relation);

                    if ($guardian_id > 0) {
                        $link = base_url() . 'login/activation?activate=' . $parent["token"];
                        $subject = 'Account Activation';
                        $data = array(
                            "dear_sir" => lang('tmp_dear_sir'),
                            "msg" => lang('tmp_info'),
                            "thanks" => lang('tmp_thanks'),
                            "poweredBy" => lang('tmp_power'),
                            "unsub" => lang('tmp_unsub'),
                            "link" => $link,
                            "email" => $request->Parent_Email,
                            "password" => "default"
                        );
                        $message = $this->load->view('email_templates/account_activation2.php', $data, TRUE);
                        $this->email_modal->emailSend($request->Parent_Email, $message, $subject, $link);
                    }
                }

            //Send mail to student email address for account verification
                if ($std_id > 0) {
                //$token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));
                    $link = base_url() . 'login/activation?activate=' . $student["token"];
                    $subject = 'Account Activation';
                    $data = array(
                        "dear_sir" => lang('tmp_dear_sir'),
                        "msg" => lang('tmp_info'),
                        "thanks" => lang('tmp_thanks'),
                        "poweredBy" => lang('tmp_power'),
                        "unsub" => lang('tmp_unsub'),
                        "link" => $link,
                        "email" => $request->Email,
                        "password" => "default"
                    );
                    $message = $this->load->view('email_templates/account_activation2.php', $data, TRUE);
                    $this->email_modal->emailSend($request->Email, $message, $subject, $link);
                }

            }
        }else{
            if ($request->DOB == '') {
                $std_dob = NULL;
            } else {
                $std_dob = (new DateTime())->createFromFormat('d/m/Y', $request->DOB)->format("Y-m-d");
            }

            if (!isset($request->Admission_Date) || $request->Admission_Date == NULL) {
                $std_adm = NULL;
            } else {
                $std_adm = (new DateTime())->createFromFormat('d/m/Y', $request->Admission_Date)->format("Y-m-d");
            }
            $std_update_id = $request->student_id;
            $sh_url = strtolower($this->session->userdata("userdata")["sh_url"]);
            if(strtolower(substr($request->RollNo, 0, strlen($sh_url))) === $sh_url){
                $roll_no = $request->RollNo;
            }else{
                $roll_no = strtoupper($sh_url.$request->RollNo);
            }

            $student = array(
                'address' => $request->Address,
            //'avatar' => explode('uploads/user/', save_image($request->avatar))[1],

                'password' => md5("default"),
                'school_id' => $this->session->userdata("userdata")["sh_id"],
                'birthplace' => $request->Birth_Place,
                'blood' => $request->Blood_Group,
                'city' => $request->City,
                'country' => $request->Country,
                'dob' => $std_dob,
                'joining_date' => $std_adm,
                'email' => $request->Email,
                'name' => $request->First_Name . " " . $request->Last_Name,
                'gender' => $request->Gender,
                'language' => $request->Language,
                'nationality' => $request->Nationality,
                'contact' => $request->Contact,
                'religion' => $request->Religion,
                'rollno' => $roll_no,
                'email_verified' => 'Y',
                'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
                'role_id' => STUDENT_ROLE_ID,
                'ic_number' => $request->NIC
            );

            $student_class_data = array(
                'class_id' => $request->class_id,
                'batch_id' => $request->section_id
            );

            $this->db->where('student_id',$std_update_id)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->where("deleted_at is null")->update('sh_student_class_relation',$student_class_data);

            $this->db->where('id', $std_update_id);

            $this->common_model->update('sh_users', $student);



            if ($this->db->affected_rows() == true) {



                $guardian_id = $request->parent_id;

                if (!isset($request->Parent_DOB) || $request->Parent_DOB == NULL) {
                    $par_dob = NULL;
                } else {
                    $par_dob = (new DateTime())->createFromFormat('d/m/Y', $request->Parent_DOB)->format("Y-m-d");
                }
                $parent = array(
                //                    'avatar' => explode('uploads/user/', save_image($request->pAvatar))[1],
                    'city' => $request->Parent_City,
                    'country' => $request->Parent_Country,
                    'password' => md5("default"),
                    'school_id' => $this->session->userdata("userdata")["sh_id"],
                    'dob' => $par_dob,
                    'email' => $request->Parent_Email,
                    'gender' => $request->Parent_Gender,
                    'ic_number' => $request->IC_Number,
                    'income' => $request->Parent_Income,
                    'name' => $request->Parent_Name,
                    'occupation' => $request->Parent_Occupation,
                    'contact' => $request->Parent_Contact,
                    'address' => $request->Parent_Contact,
                    'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
                    'role_id' => PARENT_ROLE_ID
                );

                if($guardian_id == "not_exist"){
                    $this->db->insert('sh_users',$parent);
                    $new_parent_id = $this->db->insert_id();
                    $this->db->set('guardian_id',$new_parent_id)->where('student_id',$std_update_id)->where("deleted_at is null")->update('sh_student_guardians');
                }else{
                    $this->db->where('id', $guardian_id);

                    $this->common_model->update('sh_users', $parent);
                }




            } 
        }
    }
        $data_return["message"] = count($data_return["row_ids"])." Students processes successfully";
        echo json_encode($data_return);
    }

    public function saveStudunt() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        if (!isset($request->Admission_Date)) {
            $request->Admission_Date = '';
        }

        $parent_id = $request->parentId;
        

        $checkEmailExist = 0;

        $sh_url = strtolower($this->session->userdata("userdata")["sh_url"]);
        if(strtolower(substr($request->RollNo, 0, strlen($sh_url))) === $sh_url){
            $roll_no = $request->RollNo;
        }else{
            $roll_no = strtoupper($sh_url.$request->RollNo);
        }

        $result = $this->common_model->email_exit($request->Email)->result();
        $id_check = $this->db->select('*')->from('users')->where('rollno', $roll_no)->where('school_id', $this->session->userdata("userdata")["sh_id"])->where('deleted_at',0)->get()->result();
        if (count($result) > 0 && !empty($request->Email)) {
            $checkEmailExist = 1;
            echo json_encode(array("status" => "error", "message" => "Student email (" . $request->Email . ") already exists."));
        } else if (count($id_check) > 0 && !empty($request->RollNo)) {
            $checkEmailExist = 1;
            echo json_encode(array("status" => "error", "message" => "Student ID (" . $roll_no . ") already exists."));
        } else if (!isset($guardian_id)) {



            $result = $this->common_model->email_exit($request->Parent_Email)->result();
            if (count($result) > 0 && empty($request->Parent_Email) && $request->Parent_Email != '') {
                $checkEmailExist = 1;
                echo json_encode(array("status" => "error", "message" => "Guardian email (" . $request->Parent_Email . ") already exists."));
            }
        }



        if ($checkEmailExist == 0) {
            // set default profile image
            if (!isset($request->avatar)) {
                $request->avatar = 'default.png';
            }
            if ($request->DOB == '') {
                $std_dob = NULL;
            } else {
                $std_dob = (new DateTime())->createFromFormat('d/m/Y', $request->DOB)->format("Y-m-d");
            }

            if ($request->Admission_Date == '') {
                $std_adm = date("Y-m-d");
            } else {
                $std_adm = (new DateTime())->createFromFormat('d/m/Y', $request->Admission_Date)->format("Y-m-d");
            }
            // create student object to save in database
            $student = array(
                'address' => $request->Address,
//          'avatar' => explode('uploads/user/', save_image($request->avatar))[1],
                
                'password' => md5("default"),
                'school_id' => $this->session->userdata("userdata")["sh_id"],
                'birthplace' => $request->Birth_Place,
                'blood' => $request->Blood_Group,
                'city' => $request->City,
                'country' => $request->Country,
                'dob' => $std_dob,
                'joining_date' => $std_adm,
                'email' => $request->Email,
                'name' => $request->First_Name . " " . $request->Last_Name,
                'gender' => $request->Gender,
                'language' => $request->Language,
                'nationality' => $request->Nationality,
                'contact' => $request->Contact,
                'religion' => $request->Religion,
                'rollno' => $request->RollNo,
                'email_verified' => 'Y',
                'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
                'role_id' => STUDENT_ROLE_ID,
                'ic_number' => $request->NIC
            );

            

            // parent already exist add new stdudent with existing parent...

            if ($parent_id != "") {

                $std_id = $this->common_model->insert('sh_users', $student);
                $student_class_data = array('class_id' => $request->class_id,
                    'batch_id' => $request->section_id,
                    'school_id' => $this->session->userdata("userdata")["sh_id"],
                    'academic_year_id' => $this->session->userdata("userdata")["academic_year"],
                    'student_id' => $std_id
                );
                $std_guradian_relation = array(
                    'student_id' => $std_id,
                    'guardian_id' => $parent_id,
                    'relation' => $request->Relation
                );
                $this->common_model->insert('sh_student_class_relation',$student_class_data);
                $this->common_model->insert('sh_student_guardians', $std_guradian_relation);
            } else {

                // parent not exist add new student with parent

                $std_id = $this->common_model->insert('sh_users', $student);
                $student_class_data = array('class_id' => $request->class_id,
                    'batch_id' => $request->section_id,
                    'school_id' => $this->session->userdata("userdata")["sh_id"],
                    'academic_year_id' => $this->session->userdata("userdata")["academic_year"],
                    'student_id' => $std_id
                );
                $this->common_model->insert('sh_student_class_relation',$student_class_data);
                if ($request->Parent_DOB == '') {
                    $par_dob = NULL;
                } else {
                    $par_dob = (new DateTime())->createFromFormat('d/m/Y', $request->Parent_DOB)->format("Y-m-d");
                }

                $parent = array(
//                    'avatar' => explode('uploads/user/', save_image($request->pAvatar))[1],
                    'city' => $request->Parent_City,
                    'country' => $request->Parent_Country,
                    'password' => md5("default"),
                    'school_id' => $this->session->userdata("userdata")["sh_id"],
                    'dob' => $par_dob,
                    'email' => $request->Parent_Email,
                    'gender' => $request->Parent_Gender,
                    'ic_number' => $request->IC_Number,
                    'income' => $request->Parent_Income,
                    'name' => $request->Parent_Name,
                    'occupation' => $request->Parent_Occupation,
                    'contact' => $request->Parent_Contact,
                    'address' => $request->Parent_Contact,
                    'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
                    'role_id' => PARENT_ROLE_ID
                );
                $guardian_id = $this->common_model->insert('sh_users', $parent);
                if (!$guardian_id) {
                    $guardian_id = 0;
                }
                $std_guradian_relation = array(
                    'student_id' => $std_id,
                    'guardian_id' => $guardian_id,
                    'relation' => $request->Relation
                );
                $this->common_model->insert('sh_student_guardians', $std_guradian_relation);

                if ($guardian_id > 0) {
                    $link = base_url() . 'login/activation?activate=' . $parent["token"];
                    $subject = 'Account Activation';
                    $data = array(
                        "dear_sir" => lang('tmp_dear_sir'),
                        "msg" => lang('tmp_info'),
                        "thanks" => lang('tmp_thanks'),
                        "poweredBy" => lang('tmp_power'),
                        "unsub" => lang('tmp_unsub'),
                        "link" => $link,
                        "email" => $request->Parent_Email,
                        "password" => "default"
                    );
                    $message = $this->load->view('email_templates/account_activation2.php', $data, TRUE);
                    $this->email_modal->emailSend($request->Parent_Email, $message, $subject, $link);
                }
            }

            //Send mail to student email address for account verification
            if ($std_id > 0) {
                //$token = md5(uniqid('myschool_unitedvision' . date("H:i:s")));
                $link = base_url() . 'login/activation?activate=' . $student["token"];
                $subject = 'Account Activation';
                $data = array(
                    "dear_sir" => lang('tmp_dear_sir'),
                    "msg" => lang('tmp_info'),
                    "thanks" => lang('tmp_thanks'),
                    "poweredBy" => lang('tmp_power'),
                    "unsub" => lang('tmp_unsub'),
                    "link" => $link,
                    "email" => $request->Email,
                    "password" => "default"
                );
                $message = $this->load->view('email_templates/account_activation2.php', $data, TRUE);
                $this->email_modal->emailSend($request->Email, $message, $subject, $link);
            }
            //dispaly success message after created new student
            echo json_encode(array("status" => "success", "message" => "Student created successfully."));
        }
    }

    function validateDate($date)
    {
        $d = DateTime::createFromFormat('d/m/Y', $date);
        return $d && $d->format('d/m/Y') === $date;
    }

    // update students 
    function updateStudunt() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);



        if ($request->DOB == '') {
            $std_dob = NULL;
        } else {
            $std_dob = (new DateTime())->createFromFormat('d/m/Y', $request->DOB)->format("Y-m-d");
        }

        if (!isset($request->Admission_Date) || $request->Admission_Date == NULL) {
            $std_adm = NULL;
        } else {
            $std_adm = (new DateTime())->createFromFormat('d/m/Y', $request->Admission_Date)->format("Y-m-d");
        }
        $std_update_id = $request->student_id;

        $sh_url = strtolower($this->session->userdata("userdata")["sh_url"]);
        if(strtolower(substr($request->RollNo, 0, strlen($sh_url))) === $sh_url){
            $roll_no = $request->RollNo;
        }else{
            $roll_no = strtoupper($sh_url.$request->RollNo);
        }

        $student = array(
            'address' => $request->Address,
            //'avatar' => explode('uploads/user/', save_image($request->avatar))[1],
            
            'password' => md5("default"),
            'school_id' => $this->session->userdata("userdata")["sh_id"],
            'birthplace' => $request->Birth_Place,
            'blood' => $request->Blood_Group,
            'city' => $request->City,
            'country' => $request->Country,
            'dob' => $std_dob,
            'joining_date' => $std_adm,
            'email' => $request->Email,
            'name' => $request->First_Name . " " . $request->Last_Name,
            'gender' => $request->Gender,
            'language' => $request->Language,
            'nationality' => $request->Nationality,
            'contact' => $request->Contact,
            'religion' => $request->Religion,
            'rollno' => $roll_no,
            'email_verified' => 'Y',
            'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
            'role_id' => STUDENT_ROLE_ID,
            'ic_number' => $request->NIC
        );

        $student_class_data = array(
            'class_id' => $request->class_id,
            'batch_id' => $request->section_id
        );

        $this->db->where('student_id',$std_update_id)->where('academic_year_id',$this->session->userdata("userdata")["academic_year"])->where("deleted_at is null")->update('sh_student_class_relation',$student_class_data);

        $this->db->where('id', $std_update_id);

        $this->common_model->update('sh_users', $student);

        

        if ($this->db->affected_rows() == true) {



            $guardian_id = $request->parent_id;

            if (!isset($request->Parent_DOB) || $request->Parent_DOB == NULL) {
                $par_dob = NULL;
            } else {
                $par_dob = (new DateTime())->createFromFormat('d/m/Y', $request->Parent_DOB)->format("Y-m-d");
            }
            $parent = array(
                //                    'avatar' => explode('uploads/user/', save_image($request->pAvatar))[1],
                'city' => $request->Parent_City,
                'country' => $request->Parent_Country,
                'password' => md5("default"),
                'school_id' => $this->session->userdata("userdata")["sh_id"],
                'dob' => $par_dob,
                'email' => $request->Parent_Email,
                'gender' => $request->Parent_Gender,
                'ic_number' => $request->IC_Number,
                'income' => $request->Parent_Income,
                'name' => $request->Parent_Name,
                'occupation' => $request->Parent_Occupation,
                'contact' => $request->Parent_Contact,
                'address' => $request->Parent_Contact,
                'token' => md5(uniqid('myschool_unitedvision' . date("H:i:s"))),
                'role_id' => PARENT_ROLE_ID
            );

            if($guardian_id == "not_exist"){
                $this->db->insert('sh_users',$parent);
                $new_parent_id = $this->db->insert_id();
                $this->db->set('guardian_id',$new_parent_id)->where('student_id',$std_update_id)->where("deleted_at is null")->update('sh_student_guardians');
            }else{
                $this->db->where('id', $guardian_id);

                $this->common_model->update('sh_users', $parent);
            }

            

            if ($this->db->affected_rows() == true) {
                echo json_encode(array("status" => "success", "message" => "Student updated successfully."));
            } else {
                echo json_encode(array("status" => "error", "message" => "Error! Student not Updated."));
            }
        } else {
            echo json_encode(array("status" => "error", "message" => "Error! Student not Updated."));
        }
    }

    function getClass() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        // print_r($request);
        // die();

        $class_name = $request->class_name;

        $section_name = $request->section_name;

        $school_id = $this->session->userdata("userdata")["sh_id"];

        $cls_id = $this->admin_model->dbSelect("id", "classes", " name='$class_name' AND school_id='$school_id' AND deleted_at IS NULL AND academic_year_id =".$this->session->userdata("userdata")["academic_year"]);



        $class_id = '';
        $section_id = '';
        if (count($cls_id) > 0) {
            $class_id = $cls_id[0]->id;
            $sect_id = $this->admin_model->dbSelect("id", "batches", " name='$section_name'  AND class_id= '$class_id' AND school_id='$school_id' AND deleted_at IS NULL AND academic_year_id =".$this->session->userdata("userdata")["academic_year"]);


            if (count($sect_id) > 0) {
                $section_id = $sect_id[0]->id;
            }
        }



        $data['class_id'] = $class_id;
        $data['section_id'] = $section_id;

        echo json_encode($data);
    }

    function get_prev_ids(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $sh_url = strtolower($this->session->userdata("userdata")["sh_url"]);
        if(strtolower(substr($request->rollno, 0, strlen($sh_url))) === $sh_url){
            $roll_no = $request->rollno;
        }else{
            $roll_no = strtoupper($sh_url.$request->rollno);
        }
        $email = $request->email;
        $p_email = $request->p_email;

        $data["student_id"] = $this->db->select('id')->from('sh_users')->where('rollno',$rollno)->or_where('email',$email)->get()->row()->id;

        $parent = $this->db->select('id')->from('sh_users')->where('email',$p_email)->where('deleted_at',0)->get()->row();

        $data["parent_id"] = "not_exist";

        if($parent){
            $data["parent_id"] = $parent->id;
        }

        echo json_encode($data);
    }

}
