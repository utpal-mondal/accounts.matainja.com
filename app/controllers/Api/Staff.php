<?php
/*header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');    // cache for 1 day
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");*/
class Staff extends MY_Controller
{
  //var $default_key1='st12aff34';
    function __construct()
    {
        parent::__construct();
      
         $this->lang->load('staff', $this->Settings->user_language);
        
        $this->load->library('email');
          
		/*$this->data['csrf'] = $this->_get_csrf_nonce();*/
        $this->load->model('App_model');
         //$this->load->library('ion_auth');
       // $this->load->model('auth_model');
    }
	
	  ////test dailystaffattendancereport 
     public function get_attendance()
		{

      $uid =  $this->input->get_post('uid');
$daterange =$this->input->get_post('attn_date');

			$key= $this->input->get_post('api_key');
			$default_key=$this->config->item('api_key');
			$attendance_result=$this->App_model->attendancelist($uid,$daterange); 
				if(isset($attendance_result) && !empty($attendance_result)) {
				
						$attendance_newarray['staff']=$attendance_result;
				}
			else
				{
						$attendance_array['message']="No Record Found";
						$attendance_array['error']=1;
						$attendance_newarray[]=$attendance_array;
				}
			
			echo json_encode($attendance_newarray);
		}
		//get information of single staff
		 public function get_staff()
    {
         $key = $this->input->get_post('token');
        $default_key=$this->config->item('api_key');
          if($key == $default_key)
            { 
              $id=($this->input->get_post('id')!='')?$this->input->get_post('id'):'';
                $result=$this->App_model->get_staff($id);
                $response=array();
				
				//print_r($project_manager_name);die();
              if(!empty($result))
              {
				 $project_manager_id=$result[0]->project_manager;
				 $project_manager_name=$this->App_model->get_project_manager($project_manager_id);
				 $joindate=date_create($result[0]->joindate);
				 $todays_date=date_create(date('Y-m-d'));
				 $diff=date_diff($joindate,$todays_date);
				 $experience=$diff->format("%y Year %m Month %d Day");
                 //$response['emp_id']=$result[0]->user_id;
                 $response['Name']=$result[0]->first_name.' '.$result[0]->last_name;
                 $response['Fathername']=$result[0]->staff_fathername;
                 $response['Dob']=$result[0]->dob;
				 $response['Joindate']=$result[0]->joindate;
                 $response['Interviewdate']=$result[0]->interviewdate;
				 $response['Experience']=$experience;
				 $response['Permanentaddress']=strip_tags($result[0]->address);
                 $response['Presentaddress']=strip_tags($result[0]->presentaddress);
				 $response['City']=$result[0]->city;              
			     $response['Zipcode']=$result[0]->zipcode;
              	 $response['Gender']=$result[0]->gender;
			     $response['Personalemail']=$result[0]->personalemail;
                 $response['Businessemail']=$result[0]->email;
				 $response['Phoneno']=$result[0]->phone;
				 $response['Release_date']=$result[0]->release_date;
				 $response['Note']=strip_tags($result[0]->note);
				 //$response['Project_manager']=$project_manager_name->name;		                  
                 $response['Image']=base_url($result[0]->upload);
                 $response['success']=1;
                 $response['message']='Staff Details';
               } 
             else
                {
                 $response['success']=0;
                 $response['message']='Enter the valid id ';
               }
             }
            else{
                  $response['success']=0;
                  $response['message']='Please enter valid key.';
                 }  
            
        
          echo json_encode($response);
      }

  }