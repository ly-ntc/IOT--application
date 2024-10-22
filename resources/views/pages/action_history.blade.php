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
            <h5 class="text-lg font-semibold dark:text-white-light">Action History</h5>

        </div>

        <div class="mb-5">
            <!-- Date Filter Form -->
            
            <div class="mb-4 flex justify-content-around space-x-12">
                <span id="clearAllFilters" class="clear-filter"
                    style="cursor: pointer; font-size: 24px; margin-left: 10px;display: none;">
                    <i class="fa-solid fa-filter-circle-xmark"></i>
                </span>
    
            </div>
            <!-- Table -->
            <div class="mb-5">
                <div class="table-responsive">
                    <table class="table text-center" id="dataTable">
                        <thead>
                            <tr>
                                <th>
                                    Id
                                </th>
                                <th>
                                    Device
                                    <span class="sort-icon" data-sort-field="device"
                                        data-sort-direction="asc">&#9650;</span> <!-- Mũi tên lên -->
                                    <span class="sort-icon" data-sort-field="device"
                                        data-sort-direction="desc">&#9660;</span> <!-- Mũi tên xuống -->
                                </th>
                                <th>
                                    Action
                                    <span class="sort-icon" data-sort-field="action" data-sort-direction="asc">&#9650;</span>
                                    <!-- Mũi tên lên -->
                                    <span class="sort-icon" data-sort-field="action" data-sort-direction="desc">&#9660;</span>
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
                                        <input type="text" id="searchDevice" class="form-control"
                                            placeholder="Search Device">
                                        <span class="input-group-addon clear-filter" id="clearDevice"
                                            style="display: none;">
                                            <i class="fa-solid fa-filter-circle-xmark"></i>
                                        </span>
                                    </div>
                                </th>
                                <th>
                                    <div class="input-group">
                                        <input type="text" id="searchAction" class="form-control"
                                            placeholder="Search Action">
                                        <span class="input-group-addon clear-filter" id="clearAction" style="display: none;">
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
                        const searchTime = $('#searchTime').val();
                        const searchDevice = $('#searchDevice').val();
                        const searchAction = $('#searchAction').val();
                        
    
                        $.ajax({
                            url: `/api/action_history`,
                            type: 'GET',
                            data: {
                                page: page,
                                itemsPerPage: itemsPerPage,
                                time: searchTime,
                                device: searchDevice,
                                action: searchAction,
                                sortField: sortField, // Truyền cột cần sắp xếp
                                sortDirection: sortDirection // Truyền thứ tự sắp xếp (asc/desc)
                            },
                            success: function(response) {
                                $('#dataTableBody').empty(); // Clear old table data
                                if (response.data && response.data.length > 0) {
                                    response.data.forEach(item => {
                                        $('#dataTableBody').append(`
                                <tr>
                                    <td>${item.id}</td>
                                    <td>${item.device}</td>
                                    <td>${item.action}</td>
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
                    $('#searchDevice, #searchAction, #searchTime').on('input change', function() {
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
    
                    // Trigger initial data fetch when the page loads
                    fetchData(); // Initial data fetch
                });
                // Function to toggle the visibility of the clear all filters icon
                function toggleClearAllFiltersIcon() {
                    const searchTime = $('#searchTime').val();
                    const searchDevice = $('#searchDevice').val();
                    const searchAction = $('#searchAction').val();
                  
    
                    // Show icon if any of the inputs have a value
                    const shouldShowIcon = searchTime.length>0 ||
                        searchAction.length > 0 ||
                        searchDevice.length > 0;
    
                    $('#clearAllFilters').toggle(shouldShowIcon); // Show or hide the icon
                }
    
                // Event listener for input change on date and search fields
                $('#searchDevice, #searchAction, #searchTime').on('input change', function() {
                    toggleClearAllFiltersIcon(); // Check whether to show the clear icon
                });
    
                // Clear all filters on icon click
                $('#clearAllFilters').on('click', function() {
                    // Clear the input fields
                   $('#searchTime').val('');
                    $('#searchDevice').val('');
                    $('#searchAction').val('');
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
