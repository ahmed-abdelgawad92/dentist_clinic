$(document).ready(function() {
  //search a drug in your system
  var drugSearchTimeout;
  var delay_time=1000;
  function searchDrug(drugName){
    if (!$.active) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });
      $.ajax({
        url: $("#search_drug_form").attr('action'),
        type: 'POST',
        data: {'search_drug': drugName.val().trim()},
        async: true,
        beforeSend: function(){
          $("table").hide();
          $("#loading").show();
        },
        complete: function(){
          $("table").show();
          $("#loading").hide();
        },
        success: function(response){
          var data = $.parseJSON(response);
          $("div.alert").remove();
          if(data.state=="OK"){
            var count = 1;
            $("#drug_table").html("");
            for (var drug of data.drugs) {
              var tr= "<tr><td>"+count+"</td><td>"+drug.name+"</td><td>";
              tr+='<a href="/medication/system/edit/'+drug.id+'" class="btn btn-secondary">edit <span class="glyphicon glyphicon-edit"></span></a></td><td>';
              tr+='<a href="/medication/system/delete/'+drug.id+'" class="btn delete_system_drug btn-danger">delete <span class="glyphicon glyphicon-trash"></span></a></td></tr>';
              $("#drug_table").append(tr);
              count++;
            }
          }else{
            //add errors
            var errors = $.parseJSON(response);
            $("table").hide();
            $("#search_drug_form").after("<div class='alert alert-danger'>"+errors.error+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>');
          }
        },
        error: function(data){
          alert(data);
        }
      });
    }
  }
  $("#search_drug").keyup(function(e){
    if(drugSearchTimeout){
      clearTimeout(drugSearchTimeout);
    }
    drugName= $(this);
    drugSearchTimeout = setTimeout(function () {
      searchDrug(drugName);
    }, delay_time);
  });
  $("#search_drug").blur(function(e){
    if(drugSearchTimeout){
      drugName= $(this);
      drugSearchTimeout = setTimeout(function () {
        searchDrug(drugName);
      }, delay_time);
    }
  });
  $("#search_drug_form").submit(function(e){
    e.preventDefault();
    searchDrug($("#search_drug"));
  });

  /// Delete a drug from database
  $("#drug_table").on("click",".delete_system_drug",function(event) {
    /* Act on the event */
    event.preventDefault();
    $(".float_form_container").show();
    $("#delete_system_drug").show();
    $("#delete_system_drug a.btn-danger").attr('href',$(this).attr("href"));
  });
});
