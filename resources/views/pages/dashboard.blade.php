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
            background: linear-gradient(135deg, #53687e, #e8f3fe);
        }

        /* Light Background Gradient (Even Lighter) */
        .bg-gradient-light-light {
            background: linear-gradient(135deg, #fff9d9, #fefde6);
        }

        .dark\:bg-gradient-light-dark-light {
            background: linear-gradient(135deg, #f1e181, #fefde6);
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
            color: #4361EE;
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
            color: #4361EE;
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
            <div class="mb-6 grid gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-3">
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
    {{-- <div>
        <h2>Temperature Data</h2>
        <p>Temperature: <span id="temperature">Loading...</span></p>
        <p>Humidity: <span id="humidity">Loading...</span></p>
        <p>Time: <span id="time">Loading...</span></p>
    </div> --}}
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
                acIcon.style.color = "#4361EE"; // Đổi màu khi bật
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


                        window.temperatureChartInstance = this.temperature;
                        window.humidityChartInstance = this.humidity;
                        window.lightChartInstance = this.light;
                        window.revenueChartInstance = this.revenueChart;


                        this.fetchLatestData();
                        setInterval(() => this.fetchLatestData(),
                            10000); // Fetch data every 10 seconds

                        this.fetchLatestData2();
                        setInterval(() => this.fetchLatestData2(), 10000);
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
                            data: [] // Placeholder for dynamic data
                        }, {
                            name: 'Humidity',
                            data: [] // Placeholder for dynamic data
                        }, {
                            name: 'Light',
                            data: [] // Placeholder for dynamic data
                        }],
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
                        colors: this.isDark ? ['#ff9800', '#00bcd4', '#4caf50'] : ['#ff5722',
                            '#03a9f4', '#8bc34a'
                        ],
                        markers: {
                            discrete: [{
                                seriesIndex: 0,
                                dataPointIndex: 6,
                                fillColor: '#ff9800',
                                strokeColor: 'transparent',
                                size: 7,
                            }, {
                                seriesIndex: 1,
                                dataPointIndex: 5,
                                fillColor: '#00bcd4',
                                strokeColor: 'transparent',
                                size: 7,
                            }, {
                                seriesIndex: 2,
                                dataPointIndex: 4,
                                fillColor: '#4caf50',
                                strokeColor: 'transparent',
                                size: 7,
                            }],
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
                            seriesIndex: 0, // Temperature
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
                            min: 0, // Minimum value for Temperature and Humidity
                            max: 100, // Maximum value for Temperature and Humidity
                        }, {
                            seriesIndex: 1, // Humidity
                            opposite: false, // Same side as Temperature
                            labels: {
                                formatter: (value) => value,
                                style: {
                                    fontSize: '12px',
                                    cssClass: 'apexcharts-yaxis-title',
                                },
                            },
                            // You can omit the title here since it is already set in the first y-axis config
                        }, {
                            seriesIndex: 2, // Light
                            opposite: true, // Opposite side
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
                            min: 0, // Minimum value for Light
                            max: 1000, // Maximum value for Light
                        }],

                        grid: {
                            borderColor: this.isDark ? '#191e3a' : '#e0e6ed',
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
                                inverseColors: false,
                                opacityFrom: this.isDark ? 0.19 : 0.28,
                                opacityTo: 0.05,
                                stops: this.isDark ? [100, 100] : [45, 100],
                            },
                        },
                    };
                },

                async fetchLatestData2() {
                    try {
                        const response = await fetch('api/latest-10-data');
                        const data = await response.json();

                        // Process the fetched data and update the chart
                        const temperatures = data.map(item => [new Date(item.time).getTime(), item
                            .temperature
                        ]);
                        const humidities = data.map(item => [new Date(item.time).getTime(), item
                            .humidity
                        ]);
                        const lights = data.map(item => [new Date(item.time).getTime(), item
                            .light
                        ]);

                        this.revenueChart.updateSeries([{
                            name: 'Temperature',
                            data: temperatures
                        }, {
                            name: 'Humidity',
                            data: humidities
                        }, {
                            name: 'Light',
                            data: lights
                        }]);
                    } catch (error) {
                        console.error('Error fetching data:', error);
                    }
                },
                fetchLatestData() {
                    fetch('api/latest-data')
                        .then(response => response.json())
                        .then(data => {
                            if (data) {
                                // Update temperature chart
                                if (window.temperatureChartInstance) {
                                    window.temperatureChartInstance.updateSeries([data
                                        .temperature
                                    ]);
                                    console.log('Temperature updated:', data.temperature);
                                }

                                // Update humidity chart
                                if (window.humidityChartInstance) {
                                    window.humidityChartInstance.updateSeries([data.humidity]);
                                    console.log('Humidity updated:', data.humidity);
                                }

                                // Update light chart
                                if (window.lightChartInstance) {
                                    window.lightChartInstance.updateSeries([(data.light / 1000) *
                                        100
                                    ]);
                                    console.log('Light updated:', data.light);
                                }
                            } else {
                                console.error('Unexpected data format:', data);
                            }
                        })
                        .catch(error => console.error('Error fetching data:', error));
                },

                get temperatureOptions() {
                    return {
                        series: [this.currentTemperatureValue], // Example temperature percentage
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
                        series: [this.currentHumidityValue], // Example humidity percentage
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
                        series: [this.currentLightValue], // Dynamically set the light value
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
                                                    size: '70%', // Default size
                                                },
                                                track: {
                                                    background: '#fff9c4', // Default track background
                                                },
                                                dataLabels: {
                                                    name: {
                                                        fontSize: '20px', // Default font size
                                                    },
                                                    value: {
                                                        fontSize: '36px', // Default font size
                                                    },
                                                },
                                            },
                                        },
                                        colors: ['#FFC107'], // Default color
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
                                        formatter: (val) => `${val*10} Lux`, // Display actual value
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
                }


            }));
        });
    </script>


    <!-- end main content section -->
@endsection
