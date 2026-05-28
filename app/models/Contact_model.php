<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contact_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function addContact($data)
    {
        if ($this->db->insert('contacts', $data)) {
            return $this->db->insert_id();
        }
        return FALSE;
    }

    public function getContact($id)
    {
        $q = $this->db->get_where('contacts', array('id' => $id), 1);
        if ($q->num_rows() > 0) {
            return $q->row();
        }
        return FALSE;
    }

    public function getAllContacts()
    {
        $this->db->order_by('created_at', 'DESC');
        $q = $this->db->get('contacts');
        if ($q->num_rows() > 0) {
            foreach (($q->result()) as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return FALSE;
    }

    public function updateContact($id, $data)
    {
        $this->db->where('id', $id);
        if ($this->db->update('contacts', $data)) {
            return TRUE;
        }
        return FALSE;
    }

    public function deleteContact($id)
    {
        $this->db->where('id', $id);
        if ($this->db->delete('contacts')) {
            return TRUE;
        }
        return FALSE;
    }
}
