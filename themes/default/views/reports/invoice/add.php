<style>
.dash {
	border: 1px dashed #cccccc;
	border-collapse: collapse
}
</style>
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

                    url: '<?= site_url('invoice_management/suggestions'); ?>',

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
    <h2 class="blue"><i class="fa-fw fa fa-plus"></i>
      <?= lang('add_sale'); ?>
    </h2>
  </div>
  <div class="box-content">
    <div class="row">
      <div class="col-lg-12">
        <p class="introtext"><?php echo lang('enter_info'); ?></p>
        <?php

                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-so-form');

                echo form_open_multipart("invoice_management/add/" , $attrib)

                ?>
        <div class="row">
          <div class="col-lg-12">
            <?php if ($Owner || $Admin) { ?>
            <div class="col-md-4">
              <div class="form-group">
                <?= lang("date", "sldate"); ?>
                <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : ""), 'class="form-control input-tip datetime" id="sldate"'); ?> </div>
            </div>
            <?php } ?>
            <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("ref"); ?></label>
                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : ''), 'class="form-control input-tip" id="slref"'); ?> </div>
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

                                    class="panel-heading">
                  <?= lang('please_select_these_before_adding_product') ?>
                </div>
                <div class="panel-body" style="padding: 5px;">
                  <?php /*?> <?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>

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

                                    } ?><?php */?>
                  <div class="col-md-4">
                    <div class="form-group">
                      <?= lang("customer", "slcustomer"); ?>
                      <div class="input-group">
                        <?php
                                                echo form_input('customer', (isset($_POST['customer']) ? $_POST['customer'] : ""), 'id="slcustomer" data-placeholder="' . lang("select") . ' ' . lang("customer") . '" required="required" class="form-control input-tip" style="width:100%;"');
                                                ?>
                        <div class="input-group-addon no-print" style="padding: 2px 8px; border-left: 0;"> <a href="#" id="toogle-customer-read-attr" class="external"> <i class="fa fa-pencil" id="addIcon" style="font-size: 1.2em;"></i> </a> </div>
                        <div class="input-group-addon no-print" style="padding: 2px 7px; border-left: 0;"> <a href="#" id="view-customer" class="external" data-toggle="modal" data-target="#myModal"> <i class="fa fa-eye" id="addIcon" style="font-size: 1.2em;"></i> </a> </div>
                        <?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                        <div class="input-group-addon no-print" style="padding: 2px 8px;"> <a href="<?= site_url('customers/add'); ?>" id="add-customer"class="external" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus-circle" id="addIcon"  style="font-size: 1.2em;"></i> </a> </div>
                        <?php } ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4" style="margin-left: 15px;">
                    <div class="form-group">
                      <label>
                        <?= lang("currency"); ?> *
                      </label>
                      <div class="input-group">
                        <?php 
										$currency = array(
														'' => 'Select',
														'inr' => 'INR',
														'usd' => 'USD',
														'euro' => 'EURO'
													);
										echo form_dropdown('currency', $currency, (isset($_POST['currency']) ? $_POST['currency'] : $Settings->default_currency), 'id="slcurrency" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("invoice", "slcurrency") . '" style="width:100%;" ');
										?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-12"> 
                  <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("discount_amount"); ?></label>
                <?php echo form_input('discount_amount', (isset($_POST['discount_amount']) ? $_POST['discount_amount'] : ''), 'class="form-control input-tip" id="discount_amount"'); ?> </div>
            </div>
              
              <?php /*?><div class="well well-sm">

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

                            </div><?php */?> 
              
            </div>
            <div class="col-md-12">
              <div class="control-group table-group">
                <label class="table-label">
                  <?= lang("order_items"); ?>
                  *</label>
                <div class="controls table-controls">
                 <?php /*?> <input type="hidden" name="hidden_value" class="hidden_class" value="1"/><?php */?>
                  <table id="slTable" class="table items table-striped table-bordered table-condensed table-hover sortable_table">
                    <thead>
                      <tr> 
                        <!--<th>No</th>-->
                        
                        <th class="col-md-5"><?= lang("description"); ?></th>
                        <th><?= lang("hour"); ?></th>
                        <th><?= lang("amount"); ?></th>
                        <th><?= lang("total"); ?></th>
                        <th class="col-md-2"><?= lang("action"); ?></th>
                        
                        <!--<th style="width: 30px !important; text-align: center;"><i

                                                    class="fa fa-trash-o"

                                                    style="opacity:0.5; filter:alpha(opacity=50);"></i></th>
--> 
                      </tr>
                    </thead>
                    <?php $i=1;?>
                    <tbody class="new_row">
                      <tr class="first_row">
                        <td style="width:180px;">
                        <?php 
							$input_description = array(
														'type'=>'text',
														'name'=>'description[]',
														'style'=>'padding:7px; width:100%;',
														'class'=>'message',
														'required'=>'required',
														'data-increament'=>$i
													);
							echo form_input($input_description);
						?>
                        <?php /*?><input type="text" name="description[]" style="padding:7px; width:100%;" class="message" required="required" data-increment="<?php echo $i?>"/><?php */?>
                        </td>
                        <td style="width:180px;">
                        <?php 
							$input_hour = array(
														'type'=>'text',
														'name'=>'hour[]',
														'style'=>'padding:7px; width:100%;',
														'class'=>'message1 hour_class txt',
														'data-increament'=>$i
													);
							echo form_input($input_hour);
						?>
                        <?php /*?><input type="text" name="hour[]" style="padding:7px; width:176px;" class="message1 hour_class txt" data-increment="<?php echo $i?>"/><?php */?>
                        </td>
                        <td style="width:180px;">
                        <?php 
							$input_amount = array(
														'type'=>'text',
														'name'=>'amount[]',
														'style'=>'padding:7px; width:100%;',
														'class'=>'message2 amount_class txt',
														'required'=>'required',
														'data-increament'=>$i
													);
							echo form_input($input_amount);
						?>
                        <?php /*?><input type="text" name="amount[]" style="padding:7px; width:176px;" class="message2 amount_class txt" data-increment="<?php echo $i?>" required="required"/><?php */?>
                        </td>
                        <td style="width:180px;">
                        <?php 
							$input_total_amount = array(
														'type'=>'text',
														'name'=>'price[]',
														'style'=>'padding:7px; width:100%;',
														'class'=>'message3 price_class',
														'data-increament'=>$i,
														'readonly' => 'readonly'
													);
							echo form_input($input_total_amount);
						?>
                        <?php /*?><input type="text" name="price[]" style="padding:7px; width:176px;" class="message3 price_class" data-increment="<?php echo $i?>" readonly="readonly"/><?php */?>
                        </td>
                        <td style="width:150px; text-align:center;"><i class="fa fa-plus-circle addIcon fa-2x tip pointer sldel" id="addIcon" title="Add New" onclick="AddRow()"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-minus-circle tip pointer sldel fa-2x reset_detail" title="Remove"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clone tip pointer sldel fa-x copy_toclip" aria-hidden="true" title="copy" style="font-size:21px;" id="drafted"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clipboard tip pointer sldel msg" aria-hidden="true" title="Paste" style="font-size:21px;"></i></td>
                        <!--<td><i id="1511866082877" class="fa fa-times tip pointer sldel" title="Remove" style="cursor:pointer;" onclick="delete_row()"></i></td>--></tr>
                        
                      <tr class="total_row total_amount">
                        <td colspan="3" style="width:180px; text-align:right; font-weight: bold;"><?= lang("total_amount"); ?></td>
                        <td style="width:180px; text-align:center; font-weight: bold;" colspan="2" class="total_price">0.00</td>
                        <?php 
							$total_amount = array(
														'type'=>'hidden',
														'id'=>'total_amount',
														'name' => 'total_amount'
													);
							echo form_input($total_amount);
						?>
                        <?php /*?><input type="hidden" id="total_amount" value=""/><?php */?>
                        
                        <!--<td><i id="1511866082877" class="fa fa-times tip pointer sldel" title="Remove" style="cursor:pointer;" onclick="delete_row()"></i></td>--></tr>
                    </tbody>
                    <tfoot>
                    </tfoot>
                  </table>
                </div>
              </div>
              <div>
                <table id="gst_calculate" align="right" style="display: none;">
                  <thead>
                  </thead>
                  <tbody>
                    <tr id="first_radio">
                      <td>
                      <?php 
							$input_gst_radio = array(
														'type'=>'radio',
														'name'=>'gst_radio',
														'id'=>'igst_id'
													);
							echo form_input($input_gst_radio);
						?>
                      <?php /*?><input type="radio" name="gst_radio" id="igst_id" /><?php */?>
                        &nbsp;&nbsp;&nbsp;<b>IGST@
                        <?php 
							$input_igst_percentage = array(
														'type'=>'text',
														'name'=>'igst_percentage',
														'style'=>'width: 40px;',
														'id'=>'igst_percentage',
														'placeholder'=>'18'
													);
							echo form_input($input_igst_percentage);
						?>
                        <?php /*?><input style="width: 40px;" type="text" name="igst_percentage" id="igst_percentage" placeholder="18"/><?php */?>
                        %</b>&nbsp;&nbsp;&nbsp;</td>
                      <td>
                      <?php 
							$input_igst_text = array(
														'type'=>'text',
														'name'=>'igst_text',
														'id'=>'textpart'
													);
							echo form_input($input_igst_text);
						?>
                      <?php /*?><input type="text" name="igst_text" id="textpart"/><?php */?>
                      </td>
                    </tr>
                    <tr id="second_radio">
                      <td>
                      <?php 
							$input_gst_radio = array(
														'type'=>'radio',
														'name'=>'gst_radio',
														'id'=>'sgst_id'
													);
							echo form_input($input_gst_radio);
						?>
                     <?php /*?> <input type="radio" name="gst_radio" id="sgst_id" /><?php */?>
                        &nbsp;&nbsp;&nbsp;<b>SGST@
                         <?php 
							$input_sgst_percentage = array(
														'type'=>'text',
														'name'=>'sgst_percentage',
														'id'=>'sgst_percentage',
														'style'=>'width: 40px;',
														'placeholder'=>'9'
													);
							echo form_input($input_sgst_percentage);
						?>
                       <?php /*?> <input style="width: 40px;" type="text" name="sgst_percentage" id="sgst_percentage" placeholder="9"/><?php */?>
                        %</b>&nbsp;&nbsp;&nbsp;</td>
                      <td>
                      <?php 
							$input_sgst_text = array(
														'type'=>'text',
														'name'=>'sgst_text',
														'id'=>'textpart1',
														'value'=>''
													);
							echo form_input($input_sgst_text);
						?>
                      <?php /*?><input type="text" name="sgst_text" id="textpart1" value=""/><?php */?></td>
                    </tr>
                    <tr>
                      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b style="margin-left: 15px;">CGST@
                        <?php 
							$input_cgst_percentage = array(
														'type'=>'text',
														'name'=>'cgst_percentage',
														'id'=>'cgst_percentage',
														'style'=>'width: 40px;',
														'placeholder'=>'9'
													);
							echo form_input($input_cgst_percentage);
						?>
                        <?php /*?><input style="width: 40px;" type="text" name="cgst_percentage" id="cgst_percentage" placeholder="9"/><?php */?>
                        %</b>&nbsp;&nbsp;&nbsp;</td>
                      <td>
                       <?php 
							$input_cgst_text = array(
														'type'=>'text',
														'name'=>'cgst_text',
														'id'=>'textpart2',
														'value'=>''
													);
							echo form_input($input_cgst_text);
						?>
                      <?php /*?><input type="text" name="cgst_text" id="cgst_text" value=""/><?php */?></td>
                    </tr>
                    <tr>
                      <td style="text-align:center;"><b>
                        <?= lang("total_amount_gst"); ?>
                        </b></td>
                      <td style="text-align:center; font-weight: bold;" class="total_amount_gst">0.00</td>
                      <?php 
							$input_total_gst_amount = array(
														'type'=>'hidden',
														'name'=>'total_gst_amount',
														'id'=>'total_amount_gst'
													);
							echo form_input($input_total_gst_amount);
						?>
                      <?php /*?><input type="hidden" name="total_gst_amount" id="total_amount_gst" /><?php */?>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="clearfix"></div>
            <div class="col-md-12">
              <div class="fprom-group"><?php echo form_submit('add_invoice', lang("submit"), 'id="add_invoice" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                <?php /*?><button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button><?php */?>
              </div>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?> </div>
    </div>
  </div>
</div>
<?php /*?>
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
            <label class="col-sm-4 control-label">
              <?= lang('product_tax') ?>
            </label>
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
            <label for="pserial" class="col-sm-4 control-label">
              <?= lang('serial_no') ?>
            </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="pserial">
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <label for="pquantity" class="col-sm-4 control-label">
              <?= lang('quantity') ?>
            </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="pquantity">
            </div>
          </div>
          <div class="form-group">
            <label for="punit" class="col-sm-4 control-label">
              <?= lang('product_unit') ?>
            </label>
            <div class="col-sm-8">
              <div id="punits-div"></div>
            </div>
          </div>
          <div class="form-group">
            <label for="poption" class="col-sm-4 control-label">
              <?= lang('product_option') ?>
            </label>
            <div class="col-sm-8">
              <div id="poptions-div"></div>
            </div>
          </div>
          <?php if ($Settings->product_discount) { ?>
          <div class="form-group">
            <label for="pdiscount"

                                   class="col-sm-4 control-label">
              <?= lang('product_discount') ?>
            </label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="pdiscount" <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"'; ?>>
            </div>
          </div>
          <?php } ?>
          <div class="form-group">
            <label for="pprice" class="col-sm-4 control-label">
              <?= lang('unit_price') ?>
            </label>
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
        <button type="button" class="btn btn-primary" id="editItem">
        <?= lang('submit') ?>
        </button>
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
        <h4 class="modal-title" id="mModalLabel">
          <?= lang('add_product_manually') ?>
        </h4>
      </div>
      <div class="modal-body" id="pr_popover_content">
        <form class="form-horizontal" role="form">
          <div class="form-group">
            <label for="mcode" class="col-sm-4 control-label">
              <?= lang('product_code') ?>
              *</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mcode">
            </div>
          </div>
          <div class="form-group">
            <label for="mname" class="col-sm-4 control-label">
              <?= lang('product_name') ?>
              *</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mname">
            </div>
          </div>
          <?php if ($Settings->tax1) { ?>
          <div class="form-group">
            <label for="mtax" class="col-sm-4 control-label">
              <?= lang('product_tax') ?>
              *</label>
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
            <label for="mquantity" class="col-sm-4 control-label">
              <?= lang('quantity') ?>
              *</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mquantity">
            </div>
          </div>
          <?php if ($Settings->product_serial) { ?>
          <div class="form-group">
            <label for="mserial" class="col-sm-4 control-label">
              <?= lang('product_serial') ?>
            </label>
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
            <label for="mprice" class="col-sm-4 control-label">
              <?= lang('unit_price') ?>
              *</label>
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
        <button type="button" class="btn btn-primary" id="addItemManually">
        <?= lang('submit') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<div class="modal" id="gcModal" tabindex="-1" role="dialog" aria-labelledby="mModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                        class="fa fa-2x">&times;</i></button>
        <h4 class="modal-title" id="myModalLabel">
          <?= lang('sell_gift_card'); ?>
        </h4>
      </div>
      <div class="modal-body">
        <p>
          <?= lang('enter_info'); ?>
        </p>
        <div class="alert alert-danger gcerror-con" style="display: none;">
          <button data-dismiss="alert" class="close" type="button">×</button>
          <span id="gcerror"></span> </div>
        <div class="form-group">
          <?= lang("card_no", "gccard_no"); ?>
          *
          <div class="input-group"> <?php echo form_input('gccard_no', '', 'class="form-control" id="gccard_no"'); ?>
            <div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;"><a href="#"
                                                                                                           id="genNo"><i
                                    class="fa fa-cogs"></i></a></div>
          </div>
        </div>
        <input type="hidden" name="gcname" value="<?= lang('gift_card') ?>" id="gcname"/>
        <div class="form-group">
          <?= lang("value", "gcvalue"); ?>
          * <?php echo form_input('gcvalue', '', 'class="form-control" id="gcvalue"'); ?> </div>
        <div class="form-group">
          <?= lang("price", "gcprice"); ?>
          * <?php echo form_input('gcprice', '', 'class="form-control" id="gcprice"'); ?> </div>
        <div class="form-group">
          <?= lang("customer", "gccustomer"); ?>
          <?php echo form_input('gccustomer', '', 'class="form-control" id="gccustomer"'); ?> </div>
        <div class="form-group">
          <?= lang("expiry_date", "gcexpiry"); ?>
          <?php echo form_input('gcexpiry', $this->sma->hrsd(date("Y-m-d", strtotime("+2 year"))), 'class="form-control date" id="gcexpiry"'); ?> </div>
      </div>
      <div class="modal-footer">
        <button type="button" id="addGiftCard" class="btn btn-primary">
        <?= lang('sell_gift_card') ?>
        </button>
      </div>
    </div>
  </div>
</div>
<?php */?>
<script type="text/javascript">

function AddRow()
{
	var i=$('.hidden_class').val();
	i++;
	
     $('.new_row').find('tr:last').prev().after('<tr class="first_row"><td style="width:180px;"><input type="text" name="description[]" style="padding:7px; width:100%;" class="message" data-increment='+i+'></td><td style="width:180px;"><input type="text" name="hour[]" style="padding:7px; width:176px;" class="message1 hour_class txt" data-increment='+i+'></td> <td style="width:180px;"><input type="text" name="amount[]" style="padding:7px; width:176px;" class="message2 amount_class txt" data-increment='+i+' required="required"></td><td style="width:180px;"><input type="text" name="price[]" style="padding:7px; width:176px;" class="message3 price_class" readonly="readonly" data-increment='+i+'></td><td style="width:180px; text-align:center;"><i class="fa fa-plus-circle addIcon tip pointer sldel fa-2x" id="addIcon" title="Add New" onclick="AddRow()"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-minus-circle tip pointer sldel fa-2x" id="minusIcon" title="Remove"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clone tip pointer sldel copy_toclip" aria-hidden="true" title="copy" style="font-size:21px;" id="drafted"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clipboard tip pointer sldel msg" aria-hidden="true" title="Paste" style="font-size:21px;"></i></td></tr>');
	
$('.hidden_class').val(i);
}

/*function delete_row(){
	$('#myTableRow').remove();
}*/

/*$("input[type='radio']").change(function(){
        if($(this).is(":checked")){
			$('#textpart').val()='18%';
        }
    });*/
	
	
	var total_price = 0;
	$(document).on('keyup', ".txt",function () {  
    var hour = $(this).closest('td').parent().children(':first-child').next().children().val();
	var amount = $(this).closest('td').parent().children(':first-child').next().next().children().val();
	if(amount!='' && amount!='0' && hour!='0'){
			if(hour==''){
				hour = '1';
			}
			var total = amount*hour;
			$(this).closest('td').parent().children(':first-child').next().next().next().children().val(total);
		var  total_price = 0;
		$(".price_class").each(function() {
      if (!$(this).closest('td').parent().children(':first-child').next().next().next().children().val() == '') {
        total_price = total_price + parseFloat($(this).closest('td').parent().children(':first-child').next().next().next().children().val());
      }
	});
	$(".total_price").html(total_price);
	$("#total_amount").val(total_price);
	$('.iradio_square-blue').removeClass('checked');
	$('.iradio_square-blue').attr('aria-checked','false');
	$('#textpart').val('');
	$('#textpart1').val('');
	$('#textpart2').val('');
	$('#sgst_percentage').val('');
	$('#igst_percentage').val('');
	$('#cgst_percentage').val('');
	$('#textpart').attr('readonly','readonly');
	$('#textpart1').attr('readonly','readonly');
	$('#textpart2').attr('readonly','readonly');
	$('#sgst_percentage').attr('readonly','readonly');
	$('#igst_percentage').attr('readonly','readonly');
	$('#cgst_percentage').attr('readonly','readonly');
	}
	else{
		$(this).closest('td').parent().children(':first-child').next().next().next().children().val('');
		$(".total_price").html('0.00');
		$("#total_amount").val();
		$('.iradio_square-blue').removeClass('checked');
		$('.iradio_square-blue').attr('aria-checked','false');
		$('#textpart').val('');
		$('#textpart1').val('');
		$('#textpart2').val('');
		$('#sgst_percentage').val('');
		$('#igst_percentage').val('');
		$('#cgst_percentage').val('');
		$('#textpart').attr('readonly','readonly');
		$('#textpart1').attr('readonly','readonly');
		$('#textpart2').attr('readonly','readonly');
		$('#sgst_percentage').attr('readonly','readonly');
		$('#igst_percentage').attr('readonly','readonly');
		$('#cgst_percentage').attr('readonly','readonly');
	}
	});

	$(document).on('click', '#minusIcon', function () {
     $(this).closest('tr').remove();
	 var  total_price = 0;
		$(".price_class").each(function() {
      if (!$(this).closest('td').parent().children(':first-child').next().next().next().children().val() == '') {
        total_price = total_price + parseFloat($(this).closest('td').parent().children(':first-child').next().next().next().children().val());
      }
	});
	$(".total_price").html(total_price);
	$("#total_amount").val(total_price);
	$('.iradio_square-blue').removeClass('checked');
	$('.iradio_square-blue').attr('aria-checked','false');
	$('#textpart').val('');
	$('#textpart1').val('');
	$('#textpart2').val('');
	$('#sgst_percentage').val('');
	$('#igst_percentage').val('');
	$('#cgst_percentage').val('');
	$('#textpart').attr('readonly','readonly');
	$('#textpart1').attr('readonly','readonly');
	$('#textpart2').attr('readonly','readonly');
	$('#sgst_percentage').attr('readonly','readonly');
	$('#igst_percentage').attr('readonly','readonly');
	$('#cgst_percentage').attr('readonly','readonly');
     return false;
 });
 

	var paste_data;
	var copyText1 = "";
	var copyText2 = "";
	var copyText3 = "";
	var copyText4 = "";
	$(document).on('click','.copy_toclip',function(){
	 copyText1 = $(this).closest('td').parent().children(':first-child').children().val();
	 copyText2 = $(this).closest('td').parent().children(':first-child').next().children().val();
	 copyText3 = $(this).closest('td').parent().children(':first-child').next().next().children().val();
	 copyText4 = $(this).closest('td').parent().children(':first-child').next().next().next().children().val();
	});

$('.msg').click(function () {
  var x=$(this).closest('td').prev('td').find('input').attr('data-increment');
});

$(document).ready(function(){
	
	$('#slcurrency').on('change', function(){
		if($(this).val()!='' && $(this).val()=='inr'){
			$('#gst_calculate').show();
			$('.total_amount').hide();
		}
		else{
			$('#gst_calculate').hide();
			$('.total_amount').show();
		}
	});
	
	$('#textpart').attr('readonly','readonly');
	$('#textpart1').attr('readonly','readonly');
	$('#textpart2').attr('readonly','readonly');
	$('#sgst_percentage').attr('readonly','readonly');
	$('#igst_percentage').attr('readonly','readonly');
	$('#cgst_percentage').attr('readonly','readonly');
	
	setTimeout(function(){
	$('.iCheck-helper').click(function() {
    if($(this).parent().children(':first-child').prop("id")=="igst_id"){   
	$('#igst_percentage').val('18');
 	 var sum=0;
	 
    /*$(".first_row").each(function() {
      var value2=$(this).children('td').eq(3).children('input').val();
	  if(value2==""){value2=0;}
      var value=parseInt(value2);
      sum=sum+parseInt(value);
    });*/
	if($('.total_price').text()!='0.00'){
		sum = $('.total_price').text();
	}
	var value1=sum*18/100;
	if(value1!='' && value1!=0){
		var total_amount_gst = parseFloat(value1)+parseInt(sum);
		$('.total_amount_gst').html(total_amount_gst);
		$('#total_amount_gst').val(total_amount_gst);
	}
	
	$(this).closest('td').next().find('#textpart').val(value1);
	$(this).closest('td').next().find('#textpart').removeAttr('readonly');
	$('#igst_percentage').removeAttr('readonly');
	$('#textpart1').val('');
	$('#textpart2').val('');
	$('#textpart1').attr('readonly','readonly');
	$('#textpart2').attr('readonly','readonly');
	$('#sgst_percentage').attr('readonly','readonly');
	$('#sgst_percentage').val('');
	$('#cgst_percentage').attr('readonly','readonly');
	$('#cgst_percentage').val('');
	}
	});
	
	$('.iCheck-helper').click(function() {
	if($(this).parent().children(':first-child').prop("id")=="sgst_id"){
		$('#sgst_percentage').val('9');
		$('#cgst_percentage').val('9');
		 var sum=0;
    /*$(".first_row").each(function() {
      var value2=$(this).children('td').eq(3).children('input').val();
	  if(value2==""){value2=0;}
      var value=parseInt(value2);
      sum=sum+parseInt(value);
      
    });*/
	if($('.total_price').text()!='0.00'){
		sum = $('.total_price').text();
	}
       
  //alert();
 // $(this).closest('td').next().find('#textpart').remove();
	var value=sum*9/100;
	if(value!='' && value!=0){
		var total_amount_gst = parseInt(sum)+parseFloat(value*2);
		$('.total_amount_gst').html(total_amount_gst);
		$('#total_amount_gst').val(total_amount_gst);
	}
	//alert(value);
	//$('#textpart').val('');
	$(this).closest('td').next().find('#textpart1').val(value);
	$(this).closest('tr').next().find('#textpart2').val(value);
	$(this).closest('td').next().find('#textpart1').removeAttr('readonly');
	$(this).closest('tr').next().find('#textpart2').removeAttr('readonly');
	$('#sgst_percentage').removeAttr('readonly');
	$('#cgst_percentage').removeAttr('readonly');
	$('#textpart').attr('readonly','readonly');
	$('#textpart').val('');
	$('#igst_percentage').attr('readonly','readonly');
	$('#igst_percentage').val('');
	}
	});
		
		},3000);
	
	
	})	
	
	$(document).on('keyup', "#sgst_percentage",function () {
		var sum = 0;
		var sgst_percentage = $(this).val();
		var cgst_percentage = 0;
		if(sgst_percentage!='0' && sgst_percentage!=''){
			if($('.total_price').text()!='0.00'){
				sum = $('.total_price').text();
			} 
			if($("input[name='cgst_text']").val()!=''){
				cgst_percentage = $("input[name='cgst_text']").val();
			}
		var value=sum*sgst_percentage/100;
		if(value!='' && value!=0){
			var total_amount_gst = parseFloat(value)+parseInt(sum)+parseFloat(cgst_percentage);
			$('.total_amount_gst').html(total_amount_gst);
			$('#total_amount_gst').val(total_amount_gst);
		}
		$("input[name='sgst_text']").val(value);
		}
	});
	
	$(document).on('keyup', "#igst_percentage",function () {
		var sum = 0;
		var igst_percentage = $(this).val();
		if(igst_percentage!='0' && igst_percentage!=''){
			if($('.total_price').text()!='0.00'){
				sum = $('.total_price').text();
			} 
		var value=sum*igst_percentage/100;
		if(value!='' && value!=0){
			var total_amount_gst = parseFloat(value)+parseInt(sum);
			$('.total_amount_gst').html(total_amount_gst);
			$('#total_amount_gst').val(total_amount_gst);
		}
		$("input[name='igst_text']").val(value);
		}
	});
	
	$(document).on('keyup', "#cgst_percentage",function () {
		var sum = 0;
		var cgst_percentage = $(this).val();
		var sgst_percentage = 0;
		if(cgst_percentage!='0' && cgst_percentage!=''){
			if($('.total_price').text()!='0.00'){
				sum = $('.total_price').text();
			} 
			if($("input[name='sgst_text']").val()!=''){
				sgst_percentage = $("input[name='sgst_text']").val();
			}
		var value=sum*cgst_percentage/100;
		if(value!='' && value!=0){
			var total_amount_gst = parseFloat(value)+parseInt(sum)+parseFloat(sgst_percentage);
			$('.total_amount_gst').html(total_amount_gst);
			$('#total_amount_gst').val(total_amount_gst);
		}
		$("input[name='cgst_text']").val(value);
		}
	});	
	
/*function findTotals() {
  var sum=0;
    $(".first_row").each(function() {
      var value2=$(this).children('td').eq(3).children('input').val();
	  if(value2==""){value2=0;}
      var value=parseInt(value2);
      sum=sum+parseInt(value);
      
    });
 var igst=$('#textpart').val();
  var sgst=$('#textpart1').val();
  if(sgst==""){sgst=0;}
  var cgst=$('#textpart2').val();
  if(cgst==""){cgst=0;}
  var discount=$('.discount_class').val();
  if(discount==""){discount=0;}
  if(discount[discount.length - 1] === '%') {
  var actual_discont= parseInt(discount);
  sum=sum+igst+sgst+cgst-(sum*actual_discont/100);
}
else{ 
  var actual_discont= parseInt(discount);
  sum=sum+igst+sgst+cgst-actual_discont;
  }

$('.total_amount').val(sum);
}
*/
 


$(document).on('click','.fa.fa-clipboard.tip.pointer.sldel.msg',function(){
  $(this).closest('td').parent().children(':first-child').children().val(copyText1);
  $(this).closest('td').parent().children(':first-child').next().children().val(copyText2);
  $(this).closest('td').parent().children(':first-child').next().next().children().val(copyText3);
  $(this).closest('td').parent().children(':first-child').next().next().next().children().val(copyText4);
  var  total_price = 0;
		$(".price_class").each(function() {
      if (!$(this).closest('td').parent().children(':first-child').next().next().next().children().val() == '') {
        total_price = total_price + parseFloat($(this).closest('td').parent().children(':first-child').next().next().next().children().val());
      }
	});
	$(".total_price").html(total_price);
	$("#total_amount").val(total_price);
	$('.iradio_square-blue').removeClass('checked');
	$('.iradio_square-blue').attr('aria-checked','false');
	$('#textpart').val('');
	$('#textpart1').val('');
	$('#textpart2').val('');
	$('#sgst_percentage').val('');
	$('#igst_percentage').val('');
	$('#cgst_percentage').val('');
	$('#textpart').attr('readonly','readonly');
	$('#textpart1').attr('readonly','readonly');
	$('#textpart2').attr('readonly','readonly');
	$('#sgst_percentage').attr('readonly','readonly');
	$('#igst_percentage').attr('readonly','readonly');
	$('#cgst_percentage').attr('readonly','readonly');
})
  
$(document).on('click','.reset_detail',function(){
 $(this).closest('td').parent().children(':first-child').children().val('');
 $(this).closest('td').parent().children(':first-child').next().children().val('');
  $(this).closest('td').parent().children(':first-child').next().next().children().val('');
  $(this).closest('td').parent().children(':first-child').next().next().next().children().val('');
  var  total_price = 0;
		$(".price_class").each(function() {
      if (!$(this).closest('td').parent().children(':first-child').next().next().next().children().val() == '') {
        total_price = total_price + parseFloat($(this).closest('td').parent().children(':first-child').next().next().next().children().val());
      }
	});
	$(".total_price").html(total_price);
	$("#total_amount").val(total_price);
	$('.iradio_square-blue').removeClass('checked');
	$('.iradio_square-blue').attr('aria-checked','false');
	$('#textpart').val('');
	$('#textpart1').val('');
	$('#textpart2').val('');
	$('#sgst_percentage').val('');
	$('#igst_percentage').val('');
	$('#cgst_percentage').val('');
	$('#textpart').attr('readonly','readonly');
	$('#textpart1').attr('readonly','readonly');
	$('#textpart2').attr('readonly','readonly');
	$('#sgst_percentage').attr('readonly','readonly');
	$('#igst_percentage').attr('readonly','readonly');
	$('#cgst_percentage').attr('readonly','readonly');
});

/*$('input').on('change', function() {
if($('.inr_class').is(':checked')) { alert("it's checked"); }
});*/

</script> 
<script type="text/javascript">
    $(document).ready(function () {
       /* $('#gccustomer').select2({
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "customers/suggestions",
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
        });*/
		
		$('#slcustomer').select2({
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "customers/suggestions",
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
		
		
        $('#genNo').click(function () {
            var no = generateCardNo();
            $(this).parent().parent('.input-group').children('input').val(no);
            return false;
        });
    });
	
	$(document).on('keydown', ".txt", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

	$(document).on('keydown', "#discount_amount", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
	$(document).on('keydown', "#igst_percentage", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
	$(document).on('keydown', "#sgst_percentage", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

	$(document).on('keydown', "#cgst_percentage", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

	$(document).on('keydown', "input[name='igst_text']", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
	$(document).on('keydown', "input[name='sgst_text']", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

	$(document).on('keydown', "input[name='cgst_text']", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

/*	$(".amount_class").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });*/	
	
	$(document).on('keydown', ".price_class", function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A, Command+A
            (e.keyCode === 65 && (e.ctrlKey === true || e.metaKey === true)) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
	
</script> 
