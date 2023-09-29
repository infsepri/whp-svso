function validationVat(vat){
var error=0;
if (
vat.substr(0,1) != '1' &&
vat.substr(0,1) != '2' &&
vat.substr(0,1) != '3' &&
vat.substr(0,2) != '45' &&
vat.substr(0,1) != '5' &&
vat.substr(0,1) != '6' &&
vat.substr(0,2) != '70' &&
vat.substr(0,2) != '71' &&
vat.substr(0,2) != '72' &&
vat.substr(0,2) != '77' &&
vat.substr(0,2) != '79' &&
vat.substr(0,1) != '8' &&
vat.substr(0,2) != '90' &&
vat.substr(0,2) != '91' &&
vat.substr(0,2) != '98' &&
vat.substr(0,2) != '99'

) { error=1; return error; }
var check1 = vat.substr(0,1)*9;
var check2 = vat.substr(1,1)*8;
var check3 = vat.substr(2,1)*7;
var check4 = vat.substr(3,1)*6;
var check5 = vat.substr(4,1)*5;
var check6 = vat.substr(5,1)*4;
var check7 = vat.substr(6,1)*3;
var check8 = vat.substr(7,1)*2;

var total= check1 + check2 + check3 + check4 + check5 + check6 + check7 + check8;
var division= total / 11;
var module11=total - parseInt(division)*11;
if ( module11==1 || module11==0){ comparator=0; }
else { comparator= 11-module11;}


var lastNumber =vat.substr(8,1)*1;
if ( lastNumber != comparator ){ error=1;}

return error;

}
