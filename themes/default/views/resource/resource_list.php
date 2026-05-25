<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script>

    $(document).ready(function () {

        var oTable = $('#ILData').dataTable({

            "aaSorting": [[0, "asc"], [1, "desc"]],

            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],

            "iDisplayLength": <?=$this->Settings->rows_per_page?>,

            'bProcessing': true, 'bServerSide': true,

            'sAjaxSource': '<?=site_url('resource/getresources/')?>',

            'fnServerData': function (sSource, aoData, fnCallback) {

                aoData.push({

                    "name": "<?=$this->security->get_csrf_token_name()?>",

                    "value": "<?=$this->security->get_csrf_hash()?>"

                });

                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});

            },
			'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                nRow.id = aData[0];
                nRow.className = "resource_details_link";
                return nRow;
            },
              

            "aoColumns": [{"bSortable": false,"mRender": checkbox},null, null,null,null,null,null,null,null]

        /*"fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {

                var balance = 0;

                for (var i = 0; i < aaData.length; i++) {

                    balance += parseFloat(aaData[aiDisplay[i]][6]);

                }

                var nCells = nRow.getElementsByTagName('th');

                nCells[6].innerHTML = currencyFormat(parseFloat(balance));

            }*/
	   
	   
	    }).fnSetFilteringDelay().dtFilter([

            {column_number: 1, filter_default_label: "[<?=lang('list_purchase');?>]", filter_type: "text", data: []},

            {column_number: 2, filter_default_label: "[<?=lang('list_resource');?>]", filter_type: "text", data: []},

            {column_number: 3, filter_default_label: "[<?=lang('list_name');?>]", filter_type: "text", data: []},

            {column_number: 4, filter_default_label: "[<?=lang('list_model');?>]", filter_type: "text", data: []},

            {column_number: 5, filter_default_label: "[<?=lang('list_serialno');?>]", filter_type: "text", data: []},
		
			{column_number: 6, filter_default_label: "[<?=lang('damage');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('list_assign');?>]", filter_type: "text", data: []},
			
            {column_number: 8, filter_default_label: "[<?= lang("actions"); ?>]", filter_type: "text", data: []}

        ], "footer");



        if (localStorage.getItem('remove_slls')) {

            if (localStorage.getItem('slitems')) {

                localStorage.removeItem('slitems');

            }

            if (localStorage.getItem('sldiscount')) {

                localStorage.removeItem('sldiscount');

            }

            if (localStorage.getItem('sltax2')) {

                localStorage.removeItem('sltax2');

            }

            if (localStorage.getItem('slref')) {

                localStorage.removeItem('slref');

            }

            if (localStorage.getItem('slshipping')) {

                localStorage.removeItem('slshipping');

            }

            if (localStorage.getItem('slwarehouse')) {

                localStorage.removeItem('slwarehouse');

            }

            if (localStorage.getItem('slnote')) {

                localStorage.removeItem('slnote');

            }

            if (localStorage.getItem('slinnote')) {

                localStorage.removeItem('slinnote');

            }

            if (localStorage.getItem('slcustomer')) {

                localStorage.removeItem('slcustomer');

            }

            if (localStorage.getItem('slbiller')) {

                localStorage.removeItem('slbiller');

            }

            if (localStorage.getItem('slcurrency')) {

                localStorage.removeItem('slcurrency');

            }

            if (localStorage.getItem('sldate')) {

                localStorage.removeItem('sldate');

            }

            if (localStorage.getItem('slsale_status')) {

                localStorage.removeItem('slsale_status');

            }

            if (localStorage.getItem('slpayment_status')) {

                localStorage.removeItem('slpayment_status');

            }

            if (localStorage.getItem('paid_by')) {

                localStorage.removeItem('paid_by');

            }

            if (localStorage.getItem('amount_1')) {

                localStorage.removeItem('amount_1');

            }

            if (localStorage.getItem('paid_by_1')) {

                localStorage.removeItem('paid_by_1');

            }

            if (localStorage.getItem('pcc_holder_1')) {

                localStorage.removeItem('pcc_holder_1');

            }

            if (localStorage.getItem('pcc_type_1')) {

                localStorage.removeItem('pcc_type_1');

            }

            if (localStorage.getItem('pcc_month_1')) {

                localStorage.removeItem('pcc_month_1');

            }

            if (localStorage.getItem('pcc_year_1')) {

                localStorage.removeItem('pcc_year_1');

            }

            if (localStorage.getItem('pcc_no_1')) {

                localStorage.removeItem('pcc_no_1');

            }

            if (localStorage.getItem('cheque_no_1')) {

                localStorage.removeItem('cheque_no_1');

            }

            if (localStorage.getItem('slpayment_term')) {

                localStorage.removeItem('slpayment_term');

            }

            localStorage.removeItem('remove_slls');

        }



        <?php if ($this->session->userdata('remove_slls')) {?>

        if (localStorage.getItem('slitems')) {

            localStorage.removeItem('slitems');

        }

        if (localStorage.getItem('sldiscount')) {

            localStorage.removeItem('sldiscount');

        }

        if (localStorage.getItem('sltax2')) {

            localStorage.removeItem('sltax2');

        }

        if (localStorage.getItem('slref')) {

            localStorage.removeItem('slref');

        }

        if (localStorage.getItem('slshipping')) {

            localStorage.removeItem('slshipping');

        }

        if (localStorage.getItem('slwarehouse')) {

            localStorage.removeItem('slwarehouse');

        }

        if (localStorage.getItem('slnote')) {

            localStorage.removeItem('slnote');

        }

        if (localStorage.getItem('slinnote')) {

            localStorage.removeItem('slinnote');

        }

        if (localStorage.getItem('slcustomer')) {

            localStorage.removeItem('slcustomer');

        }

        if (localStorage.getItem('slbiller')) {

            localStorage.removeItem('slbiller');

        }

        if (localStorage.getItem('slcurrency')) {

            localStorage.removeItem('slcurrency');

        }

        if (localStorage.getItem('sldate')) {

            localStorage.removeItem('sldate');

        }

        if (localStorage.getItem('slsale_status')) {


            localStorage.removeItem('slsale_status');

        }

        if (localStorage.getItem('slpayment_status')) {

            localStorage.removeItem('slpayment_status');

        }

        if (localStorage.getItem('paid_by')) {

            localStorage.removeItem('paid_by');

        }

        if (localStorage.getItem('amount_1')) {

            localStorage.removeItem('amount_1');

        }

        if (localStorage.getItem('paid_by_1')) {

            localStorage.removeItem('paid_by_1');

        }

        if (localStorage.getItem('pcc_holder_1')) {

            localStorage.removeItem('pcc_holder_1');

        }

        if (localStorage.getItem('pcc_type_1')) {

            localStorage.removeItem('pcc_type_1');

        }

        if (localStorage.getItem('pcc_month_1')) {

            localStorage.removeItem('pcc_month_1');

        }

        if (localStorage.getItem('pcc_year_1')) {

            localStorage.removeItem('pcc_year_1');

        }

        if (localStorage.getItem('pcc_no_1')) {

            localStorage.removeItem('pcc_no_1');

        }

        if (localStorage.getItem('cheque_no_1')) {

            localStorage.removeItem('cheque_no_1');

        }

        if (localStorage.getItem('slpayment_term')) {

            localStorage.removeItem('slpayment_term');

        }

        <?php $this->sma->unset_data('remove_slls');}

        ?>



        $(document).on('click', '.sledit', function (e) {

            if (localStorage.getItem('slitems')) {

                e.preventDefault();

                var href = $(this).attr('href');

                bootbox.confirm("<?=lang('you_will_loss_sale_data')?>", function (result) {

                    if (result) {

                        window.location.href = href;

                    }

                });

            }

        });



    });



</script>



<?php if ($this->Owner || ($this->GP && $this->GP['bulk_actions'])) {

	    echo form_open('resource/resource_actions', 'id="action-form"');

	}

?>

<div class="box">

    <div class="box-header">

        <h2 class="blue"> <i class="fa fa-print" aria-hidden="true"></i><?=lang('resource_management')?>

        </h2>

        <div class="box-icon">

            <ul class="btn-tasks">

                <li class="dropdown">

                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>

                    </a>

                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">

                        <li>

                            <a href="<?=site_url('resource/add')?>">

                                <i class="fa fa-plus-circle"></i> <?=lang('add_sale')?>

                            </a>

                        </li>

                        <li>

                            <a href="#" id="excel" data-action="export_excel">

                                <i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>

                            </a>

                        </li>

                        <li>

                            <a href="#" id="pdf" data-action="export_pdf">

                                <i class="fa fa-file-pdf-o"></i> <?=lang('export_to_pdf')?>

                            </a>

                        </li>

                        <li>

                            <a href="#" id="combine" data-action="combine">

                                <i class="fa fa-file-pdf-o"></i> <?=lang('combine_to_pdf')?>

                            </a>

                        </li>

                        <li class="divider"></li>

                        <li>

                            <a href="#" class="bpo"

                            title="<b><?=lang("delete_sales")?></b>"

                            data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>"

                            data-html="true" data-placement="left">

                            <i class="fa fa-trash-o"></i> Delete Resource

                        </a>

                    </li>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

    <div class="box-content">

        <div class="row">

            <div class="col-lg-12">



                <p class="introtext"><?=lang('list_results');?></p>



                <div class="table-responsive">

                    <table id="ILData" class="table table-bordered table-hover table-striped">

                        <thead>

                        <tr>

                            <th style="min-width:30px; width: 30px; text-align: center;">

                                <input class="checkbox checkft" type="checkbox" name="check"/>

                            </th>

                            <th><?= lang("list_purchase"); ?></th>

                            <th><?= lang("list_resource"); ?></th>

                            <th><?= lang("list_name"); ?></th>

                            <th><?= lang("list_model"); ?></th>

                            <th><?= lang("list_serialno"); ?></th>
                         
                            <th><?= lang("damage"); ?></th>
                             <th><?= lang("assign"); ?></th>
                           
                            <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>

                        </tr>

                        </thead>

                        <tbody>

                        <tr>

                            <td colspan="12" class="dataTables_empty"><?= lang("loading_data"); ?></td>

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
                      
                            <th style="width:80px; text-align:center;"></th>
                        </tr>
                        </tfoot>
                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<?php if ($this->Owner || ($this->GP && $this->GP['bulk_actions'])) { ?>

    <div style="display: none;">

        <input type="hidden" name="form_action" value="" id="form_action"/>

        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>

    </div>

    <?=form_close()?>

<?php }

?>
<!--<div class="modal fade in" id="myModalresource" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>-->

<script>
/*$(document).on("click", '.resource_details_link', function(){
			
			
			var id= $(this).attr('id');
			$.ajax({
			type	 : 'get',
			url	  : 'resource/viewdetails/'+id,
			success : function(data){
					//alert(data);
					$('#myModalresource').html(data);
					$('#myModalresource').modal('show');
					}
			});			

});*/
</script>

