 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {


	public function index()
	{
        $this->load->model('m_admin');
        $data['admin'] = $this->m_admin->getAdmin();

		$this->load->view('v_admin',$data);
	}
}
