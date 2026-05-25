<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('create_staff'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
             

                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("staff/add", $attrib);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo lang('Name *','Name'); ?>
                                <div class="controls">
                                    <?php echo form_input('staff_name', (isset($_POST['staff_name']) ? $_POST['staff_name'] : ""), 'class="form-control" id="name1" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('FatherName', 'FatherName'); ?>
                                <div class="controls">
                                    <?php echo form_input('staff_fathername', (isset($_POST['staff_fathername']) ? $_POST['staff_fathername'] : ""), 'class="form-control" id="f_name" required="required"'); ?>
                                </div>
                            </div>
                             
                                <div class="form-group">
                                    <?= lang('dobdate','dobdate'); ?>
                                    <?php echo form_input('dobdate', (isset($_POST['dobdate']) ? $_POST['dobdate'] : ""), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="podate" required="required"'); ?>
                                </div>
                                  
                                <div class="form-group">
                                    <?= lang('Join Date', 'Join Date'); ?>
                                    <?php echo form_input('joindate', (isset($_POST['joindate']) ? $_POST['joindate'] : ""), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="jodate" required="required"'); ?>
                                </div>
                           <div class="form-group">
                                <?php echo lang('address', 'address'); ?>
                                <div class="controls">
                                    <?php echo form_textarea('Address',(isset($_POST['Address']) ? $_POST['Address'] : ""), 'class="form-control"  id="Address" required="required"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?php echo lang('PresentAddress','PresentAddress'); ?>
                                <div class="controls">
                                    <?php echo form_textarea('PresentAddress',(isset($_POST['PresentAddress']) ? $_POST['PresentAddress'] : ""), 'class="form-control" id="PresentAddress" '); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?php echo lang('city', 'city'); ?>
                                <div class="controls">
                                       <?php echo form_input('city', (isset($_POST['city']) ? $_POST['city'] : ""), 'class="form-control" id="city" required="required"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?php echo lang('zipcode', 'zipcode'); ?>
                                <div class="controls">
                                   <?php echo form_input('zipcode', (isset($_POST['zipcode']) ? $_POST['zipcode'] : ""), 'class="form-control" id="zipcode" required="required"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= lang('gender', 'gender'); ?>
                                <?php
                                $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>
                              <div class="form-group">
                                <?php echo lang('personalemail', 'personalemail'); ?>
                                <div class="controls">
                                     <?php  echo form_input('personalemail', (isset($_POST['personalemail']) ? $_POST['personalemail'] : ""), 'class="form-control" id="personalemail" required="required"');  ?>
                                           </div></div>
                                            <div class="form-group">
                                <?php echo lang('businessemail', 'businessemail'); ?>
                                <div class="controls">
                                    <?php echo form_input('businessemail', (isset($_POST['businessemail']) ? $_POST['businessemail'] : ""), 'class="form-control" id="businessemail" required="required"'); ?>
                                </div>
                            </div>
                         
                        </div>
                   
                        
                <div class="col-md-6">
                  <div class="col-md-10">
                   

                             <div class="form-group">
                                <?= lang("Browse", "Browse") ?>
                               <input id="Browse" type="file" data-browse-label="<?= lang('Browse'); ?>" name="Browse" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                            </div>
                               <div class="form-group">
                                    <?= lang("Interview Date", "Interview Date"); ?>
                                    <?php echo form_input('interviewdate',(isset($_POST['interviewdate']) ? $_POST['interviewdate'] : ""), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="interviewdate" '); ?>
                                </div>
                                 <div class="form-group">
                                    <?= lang("interviewschedule", "interviewschedule"); ?>
                                    <?php echo form_input('interviewschedule', (isset($_POST['interviewschedule']) ? $_POST['interviewschedule'] : ""), 'class="form-control input-tip datetime"  data-date-format="yyyy-mm-dd" id="interviewschedule" '); ?>
                                </div>
                               
                                           
                             
                              <div class="form-group">
                                <?php echo lang('Phone','Phone'); ?>
                                    <div class="controls">
                                    <?php echo form_input('Phone', (isset($_POST['Phone']) ? $_POST['Phone'] : "") ,'class="form-control" id="Phone" required="required"'); ?>
                                    </div>
                            </div>
                          
                            <div class="form-group">
                                <?= lang("group", "group"); ?>
                              <?php
                              //print_r($groups);
                             // die();
                             $gp[''] = 'Select Group';
                             foreach ($groups as $group) {
								$gp[$group['id']] = $group['name'];
                              }
								  echo form_dropdown('group', $gp, (isset($_POST['group']) ? $_POST['group'] : ''), 'id="group" required="required" class="form-control" style="width:100%;"');
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
					
					echo form_dropdown('payment_mode',$payment_mode,(isset($_POST['payment mode']) ? $_POST['payment mode'] : ''), 'id="pay_mode" "required="required" class="form-control" style="width:100%;"'); ?>
                         </div>
                     <div id="dvacctnum" style="display: none">
    		      <?= lang("accountnumber", "accountnumber"); ?>
   				 <div class="controls">
                                    <?php echo form_input('accountnumber', '', 'class="form-control" id="accountnumber" required="required"'); ?>
                                    </div>
				</div>
                        <div class="form-group">
                        <?= lang('release_date','release_date'); ?>
                        <?php echo form_input('release_date', (isset($_POST['release_date']) ? $_POST['release_date'] : ""), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="release_date" '); ?>
                        </div>
                <div class="form-group">
                <?php echo lang('note', 'note'); ?>
                <div class="controls">
                <?php echo form_textarea('note',(isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="Note"'); ?>
                </div>  
                </div>
                <div class="form-group project_manager">
                <?php echo lang('project_manager', 'project_manager'); ?>
                <div class="controls">
                <?php echo form_input('project_manager', (isset($_POST['project_manager']) ? $_POST['project_manager'] : ""), 'class="form-control input-tip" id="project_manager1" '); ?></div></div>
               <div class="form-group">
                <?php echo lang('attendance_id', 'attendance_id'); ?>
                <div class="controls">
                <?php echo form_input('attendance_id', (isset($_POST['attendance_id']) ? $_POST['attendance_id'] : ""), 'class="form-control input-tip" id="attendance_id1" required="required" '); ?>
                </div>
                            </div>  



                  </div>
                    
                                
                                           
                </div>
                 </div>
                 </div>
                <p><?php echo form_submit('save', lang('save'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
      </div>    
    </div>
</div>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('.no').slideUp();
        $('#group').change(function (event) {
            var group = $(this).val();
            if (group == 1 || group == 2) {
                $('.no').slideUp();
            } else {
                $('.no').slideDown();
            }
        });
		$('#pay_mode').change(function()
		{
			if($(this).val()=='Cash')
			{
				$('#dvacctnum').hide();
				}
				else
				{
					$('#dvacctnum').show();
					}
			});
		
    });
</script>

<script type="application/javascript">
    //Get attendanceID
$(document).ready(function () {
        $('#attendance_id1').select2({
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
                        return {results: [{id: '', text: 'No Match Found'}]};
                    }
                }
            }
        });

 
  

     
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


        $('#project_manager1').select2({
            minimumInputLength: 1,
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
     });
</script>
