$( document ).ready(function() {
  $( "#confirm" ).keyup(function() {
    checkPassword();
  });
});
function checkPassword(){
  var pass=  $('#password').val();
  var confpass=  $('#confirm').val();

  if(pass==confpass){
    $("#confirm").css("border", "3px solid #9ecaed");
    $('#submit').attr("disabled", false);
    $('#passwordinvalid').hide();
  }else{
    $("#confirm").css("border", "5px solid #FF0000");
    $('#passwordinvalid').show();

    $('#submit').attr("disabled", true);
  }

  $( "#password" ).keyup(function() {
    checkPassword();
  });
}
