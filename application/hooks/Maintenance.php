<?php

class Maintenance {

    function check() {
        $ci = & get_instance();
        if ($ci->router->class != 'adminpanel' && $ci->router->class != 'xcrud_ajax' && $ci->router->class != 'cronjobs') {
            $ci->load->database();
            $data = $ci->db->select('*')->from('sh_settings')->where('name', 'maintenance')->get()->row();
            $ips = explode(',', $data->allowed_ip);
            $ip = $_SERVER['REMOTE_ADDR'];
            $con = $ci->router->class;
            $controllers = explode(',',$data->disable_controllers);
            if ($data->mode == "ON") {
                if (!in_array($ip, $ips)) {
                    echo $ci->load->view('maintenance', '', TRUE);
                    die();
                }
            }else{
                if(in_array($con,$controllers)){
                    if (!in_array($ip, $ips)) {
                        echo $ci->load->view('maintenance', '', TRUE);
                        die();
                    }
                }
            }
        }
    }

}