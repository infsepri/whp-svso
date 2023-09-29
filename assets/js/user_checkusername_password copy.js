
function checkusername(){
var  oldusername = "";
var  inputVal = document.getElementById("user_username").value;
if(inputVal!=""){
  $.ajax({
    url: 'bo/?controller=home&action=checkusername',
    type: 'POST',
    data: { 'company[username]': inputVal, 'oldusername' : oldusername} ,
    success: function (response) {
      response=JSON.parse(response);
      if(response.code==200){
        $('#msgusernameexist').show();
        $("#user_username").css("border", "3px solid #FF0000");
        $('#user-submit').attr("disabled", true);
      }else{
        $('#msgusernameexist').hide();
        $("#user_username").css("border", "3px solid #9ecaed");
        $('#user-submit').attr("disabled", false);
      }
    },
    error: function () {
      console.log("Error call verification username");
    }
  });
}
}




function checkusernamebo(){
var  oldusername = document.getElementById("oldusername").value;
var  inputVal = document.getElementById("user_username").value;
if(inputVal!=""){
  $.ajax({
    url: '?controller=home&action=checkusername',
    type: 'POST',
    data: { 'user[username]': inputVal, 'oldusername' : oldusername} ,
    success: function (response) {

      if(response==1){
        $('#msgusernameexist').show();
        $("#user_username").css("border", "3px solid #FF0000");
        $('#user-submit').attr("disabled", true);
      }else{
        $('#msgusernameexist').hide();
        $("#user_username").css("border", "3px solid #9ecaed");
        $('#user-submit').attr("disabled", false);
      }
    },
    error: function () {
      console.log("Error call verification username");
    }
  });
}
}




function checkpassword(){
  if (window.location.search.indexOf('action=signup') > -1) {
    var required = false;
} else {
    var required = true;
}
var   password = document.getElementById("user_password").value;
var  confirmpassword = document.getElementById("user_confirm_password").value;
if(document.getElementById("oldpassword")!=null){
  var  oldpassword = document.getElementById("oldpassword").value;
  document.getElementById("oldpassword").setAttribute("required", "true");
}

document.getElementById("user_password").setAttribute("required", "true");
document.getElementById("user_confirm_password").setAttribute("required", "true");

  if (password == "" && confirmpassword==""){
    if(required){
    document.getElementById("user_password").removeAttribute("required");
    document.getElementById("user_confirm_password").removeAttribute("required");
    if(document.getElementById("oldpassword")!=null){
      document.getElementById("oldpassword").removeAttribute("required");
    }

    }
    $("#user_password").css("border", "1px solid #DDDDDD");
    $("#user_confirm_password").css("border", "1px solid #DDDDDD");
    $('#user-submit').attr("disabled", false);
  }else if(password ==  confirmpassword){
    $("#user_password").css("border", "3px solid #9ecaed");
    $("#user_confirm_password").css("border", "3px solid #9ecaed");
    $('#user-submit').attr("disabled", false);
  }else if(password!="" && confirmpassword!=""){
    $("#user_password").css("border", "3px solid #FF0000");
    $("#user_confirm_password").css("border", "3px solid #FF0000");
    $('#user-submit').attr("disabled", true);
  }else{
    $("#user_password").css("border", "1px solid #DDDDDD");
    $("#user_confirm_password").css("border", "1px solid #DDDDDD");
    $('#user-submit').attr("disabled", false);
  }
}



function removeuser(user){
  var link = document.getElementById("removeuser");
  link.setAttribute('href', "?controller=user&action=delete&user="+user);
  $("#deleteConfirm").modal();
}
