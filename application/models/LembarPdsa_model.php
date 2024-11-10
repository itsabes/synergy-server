<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LembarPdsa_Model extends CI_Model
{

    public function save($data){
        $this->db->insert('sikat_lembar_pdsa', $data);
        return $this->db->insert_id();
    }

    public function update($data, $id){
        $this->db->where('id', $id);
        $this->db->update('sikat_lembar_pdsa', $data);
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('sikat_lembar_pdsa');
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    public function get($id) {
        $this->db
        ->select('*')
        ->from('sikat_lembar_pdsa')
        ->where('id = '.$id);
        
        return $this->db->get()->row();
    }

}




