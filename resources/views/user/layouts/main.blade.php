<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"
        integrity="sha512-HK5fgLBL+xu6dm/Ii3z4xhlSUyZgTT9tuc/hSrtw6uzJOvgRr2a9jyxxT1ely+B+xFAmJKVSTbpM/CuL7qxO8w=="
        crossorigin="anonymous" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="/css/user.css">
    <style>
        .sideBar{
            color: white !important;
        }
        .sideBar:hover{
            color: yellow !important;
        }
        .btn-outline-warning {
            color: #ffff00;
            border-color: #ffff00;
        }
        .btn-outline-warning:hover{
            color: black;
            border-color: yellow;
            background-color: yellow;
        }
        /*#lpro li {*/
        /*    display: inline-block;*/
        /*    list-style-type: none;*/
        /*    padding-right: 20px;*/
        /*    float: left;*/
        /*}*/
    </style>
   
    @yield('custom_css')

    <title>{{env('APP_NAME')}}</title>
</head>

<body>
    @include('includes.loader')
{{--    <i class="fas fa-angle-right"></i>--}}
    <div class="page-wrapper chiller-theme toggled">
{{--        <div class="columns">--}}
{{--            <ul id="lpro">--}}
{{--                <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">--}}
{{--                    <i id="pageSideBarIcon" class="fas fa-bars"></i>--}}
{{--                </a>--}}
{{--                <a id="show-sidebar d-none" class="graphArrow" href="#">--}}
{{--                    <i style="margin-top: 21px !important;" class="fas fa-angle-right fa-2x"></i>--}}
{{--                </a>--}}
{{--            </ul>--}}
{{--        </div>--}}
        <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
            <i id="pageSideBarIcon" class="fas fa-bars"></i>
        </a>

        <nav id="sidebar" class="sidebar-wrapper">
            <div class="sidebar-content">
                <div class="sidebar-brand">
                    <a class="logo txtWhitecolor" href="{{route('front-home', app()->getLocale())}}"><strong>BITC-WAY</strong></a>
                    <div id="close-sidebar">
                        <i class="fas fa-times"></i>
                    </div>
                </div>
                <div class="sidebar-header">
                    
                    <div class="user-info">
                        <span class="user-name txtWhitecolor">{{Auth::user()->first_name}}{{Auth::user()->last_name}}</span>
                    </div>
                </div>
                <!-- sidebar-search  -->
                <div class="sidebar-menu">
                    <ul>
                        <li class="header-menu">
                            <span class="txtWhitecolor">{{__('menuTitle1')}}</span>
                        </li>
                        <li>
                            <a href="{{route('user-wallets', app()->getLocale())}}">
                                <i class="fas fa-money-bill-wave sideBar"></i>
                                <span class="sideBar">{{__('menuoption7')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user-trade', app()->getLocale())}}">
                                <i class="fas fa-route sideBar"></i>
                                <span class="sideBar">{{__('menuoption3')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user-trade', ['type' => 'derivative', app()->getLocale()])}}">
                                <i class="fas fa-route sideBar"></i>
                                <span class="sideBar">{{__('menuoption4')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user-trade-finance', app()->getLocale())}}">
                                <i class="fas fa-money-check-alt sideBar"></i>
                                <span class="sideBar">{{__('menuoption5')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user-wallet', app()->getLocale())}}">
                                <i class="fas fa-wallet sideBar"></i>
                                <span class="sideBar">{{__('menuoption2')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user-transactions', app()->getLocale())}}">
                                <i class="fas fa-history sideBar"></i>
                                <span class="sideBar">{{__('history')}}</span>
                            </a>
                        </li>
                        <li class="header-menu">
                            <span class="sideBar">{{__('menuTitle2')}}</span>
                        </li>

                        <li>
                            <a href="{{route('user-message', app()->getLocale())}}">
                                <i class="fas fa-envelope sideBar"></i>
                                <span class="sideBar">{{__('menuoption8')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user-update-profile', app()->getLocale())}}">
                                <i class="fa fa-user sideBar"></i>
                                <span class="sideBar">{{__('menuoption9')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('user-change-password', app()->getLocale())}}">
                                <i class="fas fa-key sideBar"></i>
                                <span class="sideBar">{{__('menuoption10')}}</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{route('logout', app()->getLocale())}}">
                                <i class="fas fa-power-off sideBar"></i>
                                <span class="sideBar">{{__('menuoption11')}}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <!-- sidebar-wrapper  -->
        <main class="page-content">            
            <div class="container-fluid content">
                @yield('content')
                @include('includes.footer')
            </div>
        </main>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
     <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12"></script>
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
                if ($(this).parent().hasClass("active")
                ) {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this).parent().removeClass("active");
                } else {
                    $(".sidebar-dropdown").removeClass("active");
                    $(this).next(".sidebar-submenu").slideDown(200);
                    $(this).parent().addClass("active");
                }
            });

            $("#close-sidebar").click(function () {
                $(".page-wrapper").removeClass("toggled");
            });
            $("#show-sidebar").click(function () {
                $(".page-wrapper").addClass("toggled");
            });

            if($(window).width() <= 1024){
                $(".page-wrapper").removeClass("toggled");
            }
        });
    </script>
    @yield('custom_js')
</body>

</html>
