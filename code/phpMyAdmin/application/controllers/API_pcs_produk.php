 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/Firebase/JWT/JWT.php';
use \Firebase\JWT\JWT;

class api_pcs_produk extends REST_Controller {

    private $secret_key = "random";

    function __construct() {
        parent::__construct();
        $this->load->model('m_produk');
        $this->load->model('m_admin');
    }

    //Melihat Data
	public function produk_get()
	{
        $result = $this->m_produk->getProduk();

        $data_json = array(
            "success" => true,
            "message" => "Data Ditemukan", 
            "data" => array(
                "produk" => $result
            )
        );
        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Memasukkan Data
    public function produk_post()
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

        if($this->input->post("nama")==""){
            array_push($validation_message, "Nama tidak diperbolehkan kosong");
        }

        if($this->input->post("harga")==""){
            array_push($validation_message, "Harga tidak diperbolehkan kosong");
        }
        
        if($this->input->post("harga")!="" && !is_numeric($this->input->post("harga"))){
            array_push($validation_message, "Harga harus berisi angka");
        }

        if($this->input->post("stok")==""){
            array_push($validation_message, "Stok tidak diperbolehkan kosong");
        }

        if($this->input->post("stok")!="" && !is_numeric($this->input->post("stok"))){
            array_push($validation_message, "Stok harus berisi angka");
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
            "nama" => $this->input->post("nama"),
            "harga" => $this->input->post("harga"),
            "stok" => $this->input->post("stok"),
        );
        $result = $this->m_produk->insertProduk($data);

        $data_json = array(
            "success" => true,
            "message" => "Data Dimasukkan", 
            "data" => array(
                "produk" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Mengedit Data
    public function produk_put()
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

        if($this->input->put("idadmin")!="" && !$this->m_admin->cekAdaAdmin($this->put("idadmin"))){
            array_push($validation_message, "ID Admin tidak ditemukan");
        }

        if($this->put("nama")==""){
            array_push($validation_message, "Nama tidak diperbolehkan kosong");
        }

        if($this->put("harga")==""){
            array_push($validation_message, "Harga tidak diperbolehkan kosong");
        }

        if($this->put("harga")!="" && !is_numeric($this->put("harga"))){
            array_push($validation_message, "Harga harus berisi angka");
        }

        if($this->put("stok")==""){
            array_push($validation_message, "Stok tidak diperbolehkan kosong");
        }

        if($this->put("stok")!="" && !is_numeric($this->put("stok"))){
            array_push($validation_message, "Stok harus berisi angka");
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
            "nama" => $this->put("nama"),
            "harga" => $this->put("harga"),
            "stok" => $this->put("stok"),
        );

        $id = $this->put("id");

        $result = $this->m_produk->updateProduk($data, $id);

        $data_json = array(
            "success" => true,
            "message" => "Data Diperbaharui", 
            "data" => array(
                "produk" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Menghapus Data
    public function produk_delete() {
        $this->cekToken();
        $id = $this->delete("id");

        $result = $this->m_produk->deleteProduk($id);
        
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
                "produk" => $result
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



