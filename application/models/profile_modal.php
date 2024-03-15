<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_modal extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function getUserProfileDetail($user_id) {
        $ci = $this->db;
        $ci->select('sh_users.*,a.country_name as textCountry,b.country_name as textNationality')
                ->select('sh_users.dob as dobn', false);
        $ci->join('sh_countries a', 'sh_users.country = a.id', 'left');
        $ci->join('sh_countries b', 'sh_users.nationality = b.id', 'left');
        $ci->from('users');
        $ci->where("sh_users.id", $user_id);
        $result = $ci->get();
        return $result->row();
    }

}
