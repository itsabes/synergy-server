<?php

defined("BASEPATH") or exit("No direct script access allowed");
require_once APPPATH . "libraries/REST_Controller.php";
use Restserver\Libraries\REST_Controller;

class LembarPdsa extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("lembarPdsa_model");
        $this->load->model("lembarPdsaSiklus_Model");
    }

    public function index_get()
    {
        $id = $this->get("id");
        $lembarPdsa = [];
        if ($id != null) {
            $lembarPdsa = $this->lembarPdsa_model->get($id);
            $lembarPdsa->SIKLUS = $this->lembarPdsaSiklus_Model->get($id);
        } else {
            $lembarPdsa = $this->lembarPdsa_model->all();
        }
        $this->set_response($lembarPdsa, REST_Controller::HTTP_OK);
    }

    public function getByQuery_get()
    {
        $unit = $this->get("unit");
        $lembarPdsa = [];
        $lembarPdsa = $this->lembarPdsa_model->getByQuery();
        $this->set_response($lembarPdsa, REST_Controller::HTTP_OK);
    }

    public function saveSiklusData2($dataPost, $lembarPdsaId)
    {
        echo $siklusData = $this->input->post("siklus");
        //echo "nama file nya --> ".$_FILES['siklus']['name'][0]['files'][0];
        print_r($_FILES);

        exit();
    }

    public function saveSiklusData($dataPost, $lembarPdsaId)
    {

        $siklusDatax = $this->input->post("siklus");
        $indexConstant = 0;

        foreach ($siklusDatax as $index => $siklus) {
            // Periksa apakah ada file yang diupload
            if (!empty($_FILES["siklus"]["name"][$index]["files"][$indexConstant])) {
                $files = $_FILES["siklus"];
            
                // Ambil informasi file
                $fileTmpName = $files["tmp_name"][$index]["files"][$indexConstant];
                $fileName = $files["name"][$index]["files"][$indexConstant];
                // Atur file untuk di-upload
                $_FILES['file']['name'] = $fileName;
                $_FILES['file']['type'] = $files['type'][$index]['files'][$indexConstant];
                $_FILES['file']['tmp_name'] = $fileTmpName;
                $_FILES['file']['error'] = $files['error'][$index]['files'][$indexConstant];
                $_FILES['file']['size'] = $files['size'][$index]['files'][$indexConstant];
            
                // Konfigurasi untuk upload file
                $config['upload_path'] = './uploads/siklus/'; // Lokasi folder penyimpanan
                $config['allowed_types'] = 'jpg|jpeg|png|pdf|docx'; // Format file yang diizinkan
                $config['max_size'] = 2048; // Ukuran maksimum dalam KB (2MB)
                $config['file_name'] = uniqid() . '_' . $fileName; // Nama unik untuk file
            
                // Load library upload
                $this->load->library('upload', $config);
            
                // Proses upload
                if ($this->upload->do_upload('file')) {
                    // Berhasil diupload, ambil informasi file
                    $uploadData = $this->upload->data();
            
                    // Siapkan data untuk disimpan ke database
                    $siklusData = [
                        "LEMBAR_PDSA_ID" => $lembarPdsaId,
                        "RENCANA" => $siklus["rencana"],
                        "TANGGAL_MULAI" => $siklus["tanggalMulaiSiklus"],
                        "TANGGAL_SELESAI" => $siklus["tanggalSelesaiSiklus"],
                        "BERHARAP" => $siklus["berharap"],
                        "TINDAKAN" => $siklus["tindakan"],
                        "DIAMATI" => $siklus["diamati"],
                        "PELAJARI" => $siklus["pelajari"],
                        "TINDAKAN_SELANJUTNYA" => $siklus["tindakanSelanjutnya"],
                        "FILE_PATH" => '/uploads/siklus/' . $uploadData['file_name']
                    ];
            
                    // Simpan atau update data siklus
                    if (!empty($siklus["siklusId"])) {
                        $this->lembarPdsaSiklus_Model->update($siklusData, $siklus["siklusId"]);
                    } else {
                        $this->lembarPdsaSiklus_Model->save($siklusData);
                    }
                } else {
                    // Gagal upload, tampilkan pesan error
                    $error = $this->upload->display_errors();
                    log_message('error', 'Upload gagal: ' . $error);
                    echo $error;
                     
                }
            } else {
                // Jika tidak ada file, simpan data tanpa file
                $siklusData = [
                    "LEMBAR_PDSA_ID" => $lembarPdsaId,
                    "RENCANA" => $siklus["rencana"],
                    "TANGGAL_MULAI" => $siklus["tanggalMulaiSiklus"],
                    "TANGGAL_SELESAI" => $siklus["tanggalSelesaiSiklus"],
                    "BERHARAP" => $siklus["berharap"],
                    "TINDAKAN" => $siklus["tindakan"],
                    "DIAMATI" => $siklus["diamati"],
                    "PELAJARI" => $siklus["pelajari"],
                    "TINDAKAN_SELANJUTNYA" => $siklus["tindakanSelanjutnya"],
                ];
            
                // Simpan atau update data siklus tanpa file
                if (!empty($siklus["siklusId"])) {
                    $this->lembarPdsaSiklus_Model->update($siklusData, $siklus["siklusId"]);
                } else {
                    $this->lembarPdsaSiklus_Model->save($siklusData);
                }
            }
            
        }
    }

    public function composeData($dataPost, $isInsert)
    {
        $data = [
            "JUDUL_PROYEK" => $this->input->post("judulProyek"), // Mengakses data non-file
            "KETUA_TIM" => $this->input->post("ketuaTim"),
            "ANGGOTA_1" => $this->input->post("anggota1"),
            "ANGGOTA_2" => $this->input->post("anggota2"),
            "ANGGOTA_3" => $this->input->post("anggota3"),
            "JABATAN_1" => $this->input->post("jabatan1"),
            "JABATAN_2" => $this->input->post("jabatan2"),
            "JABATAN_3" => $this->input->post("jabatan3"),
            "BENEFIT" => $this->input->post("benefit"),
            "MASALAH" => $this->input->post("masalah"),
            "TUJUAN" => $this->input->post("tujuan"),
            "UKURAN" => $this->input->post("ukuran"),
            "ANGGARAN" => $this->input->post("anggaran"),
            "PERBAIKAN" => $this->input->post("perbaikan"),
            "PERIODE_WAKTU" => $this->input->post("periodeWaktu"),
            "TANGGAL_MULAI" => $this->input->post("tanggalMulai"),
            "TANGGAL_SELESAI" => $this->input->post("tanggalSelesai"),
        ];

        if ($isInsert) {
            $data["CREATE_DATE"] = date("Y-m-d H:i:s");
        } else {
            $data["UPDATE_DATE"] = date("Y-m-d H:i:s");
        }

        return $data;
    }

    public function index_post()
    {
        $lembarPdsaId = $this->input->post("id");
        $dataPost = $_POST;
        if (empty($lembarPdsaId)) {
            $data = $this->composeData($dataPost, true);
            $id = $this->lembarPdsa_model->save($data);
            $this->saveSiklusData($dataPost, $id);
            $lembarPdsaCreated = $this->lembarPdsa_model->get($id);
            // $settings =  $this->settings_model->get();
            // $data = json_decode(json_encode($indikatorMutu), true);
            // $data['to_email'] = $settings->notif_email_ikp;
            // $data['to_subject'] = 'Notifikasi Registrasi Indikator Mutu';
            // $data['template'] = 'indikatorMutu_create';
            // Util::curlAsync("https://rsudsawahbesar.jakarta.go.id/synergy-server-2024/email", $data);
            $this->set_response($lembarPdsaCreated, REST_Controller::HTTP_OK);
        } else {
            $data = $this->composeData($dataPost, false);
            $result = $this->lembarPdsa_model->update($data, $lembarPdsaId);
            if ($result) {
                $lembarPdsa = $this->lembarPdsa_model->get($lembarPdsaId);
                $this->saveSiklusData($dataPost, $lembarPdsaId);
                $this->set_response($lembarPdsa, REST_Controller::HTTP_OK);
            } else {
                $response = [
                    "status" => REST_Controller::HTTP_BAD_REQUEST,
                    "message" => "database error",
                ];
                $this->set_response(
                    $response,
                    REST_Controller::HTTP_BAD_REQUEST
                );
            }
        }
    }

    public function index_put()
    {
        $id = $this->input->post("id");
        $dataPut = $this->put();
        $data = $this->composeData($dataPut, true);
        if ($id) {
            $result = $this->lembarPdsa_model->update($data, $id);
            if ($result !== FALSE) {
                $lembarPdsa = $this->lembarPdsa_model->get($id);
                $this->saveSiklusData($dataPut, $id);
                $this->set_response($lembarPdsa, REST_Controller::HTTP_OK);
            } else {
                $response = [
                    "status" => REST_Controller::HTTP_BAD_REQUEST,
                    "message" => "database error",
                ];
                $this->set_response(
                    $response,
                    REST_Controller::HTTP_BAD_REQUEST
                );
            }
        } else {
            $response = [
                "status" => REST_Controller::HTTP_NOT_FOUND,
                "message" => 'param ID can\'t be null',
            ];
            $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function delete_get()
    {
        $id = $this->get("id");
        if ($id) {
            $result = $this->lembarPdsaSiklus_Model->delete($id);
            if ($result) {
                $this->lembarPdsa_model->delete($id);
                $this->set_response("deleted", REST_Controller::HTTP_OK);
            } else {
                $response = [
                    "status" => REST_Controller::HTTP_BAD_REQUEST,
                    "message" => "database error",
                ];
                $this->set_response(
                    $response,
                    REST_Controller::HTTP_BAD_REQUEST
                );
            }
        } else {
            $response = [
                "status" => REST_Controller::HTTP_NOT_FOUND,
                "message" => 'param ID can\'t be null',
            ];
            $this->set_response($response, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
