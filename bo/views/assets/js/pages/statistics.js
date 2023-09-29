function generalInfo(){
  $.post( "?controller=statistics&action=getgeneralinfo", {'servicesession':true, 'oiv':$('#center_sel').val(), 'datestart':$('#datestart').val(), 'dateend':$('#dateend').val()}, function( data ) {
    data = JSON.parse(data);
    $("#btnStat1").html(data.appointment);
    $("#btnStat2").html(data.inspection);
    $("#btnStat3").html(data.reinspection);
    $("#btnStat4").html(data.aproved);
    $("#btnStat5").html(data.reproved);
    $("#btnStat6").html(data.inWaiting);
    startFlot1(data.aproved, data.reproved);
    startFlot2(data.bytypevehicle_label, data.bytypevehicle);
    startFlot3(data.byhours_label, data.byhours);
    if(data.reproved==0 && data.aproved==0){
      var val= 0;
    }else{
      var val= Math.round((data.reproved*100/(parseFloat(data.aproved)+parseFloat(data.reproved))) *100 )/100 ;
    }

    startFlot4(val);
    startFlot5(data.bytypeinspection_label, data.bytypeinspection);
  });
}

function general(){
  generalInfo();
  reloadtable('tableVehicule');
}

$(document).ready(function(){
  general();
});




var mixedChart1;
function startFlot1(aproved, reproved){
  if(mixedChart1 instanceof Chart){
    mixedChart1.destroy();
  }

  if(aproved==0 && reproved==0){
    return 1;
  }
  var ctx1 = document.getElementById("flot1");
  ctx1.height = $(ctx1).parent().height();
    mixedChart1 = new Chart(ctx1, {
    type: 'doughnut',
    data: {
      datasets: [
              {
                data: [aproved, reproved ],
                backgroundColor:['rgba(54, 162, 235, 1)', 'rgba(179, 52, 38, 1)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(179, 52, 38, 1)'],
              }
            ],
      labels: [lang['approved'], lang['reproved'] ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: {
           display: true,
           labels: {
               fontColor: '#2b2b2b'
           }
       }
    }
  });
}




var mixedChart2;
function startFlot2(label, values){
  if(mixedChart2 instanceof Chart){
    mixedChart2.destroy();
  }

  if(label.length==0 && values.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot2");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label: lang['by_veihiculetype_total'],
                data: values,
                backgroundColor:'rgba(54, 162, 235, 1)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
              }
            ],
      labels: label
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    callback: function(value, index, values) {
                      // Make sure a tick is not a fractional value
                      if (Math.floor(value) === value) {
                          return value;
                      }
                  }
                }
            }],

            xAxes: [
                {
                  ticks: {
                    callback: function(label, index, labels) {
                      if (/\s/.test(label)) {
                        return label.split(" ");
                      }else{
                        return label;
                      }
                    }
                  }
                }
              ]

        },
        legend: {
           display: true,
           labels: {
               fontColor: '#2b2b2b'
           }
       }
    }
  });
}





var mixedChart3;
function startFlot3(label, values){
  if(mixedChart3 instanceof Chart){
    mixedChart3.destroy();
  }

  if(label.length==0 && values.length==0){
    return 1;
  }
  var ctx3 = document.getElementById("flot3");
    ctx3.height = $(ctx3).parent().height();
    ctx3.width = $(ctx3).parent().width();
    mixedChart3 = new Chart(ctx3, {
    type: 'bar',
    data: {
      datasets: [
             {
                label: lang['by_hours_total'],
                data: values,
                backgroundColor:'rgba(54, 162, 235, 1)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
              }
            ],
      labels: label
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    callback: function(value, index, values) {
                      // Make sure a tick is not a fractional value
                      if (Math.floor(value) === value) {
                          return value;
                      }
                  }
                }
            }],

            xAxes: [
                {
                  ticks: {
                    callback: function(label, index, labels) {
                      if (/\s/.test(label)) {
                        return label.split(" ");
                      }else{
                        return label;
                      }
                    }
                  }
                }
              ]

        },
        legend: {
           display: true,
           labels: {
               fontColor: '#2b2b2b'
           }
       }
    }
  });
}







var mixedChart4;
function startFlot4(values){
  if(mixedChart4 instanceof Chart){
    mixedChart4.destroy();
  }

  if(  values==0){
    return 1;
  }
  var ctx4 = document.getElementById("flot4");
    ctx4.height = $(ctx4).parent().height();
    ctx4.width = $(ctx4).parent().width();
    mixedChart4 = new Chart(ctx4, {
    type: 'bar',
    data: {
      datasets: [
             {
                label: lang['by_percentage_reproved_total'],
                data: [values],
                backgroundColor:'rgba(54, 162, 235, 1)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
              }
            ],
      labels: [lang['by_percentage_reproved_total']]
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    callback: function(value, index, values) {
                      // Make sure a tick is not a fractional value
                      if (Math.floor(value) === value) {
                          return value;
                      }
                  }
                }
            }],

            xAxes: [
                {
                  ticks: {
                    callback: function(label, index, labels) {
                      if (/\s/.test(label)) {
                        return label.split(" ");
                      }else{
                        return label;
                      }
                    }
                  }
                }
              ]

        },
        legend: {
           display: true,
           labels: {
               fontColor: '#2b2b2b'
           }
       }
    }
  });
}






var mixedChart5;
function startFlot5(label, values){
  if(mixedChart5 instanceof Chart){
    mixedChart5.destroy();
  }

  if(label.length==0 && values.length==0){
    return 1;
  }
  var ctx5 = document.getElementById("flot5");
    ctx5.height = $(ctx5).parent().height();
    ctx5.width = $(ctx5).parent().width();
    mixedChart5 = new Chart(ctx5, {
    type: 'bar',
    data: {
      datasets: [
             {
                label: lang['by_inspectiontype_total'],
                data: values,
                backgroundColor:'rgba(54, 162, 235, 1)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
              }
            ],
      labels: label
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true,
                    callback: function(value, index, values) {
                      // Make sure a tick is not a fractional value
                      if (Math.floor(value) === value) {
                          return value;
                      }
                  }
                }
            }],

            xAxes: [
                {
                  ticks: {
                    callback: function(label, index, labels) {
                      if (/\s/.test(label)) {
                        return label.split(" ");
                      }else{
                        return label;
                      }
                    }
                  }
                }
              ]

        },
        legend: {
           display: true,
           labels: {
               fontColor: '#2b2b2b'
           }
       }
    }
  });
}
