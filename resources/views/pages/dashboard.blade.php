@extends('layouts.app')
<style>
    .col {
        padding: 20px;
        color: white;
        text-align: center;
        font-size: 20px;
    }

    /* Gradient for Temperature */
    .bg-temperature {
        background: linear-gradient(90deg, #e0cbc6, #feb47b);
        /* Warm tones representing temperature */
    }

    /* Gradient for Humidity */
    .bg-humidity {
        background: linear-gradient(90deg, #9cb4b9, #51e1eb);
        /* Cool tones representing humidity */
    }

    /* Gradient for Light */
    .bg-light {
        background: linear-gradient(90deg, #cfd5a4, #ffd200);
        /* Bright tones representing light */
    }

    .circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.2);
        /* Semi-transparent white */
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto;
        /* Center the circle horizontally */
    }

    .circle span {
        font-weight: bold;
        color: white;
    }
</style>
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="col bg-temperature mr-2 " style="border-radius: 15px;">
                        <div class="circle">
                            <span>25°C</span>
                        </div>
                        Temperature
                    </div>
                    <div class="col bg-humidity mx-2" style="border-radius: 15px;">
                        <div class="circle">
                            <span>60%</span>
                        </div>
                        Humidity
                    </div>
                    <div class="col bg-light ml-2" style="border-radius: 15px;">
                        <div class="circle">
                            <span>300lx</span>
                        </div>
                        Light
                    </div>
                </div>
            </div>

        </div>
        {{-- <div class="row my-3">
            <div class="col-md-8 bg-warning" style="border-radius: 15px;">
                <canvas id="line-chart" style="width: 100%; height: 500px;"></canvas>
            </div>
            <div class="col-md-4 bg-secondary" style="border-radius: 15px;">
                <div class="row">
                    <div class="col bg-black">Quạt</div>
                </div>
                <div class="row">
                    <div class="col bg-primary">Điều hòa</div>
                </div>
                <div class="row">
                    <div class="col bg-mute">Đèn</div>
                </div>
            </div>
        </div> --}}

        <div class="row g-3 my-3">
            <div class="col-md-8 bg-warning p-3" style="border-radius: 15px;">
                <canvas id="line-chart" style="width: 100%; height: 500px;"></canvas>
            </div>
            <div class="col-md-4 bg-secondary p-3" style="border-radius: 15px;">
                {{-- Control elements go here --}}
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Sample data for the chart
        // Sample data for the chart
        const labels = ['00:00', '02:00', '04:00', '06:00', '08:00', '10:00', '12:00', '14:00', '16:00', '18:00', '20:00',
            '22:00'
        ];
        const temperatureData = [22, 23, 25, 28, 30, 31, 29, 27, 26, 24, 22, 21];
        const humidityData = [60, 62, 65, 70, 72, 73, 68, 64, 62, 61, 59, 57];
        const lightData = [300, 500, 700, 900, 1000, 1050, 950, 850, 750, 550, 400, 350];

        const ctx = document.getElementById('line-chart').getContext('2d');
        const lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Temperature (°C)',
                    data: temperatureData,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    fill: false,
                    tension: 0.1,
                    yAxisID: 'y' // Assign this dataset to the left y-axis
                }, {
                    label: 'Humidity (%)',
                    data: humidityData,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    fill: false,
                    tension: 0.1,
                    yAxisID: 'y' // Assign this dataset to the left y-axis
                }, {
                    label: 'Light (Lux)',
                    data: lightData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: false,
                    tension: 0.1,
                    yAxisID: 'y1' // Assign this dataset to the right y-axis
                }]
            },
            options: {
                responsive: true,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Time of Day'
                        }
                    },
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Temperature (°C) / Humidity (%)'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Light (Lux)'
                        },
                        grid: {
                            drawOnChartArea: false // Only draw grid lines for the left y-axis
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });
    </script>
@endsection
