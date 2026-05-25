<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo lang('leave'); ?></title>

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
             text-align: center;

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
        <?php 
        $i=1;
        $len = count($leave_array);
        foreach($leave_array as $rownew): ?>
        <div class="col-lg-12">
        <div class="text-center" style="text-decoration:underline;">
        <p><?=lang("leave_list"); ?></p>
        </div>
     <div class="clearfix"></div>
        <p style="text-align:center;"><?= $rownew['user_name'] ?></p>
               
             <div class="clearfix"></div>
             <div class="table-responsive" style="margin-top:20px;">
       <table class="table table-bordered table-hover table-striped" style="border: 1px solid #000;">
                <thead>
                <tr style="border: 1px solid black;">
                <th><?= lang("leave_date"); ?></th>
                <th><?= lang("leave_type"); ?></th>
                <th><?= lang("payment_type"); ?></th>
                <th><?= lang("status"); ?></th>
                <th><?= lang("subject"); ?></th>
              
               </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rownew['leave_data'] as $row): ?>
                    <tr style="border-color:#000;">

    <td class="order-table-cell" style="border-bottom: 1px solid black; vertical-align:middle;">
    <?= $row->leave_date. '('.date('l',strtotime($row->leave_date)).')'; ?>
</td>
    <td class="order-table-cell" style="border-bottom: 1px solid black; vertical-align:middle;">
    <?= $row->leave_type; ?>
</td>
    <td class="order-table-cell" style="border-bottom: 1px solid black; vertical-align:middle;">
    <?= $row->payment_type; ?>
</td>
    <td class="order-table-cell" style="border-bottom: 1px solid black; vertical-align:middle;">
    <?= $row->status; ?>
</td>
    <td class="order-table-cell" style="border-bottom: 1px solid black; vertical-align:middle;">
      <?= $row->subject; ?>
</td>
</tr>
                     <?php
                     endforeach;
                     /*end($rownew);
                     current($rownew);
                     $currentkey=$key($rownew);
                     $lastkey=$key($rownew);
                     if($currentkey==$lastkey)
                     {
                      break;
                     }*/
                     ?>

                      </tbody>
                  </table>
             </div>
        </div>
        <?php if ($i <= $len - 1) { ?>
       <pagebreak>
        <?php } ?>
        <?php
        $i++;
          endforeach;
        ?>
        
    </div>
</body>
</html>