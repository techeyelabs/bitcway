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

    <link rel="stylesheet" href="/css/style.css">
    <style>
        .txtWhitecolor{
            color: white !important;
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
        .dropdown-menu{
            min-width: 4.5rem;
            background-color: #081420;
            /*border: 1px solid rgba(0,0,0,.15);*/
            border: 1px solid rgba(255,255,255,0.15);
            color: white;
        }
        .dropdown-menu .dropdown-item > li > a:hover {
            /*background-image: none;*/
            background-color: #081420 !important;
        }
        .dropdown-menu a{
            color: white;
        }
    </style>
    @yield('custom_css')

    <title>{{env('APP_NAME')}}</title>
</head>

<body>


    <nav class="navbar navbar-expand-lg navbar-dark fixed-top bgco">
        <div class="container-fluid">
            <a class="" href="{{route('front-home', app()->getLocale())}}" style="padding-right:20px">
                <img src="./images/logo.png" alt="" width="150" height="50%">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse sideNavBar" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="txtWhitecolor nav-link"  aria-current="page" href="{{route('front-home', app()->getLocale())}}">{{__('nav1')}}</a>
                    </li>
                    {{--{{!Auth::check()?'disabled':''}}--}}
                    <li class="nav-item">
                        <?php if(Auth::check()){?>
                        <a class="txtWhitecolor nav-link " aria-current="page" href="{{route('user-trade', app()->getLocale())}}">{{__('nav2')}}</a>
                        <?php }else{?>
                        <a class="txtWhitecolor nav-link" aria-current="page" href="{{route('login', app()->getLocale())}}">{{__('nav2')}}</a>
                        <?php }?>
                    </li>
                    <li class="nav-item">
                        <?php if(Auth::check()){?>
                        <a class="txtWhitecolor nav-link " aria-current="page" href="{{route('user-trade', ['type' => 'derivative', app()->getLocale()])}}">{{__('nav3')}}</a>
                        <?php }else{?>
                        <a class="txtWhitecolor nav-link " aria-current="page" href="{{route('login', app()->getLocale())}}">{{__('nav3')}}</a>
                        <?php }?>
                    </li>
                    <li class="nav-item">
                        <?php if(Auth::check()){?>
                        <a class="txtWhitecolor nav-link " aria-current="page" href="{{route('user-trade-finance', app()->getLocale())}}">{{__('nav4')}}</a>
                        <?php }else{?>
                        <a class="txtWhitecolor nav-link " aria-current="page" href="{{route('login', app()->getLocale())}}">{{__('nav4')}}</a>
                        <?php }?>
                    </li>
                    <li class="nav-item">
                        <a class="txtWhitecolor nav-link" href="{{route('front-terms', app()->getLocale())}}">{{__('nav5')}}</a>
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0 ">
                    <?php if(Auth::check()){?>
                        <li class="nav-item" style="margin-right: 20px">
                            <a class="txtWhitecolor nav-link" href="{{route('user-wallets', app()->getLocale())}}"><i id="userIcon" class="fas fa-user"></i>  {{Auth::user()->first_name.' '.Auth::user()->last_name}}</a>
                        </li>
                    <?php }else{?>
                    <li class="nav-item" style="margin-right: 20px">
                        <a class="txtWhitecolor nav-link" aria-current="page" href="{{route('login', app()->getLocale())}}">{{__('nav6')}}</a>
                    </li>
{{--                    <li class="nav-item">--}}
{{--                        <a class="txtWhitecolor nav-link" href="{{route('signup', app()->getLocale())}}">{{__('nav7')}}</a>--}}
{{--                    </li>--}}
                    <?php }?>
                        <li>
                            <language-switcher></language-switcher>
                        </li>
                </ul>
                <ul class="navbar-nav mb-1 mb-lg-0">
                    <li class="nav-item dropdown" style="margin-right: 40px">
                        <a class="nav-link dropdown-toggle txtWhitecolor" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false" style="text-transform: uppercase;">
                            {{app()->getLocale()}}
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <a class="dropdown-item" href="{{ route(Route::currentRouteName(), 'jp') }}">
                                    <img class="jp_flag" style="width: 30px; height: 30px;"  src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxjaXJjbGUgc3R5bGU9ImZpbGw6I0YwRjBGMDsiIGN4PSIyNTYiIGN5PSIyNTYiIHI9IjI1NiIvPg0KPGNpcmNsZSBzdHlsZT0iZmlsbDojRDgwMDI3OyIgY3g9IjI1NiIgY3k9IjI1NiIgcj0iMTExLjMwNCIvPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPC9zdmc+DQo=" /> JP
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route(Route::currentRouteName(), 'en') }}">
                                    <img class="en_flag" style="width: 30px; height: 30px"  src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/Pg0KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDE5LjAuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPg0KPHN2ZyB2ZXJzaW9uPSIxLjEiIGlkPSJMYXllcl8xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDUxMiA1MTIiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDUxMiA1MTI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxjaXJjbGUgc3R5bGU9ImZpbGw6I0YwRjBGMDsiIGN4PSIyNTYiIGN5PSIyNTYiIHI9IjI1NiIvPg0KPHBhdGggc3R5bGU9ImZpbGw6I0Q4MDAyNzsiIGQ9Ik01MDkuODMzLDIyMi42MDloLTIyMC40NGgtMC4wMDFWMi4xNjdDMjc4LjQ2MSwwLjc0NCwyNjcuMzE3LDAsMjU2LDBzLTIyLjQ2MSwwLjc0NC0zMy4zOTEsMi4xNjcNCgl2MjIwLjQ0djAuMDAxSDIuMTY3QzAuNzQ0LDIzMy41MzksMCwyNDQuNjgxLDAsMjU2YzAsMTEuMzE5LDAuNzQ0LDIyLjQ2MSwyLjE2NywzMy4zOTFoMjIwLjQ0aDAuMDAxdjIyMC40NDINCglDMjMzLjUzOSw1MTEuMjU2LDI0NC42ODMsNTEyLDI1Niw1MTJzMjIuNDYxLTAuNzQzLDMzLjM5MS0yLjE2N3YtMjIwLjQ0di0wLjAwMWgyMjAuNDQyQzUxMS4yNTYsMjc4LjQ2MSw1MTIsMjY3LjMxOSw1MTIsMjU2DQoJQzUxMiwyNDQuNjgxLDUxMS4yNTYsMjMzLjUzOSw1MDkuODMzLDIyMi42MDl6Ii8+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8Zz4NCjwvZz4NCjxnPg0KPC9nPg0KPGc+DQo8L2c+DQo8L3N2Zz4NCg==" /> EN
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route(Route::currentRouteName(), 'cn') }}">
                                    <img class="cn_flag" style="width: 30px; height: 30px" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAxOS4wLjAsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiDQoJIHZpZXdCb3g9Ii00OSAxNDEgNTEyIDUxMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAtNDkgMTQxIDUxMiA1MTI7IiB4bWw6c3BhY2U9InByZXNlcnZlIj4NCjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+DQoJLnN0MHtmaWxsOiNEODAwMjc7fQ0KCS5zdDF7ZmlsbDojRkZEQTQ0O30NCjwvc3R5bGU+DQo8Y2lyY2xlIGNsYXNzPSJzdDAiIGN4PSIyMDciIGN5PSIzOTciIHI9IjI1NiIvPg0KPGc+DQoJPHBvbHlnb24gY2xhc3M9InN0MSIgcG9pbnRzPSI5MS4xLDI5Ni44IDExMy4yLDM2NC44IDE4NC43LDM2NC44IDEyNi45LDQwNi45IDE0OSw0NzQuOSA5MS4xLDQzMi45IDMzLjIsNDc0LjkgNTUuNCw0MDYuOSANCgkJLTIuNSwzNjQuOCA2OSwzNjQuOCAJIi8+DQoJPHBvbHlnb24gY2xhc3M9InN0MSIgcG9pbnRzPSIyNTQuNSw1MzcuNSAyMzcuNiw1MTYuNyAyMTIuNiw1MjYuNCAyMjcuMSw1MDMuOSAyMTAuMiw0ODMgMjM2LjEsNDg5LjkgMjUwLjcsNDY3LjQgMjUyLjEsNDk0LjIgDQoJCTI3OC4xLDUwMS4xIDI1Myw1MTAuNyAJIi8+DQoJPHBvbHlnb24gY2xhc3M9InN0MSIgcG9pbnRzPSIyODguMSw0NzYuNSAyOTYuMSw0NTAuOSAyNzQuMiw0MzUuNCAzMDEsNDM1IDMwOC45LDQwOS40IDMxNy42LDQzNC44IDM0NC40LDQzNC41IDMyMi45LDQ1MC41IA0KCQkzMzEuNSw0NzUuOSAzMDkuNiw0NjAuNCAJIi8+DQoJPHBvbHlnb24gY2xhc3M9InN0MSIgcG9pbnRzPSIzMzMuNCwzMjguOSAzMjEuNiwzNTMgMzQwLjgsMzcxLjcgMzE0LjMsMzY3LjkgMzAyLjUsMzkxLjkgMjk3LjksMzY1LjUgMjcxLjMsMzYxLjcgMjk1LjEsMzQ5LjIgDQoJCTI5MC41LDMyMi43IDMwOS43LDM0MS40IAkiLz4NCgk8cG9seWdvbiBjbGFzcz0ic3QxIiBwb2ludHM9IjI1NS4yLDI1NS45IDI1My4yLDI4Mi42IDI3OC4xLDI5Mi43IDI1MiwyOTkuMSAyNTAuMSwzMjUuOSAyMzYsMzAzLjEgMjA5LjksMzA5LjUgMjI3LjIsMjg5IA0KCQkyMTMsMjY2LjMgMjM3LjksMjc2LjQgCSIvPg0KPC9nPg0KPC9zdmc+DQo=" />  CN
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>


    <div id="wrap">
        <div class="container">
            @yield('content')
        </div>
    </div>
    <div class="container">
        @include('includes.footer')
    </div>
    



    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous">
    </script>

    {{-- jquery --}}
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"
        integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    {{-- vue dev version --}}
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.12/dist/vue.js"></script>

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

    <script src="{{ asset('js/app.js') }}" defer></script>

    @yield('custom_js')
</body>

</html>
