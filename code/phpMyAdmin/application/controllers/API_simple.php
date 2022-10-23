 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class api_simple extends CI_Controller {


	public function index()
	{
        $this->load->model('m_admin');
        $data['admin'] = $this->m_admin->getAdmin();

		$data_json = array(
            "success" => true,
            "message" => "Data Ditemukan", 
            "data" => $data['admin']
        );

        echo json_encode($data_json);
	}
}
