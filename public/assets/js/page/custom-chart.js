var ctx = document.getElementById("myChart5").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: postedStatisticsData.labels,
    datasets: [{
      label: 'Statistics',
      data: postedStatisticsData.data,
      borderWidth: 2,
      backgroundColor: '#8c98f3',
      borderColor: '#6777ef',
      borderWidth: 2.5,
      pointBackgroundColor: '#ffffff',
      pointRadius: 4
    }]
  },
  options: {
    legend: {
      display: false
    },
    tooltips: {
      titleFontSize: 11
    },
    scales: {
      yAxes: [{
        gridLines: {
          drawBorder: false,
          color: '#f2f2f2',
        },
        ticks: {
          beginAtZero: true,
          stepSize: 150
        }
      }],
      xAxes: [{
        ticks: {
          display: false
        },
        gridLines: {
          display: false
        }
      }]
    },
  }
});

var ctx = document.getElementById("myChart6").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    datasets: [{
      data: researchStatusData.data,
      backgroundColor: [
        '#6777ef',
        '#63ed7a',
        '#ffa426',
        '#fc544b',
      ],
      label: 'Dataset 1'
    }],
    labels: researchStatusData.labels,
  },
  options: {
    responsive: true,
    legend: {
      position: 'bottom',
    },
  }
});

var ctx = document.getElementById("myChart7").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    datasets: [{
      data: [
        totalResearch
      ],
      backgroundColor: [
        '#3abaf4',
      ],
      label: 'Dataset 1'
    }],
    labels: [
      'Total Research',
    ],
  },
  options: {
    responsive: true,
    legend: {
      position: 'bottom',
    },
  }
});

var ctx = document.getElementById("myChart8").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: researchersStatisticsData.labels,
    datasets: [{
      label: 'Statistics',
      data: researchersStatisticsData.data,
      borderWidth: 2,
      backgroundColor: '#ccebff',
      borderColor: '#99d6ff',
      borderWidth: 2.5,
      pointBackgroundColor: '#ffffff',
      pointRadius: 4
    }]
  },
  options: {
    legend: {
      display: false
    },
    tooltips: {
      titleFontSize: 11
    },
    scales: {
      yAxes: [{
        gridLines: {
          drawBorder: false,
          color: '#f2f2f2',
        },
        ticks: {
          beginAtZero: true,
          stepSize: 150
        }
      }],
      xAxes: [{
        ticks: {
          display: false
        },
        gridLines: {
          display: false
        }
      }]
    },
  }
});