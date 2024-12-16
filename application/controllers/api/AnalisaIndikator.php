<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class AnalisaIndikator extends REST_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('analisaIndikator_model');
        $this->load->model('analisaIndikatorUnit_model');
    }

    public function getByQuery_get() {
        $unit = $this->get('unit');
        $tahun = $this->get('tahun');
        $indikator = array();
        $indikator =  $this->analisaIndikator_model->getByQuery($unit,$tahun);
        $this->set_response($indikator, REST_Controller::HTTP_OK);
    }

    public function getByQueryUnit_get() {
        $unit = $this->get('unit');
        $tahun = $this->get('tahun');
        $indikator = array();
        $indikator =  $this->analisaIndikatorUnit_model->getByQuery($unit,$tahun);
        $this->set_response($indikator, REST_Controller::HTTP_OK);
    }

    public function index_post() {
        
        $dataPost = $this->post();
        $id= "";
        $dataUnit = $this->analisaIndikatorUnit_model->get($dataPost['periode'],$dataPost['unit'],$dataPost['tahun']);
        $where = array(
            "unit" => $dataPost['unit'],
            "id_profile_indikator" => $dataPost['idx'],
            "periode_analisa" => $dataPost['periode'],
            "tahun" => $dataPost['tahun'],
        );

        $result = $this->analisaIndikator_model->getWhere($where);
        if(!empty($result)){
            $response = [
                'status' => REST_Controller::HTTP_NOT_FOUND,
                'message' => 'Gagal membuat Analisa Indikator, sudah ada dengan periode yang sama.',
            ];

            $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
        }else{

            if(!empty($dataUnit)){

                $data =  array(
                    "update_date" => date("Y-m-d H:i:s")
                );
    
                $id = $this->analisaIndikatorUnit_model->update($data,$dataUnit->id);
            }else{
    
                $dataUnit =  array(
                    "periode_analisa" => $dataPost['periode'],
                    "unit" => $dataPost['unit'],
                    "tahun" => $dataPost['tahun'],
                    "create_date" => date("Y-m-d H:i:s"),
                    "update_date" => date("Y-m-d H:i:s")
                );
    
                $id = $this->analisaIndikatorUnit_model->save($dataUnit);
            }
    
            if($id !== FALSE) {
                
                $data =  array(
                        "analisa" => $dataPost['analisa'],
                        "unit" => $dataPost['unit'],
                        "tahun" => $dataPost['tahun'],
                        "rekomendasi" => $dataPost['rekomendasi'],
                        "periode_analisa" => $dataPost['periode'],
                        "id_profile_indikator" => $dataPost['idx'],
                        "create_date" => date("Y-m-d H:i:s")
                );
                    
                $id = $this->analisaIndikator_model->save($data);
                if($id !== FALSE) {
                    
                    $analisaIndikator = $this->analisaIndikator_model->get($id);
                    // $settings =  $this->settings_model->get();
                    // $data = json_decode(json_encode($indikatorMutu), true);
                    // $data['to_email'] = $settings->notif_email_ikp;
                    // $data['to_subject'] = 'Notifikasi Registrasi Indikator Mutu';
                    // $data['template'] = 'indikatorMutu_create';
                    // Util::curlAsync("https://rsudsawahbesar.jakarta.go.id/synergy-server-2024/email", $data);
                    $this->set_response($analisaIndikator, REST_Controller::HTTP_OK);
    
                }else{
    
                    $response = [
                        'status' => REST_Controller::HTTP_NOT_FOUND,
                        'message' => 'create Analisa Indikator failed',
                    ];
    
                    $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
                }
                
            }
        }
    }

    public function index_put() {

        $id = $this->put()['idx'];
        $idProfileIndikator = $this->put()['idProfileIndikator'];
        $idAnalisa = $this->put()['idAnalisa'];
        $dataPut = $this->put();
        $dataUnit =  array(
            "update_date" => date("Y-m-d H:i:s")
        );

        $this->analisaIndikatorUnit_model->update($dataUnit,$id);
        if(!empty($idAnalisa) && $idAnalisa!=null) {

            $data =  array(
                "analisa" => $dataPut['analisa'],
                "rekomendasi" => $dataPut['rekomendasi'],
                "tahun" => $dataPut['tahun'],
                "update_date" => date("Y-m-d H:i:s")
            );

            $result = $this->analisaIndikator_model->update($data, $idAnalisa);
            if($result !== FALSE) {
                $analisaIndikator = $this->analisaIndikator_model->get($idAnalisa);
                $this->set_response($analisaIndikator, REST_Controller::HTTP_OK);
            }else{
                $response = [
                    'status' => REST_Controller::HTTP_BAD_REQUEST,
                    'message' => 'database error',
                ];
                $this->set_response($response, REST_Controller::HTTP_BAD_REQUEST);
            }
            
        }else{
          
            $data =  array(
                "analisa" => $dataPut['analisa'],
                "rekomendasi" => $dataPut['rekomendasi'],
                "unit" => $dataPut['unit'],
                "tahun" => $dataPut['tahun'],
                "periode_analisa" => $dataPut['periode'],
                "id_profile_indikator" => $idProfileIndikator,
                "create_date" => date("Y-m-d H:i:s")
            );

            $where = array(
                "unit" => $dataPut['unit'],
                "tahun" => $dataPut['tahun'],
                "id_profile_indikator" => $idProfileIndikator,
                "periode_analisa" => $dataPut['periode'],
            );
    
            $result = $this->analisaIndikator_model->getWhere($where);
            if(empty($result)) {

                $id = $this->analisaIndikator_model->save($data);
                $analisaIndikator = $this->analisaIndikator_model->get($id);
                // $settings =  $this->settings_model->get();
                // $data = json_decode(json_encode($indikatorMutu), true);
                // $data['to_email'] = $settings->notif_email_ikp;
                // $data['to_subject'] = 'Notifikasi Registrasi Indikator Mutu';
                // $data['template'] = 'indikatorMutu_create';
                // Util::curlAsync("https://rsudsawahbesar.jakarta.go.id/synergy-server-2024/email", $data);
                $this->set_response($analisaIndikator, REST_Controller::HTTP_OK);

            }else{

                $response = [
                    'status' => REST_Controller::HTTP_NOT_FOUND,
                    'message' => 'Gagal membuat Analisa Indikator, sudah ada dengan periode yang sama.',
                ];
                
                $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function delete_get() {
        $id = $this->get('id');
        if($id) {
            $result = $this->analisaIndikator_model->delete($id);
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

}


