$(document).ready(function(){
  /**
  **
  **  Design Animation JS
  **
  **/
  //Scroll window
  $(window).scroll(function() {
    if ($(document).scrollTop() > 20) {
      $("#show-menu-sm-div").css("top","5px");
      $("#control-menu").css("margin-top",0);
    } else {
      $("#show-menu-sm-div").css("top","75px");
      $("#control-menu").css("margin-top",75);
    };
  });
  //Window resize event
  $(window).resize(function(){
    if($(window).width()<576){
      $("#show-menu-div").fadeOut(200, function() {
        $("#show-menu-sm-div").fadeIn(200);
      });
    }else{
      $("#show-menu-sm-div").fadeOut(200, function() {
        $("#show-menu-div").fadeIn(200);
      });
    }
    //make width of profile img equals to height
    var photoHeight = $("img.profile").css("width");
    $("img.profile").css("height",photoHeight);
  });
  //make width of profile img equals to height
  var photoHeight = $("img.profile").css("width");
  $("img.profile").css("height",photoHeight);
  //tooltip
  $('[data-toggle="tooltip"]').tooltip();
  //Datepicker
  $("#dob").datepicker({
    dateFormat : "yy-mm-dd",
    minDate : "-150y",
    maxDate : -60,
    yearRange : "-120y:-1y",
    changeYear : true ,
    changeMonth : true,
    defaultDate : "-30y"
  });
  //hide and show search form in navbar
  $("#search-form").hide();
  $("#show-search-form").click(function(){
    $(this).fadeOut(200,function(){
      $("#search-form").show(200,function(){
        $("#search-input").focus();
      });
    });
  });
  $("#search-input").blur(function(){
    $("#search-form").delay(500).slideUp(200,function(){
      $("#show-search-form").fadeIn(500);
    });
  });
  //show modal to Logout
  $("#logout").click(function(e){
    e.preventDefault();
    $("#logout-modal").modal("show");
  });
  //show modal to delete
  $("#delete").click(function(e){
    e.preventDefault();
    $("#delete-modal").modal("show");
  });
  //show control menu
  $("#show-menu-div").mouseover(function(e){
    e.preventDefault();
    $("#menu-div").delay(250).slideDown(250);
  });
  $("#show-menu-sm-div").mouseover(function(e){
    e.preventDefault();
    $("#menu-div").delay(250).slideDown(250);
  });
  //close control menu
  $("#menu-div").click(function(e){
    e.stopPropagation();
    $("#menu-div").slideUp(500);
  });
  $("#control-menu").mouseleave(function(e){
    e.stopPropagation();
    $("#menu-div").slideUp(500);
  });
  $("#control-menu").click(function(e){
    e.stopPropagation();
  });
  //add the selected imagemap to the textarea
  $(".diagnose_map").click(function(e){
    e.preventDefault();
    var tooth_nr = $(this).attr("alt").split("_").pop().toUpperCase();
    var tooth_name;
    if(Number.isInteger(parseInt(tooth_nr))){
      console.log(tooth_nr);
      tooth_name=getToothName(parseInt(tooth_nr));
      console.log(tooth_nr);
    }else {
      tooth_name=tooth_nr;
    }
    if($("#diagnose").val()==""){
      $("#diagnose").val(tooth_name+" >>> ");
    }else{
      $("#diagnose").val($("#diagnose").val()+"\n"+tooth_name+" >>> ");
    }
  });
  //show file Name
  $(".custom-file-input").change(function(){
    $(".custom-file-label").text($(this).val().split("\\").pop());
    if(!validatePhoto($.trim($("#photo").val()))){
      assignError($("#photo"),"Please choose a enter a valid photo with 'gif','png','jpg' or 'jpeg' extension");
    }else {
      $(this).addClass("is-valid");
    }
  });
  /**
  **
  **  Validation JS
  **
  **/
  //Validate search
  $("#search-form").submit(function(e){
    $patient = $.trim($("#search-input").val());
    if (!validateNotEmpty($patient)){
      return false;
    }
    $(this).submit();
  });
  //Validate LOGIN
  $("#form-login").submit(function(e){
    $("div.invalid-feedback").remove();
    $("input.is-invalid").removeClass("is-invalid");
    var uname=$.trim($("#uname").val());
    var pass=$.trim($("#password").val());
    var check = true;
    if(!validateUname(uname)){
      assignError($("#uname"),"Please enter a valid username in the right format.<br>only these special characters are allowed - , _ and @");
      check=false;
    }
    if(!validateNotEmpty(pass)){
      assignError($("#password"),"Please enter a password");
      check=false;
    }
    if (check) {
      $(this).submit();
    }else {
      return false;
    }
  });
  function validatePatientForm(){
    $("div.invalid-feedback").remove();
    $("input.is-invalid").removeClass("is-invalid");
    var pname=$.trim($("#pname").val());
    var gender=$.trim($("input:radio[name=gender]:checked").val());
    var dob=$.trim($("#dob").val());
    var address=$.trim($("#address").val());
    var phone=$.trim($("#phone").val());
    var diabetes=$.trim($("input:radio[name=diabetes]:checked").val());
    var blood_pressure=$.trim($("input:radio[name=blood_pressure]:checked").val());
    var medical_compromise=$.trim($("#medical_compromise").val());
    var check = true;
    if(!validateAlphabet(pname)){
      assignError($("#pname"),"Please enter a valid name which contains only alphabets , spaces and _");
      check=false;
    }
    if(!validateDate(dob)){
      assignError($("#dob"),"Please enter a valid date in this format : yyyy-mm-dd");
      check=false;
    }
    if(gender != ""){
      if(!validateBoolEnum(gender)){
        assignError($("#div-gender"),"Please choose a valid gender");
        check=false;
      }
    }else{
      assignError($("#div-gender"),"Please choose choose a gender");
      check=false;
    }
    if(!validateNotEmpty(address)){
      assignError($("#address"),"Please enter an address");
      check=false;
    }
    if(!validatePhone(phone)){
      assignError($("#phone"),"Please enter a valid phone no. that contains only numbers and it can begin with a '+'");
      check=false;
    }
    if (diabetes!="") {
      if(!validateBoolEnum(diabetes)){
        assignError($("#div-diabetes"),"Please choose a valid diabetes value");
        check=false;
      }
    }else {
      assignError($("#div-diabetes"),"Please choose choose a diabetes value");
      check=false;
    }
    if(blood_pressure!=""){
      if(!validateBloodEnum(blood_pressure)){
        assignError($("#div_blood_pressure"),"Please choose a valid blood pressure value");
        check=false;
      }
    }else {
      assignError($("#div_blood_pressure"),"Please choose choose a blood pressure value");
      check=false;
    }
    return check;
  }
  //Validate Patient Creation
  $("#patient-create-form").submit(function(e){
    var check= validatePatientForm();
    if (check) {
      $(document).ajaxStart(function(){
        $("#loading").show();
        $(this).hide();
      });
      $(document).ajaxEnd(function(){
        $("#loading").hide();
        $(this).show();
      });
      $.ajax({
        "url" : $(this).attr('action'),
        "method" : "POST",
        "async" : true,
        "data" : $(this).serialize()
      });
    }else{
      return false;
    }
  });
  //validate Diagnosis
  $("#diagnose-form").submit(function(e){
    $("div.invalid-feedback").remove();
    $("input.is-invalid").removeClass("is-invalid");
    var check = true;
    var total_price=$("#total_price").val();
    var diagnose = $('#diagnose').val();
    if(validateNotEmpty(total_price)){
      if(!validateNumber(total_price)){
        $("#total_price").addClass("is-invalid");
        $(".input-group-append").after("<div style='display:block' class='invalid-feedback'>Please Enter a valid price number</div>");
        check=false;
      }
    }
    if(!validateNotEmpty(diagnose)){
      assignError($("#diagnose"),"You can't create an empty Diagnosis");
      check=false;
    }
    if (check) {
      $(document).ajaxStart(function(){
        $("#loading").show();
        $(this).hide();
      });
      $(document).ajaxEnd(function(){
        $("#loading").hide();
        $(this).show();
      });
      $.ajax({
        "url" : $(this).attr('action'),
        "method" : "POST",
        "async" : true,
        "data" : $(this).serialize()
      });
    }else{
      return false;
    }
  });
});
