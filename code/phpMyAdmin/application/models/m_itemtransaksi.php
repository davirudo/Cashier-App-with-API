 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_itemtransaksi extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
	public function getItemTransaksi()
	{
        $this->db->select('item_transaksi.iditemtransaksi, item_transaksi.idtransaksi, item_transaksi.idproduk, produk.nama, item_transaksi.qty, item_transaksi.harga_saat_transaksi, item_transaksi.sub_total');
        $this->db->from('item_transaksi');
        $this->db->join('produk', 'produk.idproduk = item_transaksi.idproduk');
		$query = $this -> db -> get('');
        return $query -> result_array();
	}

    public function getItemTransaksiByIdTransaksi($idtransaksi)
	{
        $this->db->select('item_transaksi.iditemtransaksi, item_transaksi.idtransaksi, item_transaksi.idproduk, produk.nama, item_transaksi.qty, item_transaksi.harga_saat_transaksi, item_transaksi.sub_total');
        $this->db->from('item_transaksi');
        $this->db->join('produk', 'produk.idproduk = item_transaksi.idproduk');
        $this->db->where('itemtransaki.idtransaksi', $idtransaksi);
		$query = $this -> db -> get('');
        return $query -> result_array();
	}

    /////////////////////////////////////////////////////////////////////

    public function insertItemTransaksi($data) {
        $this->db->insert('item_transaksi', $data);

        $insert_id = $this->db->insert_id();
        $result = $this->db->get_where('item_transaksi', array('idtransaksi' => $insert_id));

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function updateItemTransaksi($data, $id) {
        $this->db->where('iditemtransaksi', $id);
        $this->db->update('item_transaksi', $data);

        $result = $this->db->get_where('item_transaksi', array('iditemtransaksi' => $id));

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function deleteItemTransaksi($id) {
        $result = $this->db->get_where('item_transaksi', array('idtransaksi' => $id));

        $this->db->where('idtransaksi', $id);
        $this->db->delete('item_transaksi');

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function cekLoginTransaksi($data) {
        
        $this->db->where($data);
        $result = $this->db->get('admin');

        return $result->row_array();
    }
}
