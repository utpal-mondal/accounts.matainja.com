<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
p.out_time {
    text-align: right;
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
          
           <p><strong><?php echo $attenreport->first_name.' '.$attenreport->last_name;?><strong></strong></p>
            <button type="button" class="close " data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-1x">&times;</i>
            </button>
            </div>
        <div class="modal-body">
            <div class="row margin-top_outtime">
                
                       <?php 
                      if(isset($attenreport))
                       {
                        ?>
                       
                       
                   
                       <div class="col-md-4">
                        <p class="out_time">
                         <?= lang("out_time", "out_time"); ?></p>
                     </div>
                      <div class="col-md-8">
                       <div class="form-group">
                           
                            <div class="controls">
                                  <?php echo form_hidden('userid',$attenreport->user_id);
                                 echo form_hidden('input_time',$attenreport->in_time);
                                        
                                     echo form_hidden('attn_id',$attenreport->id,' id="update_id" ');
                                if($attenreport->out_time=='0000-00-00 00:00:00')
                                    {
                                        
                                 echo form_input('out_time','', 'class="form-control input-tip datetime" data-date-format="yyyy-mm-dd" id="podate" required="required"'); 
                                     }
                                     else
                                     {
                                       echo form_input('out_time',(isset($_POST['out_time']) ? $_POST['out_time'] : $attenreport->out_time), 'class="form-control input-tip datetime" data-date-format="yyyy-mm-dd" id="podate" required="required"'); 
                                      }
                        ?>
                                </div>
                                </div>
                               
                            </div>
                       
                       <?php } ?>
                   </div>
            </div>
                <div class="modal-footer no-print">
                    
                    <?php echo form_button('update', lang('update'), 'class="btn btn-primary" id="btn" data-id="'.$attenreport->id.'"'); ?>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close'); ?></button>
                </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
    $('#btn').unbind().click(function() {
     //$(document).on('click','#btn',function(){
      var new_id = $(this).attr('data-id');
      var out_time=$('#podate').val();
      var id= $("input[name='attn_id']").val();
      var user_id= $("input[name='userid']").val();
      var intime= $("input[name='input_time']").val();
      var indate=intime.split(" ");
      var indatenew=indate[0].split('/');
      var myDate=out_time.split(" ");
      var mydatenew = myDate[0].split('/');
  if(mydatenew[0]!=''){
    out_time_new = mydatenew[2]+"-"+mydatenew[1]+"-"+mydatenew[0];
    } else{
      out_time_new = out_time; 
    }
 
    if(out_time!='' && out_time_new==indatenew)
    {
         $.ajax({
         type   : 'POST',
         url   : 'attendance/update_Modal',
         dataType: "json",
         data: {
             token: '<?php echo $this->security->get_csrf_hash();?>',
               id:id,
               outtime:out_time,
               user_id: user_id,
               in_time:intime
           },
  success : function(data){
      //alert(data);
      console.log(data);
      if(data.success==1)
      {
       
           $('#UsrTable').dataTable().fnDestroy();
        //oTable.fnDestroy();
        var oTable = $('#UsrTable').dataTable({
        "aaSorting": [[4, "desc"]],
        "aLengthMenu": [[5, 25, 50, 100, -1], [5, 25, 50, 100, "<?= lang('all') ?>"]],
        "iDisplayLength": <?= $Settings->rows_per_page ?>,
        'bProcessing': true, 'bServerSide': true,
        'sAjaxSource': '<?= site_url('attendance/getSearchbyId/') ?>',
        'fnServerData': function (sSource, aoData, fnCallback) {
            aoData.push({
                "name": "<?= $this->security->get_csrf_token_name() ?>",
                "value": "<?= $this->security->get_csrf_hash() ?>",
              
            });
             aoData.push( { "name": "userid", "value": user_id });
          
            console.log(aoData);
            $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
              },
         'fnRowCallback': function (nRow, aData, iDisplayIndex) {
            nRow.id = aData[0];
           // alert(aData);
        // nRow.className = "staff_details_link";
            return nRow;
            },
             "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {

        var totals = [[0,0,0]];
for (var i = 0; i < aaData.length; i++) {

                time = aaData[aiDisplay[i]][7].split(":");
               
                totals[0][2] += parseInt(time[2]);
                if(totals[0][2] > 60)
                {
                totals[0][2] %= 60;
                totals[0][1] += parseInt(time[1]) + 1;          
                }
                else
                totals[0][1] += parseInt(time[1]);
              

                if(totals[0][1] > 60)
                {
                totals[0][1] %= 60;
                totals[0][0] += parseInt(time[0]) + 1;          
                }
                else
                totals[0][0] += parseInt(time[0]);
              

            }

  var nCells = nRow.getElementsByTagName('th');

            nCells[7].innerHTML ="Total:" + totals[0][0] + ":" + totals[0][1] + ":" + totals[0][2];
          },
   
   
        "aoColumns": [{"bSortable": false, "mRender": checkbox},null,null,null,null,null,null,null,null,null,null]
    }).fnSetFilteringDelay().dtFilter([
        {column_number: 1, filter_default_label: "[<?=lang('name');?>]", filter_type: "text", data: []},
  {column_number: 2, filter_default_label: "[<?=lang('user_id');?>]", filter_type: "text", data: []},
        {column_number: 3, filter_default_label: "[<?=lang('in_time');?>]", filter_type: "text", data: []},

         {column_number: 4, filter_default_label: "[<?=lang('out_time');?>]", filter_type: "text", data: []},
   {column_number: 5, filter_default_label: "[<?=lang('update_time');?>]", filter_type: "text", data: []},
        {column_number: 6, filter_default_label: "[<?=lang('in_out_time');?>]", filter_type: "text", data: []},
         {column_number: 7, filter_default_label: "[<?=lang('work_hours');?>]", filter_type: "text", data: []},
        {column_number: 8, filter_default_label: "[<?=lang('reserve_previous_time');?>]", filter_type: "text", data: []},
          {column_number: 9, filter_default_label: "[<?=lang('is_late');?>]", filter_type: "text", data: []},
           {column_number: 10, filter_default_label: "[<?=lang('action');?>]", filter_type: "text", data: []},
    ], "footer");

      $('#myModalstaff').modal('hide');

      }
       else if(data.error==1)
        {
       //alert(data.message);
      
     $('#myModalstaff').html(data);
    $('#myModalstaff').modal('show');
       }
  }
  });   
    } else {
        if(new_id==id){
          alert('Please enter the valid out time.');
        }
        return false;
    }
});
});

</script>

       