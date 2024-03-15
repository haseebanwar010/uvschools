<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
        check_user_permissions();
    }

    public function index() {
        $UserData = $this->session->userdata('userdata');
        $data['profile'] = $this->profile_modal->getUserProfileDetail($UserData['user_id']);
        $this->load->view('profile/profile', $data);
    }

    public function edit() {
        $UserData = $this->session->userdata('userdata');
        $data['profile'] = $this->profile_modal->getUserProfileDetail($UserData['user_id']);
        $data['countries'] = $this->common_model->countries();
        $this->load->view('profile/profile-edit', $data);
    }

    public function update() {
        $update_option = $this->input->post('info_type');
        $message = null;
        
        if ($update_option == 'personal') {
            //$dob = new DateTime($this->input->post("dob"));
            $data['name'] = $this->input->post('name');
            $data['job_title'] = $this->input->post('job_title');
            $data['gender'] = $this->input->post('gender');
            $data["dob"] = to_mysql_date($this->input->post("dob"));
            $data['qualification'] = $this->input->post('qaulification');
            $data['nationality'] = $this->input->post('nationality');
            $data['passport_number'] = $this->input->post('passport_number');
            $data['ic_number'] = $this->input->post('ic_number');
            $data['marital_status'] = $this->input->post('marital_status');
            $data['language'] = $this->input->post('language');
            $this->session->set_userdata('site_lang', $data['language']);
            $message = '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button>' . lang("profile_updated_success") . '</div>';
            $oldValues = $this->session->userdata("userdata");
            $oldValues["name"] = $data["name"];     
            $oldValues["language"] = $data["language"];     
            $this->session->set_userdata("userdata",$oldValues);
        } else if ($update_option == 'contact') {
            $data['mobile_phone'] = $this->input->post('mobile_phone');
            $data['office_phone'] = $this->input->post('office_phone');
            $data['fax'] = $this->input->post('fax');
            $message = '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button>' . lang("lbl_contact_update_message") . '</div>';
        } else if ($update_option == 'address') {
            $data['address'] = $this->input->post('address');
            $data['country'] = $this->input->post('country');
            $data['city'] = $this->input->post('city');
            $message = '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button>' . lang("lbl_address_update_message") . '</div>';
        }else if($update_option == 'epothers'){
            $data['font_size'] = $this->input->post("fontsize");
            $message = '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button>' . lang("lbl_other_profile_settings_update_message") . '</div>';
        }
        $UserData = $this->session->userdata('userdata');
        $this->common_model->editRecord('id', $UserData['user_id'], 'users', $data);
        $this->session->set_flashdata("ep_selected_tab",$update_option);
        $this->session->set_flashdata('success', $message);
        redirect($_SERVER['HTTP_REFERER']);
    }

    function uploadImage() {

        $UserData = $this->session->userdata('userdata');
        $images = $_FILES['profile_image'];
        if ($images["size"] > 1000000) {
            $this->session->set_flashdata('success-image', '<div class="alert alert-danger alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button>' . lang("profile_image_warning") . '</div>');
        } else {
            $name = $images["name"];
            $tmp_name = $images["tmp_name"];
            $org_name = $name;
            $type = $images["type"];
            $size = $images["size"];
            if (strlen($name)) {
                $fname = time() . '_' . basename($name);
                $fname = str_replace(" ", "_", $fname);
                $fname = str_replace("%", "_", $fname);
                $name_ext = end(explode(".", basename($name)));
                $name = str_replace('.' . $name_ext, '', basename($name));
                $uploaddir = "./uploads/user/";
                $uploaddir2 = "./uploads/";
                $uploadfile = $uploaddir . $fname;
                if (move_uploaded_file($tmp_name, $uploadfile)) {
                    $result["avatar"] = $fname;

                    $this->common_model->smart_resize_image($uploadfile, null, 128, 128, false, $uploadfile, false, false, 100, true);
                    $this->common_model->editRecord('id', $UserData['user_id'], 'users', $result);
                }
            }

            $this->session->set_flashdata('success-image', '<div class="alert alert-success alert-dismissable"><button class="close" aria-hidden="true" type="button" data-dismiss="alert">×</button>' . lang("profile_image_updated_success") . ' </div>');
        }
        redirect($_SERVER['HTTP_REFERER']);
    }

    /////////// BY UMAR 20-12-2017//////////////////
    public function changePassword() {

        $current_password = md5($this->input->post('current_password'));
        $password = $this->input->post('password');
        $confirm_password = $this->input->post('confirm_password');

        $UserData = $this->session->userdata('userdata');

        $db_password = $this->common_model->getPassword($UserData['user_id']);

        $response["success"] = true;

        if ($current_password != $db_password) {
            $response["success"] = false;
            $response["error"] = lang('current_pswrd_error');
        } elseif (strlen($password) < 8) {
            $response["success"] = false;
            $response["error"] = lang('length_error');
        } else if ($password != $confirm_password) {
            $response["success"] = false;
            $response["error"] = lang('match_error');
        } else {
            $this->common_model->changePassword($UserData['user_id'], md5($password));
        }

        echo json_encode($response);
    }

    function addNewBank() {
        $name = $this->input->post('name');
        $accountno = $this->input->post('accountno');
        $beneficiary = $this->input->post('beneficiary');
        $swift = $this->input->post('swift');
        $iban = $this->input->post('iban');
        $checked = $this->input->post('primary_bank');

        $user_id = $this->input->post('emp_id');
        $sh_id = $this->bank_model->getSchoolIdOfUser($user_id);

        if ($checked) {
            $primary_bank = "Y";
            $this->bank_model->deleteOtherPrimary($user_id);
        } else {
            $primary_bank = "N";
        }

        $response["success"] = true;
        if (strlen($name) == 0) {
            $response["success"] = false;
            $response["error"] = lang('bank_name_required');
        } elseif (strlen($accountno) == 0) {
            $response["success"] = false;
            $response["error"] = lang('account_required');
        } elseif (strlen($beneficiary) == 0) {
            $response["success"] = false;
            $response["error"] = lang('beneficiary_required');
        } else {
            $bankdata = array('bank_name' => $name,
                'account_number' => $accountno,
                'beneficiary_name' => $beneficiary,
                'swift_code' => $swift,
                'iban_code' => $iban,
                'user_id' => $user_id,
                'school_id' => $sh_id,
                'is_primary' => $primary_bank);
            $insert_id = $this->bank_model->insertBank($bankdata);
            $response["success_message"] = lang('bank_add_suc');
            $response["data"] = $this->bank_model->getLastInsertedBank($insert_id);
        }

        echo json_encode($response);
    }

    function deleteBank() {
        $id = $this->input->post('id');
        $response["primary"] = "N";
        if ($this->bank_model->isPrimary($id) == "Y") {
            $response["primary"] = "Y";
        }
        $response["success"] = true;
        if ($this->bank_model->deleteBank($id)) {
            $response["success_message"] = lang('bank_deleted_suc');
        } else {
            $response["success"] = false;
            $response["error"] = lang('bank_delete_err');
        }
        echo json_encode($response);
    }

    function getBankDetails() {
        $id = $this->input->post('id');
        $response["success"] = true;
        $response["data"] = $this->bank_model->getBankDetails($id);
        echo json_encode($response);
    }

    function updateBank() {
        $name = $this->input->post('name');
        $accountno = $this->input->post('accountno');
        $beneficiary = $this->input->post('beneficiary');
        $swift = $this->input->post('swift');
        $iban = $this->input->post('iban');
        $checked = $this->input->post('primary_bank');
        $id = $this->input->post('bank_edit_id');
        $user_id = $this->bank_model->getUserIdBank($id);
        if ($checked) {
            $primary_bank = "Y";
            $this->bank_model->deleteOtherPrimary($user_id);
        } else {
            $primary_bank = "N";
        }


        $response["success"] = true;

        if (strlen($name) == 0) {
            $response["success"] = false;
            $response["error"] = lang('bank_name_required');
        } elseif (strlen($accountno) == 0) {
            $response["success"] = false;
            $response["error"] = lang('account_required');
        } elseif (strlen($beneficiary) == 0) {
            $response["success"] = false;
            $response["error"] = lang('beneficiary_required');
        } else {
            $bankdata = array('bank_name' => $name,
                'account_number' => $accountno,
                'beneficiary_name' => $beneficiary,
                'swift_code' => $swift,
                'iban_code' => $iban,
                'is_primary' => $primary_bank);
            $insert_id = $this->bank_model->updateBank($bankdata, $id);
            $response["success_message"] = lang('bank_update_suc');
            $response["data"] = $this->bank_model->getBankDetails($id);
        }

        echo json_encode($response);
    }
    
    function changepicture(){
        $postdata = file_get_contents("php://input");
        $request = json_decode($postdata);
        
        $avatar = explode('uploads/user/', save_image($request->image))[1];
        $res = $this->common_model->update_where("sh_users",array("id"=>$request->user_id),array("avatar"=>$avatar));        
        if($res){
            echo json_encode(array("status"=>"success","message"=>lang('profile_image')));
        }
    }

}
