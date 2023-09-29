var order = "asc";
var keyorder =  "datedocument";
var datestart="";
var dateend="";
var idclient ;
$( document ).ready(function() {

  $('.dates1_').hide();
  $( "#documentall" ).change(function() {
    $('.dates1_').hide();
  });
  refreshtable_map();

  $( "#documentexpired" ).change(function() {
   $('.dates1_').show();
  });


  $('#idclient').on('change', function (e) {
    if ( $( "#clientname" ).length ) {
      $("#modalpaymentdoc").prop('disabled',true);
      var nif = objs_select[$(this).attr("id")][$(this).val()].nif;
      if(nif=='999999990'){
        $( ".nameclient" ).show();
        $("#clientname").prop('required',true);
      }else{
        $( ".nameclient" ).hide();
        $("#clientname").prop('required',false);
      }
    }
    $('#formsubmitprint').prop("disabled", false);
	   $('.formsubmitprint').prop("disabled", false);
     $("#a_mappending").click();
  });

});



function confpay(row,id, elem){
  if ($(elem).is(':checked')) {
    $('#inputpay'+row).prop('disabled', false);
    $('#iddocument'+row).prop('disabled', false);
	$('#inputdiscount'+row).prop('disabled', false);
	if($('#inputdiscount'+row).attr('identify')=='-1'){
		$('#inputdiscount'+row).prop('readonly', true).css("background-color","#EBEBE4");
	}
  }else{
    $('#inputpay'+row).prop('disabled', true);
    $('#iddocument'+row).prop('disabled', true);
	$('#inputdiscount'+row).prop('disabled', true);
	if($('#inputdiscount'+row).attr('identify')=='-1'){
		$('#inputdiscount'+row).prop('readonly', false);
	}
  }
  gettotal();
}






function evententerkey(){
  $('.inputvaluepaid').keypress(function (e) {
   var key = (e.keyCode ? e.keyCode : e.which);
   if(key == 13)  // the enter key code
    {
      return false;
    }
  });
}



function openmodal(){
  $("#paymentDoc").modal('toggle');
  return false;
}




function refreshtable_map(){
  idclient = $('#idclient').val();
  $("#tablebody").html('<div class="progress progress-striped active" style="margin-bottom:0;width:100%"><div class="progress-bar" style="width: 100%"></div></div>');

  $.ajax({
    url: '?controller=clients&action=mappendinginfo',
    type: 'post',
    dataType: 'text',
    data: { id:idclient, order: order, keyorder:keyorder, documents:$("input[type='radio'][name='documents']:checked").val(), datestart:$('#datestart').val(), dateend:$('#dateend').val(), oiv:$('#center_sel').val(), entity_sel:$('#entity_sel').val()  },
    success: function (data) {

      $("#tablebody").html(data);
evententerkey();
      if ( $( "#withoutdata" ).length ) {
        $('#withintimelimit').text('0.00');
        $('#outtime').text('0.00');
        $('#totaldocuments').text('0.00');
      }else{
        $('#withintimelimit').text($('.withintimelimit').val());
        $('#outtime').text($('.outtime').val());
        $('#totaldocuments').text($('.totaldocuments').val());
      }

	  $('#tablebody').trigger('tablechanged');

    },
    error: function (xhr, ajaxOptions, thrownError) {
      $("#tablebody").html('<div class="progress progress-striped active" style="margin-bottom:0;width:100%"><div class="progress-bar" style="width: 100%"></div></div>');
    }
  });

}



function gettotal(){
  var tot = 0;
  var num = 0;
  var chckd = 0;
  $("#elementsdocument > tbody > tr").each(function() {
    var cid = $(this).attr('id');

    var checked = $("#confpay"+cid).is(':checked');
    if(checked){
      num++;
      if($("#typedoc"+cid).val() == 0){
		  var subtotal  = parseFloat($("#inputpay"+cid).val()) - parseFloat($("#inputdiscount"+cid).val());
		  if(subtotal<0){
			  chckd++;
		  }
        tot += Math.round(subtotal *100) /100;
      }else{
		  var subtotal  = parseFloat($("#inputpay"+cid).val()) - parseFloat($("#inputdiscount"+cid).val());
		  if(subtotal*-1>0){
			  chckd++;
		  }
        tot -= Math.round(subtotal *100) /100;
      }

    }
  });

tot = Math.round(tot*100)/100;
  $('#paymentvalue').val( ""+tot ) ;
  $('#postdatedcheckvalue').val( ""+tot ) ;

 $('#totalpaiddocs').text( ""+tot.formatMoney(2, ',', '.') ) ;
  if(num==0){
     $("#modalpaymentdoc").prop('disabled', true);
	 $("#modalpostdatedcheck").prop('disabled', true);
  }else{
    $("#modalpaymentdoc").prop( "disabled", false );
	$("#modalpostdatedcheck").prop( "disabled", false );
  }
  if(tot<0){$("#modalpaymentdoc").prop('disabled', true);$("#modalpostdatedcheck").prop('disabled', true);}

  if(chckd>0){
	  Swal.fire(lang['VALUE_DISCOUNT_EXCEEDED_VALUE_DOC']);
  }

}




Number.prototype.formatMoney = function(c, d, t){
var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
