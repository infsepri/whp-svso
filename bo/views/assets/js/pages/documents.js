$('#client_identify').on("change", function() {

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
            if(data.client.nif!=null && data.client.nif!=""){
              $("#clientNif").attr("readonly", true);
              $("#clientNif").attr("data-readonly", true);
            }else{
              $("#clientNif").attr("readonly", false);
              $("#clientNif").attr("data-readonly", false);
            }
						$("#clientNif").val(data.client.nif);
						$("#clientName").val(data.client.name);
						$("#clientAddress").val(data.client.address);

						$("#clientPostal").val(data.client.postalcode);
						$("#clientLocal").val(data.client.locality);

						var decoded = $('<div/>').html(data.client.country.select_show).text();
						var option = new Option(decoded, data.client.country.id, true, true);
						$('#clientCountry').append(option).trigger('change').attr("disabled", true);
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
	}else{
		$('#clientCountry').attr("disabled", false);
		$("#clientNif").attr("readonly", false);
		$("#clientNif").attr("data-readonly", false);
	}
});



if($("#client_withoutnif").length>0){
  $("#client_withoutnif").click(function(){
    if($("#client_withoutnif").is(':checked')){
      $("#clientNif").attr("readonly", true);
    }else{
      if(typeof $("#clientNif").attr("data-readonly")=="undefined" || $("#clientNif").attr("data-readonly")=="false"){
        $("#clientNif").attr("readonly", false);
        $("#clientNif").removeAttr("readonly");
      }else{
        $("#clientNif").attr("readonly", true);
      }
    }
  });
}

function putvalueLine(l, obj){
	$("#iditem_"+l).val(obj.iditem);
	if(typeof obj.iditemlist!=="undefined"){
		$("#iditemlist_"+l).val(obj.iditemlist);
	}else{
		$("#iditemlist_"+l).val("");
	}

	$("#code_"+l).val(obj.code);
	var decoded = $('<div/>').html(obj.description).text();
	$("#description_"+l).val(decoded);
	$("#price_"+l).val(obj.pvp1);
	if(typeof obj.quantity!=="undefined"){
		$("#quantity_"+l).val(obj.quantity);
	}else{
		$("#quantity_"+l).val("1");
	}

	var option = new Option(obj.vatdesc, obj.idvat, true, true);
	option.setAttribute("data-perc",obj.vatvalue);
	$("#vat_"+l).append(option).trigger('change');

	var option = new Option(obj.reasonvatdesc, obj.idreasonvat, true, true);
	$("#reasonvat_"+l).append(option).trigger('change');

	if(typeof obj.quantity!=="undefined"){
		$("#discount_"+l).val(obj.discount);
	}else{
		$("#discount_"+l).val("0");
	}
}

$("#item_select").change(function(){
	if($(this).val()>0){
		var elem = $(this);
		 $.ajax({
 			type: 'GET',
 			url: '?controller=item&action=getitem&id='+$(this).val(),
 			dataType: 'text',
 			cache: false,
 			success: function(result) {
 					data = $.parseJSON(result);
					$(elem).val(null).trigger('change');
 				  $.when(l = addRepItem($("#btnDuplicateRow"),'documentitem')).then( putvalueLine(l, data) );

 			},
 			error: function (xhr, ajaxOptions, thrownError) {

 			}
 		});

	}
});



$("#documenttypeInsp").change(function(){
	if($(this).val()==lang['invoice_receipt']){
			$(".DocInvoice").attr("disabled", true);
			$(".DocInvoice").val(null).trigger("change");
			$(".DocInvoiceReceipt").removeAttr("disabled");
	}else{
		$(".DocInvoiceReceipt").attr("disabled", true);
		$(".DocInvoiceReceipt").val(null).trigger("change");
		$(".DocInvoice").removeAttr("disabled");
	}
});





function refreshSummary(){
	var sum_document=discount_document=withoutvat_document=vat_document=0;var tmp;
	var lineItem=$('.repContainer > .repItem').not(".original");

	var impostos={};

	lineItem.each(function(key,elem){

		var line = $(elem).attr('data-line') ;

		var price_line=$("#price_"+line).val();
		var qnt_line=$("#quantity_"+line).val();
		var desconto_line=$("#discount_"+line).val();

		var ivaSelect_line=$("#vat_"+line);

		if ($(ivaSelect_line).is('select')){
			var iva_line=$(ivaSelect_line).find(":selected").attr("data-perc");
			var data = $(ivaSelect_line).select2('data');
			var ivadescricao=data[0].text;
		}
		else{
			var iva_line=$(ivaSelect_line).attr("data-perc");
			var data = $(ivaSelect_line).select2('data');
			var ivadescricao=data[0].text;
		}
		var ivaid_line=$("#vat_"+line).val();

		if(price_line=='') price_line=0;
		if(qnt_line=='') qnt_line=0;
		if(desconto_line=='') desconto_line=0;

		price_line_float=parseFloat(price_line);
		price_line_float=Math.round(price_line_float*10000)/10000;
		quant_line_float=parseFloat(qnt_line);
		quant_line_float=Math.round(quant_line_float*100)/100;
		descont_line_float=(parseFloat(desconto_line)*0.01);
		descont_line_float=Math.round(descont_line_float*100)/100;

		//sum_document da linha sem disconto
		valor_total_line=price_line_float*quant_line_float;
		valor_total_line=Math.round(valor_total_line*10000)/10000;
		valor_total_line_round=Math.round(valor_total_line*100)/100;
		//valor de desconto da linha
		discount_document_line=valor_total_line_round*descont_line_float;
		discount_document_line=Math.round(discount_document_line*10000)/10000;
		//valor após desconto
		valor_cdesconto_line=valor_total_line-discount_document_line;
		valor_cdesconto_line=Math.round(valor_cdesconto_line*10000)/10000;

		incidencia_line=valor_cdesconto_line;

		discount_document+=Math.round(discount_document_line*100)/100;
		sum_document+=valor_total_line_round;

		if(typeof impostos[ivaid_line] != 'undefined'){
			impostos[ivaid_line]["incid"]+=incidencia_line;
		}
		else{
			impostos[ivaid_line]={};
			impostos[ivaid_line]["descr"]=ivadescricao;
			impostos[ivaid_line]["perc"]=iva_line;
			impostos[ivaid_line]["incid"]=incidencia_line;
			impostos[ivaid_line]["valor"]=0;
		}
	});

	$("#tableVat > tbody").html("");
	$.each(impostos, function(key,elem){
		var tr="<tr>";
		var incid=0;
		$.each(elem, function(key2,ivainfo){
			if(key2=="perc") perc=ivainfo;
			tr+="<td><div id='my_container'><input type='hidden' class='justtextinput";
			if(key2=="incid"){
				tr+=" valNumberFloat'";
				if($(".vat_maxincidence[data-perc='"+perc+"']").text()!=""){
						tr+="error-msg-maxval='Máx:"+$(".vat_maxincidence[data-perc='"+perc+"']").text()+"' maxval='"+$(".vat_maxincidence[data-perc='"+perc+"']").text()+"'";
				}
				tr+=" readonly value='";

				incid=ivainfo;
				ivainfo=nearest(ivainfo,-0.01);
				withoutvat_document+=ivainfo;
				tr+=ivainfo;
				tr+="'/><input type='text' class='justtextinput' readonly value='"+ivainfo.formatMoney(2, ',', '.');

			} else if(key2=="valor"){
				tr+=" valNumberFloat'";
				if($(".vat_maxvalor[data-perc='"+perc+"']").text()!=""){
						tr+="error-msg-maxval='Máx:"+$(".vat_maxvalor[data-perc='"+perc+"']").text()+"' maxval='"+$(".vat_maxvalor[data-perc='"+perc+"']").text()+"'";
				}
				tr+=" readonly value='";

				perc_iva_float=(parseFloat(perc)*0.01);
				perc_iva_float = Math.round(perc_iva_float*100)/100;
				valoriva=perc_iva_float*incid;
				valoriva=Math.round(valoriva*100000)/100000;
				valoriva=Math.round(valoriva*100)/100;
				tr+=valoriva;
				vat_document+=valoriva;
				tr+="'/><input type='text' class='justtextinput' readonly value='"+valoriva.formatMoney(2, ',', '.');
			}
			else{
				tr+="' value='0'/><input type='text' class='justtextinput' readonly value='";
				tr+=ivainfo;
			}
			tr+="'/></td>";
		});
		tr+="</tr>";
		$("#tableVat > tbody").append(tr);
	});

	discount_document=nearest(discount_document,+0.01);
	withoutvat_document=nearest(withoutvat_document,+0.01);
	var total=withoutvat_document+vat_document;

	$("#sum_document").text((sum_document).formatMoney(2, ',', '.'));
	$("#discount_document").text(discount_document.formatMoney(2, ',', '.'));
	$('#withoutvat_document').text(withoutvat_document.formatMoney(2, ',', '.'));
	$('#vat_document').text(vat_document.formatMoney(2, ',', '.'));
	$('#total_document').text(total.formatMoney(2, ',', '.'));
	$('#total_document').next().val(total.toFixed(2));

}



function nearest(n, v) {
    n = n / v;
    n = Math.round(n) * v;
    return n;
}

Number.prototype.formatMoney = function(c, d, t){
var n = this,
    c = isNaN(c = Math.abs(c)) ? 2 : c,
    d = d == undefined ? "." : d,
    t = t == undefined ? "," : t,
    s = n < 0 ? "-" : "",
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
    j = (j = i.length) > 3 ? j % 3 : 0;
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
 };
