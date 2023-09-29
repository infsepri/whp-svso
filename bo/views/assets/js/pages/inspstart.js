$(document).ready(function() {
	$(".lightsCheck").on("change", function() {
		var el = this;
		$(el).parents(".col-md-12").eq(0).find(".lightsCheck").each(function() {
			var cual= this;
			if(cual!=el) {
				$(cual).prop("checked", false);
			}
		});
	});
	$(".nevmed").on("change", function() {
		if($(this).is(":checked")) {
			$(this).parents(".form-group").eq(0).find(".lightsCheck").prop("checked", false).prop("disabled", true);
		}
		else {
			$(this).parents(".form-group").eq(0).find(".lightsCheck").prop("checked", false).prop("disabled", false);
		}
		
		saveLights();
	});
});

function saveLights() {
	$(".dynamicField").attr("name", "").val("");
	$(".nevmed:checked").each(function() {
		var parent = $(this).parents(".form-group").eq(0);
		var valParID = $(this).data("getval");
		if($("#"+valParID).length>0) {
			var checkedEl = $("#"+valParID).find(".lightsCheck:checked").eq(0);
			var name = $(checkedEl).attr("name");
			name = name.replace(/]$/, "N]")
			$(parent).find(".dynamicField").attr("name", name).val("1");
		}
	});
}