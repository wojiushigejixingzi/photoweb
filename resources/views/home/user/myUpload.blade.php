@extends('layouts.base')
@section('title', '我的上传')
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
        <i-col span="24" class="demo-tabs-style2" style="padding: 15px 15px">
            <Tabs type="card" @on-click="changeType">
                <Tab-Pane label="审核中">
                    <i-table border :columns="columns" :data="data" :loading="loading" >
                        <template slot-scope="{ row }" slot="name">
                            <strong>@{{ row.name }}</strong>
                        </template>
                        <template slot-scope="{ row, index }" slot="action">
                            <i-button type="error" size="small" style="margin-right: 5px" @click="deleteImage(index)">删除</i-button>
                        </template>
                    </i-table>
                </Tab-Pane>
                <Tab-Pane label="已审核">
                    <i-table border :columns="columns" :data="data" :loading="loading" >
                        <template slot-scope="{ row }" slot="name">
                            <strong>@{{ row.name }}</strong>
                        </template>
                        <template slot-scope="{ row, index }" slot="action">
                            <i-button type="error" size="small" style="margin-right: 5px" @click="deleteImage(index)">删除</i-button>
                        </template>
                    </i-table>
                </Tab-Pane>
                <Tab-Pane label="审核未通过">
                    <i-table border :columns="columns" :data="data" :loading="loading" >
                        <template slot-scope="{ row }" slot="name">
                            <strong>@{{ row.name }}</strong>
                        </template>
                        <template slot-scope="{ row, index }" slot="action">
                            <i-button type="error" size="small" style="margin-right: 5px" @click="deleteImage(index)">删除</i-button>
                        </template>
                    </i-table>
                </Tab-Pane>
            </Tabs>
        </i-col>
</div>
@endsection
@section('js')
<script>
    var app = new Vue({
        el:"#app ",
        data(){
            return {
                tabIndex:0,
                data:[],
                loading:false,
                columns: [
                    {
                        title: '头像',
                        key: 'imgUrl',
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
                                        src: params.row.imgUrl!= "" ?params.row.imgUrl : "/image/heard.jpg", style: 'width: 100px;height: 100px;border-radius: 2px;'
                                    },
                                    style: {
                                    },
                                }),
                            ]);
                        }
                    },
                    {
                        title: '描述',
                        key: 'name'
                    },

                    {
                        title: '操作',
                        slot: 'action',
                        width: 150,
                        align: 'center'
                    }
                ],
            }
        },
        methods:{
            changeType:function(name){
                this.tabIndex = name;
                this.loading = true;
                var status = name == 1 ? 0 : 1;
                axios.get("{{url('home/user/ajaxGetMyUploadByType')}}?status="+name,{})
                    .then(function (response) {
                        app.data = [];
                        app.loading = false;
                        if (response.status == 200) {
                            app.data  =  response.data;
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
            },
            deleteImage:function (index) {
                var iamgeId = this.data[index].id;
                axios.post("{{url('home/user/ajaxDeleteImage')}}",{'iamgeId':iamgeId})
                    .then(function (response) {
                        app.$Message.info("删除成功");
                        if (response.status == 200) {
                            app.changeType(app.tabIndex);
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
            this.changeType(0)
        }
    });
</script>
@endsection








