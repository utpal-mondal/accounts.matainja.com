<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Holiday extends MY_Controller
{
	 function __construct()
    {
        parent::__construct();
         if (!$this->loggedIn) {
            $this->session->set_userdata('requested_page', $this->uri->uri_string());
            $this->sma->md('login');
        }
         $this->lang->load('holiday', $this->Settings->user_language);
         $this->load->library(array('form_validation','ion_auth'));
          $this->load->model('holiday_model');
      }

        public function index() //Fetching the staff list
        {
        $this->sma->checkPermissions();
		
		$this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
			 	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('holiday')));
        		$meta = array('page_title' => lang('holiday'), 'bc' => $bc);
        		$this->page_construct('holiday/index', $meta, $this->data);
        		}

  public function getholiday() //Get holiday details
		  {
           	   /*if ( ! $this->Owner)
           	    {
                $this->session->set_flashdata('warning', lang('access_denied'));
                $this->sma->md();
        		    }*/
                 $this->sma->checkPermissions('index', true);
                 $edit_link = anchor('holiday/edit/$1', '<i class="fa fa-edit"></i>' . lang('edit_holiday'), 'class="sledit"');
                 


            $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'

        . lang('actions') . ' <span class="caret"></span></button>

        <ul class="dropdown-menu pull-right" role="menu">

         <li>' . $edit_link . '</li>
          
         
        </ul>

    </div></div>';
        		  $this->load->library('datatables');
        $this->datatables->select("id,title,description,holiday_date,type")
        ->from('sma_holiday_info')

        ->add_column("Actions",$action,"id");
      
        echo $this->datatables->generate();
		  }

				
			function add()
			{
				/* if (!$this->Owner) {
            		$this->session->set_flashdata('warning', lang("access_denied"));
           			 redirect($_SERVER["HTTP_REFERER"]);
           			}*/
                 $this->sma->checkPermissions();
                 $this->data['title'] = "Create Holiday";
			$this->form_validation->set_rules('title', lang("Title"), 'trim|required|alpha_numeric_spaces');
			$this->form_validation->set_rules('holiday_date', lang("Holiday_Date"), 'trim|required');
			//$this->form_validation->set_rules('description', lang("Description"),'trim|required');
			$this->form_validation->set_rules('holidaytype', lang("type"),'trim|required');
				 if ($this->form_validation->run() == true)
		          {
		          	$title=$this->input->post('title');
		          	$description=$this->input->post('description');
		          	$holiday_date=str_replace('/', '-',$this->input->post('holiday_date'));
		          	$holiday_datenew=date('Y-m-d',strtotime($holiday_date));
		          	$holiday_type=$this->input->post('holidaytype');
                    
                    $holiday_data=array(
                    'title'=>($this->input->post('title')!='')?$title:'',
                    'description'=>($this->input->post('description')!='')?$description:'',
                    'holiday_date'=>($this->input->post('holiday_date')!='')?$holiday_datenew:'',
                    'type'=>$holiday_type,
                    );
                    //print_r($holiday_data);
                    //die();

                    $this->holiday_model->registerholiday($holiday_data);
                   $this->session->set_flashdata('message', 'Holiday Added Successfully');
			redirect('holiday');

                     
		          }
       				  else{
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
		
			  $bc = array(array('link' =>base_url() , 'page' => lang('home')), array('link' => 'holiday', 'page' => lang('holiday')),array('link' => '#','page' => lang('create_holiday')));
        $meta = array('page_title' => lang('create_holiday'), 'bc' => $bc);
        $this->page_construct('holiday/create_holiday', $meta, $this->data);
			 
		  }

			}	
      function holiday_actions()
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
                               if ($id != $this->session->userdata('id')) {
                               $this->holiday_model->delete_holiday($id);
                             }
                          }
                              $this->session->set_flashdata('message', lang("delete_holiday"));
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

         function edit($id = NULL) //staff details editPage.
     {
        /* if (!$this->ion_auth->logged_in() || !$this->ion_auth->in_group('owner') && $id != $this->session->userdata('user_id')) {
            $this->session->set_flashdata('warning', lang("access_denied"));
            redirect($_SERVER["HTTP_REFERER"]);
        }*/
         $this->sma->checkPermissions();
        if (!$id || empty($id)) {
            redirect('holiday');
        }
     $this->data['title'] = lang('edit_holiday');

        
    $holidayinfo=$this->holiday_model->getholidayinfo($id)->row();
    //print_r($holidayinfo);
   // die();
    
       
       // $this->data['csrf'] = $this->_get_csrf_nonce();
      
    $this->data['holidayinfo'] =$holidayinfo;
        $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');
        $this->data['id'] = $id;

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('holiday'), 'page' => lang('holiday')), array('link' => '#', 'page' => lang('edit_holiday')));
        $meta = array('page_title' => lang('edit_holiday'), 'bc' => $bc);
        $this->page_construct('holiday/edit', $meta, $this->data);
     }

     function edit_holiday()
     {
        if ($this->input->post('id')) {
            $id = $this->input->post('id');
            }
        //echo $id;
     // die();
        $this->data['title'] = lang("edit_holiday");
    
    
       
      //$user = $this->ion_auth->user($id)->row();
        $holidayinfo=$this->holiday_model->getholidayinfo($id)->row();
        $this->form_validation->set_rules('title', lang("Title"), 'trim|required|alpha_numeric_spaces');
      $this->form_validation->set_rules('holiday_date', lang("Holiday_Date"), 'trim|required');
      //$this->form_validation->set_rules('description', lang("Description"),'trim|required');
      $this->form_validation->set_rules('holidaytype', lang("type"),'trim|required');
         if ($this->form_validation->run() == true)
              {
                $title=$this->input->post('title');
                $description=$this->input->post('description');
                $holiday_date=str_replace('/', '-',$this->input->post('holiday_date'));
                $holiday_datenew=date('Y-m-d',strtotime($holiday_date));
                $holiday_type=$this->input->post('holidaytype');
                    
                    $holiday_data=array(
                    'title'=>($this->input->post('title')!='')?$title:$holidayinfo->title,
                    'description'=>($this->input->post('description')!='')?$description:$holidayinfo->description,
                    'holiday_date'=>($this->input->post('holiday_date')!='')?$holiday_datenew:$holidayinfo->holiday_date,
                    'type'=>($this->input->post('holidaytype')!='')?$holiday_type:$holidayinfo->type,
                    );
                  //print_r($holiday_data);
                 // die();

                    $this->holiday_model->updateholiday($holidayinfo->id,$holiday_data);
                   $this->session->set_flashdata('message', 'Holiday Updated Successfully');
                  redirect('holiday');

                     
              }
               else
                 {
                    $this->session->set_flashdata('error', validation_errors());
                    redirect($_SERVER["HTTP_REFERER"]);
                  }

            }
      }