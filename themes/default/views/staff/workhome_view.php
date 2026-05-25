
<style>
.modal-new
{
    min-width:400px; 
    max-width:400px;
}
.modal-header .close {
    margin-top: -12px;
    opacity: 0.4;
    position: absolute;
    right: 10px;
    top: 13px;
}
.modal-dialog.modal-new > .modal-content > .modal-header > p {
    margin: 0;
    font-size: 20px;
    font-weight: normal !important;
    text-transform: capitalize;
}
p.pswd {
    text-align: center;
    font-size: 21px;
    color: #9c9c9c;
}
.margin-top_outtime {
    margin-top: 15px;
}

</style>

<div class="modal-dialog modal-new">
    <div class="modal-content">
        <div class="modal-header">
          
           <p><strong><?php echo $userinfo->first_name.' '.$userinfo->last_name;?></strong></p>
            <button type="button" class="close " data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-1x">&times;</i>
            </button>
            </div>
        <div class="modal-body">
            <?php 
               
                 echo form_hidden('work_id',$userinfo->id,' id="insert_id" ');
                                   ?>
                <p class="startdate"> <?php echo lang('startdate','startdate'); ?></p>
                
              <div class="controls">
              <?php echo form_input('startdate',(isset($_POST['startdate']))?$workhome_info->work_date:date('d/m/Y'), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="startdate" required="required"'); ?>
                  </div>
                   <p class="end_date"> <?php echo lang('end_date','end_date'); ?></p>
            
              <div class="controls">
              <?php echo form_input('enddate',(isset($_POST['enddate']))?$_POST['enddate']:date('d/m/Y'), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="enddate" required="required"'); ?>
                  </div>
              <p class="Reason"> <?php echo lang('Reason', 'Reason'); ?></p>
            <div class="controls">
            <?php echo form_input('Reason', (isset($_POST['Reason']))?$_POST['Reason']:'', 'class="form-control" id="Reason" required="required"'); ?>
                </div>
               
                </div>
               
             <div class="modal-footer no-print">
    <?php echo form_button('update', lang('insert'), 'class="btn btn-primary" id="btn" data-id="'.$userinfo->id.'"'); ?>
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close'); ?></button>
    </div>
            <div class="clearfix"></div>
         
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#btn').unbind().click(function() {
    var id= $("input[name='work_id']").val();
    var start_date=$('#startdate').val();
     var end_date=$('#enddate').val();
     var reason=$('#Reason').val();
     
//document.write(today);
   if(id!='' && start_date!='' &&end_date!=''&&reason!='')
   {
    if(start_date<=end_date)
      {
                  $.ajax({
                       type   : 'POST',
                       url   : 'staff/homedate_add',
                       dataType: "json",
                       data: {
                           token: '<?php echo $this->security->get_csrf_hash();?>',
                             id:id,
                             start_date:start_date, 
                             end_date:end_date,
                             reason:reason
                         },
                   success : function(data){
                   console.log(data);
                    if(data.success==1)
                    {
                     
                      $('#myModalworkhome').modal('hide');

                    }
                    else if(data.error==1)
                      {
                      // $('#myModalworkhome').html(data.message);
                       //$('#myModalworkhome').modal('show');
                       alert(data.message);
                      // return false;
                     }
                    }
                    });
                }
                else{
                      alert('start date not less than end date');
                    }
      }
 

   });
 });
    </script>



  


 

    