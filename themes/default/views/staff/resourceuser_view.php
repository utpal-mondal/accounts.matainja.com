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
            'sAjaxSource': '<?= site_url('staff/getresource_list/').$id ?>',
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
             
            "aoColumns": [{"bSortable": false, "mRender": checkbox}, null, null, null, null, null, null,null,null,null,null]
        }).fnSetFilteringDelay().dtFilter([
          {column_number: 1, filter_default_label: "[<?=lang('staff_name');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('Userid');?>]", filter_type: "text", data: []},
         {column_number: 3, filter_default_label: "[<?=lang('Rid');?>]", filter_type: "text", data: []},
         
            {column_number: 4, filter_default_label: "[<?=lang('Resource Type');?>]", filter_type: "text", data: []},
               {column_number: 5, filter_default_label: "[<?=lang('Resource Name');?>]", filter_type: "text", data: []},
              {column_number: 6, filter_default_label: "[<?=lang('Model');?>]", filter_type: "text", data: []},

         {column_number: 7, filter_default_label: "[<?=lang('serial_no');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('modified date');?>]", filter_type: "text", data: []},{column_number: 9, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []},
            
          {column_number: 10, filter_default_label: "[<?= lang("actions"); ?>]", filter_type: "text", data: []},
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
                                 <th class="col-xs-2"><?php echo lang('staff_name'); ?></th>
                                 <th class="col-xs-2"><?php echo lang('Userid'); ?></th>
                                 <th class="col-xs-3"><?php echo lang('Rid'); ?></th>
                                 <th class="col-xs-2"><?php echo lang('Resource Type'); ?></th>
                                 <th class="col-xs-2"><?php echo lang('Resource Name'); ?></th>
                                 <th class="col-xs-2"><?php echo lang('Model'); ?></th>
                                 <th class="col-xs-2"><?php echo lang('serial_no'); ?></th>
                                <th class="col-xs-2"><?php echo lang('modified date'); ?></th>
                                 <th class="col-xs-2"><?php echo lang('status'); ?></th>
                                 
                             
                            
                            <th style="width:100px;"><?php echo lang('actions'); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="11" class="dataTables_empty"><?= lang('loading_data_from_server') ?></td>
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



           