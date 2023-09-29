function generalInfo(){
  $.post( "?controller=statistics&action=getinspectorinfo", {'servicesession':true, 'oiv':$('#center_sel').val(), 'datestart':$('#datestart').val(), 'dateend':$('#dateend').val(), 'motive':$("#motive_").val(), 'inspector':$("#inspector_").val()}, function( data ) {
    data = JSON.parse(data);

    startFlot1(data.countByInspector_label, data.countByInspector);

    startFlot2(data.countByReprovedInspector_label, data.countByReprovedInspector);

    startFlot3(data.countByAvgInspector_label, data.countByAvgInspector);

  });
}

function general(){
  generalInfo();
  reloadtable('tableDef');
}

$(document).ready(function(){
  general();
});




var mixedChart1;
function startFlot1(label, values){
  if(mixedChart1 instanceof Chart){
    mixedChart1.destroy();
  }

  if(label.length==0 && values.length==0){
    return 1;
  }
  var ctx1 = document.getElementById("flot1");
    ctx1.height = $(ctx1).parent().height();
    ctx1.width = $(ctx1).parent().width();
    mixedChart1 = new Chart(ctx1, {
    type: 'bar',
    data: {
      datasets: [
             {
                label: lang['inspection_by_insp'],
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
                label: lang['index_reproved_insp'],
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
                label: lang['time_avg_insp'],
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
