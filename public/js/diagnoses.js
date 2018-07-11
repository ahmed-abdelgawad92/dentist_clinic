$(document).ready(function() {

  /*
  **
  ** Within Diagnosis Page there is a lot of actions
  ** that need to be done within a float form
  **
  */

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
    $(".float_form_container,.pos").hide();
    $(".float_form").hide();
    $("body").css('overflow-y', 'auto');
  });
  $(".float_form_container").click(function(){
    $(".float_form,.pos").hide();
    $(this).hide();
    $("body").css('overflow-y', 'auto');
  });
  $(".float_form").click(function(e){
    e.stopPropagation();
  });

  //add new drug input within the float form in the diagnosis page
  var no_of_drug=1;
  $("#add_new_drug").click(function(){
    $("#new_drug").append('<div class="col-12 center mb-3">Medicine '+(++no_of_drug)+'</div><div class="form-group row">'+$('.drug_input').html()+"</div>");
  });



/*****************************************************************************************************************************************/


  /*
  **
  ** Select Which Teeth are included in the
  ** Diagnosis during Diagnosis Creation
  **
  */
  var diagnose_type=""; //flag to check which dianose type to color the tooth with its own color
  var teeth_color="";
  var choosenTeeth = new Array; //array of the selected teeth to not select it again
  $(".change_diagnose").click(function(){
    diagnose_type="";
    teeth_color="";
    $(".change_diagnose span").removeClass('glyphicon-ok-sign');
    $(".selected_diagnose").removeClass('selected_diagnose');
    $(".change_diagnose span").addClass('glyphicon-record');
    $(this).addClass('selected_diagnose');
    $(this).children('span').removeClass('glyphicon-record')
    $(this).children('span').addClass('glyphicon-ok-sign');
    $(this).blur().delay(400);
    diagnose_type=$(this).attr('data-title');
    teeth_color=$(this).attr("data-color");
    console.log("Diagnose type ="+diagnose_type);
  });
  console.log(teeth_color);
  //add the selected imagemap to the textarea
  var count=0;
  $(".diagnose_map").click(function(e){
    e.preventDefault();
    if ((teeth_color=="")||(diagnose_type=="")) {
      console.log("Diagnose type ="+diagnose_type);
      alert("Please select diagnosis type before the tooth");
      return false;
    }
    $("svg.using_map").css("z-index",0);
    var tooth_nr = $(this).attr("alt").split("_").pop().toLowerCase();
    if(choosenTeeth.indexOf(tooth_nr)==-1){
      choosenTeeth.push(tooth_nr);
      var tooth_name;
      if(Number.isInteger(parseInt(tooth_nr))){
        tooth_name=getToothName(parseInt(tooth_nr),teeth_color);
      }else {
        tooth_name=getToothName($.trim(tooth_nr),teeth_color);
      }
      var diagnose_input='<div class="form-group row stripe" id="div_'+count+'"><input type="hidden" name="teeth_name['+count+']" class="name" value="'+tooth_name[0]+'">';
      diagnose_input+='<label id="label_'+count+'" class="col-lg-2">';
      diagnose_input+=tooth_name[0]+'</label>';
      diagnose_input+="<div class='col-lg-3'>";
      if (diagnose_type=="Variation") {
        diagnose_input+='<input type="text" class="form-control mb-3 type" name="diagnose_type['+count+']" value="Variation" />';
      }else {
        diagnose_input+='<input type="text" disabled="on" class="form-control mb-3 type" name="diagnose_type['+count+']" value="'+diagnose_type+'" />';
      }
      diagnose_input+='<div class="input-group mb-3">';
      diagnose_input+='<input type="text" class="form-control price" name="price['+count+']" value="" placeholder="Price in EGP" />';
      diagnose_input+='<div class="input-group-append"><span class="input-group-text">EGP</span></div></div></div>';
      diagnose_input+='<div class="col-lg-7">';
      diagnose_input+='<textarea autofocus name="description['+count+']" placeholder="Write the Diagnosis" class="form-control diagnose_textarea"></textarea>';
      diagnose_input+='</div><div class="col-sm-12"><button type="button" class="btn btn-danger mt-3 textarea_remove" data-tooth="'+tooth_name[0]+'" id="'+count+'">remove</button></div></div>';
      $("svg.svg").html($("svg.svg").html()+tooth_name[1]+" id='circle_"+count+"'/>");
      $("#diagnose-form").prepend(diagnose_input);
      count++;
    }else{
      alert("This tooth already existed in the diagnosis");
    }
  });
  //remove textarea within diagnosis creation form
  $("#diagnose-form").on("click","button.textarea_remove",function(){
    var id=$(this).attr("id");
    choosenTeeth.splice(choosenTeeth.indexOf($(this).attr('data-tooth')),1);
    $("#div_"+id).remove();
    $("#circle_"+id).remove();
  });
  //hide the svg when it's clicked to reach the image map underneath
  $("svg.using_map").click(function(){
    $(this).css("z-index",-1);
  });
  $("img.using_map").mouseleave(function(){
    $("svg.using_map").css("z-index",0);
  });

  //show diagnose img within Diagnosis Page
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


/*****************************************************************************************************************************************/

  /*
  **
  ** Validate Diagnosis Form
  **
  */



  $("#diagnose-form").submit(function(e){
    e.preventDefault();
    $("div.alert-danger").remove();
    $("div.invalid-feedback").remove();
    $("input.is-invalid").removeClass("is-invalid");
    var check = true;
    var priceArray= new Array();;
    var descriptionArray = new Array();
    var teethArray = new Array();
    var diagnoseTypesArray = new Array();
    var discount = $("#discount").val().trim();
    if($(".diagnose_textarea").length>0){
      $('.diagnose_textarea').each(function(index){
        if(!validateNotEmpty($(this).val().trim())){
          assignError($(this),"You can't create a Diagnosis with empty description");
          check=false;
        }
        descriptionArray.push($(this).val().trim());
      });
      $('.name').each(function(index){
        if(!validateNotEmpty($(this).val().trim())){
          check=false;
        }
        teethArray.push($(this).val().trim());
      });
      $('.type').each(function(index){
        if(!validateNotEmpty($(this).val().trim())){
          assignError($(this),"You must enter the diagnosis type");
          check=false;
        }
        diagnoseTypesArray.push($(this).val().trim());
      });
      $('.price').each(function(index){
        if(!validateNotEmpty($(this).val().trim())){
          assignError($(this),"You must enter the price of this case");
          check=false;
        }else if(!validateNumber($(this).val().trim())){
          assignError($(this),"The price must be a valid number");
          check=false;
        }
        priceArray.push($(this).val().trim());
      });
    }else{
      $("div.svg").after("<div class='alert alert-danger'>You must select at least one tooth and write down a diagnosis</div>");
      check=false;
    }
    if(validateNotEmpty(discount)){
      if(!validateNumber(discount)||$("#discount_type").val()=="no"){
        $("#discount").addClass("is-invalid");
        $(".input-group-append").after("<div style='display:block' class='invalid-feedback'>Please Enter a valid Discount value and choose the discount type</div>");
        check=false;
      }
    }

    if (check) {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
      $.ajax({
        url : $(this).attr('action'),
        method : "POST",
        data : {
          'description': descriptionArray,
          'diagnose_type': diagnoseTypesArray,
          'teeth_name' : teethArray,
          'price' : priceArray,
          'discount': discount,
          'discount_type' : discount_type
        },
        beforeSend : function(){
          $("#loading").show();
          $(this).hide();
        },
        complete : function(){
          $("#loading").hide();
          $(this).show();
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



/*****************************************************************************************************************************************/


  /*
  **
  ** PRESCRIPTION ADD MEDICATION ONLY BY PRINTING
  **
  */

  //add new drug only to print within only Print Prescription page
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

/*****************************************************************************************************************************************/
});
