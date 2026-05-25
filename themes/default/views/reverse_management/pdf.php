<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $this->lang->line('purchase') . ' ' . $inv->reference_no; ?></title>

    <link href="<?php echo $assets ?>styles/style.css" rel="stylesheet">

    <style type="text/css">

        html, body {

            height: 100%;

            background: #FFF;

        }

        body:before, body:after {

            display: none !important;

        }

        .table th {

            text-align: center;

            padding: 5px;

        }

        .table td {

            padding: 4px;

        }

    </style>

</head>



<body>

<div id="wrap">

    <div class="row">

        <div class="col-lg-12">

            <?php if ($logo) { ?>

                <div class="text-center" style="margin-bottom:20px;">

                    <h1>TAX INVOICE</h1>

                </div>

            <?php }

            ?>

            <div class="clearfix"></div>

            <div class="row padding10">

                <div class="col-xs-5">

                    <h2 class=""><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>

                    <?= $biller->company ? '' : 'Attn: ' . $biller->name; ?>

                    <?php

                        echo $biller->address . '<br />' . $biller->city . ' ' . $biller->postal_code . ' ' . $biller->state . '<br />' . $biller->country;

                        echo '<p>';

                        if ($biller->vat_no != "-" && $biller->vat_no != "") {

                            echo "<br>" . lang("vat_no") . ": " . $biller->vat_no;

                        }

                        if ($biller->cf1 != '-' && $biller->cf1 != '') {

                            echo '<br>' . lang('bcf1') . ': ' . $biller->cf1;

                        }

                        if ($biller->cf2 != '-' && $biller->cf2 != '') {

                            echo '<br>' . lang('bcf2') . ': ' . $biller->cf2;

                        }

                        if ($biller->cf3 != '-' && $biller->cf3 != '') {

                            echo '<br>' . lang('bcf3') . ': ' . $biller->cf3;

                        }

                        if ($biller->cf4 != '-' && $biller->cf4 != '') {

                            echo '<br>' . lang('bcf4') . ': ' . $biller->cf4;

                        }

                        if ($biller->cf5 != '-' && $biller->cf5 != '') {

                            echo '<br>' . lang('bcf5') . ': ' . $biller->cf5;

                        }

                        if ($biller->cf6 != '-' && $biller->cf6 != '') {

                            echo '<br>' . lang('bcf6') . ': ' . $biller->cf6;

                        }

                        echo '</p>';

                        echo lang('tel') . ': ' . $biller->phone . '<br />' . lang('email') . ': ' . $biller->email;

                    ?>

                    <div class="clearfix"></div>

                </div>

                <div class="col-xs-5">

                    <h2 class=""><?= $customer->company ? $customer->company : $customer->name; ?></h2>

                    <?= $customer->company ? '' : 'Attn: ' . $customer->name; ?>

                    <?php

                        echo $customer->address . '<br />' . $customer->city . ' ' . $customer->postal_code . ' ' . $customer->state . '<br />' . $customer->country;

                        echo '<p>';

                        if ($customer->vat_no != "-" && $customer->vat_no != "") {

                            echo "<br>" . lang("vat_no") . ": " . $customer->vat_no;

                        }

                        if ($customer->cf1 != '-' && $customer->cf1 != '') {

                            echo '<br>' . lang('ccf1') . ': ' . $customer->cf1;

                        }

                        if ($customer->cf2 != '-' && $customer->cf2 != '') {

                            echo '<br>' . lang('ccf2') . ': ' . $customer->cf2;

                        }

                        if ($customer->cf3 != '-' && $customer->cf3 != '') {

                            echo '<br>' . lang('ccf3') . ': ' . $customer->cf3;

                        }

                        if ($customer->cf4 != '-' && $customer->cf4 != '') {

                            echo '<br>' . lang('ccf4') . ': ' . $customer->cf4;

                        }

                        if ($customer->cf5 != '-' && $customer->cf5 != '') {

                            echo '<br>' . lang('ccf5') . ': ' . $customer->cf5;

                        }

                        if ($customer->cf6 != '-' && $customer->cf6 != '') {

                            echo '<br>' . lang('ccf6') . ': ' . $customer->cf6;

                        }

                        echo '</p>';

                        echo lang('tel') . ': ' . $customer->phone . '<br />' . lang('email') . ': ' . $customer->email;

                    ?>

                </div>

            </div>

            <div class="clearfix"></div>

            <div class="row padding10">

                <?php /*?><div class="col-xs-5">

                    <span class="bold"><?= $Settings->site_name; ?></span><br>

                    <?= $warehouse->name ?>



                    <?php

                        echo $warehouse->address . '<br>';

                        echo ($warehouse->phone ? lang('tel') . ': ' . $warehouse->phone . '<br>' : '') . ($warehouse->email ? lang('email') . ': ' . $warehouse->email : '');

                    ?>

                    <div class="clearfix"></div>

                </div><?php */?>

                <div class="col-xs-5">

                    <div class="bold">

                        <?= lang('date'); ?>: <?= $this->sma->hrld($detail->date); ?><br>

                        <?php /*?><?= lang('ref'); ?>: <?= $inv->reference_no; ?><br><?php */?>

                        <?php if (!empty($inv->return_sale_ref)) {

                            echo lang("return_ref").': '.$inv->return_sale_ref.'<br>';

                        } ?>

                        <div class="clearfix"></div>

                        <?php /*?><div class="order_barcodes">

                            <?= $this->sma->save_barcode($inv->reference_no, 'code128', 66, false); ?>

                            <?= $this->sma->qrcode('link', urlencode(site_url('sales/view/' . $inv->id)), 2); ?>

                        </div><?php */?>

                    </div>

                    <div class="clearfix"></div>

                </div>

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
                        <tr>
						<td><?php echo($i);?></td>
                        <td><?php echo $detail->description;?></td>
                        <td><?php echo($detail->hour);?></td>
                        <td><?php echo($detail->quantity);?></td>
                        <td style="text-align:right; padding-right:10px; font-weight:bold;"><?php echo($detail->price);?>.00</td>
                          </tr>  
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

                <div class="col-xs-12">

                    

                </div>

                <div class="clearfix"></div>

                <div class="col-xs-4  pull-left">

                    <p style="height: 80px;"><?= lang('seller'); ?>

                        : <?= $biller->company != '-' ? $biller->company : $biller->name; ?> </p>

                    <hr>

                    <p><?= lang('stamp_sign'); ?></p>

                </div>

                <div class="col-xs-4  pull-right">

                    <p style="height: 80px;"><?= lang('customer'); ?>

                        : <?= $customer->company ? $customer->company : $customer->name; ?> </p>

                    <hr>

                    <p><?= lang('stamp_sign'); ?></p>

                </div>

                <div class="clearfix"></div>

                <?php if ($customer->award_points != 0 && $Settings->each_spent > 0) { ?>

                <div class="col-xs-4 pull-right">

                    <div class="well well-sm">

                        <?=

                        '<p>'.lang('this_sale').': '.floor(($inv->grand_total/$Settings->each_spent)*$Settings->ca_point)

                        .'<br>'.

                        lang('total').' '.lang('award_points').': '. $customer->award_points . '</p>';?>

                    </div>

                </div>

                <?php } ?>

            </div>



        </div>

    </div>

</div>

</body>

</html>