<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2021-04-23 14:08:04 --> Query error: Incorrect DATE value: '0000-00-00' - Invalid query: SELECT SUM(quantity_balance) as alert_num
FROM `sma_purchase_items`
WHERE `expiry` IS NOT NULL
AND `expiry` != '0000-00-00'
AND `expiry` < '2021-07-23'
