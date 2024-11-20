<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sikat_Profile_Indikator_Model extends CI_Model
{

    public function get_where($where) {
        return $this->db->get_where('sikat_profile_indikator', $where)->result();
    }
    /*
    public function getByQuery($tahun,$unit) {
        $this->db
        ->select('pro.*,an.id as id_analisa', false)
        ->from('sikat_profile_indikator as pro')
        ->join('sikat_profile_type as tp', 'pro.process_type=tp.type', 'left')
        ->join('sikat_analisa_indikator as an', 'an.id_profile_indikator=pro.id', 'left')
        ->order_by('pro.create_date', 'DESC');
        if (!isset($tahun)) {
            $this->db->limit(250);
        }

        if(isset($tahun)) $this->db->where('pro.tahun =',$tahun);
        if(isset($unit)) $this->db->where('pro.process_type =',$unit);
        return $this->db->get()->result_array();
    }
    */

    public function getByQuery($tahun,$unit) {
        $this->db
        ->select('pro.*', false)
        ->from('sikat_profile_indikator as pro')
        ->join('sikat_profile_type as tp', 'pro.process_type=tp.type', 'left')
       // ->join('sikat_analisa_indikator as an', 'an.id_profile_indikator=pro.id', 'left')
        ->order_by('pro.create_date', 'DESC');
        if (!isset($tahun)) {
            $this->db->limit(250);
        }

        if(isset($tahun)) $this->db->where('pro.tahun =',$tahun);
        if(isset($unit)) $this->db->where('pro.process_type =',$unit);
        return $this->db->get()->result_array();
    }

    public function getLevel($unit,$tahun){
        return $this->db->query("SELECT max(level)+1 as result FROM sikat_profile_indikator WHERE tahun='".$tahun."' and PROCESS_TYPE='".$unit."'")->result();
    }

    public function getByQueryWithAnalisa($unit) {

        $this->db->select('an.id as analisa_id,an.analisa,an.rekomendasi,tp.*', false)
        ->from('sikat_profile_indikator as tp')
        ->join('sikat_analisa_indikator as an', 'an.id_profile_indikator = tp.id', 'left')
        ->order_by('an.create_date', 'DESC');  

        if(isset($unit)) $this->db->where('tp.process_type =',$unit);
        return $this->db->get()->result_array();
    }

}