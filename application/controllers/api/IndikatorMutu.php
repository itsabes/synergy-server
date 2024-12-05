<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class IndikatorMutu extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('indikatorMutu_model');
        $this->load->model('settings_model');
        $this->load->model('sikat_profile_type_model');
        $this->load->model('sikat_profile_indikator_model');
    }

    public function index_get() {
        $id = $this->get('id');
        $indikatorMutu = array();
        if($id != null) {
            $indikatorMutu =  $this->indikatorMutu_model->get($id);
        }else{
            $indikatorMutu = $this->indikatorMutu_model->all();
        }
        $this->set_response($indikatorMutu, REST_Controller::HTTP_OK);
    }

    public function reportHarianByQuery_get() {
        $noRawat = $this->get('noRawat');
        $noRekamMedis = $this->get('noRekamMedis');
        $namaPasien = $this->get('namaPasien');
        $namaDokter = $this->get('namaDokter');
        $tanggalDari = $this->get('tanggalDari');
        $tanggalSampai = $this->get('tanggalSampai');
        $indikatorMutu = array();
        $indikatorMutu =  $this->indikatorMutu_model->reportHarianByQuery($noRawat, $noRekamMedis, $namaPasien, $namaDokter, $tanggalDari, $tanggalSampai);
        $this->set_response($indikatorMutu, REST_Controller::HTTP_OK);
    }

    public function allPetugas_get() {
        $petugas = $this->indikatorMutu_model->allPetugas();
        $this->set_response($petugas, REST_Controller::HTTP_OK);
    }

    public function allPetugasByQuery_get() {
        $searchStr = $this->get('searchstr');
        $petugas = $this->indikatorMutu_model->allPetugasByQuery($searchStr);
        $this->set_response($petugas, REST_Controller::HTTP_OK);
    }

    public function getByQuery_get() {
        $isRanap = $this->get('isRanap');
        $noRawat = $this->get('noRawat');
        $noRekamMedis = $this->get('noRekamMedis');
        $namaPasien = $this->get('namaPasien');
        $namaDokter = $this->get('namaDokter');
        $tanggalDari = $this->get('tanggalDari');
        $tanggalSampai = $this->get('tanggalSampai');
        $indikatorMutu = array();
        $indikatorMutu =  $this->indikatorMutu_model->getByQuery($isRanap, $noRawat, $noRekamMedis, $namaPasien, $namaDokter,$tanggalDari,$tanggalSampai);
        $this->set_response($indikatorMutu, REST_Controller::HTTP_OK);
    }

    public function composeData($dataPost,$isInsert){
        $data = [
            'JUDUL_INDIKATOR' => $dataPost['judulIndikator'],
            'TAHUN' => $dataPost['tahun'],
            //'ISI_POPULASI' => '',
            'TARGET_PENCAPAIAN' => $dataPost['targetPencapaian'],
            'DASAR_PEMIKIRAN' => $dataPost['dasarPemikiran'],
            'IS_EFEKTIF' => $dataPost['isEfektif'],
            'IS_EFISIEN' => $dataPost['isEfisien'],
            'IS_TEPAT_WAKTU' => $dataPost['isTepatWaktu'],
            'IS_AMAN' => $dataPost['isAman'],
            'IS_ADIL' => $dataPost['isAdil'],
            'IS_BERPASIEN' => $dataPost['isBerPasien'],
            'IS_INTEGRASI' => $dataPost['isIntegrasi'],
            'ACC_DATE' => '',
            'TUJUAN' => $dataPost['tujuan'],
            'DEFINISI_PEMIKIRAN' => $dataPost['defPemikiran'],
            'TIPE_INDIKATOR' => $dataPost['tipeIndikator'],
            'UKURAN_INDIKATOR' => $dataPost['ukuranIndikator'],
            'NUMERATOR' => $dataPost['numerator'],
            'DENUMERATOR' => $dataPost['denumerator'],
            'KRITERIA' => $dataPost['kriteria'],
            'FORMULA' => $dataPost['formula'],
            'SUMBER_DATA' => $dataPost['sumberData'],
            'FREK_PENGUMPULAN' => $dataPost['frekPengumpulan'],
            'PERIODE_PELAPORAN' => $dataPost['periodePelaporan'],
            'PERIODE_ANALISA' => $dataPost['periodeAnalisa'],
            'METODE_PENGUMPULAN' => $dataPost['metodePengumpulan'],
            'POPULASI_SAMPEL' => $dataPost['populasiSampel'],
            'ISI_SAMPLE' => $dataPost['isiSampel'],
            'RENCANA_ANALISIS' => $dataPost['rencanaAnalisis'],
            'INSTRUMEN_PENGAMBILAN' => $dataPost['instrumenPengambilan'],
            'ISI_INSTRUMEN' => $dataPost['isiInstrumen'],
            'BESAR_SAMPEL' => $dataPost['besarSampel'],
            'PENANGGUNG_JAWAB' => $dataPost['penanggungJawab'],
            'isINM' => $dataPost['isINM'],
            'isIMPRs' => $dataPost['isIMPRs'],
            'isIMPUnit' => $dataPost['isIMPUnit'],
            'PROCESS_TYPE' => $dataPost['unit']
            //'DAILY_MONTHLY_SPECIAL' => '',

        ];   
        
        // Set 'CREATE_DATE' if $isInsert is true; otherwise, set 'UPDATE_DATE'
        if ($isInsert) {
            $data['CREATE_DATE'] = date("Y-m-d H:i:s");
            $data['STATUS_ACC'] = 0;
            $data['ORDERS'] = $this->getOrders($dataPost['unit'],$dataPost['tahun']);
        } else {
            $data['UPDATE_DATE'] = date("Y-m-d H:i:s");
        }

        return $data;
    }

    public function getLevel($unit,$tahun){
        //query
        $defaultLevel = 1;
        $level = $this->sikat_profile_indikator_model->getLevel($unit,$tahun);
        if (isset($level[0]->result) && $level[0]->result != null) {
            $level = $level[0]->result;
        }else{
            $level = $defaultLevel;
        }

        return $level;
    }

    public function getOrders($unit,$tahun){
        //query
        $defaultOrders = 1;
        $orders = $this->sikat_profile_indikator_model->getOrders($unit,$tahun);
        if (isset($orders[0]->result) && $orders[0]->result != null) {
            $orders = $orders[0]->result;
        }else{
            $orders = $defaultOrders;
        }

        return $orders;
    }

    public function getProcessType($unit){
        //query
        return $this->sikat_profile_type_model->get($unit);
    }



    public function index_post() {
        $dataPost = $this->post();
        $data = $this->composeData($dataPost,true);
        $id = $this->indikatorMutu_model->save($data);
        if($id !== FALSE) {
            
            $indikatorMutu = $this->indikatorMutu_model->get($id);
            // $settings =  $this->settings_model->get();
            // $data = json_decode(json_encode($indikatorMutu), true);
            // $data['to_email'] = $settings->notif_email_ikp;
            // $data['to_subject'] = 'Notifikasi Registrasi Indikator Mutu';
            // $data['template'] = 'indikatorMutu_create';
            // Util::curlAsync("https://rsudsawahbesar.jakarta.go.id/synergy-server-2024/email", $data);
            $this->set_response($indikatorMutu, REST_Controller::HTTP_OK);

        }else{

            $response = [
                'status' => REST_Controller::HTTP_NOT_FOUND,
                'message' => 'create Indikator Mutu failed',
            ];

            $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function index_put() {

        $dataPut = $this->composeData($this->put(),false);
        $id = $this->put()['id'];
        if(!empty($id)) {
            $result = $this->indikatorMutu_model->update($dataPut, $id);
            if($result !== FALSE) {
                $indikatorMutu = $this->indikatorMutu_model->get($id);
                $this->set_response($indikatorMutu, REST_Controller::HTTP_OK);
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
        if(!empty($id)) {
            $result = $this->indikatorMutu_model->delete($id);
            if($result) {
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

    public function updateStatusAcc_put() {

        /*
        $statusUpdate = "0";
        if($this->put()['statusAcc']=="0"){
            $statusUpdate = "1";
        }else if($this->put()['statusAcc']=="1"){
            $statusUpdate = "0";
        }
        */
        $id = $this->put()['id'];
        $indikatorMutu = $this->indikatorMutu_model->get($id);
        $processType = $indikatorMutu->PROCESS_TYPE;
        $tahun = $indikatorMutu->TAHUN;
        $level = 0;
        if($this->put()['statusAcc']=="1"){
            $level = $this->getLevel($processType,$tahun);
        }

        $dataPut = array(
            'STATUS_ACC' => $this->put()['statusAcc'],
            'UPDATE_DATE' => date("Y-m-d H:i:s"),
            'USER_ACC' => $this->put()['userAcc'],
            'LEVEL' => $level,
            'REVIEW_ULANG' => $this->put()['reviewUlang']
        );

        if(!empty($id)) {
            $result = $this->indikatorMutu_model->update($dataPut, $id);
            if($result) {
                $this->set_response($indikatorMutu, REST_Controller::HTTP_OK);
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


