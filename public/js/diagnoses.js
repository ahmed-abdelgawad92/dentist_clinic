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
    $("#new_drug").append('<div class="col-12 center mb-3">Drug '+(++no_of_drug)+'</div><div class="form-group row">'+$('.drug_input').html()+"</div>");
  });



/*****************************************************************************************************************************************/


  /*
  **
  ** Select Which Teeth are included in the
  ** Diagnosis during Diagnosis Creation
  **
  */


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
