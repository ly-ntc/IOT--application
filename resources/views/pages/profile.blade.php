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
                            href="https://documenter.getpostman.com/view/36577462/2sAXxTbAMQ">
                            <!-- SVG api docs-->
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M6.94318 11h-.85227l.96023-2.90909h1.07954L9.09091 11h-.85227l-.63637-2.10795h-.02272L6.94318 11Zm-.15909-1.14773h1.60227v.59093H6.78409v-.59093ZM9.37109 11V8.09091h1.25571c.2159 0 .4048.04261.5667.12784.162.08523.2879.20502.3779.35937.0899.15436.1349.33476.1349.5412 0 .20833-.0464.38873-.1392.54119-.0918.15246-.2211.26989-.3878.35229-.1657.0824-.3593.1236-.5809.1236h-.75003v-.61367h.59093c.0928 0 .1719-.0161.2372-.0483.0663-.03314.1169-.08002.152-.14062.036-.06061.054-.13211.054-.21449 0-.08334-.018-.15436-.054-.21307-.0351-.05966-.0857-.10511-.152-.13636-.0653-.0322-.1444-.0483-.2372-.0483h-.2784V11h-.78981Zm3.41481-2.90909V11h-.7898V8.09091h.7898Z"/>
                                <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8.31818 2c-.55228 0-1 .44772-1 1v.72878c-.06079.0236-.12113.04809-.18098.07346l-.55228-.53789c-.38828-.37817-1.00715-.37817-1.39543 0L3.30923 5.09564c-.19327.18824-.30229.44659-.30229.71638 0 .26979.10902.52813.30229.71637l.52844.51468c-.01982.04526-.03911.0908-.05785.13662H3c-.55228 0-1 .44771-1 1v2.58981c0 .5523.44772 1 1 1h.77982c.01873.0458.03802.0914.05783.1366l-.52847.5147c-.19327.1883-.30228.4466-.30228.7164 0 .2698.10901.5281.30228.7164l1.88026 1.8313c.38828.3781 1.00715.3781 1.39544 0l.55228-.5379c.05987.0253.12021.0498.18102.0734v.7288c0 .5523.44772 1 1 1h2.65912c.5523 0 1-.4477 1-1v-.7288c.1316-.0511.2612-.1064.3883-.1657l.5435.2614v.4339c0 .5523.4477 1 1 1H14v.0625c0 .5523.4477 1 1 1h.0909v.0625c0 .5523.4477 1 1 1h.6844l.4952.4823c1.1648 1.1345 3.0214 1.1345 4.1863 0l.2409-.2347c.1961-.191.3053-.454.3022-.7277-.0031-.2737-.1183-.5342-.3187-.7207l-6.2162-5.7847c.0173-.0398.0342-.0798.0506-.12h.7799c.5522 0 1-.4477 1-1V8.17969c0-.55229-.4478-1-1-1h-.7799c-.0187-.04583-.038-.09139-.0578-.13666l.5284-.51464c.1933-.18824.3023-.44659.3023-.71638 0-.26979-.109-.52813-.3023-.71637l-1.8803-1.8313c-.3883-.37816-1.0071-.37816-1.3954 0l-.5523.53788c-.0598-.02536-.1201-.04985-.1809-.07344V3c0-.55228-.4477-1-1-1H8.31818Z"/>
                            </svg>                              
                        </a>
                    </li>
                    <li>
                        <a class="btn btn-danger flex h-10 w-10 items-center justify-center rounded-full p-0"

                            href="https://drive.google.com/file/d/16hmj9865I2m-zCDpyrK_GIaWTmrorCmd/view?usp=sharing">
                            <!-- SVG pdf file-->
                            <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 17v-5h1.5a1.5 1.5 0 1 1 0 3H5m12 2v-5h2m-2 3h2M5 10V7.914a1 1 0 0 1 .293-.707l3.914-3.914A1 1 0 0 1 9.914 3H18a1 1 0 0 1 1 1v6M5 19v1a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-1M10 3v4a1 1 0 0 1-1 1H5m6 4v5h1.375A1.627 1.627 0 0 0 14 15.375v-1.75A1.627 1.627 0 0 0 12.375 12H11Z"/>
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
