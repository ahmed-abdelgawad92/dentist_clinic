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
        beforeSend: function(){
          $("#check_uname").addClass('input_indent');
          $("#check_uname").removeClass('input_indent_off');
          $("#loading").show().delay(10);
          $("#check_uname").attr('readonly', 'on');
        },
        complete: function(){
          $("#loading").hide();
          $("#check_uname").removeClass('input_indent').delay(10);
          $("#check_uname").addClass('input_indent_off').delay(10);
          $("#check_uname").attr('readonly',false).delay(100);
        },
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
  $("#check_uname").on("keyup",function(e) {
    if(timeout) {
        clearTimeout(timeout);
    }
    username= $(this);
    timeout = setTimeout(function() {
        checkBeforeAjax(username);
    }, delay);
  });
  $("#check_uname").on("blur",function(e) {
    if(timeout){
      username= $(this);
      timeout = setTimeout(function() {
          checkBeforeAjax(username);
      }, delay);
    }
  });


/*****************************************************************************************************************************************/
  /*
  **
  ** Validate every field on key up by User Creation
  **
  */
  //name validation
  $("#admin_name").on("blur",function(e) {
    var name= $(this);
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
  });
  // Password Validation
  $("#admin_password").on("blur",function(e) {
    var password= $(this);
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
  });
  //Password Confirmation
  $("#admin_confirm_password").on("blur",function(e) {
    var confirm_password= $(this);
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
  });
  //Phone Validation
  $("#admin_phone").on("blur",function(e) {
    var phone= $(this);
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
  });
  //Role Validation
  $("#admin_role").on("blur",function(e) {
    var role= $(this);
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
      $(this).children('button').attr('disabled', 'disabled');

      if(!$.active){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });
        $.ajax({
          url : $("#create_user_form").attr('action'),
          method : "POST",
          async : true,
          data:  new FormData(this),
          contentType: false,
          cache: false,
          processData:false,
          beforeSend: function(){
            $("#loading_button").css("display","inline");
          },
          complete: function(){
            $("#loading_button").css("display","none");
            $("#create_user_form").children('button').attr('disabled', false);
          },
          success : function(data){
            var response=$.parseJSON(data);
            if(response.state=='OK'){
              var success_div='<div class="alert alert-success alert-dismissible fade show">';
              success_div+='<h4 class="alert-heading">Completed Successfully</h4>'+JSON.stringify(response.success).slice(1,-1);;
              success_div+='<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>';
              success_div+='</div>';
              $("div.card-body").prepend(success_div);
              setTimeout(function () {
                window.location.href= JSON.stringify(response.route).slice(JSON.stringify(response.route).search("user")+5,-1);
              }, 1000);
            }
          },
          error : function(data){
            alert(data);
          }
        });
      }
    }else{
      return false;
    }
  });



/*****************************************************************************************************************************************/
    /*
    **
    **change Password form
    **
    */
    $("#change_password_form").submit(function(e){
      $(".is-invalid").removeClass("is-invalid");
      $(".invalid-feedback").remove();
      var check= true;
      if(!validateNotEmpty($("#old_password").val())){
        check= false;
        assignError($("#old_password"),"Please enter your current password");
      }
      if(!validatePassword($("#new_password").val())){
        check= false;
        assignError($("#new_password"),"Password must contain at least 8 characters, one Uppercase letter, one Lowercase letter, a number, and a special character (#,?,!,@,$,%,^,&,* or -)");
      }
      if ($("#new_password").val()!=$("#confirm_new_password").val()) {
        check= false;
        assignError($("#confirm_new_password"),"Passwords don't match");
      }
      if(!check){
        e.preventDefault();
        return false;
      }else{
        $(this).submit();
      }
    });




/*****************************************************************************************************************************************/
    /*
    **
    ** Edit User form
    **
    */
    $("#edit_user_form").submit(function(e){
      $(".is-invalid").removeClass("is-invalid");
      $(".invalid-feedback").remove();
      var check= true;
      if(!validateName($("#name").val())){
        check= false;
        assignError($("#name"),"Please enter a valid Name that contains only alphabets , spaces and _ ");
      }
      if (!validatePhone($("#phone").val())) {
        assignError($("#phone"),"Please enter a valid Phone No. that contains only numbers and can start with a (+)");
        check=false;
      }
      if (!validateBoolEnum($("#role").val())) {
        assignError($("#role"),"Please Select a valid role");
        check=false;
      }
      if(!check){
        e.preventDefault();
        return false;
      }else{
        $(this).submit();
      }
    });


/*****************************************************************************************************************************************/
    /*
    **
    ** Search user
    **
    */
    function searchAjax(search_user) {
      console.log(search_user.val());

      if(!$.active){
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          url : $('#search_user_form').attr("action"),
          method : "POST",
          data : {
            'search_user': search_user.val().trim()
          },
          async : true,
          beforeSend: function(){
            $("table").hide();
            $("#loading").show();
          },
          complete: function(){
            $("table").show();
            $("#loading").hide();
          },
          success :function(data){
            var state=$.parseJSON(data);
            $("div.alert").remove();
            if(state.state=='OK'){
              //display users
              var count = 1;
              $("#user-table").html("");
              for (var user of state.users) {
                var id=JSON.stringify(user.id);
                var name=JSON.stringify(user.name).slice(1,-1);
                var uname=JSON.stringify(user.uname).slice(1,-1);
                var phone=JSON.stringify(user.phone).slice(1,-1);
                var role_num=user.role;
                console.log(role_num);
                var role="";
                if (role_num==0) {
                  role="User";
                }else{
                  role="Admin";
                }
                var tr="<tr><td>"+count+"</td><td><a href='/user/profile/"+id+"'>"+name+"</td><td>";
                tr+=uname+"</td><td>"+role+"</td><td>"+phone+"</td><td>";
                tr+="<a href='/user/log/"+id+"' class='btn btn-home'>User's Logs</a></td><td>";
                tr+="<a href='/user/edit/"+id+"' class='btn btn-secondary'>edit <span class='glyphicon glyphicon-edit'></span></a></td><td>";
                tr+="<a href='/user/delete/"+id+"' class='btn btn-danger'>delete <span class='glyphicon glyphicon-trash'></span></a></td></tr>";
                $("#user-table").append(tr);
                count++;
              }
            }else{
              //add errors
              var errors = $.parseJSON(data);
              $("table").hide();
              $("#search_user_form").after("<div class='alert alert-danger'>"+errors.error+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
            }
          },
          error : function(data){
            alert(data);
          }
        });
      }
    }

    $("#search_user").on("keyup",function(e) {
      if(timeout) {
          clearTimeout(timeout);
      }
      search_user= $(this);
      timeout = setTimeout(function() {
          searchAjax(search_user);
      }, delay);
    });
    $("#search_user_form").submit(function(e){
      e.preventDefault();
      searchAjax(search_user);
    });
/*****************************************************************************************************************************************/
    /*
    **
    ** Delete user
    **
    */
    $("#user-table").on("click",".delete_user",function(e){
      e.preventDefault();
      $(".float_form_container").show();
      $("#delete_user").show();
      $("#delete_user a.btn-danger").attr("href",$(this).attr("href"));
    });
});
