<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Leave extends MY_Controller
{
	 function __construct()
    	{
       		 parent::__construct();

           require_once MPDF;

        $this->method = new \Mpdf\Mpdf (['mode' => 'utf-8']);
       		   if (!$this->loggedIn)
       		    {
                  $this->session->set_userdata('requested_page', $this->uri->uri_string());
                  $this->sma->md('login');
                }
                  $this->lang->load('leave', $this->Settings->user_language);
                  $this->load->library(array('form_validation','ion_auth','sma'));
                   //$this->load->library(array('form_validation','ion_auth','pdf','sma'));
                    $this->load->library('email');
		         $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
                 $this->load->model(array('Leave_model','App_model'));
		        //$this->load->library('ion_auth');
       	}
       	  public function index() //Fetching the staff list
    			{
		 		//	$this->sma->checkPermissions();
					$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			 		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('leave')));
        			$meta = array('page_title' => lang('leave'), 'bc' => $bc);
        			$this->page_construct('leave/index', $meta, $this->data);
        		}

			public function add()
			{
		 	// print_r($this->input->post());
				//die();
			//$this->sma->checkPermissions();
			$this->data['titile']="Create Leave";
			$this->form_validation->set_rules('Staff_id',lang('staff'),'trim|required');
			$this->form_validation->set_rules('start_Date',lang('start_Date'),'trim|required');
			$this->form_validation->set_rules('end_Date',lang('end_Date'),'trim|required');
      $this->form_validation->set_rules('reason',lang('reason'),'trim|required');
           if ($this->form_validation->run() == true)
		      {
		   		$staff_name=$this->input->post('Staff_id');
		   		//$staff_name=$this->staff_model->get_staff_attendance($staff_id);
		      	$start_date=str_replace('/','-',$this->input->post('start_Date'));
			  $start_datenew=  new DateTime($start_date);
               $end_date=str_replace('/','-',$this->input->post('end_Date'));
          $end_datenew=  new DateTime($end_date);
             $include=$this->input->post('include_date');
            $leave_type=$this->input->post('leave_type');
           $payment_type=$this->input->post('payment_type');
           $reason=$this->input->post('reason');
        $description=$this->input->post('description');
         
               if($start_date<$end_date)
                {
                
                  $diffnew=date_diff($start_datenew,$end_datenew);
               
                 $datenew_list=$diffnew->format("%d days");
                  $date=array();
                  $date[0]= date("Y-m-d", strtotime($start_date));
                 for($i=0;$i<=$datenew_list;$i++)
                  {

                   
                  	$sdate=strtotime("+".$i." day", strtotime($start_date));
                  	if($i>=1){
                  		$date[$i]=date("Y-m-d", $sdate);
                  	}
                  	if($include==1){
                        
	                 $data_array=array(
	                	'user_id'=>$staff_name,
	                	'leave_date'=>$date[$i],
	                	'leave_type'=>$leave_type,
	                	'payment_type'=>$payment_type,
	                	'status'=>'Approve',
                    'subject'=>$reason,
                    'description'=>strip_tags($description)

	              		);
					 $result=$this->Leave_model->register($data_array);
          
          
                  	}else{

                  		if(date('N', strtotime($date[$i]))!=6 && date('N', strtotime($date[$i]))!=7){
	                  		$data_array=array(
		                	'user_id'=>$staff_name,
		                	'leave_date'=>$date[$i],
		                	'leave_type'=>$leave_type,
		                	'payment_type'=>$payment_type,
		                	'status'=>'approve',
                      'subject'=>$reason,
                    'description'=>strip_tags($description)
		              		);
							$result=$this->Leave_model->register($data_array);
                 
                  		}
                  	}
                  }
                  
              }
                  else
                  {
                 
                  	$date_list=date('Y-m-d',strtotime($start_date));
                  /*	echo $date_list;
                  	die();*/
                   $data_array=array(
                	'user_id'=>	$staff_name,
                	'leave_date'=>$date_list,
                	'leave_type'=>$leave_type,
                	'payment_type'=>$payment_type,
                	'status'=>'Approve',
                  'subject'=>$reason,
                  'description'=>strip_tags($description)
              		);
                   // print_r($data_array);
                 // die();
              $result=$this->Leave_model->registernew($data_array);

                  }
                 
           if($result==true)
              {
                
              $this->sendmail_add_leave($staff_name,$start_date,$end_date);
             }   
                 

            $this->session->set_flashdata('message', 'Leave Added Successfully');
			     redirect('leave/add');
                  
           } 
		else{
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$bc = array(array('link' =>base_url() , 'page' => lang('home')), array('link' => 'leave', 'page' => lang('leave')),array('link' => '#','page' => lang('create_leave')));
        $meta = array('page_title' => lang('create_leave'), 'bc' => $bc);
        $this->page_construct('leave/create_leave', $meta, $this->data);
			 
		  }

				}
 public function sendmail_add_leave($staff_id,$start_date,$end_date)
          {
           $admin_mail_id=$this->config->item('admin_email');
           $useremail=$this->App_model->getusermail($staff_id,$start_date,$end_date);
           /*print_r($useremail);

           die();*/
            $p=array();
            $i=0;
           foreach($useremail as $q)
             {
               $p[$i]=$q->leave_date;
               $i++;
             }
            $this->load->library('parser');
            $parse_data = array(
                        'username'=>$useremail[0]->first_name.' '.$useremail[0]->last_name,
                        'leave_type'=>$useremail[0]->leave_type,
                        'leave_date'=>implode(', ',$p),
                        'status'=>"approve",
                        'site_link' =>base_url(),
                        'site_name' => $this->Settings->site_name,
                        'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                    );
                   // print_r($parse_data); 
   $msg=file_get_contents('./themes/' . $this->theme .'email_templates/leave_email.html');
   $messagenew = $this->parser->parse_string($msg, $parse_data);

  $subject = "[Matainja]"." ".$useremail[0]->leave_date." "."[Leave Application]"." ".$useremail[0]->first_name . ' ' .$useremail[0]->last_name;
 
            $this->sma->send_email($admin_mail_id,$subject,$messagenew,null,null,null,$useremail[0]->email);
                     
            } 
   public function getleaveresult()
   {
       $staff_id=$this->input->post('staffid');
       $leave_years=$this->input->post('leaveyears');
      
       $status_result=$this->input->post('status');
       $status_array = explode(',', $status_result);
       $approve_result = count($status_array);
       $data=array();
       if($approve_result==1)
       {
        $data=$this->Leave_model->getlist($staff_id,$leave_years,$status_array);
        
       }
       else if($approve_result==2)
       {
        $data=$this->Leave_model->getlist($staff_id,$leave_years,$status_array);
         
       }
       else
       {
        $data=$this->Leave_model->getlist($staff_id,$leave_years);
         
       }
       if(!empty($data))
       {
        $result['leave_list']=$data;
       }
       else
       {
        $result['error']=1;
        $result['message']='No record found';
       }
       echo json_encode($result);
   } 
   public function downloadleaveresult()
   {
       //$staff_id=$this->input->post('staffid');
       $leave_years=$this->input->get('year');
       //$status_result=$this->input->post('status');
       $data=array();
     $user_leave=$this->Leave_model->getfullleavelist($leave_years);
     /* print_r($user_leave);
      die();*/
       foreach($user_leave as $leaveall)
        {
          $getleave_year=date('Y',strtotime($leaveall->leave_date));
           if($leave_years==$getleave_year){
            $user_data[]=$leaveall;
          }
        }
         $user_data_new=$user_data;
        $user_leave_array=array();
        $i=0;
        $user_id_array=array();
      foreach($user_data as $userleave_new)
      {
        $user_id=$userleave_new->user_id;
        if(in_array($userleave_new->user_id, $user_id_array)){
          continue;
        }
       //echo $key2=array_search($user_id, $user_data_new);
        $user_leave_array[$i] = array(
                                      'user_id'=>$userleave_new->user_id,
                                      'user_name'=>$userleave_new->first_name." ".$userleave_new->last_name
                                    );
        foreach($user_data_new as $userdetail){
            if($userdetail->user_id==$user_id){
                $user_leave_array[$i]['leave_data'][] = $userdetail;    
            }
        }
         $user_id_array[]=$user_id;
        $i++;
      }
      $this->data['leave_array']=$user_leave_array;

       /*print_r($this->data);
        die();*/
       /*------------------------------create Pdf------------------------------*/
        $name = 'leave' . "_" . date('Y_m_d_H_i_s') ."_".$staff_id.".pdf";
       $html = $this->load->view($this->theme .'leave/pdf',$this->data,true);
        
       // $get_pdf_file = $this->sma->generate_pdf($html,$name,'S',false, false, false, 5);
        $this->sma->generate_pdf($html, $name, false, false, false, false, 5);
       /*if(file_exists($get_pdf_file))
       {
        $result['url']=base_url().$get_pdf_file;
        $result['message']='Successfully download pdf';
        $result['success'] = 1;
        $result['error'] = 0;
       }
       else
       {
        $result['success'] = 0;
        $result['error'] = 1;
        $result['message']='No Pdf found';
       }
       echo json_encode($result);*/
   }
  public function updatepaymenttype()
  {
    $user_id=$this->input->post('userid');
    $leave_date=$this->input->post('leavedate');
    $payment_type=$this->input->post('paymenttype');
    $data=array(
      'user_id'=>$user_id,
      'leave_date'=>$leave_date,
       'payment_type'=>$payment_type
        );
      $updateleave=$this->Leave_model->updateleavelist($data);
      $response=array();
      if(isset($updateleave))
      {
        $response['success']=1;
        $response['message']="leave type update Successfully";
      }
      else
      {
        $response['error']=0;
        $response['message']="Not Updated";
      }
      echo json_encode($response);

  }

   public function updatestatus()
   {
    $user_id=$this->input->post('userid');
    $leave_date=$this->input->post('leavedate');
    $status=$this->input->post('leave_status');
    $data=array(
      'user_id'=>$user_id,
      'leave_date'=>$leave_date,
      'status'=>$status
    );
    //print_r($data);
    //die();
    $updatestatuslist=$this->Leave_model->updatestatuslist($data);
    if($updatestatuslist)
    {
      $this->sendmail_leave($user_id,$leave_date,$status);
      $leave_details=$this->Leave_model->count_leave($data);
     
      $data_array['cl_count']=$leave_details[0][0]['count_cl'];
      $data_array['ml_count']=$leave_details[1][0]['count_ml'];
       $data_array['success']=0;
       $data_array['message']='Updated Successfully';
       } 
   else{
       $data_array['error']=1;
    $data_array['message']='Not updated';
   } 
   echo json_encode($data_array);  
			}
  public function sendmail_leave($id,$leave_date,$status)
      {
        $useremail=$this->Leave_model->getusermail($id,$leave_date,$status);
      //  $leave_send=$this->Leave_model->
      // print_r($useremail);
       // die();
        $this->load->library('parser');
            $parse_data = array(
                        'username'=>$useremail[0]->first_name.' '.$useremail[0]->last_name,
                        'leave_type'=>$useremail[0]->leave_type,
                        'leave_date'=>$useremail[0]->leave_date,
                        'status'=>$useremail[0]->status,
                        'site_link' =>base_url(),
                        'site_name' => $this->Settings->site_name,
                        'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                    );
          //print_r($parse_data);
          //die();
                    
     echo $msg=file_get_contents('./themes/' . $this->theme .'email_templates/leave_status.html');
    echo $messagenew = $this->parser->parse_string($msg, $parse_data);
   echo $subject = "[ Leave_Status ] ".$useremail[0]->first_name . ' ' . $useremail[0]->last_name;
     $this->sma->send_email($useremail[0]->email,$subject,$messagenew);
      }



      public function leavedetail_email()
      {
      $staff_id=$this->input->post('staffid');
      $year=$this->input->post('leaveyears');
      $this->data['u_name']=$this->Leave_model->getuserinfo($staff_id);
      $this->data['user_send_leave']=$this->Leave_model->getall_leave($staff_id,$year);
      /*  print_r($this->data);
        die();*/
      $name = 'Staffleave' . "_" . date('Y_m_d_H_i_s') ."_".$staff_id.".pdf";
      $html = $this->load->view($this->theme .'leave/staffpdf',$this->data,true);
      $this->sma->generate_pdf($html, $name, 'S', false, false, false, 5);
       $filename = $name;
                      $pdfFilePath = "assets/uploads/".$filename;
                      $this->load->library('parser');
                      $parse_data = array(
                          'username'=>$this->data['u_name']->first_name.' '.$this->data['u_name']->last_name,
                          'leave_year'=>$year,
                          'site_link' =>base_url(),
                          'site_name' => $this->Settings->site_name,
                          'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                      );
                      $attachment=$pdfFilePath;
                      $msg=file_get_contents('./themes/' . $this->theme .'email_templates/staffleave_email.html');
                      $messagenew = $this->parser->parse_string($msg, $parse_data,true);
                      $subject ="Staff Yearly Leave Sheet".'-'.$year;
                     
              $mail_status=$this->sma->send_email($this->data ['u_name']->email,$subject,$messagenew,null,null,$attachment,'leave.matainja@gmail.com');
           /* $mail_status=$this->sma->send_email('matainja036@gmail.com',$subject,$messagenew,null,null,$attachment,null);*/
                      if(file_exists($attachment))
                        {
                          unlink($attachment);
                        }
                      if($mail_status==true)
                        {
                      $data['message']="Email sent successfully to"." ".$this->data ['u_name']->first_name.' '.$this->data ['u_name']->last_name;
                        }
                        echo json_encode($data);
                      }
     } 