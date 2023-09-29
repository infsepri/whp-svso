var page=1;
var pageend=1;
var order = "";
var keyorder =  "";
var search =  "";
var first=true;
var typingTimer;
var params = [];

$( document ).ready(function() {
	refreshtable();
	$( ".query" ).keyup(function() {
		search = $( ".query" ).val();
		clearTimeout(typingTimer);
		first=true;
		typingTimer = setTimeout(refreshtable, 500);
	});
	$( ".query" ).keydown(function() {
		clearTimeout(typingTimer);
	});
});


function refreshtable(){
	var numberelements=$('#elementshowtable').val();
	$(".tablebody").html('<div class="progress progress-striped active" style="margin-bottom:0;width:100%"><div class="progress-bar" style="width: 100%"></div></div>');
	page = $('#page-content').html();
	$.ajax({
		url: $('.table_elementsdocument').data('url'),
		type: 'post',
		dataType: 'html',
		async: true,
		data: { page: page, numberelements : numberelements, order: order, keyorder: keyorder, search: search, param: params},
		success: function (data) {
			$(".tablebody").html(data);
			if($('#pagination-demo').data("twbs-pagination")){
				$('#pagination-demo').twbsPagination('destroy');
			}
			if(parseInt($('#total').html())<parseInt(page)){
				$('#page-content').html('1');refreshtable();
			}
			$('#pagination-demo').twbsPagination({
				totalPages: $('#total').html(),
				startPage: parseInt(page),
				onPageClick: function (event, page) {
					$('#page-content').text('' + page);
					if(!first){
						refreshtable();
						first=true;
					}else{
						first=false;
					}
				}
			});
			$('.tablebody').trigger('tablechanged');
		},
		error: function (xhr, ajaxOptions, thrownError) {
			$(".tablebody").html('<div class="progress progress-striped active" style="margin-bottom:0;width:100%"><div class="progress-bar" style="width: 100%"></div></div>');
		}
	});
}



function ordertable(elem){
	var isAsc = $(elem).hasClass("sortingAsc");
	var isDesc = $(elem).hasClass("sortingDesc");
	$(elem).parents("table").eq(0).find(".sorting").removeClass("sortingDesc").removeClass("sortingAsc");
	
	if(!isAsc) {
		$(elem).removeClass("sortingDesc");
		$(elem).addClass("sortingAsc");
		order = "asc";
	}
	else{
		$(elem).removeClass("sortingAsc");
		$(elem).addClass("sortingDesc");
		order = "desc";
	}
	
	keyorder = $(elem).attr('id');
	first=true;
	
	refreshtable();
}
