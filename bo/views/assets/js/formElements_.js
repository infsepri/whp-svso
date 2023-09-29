var init_select;
var objs_select=[];
var multiValues=[];

function multipleAjax(el) {
	var elem = el.$select[0];
	if((typeof $(elem).data("url") !== "undefined") && !$(elem).prop("disabled")) {
		var params = (typeof $(elem).data("params") !== "undefined") ? $(elem).data("params") : "";
		var url = $(elem).data("url");
		var arrPos = (typeof $(elem).data("arr") !== "undefined") ? $(elem).data("arr") : "";

		$.ajax({
			url: url,
			data: {params: params},
			method: 'POST',
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
						if(!$(elem).prop("multiple")) {
							$(elem).val("").multiselect("refresh");
						}
						$(elem).multiselect('select', multiValues[""+arrPos]);
					}
				}

				if($(elem).val().length==0  && data.length == 1) {
					$(elem).multiselect('selectAll', false).multiselect('updateButtonText').trigger("change");
				}

				if($(elem).hasClass("addDisabled")) {
					$(elem).multiselect('disable');
				}
			}
		});
		$(elem).unbind("change").on("change", function () {
			var arrPos = (typeof $(this).data("arr") !== "undefined") ? $(this).data("arr") : "";

			multiValues[arrPos] = $(this).val();
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
			$(".awradio.allowUncheck label").unbind("click").on("click", function() {
				if($(this).parent().find("input").eq(0).prop("checked")) {
					$(this).parent().find("input").eq(0).prop("checked", false);
				}
				else {
					$(this).parent().find("input").eq(0).trigger("click");
				}
			});
			$(".awradio:not(.allowUncheck) label").unbind("click").on("click", function() {
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
				enableCollapsibleOptGroups = ($(this).data("groups")) ? true : false;
				enableClickableOptGroups = ($(this).data("groups")) ? true : false;
				numberDisplayed = (typeof $(this).data("numberdisplayed") !== "undefined") ? parseInt($(this).data("numberdisplayed")) : 3;
				allSelectedText = (typeof $(this).data("allfalse") !== "undefined") ? false : lang['all_selected'];
				nonSelectedText = (typeof $(this).data("placeholder") !== "undefined") ? $(this).data("placeholder") : lang['select_option'];

				$(this).multiselect({
					includeSelectAllOption: true,
					enableCaseInsensitiveFiltering: true,
					enableFiltering: true,
					maxHeight:300,
					buttonWidth: '100%',
					numberDisplayed: numberDisplayed,
					selectedClass: 'multiselect-selected',
					filterPlaceholder: lang['search'],
					nSelectedText: lang['selected'],
					nonSelectedText: nonSelectedText,
					selectAllText: lang['select_all'],
					enableCollapsibleOptGroups: enableCollapsibleOptGroups,
					enableClickableOptGroups: enableClickableOptGroups,
					onInitialized: function(event) {
						multipleAjax(this);
					},
					onDropdownShow: function(event) {
						multipleAjax(this);
					},
					onDropdownHide: function(event) {
						var el = this.$select[0];
						$(el).trigger("multiUpd");
					},
					allSelectedText:allSelectedText
				});


				if($(this).hasClass("closeMultiSel")) {
					$(this).on("change", function() {
						$(this).parent().find(".btn-group.open > button").eq(0).trigger("click");
					});
				}
			});
		}
	};
	var runMultipleSelectSubs = function () {
		if($(".multiselectPrimary").length>0) {
			$(".multiselectPrimary").each(function () {
				$(this).unbind("multiUpd").on("multiUpd", function(ev) {
					var line = $(this).attr("identify");
					var vl = $(this).val();
					var id_ = $(this).attr("id");
					if($(this).hasClass("select2")) {
					  if(typeof objs_select[id_]=='undefined' || typeof objs_select[id_][vl]=='undefined' || objs_select[id_][vl].count==0){
							$(".multiselectSub_"+line).val(null).trigger("change")
							$(".multiselectSub_"+line).data("params", vl+"::");
						}
						else{
							$(".multiselectSub_"+line).data("params", vl+"::");
							$(".multiselectSub_"+line).val(null).change();
						}
					}
					else {
						if($.isEmptyObject(vl)) {vl="0";}
						if(typeof vl !== "string") { vl = vl.join("::"); }
						$(".multiselectSub_"+line).data("params", vl+"::");
						$(".multiselectSub_"+line).val(null).change();
					}

					$(".subOpen.multiselectSub_"+line).parent().find(".dropdown-toggle").trigger("click").trigger("click");
				});

				if($(this).hasClass("select2")) {
					$(this).on("change", function() {
						$(this).trigger("multiUpd");
					});
				}
			});
		}
	};

    //function to initiate Select2
	var runSelect2 = function () {
		$( ".select2_basic" ).each(function( index ) {
			var placeholder = null;  var allowClear = false;var modal=null;

			if(typeof $(this).data("modal") !== "undefined") {
				modal = $(this).data("modal");
				modal = $('#'+modal);
			}

			if(typeof $(this).data("allowclear") !== "undefined") {
				allowClear = true;
			}
			if(typeof $(this).data("placeholder") !== "undefined") {
				placeholder = $(this).data("placeholder");
			}
			$(this).select2({
				minimumResultsForSearch: -1,
				language: lang_abbr,
				dropdownParent: modal,
				allowClear : allowClear,
				placeholder: placeholder
			});
		});

		$( ".select2_basic_search" ).each(function( index ) {
			var placeholder = null; var allowClear = false;var modal=null;

			if(typeof $(this).data("modal") !== "undefined") {
				modal = $(this).data("modal");
				modal = $('#'+modal);
			}

			if(typeof $(this).data("allowclear") !== "undefined") {
				allowClear = true;
			}
			if(typeof $(this).data("placeholder") !== "undefined") {
				placeholder = $(this).data("placeholder");
			}
			$(this).select2({
				placeholder: placeholder,
				dropdownParent: modal,
				allowClear : allowClear,
				language: lang_abbr
			});
		});

		$( ".selected2_p" ).each(function( index ) {
			if (!$(this).hasClass("select2-hidden-accessible")) {
				var placeholder = null; var allowClear = false; var modal=null; var all_value = "null"; var all_text=null; var all = false; var minimumInputLength = 0;
				if(typeof $(this).data("placeholder") !== "undefined") {
					placeholder = $(this).data("placeholder");

				}
				if(typeof $(this).data("allowclear") !== "undefined") {
					allowClear = true;
				}
				if(typeof $(this).data("minsearch") !== "undefined") {
					minimumInputLength = parseInt($(this).data("minsearch"));
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
				var el = $(this);
				var closeOnSelect = true;

				var te=undefined;
				if(typeof $(el).attr("data-addattr")!=="undefined"){
					var te = $(el).attr("data-addattr") ;
				}
				$(this).select2({
					ajax: {
						url: function () { return $(this).data('url'); },
						dataType: 'json',
						delay: 250,
						data: function (params) {
							if($(this).data("fieldupd") !== "undefined") {
								var fldId = $(this).data("fieldupd");
								$("#"+fldId).val(params.term);
							}
							if (typeof $(this).data('params') !== 'undefined') {
								var res = $(this).data('params').split("::");
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
							objs_select[$(el).attr("id")]=[];
							if(all){
								var decoded = $('<div/>').html(all_text).text();
								data.unshift({select_show:decoded, id:all_value});
							}
							return {
								results: $.map(data, function(obj) {

									 var showicon = 0;
									 if(typeof obj.showicon !== "undefined"){
										showicon = obj.showicon;
									 }
									if((typeof obj.all !== "undefined") && obj.all == 1) {

										var decoded = $('<div/>').html(lang[obj.select_show]).text();
										objs_select[$(el).attr("id")][obj.id]=obj;

										return { text: decoded, id: obj.id, "selected":true, showicon: showicon };
									}
									else {
										var decoded = $('<div/>').html(obj.select_show).text();
										objs_select[$(el).attr("id")][obj.id]=obj;


										if(typeof $(el).attr("data-addattr")!=="undefined"){
											var valueaddattr= $(el).attr("data-valueaddattr");
											var addattr = $(el).attr("data-addattr") ;

											return { text: decoded, id: obj.id, "selected":true, [addattr]:obj[valueaddattr] , showicon: showicon};
										}


										return { text: decoded, id: obj.id, "selected":true, showicon: showicon };
									}

								}),
								pagination: {
									more: (params.page * 30) < data.total_count
								}
							};
						},
						success: function (data) { //console.log(data);
							if($(el).val() == "") {
								if(data.length == 1) {
									if(typeof $(el).attr("data-forceselect") === "undefined" ){
										var selectOp = data[0];
										var decoded = $('<div/>').html(selectOp.select_show).text();
										if(typeof $(el).attr("data-nonselected")==="undefined" || $(el).attr("data-nonselected")==false){
											var option = new Option(decoded, selectOp.id, true, true);
										}else{
											var option = new Option(decoded, selectOp.id, false, false);
										}
										var showicon = 0;
										if(typeof selectOp.showicon!=="undefined"){ showicon= selectOp.showicon;}
										$(option).attr("showicon", showicon);
										$(el).append(option).trigger('change');
									}
								}
								else if(typeof $(el).data("selected") !== "undefined") {
									var sel = $(el).data("selected");
									$(data).each(function() {
										if(this.id == sel) {
											var decoded = $('<div/>').html(this.select_show).text();
											if(typeof $(el).attr("data-nonselected")==="undefined" || $(el).attr("data-nonselected")==false){
												var option = new Option(decoded, this.id, true, true);
											}else{
												var option = new Option(decoded, this.id, false, false);
											}
											var showicon = 0;
											if(typeof this.showicon!=="undefined"){ showicon= this.showicon;}
											$(option).attr("showicon", showicon);
											$(el).append(option).trigger('change');
											return false;
										}
									});
								}

								if(typeof $(el).attr("data-selectFirst") !== "undefined" ){
									var selectOp = data[0];
									var decoded = $('<div/>').html(selectOp.select_show).text();
									var option = new Option(decoded, selectOp.id, true, true);
									var showicon = 0;
											if(typeof selectOp.showicon!=="undefined"){ showicon= selectOp.showicon;}
											$(option).attr("showicon", showicon);
									$(el).append(option).trigger('change');
									return false;

								}


							}
						},
						cache: true
					},
					templateSelection: function(container) {
						if(typeof container[te] !=="undefined"){
							$(container.element).attr(te, container[te]);
						}
						if(typeof container['showicon'] !=="undefined"){
							$(container.element).attr('showicon', container['showicon']);
						}

						if (typeof $(container.element).attr("showicon") ==="undefined" || $(container.element).attr("showicon")==0) { return container.text; }
							var $opt = $(
								'<span><img src="views/assets/img/icon/'+container.text+'.PNG" width="20px" /> </span>'
							);

						return $opt;

			    },
					dropdownParent: modal,
					allowClear : allowClear,
					placeholder: placeholder,
					closeOnSelect: true,
					language: lang_abbr,
					escapeMarkup: function (markup) { return markup; },
					minimumInputLength: minimumInputLength,
					formatSelection: function  (obj) { return obj.id; },
					templateResult: formatState ,
					formatResult: function  (obj) { return obj.id; }
				});
			}



		});
	};

	function formatState (state) {
		if (typeof state.showicon ==="undefined" || state.showicon==0) { return state.text; }
		var $opt = $(
			'<span><img src="views/assets/img/icon/'+state.text+'.PNG" width="20px" /> </span>'
		 );

	   return $opt;
	  };

	var runSelect2Subs = function () {

		if($(".selectPrimary").length>0) {
			$(".selectPrimary").unbind("changePrim").on("changePrim", function(ev) {
				var line = $(this).attr("identify");
				//if(typeof stop !== "undefined") {stop=false;}
				var vl = $(this).val();
				var id_ = $(this).attr("id");
				if((typeof objs_select[id_] =='undefined' ||  typeof objs_select[id_][vl]=='undefined' || objs_select[id_][vl].count==0) && vl==""){
					//$(".selectSub_"+line).prop("disabled", true);
					$(".selectSub_"+line).val("").trigger("change")
					$(".selectSub_"+line).data("params", "0::");
					$(".reqClass.selectSub_"+line).removeClass("valRequired");
					$(".reqClass.selectSub_"+line).parent().find(".symbol.required").addClass("hidden");
				}
				else{
					$(".reqClass.selectSub_"+line).parent().find(".symbol.required").removeClass("hidden");
					$(".reqClass.selectSub_"+line).addClass("valRequired");

					$(".selectSub_"+line).data("params", vl+"::");
					$(".selectSub_"+line).val(null).change();
				}
				$(".subOpen.selectSub_"+line).select2('open').select2('close');

				if($(".selectSub_"+line).data("all")) {
					var allVal = $(".selectSub_"+line).data("allvalue");
					$(".selectSub_"+line).val(allVal).change();
				}

			});
			$(".selectPrimary").on("change", function() {
				$(this).trigger("changePrim");
			});
		}





		if($(".selectSubPrimary").length>0) {
			$(".selectSubPrimary").unbind("changePrim").on("changePrim", function(ev) {
				var line = $(this).attr("identify");
				//if(typeof stop !== "undefined") {stop=false;}
				var vl = $(this).val();
				var id_ = $(this).attr("id");
				if((typeof objs_select[id_] =='undefined' ||  typeof objs_select[id_][vl]=='undefined' || objs_select[id_][vl].count==0) && vl==""){
					//$(".selectsubSub_"+line).prop("disabled", true);
					$(".selectsubSub_"+line).val("").trigger("change")
					$(".selectsubSub_"+line).data("params", "0::");
					$(".reqClass.selectsubSub_"+line).removeClass("valRequired");
					$(".reqClass.selectsubSub_"+line).parent().find(".symbol.required").addClass("hidden");
				}
				else{
					$(".reqClass.selectsubSub_"+line).parent().find(".symbol.required").removeClass("hidden");
					$(".reqClass.selectsubSub_"+line).addClass("valRequired");

					$(".selectsubSub_"+line).data("params", vl+"::");
					$(".selectsubSub_"+line).val(null).change();
				}
				$(".subOpen.selectsubSub_"+line).select2('open').select2('close');

				if($(".selectsubSub_"+line).data("all")) {
					var allVal = $(".selectsubSub_"+line).data("allvalue");
					$(".selectsubSub_"+line).val(allVal).change();
				}

			});
			$(".selectSubPrimary").on("change", function() {
				$(this).trigger("changePrim");
			});
		}

	};
	var runSelectForceOpen = function () {
		$(".selOpen").select2('open').select2('close');
		setTimeout(function(){ $(".selOpen").trigger("change").trigger("multiUpd"); }, 90);
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

		if($('.input-mask-timepick').length){
			$('.input-mask-timepick').mask('00:00');
		}

		if($('.input-mask-timesecpick').length){
			$('.input-mask-timesecpick').mask('00:00:00');
		}

	};
	var runMaskMoney = function () {
		if($('.currency').length){
			$(".currency").maskMoney({
				thousands: ""
			});
		}
	};

	var runTimePicker = function() {
		if($(".time_picker").length){
			$('.time_picker').timepicker({
				template: false,
				showInputs: false,
				maxHours: 24,
				minuteStep: 5,
				showMeridian: false,
				snapToStep: false,
				defaultTime: false
			});
		}
	}

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

		if($(".elem_datepicker_year").length){
			$(".elem_datepicker_year").datetimepicker({
				minView: 4,
				startView: 4,
				autoclose: true,
				format: 'yyyy',
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
			var dt = todayDate();
			$(".elem_datetimepicker_time").each(function () {
				$(this).datetimepicker({
					startView: 1,
					autoclose: true,
					maxView:1,
					minView:0,
					minuteStep:10,
					format: 'hh:ii',
					language: 'pt-PT',
					todayHighlight: true
				});
				if(typeof $(this).data("value") !== "undefined") {
					var v = $(this).data("value");
					$(this).datetimepicker("update", dt+" "+v);
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
				var par = $(e.target).parents(".colorContainer").eq(0);
				console.log(e);
				$(par).find(".colorInput").val(e.color);
				$(par).find(".colorShow").css("background-color",e.color);
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


	var runFileUploadimage = function () {
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




	//initiate postalcodes
	/*var runPostalCode = function () {
		if($('.postalcode').length){
			$('.postalcode').on("change", function() {
				var attridentify = $(this).attr('identify');

				if($(this).val()=='' || $(this).val()=='unknown'){
					return 0;
				}
				if(($(".country_local"+attridentify).val()!='193' && $(".country_local"+attridentify).val()!='193-1' && $(".country_local"+attridentify).val()!='193-2' && $(".country_local"+attridentify).val()!='193-3') && ($(".country_local"+attridentify).val()!='PT' && $(".country_local"+attridentify).val()!='PT-MA'  && $(".country_local"+attridentify).val()!='PT-AC') ){
					return 0;
				}

				$(".locality"+attridentify).prop("readonly", true);

				$.getJSON("?controller=home&action=getpostalcode&postalcode="+$(this).val() , function (json) {
					if(json.code==200){
						$(".locality"+attridentify).val(json.designation).change();

						if($(".district_local"+attridentify).length) {
							$(".district_local"+attridentify).val($('.district_local'+attridentify+' option:contains("'+json.district+'")').val()).change();
						}

						if($(".county_local"+attridentify).length) {
							$(".county_local"+attridentify).val($('.county_local'+attridentify+' option:contains("'+json.county+'")').val()).change();
						}

						if($(".county_local"+attridentify).length) {
							$(".county_local"+attridentify).val($('.county_local'+attridentify+' option:contains("'+json.county+'")').val()).change();
						}

					}else{
						swal(lang['postalcodeinvalid']);
						$(".locality"+attridentify).val('');
					}

					$(".locality"+attridentify).prop("readonly", false);
					$(".locality"+attridentify).removeProp("readonly");

				});
			});
		}
	}; */

	//function to initiate toogle
	var runToggle = function () {
		var height = 25;
		if($('.toggle_basic').length){
			$('.toggle_basic').each( function () {
				if(typeof $(this).data("height") !== "undefined") {height = $(this).data("height");}
				$(this).bootstrapToggle({
					height: height
				});
			});
		}
	};

	return {
        //main function to initiate template pages
        init: function () {
            runInputLimiter();
            runAutosize();


            runSelect2();
			runSelect2Subs();
			runMultipleSelectSubs();
			runMultipleSelect();

            runMaskInput();
            runMaskMoney();
            runTimePicker();
            runDatePicker();
            runDateRangePicker();
            runColorPalette();
            runCKEditor();
			runChecksRadios();
			runToggle();
			//runPostalCode();
			runSelectForceOpen();
			runFileUploadimage();
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
        checks: function () {
            runChecksRadios();
        },
        toggle: function () {
            runToggle();
        },
        time: function () {
            runTimePicker();
        },
        modalDef: function () {
            runChecksRadios();
            runMultipleSelect();
        }
    };
}();

jQuery(document).ready(function() {
	FormElements.init();
});




function select2_search ($el, term) {
  $el.select2('open');

  // Get the search box within the dropdown or the selection
  // Dropdown = single, Selection = multiple
  var $search = $el.data('select2').dropdown.$search || $el.data('select2').selection.$search;
  // This is undocumented and may change in the future

  $search.val(term);
  $search.trigger('keyup');
}
