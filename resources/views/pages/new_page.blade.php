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

        .blink {
            animation: blink-animation 1s steps(5, start) infinite;
            color: red !important;
        }

        @keyframes blink-animation {
            to {
                visibility: hidden;
            }
        }

        .blink-bg {
            animation: blink-bg-animation 1s steps(2, start) infinite;
            background-color: rgb(223, 210, 126) !important;
            /* Màu nền khi nhấp nháy */
        }

        @keyframes blink-bg-animation {
            to {
                background-color: white;
                /* Màu nền tắt khi nhấp nháy */
            }
        }
    </style>
    <!-- start main content section -->
    <div x-data="sales">
        <ul class="flex space-x-2 rtl:space-x-reverse">
            <li>
                <a href="javascript:;" class="text-primary hover:underline">New page</a>
            </li>

        </ul>

        <div class="pt-5">
            <div class="mb-6 grid gap-6 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-1">
                <div x-ref="du" class="rounded-lg bg-gradient-light-light dark:bg-gradient-light-dark-light">
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
                        <div x-ref="dustChart" class="rounded-lg bg-white dark:bg-black">
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
                    <div class="control-item flex items-center justify-between p-4 rounded-lg shadow-md" id= "bgCB"
                        style="height: 150px">

                        <i class="fas fa-snowflake text-4xl mr-4 cook-icon" id="cookIcon"></i>
                        <label class="switch">
                            <input type="checkbox" class="toggle" id="cookSwitch" onclick="toggleCook()">
                            <span class="slider"></span>
                        </label>
                    </div>

                    <div class="control-item flex items-center justify-between bg-white p-4 rounded-lg shadow-md"
                        style="height: 150px">
                        <p>Số lần cảnh báo trong ngày: <span id="alertCount">0</span></p>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        async function updateAlertCount() {
            try {
                const response = await fetch('/api/getCB');
                const data = await response.json();

                const alertCount = document.getElementById('alertCount');
                alertCount.textContent = data.count;

            } catch (error) {
                console.error('Error fetching alert count:', error);
            }
        }

        setInterval(updateAlertCount, 5000);

        updateAlertCount();
    </script>
    {{-- on/off --}}
    <script>
        async function getLatestCookStatus() {
            try {
                // Gọi API để lấy trạng thái AC hiện tại từ cơ sở dữ liệu
                let statusResponse = await fetch('/api/get-cook-status', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                // Nếu API trả về dữ liệu thành công
                if (statusResponse.ok) {
                    let statusData = await statusResponse.json();
                    let cookSwitch = document.getElementById("cookSwitch");
                    let cookIcon = document.getElementById("cookIcon");

                    // Cập nhật trạng thái của công tắc và icon dựa trên dữ liệu nhận được
                    if (statusData.status === 'on') {
                        cookSwitch.checked = true;
                        cookIcon.style.color = "#4361EE"; // Màu khi bật
                    } else {
                        cookSwitch.checked = false;
                        cookIcon.style.color = "gray"; // Màu khi tắt
                    }
                } else {
                    console.error("Failed to retrieve AC status from database.");
                }
            } catch (error) {
                console.error("Error fetching AC status:", error);
            }
        }
        // Gọi hàm này khi trang được tải
        document.addEventListener("DOMContentLoaded", function() {
            getLatestCookStatus();
        });
        async function toggleCook() {
            var acSwitch = document.getElementById("cookSwitch");
            var acIcon = document.getElementById("cookIcon");

            // Determine the status based on the switch's checked property
            var status = cookSwitch.checked ? 'ON' : 'OFF'; // Send 'ON' or 'OFF'

            try {
                // Gửi trạng thái (ON/OFF) tới Laravel
                let toggleResponse = await fetch('/api/toggle-cook', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        status: status
                    })
                });

                // Kiểm tra nếu API toggle đã thành công
                if (toggleResponse.ok) {
                    console.log("AC status sent successfully.");

                    let mqttResponse = await fetch('/api/get-mqtt-data', {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        }
                    });

                    // Nếu MQTT trả về dữ liệu thành công
                    if (mqttResponse.ok) {
                        let mqttData = await mqttResponse.json();
                        console.log("Received MQTT data:", mqttData);

                        setTimeout(() => {
                            acIcon.style.color = acSwitch.checked ? "#4361EE" : "gray";
                        }, 2000);
                    } else {
                        console.error("Failed to retrieve data from MQTT.");
                    }
                } else {
                    console.error("Failed to toggle AC status.");
                }
            } catch (error) {
                console.error("Error:", error);
            }
        }

        

        async function checkDustLevel() {
            try {
                const response = await fetch('/api/latest-data'); 
                if (!response.ok) {
                    throw new Error(`Failed to fetch data: ${response.status}`);
                }

                const data = await response.json();
                const dustLevel = data.dust; 
                const cookIcon = document.getElementById('cookIcon');
                const bgCB = document.getElementById('bgCB'); 

                console.log(dustLevel);

                if (dustLevel > 70) {
                    cookIcon.classList.add('blink');
                    bgCB.classList.add('blink-bg'); 

                    
                    let toggleResponse = await fetch('/api/alter', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            status: 'ON'
                        })
                    });

                    if (toggleResponse.ok) {
                        console.log("bật alter hehe");
                    } else {
                        const errorMessage = await toggleResponse.text();
                        console.error("Failed to turn on alter:", errorMessage);
                    }

                } else {
                    cookIcon.classList.remove('blink');
                    bgCB.classList.remove('blink-bg'); 

                    // Gọi đến thiết bị (tắt alter)
                    // let toggleResponse = await fetch('/api/alter', {
                    //     method: 'POST',
                    //     headers: {
                    //         'Content-Type': 'application/json',
                    //         'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    //     },
                    //     body: JSON.stringify({
                    //         status: 'OFF'
                    //     })
                    // });

                    // if (toggleResponse.ok) {
                    //     console.log("tắt alter hehe");
                    // } else {
                    //     const errorMessage = await toggleResponse.text();
                    //     console.error("Failed to turn off alter:", errorMessage);
                    // }
                }
            } catch (error) {
                console.error('Error fetching or processing data:', error);
            }
        }
        setInterval(checkDustLevel, 5000);
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


                    const dustChart = null;
                    const du = null;

                    // revenue
                    setTimeout(() => {

                        //dustChart
                        this.dustChart = new ApexCharts(this.$refs.dustChart, this
                            .dustChartOptions);
                        this.$refs.dustChart.innerHTML = '';
                        this.dustChart.render();
                        //du
                        this.du = new ApexCharts(this.$refs.du, this
                            .duOptions);
                        this.$refs.du.innerHTML = '';
                        this.du.render();



                        window.duChartInstance = this.du;
                        window.dustChartInstance = this.dustChart;



                        this.fetchLatestData();
                        setInterval(() => this.fetchLatestData(),
                            2000);

                        this.fetchLatestDataDust();
                        setInterval(() => this.fetchLatestDataDust(), 2000);
                    }, 300);


                    this.$watch('$store.app.theme', () => {
                        isDark = this.$store.app.theme === 'dark' || this.$store.app
                            .isDarkMode ? true : false;


                        this.dustChart.updateOptions(this.dustChartOptions);

                    });

                    this.$watch('$store.app.rtlClass', () => {
                        isRtl = this.$store.app.rtlClass === 'rtl' ? true : false;

                        this.dustChart.updateOptions(this.dustChartOptions);
                    });
                },



                get dustChartOptions() {
                    return {
                        series: [{
                            name: 'Dust',
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
                            seriesIndex: 0, // Dust
                            labels: {
                                formatter: (value) => value,
                                style: {
                                    fontSize: '12px',
                                    cssClass: 'apexcharts-yaxis-title',
                                },
                            },
                            title: {
                                text: 'Dust',
                            },
                            min: 0,
                            max: 100,
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


                async fetchLatestDataDust() {
                    try {
                        const response = await fetch('api/get-dust');
                        const data = await response.json();

                        // Process the fetched data and update the chart
                        const dusts = data.map(item => [new Date(item.time).getTime(), item
                            .dust
                        ]);


                        this.dustChart.updateSeries([{
                            name: 'Dust',
                            data: dusts
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
                                if (window.duChartInstance) {
                                    window.duChartInstance.updateSeries([data.dust]);
                                }

                            } else {
                                console.error('Unexpected data format:', data);
                            }
                        })
                        .catch(error => console.error('Error fetching data:', error));
                },
                get duOptions() {
                    return {
                        series: [this.currentDuValue], // Dynamically set the light value
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
                                        formatter: (val) => `${val} mg/m3`,
                                    },
                                },
                            },
                        },
                        colors: ['#FFC107'], // Base yellow color
                        stroke: {
                            lineCap: 'round',
                        },
                        labels: ['Dust'],
                    };
                },

            }));
        });
    </script>


    <!-- end main content section -->
@endsection
