<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');    // cache for 1 day
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, X-API-KEY");

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends MY_Controller
{
    public $Contact_model;

    function __construct()
    {
        parent::__construct();
        $this->load->model('Contact_model');
    }

    // public function index()
    // {
    //     $default_key = $this->config->item('api_key');
    //     $api_key = $this->input->get_request_header('api_key', TRUE);

    //     if ($api_key == $default_key) {
    //         $contacts = $this->Contact_model->getAllContacts();
    //         $countContacts = count($contacts);
    //         if ($contacts) {
    //             $result['status'] = 1;
    //             $result['message'] = 'Contacts retrieved successfully';
    //             $result['count'] = $countContacts;
    //             $result['data'] = $contacts;                
    //         } else {
    //             $result['status'] = 0;
    //             $result['message'] = 'No contacts found';
    //         }
    //     } else {
    //         $result['status'] = 0;
    //         $result['message'] = 'Please enter valid key.';
    //     }
    //     $this->Getoutput($result);
    // }

    // public function get_contact()
    // {
    //     $default_key = $this->config->item('api_key');
    //     $api_key = $this->input->get_request_header('api_key', TRUE);
    //     $key = $this->input->get_post('api_key');
    //     $id = $this->input->get_post('id');

    //     if ($api_key == $default_key || $key == $default_key) {
    //         if ($id) {
    //             $contact = $this->Contact_model->getContact($id);
    //             if ($contact) {
    //                 $result['status'] = 1;
    //                 $result['message'] = 'Contact retrieved successfully';
    //                 $result['data'] = $contact;
    //             } else {
    //                 $result['status'] = 0;
    //                 $result['message'] = 'Contact not found';
    //             }
    //         } else {
    //             $result['status'] = 0;
    //             $result['message'] = 'Please provide contact id.';
    //         }
    //     } else {
    //         $result['status'] = 0;
    //         $result['message'] = 'Please enter valid key.';
    //     }
    //     $this->Getoutput($result);
    // }

    public function add_contact()
    {
        $default_key = $this->config->item('api_key');
        //$api_key = $this->input->get_post('api_key');
        $api_key = $this->input->get_request_header('X-API-KEY', TRUE);
        $key = $this->input->get_post('api_key');

        if ($api_key == $default_key || $key == $default_key) {
            $name = $this->input->get_post('name');
            $email = $this->input->get_post('email');
            $phone = $this->input->get_post('phone');
            $subject = $this->input->get_post('subject');
            $message = $this->input->get_post('message');

            if ($name && $email && $subject && $message) {
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'subject' => $subject,
                    'message' => $message
                );

                $contact_id = $this->Contact_model->addContact($data);
                if ($contact_id) {
                    $result['status'] = 1;
                    $result['message'] = 'Contact added successfully';
                    $result['contact_id'] = $contact_id;
                } else {
                    $result['status'] = 0;
                    $result['message'] = 'Contact could not be added';
                }
            } else {
                $result['status'] = 0;
                $result['message'] = 'Please provide all required fields (name, email, subject, message).';
            }
        } else {
            $result['status'] = 0;
            $result['message'] = 'Please enter valid key.';
        }
        $this->Getoutput($result);
    }

    // public function update_contact()
    // {
    //     $default_key = $this->config->item('api_key');
    //     $api_key = $this->input->get_request_header('api_key', TRUE);
    //     $id = $this->input->get_post('id');

    //     if ($api_key == $default_key) {
    //         if ($id) {
    //             $name = $this->input->get_post('name');
    //             $email = $this->input->get_post('email');
    //             $subject = $this->input->get_post('subject');
    //             $message = $this->input->get_post('message');

    //             $data = array();
    //             if ($name) $data['name'] = $name;
    //             if ($email) $data['email'] = $email;
    //             if ($subject) $data['subject'] = $subject;
    //             if ($message) $data['message'] = $message;

    //             if (!empty($data)) {
    //                 if ($this->Contact_model->updateContact($id, $data)) {
    //                     $result['status'] = 1;
    //                     $result['message'] = 'Contact updated successfully';
    //                 } else {
    //                     $result['status'] = 0;
    //                     $result['message'] = 'Contact could not be updated';
    //                 }
    //             } else {
    //                 $result['status'] = 0;
    //                 $result['message'] = 'Please provide at least one field to update.';
    //             }
    //         } else {
    //             $result['status'] = 0;
    //             $result['message'] = 'Please provide contact id.';
    //         }
    //     } else {
    //         $result['status'] = 0;
    //         $result['message'] = 'Please enter valid key.';
    //     }
    //     $this->Getoutput($result);
    // }

    // public function delete_contact()
    // {
    //     $default_key = $this->config->item('api_key');
    //     $api_key = $this->input->get_request_header('api_key', TRUE);
    //     $id = $this->input->get_post('id');

    //     if ($api_key == $default_key) {
    //         if ($id) {
    //             if ($this->Contact_model->deleteContact($id)) {
    //                 $result['status'] = 1;
    //                 $result['message'] = 'Contact deleted successfully';
    //             } else {
    //                 $result['status'] = 0;
    //                 $result['message'] = 'Contact could not be deleted';
    //             }
    //         } else {
    //             $result['status'] = 0;
    //             $result['message'] = 'Please provide contact id.';
    //         }
    //     } else {
    //         $result['status'] = 0;
    //         $result['message'] = 'Please enter valid key.';
    //     }
    //     $this->Getoutput($result);
    // }

    function Getoutput($response)
    {
        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
    }
}
