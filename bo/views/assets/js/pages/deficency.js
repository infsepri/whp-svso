function controlLoc(value) {
	var val = (typeof value === "undefined") ? $("[name='deficency[localized]']").is(":checked") : value;
	if(!val) {
		$(".fieldLoc .valRequired").addClass("valIgnore");
		$(".fieldLoc").hide();
	}
	else {
		$(".fieldLoc .valRequired.valIgnore").removeClass("valIgnore");
		$(".fieldLoc").show();
	}
}
function controlType() {
	var val = $("[name='deficency[type]']:checked").val();
	if(val!=2) {
		$(".fieldBInsp .valRequired").addClass("valIgnore");
		$(".fieldBInsp").hide();
	}
	else {
		$(".fieldBInsp .valRequired.valIgnore").removeClass("valIgnore");
		$(".fieldBInsp").show();
	}
}

$(window).on("load", function() {
	$(".locSwitch").on('change', function () {
        controlLoc($(this).prop(":checked"));
    });
	controlLoc();
	controlType();
});