function confirm_Swal(op, data){

  if(op==1){

    swal({
      title: lang[data[0]],
      text: lang[data[1]],
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: lang['yes'],
      cancelButtonText: lang['no']
    }).then((result) => {
      if (result.value) {

        window.location.href = data[2];
      }
    });

  }else if(op==3){

    swal({
      title: data[0],
      text: data[1],
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#DD6B55",
      confirmButtonText: lang['yes'],
      cancelButtonText: lang['no']
    }).then((result) => {
      if (result.value) {

        window.location.href = data[2];
      }
    });

  }else if(op==5){
    swal({
      title:  lang[data[0]],
      text: lang[data[1]],
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#8CD4F5",
      confirmButtonText: lang['yes'],
      cancelButtonText: lang['no']
    }).then((result) => {
      if (result.value) {
        var modal = data[2];
        $("#"+modal).modal("show");
      }
    });
  }else{

    swal({
      title: lang[data[3]],
      text: lang[data[4]],
      type: "warning",
      showCancelButton: true,
      confirmButtonColor: "#8CD4F5",
      confirmButtonText: lang['yes'],
      cancelButtonText: lang['no']
    }).then((result) => {
      if (result.value) {

      window.location.href = data[5];
      }
    });

  }
}
