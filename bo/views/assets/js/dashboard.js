function getRndInteger(min, max) {
  return Math.floor(Math.random() * (max - min + 1) ) + min;
}


var mixedChart;
function startFlot1(label, valuesexpense){
  if(mixedChart instanceof Chart){
    mixedChart.destroy();
  }

  if(label.length==0 && valuesexpense.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot1");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label:"Valor Gasto / Mês",
                data: valuesexpense,
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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
function startFlot2(label, valuesAppr, valuesRep){
  if(mixedChart2 instanceof Chart){
    mixedChart2.destroy();
  }

  if(label.length==0 && valuesAppr.length==0 && valuesRep.length==0){
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
                label:"Aprovadop",
                data: valuesAppr,
                backgroundColor:'rgba(54, 162, 235, 1)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
              },
             {
                label:"Reprovado",
                data: valuesRep,
                backgroundColor:'rgba(179, 52, 38, 1)',
                borderColor: 'rgba(179, 52, 38, 1)',
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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
function startFlot3(label, valuesexpenseyear){
  if(mixedChart3 instanceof Chart){
    mixedChart3.destroy();
  }

  if(label.length==0 && valuesexpenseyear.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot3");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart3 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label:"Valor Gasto / Ano",
                data: valuesexpenseyear,
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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
function startFlot4(label, valuesexpenseyear){
  if(mixedChart4 instanceof Chart){
    mixedChart4.destroy();
  }

  if(label.length==0 && valuesexpenseyear.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot4");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart4 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label:"Valor Recebido / Ano",
                data: valuesexpenseyear,
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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
function startFlot5(label, valuesexpenseyear1){
  if(mixedChart5 instanceof Chart){
    mixedChart5.destroy();
  }

  if(label.length==0 && valuesexpenseyear1.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot5");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart5 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label:"Valor Faturado / Ano",
                data: valuesexpenseyear1,
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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

/*________________________________________________ */
var mixedChart6;
function startFlot6(label, valuesrecipecyear){
  if(mixedChart6 instanceof Chart){
    mixedChart6.destroy();
  }

  if(label.length==0 && valuesrecipecyear.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot6");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart6 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label:"Valor das Candidaturas / Ano",
                data: valuesrecipecyear,
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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
/*________________________________________________ */
var mixedChart7;
function startFlot7(label, valuesrecipeayear){
  if(mixedChart7 instanceof Chart){
    mixedChart7.destroy();
  }

  if(label.length==0 && valuesrecipeayear.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot7");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart7 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label:"Valor das Atividades / Ano",
                data: valuesrecipeayear,
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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
/*________________________________________________ */
var mixedChart8;
function startFlot8(label, valuesrecipesyear){
  if(mixedChart8 instanceof Chart){
    mixedChart8.destroy();
  }

  if(label.length==0 && valuesrecipesyear.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot8");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart8 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label:"Valor das Vendas / Ano",
                data: valuesrecipesyear,
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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
/*________________________________________________ */
/*________________________________________________ */
var mixedChart9;
function startFlot9(label, valuesrecipetyear){
  if(mixedChart9 instanceof Chart){
    mixedChart9.destroy();
  }

  if(label.length==0 && valuesrecipetyear.length==0){
    return 1;
  }
  var ctx2 = document.getElementById("flot9");
    ctx2.height = $(ctx2).parent().height();
    ctx2.width = $(ctx2).parent().width();
    mixedChart9 = new Chart(ctx2, {
    type: 'bar',
    data: {
      datasets: [
             {
                label:"Valor das Cotas Pagas / Ano",
                data: valuesrecipetyear,
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
        plugins: {
          labels: {
            render: function (args) {
              return '';
            }
          }
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
/*________________________________________________ */



var mixedChart1;

function startFloat5(labels, values){

  var ctx4 = document.getElementById("flot5");
  if(mixedChart1 instanceof Chart){
    mixedChart1.destroy();
  }
      mixedChart1 = new Chart(ctx4, {
    type: 'bar',
    data: {
      datasets: [
              {
                label: lang['totalpayment'],
                data: values,
                backgroundColor:'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
              }
            ],
      labels: labels
    },
    options: {
        scales: {
            yAxes: [{
                ticks: {
                    beginAtZero:true
                }
            }]
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








var myChartflotSales;

function flotSales (data, labels,colors){




  var idflotSales = document.getElementById("flotSales");
  if(myChartflotSales instanceof Chart){
    myChartflotSales.destroy();
  }
    myChartflotSales = new Chart(idflotSales, {
      type: 'doughnut',
      data: {
        datasets: [
                {
                  data: data,
                  backgroundColor:colors,
                borderColor: colors

                }
              ],
        labels: labels
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


var myChartflotSaddegree;
function flotSadegree (data, labels,colors){

  var aux=[];
  labels.forEach(item => {


      switch (item) {
      case 1:
      aux.push(lang['1_sad']);
        break;
      case 2:
        aux.push(lang['2_sad1']);
        break;
      case 3:
          aux.push(lang['3_neutr']);
        break;
      case 4:
        aux.push(lang['4_happy']);
        break;
      case 5:
        aux.push(lang['5_happy1']);
      }


  });



  var idflotSaddegree = document.getElementById("flotsadegree");
  if(myChartflotSaddegree instanceof Chart){
    myChartflotSaddegree.destroy();
  }
    myChartflotSaddegree = new Chart(idflotSaddegree, {
      type: 'doughnut',
      data: {
        datasets: [
                {
                  data: data,

                  backgroundColor:colors,
                borderColor: colors

                }
              ],

        labels: aux
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
/*

$(window).on("load", function() {
	console.log("load");
	var s1 = getRndInteger(7000,9999); var c1 = getRndInteger(100,300);
	var s2 = getRndInteger(2000,6999); var c2 = getRndInteger(100,1000);
	var s3 = getRndInteger(2000,6999); var c3 = getRndInteger(100,1000);
	var s4 = getRndInteger(2000,6999); var c4 = getRndInteger(100,1000);
	var s5 = getRndInteger(2000,6999); var c5 = getRndInteger(100,1000);
	var s6 = getRndInteger(2000,6999); var c6 = getRndInteger(100,1000);

	var p1 = s1-c1;
	var p2 = s2-c2;
	var p3 = s3-c3;
	var p4 = s4-c4;
	var p5 = s5-c5;
	var p6 = s6-c6;

	LoadFloatSales(
		[s1,s2,s3,s4,s5,s6],
		["Redes de Gás", "Construção Civil", "Energia", "Redes de Água", "SISINFO", "Telecomunicações"],
		["rgba(236, 94, 105, 0.2)","rgba(255, 159, 64, 0.2)","rgba(241, 194, 5, 0.2)","rgba(99, 203, 137, 0.2)","rgba(0, 112, 224, 0.2)","rgba(153, 102, 255, 0.2)"]
	);

	LoadFloatCosts(
		[c1,c2,c3,c4,c5,c6],
		["Redes de Gás", "Construção Civil", "Energia", "Redes de Água", "SISINFO", "Telecomunicações"],
		["rgba(236, 94, 105, 0.2)","rgba(255, 159, 64, 0.2)","rgba(241, 194, 5, 0.2)","rgba(99, 203, 137, 0.2)","rgba(0, 112, 224, 0.2)","rgba(153, 102, 255, 0.2)"]
	);

	LoadFloatProfits(
		[p1,p2,p3,p4,p5,p6],
		["Redes de Gás", "Construção Civil", "Energia", "Redes de Água", "SISINFO", "Telecomunicações"],
		["rgba(236, 94, 105, 0.2)","rgba(255, 159, 64, 0.2)","rgba(241, 194, 5, 0.2)","rgba(99, 203, 137, 0.2)","rgba(0, 112, 224, 0.2)","rgba(153, 102, 255, 0.2)"]
	);

});
*/