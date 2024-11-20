<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class LembarPdsa extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('lembarPdsa_model');
        $this->load->model('lembarPdsaSiklus_Model');
    }

    public function index_get() {
        $id = $this->get('id');
        $lembarPdsa = array();
        if($id != null) {
            $lembarPdsa =  $this->lembarPdsa_model->get($id);
            $lembarPdsa->SIKLUS = $this->lembarPdsaSiklus_Model->get($id);

        }else{
            $lembarPdsa = $this->lembarPdsa_model->all();
        }
        $this->set_response($lembarPdsa, REST_Controller::HTTP_OK);
    }

    public function getByQuery_get() {
        $unit = $this->get('unit');
        $lembarPdsa = array();
        $lembarPdsa =  $this->lembarPdsa_model->getByQuery();
        $this->set_response($lembarPdsa, REST_Controller::HTTP_OK);
    }

    public function saveSiklusData($dataPost,$lembarPdsaId){

        foreach ($dataPost['siklus'] as $siklus) {
            $siklusData = [
                'LEMBAR_PDSA_ID' => $lembarPdsaId,
                'RENCANA' => $siklus['rencana'],
                'TANGGAL_MULAI' => $siklus['tanggalMulaiSiklus'],
                'TANGGAL_SELESAI' => $siklus['tanggalSelesaiSiklus'],
                'BERHARAP' => $siklus['berharap'],
                'TINDAKAN' => $siklus['tindakan'],
                'DIAMATI' => $siklus['diamati'],
                'PELAJARI' => $siklus['pelajari'],
                'TINDAKAN_SELANJUTNYA' => $siklus['tindakanSelanjutnya']
            ];

            if (!empty($siklus['siklusId'])) {
                // Update existing record
                $this->lembarPdsaSiklus_Model->update($siklusData, $siklus['siklusId']);
            } else {
                // Insert new record
                $this->lembarPdsaSiklus_Model->save($siklusData);
            }
        }

    }

    public function composeData($dataPost,$isInsert){

        $data = [
            'JUDUL_PROYEK' => $dataPost['judulProyek'],
            'KETUA_TIM' => $dataPost['ketuaTim'],
            'ANGGOTA_1' => $dataPost['anggota1'],
            'ANGGOTA_2' => $dataPost['anggota2'],
            'ANGGOTA_3' => $dataPost['anggota3'],
            'JABATAN_1' => $dataPost['jabatan1'],
            'JABATAN_2' => $dataPost['jabatan2'],
            'JABATAN_3' => $dataPost['jabatan3'],
            'BENEFIT' => $dataPost['benefit'],
            'MASALAH' => $dataPost['masalah'],
            'TUJUAN' => $dataPost['tujuan'],
            'UKURAN' => $dataPost['ukuran'],
            'ANGGARAN' => $dataPost['anggaran'],
            'PERBAIKAN' => $dataPost['perbaikan'],
            'PERIODE_WAKTU' => $dataPost['periodeWaktu'],
            'TANGGAL_MULAI' => $dataPost['tanggalMulai'],
            'TANGGAL_SELESAI' => $dataPost['tanggalSelesai'],
        ];   
    
        if ($isInsert) {
            $data['CREATE_DATE'] = date("Y-m-d H:i:s");
        } else {
            $data['UPDATE_DATE'] = date("Y-m-d H:i:s");
        }

        return $data;
    }
    

    public function index_post() {
        $dataPost = $this->post();
        $data = $this->composeData($dataPost,true);
        $id = $this->lembarPdsa_model->save($data);
        if($id !== FALSE) {
            
            $this->saveSiklusData($dataPost,$id);
            $lembarPdsaCreated = $this->lembarPdsa_model->get($id);
            // $settings =  $this->settings_model->get();
            // $data = json_decode(json_encode($indikatorMutu), true);
            // $data['to_email'] = $settings->notif_email_ikp;
            // $data['to_subject'] = 'Notifikasi Registrasi Indikator Mutu';
            // $data['template'] = 'indikatorMutu_create';
            // Util::curlAsync("https://rsudsawahbesar.jakarta.go.id/synergy-server-2024/email", $data);
            $this->set_response($lembarPdsaCreated, REST_Controller::HTTP_OK);

        }else{

            $response = [
                'status' => REST_Controller::HTTP_NOT_FOUND,
                'message' => 'create Lembar Pdsa failed',
            ];

            $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put() {
        $id = $this->put()['id'];
        $dataPut = $this->put();
        $data = $this->composeData($dataPut,true);
        if($id) {
            $result = $this->lembarPdsa_model->update($data, $id);
            if($result) {
                $lembarPdsa = $this->lembarPdsa_model->get($id);
                $this->saveSiklusData($dataPut,$id);
                $this->set_response($lembarPdsa, REST_Controller::HTTP_OK);
            }else{
                $response = [
                    'status' => REST_Controller::HTTP_BAD_REQUEST,
                    'message' => 'database error',
                ];
                $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
            }
            
        }else{
            $response = [
                'status' => REST_Controller::HTTP_NOT_FOUND,
                'message' => 'param ID can\'t be null',
            ];
            $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function delete_get() {
        $id = $this->get('id');
        if($id) {
            $result = $this->lembarPdsaSiklus_Model->delete($id);
            if($result) {
                $this->lembarPdsa_model->delete($id);
                $this->set_response('deleted', REST_Controller::HTTP_OK);
            }else{
                $response = [
                    'status' => REST_Controller::HTTP_BAD_REQUEST,
                    'message' => 'database error',
                ];
                $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
            }
        }else{
            $response = [
                'status' => REST_Controller::HTTP_NOT_FOUND,
                'message' => 'param ID can\'t be null',
            ];
            $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
        }
    }

}


