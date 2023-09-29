function showReport(show) {
    $("#save").hide();
    if(show) {
      $("#save").show();
    }
  }

    $(document).ready(function(){
      $(".nav-tabs a").click(function(){
          $(this).tab('show');
      });
      $('.nav-tabs a').on('shown.bs.tab', function(event){
          var x = $(event.target).text();
          var y = $(event.relatedTarget).text();
          $(".act span").text(x);
          $(".prev span").text(y);
      });


        $("#idstudentparent_" ).on('change', function (e) {

          var idstudentparent = $("#idstudentparent_").val();
          $("#idstudentparent").val(idstudentparent);
          if(idstudentparent=="" || idstudentparent==null){
            $("#idstudentparent").val("");
            return 0;
          }

  $.ajax({
     url: '?controller=studentparent&action=getparent',
     type: 'POST',
     data: { 'idstudentparent': idstudentparent} ,
     success: function (response) {
       if(response!=0){

           var obj = JSON.parse(response);

           $('#nameM').val( obj.namemother);


           $('#workcompanymother').val( obj.workcompanymother);
           $('#workphonemother').val( obj.workphonemother).change();
          $('#professionmother').val( obj.professionmother).change();

      


          var grademother = new Option(obj.grademother, obj.idgrademother, false, true);
          $('#idgrademother').append(grademother).trigger('change');

          var maritalstatusmother = new Option(obj.maritalstatus, obj.maritalstatusmother, false, true);
          $('#maritalstatusmother').append(maritalstatusmother).trigger('change');
          $('#maritalstatusmother').val( obj.maritalstatusmother).trigger("change");

          var maritalstatusfather = new Option(obj.maritalstatus, obj.maritalstatusfather, false, true);
          $('#maritalstatusfather').append(maritalstatusfather).trigger('change');
          $('#maritalstatusfather').val( obj.maritalstatusfather).trigger("change");

          var greeparent = new Option(obj.greeparent, obj.greeparent, false, true);
          $('#greeparent').append(greeparent).trigger('change');
          $('#greeparent').val( obj.greeparent).trigger("change");

          if(obj.idgradefather!=null){
            var grade = new Option(obj.gradefather, obj.idgradefather, false, true);
            $('#idgradefather').append(grade).trigger('change');
          }
          $('#nameF').val( obj.namefather);
          $('#user_usernamep').val( obj.email);
          
          $('#workphonefather').val( obj.workphonefather);
          $('#professionfather').val( obj.professionfather).change();
          $('#workcompanyfather').val( obj.workcompanyfather);
          if(obj.idworksituationfather!=null){
            var worksituationfather = new Option(obj.worksituationfather, obj.idworksituationfather, false, true);
            $('#idworksituationfather').append(worksituationfather).trigger('change');
          }

          if(obj.idworksituationmother!=null){
            var worksituationmother = new Option(obj.worksituationmother, obj.idworksituationmother, false, true);
            $('#idworksituationmother').append(worksituationmother).trigger('change');
          }

       }
     },
     error: function () {
       document.getElementById('idstudentparent').value= "";
       console.log("Error call verification email");
     }
   });

      //  getclient(idstudentparent);

      });
    });