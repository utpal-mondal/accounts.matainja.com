<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('add_attendance'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form', 'id' => 'add_form');
                echo form_open("attendance/add_attendance", $attrib);
                ?>
                    <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-4">
                             <div class="form-group">
                                <?php echo lang('staff_name','staff_name'); ?>
                                <div class="controls">
                                    <?php echo form_input('Staff_id', (isset($_POST['Staff_id']) ? $_POST['Staff_id'] : ""), 'class="form-control" id="Staff_id" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                    <?= lang("in_time", "in_time"); ?>
                                    <?php echo form_input('in_time',(isset($_POST['in_time']) ? $_POST['in_time'] : ""), 'class="form-control input-tip datetime" data-date-format="yyyy-mm-dd" id="in_time" required="required"'); ?>
                                </div>
                                <div class="form-group">
                                    <?= lang("out_time", "out_time"); ?>
                                    <?php echo form_input('out_time',(isset($_POST['out_time']) ? $_POST['out_time'] : ""), 'class="form-control input-tip datetime" data-date-format="yyyy-mm-dd" id="out_time" required="required"'); ?>
                                </div>
                              
                        <div class="form-group">
                        <?= lang("device_type", "device_type"); ?>
                
                    <?php 
                        $device_type = array('Manual'=>'MANUAL',
                                              'Rfid_machine'=>'RFID MACHINE',
                                              'WFH'=>'WORK FROM HOME'
                                            );
                    echo form_dropdown('device_type',$device_type,(isset($_POST['device_type']) ? $_POST['device_type'] : ''), 'id="device_type" "required="required" class="form-control" style="width:100%;"'); ?>
                         </div>
                           <div class="form-group">
                        <?= lang("is_late", "is_late"); ?>
                
                    <?php 
                        $late = array('1'=>'YES',
                                       '0'=>'NO',);
                    echo form_dropdown('late',$late,(isset($_POST['late']) ? $_POST['late'] : ''), 'id="late" "required="required" class="form-control" style="width:100%;"'); ?>
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
      var  start_date=$('#in_time').val();
       var end_date=$('#out_time').val();
       var sdate=new Date(start_date);
       var edate=new Date(end_date);
       myDate=start_date.split(" ");
  mydatenew = myDate[0].split('/');
  in_date= mydatenew[2]+"-"+mydatenew[1]+"-"+mydatenew[0];

   outDate=end_date.split(" ");
  outdatenew = outDate[0].split('/');
  out_date=outdatenew[2]+"-"+outdatenew[1]+"-"+outdatenew[0];
  //alert(out_date);
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
       if(in_date==out_date)
        {
          $('#add_form').form_submit();
        }
      else{
          alert('please select the current date');
          return false;  
          }
    }
   });
    
    });
</script>