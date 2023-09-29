function askDefaultSerie() {
	Swal.fire({
		title: lang["isseriedefault"],
		text: "",
		showCancelButton: true,
		showCloseButton: true,
		confirmButtonText: lang["yes"],
		cancelButtonText: lang["no"]
	})
	.then((result) => {
		if (result.value) {
			var oiv = $("#centerSel").val();
			$("#serieForm input").eq(0).val(oiv);
			$("#serieForm input").eq(1).val("1");
			$("#serieForm")[0].submit();
		
		} else if (result.dismiss === Swal.DismissReason.cancel) {
			var oiv = $("#centerSel").val();
			$("#serieForm input").eq(0).val(oiv);
			$("#serieForm input").eq(1).val("");
			$("#serieForm")[0].submit();
		}
	});
}