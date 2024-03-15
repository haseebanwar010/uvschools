<?php

class Admin_model extends CI_Model {

    public function dbSelectRow($selection, $table, $where) {
        $sql = "Select " . $selection . " From sh_". $table . " Where ";
        if ($where) {
            $sql .= $where;
        }
        $result = $this->db->query($sql);
        return $result->row();
    }

    public function dbSelect($selection, $table, $where) {
        $sql = "Select " . $selection . " From sh_" . $table . " Where ";
        if ($where) {
            $sql .= $where;
        }
        $result = $this->db->query($sql);
        return $result->result();
    }
    
    public function dbQuery($sql) {
        $result = $this->db->query($sql);
        return $result->result();
    }

    public function dbInsert($table, $data) {
        $this->db->insert($table, $data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }
    
    // added by zafar
    public function filter_monitring($school_id,$class_id,$bth_id,$date,$sub_id) {

$q = $this->db->query("select * from sh_study_material
    where school_id = '$school_id' AND
    delete_status = 0 AND
     class_id = '$class_id' AND
     uploaded_at = '$date' AND
    (batch_id = '$bth_id' or batch_id LIKE '$bth_id,%' or batch_id LIKE '%,$bth_id,%' or batch_id LIKE '%,$bth_id') AND
    (subject_id = '$sub_id' or subject_id LIKE '$sub_id,%' or subject_id LIKE '%,$sub_id,%' or subject_id LIKE '%,$sub_id' )
     ");
$query = $q->result();
        return $query;
    }
    
    public function getTestRecord(){
        return $this->db->select('*')->from('settings')->get()->result();
    }
}
