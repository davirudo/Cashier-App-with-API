 <?php
defined('BASEPATH') OR exit('No direct script access allowed');

class m_admin extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
	public function getAdmin()
	{
		$query = $this -> db -> get('admin');
        return $query -> result_array();
	}

    /////////////////////////////////////////////////////////////////////

    public function insertAdmin($data) {
        $this->db->insert('admin', $data);

        $insert_id = $this->db->insert_id();
        $result = $this->db->get_where('admin', array('idadmin' => $insert_id));

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function updateAdmin($data, $id) {
        $this->db->where('idadmin', $id);
        $this->db->update('admin', $data);

        $result = $this->db->get_where('admin', array('idadmin' => $id));

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function deleteAdmin($id) {
        $result = $this->db->get_where('admin', array('idadmin' => $id));

        $this->db->where('idadmin', $id);
        $this->db->delete('admin');

        return $result->row_array();
    }

    /////////////////////////////////////////////////////////////////////
    
    public function cekLoginAdmin($data) {
        
        $this->db->where($data);
        $result = $this->db->get('admin');

        return $result->row_array();
    }

    public function cekAdaAdmin($id) {

        $data = array(
        "idadmin" => $id
        );
        
        $this->db->where($data);
        $result = $this->db->get('admin');
        
        if(empty($result->row_array())) {
            return false;
        }

        return true;
    }
}
