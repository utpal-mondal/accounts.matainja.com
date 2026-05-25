<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Attendance extends MY_Controller
{
	  function __construct()
    {
        parent::__construct();
        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
		
          $this->lang->load('attendance', $this->Settings->user_language);
          $this->load->library(array('form_validation','ion_auth'));
		      $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
          $this->load->model(array('attendance_model','staff_model'));
		// $this->load->library(array('sma','pdf'));
    	}
    		 public function index() 
    			{
		    // $this->sma->checkPermissions();
					$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			 		$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('attendance')));
        			$meta = array('page_title' => lang('attendance'), 'bc' => $bc);
        			$this->page_construct('attendance/assignrfid', $meta, $this->data);
        		}
            public function add_attendance()
            {
               
        $this->form_validation->set_rules('Staff_id',lang('staff'),'trim|required');
        $this->form_validation->set_rules('in_time',lang('in_time'),'trim|required');
        $this->form_validation->set_rules('out_time',lang('out_time'),'trim|required');
      if ($this->form_validation->run() == true)
          {
              $staff_id=$this->input->post('Staff_id');
             $staffname=$this->attendance_model->getuser($staff_id)->row();

              $attn_rfid=$this->attendance_model->getrfid($staff_id)->row();
              if(!empty($attn_rfid))
              {
                $atten_rfid=$attn_rfid->attendance_id;
              }
              else
              {
                 $atten_rfid='';
              }
          
               $in_time=str_replace('/','-',$this->input->post('in_time'));
              $in_date=date('Y-m-d',strtotime($in_time));
              $intime=date('Y-m-d H:i:s',strtotime($in_time));
              $in_timenew=  new DateTime($in_time);
              $out_time=str_replace('/','-',$this->input->post('out_time'));
              $outtime=date('Y-m-d H:i:s',strtotime($out_time));
              $out_date=date('Y-m-d',strtotime($out_time));
              $out_timenew=  new DateTime($out_time);
              $dteDiff=$in_timenew->diff($out_timenew);
             $work_hournew=$dteDiff->format("%H:%I:%S");
             $late=$this->input->post('late');
            $device_type=$this->input->post('device_type');

          $dataAttendance=array(
                            'user_id'=>$staff_id,
                            'date_entry'=>$in_date,
                            'in_time'=>$intime,
                             'out_time'=>$outtime,
                             'update_time'=>date('Y-m-d H:i:s'),
                             'input_time'=>$outtime,
                             'in_out_time'=>0,
                             'work_hours'=>$work_hournew,
                             'attendence_count'=>0,
                             'reserve_previous_time'=>$outtime,
                             'is_late'=>$late
                           );
           
                $dataStaffAttendance=array(
                              'staff_id'=>$staff_id,
                              'Staff_name'=>$staffname->first_name.' '.$staffname->last_name,
                              'device_type'=>$device_type,
                               'attendance_time'=>$intime,
                               'attendance_id'=>isset($atten_rfid)?$atten_rfid:'',
                               'update_time'=>date('Y-m-d H:i:s'),
                               'note'=>'Thank you'
                           );
               //print_r($dataStaffAttendance);
              //die();
            $user_search=$this->attendance_model->check_attn_date($staff_id,$in_date,$out_date);
            
              if(!empty($user_search))
                 {

                $this->attendance_model->update_Attendance($user_search[0]->id,$user_search[0]->user_id,$dataAttendance,$dataStaffAttendance);
                $this->session->set_flashdata('message', 'Update Successfully');
                redirect('attendance/add_attendance'); 
                 }
             else{
                   $this->attendance_model->attendance_add($dataAttendance,$dataStaffAttendance);
                    $this->session->set_flashdata('message', 'Attendance Added Successfully');
                    redirect('attendance/add_attendance');
                }
        }
          else
           {
            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $bc = array(array('link' =>base_url() , 'page' => lang('home')),array('link' => '#','page' => lang('add_attendance')));
            $meta = array('page_title' => lang('add_attendance'), 'bc' => $bc);
            $this->page_construct('attendance/add_attendance', $meta, $this->data);
          }     
     
            }

        	public function getassignrf() //Get assignrfidlist
		  		{
			  /* if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            $this->sma->md();
        		   }*/
               // $this->sma->checkPermissions();

               //02-07-2018 change//
        		/*$this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('staff_attendance').".id,staff_name,".$this->db->dbprefix('attendence_rf_record').".id,rfid,time_entry")
            ->from("staff_attendance")
             ->join('attendence_rf_record','attendence_rf_record.rfid=staff_attendance.attendance_id','right');*/
             $this->load->library('datatables');
			       $this->datatables
            ->select($this->db->dbprefix('users').".id,CONCAT(first_name,' ',last_name) as staff_name,".$this->db->dbprefix('attendance_rfid_new').".id,rfid")
            ->from("attendance_rfid_new")
             ->join('sma_staff_info','sma_staff_info.attendance_id=attendance_rfid_new.rfid','left')
             ->join('users','users.id=sma_staff_info.user_id')
             ->where('attendance_rfid_new.status',1);
    
        echo $this->datatables->generate();
    }
   public function raw_attendance_record() //Get raw attendance record 
    {
       //$this->sma->checkPermissions();
       $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
                    $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => 'attendance/raw_attendance_record', 'page' => lang('attendance_raw_record')));
                    $meta = array('page_title' => lang('attendance_raw_record'), 'bc' => $bc);
                $this->page_construct('attendance/rawattendancerecord', $meta, $this->data); 
    }
        public function getrawattendancerecord() //Get
                {



              /* if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            $this->sma->md();
                   }*/
                   
                $this->load->library('datatables');
        $this->datatables
            ->select("id,staff_id,staff_name,device_type,attendance_time,attendance_id,update_time")
            ->from("sma_staff_attendance");
            
    
        echo $this->datatables->generate();
    }
      function deleteassignrid_actions()    //delete attendance rfid record
      {
          /*  if (!$this->Owner)
              {
                 $this->session->set_flashdata('warning', lang('access_denied'));
                redirect($_SERVER["HTTP_REFERER"]);
              }*/
               //$this->sma->checkPermissions();
                 $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
                 if ($this->form_validation->run() == true)
                  {
                     if (!empty($_POST['val'])) 
                     {
                         if ($this->input->post('form_action') == 'delete')
                          {
                            foreach ($_POST['val'] as $id)
                             {
                               $this->attendance_model->delete_assignrfid($id);
                            }
                            $this->session->set_flashdata('message', lang("delete_holiday"));
                              redirect($_SERVER["HTTP_REFERER"]);
                          }
                      }
                       else 
                        {
                        $this->session->set_flashdata('error', lang("no_usernew_selected"));
                        redirect($_SERVER["HTTP_REFERER"]);
                        }

                 }
          else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
      }
 function del_atten_rcd_actions()         //delete attendance raw record
      {
           /* if (!$this->Owner)
              {
                 $this->session->set_flashdata('warning', lang('access_denied'));
                redirect($_SERVER["HTTP_REFERER"]);
              }*/
               //$this->sma->checkPermissions();
                 $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
                 if ($this->form_validation->run() == true)
                  {
                     if (!empty($_POST['val'])) 
                     {
                         if ($this->input->post('form_action') == 'delete')
                          {
                            foreach ($_POST['val'] as $id)
                             {
                               $this->attendance_model->del_atten_record($id);
                            }
                              $this->session->set_flashdata('message', lang("delete_holidaynew"));
                              redirect($_SERVER["HTTP_REFERER"]);
                          }
                    }
                    else 
                     {
                        $this->session->set_flashdata('error', lang("no_user_selected"));
                        redirect($_SERVER["HTTP_REFERER"]);
                      }

        }
          else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
      }

       function deletereport_actions()         //delete attendance raw record
      {
        //print_r($_POST['val']);
        //die();
          /*  if (!$this->Owner)
              {
                 $this->session->set_flashdata('warning', lang('access_denied'));
                redirect($_SERVER["HTTP_REFERER"]);
              }*/
               //$this->sma->checkPermissions();
                 $this->form_validation->set_rules('form_action', lang("form_action"), 'required');
                 if ($this->form_validation->run() == true)
                  {
                     if (!empty($_POST['val'])) 
                     {
                         if ($this->input->post('form_action') == 'delete')
                          {
                            foreach ($_POST['val'] as $id)
                             {
                               $this->attendance_model->del_atten_report($id);
                            }
                              $this->session->set_flashdata('message', lang("delete_report"));
                              redirect($_SERVER["HTTP_REFERER"]);
                          }
                    }
                    else 
                     {
                        $this->session->set_flashdata('error', lang("no_user_selected"));
                        redirect($_SERVER["HTTP_REFERER"]);
                      }

        }
          else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        }
      }
      function analysis()      //attendance analysis
      {

           //$this->sma->checkPermissions();
          $this->data['staff_info']=$this->staff_model->getstaff();
           $this->data['cal_lang'] = $this->get_cal_lang();
           $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
           //print_r($this->data);
           //die();
        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('analysis_attendance')));
        $meta = array('page_title' => lang('analysis_attendance'), 'bc' => $bc);
        $this->load->view($this->theme . 'attendance/analysis_attendance',$this->data);
        //$this->page_construct('attendance/analysis_attendance', $meta, $this->data);
      }
       

       public function get_cal_lang() {
        switch ($this->Settings->user_language) {
            case 'arabic':
                $cal_lang = 'ar-ma';
                break;
            case 'spanish':
            $cal_lang = 'es';
            break;
            case 'german':
            $cal_lang = 'de';
            break;
            case 'thai':
            $cal_lang = 'th';
            break;
            case 'vietnamese':
            $cal_lang = 'vi';
            break;
            case 'italian':
            $cal_lang = 'it';
            break;
            case 'simplified-chinese':
            $cal_lang = 'zh-tw';
            break;
            case 'traditional-chinese':
            $cal_lang = 'zh-cn';
            break;
            case 'turkish':
            $cal_lang = 'tr';
            break;
            default:
            $cal_lang = 'en';
            break;
        }
        return $cal_lang;
    }


    function attendance_report()
    {
       //$this->sma->checkPermissions();
      $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
      $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => 'attendance/attendance_report', 'page' => lang('attendance_report')));
          $meta = array('page_title' => lang('attendance_report'), 'bc' => $bc);
       $this->page_construct('attendance/attendance_report', $meta, $this->data); 

    }
    function getattendancereport()
    {
      /* if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            $this->sma->md();
               }*/
               // $this->sma->checkPermissions();
                //$edit_link = anchor('attendance/edit/$1', '<i class="fa fa-edit"></i>' . lang('edit_attendance'), 'class="sledit"');
               $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'. lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          <li><a href="javascript:void(0);" class="sledit edit_user" data-user_id="$1"><i class="fa fa-edit"></i>Edit</a></li>
        </div></div>';
            $this->load->library('datatables');
            $this->datatables
          ->select($this->db->dbprefix('users').".id as id, CONCAT(".$this->db->dbprefix('users').".first_name, ' ' ,".$this->db->dbprefix('users').".last_name) as name, ".$this->db->dbprefix('attendence').".id,user_id,in_time,out_time,update_time,in_out_time,work_hours,reserve_previous_time,is_late")
           ->from("users")
           ->join('attendence','attendence.user_id=users.id')
           ->add_column("Actions", $action, "id");
          
           echo $this->datatables->generate();
          
    }

     function getstaffattendance()
      {

            $this->load->library('datatables');
            $staff_name=$this->input->post('staff_name');
            $valid_date_from = str_replace('/', '-', $this->input->post('fromdate'));
            $valid_date_to = str_replace('/', '-', $this->input->post('todate'));
            $fromdate=date('Y-m-d H:i:s', strtotime($valid_date_from." 00:00:00"));
            $todate=date('Y-m-d H:i:s', strtotime($valid_date_to." 23:59:59"));
           
            $this->datatables->select("id, staff_id, staff_name, device_type, attendance_time, attendance_id, update_time")
            //die('error3');
           ->from("staff_attendance")
           ->where(array("staff_id"=>$staff_name))
           ->where(array("attendance_time >="=>$fromdate))
           ->where(array("attendance_time <="=>$todate));
           echo $this->datatables->generate();
      }
       function getattenresult()
      {
         $this->load->library('datatables');
       $staff_name=$this->input->post('stf_name');

     
         $this->datatables->select("id, staff_id, staff_name, device_type, attendance_time, attendance_id, update_time")
            //die('error3');
           ->from("staff_attendance")
           //->where(array("staff_name"=>$staff_name));
           ->like('staff_name',$staff_name);
           echo $this->datatables->generate();
       
           
      }
       function getattendancesearch()
      {
          
       $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'. lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          <li><a href="javascript:void(0);" class="sledit edit_user" data-user_id="$1"><i class="fa fa-edit"></i>Edit</a></li>
        </div></div>';
         $this->load->library('datatables');

        $staff_name=$this->input->post('staff_name');
         $valid_date_from = str_replace('/', '-', $this->input->post('fromdate'));
            $valid_date_to = str_replace('/', '-', $this->input->post('todate'));
         $fromdate=date('Y-m-d H:i:s', strtotime($valid_date_from." 00:00:00"));
            $todate=date('Y-m-d H:i:s', strtotime($valid_date_to." 23:59:59"));
        //  $this->db->save_queries=TRUE;
          $this->datatables->select($this->db->dbprefix('users').".id as id,CONCAT(".$this->db->dbprefix('users').".first_name, ' ' ,".$this->db->dbprefix('users').".last_name) as name, ".$this->db->dbprefix('attendence').".id,user_id,in_time,out_time,update_time,in_out_time,work_hours,reserve_previous_time,is_late")
            //die('error3');
           ->from("users")
           ->join('attendence','attendence.user_id=users.id')
           ->where(array("user_id"=>$staff_name))
           ->where(array("in_time >="=>$fromdate))
           ->where(array("in_time <="=>$todate))
          
             ->add_column("Actions",$action,"id");
           echo $this->datatables->generate();
           
      }


      function getmonthnresult()
      {

            $this->load->library('datatables');
            $staff_name=$this->input->post('stf_id');
            $d_from = new DateTime('first day of this month');
            $fromdate = $d_from->format('Y-m-d');
            //echo $d->format('jS, F Y');

            $date = new DateTime('now');
            $date->modify('last day of this month');
            $todate =$date->format('Y-m-d');
           // echo $date->format('Y-m-d');
            //$fromdate=$this->input->post('fromdate');
            //$todate=$this->input->post('todate');
           
            $this->datatables->select("id, staff_id, staff_name, device_type, attendance_time, attendance_id, update_time")
            //die('error3');
           ->from("staff_attendance")
           ->where(array("staff_id"=>$staff_name))
           ->where(array("attendance_time >="=>$fromdate))
           ->where(array("attendance_time <="=>$todate));
           echo $this->datatables->generate();
      }
        function getprev_monthnresult()
      {

            $this->load->library('datatables');
            $staff_name=$this->input->post('stf_id');
            //$d_from = new DateTime('first day of this month');
            //$fromdate = $d_from->format('Y-m-d');
            //echo $d->format('jS, F Y');
            $last_month=date('m', strtotime('last month'));
           // $date = new DateTime('now');
           // $date->modify('last day of this month');
            //$todate =$date->format('Y-m-d');
           // echo $date->format('Y-m-d');
            //$fromdate=$this->input->post('fromdate');
            //$todate=$this->input->post('todate');
           
            $this->datatables->select("id, staff_id, staff_name, device_type, attendance_time, attendance_id, update_time")
            //die('error3');
           ->from("staff_attendance")
           ->where(array("staff_id"=>$staff_name))
           ->where("month(attendance_time)",$last_month);
           //->where(array("attendance_time <="=>$todate));
           echo $this->datatables->generate();
      }
        function getmonthnreport()
      {
       
      $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'. lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          <li><a href="javascript:void(0);" class="sledit edit_user" data-user_id="$1"><i class="fa fa-edit"></i>Edit</a></li>
        </div></div>';
            $this->load->library('datatables');
            $staff_name=$this->input->post('stf_id');
            $d_from = new DateTime('first day of this month');
            $fromdate = $d_from->format('Y-m-d');
            //echo $d->format('jS, F Y');

            $date = new DateTime('now');
            $date->modify('last day of this month');
            $todate =$date->format('Y-m-d');
           // echo $date->format('Y-m-d');
            //$fromdate=$this->input->post('fromdate');
            //$todate=$this->input->post('todate');
           
          $this->datatables->select($this->db->dbprefix('users').".id as id,CONCAT(".$this->db->dbprefix('users').".first_name, ' ' ,".$this->db->dbprefix('users').".last_name) as name,".$this->db->dbprefix('attendence').".id,user_id,in_time,out_time,update_time,in_out_time,work_hours,reserve_previous_time,is_late")
            //die('error3');
           ->from("users")
           ->join('attendence','attendence.user_id=users.id')
           ->where(array("user_id"=>$staff_name))
            ->where(array("in_time >="=>$fromdate))
           ->where(array("in_time <="=>$todate))
              ->add_column("Actions", $action, "id");
          
           echo $this->datatables->generate();
      }
       function getprev_monthnreport()
      {
       
      $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'. lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          <li><a href="javascript:void(0);" class="sledit edit_user" data-user_id="$1"><i class="fa fa-edit"></i>Edit</a></li>
        </div></div>';
            $this->load->library('datatables');
            $staff_name=$this->input->post('stf_id');
            $last_month=date('m', strtotime('last month'));
            // die();
          $this->datatables->select($this->db->dbprefix('users').".id as id,CONCAT(".$this->db->dbprefix('users').".first_name, ' ' ,".$this->db->dbprefix('users').".last_name) as name,".$this->db->dbprefix('attendence').".id,user_id,in_time,out_time,update_time,in_out_time,work_hours,reserve_previous_time,is_late")
            //die('error3');
           ->from("users")
           ->join('attendence','attendence.user_id=users.id')
           ->where(array("user_id"=>$staff_name))
            ->where("month(in_time)",$last_month)
            ->add_column("Actions", $action, "id");
          
           echo $this->datatables->generate();
      }
      public function edit_time($id = NULL)
      {
        $attendance_rawrecord=$this->attendance_model->getupdate_time($id)->row();
        $this->data['attenreport'] = $attendance_rawrecord;
         $this->load->view($this->theme.'attendance/view',$this->data);
     

      }
       public function update_Modal()
      {
        $id=$this->input->post('id');
        $user_id=$this->input->post('user_id');
        $in_time=str_replace('/','-',$this->input->post('in_time'));
        $in_timenew=new DateTime($in_time);

       $out_time_new=str_replace('/','-',$this->input->post('outtime'));
        $out_time = date('Y-m-d H:i:s',strtotime($out_time_new));
        $out_timenew = new DateTime($out_time);
        $dteDiff=$in_timenew->diff($out_timenew);
         $work_hournew=$dteDiff->format("%H:%I:%S");
         //$work_hour=strtotime($work_hournew);
         // die();
       //  if(strtotime($out_time_new) > strtotime($in_time)){
          $update_times=$this->attendance_model->update_ModalTime($id,$out_time,$work_hournew);
         if(!empty($update_times))
         {
           //$this->getSearchById('$update_times');
       //  $data['otputtime']=$update_times;
       //  $data['workhour']=date->dif('')
            $data['success']=1;
            $data['message']='successfully updated';
         }
         else
         {
          $data['error']=1;
           $data['message']='Not updated';
         }  
        
         /*else{
          $data['error']=1;
           $data['message']='Please enter valid out time.';
         }*/
         
         echo json_encode($data);

      }
      function getSearchbyId()
      {
        $id=$this->input->post('userid');
         $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'. lang('actions') . ' <span class="caret"></span></button>
        <ul class="dropdown-menu pull-right" role="menu">
          <li><a href="javascript:void(0);" class="sledit edit_user" data-user_id="$1"><i class="fa fa-edit"></i>Edit</a></li>
        </div></div>';
            $this->load->library('datatables');
            $this->datatables
          ->select($this->db->dbprefix('users').".id as id, CONCAT(".$this->db->dbprefix('users').".first_name, ' ' ,".$this->db->dbprefix('users').".last_name) as name, ".$this->db->dbprefix('attendence').".id,user_id,in_time,out_time,update_time,in_out_time,work_hours,reserve_previous_time,is_late")
           ->from("users")
           ->join('attendence','attendence.user_id=users.id')
           ->where('attendence.user_id',$id)
           ->add_column("Actions",$action,"id");
          
           echo $this->datatables->generate();

      }
      public function check_user()
      {
          $uid=$this->input->post('id');
          $user_details=$this->attendance_model->get_info($uid);
          //print_r($user_details);
          //die();
          $response=array();
          foreach($user_details as $username)
          {
          $response['name']= $username->first_name.' '.$username->last_name;
          }
          echo json_encode($response);

      }
      public function get_attendance()
      {
       
                $uid=$this->input->get_post('uid');
                $strt_date=$this->input->get_post('start');
                $end= $this->input->get_post('end');
                $start_date=new DateTime($strt_date);
                $end_date=new DateTime($end);
              //$work_form_home=$this->attendance_model->work_home_details($uid);
               $holiday_list=$this->attendance_model->holiday_list();
                $leave_list=$this->attendance_model->leave_list($uid);
                $data=$this->attendance_model->user_Get_Attdance($uid,$strt_date,$end);
              //  $work_home_details=$this->attendance_model->work_form_home($uid);
                $date=array();
                $exact_date=new DateTime('2018-02-15');
                $response=array();
                $match_date = array();
                $work_hours = array();
                $device_type=array();
                $is_late=array();
                $leave_datenew=array();
                $today = date('Y-m-d');
                $holiday_date=array();
          for($i = $exact_date, $j=0; $i <= $end_date; $i->modify('+1 day'),$j++)
          {
                  $s_date= $i->format("Y-m-d");
                if(strtotime($s_date) <=strtotime($today))
                   {
                
                      if(!empty($data))
                      {
                        foreach($data as $attn_data)
                        {
                          $match_date[] =$attn_data->date_entry;
                          $work_hours[]=$attn_data->work_hours;
                          $intime=new DateTime($attn_data->in_time);
                          $time[]=$intime->format('H:i');
                          $is_late[]=$attn_data->is_late;
                        }
                      }
                       if(!empty($work_home_details))
                       {
                          foreach($work_home_details as $work_details)
                          {
                            $work_date[] =$work_details->date_entry;
                            $work_hours[]=$work_details->work_hours;
                            $device_type[]=$work_details->device_type;
                          }
                      }
                       if(!empty($holiday_list))
                       {
                          foreach($holiday_list as $holiday_listnew)
                          {
                            $holiday_date[] =$holiday_listnew->holiday_date;
                            $holiday_title[]=$holiday_listnew->title;
                          }
                      }
                
                      if(!empty($leave_list))
                      {
                         foreach($leave_list as $leavedate_list)
                         {
                           $leave_datenew[] = date('Y-m-d',strtotime($leavedate_list->leave_date));
                            $leave_type[]=$leavedate_list->leave_type;
                           }
                       }
                if(date('N',strtotime($s_date))!=7)
                {
                        if(!empty($match_date) && in_array($s_date,$match_date))
                        {
                             $key1=array_search($s_date, $match_date);
                             if(!empty($work_date) && in_array($s_date,$work_date))
                             {
                                $key2=array_search($s_date,$work_date);
                             }
                           
                                 if(isset($key2) && $device_type[$key2]=='WFH')
                                {
                                  $response[$j]['title']='WFH'.'--'.date('H:i',strtotime($work_hours[$key2]))."   (".$time[$key2].")";
                                  $response[$j]['start']=$s_date;
                                  $response[$j]['color']='#ABEBC6';
                                }
                                  else if($is_late[$key1]==1)
                                 {
                              $response[$j]['title']='L'.'--'.date('H:i',strtotime($work_hours[$key1]))."   (".$time[$key1].")";
                              $response[$j]['start']=$s_date;
                              $response[$j]['color']='blue'; 
                                 }
                                 else
                                 {
                              $response[$j]['title']='P'.'--'.date('H:i',strtotime($work_hours[$key1]))."   (".$time[$key1].")";
                              $response[$j]['start']=$s_date;
                              $response[$j]['color']='green'; 
                              } 
                       
                         }
                         elseif(!empty($holiday_date) && in_array($s_date,$holiday_date))
                          {
                            $key=array_search($s_date, $holiday_date);
                          
                              $response[$j]['title']=$holiday_title[$key].'-'.'Holiday';
                              $response[$j]['start']=$s_date;
                              $response[$j]['color']='pink';
                          }
                         elseif(!empty($leave_datenew) && in_array($s_date,$leave_datenew))
                          {
                            $key=array_search($s_date,$leave_datenew);
                          $response[$j]['title']=$leave_type[$key];
                          $response[$j]['start']=$s_date;
                          $response[$j]['color']='orange';
                          }
                         else
                            {
                                $response[$j]['title']='Absent';
                                $response[$j]['start']=$s_date;
                                $response[$j]['color']='red';
                            }
                 }
                else
                {
                     $response[$j]['start']=$s_date;
                    $response[$j]['color']='yellow';
                }
              }
           
            }
         
        echo json_encode($response);

      }
      function usersend_mail()  /*Attendance sheet email to user and admin*/
      { 
        if($this->input->post('id')!='')
         {
          //$admin_mail_id=$this->config->item('admin_email');
          $staff_id=$this->input->post('id');
          $str_date=$this->input->post('start_date');
          $end_date=$this->input->post('end_date');
          $startdate=new DateTime($str_date);
          $enddate=new DateTime($end_date);
          $data=array();
          $user_info=$this->attendance_model->get_info($staff_id);/*get user info result*/
          $this->data['u_name']=$this->attendance_model->getuser($staff_id)->row();/*get username for specific user*/ 
        
          $check_leave=$this->attendance_model->getleavelist($staff_id); /* Get all leave date  for specific user*/
          foreach($check_leave as $check_leavedate) 
          {
           $check_datelist[]=date('Y-m-d',strtotime($check_leavedate->leave_date));
          }
        /*  print_r($check_datelist);
          die();*/
         for($i=$startdate, $j=0; $i<=$enddate;$i->modify('+1 day'),$j++)/*loop between calender start date and end date*/
           {
              $s_date= $i->format("Y-m-d");
               
               foreach($check_leave as $check_leavedate) 
                 {
                    $check_datelist[]=date('Y-m-d',strtotime($check_leavedate->leave_date));
                    $leave_type[]=$check_leavedate->leave_type;
                    $payment_type[]=$check_leavedate->payment_type;
                    $status[]=$check_leavedate->status;
                    $subject[]=$check_leavedate->subject;
                    $description[]=$check_leavedate->description;

                 }
              if(!empty($check_datelist) && in_array($s_date,$check_datelist))/*match between s_date and get all leave date from database table leave_date*/
                {
                   $key=array_search($s_date,$check_datelist);
                   if(isset($key) && $status[$key]=='Approve')
                   {
                     $leavedate[$j]['leave_date']=$s_date;
                     $leavetype[$j]['leave_type']=$leave_type[$key];
                     $paymenttype[$j]['payment_type']=$payment_type[$key];
                     $leavestatus[$j]['status']=$status[$key];
                     $subject[$j]['subject']=$subject[$key];
                     $description[$j]['description']=$description[$key];
                     $this->data['user_leave'][$j] = array(
                                'leave_date'=>$leavedate[$j]['leave_date'],
                                'leave_type'=>$leavetype[$j]['leave_type'],
                                'payment_type'=>$paymenttype[$j]['payment_type'],
                                'status'=>$leavestatus[$j]['status'],
                                'subject'=>$subject[$j]['subject'],
                                'description'=>$description[$j]['description']
                               );
                   }
                  
                }
           }
           //die();
          $this->data['user_email']=$this->attendance_model->getdatelist($staff_id,$str_date,$end_date);/*get all attendance date of all user*/
        /*print_r($this->data);
          die();*/
         
/*------------------------------create Pdf------------------------------*/
          $name = 'Attendance' . "_" . date('Y_m_d_H_i_s') ."_".$staff_id.".pdf";
          $html = $this->load->view($this->theme .'attendance/pdf',$this->data,true);
          $this->sma->generate_pdf($html, $name, 'S', false, false, false, 5);
  /*------------------------------Send Mail------------------------------*/
          $filename = $name;
          $pdfFilePath = "assets/uploads/".$filename;
          $this->load->library('parser');
          $parse_data = array(
                          'username'=>$user_info[0]->first_name.' '.$user_info[0]->last_name,
                          'start_date'=>$str_date,
                          'end_date'=>$end_date,
                          'site_link' =>base_url(),
                          'site_name' => $this->Settings->site_name,
                          'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                      );
            $attachment=$pdfFilePath;
            $msg=file_get_contents('./themes/' . $this->theme .'email_templates/attendancelist_email.html');
           $messagenew = $this->parser->parse_string($msg, $parse_data,true);
           $subject =$user_info[0]->first_name . ' ' .$user_info[0]->last_name;
         $mail_status=$this->sma->send_email($user_info[0]->email,$subject,$messagenew,null,null,$attachment,'leave.matainja@gmail.com');
         /*  $mail_status=$this->sma->send_email('matainja036@gmail.com',$subject,$messagenew,'matainja036@gmail.com',null,$attachment,null);*/
                 if(file_exists($attachment))
                      {
                        unlink($attachment);
                      }
                   if($mail_status==true)
                   {
                  $data['message']="Email sent successfully".$user_info[0]->first_name.' '.$user_info[0]->last_name;
                   }
                   
               }
               else{

                     //$admin_mail_id=$this->config->item('admin_email');
                       $str_date=$this->input->post('start_date');
                        $end_date=$this->input->post('end_date');
                      $data=array();
                      $user_email_info=$this->attendance_model->getalldatelist($str_date,$end_date);
                      $user_leave_array=array();
                       $user_id_array=array();
                      $i=0;
                      foreach($user_email_info as $user_all_id)
                      {
                         $all_user_id=$user_all_id->user_id;
                    if(in_array($user_all_id->user_id, $user_id_array)){
                         continue;
                       }
                    $user_leave_array[$i] = array(
                                            'user_id'=>$user_all_id->user_id,
                                             'user_name'=>$user_all_id->first_name." ".$userleave_new->last_name
                                              );
                   foreach($user_email_info as $userdetail){
                   if($userdetail->user_id==$all_user_id){
                  $user_leave_array[$i]['leave_data'][] =$userdetail;    
                    }
                       }
                       $user_id_array[]=$all_user_id;
                       $i++;
               }
                      
                  /*  print_r($user_leave_array);
                      die();*/
                   $this->data['leave_array']=$user_leave_array;


                     /*-------------------create pdf------------------*/ 
                      $name = 'Attendancealluser' . "_" . date('Y_m_d_H_i_s') ."_".".pdf";
                      $html = $this->load->view($this->theme .'attendance/alluserpdf', $this->data, true);
                      $this->sma->generate_pdf($html, $name, 'S', false, false, false, 5);
                      $filename = $name;
                      $pdfFilePath = "assets/uploads/".$filename;
                      $this->load->library('parser');
                      $parse_data = array(
                          'username'=>'Admin',
                          'start_date'=>$str_date,
                          'end_date'=>$end_date,
                          'site_link' =>base_url(),
                          'site_name' => $this->Settings->site_name,
                          'logo' => '<img src="' . base_url() . 'assets/uploads/logos/' . $this->Settings->logo . '" alt="' . $this->Settings->site_name . '"/>'
                      );
                      $attachment=$pdfFilePath;
                      $msg=file_get_contents('./themes/' . $this->theme .'email_templates/allattendanceuserlist_email.html');
                      $messagenew = $this->parser->parse_string($msg, $parse_data,true);
                      $subject ="All staff Attendance sheet";
                      $mail_status=$this->sma->send_email('leave.matainja@gmail.com',$subject,$messagenew,null,null,$attachment,$admin_mail_id);
            /*$mail_status=$this->sma->send_email('matainja036@gmail.com',$subject,$messagenew,null,null,$attachment,null);*/
                      if(file_exists($attachment))
                        {
                          unlink($attachment);
                        }
                      if($mail_status==true)
                        {
                      $data['message']="Email sent successfully for All Staff";
                        }


                  } 
                  echo json_encode($data);   
       
      }

     /* function get_total_attendance()
      {
        $id=$this->input->post('id');
      echo  $strt_date=$this->input->post('startdate');
       echo $end=$this->input->post('enddate');
        $start_date=new DateTime($strt_date);
          $end_date=new DateTime($end);
       
        $start_date->modify('first day of this month');
    echo $start_date->format('Y-m-d');
       //die();
       //$total_present=0;
       //$total_absent=0;
      /*for($i=$start_date;$i<=$end_date;$i++)
      {

        //$sdate=date()
       // $end_date = date()
        if(date('d',strtotime($i))>=1 && date('d',strtotime($i))>=1){

        }
       }*/
       /* $total_attendencec=$this->attendance_model->get_attn_record($id,$strt_date,$end);
            $response['Present']=$total_attendencec;
            echo json_encode($response);

       //die();
      }*/

    /*function checkstaffattendance($id=null)
    {
        if($this->input->get('this_month')!='')
        {
           $data=array();
          $attendance_data=$this->attendance_model->staffattendance($id);
          //print_r($attendance_data);
          //die();
          if(!empty($attendance_data))
          {
          foreach($attendance_data as $attendance){
           $data['attendance_details'][]=array(
          'staff_id'=>$attendance->staff_id,
          'staff_name'=>$attendance->staff_name,
          'device_type'=>$attendance->device_type,
          'attendance_time'=>$attendance->attendance_time,
          'attendance_id'=>$attendance->attendance_id,
          'update_time'=>$attendance->update_time,
          );
        }
      }
      else{
          $data['error']=1;
         $data['message']="No data found";
      }

      //echo json_encode($data);
    }
      else
      {
        $fromdate=str_replace('/', '-',$this->input->post('from'));
          $fromdatenew=date('Y=m-d',strtotime($fromdate));
          $todate=str_replace('/', '-',$this->input->post('to'));
          $todatenew=date('Y=m-d',strtotime($todate));
            $data=array(
                'id'=>$id,
                'from_date'=>$fromdatenew,
                'to_date'=>$todatenew
              );
      $attendancerange_data =$this->attendance_model->staffattendancerange($data);
      if(!empty($attendancerange_data))
      {
       foreach($attendancerange_data as $attendance){
           $data['attendance_details'][]=array(
          'staff_id'=>$attendance->staff_id,
          'staff_name'=>$attendance->staff_name,
          'device_type'=>$attendance->device_type,
          'attendance_time'=>$attendance->attendance_time,
          'attendance_id'=>$attendance->attendance_id,
          'update_time'=>$attendance->update_time,
          );
        }
      }
      else
      {
           $data['error']=1;
          $data['message']="No data found";
      }
        
      }
       echo json_encode($data);
     }
*/
    /* function searchattendancereport($id=null)
    {
        if($this->input->get('this_month')!='')
        {
           $data=array();
          $attendance_data=$this->attendance_model->staffattendancereport($id);
         // print_r($attendance_data);
        //  die();
          if(!empty($attendance_data))
          {
          foreach($attendance_data as $attendance){
           $data['attendance_details'][]=array(
            'staff_name'=>$attendance->staff_name,
          'user_id'=>$attendance->user_id,
          'in_time'=>$attendance->in_time,
          'out_time'=>$attendance->out_time,
          'in_out_time'=>$attendance->in_out_time,
          'work_hours'=>$attendance->work_hours,
          'reserve_previous_time'=>$attendance->reserve_previous_time,
          'is_late'=>$attendance->is_late,
          );
        }
      }
      else{
          $data['error']=1;
         $data['message']="No data found";
      }

      //echo json_encode($data);
    }
      else
      {
        $fromdate=str_replace('/', '-',$this->input->post('from'));
          $fromdatenew=date('Y=m-d',strtotime($fromdate));
          $todate=str_replace('/', '-',$this->input->post('to'));
          $todatenew=date('Y=m-d',strtotime($todate));
            $data=array(
                'id'=>$id,
                'from_date'=>$fromdatenew,
                'to_date'=>$todatenew
              );
      $attendancerange_data =$this->attendance_model->attendancereportrange($data);
      if(!empty($attendancerange_data))
      {
       foreach($attendancerange_data as $attendance){
              $data['attendance_details'][]=array(
            'staff_name'=>$attendance->staff_name,
          'user_id'=>$attendance->user_id,
          'in_time'=>$attendance->in_time,
          'out_time'=>$attendance->out_time,
          'in_out_time'=>$attendance->in_out_time,
           'work_hours'=>$attendance->work_hours,
          'reserve_previous_time'=>$attendance->reserve_previous_time,
          'is_late'=>$attendance->is_late,
          );
        }
      }
      else
      {
           $data['error']=1;
          $data['message']="No data found";
      }
        
      }
       echo json_encode($data);
     } */
}