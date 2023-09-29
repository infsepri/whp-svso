
function checknif(){
  var temErro=0;
  var abbr = 'PT';

  inputVal = document.getElementById('nif').value;
  if(inputVal==""){
    $('#messagenif').hide();
    $('#messagenifinvalid').show();
    //$('#user-submit').hide();
    //$("#nif").css("border", "3px solid #FF0000");
    //$('#user-submit').attr("disabled", true);
    return -1;
  }
  if(inputVal.length==9){
  error = validationVat(inputVal);
if(abbr=='PT' && error==1){
  $("#nif").css("border", "3px solid #FF0000");
  $('#user-submit').attr("disabled", true);
  $('#messagenifinvalid').show();
  $('#user-submit').hide();
  return -1;
}else{
  $('#messagenif').hide();
  $('#messagenifinvalid').hide();
  $('#user-submit').show();
  $("#nif").css("border", "3px solid #9ecaed");
  $('#user-submit').attr("disabled", false);

}

}else{
  $('#messagenif').hide();
  $('#messagenifinvalid').show();
  $('#user-submit').hide();
  $("#nif").css("border", "3px solid #FF0000");
  $('#user-submit').attr("disabled", true);
}
}


function checknifparent(){
  var temErro=0;
  var abbr = 'PT';

  inputVal = document.getElementById('nifp').value;
  if(inputVal==""){
    $('#messagenif').hide();
    $('#messagenifinvalid').show();
    $('#user-submit').hide();
    $("#nif").css("border", "3px solid #FF0000");
    $('#user-submit').attr("disabled", true);
    return -1;
  }
  if(inputVal.length==9){
  error = validationVat(inputVal);
if(abbr=='PT' && error==1){
  $("#nifp").css("border", "3px solid #FF0000");
  $('#user-submitp').attr("disabled", true);
  $('#messagenifinvalidp').show();
    $('#user-submit').hide();
  return -1;
}else{
  $('#messagenifp').hide();
  $('#messagenifinvalidp').hide();
    $('#user-submit').show();
  $("#nifp").css("border", "3px solid #9ecaed");
  $('#user-submitp').attr("disabled", false);

}

}else{
  $('#messagenifp').hide();
  $('#messagenifinvalidp').show();
  $('#user-submit').hide();
  $("#nifp").css("border", "3px solid #FF0000");
  $('#user-submitp').attr("disabled", true);
}
}




function whitespaceconservatory(callother) {
  var conservatory =  $( "#company_conservatory").val();
  if (/\s/.test(conservatory)) {
    $('#conservatoryerror').show();
    $('#company-submit').attr("disabled", true);
  }else{
    $('#conservatoryerror').hide();
    $('#company-submit').attr("disabled", false);
  }
  if(callother){
    whitespaceregisternumber(0);
  }
}

function whitespaceregisternumber(callother) {
  var registernumber =  $( "#company_registernumber").val();
  if (/\s/.test(registernumber) || registernumber.match(/[a-z]/i) ) {
    $('#registernumbererror').show();
    $('#user-submit').attr("disabled", true);
  }else{
    $('#registernumbererror').hide();
    $('#user-submit').attr("disabled", false);
  }
  if(callother){
    whitespaceconservatory(0);
  }
}
