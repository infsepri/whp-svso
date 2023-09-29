function phraseText(el) {
	var val = $(el).val();
	var opt = $(el).find("option[value='"+val+"']").eq(0);
	var html = $(opt).html();
	
	$(".phraseText").val(html);
}

function fieldCopy(el, idfill) {
	if($("#"+idfill).val() == "") {
		$("#"+idfill).val($(el).val());
	}
}

function certsCalc(idinspection) {
	startLoad();
	$.ajax({
		type: 'POST',
		url: "?controller=inspection&action=revisecertificatecalc",
		data: {"idinspection":idinspection, "servicesession":1},
		success: function(data) {
			data = $.parseJSON(data);
			
			var askOthers = false;
			
			$("#certsModal .informationFileVinWrapper").hide();
			$("#certsModal .informationFileWrapper").hide();
			$("#certsModal .certEncode").val("");
			$("#certsModal .certsWrapper").html("");
			$("#certsModal .infofile").val("");
			
			if(data.code!=200) {
				endLoad();
				Swal.fire({
					title: lang["CERTIFICATE_CALCULATE_ERROR"],
					text: lang[data.msg],
					type: 'error'
				});
			}
			else {
				if(typeof data.isInfoFile !== "undefined" && data.isInfoFile) {
					$("#certsModal .infofile").val("1");
					$("#certsModal .informationFileWrapper").show();
					if(typeof data.isInfoFileVIN !== "undefined" && data.isInfoFileVIN) {
						$("#certsModal .informationFileVinWrapper").show();
					}
				}
				
				if(typeof data.certificates !== "undefined") {
					$("#certsModal .certEncode").val(JSON.stringify(data.certificates));
					var certCount = Object.keys(data.certificates).length; var showCert = true; var countChosen = 0;
					$.each(data.certificates, function (key, cert) {
						othersClass = (cert.chosen != 1) ? "certOther" : "";
						showothersClass = (certCount > 1 && cert.chosen != 1) ? "hidden" : "";
						resClass = (cert.res == data.approvedRes) ? "label-success" : "label-danger";
						if(cert.chosen == 1) {countChosen++;}
						

						var certHtml = '<div class="col-xs-12 '+othersClass+' '+showothersClass+'">';
						certHtml += '<h5 style="color: #ff9c00;"><b>'+cert.typename+'</b></h5>';
						certHtml += '<p class="certLine">'+lang["result"]+': <span class="label '+resClass+' noRadius">'+lang[cert.reslang]+'</span></p>';
						if(cert.validity != "") {
							certHtml += '<p class="certLine"><strong>'+lang["validity"]+': '+cert.validity+'</strong></p>';
						}

						certHtml += '<p class="certLine">'+lang["certificate_nr"]+': <strong>'+cert.certificateshow+'</strong></p>';
						certHtml += '<div class="col-xs-8 col-md-5 noPadding"><div class="form-group"><label class="control-label font-12" >'+lang["printer"]+'</label>';
						certHtml += '<select class="form-control select2 select2_basic valRequired" style="width:100%;" data-modal="certsModal" name=certificate[printer]['+cert.type+']">';
						$.each(cert.printers, function (k, print) {
							certHtml += '<option value="'+print.idequipment+'">'+print.equip.networkname+'</option>';
						});

						certHtml += '</select></div></div>';
						certHtml += '</div>';

						$("#certsModal .certsWrapper").prepend(certHtml);
					});
					FormElements.select2();
					if(certCount != countChosen) {
						askOthers = true;
					}
				}
				
				if(askOthers) {
					Swal.fire({
						title: lang["ask_print_certA"],
						text: "",
						showCancelButton: true,
						confirmButtonText: lang["yes"],
						cancelButtonText: lang["no"]
					}).then((result) => {
						if (result.value) {
							$("#certsModal .certsWrapper .certOther").removeClass("hidden");
							$("#certsModal .ignoreOther").val("0");
							FormElements.select2();
						}
						else {
							$("#certsModal .certsWrapper .certOther").addClass("hidden");
							$("#certsModal .ignoreOther").val("1");
						}
					});
				}
			}
			
			endLoad();
			$("#certsModal").modal("show");
		},
		error: function () {
			endLoad();
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			return false;
		}
	});
	
}