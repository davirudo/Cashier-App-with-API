 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/Firebase/JWT/JWT.php';
use \Firebase\JWT\JWT;

class api_pcs extends REST_Controller {

    private $secret_key = "random";

    function __construct() {
        parent::__construct();
        $this->load->model('m_admin');
    }

    //Melihat Data
	public function admin_get()
	{
        $result = $this->m_admin->getAdmin();

        $data_json = array(
            "success" => true,
            "message" => "Data Ditemukan", 
            "data" => array(
                "admin" => $result
            )
        );
        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Memasukkan Data
    public function admin_post()
	{
        $this->cekToken();
        //Validasi
        $validation_message = [];
        
        if($this->input->post("email")==""){
            array_push($validation_message, "Email tidak diperbolehkan kosong");
        }

        if($this->input->post("email")!="" && !filter_var($this->input->post("email"), FILTER_VALIDATE_EMAIL)){
            array_push($validation_message, "Email tidak memenuhi kriteria");
        }

        if($this->input->post("password")==""){
            array_push($validation_message, "Password tidak diperbolehkan kosong");
        }

        if($this->input->post("nama")==""){
            array_push($validation_message, "Nama tidak diperbolehkan kosong");
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
        $this->load->model('m_admin');
        
        $data = array(
            "email" => $this->input->post("email"),
            "password" => md5($this->input->post("password")),
            "nama" => $this->input->post("nama")
        );
        $result = $this->m_admin->insertAdmin($data);

        $data_json = array(
            "success" => true,
            "message" => "Data Dimasukkan", 
            "data" => array(
                "admin" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Mengedit Data
    public function admin_put()
	{
        $this->cekToken();
        //Validasi
        $validation_message = [];
        
        if($this->put("email")==""){
            array_push($validation_message, "Email tidak diperbolehkan kosong");
        }

        if($this->put("email")!="" && !filter_var($this->put("email"), FILTER_VALIDATE_EMAIL)){
            array_push($validation_message, "Email tidak memenuhi kriteria");
        }

        if($this->put("password")==""){
            array_push($validation_message, "Password tidak diperbolehkan kosong");
        }

        if($this->put("nama")==""){
            array_push($validation_message, "Nama tidak diperbolehkan kosong");
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
        $this->load->model('m_admin');
        
        $data = array(
            "email" => $this->put("email"),
            "password" => md5($this->put("password")),
            "nama" => $this->put("nama")
        );

        $id = $this->put("id");

        $result = $this->m_admin->updateAdmin($data, $id);

        $data_json = array(
            "success" => true,
            "message" => "Data Diperbaharui", 
            "data" => array(
                "admin" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
	}

    //////////////////////////////////////////////////////////////////////

    //Menghapus Data
    public function admin_delete() {
        $this->cekToken();
        $id = $this->delete("id");

        $result = $this->m_admin->deleteAdmin($id);
        
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
                "admin" => $result
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
    }

    public function login_post() {
    $data = array(
        "email" => $this->input->post("email"),
        "password" => md5($this->input->post("password")) 
    );

    $result = $this->m_admin->cekLoginAdmin($data);

    if(empty($result)) {
        $data_json = array(
            "success" => false,
            "message" => "Email dan Password tidak valid", "error_code" => 1308, 
            "data" => null
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
        $this->output->_display();
        exit();
    }else {
        $date = new Datetime();

        $payload["idadmin"] = $result["idadmin"];
        $payload["email"] = $result["email"];
        $payload["iat"] = $date->getTimestamp();
        $payload["exp"] = $date->getTimestamp() + 3600 ;

        $data_json = array(
            "success" => true,
            "message" => "Autentikasi Berhasil!", 
            "data" => array(
                "admin" => $result,
                "token" => JWT::encode($payload,$this->secret_key)
            )
            
        );

        $this->response($data_json, REST_Controller::HTTP_OK);
    }
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



