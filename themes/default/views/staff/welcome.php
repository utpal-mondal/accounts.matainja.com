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
        'use strict';
        var oTable = $('#UsrTable').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('staff/getstaff') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
			  'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                nRow.id = aData[0];
                nRow.className = "staff_details_link";
                return nRow;
            },
			 
            "aoColumns": [{"bSortable": false, "mRender": checkbox}, null, null, null, null, null, null, null, {"mRender": user_status}, null]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('first_name');?>]", filter_type: "text", data: []},
			{column_number: 2, filter_default_label: "[<?=lang('last_name');?>]", filter_type: "text", data: []},
			{column_number: 3, filter_default_label: "[<?=lang('FatherName1');?>]", filter_type: "text", data: []},
			 {column_number: 4, filter_default_label: "[<?=lang('personalemail');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('businessemail');?>]", filter_type: "text", data: []},
            {column_number: 6, filter_default_label: "[<?=lang('Phone');?>]", filter_type: "text", data: []},
		
			  
            {column_number: 7, filter_default_label: "[<?=lang('group');?>]", filter_type: "text", data: []},
          
            {
                column_number: 8, select_type: 'select2',
                select_type_options: {
                    placeholder: '<?=lang('status');?>',
                    width: '100%',
                    style: 'width:100%;',
                    minimumResultsForSearch: -1,
                    allowClear: true
                },
                data: [{value: '1', label: '<?=lang('active');?>'}, {value: '0', label: '<?=lang('inactive');?>'}]
            },
              {column_number: 9, filter_default_label: "[<?= lang("actions"); ?>]", filter_type: "text", data: []}
,
        ], "footer");
		
	});
	
	</script>

<style>.table td:nth-child(6) {
        text-align: right;
        width: 10%;
    }

    .table td:nth-child(8) {
        text-align: center;
    }</style>
<?php if ($Owner) {
    echo form_open('staff/staff_actions', 'id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('staff'); ?></h2>
        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#"><i class="icon fa fa-tasks tip"
                                                                                  data-placement="left"
                                                                                  title="<?= lang("actions") ?>"></i></a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li><a href="<?= site_url('staff/add'); ?>"><i
                                    class="fa fa-plus-circle"></i> <?= lang("create_staff"); ?></a></li>

                            <li><a href="#" id="excel" data-action="export_excel"><i
                                    class="fa fa-file-excel-o"></i> <?= lang('export_to_excel') ?></a></li>
                        <li><a href="#" id="pdf" data-action="export_pdf"><i
                                    class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?></a></li>
                                    <li><a href="<?= site_url('staff/getsalary_report'); ?>"><i
                                    class="fa fa-plus-circle"></i> <?= lang("salaryreport"); ?></a></li>
                                    <li><a href="<?= site_url('resource'); ?>"><i
                                    class="fa fa-plus-circle"></i> <?= lang("resourcelist"); ?></a></li>
                        <li class="divider"></li>
                        <li><a href="#" class="bpo" title="<?= $this->lang->line("delete_staff") ?>"
                               data-content="<p><?= lang('r_u_sure') ?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button>"
                               data-html="true" data-placement="left"><i
                                    class="fa fa-trash-o"></i> <?= lang('delete_staff') ?></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?= lang('staff_results'); ?></p>
                   
                <div class="table-responsive">
              
                    <table id="UsrTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            <th class="col-xs-2"><?php echo lang('first_name'); ?></th>
                             <th class="col-xs-2"><?php echo lang('last_name'); ?></th>
                             <th class="col-xs-3"><?php echo "Attendence ID  "; ?></th>
                             <th class="col-xs-2"><?php echo lang('personalemail'); ?></th>
                              <th class="col-xs-2"><?php echo lang('businessemail'); ?></th>
                            <th class="col-xs-2"><?php echo lang('Phone'); ?></th>
                            <th class="col-xs-2"><?php echo lang('group'); ?></th>
                            <th style="width:100px;"><?php echo lang('status'); ?></th>
                            <th style="width:80px;"><?php echo lang('actions'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="10" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:100px;"></th>
                            <th style="width:85px; text-align:center"></th>
                        </tr>
                        </tfoot>
                    </table>
                    
                </div>

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
   

<?php } ?>
<div class="modal fade in" id="myModalstaff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
 <div class="modal fade in" id="myModalpassword" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
 <div class="modal fade in" id="myModalworkhome" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
 <div class="modal fade in" id="myModalincrement" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>
</div>

<script language="javascript">
        $(document).ready(function () {
            $('#set_admin').click(function () {
                $('#usr-form-btn').trigger('click');
            });

              $(document).on("click", '.staff_details_link', function(e){
		             var td = e.target || e.srcElement;
                     if (typeof td.cellIndex === "undefined" ) {
                      return;
                }
                   else{
                         var id= $(this).attr('id');
                           $.ajax({
                                type     : 'get',
                                 url   : 'staff/view/'+id,
                                 success : function(data)
                                 {
                   
                                    $('#myModalstaff').html(data);
                                    $('#myModalstaff').modal('show');
                                  }
                             }); 
                        }
             });
      $(document).on('click', '.edit_user', function(){
    
       
    var user_id = $(this).attr('data-user_id'); 
     
     $.ajax({
      type   : 'get',
      url   : 'staff/add_password/'+user_id,
      success : function(data){
          //alert(data);
            
          $('#myModalpassword').html(data);
          $('#myModalpassword').modal('show');
          $('#myModalstaff').modal('hide');
          }
      });   
  });

        $(document).on('click', '.edit_home', function(){
    
       //alert('hi');
    var user_id = $(this).attr('data-user_id'); 
     
     $.ajax({
      type   : 'get',
      url   : 'staff/add_work_home/'+user_id,
      success : function(data){
          //alert(data);
            
          $('#myModalworkhome').html(data);
          $('#myModalworkhome').modal('show');
          $('#myModalstaff').modal('hide');
          }
      });   
  });

    $(document).on('click', '.edit_increment', function(){
    
       //alert('hi');
    var user_id = $(this).attr('data-user_id'); 
     
     $.ajax({
      type   : 'get',
      url   : 'staff/add_increment_salary/'+user_id,
      success : function(data){
          //alert(data);
            
          $('#myModalincrement').html(data);
          $('#myModalincrement').modal('show');
          $('#myModalstaff').modal('hide');
          }
      });   
  });            
});
</script>
<script>
/*$(document).on("mouseover", '.staff_details_link', function(){
			
			
			var id= $(this).attr('id');
			$.ajax({
			dataType:'json',
			type	 : 'get',
			url	  : 'staff/photo/'+id,
			success : function(data){
					//alert(data);
					//$('#d1').html("<img src='<?=base_url()?>"+data[0].upload+"' />");
					$('tr:').append("<img src='<?=base_url()?>"+data[0].upload+"' />");
					//$('#d1').toggle();
					}
			});			

});*/

 </script>
