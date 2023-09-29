var duplicate = false;
var reason = "first print";
var print=false;

if($("#printFirst").length>0){print=$("#printFirst").val();}

if ("WebSocket" in window)
{
  // Let us open a web socket

  if (location.protocol === 'https:') {
    var ws = new WebSocket("wss://localhost:8181/Printers/printers");
  }else{
    var ws = new WebSocket("ws://127.0.0.1:8080/Printers/printers");
  }


  ws.onopen = function()
  {
    printdirectly();
  };

  ws.onmessage = function (evt)
  {
    var obj = JSON.parse(evt.data);
    Swal.fire(lang['notprint']+"<br/>"+lang[obj.msg]);
  };

  ws.onclose = function()
  {

    printpdf();
  };


}

else
{

}



function printdirectly(cntr, idshipping=null){
  if(print ||  (typeof cntr !== 'undefined' && cntr!="" && cntr=='printdeliverycharge')){
    if (typeof cntr !== 'undefined' && cntr!="") {
      controller = cntr;
    }else{
      controller = getUrlParameter('controller');
    }

    if( controller=="debitnote" && controller=="creditnote" ){
      controller = "note";
    }
    if( controller=="inspection" ){
      controller = "invoice";
    }
    id = ($("#printDocID").length>0) ? $("#printDocID").val() : getUrlParameter('id');
    $.ajax({
      url: '?controller=mnhome&action=urlprint',
      type: 'get',
      dataType: 'text',
      async: true,
      data: { controllerprint: controller, duplicate : duplicate, id: id, reason: reason },
      success: function (data) {
        ws.send(data);
      }
    });

  }
  print=false;

}


function printpdf(){
  if(print){
    id = ($("#printDocID").length>0) ? $("#printDocID").val() : getUrlParameter('id');
    controller = getUrlParameter('controller');
    if( controller=="debitnote" && controller=="creditnote" ){
      controller = "note";
    }
    if( controller=="inspection" ){
      controller = "invoice";
    }
    var win=window.open('about:blank', "winabcdef"+id);
    with(win.document)
    {
      open();
      write(' <form style="display:none;" method="post" action="?controller='+controller+'&action=downloadpdf" id="form" > <input type="text" name="pdf" value="download" readonly></input><input name="id" type="text" value="'+id+'" readonly></input><input name="mit" type="submit"  value="Pdf"></input> </form>  <script>document.getElementById("form").submit();</script>');
      close();
    }

  }
  print=false;
}



function retryprint(){
  if (ws.readyState === ws.OPEN) {

    print = true;
    duplicate = $('#duplicatedocument').is(':disabled');
    duplicate = !duplicate;
    reason = $('#reasonprint').val();
    printdirectly();
    $('input[name="reasonprint"]').attr('checked', false);
    $('#reasonprint').val('');
    $('#printDoc').modal('toggle');
    return false;
  }else{
    $('#printDoc').modal('toggle');
    return true;
  }
}


function retryprint2(){
  if (ws.readyState === ws.OPEN) {
    print = true;
    duplicate = false;
    reason = "";
    printdirectly();
    return false;
  }else{
    return true;
  }
}


function getUrlParameter(sParam) {
      var sPageURL = decodeURIComponent(window.location.search.substring(1)),
      sURLVariables = sPageURL.split('&'),
      sParameterName,
      i;

      for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
          return sParameterName[1] === undefined ? true : sParameterName[1];
        }
      }
    }
