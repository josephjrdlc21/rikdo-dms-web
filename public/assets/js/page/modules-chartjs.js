"use strict";

var ctx = document.getElementById("myChart").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'line',
  data: {
    labels: ["2024", "2023", "2022", "2021", "2020", "2019"],
    datasets: [{
      label: 'Statistics',
      data: [460, 458, 330, 502, 430, 610],
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

var ctx = document.getElementById("myChart2").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'bar',
  data: {
    labels: ["2024", "2023", "2022", "2021", "2020", "2019"],
    datasets: [{
      label: 'Statistics',
      data: [460, 458, 330, 502, 430, 610],
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

var ctx = document.getElementById("myChart3").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'doughnut',
  data: {
    datasets: [{
      data: [
        80
      ],
      backgroundColor: [
        '#3abaf4',
      ],
      label: 'Dataset 1'
    }],
    labels: [
      'Completed',
    ],
  },
  options: {
    responsive: true,
    legend: {
      position: 'bottom',
    },
  }
});

var ctx = document.getElementById("myChart4").getContext('2d');
var myChart = new Chart(ctx, {
  type: 'pie',
  data: {
    datasets: [{
      data: [
        80,
        50,
        40,
        30,
      ],
      backgroundColor: [
        '#6777ef',
        '#63ed7a',
        '#ffa426',
        '#fc544b',
      ],
      label: 'Dataset 1'
    }],
    labels: [
      'Pending',
      'Approved',
      'For Revision',
      'Rejected',
    ],
  },
  options: {
    responsive: true,
    legend: {
      position: 'bottom',
    },
  }
});