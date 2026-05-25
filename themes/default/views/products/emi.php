<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php
if (!empty($variants)) {
    foreach ($variants as $variant) {
        $vars[] = addslashes($variant->name);
    }
} else {
    $vars = array();
}
?>

<script>
    var oTable;
    $(document).ready(function () {
        oTable = $('#PRData').dataTable({
            "aaSorting": [[2, "asc"], [3, "asc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?= lang('all') ?>"]],
            "iDisplayLength": <?= $Settings->rows_per_page ?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?= site_url('products/getImeiDetails/'.$id) ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?= $this->security->get_csrf_token_name() ?>",
                    "value": "<?= $this->security->get_csrf_hash() ?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
            },
            "aoColumns": [
                {"bSortable": false, "mRender": checkbox}, null, null
            ]
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('imei_no');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('status');?>]", filter_type: "text", data: []}, 
           
        ], "footer");

    });
</script>





<div class="box">
<div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_imei'); ?></h2>
    </div>
    
    
    <div class="box-content">
    
    <?php if($this->session->flashdata('msg')): ?>
    <div class="alert alert-success">
  <p><?php echo $this->session->flashdata('msg'); ?></p>
   <?php endif; ?>
   </div>
   <div class="col-md-12">
             <div class="row">
              <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("products/imei/".$id, $attrib)
                ?>
             
               <div class="form-group standard">
                    <div class="form-group">
                        <?= lang("add_imei", "add_imei") ?> 
                        <button type="button" class="btn btn-primary btn-xs" id="addSupplier"><i class="fa fa-plus"></i>
                        </button>
                    </div>
                       <div class="col-md-7">
                            <div class="form-group">
                            <?= form_input('imei_no[]', (isset($_POST['imei_no']) ? $_POST['imei_no'] : ""), 'class="form-control tip" id="supplier_price" placeholder="' . lang('imei_no') . '"required'); ?>
                            </div>
                           
                  
                    <div id="ex-suppliers"></div>
                    </div> 
                    <div class="form-group col-md-12">
                        <?php echo form_submit('add_imei', $this->lang->line("add_imei"), 'class="btn btn-primary"'); ?>  
                    </div>

                </div> 
                 <?= form_close(); ?>
             </div>
              
              <div class="row">

                <div class="table-responsive">
                    <table id="PRData" class="table table-bordered table-condensed table-hover table-striped">
                        <thead>
                        <tr class="primary">
                            <th style="text-align: center;">
                                <input class="checkbox checkth" type="checkbox" name="check"/>
                            </th>
                            
                            <th><?= lang("imei_no") ?></th>
                            <th><?= lang("status") ?></th>
                             
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="11" class="dataTables_empty"><?= lang('loading_data_from_server'); ?></td>
                        </tr>
                        </tbody>

                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>

                            <th></th>
                            <th></th>
                           
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
               
    
          
        </div>
        <div class="clearfix"></div>
</div>
        
    </div>
</div>

<script type="text/javascript">


 $('#addSupplier').click(function () {
           
                $('#supplier_1').select2('destroy');
                var html = '<div style="clear:both;height:5px;"></div><div class="row"><div class="col-xs-12"><div class="form-group"> <input type="text" name="imei_no[]" class="form-control tip" id="imei_no" placeholder="<?= lang('add_imei') ?>" /></div></div></div>';
                $('#ex-suppliers').append(html);
           
        });
</script>

        