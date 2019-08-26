@extends('layouts.app')
@section('title', '注册')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">注册</div>

                <div class="card-body">
                    {{--<form method="POST" action="{{ route('register') }}">--}}
                        @csrf
                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">姓名</label>

                            <div class="col-md-6">
                                <input id="name" v-model="postData.name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))navbar-laravel
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">邮箱</label>

                            <div class="col-md-6">
                                <input id="email" type="email" v-model="postData.email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">密码</label>

                            <div class="col-md-6">
                                <input id="password" type="password" v-model="postData.password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">确认密码</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" v-model="postData.password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" @click="register" class="btn btn-primary">
                                    注册
                                </button>
                            </div>
                        </div>
                    {{--</form>--}}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
    @parent
    <script>
        var app = new Vue({
            el:"#app",
            data(){
                return {
                    postData:{
                        name: '',
                        password:'',
                        email:'',
                        password_confirmation:''
                    },
                }
            },
            methods:{
                //退出
                logout:function(logoutUrl){

                    debugger;
                    axios.post(logoutUrl, this.postData)
                        .then(function (response) {
                            debugger;
                            if (response.request.status == 200) {
                                app.$Message.info('退出成功');
                                window.location.href = 'login';
                            }
                        })
                        .catch(function (msg) {
                            debugger;
                            if (msg.request.status == 422) {
                                var json = JSON.parse(msg.request.responseText);
                                json = json.errors;
                                for (var item in json) {
                                    for (var i = 0; i < json[item].length; i++) {
                                        alert(json[item][i]);
                                        return; //遇到验证错误，就退出
                                    }
                                }
                            }
                        });

                },
                //注册
                register:function(){
                    debugger;
                    axios.post("{{ route('register') }}", this.postData)
                        .then(function (response) {
                            debugger;
                            if (response.status == 200) {
                                app.$Message.info('注册成功');
                                window.location.href = "/index.php";
                            }
                        })
                        .catch(function (msg) {
                            debugger;
                            if (msg.request.status == 422) {
                                var json = JSON.parse(msg.request.responseText);
                                json = json.errors;
                                for (var item in json) {
                                    for (var i = 0; i < json[item].length; i++) {
                                        alert(json[item][i]);
                                        return; //遇到验证错误，就退出
                                    }
                                }
                            }
                        });
                }
            }
        });
    </script>

@endsection
