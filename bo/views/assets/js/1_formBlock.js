function updRepItem(container, name) {
	nrKeys = $(container).find(".repItem.original").eq(0).find(".repUpd").length;
	name = (typeof name !== "undefined") ? name : "";

	cont = new Array(nrKeys).fill(0);
	$(container).find(".repItem").each(function(i, item) {
		key=0;
		if($(item).hasClass("original")) {
			key= key+1;
			return true;
		}
		$(item).find(".repUpd").each(function(i2, item2) {

			if (!$(item2).hasClass("repRemBtn")) {
				attr = $(item2).data("repattr");
				val = $(item2).attr(attr).replace(/\[(\d+)\]/gi, "["+cont[key]+"]");
				$(item2).attr(attr, val);

				if(!($(item2).hasClass("nameUpdated"))) {
					nameV = $(item2).attr("name");
					$(item2).attr("name", name+""+nameV);
					$(item2).addClass("nameUpdated");
				}
			}

			if($(item2).attr("onchange")){
				val = $(item2).attr("onchange").replace(/\[(\d+)\]/gi, "["+cont[key]+"]");
				$(item2).attr("onchange", val);
			}
			if ($(item2).hasClass("repRemBtn")) {
				if($(item2).attr("data-onclick")){
					var dataon=$(item2).attr("data-onclick");
					var newdataon=dataon.replace(/\[(\d+)\]/gi, "["+cont[key]+"]");
					$(item2).attr("data-onclick",newdataon);
				}
				else{
					var dataon=$(item2).attr("onclick");
					var newdataon=dataon.replace(/\[(\d+)\]/gi, "["+cont[key]+"]");
					$(item2).attr("onclick",newdataon);
				}
			}
			if ($(item2).hasClass("valIgnore")) {
				$(item2).removeClass("valIgnore");
			}

			startCheck = false;startDate = false;startSelect = false;startMultiSelect = false; startFile=false;

			if($(item2).hasClass("generateSelect2_search")) {
				$(item2).removeClass("generateSelect2_search").addClass("select2 select2_basic_search");
				startSelect = true;
			}

			if($(item2).hasClass("generateSelect2_ajax")) {
				$(item2).removeClass("generateSelect2_ajax").addClass("select2 selected2_p");
				startSelect = true;

				identify = cont[key]+1;
				if($(item2).hasClass("selectPrimary")) {
					$(item2).attr("identify", identify);
				}
				if($(item2).hasClass("selectSub_")) {
					classIdentify = "selectSub_"+identify;
					$(item2).addClass(classIdentify);
				}

			}

			if($(item2).hasClass("generateMulti")) {
				$(item2).removeClass("generateMulti").addClass("select_multiple_init");
				startMultiSelect = true;
			}

			if($(item2).hasClass("generateDate")) {
				$(item2).removeClass("generateDate").addClass("elem_datepicker_datetime");
				startDate = true;
			}

			if($(item2).hasClass("generateDateYear")) {
				$(item2).removeClass("generateDateYear").addClass("elem_datepicker_year");
				startDate = true;
			}

			if($(item2).hasClass("generateTime")) {
				$(item2).removeClass("generateTime").addClass("elem_datetimepicker_time");
				startDate = true;
			}

			if($(item2).hasClass("generateFile")) {
				$(item2).removeClass("generateFile").addClass("files");
				startFile = true;
			}

			if($(item2).hasClass("generateRadio")) {
				$(item2).removeClass("generateRadio").parent().eq(0).addClass("awradio");
				startCheck = true;
			}
			if($(item2).hasClass("generateCheck")) {
				$(item2).removeClass("generateCheck").parent().eq(0).addClass("awcheckbox");
				startCheck = true;
			}


			if(startCheck) {FormElements.checks();}
				if(startFile) {FormElements.filestart();}
			if(startDate) {FormElements.date();}
			if(startSelect) {FormElements.select2();}
			if(startMultiSelect) {FormElements.multiSel();}

			cont[key] = cont[key]+1;
			key= key+1;
		});
	});
}

function controlSave(saveId) {
	btn = $("#"+saveId);
	container = $(btn).parents(".repContainer").eq(0);
	cont  = $(container).find(".repItem:not(.original)").length;
	if (cont<=0) {
		$(btn).hide();
	}
	else {$(btn).show();}
}

function addRepItem(addBtn, name) {
	container = $(addBtn).parents(".repContainer").eq(0);
	cont  = $(container).find(".repItem:not(.original)").length+1;

	rep = $(container).find(".repItem.original").eq(0).clone();
	rep.removeClass("original");
	rep.find(".valIgnore").removeClass("valIgnore");
	$(container).find(".repItem").last().after(rep);

	if (cont>1) {
		$(container).find(".repRemBtn").show();
	}

	updRepItem(container, name);
	rep.find(".repRemBtn").each(function() {
		if($(this).attr("data-onclick")){
			var dataon=$(this).attr("data-onclick");
			var newdataon=dataon.replace("repRemBtn[0]",$(this).attr("id"));
			$(this).attr("data-onclick",newdataon);
		}
		else{
			var dataon=$(this).attr("onclick");
			var newdataon=dataon.replace("repRemBtn[0]",$(this).attr("id"));
			$(this).attr("onclick",newdataon);
		}
	});
}
function removeRepItem(btn,ask,controller,action,id) {

	container = $(btn).parents(".repContainer").eq(0);
/*	if ($(container).find(".repItem:not(.original)").length>1) {*/
		item = $(btn).parents(".repItem").eq(0);
		item.remove();
/*	}*/

	updRepItem(container);
}
