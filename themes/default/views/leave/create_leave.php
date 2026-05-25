<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('create_leave'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'leave_form');
                echo form_open("leave/add", $attrib);
                ?>
                    <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                             <div class="form-group">
                                <?php echo lang('staff','staff'); ?>
                                <div class="controls">
                                    <?php echo form_input('Staff_id', (isset($_POST['Staff_id']) ? $_POST['Staff_id'] : ""), 'class="form-control" id="Staff_id" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                    <?= lang("start_Date", "start_Date"); ?>
                                    <?php echo form_input('start_Date',(isset($_POST['start_Date']) ? $_POST['start_Date'] : ""), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="start_Date" required="required"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("end_Date", "end_Date"); ?>
                                    <?php echo form_input('end_Date',(isset($_POST['end_Date']) ? $_POST['end_Date'] : ""), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="end_Date" required="required"'); ?>
                                </div>
                                <div class="form-group">
                                   
                                    <?php echo form_checkbox('include_date','1',' class="form-control"'); ?>
                                     <?= lang("include", "include"); ?>
                                </div>
                                  <div class="form-group">
                    <?= lang("leave_type", "leave_type"); ?>
                
                    <?php 
                        $leave_type = array(
                                              
                                                'CL'=>'CL',
                                                'ML'=>'ML',
                                               
                                            );
                    
                    echo form_dropdown('leave_type',$leave_type,(isset($_POST['leave_type']) ? $_POST['leave_type'] : ''), 'id="leave_type" "required="required" class="form-control" style="width:100%;"'); ?>
                         </div>
                          <div class="form-group">
                    <?= lang("payment_type", "payment_type"); ?>
                
                    <?php 
                        $payment_type = array(
                                               'unpaid'=>'UnPaid',
                                                'paid'=>'Paid',
                                               
                                               
                                            );
                    
                    echo form_dropdown('payment_type',$payment_type,(isset($_POST['payment_type']) ? $_POST['payment_type'] : ''), 'id="payment_type" "required="required" class="form-control" style="width:100%;"'); ?>
                         </div>
                          <div class="form-group">
                                <?php echo lang('reason','reason'); ?>
                                <div class="controls">
                                    <?php echo form_input('reason', (isset($_POST['reason']) ? $_POST['reason'] : ""), 'class="form-control" id="reason" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?php echo lang('description', 'description'); ?>
                                <div class="controls">
                                    <?php echo form_textarea('description',(isset($_POST['description']) ? $_POST['description'] : ""), 'class="form-control"  id="description"'); ?>
                                </div>
                            </div>

                        </div>
                        
                    </div>
                </div>

                 <p><?php echo form_submit('save', lang('save'), 'class="btn btn-primary" id="btn1"'); ?></p>

                <?php echo form_close(); ?>

             </div>
         </div>
     </div>
 </div>
 <script>
$(document).ready(function(){
   
        $('#Staff_id').select2({
             //alert('hello');
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "staff/suggestions",
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
 $('#btn1').click(function(){
    //alert('hi');
      var  start_date=$('#start_Date').val();
       var end_date=$('#end_Date').val();
       var sdate=new Date(start_date);
       var edate=new Date(end_date);
//alert(start_datestart_date);
    if(start_date=='' && end_date=='')
        {
         alert("Please enter required field");
            return false;
        }
        if(end_date<start_date)
        {
         alert("End date not less than Start date ");
         return false;
        }
    else
    {
    $('#leave_form').form_submit();
    }
   });
    
    });
</script>