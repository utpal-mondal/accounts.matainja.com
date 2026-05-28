<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Contacts extends MY_Controller
{
    public $Contact_model;

    function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
        $this->lang->load('contacts', $this->Settings->user_language);
        $this->load->library('form_validation');
        $this->load->model('Contact_model');
    }

    public function index()
    {
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['contacts'] = $this->Contact_model->getAllContacts();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('contacts')));
        $meta = array('page_title' => lang('contacts'), 'bc' => $bc);
        $this->page_construct('contacts/index', $meta, $this->data);
    }
}
