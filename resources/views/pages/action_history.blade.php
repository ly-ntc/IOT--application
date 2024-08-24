@extends('layouts.app')

@section('content')
    <div class="panel">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Action History</h5>
            <a class="font-semibold hover:text-gray-400 dark:text-gray-400 dark:hover:text-gray-600" href="javascript:;"
                @click="toggleCode('code1')">
                <span class="flex items-center">
                    <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 ltr:mr-2 rtl:ml-2">
                        <path
                            d="M17 7.82959L18.6965 9.35641C20.239 10.7447 21.0103 11.4389 21.0103 12.3296C21.0103 13.2203 20.239 13.9145 18.6965 15.3028L17 16.8296"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                        <path opacity="0.5" d="M13.9868 5L10.0132 19.8297" stroke="currentColor" stroke-width="1.5"
                            stroke-linecap="round"></path>
                        <path
                            d="M7.00005 7.82959L5.30358 9.35641C3.76102 10.7447 2.98975 11.4389 2.98975 12.3296C2.98975 13.2203 3.76102 13.9145 5.30358 15.3028L7.00005 16.8296"
                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                    Code
                </span>
            </a>
        </div>
        <div class="mb-5">
            <div class="sm:ltr:mr-auto sm:rtl:ml-auto" x-data="{ search: false }" @click.outside="search = false">
                <form class="absolute inset-x-0 top-1/2 z-10 mx-4 hidden -translate-y-1/2 sm:relative sm:top-0 sm:mx-0 sm:block sm:translate-y-0" :class="{'!block' : search}" @submit.prevent="search = false" style="width: 300px;"> <!-- Adjust width here -->
                    <div class="relative">
                        <input type="text" class="peer form-input bg-gray-100 placeholder:tracking-widest ltr:pl-10 ltr:pr-10 rtl:pl-10 rtl:pr-10 sm:bg-transparent ltr:sm:pr-4 rtl:sm:pl-4" placeholder="Search..." style="padding-left: 10px; padding-right: 10px;"> <!-- Adjust padding here -->
                        <button type="button" class="absolute inset-y-0 right-0 h-full w-10 appearance-none peer-focus:text-primary">
                            <svg class="mx-auto h-6 w-6 dark:text-[#d0d2d6]" width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5"></circle>
                                <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </button>
                        <button type="button" class="absolute top-1/2 block -translate-y-1/2 hover:opacity-80 ltr:right-2 rtl:left-2 sm:hidden" @click="search = false">
                            <svg width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <circle opacity="0.5" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="1.5"></circle>
                                <path d="M14.5 9.50002L9.5 14.5M9.49998 9.5L14.5 14.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </button>
                    </div>
                </form>
                <button type="button" class="search_btn rounded-full bg-white-light/40 p-2 hover:bg-white-light/90 dark:bg-dark/40 dark:hover:bg-dark/60 sm:hidden" @click="search = ! search">
                    <svg class="mx-auto h-4.5 w-4.5 dark:text-[#d0d2d6]" width="20" height="20" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="11.5" cy="11.5" r="9.5" stroke="currentColor" stroke-width="1.5" opacity="0.5"></circle>
                        <path d="M18.5 18.5L22 22" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                    </svg>
                </button>
            </div>
        </div>        

        <div class="mb-5">
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
                        <tr>
                            <td>1</td>
                            <td>Device A</td>
                            <td>Action X</td>
                            <td>2024-08-24 10:00</td>
                            <td>User1</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>Device B</td>
                            <td>Action Y</td>
                            <td>2024-08-24 11:00</td>
                            <td>User2</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>Device C</td>
                            <td>Action Z</td>
                            <td>2024-08-24 12:00</td>
                            <td>User3</td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td>Device D</td>
                            <td>Action A</td>
                            <td>2024-08-24 13:00</td>
                            <td>User4</td>
                        </tr>
                        <tr>
                            <td>5</td>
                            <td>Device E</td>
                            <td>Action B</td>
                            <td>2024-08-24 14:00</td>
                            <td>User5</td>
                        </tr>
                        <tr>
                            <td>6</td>
                            <td>Device F</td>
                            <td>Action C</td>
                            <td>2024-08-24 15:00</td>
                            <td>User6</td>
                        </tr>
                        <tr>
                            <td>7</td>
                            <td>Device G</td>
                            <td>Action D</td>
                            <td>2024-08-24 16:00</td>
                            <td>User7</td>
                        </tr>
                        <tr>
                            <td>8</td>
                            <td>Device H</td>
                            <td>Action E</td>
                            <td>2024-08-24 17:00</td>
                            <td>User8</td>
                        </tr>
                        <tr>
                            <td>9</td>
                            <td>Device I</td>
                            <td>Action F</td>
                            <td>2024-08-24 18:00</td>
                            <td>User9</td>
                        </tr>
                        <tr>
                            <td>10</td>
                            <td>Device J</td>
                            <td>Action G</td>
                            <td>2024-08-24 19:00</td>
                            <td>User10</td>
                        </tr>
                    </tbody>
                </table>

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