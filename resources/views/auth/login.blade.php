@extends('layouts.app')
@section('title', '登陆')
@section('content')
    <main class="py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">登陆</div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">邮箱</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

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
                                        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-6 offset-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                            <label class="form-check-label" for="remember">
                                                记住我
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-8 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            登陆
                                        </button>

                                        @if (Route::has('password.request'))
                                            <a class="btn btn-link" href="{{ route('password.request') }}">
                                                忘记密码？
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    </div>
@endsection

@section('script')
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
                            if (msg.request.status == 200) {
                                this.$Message.info('注册成功');
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

