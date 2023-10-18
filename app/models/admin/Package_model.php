<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Package_model extends CI_Model
{
    public function insert($data)
    {
        $this->db->insert('sma_package', $data);
        return $this->db->insert_id();
    }
}