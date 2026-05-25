<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-users"></i><?= lang('Staff_Attendance'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
          <div class="col-md-4">
              <div class="form-group">
                <label><?= lang("Name"); ?></label>
                <?php echo form_input('name',(isset($_POST['name']) ? $_POST['name'] : ''),'class="form-control input-tip" id="uname"'); ?>
                <input type="button" id="this_month" class="btn btn-primary" value="This Month" style="padding: 6px 15px; margin:15px 0;">
                 </div>
            </div>
            
             <div class="col-md-8">
               <div class="col-md-2">
               <label><?= lang("custom_range"); ?></label>
              </div>
              <div class="col-md-3">
             <label><?= lang("from"); ?></label>
             <div class="form-group">
              <?php echo form_input('fromdate',(isset($_POST['fromdate']) ? $_POST['fromdate'] : ''),'class="form-control input-tip date" id="fromdate"'); ?>
            </div>
          </div>
           <div class="col-md-3">
              <label><?= lang("To"); ?></label>
                <div class="form-group">
             <?php echo form_input('dateto',(isset($_POST['dateto']) ? $_POST['dateto'] : ''),'class="form-control input-tip date" id="dateto"'); ?>
                </div>
              </div>
                <div class="col-md-2">
                  <label></label>
               <input type="button" id="search" class="btn btn-primary" value="Search" style="padding: 6px 15px; margin:15px 0;">
               
                </div>
            	</div>
             
            </div>
        </div>
        <div class="row" style="text-align: center;" id="staff_name"></div>
        <div class="row">
<table id="domainDataTable" class="table" style="display:none">
    <tr>
        <th>Date</th>
        <th>In Time</th>
        <th>Out Time</th>
        <th>Hours</th>
        <th>Type</th>
    </tr>
<tbody id="staff_data_table">
</tbody>
</table>
</div>
</div>
</div>
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
   var uid = $('#uname').val();
     $(document).on('click','#this_month',function (e) {
       //$('.table-responsive').css("display","block");
     var uid = $('#uname').val();
     if(uid!=''){
        //alert(uid);
         $.ajax({
                    type:'GET',
                    url: 'staff/checkstaffattendance/'+uid,
                    dataType: "json",
                    data:{this_month:1},
                    success: function (data) {
                         console.log(data);
                      $('#staff_name').html('<p>'+data.staff_name+'</p>');
                      if(data!='')
                      {
                        $('#staff_data_table').html('');
                   $('#domainDataTable').show();

              $.each(data.attendance_details, function() {
                var row = $("<tr><td>" +this.attn_date + "</td><td>"+this.in_time+"</td><td>"+this.out_time +"</td><td>"+this.hours+"</td><td>"+this.type+ "</td></tr>");
                  $("#staff_data_table").append(row);
                     });
                  }
          }
                });
     }
        
    });


 $(document).on('click','#search',function (e) {
   
    
     var uid = $('#uname').val();
      //$('.table-responsive').css("display","block");
     if(uid!='' && $('#dateto').val()!='' && $('#fromdate').val()!='' && $('#dateto').val()>$('#fromdate').val()){
      
         $.ajax({
                    type:'POST',
                    url: 'staff/checkstaffattendance/'+uid,
                    dataType: "json",
                    data:{
                         token: '<?php echo $this->security->get_csrf_hash();?>',
                        from:$('#fromdate').val(),
                        to:$('#dateto').val()
                    },
                    success: function (data) {
                        console.log(data);
                      
                          $('#staff_name').html('<p>'+data.staff_name+'</p>');
                      if(data!='')
                      {
                        $('#staff_data_table').html('');
                   $('#domainDataTable').show();


                         $.each(data.attendance_details, function() {
                        var row = $("<tr><td>" +this.attn_date + "</td><td>"+this.in_time+"</td><td>"+this.out_time +"</td><td>"+this.hours+"</td><td>"+this.type+ "</td></tr>");
                            $("#staff_data_table").append(row);
                        });
                     }
                 }
                });
     }   
    });

});
</script>