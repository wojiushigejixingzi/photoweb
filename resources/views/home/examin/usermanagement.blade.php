@extends('layouts.base')
@section('title', '用户管理')
@section('css')
    <style>
        .ivu-row img{
            width: 100%;
            height: 100%;
            padding: 10px;
        }
        .ivu-dropdown{
            /*float: right;*/
        }
        .demo-tabs-style1 > .ivu-tabs-card > .ivu-tabs-content {
            height: 120px;
            margin-top: -16px;
        }

        .demo-tabs-style1 > .ivu-tabs-card > .ivu-tabs-content > .ivu-tabs-tabpane {
            background: #fff;
            padding: 16px;
        }

        .demo-tabs-style1 > .ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-tab {
            border-color: transparent;
        }

        .demo-tabs-style1 > .ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-tab-active {
            border-color: #fff;
        }
        .demo-tabs-style2 > .ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-tab{
            border-radius: 0;
            background: #fff;
        }
        .demo-tabs-style2 > .ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-tab-active{
            border-top: 1px solid #3399ff;
        }
        .demo-tabs-style2 > .ivu-tabs.ivu-tabs-card > .ivu-tabs-bar .ivu-tabs-tab-active:before{
            content: '';
            display: block;
            width: 100%;
            height: 1px;
            background: #3399ff;
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>

@endsection
@section('body')
    <div id="app">

        <i-table border :columns="columns" :data="data" :loading="loading" >
            <template slot-scope="{ row }" slot="name">
                <strong>@{{ row.name }}</strong>
            </template>
            <template slot-scope="{ row, index }" slot="action">
                <i-button type="error" size="small" style="margin-right: 5px" @click="deleteUser(index)">删除</i-button>
            </template>
        </i-table>
    </div>
@endsection
@section('js')
    <script>
        var app = new Vue({
            el:"#app ",
            data(){
                return {
                    loading:false,
                    columns: [
                        {
                            title: '用户名',
                            key: 'name'
                        },
                        {
                            title: '邮箱',
                            key: 'email'
                        },
                        {
                            title: '头像',
                            key: 'avatar',
                            render: (h, params) => {
                                return h('div', {
                                    attrs: {
                                        style: 'width: 100px;height: 100px;'
                                    },
                                }, [
                                    h('img', {
                                        props: {
                                            type: 'primary',
                                        },
                                        attrs: {
                                            src: params.row.avatar!= "" ?params.row.avatar : "/image/heard.jpg", style: 'width: 100px;height: 100px;border-radius: 2px;'
                                        },
                                        style: {
                                        },
                                    }),
                                ]);
                            }
                        },
                        {
                            title: '操作',
                            slot: 'action',
                            width: 150,
                            align: 'center'
                        }
                    ],
                    data: [
                    ],
                }
            },
            methods:{
                deleteUser:function(index)
                {
                    this.loading = true;
                    var userId = this.data[index].id;
                    axios.post("{{url('home/examin/ajaxDeleteUser')}}",{'userId':userId})
                        .then(function (response) {
                            app.loading = false;
                            if (response.status == 200) {
                                if(response.data.status == 0)
                                {
                                    app.$Message.info("删除成功");
                                    app.ajaxGetUserInfo(0);
                                }
                                else
                                {
                                    app.$Message.info(response.data.msg);
                                }
                            }
                            else {
                                app.$Message.info(response.data.msg);
                            }
                        })
                        .catch(function (msg) {
                            app.spinShow = false;
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
                ajaxGetUserInfo:function(){
                    this.loading = true;
                    axios.get("{{url('home/examin/ajaxGetUserInfo')}}",{})
                        .then(function (response) {
                            app.loading = false;
                            if (response.status == 200) {
                                app.data = response.data.data;
                            }
                            else {
                                app.$Message.info(response.data.msg);
                            }
                        })
                        .catch(function (msg) {
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
            },
            mounted () {
                this.ajaxGetUserInfo()
            }
        });
    </script>
@endsection








