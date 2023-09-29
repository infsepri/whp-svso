var refreshPhotoInt;

$(document).ready(function() {
	if(typeof idinspection === "undefined" || idinspection == "" || idinspection == 0) {
		Swal.fire({
			title: "Impossivel prosseguir sem inspeção. Se o problema persisitir contacte a amp.",
			text: "",
			type: 'error'
		}).then((result) => {
			location.reload();
		});
		return false;
	}
	$(".faseCtrl").on("click", function () {
		switchFase(this);
	});

	setDrags();
	setUploads();

});

function closeInspection() {
	var form = $("#inspectionForm");
	var fd = new FormData();
	var other_data = $(form).serializeArray();
	$.each(other_data,function(key,input){
		fd.append(input.name,input.value);
	});


	startLoad();
	$.ajax({
		url: "?controller=inspect&action=finish",
		type: 'post',
		processData: false,
		contentType: false,
		data: fd ,
		success: function(data) {
			var data = JSON.parse(data);
			console.log(data);
			
			var msgstring = lang[data.msg];
			if (typeof data.msgparams !== "undefined") {
				$.each(data.msgparams, function( index, value ) {
					msgstring = msgstring.replace(":"+index+":", value);
				});
			}
			
			if (typeof data.msglangparam !== "undefined") {
				$.each(data.msglangparam, function( index, value ) {
					text = "";
					if(typeof value === "object") {
						$.each(value, function( i, v ) {
							text += (text == "") ? "" : ", ";
							text += lang[v];
						});
					}
					else {
						text = lang[value];
					}
					msgstring = msgstring.replace(":"+index+":", text);
				});
			}
			
			if(data.code==200) {
				window.location.href = data.href;
			}
			else if(data.code==201) {
				$("#finalModal .footers").hide();
				$("#finalModal .nav").hide();
				$("#finalModal .tab-content").addClass("noBorder noPadding");
				$('#finalModal .nav a[href="#modalCerts"]').tab('show');
				$("#finalModal .continueFooter").show();
				$("#finalModal .continueFooter .btnContinue").on("click", function() {
					finalizeInspection(data.step);
				});

				$("#finalModal .certsWrapper").html("");

				var msgHtml = '<div class="col-xs-12"><div class="alert alert-danger noMargin"><i class="fa fa-exclamation-triangle"></i>';
                msgHtml += ' <strong>Erro:</strong> '+lang[data.msg];
                msgHtml += '</div></div>';
				$("#finalModal .certsWrapper").html(msgHtml);

				endLoad();
				$("#finalModal").modal("show");
			}
			else if(data.code==202) {
				endLoad();
				Swal.fire({
					title: lang["INSPECTION_CANT_FINALIZE_TITLE"],
					text: msgstring,
					type: 'error',
					customClass: {
						popup: 'swal_fullWidth'
					}
				});
			}
		}
	});
}

function finalizeInspection(step) {
	setPhase("faseb");
	step = (typeof step !== "undefined") ? step : 0;
	var form = $("#inspectionForm");
	var fd = new FormData();
	var other_data = $(form).serializeArray();
	$.each(other_data,function(key,input){
		fd.append(input.name,input.value);
	});

	if($(".dragButtons.dropped").length>0) {
		var pointsPos = 0;
		$(".dragButtons.dropped").each(function () {
			var percTop = $(this).attr("topP");
			var percLeft = $(this).attr("leftP");

			var letter = $(this).html();
			fd.append("tridimensional[points]["+pointsPos+"][text]", letter);
			fd.append("tridimensional[points]["+pointsPos+"][top]", percTop);
			fd.append("tridimensional[points]["+pointsPos+"][left]", percLeft);
			pointsPos++;
		});
	}

	fd.append("step", step);

	var url = $(form).attr("action");

	startLoad();
	$.ajax({
		url: url,
		type: 'post',
		processData: false,
		contentType: false,
		data: fd ,
		success: function(data) {

			var data = JSON.parse(data);
			console.log(data);

			$("#finalModal .informationFileVinWrapper").hide();
			$("#finalModal .informationFileWrapper").hide();
			$("#finalModal .footers").hide();
			$("#finalModal .defaultFooter").show();
			$("#finalModal .tab-content").removeClass("noBorder noPadding");
			$("#finalModal .nav").show();
			$("#finalModal .continueFooter .btnContinue").unbind("click");
			$("#finalModal .certEncode").val("");
			$("#finalModal .infofile").val("");

			if(data.code==201) {
				$("#finalModal .footers").hide();
				$("#finalModal .nav").hide();
				$("#finalModal .tab-content").addClass("noBorder noPadding");
				$('#finalModal .nav a[href="#modalCerts"]').tab('show');
				$("#finalModal .continueFooter").show();
				$("#finalModal .continueFooter .btnContinue").on("click", function() {
					finalizeInspection(data.step);
				});
				
				var msgstring = lang[data.msg];
				if (typeof data.msgparams !== "undefined") {
					$.each(data.msgparams, function( index, value ) {
						msgstring = msgstring.replace(":"+index+":", value);
					});
				}
				
				if (typeof data.msglangparam !== "undefined") {
					$.each(data.msglangparam, function( index, value ) {
						text = "";
						if(typeof value === "object") {
							$.each(value, function( i, v ) {
								text += (text == "") ? "" : ", ";
								text += lang[v];
							});
						}
						else {
							text = lang[value];
						}
						msgstring = msgstring.replace(":"+index+":", text);
					});
				}

				endLoad();
				Swal.fire({
					title: lang["INSPECTION_CANT_FINALIZE_TITLE"],
					text: msgstring,
					type: 'error',
					customClass: {
						popup: 'swal_fullWidth'
					}
				});
			}
			else if(data.code==202) {
				$("#finalModal .footers").hide();
				$("#finalModal .nav").hide();
				$("#finalModal .tab-content").addClass("noBorder noPadding");
				$('#finalModal .nav a[href="#modalCerts"]').tab('show');
				$("#finalModal .continueFooter").show();
				$("#finalModal .continueFooter .btnContinue").on("click", function() {
					finalizeInspection(data.step);
				});

				$("#finalModal .certsWrapper").html("");

				var msgHtml = '<div class="col-xs-12"><div class="alert alert-warning noMargin"><i class="fa fa-exclamation-triangle"></i>';
                msgHtml += ' <strong>Erro:</strong> '+lang[data.msg];
                msgHtml += '</div></div>';
				$("#finalModal .certsWrapper").html(msgHtml);

				endLoad();
				$("#finalModal").modal("show");
			}
			else if(data.code==200) {

				if(typeof data.isInfoFile !== "undefined" && data.isInfoFile) {
					$("#finalModal .infofile").val("1");
					$("#finalModal .informationFileWrapper").show();
					if(typeof data.isInfoFileVIN !== "undefined" && data.isInfoFileVIN) {
						$("#finalModal .informationFileVinWrapper").show();
					}
				}


				var askCertA = false;

				$("#finalModal .certEncode").val(JSON.stringify(data.certificates));

				$("#finalModal .certsWrapper").html("");
				$("#finalModal .defsWrapper").html("");
				if(typeof data.certificates !== "undefined") {
					var certCount = Object.keys(data.certificates).length; var showCert = true;
					$.each(data.certificates, function (key, cert) {
						typeAClass = (cert.type == data.insptypeA) ? "certA" : "";
						showAClass = (certCount > 1 && cert.type == data.insptypeA) ? "hidden" : "";
						resClass = (cert.res == data.approvedRes) ? "label-success" : "label-danger";

						var certHtml = '<div class="col-xs-12 '+typeAClass+' '+showAClass+'">';
						certHtml += '<h5 style="color: #ff9c00;"><b>'+cert.typename+'</b></h5>';
						certHtml += '<p class="certLine">'+lang["result"]+': <span class="label '+resClass+' noRadius">'+lang[cert.reslang]+'</span></p>';
						if(cert.validity != "") {
							certHtml += '<p class="certLine"><strong>'+lang["validity"]+': '+cert.validity+'</strong></p>';
						}

						certHtml += '<p class="certLine">'+lang["certificate_nr"]+': <strong>'+cert.certificateshow+'</strong></p>';
						certHtml += '<div class="col-xs-8 col-md-5 noPadding"><div class="form-group"><label class="control-label font-12" >'+lang["printer"]+'</label>';
						certHtml += '<select class="form-control select2 select2_basic valRequired" style="width:100%;" data-modal="finalModal" name=inspection[printer]['+cert.type+']">';
						$.each(cert.printers, function (k, print) {
							certHtml += '<option value="'+print.idequipment+'">'+print.equip.networkname+'</option>';
						});

						certHtml += '</select></div></div>';
						certHtml += '</div>';

						$("#finalModal .certsWrapper").prepend(certHtml);
					});
					FormElements.select2();
					if(certCount > 1) {
						askCertA = true;
					}
				}

				$("#finalModal .defTab").addClass("hidden");
				if(typeof data.deficencies !== "undefined") {
					var defCount = Object.keys(data.deficencies).length;
					if(defCount>0) {
						$("#finalModal .defTab").removeClass("hidden");
					}
					$.each(data.deficencies, function (key, def) {
						var deftxt = def.name;
						if (def.axisnr != null) { deftxt += " ("+def.axisnr+"º "+lang["axis"]+")"; }
						if (def.locations != null && def.locations.length > 0) {
							var locTxt = "";
							$.each(def.locations, function (k, loc) {
								locTxt = (locTxt=="") ? loc.name : locTxt+" / "+loc.name;
							});
							deftxt += " ("+locTxt+")";
						}
						if (def.obs != null && def.obs != "" ) { deftxt += " ("+def.obs+")"; }
						if (def.reinc == 1 ) { deftxt += " ("+lang["reincidence_abr"]+")"; }

						var decoded = $('<div/>').html(deftxt).text();
						deftxt = $('<div/>').html(decoded).text();

						var defHtml = '<div class="col-xs-12">';
						defHtml += '<p class="noMargin">'+def.cod_mae+' - '+deftxt+'</p>';
						defHtml += '</div>';

						$("#finalModal .defsWrapper").append(defHtml);
					});
				}

				if(askCertA) {
					Swal.fire({
						title: lang['ask_print_certA_title'],
						text: lang['ask_print_certA'],
						type: 'warning',
						showCancelButton: true,
						confirmButtonText: lang["yes"],
						cancelButtonText: lang["no"]
					}).then((result) => {
						if (result.value) {
							$("#finalModal .certsWrapper .certA").removeClass("hidden");
							$("#finalModal .ignoreA").val("0");
							FormElements.select2();
						}
						else {
							$("#finalModal .certsWrapper .certA").addClass("hidden");
							$("#finalModal .ignoreA").val("1");
						}
					});
				}
				endLoad();
				$("#finalModal").modal("show");
			}

		}
	});
}

/* SHORTCUTS */
function clickShortcut(id) {
	startLoad();

	$.ajax({
		type: 'POST',
		url: "?controller=inspect&action=shortcut",
		data: {"idinspection":idinspection, "idsoftwareshortcut":id, "servicesession":1},
		success: function(data) {
			data = $.parseJSON(data);
			if(data.code==200) {
				endLoad();
			}
			else {
				endLoad();
				Swal.fire("", lang['SHORTCUT_NOT_POSSIBLE'], "error");
				return false;
			}
		},
		error: function () {
			endLoad();
			Swal.fire("", lang['SHORTCUT_NOT_POSSIBLE'], "error");
			return false;
		}
	});
}

/* EXTRA EQUIPS */
function controlEqextra(el) {
	if($(el).is(":checked")) {
		$(el).parents(".form-group").eq(0).find(".eqExtraWrapper").show();
	}
	else {
		$(el).parents(".form-group").eq(0).find(".eqExtraWrapper").hide();
	}
}
function convertVal(el, v) {
	var val=$(el).val();
	val = parseFloat(val);
	v = parseFloat(v);

	val = val/v*100;
	val = Math.round(val * 100) / 100;

	var elRes = $(el).parents(".form-group").eq(0).find(".convertedVal");
	$(elRes).val(val);
}

/* PHRASES */
function controlPhrase(el) {
	if($(el).is(":checked")) {
		$(el).parents(".form-group").eq(0).find(".phraseVal").addClass("valRequired").show();
	}
	else {
		$(el).parents(".form-group").eq(0).find(".phraseVal").removeClass("valRequired").hide();
	}
}
function updatePhrase(el) {
	var parent = $(el).parents(".form-group").eq(0);
	var string = "xxxxxxxx";
	var strLen = 8;

	var txtel = $(parent).find(".phraseText");
	if($(parent).find(".phraseText").length>0) {
		var txt = $(txtel).text();
		  if(txt==""){txt = $(txtel).val();}
		if(typeof($(txtel).attr("strPos")) === "undefined") {
			var n = txt.indexOf("xxxxxxxx");
			$(txtel).attr("strPos", n);
		}
		if(typeof($(txtel).attr("strLen")) !== "undefined") {
			strLen = parseInt($(txtel).attr("strLen"));
		}
		var pos = parseInt($(txtel).attr("strPos"));
		if(pos==-1) {return false;}

		var append = $(el).val();
		if(append=="") {
			append = string;
		}

		var newLen = append.length;
		var posEnd = parseInt(pos+strLen);
		var newtext = txt.slice(0, pos) + append + txt.slice(posEnd);
		$(txtel).attr("strLen", newLen);
		$(txtel).text(newtext);

		var textarea = $(el).parents(".form-group").eq(0).find("textarea").eq(0);
		$(textarea).text(newtext);
	}

}

/* FIELDS */
function fieldCopy(el, idfill) {
	if($("#"+idfill).val() == "") {
		$("#"+idfill).val($(el).val());
	}
}
function tryFillInfo(result) {
	res = $.parseJSON(result);
	if(typeof res === "object") {
		$.each( res, function( keyOG, positions ) {
			if(typeof positions === "object") {
				$.each(positions, function ( pos, value) {
					if(typeof value === "object") {
						var obj = $("[name=\""+keyOG+"["+pos+"]\"]");
						for(p in value) {
							if(typeof value[p] === "object") {
								for(k in value[p]) {
									var vl = value[p][k];
									var obj = $("[name^=\""+keyOG+"["+pos+"]["+p+"]\"]").eq(k).val(vl).trigger("change").trigger("input");
								}
							}
							else {
								var vl = value[p];
								var obj = $("[name^=\""+keyOG+"["+pos+"]["+p+"]\"]").val(vl).trigger("change").trigger("input");
							}
						}
					}
					else {
						var obj = $("[name=\""+keyOG+"["+pos+"]\"]");
						if(obj.length==1) {
							if(obj.is("[type='radio']") || obj.is("[type='checkbox']")) {
								var valAux = obj.val();
								if(valAux==value) {
									if(obj.prop("disabled")) {
										obj.prop("disabled", false).prop("checked", true).trigger("change").prop("disabled", true).trigger("change");
									}
									else {
										obj.prop("checked", true).trigger("change");
									}
								}
							}
							else{
								obj.val(value).trigger("change").trigger("input");
							}
						}
						else if(obj.length>1) {
							obj.each(function () {

								var valAux = $(this).val();
								if(valAux==value) {
									if($(this).prop("disabled")) {
										$(this).prop("disabled", false).prop("checked", true).trigger("change").prop("disabled", true).trigger("change");
									}
									else {
										$(this).prop("checked", true).trigger("change");
									}
								}
							});

						}
					}
				});
			}
		});
	}
}
function clearModal(idmodal) {
	$("#"+idmodal).find(".valText").val("").trigger("change").trigger("input");
	$("#"+idmodal).find(".valCalc").val("");
	$("#"+idmodal).find(".valCont").html("");
	$("#"+idmodal).find(".valRes").hide();
}
function toggleAll(id) {
	if($("#"+id).hasClass("allOpen")) {
		$("#"+id).find(".collapse").collapse('hide');
		$("#"+id).removeClass("allOpen");
	}
	else {
		$("#"+id).find(".collapse").collapse('show');
		$("#"+id).addClass("allOpen");
	}
}
function saveModal(idmodal) {
	var eq_extra = new FormData();

	var formGBValues = $("#"+idmodal+" :input").serializeArray();
	$.each(formGBValues, function(i, val) {
		eq_extra.append(val.name, val.value);
	});

	eq_extra.append("idinspection", idinspection);

	$.ajax({
		type: 'POST',
		url: "?controller=inspect&action=saveinpectinfo",
		data: eq_extra,
		dataType: 'text',
		contentType: false,
		processData: false,
		success: function(data) {
			data = $.parseJSON(data);
		},
		error: function () {
			console.log("Error saving modal - "+idmodal);
			return false;
		}
	});

}

function valueRes(el) {
	var idmodal = $(el).parents(".modal").eq(0).attr("id");

	var values = []; var limit = 20;
	$("#"+idmodal).find(".valCalc").each(function() {
		values.push($(this).val());
	});
	if(values[0]=="" || values[1]=="") {
		return false;
	}

	$("#"+idmodal).find(".valCont").removeClass("label-danger").removeClass("label-success");
	var dif = Math.abs(values[0]-values[1]);
	if((dif*100/values[0])>limit) {
		$("#"+idmodal).find(".valCont").html(lang["not_compliant"]).addClass("label-danger");
		$("#"+idmodal).find(".valRes").show();
	}
	else {
		$("#"+idmodal).find(".valCont").html(lang["compliant"]).addClass("label-success");
		$("#"+idmodal).find(".valRes").show();
	}
}

/* DEFICENCIES */
function getDeficencyGroups(fase, idgroup) {
	startLoad();
	$.ajax({
		type: 'POST',
		url: "?controller=inspect&action=getgroups",
		data: {"fase":fase, "idgroup":idgroup, "idinspection":idinspection, "servicesession":1},
		dataType: 'html',
		cache: false,
		success: function(data) {
			try {
				data = $.parseJSON(data);
				if(data.code!=200) {
					endLoad();
					Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
					return false;
				}
			}
			catch(err) {
				if($("#modalDeficencyGroup_"+idgroup+"_"+fase).length) {
					$("#modalDeficencyGroup_"+idgroup+"_"+fase).remove();
				}
				$(".modalWrapper").append(data);

				Main.modal();
				$("#modalDeficencyGroup_"+idgroup+"_"+fase).modal("show");
			}
			endLoad();
		},
		error: function () {
			endLoad();
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			return false;
		}
	});
}
function getDeficency(iddeficency) {
	startLoad();
	$.ajax({
		type: 'POST',
		url: "?controller=inspect&action=getdeficency",
		data: {"iddeficency":iddeficency, "idinspection":idinspection, "servicesession":1},
		dataType: 'html',
		cache: false,
		success: function(data) {
			try {
				data = $.parseJSON(data);
				if(data.code!=200) {
					endLoad();
					Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
					return false;
				}
			}
			catch(err) {
				if($("#modalDeficency_"+iddeficency).length) {
					$("#modalDeficency_"+iddeficency).remove();
				}
				$(".modalWrapper").append(data);

				Main.modal();
				FormElements.modalDef();
				$("#modalDeficency_"+iddeficency).modal("show");

			}
			endLoad();
		},
		error: function () {
			endLoad();
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			return false;
		}
	});
}
function controlDefReinc(el) {
	var modal = $(el).parents(".checkHolder").eq(0);

	var def = ($(modal).find(".defCheck").is(":checked")) ? true : false;
	var reinc = ($(modal).find(".reincCheck").is(":checked")) ? true : false;

	if(reinc && !def) {
		if($(el).hasClass("defCheck")) {
			$(modal).find(".reincCheck").prop("checked", false);
		}
		else {
			$(modal).find(".defCheck").prop("checked", true);
		}

	}
}
function saveDef(data) {
	endLoad();
	if(data.code == 200) {
		$("#modalDeficency_"+data.iddeficency).modal("hide");
	}
	else {
		endLoad();
		Swal.fire("", lang["save_deficency_error"], "error");
	}
}

/* FILES */
function getFile(multiple) {
	multiple = (typeof multiple === "undefined") ? 0 : multiple ;
	startLoad();
	/*
	var dataPost = new FormData();

	var formGBValues = $("[name*='gbvalues']").serializeArray();
	$.each(formGBValues, function(i, val) {
		if(val.value!="") {
			dataPost.append(val.name, val.value);
		}
	});

	dataPost.append("idinspection", idinspection);
	dataPost.append("multiple", multiple);
	dataPost.append("servicesession", 1);*/

	$.ajax({
		type: 'POST',
		url: "?controller=inspect&action=filetxtget",
		data: {"idinspection":idinspection, "multiple":multiple, "servicesession":1},
		success: function(data) {
			data = $.parseJSON(data);
			if(data.code==200) {
				if(data.deficencies.length>0) {
					html = '<div class="alert alert-info"><i class="fa fa-info-circle"></i> <strong>'+lang["file_imported"]+'!</strong> '+lang["automatic_defs_added"]+'.</div>';
					html += '<table class="table"><thead><tr><th>'+lang["deficency"]+'</th><th class="text-center">'+lang["degree"]+'</th></tr></thead><tbody>';
					data.deficencies.forEach(function(el) {
						defTxt = el.cod_mae+" - "+el.name;
						if(el.axisnr>0) {
							defTxt += " ("+el.axisnr+"º Eixo)";
						}
						html += '<tr><td class="text-left">'+defTxt+'</td><td class="text-center">'+el.degree+'</td></tr>';
					});
					html += '</tbody></table>'
					endLoad();
					Swal.fire({
						title: '',
						position: 'top',
						customClass: {
							popup: 'swal_fullWidth'
						},
						html: html
					});
				}
				else {
					endLoad();
					Swal.fire("", lang[data.msg], "success");
				}
			}
			else {
				endLoad();
				Swal.fire("", lang[data.msg], "error");
			}
		},
		error: function () {
			endLoad();
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			return false;
		}
	});
}
function setUploads() {
	var typeStr = "<br/> Imagens com a extensão .jpeg, .jpg, .png e .gif";
	var sizeStr = "500Kb";

	$('#plateUploader').fileupload({
		formData: {"idinspection": idinspection, "type":0},
		url: "?controller=inspect&action=uploadInspfile",
		dataType: 'json',
		acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i,
		maxFileSize: 5000000,
		messages: {
			acceptFileTypes: lang["FILEUPLOAD_ERROR_TYPE"]+typeStr,
			maxFileSize: lang["FILEUPLOAD_ERROR_SIZE"]+sizeStr
		},
		done: function (e, data) {
			if(data.result.code != 200) {
				Swal.fire(lang["photo_upload_error_title"], lang[data.result.msg], "error");
			}
			else {
				data.result.forceGreen = true;
				fillPhoto(data.result);
			}
		},
		fail: function (e, data) {
			Swal.fire("", lang["ERROR_SUBMIT_FILE"], "error");
		}
	}).on('fileuploadprocessalways', function (e, data) {
        var index = data.index;
		var file = data.files[index];
        if (file.error) {
			Swal.fire("", file.error, "error");
        }
	}).prop('disabled', !$.support.fileInput).parent().addClass($.support.fileInput ? undefined : 'disabled');
}

/* PHOTO */
function tempPhoto(typeSave) {
	startLoad();

	var phototempid = $("#photoTemp").val();
	$.ajax({
		type: 'POST',
		url: "?controller=inspect&action=tempPhoto",
		data: {"idinspection":idinspection, "phototempid":phototempid, "typeSave":typeSave, "servicesession":1},
		success: function(data) {
			data = $.parseJSON(data);
			typeSave = (typeSave==0) ? true : false;
			if(data.code==200) {
				checkPhoto(typeSave);
				if(typeSave) {
					refreshPhotoInt = setInterval(checkPhoto, 5000);
				}
			}
			else {
				endLoad();
				$("#photoModal").modal("show");
				Swal.fire("", lang['NOT_SAVE_TEMP_PHOTO'], "error");
				return false;
			}
		},
		error: function () {
			endLoad();
			$("#photoModal").modal("show");
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			return false;
		}
	});
}
function checkPhoto(forceOpen) {
	forceOpen = (typeof forceOpen === "undefined") ? false : forceOpen ;
	$.ajax({
		type: 'POST',
		url: "?controller=inspect&action=checkPhoto",
		data: {"idinspection":idinspection, "servicesession":1},
		success: function(data) {
			data = $.parseJSON(data);
			fillPhoto(data, forceOpen)
			endLoad();
		},
		error: function () {
			return false;
		}
	});
}
function forcePhoto(position) {
	startLoad();
	$.ajax({
		type: 'POST',
		url: "?controller=inspect&action=takePhoto",
		data: {"idinspection":idinspection, "position":position, "servicesession":1},
		success: function(data) {
			data = $.parseJSON(data);
			fillPhoto(data);
			endLoad();
		},
		error: function () {
			endLoad();
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			return false;
		}
	});
}
function fillPhoto(data, forceOpen) {
	forceOpen = (typeof forceOpen === "undefined") ? false : forceOpen ;
	$("#photoTemp").val("");
	$(".plateInfo .btn").removeClass("btn-success").removeClass("btn-warning").removeClass("btn-danger");

	$("#photoModal .footers").hide();
	$("#photoModal .defaultFooter").show();

	$(".plateInfo .fa, .plateInfo .far").hide();
	$(".plateInfo .loadcarlicense").show();

	if(data.code==200) {
		clearInterval(refreshPhotoInt);
		$("#photoContainer").attr("src", data.path+data.photo+"?timestamp=" + new Date().getTime());
		$("#photoPlate").text(data.plate);
		$("#photoPercentage").text(data.detect+"%");

		$(".plateInfo .fa, .plateInfo .far").hide();
		if(data.detect<95 && (typeof data.forceGreen === "undefined" || !data.forceGreen)) {
			$(".plateInfo .partialcarlicense").show();
			$(".plateInfo .btn").removeClass("btn-light-grey").addClass("btn-warning");
		}
		else {
			$(".plateInfo .correctcarlicense").show();
			$(".plateInfo .btn").removeClass("btn-light-grey").addClass("btn-success");
		}
	}
	else if(data.code==202) {
		clearInterval(refreshPhotoInt);
		$(".plateInfo .fa, .plateInfo .far").hide();
		$(".plateInfo .errorcarlicense").show();
		$(".plateInfo .btn").removeClass("btn-light-grey").addClass("btn-danger");

		$("#photoModal .footers").hide();
		$("#photoModal .confirmFooter").show();

		$("#photoContainer").attr("src", "data:image/jpg;base64,"+data.photo);
		$("#photoPlate").text(data.plate);
		$("#photoPercentage").text(data.detect+"%");
		$("#photoTemp").val(data.phototempid);

		$("#photoModal").modal("show");
	}
	else {
		$("#photoContainer").attr("src", "");
		$("#photoPlate").text("");
		$("#photoPercentage").text("");
		$(".plateInfo .btn").removeClass("btn-success").removeClass("btn-warning").addClass("btn-light-grey");

		$(".plateInfo .fa, .plateInfo .far").hide();
		$(".plateInfo .loadcarlicense").show();
	}

	if(forceOpen) {
		$("#photoModal").modal("show");
	}
}

/* FASES */
function showAllImgs() {
	$(".allDefsBtn").hide();
	$(".defsImg").show();
}
function switchFase(el){
	var parent = $(el).parents(".fasesWrapper").eq(0);
	$(parent).find("button").removeClass("faseCheck");
	$(el).addClass("faseCheck");

	var divId=$(el).data("id");
	if((divId=="fase2" || divId=="fase3" || divId=="faseb") && $("#lightsModal").length>0 && (!$("#lightsModal").hasClass("ignoreLights"))) {
		if($("#lightsModal .lightsCheck:checked").length<=0) {
			Swal.fire({
				title: lang["check_lights_warning"],
				text: "",
				type: 'warning'
			}).then((result) => {
				if (result.value) {
					$("[data-id='fase1']:visible").eq(0).trigger("click");
				}
			});
			return false;
		}
	}

	if( (divId=="fase3" || divId=="faseb") && $("#photoContainer").attr("src")=="") {
		Swal.fire({
			title: lang["check_photo_warning"],
			text: "",
			type: 'warning'
		}).then((result) => {
			if (result.value) {
				$("[data-id='fase2']:visible").eq(0).trigger("click");
			}
		});
		return false;
	}

	$(".faseShow").hide();
	$("#registrationPlate").hide();
	if(divId == "faseplate") {
		$("#inspAWrapper").hide();
		$("#inspB1Wrapper").hide();
		$("#inspBWrapper").hide();

		$(".buttonsB").show();
		$("#registrationPlate").show();

		$(".buttonsA").hide();
		$('.fases').hide();
		$(el).removeClass("faseCheck");
	}
	else if(divId == "faseb") {
		$("#inspAWrapper").hide();
		$("#inspB1Wrapper").hide();

		$(".buttonsB").show();
		$("#inspBWrapper").show();

		$(".buttonsA").hide();
		$('.fases').hide();

		$("#equipmentsBModal:not(.notFirst)").modal("show").addClass("notFirst");

	}
	else {
		var nr = divId.replace("fase", "");
		$("#inspBWrapper").hide();
		$("#inspB1Wrapper").hide();

		$(".buttonsA").show();
		$("#inspAWrapper").show();

		$(".buttonsB").hide();
		$('.fases').hide();
		
		$(".faseShow_"+nr).show();
		$('#'+divId).show(0, function(){
			$(window).trigger('resize');
		});
	}

	$(".plateInfo").hide();
	if(divId=="fase2") {
		refreshPhotoInt = setInterval(checkPhoto, 5000);
		checkPhoto();
		$(".plateInfo").show();
	}

	setPhase(divId);
}
function setPhase(divId) {
	var phase = 0;
	switch(divId) {
		case "fase2": phase = 1; break;
		case "fase3": phase = 2; break;
		case "faseb": phase = 3; break;
		default: phase = 0; break;
	}

	if(phase>0) {
		$.ajax({
			type: 'POST',
			url: "?controller=inspect&action=setPhase",
			data: {"idinspection":idinspection, "phase":phase, "servicesession":1},
			success: function(data) {
				data = $.parseJSON(data);
			},
			error: function () {
				Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
				return false;
			}
		});
	}
}

/* TIRES */
function getTire(el, index) {
	var idtire = $(el).val();
	if(idtire == "") {return false;}
	$.ajax({
		type: 'POST',
		url: "?controller=vehicle&action=gettire",
		data: {"idtire":idtire, "servicesession":1},
		success: function(data) {
			data = $.parseJSON(data);
			if(typeof data.idtire !== "undefined") {
				$("#pneu_medida_i_"+index).val(data.description);
				$("#tipo_pneu_i_"+index).val(data.version);
				$("#loadIndex"+index).val(data.load_index).trigger("input");
				$("#pressao_i_"+index).val(data.pressure);
				$("#L_aro_i_"+index).val(data.ring_width);
				/*$("#jantes_"+index).html(data.jantes);
				$("#jantes_"+index).parents(".table").eq(0).hide();
				if(data.jantes!="") {
					$("#jantes_"+index).parents(".table").eq(0).show();
				}*/
				$("#L_sessao_pneu_i_"+index).val(data.section_width);
				$("#diametro_pneu_i_"+index).val(data.diameter);
			}
			console.log(data);
		},
		error: function () {
			Swal.fire("", lang['REG_NOT_FOUND'], "error");
			return false;
		}
	});
}
function getLoadindex(readEl, writeEl) {
	var val = $("#"+readEl).val().toLowerCase();
	if(typeof val !== "undefined" && val!="") {
		if (typeof loadindexs[val] !== "undefined") {
			$("#"+writeEl).val(""+loadindexs[val]);
		}
		else {
			$("#"+writeEl).val("Indice inválido.");
		}
	}
	else {
		$("#"+writeEl).val("");
	}
}
function getVelocity(readEl, writeEl) {
	var val = $("#"+readEl).val().toLowerCase();
	if(typeof val !== "undefined" && val!="") {
		if (typeof velocities[val] !== "undefined") {
			$("#"+writeEl).val(""+velocities[val]);
		}
		else {
			$("#"+writeEl).val("Indice inválido.");
		}
	}
	else {
		$("#"+writeEl).val("");
	}
}
function validateTire(){
	var sessao_veiculo = $("#L_sessao_pneu_i_2").val();
	var sessao_documento = $("#L_sessao_pneu_i_1").val();
	if(sessao_veiculo=="" || sessao_documento=="" ){
		swal("Insira os dados");return 0;
	}
	sessao_documento = parseInt(sessao_documento);
	sessao_veiculo = parseInt(sessao_veiculo);
	var result_sessao = "";
	if(sessao_veiculo>=sessao_documento){
		result_sessao = "CONFORME";
		$("#largura_seccao_resultado").css('background-color', 'green');
		$("#largura_seccao_resultado").css('color', 'white');
	}else{
		result_sessao = "NÃO CONFORME";
		$("#largura_seccao_resultado").css('background-color', 'red');
		$("#largura_seccao_resultado").css('color', 'white');
	}

	$("#largura_seccao_gb").val(sessao_veiculo-sessao_documento);


	$("#largura_seccao_documento_resultado").val(sessao_documento);
	$("#largura_seccao_veiculo_resultado").val(sessao_veiculo);
	$("#largura_seccao_resultado").val(result_sessao);

	var diametro_veiculo = $("#diametro_pneu_i_2").val();
	var diametro_documento = $("#diametro_pneu_i_1").val();
	diametro_veiculo = parseInt(diametro_veiculo);
	diametro_documento = parseInt(diametro_documento);
	var tot_diametro = diametro_documento*5/100;
	var result_diametro = "";
	var perc = 100-(diametro_veiculo*100/diametro_documento);

	perc = Math.round(perc * 100) / 100;
	$("#valor_diametro_gb").val(perc);
	if(diametro_veiculo >= diametro_documento-tot_diametro && diametro_veiculo<= tot_diametro+diametro_documento   ){
		result_diametro = "CONFORME:  "+perc+"%";
		$("#diametro_pneu_resultado").css('background-color', 'green');
		$("#diametro_pneu_resultado").css('color', 'white');

	}else{
		result_diametro = "NÃO CONFORME: "+perc+"%";
		$("#diametro_pneu_resultado").css('background-color', 'red');
		$("#diametro_pneu_resultado").css('color', 'white');
	}
	$("#limite_diametro_pneu_sup").html(Math.round((tot_diametro+diametro_documento) * 100) / 100);
	$("#limite_diametro_pneu_inf").html(Math.round((diametro_documento-tot_diametro) * 100) / 100);
	$("#diametro_pneu_documento_resultado").val(diametro_documento);
	$("#diametro_pneu_veiculo_resultado").val(diametro_veiculo);
	$("#diametro_pneu_resultado").val(result_diametro);
	$("#tireResultModal").modal("show");

}

/* TRIDIMENSIONAL FUNCTIONS */
function setDrags() {
	if($(".dragButtons").length) {
		$(".dragButtons").draggable({
			containment: ".imageContainer",
			start: function( event, ui ) {
				if($(".imageContainer").length<1) {
					swal.fire(lang["choose_one_image"]);
					event.preventDefault();
					return false;
				}
				ui.helper.addClass("dropped");
			},
			stop: function (event, ui) {
				var imageTop = $(".imageContainer").eq(0).offset().top ;
				var imageLeft = $(".imageContainer").eq(0).offset().left;
				var imageWidth = $(".imageContainer").eq(0).width();
				var imageHeight = $(".imageContainer").eq(0).height();
				var el = ui.helper;

				var tTop = $(el).offset().top;
				var tLeft = $(el).offset().left;

				var percTop = (tTop-imageTop+5)*100/imageHeight;
				var percLeft = (tLeft-imageLeft+5)*100/imageWidth;

				$(el).attr("topP", percTop);
				$(el).attr("leftP", percLeft);
			}
		});
	}
}
function tridPin(lim) {
	if($("#trid_distpinorval").length>0) {
		var distpinorval = $("#trid_distpinorval").val();
		if(distpinorval!="") {
			distpinorval = parseFloat(distpinorval);

			if(lim>0 && distpinorval>lim) {
				$("#trid_res6").val(1).trigger("change");
			}
			else {
				$("#trid_res6").val(2).trigger("change");
			}
		}
	}
}
function tridArch(lim) {
	if($("#trid_distpinofval").length>0 && $("#trid_largval").length>0) {
		var distpinofval = $("#trid_distpinofval").val();
		var largval = $("#trid_largval").val();
		if(distpinofval!="" && largval!="") {
			largval = parseFloat(largval);
			distpinofval = parseFloat(distpinofval);
			var distarco = Math.sqrt((largval * largval) + (distpinofval * distpinofval));

			distarco = Math.round(distarco*100)/100;

			$("#trid_distarco").val(distarco);
			if(lim>0 && distarco>lim) {
				$("#trid_res5").val(1).trigger("change");
			}
			else {
				$("#trid_res5").val(2).trigger("change");
			}
		}
	}
}
function controlImage(el) {
	if($(el).is(":checked")) {
		var val = $(el).val();
		var path = $(".imageContainer img").eq(0).attr("path");

		$(".imageContainer img").eq(0).attr("src", ""+path+val);
	}

	clearTridPoints();
}
function clearTridPoints() {
	$(".dragButtons").animate({
        top: "0px",
        left: "0px"
    }).removeClass("dropped");
}
function controlTridModal() {
	$('#tridimensionalModalImg').modal('hide');
	$('#tridimensionalModal').modal('hide');
	if($(".tridImageSel:checked").length==0) {
		$('#tridimensionalModalImg').modal('show');
	}
	else {
		$('#tridimensionalModal').modal('show');
	}

}
function tridDif(elem) {
	var p = $(elem).parents(".valuesWrapper").eq(0);
	if($(p).find(".difVal").length>1) {
		var val1 = $(p).find(".difVal").eq(0).val();
		var val2 = $(p).find(".difVal").eq(1).val();
		if(val1!="" && val2!="") {
			var dif = Math.abs(val1-val2);
			$(p).find(".difValues").eq(0).val(dif);
			checkResultDif(p);
		}
	}
}
function tridRes() {
	var i = 1;
	var res = 2;
	var count = 0;
	for(i=1; i<=6; i++) {
		if($("#trid_res"+i).length>0 && $("#trid_res"+i).val()!="") {
			if(res==2) {
				res = $("#trid_res"+i).val();

			}

			if($("#trid_res"+i).val()==2) {
				$("#trid_res"+i).parent().find(".tridResVal1").hide();
				$("#trid_res"+i).parent().find(".tridResVal2").show();
			}
			else {
				$("#trid_res"+i).parent().find(".tridResVal2").hide();
				$("#trid_res"+i).parent().find(".tridResVal1").show();
			}
			count++;
		}
		else {
			$("#trid_res"+i).parent().find(".tridResVal1, .tridResVal2").hide();
		}
	}
	if(count>0) {
		$("#trid_resf").val(res);
		if(res==2) {
			$("#trid_resf").parent().find(".tridResVal1").hide();
			$("#trid_resf").parent().find(".tridResVal2").show();
		}
		else {
			$("#trid_resf").parent().find(".tridResVal2").hide();
			$("#trid_resf").parent().find(".tridResVal1").show();
		}

	}
}
function checkResultDif(el) {
	var lim = parseFloat($(el).data("limit"));
	var resnr = $(el).data("res");
	var res = 2;
	$(el).find(".difValues").each(function() {
		if($(this).val()!="" && lim>0 && $(this).val()>lim) {
			res = 1;
		}
		if(res == 1) { return false; }
	});

	$("#trid_res"+resnr).val(res).trigger("change");
}
function tridAxis(lim, total) {
	$("#trid_res1").val("");
	$("#trid_res2").val("");
	$("#trid_distmed").val("");
	$("#trid_difesq").val("");
	$("#trid_difdir").val("");
	$("#trid_diftot").val("");

	if($("#trid_docval").length>0 && $("#trid_distesq").length>0 && $("#trid_distdir").length>0) {
		var docval = $("#trid_docval").val();
		var distesq = $("#trid_distesq").val();
		var distdir = $("#trid_distdir").val();
		if(distesq!="" && distdir!="") {
			distesq = parseInt(distesq);
			distdir = parseInt(distdir);
			var distmed = (distesq+distdir)/2;
			var diftotal = Math.abs(distesq-distdir);

			$("#trid_distmed").val(distmed);
			$("#trid_diftot").val(diftotal);

			if(total>0 && diftotal>total) {
				$("#trid_res2").val(1).trigger("change");
			}
			else {
				$("#trid_res2").val(2).trigger("change");
			}
		}
		if(docval!="" && distesq!="" && distdir!="") {
			distesq = parseInt(distesq);
			distdir = parseInt(distdir);
			docval = parseInt(docval);
			var distmed = (distesq+distdir)/2;
			var difesq = (Math.abs(docval-distesq)/docval)*100;
			var difdir = (Math.abs(docval-distdir)/docval)*100;
			var diftotal = Math.abs(distesq-distdir);

			difesq = Math.round(difesq*100)/100;
			difdir = Math.round(difdir*100)/100;

			$("#trid_distmed").val(distmed);
			$("#trid_difesq").val(difesq);
			$("#trid_difdir").val(difdir);
			$("#trid_diftot").val(diftotal);
			if(lim>0 && (difdir>lim || difesq>lim)) {
				$("#trid_res1").val(1).trigger("change");
			}
			else {
				$("#trid_res1").val(2).trigger("change");
			}
			if(total>0 && diftotal>total) {
				$("#trid_res2").val(1).trigger("change");
			}
			else {
				$("#trid_res2").val(2).trigger("change");
			}
		}
	}
}
