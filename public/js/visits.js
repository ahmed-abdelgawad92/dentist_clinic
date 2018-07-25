$(document).ready(function() {
  //Datepicker
  $("#visit_date").datepicker({
    dateFormat : "yy-mm-dd",
    minDate : 0,
    changeYear : true ,
    changeMonth : true
  });

  $("#search_visit").datepicker({
    dateFormat : "yy-mm-dd",
    changeYear : true ,
    changeMonth : true
  });
  $("#search_visit").change(function(e){
    $(this).focus();
  });
  $("#search_visit_form").submit(function(e){
    $(".is-invalid").removeClass('is-invalid');
    $('.invalid-feedback').remove();
    if (!validateDate($("#search_visit").val().trim())) {
      e.preventDefault();
      assignError($("#search_visit"),"Please enter a valid date");
      return false;
    }
    $(this).attr('action', '/patient/diagnosis/visit/all/'+$("#search_visit").val().trim());
    $(this).submit();
  });
/*****************************************************************************************************************************************/
  /*
  *
  *
  * Check for appointments in a specific date
  *
  */
  $("#visit_date").change(function(e){
    $(".is-invalid").removeClass('is-invalid');
    $(".is-valid").removeClass('is-valid');
    $("#add_visit div.alert, .invalid-feedback").remove();
    if (validateDate($(this).val())) {
      var visit_date=$(this).val();
      if(!$.active){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
          url: '/patient/diagnosis/visit/avaliable/visits',
          type: 'POST',
          dataType: 'JSON',
          data: {'visit_date': visit_date},
          async: true,
          beforeSend : function(){
            $("#loading").show();
            $("#add_visit_form").hide();
            $('.alert-warning, .alert-danger').remove();
          },
          complete : function(){
            $("#loading").hide();
            $("#add_visit_form").show();
          },
          success :function(response){
            var data = response;
            if(data.state=='OK'){
              if(Object.keys(data.available_appointments).length>0){
                $("#visit_time").html('<option value="">Here is Available Visits</option>');
                for (var time in data.available_appointments) {
                  $("#visit_time").append('<option value="'+data.available_appointments[time]+'">'+data.available_appointments[time]+'</option>');
                }
                $("#visit_time").addClass('is-valid');
              }else{
                assignError($("#visit_time"),"Sorry there is no available visits at this date");
                $("#visit_time").html('<option value="">There is no Available Visits, Choose other date</option>');
              }
            }else{
              console.log(data.error);
              $("#visit_time").html('<option value="">check error</option>');
              $("#add_visit_form h4, #edit_visit_form h4").after("<div class='alert alert-danger'>Error : "+data.error+"</div>");
            }
          },
          error : function(data){
            console.log(data);
          }
        });
      }
    }else {
      assignError($(this),'Please select a valid date in the format (YYYY-MM-DD)');
      return false;
    }
  });




/*****************************************************************************************************************************************/
  /*
  *
  *
  * Check for appointments in a specific date
  *
  */
  $("#add_visit_form,#edit_visit_form").submit(function(e){
    $(".invalid-feedback").remove();
    $('.is-invalid').removeClass('is-invalid');
    var visit_date=$("#visit_date").val().trim();
    var visit_time=$("#visit_time").val().trim().split(' ').shift();
    if(!validateDate(visit_date)){
      assignError($('#visit_date'),"Please select a valid date");
      e.preventDefault();
    }
    if(!validateTime(visit_time)){
      assignError($('#visit_time'),"Please select a valid time");
      e.preventDefault();
    }
    if(!validateNotEmpty($('#visit_treatment').val().trim())){
      assignError($('#visit_treatment'),"Please write down treatment");
      e.preventDefault();
    }
  });







/*****************************************************************************************************************************************/

  //Time Picker
  // var hour;
  // var minute;
  // var am_pm;
  // $('.timepicker_form').on('keyup keypress', function(e) {
  //   var keyCode = e.keyCode || e.which;
  //   if (keyCode === 13) {
  //     e.preventDefault();
  //     return false;
  //   }
  // });
  // $(".timepicker_input").click(function(e){
  //   e.stopPropagation();
  //   var time=$(this).val();
  //   am_pm=time.split(" ").pop().toLowerCase();
  //   var hM=time.split(" ").slice(0,1).pop().split(":");
  //   hour=hM[0];
  //   minute=hM[1];
  //   $(this).siblings(".timepicker .hour").val(hour);
  //   $(this).siblings(".timepicker .minute").val(minute);
  //   $(this).siblings(".timepicker .am_pm").val(am_pm);
  //   $(this).siblings('.timepicker').slideDown(300,function(){
  //     $(this).css('display', 'flex');
  //   });
  // });
  // $("div.timepicker").click(function(e){
  //   e.stopPropagation();
  // });
  // $("form").click(function(){
  //   $("div.timepicker").slideUp(300);
  //   if (isNaN(parseInt(hour))) {
  //     hour="00";
  //   }
  //   if (isNaN(parseInt(minute))) {
  //     minute="00";
  //   }
  //   var time= parseInt(hour)+":"+parseInt(minute);
  //   if(am_pm!="pm" && am_pm!="am"){
  //     time+=" pm";
  //   }else {
  //     time+=" "+am_pm;
  //   }
  //   $(".visit_time").val(time);
  // });
  // $(".hour").change(function(){
  //   if($(".hour").val()>12){
  //     hour=12;
  //     $(".hour").val(12);
  //   }else {
  //     hour=$(".hour").val();
  //   }
  // });
  // $(".hour_up").click(function(){
  //   if ($(".hour").val()==12) {
  //     hour=1;
  //     $(".hour").val(hour);
  //   }else if ($(".hour").val()==11) {
  //     $(".hour").val(++hour);
  //     if($(".am_pm").val()=="pm"){
  //       am_pm="am";
  //       $(".am_pm").val(am_pm);
  //     }else{
  //       am_pm="pm";
  //       $(".am_pm").val(am_pm);
  //     }
  //   }else{
  //     $(".hour").val(++hour);
  //   }
  // });
  // $(".hour_down").click(function(){
  //   if ($(".hour").val()==12) {
  //     $(".hour").val(--hour);
  //     if($(".am_pm").val()=="pm"){
  //       am_pm="am";
  //       $(".am_pm").val(am_pm);
  //     }else{
  //       am_pm="pm";
  //       $(".am_pm").val(am_pm);
  //     }
  //   }else if ($(".hour").val()==1) {
  //     hour=12;
  //     $(".hour").val(hour);
  //   }else{
  //     $(".hour").val(--hour);
  //   }
  // });
  // $(".minute").change(function(){
  //   if($(".minute").val()>60){
  //     minute="00";
  //     $(".minute").val("00");
  //   }else {
  //     minute=$(".minute").val();
  //   }
  // });
  // $(".minute_up").click(function(){
  //   if ($(".minute").val()==60 || $(".minute").val()>60) {
  //     minute=0;
  //     $(".minute").val(minute);
  //   }else {
  //     $(".minute").val(++minute);
  //   }
  // });
  // $(".minute_down").click(function(){
  //   if ($(".minute").val()==0) {
  //     minute=60;
  //     $(".minute").val(minute);
  //   }else {
  //     $(".minute").val(--minute);
  //   }
  // });
  // $(".am_pm").change(function(){
  //   am_pm=$(".am_pm").val();
  // });
  // $(".am_pm_up, .am_pm_down").click(function(){
  //   if ($(".am_pm").val()=="pm") {
  //     am_pm="am";
  //     $(".am_pm").val(am_pm);
  //   }else{
  //     am_pm="pm";
  //     $(".am_pm").val(am_pm);
  //   }
  // });



/*****************************************************************************************************************************************/


});
