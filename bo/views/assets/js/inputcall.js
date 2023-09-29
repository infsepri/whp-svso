function openrole(vname, el) {
              
  var  identitytype = document.getElementById("identitytype").value;
  if(identitytype==1){
    $('.role_other').show();
  
  }else{
   $('.role_other').hide();
   $("#idrole").val("");
   $('#idrole').removeClass("valRequired");
  }
}