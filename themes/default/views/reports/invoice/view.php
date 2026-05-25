<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<script type="text/javascript">

    $(document).ready(function () {

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

<div class="box">

    <div class="box-header">

        <h2 class="blue"><i class="fa-fw fa fa-file"></i><?= lang("sale_no") . ' ' . $inv->id; ?></h2>



        <div class="box-icon">

            <ul class="btn-tasks">

                <li class="dropdown">

                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">

                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?= lang("actions") ?>">

                        </i>

                    </a>

                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">

                        <li>

                            <a href="<?= site_url('invoice_management/edit/' . $inv->id) ?>" class="sledit">

                                <i class="fa fa-edit"></i> <?= lang('edit_sale') ?>

                            </a>

                        </li>

                        <li>

                            <a href="<?= site_url('invoice_management/pdf/' . $inv->id) ?>">

                                <i class="fa fa-file-pdf-o"></i> <?= lang('export_to_pdf') ?>

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

                <div class="clearfix"></div>


                <div class="col-xs-5">

                    <div class="col-xs-2"><i class="fa fa-3x fa-file-text-o padding010 text-muted"></i></div>

                    <div class="col-xs-10">

                        <h2 class=""><?= lang("ref"); ?>: <?= $inv->reference_no; ?></h2>


                        <p style="font-weight:bold;"><?= lang("date"); ?>: <?= $this->sma->hrld($inv->date); ?></p>



                        <p style="font-weight:bold;"><?= lang("customer"); ?>: <?= $inv->customer; ?></p>
                        
                        <p style="font-weight:bold;"><?= lang("biller"); ?>: <?= $inv->biller; ?></p>
                        
                        <p style="font-weight:bold;"><?= lang("currency"); ?>: <?= $inv->currency; ?></p>



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
    
                                <th><?= lang("list_description"); ?></th>
    
                                <th><?= lang("hour"); ?></th>
                           
                                <th><?= lang("list_amount"); ?></th>
                            
                                <th><?= lang("total"); ?></th>
    
                            </tr>

                        </thead>



                        <tbody>



                        <?php $r = 1;

                        foreach ($detail as $row):


                            ?>

                            <tr>

                                <td style="text-align:center; width:40px; vertical-align:middle;"><?= $r; ?></td>

                                <td style="vertical-align:middle;">

                                    <?= $row->product_description; ?>
                                </td>
								<td style="width: 100px; text-align:center; vertical-align:middle;">
                                    <?= ($row->hour!=0)?$row->hour:1; ?> 
                                </td>

                                <td style="width: 100px; text-align:center; vertical-align:middle;"><?= $row->amount; ?></td>
                                <td style="text-align:right; width:120px; padding-right:10px;"><?= $row->price; ?></td>

                            </tr>

                            <?php

                            $r++;

                        endforeach;

                        ?>

                        </tbody>

                        <tfoot>

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

                                    style="text-align:right; padding-right:10px;">IGST@<?=$inv->sgst_percentage; ?>%

                                </td>

                                <td style="text-align:right; padding-right:10px;"><?=$inv->sgst_amount; ?></td>

                            </tr>

                        <?php } ?>
                        <?php if ($inv->cgst_percentage!=0 &&  $inv->cgst_amount!= 0) { ?>

                            <tr>

                                <td colspan="4"

                                    style="text-align:right; padding-right:10px;">IGST@<?=$inv->cgst_percentage; ?>%

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

                            <td colspan="4"

                                style="text-align:right; font-weight:bold;"><?= lang("total_amount"); ?>

                            </td>

                            <td style="text-align:right; padding-right:10px; font-weight:bold;"><?= $inv->total_amount; ?></td>

                        </tr>


                        </tfoot>

                    </table>

                </div>



                <div class="row">


                </div>

            </div>

        </div>
        
       <?php if (!$Supplier || !$Customer) { ?>
            <div class="buttons">
                <div class="btn-group btn-group-justified">
                    <div class="btn-group">
                        <a href="<?= site_url('invoice_management/add/') ?>" class="tip btn btn-primary tip" title="<?= lang('add_sale') ?>">
                            <i class="fa fa-file-text-o"></i> <span class="hidden-sm hidden-xs"><?= lang('add_sale') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('invoice_management/edit/' . $inv->id) ?>" class="tip btn btn-warning tip sledit" title="<?= lang('edit_sale') ?>">
                            <i class="fa fa-file-text-o"></i> <span class="hidden-sm hidden-xs"><?= lang('edit_sale') ?></span>
                        </a>
                    </div>
                    
                    <div class="btn-group">
                        <a href="<?= site_url('invoice_management/pdf/' . $inv->id) ?>" class="tip btn btn-primary" title="<?= lang('download_pdf') ?>">
                            <i class="fa fa-download"></i> <span class="hidden-sm hidden-xs"><?= lang('pdf') ?></span>
                        </a>
                    </div>
                    <div class="btn-group">
                        <a href="<?= site_url('invoice_management/delete/' . $inv->id) ?>" class="tip btn btn-danger bpo" title="<?= lang('delete_sale') ?>">
                            <i class="fa fa-trash-o"></i> <span class="hidden-sm hidden-xs"><?= lang('delete_sale') ?></span>
                        </a>
                    </div>
                </div>
            </div>
        <?php } ?>


    </div>

</div>

