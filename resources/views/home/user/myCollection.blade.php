@extends('layouts.base')
@section('title', '我的收藏')
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
    <Spin size="large" fix v-if="spinShow"></Spin>
    <Modal v-model="showImage">
        <img :src="imageUrl" v-if="showImage" style="width: 100%">
    </Modal>
    <i-col span="24" class="demo-tabs-style2" style="padding: 15px 15px">
        <Tabs type="card" @on-click="ajaxGetCollection">
            <Tab-Pane v-for="item in tabsInfo" :label="item.name" :name="item.id">
                <Row >
                    <i-col span="4" v-for="item in collectionData">
                        <Card>
                            <div style="text-align:center">
                                <img v-bind:src ="item.imgUrl" @click="showThisImage(item.imgUrl)">
                                <h3>@{{ item.name}}</h3>
                            </div>
                        </Card>
                    </i-col>
                </Row>
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
                showImage:false,
                imageUrl:'',
                tabs: 2,
                tabsInfo:[],
                collectionData:[],
                spinShow:false,
            }
        },
        methods:{
            //首页图片点击预览
            showThisImage:function(imageUrl)
            {
                this.showImage = true;
                this.imageUrl = imageUrl;
            },
            ajaxMyCollection:function () {
                this.spinShow = true;
                axios.get("{{url('home/user/ajaxMyFolder')}}", {})
                    .then(function (response) {
                        if (response.status == 200) {
                            app.tabsInfo = response.data;
                            //去加载收藏夹下的内容
                            app.ajaxGetCollection(response.data[0].id);
                            app.spinShow = false;
                        }
                        else {
                            app.$Message.info(response.msg);
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
            //加载收藏夹内的数据
            ajaxGetCollection:function (id) {
                this.spinShow = true;
                axios.get("{{url('home/user/ajaxCollection')}}?folderId="+id, {})
                    .then(function (response) {
                        app.spinShow = false;
                        if (response.data.status == 0) {
                            app.collectionData = response.data.data;
                        }
                        else {
                            app.$Message.info(response.data.msg);
                        }
                    })
                    .catch(function (msg) {
                        if (msg.request.status == 422) {
                            app.spinShow = false;
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
            this.ajaxMyCollection();
        }
    });
</script>
@endsection






