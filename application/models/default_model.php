<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Default_model extends CI_Model {

    function __construct() {
        parent::__construct();
        if (!empty($this->session->userdata('userdata'))) {
            date_default_timezone_set($this->session->userdata('userdata')['time_zone']);
            $now = new DateTime();
            $mins = $now->getOffset() / 60;
            $sgn = ($mins < 0 ? -1 : 1);
            $mins = abs($mins);
            $hrs = floor($mins / 60);
            $mins -= $hrs * 60;
            $offset = sprintf('%+d:%02d', $hrs * $sgn, $mins);
            $this->db->query("SET time_zone = '$offset' ");

            $url = uri_string();
            $notif = $this->db->select('id')->from('sh_notifications')->where('url', $url)->get();
            if ($notif->num_rows() > 0) {
                $id = $notif->row()->id;
                $this->db->set('is_read', 1)->where('receiver_id', $this->session->userdata('userdata')['user_id'])->where('notification_id', $id)->update('sh_notification_details');
            }
        }
    }

}

?>