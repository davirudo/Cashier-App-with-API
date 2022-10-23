 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/Firebase/JWT/JWT.php';
use \Firebase\JWT\JWT;

class api_pcs_transaksi extends REST_Controller {

    private $secret_key = "random";

    function __construct() {
        parent::__construct();
        $this->load->model('m_produk');
        $this->load->model('m_admin');
        $this->load->model('m_transaksi');
    }

    //Melihat Data
	public function transaksi_get()
	{
        $result = $this->m_transaksi->getTransaksi();

        $data_json = array(
            "success" => true,
            "message" => "Data Ditemukan", 
            "data" => array(
                "transaksi" => $result
            )
        );
        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    public function transaksi_bulanan_get()
	{
        $result = $this->m_transaksi->getTransaksiBulanan();

        $data_json = array(
            "success" => true,
            "message" => "Data Ditemukan", 
            "data" => array(
                "transaksi" => $result
            )
        );
        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Memasukkan Data
    public function transaksi_post()
	{
        $this->cekToken();
        //Validasi
        $validation_message = [];
        
        if($this->input->post("idadmin")==""){
            array_push($validation_message, "ID Admin tidak diperbolehkan kosong");
        }

        if($this->input->post("idadmin")!="" && !$this->m_admin->cekAdaAdmin($this->input->post("idadmin"))){
            array_push($validation_message, "ID Admin tidak ditemukan");
        }

        if($this->input->post("total")==""){
            array_push($validation_message, "Total tidak diperbolehkan kosong");
        }
        
        if($this->input->post("total")!="" && !is_numeric($this->input->post("total"))){
            array_push($validation_message, "Total harus berisi angka");
        }

        if(count($validation_message)>0){
            $data_json = array(
            "success" => false,
            "message" => "Data Tidak Valid", 
            "data" => $validation_message
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
        }

        

        //Jika Lolos Validasi
        
        $data = array(
            "idadmin" => $this->input->post("idadmin"),
            "total" => $this->input->post("total"),
            "tanggal" => date('Y-m-d H:i:s')
        );
        $result = $this->m_transaksi->insertTransaksi($data);

        $data_json = array(
            "success" => true,
            "message" => "Data Dimasukkan", 
            "data" => array(
                "transaksi" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Mengedit Data
    public function transaksi_put()
	{
        $this->cekToken();
        //Validasi
        $validation_message = [];
        
        if($this->put("id")==""){
            array_push($validation_message, "ID tidak diperbolehkan kosong");
        }

        if($this->put("idadmin")==""){
            array_push($validation_message, "ID Admin tidak diperbolehkan kosong");
        }

        if($this->put("idadmin")!="" && !$this->m_admin->cekAdaAdmin($this->put("idadmin"))){
            array_push($validation_message, "ID Admin tidak ditemukan");
        }

        if($this->put("total")==""){
            array_push($validation_message, "Total tidak diperbolehkan kosong");
        }

        if($this->put("total")!="" && !is_numeric($this->put("total"))){
            array_push($validation_message, "Total harus berisi angka");
        }


        if(count($validation_message)>0){
            $data_json = array(
            "success" => false,
            "message" => "Data Tidak Valid", 
            "data" => $validation_message
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
        }


        //Jika Lolos Validasi
        $data = array(
            "idadmin" => $this->put("idadmin"),
            "total" => $this->put("total"),
            "tanggal" => date('Y-m-d H:i:s')
        );

        $id = $this->put("id");

        $result = $this->m_transaksi->updateTransaksi($data, $id);

        $data_json = array(
            "success" => true,
            "message" => "Data Diperbaharui", 
            "data" => array(
                "transaksi" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Menghapus Data
    public function transaksi_delete() {
        $this->cekToken();
        $id = $this->delete("id");

        $result = $this->m_transaksi->deleteTransaksi($id);
        
        //Validasi
        if(empty($result)){
            $data_json = array(
            "success" => false,
            "message" => "Id Tidak Ditemukan", 
            "data" => null
        );

        //Jika Lolos Validasi
        $this->response($data_json, REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
        }

        $data_json = array(
            "success" => true,
            "message" => "Data Berhasil Dihapus", 
            "data" => array(
                "transaksi" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
    }

public function cekToken() {
    try{
        $token = $this-> input->get_request_header("Authorization");

    if(!empty($token)) {
        $token = explode(' ',$token)[1];
    }

    $token_decode = JWT::decode($token,$this->secret_key, array('HS256'));
    }catch(Exception $e) {
        $data_json = array(
            "success" => false,
            "message" => "Token tidak valid", 
            "error_code" => 1204, 
            "data" => null
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }
    
}

}



