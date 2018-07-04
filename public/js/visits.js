$(document).ready(function() {
  //Datepicker
  $("#visit_date").datepicker({
    dateFormat : "yy-mm-dd",
    minDate : 0,
    changeYear : true ,
    changeMonth : true,
  });


/*****************************************************************************************************************************************/


  //Time Picker
  var hour;
  var minute;
  var am_pm;
  $('#add_visit').on('keyup keypress', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
      e.preventDefault();
      return false;
    }
  });
  $("#visit_time").click(function(e){
    e.stopPropagation();
    var time=$(this).val();
    am_pm=time.split(" ").pop().toLowerCase();
    var hM=time.split(" ").slice(0,1).pop().split(":");
    hour=hM[0];
    minute=hM[1];
    $("#hour").val(hour);
    $("#minute").val(minute);
    $("#am_pm").val(am_pm);
    $("div.timepicker").slideDown(300,function(){
      $(this).css('display', 'flex');
    });
  });
  $("div.timepicker").click(function(e){
    e.stopPropagation();
  });
  $("#add_visit").click(function(){
    $("div.timepicker").slideUp(300);
    if (isNaN(parseInt(hour))) {
      hour="00";
    }
    if (isNaN(parseInt(minute))) {
      minute="00";
    }
    var time= parseInt(hour)+":"+parseInt(minute);
    if(am_pm!="pm" && am_pm!="am"){
      time+=" pm";
    }else {
      time+=" "+am_pm;
    }
    $("#visit_time").val(time);
  });
  $("#hour").change(function(){
    if($("#hour").val()>12){
      hour=12;
      $("#hour").val(12);
    }else {
      hour=$("#hour").val();
    }
  });
  $("#hour_up").click(function(){
    if ($("#hour").val()==12) {
      hour=1;
      $("#hour").val(hour);
    }else if ($("#hour").val()==11) {
      $("#hour").val(++hour);
      if($("#am_pm").val()=="pm"){
        am_pm="am";
        $("#am_pm").val(am_pm);
      }else{
        am_pm="pm";
        $("#am_pm").val(am_pm);
      }
    }else{
      $("#hour").val(++hour);
    }
  });
  $("#hour_down").click(function(){
    if ($("#hour").val()==12) {
      $("#hour").val(--hour);
      if($("#am_pm").val()=="pm"){
        am_pm="am";
        $("#am_pm").val(am_pm);
      }else{
        am_pm="pm";
        $("#am_pm").val(am_pm);
      }
    }else if ($("#hour").val()==1) {
      hour=12;
      $("#hour").val(hour);
    }else{
      $("#hour").val(--hour);
    }
  });
  $("#minute").change(function(){
    if($("#minute").val()>60){
      minute="00";
      $("#minute").val("00");
    }else {
      minute=$("#minute").val();
    }
  });
  $("#minute_up").click(function(){
    if ($("#minute").val()==60 || $("#minute").val()>60) {
      minute=0;
      $("#minute").val(minute);
    }else {
      $("#minute").val(++minute);
    }
  });
  $("#minute_down").click(function(){
    if ($("#minute").val()==0) {
      minute=60;
      $("#minute").val(minute);
    }else {
      $("#minute").val(--minute);
    }
  });
  $("#am_pm").change(function(){
    am_pm=$("#am_pm").val();
  });
  $("#am_pm_up, #am_pm_down").click(function(){
    if ($("#am_pm").val()=="pm") {
      am_pm="am";
      $("#am_pm").val(am_pm);
    }else{
      am_pm="pm";
      $("#am_pm").val(am_pm);
    }
  });



/*****************************************************************************************************************************************/


});
