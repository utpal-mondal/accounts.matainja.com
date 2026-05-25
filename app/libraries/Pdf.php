<?php
/*

Author: pakainfo
start Pdf.php file
Location: ./application/libraries/Pdf.php */ 
if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/*echo "<pre>";
echo "1123: ";
print_r(APPPATH);
echo "</pre>";
exit();*/
require_once APPPATH . '/libraries/tcpdf/tcpdf.php'; 

class Pdf extends TCPDF { 

	function construct() { 
		parent::construct(); 
	} 
}