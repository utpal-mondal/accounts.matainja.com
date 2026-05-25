<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   
    <link href="<?php echo $assets ?>styles/style.css" rel="stylesheet">
    
</head>
<body>
 <div class="box">
  <div class="box-header">

        <h2>Resource Details</h2>
        </div>
 <div class="box-content">

        <div class="row">

            <div class="col-lg-12">
        
          <p><?php echo "<br>".lang("payment_date").": ".$resourceinfo[0]->purchase_date; ?></p>
            <p><?php echo "<br>".lang("slresource").": ".$resourceinfo[0]->resource; ?></p>
            <p><?php echo "<br>".lang("slresourcename").": ".$resourceinfo[0]->name; ?></p>
            <?php echo "<br>".lang("model").": ".$resourceinfo[0]->model; ?></p>
            <p><?php echo "<br>".lang("resserial_no").": ".$resourceinfo[0]->serial_no; ?></p>
            <p><?php echo "<br>".lang("warranty").": ".$resourceinfo[0]->warranty."months"; ?></p>
            <p><?php 

            $purchase_date=$resourceinfo[0]->purchase_date;
           // echo $purchase_date;
           // die();
          $warranty= date('Y-m-d',strtotime($purchase_date. '+'.$resourceinfo[0]->warranty.'month' ));
           // $warranty = $resourceinfo->purchase_date+ $resourceinfo->warranty;
            //echo $warranty;
            //die();
            // $warranty = date($resourceinfo->purchase_date, strtotime("+".$resourceinfo->warranty ."months", strtotime($resourceinfo->purchase_date)));
              $date1=date_create($warranty);
              $date2=date_create(date("Y-m-d")) ;//this gives current time
              $diff=date_diff($date1,$date2);
              $remainingwarranty1=$diff->format("%y Year %m Month %d Day");
         

            echo "<br>".lang("Remaing_Warranty").": ".$remainingwarranty1; ?></p>
            <p><?php echo "<br>".lang("damage").": ".$resourceinfo[0]->damage; ?></p>
              </div>
              <div class="clearfix"></div> 
                       
                        <?php
                          if(!empty($resourceinfoactivity))
                          {
                            ?>
                            <div class="table-responsive">
                            <table class="table table-bordered table-hover table-striped">
                            <thead>
                            <tr>
                            <th><?= lang('no'); ?></th>
                            <th><?= lang('Rid'); ?></th>
                            <th><?= lang('Userid'); ?> </th>
                            <th><?= lang('slresourcename'); ?></th>
                            <th><?= lang('modifieddate'); ?></th>
                            <th><?= lang('status'); ?></th>
                        
                            </tr>
                            </thead>
                            <tbody>
                          <?php 
                        $r = 1;
                        foreach($resourceinfoactivity as $row1)
                        {
                            ?>
                                <tr>
                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>
                                <td><?php echo $row1->RID; ?> </td>
                                 <td><?php echo $row1->user_id; ?> </td>
                                   <td><?php echo $row1->name; ?> </td>
                                    <td><?php echo $row1->modified_date; ?> </td>
                                    <td><?php echo $row1->status; ?> </td>
                                  </tr>
                                   <?php
                            $r++;
                        }?>
                            	


                            </tbody>
                            </table>


            </div>
              <?php 
              }
              ?>
                  <div class="clearfix"></div>
                   
            </div>
            </div>
           