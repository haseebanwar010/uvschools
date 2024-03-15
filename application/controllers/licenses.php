<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Licenses extends CI_Controller {

    function __construct() {
        parent::__construct();
        checkLicense();
        if (!$this->session->userdata("userdata")) {
            redirect(site_url("login"));
        }
    }

    public function index() {
        $data["myLicenses"] = $this->common_model->get_where("sh_license", "school_id", $this->session->userdata("userdata")["sh_id"])->result();
        $this->load->view('licenses/index',$data);
    }
}
