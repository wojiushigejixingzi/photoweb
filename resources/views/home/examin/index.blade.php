@extends('layouts.base')
@section('title', '我的审核')
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
        <i-col span="24" class="demo-tabs-style2" style="padding: 15px 15px">
            <Tabs type="card" @on-click="changeType">
                <Tab-Pane label="待审核" >
                    <Row >
                        <i-table border :columns="columns12" :data="data6" on-row-dblclick="dbclick">
                            <template slot-scope="{ row }" slot="name">
                                <strong>@{{ row.name }}</strong>
                            </template>
                            <template slot-scope="{ row, index }" slot="action">
                                <i-button type="primary" size="small" style="margin-right: 5px" @click="examin(index,1)">通过</i-button>
                                <i-button type="error" size="small" style="margin-right: 5px" @click="examin(index,2)">拒绝</i-button>
                            </template>
                        </i-table>
                    </Row>
                </Tab-Pane>
                <Tab-Pane label="已审核">
                    <Row >
                        {{--<i-col span="4" v-for="item in data6">
                            <Card>
                                <div style="text-align:center">
                                    <img v-bind:src ="item.imgUrl" @click="showThisImage(item)">
                                    <h3>@{{ item.name}}</h3>
                                </div>
                            </Card>
                        </i-col>--}}
                        <i-table border :columns="columns12" :data="data6" on-row-dblclick="dbclick">
                            <template slot-scope="{ row }" slot="name">
                                <strong>@{{ row.name }}</strong>
                            </template>
                            <template slot-scope="{ row, index }" slot="action">
                                <i-button type="primary" size="small" style="margin-right: 5px" @click="examin(index,1)">通过</i-button>
                                <i-button type="error" size="small" style="margin-right: 5px" @click="examin(index,2)">拒绝</i-button>
                            </template>
                        </i-table>
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
                    modal_loading: false,
                    modal2:false,
                    columns12: [
                        {
                            title: '图片名称',
                            slot: 'name'
                        },
                        {
                            title: '上传者',
                            slot: 'name'
                        },
                        {
                            title: '图片',
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
                                            src: params.row.imgUrl, style: 'width: 100px;height: 100px;border-radius: 2px;'
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
                    data6: [
                    ],
                    showImage:false,
                    imageInfo:[],
                    imageList:[],
                    spinShow:false,
                }
            },
            methods:{
                examin:function(index,type)
                {
                    var imageId = this.data6[index].id;
                    axios.post("{{url('home/examin/ajaxExamin')}}",{'imageId':imageId,'examinStatus':type})
                        .then(function (response) {
                            app.spinShow = false;
                            if (response.status == 200) {
                                app.$Message.info("已审核通过");
                                app.remove(index)
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
                remove (index) {
                    debugger
                    this.data6.splice(index, 1);
                },
                showThisImage:function(imageInfo)
                {
                    debugger
                    this.showImage = true;
                    this.imageInfo = imageInfo;
                },
                changeType:function(type){
                    this.spinShow = true;
                    axios.get("{{url('home/examin/ajaxGetImageByType')}}?type="+type,{})
                        .then(function (response) {
                            app.data6 = [];
                            app.spinShow = false;
                            if (response.status == 200) {
                                app.data6 = response.data;
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
                }
            },
            mounted () {
                this.changeType(0)
            }
        });
    </script>
@endsection








