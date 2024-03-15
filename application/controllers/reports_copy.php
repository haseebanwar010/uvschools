public function fetchFees(){
        $school_id = $this->session->userdata("userdata")["sh_id"];
        $inputs = $this->input->post("formData");

        //------set request values
            if(isset($inputs['class_id'])){ $class_id = $inputs['class_id']; }
            if(isset($inputs['types_id'])){ $feeType = $inputs['types_id']; }
            if (isset($inputs['btch_id'])) { $btch = $inputs['btch_id']; }
            if(isset($inputs['discounts_id'])){ $discType = $inputs['discounts_id']; }
            if(isset($inputs['collects_id'])){ $collector = $inputs['collects_id']; }
            if(isset($inputs['mode'])){ $mode = $inputs['mode']; }
            if(isset($inputs['from'])){ $from = $inputs['from']; }
            if(isset($inputs['to'])){ $to = $inputs['to']; }
                         $where = "";
            if(isset($inputs['isDue']) && !empty($inputs['isDue']) && $inputs['isDue']=='true'){

                $sql1 = "SELECT 
                fc.*, 
                u.name as std_name, 
                c.name as class_name,
                b.name as batch,
                u.rollno as studentID,
                u_col.name as collector, 
                ft.name as type, 
                ft.due_date as due_date,
                ft.amount as feetype_amount, 
                fd.name as discount,
                COALESCE (concat( (ft.amount * v.percentage ) / 100 ) , 0 )as discount_amount
                from sh_users u 
                LEFT JOIN sh_fee_types ft on ft.class_id = u.class_id 
                Left join sh_fee_discount fd on u.discount_id = fd.id 
                LEFT JOIN sh_batches b on b.id = u.batch_id 
                left JOIN sh_discount_varients v on v.discount_id = fd.id AND v.fee_type_id = ft.id
                Left join sh_fee_collection fc on fc.student_id = u.id and ft.id = fc.feetype_id 
                left JOIN sh_users u_col ON u_col.id = fc.collector_id 
                LEFT join sh_classes c on c.id = u.class_id
                where u.school_id = $school_id and u.role_id = 3 and u.deleted_at = 0
                and ft.deleted_at is null 
                and fd.deleted_at is null 
                and fc.deleted_at is null
                and ft.due_date < DATE(NOW())
                AND  NOT EXISTS(SELECT * FROM sh_fee_collection fcc where fcc.student_id = u.id and ft.class_id = u.class_id and ft.id = fc.feetype_id and fcc.deleted_at is NULL)
                 ";
                    if(isset($class_id) && $class_id != NULL){
                        $cls_id = "";
                        foreach($class_id as $key => $value){
                            $cls_id .= $value. ",";
                        }
                        $cls_ids = rtrim($cls_id, ",");
                        $sql1.="AND u.class_id IN ($cls_ids)";
                    }
                    if(isset($feeType) && !empty($feeType)){
                        /* split class and batch id's form object request with , */
                        $type_id = "";
                        foreach($feeType as $key => $value){
                            $type_id .= $value. ",";
                        }
                        $feeType_id = rtrim($type_id, ",");
                         $sql1 .= " AND ft.id IN ($feeType_id)";
                    }
                    if (isset($btch) && !empty($btch)) {
                       $bch_id = "";
                       foreach ($btch as $key => $value) {
                           $bch_id .= $value . ",";
                       }
                       $batches_id = rtrim($bch_id, ",");
                       $sql1 .= " AND u.batch_id IN ($batches_id) ";
                   }
                    if(isset($discType) && !empty($discType)){
                        $disc_id = "";
                        foreach($discType as $key => $value){
                            $disc_id .= $value. ",";
                        }
                        $discType_id = rtrim($disc_id, ",");
                         $sql1 .= " AND fc.discount_id IN ($discType_id) ";
                    }
                    if(isset($collector) && !empty($collector)){
                        $cltr = "";
                        foreach($collector as $key => $value){
                            $cltr .= $value. ",";
                        }
                        $cltr_ids = rtrim($cltr, ",");
                        $sql1 .= " AND fc.collector_id IN ($cltr_ids) ";
                    }
                    if(isset($from) && $from != NULL ){
                            $sql1 .= " AND u.created_at between '".to_mysql_date($from)."%' ";
                    }
                    if(isset($to) && $to != NULL ){
                            $sql1 .= " AND '".to_mysql_date($to)."%' ";
                    }
                    $sql1 .= "ORDER BY c.id, b.id";     
                    // print_r($sql1);
                    // die();
                 $data = $this->admin_model->dbQuery($sql1);
            }else{
                
                $sql1 = "SELECT 
                fc.*, 
                u.name as std_name,
                u.rollno as studentID,
                b.name as batch, 
                c.name as class_name,
                u_col.name as collector, 
                ft.name as type, 
                ft.due_date as due_date,
                ft.amount as feetype_amount, 
                fd.name as discount,
                fd.amount as percentage ,
                COALESCE (concat( (ft.amount * v.percentage ) / 100 ) , 0 )as discount_amount
                from sh_users u 
                LEFT  JOIN sh_fee_types ft on ft.class_id = u.class_id 
                Left  join sh_fee_discount fd on u.discount_id = fd.id 
                LEFT JOIN sh_batches b on b.id = u.batch_id
                left JOIN sh_discount_varients v on v.discount_id = fd.id AND v.fee_type_id = ft.id
                Left join sh_fee_collection fc on fc.student_id = u.id and ft.id = fc.feetype_id 
                left JOIN sh_users u_col ON u_col.id = fc.collector_id 
                LEFT join sh_classes c on c.id = u.class_id
                where u.school_id = $school_id and u.role_id = 3 and u.deleted_at = 0
                and ft.deleted_at is null 
                and fd.deleted_at is null 
                and fc.deleted_at is null ";

                // set filters of query
                if(isset($mode) && $mode != NULL){
                   $sql1 .= " and fc.mode = '$mode' ";
                }
                if(isset($class_id) && $class_id != NULL){
                    $cls_id = "";
                    foreach($class_id as $key => $value){
                        $cls_id .= $value. ",";
                    }
                    $cls_ids = rtrim($cls_id, ",");
                     $sql1 .= " AND u.class_id IN ($cls_ids) ";
                }
                if(isset($feeType) && !empty($feeType)){
                    /* split class and batch id's form object request with , */
                    $type_id = "";
                    foreach($feeType as $key => $value){
                        $type_id .= $value. ",";
                    }
                    $feeType_id = rtrim($type_id, ",");
                     $sql1 .= " AND ft.id IN ($feeType_id)";
                }
                if (isset($btch) && !empty($btch)) {
                       $bch_id = "";
                       foreach ($btch as $key => $value) {
                           $bch_id .= $value . ",";
                       }
                       $batches_id = rtrim($bch_id, ",");
                       $sql1 .= " AND u.batch_id IN ($batches_id) ";
                }
                if(isset($discType) && !empty($discType)){
                     $disc_id = "";
                    foreach($discType as $key => $value){
                        $disc_id .= $value. ",";
                    }
                    $discType_id = rtrim($disc_id, ",");
                     $sql1 .= " AND fc.discount_id IN ($discType_id) ";
                }
                if(isset($collector) && !empty($collector)){
                     $cltr = "";
                    foreach($collector as $key => $value){
                        $cltr .= $value. ",";
                    }
                    $cltr_ids = rtrim($cltr, ",");
                     $sql1 .= " AND fc.collector_id IN ($cltr_ids) ";
                }
                if(isset($from) && $from != NULL ){
                     $sql1 .= " AND fc.created_at between '".to_mysql_date($from)."%' ";
                }
                if(isset($to) && $to != NULL ){
                     $sql1 .= " AND '".to_mysql_date($to)."%' ";
                }
                $sql1 .= "ORDER BY c.id, b.id"; 
                 // print_r($sql1);
                $data = $this->admin_model->dbQuery($sql1);
            }
             echo json_encode($data);
}