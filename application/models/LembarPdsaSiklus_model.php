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
        $this->db->where('LEMBAR_PDSA_ID', $id);
        $this->db->delete('sikat_siklus');
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    public function get($id) {
        $this->db
            ->select('
                ID AS siklusId,
                LEMBAR_PDSA_ID AS lembarPdsaId,
                RENCANA AS rencana,
                BERHARAP AS berharap,
                TINDAKAN AS tindakan,
                DIAMATI AS diamati,
                PELAJARI AS pelajari,
                TINDAKAN_SELANJUTNYA AS tindakanSelanjutnya
            ')
            ->from('sikat_siklus')
            ->where('LEMBAR_PDSA_ID', $id); // Ensure the correct filtering column
        
        return $this->db->get()->result(); // Returns multiple rows as an array of objects
    }
    

}




