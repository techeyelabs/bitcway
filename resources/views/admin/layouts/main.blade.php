<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">

    {{-- fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous" />

    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

    <link rel="stylesheet" href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css">
    {{-- <link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/dataTables.bootstrap5.min.css"> --}}

    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/user.css">
    <link rel="stylesheet" href="/css/admin.css">
    @yield('custom_css')

    <title>{{env('APP_NAME')}}</title>
</head>

<body>




    @include('includes.loader')


    <div class="page-wrapper chiller-theme toggled">
        <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
            <i class="fas fa-bars"></i>
        </a>
        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <div class="sidebar-brand">
                    <a class="logo" href="{{route('front-home')}}"><strong>DMGCOIN</strong></a>
                    <div id="close-sidebar">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <div class="sidebar-header">
                    <div class="user-info">
                        <span class="user-name">{{Auth::guard('admin')->user()->email}}</span>
                    </div>
                </div>
                <!-- sidebar-search  -->
                <div class="sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <span>General</span>
                        </li>

                        <li>
                            <a href="{{route('admin-user-list')}}">
                                <i class="fas fa-user"></i>
                                <span>User Management</span>
                                {{-- <span class="badge badge-pill badge-primary">Beta</span> --}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin-deposit-list')}}">
                                <i class="fas fa-money-check-alt"></i>
                                <span>Deposit History</span>
                                {{-- <span class="badge badge-pill badge-primary">Beta</span> --}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin-withdraw-list')}}">
                                <i class="fas fa-hand-holding-usd"></i>
                                <span>Withdraw History</span>
                                {{-- <span class="badge badge-pill badge-primary">Beta</span> --}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin-message-list')}}">
                                <i class="fas fa-envelope"></i>
                                <span>Message</span>
                                {{-- <span class="badge badge-pill badge-primary">Beta</span> --}}
                            </a>
                        </li>

                        <li>
                            <a href="{{route('admin-dmg-coin')}}">
                                <i class="fas fa-coins"></i>
                                <span>DMG Coin</span>
                                {{-- <span class="badge badge-pill badge-primary">Beta</span> --}}
                            </a>
                        </li>

                        <li class="header-menu">
                            <span>Settings</span>
                        </li>

                       
                        <li>
                            <a href="{{route('admin-change-password')}}">
                                <i class="fas fa-key"></i>
                                <span>Change Password</span>
                                {{-- <span class="badge badge-pill badge-primary">Beta</span> --}}
                            </a>
                        </li>
                        <li>
                            <a href="{{route('admin-logout')}}">
                                <i class="fas fa-power-off"></i>
                                <span>Logout</span>
                                {{-- <span class="badge badge-pill badge-primary">Beta</span> --}}
                            </a>
                        </li>
                        


                    </ul>
                </div>
                <!-- sidebar-menu  -->
            </div>
        </nav>
        <!-- sidebar-wrapper  -->
        <main class="page-content">            
            <div class="container-fluid content">
                @yield('content')
                @include('includes.footer')
            </div>
        </main>
        <!-- page-content" -->
    </div>


    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js" integrity="sha384-q2kxQ16AaE6UbzuKqyBE9/u/KzioAlnx2maXQHiDX9d4/zp8Ok3f+M7DPm+Ib6IU" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.min.js" integrity="sha384-pQQkAEnwaBkjpqZ8RU1fF1AKtTcHJwFl3pblpTlHXybJjHpMYo79HY3hIi4NKxyj" crossorigin="anonymous"></script>
    -->

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    {{-- vue dev version --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

    {{-- vue production version --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script> --}}

    {{-- axios --}}
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
        let token = document.head.querySelector('meta[name="csrf-token"]');
        if (token) {
            window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
        } else {
            console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
        }

    </script>

    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })

    </script>


    <script>
        jQuery(function ($) {

            $(".sidebar-dropdown > a").click(function () {
                $(".sidebar-submenu").slideUp(200);
                if (
                    $(this)
                    .parent()
                    .hasClass("active")
                ) {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this)
                        .parent()
                        .removeClass("active");
                } else {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this)
                        .next(".sidebar-submenu")
                        .slideDown(200);
                    $(this)
                        .parent()
                        .addClass("active");
                }
            });

            $("#close-sidebar").click(function () {
                $(".page-wrapper").removeClass("toggled");
            });
            $("#show-sidebar").click(function () {
                $(".page-wrapper").addClass("toggled");
            });




        });

    </script>

    @yield('custom_js')
</body>

</html>
