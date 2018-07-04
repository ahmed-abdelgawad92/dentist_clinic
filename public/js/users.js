$(document).ready(function() {
  /*
  **
  ** Check if username is existing
  **
  */
  var timeout;
  var delay = 1000;   // 2 seconds

  function checkUnameAjax(username) {
    console.log(username.val());

    if(!$.active){
      $(document).ajaxStart(function(){
        $("#loading").show();
        username.attr('readonly', 'on');
      });
      $(document).ajaxStop(function(){
        $("#loading").hide();
        username.attr('readonly',false).delay(500);
      });
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $.ajax({
        url : username.attr('data-action'),
        method : "POST",
        data : {
          'uname': username.val().trim()
        },
        async : true,
        success :function(data){
          username.siblings(".invalid-feedback, .valid-feedback").remove();
          username.removeClass('is-valid');
          username.removeClass('is-invalid');
          var state=$.parseJSON(data);
          if(state.state=='OK'){
            username.addClass('is-valid');
            username.after('<div class="valid-feedback">This Username is available</div>');
          }else{
            username.addClass('is-invalid');
            var errors = $.parseJSON(data);
            var error= JSON.stringify(errors.error.uname[0]).slice(1,-1);
            username.after('<div class="invalid-feedback">'+error+'</div>');
          }
        },
        error : function(data){
          alert(data);
        }
      });
    }
  }
  function checkBeforeAjax(username){
    console.log("User started typing!");
    username.siblings(".invalid-feedback, .valid-feedback").remove();
    username.removeClass('is-valid');
    username.removeClass('is-invalid');
    if ($.trim(validateUname(username.val()))) {
      if(timeout) {
          clearTimeout(timeout);
      }
      timeout = setTimeout(function() {
          checkUnameAjax(username);
      }, delay);
    }else {
      clearTimeout(timeout);
      assignError(username,"Please enter a valid Username that contains only alphabets, numbers, . , @ , _ or -, and not less than 3 alphabets, and starts with at least one alphabet");
    }
  }
  $("#check_uname").keyup(function(e) {
    if(timeout) {
        clearTimeout(timeout);
    }
    username= $(this);
    timeout = setTimeout(function() {
        checkBeforeAjax(username);
    }, delay);
  });
  $("#check_uname").focus(function(){
    $(this).addClass('input_indent');
    $(this).removeClass('input_indent_off');
  });
  $("#check_uname").blur(function(){
    $(this).removeClass('input_indent');
    $(this).addClass('input_indent_off');
  });


/*****************************************************************************************************************************************/
  /*
  **
  ** Validate every field on key up by User Creation
  **
  */
  //name validation
  $("#admin_name").on("keyup change",function(e) {
    if(timeout) {
        clearTimeout(timeout);
    }
    var name= $(this);
    timeout = setTimeout(function() {
      name.siblings(".invalid-feedback").remove();
      name.removeClass('is-valid');
      name.removeClass('is-invalid');
      if(validateNotEmpty(name.val())){
        if(validateName(name.val())){
          name.addClass('is-valid');
        }else{
          assignError(name,"Please enter a valid Name that contains only alphabets , spaces and _ ");
        }
      }else {
        assignError(name,"Please Enter the User's Full Name");
      }
    }, delay);
  });
  // Password Validation
  $("#admin_password").on("keyup change",function(e) {
    if(timeout) {
        clearTimeout(timeout);
    }
    var password= $(this);
    timeout = setTimeout(function() {
      password.siblings(".invalid-feedback").remove();
      password.removeClass('is-valid');
      password.removeClass('is-invalid');
      if(validateNotEmpty(password.val())){
        if(validatePassword(password.val())){
          password.addClass('is-valid');
        }else{
          assignError(password,"Password must contain at least one Uppercase letter, one Lowercase letter, a number, and a special character (#,?,!,@,$,%,^,&,* or -)");
        }
      }else {
        assignError(password,"Please Enter Password");
      }
    }, delay);
  });
  //Password Confirmation
  $("#admin_confirm_password").on("keyup change",function(e) {
    if(timeout) {
        clearTimeout(timeout);
    }
    var confirm_password= $(this);
    timeout = setTimeout(function() {
      confirm_password.siblings(".invalid-feedback").remove();
      confirm_password.removeClass('is-valid');
      confirm_password.removeClass('is-invalid');
      if(validateNotEmpty(confirm_password.val())){
        if(confirm_password.val()==$("#admin_password").val()){
          confirm_password.addClass('is-valid');
        }else{
          assignError(confirm_password,"Passwords don't match");
        }
      }else {
        assignError(confirm_password,"Please Re-type the password");
      }
    }, delay);
  });
  //Phone Validation
  $("#admin_phone").on("keyup change",function(e) {
    if(timeout) {
        clearTimeout(timeout);
    }
    var phone= $(this);
    timeout = setTimeout(function() {
      phone.siblings(".invalid-feedback").remove();
      phone.removeClass('is-valid');
      phone.removeClass('is-invalid');
      if(validateNotEmpty(phone.val())){
        if(validatePhone(phone.val())){
          phone.addClass('is-valid');
        }else{
          assignError(phone,"Please enter a valid Phone No. that contains only numbers and can start with a (+)");
        }
      }else {
        assignError(phone,"Please Enter Phone No.");
      }
    }, delay);
  });
  //Role Validation
  $("#admin_role").on("keyup change",function(e) {
    if(timeout) {
        clearTimeout(timeout);
    }
    var role= $(this);
    timeout = setTimeout(function() {
      role.siblings(".invalid-feedback").remove();
      role.removeClass('is-valid');
      role.removeClass('is-invalid');
      if(validateNotEmpty(role.val())){
        if(validateBoolEnum(role.val())){
          role.addClass('is-valid');
        }else{
          assignError(role,"Please Select a valid role");
        }
      }else {
        assignError(role,"Please Select a valid role");
      }
    }, delay);
  });
  //show file Name
  $(".custom-file-input").change(function(){
    $(".custom-file-label").text($(this).val().split("\\").pop());
    if(!validatePhoto($.trim($("#admin_photo").val()))){
      assignError($("#admin_photo"),"Please choose a valid photo with 'gif','png','jpg' or 'jpeg' extension");
    }else {
      $(this).addClass("is-valid");
    }
  });


/*****************************************************************************************************************************************/
  /*
  **
  ** Validate Form Submition by User Creation
  **
  */
  function validateUserForm(){
    $(".invalid-feedback").remove();
    $(".is-invalid").removeClass('is-invalid');
    var admin_name = $.trim($("#admin_name").val());
    var admin_uname = $.trim($("#check_uname").val());
    var admin_password = $.trim($("#admin_password").val());
    var admin_confirm_password = $.trim($("#admin_confirm_password").val());
    var admin_phone = $.trim($("#admin_phone").val());
    var admin_role = $.trim($("#admin_role").val());
    var admin_photo = $.trim($("#admin_photo").val());
    var check = true;
    if (!validateName(admin_name)) {
      assignError($("#admin_name"),"Please enter a valid Name that contains only alphabets , spaces and _ ");
      check=false;
    }
    if (!validateUname(admin_uname)) {
      assignError($("#check_uname"),"Please enter a valid Username that contains only alphabets, numbers, . , @ , _ or -, and not less than 3 alphabets, and starts with at least one alphabet");
      check=false;
    }
    if (!validatePassword(admin_password)) {
      assignError($("#admin_password"),"Password must contain at least one Uppercase letter, one Lowercase letter, a number, and a special character (#,?,!,@,$,%,^,&,* or -)");
      check=false;
    }
    if (admin_confirm_password!=admin_password) {
      assignError($("#admin_confirm_password"),"Passwords don't match");
      check=false;
    }
    if (!validatePhone(admin_phone)) {
      assignError($("#admin_phone"),"Please enter a valid Phone No. that contains only numbers and can start with a (+)");
      check=false;
    }
    if (!validateBoolEnum(admin_role)) {
      assignError($("#admin_role"),"Please Select a valid role");
      check=false;
    }
    if (validateNotEmpty(admin_photo)) {
      if (!validatePhoto(admin_photo)) {
        assignError($("#admin_photo"),"Please choose a valid photo with 'gif','png','jpg' or 'jpeg' extension");
        check=false;
      }
    }
    return check;
  }
  $("#create_user_form").submit(function(e){
    e.preventDefault();
    if(validateUserForm()){
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
    /*
    **
    **
    **
    */

});
