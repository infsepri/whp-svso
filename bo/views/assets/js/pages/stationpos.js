function setCenter() {
	var oiv = $("#centerSel").val();
	if(oiv!="") {
		$("#stationForm input[name='oiv']").val(oiv);
	}
}