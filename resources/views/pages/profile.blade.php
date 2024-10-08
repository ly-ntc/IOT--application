@extends('layouts.app')

@section('content')
    <style>
        ul.mt-5.space-y-4.font-semibold.text-white-dark {
            font-size: 20px;
        }

        p.text-xl.font-semibold.text-primary {
            font-size: 24px;
        }
    </style>
   <div class="flex justify-center">
    <div class="panel" style="width:70%; min-width: 350px;">
        <div class="mb-5 flex items-center justify-between">
            <h5 class="text-lg font-semibold dark:text-white-light">Profile</h5>
        </div>
        <div class="mb-5">
            <div class="flex flex-col items-center justify-center text-center">
                <img id="user-image" alt="User image" class="mb-5 h-24 w-24 rounded-full object-cover">
                <p class="text-xl font-semibold text-primary" id="user-name">Name:</p>
            </div>
            <div class="m-auto max-w-[300px]">
                <ul class="mt-5 space-y-4 font-semibold text-white-dark">
                    <li class="flex items-center gap-2">
                        <span>Student code:</span> <span id="user-student-code"></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>Birthday:</span> <span id="user-birthday"></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>Address:</span> <span id="user-address"></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>Email:</span> <span id="user-email"></span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>Phone number:</span> <span id="user-phone-number"></span>
                    </li>
                </ul>
                <ul class="mt-7 flex items-center justify-center gap-2">
                    <li>
                        <a class="btn btn-info flex h-10 w-10 items-center justify-center rounded-full p-0"
                            href="https://github.com/lycuyt/IOT--application">
                            <!-- SVG Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewbox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="h-5 w-5">
                                <path
                                    d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z">
                                </path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a class="btn btn-danger flex h-10 w-10 items-center justify-center rounded-full p-0"
                            href="https://github.com/lycuyt/IOT--application">
                            <!-- SVG Icon -->
                            <svg width="24" height="24" viewbox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg" class="h-5 w-5">
                                <path
                                    d="M3.33946 16.9997C6.10089 21.7826 12.2168 23.4214 16.9997 20.66C18.9493 19.5344 20.3765 17.8514 21.1962 15.9286C22.3875 13.1341 22.2958 9.83304 20.66 6.99972C19.0242 4.1664 16.2112 2.43642 13.1955 2.07088C11.1204 1.81935 8.94932 2.21386 6.99972 3.33946C2.21679 6.10089 0.578039 12.2168 3.33946 16.9997Z"
                                    stroke="currentColor" stroke-width="1.5"></path>
                                <path opacity="0.5"
                                    d="M16.9497 20.5732C16.9497 20.5732 16.0107 13.9821 14.0004 10.5001C11.99 7.01803 7.05018 3.42681 7.05018 3.42681M7.57711 20.8175C9.05874 16.3477 16.4525 11.3931 21.8635 12.5801M16.4139 3.20898C14.926 7.63004 7.67424 12.5123 2.28857 11.4516"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"></path>
                            </svg>
                        </a>
                    </li>
                    <li>
                        <a class="btn btn-dark flex h-10 w-10 items-center justify-center rounded-full p-0"
                            href="https://github.com/lycuyt/IOT--application">
                            <!-- SVG Icon -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewbox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="h-5 w-5">
                                <path
                                    d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22">
                                </path>
                            </svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
    // Fetch user profile data from the API
    fetch('api/profile')
        .then(response => response.json())
        .then(data => {
            const user = data.user;
            console.log('user', user);
            // Populate the user profile data into the HTML
            document.getElementById('user-image').src = `/images/${user.image}`;
            document.getElementById('user-name').innerText = `Name: ${user.name}`;
            document.getElementById('user-student-code').innerText = user.student_code;
            document.getElementById('user-birthday').innerText = user.birthday;
            document.getElementById('user-address').innerText = user.address;
            document.getElementById('user-email').innerText = user.email;
            document.getElementById('user-phone-number').innerText = user.phone_number;
        })
        .catch(error => console.error('Error fetching profile:', error));
</script>

@endsection
