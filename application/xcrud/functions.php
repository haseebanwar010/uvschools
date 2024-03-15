<?php

function publish_action($xcrud) {
    if ($xcrud->get('primary')) {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE base_fields SET `bool` = b\'1\' WHERE id = ' . (int) $xcrud->get('primary');
        $db->query($query);
    }
}

function img_or_document_callback($value, $fieldname, $primary_key, $row, $xcrud) {
    if(!is_null($value) || !empty($value)){
        $val = explode(".",$value);
        if($val[1] == "jpg" || $val[1] == "png" || $val == "jpeg" || $val == "gif"){
            return "<a href='uploads/announcements/".$value."' target='blank'><img style='height: 100px;' src='".base_url()."uploads/announcements/".$value."' /></a>";
        } else {
            return "<a href='uploads/announcements/".$value."' target='blank'>".$value."</a>";
        }
    }
}

function unpublish_action($xcrud) {
    if ($xcrud->get('primary')) {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE base_fields SET `bool` = b\'0\' WHERE id = ' . (int) $xcrud->get('primary');
        $db->query($query);
    }
}

function add_currency($value, $fieldname, $primary_key, $row, $xcrud) {
    $ci = & get_instance();
    return $ci->session->userdata('userdata')['currency_symbol'] . $value;
}

function exception_example($postdata, $primary, $xcrud) {
    // get random field from $postdata
    $postdata_prepared = array_keys($postdata->to_array());
    shuffle($postdata_prepared);
    $random_field = array_shift($postdata_prepared);
    // set error message
    $xcrud->set_exception($random_field, 'This is a test error', 'error');
}

function test_column_callback($value, $fieldname, $primary, $row, $xcrud) {
    return $value . ' - nice!';
}

function after_upload_example($field, $file_name, $file_path, $params, $xcrud) {
    $ext = trim(strtolower(strrchr($file_name, '.')), '.');
    if ($ext != 'pdf' && $field == 'uploads.simple_upload') {
        unlink($file_path);
        $xcrud->set_exception('simple_upload', 'This is not PDF', 'error');
    }
}

function movetop($xcrud) {
    if ($xcrud->get('primary') !== false) {
        $primary = (int) $xcrud->get('primary');
        $db = Xcrud_db::get_instance();
        $query = 'SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`';
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item) {
            if ($item['officeCode'] == $primary && $key != 0) {
                array_splice($result, $key - 1, 0, array($item));
                unset($result[$key + 1]);
                break;
            }
        }

        foreach ($result as $key => $item) {
            $query = 'UPDATE `offices` SET `ordering` = ' . $key . ' WHERE officeCode = ' . $item['officeCode'];
            $db->query($query);
        }
    }
}

function movebottom($xcrud) {
    if ($xcrud->get('primary') !== false) {
        $primary = (int) $xcrud->get('primary');
        $db = Xcrud_db::get_instance();
        $query = 'SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`';
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item) {
            if ($item['officeCode'] == $primary && $key != $count - 1) {
                unset($result[$key]);
                array_splice($result, $key + 1, 0, array($item));
                break;
            }
        }

        foreach ($result as $key => $item) {
            $query = 'UPDATE `offices` SET `ordering` = ' . $key . ' WHERE officeCode = ' . $item['officeCode'];
            $db->query($query);
        }
    }
}

function show_description($value, $fieldname, $primary_key, $row, $xcrud) {
    $result = '';
    if ($value == '1') {
        $result = '<i class="fa fa-check" />' . 'OK';
    } elseif ($value == '2') {
        $result = '<i class="fa fa-circle-o" />' . 'Pending';
    }
    return $result;
}

function custom_field($value, $fieldname, $primary_key, $row, $xcrud) {
    return '<input type="text" readonly class="xcrud-input" name="' . $xcrud->fieldname_encode($fieldname) . '" value="' . $value .
    '" />';
}

function unset_val($postdata) {
    $postdata->del('Paid');
}

function format_phone($new_phone) {
    $new_phone = preg_replace("/[^0-9]/", "", $new_phone);

    if (strlen($new_phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $new_phone);
    elseif (strlen($new_phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $new_phone);
    else
        return $new_phone;
}

function before_list_example($list, $xcrud) {
    var_dump($list);
}


    // Delete Exceptions Added By zafar start
function before_delete_subject($primary, $xcrud)
{
    $db = Xcrud_db::get_instance();
    $id = $db->escape($primary);
    $query="SELECT id,class_id,school_id,batch_id FROM sh_subjects WHERE id = $id";   
    $data = $db->query($query);
   $result = $db->row($data);

            $class_id = $result["class_id"];
            $school_id = $result["school_id"];
            $batch_id = $result["batch_id"];
            $idd = $result["id"];
            $q = $db->query("SELECT subjects FROM sh_subject_groups where class_id = $class_id and school_id = $school_id and batch_id = $batch_id");
                
            $r = $db->row($q);
            $rr= (implode($r));
            $rrr=(explode(',' , $rr));
            $l = sizeof($rrr);
            
            $res = 0;
            for($i=0; $i<=$l-1;$i++)
            {
            $n = $rrr[$i];
            if($n== $idd)
            {
                $q1 = $db->query("SELECT group_name FROM sh_subject_groups where class_id = $class_id and school_id = $school_id and batch_id = $batch_id");
                $na = $db->row($q1);
                $name= (implode($na));
                $q2 = $db->query("SELECT teacher_id,assistant_id FROM sh_assign_subjects WHERE subject_id = $id");
                $sub = $db->row($q2);
                $teacher_id = $sub["teacher_id"];
                $assistant_id = $sub["assistant_id"];
                $q3 = $db->query("SELECT name FROM sh_users WHERE id = $teacher_id OR id = $assistant_id");
                $t_name = $db->row($q3);
                // print_r($t_name); die(); 
                $teacher= (implode($t_name));
                $res = 1;
            }
            }
            if($res== 1)
            {

            $xcrud->set_exception('name', lang('del_error').lang('subject_group_execption').$name.lang('subject_teacher').$teacher , 'error');
            
            }
           
}
function before_delete_subjects($primary , $xcrud)
{
    $db = Xcrud_db::get_instance();
        $id = $db->escape($primary);

        $query="SELECT class_id,school_id,batch_id FROM sh_subjects WHERE id = $id";   
        $data = $db->query($query);
        $result = $db->row($data);

        $class_id = $result["class_id"];
        $school_id = $result["school_id"];
        $batch_id = $result["batch_id"];
        
        $q = $db->query("SELECT teacher_id,assistant_id FROM sh_assign_subjects where class_id = $class_id and school_id = $school_id and batch_id = $batch_id and subject_id = $id");           
        $r = $db->row($q);

        if (($r['teacher_id'] != 0 && $r['teacher_id'] != NULL) || ($r['assistant_id'] != 0 && $r['assistant_id'] != NULL) ) {

            $query2 = $db->query("UPDATE sh_assign_subjects SET teacher_id=0, assistant_id=0 WHERE subject_id=$id");                    
        }

        $query3 = $db->query("SELECT id,subjects FROM sh_subject_groups where  FIND_IN_SET($id, subjects) and class_id = $class_id and school_id = $school_id and batch_id = $batch_id and deleted_at is NULL");

            $r2 = $db->row($query3);
            
            if (count($r2) > 0) {

                $subject_group_id = $r2['id'];

                $subjects = explode(",", $r2['subjects']);

                foreach ($subjects as $key => $subject) {
                    $id = str_replace("'", "", $id);
                    if ($subject == $id) {
                        unset($subjects[$key]);
                    }
                } 
                $subject_ids = implode(",", $subjects);
                
                $query4 = $db->query("UPDATE sh_subject_groups SET subjects='$subject_ids' WHERE class_id = $class_id and school_id = $school_id and batch_id = $batch_id and id=$subject_group_id and deleted_at is NULL");   
            }
}
function before_delete_class_level($primary , $xcrud)
{
    $db = Xcrud_db::get_instance();
        $id = $db->escape($primary);

        $query="SELECT id,name,school_id FROM sh_classes WHERE level_id = $id";   
        $data = $db->query($query);
        $result = $db->row($data);

        $class_id = $result["id"];
        $school_id = $result["school_id"];
        $class_name = $result["name"];
        
        if($class_name != '')
        {

            $xcrud->set_exception('name', lang('del_error').lang('classes').$class_name, 'error');
            
        }
        
}

function before_delete_classes($primary , $xcrud)
{
        $db = Xcrud_db::get_instance();
        $id = $db->escape($primary);
        
        $query="SELECT school_id FROM sh_classes WHERE id=$id and deleted_at is NULL";   
        $data = $db->query($query);
        $result = $db->row($data);
        $school_id = $result["school_id"];

        $q1 = "SELECT * FROM sh_batches WHERE class_id=$id and school_id=$school_id and deleted_at is NULL";   
        $d1 = $db->query($q1);
        $batches = $db->result($d1);

        if (count($batches) > 0) {

            $xcrud->set_exception('name', lang('del_batch_first'), 'error');

        }        
        
}

function before_delete_batches($primary , $xcrud)
{
        $db = Xcrud_db::get_instance();
        $id = $db->escape($primary);
        $ci = & get_instance();
        $academic_year_id = $ci->session->userdata('userdata')['academic_year'];
        $school_id = $ci->session->userdata('userdata')['sh_id'];


        $query4 = "SELECT class_id FROM sh_batches WHERE id=$id AND deleted_at is NULL";
        $db->query($query4);
        $result4 = $db->result();
        $class_id = $result4[0]['class_id'];
        

        $query3 = "SELECT * FROM sh_students_$school_id WHERE class_id=$class_id AND batch_id=$id AND academic_year_id=$academic_year_id";
        $db->query($query3);
        $result3 = $db->result();

        if (count($result3) > 0) {
            
            $xcrud->set_exception('name', lang('del_students_first'), 'error');

        }


        $query2 = "SELECT * FROM sh_subjects WHERE batch_id=$id AND academic_year_id=$academic_year_id AND deleted_at is NULL";
        $db->query($query2);
        $result2 = $db->result();

        if (count($result2) > 0) {
            
            $xcrud->set_exception('name', lang('del_subject_first'), 'error');

        }


        $query="SELECT subject_id,teacher_id,assistant_id FROM sh_assign_subjects WHERE batch_id = $id AND (teacher_id != 0 OR assistant_id != 0 ) ";  

        $db->query($query);
        $result = $db->result();

        if(count($result) > 0)
        {
            foreach($result as $row)
            {
                    $subject_id = $row['subject_id'];
                    $assistant_id = $row['assistant_id'];
                    $teacher_id = $row['teacher_id'];        
                    $q1="SELECT name FROM sh_subjects WHERE id = $subject_id  ";   
                    $d1 = $db->query($q1);
                    $r1 = $db->row($d1);
                    $subject = $r1["name"];
                    if($teacher_id != '')
                    {
                        $q2="SELECT name FROM sh_users WHERE id = $teacher_id";   
                        $d2 = $db->query($q2);
                        $r2 = $db->row($d2);
                        if($r2)
                        {
                            $teacher = $r2["name"];
                            $message[]= "($subject , $teacher)";
                        }
                
                    }
                    if($assistant_id != '')
                    {
                        $q3="SELECT name FROM sh_users WHERE id = $assistant_id";   
                        $d3 = $db->query($q3);
                        $r3 = $db->row($d3);
                        if($r3)
                        {
                            $teacher = $r3["name"];
                            $message[]= "($subject , $teacher)";
                        }
                
                    }
            }
                $m = implode(',' , $message );
                $xcrud->set_exception('name',lang('del_error').lang('batch_class').$m , 'info');            
        }

}

function before_delete_academic_year($primary , $xcrud)
{
    $db = Xcrud_db::get_instance();
        $id = $db->escape($primary);
        $query="SELECT name FROM sh_classes WHERE academic_year_id = $id";   
        $data = $db->query($query);
        $result = $db->result($data);
        $error = "";
        foreach ($result as $key => $value) {
            $error[] = $value["name"];
        }
        
        //$class_name = $result["name"];
        if($error != ''){
            $xcrud->set_exception('name', lang('del_error').lang('academic_year'). implode(' ', $error), 'error');
        }
                    
          
        
        // die();
        // print_r($name); die();
}


// Delete Exceptions Added By zafar end

function soft_delete($primary, $xcrud) {
    $db = Xcrud_db::get_instance();
    $db->query("UPDATE " . $xcrud->table . " set deleted_at='" . date("Y-m-d h:i:s") . "' Where id = " . $db->escape($primary));
}

function soft_delete_payroll_groups($primary, $xcrud) {
    $db = Xcrud_db::get_instance();
    $db->query("UPDATE " . $xcrud->table . " set deleted_at='" . date("Y-m-d h:i:s") . "' Where id = " . $db->escape($primary));
}

function form_category_delete($primary, $xcrud) {
    $db = Xcrud_db::get_instance();
    $query = "SELECT * FROM sh_form_categories WHERE id =" . $primary;
    $db->query($query);
    if ($db->row()["tag"] == 'is_system') {
        $xcrud->set_exception('tag', lang('system_cat_validate'), 'error');
    } else {
        $query = "UPDATE sh_form_categories SET deleted_at='" . date('Y-m-d h:i:s') . "' WHERE id=" . $primary;
        $db->query($query);
    }
}

function primaryicon($value, $fieldname, $primary_key, $row, $xcrud) {
    return $value == "Y" ? '<i class="fa fa-check"></i>' : "";
}

function primaryiconcurrency($value, $fieldname, $primary_key, $row, $xcrud) {
    return $value == "yes" ? '<i class="fa fa-check"></i>' : "";
}

function status($value, $fieldname, $primary_key, $row, $xcrud) {
    return $value == 1 ? "Active" : "Disable";
}

function checkCurrency($postdata, $xcrud){
    $currency_id = $postdata->get('currency_id');
    $school_id = $postdata->get('school_id');
    $is_default = $postdata->get('is_default');
    $query = "select id from sh_school_currencies where currency_id = $currency_id and school_id = $school_id and deleted_at is null";
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $result = $db->row();

    if($result){
        $xcrud->set_exception('currency_id', "Currency already exist.");
    }else if($is_default == "yes"){
        $query = "update sh_school_currencies set is_default = 'no' where school_id = $school_id";
        $db = Xcrud_db::get_instance();
        $db->query($query);
    }

    $query = "select name,symbol from sh_currency where id = $currency_id";
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $result = $db->row();

    $postdata->set('currency_name',$result['name']);
    $postdata->set('currency_symbol',$result['symbol']);



}

function checkCurrencyUpdate($postdata, $primary, $xcrud){
    $currency_id = $postdata->get('currency_id');
    $school_id = $postdata->get('school_id');
    $is_default = $postdata->get('is_default');
    $query = "select id from sh_school_currencies where currency_id = $currency_id and school_id = $school_id and deleted_at is null and id <> $primary";
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $result = $db->row();

    if($result){
        $xcrud->set_exception('currency_id', "Currency already exist.");
    }else if($is_default == "yes"){
        $query = "update sh_school_currencies set is_default = 'no' where school_id = $school_id";
        $db = Xcrud_db::get_instance();
        $db->query($query);
    }

    $query = "select name,symbol from sh_currency where id = $currency_id";
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $result = $db->row();

    $postdata->set('currency_name',$result['name']);
    $postdata->set('currency_symbol',$result['symbol']);
}

function deleteOtherPrimary($postdata, $xcrud) {
    $start = $postdata->get('start_date');
    $end = $postdata->get('end_date');
    $query = "select count(case when CAST('$start' AS date) <= end_date and CAST('$end' AS date) >= start_date then 1 end) as overlap from sh_academic_years where school_id=".$postdata->get('school_id')." and deleted_at is null";
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $overlap = $db->row()['overlap'];
    if ($postdata->get('end_date') < $postdata->get('start_date')) {
        $xcrud->set_exception('end_date', lang('date_xcrud'));
    } else if($postdata->get('end_date') == $postdata->get('start_date')){
        $xcrud->set_exception('end_date', lang('start_date_same'));
    } else if($overlap > 0){
        $xcrud->set_exception('start_date', lang('academic_year_overlap'));
    } else if ($postdata->get('is_active') == "Y") {
        $db = Xcrud_db::get_instance();
        $query = "select * from sh_academic_years where school_id =" . $postdata->get('school_id') . " and is_active='Y' ";
        $db->query($query);
        foreach ($db->result() as $val) {
            $query = "update sh_academic_years set is_active='N' Where id=" . $val["id"];
            $db->query($query);
        }
    }
}

function check_evaluation($postdata, $xcrud) {
    $term_id = $postdata->get('term_id');
    $type = $postdata->get('type');
    $classes = $postdata->get('classes');
    $classes = explode(",", $classes);
    foreach ($classes as $key => $value) {
        $db = Xcrud_db::get_instance();
        $query = "select id from sh_evaluations where type ='" . $type . "' and find_in_set(".$value.",classes) > 0 and deleted_at is null and term_id = '".$term_id."'";
        $db->query($query);
        if(count($db->result()) > 0){
            $query = "select name from sh_classes where id = ".$value;
            $db->query($query);
            $class = $db->row()['name'];
            $xcrud->set_exception('classes', $type.' evaluation type already exist for '.$class.'.');
            break;
        }
    }
    

    
}

function check_evaluation_update($postdata, $primary, $xcrud) {
    $term_id = $postdata->get('term_id');
    $type = $postdata->get('type');
    $classes = $postdata->get('classes');
    $classes = explode(",", $classes);
    $db = Xcrud_db::get_instance();
    foreach ($classes as $key => $value) {
        
        $query = "select id from sh_evaluations where type ='" . $type . "' and find_in_set(".$value.",classes) > 0 and deleted_at is null and term_id = '".$term_id."' and id <> ".$primary;
        $db->query($query);
        if(count($db->result()) > 0){
            $query = "select name from sh_classes where id = ".$value;
            $db->query($query);
            $class = $db->row()['name'];
            $xcrud->set_exception('classes', $type.' evaluation type already exist for '.$class.'.');
            break;
        }
    }
    

    
}

function checkValidation($postdata, $xcrud) {

    $from = $postdata->get('percent_from');
    $to = $postdata->get('percent_upto');
    if ($from >= 0 && $from <= 100) {

    } else {
        $xcrud->set_exception('percent_from', lang('grade_validation'));
    }

    if ($to >= 0 && $to <= 100) {

    } else {
        $xcrud->set_exception('percent_upto', lang('grade_validation'));
    }
}

function check_exam_name($postdata, $xcrud) {
    $academic_year_id = $postdata->get('academic_year_id');
    
    $db = Xcrud_db::get_instance();
    $query = "select id from sh_online_exams where school_id =" . $postdata->get('school_id') . " and title ='" . $postdata->get('title') . "' and deleted_at is null and academic_year_id = ".$academic_year_id;
    $db->query($query);

    if($db->row()){
        $xcrud->set_exception('title', "Exam name already exists");
    }
    
}

function check_exam_name_update($postdata, $primary, $xcrud) {
    $academic_year_id = $postdata->get('academic_year_id');
    
    $db = Xcrud_db::get_instance();
    $query = "select id from sh_online_exams where school_id =" . $postdata->get('school_id') . " and title ='" . $postdata->get('title') . "' and deleted_at is null and academic_year_id = ".$academic_year_id." and id <>".$primary;
    $db->query($query);

    if($db->row()){
        $xcrud->set_exception('title', "Exam name already exists");
    }
    
}

function check_exam_details_name($postdata, $xcrud) {
    $academic_year_id = $postdata->get('academic_year_id');
    
    $db = Xcrud_db::get_instance();
    $query = "select id from sh_online_exam_details where school_id =" . $postdata->get('school_id') . " and paper_name ='" . $postdata->get('paper_name') . "' and deleted_at is null and academic_year_id = ".$academic_year_id." and exam_id = ".$postdata->get('exam_id');
    $db->query($query);

    if($db->row()){
        $xcrud->set_exception('paper_name', "Paper name already exists in this exam");
    }
    
}

function check_exam_details_name_update($postdata, $primary, $xcrud) {
    $academic_year_id = $postdata->get('academic_year_id');
    
    $db = Xcrud_db::get_instance();
    $query = "select id from sh_online_exam_details where school_id =" . $postdata->get('school_id') . " and paper_name ='" . $postdata->get('paper_name') . "' and deleted_at is null and academic_year_id = ".$academic_year_id." and id <>".$primary." and exam_id = ".$postdata->get('exam_id');
    $db->query($query);

    if($db->row()){
        $xcrud->set_exception('paper_name', "Paper name already exists in this exam");
    }
    
}

function checkValidationUpdate($postdata, $primary, $xcrud) {

    $from = $postdata->get('percent_from');
    $to = $postdata->get('percent_upto');
    if ($from >= 0 && $from <= 100) {

    } else {
        $xcrud->set_exception('percent_from', lang('grade_validation'));
    }

    if ($to >= 0 && $to <= 100) {

    } else {
        $xcrud->set_exception('percent_upto', lang('grade_validation'));
    }
}

function addSection($postdata, $xcrud) {
    $db = Xcrud_db::get_instance();
    $query = "insert into sh_batches(school_id,name,academic_year_id,class_id,teacher_id) values(" . $postdata->get('school_id') . ",'" . $postdata->get('name') . "'," . $postdata->get('academic_year_id') . ",'" . $postdata->get('class_id') . "','" .  $postdata->get('teacher_id') . "')";
    $db->query($query);
}

function subjectsAdd($postdata, $xcrud) {
    if ($postdata->get('batch_id') == "") {

        $db = Xcrud_db::get_instance();
        $query = "select id from sh_batches where school_id =" . $postdata->get('school_id') . " and class_id =" . $postdata->get('class_id') . " and deleted_at is null ";
        $db->query($query);
        foreach ($db->result() as $val) {
            $query = "insert into sh_subjects(school_id,class_id,batch_id,name,code,weekly_classes,academic_year_id) values(" . $postdata->get('school_id') . "," . $postdata->get('class_id') . "," . $val["id"] . ",'" . $postdata->get('name') . "','" . $postdata->get('code') . "'," . $postdata->get('weekly_classes') . ",".$postdata->get('academic_year_id').")";
            $db->query($query);
        }
    } else {
        $query = "insert into sh_subjects(school_id,class_id,batch_id,name,code,weekly_classes,academic_year_id) values(" . $postdata->get('school_id') . "," . $postdata->get('class_id') . "," . $postdata->get('batch_id') . ",'" . $postdata->get('name') . "','" . $postdata->get('code') . "'," . $postdata->get('weekly_classes') . ",".$postdata->get('academic_year_id').")";
        $db = Xcrud_db::get_instance();
        $db->query($query);
    }
}

function check_academic_year($postdata, $xcrud) {
    $academic_year_id = $postdata->get('academic_year_id');
    if($academic_year_id == null){
        $xcrud->set_exception('', lang('no_academic_year'));
    }   
}

function section_check($postdata, $primary, $xcrud) {
    $start = $postdata->get('start_time');
    $end = $postdata->get('end_time');
    $class_id = $postdata->get('class_id');
    $batch_id = $postdata->get('batch_id');
    $school_id = $postdata->get('school_id');
    if ($postdata->get('batch_id') == "") {
        $xcrud->set_exception('batch_id', lang('choose_section'));
    } else {
        $db = Xcrud_db::get_instance();
        if ($batch_id != "") {
            $query = "select count(case when CAST('$start' AS time) < end_time and start_time < CAST('$end' AS time) then 1 end) as overlap from sh_periods where school_id=$school_id and class_id=$class_id and batch_id=$batch_id and deleted_at is null and id!=$primary";
        } else {
            $query = "select count(case when CAST('$start' AS time) < end_time and start_time < CAST('$end' AS time) then 1 end) as overlap from sh_periods where school_id=$school_id and class_id=$class_id and deleted_at is null and id!=$primary";
        }
        $db->query($query);
        $overlap = $db->row()['overlap'];

        if ($start > $end) {
            $xcrud->set_exception('end_time', lang('end_time_validation'));
        } else if ($start == $end) {
            $xcrud->set_exception('end_time', lang('start_time_validation'));
        } else if ($overlap > 0) {
            $xcrud->set_exception('end_time', lang('period_overlap'));
        }
    }
}

function periodsAdd($postdata, $xcrud) {
    if ($postdata->get('batch_id') == "") {

        $db = Xcrud_db::get_instance();
        $query = "select id from sh_batches where school_id =" . $postdata->get('school_id') . " and class_id =" . $postdata->get('class_id') . " and deleted_at is null ";
        $db->query($query);
        foreach ($db->result() as $val) {
            $query = "insert into sh_periods(school_id,title,start_time,end_time,class_id,batch_id,is_break,academic_year_id) values(" . $postdata->get('school_id') . ",'" . $postdata->get('title') . "','" . $postdata->get('start_time') . "','" . $postdata->get('end_time') . "'," . $postdata->get('class_id') . "," . $val["id"] . ",'" . $postdata->get('is_break') . "',".$postdata->get('academic_year_id').")";
            $db->query($query);
        }
    } else {
        $query = "insert into sh_periods(school_id,title,start_time,end_time,class_id,batch_id,is_break,academic_year_id) values(" . $postdata->get('school_id') . ",'" . $postdata->get('title') . "','" . $postdata->get('start_time') . "','" . $postdata->get('end_time') . "'," . $postdata->get('class_id') . "," . $postdata->get('batch_id') . ",'" . $postdata->get('is_break') . "',".$postdata->get('academic_year_id').")";
        $db = Xcrud_db::get_instance();
        $db->query($query);
    }
}

function checkOverlap($postdata, $xcrud) {
    $start = $postdata->get('start_time');
    $end = $postdata->get('end_time');
    $class_id = $postdata->get('class_id');
    $batch_id = $postdata->get('batch_id');
    $school_id = $postdata->get('school_id');


    $db = Xcrud_db::get_instance();
    if ($batch_id != "") {
        $query = "select count(case when CAST('$start' AS time) < end_time and start_time < CAST('$end' AS time) then 1 end) as overlap from sh_periods where school_id=$school_id and class_id=$class_id and batch_id=$batch_id and deleted_at is null";
    } else {
        $query = "select count(case when CAST('$start' AS time) < end_time and start_time < CAST('$end' AS time) then 1 end) as overlap from sh_periods where school_id=$school_id and class_id=$class_id and deleted_at is null";
    }
    $db->query($query);
    $overlap = $db->row()['overlap'];

    if ($start > $end) {
        $xcrud->set_exception('end_time', lang('end_time_validation'));
    } else if ($start == $end) {
        $xcrud->set_exception('end_time', lang('start_time_validation'));
    } else if ($overlap > 0) {
        $xcrud->set_exception('end_time', lang('period_overlap'));
    }
}

function new_subject_group($postdata, $xcrud){
    $batch_id = $postdata->get('batch_id');
    $class_id = $postdata->get('class_id');
    $school_id = $postdata->get('school_id');
    $group_name = $postdata->get('group_name');
    $subjects = $postdata->get('subjects');
    if($subjects[0] == ","){
        $subjects = substr($subjects,1);
    }
    $subjects = explode(",", $subjects);
    sort($subjects , SORT_NUMERIC);
    $subjects = implode(",", $subjects);
    $postdata->set('subjects',$subjects);

    $db = Xcrud_db::get_instance();
    $query = "select id from sh_subject_groups where batch_id =" . $batch_id . " and group_name = '". $group_name ."' and deleted_at is null ";
    $db->query($query);
    if (count($db->result()) > 0) {
        $xcrud->set_exception('group_name', lang('subject_group_name_exist'));
    }

    $query = "select id from sh_subject_groups where subjects ='" . $subjects . "' and batch_id = ". $batch_id ." and deleted_at is null ";
    $db->query($query);
    if (count($db->result()) > 0) {
        $xcrud->set_exception('subjects', lang('subject_group_exist'));
    }

}
function new_subject_group_update($postdata, $primary, $xcrud){
    $batch_id = $postdata->get('batch_id');
    $class_id = $postdata->get('class_id');
    $school_id = $postdata->get('school_id');
    $group_name = $postdata->get('group_name');
    $subjects = $postdata->get('subjects');
    if($subjects[0] == ","){
        $subjects = substr($subjects,1);
    }
    $subjects = explode(",", $subjects);
    sort($subjects , SORT_NUMERIC);
    $subjects = implode(",", $subjects);
    $postdata->set('subjects',$subjects);

    $db = Xcrud_db::get_instance();
    $query = "select id from sh_subject_groups where batch_id =" . $batch_id . " and group_name = '". $group_name ."' and deleted_at is null and id <> ".$primary;
    $db->query($query);
    if (count($db->result()) > 0) {
        $xcrud->set_exception('group_name', lang('subject_group_name_exist'));
    }

    $query = "select id from sh_subject_groups where subjects ='" . $subjects . "' and batch_id = ". $batch_id ." and deleted_at is null and id <> ".$primary;
    $db->query($query);
    if (count($db->result()) > 0) {
        $xcrud->set_exception('subjects', lang('subject_group_exist'));
    }

}
function section_count($postdata, $xcrud) {
    $batch_id = $postdata->get('batch_id');
    $class_id = $postdata->get('class_id');
    $school_id = $postdata->get('school_id');
    $name = $postdata->get('name');
    $code = $postdata->get('code');

    $where_name = "select id from sh_subjects where class_id=" . $class_id . " and school_id=" . $school_id . " and name='" . $name . "' and deleted_at is null";
    $where_code = "select id from sh_subjects where class_id=" . $class_id . " and school_id=" . $school_id . " and code='" . $code . "' and deleted_at is null";

    if ($batch_id == "") {
        $db = Xcrud_db::get_instance();
        $query = "select id from sh_batches where school_id =" . $school_id . " and class_id =" . $class_id . " and deleted_at is null ";
        $db->query($query);

        if (count($db->result()) == 0) {
            $xcrud->set_exception('batch_id', lang('class_validation'));
        }
        $query = $where_name;
        $db->query($query);
        if (count($db->result()) > 0) {
            $xcrud->set_exception('name', lang('sub_name_xcrud'));
        } else {
            $query = $where_code;
            $db->query($query);
            if (count($db->result()) > 0) {
                $xcrud->set_exception('code', lang('sub_code_xcrud'));
            }
        }
    } else {
        $db = Xcrud_db::get_instance();
        $query = $where_name . " and batch_id=" . $batch_id;
        $db->query($query);
        if (count($db->result()) > 0) {
            $xcrud->set_exception('name', lang('subject_name_xcrud'));
        } else {
            $query = $where_code . " and batch_id=" . $batch_id;
            $db->query($query);
            if (count($db->result()) > 0) {
                $xcrud->set_exception('code', lang('subject_code_xcrud'));
            }
        }
    }
}

function date_validation($postdata, $xcrud) {
    if ($postdata->get('end_date') < $postdata->get('start_date')) {
        $xcrud->set_exception('end_date', lang('date_xcrud'));
    }
}

function section_check_subject($postdata, $primary, $xcrud) {
    $batch_id = $postdata->get('batch_id');
    if ($postdata->get('batch_id') == "") {
        $xcrud->set_exception('batch_id', lang('choose_section'));
    }
}

function add_user_icon($value, $fieldname, $primary_key, $row, $xcrud) {
    return '<button type="button" ng-click="getDiscountVarients(' . $primary_key . ')" class="btn btn-default" data-toggle="modal" data-target="#feeDiscountVarientModal">Varient</button>';
}

function remove_link($postdata, $xcrud) {
    $price = $postdata->get('price');
    $postdata->set('link',preg_replace('(http[s]?:[/]?[/]?)', '', $postdata->get('link')));
    if($price < 0){
        $xcrud->set_exception('price', lang('book_shop_price'));
    }
}
function remove_link_update($postdata, $primary, $xcrud) {
    $price = $postdata->get('price');
    $postdata->set('link',preg_replace('(http[s]?:[/]?[/]?)', '', $postdata->get('link')));
    if($price < 0){
        $xcrud->set_exception('price', lang('book_shop_price'));
    }
}

function check_exam_session($postdata, $xcrud) {
    $start = $postdata->get('start_date');
    $end = $postdata->get('end_date');


    if ($start > $end) {
        $xcrud->set_exception('end_date', lang('date_xcrud'));
    }
}

function setRepeated($postdata, $xcrud){
    $income_type_id = $postdata->get('income_type_id');
    $query = "select repeated from sh_income_types where id = ".$income_type_id;
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $result = $db->row();
    $postdata->set('repeated', $result['repeated']);
}

function setRepeatedUpdate($postdata, $primary, $xcrud){
    $income_type_id = $postdata->get('income_type_id');
    $query = "select repeated from sh_income_types where id = ".$income_type_id;
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $result = $db->row();
    $postdata->set('repeated', $result['repeated']);
}

function setRepeatedExpense($postdata, $xcrud){
    $expense_type_id = $postdata->get('expense_type_id');
    $query = "select repeated from sh_expense_types where id = ".$expense_type_id;
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $result = $db->row();
    $postdata->set('repeated', $result['repeated']);
}

function setRepeatedExpenseUpdate($postdata, $primary, $xcrud){
    $expense_type_id = $postdata->get('expense_type_id');
    $query = "select repeated from sh_expense_types where id = ".$expense_type_id;
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $result = $db->row();
    $postdata->set('repeated', $result['repeated']);
}

function check_exam_session_update($postdata, $primary, $xcrud) {
    $start = $postdata->get('start_date');
    $end = $postdata->get('end_date');


    if ($start > $end) {
        $xcrud->set_exception('end_date', lang('date_xcrud'));
    }
}
function update_academic_year($postdata, $primary, $xcrud) {
    $ci = & get_instance();
    $result = $ci->db->select('id,name')->from('sh_academic_years')->where('is_active',"Y")->where('school_id',$ci->session->userdata("userdata")["sh_id"])->get()->row();
    $new_id = 0;
    $new_name = "-";
    if(!empty($result)){
        $new_id = $result->id;
        $new_name = $result->name;
    }

    $oldValues = $ci->session->userdata("userdata");
    $oldValues["academic_year"] = $new_id;
    $oldValues["academic_year_name"] = $new_name;
    $ci->session->set_userdata("userdata",$oldValues);
    reloadPage($primary, $xcrud);


}

function updateCurrency($postdata, $primary, $xcrud) {
    $ci = & get_instance();
    $result = $ci->db->select('symbol')->from('sh_school_currencies sc')->join('sh_currency c','c.id = sc.currency_id and is_default = "yes"','left')->where('school_id',$ci->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->get()->row();
    $new_symbol = "";
    if(!empty($result)){
        $new_symbol = $result->symbol;
    }
    $oldValues = $ci->session->userdata("userdata");
    $oldValues["currency_symbol"] = $new_symbol;
    $ci->session->set_userdata("userdata",$oldValues);
}

function updateCurrencyDelete($primary, $xcrud) {
    $ci = & get_instance();
    $result = $ci->db->select('symbol')->from('sh_school_currencies sc')->join('sh_currency c','c.id = sc.currency_id and is_default = "yes"','left')->where('school_id',$ci->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->get()->row();
    $new_symbol = "";
    if(!empty($result)){
        $new_symbol = $result->symbol;
    }
    $oldValues = $ci->session->userdata("userdata");
    $oldValues["currency_symbol"] = $new_symbol;
    $ci->session->set_userdata("userdata",$oldValues);
}

function update_academic_year_delete($primary, $xcrud) {
    $ci = & get_instance();
    $result = $ci->db->select('id,name')->from('sh_academic_years')->where('is_active',"Y")->where('school_id',$ci->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->get()->row();
    $new_id = 0;
    $new_name = "-";
    if(count($result) > 0){
        $new_id = $result->id;
        $new_name = $result->name;
    }

    $oldValues = $ci->session->userdata("userdata");
    $oldValues["academic_year"] = $new_id;
    $oldValues["academic_year_name"] = $new_name;
    $ci->session->set_userdata("userdata",$oldValues);
    reloadPage($primary, $xcrud);


}

function deleteOtherPrimary_update($postdata, $primary, $xcrud) {
    $start = $postdata->get('start_date');
    $end = $postdata->get('end_date');
    $query = "select count(case when CAST('$start' AS date) <= end_date and CAST('$end' AS date) >= start_date then 1 end) as overlap from sh_academic_years where school_id=".$postdata->get('school_id')." and deleted_at is null and id!=$primary";
    $db = Xcrud_db::get_instance();
    $db->query($query);
    $overlap = $db->row()['overlap'];
    if ($postdata->get('end_date') < $postdata->get('start_date')) {
        $xcrud->set_exception('end_date', lang('date_xcrud'));
    } else if($postdata->get('end_date') == $postdata->get('start_date')){
        $xcrud->set_exception('end_date', lang('start_date_same'));
    } else if($overlap > 0){
        $xcrud->set_exception('start_date', lang('academic_year_overlap'));
    } else if ($postdata->get('is_active') == "Y") {
        $db = Xcrud_db::get_instance();
        $query = "select * from sh_academic_years where school_id =" . $postdata->get('school_id') . " and is_active='Y' ";
        $db->query($query);
        foreach ($db->result() as $val) {
            $query = "update sh_academic_years set is_active='N' Where id=" . $val["id"];
            $db->query($query);
        }
    }
}

function date_validation_update($postdata, $primary, $xcrud) {
    if ($postdata->get('end_date') < $postdata->get('start_date')) {
        $xcrud->set_exception('end_date', lang('date_xcrud'));
    }
}

function check_exam_details($postdata, $xcrud) {
    $exam_id = $postdata->get('exam_id');
    $class_id = $postdata->get('class_id');
    $batch_id = $postdata->get('batch_id');
    $subject_id = $postdata->get('subject_id');
    $exam_date = $postdata->get('exam_date');
    $start_time = $postdata->get('start_time');
    $end_time = $postdata->get('end_time');
    $total_marks = $postdata->get('total_marks');
    $passing_marks = $postdata->get('passing_marks');
    $school_id = $postdata->get('school_id');

    $db = Xcrud_db::get_instance();
    $query = "select id from sh_exam_details where school_id =" . $school_id . " and subject_id =" . $subject_id . " and exam_id=".$exam_id." and deleted_at is null ";
    $db->query($query);
    $exam_detail_count = count($db->result());
    $query = "select start_date, end_date from sh_exams where id = ".$exam_id;
    $db->query($query);
    $result = $db->row();
    $start_session = $result['start_date'];
    $end_session = $result['end_date'];
    if($exam_detail_count > 0){
        $xcrud->set_exception('subject_id',lang('exam_already'));
    }
    else if($exam_date < $start_session || $end_session < $exam_date){
        $xcrud->set_exception('exam_date',lang('exam_out'));
    }
    else if ($start_time == $end_time){
        $xcrud->set_exception('start_time,end_time',lang('start_time_validation'));
    }
    else if($end_time < $start_time){
        $xcrud->set_exception('start_time,end_time',lang('end_time_validation'));
    }
    else if($total_marks < 0){
        $xcrud->set_exception('total_marks',lang('total_negative'));
    }
    else if($passing_marks < 0){
        $xcrud->set_exception('passing_marks',lang('pasing_negative'));
    }
    else if($total_marks < $passing_marks){
        $xcrud->set_exception('passing_marks',lang('passing_greater'));
    }
    else{
        $query = "select count(case when CAST('$start_time' AS time) < end_time and start_time < CAST('$end_time' AS time) and exam_date = '$exam_date' then 1 end) as overlap from sh_exam_details where school_id=$school_id and exam_id=$exam_id and subject_id in (select id from sh_subjects where class_id=$class_id and batch_id=$batch_id and deleted_at is null) and deleted_at is null";
        $db->query($query);
        $overlap = $db->row()['overlap'];
        if($overlap > 0){
            $xcrud->set_exception('exam_date,start_time,end_time',lang('exam_overlapping'));
        }
    }
}

function check_exam_details_update($postdata, $primary, $xcrud) {
    $exam_id = $postdata->get('exam_id');
    $class_id = $postdata->get('class_id');
    $batch_id = $postdata->get('batch_id');
    $subject_id = $postdata->get('subject_id');
    $exam_date = $postdata->get('exam_date');
    $start_time = $postdata->get('start_time');
    $end_time = $postdata->get('end_time');
    $total_marks = $postdata->get('total_marks');
    $passing_marks = $postdata->get('passing_marks');
    $school_id = $postdata->get('school_id');

    $db = Xcrud_db::get_instance();
    $query = "select id from sh_exam_details where school_id =" . $school_id . " and subject_id =" . $subject_id . " and exam_id=".$exam_id." and deleted_at is null and id <> $primary";
    $db->query($query);
    $exam_detail_count = count($db->result());
    $query = "select start_date, end_date from sh_exams where id = ".$exam_id;
    $db->query($query);
    $result = $db->row();
    $start_session = $result['start_date'];
    $end_session = $result['end_date'];
    if($exam_detail_count > 0){
        $xcrud->set_exception('subject_id',lang('exam_already'));
    }
    else if($exam_date < $start_session || $end_session < $exam_date){
        $xcrud->set_exception('exam_date',lang('exam_out'));
    }
    else if ($start_time == $end_time){
        $xcrud->set_exception('start_time,end_time',lang('start_time_validation'));
    }
    else if($end_time < $start_time){
        $xcrud->set_exception('start_time,end_time',lang('end_time_validation'));
    }
    else if($total_marks < 0){
        $xcrud->set_exception('total_marks',lang('total_negative'));
    }
    else if($passing_marks < 0){
        $xcrud->set_exception('passing_marks',lang('pasing_negative'));
    }
    else if($total_marks < $passing_marks){
        $xcrud->set_exception('passing_marks',lang('passing_greater'));
    }
    else{
        $query = "select count(case when CAST('$start_time' AS time) < end_time and start_time < CAST('$end_time' AS time) and exam_date = '$exam_date' then 1 end) as overlap from sh_exam_details where school_id=$school_id and exam_id=$exam_id and subject_id in (select id from sh_subjects where class_id=$class_id and batch_id=$batch_id and deleted_at is null) and deleted_at is null and id <> $primary";
        $db->query($query);
        $overlap = $db->row()['overlap'];
        if($overlap > 0){
            $xcrud->set_exception('exam_date,start_time,end_time',lang('exam_overlapping'));
        }
    }
}

function passing_rules_insert($postdata, $xcrud) {
    $exam_id = $postdata->get('exam_id');
    $class_id = $postdata->get('class_id');
    $batch_id = $postdata->get('batch_id');
    $percentage = $postdata->get('percentage');

    if($percentage < 0 || $percentage > 100){
        $xcrud->set_exception('end_time', lang('passing_rule_percentage'));
    }else{
        $db = Xcrud_db::get_instance();
        $query = "select id from sh_passing_rules where exam_id = $exam_id and class_id = $class_id and batch_id = $batch_id and deleted_at is null"; 
        $db->query($query);
        $result = $db->result();
        $count = count($result);
        if($count > 0 ){
            $xcrud->set_exception('batch_id', lang('passing_rule_batch'));
        }
    }

}

function passing_rules_update($postdata, $primary ,$xcrud) {
    $exam_id = $postdata->get('exam_id');
    $class_id = $postdata->get('class_id');
    $batch_id = $postdata->get('batch_id');
    $percentage = $postdata->get('percentage');

    if($percentage < 0 || $percentage > 100){
        $xcrud->set_exception('end_time', lang('passing_rule_percentage'));
    }else{
        $db = Xcrud_db::get_instance();
        $query = "select id from sh_passing_rules where exam_id = $exam_id and class_id = $class_id and batch_id = $batch_id and deleted_at is null and id <> $primary"; 
        $db->query($query);
        $result = $db->result();
        $count = count($result);
        if($count > 0 ){
            $xcrud->set_exception('batch_id', lang('passing_rule_batch'));
        }
    }

}

function reloadPage($primary, $xcrud)
{
    echo "<script type='text/javascript'>";
    echo "location.reload();";
    echo "</script>";
}


function checkExtension($field, $file_name, $file_path, $params, $xcrud) {
    $ext = trim(strtolower(strrchr($file_name, '.')), '.');
    if ( $ext != 'pdf' && $ext != 'jpeg' && $ext != 'png' && $ext != 'gif' && $ext != 'docx' && $ext != 'txt' && $ext != 'jpg' && $ext != 'xlsx'){
        unlink($file_path);
        $xcrud->set_exception('simple_upload', 'You cannot upload this file', 'error');
    }
}

// function check_activity($postdata, $xcrud){
//     $school_id = $postdata->get('school_id');
//     $class_id = $postdata->get('class_id');
//     $batch_id = $postdata->get('batch_id');

//     $db = Xcrud_db::get_instance();
//         $query = "SELECT * FROM `sh_exam_activities` WHERE class_id='$class_id' AND batch_id='$batch_id' AND school_id='$school_id' AND deleted_at is NULL"; 
//         $db->query($query);
//         $result = $db->result();
//         $count = count($result);
//         if($count > 3 ){
//             $xcrud->set_exception('batch_id', lang('activity_count'));
//         }
// }

function check_activity($postdata, $xcrud)
{
    $school_id = $postdata->get('school_id');
    $class_id = $postdata->get('class_id');
    $batch_id = $postdata->get('batch_id');
    $subject_ids = $postdata->get('subject_ids');
    $subject_ids = explode(',', $subject_ids);
    foreach ($subject_ids as $subject_id) {
        $db = Xcrud_db::get_instance();
        $query = "SELECT * FROM `sh_exam_activities` WHERE class_id='$class_id' AND batch_id='$batch_id' AND school_id='$school_id' AND deleted_at is NULL AND FIND_IN_SET('$subject_id', subject_ids)";
        $db->query($query);
        $result = $db->result();
        $count = count($result);
        if($count > 3 ){
            $xcrud->set_exception('batch_id', lang('activity_count'));
        }
    }
}

function check_activity_update($postdata, $primary ,$xcrud){
    $school_id = $postdata->get('school_id');
    $class_id = $postdata->get('class_id');
    $batch_id = $postdata->get('batch_id');

    $db = Xcrud_db::get_instance();
        $query = "SELECT * FROM `sh_exam_activities` WHERE class_id='$class_id' AND batch_id='$batch_id' AND school_id='$school_id' AND deleted_at is NULL"; 
        $db->query($query);
        $result = $db->result();
        $count = count($result);
        if($count > 3 ){
            $xcrud->set_exception('batch_id', lang('activity_count'));
        }
}