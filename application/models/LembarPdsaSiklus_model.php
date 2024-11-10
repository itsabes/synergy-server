<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LembarPdsaSiklus_Model extends CI_Model
{

    public function save($data){
        $this->db->insert('sikat_siklus', $data);
        return $this->db->insert_id();
    }

    public function update($data, $id){
        $this->db->where('id', $id);
        $this->db->update('sikat_siklus', $data);
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('sikat_siklus');
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    public function get($id) {
        $this->db
        ->select('*')
        ->from('sikat_siklus')
        ->where('id = '.$id);
        
        return $this->db->get()->row();
    }

}




