<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnalisaIndikatorUnit_model extends CI_Model
{

    public function getByQuery($unit) {
        $this->db
        ->select('*')
        ->from('sikat_analisa_unit as an')
        ->order_by('an.create_date', 'DESC');

        if(isset($unit)) $this->db->where('an.unit =',$unit);
        return $this->db->get()->result_array();
    }

    public function save($data){
        $this->db->insert('sikat_analisa_unit', $data);
        return $this->db->insert_id();
    }

    public function get($id,$periode) {
        $this->db
        ->select('*')
        ->from('sikat_analisa_unit')
        ->where('id_profile_indikator = '.$id)
        ->where('periode_analisa = '.$periode);
        
        return $this->db->get()->row();
    }

    public function update($data, $id){
        $this->db
        ->where('id_profile_indikator = '.$id)
        ->where('periode_analisa = '.$periode);
        $this->db->update('sikat_analisa_unit', $data);
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('sikat_analisa_unit');
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }


}




