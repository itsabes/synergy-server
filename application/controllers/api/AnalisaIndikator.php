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
        $indikator = array();
        $indikator =  $this->analisaIndikator_model->getByQuery($unit);
        $this->set_response($indikator, REST_Controller::HTTP_OK);
    }

    public function getByQueryUnit_get() {
        $unit = $this->get('unit');
        $indikator = array();
        $indikator =  $this->analisaIndikatorUnit_model->getByQuery($unit);
        $this->set_response($indikator, REST_Controller::HTTP_OK);
    }

    public function index_post() {
        
        $dataPost = $this->post();
        $id= "";
        $dataUnit = $this->analisaIndikatorUnit_model->get($dataPost['idx'],$dataPost['periode'],$dataPost['unit']);
        if(!empty($dataUnit)){
            $dataUnit =  array(
                "update_date" => date("Y-m-d H:i:s")
            );

            $id = $this->analisaIndikatorUnit_model->update($dataUnit);
        }else{

            $dataUnit =  array(
                "id_profile_indikator" => $dataPost['idx'],
                "periode_analisa" => $dataPost['periode'],
                "unit" => $dataPost['unit'],
                "create_date" => date("Y-m-d H:i:s"),
                "update_date" => date("Y-m-d H:i:s")
            );

            $id = $this->analisaIndikatorUnit_model->save($dataUnit);
        }

        if($id !== FALSE) {
            
            $data =  array(
                    "analisa" => $dataPost['analisa'],
                    "rekomendasi" => $dataPost['rekomendasi'],
                    "id_profile_indikator" => $dataPost['idx'],
                    "create_date" => date("Y-m-d H:i:s")
            );
            $id = $this->analisaIndikator_model->save($data);
            $id = $this->analisaIndikator_model->save($data);
            if($id !== FALSE) {
                
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
        $dataPut = $this->put();
        $dataUnit = $this->analisaIndikatorUnit_model->get($dataPut['idx'],$dataPut['periode'],$dataPut['unit']);
        if(!empty($dataUnit)){
            $dataUnit =  array(
                "update_date" => date("Y-m-d H:i:s")
            );

            $this->analisaIndikatorUnit_model->update($dataUnit);
        }else{

            $dataUnit =  array(
                "id_profile_indikator" => $dataPut['idx'],
                "periode_analisa" => $dataPut['periode'],
                "unit" => $dataPut['unit'],
                "create_date" => date("Y-m-d H:i:s"),
                "update_date" => date("Y-m-d H:i:s")
            );

            $this->analisaIndikatorUnit_model->save($dataUnit);
        }

        if($id) {

            $data =  array(
                "analisa" => $dataPut['analisa'],
                "rekomendasi" => $dataPut['rekomendasi'],
                "update_date" => date("Y-m-d H:i:s")
            );

            $result = $this->analisaIndikator_model->update($data, $id);
            if($result) {
                $analisaIndikator = $this->analisaIndikator_model->get($id);
                $this->set_response($analisaIndikator, REST_Controller::HTTP_OK);
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


