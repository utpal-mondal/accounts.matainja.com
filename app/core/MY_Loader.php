<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Loader extends CI_Loader{
	public $db_model;
    public $calendar_model;
    public $digital_file_types;
    public $products_model;
    public $digital_upload_path;
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
    public $cmt_model;
    public $action;
    public $user;
    public $user_id;
    public $Leave_model;
    public $App_model;
    public $staff_model;
    public $attendance_model;
    public $default_key;
    public $Staff_model;
    public $resource_model;
    public $invoice_model;
    public $reports_model;
    public $calendar;
        
    function __construct()
    {
        parent::__construct();
    }

    public function view($view, $vars = array(), $return = FALSE) 
    {
        $nv = $view;
        $path = explode('/', $view);
        if($path[0] != 'default') {
            $file = str_replace('/', DIRECTORY_SEPARATOR, $view).'.php';
            if(! file_exists(VIEWPATH.$file)) { 
                $len = count($path); $i = 0;
                $path[0] = 'default';  $nv = '';
                foreach($path as $p) {
                    if($i == $len - 1) {
                        $nv .= $p;
                    } else {
                        $nv .= $p.'/';
                    }
                    $i++;
                }
            }
        }
        
        // return $this->_ci_load(array('_ci_view' => $nv, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));

        if (method_exists($this, '_ci_object_to_array'))
        {
                return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
        } else {
                return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_prepare_view_vars($vars), '_ci_return' => $return));
        }
    }

}
