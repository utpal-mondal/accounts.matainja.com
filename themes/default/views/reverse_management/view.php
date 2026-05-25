<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">

    $(document).ready(function () {

        $(document).on('click', '.sledit', function (e) {

            if (localStorage.getItem('slitems')) {

                e.preventDefault();

                var href = $(this).attr('href');

                bootbox.confirm("you will loss Invoice data", function (result) {

                    if (result) {

                        window.location.href = href;

                    }

                });

            }

        });

    });

</script>

<div class="box">

    <div class="box-header">

        <h2 class="blue"><i class="fa-fw fa fa-file"></i>Invoice Number . <?=$inv->id; ?></h2>



        <div class="box-icon">

            <ul class="btn-tasks">

                <li class="dropdown">

                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>">

                        </i>

                    </a>

                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">

                        <?php if ($inv->attachment) { ?>

                            <li>

                                <a href="<?= site_url('welcome/download/' . $inv->attachment) ?>">

                                    <i class="fa fa-chain"></i> <?= lang('attachment') ?>

                                </a>

                            </li>

                        <?php } ?>

                        <li>

                            <a href="<?= site_url('sales/edit/' . $inv->id) ?>" class="sledit">

                                <i class="fa fa-edit"></i> <?= lang('edit_sale') ?>

                            </a>

                        </li>

                        <li>

                            <a href="<?= site_url('sales/payments/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">

                                <i class="fa fa-money"></i> <?= lang('view_payments') ?>

                            </a>

                        </li>

                        <li>

                            <a href="<?= site_url('sales/add_payment/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">

                                <i class="fa fa-dollar"></i> <?= lang('add_payment') ?>

                            </a>

                        </li>

                        <li>

                            <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">

                                <i class="fa fa-envelope-o"></i> <?= lang('send_email') ?>

                            </a>

                        </li>

                        <li>

                            <a href="<?= site_url('invoice_management/pdf/' . $inv->id) ?>">

                                <i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>

                            </a>

                        </li>

                        <?php if ( ! $inv->sale_id) { ?>

                        <li>

                            <a href="<?= site_url('sales/add_delivery/' . $inv->id) ?>" data-target="#myModal" data-toggle="modal">

                                <i class="fa fa-truck"></i> <?= lang('add_delivery') ?>

                            </a>

                        </li>

                        <li>

                            <a href="<?= site_url('sales/return_sale/' . $inv->id) ?>">

                                <i class="fa fa-angle-double-left"></i> <?= lang('return_sale') ?>

                            </a>

                        </li>

                        <?php } ?>

                    </ul>

                </li>

            </ul>

        </div>

    </div>

    <div class="box-content">

        <div class="row">

            <div class="col-lg-12">

                <?php if (!empty($inv->return_sale_ref) && $inv->return_id) {

                    echo '<div class="alert alert-info no-print"><p>'.lang("sale_is_returned").': '.$inv->return_sale_ref;

                    echo ' <a data-target="#myModal2" data-toggle="modal" href="'.site_url('sales/modal_view/'.$inv->return_id).'"><i class="fa fa-external-link no-print"></i></a><br>';

                    echo '</p></div>';

                } ?>

                <div class="print-only col-xs-12">

                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>" alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">

                </div>

                <div class="well well-sm">

                    <div class="col-xs-6 border-right">



                        <div class="col-xs-2"><i class="fa fa-3x fa-building padding010 text-muted"></i></div>

                        <div class="col-xs-10">

                            <h2 class=""><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>

                            <?= $biller->company ? "" : "Attn: " . $biller->name ?>



                            <?php

                            echo $biller->address . "<br>" . $biller->city . " " . $biller->postal_code . " " . $biller->state . "<br>" . $biller->country;



                            echo "<p>";



                            if ($biller->vat_no != "-" && $biller->vat_no != "") {

                                echo "<br>" . lang("vat_no") . ": " . $biller->vat_no;

                            }

                            if ($biller->cf1 != "-" && $biller->cf1 != "") {

                                echo "<br>" . lang("bcf1") . ": " . $biller->cf1;

                            }

                            if ($biller->cf2 != "-" && $biller->cf2 != "") {

                                echo "<br>" . lang("bcf2") . ": " . $biller->cf2;

                            }

                            if ($biller->cf3 != "-" && $biller->cf3 != "") {

                                echo "<br>" . lang("bcf3") . ": " . $biller->cf3;

                            }

                            if ($biller->cf4 != "-" && $biller->cf4 != "") {

                                echo "<br>" . lang("bcf4") . ": " . $biller->cf4;

                            }

                            if ($biller->cf5 != "-" && $biller->cf5 != "") {

                                echo "<br>" . lang("bcf5") . ": " . $biller->cf5;

                            }

                            if ($biller->cf6 != "-" && $biller->cf6 != "") {

                                echo "<br>" . lang("bcf6") . ": " . $biller->cf6;

                            }



                            echo "</p>";

                            echo lang("tel") . ": " . $biller->phone . "<br>" . lang("email") . ": " . $biller->email;

                            ?>

                        </div>

                        <div class="clearfix"></div>



                    </div>

                    <div class="col-xs-6 border-right">



                        <div class="col-xs-2"><i class="fa fa-3x fa-user padding010 text-muted"></i></div>

                        <div class="col-xs-10">

                            <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>

                            <?= $customer->company ? "" : "Attn: " . $customer->name ?>



                            <?php

                            echo $customer->address . "<br>" . $customer->city . " " . $customer->postal_code . " " . $customer->state . "<br>" . $customer->country;



                            echo "<p>";



                            if ($customer->vat_no != "-" && $customer->vat_no != "") {

                                echo "<br>" . lang("vat_no") . ": " . $customer->vat_no;

                            }

                            if ($customer->cf1 != "-" && $customer->cf1 != "") {

                                echo "<br>" . lang("ccf1") . ": " . $customer->cf1;

                            }

                            if ($customer->cf2 != "-" && $customer->cf2 != "") {

                                echo "<br>" . lang("ccf2") . ": " . $customer->cf2;

                            }

                            if ($customer->cf3 != "-" && $customer->cf3 != "") {

                                echo "<br>" . lang("ccf3") . ": " . $customer->cf3;

                            }

                            if ($customer->cf4 != "-" && $customer->cf4 != "") {

                                echo "<br>" . lang("ccf4") . ": " . $customer->cf4;

                            }

                            if ($customer->cf5 != "-" && $customer->cf5 != "") {

                                echo "<br>" . lang("ccf5") . ": " . $customer->cf5;

                            }

                            if ($customer->cf6 != "-" && $customer->cf6 != "") {

                                echo "<br>" . lang("ccf6") . ": " . $customer->cf6;

                            }



                            echo "</p>";

                            echo lang("tel") . ": " . $customer->phone . "<br>" . lang("email") . ": " . $customer->email;

                            ?>

                        </div>

                        <div class="clearfix"></div>



                    </div>

                    <?php /*?><div class="col-xs-4">

                        <div class="col-xs-2"><i class="fa fa-3x fa-building-o padding010 text-muted"></i></div>

                        <div class="col-xs-10">

                            <h2 class=""><?= $Settings->site_name; ?></h2>

                            <?= $warehouse->name ?>



                            <?php

                            echo $warehouse->address . "<br>";

                            echo ($warehouse->phone ? lang("tel") . ": " . $warehouse->phone . "<br>" : '') . ($warehouse->email ? lang("email") . ": " . $warehouse->email : '');

                            ?>

                        </div>

                        <div class="clearfix"></div>

                    </div><?php */?>

                    <div class="clearfix"></div>

                </div>

                <div class="clearfix"></div>

                <?php if ($Settings->invoice_view == 1) { ?>

                    <div class="col-xs-12 text-center">

                        <h1><?= lang('tax_invoice'); ?></h1>

                    </div>

                <?php } ?>

                <div class="clearfix"></div>

                <?php /*?><div class="col-xs-7 pull-right">

                    <div class="col-xs-12 text-right order_barcodes">

                        <?= $this->sma->save_barcode($inv->reference_no, 'code128', 66, false); ?>

                        <?= $this->sma->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); ?>

                    </div>

                    <div class="clearfix"></div>

                </div><?php */?>



                <div class="col-xs-5">

                    <div class="col-xs-2"><i class="fa fa-3x fa-file-text-o padding010 text-muted"></i></div>

                    <div class="col-xs-10">

                        <h2 class=""><?= lang("ref"); ?>: <?= $inv->reference_no; ?></h2>

                        <?php if (!empty($inv->return_sale_ref)) {

                            echo '<p>'.lang("return_ref").': '.$inv->return_sale_ref;

                            if ($inv->return_id) {

                                echo ' <a data-target="#myModal2" data-toggle="modal" href="'.site_url('sales/modal_view/'.$inv->return_id).'"><i class="fa fa-external-link no-print"></i></a><br>';

                            } else {

                                echo '</p>';


                            }

                        } ?>



                        <p style="font-weight:bold;"><?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?></p>



                        <?php /*?><p style="font-weight:bold;"><?= lang("sale_status"); ?>: <?= lang($inv->sale_status); ?></p><?php */?>



                       <?php /*?> <p style="font-weight:bold;"><?= lang("payment_status"); ?>

                            : <?= lang($inv->payment_status); ?></p><?php */?>



                        <p>&nbsp;</p>

                    </div>

                    <div class="clearfix"></div>

                </div>



                <div class="clearfix"></div>



                <div class="table-responsive">

                    <table class="table table-bordered table-hover table-striped print-table order-table">



                        <thead>



                        <tr>

                            <th><?= lang("no"); ?></th>

                            <th><?= lang("description"); ?> </th>
                            
                             <th>Hour</th>

                            <th><?= lang("quantity"); ?></th>

                            <th style="padding-right:20px;">Price</th>


                        </tr>



                        </thead>
						<?php $i=1;//print_r($detail);die();
						if(isset($detail)){?>
							<?php /*?><?php foreach($detail as $details ){?><?php */?>

                        <tbody>
						<th><?php echo($i);?></th>
                        <th><?php echo $detail->description;?></th>
                        <th><?php echo($detail->hour);?></th>
                        <th><?php echo($detail->quantity);?></th>
                        <th style="text-align:right; padding-right:10px; font-weight:bold;"><?php echo($detail->price);?>.00</th>
                            
                        </tbody>
						<?php $i++;}?>
                        <?php $gst=($detail->price)*18/100;?>
                        <tfoot>
                    <tr>

                            <td colspan="4"

                                style="text-align:right; font-weight:bold;">GST@18%

                                (<?= $default_currency->code; ?>)

                            </td>

                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?php echo $gst; ?></td>

                    </tr>

         <tr>

                            <td colspan="4"

                                style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>

                                (<?= $default_currency->code; ?>)

                            </td>

                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?php echo $detail->total_price; ?>.00</td>

                        </tr>


                        </tfoot>

                    </table>

                </div>



                <div class="row">

                    <div class="col-xs-6">


                    </div>



                    <div class="col-xs-6">

                        <?php

                        if ($Settings->invoice_view == 1) {

                            if (!empty($tax_summary)) {

                                echo '<h3 class="bold">' . lang('tax_summary') . '</h3>';

                                echo '<table class="table table-bordered table-condensed"><thead><tr><th>' . lang('name') . '</th><th>' . lang('code') . '</th><th>' . lang('qty') . '</th><th>' . lang('tax_excl') . '</th><th>' . lang('tax_amt') . '</th></tr></td><tbody>';

                                foreach ($tax_summary as $summary) {

                                    echo '<tr><td>' . $summary['name'] . '</td><td class="text-center">' . $summary['code'] . '</td><td class="text-center">' . $this->sma->formatQuantity($summary['items']) . '</td><td class="text-right">' . $this->sma->formatMoney($summary['amt']) . '</td><td class="text-right">' . $this->sma->formatMoney($summary['tax']) . '</td></tr>';

                                }

                                echo '</tbody></tfoot>';

                                echo '<tr><th colspan="4" class="text-right">' . lang('total_tax_amount') . '</th><th class="text-right">' . $this->sma->formatMoney($return_sale ? $inv->product_tax+$return_sale->product_tax : $inv->product_tax) . '</th></tr>';

                                echo '</tfoot></table>';

                            }

                        }

                        ?>

                        <!--<div class="well well-sm">

                            <p><?= lang("created_by"); ?>

                            

                        </div>-->

                    </div>

                </div>



               

                    <div id="payment_buttons" class="row text-center padding10 no-print">



                        <?php 

                            if (trim(strtolower($customer->country)) == $biller->country) {

                               // $paypal_fee = $paypal->fixed_charges + ($inv->grand_total * $paypal->extra_charges_my / 100);

                            } else {

                               // $paypal_fee = $paypal->fixed_charges + ($inv->grand_total * $paypal->extra_charges_other / 100);

                            }

                            ?>

                            <div class="col-xs-6 text-center">

                                <form action="https://www.paypal.com/cgi-bin/webscr" method="post">

                                    <input type="hidden" name="cmd" value="_xclick">


                                    <input type="hidden" name="item_name" value="<?= $inv->reference_no; ?>">

                                    <input type="hidden" name="item_number" value="<?= $inv->id; ?>">

                                    <input type="hidden" name="image_url"

                                           value="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>">

                                    <input type="hidden" name="amount"


                                    <input type="hidden" name="no_shipping" value="1">

                                    <input type="hidden" name="no_note" value="1">

                                    <input type="hidden" name="currency_code" value="<?= $default_currency->code; ?>">

                                    <input type="hidden" name="bn" value="FC-BuyNow">

                                    <input type="hidden" name="rm" value="2">

                                    <input type="hidden" name="return"

                                           value="<?= site_url('sales/view/' . $inv->id); ?>">

                                    <input type="hidden" name="cancel_return"

                                           value="<?= site_url('sales/view/' . $inv->id); ?>">

                                    <input type="hidden" name="notify_url"

                                           value="<?= site_url('payments/paypalipn'); ?>"/>

                                    <input type="hidden" name="custom"


                                    <?php /*?><button type="submit" name="submit" class="btn btn-primary btn-lg btn-block"><i

                                            class="fa fa-money"></i> <?= lang('pay_by_paypal') ?></button><?php */?>

                                </form>

                            </div>

                        





                        <?php 

                            if (trim(strtolower($customer->country)) == $biller->country) {

                               // $skrill_fee = $skrill->fixed_charges + ($inv->grand_total * $skrill->extra_charges_my / 100);

                            } else {

                                //$skrill_fee = $skrill->fixed_charges + ($inv->grand_total * $skrill->extra_charges_other / 100);

                            }

                            ?>

                            <div class="col-xs-6 text-center">

                                <form action="https://www.moneybookers.com/app/payment.pl" method="post">


                                    <input type="hidden" name="status_url"

                                           value="<?= site_url('payments/skrillipn'); ?>">

                                    <input type="hidden" name="cancel_url"

                                           value="<?= site_url('sales/view/' . $inv->id); ?>">

                                    <input type="hidden" name="return_url"

                                           value="<?= site_url('sales/view/' . $inv->id); ?>">

                                    <input type="hidden" name="language" value="EN">

                                    <input type="hidden" name="ondemand_note" value="<?= $inv->reference_no; ?>">

                                    <input type="hidden" name="merchant_fields" value="item_name,item_number">

                                    <input type="hidden" name="item_name" value="<?= $inv->reference_no; ?>">

                                    <input type="hidden" name="item_number" value="<?= $inv->id; ?>">

                                    <input type="hidden" name="amount"


                                    <input type="hidden" name="currency" value="<?= $default_currency->code; ?>">

                                    <input type="hidden" name="detail1_description" value="<?= $inv->reference_no; ?>">

                                    <input type="hidden" name="detail1_text"


                                    <input type="hidden" name="logo_url"

                                           value="<?= base_url() . 'assets/uploads/logos/' . $Settings->logo; ?>">

                                    <?php /*?><button type="submit" name="submit" class="btn btn-primary btn-lg btn-block"><i

                                            class="fa fa-money"></i> <?= lang('pay_by_skrill') ?></button><?php */?>

                                </form>

                            </div>

                        

                        <div class="clearfix"></div>

                    </div>

                

               

                    <div class="row">

                        <div class="col-xs-12">

                            <div class="table-responsive">

                                <!--<table class="table table-bordered table-striped table-condensed print-table">

                                    <thead>

                                    <tr>

                                        <th><?= lang('date') ?></th>

                                        <th><?= lang('payment_reference') ?></th>

                                        <th><?= lang('paid_by') ?></th>

                                        <th><?= lang('amount') ?></th>

                                        <th><?= lang('created_by') ?></th>

                                        <th><?= lang('type') ?></th>

                                    </tr>

                                    </thead>

                                    <tbody>

                                    

                                          

                                    </tbody>

                                </table>-->

                            </div>

                        </div>

                    </div>

               

            </div>

        </div>

        <?php if (!$Supplier || !$Customer) { ?>

            <div class="buttons">

                <div class="btn-group btn-group-justified">

                    
                    

                    <div class="btn-group">

                        <a href="<?= site_url('sales/email/' . $inv->id) ?>" data-toggle="modal" data-target="#myModal" class="tip btn btn-primary tip" title="<?= lang('email') ?>">

                            <i class="fa fa-envelope-o"></i> <span class="hidden-sm hidden-xs"><?= lang('email') ?></span>

                        </a>

                    </div>

                    <div class="btn-group">

                        <a href="<?= site_url('invoice_management/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">

                            <i class="fa fa-download"></i> <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>

                        </a>

                    </div>


                    <div class="btn-group">

                        <a href="<?= site_url('sales/edit/' . $inv->id) ?>" class="tip btn btn-warning tip sledit" title="<?= lang('edit') ?>">

                            <i class="fa fa-edit"></i> <span class="hidden-sm hidden-xs"><?= lang('edit') ?></span>

                        </a>

                    </div>

                    <div class="btn-group">

                        <a href="#" class="tip btn btn-danger bpo"

                            title="<b><?= $this->lang->line("delete_sale") ?></b>"

                            data-content="<div style='width:150px;'><p><?= lang('r_u_sure') ?></p><a class='btn btn-danger' href='<?= site_url('sales/delete/' . $inv->id) ?>'><?= lang('i_m_sure') ?></a> <button class='btn bpo-close'><?= lang('no') ?></button></div>"

                            data-html="true" data-placement="top"><i class="fa fa-trash-o"></i> 

                            <span class="hidden-sm hidden-xs"><?= lang('delete') ?></span>

                        </a>

                    </div>


                    <!--<div class="btn-group"><a href="<?= site_url('sales/excel/' . $inv->id) ?>" class="tip btn btn-primary"  title="<?= lang('download_excel') ?>"><i class="fa fa-download"></i> <?= lang('excel') ?></a></div>-->

                </div>

            </div>

        <?php } ?>

    </div>

</div>

