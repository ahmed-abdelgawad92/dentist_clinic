$(document).ready(function() {
  /*
  **
  ** Show Datepicker within patient creation form
  **
  */
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
      $(document).ajaxStop(function(){
        $("#loading").hide();
        $(this).show();
      });
      $.ajax({
        url : $(this).attr('action'),
        method : "POST",
        async : true,
        data : $(this).serialize()
      });
    }else{
      return false;
    }
  });



/*****************************************************************************************************************************************/
});
