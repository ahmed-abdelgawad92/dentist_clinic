$(document).ready(function(){
  $("#dob").datepicker({
    minDate : "-150y",
    maxDate : -60,
    yearRange : "-120y:-1y",
    changeYear : true ,
    changeMonth : true,
    defaultDate : "-30y"
  });
  $("#search-form").hide();
  $("#show-search-form").click(function(){
    $(this).fadeOut(200,function(){
      $("#search-form").show(200,function(){
        $("#search-input").focus();
      });
    });
  });
  $("#search-input").blur(function(){
    $("#search-form").slideUp(200,function(){
      $("#show-search-form").fadeIn(500);
    });
  });
});
