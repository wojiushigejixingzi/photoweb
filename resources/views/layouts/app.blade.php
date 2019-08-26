@extends('layouts.base')
@section('css')
    <style>
        #app{
            background-image: url(../image/loginbg.jpg);
            background-size: cover;
            background-position: 100% 100%;
            background-attachment: fixed;
            height: 100vh;
            width: 100%;
        }
        .navbar-laravel{
            background-color: #322550 !important;
        }
    </style>
@endsection
@section('body')
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light navbar-laravel">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    photpWeb
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="login">登陆</a>
                            </li>
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="register">注册</a>
                                </li>
                            @endif
                        @else
                            <Dropdown trigger="click" class="Tablist" @on-click="logout">
                                <a href="javascript:void(0)">
                                    {{ Auth::user()->name }}
                                    <Icon type="ios-arrow-down"></Icon>
                                </a>
                                <Dropdown-Menu slot="list">
                                    <Dropdown-Item name="{{ route('logout') }}">退出</Dropdown-Item>
                                </Dropdown-Menu>
                            </Dropdown>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        @yield('content')
    </div>
    @endsection
