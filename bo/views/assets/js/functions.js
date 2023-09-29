
$( document ).ready(function() {
	getpostalcode();
});
/* CONFIRM */
function confirm_Swal(op, data){
	if(op==1){
		Swal.fire({
			title: lang[data[0]],
			text: lang[data[1]],
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#5cb85c",
			confirmButtonText: lang['yes'],
			cancelButtonText: lang['no']
		}).then((result) => {
			if (result.value) {
				window.location.href = data[2];
			}
		});

	}
	else if(op==2){
		Swal.fire({
			title: lang[data[0]],
			text: lang[data[1]],
			type: "error",
			showCancelButton: true,
			confirmButtonColor: "#5cb85c",
			confirmButtonText: lang['yes'],
			cancelButtonText: lang['no']
		}).then((result) => {
			if (result.value) {
				window.location.href = data[2];
			}
		});

	}
	else if(op==3){
		Swal.fire({
			title:  lang[data[0]],
			text: lang[data[1]],
			type: "warning",
			showCancelButton: true,
			confirmButtonColor: "#5cb85c",
			confirmButtonText: lang['yes'],
			cancelButtonText: lang['no']
		}).then((result) => {
			if (result.value) {
				var modal = data[2];
				$("#"+modal).modal("show");
			}
		});
	}
}

function topNotify(type, text, time, subtext, forceTxt, close) {
	time = (typeof time !== "undefined") ? time : 3000;
	forceTxt = (typeof forceTxt !== "undefined") ? forceTxt : false;
	subtext = (typeof subtext !== "undefined") ? subtext : "";

	text = (forceTxt) ? text : lang[text];

	Toast = Swal.mixin({
		toast: true,
		position: 'top-end',
		showConfirmButton: false,
		timer: time,
		animation:false
	});

	Toast.fire({
		type: type,
		title: text,
		text: subtext
	})
}

/* LOCATIONS */
var cntrlIsPressed = false;
function golocationspecify(id, controller, action, idref, forceBlank){
	if (typeof idref == 'undefined'){ 	var idref = "id";}
	if (typeof forceBlank === 'undefined'){ forceBlank = false;}
	if(cntrlIsPressed || forceBlank) {
		cntrlIsPressed = false;
		window.open('?controller='+controller+'&action='+action+'&'+idref+'='+id);return true;
	}
	window.location.replace('?controller='+controller+'&action='+action+'&'+idref+'='+id);

}
function golocationspecifyurl(url){
	if(cntrlIsPressed){
		cntrlIsPressed = false;
		window.open(url);return true;
	}
	window.location.replace(url);
}

$(document).keydown(function(event){
    if(event.which=="17")  cntrlIsPressed = true;
});
$(document).keyup(function(){
    cntrlIsPressed = false;
});

/*
function getpostalcode(elem_param){
	if (typeof elem_param !== 'undefined'){element_input = elem_param;}

	var element_input = ".postalcode";
	$(element_input).change(function() {
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
}*/
function getpostalcode(elem_param){
	var element_input = ".postalcode";
	if (typeof elem_param !== 'undefined'){element_input = elem_param;}
  $( element_input ).change(function() {
	var attridentify = $(this).attr('identify');
	if($(this).val()=='' || $(this).val()==lang['unknown']){
	  return 0;
	}
	if(($(".country_local"+attridentify).val()!='179' && $(".country_local"+attridentify).val()!='179-1' && $(".country_local"+attridentify).val()!='179-2' && $(".country_local"+attridentify).val()!='179-3') && ($(".country_local"+attridentify).val()!='PT' && $(".country_local"+attridentify).val()!='PT-MA'  && $(".country_local"+attridentify).val()!='PT-AC') ){
	  return 0;
	}
	$(".locality"+attridentify).prop("readonly", true);
	 $.getJSON("?controller=home&action=getpostalcode&postalcode="+$(this).val() , function (json) {
	   if(json.code==200){
		 $(".locality"+attridentify).val(json.designation).change();
  
		 if($(".district_local"+attridentify).length) {
				   if(json.iddistrict>0){ 
					   var newOption = new Option(json.district, json.iddistrict, false, false);
				 $(".district_local"+attridentify).append(newOption).val(json.iddistrict).trigger('change');
				   }
  
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
  


/* GLOBALS */
function sleep(ms) {
  return new Promise(resolve => setTimeout(resolve, ms));
}

$('.upperVal').on("input", function (e){
	this.value = this.value.toUpperCase();
});

$('.comma_to_dot').keyup(function (e){
	comma_to_dot(this);
});
function comma_to_dot(obj){
	var valor=$(obj).val();
	$(obj).val(valor.replace(",","."));

}

function todayDate() {
	var fullDate = new Date();
	var twoDigitMonth = ((fullDate.getMonth().toString().length) !== 1)? (fullDate.getMonth()) : '0' + (fullDate.getMonth()+1);
	var twoDigitDay = ((fullDate.getDate().toString().length) !== 1)? (fullDate.getDate()) : '0' + (fullDate.getDate());
	var data_hoje = fullDate.getFullYear() + "-" + twoDigitMonth + "-" + twoDigitDay;
	return data_hoje;
}

function comparedatesType(d,d2,t) {
	//t = 1 verifica se d2 maior que d1
	//t = 2 verifica se d2 menor que d1
	//t = 3 verifica se d2 igual que d1
    var isDate = false;
    try {
        var selectedDate = new Date(d);
        var selectedDate = new Date(selectedDate.getFullYear(),selectedDate.getMonth(),selectedDate.getDate());
        var selectedDate2 = new Date(d2);
        var selectedDate2 = new Date(selectedDate2.getFullYear(),selectedDate2.getMonth(),selectedDate2.getDate());

		if(t==1 && selectedDate2 > selectedDate ) { isDate = true; }
		if(t==2 && selectedDate2 < selectedDate ) { isDate = true; }
		if(t==3 && selectedDate2 == selectedDate ) { isDate = true; }

    }
    catch (e) { }

    return isDate;
}


/* LOADER */
function startLoad(txt) {
	if (typeof txt !== undefined && txt!="") {
		$("#txtLoad").html(txt);
	}
	$("#loading").find("#loadingGeral").show();
	$("#loading").show();
}

function endLoad() {
	$("#loading").hide();
	$("#txtLoad").html(lang["processing"]);
	$("#loading").find("#loadingGeral").hide();
	$("#loading").find(".btn").show();
}

/* MATH */
(function(){
	/**
	 * Decimal adjustment of a number.
	 *
	 * @param	{String}	type	The type of adjustment.
	 * @param	{Number}	value	The number.
	 * @param	{Integer}	exp		The exponent (the 10 logarithm of the adjustment base).
	 * @returns	{Number}			The adjusted value.
	 */
	function decimalAdjust(type, value, exp) {
		// If the exp is undefined or zero...
		if (typeof exp === 'undefined' || +exp === 0) {
			return Math[type](value);
		}
		value = +value;
		exp = +exp;
		// If the value is not a number or the exp is not an integer...
		if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
			return NaN;
		}
		// Shift
		value = value.toString().split('e');
		value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
		// Shift back
		value = value.toString().split('e');
		return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
	}

	// Decimal round
	if (!Math.round10) {
		Math.round10 = function(value, exp) {
			return decimalAdjust('round', value, exp);
		};
	}
	// Decimal floor
	if (!Math.floor10) {
		Math.floor10 = function(value, exp) {
			return decimalAdjust('floor', value, exp);
		};
	}
	// Decimal ceil
	if (!Math.ceil10) {
		Math.ceil10 = function(value, exp) {
			return decimalAdjust('ceil', value, exp);
		};
	}

    Number.prototype.toFixedB = function toFixed ( precision ) {
        var multiplier = Math.pow( 10, precision + 1 ),
            wholeNumber = Math.floor( this * multiplier );
        return (Math.round( wholeNumber / 10 ) * 10 / multiplier).toFixed(precision);
    }

    Number.prototype.toFixed10 = function(precision) {
        return Math.round10(this, -precision).toFixed(precision);
    }

})();
