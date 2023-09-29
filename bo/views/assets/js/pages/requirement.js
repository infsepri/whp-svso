$(window).on("load", function() {
	$('#presentertype').on("change", function() {
		if( $(this).val()==1 ){
			$(".presenterInfo").hide();
			$(".presenterInfo .valRequired").addClass("valIgnore").removeClass("hasIgnore");
		}
		else{
			$(".presenterInfo").show();
			$(".presenterInfo .valRequired.valIgnore").removeClass("valIgnore").addClass("hasIgnore");
		}
	}).change();

});

$('#inspectionClient').on("change", function() {
	$(".clientFields").val("").trigger("change");
	var idclient = $(this).val();
	if(idclient!="") {
		$.ajax({
			type: 'POST',
			url: '?controller=clients&action=getClientInfo',
			data: {idclient:idclient, service:1},
			dataType: 'text',
			cache: false,
			success: function(result) {
				data = $.parseJSON(result);
				if(typeof data.code !== "undefined") {
					if(data.code == 200 && typeof data.client !== "undefined") {
						$("#clientNif").val(data.client.nif);
						$("#clientName").val(data.client.name);
						$("#clientAddress").val(data.client.address);

						$("#clientPostal").val(data.client.postalcode);
						$("#clientLocal").val(data.client.locality);
						$("#clientPhone").val(data.client.phone);

						var decoded = $('<div/>').html(data.client.country.select_show).text();
						var option = new Option(decoded, data.client.country.id, true, true);
						$('#clientCountry').append(option).trigger('change');
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
				Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			}
		});
	}
});

$('#vehicleOwner').on("change", function() {
	$(".ownerFields").val("").trigger("change");
	var idclient = $(this).val();
	if(idclient!="") {
		$.ajax({
			type: 'POST',
			url: '?controller=clients&action=getClientInfo',
			data: {idclient:idclient, service:1},
			dataType: 'text',
			cache: false,
			success: function(result) {
				data = $.parseJSON(result);
				if(typeof data.code !== "undefined") {
					if(data.code == 200 && typeof data.client !== "undefined") {
						$("#ownerNif").val(data.client.nif);
						$("#ownerName").val(data.client.name);
						$("#ownerAddress").val(data.client.address);
						$("#ownerEmail").val(data.client.email);
						$("#ownerPostal").val(data.client.postalcode);
						$("#ownerLocal").val(data.client.locality);
						$("#ownerPhone").val(data.client.phone);

						var decoded = $('<div/>').html(data.client.country.select_show).text();
						var option = new Option(decoded, data.client.country.id, true, true);
						$('#ownerCountry').append(option).trigger('change');
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
				Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			}
		});
	}
});

$('#requirementPresenter').on("change", function() {
	$(".presenterFields").val("").trigger("change");
	var idclient = $(this).val();
	if(idclient!="") {
		$.ajax({
			type: 'POST',
			url: '?controller=clients&action=getClientInfo',
			data: {idclient:idclient, service:1},
			dataType: 'text',
			cache: false,
			success: function(result) {
				data = $.parseJSON(result);
				if(typeof data.code !== "undefined") {
					if(data.code == 200 && typeof data.client !== "undefined") {
						$("#presenterNif").val(data.client.nif);
						$("#presenterName").val(data.client.name);
						$("#presenterAddress").val(data.client.address);
						$("#presenterEmail").val(data.client.email);
						$("#presenterPostal").val(data.client.postalcode);
						$("#presenterLocal").val(data.client.locality);
						$("#presenterPhone").val(data.client.phone);

						var decoded = $('<div/>').html(data.client.country.select_show).text();
						var option = new Option(decoded, data.client.country.id, true, true);
						$('#presenterCountry').append(option).trigger('change');
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
				Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			}
		});
	}
});

$('#vehiclePlate').on("change", function() {
	$(".vehicleFields").val("").trigger("change");
	var plate = $(this).val();
	if(plate!="") {
		startLoad();
		$.ajax({
			type: 'POST',
			url: '?controller=vehicle&action=searchVehicleReq',
			data: {plate:plate, service:1},
			dataType: 'text',
			cache: false,
			success: function(result) {
				data = $.parseJSON(result);
				if(typeof data.code !== "undefined") {
					if(data.code == 200) {
						if(typeof data.vehicle !== "undefined" && data.vehicle!="") {

							$("#vehicleVin").val(data.vehicle.vin);

							var decoded = $('<div/>').html(data.vehicle.ownerName).text();
							var option = new Option(decoded, data.vehicle.idowner, true, true);
							$('#vehicleOwner').append(option).trigger('change');

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
				endLoad();
			},
			error: function (xhr, ajaxOptions, thrownError) {
				Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			}
		});
	}
});

$('#multiPayment').on("change", function() {
	var chckd = $('#multiPayment').prop("checked");
	if(chckd) {
		$(".multipayShow .valIgnore:not(.keepIgnore)").addClass("valRequired").removeClass("valIgnore");
		$(".multipayShow").show();
	}
	else {
		$(".multipayShow").hide();
		$(".multipayShow .valRequired").addClass("valIgnore").removeClass("valRequired");
	}
	updateChanges();
});


function tryprintdoc(data){
	print_invoice(data.printurl, data.href);

	setTimeout(function(){
		if(print_error_return==true){
			window.location.href = data.href;
		}
	}, 3000);
}




function updateChanges() {
	var totalDoc = $("#docTotal").val();
	var totalReceived = 0;
	var totalChanges = 0;
	var parentPaid = $("#docPaid").parent();
	if(validaForm(parentPaid)) {
		var paid = $("#docPaid").val();
		if(paid!="") {
			totalReceived = parseFloat(paid);
		}

		var multi = $('#multiPayment').prop("checked");
		if(multi) {
			var paidm = $("#docPaidM").val();
			if(paidm!="") {
				totalReceived += parseFloat(paidm);
			}
		}
	}

	if(totalReceived!=0 && totalDoc!=0) {
		var totalChanges = parseFloat(totalReceived) - parseFloat(totalDoc);
		if(totalChanges<0) {totalChanges = 0;}
	}

	if(totalChanges!=0) {
		totalChanges = totalChanges.toFixed10(2);
		$("#docChanges").val(totalChanges);
	}
	else { $("#docChanges").val(""); }
}



$("#documenttypeInsp").on("change", function() {
 if($(this).val()==lang['invoice_receipt']){
	 $(".DocInvoiceReceipt").show();
	 if(!$("#multiPayment").is(":checked")){
		 $(".multipayShow").hide();
	 }
	 $(".DocInvoice").hide();
 }else{
	 $(".DocInvoiceReceipt").hide();
	 $(".DocInvoice").show();
 }
});


$('#inspectionClient_2').on("change", function() {
$(".clientFields").val("").trigger("change");
var idclient = $(this).val();
if(idclient!="") {
	$.ajax({
		type: 'POST',
		url: '?controller=clients&action=getClientInfo',
		data: {idclient:idclient, service:1},
		dataType: 'text',
		cache: false,
		success: function(result) {
			data = $.parseJSON(result);
			if(typeof data.code !== "undefined") {
				if(data.code == 200 && typeof data.client !== "undefined") {
					$("#clientNif").val(data.client.nif);
					$("#clientName").val(data.client.name);
					$("#clientAddress").val(data.client.address);

					$("#clientPostal").val(data.client.postalcode);
					$("#clientLocal").val(data.client.locality);
					$("#clientPhone").val(data.client.phone);

					var decoded = $('<div/>').html(data.client.country.select_show).text();
					var option = new Option(decoded, data.client.country.id, true, true);
					$('#clientCountry').append(option).trigger('change');
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
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
		}
	});
}
});


$('#invoicebyClient').on("change", function() {
 if($(this).val()==1){
	 $(".infoClient").hide();
 }else{
	 $(".infoClient").show();
 }
});


function tryprintdoc(data){

	if(typeof data.printurl2 !="undefined" && data.printurl2!=null && data.printurl2!=0){
		print_invoice(data.printurl2, 0);
	}

	 print_invoice(data.printurl, data.href);



	 setTimeout(function(){
       if(print_error_return==true){
		 		window.location.href = data.href;
		 	}
	 }, 3000);


}
