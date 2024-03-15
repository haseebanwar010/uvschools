<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accounts extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        // ism 108
        if($this->session->userdata("userdata")["sh_id"] == 108){
            redirect(site_url("dashboard"));
        }
        check_user_permissions();
    }

    public function income_settings(){
       $school_id = $this->session->userdata("userdata")["sh_id"];
       if($this->session->userdata("userdata")["role_id"] == '4'){
          $arr = $this->session->userdata("userdata")["persissions"];
          $array = json_decode($arr);
          if(isset($array)) {
            $incomeAdd = $incomeEdit = $incomeDelete = 0;
            foreach ($array as $key => $value) {
              if (in_array('accounts-incomeAdd', array($value->permission)) && $value->val == 'true') {
                  $incomeAdd = '1';
              }if (in_array('accounts-incomeEdit', array($value->permission)) && $value->val == 'true') {
                  $incomeEdit = '1';
              }if (in_array('accounts-incomeDelete', array($value->permission)) && $value->val == 'true') {
                  $incomeDelete = '1';
              }
            }
          }
        }
        $xcrud = xcrud_get_instance();
        $xcrud->table('sh_income_types');
        $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
        $xcrud->pass_var('school_id', $school_id);
        $xcrud->show_primary_ai_field(false);
        $xcrud->columns('name,description,fixed,repeated');
        $xcrud->fields('name,description,fixed,repeated');
        $xcrud->label('name', lang('lbl_name'))->label('description', lang('description'))->label('fixed', lang('lbl_fixed'))->label('repeated', lang('lbl_repeated'));
        $xcrud->disabled('fixed,repeated','edit');
        $xcrud->replace_remove('soft_delete');
        $xcrud->load_view("view", "customview.php");
        $xcrud->unset_title();
        $xcrud->unset_search();
        $xcrud->unset_limitlist();
        $xcrud->unset_print();
        $xcrud->unset_csv();
        if(isset($incomeAdd)){
          if($incomeAdd == 0){
              $xcrud->unset_add();
          }
        }
        if(isset($incomeEdit)){
          if($incomeEdit == 0){
              $xcrud->unset_edit();
          }
        }
        if(isset($incomeDelete)){
          if($incomeDelete == 0){
              $xcrud->unset_remove();
          }
        }
       $data["income_types"] = $xcrud->render();
       $xcrud = xcrud_get_instance();
       $xcrud->table('sh_income_categories');
       $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->where('fixed', 'Yes');
       $xcrud->relation('income_type_id', 'sh_income_types', 'id', 'name', "sh_income_types.deleted_at IS NULL AND sh_income_types.school_id='" . $school_id . "' and fixed = 'Yes'");
       $xcrud->pass_var('school_id', $school_id);
       $xcrud->pass_var('fixed', 'Yes');
       $xcrud->show_primary_ai_field(false);
       $xcrud->columns('category_name,income_type_id,description,price,currency_type,date');
       $xcrud->fields('category_name,income_type_id,description,price,currency_type,date');
       
       $xcrud->label('category_name', lang('th_cat_name'))->label('income_type_id', lang('income_type'))->label('description', lang('description'))->label('price', lang('lbl_price'))->label('currency_type', lang('lbl_currency_type'))->label('date', lang('lbl_date'));
       
       $xcrud->relation('currency_type', 'sh_school_currencies', 'currency_id', array('currency_symbol','currency_name'),'school_id = '.$school_id.' and deleted_at is null','','',' - ');
      
       $xcrud->before_insert('setRepeated');
       $xcrud->before_update('setRepeatedUpdate');
       $xcrud->replace_remove('soft_delete');
       $xcrud->load_view("view", "customview.php");
       $xcrud->unset_title();
       $xcrud->unset_search();
       $xcrud->unset_limitlist();
       $xcrud->unset_print();
       $xcrud->unset_csv();
       if(isset($incomeAdd)){
          if($incomeAdd == 0){
              $xcrud->unset_add();
          }
        }
        if(isset($incomeEdit)){
          if($incomeEdit == 0){
              $xcrud->unset_edit();
          }
        }
        if(isset($incomeDelete)){
          if($incomeDelete == 0){
              $xcrud->unset_remove();
          }
        }
       $data["income_categories"] = $xcrud->render();
       $xcrud = xcrud_get_instance();
       $xcrud->table('sh_income_categories');
       $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->where('fixed', 'No');
       $xcrud->relation('income_type_id', 'sh_income_types', 'id', 'name', "sh_income_types.deleted_at IS NULL AND sh_income_types.school_id='" . $school_id . "' and fixed = 'No'");
       $xcrud->pass_var('school_id', $school_id);
       $xcrud->pass_var('fixed', 'No');
       $xcrud->show_primary_ai_field(false);
       $xcrud->columns('category_name,income_type_id,description,min_price,max_price,currency_type,date');
       $xcrud->fields('category_name,income_type_id,description,min_price,max_price,currency_type,date');
       
       $xcrud->label('category_name', lang('th_cat_name'))->label('income_type_id', lang('income_type'))->label('description', lang('description'))->label('min_price', lang('lbl_min_price'))->label('max_price', lang('lbl_max_price'))->label('currency_type', lang('lbl_currency_type'))->label('date', lang('lbl_date'));
       
       $xcrud->relation('currency_type', 'sh_school_currencies', 'currency_id', array('currency_symbol','currency_name'),'school_id = '.$school_id.' and deleted_at is null','','',' - ');
      
       $xcrud->before_insert('setRepeated');
       $xcrud->before_update('setRepeatedUpdate');
       $xcrud->replace_remove('soft_delete');
       $xcrud->load_view("view", "customview.php");
       $xcrud->unset_title();
       $xcrud->unset_search();
       $xcrud->unset_limitlist();
       $xcrud->unset_print();
       $xcrud->unset_csv();
       if(isset($incomeAdd)){
          if($incomeAdd == 0){
              $xcrud->unset_add();
          }
        }
        if(isset($incomeEdit)){
          if($incomeEdit == 0){
              $xcrud->unset_edit();
          }
        }
        if(isset($incomeDelete)){
          if($incomeDelete == 0){
              $xcrud->unset_remove();
          }
        }
       $data["income_categories_n"] = $xcrud->render();
       $this->load->view('accounts/income_settings', $data);
   }

   public function expense_settings(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    if($this->session->userdata("userdata")["role_id"] == '4'){
      $arr = $this->session->userdata("userdata")["persissions"];
      $array = json_decode($arr);
      if(isset($array)) {
        $expenseAdd = $expenseEdit = $expenseDelete = 0;
        foreach ($array as $key => $value) {
          if (in_array('accounts-expenseAdd', array($value->permission)) && $value->val == 'true') {
              $expenseAdd = '1';
          }if (in_array('accounts-expenseEdit', array($value->permission)) && $value->val == 'true') {
              $expenseEdit = '1';
          }if (in_array('accounts-expenseDelete', array($value->permission)) && $value->val == 'true') {
              $expenseDelete = '1';
          }
        }
      }
    }
    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_expense_types');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->show_primary_ai_field(false);
    $xcrud->columns('name,description,fixed,repeated');
    $xcrud->fields('name,description,fixed,repeated');
    
    $xcrud->label('name', lang('lbl_name'))->label('description', lang('description'))->label('fixed', lang('lbl_fixed'))->label('repeated', lang('lbl_repeated'));
    
    $xcrud->disabled('fixed,repeated','edit');
    $xcrud->replace_remove('soft_delete');
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    if(isset($expenseAdd)){
      if($expenseAdd == 0){
          $xcrud->unset_add();
      }
    }
    if(isset($expenseEdit)){
      if($expenseEdit == 0){
          $xcrud->unset_edit();
      }
    }
    if(isset($expenseDelete)){
      if($expenseDelete == 0){
          $xcrud->unset_remove();
      }
    }
    $data["expense_types"] = $xcrud->render();
    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_expense_categories');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->where('fixed','Yes');
    $xcrud->relation('expense_type_id', 'sh_expense_types', 'id', 'name', "sh_expense_types.deleted_at IS NULL AND sh_expense_types.school_id='" . $school_id . "' AND fixed='Yes'");
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->pass_var('fixed', 'Yes');
    $xcrud->show_primary_ai_field(false);
    $xcrud->columns('category_name,expense_type_id,description,price,currency_type,date');
    $xcrud->fields('category_name,expense_type_id,description,price,currency_type,date');
    
    $xcrud->label('category_name', lang('th_cat_name'))->label('expense_type_id', lang('expense_type'))->label('description', lang('description'))->label('price', lang('lbl_price'))->label('currency_type', lang('lbl_currency_type'))->label('date', lang('lbl_date'));
    
    $xcrud->relation('currency_type', 'sh_school_currencies', 'currency_id', array('currency_symbol','currency_name'),'school_id = '.$school_id.' and deleted_at is null','','',' - ');
   
    $xcrud->before_insert('setRepeatedExpense');
    $xcrud->before_update('setRepeatedExpenseUpdate');
    $xcrud->replace_remove('soft_delete');
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    if(isset($expenseAdd)){
      if($expenseAdd == 0){
          $xcrud->unset_add();
      }
    }
    if(isset($expenseEdit)){
      if($expenseEdit == 0){
          $xcrud->unset_edit();
      }
    }
    if(isset($expenseDelete)){
      if($expenseDelete == 0){
          $xcrud->unset_remove();
      }
    }
    $data["expense_categories"] = $xcrud->render();
    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_expense_categories');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null')->where('fixed','No');
    $xcrud->relation('expense_type_id', 'sh_expense_types', 'id', 'name', "sh_expense_types.deleted_at IS NULL AND sh_expense_types.school_id='" . $school_id . "' AND fixed='No'");
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->pass_var('fixed', 'No');
    $xcrud->show_primary_ai_field(false);
    $xcrud->columns('category_name,expense_type_id,description,min_price,max_price,currency_type,date');
    $xcrud->fields('category_name,expense_type_id,description,min_price,max_price,currency_type,date');
    
    $xcrud->label('category_name', lang('th_cat_name'))->label('expense_type_id', lang('expense_type'))->label('description', lang('description'))->label('min_price', lang('lbl_min_price'))->label('max_price', lang('lbl_max_price'))->label('currency_type', lang('lbl_currency_type'))->label('date', lang('lbl_date'));
    
    $xcrud->relation('currency_type', 'sh_school_currencies', 'currency_id', array('currency_symbol','currency_name'),'school_id = '.$school_id.' and deleted_at is null','','',' - ');
   
    $xcrud->before_insert('setRepeatedExpense');
    $xcrud->before_update('setRepeatedExpenseUpdate');
    $xcrud->replace_remove('soft_delete');
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    if(isset($expenseAdd)){
      if($expenseAdd == 0){
          $xcrud->unset_add();
      }
    }
    if(isset($expenseEdit)){
      if($expenseEdit == 0){
          $xcrud->unset_edit();
      }
    }
    if(isset($expenseDelete)){
      if($expenseDelete == 0){
          $xcrud->unset_remove();
      }
    }
    $data["expense_categories_n"] = $xcrud->render();
    $this->load->view('accounts/expense_settings', $data);
}


public function virtual_accounts(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    if($this->session->userdata("userdata")["role_id"] == '4'){
      $arr = $this->session->userdata("userdata")["persissions"];
      $array = json_decode($arr);
      if(isset($array)) {
        $virtualAdd = $virtualEdit = $virtualDelete = 0;
        foreach ($array as $key => $value) {
            if (in_array('accounts-virtualAdd', array($value->permission)) && $value->val == 'true') {
                $virtualAdd = '1';
            }if (in_array('accounts-virtualEdit', array($value->permission)) && $value->val == 'true') {
                $virtualEdit = '1';
            }if (in_array('accounts-virtualDelete', array($value->permission)) && $value->val == 'true') {
                $virtualDelete = '1';
            }
        }
      }
    }
    $xcrud = xcrud_get_instance();
    $xcrud->table('sh_virtual_accounts');
    $xcrud->where("school_id", $this->session->userdata("userdata")["sh_id"])->where('deleted_at is null');
    $xcrud->pass_var('school_id', $school_id);
    $xcrud->show_primary_ai_field(false);
    $xcrud->columns('account_name,account_no,bank_name,address,account_currency,contact_details');
    $xcrud->fields('account_name,account_no,account_type,bank_name,bank_code,iban,swift,branch_code,address,account_currency,contact_details');
    //update 10-08-2021 by Azeem
    // $xcrud->lable('account_name', lang('account_name'))->lable('account_no', lang('lbl_account_number'))->lable('bank_name', lang('lbl_bank_name'))->lable('address', lang('lbl_address'))->lable('account_currency', lang('currency'))->lable('contact_details', lang('contact_details'));
    
    $xcrud->label('account_name', lang('account_name'))->label('account_no', lang('lbl_account_number'))->label('bank_name', lang('lbl_bank_name'))->label('address', lang('lbl_address'))->label('account_currency', lang('currency'))->label('contact_details', lang('contact_details'))->label('swift', lang('lbl_swift'))->label('iban', lang('lbl_iban'))->label('account_type', lang('account_type'))->label('branch_code', lang('branch_code'))->label('bank_code', lang('bank_code'));
    
    $xcrud->relation('account_currency', 'sh_school_currencies', 'currency_id', array('currency_symbol','currency_name'),'school_id = '.$school_id.' and deleted_at is null','','',' - ');
    $xcrud->replace_remove('soft_delete');
    $xcrud->load_view("view", "customview.php");
    $xcrud->unset_title();
    $xcrud->unset_search();
    $xcrud->unset_limitlist();
    $xcrud->unset_print();
    $xcrud->unset_csv();
    if(isset($virtualAdd)){
      if($virtualAdd == 0){
          $xcrud->unset_add();
      }
    }
    if(isset($virtualEdit)){
      if($virtualEdit == 0){
          $xcrud->unset_edit();
      }
    }
    if(isset($virtualDelete)){
      if($virtualDelete == 0){
          $xcrud->unset_remove();
      }
    }
    $data["virtual_accounts"] = $xcrud->render();
    $this->load->view('accounts/virtual_accounts', $data);
}

public function dashboard(){
   $school_id = $this->session->userdata("userdata")["sh_id"];
    
    
    $this->load->view('accounts/dashboard');
}

function cmp($a, $b) {
    return strcmp($b->date_s, $a->date_s);
}

public function getAllData(){
    // update for academic year in accounts dashboard
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $data['total_balance_cash'] = 0;
    $data['total_balance_cheque'] = 0;
    $data['total_balance_dd'] = 0;

    if (isset($request->academic_year_id)) {
        $academic_year_id = $request->academic_year_id;
    } else {
      $academic_year_id = $this->session->userdata("userdata")["academic_year"];
    }

    $school_id = $this->session->userdata("userdata")["sh_id"];
    $query = "SELECT va.id as account_id,bank_name,account_name,account_no,SUM(COALESCE(CASE WHEN transaction_type = 'deposit' THEN amount END,0)) - SUM(COALESCE(CASE WHEN transaction_type = 'withdraw' THEN amount END,0)) as balance FROM `sh_virtual_accounts` va left join sh_virtual_transactions vt on va.id = vt.account_id where va.school_id = $school_id and va.deleted_at is null and vt.deleted_at is null group by va.id";
    $accounts = $this->db->query($query)->result();
    foreach ($accounts as $ac) {
        $last_deposit = $this->db->select('amount,date_format(date, "%d %b %Y") as date_f,symbol',false)->from('sh_virtual_transactions vt')->join('sh_currency c','c.id = vt.currency','left')->where('account_id', $ac->account_id)->where('transaction_type','deposit')->where('deleted_at is null')->order_by('date', 'desc')->limit(1)->get()->row();
        $last_withdraw = $this->db->select('amount,date_format(date, "%d %b %Y") as date_f,symbol',false)->from('sh_virtual_transactions vt')->join('sh_currency c','c.id = vt.currency','left')->where('account_id', $ac->account_id)->where('transaction_type','withdraw')->where('deleted_at is null')->order_by('date', 'desc')->limit(1)->get()->row();

        if($last_deposit){
            $ac->last_deposit_amount = $last_deposit->amount;
            $ac->last_deposit_date = $last_deposit->date_f;
            $ac->last_deposit_symbol = $last_deposit->symbol;
        }else{
            $ac->last_deposit_amount = '';
            $ac->last_deposit_date = '';
            $ac->last_deposit_symbol = '';
        }

        if($last_withdraw){
            $ac->last_withdraw_amount = $last_withdraw->amount;
            $ac->last_withdraw_date = $last_withdraw->date_f;
            $ac->last_withdraw_symbol = $last_withdraw->symbol;
        }else{
            $ac->last_withdraw_amount = '';
            $ac->last_withdraw_date = '';
            $ac->last_withdraw_symbol = '';
        }

        $ac->deposits = $this->db->select('vt.id,u.name,amount,date as date_order,date_format(date, "%d %b %Y") as date,date_format(date, "%d/%m/%Y") as date_f,collected_by,comment,payment_mode as mode,attachment as old_files,currency,deposit_by,symbol',false)->from('sh_virtual_transactions vt')->join('sh_users u','u.id = vt.deposit_by','left')->join('sh_currency c','c.id = vt.currency', 'left')->where('vt.deleted_at is null')->where('account_id', $ac->account_id)->where('transaction_type', 'deposit')->order_by('date_order','desc')->order_by('amount','desc')->get()->result();

        foreach ($ac->deposits as $d) {
            $files = $d->old_files;
            $files = explode(",", $files);
            $new_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $new_files[] = $temp;
                }
            }
            
            $d->old_files = $new_files;
        }

        $ac->withdraws = $this->db->select('vt.id,account_id,u.name,amount,date as date_order,date_format(date, "%d %b %Y") as date,date_format(date, "%d/%m/%Y") as date_f,collected_by,comment,payment_mode as mode,attachment as old_files,currency,withdraw_by,symbol',false)->from('sh_virtual_transactions vt')->join('sh_users u','u.id = vt.withdraw_by','left')->join('sh_currency c','c.id = vt.currency', 'left')->where('vt.deleted_at is null')->where('account_id', $ac->account_id)->where('transaction_type', 'withdraw')->order_by('date_order','desc')->order_by('amount','desc')->get()->result();

        foreach ($ac->withdraws as $w) {
            $files = $w->old_files;
            $files = explode(",", $files);
            $new_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $new_files[] = $temp;
                }
            }
            
            $w->old_files = $new_files;

            $w->balance = $ac->balance + $w->amount;
        }

    }
    $data["accounts"] = $accounts;
    $data["employees"] = $this->db->select('id,name')->from('sh_users')->where('school_id', $school_id)->where('deleted_at',0)->where_in('role_id',array(1,4))->get()->result();
    $data["date"] = date('d/m/Y');
    $data["income_types"] = $this->db->select('id,name')->from('sh_income_types')->where('school_id', $school_id)->where('deleted_at is null')->get()->result();
    $data["expense_types"] = $this->db->select('id,name')->from('sh_expense_types')->where('school_id', $school_id)->where('deleted_at is null')->get()->result();
    
     $incomes_result = $this->db->select('"true" as income,i.id,income_id,ic.fixed,ic.repeated,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by',false)->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')->join('sh_income_categories ic','i.income_category_id = ic.id')->join('sh_users u', 'u.id = i.collected_by','left')->join('sh_users u1', 'u1.id = i.created_by','left')->join('sh_currency c', 'c.id = i.currency', 'left')->where('i.deleted_at is null')->where('i.school_id', $school_id)->where('i.academic_year_id', $academic_year_id)->order_by('i.date','desc')->order_by('i.created_at','desc')->get()->result();
    $fee_logs = $this->db->select('"fee" as type, "Student" as income_type,"Fee Collection" as category_name,"System" as collected_by,"false" as income,date_format(f.created_at, "%d %b %Y") as date, date(f.created_at) as date_s,date_format(f.created_at, "%d/%m/%Y") as date_f, f.mode',false)->from('sh_fee_collection f')->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('f.school_id', $school_id)->where('f.deleted_at is NULL')->where('ft.deleted_at is null')->where('ft.academic_year_id',$academic_year_id)->group_by('date(created_at)')->get()->result();

    $amount = $this->db->select('date_format(created_at, "%d %b %Y") as date,COALESCE(sum(paid_amount), 0) as total',false)->from('sh_fee_collection')->where('school_id', $school_id)->where('deleted_at is NULL')->group_by('date(created_at)')->get()->result();
    
    foreach ($fee_logs as $key => $fe) {
      foreach ($amount as $key1 => $am) {
        if ($fe->date == $am->date) {
            $fe->amount = $am->total;
        }
      }
    }

    // total fee modes check added by sheraz

    $allCollectedFee = $this->db->select('f.paid_amount,f.mode')->from('sh_fee_collection f')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('ft.academic_year_id', $academic_year_id)->where('f.school_id', $school_id)->where('f.deleted_at is NULL')->get()->result();
    
    $data['total_fee_cash'] = 0;
    $data['total_fee_cheque'] = 0;
    $data['total_fee_dd'] = 0;
    foreach ($allCollectedFee as $key => $cf) {
      if ($cf->mode == 'cash') {
          $data['total_fee_cash'] += $cf->paid_amount; 
      }
      elseif($cf->mode == 'cheque'){
          $data['total_fee_cheque'] += $cf->paid_amount;
      }
      else {
          $data['total_fee_dd'] += $cf->paid_amount;
      }
    }

    // ends here

    $query = "select 'payroll' as type, 'false' as income,'Employee' as income_type,'Deductions' as category_name, 'System' as collected_by, date_format(created_at, '%d %b %Y') as date, date(created_at) as date_s,date_format(created_at, '%d/%m/%Y') as date_f, COALESCE(sum(deduction_amount), 0) as amount from (select deduction_amount,created_at from sh_payroll where school_id = $school_id and deleted_at is null group by user_id, date) as temp group by date(created_at) having amount <> 0";
    $deductions = $this->db->query($query)->result();
    foreach ($deductions as $d) {
      if($d->amount < 0){
        $d->amount *=-1;
      }
    }
    
    $incomes = array_merge($incomes_result,$fee_logs,$deductions);
    usort($incomes, array("Accounts", "cmp"));
    
    
    foreach ($incomes as $i) {
      if($i->income == "true"){
        $income_categories = $this->db->select('i.id as income_id,ic.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_income_categories ic')->join('sh_incomes i','i.income_category_id = ic.id and i.deleted_at is null and ic.deleted_at is null','left')->where('income_type_id', $i->income_id)->group_by('ic.id')->having('(price <> total and fixed="Yes") or ic.id = '.$i->income_category_id.' or (fixed = "No" and income_id is null) or repeated = "Yes"')->get()->result();
        $totals = $this->db->select('COALESCE(sum(amount),0) as total,price,fixed,repeated',false)->from('sh_incomes i')->join('sh_income_categories ic', 'i.income_category_id = ic.id')->where('income_category_id', $i->income_category_id)->where('i.deleted_at is null')->get()->row();
        $total_collected = $totals->total;
        $price = $totals->price;
        $i->income_categories = $income_categories;
        if($totals->repeated == "Yes"){
          $i->maximum = $price;
        }else{
          $i->maximum = $price - $total_collected + $i->amount;
        }

        foreach($income_categories as $ic){
            if($ic->id == $i->income_category_id){
                $ic->total = $ic->total - $i->amount;
            }
        }

        $files = $i->old_files;
        $files = explode(",", $files);
        $new_files = array();
        if($files[0] != ""){
            foreach ($files as $f) {
                $temp = new stdClass();
                $temp->name = $f;
                $temp->new_name = $f;
                $new_files[] = $temp;
            }
        }

        $i->old_files = $new_files;
      }
    }
    $data["fees"] = $this->db->select('u.name,ft.name as fee_type,uu.name as collected_by,paid_amount,feetype_amount,c.name as class_name,b.name as batch_name,receipt_no,ft.academic_year_id, ay.name as academic_year_name')->from('sh_fee_collection fc')->join('sh_users u','fc.student_id = u.id')->join('sh_users uu','fc.collector_id = uu.id')->join('sh_fee_types ft','fc.feetype_id = ft.id')->join('sh_classes c','fc.class_id = c.id')->join('sh_batches b','fc.batch_id = b.id')->join('sh_academic_years ay', 'ft.academic_year_id = ay.id')->where('fc.school_id', $school_id)->where('fc.deleted_at',NULL)->where('date(fc.created_at)',date("Y-m-d"))->get()->result();
    $data["fee_date"] = $data["payroll_date"] = date("d/m/Y");
    $data["fee_total"] = $this->db->select('COALESCE(sum(paid_amount), 0) as total',false)->from('sh_fee_collection')->where('school_id', $school_id)->where('deleted_at',NULL)->where('date(created_at)',date("Y-m-d"))->get()->row()->total;
    



    $data["payrolls"] = $this->db->select('u.name,st.name as salary,amount_paid,total_amount,uu.name as paid_by,receipt_no')->from('sh_payroll p')->join('sh_users u', 'p.user_id = u.id')->join('sh_users uu','p.paid_by = uu.id')->join('sh_salary_types st','p.salary_type_id = st.id')->where('date(p.created_at)', date("Y-m-d"))->where('p.deleted_at is null')->where('p.academic_year_id', $academic_year_id)->where('p.school_id',$school_id)->get()->result();

    $data["pay_total"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total',false)->from('sh_payroll')->where('school_id', $school_id)->where('date(created_at)',date("Y-m-d"))->where('deleted_at is null')->where('academic_year_id', $academic_year_id)->get()->row()->total;

    $this->db->select('COALESCE(sum(paid_amount), 0) as fee_collected', false);
    $this->db->from('sh_fee_collection f');
    $this->db->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner');
    $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
    $this->db->where('f.deleted_at is null');
    $this->db->where('ft.deleted_at is null');
    $this->db->where('ft.academic_year_id',$academic_year_id);
    $this->db->where('u.academic_year_id',$academic_year_id);
    $data['fee_collected'] = $this->db->get()->result()[0]->fee_collected;
    
    
    $data['total_deduction_cash'] = 0;
    $query = "select COALESCE(sum(deduction_amount), 0) as total from (select deduction_amount from sh_payroll where school_id = $school_id and deleted_at is null and academic_year_id = $academic_year_id group by user_id, date) as temp";
    $data["total_deductions"] = $this->db->query($query)->row()->total;
    $data['total_deduction_cash'] = $data["total_deductions"];

    if($data["total_deductions"] < 0){
      $data["total_deductions"] *= -1;
    }

    $data["payroll_amount"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total', false)->from('sh_payroll')->where('school_id', $school_id)->where('deleted_at is null')->where('academic_year_id', $academic_year_id)->get()->row()->total;


    // total income modes check added by sheraz

    $payroll_amount = $this->db->select('amount_paid,mode')->from('sh_payroll')->where('school_id', $school_id)->where('deleted_at is null')->where('academic_year_id', $academic_year_id)->get()->result();

    $data['total_payroll_cash'] = 0;
    $data['total_payroll_cheque'] = 0;
    $data['total_payroll_dd'] = 0;

    foreach ($payroll_amount as $key => $pa) {
        if ($pa->mode == 'cash') {
            $data['total_payroll_cash'] += $pa->amount_paid;
        }
        elseif ($pa->mode == 'cheque') {
            $data['total_payroll_cheque'] += $pa->amount_paid;
        }
        else {
            $data['total_payroll_dd'] += $pa->amount_paid;
        }
    }


    // ends here


    $data["incomes"] = $incomes;
    
    // total income modes check added by sheraz

    $data['total_income_cash'] = 0;
    $data['total_income_cheque'] = 0;
    $data['total_income_dd'] = 0;
    foreach ($data['incomes'] as $key => $inc) {
        if ($inc->mode == 'cash') {
            $data['total_income_cash'] += $inc->amount;  
        }
        elseif($inc->mode == 'cheque') {
            $data['total_income_cheque'] += $inc->amount;
        }
        else {
            $data['total_income_dd'] += $inc->amount;
        }
    }
    
    // ends here
    $data["income_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_incomes i')->join('sh_income_categories ic','i.income_category_id = ic.id')->where('i.school_id', $school_id)->where('i.deleted_at is null')->where('ic.deleted_at is null')->where('i.academic_year_id', $academic_year_id)->get()->row()->total;

    // total other income modes check added by sheraz

    $other_incomes = $this->db->select('i.amount,i.payment_mode')->from('sh_incomes i')->join('sh_income_categories ic','i.income_category_id = ic.id')->where('i.school_id', $school_id)->where('i.deleted_at is null')->where('ic.deleted_at is null')->where('i.academic_year_id', $academic_year_id)->get()->result();

    $data['total_other_income_cash'] = 0;
    $data['total_other_income_cheque'] = 0;
    $data['total_other_income_dd'] = 0;
    foreach ($other_incomes as $key => $oi) {
        if ($oi->payment_mode == 'cash') {
            $data['total_other_income_cash'] += $oi->amount;
        }
        elseif($oi->payment_mode == 'cheque'){
            $data['total_other_income_cheque'] += $oi->amount;
        } 
        else {
            $data['total_other_income_dd'] += $oi->amount;
        }
    }
    
    // ends here

    $expenses_result = $this->db->select('"true" as expense,e.id,expense_id,ec.fixed,ec.repeated,expense_category_id,paid_by as paid_by_id,et.name as expense_type, category_name, amount,u.name as paid_by,date_format(e.date, "%d %b %Y") as date,comment,date_format(e.date, "%d/%m/%Y") as date_f,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by,e.date as date_s',false)->from('sh_expenses e')->join('sh_expense_types et','e.expense_id = et.id','left')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->join('sh_users u', 'u.id = e.paid_by','left')->join('sh_users u1', 'u1.id = e.created_by','left')->join('sh_currency c','c.id = e.currency', 'left')->where('e.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->order_by('e.date','desc')->order_by('e.created_at','desc')->get()->result();
    $payroll_logs = $this->db->select('"false" as expense,"Employee" as expense_type,"Payroll" as category_name,"System" as paid_by,COALESCE(sum(amount_paid), 0) as amount,p.mode,date_format(p.created_at, "%d %b %Y") as date, date(p.created_at) as date_s,date_format(p.created_at, "%d/%m/%Y") as date_f',false)->from('sh_payroll p')->join('sh_salary_types st','p.salary_type_id = st.id')->where('p.school_id', $school_id)->where('p.deleted_at is null')->where('p.academic_year_id', $academic_year_id)->group_by('date(p.created_at)')->get()->result();
    $expenses = array_merge($expenses_result,$payroll_logs);
    usort($expenses, array("Accounts", "cmp"));
    
    foreach ($expenses as $e) {
      if($e->expense == "true"){
        $expense_categories = $this->db->select('e.id as expense_id,ec.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_expense_categories ec')->join('sh_expenses e','e.expense_category_id = ec.id and e.deleted_at is null and ec.deleted_at is null','left')->where('expense_type_id', $e->expense_id)->group_by('ec.id')->having('(price <> total and fixed="Yes") or ec.id = '.$e->expense_category_id.' or (fixed = "No" and expense_id is null) or repeated = "Yes"')->get()->result();

        $totals = $this->db->select('COALESCE(sum(amount),0) as total,price,fixed,repeated',false)->from('sh_expenses e')->join('sh_expense_categories ec', 'e.expense_category_id = ec.id')->where('expense_category_id', $e->expense_category_id)->where('e.deleted_at is null')->where('e.academic_year_id', $academic_year_id)->get()->row();
        $total_collected = $totals->total;
        $price = $totals->price;
        $e->expense_categories = $expense_categories;
        if($totals->repeated == "Yes"){
          $e->maximum = $price;
        }else{
          $e->maximum = $price - $total_collected + $e->amount;
        }
        

        foreach($expense_categories as $ec){
            if($ec->id == $e->expense_category_id){
                $ec->total = $ec->total - $e->amount;
            }
        }

        $files = $e->old_files;
        $files = explode(",", $files);
        $new_files = array();
        if($files[0] != ""){
            foreach ($files as $f) {
                $temp = new stdClass();
                $temp->name = $f;
                $temp->new_name = $f;
                $new_files[] = $temp;
            }
        }

        $e->old_files = $new_files;
      }
    }
    $data["expenses"] = $expenses;


    $data["expense_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_expenses e')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->where('e.deleted_at is null')->where('ec.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->get()->row()->total;

    // total expense modes check added by sheraz

    $total_exp = $this->db->select('e.amount,e.payment_mode')->from('sh_expenses e')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->where('e.deleted_at is null')->where('ec.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->get()->result();


    $data['total_other_expense_cash'] = 0;
    $data['total_other_expense_cheque'] = 0;
    $data['total_other_expense_dd'] = 0;
    foreach ($total_exp as $key => $exp) {
      if ($exp->payment_mode == 'cash') {
            $data['total_other_expense_cash'] += $exp->amount;
        }
        elseif($exp->payment_mode == 'cheque'){
            $data['total_other_expense_cheque'] += $exp->amount;
        }
        else {
            $data['total_other_expense_dd'] += $exp->amount;
        }  
    }
    // ends here
    $data["currencies"] = $this->db->select('currency_id,symbol,name')->from('sh_school_currencies sc')->join('sh_currency c','c.id = sc.currency_id')->where('school_id', $school_id)->where('deleted_at is null')->get()->result();
    $default_currency = "";
    $currency = $this->db->select('currency_id')->from('sh_school_currencies')->where('school_id', $school_id)->where('is_default', 'yes')->where('deleted_at is null')->get()->row();
    if($currency){
        if($currency->currency_id != '0' && $currency->currency_id != NULL){
            $default_currency = $currency->currency_id;
        }
    }
    
    $data["default_currency"] = $default_currency;
    $data["total_income"] = $data["income_total"] + $data["fee_collected"] + $data["total_deductions"];

    
    $data["total_expense"] = $data["expense_total"] + $data["payroll_amount"];
    $data["school_currency"] = $this->session->userdata("userdata")["currency_symbol"];
    


    //deleted fee logs added in expense code added by sheraz 16/11/21
    
    $deleted_fee = $this->db->select('f.*,u.name')->from('sh_fee_collection f')->join('sh_users u','f.collector_id = u.id')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('ft.academic_year_id',$academic_year_id)->where('f.school_id',$school_id)->where('f.deleted_at is null')->where('f.refund_fee','1')->get()->result();
    
    $new_deleted_fee = new stdClass();
    
    $sort=array();
    
    
    foreach ($deleted_fee as $key => $f) {
      $f->expense_type = lang('lbl_fee');
      $f->category_name = lang('refund_fee');
      $f->amount = $f->paid_amount;
    //   $f->date = date('d F Y', strtotime($f->deleted_at));
    //   $f->date = date('d F Y', strtotime($f->updated_at));
      $f->date = date('d F Y', strtotime($f->created_at));
      $f->paid_by = $f->name;
      $f->symbol = $this->session->userdata("userdata")["currency_symbol"];
      $f->expense = 'fee';
      array_push($data['expenses'], $f); 
    }
    
    foreach ($data['expenses'] as $key => $part) {
     $sort[$key] = strtotime($part->date);
    }

    array_multisort($sort, SORT_DESC, $data['expenses']);

    $total_deleted_fee = $this->db->select('COALESCE(sum(f.paid_amount), 0) as total',false)->from('sh_fee_collection f')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('ft.academic_year_id',$academic_year_id)->where('f.school_id',$school_id)->where('f.deleted_at is null')->where('f.refund_fee','1')->get()->row()->total;

    // total expense modes check added by sheraz
    $data['total_all_exp_cash'] = $data['total_payroll_cash'] + $data['total_other_expense_cash'];
    $data['total_all_exp_cheque'] = $data['total_payroll_cheque'] + $data['total_other_expense_cheque'];
    $data['total_all_exp_dd'] = $data['total_payroll_dd'] + $data['total_other_expense_dd'];
    // ends here
    $data['total_expense'] = $data['total_expense'] + $total_deleted_fee;
    $data['total_balance'] = $data['total_income'] - $data['total_expense'];
    $data['total_balance_cash'] = $data['total_income_cash'] - $data['total_all_exp_cash'];
    $data['total_balance_cheque'] = $data['total_income_cheque'] - $data['total_all_exp_cheque'];
    $data['total_balance_dd'] = $data['total_income_dd'] - $data['total_all_exp_dd'];
    echo json_encode($data);
}

public function getVirtualAccounts(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $query = "SELECT va.id as account_id,bank_name,account_name,account_no,SUM(COALESCE(CASE WHEN transaction_type = 'deposit' THEN amount END,0)) - SUM(COALESCE(CASE WHEN transaction_type = 'withdraw' THEN amount END,0)) as balance FROM `sh_virtual_accounts` va left join sh_virtual_transactions vt on va.id = vt.account_id where va.school_id = $school_id and va.deleted_at is null and vt.deleted_at is null group by va.id";
    $accounts = $this->db->query($query)->result();
    foreach ($accounts as $ac) {
        $last_deposit = $this->db->select('amount,date_format(date, "%d %b %Y") as date_f,symbol',false)->from('sh_virtual_transactions vt')->join('sh_currency c','c.id = vt.currency','left')->where('account_id', $ac->account_id)->where('transaction_type','deposit')->where('deleted_at is null')->order_by('date', 'desc')->limit(1)->get()->row();
        $last_withdraw = $this->db->select('amount,date_format(date, "%d %b %Y") as date_f,symbol',false)->from('sh_virtual_transactions vt')->join('sh_currency c','c.id = vt.currency','left')->where('account_id', $ac->account_id)->where('transaction_type','withdraw')->where('deleted_at is null')->order_by('date', 'desc')->limit(1)->get()->row();

        if($last_deposit){
            $ac->last_deposit_amount = $last_deposit->amount;
            $ac->last_deposit_date = $last_deposit->date_f;
            $ac->last_deposit_symbol = $last_deposit->symbol;
        }else{
            $ac->last_deposit_amount = '';
            $ac->last_deposit_date = '';
            $ac->last_deposit_symbol = '';
        }

        if($last_withdraw){
            $ac->last_withdraw_amount = $last_withdraw->amount;
            $ac->last_withdraw_date = $last_withdraw->date_f;
            $ac->last_withdraw_symbol = $last_withdraw->symbol;
        }else{
            $ac->last_withdraw_amount = '';
            $ac->last_withdraw_date = '';
            $ac->last_withdraw_symbol = '';
        }

        $ac->deposits = $this->db->select('vt.id,u.name,amount,date as date_order,date_format(date, "%d %b %Y") as date,date_format(date, "%d/%m/%Y") as date_f,collected_by,comment,payment_mode as mode,attachment as old_files,currency,deposit_by,symbol',false)->from('sh_virtual_transactions vt')->join('sh_users u','u.id = vt.deposit_by','left')->join('sh_currency c','c.id = vt.currency', 'left')->where('vt.deleted_at is null')->where('account_id', $ac->account_id)->where('transaction_type', 'deposit')->order_by('date_order','desc')->order_by('amount','desc')->get()->result();

        foreach ($ac->deposits as $d) {
            $files = $d->old_files;
            $files = explode(",", $files);
            $new_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $new_files[] = $temp;
                }
            }
            
            $d->old_files = $new_files;
        }

        $ac->withdraws = $this->db->select('vt.id,account_id,u.name,amount,date as date_order,date_format(date, "%d %b %Y") as date,date_format(date, "%d/%m/%Y") as date_f,collected_by,comment,payment_mode as mode,attachment as old_files,currency,withdraw_by,symbol',false)->from('sh_virtual_transactions vt')->join('sh_users u','u.id = vt.withdraw_by','left')->join('sh_currency c','c.id = vt.currency', 'left')->where('vt.deleted_at is null')->where('account_id', $ac->account_id)->where('transaction_type', 'withdraw')->order_by('date_order','desc')->order_by('amount','desc')->get()->result();

        foreach ($ac->withdraws as $w) {
            $files = $w->old_files;
            $files = explode(",", $files);
            $new_files = array();
            if($files[0] != ""){
                foreach ($files as $f) {
                    $temp = new stdClass();
                    $temp->name = $f;
                    $temp->new_name = $f;
                    $new_files[] = $temp;
                }
            }
            
            $w->old_files = $new_files;

            $w->balance = $ac->balance + $w->amount;
        }

    }
    $data["accounts"] = $accounts;

    echo json_encode($data);
}

function updateDeposit(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $files_1 = $request->old_files;
    $files_2 = $request->files;

    $files_1 = array_merge($files_1,$files_2);

    $files = "";
    
    if($files_1){
        $files = array();
        foreach ($files_1 as $value) {
            $files[] = $value->new_name;
        }
        $files = array_unique($files);
        $files=implode(",", $files);
    }
    $id = $request->id;
    $data = array('amount' => $request->amount,
        'deposit_by' => $request->deposit_by,
        'date' => to_mysql_date($request->date_f),
        'collected_by' =>$request->collected_by,
        'comment' =>$request->comment,
        'payment_mode' =>$request->mode,
        'attachment' => $files,
        'currency' => $request->currency);

    $this->db->where('id', $id)->update('sh_virtual_transactions', $data);

    $data1 = array();
    $data1["message"] = "Record updated successfully";
    echo json_encode($data1);

    
    
}

function updateWithdraw(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $files_1 = $request->old_files;
    $files_2 = $request->files;

    $files_1 = array_merge($files_1,$files_2);

    $files = "";
    
    if($files_1){
        $files = array();
        foreach ($files_1 as $value) {
            $files[] = $value->new_name;
        }
        $files = array_unique($files);
        $files=implode(",", $files);
    }
    $id = $request->id;
    $data = array('amount' => $request->amount,
        'withdraw_by' => $request->withdraw_by,
        'date' => to_mysql_date($request->date_f),
        'paid_by' =>$request->paid_by,
        'comment' =>$request->comment,
        'payment_mode' =>$request->mode,
        'attachment' => $files,
        'currency' => $request->currency);

    $this->db->where('id', $id)->update('sh_virtual_transactions', $data);

    $data1 = array();
    $data1["message"] = "Record updated successfully";
    echo json_encode($data1);

    
    
}

function getEmployees(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $data["employees"] = $this->db->select('id,name')->from('sh_users')->where('school_id', $school_id)->where('deleted_at',0)->where_in('role_id',array(1,4))->get()->result();
    echo json_encode($data);
}

function getIncomeTypes(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $data["income_types"] = $this->db->select('id,name')->from('sh_income_types')->where('school_id', $school_id)->where('deleted_at is null')->get()->result();
    echo json_encode($data);
}

function getIncomeCategoriesforEdit(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $income_id = $request->income_id;
    $id = $request->id;

    $income_data = $this->db->select('amount,income_category_id')->from('sh_incomes')->where('id', $id)->get()->row();
    $income_category_id = $income_data->income_category_id;
    $amount = $income_data->amount;

    $income_categories = $this->db->select('i.id as income_id,ic.id,category_name,ic.min_price,ic.max_price,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_income_categories ic')->join('sh_incomes i','i.income_category_id = ic.id and i.deleted_at is null and ic.deleted_at is null','left')->where('income_type_id', $income_id)->group_by('ic.id')->having('(price <> total and fixed="Yes") or ic.id = '.$income_category_id.' or (fixed = "No" and income_id is null) or repeated = "Yes"')->get()->result();

    foreach($income_categories as $i){
        if($i->id == $income_category_id){
            $i->total = $i->total - $amount;
        }
    }

    $data["income_categories"] = $income_categories;
    echo json_encode($data);


}

function getExpenseCategoriesforEdit(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $expense_id = $request->expense_id;
    $id = $request->id;

    $expense_data = $this->db->select('amount,expense_category_id')->from('sh_expenses')->where('id', $id)->get()->row();
    $expense_category_id = $expense_data->expense_category_id;
    $amount = $expense_data->amount;

    $expense_categories = $this->db->select('e.id as expense_id,ec.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_expense_categories ec')->join('sh_expenses e','e.expense_category_id = ec.id','left')->where('expense_type_id', $expense_id)->where('e.deleted_at is null')->where('ec.deleted_at is null')->group_by('ec.id')->having('(price <> total and fixed="Yes") or ec.id = '.$expense_category_id.' or (fixed="No" and expense_id is null) or repeated = "Yes"')->get()->result();

    foreach($expense_categories as $e){
        if($e->id == $expense_category_id){
            $e->total = $e->total - $amount;
        }
    }

    $data["expense_categories"] = $expense_categories;
    echo json_encode($data);


}

function getIncomeCategories(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $income_type_id = $request->id;
    $data["income_categories"] = $this->db->select('i.id as income_id,ic.min_price,ic.max_price,ic.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_income_categories ic')->join('sh_incomes i','i.income_category_id = ic.id and i.deleted_at is null and ic.deleted_at is null','left')->where('income_type_id', $income_type_id)->group_by('ic.id')->having('(price <> total and fixed="Yes" ) or (fixed="No" and income_id is null) or repeated = "Yes"')->get()->result();
    echo json_encode($data);
}

function getExpenseTypes(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $data["expense_types"] = $this->db->select('id,name')->from('sh_expense_types')->where('school_id', $school_id)->where('deleted_at is null')->get()->result();
    echo json_encode($data);
}

function getExpenseCategories(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $expense_type_id = $request->id;
    $data["expense_categories"] = $this->db->select('e.id as expense_id,ec.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_expense_categories ec')->join('sh_expenses e','e.expense_category_id = ec.id and e.deleted_at is null and ec.deleted_at is null','left')->where('expense_type_id', $expense_type_id)->group_by('ec.id')->having('(price <> total and fixed="Yes") or (fixed = "No" and expense_id is null) or repeated = "Yes"')->get()->result();
    echo json_encode($data);
}

function getDate(){
    $data["date"] = date('d/m/Y');
    echo json_encode($data);
}

// function get_incomes_expenses(){
//     $postdata = file_get_contents("php://input");
//     $request = json_decode($postdata);
//     $school_id = $this->session->userdata("userdata")["sh_id"];
//     $academic_year_id = $request->academic_year_id;
//     $incomes_result = $this->db->select('"true" as income,i.id,income_id,ic.fixed,ic.repeated,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by',false)->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')->join('sh_income_categories ic','i.income_category_id = ic.id')->join('sh_users u', 'u.id = i.collected_by','left')->join('sh_users u1', 'u1.id = i.created_by','left')->join('sh_currency c', 'c.id = i.currency', 'left')->where('i.deleted_at is null')->where('i.school_id', $school_id)->order_by('i.date','desc')->order_by('i.created_at','desc')->get()->result();
//     $fee_logs = $this->db->select('"fee" as type, "Student" as income_type,"Fee Collection" as category_name,"System" as collected_by,"false" as income,COALESCE(sum(paid_amount), 0) as amount,date_format(f.created_at, "%d %b %Y") as date, date(f.created_at) as date_s,date_format(f.created_at, "%d/%m/%Y") as date_f',false)->from('sh_fee_collection f')->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('f.school_id', $school_id)->where('f.deleted_at is null')->where('ft.deleted_at is null')->where('ft.academic_year_id',$academic_year_id)->group_by('date(created_at)')->get()->result();
//     $query = "select 'payroll' as type, 'false' as income,'Employee' as income_type,'Deductions' as category_name, 'System' as collected_by, date_format(created_at, '%d %b %Y') as date, date(created_at) as date_s,date_format(created_at, '%d/%m/%Y') as date_f, COALESCE(sum(deduction_amount), 0) as amount from (select deduction_amount,created_at from sh_payroll where school_id = $school_id and deleted_at is null group by user_id, date) as temp group by date(created_at) having amount <> 0";
//     $deductions = $this->db->query($query)->result();
//     foreach ($deductions as $d) {
//       if($d->amount < 0){
//         $d->amount *=-1;
//       }
//     }
//     $incomes = array_merge($incomes_result,$fee_logs,$deductions);
//     usort($incomes, array("Accounts", "cmp"));
//     foreach ($incomes as $i) {
//       if($i->income == "true"){
//         $income_categories = $this->db->select('i.id as income_id,ic.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_income_categories ic')->join('sh_incomes i','i.income_category_id = ic.id and i.deleted_at is null and ic.deleted_at is null','left')->where('income_type_id', $i->income_id)->group_by('ic.id')->having('(price <> total and fixed="Yes") or ic.id = '.$i->income_category_id.' or (fixed = "No" and income_id is null) or repeated = "Yes"')->get()->result();
//         $totals = $this->db->select('COALESCE(sum(amount),0) as total,price,fixed,repeated',false)->from('sh_incomes i')->join('sh_income_categories ic', 'i.income_category_id = ic.id')->where('income_category_id', $i->income_category_id)->where('i.deleted_at is null')->get()->row();
//         $total_collected = $totals->total;
//         $price = $totals->price;
//         $i->income_categories = $income_categories;
//         if($totals->repeated == "Yes"){
//           $i->maximum = $price;
//         }else{
//           $i->maximum = $price - $total_collected + $i->amount;
//         }

//         foreach($income_categories as $ic){
//             if($ic->id == $i->income_category_id){
//                 $ic->total = $ic->total - $i->amount;
//             }
//         }

//         $files = $i->old_files;
//         $files = explode(",", $files);
//         $new_files = array();
//         if($files[0] != ""){
//             foreach ($files as $f) {
//                 $temp = new stdClass();
//                 $temp->name = $f;
//                 $temp->new_name = $f;
//                 $new_files[] = $temp;
//             }
//         }

//         $i->old_files = $new_files;
//       }
//     }
//     $data["fees"] = $this->db->select('u.name,ft.name as fee_type,uu.name as collected_by,paid_amount,feetype_amount,c.name as class_name,b.name as batch_name,receipt_no,ft.academic_year_id, ay.name as academic_year_name')->from('sh_fee_collection fc')->join('sh_users u','fc.student_id = u.id')->join('sh_users uu','fc.collector_id = uu.id')->join('sh_fee_types ft','fc.feetype_id = ft.id')->join('sh_classes c','fc.class_id = c.id')->join('sh_batches b','fc.batch_id = b.id')->join('sh_academic_years ay', 'ft.academic_year_id = ay.id')->where('fc.school_id', $school_id)->where('date(fc.created_at)',date("Y-m-d"))->where('fc.deleted_at is null')->get()->result();
//     $data["fee_date"] = $data["payroll_date"] = date("d/m/Y");
//     $data["fee_total"] = $this->db->select('COALESCE(sum(paid_amount), 0) as total',false)->from('sh_fee_collection')->where('school_id', $school_id)->where('date(created_at)',date("Y-m-d"))->where('deleted_at is null')->get()->row()->total;

//     $data["payrolls"] = $this->db->select('u.name,st.name as salary,amount_paid,total_amount,uu.name as paid_by,receipt_no')->from('sh_payroll p')->join('sh_users u', 'p.user_id = u.id')->join('sh_users uu','p.paid_by = uu.id')->join('sh_salary_types st','p.salary_type_id = st.id')->where('date(p.created_at)', date("Y-m-d"))->where('p.deleted_at is null')->where('p.school_id',$school_id)->get()->result();

//     $data["pay_total"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total',false)->from('sh_payroll')->where('school_id', $school_id)->where('date(created_at)',date("Y-m-d"))->where('deleted_at is null')->where('academic_year_id', $academic_year_id)->get()->row()->total;

//     $this->db->select('COALESCE(sum(paid_amount), 0) as fee_collected', false);
//     $this->db->from('sh_fee_collection f');
//     $this->db->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner');
//     $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
//     $this->db->where('f.deleted_at is null');
//     $this->db->where('ft.deleted_at is null');
//     $this->db->where('ft.academic_year_id',$academic_year_id);
//     $data['fee_collected'] = $this->db->get()->result()[0]->fee_collected;

//     $query = "select COALESCE(sum(deduction_amount), 0) as total from (select deduction_amount from sh_payroll where school_id = $school_id and deleted_at is null and academic_year_id = $academic_year_id group by user_id, date) as temp";
//     $data["total_deductions"] = $this->db->query($query)->row()->total;

//     if($data["total_deductions"] < 0){
//       $data["total_deductions"] *= -1;
//     }

//     $data["payroll_amount"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total', false)->from('sh_payroll')->where('school_id', $school_id)->where('academic_year_id', $academic_year_id)->where('deleted_at is null')->get()->row()->total;

//     $data["incomes"] = $incomes;
//     $data["income_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_incomes i')->join('sh_income_categories ic','i.income_category_id = ic.id')->where('i.school_id', $school_id)->where('i.deleted_at is null')->where('ic.deleted_at is null')->where('i.academic_year_id', $academic_year_id)->get()->row()->total;
//     $expenses_result = $this->db->select('"true" as expense,e.id,expense_id,ec.fixed,ec.repeated,expense_category_id,paid_by as paid_by_id,et.name as expense_type, category_name, amount,u.name as paid_by,date_format(e.date, "%d %b %Y") as date,comment,date_format(e.date, "%d/%m/%Y") as date_f,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by,e.date as date_s',false)->from('sh_expenses e')->join('sh_expense_types et','e.expense_id = et.id','left')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->join('sh_users u', 'u.id = e.paid_by','left')->join('sh_users u1', 'u1.id = e.created_by','left')->join('sh_currency c','c.id = e.currency', 'left')->where('e.deleted_at is null')->where('e.school_id', $school_id)->order_by('e.date','desc')->order_by('e.created_at','desc')->get()->result();
//     $payroll_logs = $this->db->select('"false" as expense,"Employee" as expense_type,"Payroll" as category_name,"System" as paid_by,COALESCE(sum(amount_paid), 0) as amount,date_format(p.created_at, "%d %b %Y") as date, date(p.created_at) as date_s,date_format(p.created_at, "%d/%m/%Y") as date_f',false)->from('sh_payroll p')->join('sh_salary_types st','p.salary_type_id = st.id')->where('p.school_id', $school_id)->where('p.deleted_at is null')->group_by('date(p.created_at)')->get()->result();
//     $expenses = array_merge($expenses_result,$payroll_logs);
//     usort($expenses, array("Accounts", "cmp"));
//     foreach ($expenses as $e) {
//       if($e->expense == "true"){
//         $expense_categories = $this->db->select('e.id as expense_id,ec.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_expense_categories ec')->join('sh_expenses e','e.expense_category_id = ec.id and e.deleted_at is null and ec.deleted_at is null','left')->where('expense_type_id', $e->expense_id)->group_by('ec.id')->having('(price <> total and fixed="Yes") or ec.id = '.$e->expense_category_id.' or (fixed = "No" and expense_id is null) or repeated = "Yes"')->get()->result();

//         $totals = $this->db->select('COALESCE(sum(amount),0) as total,price,fixed,repeated',false)->from('sh_expenses e')->join('sh_expense_categories ec', 'e.expense_category_id = ec.id')->where('expense_category_id', $e->expense_category_id)->where('e.deleted_at is null')->get()->row();
//         $total_collected = $totals->total;
//         $price = $totals->price;
//         $e->expense_categories = $expense_categories;
//         if($totals->repeated == "Yes"){
//           $e->maximum = $price;
//         }else{
//           $e->maximum = $price - $total_collected + $e->amount;
//         }
        

//         foreach($expense_categories as $ec){
//             if($ec->id == $e->expense_category_id){
//                 $ec->total = $ec->total - $e->amount;
//             }
//         }

//         $files = $e->old_files;
//         $files = explode(",", $files);
//         $new_files = array();
//         if($files[0] != ""){
//             foreach ($files as $f) {
//                 $temp = new stdClass();
//                 $temp->name = $f;
//                 $temp->new_name = $f;
//                 $new_files[] = $temp;
//             }
//         }

//         $e->old_files = $new_files;
//       }
//     }
//     $data["expenses"] = $expenses;
//     $data["expense_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_expenses e')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->where('e.deleted_at is null')->where('ec.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->get()->row()->total;
//     $data["currencies"] = $this->db->select('currency_id,symbol,name')->from('sh_school_currencies sc')->join('sh_currency c','c.id = sc.currency_id')->where('school_id', $school_id)->where('deleted_at is null')->get()->result();
//     $default_currency = "";
//     $currency = $this->db->select('currency_id')->from('sh_school_currencies')->where('school_id', $school_id)->where('is_default', 'yes')->where('deleted_at is null')->get()->row();
//     if($currency){
//         if($currency->currency_id != '0' && $currency->currency_id != NULL){
//             $default_currency = $currency->currency_id;
//         }
//     }
//     $data["default_currency"] = $default_currency;
//     $data["total_income"] = $data["income_total"] + $data["fee_collected"] + $data["total_deductions"];
//     $data["total_expense"] = $data["expense_total"] + $data["payroll_amount"];
//     $data["school_currency"] = $this->session->userdata("userdata")["currency_symbol"];
//     echo json_encode($data);

// }

function get_incomes_expenses(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $data['total_balance_cash'] = 0;
    $data['total_balance_cheque'] = 0;
    $data['total_balance_dd'] = 0;
    $academic_year_id = $request->academic_year_id;
    $incomes_result = $this->db->select('"true" as income,i.id,income_id,ic.fixed,ic.repeated,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by',false)->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')->join('sh_income_categories ic','i.income_category_id = ic.id')->join('sh_users u', 'u.id = i.collected_by','left')->join('sh_users u1', 'u1.id = i.created_by','left')->join('sh_currency c', 'c.id = i.currency', 'left')->where('i.deleted_at is null')->where('i.school_id', $school_id)->where('i.academic_year_id', $academic_year_id)->order_by('i.date','desc')->order_by('i.created_at','desc')->get()->result();
    $fee_logs = $this->db->select('"fee" as type, "Student" as income_type,"Fee Collection" as category_name,"System" as collected_by,"false" as income,date_format(f.created_at, "%d %b %Y") as date, date(f.created_at) as date_s,date_format(f.created_at, "%d/%m/%Y") as date_f, f.mode',false)->from('sh_fee_collection f')->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('f.school_id', $school_id)->where('f.deleted_at is NULL')->where('ft.deleted_at is null')->where('ft.academic_year_id',$academic_year_id)->group_by('date(created_at)')->get()->result();

    $amount = $this->db->select('date_format(created_at, "%d %b %Y") as date,COALESCE(sum(paid_amount), 0) as total',false)->from('sh_fee_collection')->where('school_id', $school_id)->where('deleted_at is NULL')->group_by('date(created_at)')->get()->result();
    
    foreach ($fee_logs as $key => $fe) {
      foreach ($amount as $key1 => $am) {
        if ($fe->date == $am->date) {
            $fe->amount = $am->total;
        }
      }
    }

    // total fee modes check added by sheraz

    $allCollectedFee = $this->db->select('f.paid_amount,f.mode')->from('sh_fee_collection f')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('ft.academic_year_id', $academic_year_id)->where('f.school_id', $school_id)->where('f.deleted_at is NULL')->get()->result();
    
    $data['total_fee_cash'] = 0;
    $data['total_fee_cheque'] = 0;
    $data['total_fee_dd'] = 0;
    foreach ($allCollectedFee as $key => $cf) {
      if ($cf->mode == 'cash') {
          $data['total_fee_cash'] += $cf->paid_amount; 
      }
      elseif($cf->mode == 'cheque'){
          $data['total_fee_cheque'] += $cf->paid_amount;
      }
      else {
          $data['total_fee_dd'] += $cf->paid_amount;
      }
    }

    // ends here

    $query = "select 'payroll' as type, 'false' as income,'Employee' as income_type,'Deductions' as category_name, 'System' as collected_by, date_format(created_at, '%d %b %Y') as date, date(created_at) as date_s,date_format(created_at, '%d/%m/%Y') as date_f, COALESCE(sum(deduction_amount), 0) as amount from (select deduction_amount,created_at from sh_payroll where school_id = $school_id and deleted_at is null group by user_id, date) as temp group by date(created_at) having amount <> 0";
    $deductions = $this->db->query($query)->result();
    foreach ($deductions as $d) {
      if($d->amount < 0){
        $d->amount *=-1;
      }
    }
    
    $incomes = array_merge($incomes_result,$fee_logs,$deductions);
    usort($incomes, array("Accounts", "cmp"));
    
    
    foreach ($incomes as $i) {
      if($i->income == "true"){
        $income_categories = $this->db->select('i.id as income_id,ic.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_income_categories ic')->join('sh_incomes i','i.income_category_id = ic.id and i.deleted_at is null and ic.deleted_at is null','left')->where('income_type_id', $i->income_id)->group_by('ic.id')->having('(price <> total and fixed="Yes") or ic.id = '.$i->income_category_id.' or (fixed = "No" and income_id is null) or repeated = "Yes"')->get()->result();
        $totals = $this->db->select('COALESCE(sum(amount),0) as total,price,fixed,repeated',false)->from('sh_incomes i')->join('sh_income_categories ic', 'i.income_category_id = ic.id')->where('income_category_id', $i->income_category_id)->where('i.deleted_at is null')->get()->row();
        $total_collected = $totals->total;
        $price = $totals->price;
        $i->income_categories = $income_categories;
        if($totals->repeated == "Yes"){
          $i->maximum = $price;
        }else{
          $i->maximum = $price - $total_collected + $i->amount;
        }

        foreach($income_categories as $ic){
            if($ic->id == $i->income_category_id){
                $ic->total = $ic->total - $i->amount;
            }
        }

        $files = $i->old_files;
        $files = explode(",", $files);
        $new_files = array();
        if($files[0] != ""){
            foreach ($files as $f) {
                $temp = new stdClass();
                $temp->name = $f;
                $temp->new_name = $f;
                $new_files[] = $temp;
            }
        }

        $i->old_files = $new_files;
      }
    }
    $data["fees"] = $this->db->select('u.name,ft.name as fee_type,uu.name as collected_by,paid_amount,feetype_amount,c.name as class_name,b.name as batch_name,receipt_no,ft.academic_year_id, ay.name as academic_year_name')->from('sh_fee_collection fc')->join('sh_users u','fc.student_id = u.id')->join('sh_users uu','fc.collector_id = uu.id')->join('sh_fee_types ft','fc.feetype_id = ft.id')->join('sh_classes c','fc.class_id = c.id')->join('sh_batches b','fc.batch_id = b.id')->join('sh_academic_years ay', 'ft.academic_year_id = ay.id')->where('fc.school_id', $school_id)->where('fc.deleted_at',NULL)->where('date(fc.created_at)',date("Y-m-d"))->get()->result();
    $data["fee_date"] = $data["payroll_date"] = date("d/m/Y");
    $data["fee_total"] = $this->db->select('COALESCE(sum(paid_amount), 0) as total',false)->from('sh_fee_collection')->where('school_id', $school_id)->where('deleted_at',NULL)->where('date(created_at)',date("Y-m-d"))->get()->row()->total;
    



    $data["payrolls"] = $this->db->select('u.name,st.name as salary,amount_paid,total_amount,uu.name as paid_by,receipt_no')->from('sh_payroll p')->join('sh_users u', 'p.user_id = u.id')->join('sh_users uu','p.paid_by = uu.id')->join('sh_salary_types st','p.salary_type_id = st.id')->where('date(p.created_at)', date("Y-m-d"))->where('p.deleted_at is null')->where('p.academic_year_id', $academic_year_id)->where('p.school_id',$school_id)->get()->result();

    $data["pay_total"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total',false)->from('sh_payroll')->where('school_id', $school_id)->where('date(created_at)',date("Y-m-d"))->where('deleted_at is null')->where('academic_year_id', $academic_year_id)->get()->row()->total;

    $this->db->select('COALESCE(sum(paid_amount), 0) as fee_collected', false);
    $this->db->from('sh_fee_collection f');
    $this->db->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner');
    $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
    $this->db->where('f.deleted_at is null');
    $this->db->where('ft.deleted_at is null');
    $this->db->where('ft.academic_year_id',$academic_year_id);
    $this->db->where('u.academic_year_id',$academic_year_id);
    $data['fee_collected'] = $this->db->get()->result()[0]->fee_collected;
    
    
    $data['total_deduction_cash'] = 0;
    $query = "select COALESCE(sum(deduction_amount), 0) as total from (select deduction_amount from sh_payroll where school_id = $school_id and deleted_at is null and academic_year_id = $academic_year_id group by user_id, date) as temp";
    $data["total_deductions"] = $this->db->query($query)->row()->total;
    $data['total_deduction_cash'] = $data["total_deductions"];

    if($data["total_deductions"] < 0){
      $data["total_deductions"] *= -1;
    }

    $data["payroll_amount"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total', false)->from('sh_payroll')->where('school_id', $school_id)->where('deleted_at is null')->where('academic_year_id', $academic_year_id)->get()->row()->total;


    // total income modes check added by sheraz

    $payroll_amount = $this->db->select('amount_paid,mode')->from('sh_payroll')->where('school_id', $school_id)->where('deleted_at is null')->where('academic_year_id', $academic_year_id)->get()->result();

    $data['total_payroll_cash'] = 0;
    $data['total_payroll_cheque'] = 0;
    $data['total_payroll_dd'] = 0;

    foreach ($payroll_amount as $key => $pa) {
        if ($pa->mode == 'cash') {
            $data['total_payroll_cash'] += $pa->amount_paid;
        }
        elseif ($pa->mode == 'cheque') {
            $data['total_payroll_cheque'] += $pa->amount_paid;
        }
        else {
            $data['total_payroll_dd'] += $pa->amount_paid;
        }
    }


    // ends here


    $data["incomes"] = $incomes;
    
    // total income modes check added by sheraz

    $data['total_income_cash'] = 0;
    $data['total_income_cheque'] = 0;
    $data['total_income_dd'] = 0;
    foreach ($data['incomes'] as $key => $inc) {
        if ($inc->mode == 'cash') {
            $data['total_income_cash'] += $inc->amount;  
        }
        elseif($inc->mode == 'cheque') {
            $data['total_income_cheque'] += $inc->amount;
        }
        else {
            $data['total_income_dd'] += $inc->amount;
        }
    }
    
    // ends here
    $data["income_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_incomes i')->join('sh_income_categories ic','i.income_category_id = ic.id')->where('i.school_id', $school_id)->where('i.deleted_at is null')->where('ic.deleted_at is null')->where('i.academic_year_id', $academic_year_id)->get()->row()->total;

    // total other income modes check added by sheraz

    $other_incomes = $this->db->select('i.amount,i.payment_mode')->from('sh_incomes i')->join('sh_income_categories ic','i.income_category_id = ic.id')->where('i.school_id', $school_id)->where('i.deleted_at is null')->where('ic.deleted_at is null')->where('i.academic_year_id', $academic_year_id)->get()->result();

    $data['total_other_income_cash'] = 0;
    $data['total_other_income_cheque'] = 0;
    $data['total_other_income_dd'] = 0;
    foreach ($other_incomes as $key => $oi) {
        if ($oi->payment_mode == 'cash') {
            $data['total_other_income_cash'] += $oi->amount;
        }
        elseif($oi->payment_mode == 'cheque'){
            $data['total_other_income_cheque'] += $oi->amount;
        } 
        else {
            $data['total_other_income_dd'] += $oi->amount;
        }
    }
    
    // ends here

    $expenses_result = $this->db->select('"true" as expense,e.id,expense_id,ec.fixed,ec.repeated,expense_category_id,paid_by as paid_by_id,et.name as expense_type, category_name, amount,u.name as paid_by,date_format(e.date, "%d %b %Y") as date,comment,date_format(e.date, "%d/%m/%Y") as date_f,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by,e.date as date_s',false)->from('sh_expenses e')->join('sh_expense_types et','e.expense_id = et.id','left')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->join('sh_users u', 'u.id = e.paid_by','left')->join('sh_users u1', 'u1.id = e.created_by','left')->join('sh_currency c','c.id = e.currency', 'left')->where('e.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->order_by('e.date','desc')->order_by('e.created_at','desc')->get()->result();
    $payroll_logs = $this->db->select('"false" as expense,"Employee" as expense_type,"Payroll" as category_name,"System" as paid_by,COALESCE(sum(amount_paid), 0) as amount,p.mode,date_format(p.created_at, "%d %b %Y") as date, date(p.created_at) as date_s,date_format(p.created_at, "%d/%m/%Y") as date_f',false)->from('sh_payroll p')->join('sh_salary_types st','p.salary_type_id = st.id')->where('p.school_id', $school_id)->where('p.deleted_at is null')->where('p.academic_year_id', $academic_year_id)->group_by('date(p.created_at)')->get()->result();
    $expenses = array_merge($expenses_result,$payroll_logs);
    usort($expenses, array("Accounts", "cmp"));
    
    foreach ($expenses as $e) {
      if($e->expense == "true"){
        $expense_categories = $this->db->select('e.id as expense_id,ec.id,category_name,fixed,repeated,price,COALESCE(sum(amount),0) as total',false)->from('sh_expense_categories ec')->join('sh_expenses e','e.expense_category_id = ec.id and e.deleted_at is null and ec.deleted_at is null','left')->where('expense_type_id', $e->expense_id)->group_by('ec.id')->having('(price <> total and fixed="Yes") or ec.id = '.$e->expense_category_id.' or (fixed = "No" and expense_id is null) or repeated = "Yes"')->get()->result();

        $totals = $this->db->select('COALESCE(sum(amount),0) as total,price,fixed,repeated',false)->from('sh_expenses e')->join('sh_expense_categories ec', 'e.expense_category_id = ec.id')->where('expense_category_id', $e->expense_category_id)->where('e.deleted_at is null')->where('e.academic_year_id', $academic_year_id)->get()->row();
        $total_collected = $totals->total;
        $price = $totals->price;
        $e->expense_categories = $expense_categories;
        if($totals->repeated == "Yes"){
          $e->maximum = $price;
        }else{
          $e->maximum = $price - $total_collected + $e->amount;
        }
        

        foreach($expense_categories as $ec){
            if($ec->id == $e->expense_category_id){
                $ec->total = $ec->total - $e->amount;
            }
        }

        $files = $e->old_files;
        $files = explode(",", $files);
        $new_files = array();
        if($files[0] != ""){
            foreach ($files as $f) {
                $temp = new stdClass();
                $temp->name = $f;
                $temp->new_name = $f;
                $new_files[] = $temp;
            }
        }

        $e->old_files = $new_files;
      }
    }
    $data["expenses"] = $expenses;


    $data["expense_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_expenses e')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->where('e.deleted_at is null')->where('ec.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->get()->row()->total;

    // total expense modes check added by sheraz

    $total_exp = $this->db->select('e.amount,e.payment_mode')->from('sh_expenses e')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->where('e.deleted_at is null')->where('ec.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->get()->result();


    $data['total_other_expense_cash'] = 0;
    $data['total_other_expense_cheque'] = 0;
    $data['total_other_expense_dd'] = 0;
    foreach ($total_exp as $key => $exp) {
      if ($exp->payment_mode == 'cash') {
            $data['total_other_expense_cash'] += $exp->amount;
        }
        elseif($exp->payment_mode == 'cheque'){
            $data['total_other_expense_cheque'] += $exp->amount;
        }
        else {
            $data['total_other_expense_dd'] += $exp->amount;
        }  
    }
    
    // ends here
    $data["currencies"] = $this->db->select('currency_id,symbol,name')->from('sh_school_currencies sc')->join('sh_currency c','c.id = sc.currency_id')->where('school_id', $school_id)->where('deleted_at is null')->get()->result();
    $default_currency = "";
    $currency = $this->db->select('currency_id')->from('sh_school_currencies')->where('school_id', $school_id)->where('is_default', 'yes')->where('deleted_at is null')->get()->row();
    if($currency){
        if($currency->currency_id != '0' && $currency->currency_id != NULL){
            $default_currency = $currency->currency_id;
        }
    }
    
    $data["default_currency"] = $default_currency;
    $data["total_income"] = $data["income_total"] + $data["fee_collected"] + $data["total_deductions"];

    
    $data["total_expense"] = $data["expense_total"] + $data["payroll_amount"];
    $data["school_currency"] = $this->session->userdata("userdata")["currency_symbol"];
    


    //deleted fee logs added in expense code added by sheraz 16/11/21
    
    $deleted_fee = $this->db->select('f.*,u.name')->from('sh_fee_collection f')->join('sh_users u','f.collector_id = u.id')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('ft.academic_year_id',$academic_year_id)->where('f.school_id',$school_id)->where('f.deleted_at is null')->where('f.refund_fee','1')->get()->result();
    
    $new_deleted_fee = new stdClass();
    
    $sort=array();
    
    
    foreach ($deleted_fee as $key => $f) {
      $f->expense_type = lang('lbl_fee');
      $f->category_name = lang('refund_fee');
      $f->amount = $f->paid_amount;
    //   $f->date = date('d F Y', strtotime($f->deleted_at));
    //   $f->date = date('d F Y', strtotime($f->updated_at));
      $f->date = date('d F Y', strtotime($f->created_at));
      $f->paid_by = $f->name;
      $f->symbol = $this->session->userdata("userdata")["currency_symbol"];
      $f->expense = 'fee';
      array_push($data['expenses'], $f); 
    }
    
    foreach ($data['expenses'] as $key => $part) {
     $sort[$key] = strtotime($part->date);
    }

    array_multisort($sort, SORT_DESC, $data['expenses']);

    $total_deleted_fee = $this->db->select('COALESCE(sum(f.paid_amount), 0) as total',false)->from('sh_fee_collection f')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('ft.academic_year_id',$academic_year_id)->where('f.school_id',$school_id)->where('f.deleted_at is null')->where('f.refund_fee','1')->get()->row()->total;

    // total expense modes check added by sheraz
    $data['total_all_exp_cash'] = $data['total_payroll_cash'] + $data['total_other_expense_cash'];
    $data['total_all_exp_cheque'] = $data['total_payroll_cheque'] + $data['total_other_expense_cheque'];
    $data['total_all_exp_dd'] = $data['total_payroll_dd'] + $data['total_other_expense_dd'];
    // ends here
    $data['total_expense'] = $data['total_expense'] + $total_deleted_fee;
    $data['total_balance'] = $data['total_income'] - $data['total_expense'];
    $data['total_balance_cash'] = $data['total_income_cash'] - $data['total_all_exp_cash'];
    $data['total_balance_cheque'] = $data['total_income_cheque'] - $data['total_all_exp_cheque'];
    $data['total_balance_dd'] = $data['total_income_dd'] - $data['total_all_exp_dd'];
    echo json_encode($data);

}



function fetchfeedetails(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $date = $request->date;

    $date_mysql = to_mysql_date($date);

    if($date_mysql == "" || $date_mysql == "error"){
      $date_mysql = date("Y-m-d");
      $date = date("d/m/Y");
    }

    $data["fees"] = $this->db->select('u.name,ft.name as fee_type,uu.name as collected_by,paid_amount,feetype_amount,c.name as class_name,b.name as batch_name,receipt_no,ft.academic_year_id, ay.name as academic_year_name')->from('sh_fee_collection fc')->join('sh_users u','fc.student_id = u.id')->join('sh_users uu','fc.collector_id = uu.id')->join('sh_fee_types ft','fc.feetype_id = ft.id')->join('sh_classes c','fc.class_id = c.id')->join('sh_batches b','fc.batch_id = b.id')->join('sh_academic_years ay', 'ft.academic_year_id = ay.id')->where('fc.school_id', $school_id)->where('fc.deleted_at',NULL)->where('date(fc.created_at)',$date_mysql)->get()->result();
    $data["fee_date"] = $date;
    $data["fee_total"] = $this->db->select('COALESCE(sum(paid_amount), 0) as total',false)->from('sh_fee_collection')->where('school_id', $school_id)->where('deleted_at',NULL)->where('date(created_at)',$date_mysql)->get()->row()->total;
    
    echo json_encode($data);
    
}

function fetchpayrolldetails(){
  $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $date = $request->date;

    $date_mysql = to_mysql_date($date);

    if($date_mysql == "" || $date_mysql == "error"){
      $date_mysql = date("Y-m-d");
      $date = date("d/m/Y");
    }

    $data["payrolls"] = $this->db->select('u.name,st.name as salary,amount_paid,total_amount,uu.name as paid_by,receipt_no')->from('sh_payroll p')->join('sh_users u', 'p.user_id = u.id')->join('sh_users uu','p.paid_by = uu.id')->join('sh_salary_types st','p.salary_type_id = st.id')->where('date(p.created_at)', $date_mysql)->where('p.deleted_at is null')->where('p.school_id',$school_id)->get()->result();
    $data["payroll_date"] = $date;
    $data["pay_total"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total',false)->from('sh_payroll')->where('school_id', $school_id)->where('date(created_at)',$date_mysql)->where('deleted_at is null')->get()->row()->total;
    echo json_encode($data);
}

function fetchdeductions(){
  $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $date = $request->date;

    $date_mysql = to_mysql_date($date);

    if($date_mysql == "" || $date_mysql == "error"){
      $date_mysql = date("Y-m-d");
      $date = date("d/m/Y");
    }

    $data["deductions"] = $this->db->select('u.name,st.name as salary,sum(amount_paid) as amount_paid,total_amount,uu.name as paid_by,receipt_no,deduction_amount as deductions')->from('sh_payroll p')->join('sh_users u', 'p.user_id = u.id')->join('sh_users uu','p.paid_by = uu.id')->join('sh_salary_types st','p.salary_type_id = st.id')->where('date(p.created_at)', $date_mysql)->where('p.deleted_at is null')->where('p.school_id',$school_id)->group_by('user_id,p.date')->having('deductions <> 0')->get()->result();
    foreach ($data["deductions"] as $d) {
      if($d->deductions < 0){
        $d->deductions *= -1;
      }
    }
    $query = "select COALESCE(sum(deduction_amount), 0) as total from (select deduction_amount from sh_payroll where school_id = $school_id and deleted_at is null and date(created_at) = '$date_mysql' group by user_id, date) as temp";
    $data["total_deductions"] = $this->db->query($query)->row()->total;
    if($data["total_deductions"] < 0){
      $data["total_deductions"] *= -1;
    }
    $data["deduction_date"] = $date;
    echo json_encode($data);
}

function newIncome(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $academic_year_id = $request->academic_year_id;
    $income_id = $request->income_id;
    $income_category_id = $request->income_category_id;
    $date = to_mysql_date($request->date);
    $collected_by = $request->collected_by;
    $amount = $request->amount;
    $amount1 = $request->maximum;
    $currency = $request->currency;
    $comment = $request->comment;
    $payment_mode = $request->mode;
    $files_1 = $request->files;

    $files = "";
    
    if($files_1){
        $files = array();
        foreach ($files_1 as $value) {
            $files[] = $value->new_name;
        }
        $files = array_unique($files);
        $files=implode(",", $files);
    }
    if ($request->fixed == 'Yes') {

        $data = array('income_id' => $income_id,
        'income_category_id' => $income_category_id,
        'date' => $date,
        'collected_by' => $this->session->userdata("userdata")["user_id"],
        'amount' => $amount1,
        'currency' => $currency,
        'comment' => $comment,
        'payment_mode' => $payment_mode,
        'files' => $files,
        'school_id' => $school_id,
        'academic_year_id' => $academic_year_id);

        $this->db->insert('sh_incomes', $data);

    } else {

    $data = array('income_id' => $income_id,
        'income_category_id' => $income_category_id,
        'date' => $date,
        'collected_by' => $this->session->userdata("userdata")["user_id"],
        'amount' => $amount,
        'currency' => $currency,
        'comment' => $comment,
        'payment_mode' => $payment_mode,
        'files' => $files,
        'school_id' => $school_id,
        'academic_year_id' => $academic_year_id);

        $this->db->insert('sh_incomes', $data);      
    } 

    $data = array();
    $data["message"] = "Income added successfully";
    echo json_encode($data);
}

function updateIncome(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = $request->id;
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $academic_year_id = $request->academic_year_id;
    $income_id = $request->income_id;
    $income_category_id = $request->income_category_id;
    $date = to_mysql_date($request->date_f);
    $collected_by = $request->collected_by_id;
    $amount = $request->amount;
    $amount1 = $request->maximum;
    $currency = $request->currency;
    $comment = $request->comment;
    $payment_mode = $request->mode;
    $files_1 = $request->old_files;
    $files_2 = $request->files;

    $files_1 = array_merge($files_1,$files_2);

    $files = "";
    
    if($files_1){
        $files = array();
        foreach ($files_1 as $value) {
            $files[] = $value->new_name;
        }
        $files = array_unique($files);
        $files=implode(",", $files);
    }

    if ($request->fixed == 'Yes') {

        $data = array('income_id' => $income_id,
        'income_category_id' => $income_category_id,
        'date' => $date,
        'collected_by' => $this->session->userdata("userdata")["user_id"],
        'amount' => $amount1,
        'currency' => $currency,
        'comment' => $comment,
        'payment_mode' => $payment_mode,
        'files' => $files,
        'school_id' => $school_id,
        'academic_year_id' => $academic_year_id);

        $this->db->where('id', $id)->update('sh_incomes', $data);

    } else {

        $data = array('income_id' => $income_id,
        'income_category_id' => $income_category_id,
        'date' => $date,
        'collected_by' => $collected_by,
        'amount' => $amount,
        'currency' => $currency,
        'comment' => $comment,
        'payment_mode' => $payment_mode,
        'files' => $files,
        'school_id' => $school_id,
        'academic_year_id' => $academic_year_id);

        $this->db->where('id', $id)->update('sh_incomes', $data);      
    }
    $data = array();
    $data["message"] = "Income updated successfully";
    echo json_encode($data);
}

function newExpense(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $academic_year_id = $request->academic_year_id;
    $expense_id = $request->expense_id;
    $expense_category_id = $request->expense_category_id;
    $date = to_mysql_date($request->date);
    $paid_by = $this->session->userdata("userdata")["user_id"];
    $amount = $request->amount;
    $currency = $request->currency;
    $comment = $request->comment;
    $payment_mode = $request->mode;
    $files_1 = $request->files;

    $files = "";
    
    if($files_1){
        $files = array();
        foreach ($files_1 as $value) {
            $files[] = $value->new_name;
        }
        $files = array_unique($files);
        $files=implode(",", $files);
    }

    $data = array('expense_id' => $expense_id,
        'expense_category_id' => $expense_category_id,
        'date' => $date,
        'paid_by' => $this->session->userdata("userdata")["user_id"],
        'amount' => $amount,
        'currency' => $currency,
        'comment' => $comment,
        'payment_mode' => $payment_mode,
        'files' => $files,
        'school_id' => $school_id,
        'academic_year_id' => $academic_year_id);
    $this->db->insert('sh_expenses', $data);
    $data = array();
    $data["message"] = "Expense added successfully";
    echo json_encode($data);
}

function updateExpense(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $academic_year_id = $request->academic_year_id;
    $id = $request->id;
    $expense_id = $request->expense_id;
    $expense_category_id = $request->expense_category_id;
    $date = to_mysql_date($request->date_f);
    $paid_by = $request->paid_by_id;
    $amount = $request->amount;
    $currency = $request->currency;
    $comment = $request->comment;
    $payment_mode = $request->mode;
    $files_1 = $request->old_files;
    $files_2 = $request->files;

    $files_1 = array_merge($files_1,$files_2);

    $files = "";
    
    if($files_1){
        $files = array();
        foreach ($files_1 as $value) {
            $files[] = $value->new_name;
        }
        $files = array_unique($files);
        $files=implode(",", $files);
    }

    $data = array('expense_id' => $expense_id,
        'expense_category_id' => $expense_category_id,
        'date' => $date,
        'paid_by' => $paid_by,
        'amount' => $amount,
        'currency' => $currency,
        'comment' => $comment,
        'payment_mode' => $payment_mode,
        'files' => $files,
        'academic_year_id' => $academic_year_id);
    $this->db->where('id', $id)->update('sh_expenses', $data);
    $data = array();
    $data["message"] = "Expense updated successfully";
    echo json_encode($data);
}

function deleteIncome(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = $request->id;
    $data = array("deleted_at" => date("Y-m-d H:i:s"));
    $this->db->where('id', $id)->update('sh_incomes', $data);
    $data = array();
    $data["message"] = "Income deleted successfully";
    echo json_encode($data);
}

function deleteExpense(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = $request->id;
    $data = array("deleted_at" => date("Y-m-d H:i:s"));
    $this->db->where('id', $id)->update('sh_expenses', $data);
    $data = array();
    $data["message"] = "Expense deleted successfully";
    echo json_encode($data);
}

function deleteDeposit(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = $request->id;
    $data = array("deleted_at" => date("Y-m-d H:i:s"));
    $this->db->where('id', $id)->update('sh_virtual_transactions', $data);
    $data = array();
    $data["message"] = "Deposit deleted successfully";
    echo json_encode($data);
}

function deleteWithdraw(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $id = $request->id;
    $data = array("deleted_at" => date("Y-m-d H:i:s"));
    $this->db->where('id', $id)->update('sh_virtual_transactions', $data);
    $data = array();
    $data["message"] = "Withdraw deleted successfully";
    echo json_encode($data);
}

function newDeposit(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $account_id = $request->account_id;
    $amount = $request->amount;
    $date = to_mysql_date($request->date);
    $deposit_by = $request->deposit_by;
    $comment = $request->comment;
    $collected_by = $request->collected_by;
    $mode = $request->mode;
    $currency = $request->currency;
    $transaction_type = 'deposit';
    $files_1 = $request->files;

    $files = "";
    
    if($files_1){
        $files = array();
        foreach ($files_1 as $value) {
            $files[] = $value->new_name;
        }
        $files = array_unique($files);
        $files=implode(",", $files);
    }

    $data = array('amount' => $amount,
        'deposit_by' => $deposit_by,
        'transaction_type' => $transaction_type,
        'collected_by' => $collected_by,
        'date' => $date,
        'comment' => $comment,
        'payment_mode' => $mode,
        'currency' => $currency,
        'attachment' => $files,
        'account_id' => $account_id,
        'school_id' => $school_id
    );
    $this->db->insert('sh_virtual_transactions', $data);

    $data1 = array();
    $data1["message"] = "Amount deposited successfully";
    echo json_encode($data1);
}

function newWithdraw(){
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $account_id = $request->account_id;
    $amount = $request->amount;
    $date = to_mysql_date($request->date);
    $withdraw_by = $request->withdraw_by;
    $comment = $request->comment;
    $paid_by = $request->paid_by;
    $mode = $request->mode;
    $currency = $request->currency;
    $transaction_type = 'withdraw';
    $files_1 = $request->files;

    $files = "";
    
    if($files_1){
        $files = array();
        foreach ($files_1 as $value) {
            $files[] = $value->new_name;
        }
        $files = array_unique($files);
        $files=implode(",", $files);
    }

    $data = array('amount' => $amount,
        'withdraw_by' => $withdraw_by,
        'transaction_type' => $transaction_type,
        'paid_by' => $paid_by,
        'date' => $date,
        'comment' => $comment,
        'payment_mode' => $mode,
        'currency' => $currency,
        'attachment' => $files,
        'account_id' => $account_id,
        'school_id' => $school_id
    );
    $this->db->insert('sh_virtual_transactions', $data);

    $data1 = array();
    $data1["message"] = "Amount withdrawn successfully";
    echo json_encode($data1);
}

public function getIncomesInExcel($academic_year_id){
  $school_id = $this->session->userdata("userdata")["sh_id"];
  $incomes_result = $this->db->select('"true" as income,i.id,income_id,ic.fixed,ic.repeated,income_category_id,collected_by as collected_by_id,it.name as income_type, category_name, amount,u.name as collected_by,date_format(i.date, "%d %b %Y") as date,comment,date_format(i.date, "%d/%m/%Y") as date_f,i.date as date_s,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by',false)->from('sh_incomes i')->join('sh_income_types it','i.income_id = it.id','left')->join('sh_income_categories ic','i.income_category_id = ic.id')->join('sh_users u', 'u.id = i.collected_by','left')->join('sh_users u1', 'u1.id = i.created_by','left')->join('sh_currency c', 'c.id = i.currency', 'left')->where('i.deleted_at is null')->where('i.school_id', $school_id)->where('i.academic_year_id', $academic_year_id)->order_by('i.date','desc')->order_by('i.created_at','desc')->get()->result();
//   $fee_logs = $this->db->select('"fee" as type, "Student" as income_type,"Fee Collection" as category_name,"System" as collected_by,"false" as income,COALESCE(sum(paid_amount), 0) as amount,date_format(f.created_at, "%d %b %Y") as date, date(f.created_at) as date_s,date_format(f.created_at, "%d/%m/%Y") as date_f',false)->from('sh_fee_collection f')->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('f.school_id', $school_id)->where('f.deleted_at is null')->where('ft.deleted_at is null')->where('ft.academic_year_id',$academic_year_id)->group_by('date(created_at)')->get()->result();
  $fee_logs = $this->db->select('"fee" as type, "Student" as income_type,"Fee Collection" as category_name,"System" as collected_by,"false" as income,COALESCE(sum(paid_amount), 0) as amount,date_format(f.created_at, "%d %b %Y") as date, date(f.created_at) as date_s,date_format(f.created_at, "%d/%m/%Y") as date_f',false)->from('sh_fee_collection f')->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('f.school_id', $school_id)->where('f.deleted_at',NULL)->where('u.academic_year_id',$academic_year_id)->where('ft.deleted_at is null')->where('ft.academic_year_id',$academic_year_id)->group_by('date(created_at)')->get()->result();

  $query = "select 'payroll' as type, 'false' as income,'Employee' as income_type,'Deductions' as category_name, 'System' as collected_by, date_format(created_at, '%d %b %Y') as date, date(created_at) as date_s,date_format(created_at, '%d/%m/%Y') as date_f, COALESCE(sum(deduction_amount), 0) as amount from (select deduction_amount,created_at from sh_payroll where school_id = $school_id and academic_year_id = $academic_year_id and deleted_at is null group by user_id, date) as temp group by date(created_at) having amount <> 0";
  $deductions = $this->db->query($query)->result();
  foreach ($deductions as $d) {
    if($d->amount < 0){
      $d->amount *=-1;
    }
  }
  $incomes = array_merge($incomes_result,$fee_logs,$deductions);
  usort($incomes, array("Accounts", "cmp"));

  $data["income_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_incomes i')->join('sh_income_categories ic','i.income_category_id = ic.id')->where('i.school_id', $school_id)->where('i.academic_year_id', $academic_year_id)->where('i.deleted_at is null')->where('ic.deleted_at is null')->get()->row()->total;

  $this->db->select('COALESCE(sum(paid_amount), 0) as fee_collected', false);
  $this->db->from('sh_fee_collection f');
  $this->db->join('sh_student_class_relation u','u.student_id = f.student_id', 'inner');
  $this->db->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner');
  $this->db->where('f.deleted_at is null');
  $this->db->where('ft.deleted_at is null');
  $this->db->where('ft.academic_year_id',$academic_year_id);
  $this->db->where('u.academic_year_id',$academic_year_id);
  $data['fee_collected'] = $this->db->get()->result()[0]->fee_collected;

  $query = "select COALESCE(sum(deduction_amount), 0) as total from (select deduction_amount from sh_payroll where school_id = $school_id and academic_year_id = $academic_year_id and deleted_at is null group by user_id, date) as temp";
  $data["total_deductions"] = $this->db->query($query)->row()->total;

  if($data["total_deductions"] < 0){
    $data["total_deductions"] *= -1;
  }

  $total_income = $data["income_total"] + $data["fee_collected"] + $data["total_deductions"];

  $style = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ) 
    ); 


    $object = new PHPExcel();

    $object->setActiveSheetIndex(0);
    $object->getDefaultStyle()->applyFromArray($style);

    $table_columns = array("Sr.", "Income Type", "Category", "Amount", "Date", "Collected By");

    $column = 0;

    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $object->getActiveSheet()->getStyleByColumnAndRow($column, 1)->getFont()->setBold(true);

      $column++;
    }

    $row = 2;

    foreach ($incomes as $i) {
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $row-1);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $i->income_type);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $i->category_name);
      $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $i->amount);
      $object->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $i->date_f);
      $object->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $i->collected_by);
      $row++;
    }
    $row++;

    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, "Total Income");
    $object->getActiveSheet()->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $total_income);

    foreach(range('A','F') as $columnID) {
      $object->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
    }

    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Income Logs".'.xls"');
    $object_writer->save('php://output');

}

// public function getExpensesInExcel($academic_year_id){
//   $school_id = $this->session->userdata("userdata")["sh_id"];
//   $expenses_result = $this->db->select('"true" as expense,e.id,expense_id,ec.fixed,ec.repeated,expense_category_id,paid_by as paid_by_id,et.name as expense_type, category_name, amount,u.name as paid_by,date_format(e.date, "%d %b %Y") as date,comment,date_format(e.date, "%d/%m/%Y") as date_f,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by,e.date as date_s',false)->from('sh_expenses e')->join('sh_expense_types et','e.expense_id = et.id','left')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->join('sh_users u', 'u.id = e.paid_by','left')->join('sh_users u1', 'u1.id = e.created_by','left')->join('sh_currency c','c.id = e.currency', 'left')->where('e.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->order_by('e.date','desc')->order_by('e.created_at','desc')->get()->result();
//     $payroll_logs = $this->db->select('"false" as expense,"Employee" as expense_type,"Payroll" as category_name,"System" as paid_by,COALESCE(sum(amount_paid), 0) as amount,date_format(p.created_at, "%d %b %Y") as date, date(p.created_at) as date_s,date_format(p.created_at, "%d/%m/%Y") as date_f',false)->from('sh_payroll p')->join('sh_salary_types st','p.salary_type_id = st.id')->where('p.school_id', $school_id)->where('p.academic_year_id', $academic_year_id)->where('p.deleted_at is null')->group_by('date(p.created_at)')->get()->result();
//     $expenses = array_merge($expenses_result,$payroll_logs);
//     usort($expenses, array("Accounts", "cmp"));

//     $data["expense_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_expenses e')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->where('e.deleted_at is null')->where('ec.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->get()->row()->total;

//     $data["payroll_amount"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total', false)->from('sh_payroll')->where('school_id', $school_id)->where('academic_year_id', $academic_year_id)->where('deleted_at is null')->get()->row()->total;

//     $total_expense = $data["expense_total"] + $data["payroll_amount"];

//     $style = array(
//       'alignment' => array(
//         'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
//         'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
//       ) 
//     ); 


//     $object = new PHPExcel();

//     $object->setActiveSheetIndex(0);
//     $object->getDefaultStyle()->applyFromArray($style);

//     $table_columns = array("Sr.", "Expense Type", "Category", "Amount", "Date", "Paid By");

//     $column = 0;

//     foreach($table_columns as $field)
//     {
//       $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
//       $object->getActiveSheet()->getStyleByColumnAndRow($column, 1)->getFont()->setBold(true);

//       $column++;
//     }

//     $row = 2;

//     foreach ($expenses as $e) {
//       $object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $row-1);
//       $object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $e->expense_type);
//       $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $e->category_name);
//       $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $e->amount);
//       $object->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $e->date_f);
//       $object->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $e->paid_by);
//       $row++;
//     }
//     $row++;

//     $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, "Total Expense");
//     $object->getActiveSheet()->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
//     $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $total_expense);

//     foreach(range('A','F') as $columnID) {
//       $object->getActiveSheet()->getColumnDimension($columnID)
//       ->setAutoSize(true);
//     }

//     $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
//     header('Content-Type: application/vnd.ms-excel');
//     header('Content-Disposition: attachment;filename="'."Expense Logs".'.xls"');
//     $object_writer->save('php://output');
// }


public function getExpensesInExcel($academic_year_id){
  $school_id = $this->session->userdata("userdata")["sh_id"];
  $expenses_result = $this->db->select('"true" as expense,e.id,expense_id,ec.fixed,ec.repeated,expense_category_id,paid_by as paid_by_id,et.name as expense_type, category_name, amount,u.name as paid_by,date_format(e.date, "%d %b %Y") as date,comment,date_format(e.date, "%d/%m/%Y") as date_f,payment_mode as mode,currency,concat(c.symbol," - ",c.name) as full_currency,files as old_files,symbol,u1.name as added_by,e.date as date_s',false)->from('sh_expenses e')->join('sh_expense_types et','e.expense_id = et.id','left')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->join('sh_users u', 'u.id = e.paid_by','left')->join('sh_users u1', 'u1.id = e.created_by','left')->join('sh_currency c','c.id = e.currency', 'left')->where('e.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->order_by('e.date','desc')->order_by('e.created_at','desc')->get()->result();
    $payroll_logs = $this->db->select('"false" as expense,"Employee" as expense_type,"Payroll" as category_name,"System" as paid_by,COALESCE(sum(amount_paid), 0) as amount,date_format(p.created_at, "%d %b %Y") as date, date(p.created_at) as date_s,date_format(p.created_at, "%d/%m/%Y") as date_f',false)->from('sh_payroll p')->join('sh_salary_types st','p.salary_type_id = st.id')->where('p.school_id', $school_id)->where('p.academic_year_id', $academic_year_id)->where('p.deleted_at is null')->group_by('date(p.created_at)')->get()->result();
    $expenses = array_merge($expenses_result,$payroll_logs);
    usort($expenses, array("Accounts", "cmp"));
    $data["expense_total"] = $this->db->select('COALESCE(sum(amount),0) as total',false)->from('sh_expenses e')->join('sh_expense_categories ec','e.expense_category_id = ec.id')->where('e.deleted_at is null')->where('ec.deleted_at is null')->where('e.school_id', $school_id)->where('e.academic_year_id', $academic_year_id)->get()->row()->total;
    $deleted_fee = $this->db->select('f.*,u.name')->from('sh_fee_collection f')->join('sh_users u','f.collector_id = u.id')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('ft.academic_year_id',$academic_year_id)->where('f.school_id',$school_id)->where('f.deleted_at is null')->where('f.refund_fee','1')->get()->result();
    $new_deleted_fee = new stdClass();
    foreach ($deleted_fee as $key => $f) {
      $f->expense_type = 'Fee';
      $f->category_name = 'Deleted Fee';
      $f->amount = $f->paid_amount;
    //   $f->date_f = date('d F Y', strtotime($f->deleted_at));
    //   $f->date_f = date('d F Y', strtotime($f->updated_at));
      $f->date_f = date('d F Y', strtotime($f->created_at));
      $f->paid_by = $f->name;
      $f->symbol = 'Rp';
      $f->expense = 'fee';
      array_push($expenses, $f);
    }
    foreach ($expenses as $key => $part) {
     $sort[$key] = strtotime($part->date_f);
    }
    array_multisort($sort, SORT_DESC, $expenses);
    $total_deleted_fee = $this->db->select('COALESCE(sum(f.paid_amount), 0) as total',false)->from('sh_fee_collection f')->join('sh_fee_types ft','ft.id = f.feetype_id', 'inner')->where('ft.academic_year_id',$academic_year_id)->where('f.school_id',$school_id)->where('f.deleted_at',NULL)->where('f.refund_fee','1')->get()->row()->total;
    $data['expense_total'] = $data['expense_total'] + $total_deleted_fee;
    $data["payroll_amount"] = $this->db->select('COALESCE(sum(amount_paid), 0) as total', false)->from('sh_payroll')->where('school_id', $school_id)->where('academic_year_id', $academic_year_id)->where('deleted_at is null')->get()->row()->total;
    $total_expense = $data["expense_total"] + $data["payroll_amount"];
    $style = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      )
    );
    $object = new PHPExcel();
    $object->setActiveSheetIndex(0);
    $object->getDefaultStyle()->applyFromArray($style);
    $table_columns = array("Sr.", "Expense Type", "Category", "Amount", "Date", "Paid By");
    $column = 0;
    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $object->getActiveSheet()->getStyleByColumnAndRow($column, 1)->getFont()->setBold(true);
      $column++;
    }
    $row = 2;
    foreach ($expenses as $e) {
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $row-1);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $e->expense_type);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $e->category_name);
      $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $e->amount);
      $object->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $e->date_f);
      $object->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $e->paid_by);
      $row++;
    }
    $row++;
    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, "Total Expense");
    $object->getActiveSheet()->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $total_expense);
    foreach(range('A','F') as $columnID) {
      $object->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
    }
    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Expense Logs".'.xls"');
    $object_writer->save('php://output');
}


function getFeesInExcel($d, $m, $y, $academic_year_id){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $date = $d."/".$m."/".$y;

    $date_mysql = to_mysql_date($date);

    if($date_mysql == "" || $date_mysql == "error"){
      $date_mysql = date("Y-m-d");
      $date = date("d/m/Y");
    }

    // $fees = $this->db->select('u.name,ft.name as fee_type,uu.name as collected_by,paid_amount,feetype_amount,c.name as class_name,b.name as batch_name,receipt_no')->from('sh_fee_collection fc')->join('sh_users u','fc.student_id = u.id')->join('sh_users uu','fc.collector_id = uu.id')->join('sh_fee_types ft','fc.feetype_id = ft.id')->join('sh_classes c','fc.class_id = c.id')->join('sh_batches b','fc.batch_id = b.id')->where('fc.school_id', $school_id)->where('date(fc.created_at)',$date_mysql)->where('fc.deleted_at is null')->where('ft.academic_year_id', $academic_year_id)->get()->result();
    $fees = $this->db->select('u.name,ft.name as fee_type,uu.name as collected_by,paid_amount,feetype_amount,c.name as class_name,b.name as batch_name,receipt_no')->from('sh_fee_collection fc')->join('sh_users u','fc.student_id = u.id')->join('sh_users uu','fc.collector_id = uu.id')->join('sh_fee_types ft','fc.feetype_id = ft.id')->join('sh_classes c','fc.class_id = c.id')->join('sh_batches b','fc.batch_id = b.id')->where('fc.school_id', $school_id)->where('fc.deleted_at',NULL)->where('date(fc.created_at)',$date_mysql)->where('ft.academic_year_id', $academic_year_id)->get()->result();
    $fee_total = $this->db->select('COALESCE(sum(paid_amount), 0) as total',false)->from('sh_fee_collection')->where('school_id', $school_id)->where('deleted_at',NULL)->where('date(created_at)',$date_mysql)->get()->row()->total;
    // $fee_total = $this->db->select('COALESCE(sum(paid_amount), 0) as total',false)->from('sh_fee_collection')->where('school_id', $school_id)->where('date(created_at)',$date_mysql)->where('deleted_at is null')->get()->row()->total;
    
    $style = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ) 
    ); 


    $object = new PHPExcel();

    $object->setActiveSheetIndex(0);
    $object->getDefaultStyle()->applyFromArray($style);

    $table_columns = array("Sr.", "Student Name", "Class", "Section", "Fee Type", "Total Amount", "Paid", "Collected By", "Receipt No.");

    $column = 0;

    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $object->getActiveSheet()->getStyleByColumnAndRow($column, 1)->getFont()->setBold(true);

      $column++;
    }

    $row = 2;

    foreach ($fees as $f) {
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $row-1);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $f->name);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $f->class_name);
      $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $f->batch_name);
      $object->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $f->fee_type);
      $object->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $f->feetype_amount);
      $object->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $f->paid_amount);
      $object->getActiveSheet()->setCellValueByColumnAndRow(7, $row, $f->collected_by);
      $object->getActiveSheet()->setCellValueByColumnAndRow(8, $row, $f->receipt_no);
      $row++;
    }

    $row++;

    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $row, "Total Fee Collected");
    $object->getActiveSheet()->getStyleByColumnAndRow(5, $row)->getFont()->setBold(true);
    $object->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $fee_total);

    foreach(range('A','I') as $columnID) {
      $object->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
    }

    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Fee Logs (".$date.').xls"');
    $object_writer->save('php://output');
}

public function getPayrollsInExcel($d, $m, $y, $academic_year_id){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $date = $d."/".$m."/".$y;

    $date_mysql = to_mysql_date($date);

    if($date_mysql == "" || $date_mysql == "error"){
      $date_mysql = date("Y-m-d");
      $date = date("d/m/Y");
    }

    $payrolls = $this->db->select('u.name,st.name as salary,amount_paid,total_amount,uu.name as paid_by,receipt_no')->from('sh_payroll p')->join('sh_users u', 'p.user_id = u.id')->join('sh_users uu','p.paid_by = uu.id')->join('sh_salary_types st','p.salary_type_id = st.id')->where('date(p.created_at)', $date_mysql)->where('p.deleted_at is null')->where('p.school_id',$school_id)->where('p.academic_year_id', $academic_year_id)->get()->result();
    $pay_total = $this->db->select('COALESCE(sum(amount_paid), 0) as total',false)->from('sh_payroll')->where('school_id', $school_id)->where('academic_year_id', $academic_year_id)->where('date(created_at)',$date_mysql)->where('deleted_at is null')->get()->row()->total;

    $style = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ) 
    ); 


    $object = new PHPExcel();

    $object->setActiveSheetIndex(0);
    $object->getDefaultStyle()->applyFromArray($style);

    $table_columns = array("Sr.", "Employee Name", "Salary", "Amount Paid", "Total Pay", "Paid By", "Receipt No.");

    $column = 0;

    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $object->getActiveSheet()->getStyleByColumnAndRow($column, 1)->getFont()->setBold(true);

      $column++;
    }

    $row = 2;

    foreach ($payrolls as $p) {
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $row-1);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $p->name);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $p->salary);
      $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $p->amount_paid);
      $object->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $p->total_amount);
      $object->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $p->paid_by);
      $object->getActiveSheet()->setCellValueByColumnAndRow(6, $row, $p->receipt_no);
      $row++;
    }

    $row++;

    $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, "Total Paid");
    $object->getActiveSheet()->getStyleByColumnAndRow(2, $row)->getFont()->setBold(true);
    $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $pay_total);

    foreach(range('A','G') as $columnID) {
      $object->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
    }

    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Payroll Logs (".$date.').xls"');
    $object_writer->save('php://output');
}

function getDeductionsInExcel($d, $m, $y){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $date = $d."/".$m."/".$y;

    $date_mysql = to_mysql_date($date);

    if($date_mysql == "" || $date_mysql == "error"){
      $date_mysql = date("Y-m-d");
      $date = date("d/m/Y");
    }

    $deductions = $this->db->select('u.name,st.name as salary,sum(amount_paid) as amount_paid,total_amount,uu.name as paid_by,receipt_no,deduction_amount as deductions')->from('sh_payroll p')->join('sh_users u', 'p.user_id = u.id')->join('sh_users uu','p.paid_by = uu.id')->join('sh_salary_types st','p.salary_type_id = st.id')->where('date(p.created_at)', $date_mysql)->where('p.deleted_at is null')->where('p.school_id',$school_id)->group_by('user_id,p.date')->having('deductions <> 0')->get()->result();
    foreach ($deductions as $d) {
      if($d->deductions < 0){
        $d->deductions *= -1;
      }
    }
    $query = "select COALESCE(sum(deduction_amount), 0) as total from (select deduction_amount from sh_payroll where school_id = $school_id and deleted_at is null and date(created_at) = '$date_mysql' group by user_id, date) as temp";
    $total_deductions = $this->db->query($query)->row()->total;
    if($total_deductions < 0){
      $total_deductions *= -1;
    }

    $style = array(
      'alignment' => array(
        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
      ) 
    ); 


    $object = new PHPExcel();

    $object->setActiveSheetIndex(0);
    $object->getDefaultStyle()->applyFromArray($style);

    $table_columns = array("Sr.", "Employee Name", "Salary", "Amount Paid", "Total Pay", "Deductions");

    $column = 0;

    foreach($table_columns as $field)
    {
      $object->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
      $object->getActiveSheet()->getStyleByColumnAndRow($column, 1)->getFont()->setBold(true);

      $column++;
    }

    $row = 2;

    foreach ($deductions as $d) {
      $object->getActiveSheet()->setCellValueByColumnAndRow(0, $row, $row-1);
      $object->getActiveSheet()->setCellValueByColumnAndRow(1, $row, $d->name);
      $object->getActiveSheet()->setCellValueByColumnAndRow(2, $row, $d->salary);
      $object->getActiveSheet()->setCellValueByColumnAndRow(3, $row, $d->amount_paid);
      $object->getActiveSheet()->setCellValueByColumnAndRow(4, $row, $d->total_amount);
      $object->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $d->deductions);
      $row++;
    }

    $row++;

    $object->getActiveSheet()->setCellValueByColumnAndRow(4, $row, "Total Deductions");
    $object->getActiveSheet()->getStyleByColumnAndRow(4, $row)->getFont()->setBold(true);
    $object->getActiveSheet()->setCellValueByColumnAndRow(5, $row, $total_deductions);

    foreach(range('A','F') as $columnID) {
      $object->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
    }

    $object_writer = PHPExcel_IOFactory::createWriter($object, 'Excel5');
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'."Deduction Logs (".$date.').xls"');
    $object_writer->save('php://output');
    
}

function getNewFeeDetails(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $data = $this->db->select('u.name,ft.name as fee_type,uu.name as collected_by,paid_amount,feetype_amount,c.name as class_name,b.name as batch_name,receipt_no,ft.academic_year_id, ay.name as academic_year_name')->from('sh_fee_collection fc')->join('sh_users u','fc.student_id = u.id')->join('sh_users uu','fc.collector_id = uu.id')->join('sh_fee_types ft','fc.feetype_id = ft.id')->join('sh_classes c','fc.class_id = c.id')->join('sh_batches b','fc.batch_id = b.id')->join('sh_academic_years ay', 'ft.academic_year_id = ay.id')->where('fc.school_id', $school_id)->where('fc.deleted_at',NULL)->where('fc.id',$request->id)->get()->result();
    echo json_encode($data);
}

public function deleteCollectedFeePermanently(){
    $school_id = $this->session->userdata("userdata")["sh_id"];
    $postdata = file_get_contents("php://input");
    $request = json_decode($postdata);
    $res = $this->db->where('id', $request->id)->delete('sh_fee_collection');

    if ($res) {
      $data = array('status'=>'success', 'message'=> "fee deleted successfully!");
    }

    echo json_encode($data);
}

}