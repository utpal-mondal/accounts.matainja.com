
<style>
.modal-new1
{
    min-width:800px; 
    max-width:800px;
     min-height: :800px; 
    max-height:800px;
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
.btn_change
{
      margin-top: 38px;
    padding: 5px;
}
.tble_list{
  margin:auto;
}
.increment_info
{
  text-align:center; 
}
/*p.pswd {
    text-align: center;
    font-size: 21px;
    color: #9c9c9c;
}
.margin-top_outtime {
    margin-top: 15px;
}

</style>

<div class="modal-dialog modal-new1">
    <div class="modal-content">
        <div class="modal-header">
        <p><strong><?php echo $staff_info->first_name.' '.$staff_info->last_name;?></strong></p>
            <button type="button" class="close " data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-1x">&times;</i>
         </div>
        <div class="modal-body">
          <div class="col-xs-12">
         <div class="row">
         <div class="col-xs-6 col-sm-3">
           <?php  echo form_hidden('attn_id',$staff_info->id,' id="insert_id" '); ?>
                <p class="prev_salary"> <?php echo lang('prev_salary','prev_salary'); ?></p>
            <div class="controls">
            <?php echo form_input('prev_salary', (isset($_POST['prev_salary']))?$_POST['prev_salary']:'', 'class="form-control" id="prev_salary" required="required"'); ?>
                </div>
</div>


         <div class="col-xs-6 col-sm-3">
                <p class="increment_amount"> <?php echo lang('increment_amount','increment_amount'); ?></p>
            <div class="controls">
            <?php echo form_input('increment_amount', (isset($_POST['increment_amount']))?$_POST['increment_amount']:'', 'class="form-control" id="increment_amount" required="required"'); ?>
                </div>
              </div>

         <div class="col-xs-6 col-sm-2">
                   <p class="effective_date"> <?php echo lang('effective_date','effective_date'); ?></p>
            
              <div class="controls">
              <?php echo form_input('effective_date',(isset($_POST['effective_date']))?$_POST['effective_date']:date('d/m/Y'), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="effective_date" required="required"'); ?>
                  </div> 
                  </div>  
                  <div class="col-xs-6 col-sm-2">
                <p class="remarks"> <?php echo lang('remarks','remarks'); ?></p>
            <div class="controls">
            <?php echo form_input('remarks', (isset($_POST['remarks']))?$_POST['remarks']:'', 'class="form-control" id="remarks" required="required"'); ?>
                </div>
              </div>  
                
     <div class="col-xs-6 col-sm-2">
    <?php echo form_button('update', lang('insert'), 'class="btn btn-primary btn_change" id="btn" data-id="'.$staff_info->id.'"'); ?>   
              </div>
               </div>
             </div>
                <div class="clearfix"></div>
               <div class="col-xs-12">
               <div class="row">
              <?php if(isset($sal_history))
              { 
              ?>
                <table class="table table-striped" style="margin-top:20px;">
                   <thead>
                  <tr>
                    <th>Previous Salary</th>
                    <th>Increment Amount</th>
                    <th>Gross Salary</th>
                    <th>Effected Date</th>
                    <th>Remarks</th>
                    </tr>
                     <thead>
                    <tbody class="increment_info">
                      <?php foreach ($sal_history as $inc_sal) {
                        ?>
                       <tr>

                        <td><?php echo $inc_sal->prev_salary; ?></td>
                          <td><?php echo $inc_sal->increment_amount; ?></td>
                            <td><?php echo $inc_sal->gross_salary; ?></td>
                              <td><?php echo $inc_sal->effected_date; ?></td>
                                <td><?php echo $inc_sal->remarks; ?></td>

                       </tr>
                    <?php  } ?>
                    </tbody>

                </table>
                <?php } ?>
               </div>
             </div>
        <div class="clearfix"></div>
         
        </div>
    </div>
    <script>
$(document).ready(function(){
    $('#btn').unbind().click(function() {
    var id= $("input[name='attn_id']").val();
    var prev_sal=parseInt($('#prev_salary').val());
    var increment_amount=parseInt($('#increment_amount').val());
    var effective_date=$('#effective_date').val();
    var rmks=$('#remarks').val();
    if(prev_sal!='' && increment_amount!='' && rmks!='')
    {
       if(!isNaN(prev_sal) && !isNaN(increment_amount))
       {
        if(isNaN(rmks))
        {
                   $.ajax({
                 type   : 'POST',
                 url   : 'staff/add_increment_info',
                 dataType: "json",
                 data: {
                     token: '<?php echo $this->security->get_csrf_hash();?>',
                       id:id,
                       prevsal:prev_sal, 
                       incrt_amount:increment_amount, 
                       effct_date:effective_date, 
                       remarks:rmks,
                   },
             success : function(data){
             console.log(data);
              if(data.success==1)
              {
                
                $('#myModalincrement').modal('hide');

              }
              else if(data.error==1)
                {
                 $('#myModalincrement').html(data.message);
                 $('#myModalincrement').modal('show');
               }
              }
              });
          }
          else
          {
             alert('plese input string value in the remarks field');
          }
        }
          else
          {
      alert('plese input integer value in the previous Salary and increment amount field');
          }
        }
       else{
              alert('Please fill up the required field');
           }
   });
 });
    </script>






  


 

    