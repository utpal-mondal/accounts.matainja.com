<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {
    public $benchmark;
    public $hooks;
    public $config;
    public $log;
    public $utf8;
    public $uri;
    public $router;
    public $output;
    public $security;
    public $input;
    public $lang;
    public $Settings;
    public $site;
    public $theme;
    public $data;
    public $loggedIn;
    public $default_currency;
    public $Owner;
    public $Customer;
    public $Supplier;
    public $Admin;
    public $GP;
    public $dateFormats;
    public $m;
    public $v;
    public $sma;
    public $session;
    public $parser;
    public $load;
    public $db;
    public $db_model;
    public $form_validation;
    public $calendar_model;
    public $digital_upload_path;
    public $products_model;
    public $digital_file_types;
    public $upload_path;
    public $thumbs_path;
    public $popup_attributes;
    public $allowed_file_size;
    public $image_types;
    public $sales_model;
    public $quotes_model;
    public $purchases_model;
    public $transfers_model;
    public $companies_model;
    public $action;
    public $cmt_model;
    public $email;
    public $user;
    public $user_id;
    public $Leave_model;
    public $App_model;
    public $staff_model;
    public $attendance_model;
    public $Staff_model;
    public $resource_model;
    public $invoice_model;
    public $reports_model;
    public $calendar;
    public $zend;
    
    function __construct()
    {
        parent::__construct();
        $this->Settings = $this->site->get_setting();
        if($sma_language = $this->input->cookie('sma_language', TRUE)) {
            $this->config->set_item('language', $sma_language);
            $this->lang->load('sma', $sma_language);
            $this->Settings->user_language = $sma_language;
        } else {
            $this->config->set_item('language', $this->Settings->language);
            $this->lang->load('sma', $this->Settings->language);
            $this->Settings->user_language = $this->Settings->language;
        }
        if($rtl_support = $this->input->cookie('sma_rtl_support', TRUE)) {
            $this->Settings->user_rtl = $rtl_support;
        } else {
            $this->Settings->user_rtl = $this->Settings->rtl;
        }
        $this->theme = $this->Settings->theme.'/views/';
        if(is_dir(VIEWPATH.$this->Settings->theme.DIRECTORY_SEPARATOR.'assets'.DIRECTORY_SEPARATOR)) {
            $this->data['assets'] = base_url() . 'themes/' . $this->Settings->theme . '/assets/';
        } else {
            $this->data['assets'] = base_url() . 'themes/default/assets/';
        }

        $this->data['Settings'] = $this->Settings;
        $this->loggedIn = $this->sma->logged_in();

        $this->Owner = NULL;
        $this->Customer = NULL;
        $this->Supplier = NULL;
        $this->Admin = NULL;
        $this->data['Owner'] = NULL;
        $this->data['Customer'] = NULL;
        $this->data['Supplier'] = NULL;
        $this->data['Admin'] = NULL;

        if($this->loggedIn) {
            $this->default_currency = $this->site->getCurrencyByCode($this->Settings->default_currency);
            $this->data['default_currency'] = $this->default_currency;
            $this->Owner = $this->sma->in_group('owner') ? TRUE : NULL;
            $this->data['Owner'] = $this->Owner;
            $this->Customer = $this->sma->in_group('customer') ? TRUE : NULL;
            $this->data['Customer'] = $this->Customer;
            $this->Supplier = $this->sma->in_group('supplier') ? TRUE : NULL;
            $this->data['Supplier'] = $this->Supplier;
            $this->Admin = $this->sma->in_group('admin') ? TRUE : NULL;
            $this->data['Admin'] = $this->Admin;

            if($sd = $this->site->getDateFormat($this->Settings->dateformat)) {
                $dateFormats = array(
                    'js_sdate' => $sd->js,
                    'php_sdate' => $sd->php,
                    'mysq_sdate' => $sd->sql,
                    'js_ldate' => $sd->js . ' hh:ii',
                    'php_ldate' => $sd->php . ' H:i',
                    'mysql_ldate' => $sd->sql . ' %H:%i'
                    );
            } else {
                $dateFormats = array(
                    'js_sdate' => 'mm-dd-yyyy',
                    'php_sdate' => 'm-d-Y',
                    'mysq_sdate' => '%m-%d-%Y',
                    'js_ldate' => 'mm-dd-yyyy hh:ii:ss',
                    'php_ldate' => 'm-d-Y H:i:s',
                    'mysql_ldate' => '%m-%d-%Y %T'
                    );
            }
            if(file_exists(APPPATH.'controllers'.DIRECTORY_SEPARATOR.'Pos.php')) {
                define("POS", 1);
            } else {
                define("POS", 0);
            }
            if(!$this->Owner && !$this->Admin) {
                $gp = $this->site->checkPermissions();
                $this->GP = $gp[0];
                $this->data['GP'] = $gp[0];
            } else {
                $this->data['GP'] = NULL;
            }
            $this->dateFormats = $dateFormats;
            $this->data['dateFormats'] = $dateFormats;
            $this->load->language('calendar');
            //$this->default_currency = $this->Settings->currency_code;
            //$this->data['default_currency'] = $this->default_currency;
            $this->m = strtolower($this->router->fetch_class());
            $this->v = strtolower($this->router->fetch_method());
            $this->data['m']= $this->m;
            $this->data['v'] = $this->v;
            $this->data['dt_lang'] = json_encode(lang('datatables_lang'));
            $this->data['dp_lang'] = json_encode(array('days' => array(lang('cal_sunday'), lang('cal_monday'), lang('cal_tuesday'), lang('cal_wednesday'), lang('cal_thursday'), lang('cal_friday'), lang('cal_saturday'), lang('cal_sunday')), 'daysShort' => array(lang('cal_sun'), lang('cal_mon'), lang('cal_tue'), lang('cal_wed'), lang('cal_thu'), lang('cal_fri'), lang('cal_sat'), lang('cal_sun')), 'daysMin' => array(lang('cal_su'), lang('cal_mo'), lang('cal_tu'), lang('cal_we'), lang('cal_th'), lang('cal_fr'), lang('cal_sa'), lang('cal_su')), 'months' => array(lang('cal_january'), lang('cal_february'), lang('cal_march'), lang('cal_april'), lang('cal_may'), lang('cal_june'), lang('cal_july'), lang('cal_august'), lang('cal_september'), lang('cal_october'), lang('cal_november'), lang('cal_december')), 'monthsShort' => array(lang('cal_jan'), lang('cal_feb'), lang('cal_mar'), lang('cal_apr'), lang('cal_may'), lang('cal_jun'), lang('cal_jul'), lang('cal_aug'), lang('cal_sep'), lang('cal_oct'), lang('cal_nov'), lang('cal_dec')), 'today' => lang('today'), 'suffix' => array(), 'meridiem' => array()));

        }
    }

    function page_construct($page, $meta = array(), $data = array()) {
        // Read flashdata
        $flash_message = isset($data['message']) ? $data['message'] : $this->session->flashdata('message');
        $flash_error = isset($data['error']) ? $data['error'] : $this->session->flashdata('error');
        $flash_warning = isset($data['warning']) ? $data['warning'] : $this->session->flashdata('warning');
        
        // In CI3 database driver, flashdata is stored under the actual key name.
        // Force-unset them so they never persist past this request.
        $this->session->unset_userdata(['message', 'error', 'warning']);
        
        // Only pass one message to view with priority: error > warning > message
        $meta['message'] = $flash_error ? '' : ($flash_warning ? '' : $flash_message);
        $meta['error'] = $flash_error;
        $meta['warning'] = $flash_error ? '' : $flash_warning;
        $meta['info'] = $this->site->getNotifications();
        $meta['events'] = $this->site->getUpcomingEvents();
        $meta['ip_address'] = $this->input->ip_address();
        $meta['Owner'] = $data['Owner'];
        $meta['Admin'] = $data['Admin'];
        $meta['Supplier'] = $data['Supplier'];
        $meta['Customer'] = $data['Customer'];
        $meta['Settings'] = $data['Settings'];
        $meta['dateFormats'] = $data['dateFormats'];
        $meta['assets'] = $data['assets'];
        $meta['GP'] = $data['GP'];
        $meta['qty_alert_num'] = $this->site->get_total_qty_alerts();
        $meta['exp_alert_num'] = $this->site->get_expiring_qty_alerts();
        $this->load->view($this->theme . 'header', $meta);
        $this->load->view($this->theme . $page, $data);
        $this->load->view($this->theme . 'footer');
    }

}
