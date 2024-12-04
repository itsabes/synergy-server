<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . 'libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Dynamic extends REST_Controller {

    public function __construct() 
    {
        parent::__construct();
        $this->load->model('sikat_profile_indikator_model');
        $this->load->model('sikat_profile_type_model');
    }

    public function getHeaderData_get() {
        
        $process_type = $this->get('proc_type');
        $year = $this->get('year');
        $dynamicData = array();
        /*$where = array(
            "process_type" =>  $process_type,
            "tahun" =>  $year
        );*/
        $dynamicData=$this->sikat_profile_indikator_model->getByQuery($year,$process_type);
        $this->set_response($dynamicData, REST_Controller::HTTP_OK);
    }

    public function getHeaderDataFormA_get() {
        
        $process_type = $this->get('proc_type');
        $year = $this->get('year');
        $dynamicData = array();
        $where = array(
            "process_type" =>  $process_type,
            "tahun" =>  $year,
            "status_acc"   => '1'
        );
        $dynamicData=$this->sikat_profile_indikator_model->get_where($where);

        $combinedNumerator = [];
        $combinedDeNumerator = [];
        $iterator = 1;
        foreach ($dynamicData as $item) {

            if (!empty($item->NUMERATOR) && !empty($item->DENUMERATOR)) {
                $combinedNumerator[$item->ID] = [
                    'ID'        => $item->ID,
                    'JUDUL' => $item->NUMERATOR,
                    'ORDERS' =>$item->ORDERS
                ];

                $combinedDeNumerator[$item->ID] = [
                    'ID'        => $item->ID,
                    'JUDUL' => $item->DENUMERATOR,
                    'ORDERS' =>$item->ORDERS
                ];
            }

            $iterator++;
        }

        $finalCombined = array_merge($combinedNumerator, $combinedDeNumerator);
        // Mengurutkan $finalCombined berdasarkan 'LEVEL'
        usort($finalCombined, function ($a, $b) {
            return $a['ORDERS'] <=> $b['ORDERS']; // Menggunakan spaceship operator untuk pengurutan
        });

        $this->set_response($finalCombined, REST_Controller::HTTP_OK);
    }


    public function getByQuery_get() {
        $tahun = $this->get('tahun');
        $unit = $this->get('unit');
        $indikator = array();
        $indikator =  $this->sikat_profile_indikator_model->getByQuery($tahun,$unit);
        $this->set_response($indikator, REST_Controller::HTTP_OK);
    }

    public function getProcessType_get() {
        $type = array();
        $type =  $this->sikat_profile_type_model->all();
        $this->set_response($type, REST_Controller::HTTP_OK);
    }


}


