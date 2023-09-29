function refreshtablesummary(){
  var id = $("#id_Client").val();
  $("#tablesummarybody").html('<div class="progress progress-striped active" style="margin-bottom:0;width:100%"><div class="progress-bar" style="width: 100%"></div></div>');

  var search = $(".query").val();
  datestart =$("#datesince").val();
   dateend = $("#date_end").val();
   oiv = $("#center_sel").val();
   entity_sel = $("#entity_sel").val();
  $.ajax({
    url: '?controller=clients&action=currentaccountsummary',
    type: 'post',
    dataType: 'html',
    async: true,
    data: {  id: id,  search: search, datestart: datestart, dateend: dateend, oiv:oiv , entity_sel:entity_sel },
    success: function (data) {

      $("#tablesummarybody").html(data);
	  $("#legContainer").html($("#htmlLeg").html());

    },
    error: function (xhr, ajaxOptions, thrownError) {
      $("#tablesummarybody").html('<div class="progress progress-striped active" style="margin-bottom:0;width:100%"><div class="progress-bar" style="width: 100%"></div></div>');
    }
  });

}
