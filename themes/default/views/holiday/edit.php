<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 
 <div class="box">
		 <div class="box-header">
		  <h2 class="blue"><i class="fa-fw fa fa-edit nb"></i><?=lang('edit_holiday');?></h2>
			</div>
			 <div class="box-content">
				<div class="row">
			<div class="col-lg-12">

		<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
								echo form_open_multipart('holiday/edit_holiday/' . $holidayinfo->id, $attrib);
								?>
								
		  <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                        	 <div class="form-group">
                                <?php echo lang('Title','Tiltle'); ?>
                                <div class="controls">
                                    <?php echo form_input('title',(isset($_POST['title']) ? $_POST['title'] :$holidayinfo->title), 'class="form-control" id="holidaytitle" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                    <?= lang("Holiday_Date", "HolidayDate"); ?>
                                    <?php echo form_input('holiday_date',(isset($_POST['holiday_date']) ? $_POST['holiday_date'] :$holidayinfo->holiday_date), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="holiday_date" required="required"'); ?>
                                </div>

                                  <div class="form-group">
                    <?= lang("type", "type"); ?>
                
                    <?php 
                        $holiday_type = array(
                                              
                                                'national holiday'=>'National Holiday',
                                                'state holiday'=>'State Holiday',
                                               
                                            );
                    
                    echo form_dropdown('holidaytype',$holiday_type,(isset($_POST['holidaytype']) ? $_POST['holidaytype'] :$holidayinfo->type), 'id="holidaytype" "required="required" class="form-control" style="width:100%;" required="required"'); ?>
                         </div>

                        </div>
                         <div class="col-md-6">
                             <div class="col-md-10">
                         	 <div class="form-group">
                                <?php echo lang('Description', 'Description'); 
                              
                              	?>
                                <div class="controls">
                                    <?php echo form_textarea('description',(isset($_POST['description']) ? $_POST['description'] :$holidayinfo->description), 'class="form-control"  id="description" '); ?>
                                </div>
                              
                            </div>

                      

                         </div>
                        </div>
                    </div>
                </div>
				<?php echo form_hidden('id', $id); ?>
                 <p><?php echo form_submit('update', lang('update'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
            </div>
        </div>
    </div>
</div>