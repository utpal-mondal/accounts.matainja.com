<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Max-Age: 86400');    // cache for 1 day
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

class Login extends MY_Controller
{

  //var $default_key1='st12aff34';
    function __construct()
    {
        parent::__construct();
      
         $this->lang->load('staff', $this->Settings->user_language);
        
        $this->load->library('email');
          
		/*$this->data['csrf'] = $this->_get_csrf_nonce();*/
        $this->load->model('App_model');
         $this->load->config('ion_auth', TRUE);

        // Load IonAuth MongoDB model if it's set to use MongoDB,
        $this->load->model('auth_model');
         $this->load->library('ion_auth');
        
    }
    public function user_login() //user login and get their details.
    {
      // $token_val=$this->security->get_csrf_hash();
      //echo $token=$this->input->get_post('token_cookie');
    //die();
          $key = $this->input->get_post('api_key');
         $default_key=$this->config->item('api_key');
        //echo "lll";
          if($key == $default_key)
            { 
              //$username=($this->input->get_post('user_name')!='')?$this->input->get_post('user_name'):'';


              $email=($this->input->get_post('email')!='')?$this->input->get_post('email'):'';
              
               $app_password=($this->input->get_post('password')!='')?$this->input->get_post('password'):'';

               $remember = (bool)$this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('email'), $this->input->post('password'), $remember)) {

              


             
               $result=$this->App_model->check_user($email,$app_password);
               
               //die();
                $response=array();
                if(!empty($result))
                {
                   $response['emp_id']=$result->user_id;
                   $response['name']=$result->first_name.' '.$result->last_name;
                   $response['staff_fathername']=$result->staff_fathername;
                   $response['Dob']=$result->dob;
                   $response['address']=$result->address;
                   $response['city']=$result->city;
                   $response['zipcode']=$result->zipcode;
                   $response['personalemail']=$result->personalemail;
                   $response['businessemail']=$result->email;
                   $response['joindate']=$result->joindate;
                   $response['image']=base_url($result->upload);
                   if($this->ion_auth->in_group('owner')) {

                    $response['group']='owner';

                    

                   }
                   else
                   {
                    $response['group']='staff';
                     
                   }
                   $response['success']=1;
                   $response['message']='Login Successfully';
                   $response['password']='Login Successfully';
                  //$response['result']=$result;
                    $response['error']=false;
                 } 




            }
               
          
                
             else
                {
                 $response['success']=0;
                 $response['error']=true;
                 $response['message']='Enter the valid login details';
               }
             }
            else{
               $response['error']=true;
                  $response['success']=0;
                  $response['message']='Please enter valid key.';
                 }  
            
        
          echo json_encode($response);
      }


   /* public function syn_attendance()
    {
       $key = $this->input->get_post('api_key');
        $default_key=$this->config->item('api_key');
          if($key == $default_key)
            { 
                $attendance_details=$this->App_model->get_attendance();
     
                if(!empty($attendance_details))
                  {
                  foreach($attendance_details as $attn_details)
                    {
                        $data['user_atten_details'][]=array(
                        'staff_name'=>$attn_details->first_name.''.$attn_details->last_name,
                        'user_id'=>$attn_details->user_id,
                        'in_time'=>$attn_details->in_time,
                        'out_time'=>$attn_details->out_time,
                        'update_time'=>$attn_details->update_time,
                        'in_out_time'=>$attn_details->in_out_time,
                        'reserve_previous_time'=>$attn_details->reserve_previous_time,
                        'is_late'=>$attn_details->is_late,

                          );
                      }
                }
                else{
                $data['message']="No record found";
                }
              }
              else{
                    $data['error']=1;
                    $data['message']='Please enter valid key.';
                   }  
            
          
          echo json_encode($data);
      }
      */
     public function this_Month() //current month wise attendance fetch for specific user.
     {
       $key = $this->input->get_post('api_key');
        $default_key=$this->config->item('api_key');
     $user_id=($this->input->get_post('user_id')!='')?$this->input->get_post('user_id'):'';
      
     $attn_month=$this->App_model->attn_this_month($user_id);
    
   if($key == $default_key)
      { 
        if(!empty($attn_month))
         {
          foreach($attn_month as $attn_month_new)
            {
                $data['atten_thismonth_details'][]=array(
                'staff_name'=>$attn_month_new->first_name.''.$attn_month_new->last_name,
                'user_id'=>$attn_month_new->user_id,
                'in_time'=>$attn_month_new->in_time,
                'out_time'=>$attn_month_new->out_time,
                'update_time'=>$attn_month_new->update_time,
                'in_out_time'=>$attn_month_new->in_out_time,
                'reserve_previous_time'=>$attn_month_new->reserve_previous_time,
                'is_late'=>$attn_month_new->is_late,

                 );
            }
       }
        else{
              $data['message']="No record found";
              }
        }      
       else{
            $data['success']=0;
            $data['message']='Please enter valid key.';
            }  
            
        echo json_encode($data);
     }
      public function prev_Month() //previous month attendance for specific user.
     {
       $key = $this->input->get_post('api_key');
        $default_key=$this->config->item('api_key');
     $user_id=($this->input->get_post('user_id')!='')?$this->input->get_post('user_id'):'';
      
     $attn_month=$this->App_model->attn_prev_month($user_id);
    
    if($key == $default_key)
      { 
        if(!empty($attn_month))
         {
          foreach($attn_month as $attn_month_new)
            {
                $data['atten_prevmonth_details'][]=array(
                'staff_name'=>$attn_month_new->first_name.''.$attn_month_new->last_name,
                'user_id'=>$attn_month_new->user_id,
                'in_time'=>$attn_month_new->in_time,
                'out_time'=>$attn_month_new->out_time,
                'update_time'=>$attn_month_new->update_time,
                'in_out_time'=>$attn_month_new->in_out_time,
                'reserve_previous_time'=>$attn_month_new->reserve_previous_time,
                'is_late'=>$attn_month_new->is_late,

                 );
            }
       }
        else{
              $data['message']="No record found";
              }
        }      
       else{
            $data['success']=0;
            $data['message']='Please enter valid key.';
            }  
            
        echo json_encode($data);
     }
      public function month_Range() //start and end date wise attendance fetch for specific user.
     {
       $key = $this->input->get_post('api_key');
        $default_key=$this->config->item('api_key');
        $user_id=($this->input->get_post('user_id')!='')?$this->input->get_post('user_id'):'';
        $valid_date_from = str_replace('/', '-', $this->input->post('fromdate'));
        $valid_date_to = str_replace('/', '-', $this->input->post('todate'));
        $fromdate=date('Y-m-d H:i:s', strtotime($valid_date_from." 00:00:00"));
         $todate=date('Y-m-d H:i:s', strtotime($valid_date_to." 23:59:59"));
        $attn_month_range=$this->App_model->attn_month_Range($user_id,$fromdate,$todate);
      if($key == $default_key)
      { 
        if(!empty($attn_month_range))
         {
          foreach($attn_month_range as $month_range)
            {
                $data['atten_month_details'][]=array(
                'staff_name'=>$month_range->first_name.''.$month_range->last_name,
                'user_id'=>$month_range->user_id,
                'in_time'=>$month_range->in_time,
                'out_time'=>$month_range->out_time,
                'update_time'=>$month_range->update_time,
                'in_out_time'=>$month_range->in_out_time,
                'reserve_previous_time'=>$month_range->reserve_previous_time,
                'is_late'=>$month_range->is_late,

                 );
            }
       }
        else{
                $data['message']="No record found";
                }
    }
       else{
            $data['success']=0;
            $data['message']='Please enter valid key.';
            }  
        echo json_encode($data);
     }
            public function add_Leave() // add leave 
              {
                $key = $this->input->get_post('api_key');
                $default_key=$this->config->item('api_key');
                $staff_id=($this->input->get_post('user_id')!='')?$this->input->get_post('user_id'):'';
                $start_date=str_replace('/','-',$this->input->post('start_Date'));
                $start_datenew=  new DateTime($start_date);
                       $end_date=str_replace('/','-',$this->input->post('end_Date'));
                  $end_datenew=  new DateTime($end_date);
                     $include=($this->input->post('include_date')!='')?$this->input->post('include_date'):'';
                    $leave_type=($this->input->post('leave_type')!='')?$this->input->post('leave_type'):'';
                   $subject=($this->input->post('subject')!='')?$this->input->post('subject'):'';
                     $description=($this->input->post('description')!='')?$this->input->post('description'):'';
                   
         if($key == $default_key)
                { 
                $useremail=$this->App_model->getusermail($staff_id,$start_date,$end_date);
                  if(!empty($useremail))
                    {
                      $response['success']=0;
                      $response['message']='Already Leave apply in this Date';
                    }
                  else
                    {
                      if(strtotime($start_date)<strtotime($end_date))
                      {
                        $datenew=date_diff($start_datenew,$end_datenew);
                        $datenew_list=$datenew->format("%d days");
                         $date=array();
                         $date[0]=date('d-M-Y',strtotime($start_date));
                         for($i=0;$i<=$datenew_list;$i++)
                             {
                               $sdate=strtotime("+".$i."day",strtotime($start_date));
                                if($i>=1)
                                 {
                                  $date[$i]=date('d-M-Y',$sdate);
                                  }
                              if($include==1)
                                {
                                 $data_array=array(
                                  'user_id'=>$staff_id,
                                  'leave_date'=>$date[$i],
                                  'leave_type'=>$leave_type,
                                  'payment_type'=>'UnPaid',
                                  'status'=>'Pending',
                                  'subject'=>$subject,
                                  'description'=>strip_tags($description,"<p>")
                                   );
                                        $result=$this->App_model->register($data_array);
                                        $response=array();
                                  if($result==true)
                                   {
                                      $response['success']=1;
                                      $response['message']='leave  Added Successfully';
                                      } 
                                      else
                                      {
                                        $response['success']=0;
                                        $response['message']='Enter the valid Leave details';
                                      }
                                }
                                else
                                  { 
                                    if(date('N', strtotime($date[$i]))!=6 && date('N', strtotime($date[$i]))!=7)
                                      {
                                         $data_array=array(
                                        'user_id'=>$staff_id,
                                        'leave_date'=>$date[$i],
                                        'leave_type'=>$leave_type,
                                        'payment_type'=>'UnPaid',
                                        'status'=>'Pending',
                                         'subject'=>$subject,
                                         'description'=>strip_tags($description,"<p>")
                                        );
                                        $result=$this->App_model->register($data_array);
                                         $response=array();
                                        if($result==true)
                                          {
                                          $response['success']=1;
                                          $response['message']='leave  Added Successfully';
                                         } 
                                       else
                                         {
                                            $response['success']=0;
                                            $response['message']='Enter the valid Leave details';
                                          }
                                        }
                                    }
                              }

                           
                          }
               else
               { 
                if(strtotime($start_date)==strtotime($end_date))
                          {
                           $date_list=date('d-M-Y',strtotime($start_date));
                           //die();
                            $data_array=array(
                            'user_id'=> $staff_id,
                            'leave_date'=>$date_list,
                            'leave_type'=>$leave_type,
                            'payment_type'=>'UnPaid',
                            'status'=>'Pending',
                             'subject'=>$subject,
                            'description'=>strip_tags($description,"<p>")
                            );
                            $result=$this->App_model->registernew($data_array);

                            $response=array();
                            if($result==true)
                            {
                              //$this->sendmail_leave($staff_id,$start_date,$end_date);
                              $response['success']=1;
                              $response['message']='leave  Added Successfully';
                              //$response['result']=$result;
                             } 
                           else
                              {
                               $response['success']=0;
                               $response['message']='Enter the valid Leave details';
                             }
                          }
                          
                       else{
                           $response['success']=0;
                           $response['message']='start date not less than end date';

                          }
                         
                         
                      }
                      if( $response['success']==1)
                      {

                        $this->sendmail_leave($staff_id,$start_date,$end_date);
                      }

                   }
                 }
                
                else{
                      $response['success']=0;
                      $response['message']='Please enter valid key.';
                    }  
                echo json_encode($response);
                

            }
    public function sendmail_leave($staff_id,$start_date,$end_date)
          {
           $admin_mail_id=$this->config->item('admin_email');
           $useremail=$this->App_model->getusermail($staff_id,$start_date,$end_date);
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
                        'status'=>"pending",
                        'site_link' =>base_url(),
                        'site_name' => $this->Settings->site_name,
                        'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                    );
                    
   $msg=file_get_contents('./themes/' . $this->theme .'email_templates/leave_email.html');
   $messagenew = $this->parser->parse_string($msg, $parse_data,true);

  $subject = "[ leave_email ] ".$useremail[0]->first_name . ' ' .$useremail[0]->last_name;
  
            $this->sma->send_email($admin_mail_id,$subject,$messagenew);
                     
            }
          public function getleaveresult()
         {
          $key = $this->input->get_post('api_key');
          $default_key=$this->config->item('api_key');
        $staff_id=($this->input->post('staffid')!='')?$this->input->post('staffid'):'';
       $leave_years=($this->input->post('leaveyears')!='')?$this->input->post('leaveyears'):'';
       $status_result=($this->input->post('status')!='')?$this->input->post('status'):'';
       $status_array = explode(',', $status_result);
       $approve_result = count($status_array);
       $data=array();
    if($key == $default_key)
      { 
             if($approve_result==1)
             {
              $data=$this->App_model->getlist($staff_id,$leave_years,$status_array);
              
             // print_r($data);
              //die();
             }
             else if($approve_result==2)
             {
              $data=$this->App_model->getlist($staff_id,$leave_years,$status_array);
               
             }
             else
                 {
                  $data=$this->App_model->getlist($staff_id,$leave_years);
                   
                 }
                 if(!empty($data))
                 {
                  $result['leave_list']=$data;
                 }
                 else
                 {
                  $result['success']=0;
                  $result['message']='No record found';
                 }
            }
            else{
                  $result['success']=0;
                  $result['message']='Please enter valid key.';
                }  
                 
       echo json_encode($result);
    } 
    public function get_leave() // get all leave for specifice user.
    {
      $key=$this->input->post('api_key');
      $default_key=$this->config->item('api_key');
      $staff_id=($this->input->post('user_id')!='')?$this->input->post('user_id'):'';
      $leaveyear=($this->input->post('leave_year')!='')?$this->input->post('leave_year'):'';
      $user_info=$this->App_model->get_user_info($staff_id);
     if($default_key==$key)
     {
      $leave_datelist=$this->App_model->get_Leave_Details($staff_id,$leaveyear);
      //print_r($leave_datelist);
      //die();
      $response=array();
        if(!empty($leave_datelist))
         {
              $response['success']=1;
              $response['color']="#F7DC6F";
          $response['message']='Get all leave details';
              $response['leave_details']=$leave_datelist;
             
            
           
         }
         else
         {
          $response['success']=0;
          $response['message']='No record found';
         }
     }
     else
     {
        $response['success']=0;
        $response['message']='Please enter the valid key';
     }
     echo json_encode($response);


    }
     public function get_leavedetails() //get leave list fetch for specific user in 6 months,1 year and 2years respectively.
    {
      $key=$this->input->post('api_key');
      $default_key=$this->config->item('api_key');
      $staff_id=($this->input->post('user_id')!='')?$this->input->post('user_id'):'';
      $leave_duration=($this->input->post('leave_duration')!='')?$this->input->post('leave_duration'):'';
      $leave_type=($this->input->post('leave_type')!='')?$this->input->post('leave_type'):'';
      $status=($this->input->post('status')!='')?$this->input->post('status'):'';
      $user_info=$this->App_model->get_user_info($staff_id);
       if($default_key==$key)
        {
            if($staff_id!=''&&  $leave_duration!='' && $leave_type!='' && $status!='')
            {
              $leave_datelist=$this->App_model->get_Leaveinfo($staff_id,$leave_duration,$leave_type,$status);
               // print_r($leave_datelist);
               // die();
                  $response=array();
                    if(!empty($leave_datelist))
                     {
                        if($leave_duration==6)
                          {
                            $current_date=now();
                            $newdate=date('d-M-Y');
                            $effective_date= date('d-M-Y',strtotime('-6 months',$current_date));
                           //die();
                            $data=array();
                            foreach($leave_datelist as $leave_list)
                            {
                              $leave_date = date('d-m-Y', strtotime($leave_list->leave_date));
                              if(strtotime($leave_date)<=strtotime($newdate) && strtotime($leave_date)>=strtotime($effective_date)){
                                  $data[] = $leave_list;
                              }
                              //echo date('d-m-Y', strtotime($leave_list->leave_date));
                              //$leave_data=$leave_list->leave_date;                          
                            }
                         //die();
                        } 
                      
                      if($leave_duration==12)
                          {
                            $current_date=now();
                            $newdate=date('d-M-Y');
                            $effective_date= date('d-M-Y',strtotime('-12 months',$current_date));
                           //die();
                            $data=array();
                            foreach($leave_datelist as $leave_list)
                            {
                              $leave_date = date('d-m-Y', strtotime($leave_list->leave_date));
                              if(strtotime($leave_date)<=strtotime($newdate) && strtotime($leave_date)>=strtotime($effective_date)){
                                  $data[] = $leave_list;
                              }
                              //echo date('d-m-Y', strtotime($leave_list->leave_date));
                              //$leave_data=$leave_list->leave_date;                          
                            }
                         //die();
                        } 
                         if($leave_duration==24)
                          {
                            $current_date=now();
                            $newdate=date('d-M-Y');
                            $effective_date= date('d-M-Y',strtotime('-24 months',$current_date));
                           //die();
                            $data=array();
                            foreach($leave_datelist as $leave_list)
                            {
                              $leave_date = date('d-m-Y', strtotime($leave_list->leave_date));
                              if(strtotime($leave_date)<=strtotime($newdate) && strtotime($leave_date)>=strtotime($effective_date)){
                                  $data[] = $leave_list;
                              }
                              //echo date('d-m-Y', strtotime($leave_list->leave_date));
                              //$leave_data=$leave_list->leave_date;                          
                            }
                         //die();
                        } 
                          $response['success']=1;
                          //$response['color']="#F7DC6F";
                          $response['message']='Get leave details';
                          $response['leave_details']=$data;
                         }
                     else
                        {
                          $response['success']=0;
                          $response['message']='No record found';
                         }
                  }
           
                  else
                  {
                    $response['success']=0;
                    $response['message']='Please fill up the field';
                  }
             }
            else
              {
                 $response['success']=0;
                 $response['message']='Please enter the valid key';
                }
            echo json_encode($response);
   }

  public function getleavetype()
  {
  
       $casula_leave=10;
       $medical_leave=5;
       $maternity_leave=5;
       $key=$this->input->post('api_key');
       $default_key=$this->config->item('api_key');
       $staff_id=($this->input->post('user_id')!='')?$this->input->post('user_id'):'';
       $leave_type=($this->input->post('leave_type')!='')?$this->input->post('leave_type'):'';
   
       $response=array();
     if($key==$default_key)
      {
        if($staff_id!='' &&  $leave_type!='' )
        {
           $leavetype_list=$this->App_model->getleavetypelist($staff_id,$leave_type);
               if($leave_type=="CL")
               {
                 $total_CL= $casula_leave - $leavetype_list;
                 $response['Remaining_CL']=$total_CL;
                }
                if($leave_type=="ML")
                {
                 $total_ML=$medical_leave - $leavetype_list;
                $response['Remaining_ML']=$total_ML;
                 }
               if($leave_type=="MAL")
               {
               $total_MAL=$maternity_leave - $leavetype_list;
                $response['Remaining_MAL']=$total_MAL;
               }
                else{
                     $response['success']=0;
                     $rseponse['message']="you have not apply any leave ";
                    }
          }
          else {
                  $response['success']=0;
                  $rseponse['message']=" Please fill up the field";
              }
        }
          else
            {
               $response['success']=0;
               $response['message']='Please enter the valid key';
            }
            echo json_encode($response);
      }

  public function dailystaffattendancereport()
  {

    $key= $this->input->get_post('api_key');
    $default_key=$this->config->item('api_key');
    $attendance_result=$this->App_model->match_attendancedate();
   /* print_r($attendance_result);
    die();*/
    $todays_date=date('Y-m-d');
     $data=array();
    $attendance_array=array();
    $attendance_newarray=array();
    
   /* if($key==$default_key)
    {*/
       if(!empty($attendance_result))
   {
      foreach($attendance_result as $data)
       {
        if($data->active==1){
          if(strtotime($data->date_entry)==strtotime($todays_date))
            {
          
          if($data->is_late==1)
            {
                $attendance_array['u_name']=$data->first_name.' '.$data->last_name;
                $attendance_array['in_time']=$data->in_time;
                 $attendance_array['out_time']=$data->out_time;
                $attendance_array['attendance']="Late";
                $attendance_array['phone_number']=$data->phone;
                $attendance_array['status']='Active';
                $attendance_array['success']=0;
                $attendance_newarray[]=$attendance_array;
              }
              else{
                   $attendance_array['u_name']=$data->first_name.' '.$data->last_name;
                   $attendance_array['attendance']="Present";
                   $attendance_array['phone_number']=$data->phone;
                    $attendance_array['out_time']=$data->out_time;
                   $attendance_array['status']='Active';
                   $attendance_array['in_time']=$data->in_time;
                   $attendance_array['success']=1;
                   $attendance_newarray[]=$attendance_array;
                  }
           }
           else if(strtotime($data->date_entry)=='')
           {
            $attendance_array['u_name']=$data->first_name.' '.$data->last_name;
                   $attendance_array['attendance']="absent";
                   $attendance_array['status']='active';
                   $attendance_array['success']=2;
                   $attendance_array['phone_number']=$data->phone;
                   $attendance_newarray[]=$attendance_array;
           }
         }
             else if($data->active==0)
              {

               $attendance_array['u_name']=$data->first_name.' '.$data->last_name;
               /*$attendance_array['success']=2;*/
               $attendance_array['success']=3;
               $attendance_array['status']='Inactive';
               //$attendance_newarray[]=$attendance_array;
             }
           else{
           $attendance_array['message']="No Attendance Found";
           $attendance_array['error']=1;
           $attendance_newarray[]=$attendance_array;
          }
         }
       }
      else
         {
           $attendance_array['message']="No Record Found";
           $attendance_array['error']=1;
           $attendance_newarray[]=$attendance_array;
         }
   /* }*/
  /*  else{
         $attendance_newarray['success']=0;
         $attendance_newarray['message']='Please enter the valid key';
    }*/
  
         echo json_encode($attendance_newarray);
  }



    /*public function attendance_sheet()
    {
        $key= $this->input->get_post('api_key');
        $default_key=$this->config->item('api_key');

        $id=$this->input->post('user_id');
        $month=$this->input->post('attn_month');
       $year=$this->input->post('attn_year');
        if($default_key==$key)
        {
         
         $staff_sheet=$this->App_model->get_attendancesheet($id,$month,$year);
        //print_r($staff_sheet);
          //die();
         foreach ($staff_sheet as $row) 
         {
           $Attn_date=$row->date_entry();
         }
         if($month==date('M',strtotime($Attn_date)) && $year==date('Y',strtotime($Attn_date)))
         {
          $total_days=date('t',strtotime($month));

            for($i=$month;$i<=$total_days;$i++)
            {
              echo $month[$i];
              die(); 
            }
         }
         
        }
        else
        {
          $response['success']=0;
          $response['message']='Please enter valid key';
        }
     
    }*/

  }