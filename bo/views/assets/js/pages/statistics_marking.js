function generalInfo(){
  $.post( "?controller=statistics&action=getmarking", {'servicesession':true, 'oiv':$('#center_sel').val(), 'datestart':$('#datestart').val(), 'dateend':$('#dateend').val()}, function( data ) {
    data = JSON.parse(data);

    startFlot1(data.appointment_month_label, data.appointment_month );

    startFlot2(data.appointment_insp_label, data.appointment_insp);

  });
}

function general(){
  generalInfo();
}

$(document).ready(function(){
  general();
});




var mixedChart1;
function startFlot2(label, values){
  if(mixedChart1 instanceof Chart){
    mixedChart1.destroy();
  }

  if(label.length==0 && values.length==0){
    return 1;
  }
  var ctx1 = document.getElementById("flot2");

    mixedChart1 = new Chart(ctx1, {
    type: 'doughnut',
    data: {
      datasets: [
              {
                data: values,
                backgroundColor:['rgba(54, 162, 235, 1)', 'rgba(179, 52, 38, 1)'],
                borderColor: ['rgba(54, 162, 235, 1)', 'rgba(179, 52, 38, 1)'],
              }
            ],
      labels: label
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
function startFlot1(label, values){
  if(mixedChart2 instanceof Chart){
    mixedChart2.destroy();
  }

  if(label.length==0 && values.length==0){
    return 1;
  }
  var l=[];
  for(var i=0; i<label.length; i++ ){
    l.push(lang['month_names'][label[i]]);
  }
  var ctx2 = document.getElementById("flot1");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart2 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label: lang['MENU_INSPECTIONS_APPOINTMENT'],
                data: values,
                backgroundColor:'rgba(54, 162, 235, 1)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
              }
            ],
      labels: l
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
