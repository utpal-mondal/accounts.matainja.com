<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="box">
  <div class="box-header">

        <h2 class="blue"><i class="fa-fw fa fa-file"></i>Resource Details</h2>
        </div>
 <div class="box-content">

        <div class="row">

            <div class="col-lg-12"><?php $i=1;
            //print_r($resourceinfo1);die();
            if(!empty($resource_list))
            {

              ?>
                <div class="table-responsive">
                <table class="table table-striped table-bordered" style="margin-bottom:0;">
                  <thead>
                   <tr>
                    <th><?= lang("id"); ?></th>
                     <th><?= lang("Userid"); ?></th>
                     <th><?= lang("Rid"); ?> </th>
                     <th><?= lang("staff_name"); ?> </th>
                        <th>Resource Type</th>
                        <th>Resource Name</th>
                          <th>Model</th>
                          <th>Serial No</th>
                          <th>Modified Date</th>
                         <th><?= lang("status"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                <?php $i=1;
                foreach($resource_list as $resourceinfo12 ){ ?> 
                        <tr align="center">
                        <td><?php echo $i;?></td>
                        <td><?php echo $resourceinfo12->user_id;?></td>
                        <td><?php echo $resourceinfo12->RID;?></td>
                        <td><?php echo $resourceinfo12->staff_name;?></td>
                        <td><?php echo $resourceinfo12->resource;?></td>
                          <td><?php echo $resourceinfo12->name;?></td>
                         <td><?php echo $resourceinfo12->model;?></td>
                          <td><?php echo $resourceinfo12->serial_no;?></td>
                        <td><?php echo $resourceinfo12->modified_date;?></td>
                        <td><?php echo $resourceinfo12->status;?></td>
                        </tr>
                 <?php 
                        $i++;
                  }
                          ?>
                        </tbody>
                 
                    </table>
                 
                  </div>
                  <?php } else { ?>
                  <div>
                    <p align="center">No resources are assigned to this staff.</p>
                  </div>  
                  <?php } ?>
                </div>
                </div>
                </div>
                </div>