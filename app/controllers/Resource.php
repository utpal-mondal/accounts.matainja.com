<?php defined('BASEPATH') or exit('No direct script access allowed');



class Resource extends MY_Controller
{

    public function __construct()

    {

        parent::__construct();

        require_once MPDF;

        $this->method = new \Mpdf\Mpdf (['mode' => 'utf-8']);


        if (!$this->loggedIn) {

            $this->session->set_userdata('requested_page', $this->uri->uri_string());

            $this->sma->md('login');

        }

        if ($this->Supplier) {

            $this->session->set_flashdata('warning', lang('access_denied'));

            redirect($_SERVER["HTTP_REFERER"]);

        }

        $this->lang->load('resource', $this->Settings->user_language);

        $this->load->library(array('form_validation','ion_auth'));

        $this->load->model(array('resource_model','sales_model'));

        $this->digital_upload_path = 'files/';

        $this->upload_path = 'assets/uploads/';

        $this->thumbs_path = 'assets/uploads/thumbs/';

        $this->image_types = 'gif|jpg|jpeg|png|tif';

        $this->digital_file_types = 'zip|psd|ai|rar|pdf|doc|docx|xls|xlsx|ppt|pptx|gif|jpg|jpeg|png|tif|txt';

        $this->allowed_file_size = '1024';

        $this->data['logo'] = true;

    }



    public function index()

    {

        $this->sma->checkPermissions();

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('resource')));

        $meta = array('page_title' => lang('resource'), 'bc' => $bc);

        $this->page_construct('resource/resource_list', $meta, $this->data);

    }
	
	
	public function view($id = NULL)
     {
		
		 $this->sma->checkPermissions('index', true);
		  //die('view');
		  
        $user = $this->ion_auth->user($id)->row();
        
		$resourceinfo=$this->resource_model->getresourceinfo($id)->row();
		/*$resourceinfo1=$this->resource_model->getresourceactivity()->result();*/
         
  /*  print_r($resourceinfo);
   die();*/
        // $user = $this->ion_auth->user($assign)->row();
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['user'] = $user;
        $this->data['resourceinfo'] = $resourceinfo;
       
		 // $this->data['resourceinfo1'] = $resourceinfo1;
		  $this->data['csrf'] = $this->_get_csrf_nonce();
		  	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('resource'), 'page' => lang('resource')), array('link' => '#', 'page' => lang('view_resource')));

            	$meta = array('page_title' => lang('view_resource'), 'bc' => $bc);

            	$this->page_construct('resource/view', $meta, $this->data);
		
      
    }

public function viewdetails($id = NULL)
     {

         $this->sma->checkPermissions('index', true);
          //die('view');
          
        $user = $this->ion_auth->user($id)->row();
       $resourceinfo=$this->resource_model->getresourceinfo($id)->row();
    // $resourceinfo1=$this->resource_model->getresourceactivity($id)->result();
        $this->data['csrf'] = $this->_get_csrf_nonce();
        $this->data['user'] = $user;
         $this->data['resourceinfo'] = $resourceinfo;
         // $this->data['resourceinfo1'] = $resourceinfo1;
          $this->data['csrf'] = $this->_get_csrf_nonce();
        
        $this->load->view($this->theme.'resource/resourcedetails',$this->data);
        }



		function resource_actions()
    	{
       		/* if (!$this->Owner) {
            $this->session->set_flashdata('warning', lang('access_denied'));
            redirect($_SERVER["HTTP_REFERER"]);
       		 }*/
            $this->sma->checkPermissions('index', true);

      	  $this->form_validation->set_rules('form_action', lang("form_action"), 'required');

        if ($this->form_validation->run() == true) {

            if (!empty($_POST['val'])) {
                if ($this->input->post('form_action') == 'delete') {
                    foreach ($_POST['val'] as $id) {
                        if ($id != $this->session->userdata('user_id')) {
                            $this->resource_model->deleteresource($id);
                        }
                    }
                    $this->session->set_flashdata('message', lang("resource_deleted"));
                    redirect($_SERVER["HTTP_REFERER"]);
                }
            elseif ($this->input->post('form_action') == 'combine') {

                    $html = $this->combine_pdf($_POST['val']);

                 }
                       

				elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {

                    $this->load->library('excel');
                    $this->excel->setActiveSheetIndex(0);
                    $this->excel->getActiveSheet()->setTitle(lang('resource'));
                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('list_purchase'));
                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('list_resource'));
                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('list_name'));
                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('list_model'));
                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('list_serialno'));
					$this->excel->getActiveSheet()->SetCellValue('F1', lang('warranty1'));
                    $this->excel->getActiveSheet()->SetCellValue('G1', lang('damage'));
					$this->excel->getActiveSheet()->SetCellValue('H1', lang('list_assign'));
                    
                    $row = 2;
                    foreach ($_POST['val'] as $id) {
                       $resourceinfo=$this->resource_model->getresourceinfo($id)->row();
						
                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $resourceinfo->purchase_date );
                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $resourceinfo->resource);
                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $resourceinfo->name);
						$this->excel->getActiveSheet()->SetCellValue('D' . $row, $resourceinfo->model);
                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $resourceinfo->serial_no);
						$this->excel->getActiveSheet()->SetCellValue('F' . $row, $resourceinfo->warranty);
                        $this->excel->getActiveSheet()->SetCellValue('G' . $row, $resourceinfo->damage);
						$this->excel->getActiveSheet()->SetCellValue('H' . $row, $resourceinfo->assign);
                        $row++;
                    }

                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                    $filename = 'resource_' . date('Y_m_d_H_i_s');
						
                    if ($this->input->post('form_action') == 'export_pdf') {
                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));
                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);
                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
                        require_once(APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php");
                        $rendererName = PHPExcel_Settings::PDF_RENDERER_MPDF;
                        $rendererLibrary = 'MPDF';
                        $rendererLibraryPath = APPPATH . 'third_party' . DIRECTORY_SEPARATOR . $rendererLibrary;
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
			}
			else {
                $this->session->set_flashdata('error', lang("no_resource_selected"));
                redirect($_SERVER["HTTP_REFERER"]);
            }
		}
			else {
            $this->session->set_flashdata('error', validation_errors());
            redirect($_SERVER["HTTP_REFERER"]);
        
		}
		}
	
	
	 public function delete($id=null)
     {
   $this->sma->checkPermissions('index', true);
	  if ($this->input->get('user_id')) {

            $id = $this->input->get('user_id');

        }

    if($this->resource_model->deleteresource($id))
        {
			$this->load->helper("file");
			if(file_exists($this->upload_path.'resource/image/'.$id)){
					
					delete_files($this->upload_path.'resource/image/'.$id);
				}
				if(file_exists($this->upload_path.'resource/bill/'.$id)){
				
					delete_files($this->upload_path.'resource/bill/'.$id);
				}
         if ($this->input->is_ajax_request()) {

            echo "resource Delete sucessfully";die();

            }

            $this->session->set_flashdata('message', 'resource Delete sucessfully');

            redirect('resource');

        }


}
public function warranty_calc($a,$b){
            echo $a.",".$b;
            return "months";
        }
	
	 public function getresources() 

    {
		

        //$this->sma->checkPermissions('index');



        //if ((!$this->Owner || !$this->Admin)) {

            $user = $this->site->getUser();

        //}
	 $resourceinfo=$this->resource_model->getresourceinfo1()->row();
 //$resourceinfonew=$this->resource_model->getresourceinfo12()->result();
//echo $resourceinfo;
//die();
	 

		//$warranty = $resourceinfo->purchase_date + $resourceinfo->warranty." months";
		//$date1=date_create($warranty);// this is your warranty time after 2 years
		//$warranty = $resourceinfo->purchase_date;
		//$warranty = strtotime(date("Y-m-d", strtotime($warranty)) . " +6 month");
		//$warranty = date("Y-m-d",$warranty);
		//echo $warranty;
	//	die();
		//$date2=date_create(date("Y-m-d H:i:s")) ;//this gives current time
		//$diff=date_diff($date1,$date2);
		//$remainingwarranty1=$diff->format("%m months");
		//echo $noOfDays;
	

        $detail_link = anchor('resource/view/$1', '<i class="fa fa-file-text-o"></i>' . lang('invoice_details'));

        $edit_link = anchor('resource/edit/$1', '<i class="fa fa-edit"></i>' . lang('edit_sale'), 'class="sledit"');

        $pdf_link = anchor('resource/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));

        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"

        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('resource/delete/$1') . "'>"

        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "

        . lang('delete_sale') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'

        . lang('actions') . ' <span class="caret"></span></button>

        <ul class="dropdown-menu pull-right" role="menu">

            <li>' . $detail_link . '</li>

            <li>' . $edit_link . '</li>

            <li>' . $pdf_link . '</li>

            <li>' . $delete_link . '</li>

        </ul>

    </div></div>';

    
	  //echo $remainingwarranty1;
	  //die();
	  
        $this->load->library('datatables');
       
        $this->datatables->select("id, DATE_FORMAT(purchase_date, '%d-%m-%Y') as date, resource, name, model, serial_no,damage,assign")->from('sma_resource');
      //$this->datatables->add_column("Remaining Warranty","purchase_date","warranty",$this->warranty_calc());
      //$this->datatables->add_column("Remaining Warranty",$remainingwarranty1,"months");
        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();

		}


    public function add($quote_id='') {
		
        $this->sma->checkPermissions();
		
        $this->form_validation->set_rules('payment_date', lang("payment_date"), 'required');
		
        $this->form_validation->set_rules('resource', lang("slresource"), 'required');
		
        $this->form_validation->set_rules('resourcename', lang("slresourcename"), 'required');
		
		$this->form_validation->set_rules('model', lang("model"), 'required');

		$this->form_validation->set_rules('serial_no', lang("resserial_no"), 'required');

		$this->form_validation->set_rules('warranty', lang("warranty"), 'required');

		$this->form_validation->set_rules('damage', lang("damage"), 'required');

		//$this->form_validation->set_rules('assign', lang("assign"), 'required');

        if ($this->form_validation->run() == true && $_FILES['bill']['size']>0) {

            if ($this->Owner || $this->Admin) {
                $p_date = str_replace('/','-',$this->input->post('payment_date'));
                $payment_date=date("Y-m-d", strtotime($p_date));
            } else {
                $payment_date = date('Y-m-d');
            }
			
            $resource = $this->input->post('resource');

            $resourcename = $this->input->post('resourcename');
			
			$model = $this->input->post('model');
			
            $warranty = $this->input->post('warranty');

			$serial_no = $this->input->post('serial_no');
			
			$damage = $this->input->post('damage');
			
			$assign = $this->input->post('assign');

            if(isset($assign) && $assign!=''){
                $user = $this->ion_auth->user($assign)->row();
                $assign_status = 'Yes';
            } else{
                $assign_status = 'No';
            }
			
			$data = array(
							'purchase_date'=>$payment_date,
							'resource'=>$resource,
							'name'=>$resourcename,
							'model'=>$model,
							'serial_no'=>$serial_no,
							'warranty'=>$warranty."months",
							'damage'=>$damage,
							'assign'=>$assign_status,
							'image'=>'',
							'bill'=>'',
							);
			$insert_data = $this->resource_model->insert_resource($data);
			if($assign_status=='Yes'){
                $data_resource_activity = array(
                                                'RID'=>$insert_data,
                                                'user_id'=>$assign,
                                                'name'=>$user->first_name.' '.$user->last_name,
                                                'modified_date'=>date('Y-m-d'),
                                                'status'=>'Assigned'
                                            );
               $add_resource_activity = $this->resource_model->insert_resource_activity($data_resource_activity);
             
            }
            
			
			if($_FILES['resource_image']['size'] > 0) {

            
				
				mkdir($this->upload_path.'resource/image/'.$insert_data);
				
                $this->load->library('upload');

                $config['upload_path'] = $this->upload_path.'resource/image/'.$insert_data;

                $config['allowed_types'] = $this->image_types;

                $config['max_size'] = $this->allowed_file_size;

                $config['overwrite'] = false;

                $config['encrypt_name'] = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('resource_image')) {

                    $error = $this->upload->display_errors();

                    $this->session->set_flashdata('error', $error);

                    redirect($_SERVER["HTTP_REFERER"]);

                }

              $image = $this->upload->file_name;

                $data['image'] = $image;
				
				$this->resource_model->update_image($insert_data,$data);

            }
			
			if($_FILES['bill']['size'] > 0) {
				
				mkdir($this->upload_path.'resource/bill/'.$insert_data);
				
                $this->load->library('upload');

                $config['upload_path'] = $this->upload_path.'resource/bill/'.$insert_data;

                $config['allowed_types'] = $this->image_types;

                $config['max_size'] = $this->allowed_file_size;

                $config['overwrite'] = false;

                $config['encrypt_name'] = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('bill')) {

                    $error = $this->upload->display_errors();

                    $this->session->set_flashdata('error', $error);

                    redirect($_SERVER["HTTP_REFERER"]);

                }

               $bill = $this->upload->file_name;

                $data['bill'] = $bill;
				
				$this->resource_model->update_image($insert_data,$data);


            }
			
			$this->session->set_flashdata('message', lang("resource_added"));
			redirect('resource');
		} else {
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));
			if(!empty($_FILES) && $_FILES['bill']['size']==0){
				$this->data['error'] = 'Please select a bill to upload.';
			}
            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('resource'), 'page' => lang('resource')), array('link' => '#', 'page' => lang('add_resource')));
            $this->data['meta'] = array('page_title' => lang('add_resource'), 'bc' => $bc);
			$this->load->view($this->theme . 'resource/add',$this->data);
			}
			
			
		
    }
	/*Added on 28.12.2017 by abhinaba */
	 function edit($id = NULL)
		 {
			$this->sma->checkPermissions(); 
        if (!$id || empty($id)) {
            redirect('resource');
        }
		 $this->data['title'] = lang('edit_resource');

            $resourceinfo = $this->resource_model->getresourceinfo($id)->row();
            $resourceinfo1 = $this->resource_model->getresourceassigneduser($id);

     /* print_r($resourceinfo1); 
       die();*/
       
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['resourceinfo'] = $resourceinfo;
            $this->data['resourceinfo1'] = $resourceinfo1;
           /*print_r($resourceinfo1);
       die();*/
       

        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        
        
        $this->data['id'] = $id;
        //print_r($this->data);die();
       $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('resource'), 'page' => lang('resource')), array('link' => '#', 'page' => lang('edit_resource')));
            $this->data['meta'] = array('page_title' => lang('edit_resource'), 'bc' => $bc);
			$this->load->view($this->theme . 'resource/edit',$this->data);
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
	/*Added on 28.12.2017 by abhinaba */
	
	  public function edit_resource($id = null)

    {
		
 if ($this->input->get('id')) {

             $id = $this->input->get('id');

        }
		$resourceinfo=$this->resource_model->getresourceinfo($id)->row();
        $resourceinfo11=$this->resource_model->getresourceinfo11($id)->row();
		if($this->input->post()){
		//print_r($this->input->post());
        $this->form_validation->set_rules('payment_date', lang("payment_date"), 'required');
		
        $this->form_validation->set_rules('resource', lang("slresource"), 'required');
		
        $this->form_validation->set_rules('resourcename', lang("slresourcename"), 'required');
		
		$this->form_validation->set_rules('model', lang("model"), 'required');

		$this->form_validation->set_rules('serial_no', lang("resserial_no"), 'required');

		$this->form_validation->set_rules('warranty', lang("warranty"), 'required');

		$this->form_validation->set_rules('damage', lang("damage"), 'required');

		//$this->form_validation->set_rules('assign', lang("assign"), 'required');
		/*if($this->input->post()){
				print_r($this->input->post());die();
				
			}*/
              /*if($this->input->post(''))*/


			$payment_date = str_replace('/','-',$this->input->post('payment_date'));
		   $pymt_date=date("Y-m-d", strtotime($payment_date));
            //$assign=$resourceinfo->assign;
           if(!empty($resourceinfo11)){
                $assign12=$resourceinfo11->user_id;   
           }else{
               $assign12='';
           }
            //echo $assign12;
            //die();
           
          $assign1=$this->input->post('assign');
          //echo $assign1; die();
          if(isset($assign1) && $assign1!=''){
               $user = $this->ion_auth->user($assign1)->row();
               if($assign12!='' && $assign1==$assign12){
                //die('1');
                   $assign_status = 'Yes';
                   $insertresourceactivity=0;
            }
             else
             {
                 //die('2');
                 $insertresourceactivity=1;
                  $assign_status = 'Yes';
             }
          }else{
            $updateresourceactivity=1;
                $assign_status = 'No';
            }
			if ($this->form_validation->run() == true) {
				
 			
          			$data = array(
							'purchase_date'=>$pymt_date,
							'resource'=>$this->input->post('resource'),
							'name'=>$this->input->post('resourcename'),
							'model'=>$this->input->post('model'),
							'serial_no'=>$this->input->post('serial_no'),
							'warranty'=>$this->input->post('warranty'),
							'damage'=>$this->input->post('damage'),
							'assign'=>$assign_status,
							'image'=>$resourceinfo->image,
							'bill'=>$resourceinfo->bill,
							);
							
							
		
			$update_data =$this->resource_model->update_resource($resourceinfo->id,$data);

            if($assign_status=='Yes' && $insertresourceactivity==1){

                    $data_resource_activity = array(
                                                'RID'=>$id,
                                                'user_id'=>$assign1,
                                                'name'=>$user->first_name.' '.$user->last_name,
                                                'modified_date'=>date('Y-m-d'),
                                                'status'=>'Assigned'
                                            );
                       $update_resource_activity = $this->resource_model->insert_new_resource_activity($resourceinfo->id,$data_resource_activity);
                  }
                  if($assign_status=='No' && $updateresourceactivity==1)
                    {
                     $this->resource_model->delete_resource1($resourceinfo->id);                     
                     }

				if($_FILES['resource_image']['size'] > 0) {
                     if(file_exists($this->upload_path.'resource/image/'.$resourceinfo->id))
                        {
                             $this->load->helper("file");
                            delete_files($this->upload_path.'resource/image/'.$resourceinfo->id);
                        }
                     else
                       {
				
				        mkdir($this->upload_path.'resource/image/'.$resourceinfo->id);
				        }
                $this->load->library('upload');

                $config['upload_path'] = $this->upload_path.'resource/image/'.$resourceinfo->id;

                $config['allowed_types'] = $this->image_types;

                $config['max_size'] = $this->allowed_file_size;

                $config['overwrite'] = false;

                $config['encrypt_name'] = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('resource_image')) {

                    $error = $this->upload->display_errors();

                    $this->session->set_flashdata('error', $error);

                    redirect($_SERVER["HTTP_REFERER"]);

                }

                $image = $this->upload->file_name;

                $data['image'] = $image;
				
				$this->resource_model->update_image($resourceinfo->id, $data);

            }
			
			if($_FILES['bill']['size'] > 0) {
              if(file_exists($this->upload_path.'resource/bill/'.$resourceinfo->id))
                        {
                             $this->load->helper("file");
                            delete_files($this->upload_path.'resource/bill/'.$resourceinfo->id);
                        }
                     else
                       {
                
                       mkdir($this->upload_path.'resource/bill/'.$resourceinfo->id);
                        }
				
				
				
                $this->load->library('upload');

                $config['upload_path'] = $this->upload_path.'resource/bill/'.$resourceinfo->id;

                $config['allowed_types'] = $this->image_types;

                $config['max_size'] = $this->allowed_file_size;

                $config['overwrite'] = false;

                $config['encrypt_name'] = true;

                $this->upload->initialize($config);

                if (!$this->upload->do_upload('bill')) {

                    $error = $this->upload->display_errors();

                    $this->session->set_flashdata('error', $error);

                    redirect($_SERVER["HTTP_REFERER"]);

                }

                $bill = $this->upload->file_name;

                $data['bill'] = $bill;
				
				$this->resource_model->update_bill($resourceinfo->id, $data);

            }
			
		
			$this->session->set_flashdata('message', lang("resource_updated"));
			redirect('resource');
			
		}
		
		}else{
        	
			
				
            	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('resource'), 'page' => lang('resource')), array('link' => '#', 'page' => lang('edit_sale')));

            	$meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);

            	$this->page_construct('resource/edit', $meta, $this->data);
			}
		}
	
	

	public function pdf($id = null,$view = null,$save_bufffer = null)
    {

        $this->sma->checkPermissions();

        if ($this->input->get('id'))
        {
           $id = $this->input->get('id');
        }
        
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error'); 
        $this->data['resourceinfo']=$this->resource_model->getresourceinfobyid($id);
        $this->data['resourceinfoactivity']=$this->resource_model->getresourceinfobyid1($id);
   
        //print_r($resource1);
        //die();
        /*$this->data['purchase_date'] =$resource->purchase_date;
        $this->data['Resource'] =$resource->resource;
        $this->data['Name'] =$resource->name;
        $this->data['Model'] =$resource->model;
        $this->data['Serial No'] =$resource->serial_no;
        $this->data['Warranty'] =$resource->warranty;
        $this->data['Damage'] =$resource->damage;
        $this->data['Assign'] =$resource->assign;*/
            
        //$name = lang("resource") . ".pdf";

        $name = lang("resource")."_".$id . "_" .date('Y_m_d_H_i_s').".pdf";
        $html = $this->load->view($this->theme . 'resource/pdf', $this->data, true);

        if ($view) {
            $this->load->view($this->theme . 'resource/pdf', $this->data);
        } elseif ($save_bufffer) {
            return $this->sma->generate_pdf($html, $name, $save_bufffer);
        } else {
            $this->sma->generate_pdf($html, $name, false);
        }

    }
    
	
	
    public function combine_pdf($resourceid)
    {

        //$this->sma->checkPermissions('pdf');

        foreach ($resourceid as $id1) {

            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
            $this->data['resourceinfo']=$this->resource_model->getresourceinfobyid($id1);
            $this->data['resourceinfoactivity']=$this->resource_model->getresourceinfobyid1($id1);
            $html_data = $this->load->view($this->theme . 'resource/pdf', $this->data, true);
            $html[] = array(
                'content' => $html_data
            );
        }
        
        $name = lang("resource") . ".pdf";
        $this->sma->generate_pdf($html,$name);

    }

}