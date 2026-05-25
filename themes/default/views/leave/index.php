<style>
#d1 {
  height: 100px !important;
  left: 30%;
  position:fixed;
  top: 74%;
  width: 20px !important;
 
  
}
#d1 img {
  border: 6px solid #fff;
  border-radius: 65px;
  height: 100px;
  width: 100px;
 
  
}
</style>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('leave'); ?></h2>
    </div>
<div class="box-content">
        <div class="row">
            <div class="col-lg-12">
              <div class="col-xs-4">
              <div class="form-group">
                <label><?= lang("staff"); ?></label>
                <?php echo form_input('staff_id',(isset($_POST['staff_id']) ? $_POST['staff_id'] : ''),'class="form-control input-tip" id="staff_id"'); ?>
                      <?php echo form_checkbox('status','approve',' id="approvelist" class="form-control"'); ?>
                                     <?= lang("approve", "approve"); ?>
                     <?php echo form_checkbox('status','pending',' id="pendinglist" class="form-control"'); ?>
                                     <?= lang("pending", "pending"); ?>
                         <?php echo form_checkbox('status','reject',' id="rejectlist" class="form-control "'); ?>
                                     <?= lang("reject", "reject"); ?>             
                 </div>
             </div>
       <div class="col-xs-4">      
       <label><?= lang("year"); ?></label>
                 <div class="form-group">
               <?php $yearArray = range(2000, 2050);?>
                        <select name="year" id="year">
                        <option value="">Select Year</option>
                  <?php
                        foreach ($yearArray as $year) {
                        // if you want to select a particular year
                        $selected = ($year == "2018") ? 'selected' : '';
                        echo '<option '.$selected.' value="'.$year.'">'.$year.'</option>';
                        }
                     ?>
                       </select>
                     </div>
                     <input type="button" id="search" class="btn btn-primary" value="Search" style="padding: 6px 15px; margin:20px 0;">&nbsp&nbsp
          <input type="button" id="download" data_year="2018" class="btn btn-primary" value="Download PDF" style="padding: 6px 15px; margin:20px 0;">&nbsp
        <input type="button" id="send_mail" data_id= "" data_year="" class="btn btn-primary" value="Send Mail" style="padding: 6px 15px; margin:20px 0;">
         <div class="alert alert-success alert-dismissible" id="email_info" style="display:none;">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <span id="success_text">
                <strong>Success!</strong> Indicates a successful or positive action.
                </span>
                </div>
      </div>
           <div class="col-lg-12">  
           <div class="total_leave" style="display:none;">
           <span class="cl" style="display: none;"><label>Total CL:</label> <span id="leave_cl"></span></span>
           <span class="ml" style="display: none;"><label>Total ML:</label> <span id="leave_ml"></span></span>
           </div> 
           </div>
         </div>
         <table id="domainDataTable" class="table" style="display:none">
          <tr>
            <th>Staff ID</th>
            <th>Leave Date</th>
            <th>Leave Type</th>
            <th>Payment type</th>
            <th>Status</th>
            <th>Reason</th>
          </tr>
          <tbody id="leave_data_table">
          </tbody>
          </table>
        </div>
      </div>
    </div>
      <script>
        $(document).ready(function(){
               $('#staff_id').select2({
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
       $('select').change(function(){
           var years=$(this).val();
           var data_year=$('#download').attr('data_year',years);

         });

      $('#download').click(function(){
          var year=$(this).attr('data_year');
                   if(year=='')
                      {
                        alert('Please Select the year');
                         return false;
                       }
                     else{
                          window.location.href = '<?php echo base_url(); ?>leave/downloadleaveresult?year='+year;
                       }
           });
      
      $('#send_mail').click(function(){
         var staff_id=$('#staff_id').val();
         var year=$('#year').val();
      /*   alert(staff_id);*/
          //alert(staff_id);
          /*var data_id=(this).attr('data_id',$staff_id);*/
          
           /* alert(year);*/
            if(staff_id=='')
               {
                  alert('Please fill up the Satff Name');
                  return false;
               }
              if(year=='')
                {
                  alert('Please Select the year');
                   return false;
                 }
           else{
                 $.ajax({
                 type:'POST',
                 url: 'leave/leavedetail_email',
                 dataType: "json",
                 data:{
                      token: '<?php echo $this->security->get_csrf_hash();?>',
                      staffid:staff_id,
                      leaveyears:year
                     },
                   /*    beforeSend: function() {
                       $('#loading').show();
                                },
                       complete: function(){
                        $('#loading').hide();
                          },*/
                      success: function (data)
                      {
                        console.log(data);
                         if(data!='')
                         {
                           $('#email_info').show();
                           $('#success_text').html(data.message);

                         }
                        else
                          {
                            alert('Email is not sent successfully');
                          }
                      }
                        });
                }
         });



             $('#search').click(function(){

                 var staff_id=$('#staff_id').val();
                 //var approve=$('#approvelist').val();
                 //var pending=$('#pendinglist').val();
                 //var reject=$('#rejectlist').val();
                  var data=[];
                  $.each($("input[name='status']:checked"),function()
                  {
                    data.push($(this).val());
                  });
                   var status_data=data.join(",");
                   var year=$('#year').val();
                   if(staff_id=='')
                     {
                        alert('Please fill up the Satff Name');
                        return false;
                      }
                    if(year=='')
                      {
                        alert('Please Select the year');
                         return false;
                       }
                    if(!$("input[type='checkbox']").is(':checked'))
                      {
                        alert('please checked atleast one checkbox');
                        return false;
                      }

                   else{
             
                           $.ajax({
                                     type:'POST',
                                     url: 'leave/getleaveresult',
                                     dataType: "json",
                                     data:{
                                          token: '<?php echo $this->security->get_csrf_hash();?>',
                                          staffid:staff_id,
                                          leaveyears:year,
                                          status:status_data
                                          //approveresult:1
                                          //pendingresult:2,
                                         // rejectresult:3
                                         },
                                          success: function (data)
                                             {
                                                console.log(data);
                                                     if(data.error!=1)
                                                    {
                                                $('#leave_data_table').html('');
                                                $('#domainDataTable').show();
                                         
                                              var count_cl=0;
                                              var count_ml=0;
                                              
                                            $.each(data.leave_list, function() {
                                              if(this.payment_type=='unpaid')
                                              {
                                                var payment_type="<select id='p1' class='payment_type' data-user_id='"+this.user_id+"' data-leave_date='"+this.leave_date+"'><option value=''>select</option><option value='unpaid' selected='selected'>UnPaid</option><option value='paid'>Paid</option></select>";
                                              }
                                              else
                                              {
                                                 var payment_type="<select id='p1' class='payment_type' data-user_id='"+this.user_id+"' data-leave_date='"+this.leave_date+"'><option value=''>select</option><option value='paid' selected='selected'>Paid</option><option value='unpaid'>UnPaid</option></select>";
                                              }
                                              if(this.status=='Approve'){
                                                var status="<select id='s1' class='leave_status' data-user_id='"+this.user_id+"' data-leave_date='"+this.leave_date+"'><option value=''>select</option><option value='Approve' selected='selected'>APPROVE</option><option value='Pending'>PENDING</option><option value='Reject'>REJECT</option></select>";
                                            }else if(this.status=='Pending'){
                                              var status="<select id='s1' class='leave_status' data-user_id='"+this.user_id+"' data-leave_date='"+this.leave_date+"'><option value=''>select</option><option value='Approve'>APPROVE</option><option value='Pending' selected='selected'>PENDING</option><option value='Reject'>REJECT</option></select>";
                                            }else if(this.status=='Reject'){
                                              var status="<select id='s1' class='leave_status' data-user_id='"+this.user_id+"' data-leave_date='"+this.leave_date+"'><option value=''>select</option><option value='Approve'>APPROVE</option><option value='Pending'>PENDING</option><option value='Reject' selected='selected'>REJECT</option></select>";
                                            }else{
                                              var status="<select id='s1' class='leave_status' data-user_id='"+this.user_id+"' data-leave_date='"+this.leave_date+"'><option value=''>select</option><option value='Approve'>APPROVE</option><option value='Pending'>PENDING</option><option value='Reject'>REJECT</option></select>";
                                            }
                                            var row = $("<tr><td>" +this.user_id + "</td><td>"+this.leave_date+"</td><td>"+this.leave_type +"</td><td>"+payment_type+ "</td><td>"+status+"</td><td>"+this.subject+"</td></tr>");
                                                $("#leave_data_table").append(row);
                                            if(this.leave_type=="ML" && this.status=="Approve")
                                              {
                                               count_ml++;
                                              }
                                              if(this.leave_type=="CL" && this.status=="Approve")
                                              {
                                                count_cl++;
                                              }
                                            });
                                           // alert(count_ml);
                                          if(count_cl>=0)
                                        {
                                          $('.total_leave').show();
                                          $('#leave_cl').html(count_cl);
                                          $('.cl').show();
                                        }
                                          if(count_ml>=0)
                                        {
                                          $('.total_leave').show(); 
                                          $('#leave_ml').html(count_ml);
                                          $('.ml').show();
                                        }
                                       
                                       
                                  }
                                  else if(data.error==1){
                                     $('#leave_data_table').html('');
                                       $('#leave_data_table').append('<tr><td align="center" colspan="5">'+data.message+'</td></tr>'); 
                                       $('#domainDataTable').show();
                                      }  
                                             }
                                          });
                                 
                             }
                         });

                            $(document).on('change', '.leave_status', function(){
                                var user_id=$(this).attr('data-user_id');
                                var leave_date=$(this).attr('data-leave_date');
                                var status=$(this).val(); 
                                //alert(user_id);
                                //alert(leave_date);
                                //alert(status);
                                 $.ajax({
                                      type:'POST',
                                      url: 'leave/updatestatus',
                                      dataType: "json",
                                      data:{
                                        token: '<?php echo $this->security->get_csrf_hash();?>',
                                        userid:user_id,
                                        leavedate:leave_date,
                                        leave_status:status
                                        },
                                      success: function(data){
                                          console.log(data);
                                          if(data.error!=1)
                                          {
                                              if(data.cl_count>=0)
                                                {
                                                  $('.total_leave').show();
                                                  $('#leave_cl').html(data.cl_count);
                                                 // $('.cl').show(); 
                                                 }
                                                if(data.ml_count>=0)
                                                  {
                                                   $('.total_leave').show();
                                                    $('#leave_ml').html(data.ml_count);
                                                   // $('.ml').show();
                                                    }
                                                    
                                                    alert(data.message);
                                           } 
                                          else if(data.error==1)
                                          {
                                             alert(data.message);
                                          }
                                      }
                                  });
                            });
                            $(document).on('change', '.payment_type', function(){
                              var user_id=$(this).attr('data-user_id');
                              var leave_date=$(this).attr('data-leave_date');
                              var payment_type=$(this).val(); 
                              //alert(user_id);
                              //alert(leave_date);
                              //alert(status);
                               $.ajax({
                                    type:'POST',
                                    url: 'leave/updatepaymenttype',
                                    dataType: "json",
                                    data:{
                                      token: '<?php echo $this->security->get_csrf_hash();?>',
                                      userid:user_id,
                                      leavedate:leave_date,
                                      paymenttype:payment_type
                                      },
                                    success: function(data){
                                        console.log(data);
                                        if(data.error!=1)
                                        {
                                            if(data.cl_count>=0)
                                              {
                                                $('.total_leave').show();
                                                $('#leave_cl').html(data.cl_count);
                                                $('.cl').show(); 
                                               }
                                              if(data.ml_count>=0)
                                                {
                                                 $('.total_leave').show();
                                                  $('#leave_ml').html(data.ml_count);
                                                  $('.ml').show();
                                                  }
                                                  
                                                  alert(data.message);
                                         } 
                                        else if(data.error==1)
                                        {
                                           alert(data.message);
                                        }
                                    }
                                });
                          });

                    });

</script>

