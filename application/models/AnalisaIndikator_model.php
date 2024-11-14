<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AnalisaIndikator_model extends CI_Model
{

    public function getByQuery($unit,$id) {
        $this->db
        ->select('an.*, tp.JUDUL_INDIKATOR,tp.NUMERATOR,tp.DENUMERATOR,tp.TARGET_PENCAPAIAN,tp.PERIODE_ANALISA', false)
        ->from('sikat_analisa_indikator as an')
        ->join('sikat_profile_indikator as tp', 'an.id_profile_indikator=tp.id', 'left')
        ->order_by('an.create_date', 'DESC');

        if(isset($unit)) $this->db->where('tp.process_type =',$unit);
        if(isset($id)) $this->db->where('an.id =',$id);
        return $this->db->get()->result_array();
    }

    public function save($data){
        $this->db->insert('sikat_analisa_indikator', $data);
        return $this->db->insert_id();
    }

    public function get($id) {
        $this->db
        ->select('*')
        ->from('sikat_analisa_indikator')
        ->where('id = '.$id);
        
        return $this->db->get()->row();
    }

    public function update($data, $id){
        $this->db->where('id', $id);
        $this->db->update('sikat_analisa_indikator', $data);
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('sikat_analisa_indikator');
        // Get the number of affected rows
        $affectedRows = $this->db->affected_rows();
        return $affectedRows;
    }


}




