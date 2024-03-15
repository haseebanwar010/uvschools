<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee_model extends CI_Model {

    function __construct() {
        parent::__construct();
        
    }

     public function getAll($school_id,$request=NULL){
        $ci = $this->db;
        $ci->select('users.*,a.country_name as country, d.name as department_name, c.category as category_name');
        $ci->from('users')->join('sh_countries a','a.id = users.country','left')->join('sh_role_categories c','c.id = sh_users.role_category_id','left')->join('sh_departments d','d.id = sh_users.department_id','left');
        $ci->where('users.school_id', $school_id);
        $ci->where('users.role_id', 4);
        $ci->where('users.deleted_at', 0);
        (isset($request) && $request!="") ? $ci->where("role_category_id", decrypt($request['category'])) : '';
        (isset($request) && $request!="") ? $ci->where("department_id", decrypt($request['department'])) : '';
        $query =  $ci->get();
        return $query->result();
        
    }
    
    public function getCategories($school_id){
        $ci = $this->db;
        $ci->select('*');
        $ci->from('role_categories');
        $ci->where('school_id', $school_id);
        $ci->where('role_id', 2);
        $ci->where('deleted_at', 0);
        $query =  $ci->get();
        return $query->result();
    }
    public function getDepartments($school_id){
        $ci = $this->db;
        $ci->select('*');
        $ci->from('departments');
        $ci->where('school_id', $school_id);
        $ci->where('deleted_at', 0);
        $query =  $ci->get();
        return $query->result();
    }

   public function getEmployeByID($id){
        $ci = $this->db;
        $ci->select('users.*,a.country_name as country,b.country_name as nationality')
        ->select('sh_users.dob as dobn' ,false);
        $ci->from('users');
        $ci->join('sh_countries a','a.id = users.country','left');
        $ci->join('sh_countries b','b.id = users.nationality','left');
        $ci->where('users.id', $id);
        $ci->where('deleted_at', 0);
        $query =  $ci->get();
        return $query->row();
    }

    public function getEmployeByIDEdit($id){
        $ci = $this->db;
        $ci->select('users.*')
        ->select('sh_users.dob as dobn' ,false);
        $ci->from('users');
        $ci->where('users.id', $id);
        $ci->where('deleted_at', 0);
        $query =  $ci->get();
        return $query->row();
    }

}
