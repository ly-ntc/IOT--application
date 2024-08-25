@extends('layouts.app')

@section('content')
    <style>
        /* Temperature Background Gradient (Even Lighter) */
        .bg-gradient-temperature-light {
            background: linear-gradient(135deg, #f2e0d4, #ffd8c0);
        }

        .dark\:bg-gradient-temperature-dark-light {
            background: linear-gradient(135deg, #ffb07c, #ffd8c0);
        }

        /* Humidity Background Gradient (Even Lighter) */
        .bg-gradient-humidity-light {
            background: linear-gradient(135deg, #e0e8f1, #e8f3fe);
        }

        .dark\:bg-gradient-humidity-dark-light {
            background: linear-gradient(135deg, #7db2e8, #e8f3fe);
        }

        /* Light Background Gradient (Even Lighter) */
        .bg-gradient-light-light {
            background: linear-gradient(135deg, #fff9d9, #fefde6);
        }

        .dark\:bg-gradient-light-dark-light {
            background: linear-gradient(135deg, #fff9d9, #fefde6);
        }

        .panel {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .control-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: 0.4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.4s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:checked+.slider:before {
            transform: translateX(26px);
        }

        .control-item i {
            font-size: 2.5rem;
            /* Điều chỉnh kích thước icon lớn hơn */
        }

        .control-item span {
            font-size: 1.25rem;
            /* Điều chỉnh kích thước văn bản lớn hơn */
        }

        /* Hiệu ứng quạt quay */
        .fan-icon.spin {
            animation: spin 1s linear infinite;
        }

        /* Animation quay */
        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }

        /* Icon bật và tắt */
        .ac-icon.on {
            color: blue;
            /* Màu khi bật */
        }

        .ac-icon.off {
            color: gray;
            /* Màu khi tắt */
        }
    </style>
    <!-- start main content section -->
    <div x-data="sales">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
            </li>

        </ul>

        <div class="pt-5">
            <div class="mb-6 grid gap-6 sm:grid-cols-2 xl:grid-cols-3">
                <!-- Temperature -->
                <div x-ref="temperature"
                    class="rounded-lg bg-gradient-temperature-light dark:bg-gradient-temperature-dark-light">
                    <!-- loader -->
                    <div
                        class="grid min-h-[300px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                        <span
                            class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
                    </div>
                </div>

                <!-- Humidity -->
                <div x-ref="humidity" class="rounded-lg bg-gradient-humidity-light dark:bg-gradient-humidity-dark-light">
                    <!-- loader -->
                    <div
                        class="grid min-h-[300px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                        <span
                            class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
                    </div>
                </div>

                <!-- Light -->
                <div x-ref="light" class="rounded-lg bg-gradient-light-light dark:bg-gradient-light-dark-light">
                    <!-- loader -->
                    <div
                        class="grid min-h-[300px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                        <span
                            class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
                    </div>
                </div>
            </div>


            <div class="mb-6 grid gap-6 xl:grid-cols-3">
                {{-- start chart --}}
                <div class="panel h-full xl:col-span-2">
                    <div class="mb-5 flex items-center dark:text-white-light">
                        <h5 class="text-lg font-semibold">Chart</h5>
                        <div x-data="dropdown" @click.outside="open = false" class="dropdown ltr:ml-auto rtl:mr-auto">
                            <a href="javascript:;" @click="toggle">
                                <svg class="h-5 w-5 text-black/70 hover:!text-primary dark:text-white/70"
                                    viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="5" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                                    </circle>
                                    <circle opacity="0.5" cx="12" cy="12" r="2" stroke="currentColor"
                                        stroke-width="1.5"></circle>
                                    <circle cx="19" cy="12" r="2" stroke="currentColor" stroke-width="1.5">
                                    </circle>
                                </svg>
                            </a>

                        </div>
                    </div>


                    <div class="relative overflow-hidden">
                        <div x-ref="revenueChart" class="rounded-lg bg-white dark:bg-black">
                            <!-- loader -->
                            <div
                                class="grid min-h-[325px] place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                                <span
                                    class="inline-flex h-5 w-5 animate-spin rounded-full border-2 border-black !border-l-transparent dark:border-white"></span>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- end chart --}}

                <div class="panel h-full flex flex-col space-y-4">
                    <!-- Fan Control Row -->
                    <div class="control-item flex items-center justify-between bg-white p-4 rounded-lg shadow-md">
                        <!-- Biểu tượng quạt -->
                        <i class="fas fa-fan text-4xl mr-4 fan-icon" id="fanIcon"></i>
                        <label class="switch">
                            <input type="checkbox" class="toggle" id="fanSwitch" onclick="toggleFan()">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- AC Control Row -->
                    <div class="control-item flex items-center justify-between bg-white p-4 rounded-lg shadow-md">
                        <!-- Biểu tượng điều hòa -->
                        <i class="fas fa-snowflake text-4xl mr-4 ac-icon" id="acIcon"></i>
                        <label class="switch">
                            <input type="checkbox" class="toggle" id="acSwitch" onclick="toggleAC()">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <!-- Light Control Row -->
                    <div class="control-item flex items-center justify-between bg-white p-4 rounded-lg shadow-md">
                        <!-- Biểu tượng bóng đèn -->
                        <i class="fas fa-lightbulb text-4xl mr-4" id="lightIcon"></i>
                        <label class="switch">
                            <input type="checkbox" class="toggle" id="lightSwitch" onclick="toggleLight()">
                            <span class="slider"></span>
                        </label>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div>
        <h2>Temperature Data</h2>
        <p>Temperature: <span id="temperature">Loading...</span></p>
        <p>Humidity: <span id="humidity">Loading...</span></p>
        <p>Time: <span id="time">Loading...</span></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- on/off --}}
    <script>
        function toggleFan() {
            var fanSwitch = document.getElementById("fanSwitch");
            var fanIcon = document.getElementById("fanIcon");
            if (fanSwitch.checked) {
                fanIcon.classList.add("spin"); // Quạt quay
            } else {
                fanIcon.classList.remove("spin"); // Quạt dừng quay
            }
        }

        function toggleAC() {
            var acSwitch = document.getElementById("acSwitch");
            var acIcon = document.getElementById("acIcon");
            if (acSwitch.checked) {
                acIcon.style.color = "blue"; // Đổi màu khi bật
            } else {
                acIcon.style.color = "gray"; // Đổi màu khi tắt
            }
        }

        function toggleLight() {
            var lightSwitch = document.getElementById("lightSwitch");
            var lightIcon = document.getElementById("lightIcon");
            if (lightSwitch.checked) {
                lightIcon.style.color = "yellow"; // Đổi màu khi bật
            } else {
                lightIcon.style.color = ""; // Trở về màu ban đầu khi tắt
            }
        }
    </script>
    {{-- data --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function fetchLatestData() {
                fetch('/latest-data')
                    .then(response => response.json())
                    .then(data => {
                        // Kiểm tra dữ liệu
                        if (data && typeof data.temperature === 'number' && typeof data.humidity === 'number') {
                            // Cập nhật các phần tử DOM
                            document.getElementById('temperature').textContent = temperature + '°C';
                            document.getElementById('humidity').textContent = humidity + '%';
                            document.getElementById('time').textContent = new Date(data.time).toLocaleString();

                            // Có thể thực hiện thêm các hành động với biến temperature ở đây
                            console.log('Updated temperature:', temperature);
                        } else {
                            console.error('Unexpected data format:', data);
                        }
                    })
                    .catch(error => console.error('Error fetching data:', error));
            }

            // Lấy dữ liệu ngay lập tức
            fetchLatestData();

            // Cập nhật dữ liệu mỗi 10 giây
            setInterval(fetchLatestData, 10000);
        });


        document.addEventListener('alpine:init', () => {
            // content section
            Alpine.data('sales', () => ({
                init() {
                    isDark = this.$store.app.theme === 'dark' || this.$store.app.isDarkMode ? true :
                        false;
                    isRtl = this.$store.app.rtlClass === 'rtl' ? true : false;

                    const revenueChart = null;
                    const temperature = null;
                    const humidity = null;
                    const light = null;
                    // revenue
                    setTimeout(() => {
                        this.revenueChart = new ApexCharts(this.$refs.revenueChart, this
                            .revenueChartOptions);
                        this.$refs.revenueChart.innerHTML = '';
                        this.revenueChart.render();

                        //temperature
                        this.temperature = new ApexCharts(this.$refs.temperature, this
                            .temperatureOptions);
                        this.$refs.temperature.innerHTML = '';
                        this.temperature.render();
                        //humidity
                        this.humidity = new ApexCharts(this.$refs.humidity, this
                            .humidityOptions);
                        this.$refs.humidity.innerHTML = '';
                        this.humidity.render();
                        //light
                        this.light = new ApexCharts(this.$refs.light, this
                            .lightOptions);
                        this.$refs.light.innerHTML = '';
                        this.light.render();

                    }, 300);

                    this.$watch('$store.app.theme', () => {
                        isDark = this.$store.app.theme === 'dark' || this.$store.app
                            .isDarkMode ? true : false;
                        this.revenueChart.updateOptions(this.revenueChartOptions);
                        this.temperature.updateOptions(this.temperatureOptions);
                        this.humidity.updateOptions(this.humidityOptions);
                        this.light.updateOptions(this.lightOptions);
                    });

                    this.$watch('$store.app.rtlClass', () => {
                        isRtl = this.$store.app.rtlClass === 'rtl' ? true : false;
                        this.revenueChart.updateOptions(this.revenueChartOptions);
                    });
                },
                get revenueChartOptions() {
                    return {
                        series: [{
                                name: 'Temperature',
                                data: [
                                    [new Date('2024-01-01').getTime(), 23],
                                    [new Date('2024-02-01').getTime(), 24],
                                    [new Date('2024-03-01').getTime(), 22],
                                    [new Date('2024-04-01').getTime(), 25],
                                    [new Date('2024-05-01').getTime(), 24],
                                    [new Date('2024-06-01').getTime(), 26],
                                    [new Date('2024-07-01').getTime(), 27],
                                    [new Date('2024-08-01').getTime(), 26],
                                    [new Date('2024-09-01').getTime(), 25],
                                    [new Date('2024-10-01').getTime(), 24],
                                    [new Date('2024-11-01').getTime(), 23],
                                    [new Date('2024-12-01').getTime(), 24],
                                ],
                                yaxis: {
                                    show: true,
                                    seriesIndex: 0,
                                    opposite: false,
                                },
                            },
                            {
                                name: 'Humidity',
                                data: [
                                    [new Date('2024-01-01').getTime(), 60],
                                    [new Date('2024-02-01').getTime(), 62],
                                    [new Date('2024-03-01').getTime(), 58],
                                    [new Date('2024-04-01').getTime(), 65],
                                    [new Date('2024-05-01').getTime(), 64],
                                    [new Date('2024-06-01').getTime(), 67],
                                    [new Date('2024-07-01').getTime(), 70],
                                    [new Date('2024-08-01').getTime(), 66],
                                    [new Date('2024-09-01').getTime(), 63],
                                    [new Date('2024-10-01').getTime(), 62],
                                    [new Date('2024-11-01').getTime(), 61],
                                    [new Date('2024-12-01').getTime(), 60],
                                ],
                                yaxis: {
                                    show: true,
                                    seriesIndex: 1,
                                    opposite: false,
                                },
                            },
                            {
                                name: 'Light',
                                data: [
                                    [new Date('2024-01-01').getTime(), 300],
                                    [new Date('2024-02-01').getTime(), 320],
                                    [new Date('2024-03-01').getTime(), 280],
                                    [new Date('2024-04-01').getTime(), 350],
                                    [new Date('2024-05-01').getTime(), 340],
                                    [new Date('2024-06-01').getTime(), 360],
                                    [new Date('2024-07-01').getTime(), 370],
                                    [new Date('2024-08-01').getTime(), 340],
                                    [new Date('2024-09-01').getTime(), 330],
                                    [new Date('2024-10-01').getTime(), 320],
                                    [new Date('2024-11-01').getTime(), 310],
                                    [new Date('2024-12-01').getTime(), 300],
                                ],
                                yaxis: {
                                    show: true,
                                    seriesIndex: 2,
                                    opposite: true,
                                },
                            }
                        ],
                        chart: {
                            height: 325,
                            type: 'area',
                            fontFamily: 'Nunito, sans-serif',
                            zoom: {
                                enabled: false,
                            },
                            toolbar: {
                                show: false,
                            },
                        },
                        dataLabels: {
                            enabled: false,
                        },
                        stroke: {
                            show: true,
                            curve: 'smooth',
                            width: 2,
                            lineCap: 'square',
                        },
                        dropShadow: {
                            enabled: true,
                            opacity: 0.2,
                            blur: 10,
                            left: -7,
                            top: 22,
                        },
                        colors: isDark ? ['#ff9800', '#00bcd4', '#4caf50'] : ['#ff5722', '#03a9f4',
                            '#8bc34a'
                        ],
                        markers: {
                            discrete: [{
                                    seriesIndex: 0,
                                    dataPointIndex: 6,
                                    fillColor: '#ff9800',
                                    strokeColor: 'transparent',
                                    size: 7,
                                },
                                {
                                    seriesIndex: 1,
                                    dataPointIndex: 5,
                                    fillColor: '#00bcd4',
                                    strokeColor: 'transparent',
                                    size: 7,
                                },
                                {
                                    seriesIndex: 2,
                                    dataPointIndex: 4,
                                    fillColor: '#4caf50',
                                    strokeColor: 'transparent',
                                    size: 7,
                                }
                            ],
                        },
                        xaxis: {
                            type: 'datetime',
                            axisBorder: {
                                show: false,
                            },
                            axisTicks: {
                                show: false,
                            },
                            labels: {
                                format: 'MMM yyyy',
                                style: {
                                    fontSize: '12px',
                                    cssClass: 'apexcharts-xaxis-title',
                                },
                            },
                        },
                        yaxis: [{
                                seriesIndex: 0,
                                labels: {
                                    formatter: (value) => value,
                                    style: {
                                        fontSize: '12px',
                                        cssClass: 'apexcharts-yaxis-title',
                                    },
                                },
                                title: {
                                    text: 'Temperature / Humidity',
                                },
                            },
                            {
                                seriesIndex: 2,
                                opposite: true,
                                labels: {
                                    formatter: (value) => value,
                                    style: {
                                        fontSize: '12px',
                                        cssClass: 'apexcharts-yaxis-title',
                                    },
                                },
                                title: {
                                    text: 'Light',
                                },
                            }
                        ],
                        grid: {
                            borderColor: isDark ? '#191e3a' : '#e0e6ed',
                            strokeDashArray: 5,
                            xaxis: {
                                lines: {
                                    show: true,
                                },
                            },
                            yaxis: {
                                lines: {
                                    show: false,
                                },
                            },
                            padding: {
                                top: 0,
                                right: 0,
                                bottom: 0,
                                left: 0,
                            },
                        },
                        legend: {
                            position: 'top',
                            horizontalAlign: 'right',
                            fontSize: '16px',
                            markers: {
                                width: 10,
                                height: 10,
                                offsetX: -2,
                            },
                            itemMargin: {
                                horizontal: 10,
                                vertical: 5,
                            },
                        },
                        tooltip: {
                            x: {
                                format: 'MMM dd, yyyy',
                            },
                            marker: {
                                show: true,
                            },
                        },
                        fill: {
                            type: 'gradient',
                            gradient: {
                                shadeIntensity: 1,
                                inverseColors: !1,
                                opacityFrom: isDark ? 0.19 : 0.28,
                                opacityTo: 0.05,
                                stops: isDark ? [100, 100] : [45, 100],
                            },
                        },
                    };
                },
                get temperatureOptions() {
                    return {
                        series: [30], // Example temperature percentage
                        chart: {
                            type: 'radialBar',
                            height: 240,
                            fontFamily: 'Nunito, sans-serif',
                            events: {
                                mouseEnter: function(event, chartContext, config) {
                                    chartContext.updateOptions({
                                        plotOptions: {
                                            radialBar: {
                                                hollow: {
                                                    size: '80%', // Increase size on hover
                                                },
                                                track: {
                                                    background: '#ffebee', // Light pink background on hover
                                                },
                                                dataLabels: {
                                                    name: {
                                                        fontSize: '22px', // Increase font size on hover
                                                    },
                                                    value: {
                                                        fontSize: '40px', // Increase font size on hover
                                                    },
                                                },
                                            },
                                        },
                                        colors: ['#FF5722'], // Orange color on hover
                                    });
                                },
                                mouseLeave: function(event, chartContext, config) {
                                    chartContext.updateOptions({
                                        plotOptions: {
                                            radialBar: {
                                                hollow: {
                                                    size: '70%', // Original size
                                                },
                                                track: {
                                                    background: '#ffe0b2', // Original track color
                                                },
                                                dataLabels: {
                                                    name: {
                                                        fontSize: '20px', // Original font size
                                                    },
                                                    value: {
                                                        fontSize: '36px', // Original font size
                                                    },
                                                },
                                            },
                                        },
                                        colors: ['#FF5722'], // Original color
                                    });
                                },
                            },
                        },
                        plotOptions: {
                            radialBar: {
                                startAngle: -90,
                                endAngle: 90,
                                hollow: {
                                    size: '70%',
                                    background: 'transparent',
                                },
                                track: {
                                    strokeWidth: '100%',
                                    background: '#ffe0b2', // Light peach background
                                },
                                dataLabels: {
                                    name: {
                                        show: true,
                                        fontSize: '20px',
                                        color: '#333',
                                        offsetY: -10,
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '36px',
                                        color: '#333',
                                        offsetY: 10,
                                        formatter: (val) => {
                                            return `${val}°C`;
                                        },
                                    },
                                },
                            },
                        },
                        colors: ['#FF5722'], // Base color
                        stroke: {
                            lineCap: 'round',
                        },
                        labels: ['Temperature'],
                    };
                },
                get humidityOptions() {
                    return {
                        series: [75], // Example humidity percentage
                        chart: {
                            type: 'radialBar',
                            height: 240,
                            fontFamily: 'Nunito, sans-serif',
                            events: {
                                mouseEnter: function(event, chartContext, config) {
                                    chartContext.updateOptions({
                                        plotOptions: {
                                            radialBar: {
                                                hollow: {
                                                    size: '80%', // Increase size on hover
                                                },
                                                track: {
                                                    background: '#e0f7fa', // Light cyan background on hover
                                                },
                                                dataLabels: {
                                                    name: {
                                                        fontSize: '22px', // Increase font size on hover
                                                    },
                                                    value: {
                                                        fontSize: '40px', // Increase font size on hover
                                                    },
                                                },
                                            },
                                        },
                                        colors: ['#00BCD4'], // Cyan color on hover
                                    });
                                },
                                mouseLeave: function(event, chartContext, config) {
                                    chartContext.updateOptions({
                                        plotOptions: {
                                            radialBar: {
                                                hollow: {
                                                    size: '70%', // Original size
                                                },
                                                track: {
                                                    background: '#b2ebf2', // Original track color
                                                },
                                                dataLabels: {
                                                    name: {
                                                        fontSize: '20px', // Original font size
                                                    },
                                                    value: {
                                                        fontSize: '36px', // Original font size
                                                    },
                                                },
                                            },
                                        },
                                        colors: ['#00BCD4'], // Original color
                                    });
                                },
                            },
                        },
                        plotOptions: {
                            radialBar: {
                                startAngle: -90,
                                endAngle: 90,
                                hollow: {
                                    size: '70%',
                                    background: 'transparent',
                                },
                                track: {
                                    strokeWidth: '100%',
                                    background: '#b2ebf2', // Light cyan background
                                },
                                dataLabels: {
                                    name: {
                                        show: true,
                                        fontSize: '20px',
                                        color: '#333',
                                        offsetY: -10,
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '36px',
                                        color: '#333',
                                        offsetY: 10,
                                        formatter: (val) => {
                                            return `${val}%`;
                                        },
                                    },
                                },
                            },
                        },
                        colors: ['#00BCD4'], // Base color
                        stroke: {
                            lineCap: 'round',
                        },
                        labels: ['Humidity'],
                    };
                },
                get lightOptions() {
                    return {
                        series: [75], // Example light intensity percentage
                        chart: {
                            type: 'radialBar',
                            height: 240,
                            fontFamily: 'Nunito, sans-serif',
                            events: {
                                mouseEnter: function(event, chartContext, config) {
                                    chartContext.updateOptions({
                                        plotOptions: {
                                            radialBar: {
                                                hollow: {
                                                    size: '80%', // Increase size on hover
                                                },
                                                track: {
                                                    background: '#fffde7', // Light yellow background on hover
                                                },
                                                dataLabels: {
                                                    name: {
                                                        fontSize: '22px', // Increase font size on hover
                                                    },
                                                    value: {
                                                        fontSize: '40px', // Increase font size on hover
                                                    },
                                                },
                                            },
                                        },
                                        colors: ['#FFC107'], // Yellow color on hover
                                    });
                                },
                                mouseLeave: function(event, chartContext, config) {
                                    chartContext.updateOptions({
                                        plotOptions: {
                                            radialBar: {
                                                hollow: {
                                                    size: '70%', // Original size
                                                },
                                                track: {
                                                    background: '#fff9c4', // Original track color
                                                },
                                                dataLabels: {
                                                    name: {
                                                        fontSize: '20px', // Original font size
                                                    },
                                                    value: {
                                                        fontSize: '36px', // Original font size
                                                    },
                                                },
                                            },
                                        },
                                        colors: ['#FFC107'], // Original color
                                    });
                                },
                            },
                        },
                        plotOptions: {
                            radialBar: {
                                startAngle: -90,
                                endAngle: 90,
                                hollow: {
                                    size: '70%',
                                    background: 'transparent',
                                },
                                track: {
                                    strokeWidth: '100%',
                                    background: '#fff9c4', // Light yellow background
                                },
                                dataLabels: {
                                    name: {
                                        show: true,
                                        fontSize: '20px',
                                        color: '#333',
                                        offsetY: -10,
                                    },
                                    value: {
                                        show: true,
                                        fontSize: '36px',
                                        color: '#333',
                                        offsetY: 10,
                                        formatter: (val) => {
                                            return `${val} Lux`;
                                        },
                                    },
                                },
                            },
                        },
                        colors: ['#FFC107'], // Base yellow color
                        stroke: {
                            lineCap: 'round',
                        },
                        labels: ['Light'],
                    };
                },
            }));
        });
    </script>


    <!-- end main content section -->
@endsection
