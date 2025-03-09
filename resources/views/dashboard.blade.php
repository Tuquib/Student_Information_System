@extends('layouts.dashboardLayout')

@section('title')
Dashboard
@endsection

@section('content')
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row">
        <div class="ms-3">
          <h3 class="mb-0 h4 font-weight-bolder">Dashboard</h3>
          <p class="mb-4">
            Student Enrollment Management System
          </p>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Total Students</p>
                  <h4 class="mb-0">{{ $totalStudents }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">person</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm">{{ $enrolledCount }} currently enrolled</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Total Subjects</p>
                  <h4 class="mb-0">{{ $totalSubjects }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">menu_book</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm">Available subjects</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
          <div class="card">
            <div class="card-header p-2 ps-3">
              <div class="d-flex justify-content-between">
                <div>
                  <p class="text-sm mb-0 text-capitalize">Total Enrolled Students</p>
                  <h4 class="mb-0">{{ $enrolledCount }}</h4>
                </div>
                <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                  <i class="material-symbols-rounded opacity-10">school</i>
                </div>
              </div>
            </div>
            <hr class="dark horizontal my-0">
            <div class="card-footer p-2 ps-3">
              <p class="mb-0 text-sm">Currently enrolled students</p>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card">
                <div class="card-header p-2 ps-3">
                    <div class="d-flex justify-content-between">
                        <div>
                            <p class="text-sm mb-0 text-capitalize">Unenrolled Students</p>
                            <h4 class="mb-0">{{ $unenrolledCount }}</h4>
                        </div>
                        <div class="icon icon-md icon-shape bg-gradient-dark shadow-dark shadow text-center border-radius-lg">
                            <i class="material-symbols-rounded opacity-10">person_off</i>
                        </div>
                    </div>
                </div>
                <hr class="dark horizontal my-0">
                <div class="card-footer p-2 ps-3">
                    <p class="mb-0 text-sm">Students pending enrollment</p>
                </div>
            </div>
        </div>
      </div>
      <div class="row mt-4">
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-body p-3">
              <div class="chart-container border-radius-lg py-3 pe-1 mb-3">
                <div class="chart">
                  <canvas id="chart-bars" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
              <h6 class="ms-2 mt-4 mb-0">Student Status Distribution</h6>
              <p class="text-sm ms-2">Enrolled vs Unenrolled Students</p>
            </div>
          </div>
        </div>
        <div class="col-lg-6 mb-4">
          <div class="card z-index-2">
            <div class="card-body p-3">
              <div class="chart-container border-radius-lg py-3 pe-1 mb-3">
                <div class="chart">
                  <canvas id="chart-line" class="chart-canvas" height="170"></canvas>
                </div>
              </div>
              <h6 class="ms-2 mt-4 mb-0">Course Distribution</h6>
              <p class="text-sm ms-2">Students per Course</p>
            </div>
          </div>
        </div>
      </div>
      <footer class="footer py-4  ">
        <div class="container-fluid">
          <div class="row align-items-center justify-content-lg-between">
            <div class="col-lg-6 mb-lg-0 mb-4">
              <div class="copyright text-center text-sm text-muted text-lg-start">
                © <script>
                  document.write(new Date().getFullYear())
                </script>,
                made with <i class="fa fa-heart"></i> by
                <a href="https://www.creative-tim.com" class="font-weight-bold" target="_blank">Creative Tim</a>
                for a better web.
              </div>
            </div>
            <div class="col-lg-6">
              <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com" class="nav-link text-muted" target="_blank">Creative Tim</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/presentation" class="nav-link text-muted" target="_blank">About Us</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/blog" class="nav-link text-muted" target="_blank">Blog</a>
                </li>
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/license" class="nav-link pe-0 text-muted" target="_blank">License</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </footer>
    </div>
 
@endsection

@push('scripts')
<script>
    // Student Status Distribution Chart
    var ctx = document.getElementById("chart-bars").getContext("2d");
    new Chart(ctx, {
        type: "doughnut",
        data: {
            labels: ["Enrolled", "Unenrolled"],
            datasets: [{
                label: "Students",
                backgroundColor: [
                    "rgba(67, 160, 71, 0.9)",
                    "rgba(255, 167, 38, 0.9)"
                ],
                borderColor: ["#ffffff", "#ffffff"],
                borderWidth: 2,
                data: [
                    {{ $studentStatus['Enrolled'] ?? 0 }},
                    {{ $studentStatus['Unenrolled'] ?? 0 }}
                ],
                hoverOffset: 4
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        usePointStyle: true,
                        pointStyle: 'circle',
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif",
                            weight: '500'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        family: "'Inter', sans-serif",
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13,
                        family: "'Inter', sans-serif"
                    },
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            let total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            let percentage = ((value * 100) / total).toFixed(1);
                            return `${context.label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });

    // Course Distribution Chart
    var ctx2 = document.getElementById("chart-line").getContext("2d");
    new Chart(ctx2, {
        type: "bar",
        data: {
            labels: @json($courseDistribution->pluck('course')),
            datasets: [{
                label: "Students",
                backgroundColor: 'rgba(67, 160, 71, 0.8)',
                borderColor: '#43A047',
                borderWidth: 1,
                borderRadius: 6,
                data: @json($courseDistribution->pluck('student_count')),
                maxBarThickness: 40,
                hoverBackgroundColor: 'rgba(67, 160, 71, 1)'
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        family: "'Inter', sans-serif",
                        weight: '600'
                    },
                    bodyFont: {
                        size: 13,
                        family: "'Inter', sans-serif"
                    },
                    callbacks: {
                        label: function(context) {
                            let value = context.raw;
                            let total = context.dataset.data.reduce((acc, val) => acc + val, 0);
                            let percentage = ((value * 100) / total).toFixed(1);
                            return `${value} students (${percentage}% of total)`;
                        }
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index',
            },
            scales: {
                y: {
                    grid: {
                        drawBorder: false,
                        display: true,
                        drawOnChartArea: true,
                        drawTicks: false,
                        borderDash: [4, 4],
                        color: 'rgba(0, 0, 0, 0.05)'
                    },
                    ticks: {
                        display: true,
                        color: '#344767',
                        padding: 10,
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif",
                            weight: '500'
                        },
                        callback: function(value) {
                            return value + ' students';
                        }
                    }
                },
                x: {
                    grid: {
                        drawBorder: false,
                        display: false,
                        drawOnChartArea: false,
                        drawTicks: false,
                    },
                    ticks: {
                        display: true,
                        color: '#344767',
                        padding: 10,
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif",
                            weight: '500'
                        }
                    }
                },
            },
            animation: {
                duration: 750,
                easing: 'easeInOutQuart'
            }
        }
    });

    // Auto refresh logic
    function refreshCharts() {
        fetch(window.location.href)
            .then(response => response.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newData = JSON.parse(doc.getElementById('chart-data').textContent);
                
                if (typeof charts !== 'undefined') {
                    if (charts.bars) {
                        charts.bars.data.datasets[0].data = [
                            newData.studentStatus.Enrolled || 0,
                            newData.studentStatus.Unenrolled || 0
                        ];
                        charts.bars.update();
                    }
                    if (charts.line) {
                        charts.line.data.labels = newData.courseDistribution.map(c => c.course);
                        charts.line.data.datasets[0].data = newData.courseDistribution.map(c => c.student_count);
                        charts.line.update();
                    }
                }
            });
    }

    // Refresh every 30 seconds
    setInterval(refreshCharts, 30000);
</script>

<!-- Add hidden element to store chart data for auto-refresh -->
<div id="chart-data" style="display: none;">
    @json([
        'studentStatus' => $studentStatus,
        'courseDistribution' => $courseDistribution
    ])
</div>
@endpush
