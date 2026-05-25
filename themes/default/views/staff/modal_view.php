
<style>
.modal-new
{
    min-width:400px; 
    max-width:400px;
}
.modal-header .close {
    margin-top: -12px;
    opacity: 0.4;
    position: absolute;
    right: 10px;
    top: 13px;
}
.modal-dialog.modal-new > .modal-content > .modal-header > p {
    margin: 0;
    font-size: 20px;
    font-weight: normal !important;
    text-transform: capitalize;
}
p.pswd {
    text-align: center;
    font-size: 21px;
    color: #9c9c9c;
}
.margin-top_outtime {
    margin-top: 15px;
}

</style>

<div class="modal-dialog modal-new">
    <div class="modal-content">
        <div class="modal-header">
          
           <p><strong><?php echo $userinfo->first_name.' '.$userinfo->last_name;?></strong></p>
            <button type="button" class="close " data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-1x">&times;</i>
            </button>
            </div>
        <div class="modal-body">
            <?php 
            if(isset($userinfo))
                       {
                echo form_hidden('attn_id',$userinfo->id,' id="insert_id" ');
                                   ?>
                               <p class="pswd"> <?php echo lang('app_password','app_password'); ?></p>
                                <div class="controls">
                                    <?php echo form_password('app_password', (isset($_POST['app_password']) ? $_POST['app_password'] :''), 'class="form-control" id="app_password" style="width:50%; margin:0 auto 20px;" type="password" required="required" pattern=".{3,10}"'); ?>
                                </div>
             <div class="modal-footer no-print">
    <?php echo form_button('update', lang('insert'), 'class="btn btn-primary" id="btn" data-id="'.$userinfo->id.'"'); ?>
    <button type="button" class="btn btn-default" data-dismiss="modal"><?= lang('close'); ?></button>
    </div>
            <div class="clearfix"></div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('#btn').unbind().click(function() {
    var id= $("input[name='attn_id']").val();
    var app_pswd=$('#app_password').val();
    $.ajax({
         type   : 'POST',
         url   : 'staff/update_App_password',
         dataType: "json",
         data: {
             token: '<?php echo $this->security->get_csrf_hash();?>',
               id:id,
               apppswd:app_pswd, 
           },
     success : function(data){
     console.log(data);
      if(data.success==1)
      {
       
        $('#myModalpassword').modal('hide');

      }
      else if(data.error==1)
        {
         $('#myModalpassword').html(data.message);
         $('#myModalpassword').modal('show');
       }
      }
      });
   });
 });
    </script>



  


 

    