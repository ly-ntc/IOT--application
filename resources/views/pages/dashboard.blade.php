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
            gap: 1rem;
            /* Space between rows */
        }

        .control-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-radius: 8px;
            height: 100px;
            /* Ensure uniform height */
            background: linear-gradient(to right, currentColor, transparent);
            /* Gradient effect */
        }

        .btn {
            background-color: white;
            color: black;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #ddd;
        }

        .fas {
            font-size: 2rem;
            /* Adjust size as needed */
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
                    <div
                        class="control-item bg-gradient-to-r from-blue-500 to-teal-500 p-4 flex items-center justify-between">
                        <button class="btn">Toggle Fan</button>
                        <i class="fas fa-fan  text-2xl"></i>
                    </div>
                    <div
                        class="control-item bg-gradient-to-r from-red-500 to-yellow-500 p-4 flex items-center justify-between">
                        <button class="btn">Toggle AC</button>
                        <i class="fas fa-snowflake  text-2xl"></i>
                    </div>
                    <div
                        class="control-item bg-gradient-to-r from-green-500 to-lime-500 p-4 flex items-center justify-between">
                        <button class="btn">Toggle Light</button>
                        <i class="fas fa-lightbulb  text-2xl"></i>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script></script>

    <!-- end main content section -->
@endsection
