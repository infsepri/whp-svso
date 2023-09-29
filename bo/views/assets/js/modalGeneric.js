function genericModal(id, controller, action, idref, modal, array_name, title_modal){
  if (typeof idref == 'undefined' || idref==null){
    var idref = "id";
  }
  if (typeof title_modal != 'undefined' && title_modal!=null){

      $("#"+modal+" .modal-title").empty();
      $("#"+modal+" .modal-title").html(lang[title_modal]);
    }

  $("#"+modal+" form")[0].reset();
  var formVar = $("#"+modal+" form").serializeArray();
  if (typeof id == 'undefined' || id==null){
    $("#"+modal).modal('show');
    $("#"+modal+" form").attr("action", "?controller="+controller+"&action="+action);
  }else{
      $.ajax(
    	{
    		url: '?controller='+controller+'&action=getdetail',
    		type: 'POST', // -> $_GET no PHP
    		data: { id: id, servicesession: true},
    		success: function( data ) {
          data=JSON.parse(data);
          $(formVar).each(function(i, field){

        $.each( data, function(i1, field1){

              var t = array_name+"["+i1+"]";
              if(t==$(field)[0].name){
                $("[name='"+$(field)[0].name+"']").val(field1).change();

              }
              });


          });
          $("#"+modal).modal('show');
          $("#"+modal+" form").attr("action", "?controller="+controller+"&action="+action);
    		},
    		error: function() {
    			return false;
    		}
    	}
    );
  }

}
