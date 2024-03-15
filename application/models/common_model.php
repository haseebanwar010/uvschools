<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {

    function __construct() {
        parent::__construct();
    } 

    /////******************** login function 
    function login($email, $password) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $this->db->where('password', MD5($password));
        $this->db->where("(role_id = 3 OR role_id = 4)");
        $this->db->where('status', '1');
        $this->db->limit(1);

        $query = $this->db->get();
        /* echo $this->db->last_query();
          exit; */
        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    ////*************
    function query($query) {
        return $this->db->query($query);
    }

    /////******************** admin login function 
    function admin_login($username, $password) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $username);
        $this->db->where('password', MD5($password));
        $this->db->where("(role_id = 1 OR role_id = 2)");
        //$this -> db -> or_where('role_id', '2');

        $this->db->limit(1);

        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->row();
        } else {
            return false;
        }
    }

    /////***************** Get user type 	
    function get_user_type($id) {


        $this->db->select('role_id');
        $this->db->from('users');
        $this->db->where('id', $id);


        $query = $this->db->get();
        /* echo $this->db->last_query();
          exit; */
        if ($query->num_rows() > 0) {
            $result = $query->row();
            return $result->user_role;
        } else {
            return false;
        }
    }

    /////*************
    /////*************
    function get_where($tablename, $column, $id = NULL) {
        $WHERE = "";
        if ($id != NULL) {
            $WHERE = "WHERE `$column` = $id";
        }
        $result = $this->db->query("
			SELECT *
			FROM $tablename
			$WHERE
		");
        return $result;
    }

    /////***************** Edit Record Function 	
    function editRecord($columName, $where, $tbl_name, $data) {
        $this->db->where($columName, $where);
        $this->db->update($tbl_name, $data);
    }

    ////*********************** get record of user table 
    function user_result($tablename, $id = NULL) {
        $WHERE = "";
        if ($id != NULL) {
            $WHERE = "WHERE user_id = $id";
        }
        $result = $this->db->query("
			SELECT *
			FROM $tablename
			$WHERE
		");
        return $result;
    }

    ////*********************** get record of table 
    function result($tablename, $id = NULL) {
        $WHERE = "";
        if ($id != NULL) {
            $WHERE = "WHERE id = $id";
        }
        $result = $this->db->query("
			SELECT *
			FROM $tablename
			$WHERE
		");
        return $result;
    }

    /////************************ insert into
    function insert($tbl_name, $data) {
        $this->db->insert($tbl_name, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /////************************ update into
    function update($tbl_name, $data) {
        $this->db->update($tbl_name, $data);
    }

    /////************************ email exists
    function email_exit($email) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email)->where('deleted_at',0);

        $query = $this->db->get();

        return $query;
    }

    /////************************ delete
    function delete($column, $id, $table) {
        $this->db->where($column, $id);
        $res = $this->db->delete($table);
        return $res;
    }

    ////*********** user max id
    function user_max_id() {
        $this->db->select('max(id) as id');
        $this->db->from('users');
        $user_id = $this->db->get();
        $user_id = $user_id->row();
        $user_id = $user_id->id;
        return $user_id + 1;
    }

    ///////******** delete where where
    function deleteWhereWhere($column, $id, $column1, $id1, $column2, $id2, $table) {
        $this->db->where($column, $id);
        $this->db->where($column1, $id1);
        $this->db->where($column2, $id2);
        $this->db->delete($table);

        //$query = $this -> db -> get();

        return $query;
    }

    function getUserInfo($activationID) {
        $this->db->select("li.licence_key ,u.id as user_id");
        $this->db->from("license as li");
        $this->db->join("users as u", "u.school_id=li.school_id", "left");
        $this->db->where("li.licence_key", $activationID);
        $result = $this->db->get();
        return $result->row();
    }

    ////////// Login TO Dashboard through various Checks //////////
    function admin_login2($email, $password, $school_id) {
        $sql = "Select "
                . "rol.name as role_name,"
                . "u.id as user_id,u.*,"
                . "sh.url as sh_url, "
                . "sh.name as sh_name, "
                . "sh.logo as sh_logo, "
                . "sh.theme_color as theme_color, "
                . "sh.address as sh_address, "
                . "sh.phone as sh_phone, "
                . "sh.enable_gd as sh_enable_gd, "
                . "sh.teacher_dept_id as teacher_dept_id, "
                . "sh.accounts_dept_id as accounts_dept_id, "
                . "sh.time_zone as time_zone, "
                . "IFNULL(ay.id,0) as academic_year, "
                . "IFNULL(ay.name,'-') as academic_year_name, "
                . "c.symbol as currency_symbol, "
                . "li.* From sh_users u "
                . "Left Join sh_academic_years ay on u.school_id = ay.school_id AND ay.is_active = 'Y' AND ay.deleted_at is null "
                . "Inner Join sh_school sh ON u.school_id=sh.id "
                . "Inner Join sh_roles rol ON u.role_id=rol.id "
                . "Left Join sh_school_currencies sc ON sh.id = sc.school_id AND is_default = 'yes' AND sc.deleted_at is null "
                . "Left Join sh_currency c ON sc.currency_id = c.id "
                . "Left Join sh_license li "
                . "ON u.school_id=li.school_id Where ( u.email='$email' OR u.rollno='$email' ) AND u.school_id=$school_id "
                . "AND u.password=md5('$password') AND u.deleted_at = 0 AND (u.role_id=" . ADMIN_ROLE_ID . " OR u.role_id = " . STUDENT_ROLE_ID . " OR u.role_id=" . EMPLOYEE_ROLE_ID . " OR u.role_id=" . PARENT_ROLE_ID . ") "
                . "order by li.id desc limit 1;";
//        echo $sql; exit;
        $query = $this->db->query($sql);
        return $query->row();
    }
    
    function admin_login2_default($email, $password) {
        $sql = "Select "
                . "rol.name as role_name,"
                . "u.id as user_id,u.*,"
                . "sh.url as sh_url, "
                . "sh.name as sh_name, "
                . "sh.logo as sh_logo, "
                . "sh.theme_color as theme_color, "
                . "sh.address as sh_address, "
                . "sh.phone as sh_phone, "
                . "sh.enable_gd as sh_enable_gd, "
                . "sh.teacher_dept_id as teacher_dept_id, "
                . "sh.accounts_dept_id as accounts_dept_id, "
                . "sh.time_zone as time_zone, "
                . "IFNULL(ay.id,0) as academic_year, "
                . "IFNULL(ay.name,'-') as academic_year_name, "
                . "c.symbol as currency_symbol, "
                . "li.* From sh_users u "
                . "Left Join sh_academic_years ay on u.school_id = ay.school_id and ay.is_active = 'Y' AND ay.deleted_at is null "
                . "Inner Join sh_school sh ON u.school_id=sh.id "
                . "Inner Join sh_roles rol ON u.role_id=rol.id "
                . "Left Join sh_school_currencies sc ON sh.id = sc.school_id and is_default = 'yes' AND sc.deleted_at is null "
                . "Left Join sh_currency c ON sc.currency_id = c.id "
                . "Left Join sh_license li "
                . "ON u.school_id=li.school_id Where ( u.email='$email' OR rollno='$email' ) "
                . "AND u.password=md5('$password') AND u.deleted_at = 0 AND (role_id=" . ADMIN_ROLE_ID . " OR role_id = " . STUDENT_ROLE_ID . " OR role_id=" . EMPLOYEE_ROLE_ID . " OR role_id=" . PARENT_ROLE_ID . ") "
                . "order by li.id desc limit 1;";
//        echo $sql; exit;
        $query = $this->db->query($sql);
        return $query->row();
    }

    function countries() {
        $this->db->select('*');
        $this->db->from('sh_countries');
        $query = $this->db->get();
        return $query->result();
    }

    function smart_resize_image($file, $string = null, $width = 0, $height = 0, $proportional = false, $output = 'file', $delete_original = true, $use_linux_commands = false, $quality = 100, $cropFromTop = false) {

        if ($height <= 0 && $width <= 0)
            return false;
        if ($file === null && $string === null)
            return false;

        # Setting defaults and meta
        $info = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
        $image = '';
        $final_width = 0;
        $final_height = 0;
        list($width_old, $height_old) = $info;
        $cropHeight = $cropWidth = 0;

        # Calculating proportionality
        if ($proportional) {
            if ($width == 0)
                $factor = $height / $height_old;
            elseif ($height == 0)
                $factor = $width / $width_old;
            else
                $factor = min($width / $width_old, $height / $height_old);

            $final_width = round($width_old * $factor);
            $final_height = round($height_old * $factor);
        }
        else {
            $final_width = ( $width <= 0 ) ? $width_old : $width;
            $final_height = ( $height <= 0 ) ? $height_old : $height;
            $widthX = $width_old / $width;
            $heightX = $height_old / $height;

            $x = min($widthX, $heightX);
            $cropWidth = ($width_old - $width * $x) / 2;
            $cropHeight = ($height_old - $height * $x) / 2;
        }

        # Loading image to memory according to type
        switch ($info[2]) {
            case IMAGETYPE_JPEG: $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);
                break;
            case IMAGETYPE_GIF: $file !== null ? $image = imagecreatefromgif($file) : $image = imagecreatefromstring($string);
                break;
            case IMAGETYPE_PNG: $file !== null ? $image = imagecreatefrompng($file) : $image = imagecreatefromstring($string);
                break;
            default: return false;
        }


        # This is the resizing/resampling/transparency-preserving magic
        $image_resized = imagecreatetruecolor($final_width, $final_height);
        if (($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG)) {
            $transparency = imagecolortransparent($image);
            $palletsize = imagecolorstotal($image);

            if ($transparency >= 0 && $transparency < $palletsize) {
                $transparent_color = imagecolorsforindex($image, $transparency);
                $transparency = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($image_resized, 0, 0, $transparency);
                imagecolortransparent($image_resized, $transparency);
            } elseif ($info[2] == IMAGETYPE_PNG) {
                imagealphablending($image_resized, false);
                $color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
                imagefill($image_resized, 0, 0, $color);
                imagesavealpha($image_resized, true);
            }
        }

        if ($cropFromTop) {
            $cropHeightFinal = 0;
        } else {
            $cropHeightFinal = $cropHeight;
        }
        imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeightFinal, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);


        # Taking care of original, if needed
        if ($delete_original) {
            if ($use_linux_commands)
                exec('rm ' . $file);
            else
                @unlink($file);
        }

        # Preparing a method of providing result
        switch (strtolower($output)) {
            case 'browser':
                $mime = image_type_to_mime_type($info[2]);
                header("Content-type: $mime");
                $output = NULL;
                break;
            case 'file':
                $output = $file;
                break;
            case 'return':
                return $image_resized;
                break;
            default:
                break;
        }

        # Writing image according to type to the output destination and image quality
        switch ($info[2]) {
            case IMAGETYPE_GIF: imagegif($image_resized, $output);
                break;
            case IMAGETYPE_JPEG: imagejpeg($image_resized, $output, $quality);
                break;
            case IMAGETYPE_PNG:
                $quality = 9 - (int) ((0.9 * $quality) / 10.0);
                imagepng($image_resized, $output, $quality);
                break;
            default: return false;
        }

        return true;
    }

    /////// BY UMAR 19-12-2017 AND 20-12-2017 //////////////////
    function insertRecovery($email, $recovery_key) {
        $this->db->where('email', $email)->update('sh_users', array('recovery_key' => $recovery_key));
    }

    function resetPassword($password, $key) {
        $this->db->where('recovery_key', $key)->update('sh_users', array('password' => md5($password)));
    }

    function getPassword($id) {

        $result = $this->db->get_where('sh_users', array('id' => $id));
        return $result->row()->password;
    }

    function changePassword($id, $password) {
        $this->db->where('id', $id)->update('sh_users', array('password' => $password));
    }

    //////////BY YASIR 19-12-2017 ///////////////////
    public function time_zone_list() {
        $timezones = DateTimeZone::listIdentifiers(DateTimeZone::ALL);
        return $timezones;
    }

    /////// BY SHAHZAIB 21-12-2017 /////////////
    function getUserRoles() {
        $this->db->select('*');
        $this->db->from('roles');
        $query = $this->db->get();
        return $query->result();
    }

    function getRoleCategories($school_id) {
        $this->db->select('role_categories.id as rid , role_categories.*,departments.*');
        $this->db->from('role_categories');
        $this->db->where('role_categories.school_id', $school_id);
        $this->db->where('role_categories.deleted_at', 0);
        $this->db->where('departments.deleted_at', 0);
        $this->db->join('departments', 'departments.id = role_categories.department_id', 'inner');
        $query = $this->db->get();
        return $query->result();
    }

    function getRoleCategoriesById($id) {
        $this->db->select('*');
        $this->db->from('role_categories');
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    //////////BY Yasir 21-12-2017 ///////////////////
    function update_where($tbl_name, $where, $data) {
        $this->db->where($where);
        return $this->db->update($tbl_name, $data);
    }

    function saveAttendance($data) {
       $sql = "Replace INTO sh_attendance (`school_id`, `class_id`, `batch_id`, `user_id`, `date`, `status`, `comment`) VALUES ";
       $values = "";
       foreach ($data as $val) {
           $values .= "(" . $this->session->userdata('userdata')['sh_id'] . "," . $val->class_id . "," . $val->batch_id . "," . $val->id . ",'" . to_mysql_date($val->date) . "','" . $val->status . "','" . $val->comment . "'),";
       }
       $sql = $sql . rtrim($values, ",");
       $this->db->query($sql);
       return true;
   }
    
    // function saveMarksheet($data) {
    // // update exam witha activites sheraz
    //     $sql = "Replace INTO sh_marksheets (`exam_id`,`exam_detail_id`, `obtained_marks`, `grade`, `status`,`student_id`, `school_id`, `class_id`, `batch_id`, `remarks`, `activities`, `total_obtained_marks`, `total_grade`) VALUES ";
    //     $values = "";
    //     foreach ($data as $val) {
    //         if (isset($val->activities) && $val->obtain_marks != "") {
    //             $obtain_activity_marks = 0;
    //             foreach ($val->activities as $key => $value) { 
    //                 $obtain_activity_marks += $value->$key->obtained_marks;
    //             }
    //             $total_obtained_marks = $val->obtain_marks + $obtain_activity_marks;    
    //         }
    //         else {
    //             $total_obtained_marks = $val->obtain_marks;
    //         }
    //         $status = "Pass";
    //         if(!is_null($total_obtained_marks)) {
    //             if($total_obtained_marks < $val->passing_marks){
    //                 $status = "Fail";
    //         }
    //     }
    //         $values .= "(" . $val->exam_id . "," . $val->exam_detail_id . ",'" . $val->obtain_marks . "','".$val->grade."', '" .$status. "',". $val->id . "," . $this->session->userdata('userdata')['sh_id'] . "," . $val->class_id . "," . $val->batch_id . ",'". $val->remarks ."','". json_encode($val->activities) ."','". $total_obtained_marks ."','". $val->total_grade ."'),";
    //     }
    //     $sql = $sql . rtrim($values, ",");
    //     $this->db->query($sql);
    //     return true;
    // }
        
    // function saveMarksheet($data) {
    //     $sql = "Replace INTO sh_marksheets (`exam_id`,`exam_detail_id`, `obtained_marks`, `grade`, `status`,`student_id`, `school_id`, `class_id`, `batch_id`, `remarks`, `activities`, `total_obtained_marks`, `total_grade`) VALUES ";
    //     $values = "";
    //     foreach ($data as $val) {
    //         if (!isset($val->total_grade)) {
    //             $val->total_grade = "";
    //         }
    //         if (isset($val->activities) && $val->obtain_marks != "" && $val->activities != "[]") {
    //             $obtain_activity_marks = 0;
    //             foreach ($val->activities as $key => $value) {
    //                 $obtain_activity_marks += $value->$key->obtained_marks;
    //             }
    //             $total_obtained_marks = $val->obtain_marks + $obtain_activity_marks;
    //         }
    //         else {
    //             $total_obtained_marks = $val->obtain_marks;
    //         }
    //         $status = "Pass";
    //         if(!is_null($total_obtained_marks)) {
    //             if($total_obtained_marks < $val->passing_marks){
    //                 $status = "Fail";
    //         }
    //     }
    //     if ($val->activities == "[]" || $val->activities == null) {
    //         str_replace("", '', $val->activities);
    //         $values .= "(" . $val->exam_id . "," . $val->exam_detail_id . ",'" . $val->obtain_marks . "','".$val->grade."', '" .$status. "',". $val->id . "," . $this->session->userdata('userdata')['sh_id'] . "," . $val->class_id . "," . $val->batch_id . ",'". $val->remarks ."','". $val->activities ."','". $total_obtained_marks ."','". $val->total_grade ."'),";
    //     } else {
    //         $values .= "(" . $val->exam_id . "," . $val->exam_detail_id . ",'" . $val->obtain_marks . "','".$val->grade."', '" .$status. "',". $val->id . "," . $this->session->userdata('userdata')['sh_id'] . "," . $val->class_id . "," . $val->batch_id . ",'". $val->remarks ."','". json_encode($val->activities) ."','". $total_obtained_marks ."','". $val->total_grade ."'),";
    //     }
    //     }
    //     $sql = $sql . rtrim($values, ",");
    //     $this->db->query($sql);
    //     return true;
    // }
    
    function saveMarksheet($data)
    {
        $sql = "Replace INTO sh_marksheets (`exam_id`,`exam_detail_id`, `obtained_marks`, `grade`, `status`,`student_id`, `school_id`, `class_id`, `batch_id`, `remarks`, `activities`, `total_obtained_marks`, `total_grade`) VALUES ";
        $values = "";
        
        foreach ($data as $val) {
            if (!isset($val->total_grade)) {
                $val->total_grade = "";
            }
            if (isset($val->activities) && $val->obtain_marks != "" && sizeof($val->activities) >0) {
                $obtain_activity_marks = 0;
                foreach ($val->activities as $key => $value) {
                    $obtain_activity_marks += $value->$key->obtained_marks;
                }
                $total_obtained_marks = $val->obtain_marks + $obtain_activity_marks;
            }
            else {
                $total_obtained_marks = $val->obtain_marks;
            }
            $status = "Pass";
            if(!is_null($total_obtained_marks)) {
                if($total_obtained_marks < $val->passing_marks){
                    $status = "Fail";
            }
        }
            if ($val->activities == "[]" || $val->activities == null) {
                str_replace("", '', $val->activities);
               $new_activities = "[]";
            } else {
                $new_activities = json_encode($val->activities, JSON_UNESCAPED_UNICODE);
            }

        $values .= "(" . $val->exam_id . "," . $val->exam_detail_id . ",'" . $val->obtain_marks . "','".$val->grade."', '" .$status. "',". $val->id . "," . $this->session->userdata('userdata')['sh_id'] . "," . $val->class_id . "," . $val->batch_id . ",'". $val->remarks ."','". $new_activities ."','". $total_obtained_marks ."','". $val->total_grade ."'),";
        }
        
        // print_r($sql . $values);die;
        $sql = $sql . rtrim($values, ",");
        
        $this->db->query($sql);
        return true;
    }
    
    
    function saveSubjectAssingments($data) {
        $school_id = $this->session->userdata('userdata')['sh_id'];
        $class_id = $data->class_id;
        $batch_id = $data->batch_id;
        $class_name = $this->db->select('name')->from('sh_classes')->where('id', $class_id)->get()->row()->name;
        $batch_name = $this->db->select('name')->from('sh_batches')->where('id', $batch_id)->get()->row()->name;
        $notifications = array();
        foreach ($data->data as $key => $req) {
            $old_teacher_id = "";
            $new_teacher_id = $req->$key->id;
            $teacher_name = $this->db->select('name')->from('sh_users')->where('id', $new_teacher_id)->get()->row()->name;
            $subject_id = $key;
            $subject_name = $this->db->select('name')->from('sh_subjects')->where('id', $subject_id)->get()->row()->name;
            $old_data = $this->db->select('teacher_id')->from('sh_assign_subjects')->where('subject_id', $subject_id)->where('class_id',$class_id)->where('batch_id',$batch_id)->get()->row();
            if($old_data){
                $old_teacher_id = $old_data->teacher_id;
            }
            $data1 = array('school_id' => $school_id, 'teacher_id' => $new_teacher_id, 'subject_id' => $subject_id, 'class_id' => $class_id, 'batch_id' => $batch_id);
            $this->db->replace('sh_assign_subjects', $data1);
            $temp = array();
            $temp1 = array();
            if($old_teacher_id == ""){
                $temp['id'][0] = $new_teacher_id;
                $temp['keyword'] = 'subject_assigned';
                $names = array('class'=>$class_name,'section'=>$batch_name,'subject'=>$subject_name);
                $temp['data'] = $names;
                $notifications[] = $temp;
            } else if($old_teacher_id != $new_teacher_id){
                $temp['id'][0] = $new_teacher_id;
                $temp['keyword'] = 'subject_assigned';
                $names = array('class'=>$class_name,'section'=>$batch_name,'subject'=>$subject_name);
                $temp['data'] = $names;
                $notifications[] = $temp;

                $temp1['id'][0] = $old_teacher_id;
                $temp1['keyword'] = 'subject_assigned_name';
                $names1 = array('class'=>$class_name,'section'=>$batch_name,'subject'=>$subject_name,'teacher'=>$teacher_name);
                $temp1['data'] = $names1;
                $notifications[] = $temp1;
            }
        }
        return $notifications;
    }
    
    /*import student required method*/
   function getImportParentId($req){
       if(isset($req)){
            $this->db->select('id');
            $this->db->from('sh_users');
            $this->db->where('email', $req->email)->where('deleted_at', 0);
            $query =  $this->db->get();

            return $query->result();
           }
        
   }
   
   /*------- Save employee Attendance -------*/
   function saveEmployeeAttendance($data) {
        $sql = "Replace INTO sh_attendance (`class_id`, `batch_id`, `school_id`,`user_id`, `date`, `status`,`comment`) VALUES ";
        $values = "";
        foreach ($data as $val) {
            $values .= "(0,0," . $this->session->userdata('userdata')['sh_id'] . "," . $val->id . ",'" . to_mysql_date($val->date) . "','" . $val->status . "','" . $val->comment . "'),";
        }
        $sql = $sql . rtrim($values, ",");
        $this->db->query($sql);
        return true;
    }
    
   function retakeAttendance($data) {
        $status = 'inprocess';
        $type = 'attendance';
        $sql = "INSERT INTO sh_request_log (`user_id`, `school_id`, `class_id`, `batch_id`, `date`, `type`, `status`) VALUES (" . $this->session->userdata('userdata')['user_id'] . ", ". $this->session->userdata('userdata')['sh_id'] . "," . $data->class_id . "," . $data->batch_id . ",'" . to_mysql_date($data->date) . "','" . $type . " ','" . $status . "')";
       
        $this->db->query($sql);
        return true;
    }
    
    //---------------------API Method-------------------//
    ////////// Login TO Dashboard through various Checks //////////
    function admin_login2_2($email, $password) {
        $sql = "Select "
                . "rol.name as role_name,"
                . "u.id as user_id,u.*,"
                . "sh.url as sh_url, "
                . "sh.name as sh_name, "
                . "sh.logo as sh_logo, "
                . "sh.theme_color as theme_color, "
                . "sh.address as sh_address, "
                . "sh.phone as sh_phone, "
                . "sh.teacher_dept_id as teacher_dept_id, "
                . "sh.accounts_dept_id as accounts_dept_id, "
                . "sh.time_zone as time_zone, "
                . "IFNULL(ay.id,0) as academic_year, "
                . "IFNULL(ay.name,'-') as academic_year_name, "
                . "c.symbol as currency_symbol, "
                . "li.* From sh_users u "
                . "Left Join sh_academic_years ay on u.school_id = ay.school_id and ay.is_active = 'Y' AND ay.deleted_at is null "
                . "Inner Join sh_school sh ON u.school_id=sh.id "
                . "Inner Join sh_roles rol ON u.role_id=rol.id "
                . "Left Join sh_currency c ON sh.currency_symbol = c.code "
                . "Left Join sh_license li "
                . "ON u.school_id=li.school_id Where ( u.email='$email' OR rollno='$email' ) "
                . "AND u.password=md5('$password') AND u.deleted_at = 0 AND (role_id=" . ADMIN_ROLE_ID . " OR role_id = " . STUDENT_ROLE_ID . " OR role_id=" . EMPLOYEE_ROLE_ID . " OR role_id=" . PARENT_ROLE_ID . ") "
                . "order by li.id desc limit 1;";
        //echo $sql; exit;
        $query = $this->db->query($sql);
        return $query->result();
    }
}
