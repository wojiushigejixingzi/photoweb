@extends('layouts.home')
@section('title', "个人中心")
@section('css')
    <style>
        html {
            height: 100%;
        }
        body{
            background-image:url("/image/bgcontext.png")
        }
        iframe {
            position: relative;
            min-height: 800px;
            box-sizing: border-box;
            padding-bottom: 80px; /* .footer 的高度，为 footer 占位 */
        }

    </style>
@endsection
@section('body')
        <nav id="navbar">
            <a href="{{url('home/index/index ')}}">返回</a>
        </nav>
    </header>
    <div id="info">
        <div id="aside">
            <dl>
                <i-menu :theme="theme3" active-name="{{url('home/user/userinfo ')}}" @on-select="testSelect">
                    <Menu-Group title="个人中心" >
                        <Menu-Item name="{{url('home/user/userinfo ')}}">
                            个人信息
                        </Menu-Item>
                        <Menu-Item name="{{url('home/user/myCollection ')}}">
                            我的收藏
                        </Menu-Item>
                        <Menu-Item name="{{url('home/user/myUpload ')}} ">
                            我的上传
                        </Menu-Item>
                        <Menu-Item v-show="isShowMenu == 'admin'" name="{{url('home/examin/index ')}} ">
                            我的审核
                        </Menu-Item>
                        <Menu-Item v-show="isShowMenu == 'admin'" name="{{url('home/examin/userManagement')}} ">
                            用户管理
                        </Menu-Item>
                    </Menu-Group>
                </i-menu>
            </dl>
        </div>
        <div id="content">
            <iframe id="iframe"  frameborder="0" scrolling="auto" height="100%" class="ifrem_info" :src=iframeUrl width="100%" frameborder="0" name="info_list" scrolling="no"></iframe>
        </div>
    </div>
    <footer>
        <div class="footer">
            <center>学号：201577E0149 姓名：赵爽 Copyright © 2019-2019<a href="index.html">二次元图片网站</a> All Rights Reserved</center>
        </div>
    </footer>
</div>
@endsection
@section('js')
<script>
    var app = new Vue({
        el:"#app",
        data(){
            return {
                theme3: 'dark',
                iframeUrl:"/index.php/home/user/userinfo",
                isShowMenu:"{{ Auth::user()->role }}"
            }
        },
        mounted: function () {
        },
        methods:{
            testSelect:function(item){
                this.iframeUrl = item;

            },
        }
    });
</script>
@endsection