<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Staff extends MY_Controller
{
	var $default_key='st12aff34';
	//var $default_key1='st12aff02';

    function __construct()
    {
        parent::__construct();

        require_once MPDF;

        $this->method = new \Mpdf\Mpdf (['mode' => 'utf-8']);
		 
        $this->upload_path = 'assets/uploads/staff/';
       
        $this->image_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';
       
       $this->allowed_file_size = '9024';

        if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
		 if ($this->Supplier) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
        }
          $this->lang->load('staff', $this->Settings->user_language);
        $this->load->library(array('form_validation','ion_auth'));
		   $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('Staff_model');
		 $this->load->library('ion_auth');
    }

    public function index() //Fetching the staff list
    {
		 $this->sma->checkPermissions();
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			 	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('staff')));
        		$meta = array('page_title' => lang('staff'), 'bc' => $bc);
        		$this->page_construct('staff/welcome', $meta, $this->data);
        		}
				
				
	public function view($id = NULL) //View the staff details.
     {
		
		 //$this->sma->checkPermissions();
		  //die('view');
		  
        $user = $this->ion_auth->user($id)->row();
		$staffinfo=$this->Staff_model->getstaffinfo($id)->row();
        $groups = $this->ion_auth->groups()->result_array();
        //$rfids = $this->Staff_model->rfidfetch()->result_array();
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['user'] = $user;
        $this->data['groups'] = $groups;
		 $this->data['staffinfo'] = $staffinfo;
		 // $this->data['rfids'] = $rfids;
		  $this->data['csrf'] = $this->_get_csrf_nonce();
		  if($user->group_id!=10)
		  {
		   $joindate=date_create($staffinfo->joindate);
		   $today_date=date_create(date("Y-m-d"));
           $diff=date_diff($joindate,$today_date);
           $remainingwarranty1=$diff->format("%y Year %m Month %d Day");
           $this->data['remainingwarranty1']=$remainingwarranty1;
		  }
		  else{
		   
		    $joindate=date_create($staffinfo->joindate);
		   $release_date=date_create($staffinfo->release_date);
           $diff=date_diff($joindate,$release_date);
           $remainingwarranty1=$diff->format("%y Year %m Month %d Day");
            $this->data['remainingwarranty1']=$remainingwarranty1;
		  }
		  
  
		   if($staffinfo->interviewdate=='0000-00-00')
		   {
		   	$this->data['interviewdate']="N/A";
		   }
		   else
		   {
		   		$this->data['interviewdate']=$staffinfo->interviewdate;
		   }
		   if($staffinfo->release_date=='0000-00-00')
		   {
		   	$this->data['release_date']="N/A";
		   }
		   else
		   {
		   		$this->data['release_date']=$staffinfo->release_date;
		   }
		  if($staffinfo->project_manager==0)
		  {
		  	$this->data['project_manager']="";
		  }
		  else
		  {
		  	 $project_manager = $this->ion_auth->user($staffinfo->project_manager)->row();

    		$this->data['project_manager']=$project_manager->first_name.' '.$project_manager->last_name;
		  }

        $this->load->view($this->theme.'staff/view',$this->data);
    }

        function staffs() //get the staff list page.
	   
	   {
		   if ( ! $this->loggedIn) {
            redirect('login');
        }
        /*if ( ! $this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'welcome');
        }*/
          $this->sma->checkPermissions();
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('staff')));
        $meta = array('page_title' => lang('staff'), 'bc' => $bc);
        $this->page_construct('staff/welcome', $meta, $this->data);
		   }
		   
		   
		  function add()   //Staff add details.
		 {
		 		/* if (!$this->Owner) {
            		$this->session->set_flashdata('warning', lang("access_denied"));
           			 redirect($_SERVER["HTTP_REFERER"]);
       				 }*/
       				  $this->sma->checkPermissions();
			
			$this->data['title'] = "Create Staff";
			$this->form_validation->set_rules('staff_name', lang("Name *"), 'trim|required|alpha_numeric_spaces');
			$this->form_validation->set_rules('staff_fathername', lang("fathername"), 'trim|required|alpha_numeric_spaces');
			$this->form_validation->set_rules('dobdate', lang("dobdate"),'trim|required');
			$this->form_validation->set_rules('joindate', lang("joindate"),'trim|required');
			$this->form_validation->set_rules('Address', lang("address"), 'trim|required');
			$this->form_validation->set_rules('PresentAddress', lang("PresentAddress"),'trim');
			$this->form_validation->set_rules('city', lang("city"), 'trim|required|alpha');
			$this->form_validation->set_rules('zipcode', lang("zipcode"), 'trim|required|numeric');
			$this->form_validation->set_rules('group', lang("group"), 'required');
			$this->form_validation->set_rules('personalemail', lang("personalemail"), 'is_unique[sma_staff_info.personalemail]|required|valid_email');
			$this->form_validation->set_rules('businessemail', lang("businessemail"),'required|is_unique[sma_users.email]|valid_email');
			$this->form_validation->set_rules('Phone', lang("Phone"), 'required|numeric');
			$this->form_validation->set_rules('accountnumber', lang("accountnumber"),'numeric');
		//	$this->form_validation->set_rules('attendance_id', lang("attendance_id"),'required');
		
	
			
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
		  $join = str_replace('/','-',$this->input->post('joindate'));
		  $join_data=date("Y-m-d", strtotime($join));
		  $interviewdate = str_replace('/','-',$this->input->post('interviewdate'));
		  $interviewdate1=date("Y-m-d", strtotime($interviewdate));
		  $interviewschedule = str_replace('/','-',$this->input->post('interviewschedule'));
		  $interviewschedule_data=date("Y-m-d H:i:s", strtotime($interviewschedule));
		   $release_date1 = str_replace('/','-',$this->input->post('release_date'));
		   //$release_data2=$this->input->post('release_date');
		 $release_data1=date("Y-m-d", strtotime($release_date1));
		  $project_manager=$this->input->post('project_manager');
		 $attendance_id1=$this->input->post('attendance_id');
			if($attendance_id1!=''){
				$rfidnew = $this->Staff_model->checkrfid($attendance_id1);
				$rfidupdate=$this->Staff_model->updaterfidstatus($rfidnew[0]->id);
				if($rfidnew[0]->rfid==123)
				{
					$rfidget=NULL;
				}
				else
				{
					$rfidget=$rfidnew[0]->rfid;
					
					}		
			}



		
		  
  			
  			//print_r($rfid);
		//die();
		

		  $staff_data = array(
                'last_ip_address' => $_SERVER['REMOTE_ADDR'],
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'username' => $username,
				 'password' => md5($username),
				 'app_password'=>'',
				 'salt'=>NULL,
				  'email' => $this->input->post('businessemail'),
				  'activation_code'=>NULL,
				   'forgotten_password_code'=>NULL, 
				 'forgotten_password_time'=>NULL,
				 'remember_code'=>'',
				 'created_on' => strtotime(date('Y-m-d H:s:i')),
				'last_login' => strtotime(date('Y-m-d H:s:i')),
				'active'=> 1, 
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
										'user_id'		=>$q,
										'staff_fathername' => $this->input->post('staff_fathername'),
										'dob' => $dob_data,
										'joindate' => $join_data,
										'address' => $this->input->post('Address'),
										'presentaddress' => $this->input->post('PresentAddress'),
										'city' => $this->input->post('city'),
										'zipcode' => $this->input->post('zipcode'),
										'upload'=> '', 
										'interviewdate' => ($this->input->post('interviewdate')!='') ? $interviewdate1 : '',
						             	'interviewschedule' => ($this->input->post('interviewschedule')!='') ? $interviewschedule_data : '',
										'personalemail' => $this->input->post('personalemail'),
										'group_id'=>$group_staff,
										'payment_mode'=>$paymentmode,
										'account_number'=>$acc_number,
										'release_date'=> ($this->input->post('release_date')!='') ? $release_data1 : '',
										'note'=>$this->input->post('note'),
										'project_manager'=>($this->input->post('project_manager')!='') ? $this->input->post('project_manager') : 0,
										'attendance_id'=>(isset($rfidget))?$rfidget:NULL	
										);  
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
				  
        $this->session->set_flashdata('message', 'Staff Added Successfully');
		redirect('staff');

}
		 		}
		  else{
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		$this->data['groups'] = $this->ion_auth->groups()->result_array();
			  $bc = array(array('link' =>base_url() , 'page' => lang('home')), array('link' => 'staff', 'page' => lang('staff')),array('link' => '#','page' => lang('create_staff')));
        $meta = array('page_title' => lang('create_staff'), 'bc' => $bc);
        $this->page_construct('staff/create_staff', $meta, $this->data);
			 
		  }
		  
		}
		  public function getstaff() //Get staff details
		  {
			  /* if ( ! $this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            $this->sma->md();
        }*/
         //$this->sma->checkPermissions();

            $assign_link = anchor('staff/resourcelist/$1', '<i class="fa fa-file-text-o"></i>' . lang('assign_resource'));

        $edit_link = anchor('staff/edit/$1', '<i class="fa fa-edit"></i>' . lang('edit_staff'), 'class="sledit"');
         // $resourcelist_link = anchor('staff/resourcelist/$1', '<i class="fa fa-edit"></i>' . lang('resourcelist'), 'class="resourcelist"');
         $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'

        . lang('actions') . ' <span class="caret"></span></button>

        <ul class="dropdown-menu pull-right" role="menu">

            <li>' . $assign_link . '</li>
              
            <li>' . $edit_link . '</li>
             <li><a href="javascript:void(0);" class="sledit edit_home" data-user_id="$1"><i class="fa fa-file-text-o"></i>Add work From Home</a></li>
            <li><a href="javascript:void(0);" class="sledit edit_user" data-user_id="$1"><i class="fa fa-edit"></i>Generate Password</a></li>

            
            <li><a href="javascript:void(0);" class="sledit edit_increment" data-user_id="$1"><i class="fa fa-edit"></i>Salary Increment History</a></li>

         

        </ul>

    </div></div>';

			    $this->load->library('datatables');
        $this->datatables
            ->select($this->db->dbprefix('users').".id as id, first_name, last_name, ".$this->db->dbprefix('staff_info').".attendance_id,personalemail," .$this->db->dbprefix('users').".email,phone,". $this->db->dbprefix('groups').".name, active")
            ->from("users")
			->join('staff_info','staff_info.user_id=users.id','left') 
			->join('groups', 'users.group_id=groups.id','left') 
			->where('users.is_staff', 1)
            ->edit_column('active', '$1__$2','active, id')
            //->add_column("Actions", "<div id=\"user_photo\" class=\"text-center\"><a href='" . site_url('staff/edit/$1') . "' class='tip' title='" . lang("edit_staff") . "'><i class=\"fa fa-edit\"></i></a></div><input type='hidden' value='$1'>", "id");
            ->add_column("Actions", $action, "id");
      //  if (!$this->Owner) {
        //    $this->datatables->unset_column('id');
        //}
        echo $this->datatables->generate();
		
}
        function resourcelist($id=null)
        {
           // $this->data['resource_list']= $this->Staff_model->resourcelist($id)->result();
        	$this->data['id']= $id;
           // print_r($resource_list);
            //die();
           $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('staff'), 'page' => lang('staff')), array('link' => '#', 'page' => lang('view_staffresource')));

            	$meta = array('page_title' => lang('view_staffresource'), 'bc' => $bc);

            	$this->page_construct('staff/resourceuser_view', $meta, $this->data);
        }
        function getresource_list($id=null)
        {
        	  $detail_link = anchor('resource/view/$1', '<i class="fa fa-file-text-o"></i>' . lang('Resource_details'));
        	 $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'

        . lang('actions') . ' <span class="caret"></span></button>

         <ul class="dropdown-menu pull-right" role="menu">

            <li>' . $detail_link . '</li>
            </ul>
         </div></div>';
            //$this->save_queries=true;
        	    	$this->load->library('datatables');
        	$this->datatables->select("sma_resource.`id` as id, sma_resource_activity.`name` as staff_name, sma_resource_activity.`user_id`,sma_resource_activity.`RID`,   sma_resource.`resource`,sma_resource.`name`,sma_resource.`model`,sma_resource.`serial_no`,sma_resource_activity.`modified_date`,sma_resource_activity.`status`")
            ->from("sma_resource")
			->join('sma_resource_activity','sma_resource_activity.RID=sma_resource.id')
			->where('sma_resource_activity.user_id',$id)
			 ->add_column("Actions", $action, "id");
			  echo $this->datatables->generate();
			       
        }
        function salary_actions()
        {
        	
      		$this->form_validation->set_rules('form_action', lang("form_action"), 'required');
       		if ($this->form_validation->run() == true)
       		 {
        	 	if (!empty($_POST['val']))
        	 	  {
                    if ($this->input->post('form_action') == 'delete')
                       {
			                    foreach ($_POST['val'] as $id) 
			                    {

			                        if ($id != $this->session->userdata('user_id')) 
			                        {
			                            $this->Staff_model->del_salary_details($id);
			                        }
                                }
			                    $this->session->set_flashdata('message', lang("staff_salary_details"));
			                    redirect($_SERVER["HTTP_REFERER"]);
                		}
                  }
                 else
                    {
	                $this->session->set_flashdata('error', lang("no_user_selected"));
	                redirect($_SERVER["HTTP_REFERER"]);
                    }
             }
             else 
                {
            	  $this->session->set_flashdata('error', validation_errors());
            	   redirect($_SERVER["HTTP_REFERER"]);
                 }
        }
		
		function staff_actions() // staff different action is here.
    	{
       		/* if (!$this->Owner) {
	            $this->session->set_flashdata('warning', lang('access_denied'));
	            redirect($_SERVER["HTTP_REFERER"]);
       		}*/

	        $this->sma->checkPermissions();
	      	$this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        	if ($this->form_validation->run() == true) {

	            if (!empty($_POST['val'])) {

	                if ($this->input->post('form_action') == 'delete') {
	                    foreach ($_POST['val'] as $id) {
	                        if ($id != $this->session->userdata('user_id')) {
	                            $this->Staff_model->delete_staff($id);
	                        }
	                    }
	                    $this->session->set_flashdata('message', lang("staff_deleted"));
	                    redirect($_SERVER["HTTP_REFERER"]);
	                }

					if ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

	                    $this->load->library('excel');
	                    $this->excel->setActiveSheetIndex(0);
	                    $this->excel->getActiveSheet()->setTitle(lang('staff'));
	                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('first_name'));
	                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('last_name'));
	                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('businessemail'));
	                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('Phone'));
	                	// $this->excel->getActiveSheet()->SetCellValue('E1', lang('company'));
	                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('FatherName1'));
	                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('group'));
						$this->excel->getActiveSheet()->SetCellValue('G1', lang('status'));

	                    $row = 2;
	                    foreach ($_POST['val'] as $id) {
	                        $user = $this->site->getUser($id);
							$staff = $this->site->getstaff($id);
							
	                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $user->first_name);
	                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $user->last_name);
	                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $user->email);
							$this->excel->getActiveSheet()->SetCellValue('D' . $row, $user->phone);
	                        //$this->excel->getActiveSheet()->SetCellValue('E' . $row, $user->company);
							$this->excel->getActiveSheet()->SetCellValue('E' . $row, $staff->staff_fathername);
	                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, "Staff");
	                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, ($user->active==1)?'Active':'Inactive');
	                        $row++;
	                    }

						$this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
						$this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
						$this->excel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
						$this->excel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	                    $filename = 'staff_' . date('Y_m_d_H_i_s');
						
						
	                    if ($this->input->post('form_action') == 'export_pdf')
	                    {
	                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
	                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
	                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

	                        // require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");

	                        require_once MPDF;

                        	$rendererLibraryPath = new \Mpdf\Mpdf (['mode' => 'utf-8']);
	                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;

	                        /*$rendererLibrary = 'MPDF';
	                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;*/

	                        if (!PHPExcel_Settings::setPdfRenderer($rendererName, $rendererLibraryPath)) {
	                            die('Please set the $rendererName: ' . $rendererName . ' and $rendererLibraryPath: ' . $rendererLibraryPath . ' values' .
	                                PHP_EOL . ' as appropriate for your directory structure');
	                        }

	                        header('Content-Type: application/pdf');
	                        header('Content-Disposition: attachment;filename="' . $filename . '.pdf"');
	                        header('Cache-Control: max-age=0');

	                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'PDF');
	                        return $objWriter->save('php://output');
	                    }
						
	                    if ($this->input->post('form_action') == 'export_excel') {
	                        header('Content-Type: application/vnd.ms-excel');
	                        header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	                        header('Cache-Control: max-age=0');

	                        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');
	                        return $objWriter->save('php://output');
	                    }

	                    redirect($_SERVER["HTTP_REFERER"]);
	                }
	            }else {
	                $this->session->set_flashdata('error', lang("no_user_selected"));
	                redirect($_SERVER["HTTP_REFERER"]);
	            }
	        } else {
	            $this->session->set_flashdata('error', validation_errors());
	            redirect($_SERVER["HTTP_REFERER"]);
	        }
		}

		function edit($id = NULL) //staff details editPage.
		{
			/*if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
	            $this->session->set_flashdata('warning', lang("access_denied"));
	            redirect($_SERVER["HTTP_REFERER"]);
        	}*/
	        
	        $this->sma->checkPermissions();
	        
	        if (!$id || empty($id)) {
	            redirect('staff');
	        }
			
			$this->data['title'] = lang('edit_staff');

	        $user = $this->ion_auth->user($id)->row();
			$staffinfo=$this->Staff_model->getstaffinfo($id)->row();
			
	        $groups = $this->ion_auth->groups()->result_array();
	        $this->data['csrf'] = $this->_get_csrf_nonce();
	        $this->data['user'] = $user;
	        $this->data['groups'] = $groups;
			$this->data['staffinfo'] = $staffinfo;
	        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
	        $this->data['id'] = $id;

	        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('staff/staffs'), 'page' => lang('staff')), array('link' => '#', 'page' => lang('edit_staff')));
	        $meta = array('page_title' => lang('edit_staff'), 'bc' => $bc);

	        $this->page_construct('staff/edit', $meta, $this->data);
		}

		function _get_csrf_nonce()
	    {
	        $this->load->helper('string');
	        $key = random_string('alnum', 8);
	        $value = random_string('alnum', 20);
	        $this->session->set_flashdata('csrfkey', $key);
	        $this->session->set_flashdata('csrfvalue', $value);

	        return array($key => $value);
	    }
	
		function edit_staff($id = NULL) //Edit staff details .
		{
		 	$rfid ="";
			   

			if ($this->input->post('id')) {
	        	$id = $this->input->post('id');
	        }
	        $this->data['title'] = lang("edit_staff");
			
			
	       
			  $user = $this->ion_auth->user($id)->row();
			  	$staffinfo=$this->Staff_model->getstaffinfo($id)->row();
			  	//print_r($user);
			  	//die();

			 
	        /*if ($user->username != $this->input->post('username')) {
	            $this->form_validation->set_rules('username', lang("username"), 'trim|is_unique[sma_users.username]');
	        }*/
			if ($user->email != $this->input->post('businessemail')) {
	            $this->form_validation->set_rules('businessemail', lang("businessemail"), 'trim|is_unique[sma_users.email]');
	        }
			if ($staffinfo->personalemail != $this->input->post('personalemail')) {
	            $this->form_validation->set_rules('personalemail', lang("personalemail"), 'trim|is_unique[sma_staff_info.personalemail]');
	        }

			$this->form_validation->set_rules('Name', lang("Name *"), 'trim|required|alpha_numeric_spaces');
			$this->form_validation->set_rules('staff_fathername', lang("FatherName"), 'trim|required|alpha_numeric_spaces');
			$this->form_validation->set_rules('dobdate', lang("dobdate"),'trim|required');
			$this->form_validation->set_rules('joindate', lang("joindate"),'trim|required');
			$this->form_validation->set_rules('address', lang("address"), 'trim|required');
			$this->form_validation->set_rules('PresentAddress', lang("PresentAddress"),'trim');
			$this->form_validation->set_rules('city', lang("city"), 'trim|required|alpha');
			$this->form_validation->set_rules('zipcode', lang("zipcode"), 'trim|required|numeric');
			$this->form_validation->set_rules('group', lang("group"), 'required');
			$this->form_validation->set_rules('phone', lang("Phone"), 'required|numeric');
			$this->form_validation->set_rules('accountnumber', lang("accountnumber"),'numeric');

			//$this->form_validation->set_rules('attendance_id', lang("attendance_id"),'required');
			/*if($this->input->post()){
				print_r($this->input->post());die();
			}*/

			if ($this->form_validation->run() === TRUE) {
				
				$staffname=explode(" ",$this->input->post('Name'));
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
					$firstname=$this->input->post('Name');
				}
				
				$businessemail=$this->input->post('businessemail');
				//$path=explode('@',$businessemail);
				//$username=$path[0];
				$group_staff=$this->input->post('group');
				$paymentmode=$this->input->post('payment_mode');
				$acc_number=$this->input->post('accountnumber');
				$dob = str_replace('/','-',$this->input->post('dobdate'));
				$dob_data=date("Y-m-d", strtotime($dob));
				$join = str_replace('/','-',$this->input->post('joindate'));
				$join_data=date("Y-m-d", strtotime($join));
				$interviewdate = str_replace('/','-',$this->input->post('interviewdate'));
				$interviewdate1=date("Y-m-d", strtotime($interviewdate));
				$interviewschedule = str_replace('/','-',$this->input->post('interviewschedule'));
				$interviewschedule_data=date("Y-m-d H:i:s", strtotime($interviewschedule));
				$release_date = str_replace('/','-',$this->input->post('release_date'));
				$release_data1=date("Y-m-d", strtotime($release_date));
				$notelist= $this->input->post('note');
				$project_manager=$this->input->post('project_manager');
				$attendance_id1=$this->input->post('attendance_id');
			
				if($attendance_id1!=''){
					//$rfid = $this->Staff_model->checkrfid($attendance_id1);
					//$rfidupdate=$this->Staff_model->updaterfidstatus($rfid[0]->id);
					 //$rfidupdatenew=$this->Staff_model->releaserfidstatus($staffinfo->attendance_id);
			  	    
					$rfid= $attendance_id1;
				}
			
	 			
			
			   

				$staff_data = array(
	                'last_ip_address' => $_SERVER['REMOTE_ADDR'],
	                'ip_address' => $_SERVER['REMOTE_ADDR'],
	                'username' => $user->username,
					'password' =>$user->password ,
					'app_password' =>'' ,
					'salt'=>NULL,
					'email' => $this->input->post('businessemail'),
					'activation_code'=>NULL,
					'forgotten_password_code'=>NULL, 
					'forgotten_password_time'=>NULL,
					'remember_code'=>'',
					'created_on' => strtotime(date('Y-m-d H:s:i')),
					'last_login' => strtotime(date('Y-m-d H:s:i')),
					'active'=> 1, 
					'first_name' =>$firstname,
	                'last_name'=>$lastname,
	                'company' => 'Matainja Technologies',
					'phone' => $this->input->post('phone'),
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
				//print_r($staff_data);
				//die();
				$q=$this->Staff_model->updatestaff1($user->id,$staff_data);
				//echo($q);
				//die('error');
				
				
				if($q){
					
				    $additional_data = array(
						'user_id'		=>$user->id,
						'staff_fathername' => $this->input->post('staff_fathername'),
						'dob' => $dob_data,
						'joindate' => $join_data,
						'address' => $this->input->post('address'),
						'presentaddress' => $this->input->post('PresentAddress'),
						'city' => $this->input->post('city'),
						'zipcode' => $this->input->post('zipcode'),
						'upload'=> '', 
						'interviewdate' => (isset($interviewdate) && $interviewdate!='')?$interviewdate1:'',
						'interviewschedule' => (isset($interviewschedule) && $interviewschedule!='')?$interviewschedule_data:'',
						'personalemail' => $this->input->post('personalemail'),
						'group_id'=>$group_staff,
						'payment_mode'=>$paymentmode,
						
						'account_number'=>$acc_number,
						'release_date'=>(isset($release_date) && $release_date!='')?$release_data1:'',
						'note'         =>$notelist,
						'project_manager'=>$project_manager,
						'attendance_id'=>($rfid!='')?$rfid:NULL,
					);
					// print_r($additional_data);
					//die();
					$p=$this->Staff_model->updatestaff2($staffinfo->user_id,$additional_data);
				
					if($p){
						if ($_FILES['Browse']['size'] > 0)
						{

							if(file_exists($this->upload_path.'/'.$staffinfo->id))
							{
								$this->load->helper("file");
								delete_files($this->upload_path.'/'.$staffinfo->id);
							}else{
								mkdir($this->upload_path.'/'.$staffinfo->id,0777,true);
							}

			                $this->load->library('upload');
			                $config['upload_path'] =$this->upload_path.'/'.$staffinfo->id;
			                $config['allowed_types'] = $this->image_types;
			            	// $config['max_size'] = $this->allowed_file_size;
			                $config['overwrite'] = FALSE;
			                $config['encrypt_name'] = TRUE;
							$config['file_name'] =$_FILES['Browse']['name']  ;
			                $this->upload->initialize($config);

			                if (!$this->upload->do_upload('Browse')) {
			                    $error = $this->upload->display_errors();
			                    $this->session->set_flashdata('error', $error);
			                    redirect($_SERVER["HTTP_REFERER"]);
			                }else{
				                $photo = $this->upload->file_name;
								$d=array('upload'=>$this->upload_path.$staffinfo->id.'/'.$photo);
								if(!empty($d))
								$this->Staff_model->uploadfile($d,$staffinfo->id);
							}
						}
						else{
							$d=array('upload'=>$staffinfo->upload);
							$this->Staff_model->uploadfile($d,$staffinfo->id);
						}
						$this->session->set_flashdata('message', lang('staff_updated'));
			            redirect("staff");
					}
				}
			}else{
				$this->session->set_flashdata('error', validation_errors());
            	redirect($_SERVER["HTTP_REFERER"]);
			}
		}

		function staff_attendance()
		{
			$this->data['csrf'] = $this->_get_csrf_nonce();
			$bc = array(array('link' =>base_url() , 'page' => lang('home')), array('link' => 'staff', 'page' => lang('staff')),array('link' => '#','page' => lang('Staff_Attendance')));
	        $meta = array('page_title' => lang('Staff_Attendance'), 'bc' => $bc);
	        $this->page_construct('staff/staff_attendance_view', $meta, $this->data);

		} 

		public function add_password($id=null)
        {
             
            $user_info=$this->Staff_model->getpasswordById($id)->row();
            $this->data['userinfo']=$user_info;
        	//print_r($user_info);
         	//die();
            $this->load->view($this->theme.'staff/modal_view',$this->data);
        }
        
        public function add_work_home($id=null)
        {
             $user_info=$this->Staff_model->getpasswordById($id)->row();
            $workhome_info=$this->Staff_model->work_home_ById($id)->row();
            $this->data['userinfo']=$user_info;
            $this->data['workinfo']=$workhome_info;

           //print_r($user_info);
         //die();
            $this->load->view($this->theme.'staff/workhome_view',$this->data);
         }

          public function add_increment_salary($id=null)
         {

            $user_info=$this->Staff_model->getpasswordById($id)->row();
            $salary_info=$this->Staff_model->salary_details($id)->row();
              $salary_history=$this->Staff_model->salary_details($id)->result();
              //print_r($salary_history);
         	//	die();
            $this->data['increment_info']=$salary_info;
            $this->data['staff_info']=$user_info;
            $this->data['sal_history']=$salary_history;
           // print_r($this->data);
          //die();
            $this->load->view($this->theme.'staff/salary_modalview',$this->data);
         }

          public function update_App_password()
           {
              $id=$this->input->post('id');
              $app_pswd=$this->input->post('apppswd');
              $update_password=$this->Staff_model->update_ModalPswd($id,$app_pswd);
              
           
                 if(!empty($update_password))
         		  {
         		  	//$this->session->set_flashdata('message', lang('staff_updated'));
       				$data['success']=1;
            		$data['message']='successfully updated';
         		  }
               else
                {
                  $data['error']=1;
                  $data['message']='Not updated';
                }
                echo json_encode($data); 
            }
              public function add_increment_info()
           {
              $id=$this->input->post('id');
             
              $prev_sal=$this->input->post('prevsal');
              $data=array();
              $datalist=array();

              $increment_amount=$this->input->post('incrt_amount');
              $effect_datenew=str_replace('-','/',$this->input->post('effct_date'));
             $effect_date=date('Y-m-d',strtotime($effect_datenew));
             $gross_salary=$prev_sal+$increment_amount;
               $rmks=$this->input->post('remarks');
             
		             
		               $datalist=array(
		               	'user_id'=>$id,
		               	'prev_salary'=> $prev_sal,
		               	'increment_amount'=> $increment_amount,
		               	'gross_salary'=>$gross_salary,
		               	'effected_date'=>$effect_date,
		               	'remarks'=>$rmks,
		               );
              		$add_sal_info=$this->Staff_model->add_Modalincrement($datalist);
                if($add_sal_info==true)
         		  {
         		  	//$this->session->set_flashdata('message', lang('staff_updated'));
       				$data['success']=1;
            		$data['message']='successfully insert';
         		   }

                 else
                   {
                     $data['error']=1;
                     $data['message']='Not insert';
                   }
              
                echo json_encode($data); 
            }

            public function getsalary_report()
            {
            	$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		
			  $bc = array(array('link' =>base_url() , 'page' => lang('home')), array('link' => 'staff', 'page' => lang('staff')),array('link' => '#','page' => lang('salaryreport')));
        $meta = array('page_title' => lang('salaryreport'), 'bc' => $bc);
        $this->page_construct('staff/salary_report', $meta, $this->data);
            }
            public function getsalary_info(){
         /* $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'

        . lang('actions') . ' <span class="caret"></span></button></div></div>';*/

            $this->load->library('datatables');
            $this->datatables
          ->select($this->db->dbprefix('users').".id as id, CONCAT(".$this->db->dbprefix('users').".first_name, ' ' ,".$this->db->dbprefix('users').".last_name) as name, ".$this->db->dbprefix('increment_history').".id,user_id,prev_salary,increment_amount,gross_salary,effected_date,remarks")
           ->from("users")
           ->join('increment_history','increment_history.user_id=users.id')
           ->where('users.active',1);
          // ->add_column("Actions", $action, "id");
          
           echo $this->datatables->generate();
            }

              public function homedate_add()
           {
              $id=$this->input->post('id');

                $start_date=str_replace('/','-',$this->input->post('start_date'));
                 $start_datenew=  new DateTime($start_date);
               $end_date=str_replace('/','-',$this->input->post('end_date'));
                  $end_datenew=  new DateTime($end_date);
                  $reason=$this->input->post('reason');
                  $today_date= date('d-m-Y');
               
                  if(strtotime($today_date)<= strtotime($start_date) && strtotime($today_date)<=strtotime($end_date))
                  {
                     $home_info=$this->Staff_model->get_home_info($id,$start_date,$end_date);
                  // print_r($home_info);
                   //die();
                    if(empty($home_info) || $home_info==false)
                    {
                     if(strtotime($start_date)<=strtotime($end_date))
			              {
			              $datenew=date_diff($start_datenew,$end_datenew);
			              $datenew_list=$datenew->format("%d days");
			               $date=array();
			               $date[0]=date('Y-m-d',strtotime($start_date));
			             for($i=0;$i<=$datenew_list;$i++)
			                {
			               		$sdate=strtotime("+".$i."day",strtotime($start_date));
			                	 $date[$i]=date('Y-m-d',$sdate);
			                 	$data_array=array(
			                                  'user_id'=>$id,
			                                  'work_date'=>$date[$i],
			                                  'reason'=>$reason,
			                                  'is_approve'=>1,

			                              );
			                 	 $result=$this->Staff_model->home_date_register($data_array);
			                 	 $response=array();
			                       if($result==true)
			                         {
			                          $response['success']=1;
			                          $response['message']='Successfully Added';
									  } 
			                         else
			                           {
			                        	$response['error']=1;
			                        	$response['message']='Not Added';
			                      		}
			                                  
			               }
			           }

			           else{
			                        $date_list=date('Y-m-d',strtotime($start_date));
			                           //die();
			                         $data_array=array(
			                                  'user_id'=>$id,
			                                  'work_date'=>$date_list,
			                                   'reason'=>$reason,
			                                  'is_approve'=>1,
			                              );
			                       
			           $result=$this->Staff_model->home_date_register($data_array);
			                 	 $response=array();
			                       if($result==true)
			                         {
				                          $response['success']=1;
				                          $response['message']='Successfully Added';
									  } 
			                         else
			                           {
				                        	$response['error']=1;
				                        	$response['message']='Not Added';
			                      		}
			                       }
			                      }
			                      else
			                      {
			                      	$response['error']=1;
				                    $response['message']='Date already exists';
			                      } 
			             }
			              else{
			              	$response['error']=1;
				            $response['message']='date is note less than current date';
			              }   
			                 
			       
			     echo json_encode($response); 
            }
  

	function suggestions($term = NULL, $limit = NULL)
    {
        //$this->sma->checkPermissions('index');
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        $limit = $this->input->get('limit', TRUE);
        $rows['results'] = $this->Staff_model->getStaffSuggestions($term, $limit);
        $this->sma->send_json($rows);
    }
   

    function staffrfid($term = NULL, $limit = NULL)
    {


        //$this->sma->checkPermissions('index');
        //die("error2");
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        
        $limit = $this->input->get('limit', TRUE);
         
        $rows['results'] = $this->Staff_model->getStaffRfid($term, $limit);
        $this->sma->send_json($rows);
    }
 function suggestions_pm($term = NULL, $limit = NULL)
    {


        //$this->sma->checkPermissions('index');
        //die("error2");
        if ($this->input->get('term')) {
            $term = $this->input->get('term', TRUE);
        }
        if (strlen($term) < 1) {
            return FALSE;
        }
        
        $limit = $this->input->get('limit', TRUE);
         
        $rows['results'] = $this->Staff_model->getStaffpm($term, $limit);
        $this->sma->send_json($rows);
    }
 

    function getstaff1($id)
    {
       $user = $this->ion_auth->user($id)->row(); 
        $data[0]=array(
                    'id'=>$id,
                    'text'=>$user->first_name.' '.$user->last_name
                    );
        echo json_encode($data);
    }
     function getrfid($rfid)
    {
    	//echo $rfid;
       $rfid = $this->Staff_model->getrfidfetch(urldecode($rfid))->row(); 
      // print_r($rfid);
      // die();
        $data[0]=array(
                    'id'=>$rfid->id,
                    'text'=>$rfid->rfid
                    );
        echo json_encode($data);
    }
     function getprojectid($pid)
    {
       $projectid = $this->Staff_model->getproject_id($pid)->row(); 
        $data[0]=array(
                    'id'=>$projectid->id,
                    'text'=>$projectid->first_name.' '.$projectid->last_name
                    );
    	echo json_encode($data);
    }
	 function photo($id)
    {
		
      $data=$this->Staff_model->getphoto($id); 
	
	  echo json_encode($data);	
	     }

	     function set_attendance(){
	     	
	     	$user_id = $this->input->get('user_id');
	     	$key = $this->input->get('key');
	     	if($key == $this->default_key){
	     	$user_info = $this->Staff_model->get_staff_info($user_id)->row();
              
	     	if(!empty($user_info)){
	     	//	$get_attendance=$this->Staff_model->get_staff_attendance($user_id);
	     		 //print_r($get_attendance);
	     	//die();
	     	
	     	//if(!empty($get_attendance)){
	     		$data = array(
	     					'staff_id'=>$user_info->id,
	     					'staff_name'=>$user_info->first_name.' '.$user_info->last_name,
	     					'device_type'=>$user_info->attendance_id,
	     					'attendance_time'=>date('Y-m-d h:i:s'),
	     					
	     				);
	     	
	     		$insert_attendance=$this->Staff_model->insert_attendance($data); 
	     		if($insert_attendance){
	     			$result['success']=1;
	     			$result['message']='Attendance added successfully';
	     		}
	     		else{
	     			$result['error']=1;
	     			$result['message']='Attendance cannot be added';
	     		}	
	     		}
	     		/* else if(!empty($get_attendance)){
	     			$data = array(
	     					'staff_id'=>$user_info->id,
	     					'attendance_time'=>$get_attendance[0]->attendance_time,
	     					'out_time'		=>strtotime(date('H:i:s'))

	     				);*/
	     					//$staffid=$user_info->id;
	     			     	//$out_time=strtotime(date('H:i:s'));
	     			//echo $out_time;
	     			//die();
	     		
	     			/*$this->Staff_model->updateouttime($data);

                      $result['success']=1;
	     			$result['message']='Attendance updated successfully';
	 			
                */
	     			/*}
	     		else{
	     		$result['error']=1;
	     		$result['message']='Please enter valid user id.';
	     	}*/
	     	}
	     	
	     	else{
	     		$result['error']=1;
	     		$result['message']='Please enter valid key.';
	     	}
	     	echo json_encode($result);
	     }
	 
	     


	    
	     function checkstaffattendance($id=null)
	     {
	     	

	     	if($this->input->get('this_month')!=''){
	     		$attendance_data = $this->Staff_model->staffattendance($id);
	     	//print_r($attendance_data);
	   //die();
	     		$user_info = $this->Staff_model->get_staff_info($id)->row();
	     		
	     		$data=array();
	     		$data['staff_name']=$user_info->first_name.' '.$user_info->last_name;
                     //$total='0000-00-00';
	     		foreach($attendance_data as $attendance){
	     			   $datefirst=$attendance->InStamp;
	     			
	     		  $datefirstnew=date('Y-m-d',strtotime($datefirst));
	     		
	     			
	     			 $intime=$attendance->InStamp;
	     			$intimenew=date('H:i:s',strtotime($intime));
	     			//die();
	     			  $outtime=$attendance->OutStamp;
	     			$outtimenew=date('H:i:s',strtotime($outtime));
	     			 $hours=$attendance->Hours;
	     			$devicetype=$attendance->device_type;
					//$total_hours=$total+$hours;
	     			/*if($outtime!=0){
	     				$outtimenew=date('H:i:s',$outtime);	
	     				$Hours=$outtime-$intime;
                    	$Hoursnew=date('H:i:s',$Hours);
	     			}else{
	     				$outtimenew='WORKING';
	     				$Hoursnew='';
	     			}*/
	     			
	     			$data['attendance_details'][]=array(
	     				'attn_date'=>$datefirstnew,
	     				'in_time'=>$intimenew,
	     				'out_time'=>$outtimenew,
	     				 'hours'=>$hours,
                         'type'=>$devicetype,
						 // 'Totalhours'=>$total_hours
						
						 
	     			);
	     			//print_r($data);
	     		//die();
	     			
	     		}
	     		
	     	echo json_encode($data);
	     		
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
	     	$attendancerange_data =$this->Staff_model->staffattendancerange($data);
	     	$user_info = $this->Staff_model->get_staff_info($id)->row();
	     		
	     		$data=array();
	     		$data['staff_name']=$user_info->first_name.' '.$user_info->last_name;
	     		foreach($attendancerange_data as $attendance){
	     			/*$datefirst=$attendance->attendance_time;
	     			$datefirstnew=date('d-m-Y',$datefirst);
	     			$intime=$attendance->in_time;
	     			$intimenew=date('H:i:s',$intime);
	     			$outtime=$attendance->out_time;
	     			if($outtime!=0){
	     				$outtimenew=date('H:i:s',$outtime);	
	     				$Hours=$outtime-$intime;
                    	$Hoursnew=date('H:i:s',$Hours);
	     			}else{
	     				$outtimenew='WORKING';
	     				$Hoursnew='';
	     			}
	     			*/
	     			$datefirst=$attendance->InStamp;
	     			
	     		 $datefirstnew=date('Y-m-d',strtotime($datefirst));
	     		
	     			
	     			 $intime=$attendance->InStamp;
	     			$intimenew=date('H:i:s',strtotime($intime));
	     			  $outtime=$attendance->OutStamp;
	     			$outtimenew=date('H:i:s',strtotime($outtime));
	     			 $hours=$attendance->Hours;
	     			$devicetype=$attendance->device_type;
	     			
	     			$data['attendance_details'][]=array(
	     				'attn_date'=>$datefirstnew,
	     				'in_time'=>$intimenew,
	     				'out_time'=>$outtimenew,
	     				 'hours'=>$hours,
                         'type'=>$devicetype
	     			);
	     	}
	     		echo json_encode($data);
	     }
	  } 
           
 		
	    
	     		     

}

