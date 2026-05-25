<style>
#d1 {
  height: 100px !important;
  left: 30%;
  position:fixed;
  top: 74%;
  width: 20px !important;
 
  
}
#d1 img {
  border: 6px solid #fff;
  border-radius: 65px;
  height: 100px;
  width: 100px;
 
  
}
</style>
<script>
    $(document).ready(function () {

  $(".table-responsive").before('<div class="col-md-3"><div class="stf_id"><?=lang('staff_name');?><input type="text" class="form-control for" id="uname"></div><input type="button" id="this_month" class="btn btn-primary" value="This Month" style="padding: 6px 12px; margin:15px 0;">&nbsp&nbsp<input type="button" id="prev_month" class="btn btn-primary" value="Previous Month" style="padding: 6px 15px; margin:15px 0;"></div>'+'<div class="col-md-2"><div class="form_date"><?=lang('from');?><input type="text" class="form-control input-tip date" id="fromdate"></div></div>'+'<div class="col-md-2"><div class="to_date"><?=lang('To');?><input type="text" class="form-control input-tip date" id="dateto"></div></div>'+'<input type="button" id="search" class="btn btn-primary" value="Search" style="padding: 6px 15px; margin:15px 0;">');


        'use strict';
        var oTable = $('#UsrTable').dataTable({
            "aaSorting": [[4, "desc"]],
            "aLengthMenu": [[5, 25, 50, 100, -1], [5, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('attendance/getattendancereport') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
          "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {

            var totals = [[0,0,0]];
for (var i = 0; i < aaData.length; i++) {

                    time = aaData[aiDisplay[i]][7].split(":");
                    //alert(time[2]);
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

$(document).on('click','input#search',function(){
          //alert('hi');
           var staff_id=$('#uname').val(); 
           var from_date=$('#fromdate').val();
           var to_date=$('#dateto').val();
          // alert(from_date);
           // alert(to_date);
           if(staff_id==''){
            alert('Please enter staff name');
           }
            if(from_date==''){
            alert('Please enter form date');
            return false;
           }
            if(to_date==''){
            alert('Please enter to date');
            return false;
           }
            if(from_date>to_date){
            alert('form date never greater than to date');
            return false;
           }
          else
          {
            $('#UsrTable').dataTable().fnDestroy();
            //oTable.fnDestroy();
            var oTable = $('#UsrTable').dataTable({
            "aaSorting": [[4, "desc"]],
            "aLengthMenu": [[5, 25, 50, 100, -1], [5, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('attendance/getattendancesearch') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>",
                  
                });
                aoData.push( { "name": "staff_name", "value": staff_id });
                aoData.push( { "name": "fromdate", "value": from_date });
                aoData.push( { "name": "todate", "value": to_date });
                console.log(aoData);
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                  },
    "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {

            var totals = [[0,0,0]];
for (var i = 0; i < aaData.length; i++) {

                    time = aaData[aiDisplay[i]][7].split(":");
                    //alert(time[2]);
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
                      
             //'fnRowCallback': function (nRow, aData, iDisplayIndex) {
               // nRow.id = aData[0];
               // alert(aData);
            // nRow.className = "staff_details_link";
               // return nRow;
              //  },
               
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


      }

         
    
  });
$(document).on('click','input#this_month',function(){
          //alert('hi');
           var staff_id=$('#uname').val();
          // alert(from_date);
           // alert(to_date);
           if(staff_id==''){
            alert('Please enter staff name');
           }else{

            var staff_id=$('#uname').val(); 
         // alert(name);
           $('#UsrTable').dataTable().fnDestroy();
            //oTable.fnDestroy();
            var oTable = $('#UsrTable').dataTable({
            "aaSorting": [[4, "desc"]],
            "aLengthMenu": [[5, 25, 50, 100, -1], [5, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('attendance/getmonthnreport') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>",
                  
                });
                aoData.push( { "name": "stf_id", "value": staff_id });
                
                console.log(aoData);
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                  },
        "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {

            var totals = [[0,0,0]];
for (var i = 0; i < aaData.length; i++) {

                    time = aaData[aiDisplay[i]][7].split(":");
                    //alert(time[2]);
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
            // 'fnRowCallback': function (nRow, aData, iDisplayIndex) {
               // nRow.id = aData[0];
               // alert(aData);
            // nRow.className = "staff_details_link";
              //  return nRow;
              //  },
       
       
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

                  
           }
         });

$(document).on('click','input#prev_month',function(){
          //alert('hi');
           var staff_id=$('#uname').val();
          // alert(from_date);
           // alert(to_date);
           if(staff_id==''){
            alert('Please enter staff name');
           }else{

            var staff_id=$('#uname').val(); 
         // alert(name);
           $('#UsrTable').dataTable().fnDestroy();
            //oTable.fnDestroy();
            var oTable = $('#UsrTable').dataTable({
            "aaSorting": [[4, "desc"]],
            "aLengthMenu": [[5, 25, 50, 100, -1], [5, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('attendance/getprev_monthnreport') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>",
                  
                });
                aoData.push( { "name": "stf_id", "value": staff_id });
                
                console.log(aoData);
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
                  },
        "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {

            var totals = [[0,0,0]];
for (var i = 0; i < aaData.length; i++) {

                    time = aaData[aiDisplay[i]][7].split(":");
                    //alert(time[2]);
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
            // 'fnRowCallback': function (nRow, aData, iDisplayIndex) {
               // nRow.id = aData[0];
               // alert(aData);
            // nRow.className = "staff_details_link";
              //  return nRow;
              //  },
       
       
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

                  
           }
         });


		
	});
	
	</script>

<style>.table td:nth-child(1) {
        text-align: right;
        width: 10%;
    }

    .table td:nth-child(2) {
        text-align: center;
    }
 .table td:nth-child(3)  {
        text-align: center;
    }
.dtFilter-filter-wrapper .dtFilter-filter:first-child{
    text-align: center;
}
</style>
<?php if ($Owner) {
    echo form_open('attendance/deletereport_actions','id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('attendance_report'); ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                       
                                             <li class="divider"></li>

                        <li><a href="#" class="bpo" title="<?= $this->lang->line("delete") ?>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                               data-html="true" data-placement="left"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>

        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"></p>
                   
                <div class="table-responsive">
              
                    <table id="UsrTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                             
                            <th class="col-xs-2"><?php echo lang('name'); ?></th>
                             <th class="col-xs-2"><?php echo lang('user_id'); ?></th>
                             <th class="col-xs-2"><?php echo lang('in_time'); ?></th>
                             <th class="col-xs-2"><?php echo lang('out_time'); ?></th>
                              <th class="col-xs-2"><?php echo lang('update_time'); ?></th>
                             <th class="col-xs-2"><?php echo lang('in_out_time'); ?></th>
                               <th class="col-xs-2"><?php echo lang('work_hours'); ?></th>
                             <th class="col-xs-2"><?php echo lang('reserve_previous_time'); ?></th>
                             <th class="col-xs-2"><?php echo lang('is_late'); ?></th>
                            <th class="col-xs-2"><?php echo lang('action'); ?></th>

                              
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                        </tr>
                        </tfoot>
                    </table>
                    
                </div>

            </div>
</div>
</div>
<?php if ($Owner) { ?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?= form_submit('performAction', 'performAction', 'id="action-form-submit"') ?>
    </div>
    <?= form_close() ?>
    <script language="javascript">
        $(document).ready(function () {
            $('#set_admin').click(function () {
                $('#usr-form-btn').trigger('click');
            });

        });
    </script>

<?php } ?>
<div class="modal fade in" id="myModalstaff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>


<script>
  $(document).ready(function(){
    $('#uname').select2({
            
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

   $(document).on('click', '.edit_user', function(){
    //alert('hello');
    var user_id = $(this).attr('data-user_id'); 
    var outime_time = $(this).closest('td').prev().prev().prev().prev().prev().prev().text(); 

    //alert(user_id); 
  //alert(outime_time);
    $.ajax({
      type   : 'get',
      url   : 'attendance/edit_time/'+user_id,
      success : function(data){
          //alert(data);
          $('#myModalstaff').html(data);
          $('#myModalstaff').modal('show');
          }
      });   
});



  });
</script>