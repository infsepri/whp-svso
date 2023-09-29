function showInfo(idequipmentactivity, disabled) {
	if (typeof idequipmentactivity !== undefined && idequipmentactivity!=0) {
		$.ajax({
			type: 'POST',
			url: '?controller=equipmenthistoric&action=getInformation',
			data: {idequipmentactivity:idequipmentactivity, service:1},
			dataType: 'text',
			cache: false,
			success: function(result) {
				data = $.parseJSON(result);
				if(typeof data.code !== "undefined") {
					if(data.code == 200) {
						if(data.modal == "modalEquipAway") {
							fillEquipAway(data, disabled);
						}
						if(data.modal == "modalEquipReceive") {
							fillEquipReceive(data, disabled);
						}
					}
					else {
						var msgstring = lang[data.msg];
						if (typeof data.msgparams !== "undefined") {
							$.each(data.msgparams, function( index, value ) {
								msgstring = msgstring.replace(":"+index+":", value);
							});
						}
						var type = "error";
						Swal.fire({
							title: "",
							text: msgstring,
							type: type
						});
					}
				}
				else {
					Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				/*alert(xhr.status);
				alert(thrownError);*/
				Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			}
		});
	}
}

function controlState(el) {
	var container = $(el).parents(".rowContainer").eq(0);
	var val = parseInt($(el).val());
	$(container).find(".infoBtn").hide();
	if(val!="") {
		if($.inArray(val, infoStates) !== -1) {
			$(container).find(".infoBtn").show();
			
			if($.inArray(val, awayStates) !== -1) {
				fillEquipAway(undefined, false);
			}
			else if($.inArray(val, receiveStates) !== -1) {
				fillEquipReceive(undefined, false);
			}
		}
	}
}

function fillEquipAway(data, disabled) {
	$("#modalEquipAway .modal-title .tChoose").show();
	$("#modalEquipAway .modal-title .tShow").hide();
	
	if(typeof data !== "undefined" && typeof data.supplier !== "undefined") {
		$("#modalEquipAway .modal-title .tChoose").hide();
		$("#modalEquipAway .modal-title .tShow").show();
		
		var selEl = $("#modalEquipAway select").eq(0);
		
		var decoded = $('<div/>').html(data.supplier.name).text();
		var option = new Option(decoded, data.supplier.idsupplier, true, true);
		$(selEl).append(option).trigger('change');
		$(selEl).prop("disabled", false);
		
		if(disabled) {
			$(selEl).prop("disabled", true);
		}
	}

	$("#modalEquipAway .modal-footer").show();
	if(disabled) { $("#modalEquipAway .modal-footer").hide(); }
	
	
	$("#modalEquipAway").modal("show");
}

function fillEquipReceive(data, disabled) {
	$("#modalEquipReceive .vlQuestion input[type='radio']").prop("checked", false).prop("disabled", false);
	if(typeof data !== "undefined" && typeof data.answers !== "undefined") {
		console.log(data);
		
		$.each(data.answers, function(k, val) {
			console.log("#modalEquipReceive .vlQuestion.vlQuestion"+k+" input[type='radio'][value='"+val+"']");
			$("#modalEquipReceive .vlQuestion.vlQuestion"+k+" input[type='radio'][value='"+val+"']").prop("checked", true);	
		});
		if(disabled) {
			$("#modalEquipReceive .vlQuestion input[type='radio']").prop("disabled", true);
		}
		
		/*$("#modalEquipReceive .modal-title .tChoose").hide();
		$("#modalEquipReceive .modal-title .tShow").show();
		
		var selEl = $("#modalEquipReceive select").eq(0);
		
		var decoded = $('<div/>').html(data.supplier.name).text();
		var option = new Option(decoded, data.supplier.idsupplier, true, true);
		$(selEl).append(option).trigger('change');
		$(selEl).prop("disabled", false);
		
		if(disabled) {
			$(selEl).prop("disabled", true);
		}*/
	}

	$("#modalEquipReceive .modal-footer").show();
	if(disabled) { $("#modalEquipReceive .modal-footer").hide(); }
	
	$("#modalEquipReceive").modal("show");
}


