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
<link href='<?= $assets ?>fullcalendar/css/fullcalendar.min.css' rel='stylesheet' />
<link href='<?= $assets ?>fullcalendar/css/fullcalendar.print.css' rel='stylesheet' media='print' />
<link href="<?= $assets ?>fullcalendar/css/bootstrap-colorpicker.min.css" rel="stylesheet" />

<style>
 ul.nav.nav-tabs > li {
    width: auto;
    float: none;
    display: block;
}
    .fc th {
        padding: 10px 0px;
        vertical-align: middle;
        background:#F2F2F2;
        width: 14.285%;
    }
    .fc-content {
        cursor: pointer;
        padding:12px !important;
    }
    .fc-day-grid-event>.fc-content {
        padding: 4px;
    }

    .fc .fc-center {
        margin-top: 5px;
    }
    .error {
        color: #ac2925;
        margin-bottom: 15px;
    }
    .event-tooltip {
        width:150px;
        background: rgba(0, 0, 0, 0.85);
        color:#FFF;
        padding:10px;
        position:absolute;
        z-index:10001;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
    }
    .staff_rpt {
    overflow-x: hidden;
    overflow-y: scroll;
    max-height: 550px;
}
.greenbox {
    width: 15px;
    height: 15px;
    background-color: green;
    float: left;
    margin-right: 5px;
}
.redbox {
    width: 15px;
    height: 15px;
    background-color: red;
    float: left;
    margin-right: 5px;
}
.bluebox {
    width: 15px;
    height: 15px;
    background-color: blue;
    float: left;
    margin-right: 5px;
}
.pinkbox {
    width: 15px;
    height: 15px;
    background-color: pink;
    float: left;
    margin-right: 5px;
}
.yellowbox {
    width: 15px;
    height: 15px;
    background-color: yellow;
    float: left;
    margin-right: 5px;
}
.orangebox {
    width: 15px;
    height: 15px;
    background-color: orange;
    float: left;
    margin-right: 5px;
}
.mixedbox{
     width: 15px;
    height: 15px;
    background-color: #ABEBC6;
    float: left;
    margin-right: 5px;
}
.color_row   
{
 margin:0 15px 10px 0;
 padding: 0;
 float: left;

}
.boxtext   
{
  font-size:15px;
  color:#000;position: relative;
    top: -2px;
    margin-left: 6px;
 
}
</style>
</head>
<body>
<div id="loading"></div>

<noscript>

    <div class="global-site-notice noscript">

        <div class="notice-inner">

            <p><strong>JavaScript seems to be disabled in your browser.</strong><br>You must have JavaScript enabled in

                your browser to utilize the functionality of this website.</p>

        </div>

    </div>

</noscript>
<p><a href="<?=base_url()?>attendance">Go Back</a></p>
<?php if ($error) { ?>

                            <div class="alert alert-danger">

                                <button data-dismiss="alert" class="close" type="button">×</button>

                                <?= $error; ?>

                            </div>

                        <?php } ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-calendar"></i><?= lang('analysis_attendance'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
               
                <div class="col-lg-2 col-xs-3">
                    
                    <div>

                <div class="form-group">
                   <label><?= lang("Name"); ?></label>
                <?php echo form_input('name',(isset($_POST['name']) ? $_POST['name'] : ''),'class="form-control input-tip" id="uname"'); ?>
                <input type="button" id="this_search" class="btn btn-primary" value="Search" style="padding: 6px 6px; margin:15px 0;">
                </div>
                 
                 </div>
                   <?php if(!empty($staff_info))
                   {
                    ?>
                         <div class="staff_rpt">
                        <ul class="nav nav-tabs">
                   
                   <?php foreach($staff_info as$staff_info_new )
                    {
                        ?>
            <li class="active" ><a href="javascript:void(0)" class="attn_display"  staff_id="<?php echo $staff_info_new->user_id;?>" data-toggle="tab">
                <?php echo $staff_info_new->first_name.' '. $staff_info_new->last_name.'('.$staff_info_new->attendance_id.')'; 
                echo "<br>";
                ?>
                    
                </a></li>
                   
                    <?php  } ?>
                     </ul>
                 </div>
                  <?php } ?>
                 
                </div>
                <div class="col-lg-10 col-xs-9">

               <div class="get_user" style="text-align:center;">

                </div>
         
                <div class="color_row"><span class="greenbox"></span><span class="boxtext">Present</span></div><div class="color_row"><span class="redbox"></span><span class="boxtext">Absent</span></div><div class="color_row"><span class="bluebox"></span><span class="boxtext">Late</span></div><div class="color_row"><span class="pinkbox"></span><span class="boxtext">Holiday</span></div><div class="color_row"><span class="yellowbox"></span><span class="boxtext">Sunday</span></div><div class="color_row"><span class="orangebox"></span><span class="boxtext">Leave</span></div><div class="color_row"><span class="mixedbox"></span><span class="boxtext">Work From Home</span></div>
                <input type="button" id="this_send" data_email='' data-start="" data-end="" class="btn btn-primary pull-right" value="Send" style="padding: 6px 6px; margin:10px 0;">
                <div class="alert alert-success alert-dismissible" id="email_info" style="display:none;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Success!</strong> Indicates a successful or positive action.
                </div>
           
                <div class="clearfix"></div>
                <div id='calendar'></div>
                  
                <div class="modal fade cal_modal">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                    <i class="fa fa-2x">&times;</i>
                                </button>
                                <h4 class="modal-title"></h4>
                            </div>
                            <div class="modal-body">
                                <div class="error"></div>
                                <form>
                                    <input type="hidden" value="" name="eid" id="eid">
                                    <div class="form-group">
                                        <?= lang('title', 'title'); ?>
                                        <?= form_input('title', set_value('title'), 'class="form-control tip" id="title" required="required"'); ?>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang('start', 'start'); ?>
                                                <?= form_input('start', set_value('start'), 'class="form-control datetime" id="start" required="required"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang('end', 'end'); ?>
                                                <?= form_input('end', set_value('end'), 'class="form-control datetime" id="end"'); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <?= lang('event_color', 'color'); ?>
                                                <div class="input-group">
                                                    <span class="input-group-addon" id="event-color-addon" style="width:2em;"></span>
                                                    <input id="color" name="color" type="text" class="form-control input-md" readonly="readonly" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <?= lang('description', 'description'); ?>
                                        <textarea class="form-control skip" id="description" name="description"></textarea>
                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer"></div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script src='<?= $assets ?>fullcalendar/js/moment.min.js'></script>
<script src="<?= $assets ?>fullcalendar/js/fullcalendar.min.js"></script>
<script src="<?= $assets ?>fullcalendar/js/lang-all.js"></script>
<script src='<?= $assets ?>fullcalendar/js/bootstrap-colorpicker.min.js'></script>




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
<script type="text/javascript">
    var currentLangCode = '<?= $cal_lang; ?>', moment_df = '<?= strtoupper($dateFormats['js_sdate']); ?> HH:mm', cal_lang = {},
    tkname = "<?=$this->security->get_csrf_token_name()?>", tkvalue = "<?=$this->security->get_csrf_hash()?>";
    cal_lang['add_event'] = '<?= lang('add_event'); ?>';
    cal_lang['edit_event'] = '<?= lang('edit_event'); ?>';
    cal_lang['delete'] = '<?= lang('delete'); ?>';
    cal_lang['event_error'] = '<?= lang('event_error'); ?>';
</script>
<script>
    $(document).ready(function(){
         $('#uname').select2({
             //alert('hello');
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
       $('#calendar').fullCalendar({
        lang: currentLangCode,
        isRTL: (site.settings.user_rtl == 1 ? true : false),
        eventLimit: true,
        timeFormat: 'H:mm',
        height: 550,
        // timezone: site.settings.timezone, // 'local', 'UTC' or timezone
        ignoreTimezone: false,
        selectable: true,
        selectHelper: true,
        select: function(start, end) {

            startDate = start.format();
            endDate = end.format();

           
        },
        header: {
            left: 'prev, next, today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        // Get all events stored in database
        events: site.base_url+'calendar/get_events',
        eventAfterAllRender: function(){
                 var start_date=$('#calendar').fullCalendar('getView').start.format();
                    var end_date=$('#calendar').fullCalendar('getView').end.format();
                    $('#this_send').attr('data-start',start_date);
                    $('#this_send').attr('data-end',end_date);
            }
        
       
        
    });
      $(document).on('click', ".attn_display", function(){
         //alert('hello');
        var uid=$(this).attr('staff_id');
        var send=$('#this_send').attr('data_email',uid);
        var start_date_attn = '';
        var end_date_attn = '';

        $('#calendar').fullCalendar('destroy');
          $.ajax({
                 type:'POST',
                    url: 'attendance/check_user',
                    dataType: "json",
                    data:{
                   token: '<?php echo $this->security->get_csrf_hash();?>',
                        id:uid},
                   beforeSend: function() {
                            $('#loading').show();
                            },
                   complete: function(){
                           $('#loading').hide();
                          },
                    success: function (data) {
                    console.log(data);
                    if(data!='')
                    {
                        
                    $('.get_user').html('<p><strong>'+data.name+'</strong></p>');
                    }
                    else
                    {
                      $('.get_user').html('');  
                    }
                }
        });

        $('#calendar').fullCalendar({
            lang: currentLangCode,
            isRTL: (site.settings.user_rtl == 1 ? true : false),
            eventLimit: true,
            timeFormat: 'H:mm',
            height: 550,
            // timezone: site.settings.timezone, // 'local', 'UTC' or timezone
            ignoreTimezone: false,
            selectable: true,
            selectHelper: true,
            select: function(start, end) {
               startDate = start.format();
                endDate = end.format();

            },
            header: {
                left: 'prev, next, today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
          /*    loading: function( isLoading, view ) {
            if(isLoading) {// isLoading gives boolean value
                 $('#loading').show();
            } else {
                $('#loading').hide();
            }*/
            events: site.base_url+'attendance/get_attendance?uid='+uid,
            eventAfterAllRender: function(){
                 var start_date=$('#calendar').fullCalendar('getView').start.format();
                    var end_date=$('#calendar').fullCalendar('getView').end.format();
                    $('#this_send').attr('data-start',start_date);
                    $('#this_send').attr('data-end',end_date);
            } 
            /* eventAfterAllRender: function(){
                   var start_date=$('#calendar').fullCalendar('getView').start.format();
                    var end_date=$('#calendar').fullCalendar('getView').end.format();
                     $.ajax({

                          type:'POST',
                                url: 'attendance/get_total_attendance',
                                dataType: "json",
                                data:{
                               token: '<?php //echo $this->security->get_csrf_hash();?>',
                                    id:uid,
                                    startdate:start_date,
                                    enddate:end_date},
                                success: function (data) {
                                console.log(data);
                                if(data!='')
                                {
                                    
                                //$('.get_user').html('<p><strong>'+data.name+'</strong></p>');
                                 
                                }
                                else
                                {
                                  //$('.get_user').html('');  
                                }
                            }
                    });  
                }*/
        }); 
      });
    $(document).on('click','#this_send',function(){
        var uid=$(this).attr('data_email');
        //alert(uid);
        var start_date=$(this).attr('data-start');
        var end_date=$(this).attr('data-end');
     
        $.ajax({
            type:'POST',
                 url:'attendance/usersend_mail',
                 dataType:"json",
                 data:{
                      token: '<?php echo $this->security->get_csrf_hash();?>',
                      id:uid,
                      start_date:start_date,
                       end_date:end_date,
                       },
                 beforeSend: function() {
                    $('#loading').show();
                                },
                 complete: function(){
                    $('#loading').hide();
                          },
                    success: function (data) {
                    console.log(data); 
                    if(data!=''){
                    $('#email_info').html(data.message);
                    //alert(data.message);
                     }
                     else
                     {

                       alert('email is not sent successfully');
                     }
                    }   
                });
     });
        $(document).on('click', "#this_search", function(){
         //alert('hello');
        var uid=$('#uname').val();
         var send=$('#this_send').attr('data_email',uid);
        var start_date_attn = '';
        var end_date_attn = '';
         $('#calendar').fullCalendar('destroy');

          $.ajax({

              type:'POST',
                    url: 'attendance/check_user',
                    dataType: "json",
                    data:{
                   token: '<?php echo $this->security->get_csrf_hash();?>',
                        id:uid},
                   beforeSend: function() {
                                $('#loading').show();
                                },
                   complete: function(){
                             $('#loading').hide();
                          },
                    success: function (data) {
                    console.log(data);
                    if(data!='')
                    {
                    $('.get_user').html('<p><strong>'+data.name+'</strong></p>');
                     
                    }
                    else
                    {
                      $('.get_user').html('');  
                    }
                }
        });

        $('#calendar').fullCalendar({
            lang: currentLangCode,
            isRTL: (site.settings.user_rtl == 1 ? true : false),
            eventLimit: true,
            timeFormat: 'H:mm',
            height: 550,
            // timezone: site.settings.timezone, // 'local', 'UTC' or timezone
            ignoreTimezone: false,
            selectable: true,
            selectHelper: true,
            select: function(start, end) {

                startDate = start.format();
                endDate = end.format();

               
            },
            header: {
                left: 'prev, next, today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            // Get all events stored in database
            events: site.base_url+'attendance/get_attendance?uid='+uid, 
            eventAfterAllRender: function(){
                 var start_date=$('#calendar').fullCalendar('getView').start.format();
                    var end_date=$('#calendar').fullCalendar('getView').end.format();
                    $('#this_send').attr('data-start',start_date);
                    $('#this_send').attr('data-end',end_date);
            }
        });   
      });
    });
    </script>



</body>
</html>
