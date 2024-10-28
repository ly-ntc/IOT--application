@extends('layouts.app')

@section('content')
    <style>
        .text-gray-500 {
            --tw-text-opacity: 1;
            color: #1037f0;

            .bg-white {
                --tw-bg-opacity: 1;
                background-color: #f0f1f8;
            }
        }

        /* Tùy chỉnh CSS */
        .date-group {
            display: flex;
            align-items: center;
            margin-right: 2rem;
            /* Khoảng cách giữa các nhóm */
        }

        .date-group label {
            margin-right: 0.5rem;
            /* Khoảng cách giữa nhãn và trường nhập liệu */
        }

        /* Nếu sử dụng Tailwind CSS */
        .space-x-2> :not([hidden])~ :not([hidden]) {
            margin-left: 0.5rem;
            /* Khoảng cách giữa nhãn và trường nhập liệu */
        }

        #clearAllFilters {
            font-size: 24px;
            /* Điều chỉnh kích thước phù hợp */
            margin-left: 10px;
            margin-top: 10px;
            /* Thêm khoảng cách giữa nút xóa và các ô input */
        }

        .sort-icon {
            cursor: pointer;
            margin-left: 5px;
            /* Khoảng cách giữa tiêu đề và biểu tượng */
        }

        .sort-icon.active {
            color: blue;
            /* Màu sắc cho biểu tượng đang được chọn */
        }
    </style>
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Data Sensors</h5>
        </div>
        {{-- Select --}}
        {{-- <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ search: false }" @click.outside="search = false">
            <form
                class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0"
                :class="{ '!block': search }" @submit.prevent="search = false" style="width: 500px; margin-bottom: 20px">
                <div class="relative flex items-center">
                    <!-- Thêm Select ở đây -->
                    <select
                        class="form-select bg-gray-100 border border-gray-300 text-gray-700 py-1 px-3 rounded-l-md ltr:mr-2 rtl:ml-2">
                        <option value="temperature">All</option>
                        <option value="temperature">Nhiệt độ</option>
                        <option value="humidity">Độ ẩm</option>
                        <option value="light">Ánh sáng</option>
                    </select>

                    <!-- Input -->
                    <input type="text"
                        class="peer form-input bg-gray-100 placeholder:tracking-widest ltr:pl-3 ltr:pr-9 rtl:pl-9 rtl:pr-3 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4"
                        placeholder="Search...">

                    <!-- Icon Search -->
                    <button type="button"
                        class="absolute right-2 top-1/2 -translate-y-1/2 h-9 w-9 appearance-none peer-focus:text-primary">
                        <svg class="mx-auto" width="16" height="16" viewbox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5"
                                opacity="0.5"></circle>
                            <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>

            <button type="button"
                class="search_btn rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 dark:bg-dark/40 dark:hover:bg-dark/60 sm:hidden"
                @click="search = ! search">
                <svg class="mx-auto h-4.5 w-4.5 dark:text-[#d0d2d6]" width="20" height="20" viewbox="0 0 24 24"
                    fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5">
                    </circle>
                    <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                </svg>
            </button>
        </div> --}}

        <!-- Date Filter Form -->
        <!-- Search and Filter Form -->
        <div class="mb-4 flex justify-content-around space-x-12">

            {{-- <div class="flex items-center space-x-2" style="margin-right: 30px">
                <label for="start-date" class="text-sm font-medium text-gray-700 dark:text-gray-300">Start Date:</label>
                <input type="date" id="start-date" name="startDate" id="start-date"
                    class="p-2 border rounded dark:bg-dark dark:text-white">
            </div>
            <div class="flex items-center space-x-2" style="margin-right: 30px">
                <label for="end-date" class="text-sm font-medium text-gray-700 dark:text-gray-300">End Date:</label>
                <input type="date" id="end-date" name="endDate" id="end-date"
                    class="p-2 border rounded dark:bg-dark dark:text-white">
            </div>
            <div class="flex items-center">
                <button type="submit" class="text-white p-2 rounded" style="background: #4361ee" id="sumbitFilter">Apply
                    Filter</button>
            </div> --}}
            <span id="clearAllFilters" class="clear-filter"
                style="cursor: pointer; font-size: 24px; margin-left: 10px;display: none;">
                <i class="fa-solid fa-filter-circle-xmark"></i>
            </span>

        </div>

        <!-- Table for Displaying Data -->
        <div class="mb-5">
            <div class="table-responsive">
                <table class="table text-center" id="dataTable">
                    <thead>
                        <tr>
                            <th>
                                Id
                            </th>
                            <th>
                                Temperature
                                <span class="sort-icon" data-sort-field="temperature"
                                    data-sort-direction="asc">&#9650;</span> <!-- Mũi tên lên -->
                                <span class="sort-icon" data-sort-field="temperature"
                                    data-sort-direction="desc">&#9660;</span> <!-- Mũi tên xuống -->
                            </th>
                            <th>
                                Humidity
                                <span class="sort-icon" data-sort-field="humidity" data-sort-direction="asc">&#9650;</span>
                                <!-- Mũi tên lên -->
                                <span class="sort-icon" data-sort-field="humidity" data-sort-direction="desc">&#9660;</span>
                                <!-- Mũi tên xuống -->
                            </th>
                            <th>
                                Light
                                <span class="sort-icon" data-sort-field="light" data-sort-direction="asc">&#9650;</span>
                                <!-- Mũi tên lên -->
                                <span class="sort-icon" data-sort-field="light" data-sort-direction="desc">&#9660;</span>
                                <!-- Mũi tên xuống -->
                            </th>
                            <th>
                                Dust
                                <span class="sort-icon" data-sort-field="dust" data-sort-direction="asc">&#9650;</span>
                                <!-- Mũi tên lên -->
                                <span class="sort-icon" data-sort-field="dust" data-sort-direction="desc">&#9660;</span>
                                <!-- Mũi tên xuống -->
                            </th>
                            <th>
                                Time
                                <span class="sort-icon" data-sort-field="time" data-sort-direction="asc">&#9650;</span>
                                <!-- Mũi tên lên -->
                                <span class="sort-icon" data-sort-field="time" data-sort-direction="desc">&#9660;</span>
                                <!-- Mũi tên xuống -->
                            </th>
                        </tr>
                        <tr>
                            <th></th>
                            <th>
                                <div class="input-group">
                                    <input type="text" id="searchTemperature" class="form-control"
                                        placeholder="Search Temperature">
                                    <span class="input-group-addon clear-filter" id="clearTemperature"
                                        style="display: none;">
                                        <i class="fa-solid fa-filter-circle-xmark"></i>
                                    </span>
                                </div>
                            </th>
                            <th>
                                <div class="input-group">
                                    <input type="text" id="searchHumidity" class="form-control"
                                        placeholder="Search Humidity">
                                    <span class="input-group-addon clear-filter" id="clearHumidity" style="display: none;">
                                        <i class="fa-solid fa-filter-circle-xmark"></i>
                                    </span>
                                </div>
                            </th>
                            <th>
                                <div class="input-group">
                                    <input type="text" id="searchLight" class="form-control" placeholder="Search Light">
                                    <span class="input-group-addon clear-filter" id="clearLight" style="display: none;">
                                        <i class="fa-solid fa-filter-circle-xmark"></i>
                                    </span>
                                </div>
                            </th>
                            <th>
                                <div class="input-group">
                                    <input type="text" id="searchDust" class="form-control" placeholder="Search Dust">
                                    <span class="input-group-addon clear-filter" id="clearLight" style="display: none;">
                                        <i class="fa-solid fa-filter-circle-xmark"></i>
                                    </span>
                                </div>
                            </th>
                            <th>
                                <div class="input-group">
                                    <input type="datetime-local" id="searchTime" class="form-control" placeholder="Search Time">
                                    <span class="input-group-addon clear-filter" id="clearTime" style="display: none;">
                                        <i class="fa-solid fa-filter-circle-xmark"></i>
                                    </span>
                                </div>
                                
                            </th>
                        </tr>
                    </thead>
                    <tbody id="dataTableBody">
                        <!-- Data will be inserted here by JavaScript -->
                    </tbody>
                </table>

                <!-- Pagination and Limit Dropdown -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <!-- Dropdown to select number of items per page -->
                    <div class="form-inline">
                        <label for="itemsPerPage" class="mr-2">Limit</label>
                        <select name="itemsPerPage" id="itemsPerPage" class="form-control w-auto" onchange="fetchData()">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <div id = "paginationLinks">

                    </div>
                </div>
            </div>
        </div>

        <!-- jQuery and AJAX Script to Fetch Data -->
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            // Fetch data function
            $(document).ready(function() {
                $('.sort-icon').on('click', function() {
                    const sortField = $(this).data('sort-field'); // Lấy trường sắp xếp
                    const sortDirection = $(this).data('sort-direction'); // Lấy hướng sắp xếp

                    // Gọi hàm fetchData với các tham số sắp xếp
                    fetchData(1, sortField,
                        sortDirection); // Fetch dữ liệu với trang đầu tiên và thông tin sắp xếp

                    // Làm nổi bật biểu tượng đang được sử dụng
                    $('.sort-icon').removeClass('active'); // Bỏ chọn tất cả các biểu tượng
                    $(this).addClass('active'); // Chọn biểu tượng hiện tại

                    // Thay đổi màu sắc biểu tượng khi được chọn
                    $('.sort-icon').css('color', ''); // Reset màu
                    $(this).css('color', 'blue'); // Màu của biểu tượng đang chọn
                });
                // Fetch data function
                function fetchData(page = 1, sortField = 'time', sortDirection = 'desc') {
                    const itemsPerPage = $('#itemsPerPage').val();
                    // console.log("Items per page:", itemsPerPage); // Log items per page
                    //xử lý sự kiện click chuột vào nút submitFilter
                    const searchTemperature = $('#searchTemperature').val();
                    const searchHumidity = $('#searchHumidity').val();
                    const searchLight = $('#searchLight').val();
                    const searchDust = $('#searchDust').val();
                    const searchTime = $('#searchTime').val();

                    console.log("search time", searchTime);

                    $.ajax({
                        url: `/api/data_sensors`,
                        type: 'GET',
                        data: {
                            page: page,
                            itemsPerPage: itemsPerPage,
                            temperature: searchTemperature,
                            humidity: searchHumidity,
                            light: searchLight,
                            dust: searchDust,
                            time: searchTime,
                            sortField: sortField, 
                            sortDirection: sortDirection 
                        },
                        success: function(response) {
                            $('#dataTableBody').empty(); 
                            if (response.data && response.data.length > 0) {
                                response.data.forEach(item => {
                                    $('#dataTableBody').append(`
                            <tr>
                                <td>${item.id}</td>
                                <td>${item.temperature}</td>
                                <td>${item.humidity}</td>
                                <td>${item.light}</td>
                                <td>${item.dust}</td>
                                <td>${item.time}</td>
                            </tr>
                        `);
                                });
                            } else {
                                $('#dataTableBody').append(`
                        <tr>
                            <td colspan="5" class="text-center">No data found</td>
                        </tr>
                    `);
                            }
                            $('#paginationLinks').html(response.links);
                        },
                        error: function(error) {
                            console.log("Error:", error);
                        }
                    });
                }
                $('#sumbitFilter').on('click', function(e) {
                    e.preventDefault(); // Ngăn chặn hành vi submit mặc định của form (nếu là button trong form)
                    fetchData(); // Gọi fetchData sau khi nhấn nút submit
                });
                // Function to toggle clear filter icon
                function toggleClearFilterIcon(inputId, iconId) {
                    const inputVal = $(inputId).val();
                    // console.log(`Value of ${inputId}:`, inputVal.length);
                    // console.log(`Icon ID: ${iconId}`);
                    $(iconId).toggle(inputVal.length > 0); // Show the icon if there is input
                    if ($('.clear-filter:visible').length === 0) {
                        $('#clearAllFilters').hide(); // Hide the clear all filters icon
                    } else {
                        $('#clearAllFilters')
                            .show(); // Show the clear all filters icon if any clear-filter icon is visible
                    }
                }

                // Handle input events for filtering
                $('#searchTemperature, #searchHumidity, #searchLight,#searchDust, #searchTime').on('input change', function() {
                    const inputId = `#${this.id}`;
                    const iconId = `#clear${this.id.replace('search', '')}`

                    // Toggle the icon visibility based on input value
                    toggleClearFilterIcon(inputId, iconId);

                    // Fetch data on input change
                    fetchData();
                });

                // Clear input on icon click
                $('.clear-filter').on('click', function() {
                    const inputId = `#search${$(this).attr('id').replace('clear', '')}`;
                    $(inputId).val(''); // Clear the input
                    $(this).hide(); // Hide the icon
                    toggleClearAllFiltersIcon();
                    fetchData(); // Fetch data after clearing
                });

                // Trigger fetch on items per page change
                $(document).on('change', '#itemsPerPage', function() {
                    fetchData(); // Fetch data when items per page changes
                });

                // Handle pagination link clicks
                $(document).on('click', '#paginationLinks a', function(e) {
                    e.preventDefault(); // Prevent default link behavior
                    let page = $(this).attr('href').split('page=')[1]; // Get the page number from the link
                    fetchData(page); // Fetch data from the new page
                });
                fetchData(); // Initial data fetch
            });
            // Function to toggle the visibility of the clear all filters icon
            function toggleClearAllFiltersIcon() {
                const searchTemperature = $('#searchTemperature').val();
                const searchHumidity = $('#searchHumidity').val();
                const searchLight = $('#searchLight').val();
                const searchDust = $('#searchDust').val();
                const searchTime = $('#searchTime').val();

                // Show icon if any of the inputs have a value
                const shouldShowIcon = searchTime.length > 0 ||
                    searchDust.length > 0 ||
                    searchTemperature.length > 0 ||
                    searchHumidity.length > 0 ||
                    searchLight.length > 0;

                $('#clearAllFilters').toggle(shouldShowIcon); // Show or hide the icon
            }

            // Event listener for input change on date and search fields
            $('#searchTemperature, #searchHumidity, #searchLight,#searchDust, #searchTime').on('input change', function() {
                toggleClearAllFiltersIcon(); // Check whether to show the clear icon
            });

            // Clear all filters on icon click
            $('#clearAllFilters').on('click', function() {
                // Clear the input fields
                $('#searchTemperature').val('');
                $('#searchHumidity').val('');
                $('#searchLight').val('');
                $('#searchDust').val('');
                $('#searchTime').val('');
                // Hide all the small clear filter icons
                $('.clear-filter').hide(); // Hide all clear-filter icons

                // Hide the clear all filters icon
                $(this).hide();

            });

            // Initial call to ensure icon is hidden on page load
            $(document).ready(function() {
                $('#clearAllFilters').hide(); // Hide the icon initially
            });
        </script>

    </div>


    </div>
@endsection
