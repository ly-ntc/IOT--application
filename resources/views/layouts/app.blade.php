<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bootstrap Sidebar</title>
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        h1 {
            font-weight: 800;
        }

        li {
            list-style: none;
        }

        a {
            text-decoration: none;
        }

        .main {
            min-height: 100vh;
            width: 100%;
            overflow: hidden;
            background-color: #f9f9f9;
        }

        #sidebar {
            max-width: 264px;
            min-width: 264px;
            transition: all 0.35s ease-in-out;
            background-color: #dcb1b1;
            display: flex;
            flex-direction: column;
        }

        #sidebar.collapsed {
            margin-left: -264px;
        }

        .toggler-btn {
            background-color: transparent;
            cursor: pointer;
            border: 0;
        }

        .toggler-btn i {
            font-size: 1.75rem;
            color: #dcb1b1;
            font-weight: 1000;
        }

        .navbar {
            padding: 1.15rem 1.5rem;
            border-bottom: 2px dashed #b3a8a8;
        }

        .sidebar-nav {
            flex: 1 1 auto;
        }

        .sidebar-logo {
            padding: 1.15rem 1.5rem;
            text-align: center;
        }

        .sidebar-logo a {
            color: #FFF;
            font-weight: 800;
            font-size: 1.5rem;
        }

        .sidebar-header {
            color: #FFF;
            font-size: .75rem;
            padding: 1.5rem 1.5rem .375rem;
        }

        a.sidebar-link {
            padding: .625rem 1.625rem;
            color: #FFF;
            position: relative;
            transition: all 0.35s;
            display: block;
            font-size: 1.25rem;
        }

        a.sidebar-link:hover {
            background-color: #f9f6f630;
        }

        .sidebar-link[data-bs-toggle="collapse"]::after {
            border: solid;
            border-width: 0 .075rem .075rem 0;
            content: "";
            display: inline-block;
            padding: 2px;
            position: absolute;
            right: 1.5rem;
            top: 1.4rem;
            transform: rotate(-135deg);
            transition: all .2s ease-out;
        }

        .sidebar-link[data-bs-toggle="collapse"].collapsed::after {
            transform: rotate(45deg);
            transition: all .2s ease-out;
        }

        /* Screen size less than 768px */

        @media (max-width:768px) {

            .sidebar-toggle {
                margin-left: -264px;
            }

            #sidebar.collapsed {
                margin-left: 0;
            }
        }
    </style>
<style>
    .navbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
    }
    .search-bar {
        flex: 1;
        text-align: center;
    }
    .search-bar .form-group {
        display: inline-block;
        width: 300px; /* Adjust the width of the search bar */
    }
    .navbar .user-info {
        display: flex;
        align-items: center;
    }
    .navbar .user-image {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-left: 10px;
    }
    .navbar .user-name {
        font-size: 16px;
        margin-left: 10px;
    }
</style>

</head>

<body>
    <div class="d-flex">
        @include('layouts.sidebar')
        {{-- Header --}}
        @include('layouts.header')
        {{-- end Header --}}
        <!-- Main Component -->
        <div class="main">
            <nav class="navbar navbar-expand">
                <button class="toggler-btn" type="button">
                    <i class="lni lni-text-align-left"></i>
                </button>
            
                <div class="search-bar">
                    <i class="fa-solid fa-magnifying-glass"></i>
                    <div class="form-group">
                        <input type="text" class="form-control" placeholder="Search">
                    </div>
                </div>
            
                <div class="user-info ml-auto">
                    
                    <img src="https://lh3.googleusercontent.com/ogw/AF2bZyiRtZJFfX22aVoZ6k_vr1CsXi3GTzOwRafWV5VtEH7Oyg=s64-c-mo"
                         alt="User Image" class="user-image">
                </div>
                <div class="div"><span class="user-name">John Doe</span></div>
            </nav>
            <main class="p-3">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @include('layouts.footer')
</body>

</html>
