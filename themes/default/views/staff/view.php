<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
          <h4 class="modal-title" id="myModalLabel"><?= $user->first_name." ".$user->last_name; ?></h4>
        </div>
        <div class="modal-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered" style="margin-bottom:0;">
                    <tbody>
                    <tr>
                        <td><strong><?= lang("profile_pics"); ?></strong></td>
                        <td> 
                        
<?php if($staffinfo->upload=="")
{?>
	 <img src="<?php echo base_url()."/assets/images/male.png" ?>" width="150"/></strong></td>
	<?php } else { ?>
                          
 <img src="<?php echo base_url().$staffinfo->upload;?>" width="150"/></strong></td>
    <?php } ?>                </tr>
                    <tr>
                        <td><strong><?= lang("Name"); ?></strong></td>
                        <td><?= $user->first_name." ".$user->last_name; ?></strong></td>
                    </tr>
                     <tr>
                        <td><strong><?= lang("FatherName1"); ?></strong></td>
                        <td><?= $staffinfo->staff_fathername; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("dobdate11"); ?></strong></td>
                        <td><?= $staffinfo->dob; ?></strong></td>
                    </tr>
                     <tr>
                        <td><strong><?= lang("JoinDate11"); ?></strong></td>
                        <td><?= $staffinfo->joindate; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("Interview Date"); ?></strong></td>
                        <td><?= $interviewdate; ?></strong></td>
                    </tr>
                     <tr>
                        <td><strong><?= lang("Experience"); ?></strong></td>
                        <td><?= $remainingwarranty1; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("address11"); ?></strong></td>
                        <td><?= $staffinfo->address; ?></strong></td>
                    </tr>
                      <tr>
                        <td><strong><?= lang("PresentAddress"); ?></strong></td>
                        <td><?= $staffinfo->presentaddress; ?></strong></td>
                    </tr>
                    <tr>
                        <td><strong><?= lang("city"); ?></strong></td>
                        <td><?= $staffinfo->city; ?></strong></td>
                    </tr>
                     <tr>
                        <td><strong><?= lang("zipcode"); ?></strong></td>
                        <td><?= $staffinfo->zipcode; ?></strong></td>
                    </tr>
                     <tr>
                        <td><strong><?= lang("gender"); ?></strong></td>
                        <td><?= $user->gender; ?></strong></td>
                    </tr>
                     <tr>
                        <td><strong><?= lang("personalemail"); ?></strong></td>
                        <td><?=  $staffinfo->personalemail; ?></strong></td>
                    </tr>
                      <tr>
                        <td><strong><?= lang("businessemail"); ?></strong></td>
                        <td><?=  $user->email; ?></strong></td>
                    </tr>
                        <tr>
                        <td><strong><?= lang("Phone"); ?></strong></td>
                        <td><?=  $user->phone; ?></strong></td>
                    </tr>
                   
                    <tr>
                        <td><strong><?= lang("release_date"); ?></strong></td>
                       
                        <td><?=  $release_date; ?></strong></td>
                    </tr>
                    
                    <tr>
                        <td><strong><?= lang("note"); ?></strong></td>
                        <td><?=  $staffinfo->note; ?></strong></td>
                    </tr>
                    <?php if($project_manager!=''){ ?>
                     <tr>
                        <td><strong><?= lang("project_manager"); ?></strong></td>
                                  
                        <td><?=  $project_manager; ?></strong></td>
                    </tr>
                    <?php } ?>
                      <tr>
                        <td><strong><?= lang("attendance_id"); ?></strong></td>
                        <td><?=  $staffinfo->attendance_id; ?></strong></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer no-print">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?= lang('close'); ?></button>
             <a href="<?=site_url('staff/edit/'.$staffinfo->user_id);?>" class="btn btn-primary"><?= lang('edit_staff'); ?></a>
               
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>