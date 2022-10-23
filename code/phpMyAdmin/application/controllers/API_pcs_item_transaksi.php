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
	public function itemtransaksi_get()
	{
        $result = $this->m_transaksi->getItemTransaksi();

        $data_json = array(
            "success" => true,
            "message" => "Data Ditemukan", 
            "data" => array(
                "item_transaksi" => $result
            )
        );
        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Memasukkan Data
    public function itemtransaksi_post()
	{
        $this->cekToken();
        //Validasi
        $validation_message = [];
        
        if($this->input->post("idtransaksi")==""){
            array_push($validation_message, "ID Transaksi tidak diperbolehkan kosong");
        }

        if($this->input->post("idtransaksi")!="" && !$this->m_transaksi->cekAdaTransaksi($this->input->post("idtransaksi"))){
            array_push($validation_message, "ID Transaksi tidak ditemukan");
        }

        if($this->input->post("idproduk")==""){
            array_push($validation_message, "ID Produk tidak diperbolehkan kosong");
        }

        if($this->input->post("idproduk")!="" && !$this->m_produk->cekAdaProduk($this->input->post("idproduk"))){
            array_push($validation_message, "ID Produk tidak ditemukan");
        }

        if($this->input->post("qty")==""){
            array_push($validation_message, "Qty tidak diperbolehkan kosong");
        }
        
        if($this->input->post("qty")!="" && !is_numeric($this->input->post("qty"))){
            array_push($validation_message, "Qty harus berisi angka");
        }

        if($this->input->post("harga_saat_transaksi")==""){
            array_push($validation_message, "Harga tidak diperbolehkan kosong");
        }
        
        if($this->input->post("harga_saat_transaksi")!="" && !is_numeric($this->input->post("harga_saat_transaksi"))){
            array_push($validation_message, "Harga harus berisi angka");
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
            "idtransaksi" => $this->input->post("idtransaksi"),
            "idproduk" => $this->input->post("idproduk"),
            "qty" => $this->input->post("qty"),
            "harga_saat_transaksi" => $this->input->post("harga_saat_transaksi"),
            "subtotal" => $this->input->post("qty") * $this->input->post("harga_saat_transaksi")
        );
        $result = $this->m_itemtransaksi->insertItemTransaksi($data);

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
    public function itemtransaksi_put()
	{
        $this->cekToken();
        //Validasi
        $validation_message = [];
        
        if($this->put("id")==""){
            array_push($validation_message, "ID tidak diperbolehkan kosong");
        }

        if($this->put("idtransaksi")==""){
            array_push($validation_message, "ID Transaksi tidak diperbolehkan kosong");
        }

        if($this->put("idtransaksi")!="" && !$this->m_transaksi->cekAdaTransaksi($this->put("idtransaksi"))){
            array_push($validation_message, "ID Transaksi tidak ditemukan");
        }

        if($this->put("idproduk")==""){
            array_push($validation_message, "ID Produk tidak diperbolehkan kosong");
        }

        if($this->put("idproduk")!="" && !$this->m_produk->cekAdaProduk($this->put("idproduk"))){
            array_push($validation_message, "ID Produk tidak ditemukan");
        }

        if($this->input("qty")==""){
            array_push($validation_message, "Qty tidak diperbolehkan kosong");
        }
        
        if($this->put("qty")!="" && !is_numeric($this->put("qty"))){
            array_push($validation_message, "Qty harus berisi angka");
        }

        if($this->put("harga_saat_transaksi")==""){
            array_push($validation_message, "Harga tidak diperbolehkan kosong");
        }
        
        if($this->put("harga_saat_transaksi")!="" && !is_numeric($this->put("harga_saat_transaksi"))){
            array_push($validation_message, "Harga harus berisi angka");
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
            "idtransaksi" => $this->put("idtransaksi"),
            "idproduk" => $this->put("idproduk"),
            "qty" => $this->put("qty"),
            "harga_saat_transaksi" => $this->put("harga_saat_transaksi"),
            "subtotal" => $this->put("qty") * $this->put("harga_saat_transaksi")
        );

        $id = $this->put("id");

        $result = $this->m_transaksi->updateitemTransaksi($data, $id);

        $data_json = array(
            "success" => true,
            "message" => "Data Diperbaharui", 
            "data" => array(
                "item_transaksi" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Menghapus Data
    public function itemtransaksi_delete() {
        $this->cekToken();
        $id = $this->delete("id");

        $result = $this->m_itemtransaksi->deleteItemTransaksi($id);
        
        //Validasi
        if(empty($result)){
            $data_json = array(
            "success" => false,
            "message" => "ID Tidak Ditemukan", 
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
                "item_transaksi" => $result
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



