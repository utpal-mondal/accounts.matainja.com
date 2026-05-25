<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">

    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,

        product_tax = 0, invoice_tax = 0, product_discount = 0, order_discount = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,

        tax_rates = <?php echo json_encode($tax_rates); ?>;

    //var audio_success = new Audio('<?=$assets?>sounds/sound2.mp3');

    //var audio_error = new Audio('<?=$assets?>sounds/sound3.mp3');

    $(document).ready(function () {

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

            if (localStorage.getItem('payment_note_1')) {

                localStorage.removeItem('payment_note_1');

            }

            if (localStorage.getItem('slpayment_term')) {

                localStorage.removeItem('slpayment_term');

            }

            localStorage.removeItem('remove_slls');

        }

        <?php if($quote_id) { ?>

        // localStorage.setItem('sldate', '<?= $this->sma->hrld($quote->date) ?>');

        localStorage.setItem('slcustomer', '<?= $quote->customer_id ?>');

        localStorage.setItem('slbiller', '<?= $quote->biller_id ?>');

        localStorage.setItem('slwarehouse', '<?= $quote->warehouse_id ?>');

        localStorage.setItem('slnote', '<?= str_replace(array("\r", "\n"), "", $this->sma->decode_html($quote->note)); ?>');

        localStorage.setItem('sldiscount', '<?= $quote->order_discount_id ?>');

        localStorage.setItem('sltax2', '<?= $quote->order_tax_id ?>');

        localStorage.setItem('slshipping', '<?= $quote->shipping ?>');

        localStorage.setItem('slitems', JSON.stringify(<?= $quote_items; ?>));

        <?php } ?>

        <?php if($this->input->get('customer')) { ?>

        if (!localStorage.getItem('slitems')) {

            localStorage.setItem('slcustomer', <?=$this->input->get('customer');?>);

        }

        <?php } ?>

        <?php if ($Owner || $Admin) { ?>

        if (!localStorage.getItem('sldate')) {

            $("#sldate").datetimepicker({

                format: site.dateFormats.js_ldate,

                fontAwesome: true,

                language: 'sma',

                weekStart: 1,

                todayBtn: 1,

                autoclose: 1,

                todayHighlight: 1,

                startView: 2,

                forceParse: 0

            }).datetimepicker('update', new Date());

        }

        $(document).on('change', '#sldate', function (e) {

            localStorage.setItem('sldate', $(this).val());

        });

        if (sldate = localStorage.getItem('sldate')) {

            $('#sldate').val(sldate);

        }

        <?php } ?>

        $(document).on('change', '#slbiller', function (e) {

            localStorage.setItem('slbiller', $(this).val());

        });

        if (slbiller = localStorage.getItem('slbiller')) {

            $('#slbiller').val(slbiller);

        }

        if (!localStorage.getItem('slref')) {

            localStorage.setItem('slref', '<?=$slnumber?>');

        }

        if (!localStorage.getItem('sltax2')) {

            localStorage.setItem('sltax2', <?=$Settings->default_tax_rate2;?>);

        }

        ItemnTotals();

        $('.bootbox').on('hidden.bs.modal', function (e) {

            $('#add_item').focus();

        });

        $("#add_item").autocomplete({

            source: function (request, response) {

                if (!$('#slcustomer').val()) {

                    $('#add_item').val('').removeClass('ui-autocomplete-loading');

                    bootbox.alert('<?=lang('select_above');?>');

                    $('#add_item').focus();

                    return false;

                }

                $.ajax({

                    type: 'get',

                    url: '<?= site_url('sales/suggestions'); ?>',

                    dataType: "json",

                    data: {

                        term: request.term,

                        warehouse_id: $("#slwarehouse").val(),

                        customer_id: $("#slcustomer").val()

                    },

                    success: function (data) {

                        response(data);

                    }

                });

            },

            minLength: 1,

            autoFocus: false,

            delay: 250,

            response: function (event, ui) {

                if ($(this).val().length >= 16 && ui.content[0].id == 0) {

                    bootbox.alert('<?= lang('no_match_found') ?>', function () {

                        $('#add_item').focus();

                    });

                    $(this).removeClass('ui-autocomplete-loading');

                    $(this).removeClass('ui-autocomplete-loading');

                    $(this).val('');

                }

                else if (ui.content.length == 1 && ui.content[0].id != 0) {

                    ui.item = ui.content[0];

                    $(this).data('ui-autocomplete')._trigger('select', 'autocompleteselect', ui);

                    $(this).autocomplete('close');

                    $(this).removeClass('ui-autocomplete-loading');

                }

                else if (ui.content.length == 1 && ui.content[0].id == 0) {

                    bootbox.alert('<?= lang('no_match_found') ?>', function () {

                        $('#add_item').focus();

                    });

                    $(this).removeClass('ui-autocomplete-loading');

                    $(this).val('');

                }

            },

            select: function (event, ui) {

                event.preventDefault();

                if (ui.item.id !== 0) {

                    var row = add_invoice_item(ui.item);

                    if (row)

                        $(this).val('');

                } else {

                    bootbox.alert('<?= lang('no_match_found') ?>');

                }

            }

        });

        $(document).on('change', '#gift_card_no', function () {

            var cn = $(this).val() ? $(this).val() : '';

            if (cn != '') {

                $.ajax({

                    type: "get", async: false,

                    url: site.base_url + "sales/validate_gift_card/" + cn,

                    dataType: "json",

                    success: function (data) {

                        if (data === false) {

                            $('#gift_card_no').parent('.form-group').addClass('has-error');

                            bootbox.alert('<?=lang('incorrect_gift_card')?>');

                        } else if (data.customer_id !== null && data.customer_id !== $('#slcustomer').val()) {

                            $('#gift_card_no').parent('.form-group').addClass('has-error');

                            bootbox.alert('<?=lang('gift_card_not_for_customer')?>');



                        } else {

                            $('#gc_details').html('<small>Card No: ' + data.card_no + '<br>Value: ' + data.value + ' - Balance: ' + data.balance + '</small>');

                            $('#gift_card_no').parent('.form-group').removeClass('has-error');

                        }

                    }

                });

            }

        });

    });

</script>





<div class="box">

    <div class="box-header">

        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('add_sale'); ?></h2>

    </div>

    <div class="box-content">

        <div class="row">

            <div class="col-lg-12">



                <p class="introtext"><?php echo lang('enter_info'); ?></p>

                <?php

                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-so-form');

                echo form_open_multipart("sales/add/" , $attrib)

                ?>





                <div class="row">

                    <div class="col-lg-12">

                        <?php if ($Owner || $Admin) { ?>

                            <div class="col-md-4">

                                <div class="form-group">

                                   <?= lang("date", "sldate"); ?>

                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>

                                </div>

                            </div>

                        <?php } ?>

                        <div class="col-md-4">

                            <div class="form-group">

                                <?= lang("reference_no", "slref"); ?>

                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ''), 'class="form-control input-tip" id="slref" required="required"'); ?>

                            </div>

                        </div>

                        <?php if ($Owner || $Admin || !$this->session->userdata('biller_id')) { ?>

                            <div class="col-md-4">

                                <div class="form-group">

                                    <?= lang("biller", "slbiller"); ?>

                                    <?php

                                    $bl[""] = "";

                                    foreach ($billers as $biller) {

                                        $bl[$biller->id] = $biller->company != '-' ? $biller->company : $biller->name;

                                    }

                                   echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $Settings->default_biller), 'id="slbiller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');

                                    ?>

                                </div>

                            </div>

                        <?php } else {

                            $biller_input = array(

                                'type' => 'hidden',

                                'name' => 'biller',

                                'id' => 'slbiller',

                                'value' => $this->session->userdata('biller_id'),

                            );

                            echo form_input($biller_input);

                        } ?>



                        <div class="clearfix"></div>

                        <div class="col-md-12">

                            <div class="panel panel-warning">

                                <div

                                    class="panel-heading"><?= lang('please_select_these_before_adding_product') ?></div>

                                <div class="panel-body" style="padding: 5px;">



                                    <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <?= lang("warehouse", "slwarehouse"); ?>

                                                <?php

                                                $wh[''] = '';

                                                foreach ($warehouses as $warehouse) {

                                                    $wh[$warehouse->id] = $warehouse->name;

                                                }

                                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $Settings->default_warehouse), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');

                                                ?>

                                            </div>

                                        </div>

                                    <?php } else {

                                        $warehouse_input = array(

                                            'type' => 'hidden',

                                            'name' => 'warehouse',

                                            'id' => 'slwarehouse',

                                            'value' => $this->session->userdata('warehouse_id'),

                                        );

                                        echo form_input($warehouse_input);

                                    } ?>



                                    <div class="col-md-4">

                                        <div class="form-group">

                                            <?= lang("customer", "slcustomer"); ?>

                                            <div class="input-group">

                                                <?php

                                                echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="slcustomer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="width:100%;"');

                                                ?>

                                                <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;">

                                                    <a href="#" id="toogle-customer-read-attr" class="external">

                                                        <i class="fa fa-pencil" id="addIcon" style="font-size: 1.2em;"></i>

                                                    </a>

                                                </div>

                                                <div class="input-group-addon no-print" style="padding: 2px 7px; border-left: 0;">

                                                    <a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal">

                                                        <i class="fa fa-eye" id="addIcon" style="font-size: 1.2em;"></i>

                                                    </a>

                                                </div>

                                                <?php if ($Owner || $Admin || $GP['customers-add']) { ?>

                                                <div class="input-group-addon no-print" style="padding: 2px 8px;">

                                                    <a href="<?= site_url('customers/add'); ?>" id="add-customer"class="external" data-toggle="modal" data-target="#myModal">

                                                        <i class="fa fa-plus-circle" id="addIcon"  style="font-size: 1.2em;"></i>

                                                    </a>

                                                </div>

                                                <?php } ?>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>



                        </div>





                        <div class="col-md-12" id="sticker">

                            <!--<div class="well well-sm">

                                <div class="form-group" style="margin-bottom:0;">

                                    <div class="input-group wide-tip">

                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">

                                            <i class="fa fa-2x fa-barcode addIcon"></i></a></div>

                                        <?php echo form_input('add_item', '', 'class="form-control input-lg" id="add_item" placeholder="' . lang("add_product_to_order") . '"'); ?>

                                        <?php if ($Owner || $Admin || $GP['products-add']) { ?>

                                        <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">

                                            <a href="#" id="addManually">

                                                <i class="fa fa-2x fa-plus-circle addIcon" id="addIcon"></i>

                                            </a>

                                        </div>

                                        <?php } ?>

                                    </div>

                                </div>

                                <div class="clearfix"></div>

                            </div>-->

                        </div>



                        <div class="col-md-12">

                            <div class="control-group table-group">

                                <label class="table-label"><?= lang("order_items"); ?> *</label>



                                <div class="controls table-controls">

                                    <table id="slTable"

                                           class="table items table-striped table-bordered table-condensed table-hover sortable_table">

                                        <thead>

                                        <tr>
                                        <!--<th>No</th>-->

                                            <th class="col-md-4">Description</th>

                                            <?php

                                                echo '<th class="col-md-2">Hour</th>';
                                            ?>

                                            <th class="col-md-1"><?= lang("quantity"); ?></th>

                                            <th class="col-md-1">Price</th>

                                           <?php /*?> <?php

                                            if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount') || $inv->product_discount)) {

                                                echo '<th class="col-md-1">' . lang("discount") . '</th>';

                                            }

                                            ?><?php */?>

                                            <?php /*?><?php

                                            if ($Settings->tax1) {

                                                echo '<th class="col-md-1">' . lang("product_tax") . '</th>';

                                            }

                                            ?><?php */?>

                                            <th style="width:100%"><?php /*?><?= lang("subtotal"); ?> (<span

                                                    class="currency"><?= $default_currency->code ?></span>)<?php */ ?>
                                                Action     

                                            </th>

                                            <!--<th style="width: 30px !important; text-align: center;"><i

                                                    class="fa fa-trash-o"

                                                    style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
-->
                                        </tr>
										
                                        </thead>

                                        <tbody class="new_row">
                                        <tr>
                                        <td style="width:180px;"><input type="text" name="descrption" style="padding:7px; width:100%;"/></td>
                                        <td style="width:180px;"><input type="text" name="hour" style="padding:7px; width:176px;"/></td>
                                        <td style="width:180px;"><input type="text" name="quantity" style="padding:7px; width:176px;"/></td>
                                        <td style="width:180px;"><input type="text" name="price" style="padding:7px; width:176px;"/></td>
                                        <td style="width:150px; text-align:center;"><i class="fa fa-plus-circle addIcon fa-2x tip pointer sldel" id="addIcon" title="Add New" onclick="AddRow()"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clone tip pointer sldel fa-x" aria-hidden="true" title="copy" style="font-size:21px;" id="drafted"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clipboard tip pointer sldel" aria-hidden="true" title="Paste" style="font-size:21px;"></i></td>
                                        <!--<td><i id="1511866082877" class="fa fa-times tip pointer sldel" title="Remove" style="cursor:pointer;" onclick="delete_row()"></i></td>--></tr></tbody>
                                       
										

                                        <tfoot></tfoot>

                                    </table>

                                </div>

                            </div>
                            <div>
                            <table align="right">
                            <thead></thead>
                         <tbody><tr><td><input type="radio" name="gst_radio" id="igst_id" />&nbsp;&nbsp;&nbsp;IGST&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="igst_text" value="" id="textpart"/></td></tr>
                <tr><td><input type="radio" name="gst_radio" id="sgst_id" />&nbsp;&nbsp;&nbsp;SGST&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="sgst_text" id="textpart"/></td></tr>
                <tr><td><input type="radio" name="gst_radio" id="cgst_id" />&nbsp;&nbsp;&nbsp;CGST&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="cgst_text" id="textpart"/></td></tr>
                         <tr><td>Discount&nbsp;&nbsp;&nbsp;</td><td><input type="text" name="discount" value="10%"/></td></tr></tbody>
                            
                            </table>
                            
                            
                            </div>

                        </div>



                       



                        



                        



                        



                        

                       
                        

                        
                        <div class="clearfix"></div>



                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>



                        

                        <div class="col-md-12">

                            <div

                                class="fprom-group"><?php echo form_submit('edit_sale', lang("submit"), 'id="edit_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>

                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>

                            </div>

                        </div>

                    </div>

                </div>

                <div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">

                    <table class="table table-bordered table-condensed totals" style="margin-bottom:0;">

                        <tr class="warning">

                            <td><?= lang('items') ?> <span class="totals_val pull-right" id="titems">0</span></td>

                            <td><?= lang('total') ?> <span class="totals_val pull-right" id="total">0.00</span></td>

                            <?php if (($Owner || $Admin || $this->session->userdata('allow_discount')) || $inv->total_discount) { ?>

                            <td><?= lang('order_discount') ?> <span class="totals_val pull-right" id="tds">0.00</span></td>

                            <?php } ?>

                            <?php if ($Settings->tax2) { ?>

                                <td><?= lang('order_tax') ?> <span class="totals_val pull-right" id="ttax2">0.00</span></td>

                            <?php } ?>

                            <td><?= lang('shipping') ?> <span class="totals_val pull-right" id="tship">0.00</span></td>

                            <td><?= lang('grand_total') ?> <span class="totals_val pull-right" id="gtotal">0.00</span></td>

                        </tr>

                    </table>

                </div>



                <?php echo form_close(); ?>



            </div>



        </div>

    </div>

</div>



<div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i

                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>

                <h4 class="modal-title" id="prModalLabel"></h4>

            </div>

            <div class="modal-body" id="pr_popover_content">

                <form class="form-horizontal" role="form">


                    <?php if ($Settings->tax1) { ?>

                        <div class="form-group">

                            <label class="col-sm-4 control-label"><?= lang('product_tax') ?></label>

                            <div class="col-sm-8">

                                <?php

                                $tr[""] = "";

                                foreach ($tax_rates as $tax) {

                                    $tr[$tax->id] = $tax->name;

                                }

                                echo form_dropdown('ptax', $tr, "", 'id="ptax" class="form-control pos-input-tip" style="width:100%;"');

                                ?>

                            </div>

                        </div>

                    <?php } ?>

                    <?php if ($Settings->product_serial) { ?>

                        <div class="form-group">

                            <label for="pserial" class="col-sm-4 control-label"><?= lang('serial_no') ?></label>



                            <div class="col-sm-8">

                                <input type="text" class="form-control" id="pserial">

                            </div>

                        </div>

                    <?php } ?>

                    <div class="form-group">

                        <label for="pquantity" class="col-sm-4 control-label"><?= lang('quantity') ?></label>



                        <div class="col-sm-8">

                            <input type="text" class="form-control" id="pquantity">

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="punit" class="col-sm-4 control-label"><?= lang('product_unit') ?></label>

                        <div class="col-sm-8">

                            <div id="punits-div"></div>

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="poption" class="col-sm-4 control-label"><?= lang('product_option') ?></label>

                        <div class="col-sm-8">

                            <div id="poptions-div"></div>

                        </div>

                    </div>

                    <?php if ($Settings->product_discount) { ?>

                        <div class="form-group">

                            <label for="pdiscount"

                                   class="col-sm-4 control-label"><?= lang('product_discount') ?></label>



                            <div class="col-sm-8">

                                <input type="text" class="form-control" id="pdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>

                            </div>

                        </div>

                    <?php } ?>

                    <div class="form-group">

                        <label for="pprice" class="col-sm-4 control-label"><?= lang('unit_price') ?></label>



                        <div class="col-sm-8">

                            <input type="text" class="form-control" id="pprice" <?= ($Owner || $Admin || $GP['edit_price']) ? '' : 'readonly'; ?>>

                        </div>

                    </div>

                    <table class="table table-bordered table-striped">

                        <tr>

                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>

                            <th style="width:25%;"><span id="net_price"></span></th>

                            <th style="width:25%;"><?= lang('product_tax'); ?></th>

                            <th style="width:25%;"><span id="pro_tax"></span></th>

                        </tr>

                    </table>

                    <input type="hidden" id="punit_price" value=""/>

                    <input type="hidden" id="old_tax" value=""/>

                    <input type="hidden" id="old_qty" value=""/>

                    <input type="hidden" id="old_price" value=""/>

                    <input type="hidden" id="row_id" value=""/>

                </form>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="editItem"><?= lang('submit') ?></button>

            </div>

        </div>

    </div>

</div>



<div class="modal" id="mModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">

    <div class="modal-dialog">

        <div class="modal-content">

            <div class="modal-header">

                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true"><i

                            class="fa fa-2x">&times;</i></span><span class="sr-only">Close</span></button>

                <h4 class="modal-title" id="mModalLabel"><?= lang('add_product_manually') ?></h4>

            </div>

            <div class="modal-body" id="pr_popover_content">

                <form class="form-horizontal" role="form">

                    <div class="form-group">

                        <label for="mcode" class="col-sm-4 control-label"><?= lang('product_code') ?> *</label>



                        <div class="col-sm-8">

                            <input type="text" class="form-control" id="mcode">

                        </div>

                    </div>

                    <div class="form-group">

                        <label for="mname" class="col-sm-4 control-label"><?= lang('product_name') ?> *</label>



                        <div class="col-sm-8">

                            <input type="text" class="form-control" id="mname">

                        </div>

                    </div>

                    <?php if ($Settings->tax1) { ?>

                        <div class="form-group">

                            <label for="mtax" class="col-sm-4 control-label"><?= lang('product_tax') ?> *</label>



                            <div class="col-sm-8">

                                <?php

                                $tr[""] = "";

                                foreach ($tax_rates as $tax) {

                                    $tr[$tax->id] = $tax->name;

                                }

                                echo form_dropdown('mtax', $tr, "", 'id="mtax" class="form-control input-tip select" style="width:100%;"');

                                ?>

                            </div>

                        </div>

                    <?php } ?>

                    <div class="form-group">

                        <label for="mquantity" class="col-sm-4 control-label"><?= lang('quantity') ?> *</label>



                        <div class="col-sm-8">

                            <input type="text" class="form-control" id="mquantity">

                        </div>

                    </div>

                    <?php if ($Settings->product_serial) { ?>

                        <div class="form-group">

                            <label for="mserial" class="col-sm-4 control-label"><?= lang('product_serial') ?></label>



                            <div class="col-sm-8">

                                <input type="text" class="form-control" id="mserial">

                            </div>

                        </div>

                    <?php } ?>

                    <?php if ($Settings->product_discount) { ?>

                        <div class="form-group">

                            <label for="mdiscount" class="col-sm-4 control-label">

                                <?= lang('product_discount') ?>

                            </label>



                            <div class="col-sm-8">

                                <input type="text" class="form-control" id="mdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>

                            </div>

                        </div>

                    <?php } ?>

                    <div class="form-group">

                        <label for="mprice" class="col-sm-4 control-label"><?= lang('unit_price') ?> *</label>



                        <div class="col-sm-8">

                            <input type="text" class="form-control" id="mprice">

                        </div>

                    </div>

                    <table class="table table-bordered table-striped">

                        <tr>

                            <th style="width:25%;"><?= lang('net_unit_price'); ?></th>

                            <th style="width:25%;"><span id="mnet_price"></span></th>

                            <th style="width:25%;"><?= lang('product_tax'); ?></th>

                            <th style="width:25%;"><span id="mpro_tax"></span></th>

                        </tr>

                    </table>

                </form>

            </div>

            <div class="modal-footer">

                <button type="button" class="btn btn-primary" id="addItemManually"><?= lang('submit') ?></button>

            </div>

        </div>

    </div>

</div>
<script type="text/javascript">
function AddRow()
{
    $('.new_row').append('<tr class="myTableRow"><td style="width:180px;"><input type="text" name="descrption" style="padding:7px; width:100%;"/></td><td style="width:180px;"><input type="text" name="hour" style="padding:7px; width:176px;"/></td> <td style="width:180px;"><input type="text" name="quantity" style="padding:7px; width:176px;"/></td><td style="width:180px;"><input type="text" name="price" style="padding:7px; width:176px;"/></td><td style="width:180px; text-align:center;"><i class="fa fa-minus-circle tip pointer sldel fa-2x" id="minusIcon" title="Remove"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clone tip pointer sldel" aria-hidden="true" title="copy" style="font-size:21px;" id="drafted"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clipboard tip pointer sldel" aria-hidden="true" title="Paste" style="font-size:21px;"></i></td></tr>')
}
function delete_row(){
$('#myTableRow').remove();
}
$("input[type='radio']").change(function(){
        if( $(this).is(":checked") ){ 
			//function getvalue(){
						$('#textpart').val()='18%';
						//}
        }
    });
	$(document).on('click', '#minusIcon', function () { // <-- changes
     //alert("aa");
     $(this).closest('tr').remove();
     return false;
 });
 $('#drafted').click(function(event) {
   var $table = $(this).prev('table');
   var new_line = $table.find('tr:td').clone();
   alert(new_line);
   //$table.append(new_line);
});
</script>
