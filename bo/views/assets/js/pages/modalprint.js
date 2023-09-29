function changereason(elem){
  var reason = $(elem).val();
  $('#reasonprint').val(reason);
  $('#reasonprint').prop("readonly", true);
  if(reason=="Outro"){
    $('#reasonprint').val('');
    $('#reasonprint').removeAttr('readonly');
  }
}
