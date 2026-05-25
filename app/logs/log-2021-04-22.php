<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-04-22 09:56:48 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 10:30:01 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 10:30:08 --> Could not find the language line "5534.2000"
ERROR - 2021-04-22 10:30:08 --> Severity: Warning --> file_exists() expects parameter 1 to be a valid path, object given G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Settings.php 325
ERROR - 2021-04-22 10:30:08 --> Severity: Warning --> is_readable() expects parameter 1 to be a valid path, object given G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Settings.php 325
ERROR - 2021-04-22 10:30:08 --> Severity: 4096 --> Object of class Mpdf\Mpdf could not be converted to string G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Writer\PDF.php 64
ERROR - 2021-04-22 10:30:08 --> Severity: 4096 --> Object of class Mpdf\Mpdf could not be converted to string G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Writer\PDF.php 66
ERROR - 2021-04-22 10:30:08 --> Severity: 4096 --> Object of class Mpdf\Mpdf could not be converted to string G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Writer\PDF\mPDF.php 30
ERROR - 2021-04-22 10:30:08 --> Severity: error --> Exception: Unable to load PDF Rendering library G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Writer\PDF\mPDF.php 34
ERROR - 2021-04-22 10:30:08 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at G:\Ampps\www\accounts.matainja.com\system\core\Exceptions.php:271) G:\Ampps\www\accounts.matainja.com\system\core\Common.php 570
ERROR - 2021-04-22 10:43:56 --> Could not find the language line "5534.2000"
ERROR - 2021-04-22 10:43:56 --> Severity: Warning --> file_exists() expects parameter 1 to be a valid path, object given G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Settings.php 325
ERROR - 2021-04-22 10:43:56 --> Severity: Warning --> is_readable() expects parameter 1 to be a valid path, object given G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Settings.php 325
ERROR - 2021-04-22 10:43:56 --> Severity: 4096 --> Object of class Mpdf\Mpdf could not be converted to string G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Writer\PDF.php 64
ERROR - 2021-04-22 10:43:56 --> Severity: 4096 --> Object of class Mpdf\Mpdf could not be converted to string G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Writer\PDF.php 66
ERROR - 2021-04-22 10:43:56 --> Severity: 4096 --> Object of class Mpdf\Mpdf could not be converted to string G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Writer\PDF\mPDF.php 30
ERROR - 2021-04-22 10:43:56 --> Severity: error --> Exception: Unable to load PDF Rendering library G:\Ampps\www\accounts.matainja.com\app\third_party\PHPExcel\PHPExcel\Writer\PDF\mPDF.php 34
ERROR - 2021-04-22 10:43:56 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at G:\Ampps\www\accounts.matainja.com\system\core\Exceptions.php:271) G:\Ampps\www\accounts.matainja.com\system\core\Common.php 570
ERROR - 2021-04-22 10:56:10 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 10:56:10 --> Severity: Notice --> Undefined variable: payment_ref G:\Ampps\www\accounts.matainja.com\themes\default\views\sales\sale_by_csv.php 232
ERROR - 2021-04-22 10:56:14 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 10:56:14 --> Query error: Expression #16 of SELECT list is not in GROUP BY clause and contains nonaggregated column 'newmata_accounts.sma_sale_items.id' which is not functionally dependent on columns in GROUP BY clause; this is incompatible with sql_mode=only_full_group_by - Invalid query: SELECT *
FROM `sma_deliveries`
LEFT JOIN `sma_sale_items` ON `sma_sale_items`.`sale_id`=`sma_deliveries`.`sale_id`
GROUP BY `sma_deliveries`.`id`
ERROR - 2021-04-22 10:56:14 --> Severity: error --> Exception: Call to a member function num_rows() on bool G:\Ampps\www\accounts.matainja.com\app\libraries\Datatables.php 438
ERROR - 2021-04-22 13:12:48 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 13:12:54 --> Could not find the language line "Rid"
ERROR - 2021-04-22 13:12:54 --> Could not find the language line "Userid"
ERROR - 2021-04-22 13:13:07 --> Could not find the language line "Rid"
ERROR - 2021-04-22 13:13:07 --> Could not find the language line "Userid"
ERROR - 2021-04-22 13:15:14 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 13:44:24 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 13:44:43 --> Severity: Warning --> Invalid argument supplied for foreach() G:\Ampps\www\accounts.matainja.com\app\controllers\Leave.php 226
ERROR - 2021-04-22 13:44:43 --> Severity: Notice --> Undefined variable: user_data G:\Ampps\www\accounts.matainja.com\app\controllers\Leave.php 233
ERROR - 2021-04-22 13:44:43 --> Severity: Notice --> Undefined variable: user_data G:\Ampps\www\accounts.matainja.com\app\controllers\Leave.php 237
ERROR - 2021-04-22 13:44:43 --> Severity: Warning --> Invalid argument supplied for foreach() G:\Ampps\www\accounts.matainja.com\app\controllers\Leave.php 237
ERROR - 2021-04-22 13:44:43 --> Severity: Notice --> Undefined variable: staff_id G:\Ampps\www\accounts.matainja.com\app\controllers\Leave.php 261
ERROR - 2021-04-22 13:45:59 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 13:46:21 --> Severity: Warning --> mail(): Failed to connect to mailserver at &quot;localhost&quot; port 25, verify your &quot;SMTP&quot; and &quot;smtp_port&quot; setting in php.ini or use ini_set() G:\Ampps\www\accounts.matainja.com\system\libraries\Email.php 1902
ERROR - 2021-04-22 13:46:22 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 13:46:24 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-22'
ERROR - 2021-04-22 13:46:35 --> Severity: Notice --> Undefined variable: staff_id G:\Ampps\www\accounts.matainja.com\app\controllers\Leave.php 261
