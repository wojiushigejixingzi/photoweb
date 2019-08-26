<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title')</title>

    <script src="//vuejs.org/js/vue.min.js"></script>
    <script src="//unpkg.com/iview/dist/iview.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="//unpkg.com/iview/dist/styles/iview.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/information.css') }}" type="text/css" rel="stylesheet" />
    {{--为了搜索新加的--}}
    <style>
        hgroup div{
            float: right!important;
        }
    </style>
    {{--为了搜索新加的--}}
    @yield('css')
</head>
<body style="padding: 20px">
<div id="app">
    <header>
        <hgroup class="title">
            <a href="index.php" class="big">二次元图片网站</a>
            <br>这里可以找到你所需要的ACG图片素材哟1
            <i-input v-model="ajaxGetIndexInfoParam.keyword"  placeholder="请输入图片名称" style="width: auto">
                <Icon type="ios-search" @click.native="search" slot="prefix"/>
            </i-input>
        </hgroup>
        <img src="/image/header.jpg" width="100%" height="360" alt="xiaozhan">
@yield('body')
@yield('js')
</body>
</html>