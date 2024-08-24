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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    

    <!-- end main content section -->
@endsection
