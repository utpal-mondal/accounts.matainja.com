<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo lang('attendance'); ?></title>

    <link href="<?php echo $assets ?>styles/style.css" rel="stylesheet">

    <style type="text/css">

        html, body {

            height: 100%;

            background: #FFF;

        }

        body:before, body:after {

            display: none !important;

        }

        .table th {

            text-align: center;
			border-color: #000 !important;
            padding: 5px;
            

        }

        .table td {

            padding: 4px;
			border-color: #000 !important;
            border:1px solid black;

        }
         .table tr{

            padding: 4px;
            border-color: #000 !important;
            border:1px solid black;

        }
		.order-table-last{
			height: 5px;
		}
		
		.table{
			border-color: #000 !important;
             border:1px solid black;
		}
		
    </style>

</head>
<body>
    <div class="row">
    	<div style="width:35%;float:left;text-align:left;margin-right:10px">
        <img width="80px" src="<?=base_url()?>/assets/images/matainja.jpg">
		</div>
        <div style="width:65%;float:left;padding-top:2px;">
         <h2 style="font-size:20px;">MATAINJA TECHNOLOGIES</h2>
        </div>
          <?php foreach($leave_array as $rownew): ?>
        <div class="col-lg-12">
        <div class="text-center" style="text-decoration:underline;">
        <p><?=lang("attendance_list"); ?></p>
        </div>
        <div class="clearfix"></div>
         <p style="text-align:center;"><?= $rownew['user_name'] ?></p>
          <div class="clearfix"></div>
       <div class="table-responsive" style="margin-top:20px;">
       <table class="table table-bordered table-hover" style="border: 1px solid #000;">
                <thead>
                <tr style="border: 1px solid black;">
               
                <th><?= lang("date_entry"); ?></th>
                <th><?= lang("in_time"); ?></th>
                <th><?= lang("out_time"); ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rownew['leave_data'] as $row): ?>
                    <tr style="border-color:#000;">
    <td class="order-table-cell" style="border-bottom: 1px solid black; vertical-align:middle;">
    <?= date('d-m-Y',strtotime($row->date_entry)).'-'.date("l", strtotime($row->date_entry)); ?>
</td>
<td class="order-table-cell" style="border-bottom: 1px solid black; width: 100px; text-align:right; vertical-align:middle;">
     <?=date('H:i:s',strtotime($row->in_time)); ?> 
</td>
<td class="order-table-cell" style="border-bottom: 1px solid black; width: 100px; vertical-align:middle;"><?= date('H:i:s',strtotime($row->out_time)); ?></td>

                    </tr>
                    <?php
                     endforeach;
                     ?>
                      </tbody>
                  </table>
             </div>
               </div>
         <pagebreak>
        <?php
          endforeach;
        ?>
    </div>
</body>
</html>