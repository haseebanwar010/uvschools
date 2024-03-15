<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index() {
        $this->load->view("header");
        $this->load->view("left-sidebar");
        $this->load->view("right-sidebar");
    }

}
