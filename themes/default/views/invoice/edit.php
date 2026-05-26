<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">

    var count = 1, an = 1, product_variant = 0, DT = <?= $Settings->default_tax_rate ?>,

        product_tax = 0, invoice_tax = 0, total_discount = 0, total = 0, allow_discount = <?= ($Owner || $Admin || $this->session->userdata('allow_discount')) ? 1 : 0; ?>,

        tax_rates = '<?php //echo json_encode($tax_rates); ?>' ;

    //var audio_success = new Audio('<?= $assets ?>sounds/sound2.mp3');

    //var audio_error = new Audio('<?= $assets ?>sounds/sound3.mp3');

    $(document).ready(function () {

        <?php if ($inv) { ?>

        localStorage.setItem('sldate', '<?= $this->sma->hrld($inv->date) ?>');

        localStorage.setItem('slcustomer', '<?= $inv->customer_id ?>');

        localStorage.setItem('slbiller', '<?= $inv->biller_id ?>');

        localStorage.setItem('slref', '<?= $inv->reference_no ?>');

        <?php } ?>

        <?php if ($Owner || $Admin) { ?>

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

        //ItemnTotals();

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



        $(window).bind('beforeunload', function (e) {

            localStorage.setItem('remove_slls', true);

            if (count > 1) {

                var message = "You will loss data!";

                return message;

            }

        });

        $('#reset').click(function (e) {

            $(window).unbind('beforeunload');

        });

        $('#edit_sale').click(function () {

            $(window).unbind('beforeunload');

            $('form.edit-so-form').submit();

        });

        // Toggle payment_date visibility based on payment_status
        $(document).on('change', '#slpayment_status', function() {
            if ($(this).val() == 'paid') {
                $('#payment_date_container').show();
            } else {
                $('#payment_date_container').hide();
                $('#slpayment_date').val('');
            }
        });

    });

</script>





<div class="box">

    <div class="box-header">

        <h2 class="blue"><i class="fa-fw fa fa-plus"></i><?= lang('edit_sale'); ?></h2>

    </div>

    <div class="box-content">

        <div class="row">

            <div class="col-lg-12">



                <p class="introtext"><?php echo lang('edit_sale'); ?></p>

                <?php

                $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'class' => 'edit-so-form');

                echo form_open_multipart("invoice_management/edit/" . $inv->id, $attrib)

                ?>





                <div class="row">
                <div class="alert alert-danger date-con" style="display: none;">
                  <button data-dismiss="alert" class="close" type="button">×</button>
                  <span id="date-error"></span> </div>
          		<div class="alert alert-danger customer-con" style="display: none;">
                  <button data-dismiss="alert" class="close" type="button">×</button>
                  <span id="customer-error"></span> </div>

                    <div class="col-lg-12">

                        <?php if ($Owner || $Admin) { ?>

                            <div class="col-md-4">

                                <div class="form-group">

                                    <?= lang("date", "sldate"); ?>

                                    <?php echo form_input('date', (isset($_POST['date']) ? $_POST['date'] : $this->sma->hrld($inv->date)), 'class="form-control input-tip datetime" id="sldate" required="required"'); ?>

                                </div>

                            </div>

                        <?php } ?>

                        <div class="col-md-4">

                            <div class="form-group">

                              <label> <?= lang("ref"); ?></label>

                                <?php echo form_input('reference_no', (isset($_POST['reference_no']) ? $_POST['reference_no'] : $inv->reference_no), 'class="form-control input-tip" id="slref"'); ?>

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

                                    echo form_dropdown('biller', $bl, (isset($_POST['biller']) ? $_POST['biller'] : $inv->biller_id), 'id="slbiller" data-placeholder="' . lang("select") . ' ' . lang("biller") . '" required="required" class="form-control input-tip select" style="width:100%;"');

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


                                <div class="panel-body" style="padding: 5px;">



                                    <?php /*?><?php if ($Owner || $Admin || !$this->session->userdata('warehouse_id')) { ?>

                                        <div class="col-md-4">

                                            <div class="form-group">

                                                <?= lang("warehouse", "slwarehouse"); ?>

                                                <?php

                                                $wh[''] = '';

                                                foreach ($warehouses as $warehouse) {

                                                    $wh[$warehouse->id] = $warehouse->name;

                                                }

                                                echo form_dropdown('warehouse', $wh, (isset($_POST['warehouse']) ? $_POST['warehouse'] : $inv->warehouse_id), 'id="slwarehouse" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("warehouse") . '" required="required" style="width:100%;" ');

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
                                                <?php /*?><div class="input-group-addon" style="padding-left: 10px; padding-right: 10px;">
                                                    <a href="javascript:void(0);" id="removeReadonly">
                                                        <i class="fa fa-unlock" id="unLock"></i>
                                                    </a>
                                                </div><?php */?>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-4" style="margin-left: 15px;">
                                        <div class="form-group">
                                          <label>
                                            <?= lang("currency"); ?>
                                          </label>
                                          <div class="input-group">
                                            <?php 
													$currency[''] = 'Select Currency';
													foreach($currencies as $currency_data){
														$currency[$currency_data->code] = $currency_data->name;
													}
                                        			/*$currency = array(
																		'USD' => 'USD',
																		'INR' => 'INR',
																		'EURO' => 'EURO'
																	);*/
                                                            echo form_dropdown('currency', $currency, (isset($_POST['currency']) ? $_POST['currency'] : $inv->currency), 'id="slcurrency" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("invoice", "slcurrency") . '" style="width:100%;" ');
                                                            ?>
                                          </div>
                                        </div>
                					  </div>
                                      
                                     <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("payment_mode"); ?> *</label>
                <?php
				$payment_mode = array(
									''=>'Select Payment Mode',
									'Wire-Transfer'=>'Wire Transfer',
									'Paypal'=>'Paypal',
									'Neft'=>'NEFT',
									'Digital-Wire'=>'Digital Wire'
								);
				echo form_dropdown('payment_mode', $payment_mode, (isset($_POST['payment_mode']) ? $_POST['payment_mode'] : $inv->payment_mode), 'id="slpayment_mode" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("payment_mode") . '" style="width:100%;" '); ?> </div>
            </div>
                                     <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("payment_status"); ?></label>
                <?php
				$payment_status = array(
									'pending'=>'Pending',
									'paid'=>'Paid'
								);
				echo form_dropdown('payment_status', $payment_status, (isset($_POST['payment_status']) ? $_POST['payment_status'] : $inv->payment_status), 'id="slpayment_status" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("payment_status") . '" style="width:100%;" '); ?> </div>
            </div>
                                     <div class="col-md-4" id="payment_date_container" style="<?= $inv->payment_status == 'paid' ? '' : 'display:none;' ?>">
              <div class="form-group">
                <label><?= lang("payment_date"); ?></label>
                <?php echo form_input('payment_date', (isset($_POST['payment_date']) ? $_POST['payment_date'] : ($inv->payment_date ? $this->sma->hrld($inv->payment_date) : '')), 'class="form-control input-tip datetime" id="slpayment_date"'); ?> </div>
            </div>
                                </div>
                            </div>



                        </div>
                        <div class="col-md-12">
                        <div class="col-md-4">
        <div class="form-group">
                <label><?= lang("discount_amount"); ?></label>
                <?php echo form_input('discount_amount', (isset($_POST['discount_amount']) ? $_POST['discount_amount'] : $inv->discount), 'class="form-control input-tip" id="discount_amount"'); ?> </div>
            </div>
						<div class="col-md-4">
                          <div class="form-group">
                            <label><?= lang("commision_fee"); ?></label>
                           <div style="padding:0px;" class="col-md-8"> 
                            <?php 
                            echo form_input('commision_fee', (isset($_POST['commision_fee']) ? $_POST['commision_fee'] : $inv->commision_fees), 'class="form-control input-tip" id="commision_fee"'); ?> 
                            </div>
                            <div class="col-md-4"> 
                            <?php 
                            echo form_input('commision_fee_percentage', (isset($_POST['commision_fee_percentage']) ? $_POST['commision_fee_percentage'] : ''), 'class="form-control input-tip" id="commision_fee_percentage"'); ?> 
                            </div>
                        </div>
                        </div>
                        </div>




                        <?php /*?><div class="col-md-12" id="sticker">

                            <div class="well well-sm">

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

                            </div>

                        </div><?php */?>



                        <?php /*?><div class="col-md-12">

                            <div class="control-group table-group">

                                <label class="table-label"><?= lang("order_items"); ?> *</label>



                                <div class="controls table-controls">

                                    <table id="slTable"

                                           class="table items table-striped table-bordered table-condensed table-hover sortable_table">

                                        <thead>

                                        <tr>

                                            <th class="col-md-4"><?= lang("product_name") . " (" . lang("product_code") . ")"; ?></th>

                                            <?php

                                            if ($Settings->product_serial) {

                                                echo '<th class="col-md-2">' . lang("serial_no") . '</th>';

                                            }

                                            ?>

                                            <th class="col-md-1"><?= lang("net_unit_price"); ?></th>

                                            <th class="col-md-1"><?= lang("quantity"); ?></th>

                                            <?php

                                            if ($Settings->product_discount && ($Owner || $Admin || $this->session->userdata('allow_discount') || $inv->product_discount)) {

                                                echo '<th class="col-md-1">' . lang("discount") . '</th>';

                                            }

                                            ?>

                                            <?php

                                            if ($Settings->tax1) {

                                                echo '<th class="col-md-1">' . lang("product_tax") . '</th>';

                                            }

                                            ?>

                                            <th><?= lang("subtotal"); ?> (<span

                                                    class="currency"><?= $default_currency->code ?></span>)

                                            </th>

                                            <th style="width: 30px !important; text-align: center;"><i

                                                    class="fa fa-trash-o"

                                                    style="opacity:0.5; filter:alpha(opacity=50);"></i></th>

                                        </tr>

                                        </thead>

                                        <tbody></tbody>

                                        <tfoot></tfoot>

                                    </table>

                                </div>

                            </div>

                        </div><?php */?>



                       <?php /*?> <?php if ($Settings->tax2) { ?>

                            <div class="col-md-4">

                                <div class="form-group">

                                    <?= lang("order_tax", "sltax2"); ?>

                                    <?php

                                    $tr[""] = "";

                                    foreach ($tax_rates as $tax) {

                                        $tr[$tax->id] = $tax->name;

                                    }

                                    echo form_dropdown('order_tax', $tr, (isset($_POST['order_tax']) ? $_POST['order_tax'] : $Settings->default_tax_rate2), 'id="sltax2" data-placeholder="' . lang("select") . ' ' . lang("order_tax") . '" class="form-control input-tip select" style="width:100%;"');

                                    ?>

                                </div>

                            </div>

                        <?php } ?>



                        <?php if (($Owner || $Admin || $this->session->userdata('allow_discount')) || $inv->order_discount_id) { ?>

                        <div class="col-md-4">

                            <div class="form-group">

                                <?= lang("order_discount", "sldiscount"); ?>

                                <?php echo form_input('order_discount', '', 'class="form-control input-tip" id="sldiscount" '.(($Owner || $Admin || $this->session->userdata('allow_discount')) ? '' : 'readonly="true"')); ?>

                            </div>

                        </div>

                        <?php } ?><?php */?>



                        <?php /*?><div class="col-md-4">

                            <div class="form-group">

                                <?= lang("shipping", "slshipping"); ?>

                                <?php echo form_input('shipping', '', 'class="form-control input-tip" id="slshipping"'); ?>



                            </div>

                        </div>
                        <div class="col-md-4">

                            <div class="form-group">

                                <?= lang("document", "document") ?>

                                <input id="document" type="file" data-browse-label="<?= lang('browse'); ?>" name="document" data-show-upload="false"

                                       data-show-preview="false" class="form-control file">

                            </div>

                        </div>
                        <div class="col-sm-4">

                            <div class="form-group">

                                <?= lang("sale_status", "slsale_status"); ?>

                                <?php $sst = array('pending' => lang('pending'), 'completed' => lang('completed'));

                                echo form_dropdown('sale_status', $sst, '', 'class="form-control input-tip" required="required" id="slsale_status"');

                                ?>



                            </div>

                        </div>
                        <div class="col-sm-4">

                            <div class="form-group">

                                <?= lang("payment_term", "slpayment_term"); ?>

                                <?php echo form_input('payment_term', '', 'class="form-control tip" data-trigger="focus" data-placement="top" title="' . lang('payment_term_tip') . '" id="slpayment_term"'); ?>



                            </div>

                        </div>
                        <?= form_hidden('payment_status', $inv->payment_status); ?>
                        <div class="clearfix"></div>
                        <input type="hidden" name="total_items" value="" id="total_items" required="required"/>
                        <div class="row" id="bt">

                            <div class="col-md-12">

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <?= lang("sale_note", "slnote"); ?>

                                        <?php echo form_textarea('note', (isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="slnote" style="margin-top: 10px; height: 100px;"'); ?>



                                    </div>

                                </div>

                                <div class="col-md-6">

                                    <div class="form-group">

                                        <?= lang("staff_note", "slinnote"); ?>

                                        <?php echo form_textarea('staff_note', (isset($_POST['staff_note']) ? $_POST['staff_note'] : ""), 'class="form-control" id="slinnote" style="margin-top: 10px; height: 100px;"'); ?>



                                    </div>

                                </div>





                            </div>



                        </div>
                        <div class="col-md-12">

                            <div

                                class="fprom-group"><?php echo form_submit('edit_sale', lang("submit"), 'id="edit_sale" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>

                                <button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button>

                            </div>

                        </div><?php */?>
                        
                        
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
                    <?php if(!empty($detail)){ 
							foreach($detail as $row){?>
                        		<tr class="first_row">
                        <td style="width:180px;">
                        <?php 
							$input_description = array(
														'type'=>'text',
														'name'=>'description[]',
														'style'=>'padding:7px; width:100%;',
														'class'=>'message',
														'required'=>'required',
														'data-increament'=>$i,
														'value'=>$row->product_description
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
														'data-increament'=>$i,
														'value'=>$row->hour
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
														'data-increament'=>$i,
														'value'=>$row->amount
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
														'readonly' => 'readonly',
														'value'=>$row->price
													);
							echo form_input($input_total_amount);
						?>
                        <?php /*?><input type="text" name="price[]" style="padding:7px; width:176px;" class="message3 price_class" data-increment="<?php echo $i?>" readonly="readonly"/><?php */?>
                        </td>
                        <td style="width:150px; text-align:center;"><i class="fa fa-plus-circle addIcon fa-2x tip pointer sldel" id="addIcon" title="Add New" onclick="AddRow()"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-minus-circle tip pointer sldel fa-2x" id="minusIcon" title="Remove"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clone tip pointer sldel fa-x copy_toclip" aria-hidden="true" title="copy" style="font-size:21px;" id="drafted"></i>&nbsp;&nbsp;&nbsp;<i class="fa fa-clipboard tip pointer sldel msg" aria-hidden="true" title="Paste" style="font-size:21px;"></i></td>
                        <!--<td><i id="1511866082877" class="fa fa-times tip pointer sldel" title="Remove" style="cursor:pointer;" onclick="delete_row()"></i></td>--></tr>    
                    <?php 	$i++;}
							}else{?>
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
                      <?php } ?>
                      
                      <tr class="total_row total_amount" <?php if($inv->currency=='INR'){ ?>style="display: none;"<?php } ?> >
                        <td colspan="3" style="width:180px; text-align:right; font-weight: bold;"><?= lang("total_amount"); ?></td>
                        <td style="width:180px; text-align:center; font-weight: bold;" colspan="2" class="total_price"><?=($inv->total_amount!='')?$inv->total_amount:'0.00'?></td>
                        <?php 
							$total_amount = array(
														'type'=>'hidden',
														'id'=>'total_amount',
														'name' => 'total_amount',
														'value'=>($inv->total_amount!='')?$inv->total_amount:''
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
                <table id="gst_calculate" align="right" <?php if($inv->currency=='INR'){echo 'style="display: block;"';}else{echo 'style="display: none;"';} ?> class="items table-striped table-bordered table-condensed table-hover sortable_table">
                  <thead>
                  </thead>
                  <tbody>
                    <tr id="first_radio">
                      <td>
                      <?php 
					  		if($inv->igst_percentage!=0 && $inv->igst_amount!=0){
								$radio_checked_igst = TRUE;
							}else{
								$radio_checked_igst = FALSE;
							}
							$input_gst_radio = array(
														'name'=>'gst_radio',
														'id'=>'igst_id',
														'checked'=>$radio_checked_igst
													);
							echo form_radio($input_gst_radio);
						?>
                      <?php /*?><input type="radio" name="gst_radio" id="igst_id" /><?php */?>
                        &nbsp;&nbsp;&nbsp;<b>IGST@
                        <?php 
							$input_igst_percentage = array(
														'type'=>'text',
														'name'=>'igst_percentage',
														'style'=>'width: 40px;',
														'id'=>'igst_percentage',
														'value'=>($inv->igst_percentage!=0)?$inv->igst_percentage:''
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
														'id'=>'textpart',
														'value'=>($inv->igst_amount!=0)?$inv->igst_amount:''
													);
							echo form_input($input_igst_text);
						?>
                      <?php /*?><input type="text" name="igst_text" id="textpart"/><?php */?>
                      </td>
                    </tr>
                    <tr id="second_radio">
                      <td>
                      <?php 
					  		if(($inv->sgst_percentage!=0 && $inv->sgst_amount!=0)||($inv->cgst_percentage!=0 && $inv->cgst_amount!=0)){
								$radio_checked = TRUE;
							}else{
								$radio_checked = FALSE;
							}
							$input_gst_radio = array(
														'name'=>'gst_radio',
														'id'=>'sgst_id',
														'checked'=>$radio_checked
													);
							echo form_radio($input_gst_radio);
						?>
                     <?php /*?> <input type="radio" name="gst_radio" id="sgst_id" /><?php */?>
                        &nbsp;&nbsp;&nbsp;<b>SGST@
                         <?php 
							$input_sgst_percentage = array(
														'type'=>'text',
														'name'=>'sgst_percentage',
														'id'=>'sgst_percentage',
														'style'=>'width: 40px;',
														'value'=>($inv->sgst_percentage!=0)?$inv->sgst_percentage:''
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
														'value'=>($inv->sgst_amount!=0)?$inv->sgst_amount:''
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
														'value'=>($inv->cgst_percentage!=0)?$inv->cgst_percentage:''
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
														'value'=>($inv->cgst_amount!=0)?$inv->cgst_amount:''
													);
							echo form_input($input_cgst_text);
						?>
                      <?php /*?><input type="text" name="cgst_text" id="cgst_text" value=""/><?php */?></td>
                    </tr>
                    <tr>
                      <td><b>
                        <?= lang("total_amount_gst"); ?>
                        </b></td>
                      <td style="text-align:center; font-weight: bold;" class="total_amount_gst"><?=($inv->currency=='INR' && $inv->total_amount!=0)?$inv->total_amount:'0.00'?></td>
                      <?php 
							$input_total_gst_amount = array(
														'type'=>'hidden',
														'name'=>'total_gst_amount',
														'id'=>'total_amount_gst',
														'value'=>($inv->currency=='INR' && $inv->total_amount!=0)?$inv->total_amount:''
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
              <div class="fprom-group"><?php echo form_submit('update_invoice', lang("update"), 'id="update_invoice" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                <?php /*?><button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button><?php */?>
              </div>
            </div>
                    </div>

                </div>

                <?php /*?><div id="bottom-total" class="well well-sm" style="margin-bottom: 0;">

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

                </div><?php */?>



                <?php echo form_close(); ?>



            </div>



        </div>

    </div>

</div>

    <?php /*?><div class="modal" id="prModal" tabindex="-1" role="dialog" aria-labelledby="prModalLabel" aria-hidden="true">
    
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
    
    </div><?php */?>
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


	$(document).on('keyup','#commision_fee_percentage',function(){
		var total_price = 0;
		if($('#total_amount_gst').val()!='' && $('#total_amount_gst').val()!=0){
			total_price = $('#total_amount_gst').val();
		} else if($('#total_amount').val()!='' && $('#total_amount').val()!=0){
			total_price = $('#total_amount').val(); 
		}
		
		var commision_fee = 0;
		var commision_fee_percentage = 0;
		if($(this).val()!='' && $(this).val()!=0){
			commision_fee_percentage = $(this).val();
			if(total_price!='' && total_price!=0){
				commision_fee = (total_price*commision_fee_percentage)/100;
			}
		}
		$('#commision_fee').val(commision_fee);
	})
	
	var total_price = 0;
	$(document).on('keyup', ".txt",function () { 
	$('#commision_fee').val(''); 
	$('#discount_amount').val('');
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
	$('#commision_fee').val(''); 
	$('#discount_amount').val('');
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
	
			var  total_price = 0;
			$(".price_class").each(function() {
			  if (!$(this).closest('td').parent().children(':first-child').next().next().next().children().val() == '') {
				total_price = total_price + parseFloat($(this).closest('td').parent().children(':first-child').next().next().next().children().val());
			  }
			});
			$(".total_price").html(total_price);
			$("#total_amount").val(total_price);
	
	$('#update_invoice').click(function(){
		if($('#sldate').val()==''){
			    $( "#date-error" ).text("Please select a date to cotinue.").show();
    			$('.date-con').show();			
				return;
		}
		if($('#slcustomer').val()==''){
			    $( "#customer-error" ).text("Please select a customer to cotinue.").show();
    			$('.customer-con').show();			
				return;
		}
	})
	
	$('#slcurrency').on('change', function(){
		if($(this).val()!='' && $(this).val()=='INR'){
			$('#commision_fee').val(''); 
			$('#discount_amount').val('');
			$('#gst_calculate').show();
			$('.total_amount').hide();
		}
		else{
			var  total_price = 0;
			$(".price_class").each(function() {
			  if (!$(this).closest('td').parent().children(':first-child').next().next().next().children().val() == '') {
				total_price = total_price + parseFloat($(this).closest('td').parent().children(':first-child').next().next().next().children().val());
			  }
			});
			$(".total_price").html(total_price);
			$("#total_amount").val(total_price);
			$('#commision_fee').val(''); 
			$('#discount_amount').val('');
			$('#total_amount_gst').val('');
			$('.total_amount_gst').text('0.00');
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
	function nsCustomer() {
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
}
    $(document).ready(function () {
        /*$('#gccustomer').select2({
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
		
		var $customer = $('#slcustomer');
    $customer.change(function (e) {
        localStorage.setItem('slcustomer', $(this).val());
        //$('#slcustomer_id').val($(this).val());
    });
    if (slcustomer = localStorage.getItem('slcustomer')) {
        $customer.val(slcustomer).select2({
            minimumInputLength: 1,
            data: [],
            initSelection: function (element, callback) {
                $.ajax({
                    type: "get", async: false,
                    url: site.base_url+"customers/getCustomer/" + $(element).val(),
                    dataType: "json",
                    success: function (data) {
                        callback(data[0]);
                    }
                });
            },
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
    }
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