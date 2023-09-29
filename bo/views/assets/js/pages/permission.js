function toggleMenus(obj, parent) {
	if ($(obj).hasClass("active")) {
		$("#"+parent).find(" > .menuController").hide();
		$(obj).removeClass("active");
	}
	else {
		$("#"+parent).find(" > .menuController").css("height", "").show();
		$(obj).addClass("active");
	}
}


function openAll() {
	$(".accordion-toggle.collapsed").removeClass("collapsed");
	$(".panel-collapse.collapse").addClass("in").css("height", "");
	$(".openMenu:not(.active)").trigger("click");
}
function closeAll() {
	$(".accordion-toggle:not(.collapsed)").trigger("click");
	$(".openMenu.active").trigger("click");
}

function openRoles() {
	$(".accordion-toggle.collapsed").removeClass("collapsed");
	$(".panel-collapse.collapse").addClass("in").css("height", "");
}
function closeRoles() {
	$(".accordion-toggle:not(.collapsed)").trigger("click");
}


function controlParent(father, op) {
	if($("."+father+" input.permCheck."+op+":checked").length>0) {
		$("."+father+" > input."+op+"").prop("checked", true);
	}
	else {
		$("."+father+" > input."+op+"").prop("checked", false);
	}
}

$("input.checkMenu").change(function() {
	var id = $(this).attr("id");
	var value = false;
	if($(this).is(':checked')) value = true;
	
	idArr = id.split("_");
	$("#menu_"+idArr[1]+"_"+idArr[2]).find("input.permCheck[id$='_"+idArr[3]+"']").prop('checked', value);
	
	$("#menu_"+idArr[1]+"_"+idArr[2]+" .menuController").each(function() {
		var el = $(this).find("input.permCheck[id$='_"+idArr[3]+"']:not(.checkMenu)").eq(0);
		$(el).trigger("change")
	});
	
});

$(document).ready(function () {
	$(".checkMenu").each(function() {
		var id = $(this).attr("id");
		idArr = id.split("_");
		var porMarcar = $("#menu_"+idArr[1]+"_"+idArr[2]).find("input.permCheck[id$='_"+idArr[3]+"']:not(.checkMenu):not(':checked')").length;
		var marcadas = $("#menu_"+idArr[1]+"_"+idArr[2]).find("input.permCheck[id$='_"+idArr[3]+"']:not(.checkMenu):checked").length;
		
		if ( porMarcar<=0 && marcadas>0) {
			$(this).prop('checked', true);
		}
	});
});