<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>

<html>

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo lang('invoice') . ' ' . $inv->reference_no; ?></title>

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
			border-color: #000 !important;
            padding: 5px;

        }

        .table td {

            padding: 4px;
			border-color: #000 !important;

        }
		.order-table-last{
			height: 250px;
		}
		
		.table{
			border-color: #000 !important;
		}
		
    </style>

</head>



<body>

    <div class="row">
    	<div style="width:35%;float:left;text-align:left;margin-right:10px">
        <img width="80px" src="<?=base_url()?>/assets/images/matainja.jpg">
		</div>
        <div style="width:65%;float:left;padding-top:2px;">
         <h2>MATAINJA TECHNOLOGIES</h2>
        </div>
        <div class="col-lg-12">


                <div class="text-center" style="text-decoration:underline;">
					<p><?=lang("tax_invoice"); ?></p>
                </div>

                <div style="text-align:right;">

                    <div>

                        <?= lang('date'); ?>: <?= $this->sma->hrld($inv->date); ?><br>

                        <?= lang('ref'); ?>: <?= $inv->reference_no; ?><br>

                        <div class="clearfix"></div>

                    </div>

                    <div class="clearfix"></div>

                </div>

            <div class="clearfix"></div>

            <div class="row padding10" style="border: 1px solid;margin-top:20px;">

                <div class="col-xs-5" style="border-right: 1px solid;">

                    <h2 class=""><?= $biller->company != '-' ? $biller->company : $biller->name; ?></h2>

                    <?= $biller->company ? '' : 'Attn: ' . $biller->name; ?>

                    <?php

                        echo $biller->address . '<br />' . $biller->city . ' ' . $biller->postal_code . ' ' . $biller->state . '<br />' . $biller->country;

                        echo '<p>';

                        if ($biller->vat_no != "-" && $biller->vat_no != "") {

                            echo lang("vat_no") . ": " . $biller->vat_no;

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

                        echo lang('email') . ': ' . $biller->email;

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

                            echo lang("vat_no") . ": " . $customer->vat_no;

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

                        echo lang('email') . ': ' . $customer->email;

                    ?>

                </div>

            </div>

            <div class="clearfix"></div>

            <div class="clearfix"></div>

            <div class="table-responsive" style="margin-top:20px;">

                    <table class="table table-bordered table-hover table-striped print-table order-table" style="border: 1px solid #000;">



                        <thead>

                            <tr style="border: 1px solid #000;">
    
                                <th><?= lang("no"); ?></th>
    
                                <th><?= lang("list_description"); ?></th>
    
                                <th><?= lang("hour"); ?></th>
                           
                                <th><?= lang("list_amount"); ?>(<?=strtoupper($inv->currency)?>)</th>
                            
                                <th><?= lang("total"); ?>(<?=strtoupper($inv->currency)?>)</th>
    
                            </tr>

                        </thead>



                        <tbody>



                        <?php $r = 1;
							$total_sum = 0;

                        foreach ($detail as $row):


                            ?>

                            <tr style="border-bottom: 0;border-top: 0; border-color:#000;">

                                <td class="order-table-cell" style="border-bottom: 0;border-top: 0;text-align:left; width:40px; vertical-align:middle;border-color:#000;"><?= $r; ?></td>

                                <td class="order-table-cell" style="border-color:#000;border-bottom: 0;border-top: 0;vertical-align:middle;">

                                    <?= $row->product_description; ?>
                                </td>
								<td class="order-table-cell" style="border-color:#000;border-bottom: 0;border-top: 0;width: 100px; text-align:right; vertical-align:middle;">
                                    <?= ($row->hour!=0)?$row->hour:1; ?> 
                                </td>

                                <td class="order-table-cell" style="border-color:#000;border-bottom: 0;border-top: 0;width: 100px; text-align:right; vertical-align:middle;"><?= $row->amount; ?></td>
                                <td class="order-table-cell" style="border-color:#000;border-bottom: 0;border-top: 0;text-align:right; width:120px; padding-right:10px;"><?= $row->price; ?></td>

                            </tr>

                            <?php
							$total_sum = $total_sum+$row->price;
                            $r++;

                        endforeach;

                        ?>
                        <tr style="border-color:#000;border-top: 0;"><td class="order-table-last" style="border-top: 0;"></td><td class="order-table-last" style="border-color:#000;border-top: 0;"></td><td class="order-table-last" style="border-top: 0;"></td><td class="order-table-last" style="border-color:#000;border-top: 0;"></td><td class="order-table-last" style="border-top: 0;"></td></tr>

                        </tbody>

                        <tfoot>
                        
                        <tr>
                        
                            <td colspan="4"
                            
                                style="border-color:#000;text-align:right; padding-right:10px;"><?= lang("total_amount"); ?>
                            
                            </td>
                            
                            <td style="border-color:#000;text-align:right; padding-right:10px;">(<?=strtoupper($inv->currency)?>) <?=$total_sum; ?></td>
                        
                        </tr>

                        <?php if ($inv->igst_percentage!=0 &&  $inv->igst_amount!= 0) { ?>

                            <tr>

                                <td colspan="4"

                                    style="text-align:right; padding-right:10px;">IGST@<?=$inv->igst_percentage; ?>%

                                </td>

                                <td style="text-align:right; padding-right:10px;"><?=$inv->igst_amount; ?></td>

                            </tr>

                        <?php } ?>
                        <?php if ($inv->sgst_percentage!=0 &&  $inv->sgst_amount!= 0) { ?>

                            <tr>

                                <td colspan="4"

                                    style="text-align:right; padding-right:10px;">SGST@<?=$inv->sgst_percentage; ?>%

                                </td>

                                <td style="text-align:right; padding-right:10px;"><?=$inv->sgst_amount; ?></td>

                            </tr>

                        <?php } ?>
                        <?php if ($inv->cgst_percentage!=0 &&  $inv->cgst_amount!= 0) { ?>

                            <tr>

                                <td colspan="4"

                                    style="text-align:right; padding-right:10px;">CGST@<?=$inv->cgst_percentage; ?>%

                                </td>

                                <td style="text-align:right; padding-right:10px;"><?=$inv->cgst_amount; ?></td>

                            </tr>

                        <?php } ?>
                        
                        <tr>

                            <td colspan="4"

                                style="text-align:right; font-weight:bold;"><?= lang("discount_amount"); ?>

                            </td>

                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $inv->discount; ?></td>

                        </tr>
                        
                        <tr>

                            <td colspan="2"><?=lang('amount_in_words')?>: <?=$amount_in_words?></td>

                            <td colspan="2"

                                style="text-align:right; font-weight:bold;"><?=lang('total')?>

                            </td>

                            <td style="text-align:right; padding-right:10px; font-weight:bold;">(<?=strtoupper($inv->currency)?>) <?= $inv->total_amount; ?></td>

                        </tr>
                            
                        </tfoot>

                    </table>
                    
                    <table class="table table-bordered table-hover table-striped print-table order-table" style="border: 1px solid #000;">
                    <tbody>
                    <tr>

                            <td  style="width:70%; text-align:left;"><?=lang('declaration');?>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus massa purus, non placerat enim blandit sed. Nulla lectus metus, cursus efficitur tortor ac, consectetur tristique risus. Nullam quis dictum arcu. Proin in facilisis est, vitae tempor est.
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas faucibus massa purus, non placerat enim blandit sed. Nulla lectus metus, cursus efficitur tortor ac, consectetur tristique risus. Nullam quis dictum arcu. Proin in facilisis est, vitae tempor est.
                                </td>

                             <td  style="width:30%;">
							 <table style="height:100%; padding:0px;">
                            
                            <tr style="border:none;padding:0px;"><td valign="top" align="left" style="border:none; padding:0px;"><p><?= lang('seller'); ?>

                        : <?= $biller->company != '-' ? $biller->company : $biller->name; ?> </p></td></tr>
					<tr style="border:none;"><td valign="top" align="left" style="border:none;"></td></tr>
                    <tr style="border:none;"><td valign="top" align="left" style="border:none;"></td></tr>
                    <tr style="border:none;"><td valign="top" align="left" style="border:none;"></td></tr>
					 <tr style="border:none;"><td valign="top" align="left" style="border:none;"></td></tr>
                      <tr style="border:none;"><td valign="top" align="left" style="border:none;"></td></tr>
                       <tr style="border:none;"><td valign="top" align="left" style="border:none;"></td></tr>
                    <tr style="border:none;padding:0px;"><td valign="bottom" align="right" style="border:none;padding:0px;"><p><?= lang('stamp_sign'); ?></p></td></tr>
                    </table>
                                </td>

                        </tr>
                    </tbody>
                    </table>

                </div>



            <?php /*?><div class="row">

                

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

            </div><?php */?>



        </div>

    </div>

</body>

</html>