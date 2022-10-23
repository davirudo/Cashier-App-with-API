 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends CI_Controller {


	public function index()
	{
        $this->load->model('m_produk');
        $data['produk'] = $this->m_produk->getProduk();

		$this->load->view('v_produk',$data);
	}
}
