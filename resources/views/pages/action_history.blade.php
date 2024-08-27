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
    </style>
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Action History</h5>

        </div>

        <div class="mb-5">
            <!-- Date Filter Form -->
            <form action="" method="GET" class="mb-4 flex justify-content-around space-x-12">
                @csrf
                <!-- Start Date Group -->
                <div class="flex items-center space-x-2" style="margin-right: 30px">
                    <label for="start-date" class="text-sm font-medium text-gray-700 dark:text-gray-300">Start Date:</label>
                    <input type="date" id="start-date" name="start_date" value="{{ request('start_date') }}"
                        class="p-2 border rounded dark:bg-dark dark:text-white">
                </div>

                <!-- End Date Group -->
                <div class="flex items-center space-x-2" style="margin-right: 30px">
                    <label for="end-date" class="text-sm font-medium text-gray-700 dark:text-gray-300">End Date:</label>
                    <input type="date" id="end-date" name="end_date" value="{{ request('end_date') }}"
                        class="p-2 border rounded dark:bg-dark dark:text-white">
                </div>

                <!-- Submit Button -->
                <div class="flex items-center">
                    <button type="submit" class="text-white p-2 rounded" style="background: #4361ee">Apply Filter</button>
                </div>
            </form>



            <!-- Table -->
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>
                                Device
                                <a href="javascript:;" onclick="sortTable('temperature', 'asc')">↑</a>
                                <a href="javascript:;" onclick="sortTable('temperature', 'desc')">↓</a>
                            </th>
                            <th>
                                Action
                                <a href="javascript:;" onclick="sortTable('humidity', 'asc')">↑</a>
                                <a href="javascript:;" onclick="sortTable('humidity', 'desc')">↓</a>
                            </th>
                            <th>
                                Time
                                <a href="javascript:;" onclick="sortTable('light', 'asc')">↑</a>
                                <a href="javascript:;" onclick="sortTable('light', 'desc')">↓</a>
                            </th>
                            <th>
                                User
                                <a href="javascript:;" onclick="sortTable('time', 'asc')">↑</a>
                                <a href="javascript:;" onclick="sortTable('time', 'desc')">↓</a>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Sample Data Rows -->
                        @foreach ($allData as $index => $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $item->device }}</td>
                                <td>{{ $item->action }}</td>
                                <td>{{ $item->time }}</td>
                                <td>{{ $item->user->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <!-- Pagination and Items per Page -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <!-- Dropdown to select number of items per page -->
                    <form method="GET" action="{{ route('getAllAction') }}" class="">
                        <span for="itemsPerPage" class="mr-2">Limit</span>
                        <select name="itemsPerPage" id="itemsPerPage" class="form-control w-auto border"
                            onchange="this.form.submit()">
                            <option value="10" {{ request('itemsPerPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="15" {{ request('itemsPerPage') == 15 ? 'selected' : '' }}>15</option>
                            <option value="20" {{ request('itemsPerPage') == 20 ? 'selected' : '' }}>20</option>
                        </select>
                    </form>

                    <!-- Pagination links -->
                    <div>
                        {!! $allData->links() !!}
                    </div>
                </div>
            </div>
        </div>


    </div>
    <script>
        function sortTable(column, order) {
            // Implement sorting logic here
            console.log(`Sorting ${column} in ${order} order.`);
        }

        document.getElementById('searchInput').addEventListener('input', function() {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll('#tableBody tr');

            rows.forEach(row => {
                let cells = row.getElementsByTagName('td');
                let match = false;

                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].textContent.toLowerCase().includes(filter)) {
                        match = true;
                        break;
                    }
                }

                row.style.display = match ? '' : 'none';
            });
        });
    </script>
@endsection
