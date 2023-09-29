
$( document ).ready(function() {
	    getpostalcode();
});

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
