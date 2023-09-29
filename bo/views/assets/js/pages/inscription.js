var idpaymentformncred = 1; //Prazo de pagamento Pronto Pagamento
var arrayFuelsCalcdoc = ["8","10","11"];
var arrayFuelsGNC = ["9","12","13"];

var idreinspectiontype = 2; //ID reinspeção
var idpaymentoffer = 6; //ID Pagamento oferta

function loadPrice() {
	var idcategory = $("#vehicleCat").val();
	var idinspectiontype = $("#inspType").val();
	if(idcategory!="" && idinspectiontype!="") {

		$.ajax({
			type: 'POST',
			url: '?controller=inspection&action=getPrice',
			data: {idcategory:idcategory, idinspectiontype:idinspectiontype, service:1},
			dataType: 'text',
			cache: false,
			success: function(result) {
				data = $.parseJSON(result);
				var clear = true;
				if(typeof data.code !== "undefined") {
					if(data.code == 200 && typeof data.price !== "undefined") {
						clear = false;

						$("#docValue").val(data.price.value_show);
						$("#docVatTotal").val(data.price.vatvalue_show);
						$("#docTotal").val(data.price.total_show);
						$("#docVatText").html("("+data.price.vatperc+"%)").show();
						updateChanges();
					}
				}
				if(clear) {
					if(typeof data.msg !== "undefined") {
						var msgstring = lang[data.msg];
						if (typeof data.msgparams !== "undefined") {
							$.each(data.msgparams, function( index, value ) {
								msgstring = msgstring.replace(":"+index+":", value);
							});
						}

						Swal.fire({
							title: "",
							text: msgstring,
							type: "danger"
						});
					}


					cleanValues();
				}
			},
			error: function (xhr, ajaxOptions, thrownError) {
				cleanValues();
			}
		});
	}
	else {
		cleanValues();
	}
}
function cleanValues() {
	$("#docValue").val(0);
	$("#docVatTotal").val(0);
	$("#docTotal").val(0);
	$("#docVatText").html("").hide();
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


$('#plateDate').on("change", function() {
	var value = $(this).val();
	var valF = $('#fstplateDate').val();
	if(value!="" && valF=="") {
		$('#fstplateDate').val(value).trigger("change");
	}
});

$('#frontTire').on("change", function() {
	var value = $(this).val();
	var valF = $('#rearTire').val();
	var text = $(this).find(':selected').html();

	if(value!="" && valF=="") {
		var option = new Option(text, value, true, true);
		$('#rearTire').append(option).trigger('change');
	}
});

$('#invoicebyClient').on("change", function() {
	if($(this).val()==1){ $(".infoClient").hide(); }
	else{ $(".infoClient").show();}
});

$("#documenttypeInsp").on("change", function() {
	if($(this).val()==lang['invoice_receipt']) {
		$(".DocInvoiceReceipt").show();
		if(!$("#multiPayment").is(":checked")){
			$(".multipayShow").hide();
		}
		$(".DocInvoice").hide();
	}
	else {
		$(".DocInvoiceReceipt").hide();
		$(".DocInvoice").show();
	}
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
						
						if(data.client.idpaymentform != "" && data.client.idpaymentform != null && data.client.idpaymentform != idpaymentformncred) {
							$("#documenttypeInsp").find("option[value='"+lang["invoice"]+"']").prop("selected", true);
							$("#documenttypeInsp").trigger("change");
							
							$("#documentPaymentform").val(data.client.idpaymentform).trigger("change");
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
			data: {idclient:idclient, state:1, service:1},
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

$('#vehiclePlate').on("change", function() {
	$(".vehicleFields").val("").trigger("change");
	var plate = $(this).val();
	if(plate!="") {
		startLoad();
		$.ajax({
			type: 'POST',
			url: '?controller=vehicle&action=searchVehicleInsc',
			data: {plate:plate, service:1},
			dataType: 'text',
			cache: false,
			success: function(result) {
				data = $.parseJSON(result);
				if(typeof data.code !== "undefined") {
					if(data.code == 200) {
						if($("#IMTSearchModal").length>0) {
							$("#IMTSearchModal input[name='plate']").val(plate);
						}
						
						if(typeof data.vehicle !== "undefined" && data.vehicle!="") {

							if(data.vehicle.inpounded==1){
								Swal.fire({
									title: '<strong> '+lang["vehicle_inpounded"]+' </strong>',
									type: 'info',
									html: lang["inpoundwarning_text"]+"<br/>"+lang["inpoundwarning_clickok"],
									showCloseButton: true,
									confirmButtonText: lang["OK"]
								}).then((result) => {
									if (result.value) { 
										window.open('index.php?controller=inpound&action=edit&id='+data.vehicle.idinpound);
									}
								});
								
								resetFormInspecao();
								endLoad();
								return false;
							}
							
							if(data.vehicle.block==1){
								Swal.fire({
									type: 'error',
								  title: lang['vehicule_block'],
								  text: lang['vehicule_block_abbr'],
									allowOutsideClick: false
								});
							}
							if(data.vehicle.notregular==1){
								Swal.fire({
									type: 'error',
								  title: lang['vehicule_notregular'],
								  text: lang['vehicule_notregular_abbr'],
									allowOutsideClick: false
								});
							}
							if(data.vehicle.canceled==1){
								Swal.fire({
									type: 'error',
								  title: lang['vehicule_cancel'],
								  text: lang['vehicule_cancel_abbr'],
									allowOutsideClick: false
								});
							}

							$("#vehicleVin").val(data.vehicle.vin);
							$("#vehicleEmdate").val(data.vehicle.emission_date);
							$("#plateDate").val(data.vehicle.plate_date);
							$("#fstplateDate").val(data.vehicle.plate_first_date);

							var decoded = $('<div/>').html(data.vehicle.brandName).text();
							var option = new Option(decoded, data.vehicle.idbrand, true, true);
							$('#vehicleBrand').append(option).trigger('change');

							var decoded = $('<div/>').html(data.vehicle.modelName).text();
							var option = new Option(decoded, data.vehicle.idmodel, true, true);
							$('#vehicleModel').append(option).trigger('change');

							var decoded = $('<div/>').html(data.vehicle.fuelName).text();
							var option = new Option(decoded, data.vehicle.idfuel, true, true);
							$('#vehicleFuel').append(option).trigger('change');

							var decoded = $('<div/>').html(data.vehicle.cecatName).text();
							var option = new Option(decoded, data.vehicle.idcecategory, true, true);
							$('#vehicleCecat').append(option).trigger('change');

							var decoded = $('<div/>').html(data.vehicle.catName).text();
							var option = new Option(decoded, data.vehicle.idcategory, true, true);
							$('#vehicleCat').append(option).trigger('change');

							var decoded = $('<div/>').html(data.vehicle.serviceName).text();
							var option = new Option(decoded, data.vehicle.idservice, true, true);
							$('#vehicleService').append(option).trigger('change');

							var decoded = $('<div/>').html(data.vehicle.typeName).text();
							var option = new Option(decoded, data.vehicle.idtype, true, true);
							$('#vehicleType').append(option).trigger('change');


							if(typeof(data.vehicle.idtire_front)!=="undefined" && data.vehicle.idtire_front!="" && data.vehicle.idtire_front!=null) {
								var decoded = $('<div/>').html(data.vehicle.ftireName).text();
								var option = new Option(decoded, data.vehicle.idtire_front, true, true);
								$('#frontTire').append(option).trigger('change');
							}
							

							if(typeof(data.vehicle.idtire_back)!=="undefined" && data.vehicle.idtire_back!="" && data.vehicle.idtire_back!=null) {
								var decoded = $('<div/>').html(data.vehicle.btireName).text();
								var option = new Option(decoded, data.vehicle.idtire_back, true, true);
								$('#rearTire').append(option).trigger('change');
							}


							var decoded = $('<div/>').html(data.vehicle.ownerName).text();
							var option = new Option(decoded, data.vehicle.idowner, true, true);
							$('#vehicleOwner').append(option).trigger('change');

							$('#vehicleyear').val(data.vehicle.year);
							$('#vehiclehomologation').val(data.vehicle.homologation);
							$('#vehiclecylinder').val(data.vehicle.cylinder);
							$('#vehiclenoiselevel').val(data.vehicle.noiselevel);
							$('#vehicleacceleration').val(data.vehicle.acceleration);


							if(data.vehicle.idcolor!=null){
								var decoded = $('<div/>').html(data.vehicle.colorName).text();
								var option = new Option(decoded, data.vehicle.idcolor, true, true);
								$('#vehicleidcolor').append(option).trigger('change');
							}


						 if(data.vehicle.idcolor_second!=null){
								var decoded = $('<div/>').html(data.vehicle.color_secondName).text();
								var option = new Option(decoded, data.vehicle.idcolor_second, true, true);
								$('#vehicleidcolor_second').append(option).trigger('change');
							}

							$('#vehicleothercolors').val(data.vehicle.othercolors);
							$('#vehiclenraxis').val(data.vehicle.nraxis);
							$('#vehicleaxis_distance').val(data.vehicle.axis_distance);
							$('#vehiclelotation').val(data.vehicle.lotation);

							if(data.vehicle.idbox!=null){
								var decoded = $('<div/>').html(data.vehicle.boxName).text();
								var option = new Option(decoded, data.vehicle.idbox, true, true);
								$('#vehicleidbox').append(option).trigger('change');
							}


							$('#vehiclebox_length').val(data.vehicle.box_length);
							$('#vehicleweight_front').val(data.vehicle.weight_front);
							$('#vehicleweight_back').val(data.vehicle.weight_back);
							$('#vehicletowable_weight').val(data.vehicle.towable_weight);
							$('#vehicleelevation').val(data.vehicle.elevation);
							$('#vehicletare').val(data.vehicle.tare);
							$('#vehiclecontrol_number').val(data.vehicle.control_number);
							$('#vehiclespecial_notes').val(data.vehicle.special_notes);

							$("#imtSearchRes").val(lang["siivh_not_working"]);
							if(typeof(data.inspInfo) !== "undefined" && data.inspInfo!=null){
									if(data.vehicle.situationimt!="") {
										$("#imtSearchRes").val(data.vehicle.situationimt);
									}
									var htmlInsp = "";
									var resSit = "<span class='label noRadius label-success'style='padding: 5px !important;'><strong style='font-size: 13px !important;'>"+data.vehicle.situationimt+"</strong></span>";
									if(data.vehicle.notregular==1){
										resSit = "<span class='label noRadius label-danger'style='padding: 5px !important;'><strong style='font-size: 13px !important;'>"+data.vehicle.situationimt+"</strong></span>";
									}
									
									if(typeof(data.inspInfo.result)!=="undefined") {
										var res = "<span class='label label-danger  noRadius'style='padding: 5px !important;'><strong style='font-size: 13px !important;'>"+lang["reproved"]+"</strong></span>";
										if (data.inspInfo.result==1) {
											res = "<span class='label label-success noRadius 'style='padding: 5px !important;'><strong style='font-size: 13px !important;'>"+lang["approved"]+"</strong></span>";
										}
										
										htmlInsp = " <strong>"+lang['validity']+":</strong> "+data.inspInfo.nextinspection+" <strong>Cert:</strong> "+data.inspInfo.certificatenumber+" <strong>"+lang['type']+":</strong> "+data.inspInfo.inspectiontypeName+" <strong>"+lang["result"]+":</strong> "+res+""
									}

									$("#info_last_inspection").html(" <strong>"+lang['imt_situation']+":</strong> "+resSit+""+htmlInsp);


									if(data.inspInfo.nextinspectionpermit==0){
										Swal.fire(lang['vehicle_with_valid_insp']);
									}


									if(data.inspInfo.result!=1 && data.inspInfo.last_insp_this_center==1){
										if(data.inspInfo.nextinspection<data.ActualDate ){
											Swal.fire(lang['vehicle_with_limit_reins_exccedd']);
										}else{
											if(typeof data.insp.idinspectiontype !=="undefined"){
												var newOption = new Option(data.insp.name, data.insp.idinspectiontype, true, true);
												$("#inspType").append(newOption).trigger('change');
											}

										}
									}


							}


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
	console.log(chckd);
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

$('#docPaymentmean').on("change", function() {
	var idinspectiontype = $("#inspType").val();
	var paymentmean = $("#docPaymentmean").val();
	
	if(idinspectiontype==idreinspectiontype && paymentmean==idpaymentoffer) {
		$("#withoutnifdoc").prop("checked", true).trigger("change");
	}
});


function controlDocuments() {
	var comb = $("#vehicleFuel").val();
	var insp = $("#inspType").val();
	var subs = $("#InspectionSubtype").val();
	if($.inArray(comb, arrayFuelsCalcdoc)!=-1 && ($.inArray("4", subs)!=-1 || $.inArray("25", subs)!=-1)) {
		//swal documentos gpl para B
		Swal.fire({
			title: lang["calcdocumentask"],
			showCancelButton: true,
			confirmButtonText: lang["yes"],
			cancelButtonText: lang["no"],
		}).then((result) => {
			if (!result.value) {
				Swal.fire({
					title: lang["nodocumentwarning"],
					text: ""
				});
				resetFormInspecao();
			}
			else {
				confirmVin();
			}
		});
		return false;
	}
	else{
		if($.inArray(comb, arrayFuelsGNC)!=-1 && insp=="1") {
			//swal documentos GNC para A
			Swal.fire({
				title: lang["gndocumentask"],
				showCancelButton: true,
				confirmButtonText: lang["yes"],
				cancelButtonText: lang["no"],
			}).then((result) => {
				if (!result.value) {
					Swal.fire({
						title: lang["nodocumentwarning"],
						text: ""
					});
					resetFormInspecao()
				}
				else {
					confirmVin();
				}
			});
			return false;
		}
	}
	return true;
}

function resetFormInspecao() {
	$("#formInspection")[0].reset();
	$("#vehicleFuel").val("").trigger("change");
	
}
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

$(document).ready(function() {
	if($("#docSave").length>0){
		$("#docSave").click(function(){
			$(".ownerNifReq, .clientNifReq").hide();
			$("#ownerNif, #clientNif").removeClass("valRequired");
			
			if($("#documenttypeInsp").val()==lang['invoice']) { /*Control NIF requirements*/
				if($('#invoicebyClient').val()==1) {
					$(".ownerNifReq").show();
					$("#ownerNif").addClass("valRequired");
				}
				else {
					$(".clientNifReq").show();
					$("#clientNif").addClass("valRequired");
				}
			}
			
			if($("#idinspection").length==0) {
				if(controlDocuments()) {
					confirmVin();
				}
			}
			else{
				confirmVin()
			}
			return false;
		});
	}
});

async function confirmVin(){
	const {value: VinValidate} =  await Swal.fire({
		input: 'text',
		inputPlaceholder: lang['vinnr'],
		confirmButtonText: lang['confirm'],
		cancelButtonText: lang['modalclose'],
		showCancelButton: true,
		allowOutsideClick: false,
		inputValidator: (op) => {
			if (!op) {
				return lang['vin4characters'];
			}
			if (op.length<4 || op.length>4) {
				return lang['vin4characters'];
			}
		}
	});

	if (VinValidate) {
		var vin = $("#vehicleVin").val();
		var lastFour = vin.substr(vin.length - 4);
		if (lastFour!=VinValidate) {
			Swal.fire(lang['n_vin_not_correct']);
			return false;
		}
		$("#formInspection").submit();
	}
	return false;
}
