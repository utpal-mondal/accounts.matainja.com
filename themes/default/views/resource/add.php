<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $assets=base_url().'themes/default/assets/'; ?>
<!DOCTYPE html>
<html>
<!--Header Start-->
<head>

    <meta charset="utf-8">

    <base href="<?= site_url() ?>"/>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php /*?><?= $page_title ?> - <?= $Settings->site_name ?><?php */?></title>

    <link rel="shortcut icon" href="<?= $assets ?>images/icon.png"/>

    <link href="<?= $assets ?>styles/theme.css" rel="stylesheet"/>

    <link href="<?= $assets ?>styles/style.css" rel="stylesheet"/>

    <script type="text/javascript" src="<?= $assets ?>js/jquery-2.0.3.min.js"></script>

    <script type="text/javascript" src="<?= $assets ?>js/jquery-migrate-1.2.1.min.js"></script>

    <!--[if lt IE 9]>

    <script src="<?= $assets ?>js/jquery.js"></script>

    <![endif]-->

    <noscript><style type="text/css">#loading { display: none; }</style></noscript>

    <?php if ($Settings->user_rtl) { ?>

        <link href="<?= $assets ?>styles/helpers/bootstrap-rtl.min.css" rel="stylesheet"/>

        <link href="<?= $assets ?>styles/style-rtl.css" rel="stylesheet"/>

        <script type="text/javascript">

            $(document).ready(function () { $('.pull-right, .pull-left').addClass('flip'); });

        </script>

    <?php } ?>

    <script type="text/javascript">

        $(window).load(function () {

            $("#loading").fadeOut("slow");

        });

    </script>

</head>
<!--Header End-->


<body>

<noscript>

    <div class="global-site-notice noscript">

        <div class="notice-inner">

            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in

                your browser to utilize the functionality of this website.</p>

        </div>

    </div>

</noscript>
<p><a href="<?=base_url()?>resource">Go Back</a></p>
<?php if ($error) { ?>

                            <div class="alert alert-danger">

                                <button data-dismiss="alert" class="close" type="button">×</button>

                                <?= $error; ?>

                            </div>

                        <?php } ?>

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

                echo form_open_multipart("resource/add/" , $attrib)

                ?>
        <div class="row">
        <div class="alert alert-danger date-con" style="display: none;">
                  <button data-dismiss="alert" class="close" type="button">×</button>
                  <span id="date-error"></span> </div>
          <div class="alert alert-danger customer-con" style="display: none;">
                  <button data-dismiss="alert" class="close" type="button">×</button>
                  <span id="customer-error"></span> </div>
          <div class="col-lg-12">
          
           <div class="col-md-12">
            <div class="col-md-4">
              <div class="form-group">
                <?= lang("payment_date"); ?>
                <?php echo form_input('payment_date', (isset($_POST['payment_date']) ? $_POST['payment_date'] : ""), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="sldate"'); ?> </div>
            </div>
          
            <div class="col-md-4">
              <div class="form-group">
                <?= lang("slresource"); 
                $resource_type=array(
                   ''=>'Select',
                 'Mouse'=>'Mouse',
          'UPS'=>'UPS',
          'HardDisk'=>'HardDisk',
          'WirelessAdapter'=>'WirelessAdapter',
          'RAM'=>'RAM',
          'SMPS'=>'SMPS',
          'Laptop'=>'Laptop',
                    'Keyboard'=>'Keyboard', 
                     'Monitor'=>'Monitor',
                      'CPU'=>'CPU',
                     'HeadPhone'=>'HeadPhone',
          'Others'=>'Others'


                );?>
                <?php echo form_dropdown('resource', $resource_type,(isset($_POST['resource']) ? $_POST['resource'] : ""), 'class="form-control input-tip" id="slresource"'); ?> </div>
            </div>
            <div class="col-md-4">
                    <div class="form-group">
                      <?= lang("slresourcename"); ?>
                      <div class="input-group">
                        <?php echo form_input('resourcename', (isset($_POST['resourcename']) ? $_POST['resourcename'] : ""), 'id="slresourcename" class="form-control input-tip"'); ?>
                        <?php /*?><?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                        <div class="input-group-addon no-print" style="padding: 2px 8px;"> <a href="<?= site_url('customers/add'); ?>" id="add-customer"class="external" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus-circle" id="addIcon"  style="font-size: 1.2em;"></i> </a> </div>
                        <?php } ?><?php */?>
                      </div>
                    </div>
                  </div>
           </div>
           <div class="col-md-12">
            <div class="col-md-4">
                    <div class="form-group">
                      <?= lang("model"); ?>
                      <div class="input-group">
                        <?php echo form_input('model', (isset($_POST['model']) ? $_POST['model'] : ""), 'id="slmodel" class="form-control input-tip"'); ?>
                        <?php /*?><?php if ($Owner || $Admin || $GP['customers-add']) { ?>
                        <div class="input-group-addon no-print" style="padding: 2px 8px;"> <a href="<?= site_url('customers/add'); ?>" id="add-customer"class="external" data-toggle="modal" data-target="#myModal"> <i class="fa fa-plus-circle" id="addIcon"  style="font-size: 1.2em;"></i> </a> </div>
                        <?php } ?><?php */?>
                      </div>
                    </div>
                  </div>
            <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("resserial_no"); ?></label>
                <?php echo form_input('serial_no', (isset($_POST['serial_no']) ? $_POST['serial_no'] : ''), 'class="form-control input-tip" id="slserial_no"'); ?> </div>
            </div>
            </div>
            
            <div class="clearfix"></div>
            <div class="col-md-12">
            	<div class="panel panel-warning">
                 <div class="panel-body" style="padding: 5px;">
                  <div class="col-md-4">
                    <div class="form-group">
                      <label>
                        <?= lang("warranty").' ('.lang("slmonths").')'; ?> *
                      </label>
                      <div class="input-group">
                        <?php 
							/*	$warranty = array(
												''=>'Select Warranty',
												'0'=>'No Warranty',
												'1'=>'1 month',
												'2'=>'2 months',
												'3'=>'3 months',
												'4'=>'4 months',
												'5'=>'5 months',
												'6'=>'6 months',
												'7'=>'7 months',
												'8'=>'8 months',
												'9'=>'9 months',
												'10'=>'10 months',
												'11'=>'11 months',
												'12'=>'12 months'
											);
						*/
						echo form_input('warranty',(isset($_POST['warranty']) ? $_POST['warranty'] : ''), 'id="slwarranty" class="form-control input-tip" data-placeholder="'.lang("slwarranty").'" style="width:100%;" '); ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("damage"); ?> *</label>
                <?php 
				$damage = array(
									'No'=>'No',
									'Yes'=>'Yes'
								);
				echo form_dropdown('damage', $damage, (isset($_POST['damage']) ? $_POST['damage'] : ''), 'id="slpayment_mode" class="form-control input-tip select" data-placeholder="' . lang("select") . ' ' . lang("damage") . '" style="width:100%;" '); ?> </div>
            </div>
	                </div>
    	        </div>
        	 </div>
            <div class="col-md-12">
            <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("assign"); ?></label>
                <?php echo form_input('assign', (isset($_POST['assign']) ? $_POST['assign'] : ''), 'class="form-control input-tip" id="slassign"'); ?> </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("image"); ?></label>
               <div> 
                <?php /*?><?php 
				echo form_input('commision_fee', (isset($_POST['commision_fee']) ? $_POST['commision_fee'] : ''), 'class="form-control input-tip" id="commision_fee"'); ?><?php */?> 
                <input id="resource_image" type="file" data-browse-label="<?= lang('browse'); ?>" name="resource_image" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                </div>
            </div>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("bill"); ?></label>
               <div> 
                <?php /*?><?php 
				echo form_input('commision_fee', (isset($_POST['commision_fee']) ? $_POST['commision_fee'] : ''), 'class="form-control input-tip" id="commision_fee"'); ?><?php */?> 
                <input id="bill" type="file" data-browse-label="<?= lang('browse'); ?>" name="bill" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">
                </div>
            </div>
            </div>
            </div>
            <?php /*?><div class="col-md-12"> 
                  
              
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
            <div class="clearfix"></div>
            <div class="col-md-12">
              <div class="form-group"><?php echo form_submit('add_resource', lang("submit"), 'id="add_resource" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                <?php /*?><button type="button" class="btn btn-danger" id="reset"><?= lang('reset') ?></button><?php */?>
              </div>
            </div>
          </div>
        </div>
        <?php echo form_close(); ?> </div>
    </div>
  </div>
</div>









<?php unset($Settings->setting_id, $Settings->smtp_user, $Settings->smtp_pass, $Settings->smtp_port, $Settings->update, $Settings->reg_ver, $Settings->allow_reg, $Settings->default_email, $Settings->mmode, $Settings->timezone, $Settings->restrict_calendar, $Settings->restrict_user, $Settings->auto_reg, $Settings->reg_notification, $Settings->protocol, $Settings->mailpath, $Settings->smtp_crypto, $Settings->corn, $Settings->customer_group, $Settings->envato_username, $Settings->purchase_code); ?>
<script type="text/javascript">
var dt_lang = <?=$dt_lang?>, dp_lang = <?=$dp_lang?>, site = <?=json_encode(array('base_url' => base_url(), 'settings' => $Settings, 'dateFormats' => $dateFormats))?>;
var lang = {paid: '<?=lang('paid');?>', pending: '<?=lang('pending');?>', completed: '<?=lang('completed');?>', ordered: '<?=lang('ordered');?>', received: '<?=lang('received');?>', partial: '<?=lang('partial');?>', sent: '<?=lang('sent');?>', r_u_sure: '<?=lang('r_u_sure');?>', due: '<?=lang('due');?>', returned: '<?=lang('returned');?>', transferring: '<?=lang('transferring');?>', active: '<?=lang('active');?>', inactive: '<?=lang('inactive');?>', unexpected_value: '<?=lang('unexpected_value');?>', select_above: '<?=lang('select_above');?>', download: '<?=lang('download');?>'};
</script>

<?php
$s2_lang_file = read_file('./assets/config_dumps/s2_lang.js');
foreach (lang('select2_lang') as $s2_key => $s2_line) {
    $s2_data[$s2_key] = str_replace(array('{', '}'), array('"+', '+"'), $s2_line);
}
$s2_file_date = $this->parser->parse_string($s2_lang_file, $s2_data, true);
?>
<script type="text/javascript" src="<?= $assets ?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.dataTables.dtFilter.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/select2.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery-ui.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/bootstrapValidator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/custom.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/jquery.calculator.min.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/core.js"></script>
<script type="text/javascript" src="<?= $assets ?>js/perfect-scrollbar.min.js"></script>
<?= ($m == 'purchases' && ($v == 'add' || $v == 'edit' || $v == 'purchase_by_csv')) ? '<script type="text/javascript" src="' . $assets . 'js/purchases.js"></script>' : ''; ?>
<?= ($m == 'transfers' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/transfers.js"></script>' : ''; ?>
<?= ($m == 'sales' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/sales.js"></script>' : ''; ?>
<?= ($m == 'quotes' && ($v == 'add' || $v == 'edit')) ? '<script type="text/javascript" src="' . $assets . 'js/quotes.js"></script>' : ''; ?>
<?= ($m == 'products' && ($v == 'add_adjustment' || $v == 'edit_adjustment')) ? '<script type="text/javascript" src="' . $assets . 'js/adjustments.js"></script>' : ''; ?>

<script type="text/javascript" charset="UTF-8">var r_u_sure = "<?=lang('r_u_sure')?>";
    <?=$s2_file_date?>
    $.extend(true, $.fn.dataTable.defaults, {"oLanguage":<?=$dt_lang?>});
    $.fn.datetimepicker.dates['sma'] = <?=$dp_lang?>;
    $(window).load(function () {
        $('.mm_<?=$m?>').addClass('active');
        $('.mm_<?=$m?>').find("ul").first().slideToggle();
        $('#<?=$m?>_<?=$v?>').addClass('active');
        $('.mm_<?=$m?> a .chevron').removeClass("closed").addClass("opened");
    });
</script>
<script type="application/javascript">
		$('#slassign').select2({
            minimumInputLength: 1,
            ajax: {
                url: site.base_url + "staff/suggestions",
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
</script>
</body>
</html>