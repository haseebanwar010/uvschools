<?php
if (!defined('BASEPATH'))
  exit('No direct script access allowed');
class School_home extends CI_Controller {
  function __construct() {
    parent::__construct();
    checkLicense();
  }
  function school_check($url) {
      
    $data['school'] = $this->db->select('*')->from('sh_school')->where('url',$url)->get()->row();
    
    if($data['school'] && $url!='signup')
    {
        $school_id = $data['school']->id; 

        $academic_year = $this->admin_model->dbQuery("SELECT id FROM sh_academic_years WHERE school_id='$school_id' AND is_active='Y' AND deleted_at is null");
    
        $academic_year_id = $academic_year['0']->id;
    
        if(!empty($data['school']))
        {
    
          $data['images'] = $this->db->query("SELECT * FROM sh_lp_gallery_images WHERE school_id='$school_id' AND status='active' AND deleted_at is null")->result();
    
          $data['teachers'] = $this->db->query("SELECT * FROM sh_lp_teachers WHERE school_id='$school_id' AND status='active' AND deleted_at is null")->result();
    
          $data['classes'] = $this->db->query("SELECT * FROM sh_lp_classes WHERE school_id='$school_id' AND status='active' AND deleted_at is null")->result();
    
          $data['slider'] = $this->db->query("SELECT * FROM sh_lp_slider WHERE school_id='$school_id' AND status='active' AND deleted_at is null")->result();
    
          $data['classes_background'] = $this->db->query("SELECT * FROM sh_lp_background_images WHERE school_id='$school_id' AND status='active' AND background='classes' AND deleted_at is null")->result();
    
          $data['video_background'] = $this->db->query("SELECT * FROM sh_lp_background_images WHERE school_id='$school_id' AND status='active' AND background='video' AND deleted_at is null")->result();
    
          $data['stats_background'] = $this->db->query("SELECT * FROM sh_lp_background_images WHERE school_id='$school_id' AND status='active' AND background='stats' AND deleted_at is null")->result();
    
          $data['video'] = $this->db->query("SELECT * FROM sh_lp_video WHERE school_id='$school_id' AND status='active' AND deleted_at is null")->result();
    
          $data['facebook'] = $this->db->query("SELECT * FROM sh_lp_social_links WHERE school_id='$school_id' AND social_site='facebook' AND status='active' AND deleted_at is null")->result();
    
          $data['youtube'] = $this->db->query("SELECT * FROM sh_lp_social_links WHERE school_id='$school_id' AND social_site='youtube' AND status='active' AND deleted_at is null")->result();
    
          $data['linkedin'] = $this->db->query("SELECT * FROM sh_lp_social_links WHERE school_id='$school_id' AND social_site='linkedin' AND status='active' AND deleted_at is null")->result();
    
          $data['twitter'] = $this->db->query("SELECT * FROM sh_lp_social_links WHERE school_id='$school_id' AND social_site='twitter' AND status='active' AND deleted_at is null")->result();
    
          $data['events'] = $this->db->query("SELECT * FROM sh_events WHERE MONTH(start) = MONTH(CURRENT_DATE())
            AND YEAR(start) = YEAR(CURRENT_DATE()) AND school_id='$school_id' AND mode='public' AND deleted_at is null")->result();
    
          $data['news'] = $this->db->query("SELECT * FROM sh_lp_news WHERE school_id='$school_id' AND status='active' AND deleted_at is null")->result();
    
          $result = $this->db->query("SELECT * FROM sh_lp_stats WHERE school_id='$school_id' AND deleted_at is null")->result();
    //sheraz added new 05/01/2020
          $res = $this->db->query("SELECT * FROM sh_lp_slider WHERE school_id='$school_id' AND deleted_at is null")->result();
    
          if(isset($res[0])){
            $data['heading_font_size'] = $res[0]->heading_font_size;
            $data['heading_color'] = $res[0]->heading_color;
            $data['sub_heading_font_size'] = $res[0]->sub_heading_font_size;
            $data['sub_heading_color'] = $res[0]->sub_heading_color;
            $data['description_font_size'] = $res[0]->description_font_size;
            $data['description_color'] = $res[0]->description_color;
          } else {
            $data['heading_font_size'] = "";
            $data['heading_color'] = "";
            $data['sub_heading_font_size'] = "";
            $data['sub_heading_color'] = "";
            $data['description_font_size'] = "";
            $data['description_color'] = "";
          }
    
          if(isset($result[0])){
            $data['total_students'] = $result[0]->total_students;
            $data['total_classes'] = $result[0]->total_classes;
            $data['total_employees'] = $result[0]->total_employees;
            $data['total_bus'] = $result[0]->total_bus;
          }else{
            $data['total_students'] = "";
            $data['total_classes'] = "";
            $data['total_employees'] = "";
            $data['total_bus'] = "";
          }
          $this->load->view('landing_page/landing_page_school', $data);
        
      }
        else
        {
          redirect(site_url());
        }
    }
    else
    {
        redirect('/');
    }
    
  }
}