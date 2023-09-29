//SUBMISSAO DE FORMS
function tryFormSubmit(form, txt, load){
	if (typeof txt === 'undefined') { txt = ""; }
	if (typeof load === 'undefined') { load = true; }
	$(form).find(".summernote").each(function() {
		if ($(this).attr("name")!==undefined) {
			var name = $(this).attr("name");
			$(form).find("textarea[name='"+name+"']").val($(this).summernote('code'));
		}
		
	});
	
	if($(form).find('.ckeditorelem').length>0){
		CKupdate();
	}
	
	var valid = validaForm(form);
	if (!valid) return false;
	
	if($(form).find("[name='service']").length<=0) {
		$(form).append("<input type='hidden' name ='service' value='true' class='valIgnore' /><input type='hidden' name ='servicesession' class='valIgnore' value='true'/>");
	}
	
	
	var fd = new FormData();
	if ($('input[type="file"]:not([name^="video"]):not(.file_)').length>0) {
		var file_data = $('input[type="file"]:not([name^="video"]):not(.file_)')[0].files; // for multiple files
		for(var i = 0;i<file_data.length;i++){
			fd.append("photo", file_data[i]);
		}
	}
	if($('.file_').length) {
		for(var j = 0;j<$('.file_').length;j++){
			var file_data = $('.file_')[j].files; // for multiple files
				for(var i = 0;i<file_data.length;i++){
				fd.append($('.file_')[j].name, file_data[i]);
			}
		}
	}
	if ($("input[type='file'][name^='video']").length>0) {
		$.each($("input[type='file'][name^='video']"), function(i, obj) {
			var name = $(obj).attr("name");
			$.each(obj.files,function(j, file){
				fd.append('videos['+name+']', file);
			});
		});
	}
	
	var other_data = $(form).serializeArray();
	$.each(other_data,function(key,input){
		fd.append(input.name,input.value);
	});
	var url = $(form).attr("action");
	$(form).find('input[type=submit]').prop('disabled', true);
	if(load) { startLoad(txt); }
	$.ajax({
		url: url,
		type: 'post',
		processData: false,
		contentType: false,
		data: fd ,
		success: function(data) {
			var data = JSON.parse(data);
			if(typeof(data.jsfunc)!=="undefined" && typeof window[data.jsfunc] === "function") {
				if(typeof(data.jsfuncparams)!=="undefined") {
					endLoad();
					window[data.jsfunc](data.jsfuncparams);
				}
				else {
					window[data.jsfunc](data);
				}
				
				if(typeof(data.jsmsg)!=="undefined") {
					endLoad();
					var msgstring = lang[data.jsmsg];
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
					
					var type = "error"; var title = "";
					if (data.code==200) {type="success";}
					if (data.code==202) {type="warning";}
					if (typeof(data.typemsg)!=="undefined") {type=data.typemsg;}
					if (typeof(data.titlemsg)!=="undefined") {title = msgstring; msgstring = "";}
					
					Swal.fire({
						title: title,
						text: msgstring,
						type: type
					});
				}
			}
			else if (data.code==200 && typeof(data.newUrl)!=="undefined" && data.newUrl==1) {
				window.location.href = data.href;
			}
			else if (data.code==200 && typeof(data.refresh)!=="undefined" && data.refresh==1) {
				location.reload(); 
			}
			else {
				endLoad();
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
				var type = "error"; var title = "";
				if (data.code==200) {type="success";}
				if (data.code==202) {type="warning";}
				if (typeof(data.typemsg)!=="undefined") {type=data.typemsg;}
				if (typeof(data.titlemsg)!=="undefined") {title = msgstring; msgstring = "";}
				
				Swal.fire({
					title: title,
					text: msgstring,
					type: type
				},
				function(){
					if(typeof(data.msgUrl)!=="undefined") {
						window.location.href = '?controller='+data.controller+'&action='+data.action;
					}
				});
				
				$(form).find('input[type=submit]').prop('disabled', false);
				return false;
			}
		},
		error: function () {
			endLoad();
			Swal.fire("", lang['NOT_SAVE_CONTACT_US'], "error");
			$(form).find('input[type=submit]').prop('disabled', false);
			return false;
		}
	});
	return false;
}

//VALIDACAO DE FORMS 
function validaForm(form, modal){
	if (typeof modal === 'undefined') { modal = false; }
	valid = true;
	$(form).find("input:not(.valIgnore), textarea:not(.valIgnore), input.date-picker:not(.valIgnore), select:not(.valIgnore), .valRadio:not(.valIgnore), .valCheck:not(.valIgnore)").each(function() {
		/*if ($(this).hasClass("valRadio")) {
			valRadio=validaRadio(this);
			if (valid==true && valRadio==false) {valid=false;}
		}*/
		if ($(this).hasClass("g-recaptcha-response")) {
			$("div.g-recaptcha").css("border", "none");
			valCaptcha=grecaptcha.getResponse()
			if (valCaptcha=="") {$("div.g-recaptcha").css("border", "1px solid red");}
			if (valid==true && grecaptcha.getResponse()=="") {valid=false;}
		}
		else {
			validaRes = validaCampo(this);
			var parent = $(this).parent();
			if ($(this).parents(".fileupload").length==1) parent = $(this).parents(".fileupload").eq(0);
			if (parent.hasClass("input-group")) {parent = $(parent).parent();}
			if (validaRes != "true") {
				msgControl(this, validaRes);
				valid=false;
			}
			else {
				$(parent).removeClass("has-error");
				$(parent).find(".errorMsg").remove();
			}
		}
		
	});
	if (modal==true && valid==true) {$('#loadModal').modal();}
	return valid;
	
}

function msgControl(elem, validaRes) {
	var parent = $(elem).parent();
	if ($(elem).parents(".fileupload").length==1) parent = $(elem).parents(".fileupload").eq(0);
	if (parent.hasClass("input-group")) {parent = $(parent).parent();} 
	
	$(parent).addClass("has-error");
	if (!$(elem).hasClass("noMsg")) {
		if ($(parent).find(".errorMsg").length==0) {
			$(parent).append("<span class='errorMsg help-block'></span>");
		}
		$(parent).find(".errorMsg").eq(0).html(validaRes);
	}
}
function clearError(elem) {
	var parent = $(elem).parent();
	if ($(elem).parents(".fileupload").length==1) parent = $(elem).parents(".fileupload").eq(0);
	if (parent.hasClass("input-group")) {parent = $(parent).parent();} 
	
	$(parent).removeClass("has-error");
	$(parent).find(".errorMsg").remove();
}
function validaRadio (div) {
	isChecked = false;
	$(div).find("input[type='radio']").each(function() {
		if ($(this).prop("checked")) {
			isChecked = true;
			return false;
		}
	});
	return isChecked;
}

function validaCheck (div) {
	isChecked = false;
	$(div).find("input[type='checkbox']").each(function() {
		if ($(this).prop("checked")) {
			isChecked = true;
			return false;
		}
	});
	return isChecked;
}

function msgErro (obj,which,msg_predf, val) {
	isChecked = false;
	if($(obj).attr("error-msg-"+which)){
		return $(obj).attr("error-msg-"+which);
	}
	else{
		if(typeof lang["msg_"+which] !== "undefined") {
			msg_predf = lang["msg_"+which];
		}
		if(typeof val !== "undefined") {
			msg_predf = msg_predf.replace(":1:", val);
		}
		return msg_predf;
	}
}


function validaCampo(field) {
	fieldClass = "";
	flag=false;
    fieldName = $(field).attr("name");
    fieldClass += $(field).attr("class");
    value = $.trim($(field).val());
	if ((value == "" || value == null) && !$(field).hasClass("valCheck") && !$(field).hasClass("valRadio")) {
		if ($(field).hasClass("valRequired")) { return msgErro(field,"valRequired","Este campo é obrigatório.");}
	}
	else {
		if ($(field).hasClass("time-picker") && $(field).hasClass("notZero")) {
			if (value=="00:00" && $(field).hasClass("valRequired")) {return msgErro(field,"valRequired","Este campo é obrigatório.");}
		}
		if ($(field).hasClass("valCheck")) { 
	        if (!validaCheck($(field))) { return msgErro(field,"valCheck","Escolha pelo menos uma opção."); }
	    }
		if ($(field).hasClass("valRadio")) { 
	        if (!validaRadio($(field))) { return msgErro(field,"valRadio","Escolha pelo menos uma opção."); }
	    }
	    if ($(field).hasClass("valNumber")) {
	        if (!isNumber(value)) { return msgErro(field,"valNumber","Este campo não é um número válido."); }//validates if number
			else {
				if ($(field).attr("minval") != "" && $(field).attr("minval") != null && isNumber($(field).attr("minval"))) {
					var mv = parseInt($(field).attr("minval"));
					if (parseInt(value)<mv) {
						return msgErro(field,"minval","O valor tem que ser maior ou igual a "+mv+".", mv);
					};
				}
				if ($(field).attr("maxval") != "" && $(field).attr("maxval") != null && isNumber($(field).attr("maxval"))) {
					var mv = parseInt($(field).attr("maxval"));
					if (parseInt(value)>mv) {return msgErro(field,"maxval","O valor tem que ser menor ou igual a "+mv+".", mv);};
				}
				if ($(field).hasClass("notZero")) {
					if (parseFloat(value)==0) {return msgErro(field,"notZero","O valor não pode ser 0.");}
				}
			}
	    }
		if ($(field).hasClass("valNumberFloat")) {
	        if (!isNumber(value.replace(/,/g, '.'))) { return msgErro(field,"valNumberFloat","Este campo não é um número válido."); }//validates if number
			else {
				if ($(field).attr("minval") != "" && $(field).attr("minval") != null && isNumber($(field).attr("minval").replace(/,/g, '.'))) {
					var mv = parseFloat($(field).attr("minval").replace(/,/g, '.'));
					if (parseFloat(value)<mv) {return msgErro(field,"minval","O valor tem que ser maior ou igual a "+mv+".", mv);}
				}
				if ($(field).attr("maxval") != "" && $(field).attr("maxval") != null && isNumber($(field).attr("maxval").replace(/,/g, '.'))) {
					var mv = parseFloat($(field).attr("maxval").replace(/,/g, '.'));
					if (parseFloat(value)>mv) {return msgErro(field,"maxval","O valor tem que ser menor ou igual a "+mv+".", mv);}
				}
				if ($(field).hasClass("notZero")) {
					if (parseFloat(value)==0) {return msgErro(field,"notZero","O valor não pode ser 0.");}
				}
			}
	    }
		if ($(field).attr("length") != "" && $(field).attr("length") != null) {
			var ln = parseInt($(field).attr("length"));
			if (value.toString().length!=ln) {return msgErro(field,"lenght","Tamanho inválido. Tamanho correcto: "+ln+".", ln);}
		}
		if ($(field).attr("minlength") != "" && $(field).attr("minlength") != null) {
			var ln = parseInt($(field).attr("minlength"));
			if (value.toString().length<ln) {return msgErro(field,"minlenght","Tamanho inválido. Tamanho minimo: "+ln+".", ln);}
		}
		if ($(field).hasClass("valMatch") && $(field).attr("fieldid") != null) {
			var idfield = $(field).attr("fieldid");
			var valorfield = $('#'+idfield).val();
			if($(field).attr("fieldname") != null) var nomefield=$(field).attr("fieldname"); else var nomefield=idfield;
			if (value!=valorfield) {return msgErro(field,"valMatch","Valor não coincide com "+nomefield+".", nomefield);}
	    }
	    if ($(field).hasClass("valDate")) { //validates if date
	        if (!isValidDate(value)) { return msgErro(field,"valDate","Data inválida."); }
			if ($(field).hasClass("afterTodayDate")) { if (isAfterTodayDate(value)){ return msgErro(field,"afterTodayDate","Data não pode ser posterior à data de hoje."); }}
			if ($(field).hasClass("beforeTodayDate")) { if (isBeforeTodayDate(value)){ return msgErro(field,"beforeTodayDate","Data não pode ser anterior à data de hoje."); }}
			
			
			if ($(field).hasClass("biggerThan") != "" && $(field).attr("fieldid") != null) {
				var idfield = $(field).attr("fieldid");
				var valorfield = $('#'+idfield).val();
				if($(field).attr("fieldname") != null) var nomefield=$(field).attr("fieldname"); else var nomefield=idfield;
				if (comparedates(valorfield,value)) {return msgErro(field,"biggerThan","Valor tem de ser superior a "+nomefield+".", nomefield);}
			}
			if ($(field).hasClass("lessThan") != "" && $(field).attr("fieldid") != null) {
				var idfield = $(field).attr("fieldid");
				var valorfield = $('#'+idfield).val();
				if($(field).attr("fieldname") != null) var nomefield=$(field).attr("fieldname"); else var nomefield=idfield;
				if (comparedates(value,valorfield)) {return msgErro(field,"lessThan","Valor tem de ser inferior a "+nomefield+".", nomefield);}
			}
			
			}
		if ($(field).hasClass("valCP")) { 
	        if (!isCP(value)) { return msgErro(field,"valCP","Código postal inválido."); }
	    }
		if ($(field).hasClass("valNif")) { //validates if date
			if($(field).attr("data-checkselect") != null) var idselect=$(field).attr("data-checkselect"); else var idselect=0;
		
	        if (!isValidNIF(value,idselect)) { return msgErro(field,"valNif","Número de contribuinte inválido."); }
	    }
		if ($(field).hasClass("valEmail")) { //validates if email
	        if (!isEmail(value)) { return msgErro(field,"valEmail","Este email não é válido."); }
	    }
	}	
	if(!flag) return "true";
}


/* VALIDACAO DE CAMPOS */
//valida email
function isEmail(email) {
    var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
}
//valida codigos_postal
function isCP (cod) {
	var regex = /^[0-9]{4}\-[0-9]{3}$/;
	return regex.test(cod);
}

//validata numeros
function isNumber(num) {
    var valid = 0;
    var regex = /^\+\d{2}\({1}\d{1}\){1}[0-9]*[0-9]$/;

    num = num.replace(/ /g, '');
    if (num.match(regex)) {
        valid = 1;
    }
    if (!isNaN(num)) {
        valid = 1;
    }
    return valid;
}

//valida NIF
function isValidNIF(contribuinte,idselect){
	var selected="";
	if(idselect!=0){
		selected=$('#'+idselect).find(":selected").attr("data-abbrev");
	
	}
	if(selected=="PT" || selected==""){
	
		contribuinte = contribuinte.toString();
		if(contribuinte.length!=9) return false;
		if (
		contribuinte.substr(0,1) != '1' && // pessoa singular
		contribuinte.substr(0,1) != '2' && // pessoa singular
		contribuinte.substr(0,1) != '3' && // pessoa singular
		contribuinte.substr(0,2) != '45' && // pessoa singular nao residente
		contribuinte.substr(0,1) != '5' && // pessoa colectiva
		contribuinte.substr(0,1) != '6' && // administracaoo publica
		contribuinte.substr(0,2) != '70' && // heranca indivisa
		contribuinte.substr(0,2) != '71' && // pessoa colectiva nao residente
		contribuinte.substr(0,2) != '72' && // fundos de investimento
		contribuinte.substr(0,2) != '77' && // atribuicao oficiosa
		contribuinte.substr(0,2) != '79' && // regime excepcional
		contribuinte.substr(0,1) != '8' && // empresario em nome individual (extinto)
		contribuinte.substr(0,2) != '90' && // condominios e sociedades irregulares
		contribuinte.substr(0,2) != '91' && // condominios e sociedades irregulares
		contribuinte.substr(0,2) != '98' && // nao residentes
		contribuinte.substr(0,2) != '99' // sociedades civis

		) { return false;}
		var check1 = contribuinte.substr(0,1)*9;
		var check2 = contribuinte.substr(1,1)*8;
		var check3 = contribuinte.substr(2,1)*7;
		var check4 = contribuinte.substr(3,1)*6;
		var check5 = contribuinte.substr(4,1)*5;
		var check6 = contribuinte.substr(5,1)*4;
		var check7 = contribuinte.substr(6,1)*3;
		var check8 = contribuinte.substr(7,1)*2;

		var total= check1 + check2 + check3 + check4 + check5 + check6 + check7 + check8;

		var divisao= total / 11;
		var modulo11=total - parseInt(divisao)*11;
		if ( modulo11==1 || modulo11==0){ comparador=0; } // excepcao
		else { comparador= 11-modulo11;}


		var ultimoDigito=contribuinte.substr(8,1)*1;
		if ( ultimoDigito != comparador ){ return false;}

		return true;
	}
	else{
		return true;
	}
}

function isFloat(n){
    return Number(n) === n && n % 1 !== 0;
}
//check if date is valid
function isValidDate(d) {
    var isDate = false;
    try {
		$.fn.datepicker.DPGlobal.parseDate(d, 'dd-mm-yyyy');
        isDate = true;
    }
    catch (e) { }


    return isDate;
}
function isAfterTodayDate(d) {
    var isDate = false;
    try {
        var selectedDate = new Date(d);
        var selectedDate = new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate());
        var today = new Date();
        var today = new Date(today.getFullYear(),today.getMonth(),today.getDate());
		
        if (selectedDate > today)
            isDate = true;
    }
    catch (e) { }

    return isDate;
}
function isBeforeTodayDate(d) {
    var isDate = false;
    try {
        var selectedDate = new Date(d);
        var selectedDate = new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate());
        var today = new Date();
        var today = new Date(today.getFullYear(),today.getMonth(),today.getDate());

        if (selectedDate < today)
            isDate = true;
    }
    catch (e) { }

    return isDate;
}
function comparedates(d,d2) {
    var isDate = false;
    try {
        var selectedDate = new Date(d);
        var selectedDate = new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate());
        var selectedDate2 = new Date(d2);
        var selectedDate2 = new Date(selectedDate2.getFullYear(),selectedDate2.getMonth(),selectedDate2.getDate());

        if (selectedDate > selectedDate2)
            isDate = true;
    }
    catch (e) { }

    return isDate;
}
