var init_select;
var objs_select=[];

function multipleAjax(el) {
	var elem = el.$select[0];
	if(typeof $(elem).data("url") !== "undefined") {
		var params = (typeof $(elem).data('params') !== "undefined") ? $(elem).data('params') : "";
		var url = $(elem).data("url");
		var arrPos = (typeof $(elem).attr("data-arr") !== "undefined") ? $(elem).attr("data-arr") : "";

		$.ajax({
			url: url,
			data: {params: params},
			method: 'GET',
			success: function(data){
			  var data = JSON.parse(data);
				var options = [];
				for (var field in data) {
				  var select_show = $('<div/>').html(data[field].select_show).text();
				  var obj={
						"label" : select_show,
						"title" : select_show,
						"value" : data[field].id
					}
					options.push(obj);
				}

				$(elem).multiselect('dataprovider', options);
				if(arrPos!="") {
					if(typeof multiValues[arrPos] !== "undefined") {
						$(elem).multiselect('select', multiValues[arrPos]);
					}
				}

			}
		});
	}
}

var FormElements = function () {
	var runChecksRadios = function () {
		if($(".awcheckbox").length) {
			$(".awcheckbox label").unbind("click").on("click", function() {
				$(this).parent().find("input").eq(0).trigger("click");
			});
		}
		if($(".awradio").length) {
			$(".awradio label").unbind("click").on("click", function() {
				$(this).parent().find("input").eq(0).trigger("click");
			});
		}
	};

    //function to initiate jquery.inputlimiter
	var runInputLimiter = function () {
		if($('.limited').length){
			$('.limited').inputlimiter({
				remText: 'Apenas tem %n carácter(es) restantes...',
				remFullText: 'Não pode escrever mais carácteres!',
				limitText: 'Campo limitado a %n carácter(es).'
			});
		}

	};
    //function to initiate query.autosize
	var runAutosize = function () {
		if($('textarea.autosize').length){
			$("textarea.autosize").autosize();
		}
	};

	//function to initiate multiple-select
	var runMultipleSelect = function () {
		if($("select.select_multiple_init").length) {
			$( "select.select_multiple_init" ).each(function( index ) {
				$(this).multiselect({
					includeSelectAllOption: true,
					enableCaseInsensitiveFiltering: true,
					enableFiltering: true,
					maxHeight:300,
					buttonWidth: '100%',
					selectedClass: 'multiselect-selected',
					filterPlaceholder: lang['search'],
					nSelectedText: lang['selected'],
					nonSelectedText: lang['select_option'],
					selectAllText: lang['select_all'],
					onInitialized: function(event) {
						multipleAjax(this);
					},
					onDropdownShow: function(event) {
						multipleAjax(this);
					},
					allSelectedText:lang['all_selected']
				});
			});
		}
	};
	var runMultipleSelectSubs = function () {

		if($(".multiselectPrimary").length>0) {
			$(".multiselectPrimary").unbind("change").change(function(ev) {
				var line = $(this).attr("identify");
				var vl = $(this).val();
				if(typeof objs_select[vl]=='undefined' || objs_select[vl].count==0){
					$(".multiselectSub_"+line).val("").trigger("change")
					$(".multiselectSub_"+line).data("params", "0::");
					$(".reqClass.multiselectSub_"+line).removeClass("valRequired");
					$(".reqClass.multiselectSub_"+line).parent().find(".symbol.required").addClass("hidden");
				}
				else{
					$(".multiselectSub_"+line).prop("disabled", false);
					$(".reqClass.multiselectSub_"+line).parent().find(".symbol.required").removeClass("hidden");
					$(".reqClass.multiselectSub_"+line).addClass("valRequired");

					$(".multiselectSub_"+line).data("params", vl+"::");
					$(".multiselectSub_"+line).val(null).change();
				}
			});
		}
	};

    //function to initiate Select2
	var runSelect2 = function () {
		$( ".select2_basic" ).each(function( index ) {
			var placeholder = null;var allowClear = false;
			if(typeof $(this).data("placeholder") !== "undefined") {
				placeholder = $(this).data("placeholder");
			}
			if(typeof $(this).data("allowclear") !== "undefined") {
				allowClear = true;
			}
			$(this).select2({
				minimumResultsForSearch: -1,
				language: lang_abbr,
				placeholder: placeholder,
				allowClear: allowClear,
				width: '100%'
			});
		});

		$( ".select2_basic_search" ).each(function( index ) {
			var placeholder = null;
			if(typeof $(this).data("placeholder") !== "undefined") {
				placeholder = $(this).data("placeholder");
			}
			$(this).select2({
				placeholder: placeholder,
				language: lang_abbr,
				width: '100%'
			});
		});

		$( ".selected2_p" ).each(function( index ) {
			if (!$(this).hasClass("select2-hidden-accessible")) {
				var placeholder = null; var allowClear = false; var modal=null; var all_value = "null"; var all_text=null; var all = false;
				if(typeof $(this).data("placeholder") !== "undefined") {
					placeholder = $(this).data("placeholder");

				}
				if(typeof $(this).data("allowclear") !== "undefined") {
					allowClear = true;
				}
				if(typeof $(this).data("modal") !== "undefined") {
					modal = $(this).data("modal");
					modal = $('#'+modal);
				}
				if(typeof $(this).data("all") !== "undefined" && typeof $(this).data("allvalue") !== "undefined" && typeof $(this).data("alltext") !== "undefined") {
					if($(this).data("all")){
						all= true;
						all_value = ""+$(this).data("allvalue");
						all_text = ""+$(this).data("alltext");
					}
				}
				$(this).select2({
					ajax: {
						url: function () { return $(this).data('url'); },
						dataType: 'json',
						delay: 250,
						data: function (params) {
							if (typeof $(this).attr("data-params") !== 'undefined') {  
								var res = $(this).attr("data-params").split("::");
								var tt="";
								for (var i = 0; i < res.length; i++) {
									if(res[i]==""){continue;}
									var res1 = res[i].split("_");
									tt += res1[0]+"::";
								}
								res= tt;
								return {
									q: params.term,
									page: params.page,
									params:res
								};
							}
							return {
								q: params.term,
								page: params.page
							};
						},

						processResults: function (data, params) {
							params.page = params.page || 1;
							objs_select=[];
							if(all){
								var decoded = $('<div/>').html(all_text).text();
								data.unshift({select_show:decoded, id:all_value});
							}
							return {
								results: $.map(data, function(obj) {
									var decoded = $('<div/>').html(obj.select_show).text();
									objs_select[obj.id]=obj;

									return { text: decoded, id: obj.id  };
								}),
								pagination: {
									more: (params.page * 30) < data.total_count
								}
							};
						},
						cache: true
					},
					dropdownParent: modal,
					allowClear : allowClear,
					placeholder: placeholder,
					language: lang_abbr,
					escapeMarkup: function (markup) { return markup; },
					minimumInputLength: 0,
					formatSelection: function  (obj) { return obj.id; },
					formatResult: function  (obj) { return obj.id; },
					width: 'element'
				});
			}
		});
	};
	var runSelect2Subs = function () {

		if($(".selectPrimary").length>0) {
			$(".selectPrimary").unbind("change").change(function(ev) {
				var line = $(this).attr("identify");
				//if(typeof stop !== "undefined") {stop=false;}
				var vl = $(this).val();
				if(typeof objs_select[vl]=='undefined' || objs_select[vl].count==0){
					//$(".selectSub_"+line).prop("disabled", true);
					$(".selectSub_"+line).val("").trigger("change")
					$(".selectSub_"+line).data("params", "0::");
					$(".reqClass.selectSub_"+line).removeClass("valRequired");
					$(".reqClass.selectSub_"+line).parent().find(".symbol.required").addClass("hidden");
				}
				else{
					$(".selectSub_"+line).prop("disabled", false);
					$(".reqClass.selectSub_"+line).parent().find(".symbol.required").removeClass("hidden");
					$(".reqClass.selectSub_"+line).addClass("valRequired");

					$(".selectSub_"+line).data("params", vl+"::");
					$(".selectSub_"+line).val(null).change();
				}

				if($(".selectSub_"+line).data("all")) {
					var allVal = $(".selectSub_"+line).data("allvalue");
					$(".selectSub_"+line).val(allVal).change();
				}

			});
		}
	};

	//function to initiate jquery.maskedinput
	var runMaskInput = function () {

		$(".valFloat").on("change", function() {
			var val = $(this).val();
			val = val.replace(/\,/g, '.');
			$(this).val(val);
		});

		if($('.input-mask-dateTime').length){
			$('.input-mask-dateTime').mask('00/00/0000 00:00:00');
		}
		if($('.input-mask-matricula').length){
			$('.input-mask-matricula').mask('AA-AA-AA');
		}
		$('.input-mask-matricula').keyup(function(e) {
			if (e.keyCode==8) {
				$('.input-mask-matricula').unmask();
				$('.input-mask-matricula').removeAttr("maxLength");
			}
		});

		if($('.input-mask-data').length){
			$('.input-mask-data').mask('0000-00-00');
		}

	};
	var runMaskMoney = function () {
		if($('.currency').length){
			$(".currency").maskMoney({
				thousands: ""
			});
		}
	};


	var runFileUpload = function () {
		if($('.files').length){
			$( ".files" ).each(function(  ) {
				var aux=$(this).attr("accept").toUpperCase();
				arr_aux = aux.split(',');

						$(this).fileinput({
							language: lang_abbr,
							validateInitialCount: true,
							'previewFileType':'any',
								overwriteInitial: true,
								maxFileSize:600,
								showUpload: false,
								allowedFileExtensions: arr_aux

						});

					});
		}

	};

	var runFileUploadativity = function () {
		if($('.files1').length){
			$( ".files1" ).each(function(  ) {
				var id = $(this).attr("id");
				var initialPreviewAsData=true;
				var maxFileSize=200;
				var showUpload= false;
				if(typeof files_input_load!=="undefined" && typeof files_input_load[id]!=="undefined" && typeof files_input_load[id][1]!=="undefined"){
					var previewurl = [];

					var previewconfig = [];
					for (var i = 0; i < files_input_load[id][1].length; i++) {
						previewurl.push(files_input_load[id][1][i][0]);
						previewconfig.push(files_input_load[id][1][i][1]);

					}

					if(typeof files_input_load[id][0].initialPreviewAsData !=="undefined"){ initialPreviewAsData=files_input_load[id][0].initialPreviewAsData; }
					if(typeof files_input_load[id][0].maxFileSize !=="undefined"){ maxFileSize=files_input_load[id][0].maxFileSize; }
					if(typeof files_input_load[id][0].showUpload !=="undefined"){ showUpload=files_input_load[id][0].showUpload; }
				}else
				if(typeof files_input_load!=="undefined" && typeof files_input_load[id]!=="undefined" && typeof files_input_load[id][0]!=="undefined"){
					if(typeof files_input_load[id][0].initialPreviewAsData !=="undefined"){ initialPreviewAsData=files_input_load[id][0].initialPreviewAsData; }
					if(typeof files_input_load[id][0].maxFileSize !=="undefined"){ maxFileSize=files_input_load[id][0].maxFileSize; }
					if(typeof files_input_load[id][0].showUpload !=="undefined"){ showUpload=files_input_load[id][0].showUpload; }
				}



				var aux=$(this).attr("accept").toUpperCase();
				arr_aux = aux.split(',');

				if(typeof files_input_load!=="undefined" && typeof files_input_load[id]!=="undefined" && typeof files_input_load[id][1]!=="undefined"){
						$(this).fileinput({
							language: lang_abbr,
							validateInitialCount: true,
							'previewFileType':'any',
								overwriteInitial: true,
								maxFileSize:maxFileSize,
								showUpload: showUpload,
								allowedFileExtensions: arr_aux,
								initialPreviewAsData: initialPreviewAsData,
								initialPreview:previewurl,
								initialPreviewConfig:previewconfig,
						});
					}else{
						$(this).fileinput({
							language: lang_abbr,
							validateInitialCount: true,
							'previewFileType':'any',
								overwriteInitial: true,
								showUpload: showUpload,
								maxFileSize:maxFileSize,
								allowedFileExtensions: arr_aux
						});
					}

					});
		}

	};

    //function to initiate bootstrap-datepicker
    var runDatePicker = function () {
		/*if($(".elem_datepicker").length){
			$(".elem_datepicker").datepicker({
				format: 'yyyy-mm-dd',
				language: 'pt-PT'
			});
		}*/

		if($(".elem_datepicker_datetime").length){
			$(".elem_datepicker_datetime").datetimepicker({
				minView: 2,
				autoclose: true,
				format: 'yyyy-mm-dd',
				language: 'pt-PT',
				todayHighlight: true
			});
		}

		if($(".elem_datetimepicker_datetime").length){
			$(".elem_datetimepicker_datetime").datetimepicker({
				autoclose: true,
				format: 'yyyy-mm-dd hh:ii',
				language: 'pt-PT',
				todayHighlight: true
			});
		}

		if($(".elem_datetimepicker_time").length){
			$(".elem_datetimepicker_time").datetimepicker({
				startView: 1,
				autoclose: true,
				maxView:1,
				minView:0,
				minuteStep:10,
				format: 'hh:ii',
				language: 'pt-PT',
				todayHighlight: true
			});
			$(".elem_datetimepicker_time").each(function () {
				var dt = todayDate();
				if(typeof $(this).data("value") !== "undefined") {
					var v = $(this).data("value");
					$(this).datetimepicker('update', dt+" "+v);
				}
			});
		}
    };

	 var runDateRangePicker = function () {
		if($(".input-daterange").length){
			$(".startDate, .endDate").on("change", function() {
				var isEnd = $(this).hasClass("endDate");

				var par = $(this).parents(".input-daterange")[0];
				var stEl = $(par).find(".startDate")[0];
				var endEl = $(par).find(".endDate")[0];

				var stVal = $(stEl).val();
				var endVal = $(endEl).val();

				if(stVal!="" && endVal!="") {
					var res = comparedatesType(stVal, endVal, 2);
					if(isEnd && res) {
						$(stEl).val(endVal);
					}
					if(!isEnd && res) {
						$(endEl).val(stVal);
					}
				}

			});
		}
    };

    //function to initiate bootstrap-colorpalette
	var options = {
		colors:[['#32c5d2', '#52c9ba', '#5dc09c', '#84c68f'], ['#cd6262', '#e7505a', '#d05163'], ['#c8d046', '#c5bf66', '#c5b96b'],
		['#5e9cd1', '#5893dd', '#57bfe1'], ['#a962bb', '#aa67a3', '#ac3773'], ['#685e47', '#7a6a61', '#9d8b81'], ['#525e64', '#31383c', '#41515b']]
	}
	var runColorPalette = function () {
		if($('.color-palette').length){
			$('.color-palette').colorPalette(options)
			.on('selectColor', function (e) {
				$('#selected-color1').val(e.color);
				$('#show_color').css("background-color",e.color);
			});
		}
	};

    //function to initiate ckeditor
	var runCKEditor = function () {

		var height = 150;
		if($('.ckeditorfull').length){
			 $('.ckeditorfull').each( function () {
				if(typeof $(this).data("height") !== "undefined") {height = $(this).data("height");}
				CKEDITOR.replace( this.id, {
					allowedContent : true,
					height: height
				} );
			});
		}

		if($('.ckeditorbasic').length){
			 $('.ckeditorbasic').each( function () {
				if(typeof $(this).data("height") !== "undefined") {height = $(this).data("height");}
				CKEDITOR.replace( this.id, {
					toolbarGroups: [
						{"name":"basicstyles","groups":["basicstyles"]},
						{"name":"styles","groups":["styles"]},
						{"name": 'colors' },
						{"name": 'links' }
					],
					allowedContent : true,
					height: height
				} );
			});
		}

		if($('.ckeditornormal').length){
			 $('.ckeditornormal').each( function () {
				if(typeof $(this).data("height") !== "undefined") {height = $(this).data("height");}
				CKEDITOR.replace( this.id, {
					toolbarGroups: [
						{"name":"clipboard","groups":["clipboard"]},
						{"name":"basicstyles","groups":["basicstyles"]},
						{"name":"styles","groups":["styles"]},
						{"name": 'colors' },
						{"name": 'insert' },
						{"name": 'links' }
					],
					removeButtons: "Image,Flash,Smiley,SpecialChar,Iframe,PageBreak,Source",
					allowedContent : true,
					height: height
				} );
			});
		}

		if($('.ckeditorsimple').length){
			var height = 80;
			 $('.ckeditorsimple').each( function () {
				if(typeof $(this).data("height") !== "undefined") {height = $(this).data("height");}
				CKEDITOR.replace( this.id, {
					toolbarGroups: [
						{"name":"basicstyles","groups":["basicstyles"]},
						{"name": 'colors' }
					],
					allowedContent : true,
					height: height
				} );
			});
		}
	};

	return {
        //main function to initiate template pages
        init: function () {
            runInputLimiter();
            runAutosize();
            runMultipleSelect();
            runMultipleSelectSubs();
            runSelect2();
			runSelect2Subs();
            runMaskInput();
            runMaskMoney();
            runDatePicker();
            runDateRangePicker();
            runColorPalette();
           runCKEditor();
			runChecksRadios();
			runFileUpload();
			runFileUploadativity();
        },
        select2: function () {
            runSelect2();
			runSelect2Subs();
        },
        multiSel: function () {
            runMultipleSelect();
            runMultipleSelectSubs();
        },
        date: function () {
            runDatePicker();
        },
        filestart: function () {
            runFileUpload();
        },
				filestart1: function () {
						runFileUploadativity();
				},
        checks: function () {
            runChecksRadios();
        },
        editor: function () {
            runCKEditor();
        }
    };
}();

jQuery(document).ready(function() {
	FormElements.init();
});
