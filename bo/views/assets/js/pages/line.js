function controlType() {
	var val = $("[name='line[type]']:checked").eq(0).val();
	if(val==2) {
		$(".fieldA .valRequired").addClass("valIgnore");
		$(".fieldA").hide();
		$(".fieldB").show();
	}
	else {
		$(".fieldA .valRequired.valIgnore").removeClass("valIgnore");
		$(".fieldA").show();
		$(".fieldB").hide();
	}
}

$(window).on("load", function() {
	controlType();
});