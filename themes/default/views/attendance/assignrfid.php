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
            "aaSorting": [[2, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('attendance/getassignrf') ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
             'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                nRow.id = aData[0];
            // nRow.className = "staff_details_link";
                return nRow;
            },
			 
			 
            "aoColumns": [{"bSortable": false, "mRender": checkbox},null,null]
        }).fnSetFilteringDelay().dtFilter([
        {column_number: 1, filter_default_label: "[<?=lang('staff_name');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('rfid');?>]", filter_type: "text", data: []},
        ], "footer");
		
	});
	
	</script>

<style>
.table td:nth-child(1) {
        text-align: right;
        width: 10%;
    }.table td:nth-child(2) {
        text-align: center;
      
    }

    .table td:nth-child(3) {
        text-align: center;
    }
 .table td:nth-child(4)  {
        text-align: center;
    }
.dtFilter-filter-wrapper .dtFilter-filter:first-child{
    text-align: center;
}
</style>
<?php if ($Owner) {
    echo form_open('attendance/deleteassignrid_actions','id="action-form"');
} ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('attendance'); ?></h2>
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

              
                <div class="box-content">
         <div class="row"> 
            <div class="col-lg-12">
                <p class="introtext"><?= lang('attendance'); ?></p>
                   
                <div class="table-responsive">
              
                    <table id="UsrTable" cellpadding="0" cellspacing="0" border="0"
                           class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                              <th class="col-xs-2"><?php echo lang('staff_name'); ?></th>
                           <th class="col-xs-2"><?php echo lang('rfid'); ?></th>
                           <!--   <th class="col-xs-2"><?php// echo lang('time_entry'); ?></th> -->
                             
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="2" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                             <th style="text-align: center;"></th>
                            <th style="text-align: center;"></th>
                          <!--  <th style="text-align: center;"></th> -->
                            
                        </tr>
                        </tfoot>
                    </table>
                    
                </div>

            </div>
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
    <script language="javascript">
        $(document).ready(function () {
            $('#set_admin').click(function () {
                $('#usr-form-btn').trigger('click');
            });

        });
    </script>

<?php } ?>
<div class="modal fade in" id="myModalstaff" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

