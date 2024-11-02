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
        $dynamicData = array();
        $where = array(
            "process_type" =>  $process_type
        );
        $dynamicData=$this->sikat_profile_indikator_model->get_where($where);
        $this->set_response($dynamicData, REST_Controller::HTTP_OK);
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


