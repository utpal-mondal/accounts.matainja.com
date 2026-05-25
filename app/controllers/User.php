<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User extends MY_Controller
{

  //var $default_key1='st12aff34';
    function __construct()
    {
        parent::__construct();
      
         $this->lang->load('staff', $this->Settings->user_language);
        
        $this->load->library('email');
          
		//$this->data['csrf'] = $this->_get_csrf_nonce();
        $this->load->model('Staff_model');
         $this->load->library('ion_auth');
         $this->entry_time = "10:15";
    }

   public function synuser()
            {
                	
	     		$key = $this->input->get_post('api_key');
				$default_key=$this->config->item('api_key');
                $user_details=$this->Staff_model->getstaff();
                $update_time=$this->Staff_model->getupdatetime();
         	if($key == $default_key)
			{
                if($user_details!='')
                 {
                    foreach($user_details as $userdetails)
                    {
                    if ($userdetails->active==1)
                    {
                        $statususer='Active';
                    }
                else{
                    $statususer='In Active';    
                    }  
       
                $data['usersdetails'][]=array(
                'username'=>$userdetails->username,
                'name'=>$userdetails->first_name.''.$userdetails->last_name,
                'dob'=>$userdetails->dob,
                'user_id'=>$userdetails->user_id,
				'rfid'=>$userdetails->attendance_id,
				'modified_date'=>$userdetails->updated_on,
                'status'=>$statususer

             );
        
            }
           
            }
      		if(!empty($update_time))
         foreach($update_time as $update_timenew )
            {
                $data['update_table_data'][]=array(
                'updated_time'=>$update_timenew->update_time,
                'table_name'=>$update_timenew->table_name,

           );
            } 
			
			}
			else{
				$data['error']=1;
	     		$data['message']='Please enter valid key.';
			}
            echo json_encode($data);
            }
            
          /*  public function userlist()
            {
               $user_details=$this->Staff_model->getstaff();
                $update_time=$this->Staff_model->getupdatetime();
              //print_r($user_details);
              //die();
                if($user_details!='')
                 {
                    foreach($user_details as $userdetails)
                    {
                    if ($userdetails->active==1)
                    {
                        $statususer='Active';
                    }
                else{
                    $statususer='In Active';    
                    }  
       
                $data['usersdetails'][]=array(
                'Name'=>$userdetails->first_name." ".$userdetails->last_name,
                'DOB'=>$userdetails->dob,
                'RFID'=>$userdetails->attendance_id,
                'Status'=>$statususer


             );  
               }
                 }
         //echo json_encode($data);
         foreach($update_time as $update_timenew )
            {
                $data['update_timenew'][]=array(
                'ROWTIMEESTAMP'=>$update_timenew->update_time,
                'UPDATE_TABLE'=>$update_timenew->table_name,

           );
            }   
            echo json_encode($data);

            }
			*/

			function UnitTest()
			{
				$today=date("Y-m-d H:i:s");
				$user_info =array();
              $this->SendEmailStaff($user_info);

			}
		function Getoutput($response)
		{



		 /* $this->output
        ->set_content_type('application/json')
        ->set_output(json_encode($response));*/

		}
		function timeDiff($start,$end)
			{

		       $start_ts = strtotime($start);

		       $end_ts = strtotime($end);

		       $diff = $end_ts - $start_ts;

		       return round($diff/60);
			}
          // All attenedence data for a particular date
		function GetStaffHours($data=array(),$post_response)
		{
			echo "GetStaffHoursCalling";
           $response =array();
           $inc =0;
               $date_attd = date("Y-m-d",strtotime($post_response['attendance_time']));
               
           // First Record Then Out time Fire
              
           if(!empty($data))
           {
                    // Oute Time
           	         //echo "Data is Valid";
                      
           	        $attendec_record_raw = $this->Staff_model->Getattendence($data->user_id,$date_attd);

									           	        /*



									    [id] => 1
									    [user_id] => 13
									    [date_entry] => 2018-02-09
									    [in_time] => 2018-02-09 10:59:40
									    [out_time] => 2018-02-09 13:59:40
									    [input_time] => 2018-02-09 13:59:40
									    [in_out_time] => 1
									    [work_hours] => 01:00:00
									    [attendence_count] => 3
    */

           	        if(!empty($attendec_record_raw))
           	        {

			           	   $response['entry_time'] = $attendec_record_raw->in_time;
			               $response['out_time'] = $post_response['attendance_time'];
			               $response['device_type'] = $post_response['device_type'];
			               $response['rfid'] = $post_response['rfid'];

			               

			               $response['working_hours'] = $attendec_record_raw->work_hours;
			               $response['in_out_time'] = $attendec_record_raw->in_out_time;
			               $response['attendence_count'] = $attendec_record_raw->attendence_count;
	                }



           	}
              
               
            

			
			

			return $response;

		}
	 function SendEmailStaff($user_info,$get_attendance,$post_response)
	   {

	   	   $today_date =date("Y-m-d");
	   	   $note="";
	   	   $prefix_text ="Bye";
	   	   $signoff_time ="";
	   	   $outtimeText = "";


	   	   i
	   	
	 	
           $attendec_record =  $this->GetStaffHours($user_info,$post_response);

         
            /*

					            Array
					(
					    [entry_time] => 2018-02-09 10:59:40
					    [out_time] => 2018-02-09 18:59:40
					    [device_type] => rfmachine
					    [rfid] => 166-62-196-9
					    [working_hours] => 01:00:00
					    [in_out_time] => 1
					    [attendence_count] => 3
					)


            */
	      if(!empty($attendec_record))
	      {
	      	        $attendence_count= $attendec_record['attendence_count'];
	      	        $in_out_time= $attendec_record['in_out_time'];

	      	        if($attendence_count==1)
	      	        	$note= $post_response['note'];

	      	        if($in_out_time == 1)
	      	        	$prefix_text ="Welcome";
	      	        else
	      	        {
	      	        	$signoff_time=$attendec_record['out_time'];
	      	        	$outtimeText = "Signoff Time : ";
	      	        }
	       
                    $this->load->library('parser');
                    $parse_data = array(
                        'user_name' => $user_info->first_name . ' ' . $user_info->last_name,
                        'device_type'=>$post_response['device_type'],
                        'Time' =>$attendec_record['entry_time'] ,
                        'Hour'=> $attendec_record['working_hours'] ,
                        'Note'=>$note,
                        'signoff_time'=>$outtimeText.$signoff_time,
                        'RFID'=>$post_response['rfid'],
                        'site_link' =>base_url(),
                        'site_name' => $this->Settings->site_name,
                        'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                    );
                    
                

	 	

					  $msg=file_get_contents('./themes/' . $this->theme . 'email_templates/attendance_notification.html');
					  $messagenew = $this->parser->parse_string($msg, $parse_data);

 
 
		                $subject = "[ Attendance Notification ] ".$prefix_text." " .$user_info->first_name . ' ' . $user_info->last_name;

		               $this->sma->send_email($user_info->email,$subject,$messagenew);

		               print_r($user_info);

		               if(isset($user_info->project_manager) && $user_info->project_manager>0)
		               {

                         $pminfo = $this->Staff_model->getstaffById($user_info->project_manager);

                         print_r($pminfo);

		               }
		    }

	 }
      function set_attendance(){
		  $default_key=$this->config->item('api_key');
		  $today = date("Y-m-d H:i:s");
		  $post_response=array();
		  	//echo $this->input->get_post('key');die();
	     	$user_id = ($this->input->get_post('user_id')!='')?$this->input->get_post('user_id'):0;
	     	$api_key =  ($this->input->get_post('api_key')!='')?$this->input->get_post('api_key'):"";
			$note=($this->input->get_post('note')!='')?$this->input->get_post('note'):"";
			$rfid=($this->input->get_post('rfid')!='')?$this->input->get_post('rfid'):"";
			$device_type=($this->input->get_post('device_type')!='')?$this->input->get_post('device_type'):"";
			$attendance_time=($this->input->get_post('attendance_time')!='')?$this->input->get_post('attendance_time'):"";

			//Post Data from Rasberry PI 
			$post_response['user_id'] = $user_id;
			$post_response['rfid'] = $rfid;
			$post_response['device_type'] = $device_type;
			$post_response['attendance_time'] = $attendance_time;
			$post_response['note'] = $note;

			//$note = $this->input->get_post('note');
	     	if($api_key == $default_key)
			{
	     	$user_info = $this->Staff_model->get_staff_info($user_id)->row();
              
	     		if(!empty($user_info) && $user_id>0)
					{
					$get_attendance=$this->Staff_model->get_staff_attendancenew($user_id);
					//print_r($get_attendance);
					//die();
					
	     			 if(!empty($get_attendance)) {
	     				$note_data="";
	     			 } else {
						$note_data=$note;
					 }
					$data = array(
								'staff_id'=>$user_id,
								'staff_name'=>$user_info->first_name.' '.$user_info->last_name,
								'device_type'=>$device_type,
								'attendance_time'=>$attendance_time,
								'update_time'=> $today,
								'attendance_id'=> $rfid,
								'note'=>$note_data
						);
						$insert_attendance=$this->Staff_model->insert_attendance($data);	
	     				if($insert_attendance)
						{
							

							$this->Staff_model->AttedenceRecord($user_info,$get_attendance,$post_response);
							$this->SendEmailStaff($user_info,$get_attendance,$post_response);
							$result['status']=1;
	     					$result['message']='Attendance added successfully';


	     				} else {
	     							$result['status']=0;
	     							$result['message']='Attendance cannot be added';
	     				}
				
				}else{
					
					if(isset($rfid) && $rfid!="")
					{
						
						$data = array(
								'rfid'=>$rfid
								
						);
						
							$insert_attendance=$this->Staff_model->insert_rfid($data);	
							$result['status']=2;
	     					$result['message']='Welcome New Staff';
					
					}
					else
					{
					
					$result['status']=0;
	     			$result['message']='Please enter valid user id.';
					}
				} 
			}else{
				$result['status']=0;
	     		$result['message']='Please enter valid key.';
			}
                $this->Getoutput1ss($result);
	     	//echo json_encode($result);
	     
	  }
	  



}
