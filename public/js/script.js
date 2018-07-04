$(document).ready(function(){
  /**
  **
  **  Design Animation JS
  **
  **/

/*****************************************************************************************************************************************/


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


/*****************************************************************************************************************************************/



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


/*****************************************************************************************************************************************/


  //make width of profile img equals to height
  var photoHeight = $("img.profile").css("width");
  $("img.profile").css("height",photoHeight);
  //print Prescription
  $('#print').click(function(e){
    e.preventDefault();
    window.print();
  });


/*****************************************************************************************************************************************/

  //tooltip
  $('[data-toggle="tooltip"]').tooltip();

/*****************************************************************************************************************************************/



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


/*****************************************************************************************************************************************/



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



/*****************************************************************************************************************************************/



  //show control menu within the navbar
  $("#show-menu-div").mouseover(function(e){
    e.preventDefault();
    $("#menu-div").delay(250).slideDown(250);
  });
  $("#show-menu-sm-div").mouseover(function(e){
    e.preventDefault();
    $("#menu-div").delay(250).slideDown(250);
  });
  //close control menu within the navbar
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



/*****************************************************************************************************************************************/


  //show barcode on prescription
  JsBarcode("#barcode").init();


/*****************************************************************************************************************************************/

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


/*****************************************************************************************************************************************/


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


/*****************************************************************************************************************************************/
});
