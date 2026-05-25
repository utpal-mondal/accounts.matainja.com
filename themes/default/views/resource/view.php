<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
  <div class="box">
  <div class="box-header">
  <h2 class="blue"><i class="fa-fw fa fa-file"></i>Resource Details</h2>
  </div>
 <div class="box-content">
 <?php if(isset($resourceinfo))
    { ?>
  <div class="row">
   

  
      <div class="col-lg-12">
        <div class="well well-sm">
          <div class="col-xs-6  border-right">
          <h2 class=""><?=lang("payment_date");?></h2>
          <p><?php echo $resourceinfo->purchase_date; ?></p>
            <h2 class=""><?=lang("slresource");?></h2>
            <p><?php echo $resourceinfo->resource; ?></p>
             <h2 class=""><?=lang("slresourcename");?></h2>
            <p><?php echo $resourceinfo->resource_name; ?></p>
             <h2 class=""><?=lang("model");?></h2>
            <?php echo $resourceinfo->model; ?></p>
             <h2 class=""><?=lang("resserial_no");?></h2>
            <p><?php echo $resourceinfo->serial_no; ?></p>
              <h2 class=""><?=lang("warranty1");?></h2>
            <p><?php echo $resourceinfo->warranty." months"; ?></p>
             <h2 class=""><?=lang("Remaing_Warranty");?></h2>
            <p><?php 
                $purchase_date=$resourceinfo->purchase_date;
                $warranty= date('Y-m-d',strtotime($purchase_date. '+'.$resourceinfo->warranty.'month' ));
              $date1=date_create($warranty);
              $date2=date_create(date("Y-m-d")) ;//this gives current time
              $diff=date_diff($date1,$date2);
              $remainingwarranty1=$diff->format("%y Year %m Month %d Day");
              echo $remainingwarranty1; ?></p>
              <h2 class=""><?=lang("damage");?></h2>
              <p><?php echo $resourceinfo->damage; ?></p>
              <h2 class=""><?=lang("assign_resource");?></h2>
               <p><?php echo $resourceinfo->name; ?></p>
              </div>
              <div class="col-xs-6">
               <div class="col-lg-4">
                <h2 class=""><?=lang("image");?></h2>
           <img src="<?php echo base_url()."assets/uploads/resource/image/".$resourceinfo->id."/".$resourceinfo->image;?>" width="150"/>
           </div>
          </div>
          
      <div class="clearfix"></div> 
                <?php $i=1;
           
            if(!empty($resourceinfo1))
            {

              ?>
                <div class="table-responsive">
                <table class="table table-striped table-bordered" style="margin-bottom:0;">
                  <thead>
                   <tr>
                    <th><?= lang("id"); ?></th>
                   <th><?= lang("Rid"); ?> </th>
                    <th><?= lang("Userid"); ?></th>
                        <th><?= lang("slresourcename"); ?></th>
                        <th><?= lang("modifieddate"); ?></th>
                         <th><?= lang("status"); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                <?php $i=1;
                foreach($resourceinfo1 as $resourceinfo12 ){ ?> 
                        <tr align="center">
                        <td><?php echo $i;?></td>
                        <td><?php echo $resourceinfo12->RID;?></td>
                        <td><?php echo $resourceinfo12->user_id;?></td>
                        <td><?php echo $resourceinfo12->name;?></td>
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
                  <?php 
                  }?> 
                  </div>
        </div>

      </div> 
               <div class="clearfix"></div>
               
               <div id="payment_buttons" class="row text-center padding10 no-print">
               <div class="col-xs-6 text-center">
               </div>
                <div class="col-xs-6 text-center">
               </div>
               <div class="clearfix"></div>
                 </div>
               <div class="buttons">

                  <div class="btn-group btn-group-justified">

                
                <div class="btn-group">

                        <a href="<?= site_url('resource/pdf/'.$resourceinfo->id) ?>" class="tip btn btn-primary" title="<?= lang('pdf') ?>">

                            <i class="fa fa-edit"></i> <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>

                        </a>

                    </div>

                    <div class="btn-group">

                        <a href="<?= site_url('resource/edit/' .$resourceinfo->id) ?>" class="tip btn btn-warning tip sledit" title="<?= lang('edit') ?>">

                            <i class="fa fa-edit"></i> <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>

                        </a>

                    </div>
                      <div class="btn-group">
                   <a href="<?php echo base_url()."assets/uploads/resource/bill/".$resourceinfo->id."/".$resourceinfo->bill;?>" download class="tip btn btn-primary" title="<?= lang('bill1') ?>">
                   <i class="fa fa-edit"></i> <span class="hidden-sm hidden-xs"><?= lang('bill1') ?></span>
                     </a>
        
                    </div>
                   <div class="btn-group">

                        <a href="#" class="tip btn btn-danger bpo"

                            title="<b><?= $this->lang->line("delete_sale") ?></b>"

                            data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('resource/delete/' .$resourceinfo->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"

                            data-html="true" data-placement="top"><i class="fa fa-trash-o"></i> 

                            <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>

                        </a>

                    </div>
                  </div>
                </div>
                 <?php } ?>
            </div>
          
     
   <!--  -->