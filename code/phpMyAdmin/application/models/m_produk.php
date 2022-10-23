 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_produk extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
	public function getProduk()
	{
        $this->db->select('produk.idproduk,produk.idadmin,admin.nama as adminnama, produk.nama, produk.stok, produk.harga');
        $this->db->from('produk');
        $this->db->join('admin', 'admin.idadmin = produk.idadmin');
		$query = $this -> db -> get('');
        return $query -> result_array();
	}

    /////////////////////////////////////////////////////////////////////

    public function insertProduk($data) {
        $this->db->insert('produk', $data);

        $insert_id = $this->db->insert_id();
        $result = $this->db->get_where('produk', array('idproduk' => $insert_id));

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function updateProduk($data, $id) {
        $this->db->where('idproduk', $id);
        $this->db->update('produk', $data);

        $result = $this->db->get_where('produk', array('idproduk' => $id));

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function deleteProduk($id) {
        $result = $this->db->get_where('produk', array('idproduk' => $id));

        $this->db->where('idproduk', $id);
        $this->db->delete('produk');

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function cekLoginProduk($data) {
        
        $this->db->where($data);
        $result = $this->db->get('admin');

        return $result->row_array();
    }
}
