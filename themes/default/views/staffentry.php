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

<?php if($this->session->flashdata('message')!='') { ?>

                            <div class="alert alert-success">

                                <button data-dismiss="alert" class="close" type="button">×</button>

                                <?=$this->session->flashdata('message'); ?>

                            </div>

                        <?php } ?>

<?php if ($error) { ?>

                            <div class="alert alert-danger">

                                <button data-dismiss="alert" class="close" type="button">×</button>

                                <?= $error; ?>

                            </div>

                        <?php } ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('create_staff'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
             

                <?php $attrib = array('class' => 'form-horizontal', 'data-toggle' => 'validator', 'role' => 'form');
                echo form_open_multipart("entry", $attrib);
                ?>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php echo lang('Name *','Name'); ?>
                                <div class="controls">
                                    <?php echo form_input('staff_name', (isset($_POST['staff_name']) ? $_POST['staff_name'] : ""), 'class="form-control" id="name1" required="required" pattern=".{3,10}"'); ?>
                                </div>
                            </div>

                            <div class="form-group">
                                <?php echo lang('FatherName', 'FatherName'); ?>
                                <div class="controls">
                                    <?php echo form_input('staff_fathername', (isset($_POST['staff_fathername']) ? $_POST['staff_fathername'] : ""), 'class="form-control" id="f_name" required="required"'); ?>
                                </div>
                            </div>
                             
                                <div class="form-group">
                                    <?= lang('dobdate','dobdate'); ?>
                                    <?php echo form_input('dobdate', (isset($_POST['dobdate']) ? $_POST['dobdate'] : ""), 'class="form-control input-tip date" data-date-format="yyyy-mm-dd" id="podate" required="required"'); ?>
                                </div>
                                  
                               
                                    <?php echo form_hidden('joindate',date('Y-m-d')); ?>
                                
                           <div class="form-group">
                                <?php echo lang('address', 'address'); ?>
                                <div class="controls">
                                    <?php echo form_textarea('Address',(isset($_POST['Address']) ? $_POST['Address'] : ""), 'class="form-control"  id="Address" required="required"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?php echo lang('PresentAddress','PresentAddress'); ?>
                                <div class="controls">
                                    <?php echo form_textarea('PresentAddress',(isset($_POST['PresentAddress']) ? $_POST['PresentAddress'] : ""), 'class="form-control" id="PresentAddress" '); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?php echo lang('city', 'city'); ?>
                                <div class="controls">
                                       <?php echo form_input('city', (isset($_POST['city']) ? $_POST['city'] : ""), 'class="form-control" id="city" required="required"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?php echo lang('zipcode', 'zipcode'); ?>
                                <div class="controls">
                                   <?php echo form_input('zipcode', (isset($_POST['zipcode']) ? $_POST['zipcode'] : ""), 'class="form-control" id="zipcode" required="required"'); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <?= lang('gender', 'gender'); ?>
                                <?php
                                $ge[''] = array('male' => lang('male'), 'female' => lang('female'));
                                echo form_dropdown('gender', $ge, (isset($_POST['gender']) ? $_POST['gender'] : ''), 'class="tip form-control" id="gender" data-placeholder="' . lang("select") . ' ' . lang("gender") . '" required="required"');
                                ?>
                            </div>
                              
                                            
                         
                        </div>
                   
                        
                <div class="col-md-6">
                  <div class="col-md-10">
                   
                   <div class="form-group">
                                <?php echo lang('personalemail', 'personalemail'); ?>
                                <div class="controls">
                                     <?php  echo form_input('personalemail', (isset($_POST['personalemail']) ? $_POST['personalemail'] : ""), 'class="form-control" id="personalemail" required="required"');  ?>
                                           </div></div>
                             <div class="form-group">
                                <?php echo lang('businessemail', 'businessemail'); ?>
                                <div class="controls">
                                    <?php echo form_input('businessemail', (isset($_POST['businessemail']) ? $_POST['businessemail'] : ""), 'class="form-control" id="businessemail" required="required"'); ?>
                                </div>
                            </div>
                             <div class="form-group">
                                <?= lang("Browse", "Browse") ?>
                               <input id="Browse" type="file" data-browse-label="<?= lang('Browse'); ?>" name="Browse" data-show-upload="false"
                                       data-show-preview="false" class="form-control file">

                            </div>
                             
                                    
                                    <?php echo form_hidden('interviewdate',date('Y-m-d')); ?>
                           
                                
                                 
                                    <?php echo form_hidden('interviewschedule',date('Y-m-d H:i:s')); ?>
                              
                               
                                           
                             
                              <div class="form-group">
                                <?php echo lang('Phone','Phone'); ?>
                                    <div class="controls">
                                    <?php echo form_input('Phone', (isset($_POST['Phone']) ? $_POST['Phone'] : "") ,'class="form-control" id="Phone" required="required"'); ?>
                                    </div>
                            </div>
                          
                                <?php 
								foreach ($groups as $group) {
									if($group['name']=='staff' || $group['name']=='Staff') 
									{   
										$user_group=$group['id'];
									}
								 }
								 echo form_hidden('group',$user_group);
                                ?>
                            
             
                  
              
                   <?php echo form_hidden('payment_mode',""); ?>
                   <?php echo form_hidden('accountnumber',""); ?>
                    
                <div class="form-group">
                <?php echo lang('note', 'note'); ?>
                <div class="controls">
                <?php echo form_textarea('note',(isset($_POST['note']) ? $_POST['note'] : ""), 'class="form-control" id="Note"'); ?>
                </div>  
                </div>
                
                <?php echo form_hidden('project_manager', 0); ?>
              
                <?php echo form_hidden('attendance_id',""); ?>
                


                  </div>
                    
                                
                                           
                </div>
                 </div>
                 </div>
                <p><?php echo form_submit('save', lang('save'), 'class="btn btn-primary"'); ?></p>

                <?php echo form_close(); ?>
      </div>    
    </div>
</div>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function () {
        $('.no').slideUp();
        $('#group').change(function (event) {
            var group = $(this).val();
            if (group == 1 || group == 2) {
                $('.no').slideUp();
            } else {
                $('.no').slideDown();
            }
        });
        $('#pay_mode').change(function()
        {
            if($(this).val()=='Cash')
            {
                $('#dvacctnum').hide();
                }
                else
                {
                    $('#dvacctnum').show();
                    }
            });
        
    });
</script>


<script type="application/javascript">
$(document).ready(function () {
     
     $('#group').on('change',function()
  {
   // alert('hi');
    var p = $(this).val();
   // alert(p);
    //alert('hi');
    if(p==7)
    {
       //alert('hi');
       $('#project_manager1').val('');
      $('.project_manager').hide();
    }
    else
    {
         $('.project_manager').show();
     
}
});


       
     });
</script>

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

</body>
</html>