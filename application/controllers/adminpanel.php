<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adminpanel extends CI_Controller {

    public function index() {
        $this->load->view('admin_panel');
    }

    public function auth() {
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        $pass = md5($request->passcode);
        
        if ($pass == '61d3889631329d9d4e40b5a48446a462') {
            $response["success"] = true;
            $xcrud = xcrud_get_instance();
            $xcrud->table('sh_license')->where('deleted_at', 0);
            $xcrud->join('school_id', 'sh_school', 'id');
            $xcrud->join('school_id', 'sh_users', 'school_id');
            $xcrud->show_primary_ai_field(false);
            $xcrud->columns('sh_school.name,sh_school.url, sh_users.email, sh_users.contact, licence_type,  sh_school.phone, sh_school.address, sh_school.city,sh_school.time_zone, sh_school.country, start_date, end_date');
            $xcrud->fields('sh_school.name,sh_school.url, sh_users.email, sh_users.contact, licence_type, sh_school.phone, sh_school.address, sh_school.city, sh_school.time_zone, sh_school.country, start_date, end_date');
            // $xcrud->columns('sh_school.name,sh_school.url, sh_school.institution_type, licence_type, start_date, end_date');
            // $xcrud->fields('sh_school.name,sh_school.url, sh_school.institution_type, licence_type, start_date, end_date');
            
            $xcrud->readonly('sh_school.name,sh_school.url');
            $xcrud->label('sh_school.name', 'School Name');
            $xcrud->where("sh_users.role_id = 1");
            // $xcrud->where("sh_school.country !='Pakistan'");
            // $xcrud->where("sh_school.country !='Libya'");
            // $xcrud->where("sh_school.country !='Sudan'");
            $xcrud->load_view("view", "customview.php");
            $xcrud->unset_add();
            $xcrud->table_name('Licenses');
            //$xcrud->unset_search();
            $xcrud->unset_limitlist();
            $xcrud->unset_remove();
            // $xcrud->unset_print();
            $xcrud->unset_csv();


            $maintenance = xcrud_get_instance();
            $maintenance->table('sh_settings')->where('name', 'maintenance');


            $maintenance->show_primary_ai_field(false);
            $maintenance->columns('mode,allowed_ip,message,disable_controllers');
            $maintenance->fields('mode,allowed_ip,message,disable_controllers');


            $maintenance->load_view("view", "customview.php");
            $maintenance->unset_add();
            $maintenance->table_name('Maintenance');
            $maintenance->unset_search();
            $maintenance->unset_limitlist();
            $maintenance->unset_remove();
            $maintenance->unset_print();
            $maintenance->unset_csv();
            $response["success"] = true;
            $response["license"] = $xcrud->render();
            $response["maintenance"] = $maintenance->render();
        } else {
            $response["success"] = false;
            $response["error"] = "Sorry, wrong passcode!";
        }

        echo json_encode($response);
    }

    public function resolve_collation_issues() {
        $db_name = $this->db->database;
        $res = $this->db->query('ALTER DATABASE `' . $db_name . '` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci');
        $arr = $this->db->query('show full tables where Table_Type = "BASE TABLE"')->result_array();
        foreach ($arr as $a) {
            $this->db->query('ALTER TABLE ' . $a['Tables_in_' . $db_name] . ' CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci');
        }
        if ($res) {
            $response["success"] = true;
        } else {
            $response["success"] = false;
        }
        echo json_encode($response);
    }

    public function temp() {
   //    $permissions = '[
   //     {"label": "View Students", "permission": "students-show", "val": "false"},
   //     {"label": "Add Students", "permission": "students-add", "val": "false"},
   //     {"label": "Edit Students", "permission": "students-edit", "val": "false"},
   //     {"label": "View Students Details", "permission": "students-view", "val": "false"},
   //     {"label": "Student Attendance", "permission": "attendance-show", "val": "false"},
   //     {"label": "Attendance Report", "permission": "attendance-report", "val": "false"},
   //     {"label": "View Parents", "permission": "parents-all", "val": "false"},
   //     {"label": "Add Parents", "permission": "parents-add", "val": "false"},
   //     {"label": "Edit Parents", "permission": "parents-edit", "val": "false"},
   //     {"label": "View Parents Details", "permission": "parents-view", "val": "false"},
   //     {"label": "Employee Attendance", "permission": "attendance-employee", "val": "false"},
   //     {"label": "Employee Report", "permission": "attendance-emp_report", "val": "false"},
   //     {"label": "View Employees", "permission": "employee-all", "val": "false"},
   //     {"label": "Single Employee Details", "permission": "employee-view", "val": "false"},
   //     {"label": "Add Employee", "permission": "employee-add", "val": "false"},
   //     {"label": "Edit Employee", "permission": "employee-edit", "val": "false"},
   //     {"label": "Upload Study Material", "permission": "study_material-upload", "val": "false"},
   //     {"label": "Download Study Material", "permission": "study_material-download", "val": "false"},
   //     {"label": "Buy Books", "permission": "study_material-book_shop", "val": "false"},
   //     {"label": "Download Forms", "permission": "forms-all", "val": "false"},
   //     {"label": "Create Form", "permission": "forms-create", "val": "false"},
   //     {"label": "Edit Form", "permission": "forms-edit", "val": "false"},
   //     {"label": "Create Form Category", "permission": "forms-category_create", "val": "false"},
   //     {"label": "View Profile", "permission": "profile-index", "val": "false"},
   //     {"label": "Edit Profile", "permission": "profile-edit", "val": "false"},
   //     {"label": "Students Shift", "permission": "students-shift", "val": "false"},
   //     {"label": "View Student Fee", "permission": "fee-collection", "val": "false"},
   //     {"label": "Collect Student Fee", "permission": "collection-allow", "val": "false"},
   //     {"label": "View Collection Detail", "permission": "view-collection", "val": "false"},
   //     {"label": "Fee Statistics", "permission": "fee-statistics", "val": "false"},
   //     {"label": "View Time Table", "permission": "timetable-show", "val": "false"},
   //     {"label": "Edit Time Table", "permission": "timetable-edit", "val": "false"},
   //     {"label": "View Reports", "permission": "reports-all", "val": "false"},
   //     {"label": "Manage Syllabus", "permission": "syllabus-add", "val": "false"},      
   //     {"label": "Assign Teacher", "permission": "assign-teacher", "val": "false"},      
   //     {"label": "Manage Exams", "permission": "examination-add", "val": "false"},
   //     {"label": "Manage Marks Exams", "permission": "examination-marks", "val": "false"},
   //     {"label": "Manage Major Sheet", "permission": "examination-majorSheet", "val": "false"},
   //     {"label": "Student Attendance", "permission": "applications-student", "val": "false"},
   //     {"label": "Employee Attendance", "permission": "applications-employee", "val": "false"},
   //     {"label": "Study Plan", "permission": "applications-studyplan", "val": "false"},
   //     {"label": "Mark Sheet", "permission": "applications-marksheet", "val": "false"},
   //     {"label": "View Salaries", "permission": "payroll-index", "val": "false"},
   //     {"label": "Pay Salaries", "permission": "payroll-pay", "val": "false"},
   //     {"label": "Delete Salaries", "permission": "payroll-delete", "val": "false"},
   //     {"label": "View Payroll Settings", "permission": "payroll-settings", "val": "false"},
   //     {"label": "Add Payroll Settings", "permission": "payroll-settingsadd", "val": "false"},
   //     {"label": "Edit Payroll Settings", "permission": "payroll-settingsedit", "val": "false"},
   //     {"label": "Delete Payroll Settings", "permission": "payroll-settingsdelete", "val": "false"},
   //     {"label": "View Evaluation Settings", "permission": "settings-evaluation", "val": "false"},
   //     {"label": "Add Evaluation Settings", "permission": "settings-evaladd", "val": "false"},
   //     {"label": "Edit Evaluation Settings", "permission": "settings-evaledit", "val": "false"},
   //     {"label": "Delete Evaluation Settings", "permission": "settings-evaledelete", "val": "false"},
   //     {"label": "Evaluate Students", "permission": "students-evaluate", "val": "false"},
   //     {"label": "Evaluation Report", "permission": "students-report_card", "val": "false"},
   //     {"label": "View Trash", "permission": "trash-index", "val": "false"},
   //     {"label": "Recover Record", "permission": "trash-recover", "val": "false"},
   //     {"label": "Permanent Delete", "permission": "trash-delete", "val": "false"},
   //     {"label": "Account Dashboard", "permission": "accounts-dashboard", "val": "false"},
   //     {"label": "Account Collect", "permission": "accounts-collect", "val": "false"},
   //     {"label": "Account Pay", "permission": "accounts-pay", "val": "false"},
   //     {"label": "Account Deposit", "permission": "accounts-deposit", "val": "false"},
   //     {"label": "Account DepositEdit", "permission": "accounts-depositEdit", "val": "false"},
   //     {"label": "Account Withdraw", "permission": "accounts-withdraw", "val": "false"},

   //     {"label": "Income settings", "permission": "accounts-income_settings", "val": "false"},
   //     {"label": "Income Add", "permission": "accounts-incomeAdd", "val": "false"},
   //     {"label": "Income Edit", "permission": "accounts-incomeEdit", "val": "false"},
   //     {"label": "Income Delete", "permission": "accounts-incomeDelete", "val": "false"},

   //     {"label": "Expense Settings", "permission": "accounts-expense_settings", "val": "false"},
   //     {"label": "Expense Add", "permission": "accounts-expenseAdd", "val": "false"},
   //     {"label": "Expense Edit", "permission": "accounts-expenseEdit", "val": "false"},
   //     {"label": "Expense Delete", "permission": "accounts-expenseDelete", "val": "false"},

   //     {"label": "Virtual Accounts", "permission": "accounts-virtual_accounts", "val": "false"},
   //     {"label": "Virtual Account Add", "permission": "accounts-virtualAdd", "val": "false"},
   //     {"label": "Virtual Account Edit", "permission": "accounts-virtualEdit", "val": "false"},
   //     {"label": "Virtual Account Delete", "permission": "accounts-virtualDelete", "val": "false"},

   //     {"label": "Manage Employees", "permission": "manage-viewEmployees", "val": "false"},
   //     {"label": "Employee Status", "permission": "manage-changeEmpStatus", "val": "false"},
   //     {"label": "Manage Stduents", "permission": "manage-viewStduents", "val": "false"},
   //     {"label": "Students Status", "permission": "manage-changeStdStatus", "val": "false"},
   //     {"label": "Manage Guardians", "permission": "manage-viewGuardians", "val": "false"},
   //     {"label": "Guardian Status", "permission": "manage-changeGuardianStatus", "val": "false"},

   //      {"label": "View Exam Settings", "permission": "online_exams-settings", "val": "false"},
   //      {"label": "Add Exam Settings", "permission": "online_exams-addSettings", "val": "false"},
   //      {"label": "Edit Exam Settings", "permission": "online_exams-settingsEdit", "val": "false"},
   //      {"label": "Delete Exam Settings", "permission": "online_exams-settingsDelete", "val": "false"},
   //      {"label": "Add Question", "permission": "online_exams-add_question", "val": "false"},
   //      {"label": "Publish Online Exam", "permission": "online_exams-publish_papers", "val": "false"},
   //      {"label": "Veiw Major Sheet", "permission": "online_exams-results", "val": "false"},
   //      {"label": "Messaging", "permission": "messages-show", "val": "false"},
   //      {"label": "Add Announcement", "permission": "announcements-index", "val": "false"},
   //      {"label": "Monitoring", "permission": "monitoring-index", "val": "false"}
   // ]';

   $permissions = '[
    {"label":"View Students","permission":"students-show","val":"true"},
    {"label":"Add Students","permission":"students-add","val":"false"},
    {"label":"Edit Students","permission":"students-edit","val":"false"},
    {"label":"View Students Details","permission":"students-view","val":"false"},
    {"label":"Student Attendance","permission":"attendance-show","val":"true"},
    {"label":"Attendance Report","permission":"attendance-report","val":"true"},
    {"label":"Students Shift","permission":"students-shift","val":"false"},
    {"label":"View Guardians","permission":"parents-all","val":"true"},
    {"label":"Add Guardians","permission":"parents-add","val":"false"},
    {"label":"Edit Guardians","permission":"parents-edit","val":"false"},
    {"label":"View Guardian Details","permission":"parents-view","val":"false"},
    {"label":"Employee Attendance","permission":"attendance-employee","val":"false"},
    {"label":"Employee Report","permission":"attendance-emp_report","val":"false"},
    {"label":"Add Employee","permission":"employee-add","val":"false"},
    {"label":"View Employees","permission":"employee-all","val":"false"},
    {"label":"Single Employee Details","permission":"employee-view","val":"false"},
    {"label":"View Time Table","permission":"timetable-show","val":"true"},
    {"label":"Edit Time Table","permission":"timetable-edit","val":"true"},
    {"label":"Monitoring","permission":"monitoring-index","val":"false"},
    {"label":"Upload Study Material","permission":"study_material-upload","val":"true"},
    {"label":"Download Study Material","permission":"study_material-download","val":"true"},
    {"label":"Manage Syllabus","permission":"syllabus-add","val":"false"},
    {"label":"Buy Books","permission":"study_material-book_shop","val":"true"},
    {"label":"View Profile","permission":"profile-index","val":"true"},
    {"label":"Edit Profile","permission":"profile-edit","val":"false"},
    {"label":"View Reports","permission":"reports-all","val":"false"},
    {"label":"Add Announcement","permission":"announcements-index","val":"false"},
    {"label":"Messaging Employees","permission":"messages-employee","val":"false"},
    {"label":"Messaging parents","permission":"messages-parent","val":"false"},
    {"label":"Messaging Students","permission":"messages-student","val":"false"},
    {"label":"View Evaluation Settings","permission":"settings-evaluation","val":"false"},
    {"label":"Add Evaluation Settings","permission":"settings-evaladd","val":"false"},
    {"label":"Edit Evaluation Settings","permission":"settings-evaledit","val":"false"},
    {"label":"Delete Evaluation Settings","permission":"settings-evaledelete","val":"false"},
    {"label":"Evaluate Students","permission":"students-evaluate","val":"false"},
    {"label":"Evaluation Report","permission":"students-report_card","val":"false"},
    {"label":"Manage Exams","permission":"examination-add","val":"false"},
    {"label":"Publish Result","permission":"examination-publish_result","val":"false"},
    {"label":"Manage Marks Exams","permission":"examination-marks","val":"false"},
    {"label":"Manage Major Sheet","permission":"examination-majorSheet","val":"false"},
    {"label":"View Exam Settings","permission":"online_exams-settings","val":"false"},
    {"label":"Add Exam Setting","permission":"online_exams-addSettings","val":"false"},
    {"label":"Edit Exam Settings","permission":"online_exams-settingsEdit","val":"false"},
    {"label":"Delete Exam Settings","permission":"online_exams-settingsDelete","val":"false"},
    {"label":"Add Question","permission":"online_exams-add_question","val":"false"},
    {"label":"Publish Online Exam","permission":"online_exams-publish_papers","val":"false"},
    {"label":"Veiw Major Sheet","permission":"online_exams-results","val":"false"},
    {"label":"Student Attendance","permission":"applications-student","val":"false"},
    {"label":"Employee Attendance","permission":"applications-employee","val":"false"},
    {"label":"Study Plan","permission":"applications-studyplan","val":"false"},
    {"label":"Mark Sheet","permission":"applications-marksheet","val":"false"},
    {"label":"Download Forms","permission":"forms-all","val":"true"},
    {"label":"Create Form","permission":"forms-create","val":"false"},
    {"label":"Edit Form","permission":"forms-edit","val":"false"},
    {"label":"Create Form Category","permission":"forms-category_create","val":"false"},
    {"label":"View Employees","permission":"manage-viewEmployees","val":"false"},
    {"label":"Active Deactive Employees","permission":"manage-changeEmpStatus","val":"false"},
    {"label":"View Students","permission":"manage-viewStduents","val":"false"},
    {"label":"Active Deactive Students","permission":"manage-changeStdStatus","val":"false"},
    {"label":"View Guardians","permission":"manage-viewGuardians","val":"false"},
    {"label":"Active Deactive Guardians","permission":"manage-changeGuardianStatus","val":"false"},
    {"label":"View Trash","permission":"trash-index","val":"false"},
    {"label":"Recover Record","permission":"trash-recover","val":"false"},
    {"label":"Permanent Delete","permission":"trash-delete","val":"false"},
    {"label":"View Student Fee","permission":"fee-collection","val":"false"},
    {"label":"Collect Student Fee","permission":"collection-allow","val":"false"},
    {"label":"View Collection Detail","permission":"view-collection","val":"false"},
    {"label":"Fee Statistics","permission":"fee-statistics","val":"false"},
    {"label":"Disable Fee","permission":"fee-disable","val":"false"},
    {"label":"Exemption Fee","permission":"fee-exemption","val":"false"},
    {"label":"Apply Fee Discounts","permission":"fee-discounts","val":"false"},
    {"label":"View Salaries","permission":"payroll-index","val":"false"},
    {"label":"Pay Salaries","permission":"payroll-pay","val":"false"},
    {"label":"Delete Salaries","permission":"payroll-delete","val":"false"},
    {"label":"View Payroll Settings","permission":"payroll-settings","val":"false"},
    {"label":"Add Payroll Settings","permission":"payroll-settingsadd","val":"false"},
    {"label":"Edit Payroll Settings","permission":"payroll-settingsedit","val":"false"},
    {"label":"Delete Payroll Settings","permission":"payroll-settingsdelete","val":"false"},
    {"label":"Dashboard","permission":"accounts-dashboard","val":"false"},
    {"label":"Allow Collect","permission":"accounts-collect","val":"false"},
    {"label":"Allow Pay","permission":"accounts-pay","val":"false"},
    {"label":"Allow Deposit","permission":"accounts-deposit","val":"false"},
    {"label":"Edit Deposit","permission":"accounts-depositEdit","val":"false"},
    {"label":"Allow Withdraw","permission":"accounts-withdraw","val":"false"},
    {"label":"Income Settings","permission":"accounts-income_settings","val":"false"},
    {"label":"Income Add","permission":"accounts-incomeAdd","val":"false"},
    {"label":"Income Edit","permission":"accounts-incomeEdit","val":"false"},
    {"label":"Income Delete","permission":"accounts-incomeDelete","val":"false"},
    {"label":"Expense Settings","permission":"accounts-expense_settings","val":"false"},
    {"label":"Expense Add","permission":"accounts-expenseAdd","val":"false"},
    {"label":"Expense Edit","permission":"accounts-expenseEdit","val":"false"},
    {"label":"Expense Delete","permission":"accounts-expenseDelete","val":"false"},
    {"label":"View Virtual Accounts","permission":"accounts-virtual_accounts","val":"false"},
    {"label":"Add Virtual Accounts","permission":"accounts-virtualAdd","val":"false"},
    {"label":"Edit Virtual Accounts","permission":"accounts-virtualEdit","val":"false"},
    {"label":"Delete Virtual Accounts","permission":"accounts-virtualDelete","val":"false"}
    ]';

    // {"label":"","permission":"calandar-view","val":"false"},
    // {"label":"","permission":"calandar-add","val":"false"},
    // {"label":"","permission":"calandar-edit","val":"false"},
    // {"label":"","permission":"calandar-delete","val":"false"}

        $xyz = json_decode($permissions);
        $employees = $this->db->select('id,permissions')->from('sh_users')->where('role_id', 4)->get()->result();
        foreach ($employees as $emp) {
            $permissions = $xyz;

            $permissions2 = json_decode($emp->permissions);
            foreach ($permissions as $per) {
                foreach ($permissions2 as $per2) {
                    if ($per2->permission == $per->permission || ($per2->permission == "applications-all" && ($per->permission == "applications-student" || $per->permission == "applications-employee" || $per->permission == "applications-studyplan" || $per->permission == "applications-marksheet"))) {
                        $per->val = $per2->val;
                        break;
                    }
                }
            }
            $permissions = json_encode($permissions);
            $this->db->set('permissions', $permissions)->where('id', $emp->id)->update('sh_users');
        }

        $categories = $this->db->select('id,default_permissions')->from('sh_role_categories')->get()->result();

        foreach ($categories as $cat) {
            $permissions = $xyz;

            $permissions2 = json_decode($cat->default_permissions);
            foreach ($permissions as $per) {
                foreach ($permissions2 as $per2) {
                    if ($per2->permission == $per->permission || ($per2->permission == "applications-all" && ($per->permission == "applications-student" || $per->permission == "applications-employee" || $per->permission == "applications-studyplan" || $per->permission == "applications-marksheet"))) {
                        $per->val = $per2->val;
                        break;
                    }
                }
            }
            $permissions = json_encode($permissions);
            $this->db->set('default_permissions', $permissions)->where('id', $cat->id)->update('sh_role_categories');
        }
    }
    
    /*public function update_data(){
        $schools  = $this->admin_model->dbSelect("id", "school", " deleted_at=0 ");
        $sql9_0 = "ALTER TABLE `sh_classes` ADD `academic_year_id` INT NULL DEFAULT NULL AFTER `level_id`; ";
        $sql9_4 = "ALTER TABLE `sh_fee_types` ADD `academic_year_id` INT NULL DEFAULT NULL AFTER `due_date`;";
        $sql9_1 = "ALTER TABLE `sh_subjects` ADD `academic_year_id` INT NULL DEFAULT NULL AFTER `weekly_classes`; ";
        $sql9_5 = "ALTER TABLE `sh_subject_groups` ADD `academic_year_id` INT NULL DEFAULT NULL AFTER `subjects`;";
        $sql9_2 = "ALTER TABLE `sh_periods` ADD `academic_year_id` INT NULL DEFAULT NULL AFTER `is_break`; ";
        $sql9_6 = "ALTER TABLE `sh_exam_details` ADD `academic_year_id` INT NULL DEFAULT NULL AFTER `passing_marks`;";
        $sql9_3 = "ALTER TABLE `sh_passing_rules` ADD `academic_year_id` INT NULL DEFAULT NULL AFTER `minimum_subjects`;";
        $sql9_7 = "ALTER TABLE `sh_timetable_new` ADD `academic_year_id` INT NULL DEFAULT NULL AFTER `room_no`;";
        $this->db->query($sql9_0);
        $this->db->query($sql9_1);
        $this->db->query($sql9_2);
        $this->db->query($sql9_3);
        $this->db->query($sql9_4);
        $this->db->query($sql9_5);
        $this->db->query($sql9_6);
        $this->db->query($sql9_7);
        echo "---column academic_year_id added successfully!----<br/>";
        
        foreach($schools as $sh){
            $sql1 = "update sh_classes set academic_year_id=(select COALESCE(id,0) from sh_academic_years Where is_active='Y' and deleted_at is NULL AND school_id=".$sh->id.") where school_id=".$sh->id;
            $sql2 = "update sh_fee_types set academic_year_id=(select COALESCE(id,0) from sh_academic_years Where is_active='Y' and deleted_at is NULL AND school_id=".$sh->id.") where school_id=".$sh->id;
            $sql3 = "update sh_subjects set academic_year_id=(select COALESCE(id,0) from sh_academic_years Where is_active='Y' and deleted_at is NULL AND school_id=".$sh->id.") where school_id=".$sh->id;
            $sql4 = "update sh_subject_groups set academic_year_id=(select COALESCE(id,0) from sh_academic_years Where is_active='Y' and deleted_at is NULL AND school_id=".$sh->id.") where school_id=".$sh->id;
            $sql5 = "update sh_periods set academic_year_id=(select COALESCE(id,0) from sh_academic_years Where is_active='Y' and deleted_at is NULL AND school_id=".$sh->id.") where school_id=".$sh->id;
            $sql6 = "update sh_exam_details set academic_year_id=(select COALESCE(id,0) from sh_academic_years Where is_active='Y' and deleted_at is NULL AND school_id=".$sh->id.") where school_id=".$sh->id;
            $sql7 = "update sh_passing_rules set academic_year_id=(select COALESCE(id,0) from sh_academic_years Where is_active='Y' and deleted_at is NULL AND school_id=".$sh->id.") where school_id=".$sh->id;
            $sql8 = "update sh_timetable_new set academic_year_id=(select COALESCE(id,0) from sh_academic_years Where is_active='Y' and deleted_at is NULL AND school_id=".$sh->id.") where period_id in (select id from sh_periods where school_id = ".$sh->id." and deleted_at is null)";
            $this->db->query($sql1);
            $this->db->query($sql2);
            $this->db->query($sql3);
            $this->db->query($sql4);
            $this->db->query($sql5);
            $this->db->query($sql6);
            $this->db->query($sql7);
            $this->db->query($sql8);
        }
        
        echo "---student data updated successfully!----<br/>";
        
        $sql10 = "CREATE TABLE `sh_student_class_relation` (
            `id` int(11) NOT NULL,
            `student_id` int(11) NOT NULL,
            `class_id` int(11) NOT NULL,
            `batch_id` int(11) NOT NULL,
            `subject_group_id` int(11) DEFAULT NULL,
            `academic_year_id` int(11) DEFAULT NULL,
            `school_id` int(11) NOT NULL,
            `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            `updated_at` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
            `deleted_at` datetime DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;"; 
        $sql10_1 = "ALTER TABLE `sh_student_class_relation` ADD PRIMARY KEY (`id`);";
        $sql10_2 = "ALTER TABLE `sh_student_class_relation` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;";
        $this->db->query($sql10);
        $this->db->query($sql10_1);
        $this->db->query($sql10_2);
        
        echo "---student class relation table create successfully!----<br/>";
        
        foreach($schools as $sh1){
            $sql11 = "INSERT INTO sh_student_class_relation ( 
                student_id,class_id,batch_id,subject_group_id,academic_year_id,school_id) 
                SELECT id, 
                    class_id, 
                    batch_id, 
                    subject_group_id,(SELECT COALESCE(id,0) FROM `sh_academic_years` where school_id = ".$sh1->id." and is_active = 'Y'),".$sh1->id."
                    FROM sh_users where school_id=".$sh1->id." and deleted_at=0 and role_id=3";
            $this->db->query($sql11);
        }
        
        echo "---data inserted into student class relation table successfully!----<br/>";
        
        $sql12 = "ALTER TABLE `sh_users` DROP `class_id`, DROP `batch_id`, DROP `subject_group_id`;";
        $this->db->query($sql12);
        
        foreach($schools as $sh2) {
            $sql13 = "create view sh_students_".$sh2->id." AS
                select u.*,
                cr.class_id,
                cr.batch_id,
                cr.subject_group_id,
                cr.academic_year_id 
                from sh_users u 
                inner join sh_student_class_relation cr on u.id = cr.student_id 
                where cr.academic_year_id = (select COALESCE(id,0) from sh_academic_years where school_id = ".$sh2->id." and is_active = 'Y' and deleted_at is null) "
                    . "and u.role_id = 3 and u.deleted_at=0 and u.school_id=".$sh2->id." and cr.deleted_at is null";
            $this->db->query($sql13);
        }
        
        echo "---school students view created successfully!----<br/>";
        echo "Data updated successfully!";
    }*/

    public function update_views(){
        $schools  = $this->admin_model->dbSelect("id", "school", " deleted_at=0 ");
        foreach($schools as $sh2) {
            $sql13 = "create view sh_students_".$sh2->id." AS
                select u.*,
                cr.class_id,
                cr.batch_id,
                cr.subject_group_id,
                cr.academic_year_id,
                cr.discount_id
                from sh_users u 
                inner join sh_student_class_relation cr on u.id = cr.student_id 
                where cr.academic_year_id = (select COALESCE(id,0) from sh_academic_years where school_id = ".$sh2->id." and is_active = 'Y' and deleted_at is null) "
                    . "and u.role_id = 3 and u.deleted_at=0 and u.school_id=".$sh2->id." and cr.deleted_at is null";
            $this->db->query($sql13);
        }
        echo "Views updated successfully";
    }

    public function update_currencies(){
      $currencies  = $this->admin_model->dbSelect("*", "school_currencies", " deleted_at is null ");
        foreach($currencies as $c) {

          $result = $this->db->select('name,symbol')->from('sh_currency')->where('id', $c->currency_id)->get()->row();
          if($result){
            $data["currency_name"] = $result->name;
            $data["currency_symbol"] = $result->symbol;
            $this->db->where('id', $c->id)->update("sh_school_currencies", $data);
          }

        }
    }

}
