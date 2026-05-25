<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
 
 <div class="box">
		 <div class="box-header">
		  <h2 class="blue"><i class="fa-fw fa fa-edit nb"></i><?=lang('edit_staff');?></h2>
			</div>
			 <div class="box-content">
				<div class="row">
			<div class="col-lg-12">

		<?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
								echo form_open_multipart('staff/edit_staff/' . $user->id, $attrib);
								?>
								
		 <div class="col-md-12">
						<div class="col-md-5">
					<div class="form-group">
			<?php echo lang('Name *', 'Name'); ?>
		 				<div class="controls">
						<?php echo form_input('Name', $user->first_name." ".$user->last_name, 'class="form-control" id="first_name" required="required"'); ?>
								</div>
								</div>
						<div class="form-group">
						<?php echo lang('FatherName', 'FatherName'); ?>
								
						<div class="controls">
						<?php echo form_input('staff_fathername', $staffinfo->staff_fathername , 'class="form-control" id="fathername" required="required"'); ?>
							</div>
							</div>
							<div class="form-group">
							<?= lang("dobdate", "dobdate"); ?>
							<div class="controls">
							<?php echo form_input('dobdate',(isset($_POST['dobdate']) ? $_POST['dobdate'] : $staffinfo->dob), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="podate" required="required"'); ?>
								</div>
							    </div>
								<div class="form-group">
																  
								<?= lang("Join Date", "Join Date"); ?>
								<div class="controls">
								<?php echo form_input('joindate',(isset($_POST['joindate']) ? $_POST['joindate'] : $staffinfo->joindate), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="jodate" required="required"'); ?>
									</div>
																	
									</div>
									<div class="form-group">
									<?php echo lang('address','address'); ?>
								
								<div class="controls">
								<?php echo form_textarea('address',(isset($_POST['address']) ? $_POST['address'] :  $staffinfo->address) , 'class="form-control" id="address" '); ?>
									</div>
									</div>
									<div class="form-group">
									<?php echo lang('PresentAddress', 'PresentAddress'); ?>
								
									<div class="controls">
									<?php echo form_textarea('PresentAddress',(isset($_POST['PresentAddress']) ? $_POST['PresentAddress'] :  $staffinfo->presentaddress ), 'class="form-control" id="PresentAddress" '); ?>
									</div>
									</div>
									<div class="form-group">
									<?php echo lang('city', 'city'); ?>
								
									<div class="controls">
									<?php echo form_input('city', (isset($_POST['city']) ? $_POST['city'] : $staffinfo->city) , 'class="form-control" id="city" required="required"'); ?>
									</div>
									</div>
									<div class="form-group">
									<?php echo lang('zipcode', 'zipcode'); ?>
								
									<div class="controls">
									<?php echo form_input('zipcode', (isset($_POST['zipcode']) ? $_POST['zipcode'] :$staffinfo->zipcode) , 'class="form-control" id="zipcode" required="required"'); ?>
														</div>
													</div>
										<div class="form-group">
										<?= lang('gender', 'gender'); ?>
										<div class="controls"> 
										 <?php
						$ge[''] = array('male' => lang('male'), 'female' => lang('female'));
							echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : $user->gender), 'class="tip form-control" id="gender" required="required"');
														?>
										</div>
										</div>
								<div class="form-group">
							<?php echo lang('personalemail', 'personalemail'); ?>
									<div class="controls">
							 <?php  echo form_input('personalemail',(isset($_POST['personalemail']) ? $_POST['personalemail'] : $staffinfo->personalemail), 'class="form-control" id="personalemail" required="required"');  ?>
								   </div></div>
									</div>
								<div class="col-md-6 col-md-offset-1">
						<?php //if ($Owner && $id != $this->session->userdata('user_id')) { ?>
								
							<div class="row">
																					   
						<div class="panel-body" style="padding: 5px;">
						<div class="col-md-12">
						<div class="col-md-12">
						<div class="form-group">
						<?= lang("Browse", "Browse") ?>
					 <input id="Browse" type="file" data-browse-label="<?= lang('Browse'); ?>" name="Browse" data-show-upload="false" data-show-preview="false" class="form-control file">
					 <?php if($staffinfo->upload==""){ ?>
					<img src="<?php echo base_url()."/assets/images/male.png" ?>" width="150"/>
					<?php } else { ?>
					<img src="<?php echo base_url().$staffinfo->upload;?>" width="150"/>
							<?php } ?>
					</div>
					<div class="form-group">
					<?= lang("Interview Date", "Interview Date"); ?>
								
					<?php 
					if(($staffinfo->interviewdate)=="0000-00-00"){$interviewdate='';}else{$interviewdate=$staffinfo->interviewdate;}
						?>
					<?php echo form_input('interviewdate',(isset($_POST['interviewdate']) ? $_POST['interviewdate'] : $interviewdate), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="interviewdate" '); ?>
								</div>
						<div class="form-group">
					<?= lang("interviewschedule", "interviewschedule"); ?>
					<?php 
					if(($staffinfo->interviewschedule)=="0000-00-00 00:00:00"){$interviewschedule='';}else{$interviewschedule=$staffinfo->interviewschedule;}
				     ?>
					<?php echo form_input('interviewschedule',(isset($_POST['interviewschedule']) ? $_POST['interviewschedule'] : $interviewschedule), 'class="form-control input-tip datetime" data-date-format="yyyy-mm-dd" id="interviewschedule"'); ?>
								</div>
						<div class="form-group">
						<?php echo lang('businessemail', 'businessemail'); ?>
					   <div class="controls">
					<?php echo form_input('businessemail', (isset($_POST['businessemail']) ? $_POST['businessemail'] :$user->email), 'class="form-control" id="businessemail" required="required"'); ?>
								</div>
								</div>
							<div class="form-group">
								
							<?php echo lang('phone', 'phone'); ?>
							<div class="controls">
							<input type="text" name="phone" class="form-control" id="phone" required="required" value="<?= $user->phone ?>"></div></div>
							<div class="form-group">
							<?= lang("group", "group"); ?>
																							   
							<?php
							 $gp=array(''=>'Select');
						foreach ($groups as $group) {
						$gp[$group['id']] = $group['name'];
								}
						 echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : $staffinfo->group_id), 'id="group" required="required" class="form-control" style="width:100%;"');
						?>
						</div>
						<div class="form-group">
						<?= lang("payment_mode", "payment_mode"); ?>
								
						<?php 
						$payment_mode = array(
							'Cash'=>'Cash',
							'Bank'=>'Bank',
							'NEFT'=>'NEFT'
								           );
								
					echo form_dropdown('payment_mode',$payment_mode,(isset($_POST['payment_mode']) ? $_POST['payment_mode'] : $staffinfo->payment_mode), 'id="pay_mode" "required="required" class="form-control" style="width:100%;"'); ?>
						<div id="dvacctnum" <?php if($staffinfo->payment_mode=='Cash'){ ?>style="display: none" <?php } ?>>
						<?= lang("accountnumber", "accountnumber"); ?>
						<div class="controls">
						<?php echo form_input('accountnumber',(isset($_POST['accountnumber']) ? $_POST['accountnumber'] : $staffinfo->account_number) , 'class="form-control" id="accountnumber" required="required"'); ?>
							</div>
							</div>
								
							</div>
						<div class="form-group">
					<?= lang('release_date','release_date'); ?>
				<?php 
					if(($staffinfo->release_date)=="0000-00-00"){$release_date='';}else{$release_date=$staffinfo->release_date;}
				     ?>
			   <?php echo form_input('release_date', (isset($_POST['release_date']) ? $_POST['release_date'] :$release_date), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="release_date" '); ?>
								</div>
						<div class="form-group">
						<?php echo lang('note', 'note'); ?>
						<div class="controls">
						<?php echo form_textarea('note',$staffinfo->note,'class="form-control" id="Note"  '); ?>
						</div>  
						</div>
					<div class="form-group project_manager" <?php if($staffinfo->group_id==7){ ?> style="display: none;"<?php } ?>>
					<?php echo lang('project_manager', 'project_manager'); ?>
					<div class="controls">
				<?php echo form_input('project_manager', (isset($_POST['project_manager']) ? $_POST['project_manager'] : ''), 'class="form-control input-tip" id="project_manager1" '); ?></div></div>
				<div class="form-group">
				<?php echo lang('attendance_id', 'attendance_id'); ?>
				<div class="controls">
				<?php echo form_input('attendance_id', (isset($_POST['attendance_id']) ? $_POST['attendance_id'] : $staffinfo->attendance_id), 'class="form-control input-tip" '); ?></div></div>
																										
				</div>
				</div>
				</div>
				</div>
				</div>
				<?php //} ?>
				<?php echo form_hidden('id', $id); ?>
				<?php echo form_hidden($csrf); ?>
				</div>
				<p><?php echo form_submit('update', lang('update'), 'class="btn btn-primary"'); ?></p>
				<?php echo form_close(); ?>
				</div>
				</div>
				</div>
				</div>
				
		<script type="text/javascript">
$(document).ready(function(){
		  <?php 
if ($staffinfo) { ?>
		localStorage.setItem('attendance_id', '<?= $staffinfo->attendance_id; ?>');
	<?php }else{ ?>
	  localStorage.setItem('attendance_id', '');
	  <?php } ?>
	 });
</script>
<script type="text/javascript">
$(document).ready(function(){
		  <?php 
if ($staffinfo) { ?>


		localStorage.setItem('project_manager1', '<?= ($staffinfo->project_manager!=0)?$staffinfo->project_manager:''; ?>');
	<?php }else{ ?>
	  localStorage.setItem('project_manager1', '');
	  <?php } ?>
	 });
</script>
	<script>
		$(document).ready(function () {
			$('#change-password-form').bootstrapValidator({
				message: 'Please enter/select a value',
				submitButtons: 'input[type="submit"]'
			});
		   

  });
</script>
<script>
	   $(document).ready(function () {


		var $ataffattendance =$('#attendance_id');
	 $ataffattendance.change(function (e) {
	 localStorage.setItem('attendance_id', $(this).val());
	   // $('#slassign').val('');
	});
	 $('#attendance_id').select2({
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "staff/staffrfid",
                dataType: 'json',
                quietMillis: 15,
                data: function (term, page) {
                    return {
                        term: term,
                        limit: 10
                    };
                },
                results: function (data, page) {
                    if (data.results != null) {
                        return {results: data.results};
                    } else {
                        return {results: [{id: '', text: 'No Match Founddd'}]};
                    }
                }
            }
        });

	

	 
	 });
   
</script>
<script>
	   $(document).ready(function () {
		var $resource =$('#project_manager1');
	 $resource.change(function (e) {
	 localStorage.setItem('project_manager1`', $(this).val());
	   // $('#slassign').val('');
	});
$('#project_manager1').select2({
					 minimumInputLength: 1,
				  ajax: 
				  {
					url: site.base_url + "staff/suggestions_pm",
					dataType: 'json',
					quietMillis: 15,
					data: function (term, page)
						{
					  return {
						 term: term,
						 limit: 10
							};
						},
					results: function (data, page) {
					if (data.results != null) {
						return {results: data.results};
					} 
					else
					  {
						return {results: [{id: '', text: 'No Match Found'}]};
					  }
				}
			}
		});
	

	 if (project_manager1=localStorage.getItem('project_manager1')) {
	   //alert('123');
		$resource.val(project_manager1).select2({
			minimumInputLength: 1,
			data: [],
			initSelection: function (element, callback) {
				$.ajax({
					type: "get", async: false,
					url: site.base_url+"staff/getprojectid/" + $(element).val(),
					dataType: "json",
					success: function (data) {
						callback(data[0]);
					}
				});
			},

			ajax: {
				url: site.base_url + "staff/suggestions_pm",
				dataType: 'json',
				quietMillis: 15,
				data: function (term, page) {
					return {
						term: term,
						limit: 10
					};
				},
				results: function (data, page) {
					if (data.results != null) {
						return {results: data.results};
					} else {
						return {results: [{id: '', text: 'No Match Found'}]};
					}
				}
			}
   
		});
	 }
	 });
 </script>
	   <script type="application/javascript">
$(document).ready(function () {
	 
	 $('#group').on('change',function()
  {
  // alert('hi');
	var p = $(this).val();
   // alert(p);
	//alert('hi');
	if(p==7)
	{
	   //alert('hi');
	   $('#project_manager1').val('');
	  $('.project_manager').hide();
	}
	else
	{
		 $('.project_manager').show();
	 
}
});
});
   
</script>
	<?php if ($Owner && $id != $this->session->userdata('user_id')) { ?>
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function () {
			$('#group').change(function (event) {
				var group = $(this).val();
				if (group == 1 || group == 2) {
					$('.no').slideUp();
				} else {
					$('.no').slideDown();
				}
			});
			var group = <?=$user->group_id?>;
			if (group == 1 || group == 2) {
				$('.no').slideUp();
			} else {
				$('.no').slideDown();
			}
			$('#pay_mode').change(function()
		{
			if($(this).val()=='Cash')
			{
				$('#dvacctnum').hide();
				$('#accountnumber').val('');
				}
				else
				{
					$('#dvacctnum').show();
					}
			});

		});
	</script>



<?php } ?>
