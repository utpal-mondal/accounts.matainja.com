<?php defined('BASEPATH') or exit('No direct script access allowed');



class Invoice_management extends MY_Controller

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

        $this->lang->load('invoice', $this->Settings->user_language);

        $this->load->library('form_validation');

        $this->load->model(array('invoice_model','sales_model'));

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

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => '#', 'page' => lang('invoice')));

        $meta = array('page_title' => lang('invoice'), 'bc' => $bc);

        $this->page_construct('invoice/invoice_list', $meta, $this->data);
    }
	
	public function getinvoices()
    {

        $this->sma->checkPermissions('index');



        if ((!$this->Owner && !$this->Admin)) {

            $user = $this->site->getUser();

        }

        $detail_link = anchor('invoice_management/view/$1', '<i class="fa fa-file-text-o"></i>' . lang('invoice_details'));

        $duplicate_link = anchor('invoice_management/add?invoice_id=$1', '<i class="fa fa-plus-circle"></i> ' . lang('duplicate_sale'));


        $edit_link = anchor('invoice_management/edit/$1', '<i class="fa fa-edit"></i>' . lang('edit_sale'), 'class="sledit"');

        $pdf_link = anchor('invoice_management/pdf/$1', '<i class="fa fa-file-pdf-o"></i> ' . lang('download_pdf'));

        $delete_link = "<a href='#' class='po' title='<b>" . lang("delete_sale") . "</b>' data-content=\"<p>"

        . lang('r_u_sure') . "</p><a class='btn btn-danger po-delete' href='" . site_url('invoice_management/delete/$1') . "'>"

        . lang('i_m_sure') . "</a> <button class='btn po-close'>" . lang('no') . "</button>\"  rel='popover'><i class=\"fa fa-trash-o\"></i> "

        . lang('delete_sale') . "</a>";

        $action = '<div class="text-center"><div class="btn-group text-left">'

        . '<button type="button" class="btn btn-default btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">'

        . lang('actions') . ' <span class="caret"></span></button>

        <ul class="dropdown-menu pull-right" role="menu">

            <li>' . $detail_link . '</li>

            <li>' . $duplicate_link . '</li>

            <li>' . $edit_link . '</li>

            <li>' . $pdf_link . '</li>

            <li>' . $delete_link . '</li>

        </ul>

    	</div></div>';



        $this->load->library('datatables');

        $this->datatables->select("id, DATE_FORMAT(date, '%Y-%m-%d %T') as date, reference_no, biller, customer, currency, total_amount, payment_status, payment_mode, DATE_FORMAT(payment_date, '%Y-%m-%d') as payment_date")->from('sma_invoice');

        //}


       // $this->datatables->where('pos !=', 1); // ->where('sale_status !=', 'returned');

        /*if (!$this->Customer && !$this->Supplier && !$this->Owner && !$this->Admin && !$this->session->userdata('view_right')) {

            $this->datatables->where('created_by', $this->session->userdata('user_id'));

        } elseif ($this->Customer) {

            $this->datatables->where('customer_id', $this->session->userdata('user_id'));

        }*/

        $this->datatables->add_column("Actions", $action, "id");

        echo $this->datatables->generate();

	}


    public function add($quote_id='')
    {
		
		/*if($this->input->post()){
			print_r($this->input->post());die();
		}*/
		
        $this->sma->checkPermissions();

        $invoive_id = $this->input->get('invoice_id') ? $this->input->get('invoice_id') : NULL;

		if(isset($invoive_id) && $invoive_id!=''){

			$get_invoice_items=$this->invoice_model->getinvoiceitems($invoive_id);
			$duplicate_invoice = $this->invoice_model->duplicate_invoice($invoive_id);

			foreach($get_invoice_items as $inv_items){

				$data_item = array(
					'invoice_id' => $duplicate_invoice,
					'product_description' =>  $inv_items->product_description,
					'hour' => $inv_items->hour,
					'amount' =>$inv_items->amount,
					'price' => $inv_items->price
				);

				$insert_invoice_item = $this->invoice_model->insert_invoice_item($data_item);

			}

			if($duplicate_invoice){
				$this->session->set_flashdata('message', lang("invoice_copied"));
				redirect('invoice_management');
			}

		}

        $this->form_validation->set_rules('customer', lang("customer"), 'required');
		
        $this->form_validation->set_rules('date', lang("date"), 'required');
		
        $this->form_validation->set_rules('biller', lang("biller"), 'required');
		
		$this->form_validation->set_rules('currency', lang("currency"), 'required');
		
		$this->form_validation->set_rules('payment_mode', lang("payment_mode"), 'required');

        if ($this->form_validation->run() == true && ($this->input->post('description')!='') && $this->input->post('amount')!='') 
        {

            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('inv');

            if ($this->Owner || $this->Admin) {

                $date = $this->sma->fld(trim($this->input->post('date')));

            } else {

                $date = date('Y-m-d H:i:s');

            }

            
            $customer_id = $this->input->post('customer');

            $biller_id = $this->input->post('biller');
			
			$customer_details = $this->site->getCompanyByID($customer_id);
			
            $customer = $customer_details->company != '-' ? $customer_details->company : $customer_details->name;

            $biller_details = $this->site->getCompanyByID($biller_id);

            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
			
			$currency = $this->input->post('currency');
			
			$payment_mode = $this->input->post('payment_mode');
			
			$total_amount = $this->input->post('total_amount');
			
			$total_gst_amount = $this->input->post('total_gst_amount');
			
			$igst_amount = $this->input->post('igst_text');
			
			$sgst_amount = $this->input->post('sgst_text');
			
			$cgst_amount = $this->input->post('cgst_text');
			
			$igst_percentage = $this->input->post('igst_percentage');
			
			$sgst_percentage = $this->input->post('sgst_percentage');
			
			$cgst_percentage = $this->input->post('cgst_percentage');
			
			$commision_fee = $this->input->post('commision_fee');
			
			$discount = $this->input->post('discount_amount');
			
			if((isset($commision_fee)&&$commision_fee!='') && $commision_fee<$total_amount){
				$commision_fee_amount = $commision_fee;
				if(isset($total_gst_amount)&&$total_gst_amount!=''){
					$commision_fee_amount = ($commision_fee<$total_gst_amount)?$commision_fee:0;
				}
			}else{
				$commision_fee_amount = 0;
			}		
			
			if((isset($discount)&&$discount!='') && $discount<$total_amount){
				$discount_amount = $discount;
				if(isset($total_gst_amount)&&$total_gst_amount!=''){
					$discount_amount = ($discount<$total_gst_amount)?$discount:0;
				}
			}else{
				$discount_amount = 0;
			}	
			
			$data = array(
				'date'=>$date,
				'reference_no'=>$reference,
				'customer_id'=>$customer_id,
				'customer'=>$customer,
				'biller_id'=>$biller_id,
				'biller'=>$biller,
				'currency'=>$currency,
				'payment_mode' => $payment_mode,
				'commision_fees'=>(isset($commision_fee_amount)&&$commision_fee_amount!='')?$commision_fee_amount:0,
				'igst_percentage'=>(isset($igst_percentage)&&$igst_percentage!='')?$igst_percentage:0,
				'sgst_percentage'=>(isset($sgst_percentage)&&$sgst_percentage!='')?$sgst_percentage:0,
				'cgst_percentage'=>(isset($cgst_percentage)&&$cgst_percentage!='')?$cgst_percentage:0,
				'igst_amount'=>(isset($igst_amount)&&$igst_amount!='')?$igst_amount:0,
				'sgst_amount'=>(isset($sgst_amount)&&$sgst_amount!='')?$sgst_amount:0,
				'cgst_amount'=>(isset($cgst_amount)&&$cgst_amount!='')?$cgst_amount:0,
				'discount'=>(isset($discount_amount)&&$discount_amount!='')?$discount_amount:0,
				'total_amount'=>(isset($total_gst_amount)&&$total_gst_amount!='')?$total_gst_amount - $discount_amount - $commision_fee_amount:$total_amount - $discount_amount - $commision_fee_amount
			);

			
			$insert_data = $this->invoice_model->insert_invoice($data);
			

			$description = $this->input->post('description');
			
            $hour = $this->input->post('hour');
			
            $amount = $this->input->post('amount');
			
            $price = $this->input->post('price');
			
			$count = sizeof($description);
			
			/*echo "<pre>";
			echo "description";
		    print_r($description);
		    echo "</pre>";
		    exit();*/

			for($i=0;$i<$count;$i++){

				$data_item = array(
					'invoice_id' => $insert_data,
					'product_description' =>  $description[$i],
					'hour' => $hour[$i],
					'amount' => $amount[$i],
					'price' => $price[$i]
				);

				$insert_invoice_item = $this->invoice_model->insert_invoice_item($data_item);
			}

			$this->session->set_flashdata('message', lang("sale_added"));

			redirect('invoice_management');
			
		}else {

            $this->data['tax_rates'] = $this->site->getAllTaxRates();
			$this->data['currencies'] = $this->site->getAllCurrencies();
            $this->data['billers'] = $this->site->getAllCompanies('biller');
            $this->data['error'] = (validation_errors() ? validation_errors() : $this->session->flashdata('error'));

            $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('invoice_management'), 'page' => lang('invoice')), array('link' => '#', 'page' => lang('add_sale')));
            $meta = array('page_title' => lang('add_sale'), 'bc' => $bc);

			$this->page_construct('invoice/add', $meta, $this->data);

		}
    }
	
	public function edit($id = null)
    {

        $this->sma->checkPermissions();


        if ($this->input->get('id')) {

            $id = $this->input->get('id');

        }
		
		if($this->input->post()){

			$this->form_validation->set_rules('customer', lang("customer"), 'required');
		
			$this->form_validation->set_rules('date', lang("date"), 'required');
			
			$this->form_validation->set_rules('biller', lang("biller"), 'required');
			
			$this->form_validation->set_rules('currency', lang("currency"), 'required');

			$this->form_validation->set_rules('payment_mode', lang("payment_mode"), 'required');

			if ($this->form_validation->run() == true && $this->input->post('description')!='' && $this->input->post('amount')!='') 
			{

	            $reference = $this->input->post('reference_no') ? $this->input->post('reference_no') : $this->site->getReference('inv');

	            if ($this->Owner || $this->Admin) {

	                $date = $this->sma->fld(trim($this->input->post('date')));

	            } else {

	                $date = date('Y-m-d H:i:s');

	            }

	            $customer_id = $this->input->post('customer');

	            $biller_id = $this->input->post('biller');
				
				$customer_details = $this->site->getCompanyByID($customer_id);
				
	            $customer = $customer_details->company != '-' ? $customer_details->company : $customer_details->name;

	            $biller_details = $this->site->getCompanyByID($biller_id);

	            $biller = $biller_details->company != '-' ? $biller_details->company : $biller_details->name;
				
				$currency = $this->input->post('currency');

				$payment_mode = $this->input->post('payment_mode');

				$payment_status = $this->input->post('payment_status');

				$payment_date = $this->input->post('payment_date');

				$total_amount = $this->input->post('total_amount');
				
				$total_gst_amount = $this->input->post('total_gst_amount');
				
				$igst_amount = $this->input->post('igst_text');
				
				$sgst_amount = $this->input->post('sgst_text');
				
				$cgst_amount = $this->input->post('cgst_text');
				
				$igst_percentage = $this->input->post('igst_percentage');
				
				$sgst_percentage = $this->input->post('sgst_percentage');
				
				$cgst_percentage = $this->input->post('cgst_percentage');
				
				$commision_fee = $this->input->post('commision_fee');
				
				$discount = $this->input->post('discount_amount');
				
				if((isset($commision_fee)&&$commision_fee!='') && $commision_fee<$total_amount){
					$commision_fee_amount = $commision_fee;
					if(isset($total_gst_amount)&&$total_gst_amount!=''){
						$commision_fee_amount = ($commision_fee<$total_gst_amount)?$commision_fee:0;
					}
				}else{
					$commision_fee_amount = 0;
				}	
				
				if((isset($discount)&&$discount!='') && $discount<$total_amount){
					$discount_amount = $discount;
					if(isset($total_gst_amount)&&$total_gst_amount!=''){
						$discount_amount = ($discount<$total_gst_amount)?$discount:0;
					}
				}else{
					$discount_amount = 0;
				}		
				
				$data = array(
					'date'=>$date,
					'reference_no'=>$reference,
					'customer_id'=>$customer_id,
					'customer'=>$customer,
					'biller_id'=>$biller_id,
					'biller'=>$biller,
					'currency'=>$currency,
					'payment_mode' => $payment_mode,
					'payment_status' => $payment_status,
					'payment_date' => ($payment_status == 'paid' && $payment_date) ? $this->sma->fld($payment_date) : NULL,
					'commision_fees'=>(isset($commision_fee_amount)&&$commision_fee_amount!='')?$commision_fee_amount:0,
					'igst_percentage'=>(isset($igst_percentage)&&$igst_percentage!='')?$igst_percentage:0,
					'sgst_percentage'=>(isset($sgst_percentage)&&$sgst_percentage!='')?$sgst_percentage:0,
					'cgst_percentage'=>(isset($cgst_percentage)&&$cgst_percentage!='')?$cgst_percentage:0,
					'igst_amount'=>(isset($igst_amount)&&$igst_amount!='')?$igst_amount:0,
					'sgst_amount'=>(isset($sgst_amount)&&$sgst_amount!='')?$sgst_amount:0,
					'cgst_amount'=>(isset($cgst_amount)&&$cgst_amount!='')?$cgst_amount:0,
					'discount'=>(isset($discount_amount)&&$discount_amount!='')?$discount_amount:0,
					'total_amount'=>(isset($total_gst_amount)&&$total_gst_amount!='')?$total_gst_amount - $discount_amount - $commision_fee_amount:$total_amount - $discount_amount - $commision_fee_amount
				);
				
				$update_data = $this->invoice_model->update_invoice($id, $data);
				
				$description = $this->input->post('description');
				
	            $hour = $this->input->post('hour');
				
	            $amount = $this->input->post('amount');
				
	            $price = $this->input->post('price');
				
				$count = sizeof($description);
				
				$delete_invoice_item = $this->invoice_model->deleteinvoiceitems($id);
				
				for($i=0;$i<$count;$i++){
					$data_item = array(
						'invoice_id' => $id,
						'product_description' =>  $description[$i],
						'hour' => $hour[$i],
						'amount' => $amount[$i],
						'price' => $price[$i]
					);
					$insert_invoice_item = $this->invoice_model->insert_invoice_item($data_item);
				}

				$this->session->set_flashdata('message', lang("sale_updated"));
				redirect('invoice_management');
			
			}
		
		}else{
        	
			$inv = $this->invoice_model->getInvoicedetailByID($id);
			
			$this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
			
			$this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
			
			$this->data['billers'] = $this->site->getAllCompanies('biller');
			
			$this->data['inv'] = $inv;
			
			$this->data['detail']=$this->invoice_model->getinvoiceitems($id);
			
			$this->data['currencies'] = $this->site->getAllCurrencies();
			
        	$bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('invoice_management'), 'page' => lang('invoice')), array('link' => '#', 'page' => lang('edit_sale')));

        	$meta = array('page_title' => lang('edit_sale'), 'bc' => $bc);

        	$this->page_construct('invoice/edit', $meta, $this->data);
		}
	}
		
	public function view($id = null)
    {

        $this->sma->checkPermissions('index');

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $inv = $this->invoice_model->getInvoicedetailByID($id);

        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);

        $this->data['inv'] = $inv;

		$this->data['detail']=$this->invoice_model->getinvoiceitems($id);

        $bc = array(array('link' => base_url(), 'page' => lang('home')), array('link' => site_url('invoice_management'), 'page' => lang('invoice')), array('link' => '#', 'page' => lang('view')));

        $meta = array('page_title' => lang('view_sales_details'), 'bc' => $bc);

        $this->page_construct('invoice/view', $meta, $this->data);

    }
	
    public function suggestions()
    {

        $term = $this->input->get('term', true);

        $warehouse_id = $this->input->get('warehouse_id', true);

        $customer_id = $this->input->get('customer_id', true);



        if (strlen($term) < 1 || !$term) {

            die("<script type='text/javascript'>setTimeout(function(){ window.top.location.href = '" . site_url('welcome') . "'; }, 10);</script>");

        }



        $analyzed = $this->sma->analyze_term($term);

        $sr = $analyzed['term'];

        $option_id = $analyzed['option_id'];



        $warehouse = $this->site->getWarehouseByID($warehouse_id);

        $customer = $this->site->getCompanyByID($customer_id);

        $customer_group = $this->site->getCustomerGroupByID($customer->customer_group_id);

        $rows = $this->sales_model->getProductNames($sr, $warehouse_id);

        if ($rows) {

            $c = str_replace(".", "", microtime(true));

            $r = 0;

            foreach ($rows as $row) {

                unset($row->cost, $row->details, $row->product_details, $row->image, $row->barcode_symbology, $row->cf1, $row->cf2, $row->cf3, $row->cf4, $row->cf5, $row->cf6, $row->supplier1price, $row->supplier2price, $row->cfsupplier3price, $row->supplier4price, $row->supplier5price, $row->supplier1, $row->supplier2, $row->supplier3, $row->supplier4, $row->supplier5, $row->supplier1_part_no, $row->supplier2_part_no, $row->supplier3_part_no, $row->supplier4_part_no, $row->supplier5_part_no);

                $option = false;

                $row->quantity = 0;

                $row->item_tax_method = $row->tax_method;

                $row->qty = 1;

                $row->discount = '0';

                $row->serial = '';

                $options = $this->sales_model->getProductOptions($row->id, $warehouse_id);

                if ($options) {

                    $opt = $option_id && $r == 0 ? $this->sales_model->getProductOptionByID($option_id) : $options[0];

                    if (!$option_id || $r > 0) {

                        $option_id = $opt->id;

                    }

                } else {

                    $opt = json_decode('{}');

                    $opt->price = 0;

                }

                $row->option = $option_id;

                $pis = $this->site->getPurchasedItems($row->id, $warehouse_id, $row->option);

                if ($pis) {

                    foreach ($pis as $pi) {

                        $row->quantity += $pi->quantity_balance;

                    }

                }

                if ($options) {

                    $option_quantity = 0;

                    foreach ($options as $option) {

                        $pis = $this->site->getPurchasedItems($row->id, $warehouse_id, $row->option);

                        if ($pis) {

                            foreach ($pis as $pi) {

                                $option_quantity += $pi->quantity_balance;

                            }

                        }

                        if ($option->quantity > $option_quantity) {

                            $option->quantity = $option_quantity;

                        }

                    }

                }

                if ($row->promotion) {

                    $row->price = $row->promo_price;

                } elseif ($customer->price_group_id) {

                    if ($pr_group_price = $this->site->getProductGroupPrice($row->id, $customer->price_group_id)) {

                        $row->price = $pr_group_price->price;

                    }

                } elseif ($warehouse->price_group_id) {

                    if ($pr_group_price = $this->site->getProductGroupPrice($row->id, $warehouse->price_group_id)) {

                        $row->price = $pr_group_price->price;

                    }

                }

                $row->price = $row->price + (($row->price * $customer_group->percent) / 100);

                $row->real_unit_price = $row->price;

                $row->base_quantity = 1;

                $row->base_unit = $row->unit;

                $row->base_unit_price = $row->price;

                $row->unit = $row->sale_unit ? $row->sale_unit : $row->unit;

                $combo_items = false;

                if ($row->type == 'combo') {

                    $combo_items = $this->sales_model->getProductComboItems($row->id, $warehouse_id);

                }

                $units = $this->site->getUnitsByBUID($row->base_unit);

                $tax_rate = $this->site->getTaxRateByID($row->tax_rate);



                $pr[] = array('id' => ($c + $r), 'item_id' => $row->id, 'label' => $row->name . " (" . $row->code . ")", 'category' => $row->category_id, 

                    'row' => $row, 'combo_items' => $combo_items, 'tax_rate' => $tax_rate, 'units' => $units, 'options' => $options);

                $r++;

            }

            $this->sma->send_json($pr);

        } else {

            $this->sma->send_json(array(array('id' => 0, 'label' => lang('no_match_found'), 'value' => $term)));

        }

    }
	
	public function delete($id = null)
    {

        $this->sma->checkPermissions(null, true);

        if ($this->input->get('id')) {

            $id = $this->input->get('id');

        }

        if ($this->invoice_model->deleteinvoice($id)) {
			
			$delete_invoice_item = $this->invoice_model->deleteinvoiceitems($id);

            if ($this->input->is_ajax_request()) {

                echo lang("sale_deleted");die();

            }

            $this->session->set_flashdata('message', lang('invoice_deleted'));

            redirect('welcome');

        }

    }
	
	public function pdf($id = null, $view = null, $save_bufffer = null)
    {

        $this->sma->checkPermissions();

        if ($this->input->get('id')) {
            $id = $this->input->get('id');
        }

        $inv = $this->invoice_model->getInvoicedetailByID($id);

        $this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);

        $this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);

        $this->data['inv'] = $inv;

		$this->data['detail']=$this->invoice_model->getinvoiceitems($id);

	   
		// $f = new NumberFormatter("en", NumberFormatter::SPELLOUT);
		// $this->data['amount_in_words'] = $f->format($inv->total_amount).' '.lang('amount_only');
		$this->data['amount_in_words'] = $this->numberTowords($inv->total_amount); 

		/*echo "<pre>";
		echo "this->data: ";
	    print_r($this->data['amount_in_words']);
	    echo "</pre>";
	    exit();*/

        $name = lang("sale") . "_" . str_replace('/', '_', $inv->reference_no) . ".pdf";
		
		


        $html = $this->load->view($this->theme . 'invoice/pdf', $this->data, true);

        if (! $this->Settings->barcode_img) {

            $html = preg_replace("'\<\?xml(.*)\?\>'", '', $html);

        }



        if ($view) {

            $this->load->view($this->theme . 'invoice/pdf', $this->data);

        } elseif ($save_bufffer) {

            return $this->sma->generate_pdf($html, $name, $save_bufffer, $this->data['biller']->invoice_footer);

        } else {

 			$this->load->view($this->theme . 'invoice/pdf', $this->data);

           $this->sma->generate_pdf($html, $name, false, false, false, false, 5); //off due to mpdf not work  
 		}

    }
	
	public function invoice_actions()
	{

        if (!$this->Owner && !$this->GP['bulk_actions']) {

            $this->session->set_flashdata('warning', lang('access_denied'));

            redirect($_SERVER["HTTP_REFERER"]);

        }



        $this->form_validation->set_rules('form_action', lang("form_action"), 'required');



        if ($this->form_validation->run() == true) {



            if (!empty($_POST['val'])) {

                if ($this->input->post('form_action') == 'delete') {



                    $this->sma->checkPermissions('delete');
                    foreach ($_POST['val'] as $id) {
						if ($this->invoice_model->deleteinvoice($id)) {
							$delete_invoice_item = $this->invoice_model->deleteinvoiceitems($id);
        				}
                    }

                    $this->session->set_flashdata('message', lang("sales_deleted"));

                    redirect($_SERVER["HTTP_REFERER"]);



                } elseif ($this->input->post('form_action') == 'combine') {



                    $html = $this->combine_pdf($_POST['val']);



                } elseif ($this->input->post('form_action') == 'export_excel' || $this->input->post('form_action') == 'export_pdf') {



                    $this->load->library('excel');

                    $this->excel->setActiveSheetIndex(0);

                    $this->excel->getActiveSheet()->setTitle(lang('invoices'));

                    $this->excel->getActiveSheet()->SetCellValue('A1', lang('date'));

                    $this->excel->getActiveSheet()->SetCellValue('B1', lang('reference_no'));

                    $this->excel->getActiveSheet()->SetCellValue('C1', lang('biller'));

                    $this->excel->getActiveSheet()->SetCellValue('D1', lang('currency'));

                    $this->excel->getActiveSheet()->SetCellValue('E1', lang('list_currency'));

                    $this->excel->getActiveSheet()->SetCellValue('F1', lang('total_amount'));

                    $row = 2;

                    foreach ($_POST['val'] as $id) {

                        $inv = $this->invoice_model->getInvoicedetailByID($id);

                        $this->excel->getActiveSheet()->SetCellValue('A' . $row, $this->sma->hrld($inv->date));

                        $this->excel->getActiveSheet()->SetCellValue('B' . $row, $inv->reference_no);

                        $this->excel->getActiveSheet()->SetCellValue('C' . $row, $inv->biller);

                        $this->excel->getActiveSheet()->SetCellValue('D' . $row, $inv->customer);

                        $this->excel->getActiveSheet()->SetCellValue('E' . $row, $inv->currency);

                        $this->excel->getActiveSheet()->SetCellValue('F' . $row, $inv->total_amount);

                        $row++;

                    }



                    $this->excel->getActiveSheet()->getColumnDimension('A')->setWidth(20);

                    $this->excel->getActiveSheet()->getColumnDimension('B')->setWidth(20);

                    $this->excel->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

                    $filename = 'sales_' . date('Y_m_d_H_i_s');

                    if ($this->input->post('form_action') == 'export_pdf') {

                        $styleArray = array('borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)));

                        $this->excel->getDefaultStyle()->applyFromArray($styleArray);

                        $this->excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);

                        require_once APPPATH . "third_party" . DIRECTORY_SEPARATOR . "MPDF" . DIRECTORY_SEPARATOR . "mpdf.php";

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

            } else {

                $this->session->set_flashdata('error', lang("no_sale_selected"));

                redirect($_SERVER["HTTP_REFERER"]);

            }

        } else {

            $this->session->set_flashdata('error', validation_errors());

            redirect($_SERVER["HTTP_REFERER"]);

        }
	}
	
	public function combine_pdf($invoices_id)
    {

    	$this->sma->checkPermissions('pdf');


        foreach ($invoices_id as $id) {


            $this->data['error'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('error');

            $inv = $this->invoice_model->getInvoicedetailByID($id);

            if (!$this->session->userdata('view_right')) {

                $this->sma->view_rights($inv->created_by);

            }

            $inv = $this->invoice_model->getInvoicedetailByID($id);

			$this->data['customer'] = $this->site->getCompanyByID($inv->customer_id);
			
			$this->data['biller'] = $this->site->getCompanyByID($inv->biller_id);
			
			$this->data['inv'] = $inv;
			
			$this->data['detail']=$this->invoice_model->getinvoiceitems($id);
			
			// $f =  new NumberFormatter("en", NumberFormatter::SPELLOUT);
			// $this->data['amount_in_words'] = $f->format($inv->total_amount).' '.lang('amount_only');
			$this->data['amount_in_words'] = $this->numberTowords($inv->total_amount);

            $html_data = $this->load->view($this->theme . 'invoice/pdf', $this->data, true);

            $html[] = array(

                'content' => $html_data

                //'footer' => $this->data['biller']->invoice_footer,

            );

        }


        $name = lang("invoice") . ".pdf";
		
        /*echo "<pre>";
    	print_r($name);
    	echo "</pre>";
    	exit();*/
    	
		$this->sma->generate_pdf($html, $name, false, false, false, false, 5);

    }

    public function numberTowords(float $amount)
	{
		$amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
		// Check if there is any number after decimal
		$amt_hundred = null;
		$count_length = strlen($num);
		$x = 0;
		$string = array();
		$change_words = array(
	   		0 => '', 1 => 'One', 2 => 'Two',
			3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
			7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
			10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
			13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
			16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
			19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
			40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
			70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
		);

		$here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');

		while( $x < $count_length ) {

			$get_divider = ($x == 2) ? 10 : 100;
			$amount = floor($num % $get_divider);
			$num = floor($num / $get_divider);
			$x += $get_divider == 10 ? 1 : 2;

			if ($amount) {

				$add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
				$amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
				$string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.' 
				'.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. ' 
				'.$here_digits[$counter].$add_plural.' '.$amt_hundred;

			}else $string[] = null;
	    }
		/*echo "<pre>";
		echo "Implode_to_Rupees: ";
	    print_r($string);
	    echo "</pre>";
	    exit();*/
		$implode_to_Rupees = implode('', array_reverse($string));
		$get_paise = ($amount_after_decimal > 0) ? "And " . ($change_words[$amount_after_decimal / 10] . " 
		" . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';


		return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees' : '') . $get_paise;
	}

}