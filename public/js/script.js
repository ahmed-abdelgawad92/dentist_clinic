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
  //print Prescription
  $('#print').click(function(e){
    e.preventDefault();
    window.print();
  });
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
  //open float form
  $(".action").click(function(e){
    e.preventDefault();
    var data_action= $(this).attr('data-action');
    $(".float_form_container").show();
    $(data_action).show();
    if(data_action=="#delete_diagnosis"){
        $(data_action+" a.btn-danger").attr("href",$(this).attr('data-url'));
    }
    else {
      $(data_action+" form").attr("action",$(this).attr('data-url'));
    }
    $("body").css('overflow-y', 'hidden');
  });
  //close float form
  $("span.close, button.close_button").click(function(){
    $(".float_form_container").hide();
    $(".float_form").hide();
    $("body").css('overflow-y', 'auto');
  });
  $(".float_form_container").click(function(){
    $(".float_form").hide();
    $(this).hide();
    $("body").css('overflow-y', 'auto');
  });
  $(".float_form").click(function(e){
    e.stopPropagation();
  });
  var no_of_drug=1;
  //add new drug input
  $("#add_new_drug").click(function(){
    $("#new_drug").append('<div class="col-12 center mb-3">Drug '+(++no_of_drug)+'</div><div class="form-group row">'+$('.drug_input').html()+"</div>");
  });
  //add new drug only to print
  $("#show_prescription_form").click(function(e){
    e.preventDefault();
    $(".float_form_container").show();
    $("#add_drug").show();
    $("#drug, #dose").val("");
    $("#drug").focus();
  });
  var counter=0;
  $("#add_drug_to_prescription").click(function(){
    counter++;
    var drug = $("#drug").val();
    var dose = $("#dose").val();
    $("table").append('<tr class="drug_js_'+counter+'" ><td>'+drug+'</td><td>'+dose+'</td><td><button class="btn btn-danger delete_drug" id="js_'+counter+'">Remove it just during print</button></td><td></td><td></td></tr>')
    //delete a drug before printing
    $(".delete_drug").on("click",function(e){
      var id = $(this).attr("id");
      $(".drug_"+id).remove();
      $(this).remove();
    });
    $("span.close").trigger("click");
  });
  //delete a drug before printing
  $(".delete_drug").on("click",function(e){
    var id = $(this).attr("id");
    $(".drug_"+id).remove();
    $(this).remove();
  });
  //show barcode on prescription
  JsBarcode("#barcode").init();
  //add the selected imagemap to the textarea
  var count=0;
  $(".diagnose_map").click(function(e){
    e.preventDefault();
    $("svg.using_map").css("z-index",0);
    var tooth_nr = $(this).attr("alt").split("_").pop().toLowerCase();
    var tooth_name;
    if(Number.isInteger(parseInt(tooth_nr))){
      tooth_name=getToothName(parseInt(tooth_nr));
    }else {
      tooth_name=getToothName($.trim(tooth_nr));
    }
    var diagnose_input='<div class="form-group row stripe" id="div_'+count+'">';
    diagnose_input+='<label id="label_'+count+'" class="col-sm-3">';
    diagnose_input+="** "+tooth_name.shift()+' >>> </label>'
    diagnose_input+='<div class="col-sm-9">';
    diagnose_input+='<textarea autofocus name="diagnose[]" placeholder="Write the Diagnosis" class="form-control diagnose_textarea"></textarea>';
    diagnose_input+='</div><div class="col-sm-12"><button type="button" class="btn btn-danger mt-3 textarea_remove" id="'+count+'">remove</button></div></div>';
    $("svg.svg").html($("svg.svg").html()+tooth_name.pop()+" id='circle_"+count+"'/>");
    $("#diagnose-form").prepend(diagnose_input);
    count++;
    $("button.textarea_remove").click(function(){
      var id=$(this).attr("id");
      $("#div_"+id).remove();
      $("#circle_"+id).remove();
    });
  });
  //
  $("svg.using_map").click(function(){
    $(this).css("z-index",-1);
  });
  $("img.using_map").mouseleave(function(){
    $("svg.using_map").css("z-index",0);
  });

  //show diagnose img
  $("#show_diagnose_img").click(function(){
    if($("#show_diagnose_img span").hasClass("glyphicon-chevron-down")){
      $("div.svg").slideDown(500,function(){
        $("#show_diagnose_img span").removeClass("glyphicon-chevron-down");
        $("#show_diagnose_img span").addClass("glyphicon-chevron-up");
      });
    }else{
      $("div.svg").slideUp(500,function(){
        $("#show_diagnose_img span").removeClass("glyphicon-chevron-up");
        $("#show_diagnose_img span").addClass("glyphicon-chevron-down");
      });
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
  //open xray img in a float div
  var next_img;
  var prev_img;
  var img_array= new Array;
  var img_desc_array= new Array;
  $("img.xray").each(function(){
    img_array.push($(this).attr("src"));
    img_desc_array.push($(this).attr("alt"));
  });
  $("img.xray").click(function(){
    $(".float_form_container").show();
    $("#xray_gallery").show();
    var img_src=$(this).attr('src');
    var img_desc= $(this).attr('alt');
    $("#xray_gallery img").attr('src',img_src);
    if(img_desc!="")
      $("div#img_desc").html('Description : '+img_desc);
  });
  $("#next_img").click(function(){
    var i=img_array.indexOf($("#xray_gallery img").attr('src'));
    if(i==img_array.length-1){
      $("#xray_gallery img").attr('src',img_array[0]);
      if(img_desc_array[0]!="")
        $("div#img_desc").html('Description : '+img_desc_array[0]);
    }else{
      $("#xray_gallery img").attr('src',img_array[i+1]);
      if(img_desc_array[i+1]!="")
        $("div#img_desc").html('Description : '+img_desc_array[i+1]);
    }
  });
  $("#prev_img").click(function(){
    var i=img_array.indexOf($("#xray_gallery img").attr('src'));
    if(i==0){
      $("#xray_gallery img").attr('src',img_array[img_array.length-1]);
      if(img_desc_array[img_array.length-1]!="")
        $("div#img_desc").html('Description : '+img_desc_array[img_array.length-1]);
    }else{
      $("#xray_gallery img").attr('src',img_array[i-1]);
      if(img_desc_array[i-1]!="")
        $("div#img_desc").html('Description : '+img_desc_array[i-1]);
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
      $(document).ajaxStop(function(){
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
    e.preventDefault();
    $("div.invalid-feedback").remove();
    $("input.is-invalid").removeClass("is-invalid");
    var check = true;
    var total_price=$("#total_price").val();
    var diagnoseArray = new Array();
    if($(".diagnose_textarea").length>0){
      $('.diagnose_textarea').each(function(index){
        if(!validateNotEmpty($(this).val())){
          assignError($(this),"You can't create an empty Diagnosis");
          check=false;
        }
        var element = $("#label_"+index).text()+" "+$(this).val();
        diagnoseArray.push(element);
      });
    }else{
      $("div.svg").after("<div class='alert alert-danger'>You must select at least one tooth and write down a diagnosis</div>");
      check=false;
    }
    if(validateNotEmpty(total_price)){
      if(!validateNumber(total_price)){
        $("#total_price").addClass("is-invalid");
        $(".input-group-append").after("<div style='display:block' class='invalid-feedback'>Please Enter a valid price number</div>");
        check=false;
      }
    }

    if (check) {
      $(document).ajaxStart(function(){
        $("#loading").show();
        $(this).hide();
      });
      $(document).ajaxStop(function(){
        $("#loading").hide();
        $(this).show();
      });
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url : $(this).attr('action'),
        method : "POST",
        data : {
          'diagnose': diagnoseArray,
          'total_price': total_price,
          'async' : false,
          'admin': 'ahmed'
        },
        success :function(data){
          if(data['state']=='OK'){
            window.location.href = "/patient/diagnosis/display/"+data['id'];
          }else{
            $("div.svg").after("<div class='alert alert-danger'>"+data['error']+"</div>");
          }
        },
        error : function(data){
          alert(data);
        }
      });
    }else{
      return false;
    }
  });
});
