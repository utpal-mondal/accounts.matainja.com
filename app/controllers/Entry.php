<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Entry extends MY_Controller
{

  //var $default_key1='st12aff34';
    function __construct()
    {
        parent::__construct();
      
         $this->lang->load('staff', $this->Settings->user_language);
        
       

        $this->upload_path = 'assets/uploads/staff/';
       
        $this->image_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
       
       $this->allowed_file_size = '9024';
      $this->lang->load('staff', $this->Settings->user_language);
        $this->load->library(array('form_validation','ion_auth'));
           $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model(array('Staff_model','settings_model'));
		$this->load->library('session');
       
          
		//$this->data['csrf'] = $this->_get_csrf_nonce();
       
         $this->load->library('ion_auth');
    }
     public function index()

    {

        $this->data['title'] = "Create Staff";
            $this->form_validation->set_rules('staff_name', lang("Name *"), 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('staff_fathername', lang("fathername"), 'trim|required|alpha_numeric_spaces');
            $this->form_validation->set_rules('dobdate', lang("dobdate"),'trim|required');
           
            $this->form_validation->set_rules('Address', lang("address"), 'trim|required');
            $this->form_validation->set_rules('PresentAddress', lang("PresentAddress"),'trim');
            $this->form_validation->set_rules('city', lang("city"), 'trim|required|alpha');
            $this->form_validation->set_rules('zipcode', lang("zipcode"), 'trim|required|numeric');
          
            $this->form_validation->set_rules('personalemail', lang("personalemail"), 'is_unique[sma_staff_info.personalemail]|required|valid_email|trim');
            $this->form_validation->set_rules('businessemail', lang("businessemail"),'required|is_unique[sma_users.email]|valid_email|trim');
            $this->form_validation->set_rules('Phone', lang("Phone"), 'required|numeric');
           
        //  $this->form_validation->set_rules('attendance_id', lang("attendance_id"),'required');
        
    
            
         if ($this->form_validation->run() == true)
          {
            $staffname=explode(" ",$this->input->post('staff_name'));
            $firstname='';
            $lastname='';
            if(isset($staffname[1]) && $staffname[1]!=''){
            foreach($staffname as $key=>$value)
                {
                  if($key==0){
                        $firstname=$value;
                     }else{
                        $lastname .= $value.' ';
                    }
                }
            }else{
                $firstname=$this->input->post('staff_name');
            }
        
          $businessemail=$this->input->post('businessemail');
          $path=explode('@',$businessemail);
          $username=$path[0];
          $group_staff=$this->input->post('group');
          $paymentmode=$this->input->post('payment_mode');
          $acc_number=$this->input->post('accountnumber');
          $dob = str_replace('/','-',$this->input->post('dobdate'));
          $dob_data=date("Y-m-d", strtotime($dob));
          $joindate = $this->input->post('joindate');
          //$join_data=date("Y-m-d", strtotime($joindate));
          $interviewdate =$this->input->post('interviewdate');
         
          $interviewschedule = $this->input->post('interviewschedule');
           //$release_data2=$this->input->post('release_date');
        
          $project_manager=$this->input->post('project_manager');
            
        
      $staff_data = array(
                'last_ip_address' => $_SERVER['REMOTE_ADDR'],
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'username' => $username,
                 'password' => md5($username),
                 'salt'=>NULL,
                  'email' => $this->input->post('businessemail'),
                  'activation_code'=>NULL,
                   'forgotten_password_code'=>NULL, 
                 'forgotten_password_time'=>NULL,
                 'remember_code'=>'',
                 'created_on' => strtotime(date('Y-m-d H:s:i')),
                'last_login' => strtotime(date('Y-m-d H:s:i')),
                'active'=> 0, 
               'first_name' =>$firstname,
                'last_name'=>$lastname,
                'company' => 'Matainja Technologies',
                'phone' => $this->input->post('Phone'),
                'avatar'=>NULL,
                'gender'=>$this->input->post('gender'),
                'group_id'=>$group_staff,
                'warehouse_id'=>0,
                'biller_id'=>0,
                'company_id'=>NULL,
                'show_cost'=>0,
                'show_price'=>0,
                'award_points'=>0,
                'view_right'=>1,
                'edit_right'=>0,
                'allow_discount'=>0,
                'is_staff'=>1,
                //'RFID'=>(isset($rfid[0]->id) && $rfid[0]->id!='')?$rfid[0]->id:0,
            );
            $q=$this->Staff_model->registerstff($staff_data);
            
         if($q){
                             $additional_data = array(
                                        'user_id'       =>$q,
                                        'staff_fathername' => $this->input->post('staff_fathername'),
                                        'dob' => $dob_data,
                                        'joindate' =>$joindate,
                                        'address' => $this->input->post('Address'),
                                        'presentaddress' => $this->input->post('PresentAddress'),
                                        'city' => $this->input->post('city'),
                                        'zipcode' => $this->input->post('zipcode'),
                                        'upload'=> '', 
                                        'interviewdate' =>$interviewdate ,
                                        'interviewschedule' =>$interviewschedule ,
                                        'personalemail' => $this->input->post('personalemail'),
                                        'group_id'=>$group_staff,
                                        'payment_mode'=>'',
                                        'account_number'=>'',
                                        'release_date'=>'',
                                        'note'=>$this->input->post('note'),
                                        'project_manager'=>$project_manager,
                                        'attendance_id'=>NULL  
                                        );  
                             //print_r($additional_data);
           //die();

            $p=$this->Staff_model->register($additional_data);
         
           if($p){
             if ($_FILES['Browse']['size'] > 0) {
                 mkdir($this->upload_path.'/'.$p,0777,true);
                $this->load->library('upload');
                $config['upload_path'] =$this->upload_path.'/'.$p  ;
                $config['allowed_types'] = $this->image_types;
                $config['max_size'] = $this->allowed_file_size;
                $config['overwrite'] = FALSE;
                $config['encrypt_name'] = TRUE;
                 $config['file_name'] =$_FILES['Browse']['name']  ;
                $this->upload->initialize($config);
                if (!$this->upload->do_upload('Browse')) {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('error', $error);
                    redirect($_SERVER["HTTP_REFERER"]);
                }
                else{
                $photo = $this->upload->file_name;
                $d=array('upload'=>$this->upload_path.$p.'/'.$photo);
                $this->Staff_model->uploadfile($d,$p);
                
               
                }

        }

         
                            }        
                  
        //$result['message']='Welcome New Staff';
        //$result['ID']=$q;
        $this->session->set_flashdata('message','Staff Added Successfully!!!<br>#'.$q);
        //echo $q;
         redirect('entry');
		     // echo json_encode($result);
        //$this->session->flashdata('message');
       

}
                }
          else{
		$this->load->language('calendar');
        $this->data['Settings'] = $this->site->get_setting();
		$this->m = strtolower($this->router->fetch_class());
            $this->v = strtolower($this->router->fetch_method());
            $this->data['m']= $this->m;
            $this->data['v'] = $this->v;
		$this->data['dateFormats'] = $this->settings_model->getDateFormats();
		 $this->data['dt_lang'] = json_encode(lang('datatables_lang'));
            $this->data['dp_lang'] = json_encode(array('days' => array(lang('cal_sunday'), lang('cal_monday'), lang('cal_tuesday'), lang('cal_wednesday'), lang('cal_thursday'), lang('cal_friday'), lang('cal_saturday'), lang('cal_sunday')), 'daysShort' => array(lang('cal_sun'), lang('cal_mon'), lang('cal_tue'), lang('cal_wed'), lang('cal_thu'), lang('cal_fri'), lang('cal_sat'), lang('cal_sun')), 'daysMin' => array(lang('cal_su'), lang('cal_mo'), lang('cal_tu'), lang('cal_we'), lang('cal_th'), lang('cal_fr'), lang('cal_sa'), lang('cal_su')), 'months' => array(lang('cal_january'), lang('cal_february'), lang('cal_march'), lang('cal_april'), lang('cal_may'), lang('cal_june'), lang('cal_july'), lang('cal_august'), lang('cal_september'), lang('cal_october'), lang('cal_november'), lang('cal_december')), 'monthsShort' => array(lang('cal_jan'), lang('cal_feb'), lang('cal_mar'), lang('cal_apr'), lang('cal_may'), lang('cal_jun'), lang('cal_jul'), lang('cal_aug'), lang('cal_sep'), lang('cal_oct'), lang('cal_nov'), lang('cal_dec')), 'today' => lang('today'), 'suffix' => array(), 'meridiem' => array()));
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['groups'] = $this->ion_auth->groups()->result_array();
              $bc = array(array('link' =>base_url() , 'page' => lang('home')), array('link' => 'staff', 'page' => lang('staff')),array('link' => '#','page' => lang('create_staff')));
        $this->data['meta'] = array('page_title' => lang('create_staff'), 'bc' => $bc);
        //$this->page_construct('staff/create_staff', $meta, $this->data);
         $this->load->view($this->theme . 'staffentry', $this->data);
             
          }
          
        
       
    }


   
}
?>