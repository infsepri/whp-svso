var page=1;
var pageend=1;
var orderArr = {};
var keyorderArr =  {};
var searchArr =  {};
var typingTimer;
var params = [];
firstArr = {};

$(window).on("load", function() {
	refreshtable();
	$( ".query" ).keyup(function() {
		var idCont = $(this).parents(".tableContainer").eq(0).attr("id");
		searchArr[idCont] = $(this).val();
		clearTimeout(typingTimer);
		firstArr[idCont] = true;
		typingTimer = setTimeout(refreshtable, 500, idCont, true);
	});
	$( ".query" ).keydown(function() {
		clearTimeout(typingTimer);
	});
});

function reloadtable(tableID) {
	firstArr[tableID] = true;
	refreshtable(tableID);
}
function tablenumbers(elem){ 
	var tableID = $(elem).parents(".tableContainer").eq(0).attr("id");
	refreshtable(tableID);
}
function refreshtable(tableIDParam, asyncR){
	if(typeof tableIDParam === "undefined") {tableIDParam = "";}
	if(typeof asyncR === "undefined") {asyncR = false;}

	if($(".tableContainer").length==1) {
		asyncR = true;
	}
	$(".tableContainer").each(function() {
		var idContainer = $(this).attr("id");
		var oiv = "";
		var entity_sel = "";
		if(tableIDParam!="" && idContainer!=tableIDParam) {
			return true;
		}
		tableID = idContainer;

		var numberelements=$("#"+tableID+" .elementshowtable").val();
		$("#"+tableID+" .tablebody").html('<div class="progress progress-striped active" style="margin-bottom:0;width:100%"><div class="progress-bar" style="width: 100%"></div></div>');

		page = $("#"+tableID+" .page-content").html();
		if($("[name^='tableParam']").length>0) {
			params = {};
			$("[name^='tableParam']").each(function() {
				if(this.value=="" && !$(this).hasClass("checkbox_input")) {return true;}
				var name = this.name;

				var matches = name.match(/\[(.*?)\]/);

				if(matches[1]=="") {return true;}
				var pos = ""+matches[1];
				if(pos=="oiv") {
					oiv = this.value;
				}
				if(pos=="entity_sel") {
					entity_sel = this.value;
				} 
				if($(this).hasClass("checkbox_input")){
					if($(this).is(":checked")){
						params[pos] = 1;
					}else{
						params[pos] = 0;
					}
				}else{
					params[pos] = $(this).val();
				}



			});
		}

		order = (typeof orderArr[tableID] !== "undefined") ? orderArr[tableID] : "";
		keyorder = (typeof keyorderArr[tableID] !== "undefined") ? keyorderArr[tableID] : "";
		search = (typeof searchArr[tableID] !== "undefined") ? searchArr[tableID] : "";

		$.ajax({
			url: $("#"+tableID+" .table_elementsdocument").data('url'),
			type: 'post',
			dataType: 'html',
			async: asyncR,
			data: { page: page, numberelements : numberelements, order: order, keyorder: keyorder, search: search, param: params, oiv: oiv, entity_sel:entity_sel},
			success: function (data) {

				$("#"+tableID+" .tablebody").html(data);
				if($("#"+tableID+" .pagination-demo").data("twbs-pagination")){
					$("#"+tableID+" .pagination-demo").twbsPagination('destroy');
				}
				if(parseInt($("#"+tableID+" .total").html())<parseInt(page)){
					$("#"+tableID+" .page-content").html('1');
					reloadtable(tableID);
				}
				$("#"+tableID+" .pagination-demo").twbsPagination({
					totalPages: $("#"+tableID+" .total").html(),
					startPage: parseInt(page),
					onPageClick: function (event, page) {
						var idCont = $(this).parents(".tableContainer").eq(0).attr("id");
						$("#"+idCont+" .page-content").text('' + page);

						if ((typeof firstArr[idCont] !== "undefined") && firstArr[idCont]===false) {
							refreshtable(idCont, true);
							firstArr[idCont]=true;
						}else{
							firstArr[idCont]=false;
						}
					}
				});
				if($("#"+tableID+" .totalLeg").length>0) {
					var txt = $("#"+tableID+" .totalLeg").html();
					$("#"+tableID+" .totalContainer").html(txt);
				}
				
				if($("#"+tableID+" .legendHtml").length>0) {
					var txt = $("#"+tableID+" .legendHtml").html();
					$("#"+tableID+" .legContainer").html(txt);
				}

				$("#"+tableID+" .tablebody").trigger('tablechanged');
			},
			error: function (xhr, ajaxOptions, thrownError) {
				$("#"+tableID+" .tablebody").html('<div class="progress progress-striped active" style="margin-bottom:0;width:100%"><div class="progress-bar" style="width: 100%"></div></div>');
			}
		});

	});
}
function ordertable(elem){
	var idCont = $(elem).parents(".tableContainer").eq(0).attr("id");

	var isAsc = $(elem).hasClass("sortingAsc");
	var isDesc = $(elem).hasClass("sortingDesc");
	$(elem).parents("table").eq(0).find(".sorting").removeClass("sortingDesc").removeClass("sortingAsc");

	if(!isAsc) {
		$(elem).removeClass("sortingDesc");
		$(elem).addClass("sortingAsc");
		orderArr[idCont] = "asc";
	}
	else{
		$(elem).removeClass("sortingAsc");
		$(elem).addClass("sortingDesc");
		orderArr[idCont] = "desc";
	}

	keyorderArr[idCont] = $(elem).attr('id');


	firstArr[idCont]=true;
	refreshtable(idCont,true);
}
