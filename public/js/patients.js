$(document).ready(function() {
  /*
  **
  ** Show Datepicker within patient creation form
  **
  */
  //Datepicker
  // $("#dob").datepicker({
  //   dateFormat : "yy-mm-dd",
  //   minDate : "-150y",
  //   maxDate : -60,
  //   yearRange : "-120y:-1y",
  //   changeYear : true ,
  //   changeMonth : true,
  //   defaultDate : "-30y"
  // });


/*****************************************************************************************************************************************/

/*
**
** show last and next visit
**
*/
$(".display_visit").click(function(event) {
  event.preventDefault();
  $('.float_form_container').show();
  $('#show_visit').show();
  $("body").css('overflow-y', 'hidden');
  var time= $(this).attr('data-time');
  var date= $(this).attr('data-date');
  var treatment= $(this).attr('data-treatment');
  var day=$(this).attr('data-day');
  var day_nr=$(this).attr('data-day-nr');
  $('.data-visit').html('<h4 class="mb-3"><div class="calendar-date"><div class="calendar-day">'+day+'</div><div class="calendar-day-nr">'+day_nr+'</div></div>'+date+"<br>"+time+"</h4>");
  $('.data-visit').append('<h4 class="treatment_align">Treatment<br>'+treatment+'</h4>');
});


/*****************************************************************************************************************************************/

/*
**
** Print Barcode
**
*/
$(".print-div").hide();
$("#print_barcode").click(function(){
  $(".card").hide();
  $(".print-div").show();
  window.print();
  $(".card").show();
  $(".print-div").hide();
});
/*****************************************************************************************************************************************/

/*
**
** Upload patient profile photo
**
*/
$("#photo").change(function(){
  $("#change_profile_pic label").text($(this).val().split("\\").pop());
  $("#change_profile_pic").slideDown(300);
  $(this).blur();
  readURL(this);
});
$("#upload_new_photo").click(function(){
  if(!validatePhoto($.trim($("#photo").val()))){
    alert("Please choose a valid photo with 'gif','png','jpg' or 'jpeg' extension");
  }else {
    $("#change_profile_pic_form").submit();
  }
});


/*****************************************************************************************************************************************/



  //show file Name
  $(".custom-file-input").change(function(){
    $(".custom-file-label").text($(this).val().split("\\").pop());
    if(!validatePhoto($.trim($("#photo").val()))){
      assignError($("#photo"),"Please choose a valid photo with 'gif','png','jpg' or 'jpeg' extension");
    }else {
      $(this).addClass("is-valid");
    }
  });


/*****************************************************************************************************************************************/

  /*
  **
  ** Validate Patient Form before submitting
  **
  */
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
    if(!validateNumber(dob)){
      assignError($("#dob"),"Please enter a valid age");
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
      $(this).submit();
    }else{
      e.preventDefault();
      return false;
    }
  });



/*****************************************************************************************************************************************/
});
