 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_transaksi extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
	public function getTransaksi()
	{
        $this->db->select('transaksi.idtransaksi, transaksi.total, transaksi.tanggal, admin.nama');
        $this->db->from('transaksi');
        $this->db->join('admin', 'admin.idadmin = transaksi.idadmin');
		$query = $this -> db -> get('');
        return $query -> result_array();
	}

    public function getTransaksiBulanan()
	{
        $this->db->select('transaksi.idtransaksi, transaksi.total, transaksi.tanggal, admin.nama');
        $this->db->from('transaksi');
        $this->db->join('admin', 'admin.idadmin = transaksi.idadmin');
        $this->db->where('month(tanggal)', date('m'));
		$query = $this -> db -> get('');
        return $query -> result_array();
	}

    /////////////////////////////////////////////////////////////////////

    public function insertTransaksi($data) {
        $this->db->insert('transaksi', $data);

        $insert_id = $this->db->insert_id();
        $result = $this->db->get_where('transaksi', array('idtransaksi' => $insert_id));

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function updateTransaksi($data, $id) {
        $this->db->where('idtransaksi', $id);
        $this->db->update('transaksi', $data);

        $result = $this->db->get_where('transaksi', array('idtransaksi' => $id));

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function deleteTransaksi($id) {
        $result = $this->db->get_where('transaksi', array('idtransaksi' => $id));

        $this->db->where('idtransaksi', $id);
        $this->db->delete('transaksi');

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function cekLoginTransaksi($data) {
        
        $this->db->where($data);
        $result = $this->db->get('admin');

        return $result->row_array();
    }
}
