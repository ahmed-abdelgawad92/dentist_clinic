$(document).ready(function() {
  //open xray img in a float div within Diagnosis page
  var next_img;
  var prev_img;
  var img_array= new Array;
  var img_desc_array= new Array;
  var img_xray_id= new Array;
  $("img.xray").each(function(){
    img_array.push($(this).attr("src"));
    if($(this).attr("alt")!=""){
      img_desc_array.push($(this).attr("alt"));
    }
    else {
      $(this).attr("alt"," None");
      img_desc_array.push(" None ");
    }
    img_xray_id.push($(this).attr("data-id"));
  });
  $("img.xray").click(function(){
    $(".float_form_container,.pos").show();
    $("#xray_gallery").show();
    var img_src=$(this).attr('src');
    var img_desc= $(this).attr('alt');
    var img_id= $(this).attr('data-id');
    $("body").css('overflow-y', 'hidden');
    $("#xray_gallery img").attr('src',img_src);
    $("a.btn-danger").attr('href', '/patient/diagnosis/oralradiology/'+img_id);
    $("a.btn-secondary").attr('href', '/patient/diagnosis/oralradiology/'+img_id);
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
});
