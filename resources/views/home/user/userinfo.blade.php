@extends('layouts.base')
@section('title', '个人中心')
@section('css')
    <style>
        #app{
            padding: 10px;
        }
        .demo-upload-list{
            display: inline-block;
            width: 60px;
            height: 60px;
            text-align: center;
            line-height: 60px;
            border: 1px solid transparent;
            border-radius: 4px;
            overflow: hidden;
            background: #fff;
            position: relative;
            box-shadow: 0 1px 1px rgba(0,0,0,.2);
            margin-right: 4px;
        }
        .demo-upload-list img{
            width: 100%;
            height: 100%;
        }
        .demo-upload-list-cover{
            display: none;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0,0,0,.6);
        }
        .demo-upload-list:hover .demo-upload-list-cover{
            display: block;
        }
        .demo-upload-list-cover i{
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            margin: 0 2px;
        }
    </style>
@endsection
@section('body')
<div id="app">
    <Row>
        <i-col span="11">
        <Card>
            <p slot="title">头像</p>
            <div class="demo-upload-list" v-for="item in uploadList">
                <template v-if="item.status === 'finished'">
                    <img :src="item.url">
                    <div class="demo-upload-list-cover">
                        <Icon type="ios-eye-outline" @click.native="handleView(item.url)"></Icon>
                        {{--<Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>--}}
                    </div>
                </template>
                <template v-else>
                    <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                </template>
            </div>
            <Upload
                    ref="upload"
                    :show-upload-list="false"
                    :default-file-list="defaultList"
                    :on-success="handleSuccess"
                    :format="['jpg','jpeg','png']"
                    :max-size="2048"
                    :on-format-error="handleFormatError"
                    :on-exceeded-size="handleMaxSize"
                    :before-upload="handleBeforeUpload"
                    multiple
                    name="avatar"
                    type="drag"
                    action="updateAvatar"
                    headers=$('meta[name="csrf-token"]').attr('content')
                    style="display: inline-block;width:58px;">
                <div style="width: 58px;height:58px;line-height: 58px;">
                    <Icon type="ios-camera" size="20"></Icon>
                </div>
            </Upload>
            <Modal title="头像" v-model="visible">
                <img :src="headUrl" v-if="visible" style="width: 100%">
            </Modal>
        </Card>
        </i-col>
        <i-col span="11" offset="1">
        <Card dis-hover>
            <p slot="title">个人信息</p>
            <p>用户名：{{ Auth::user()->name }}</p>
            <p>邮箱：{{ Auth::user()->email}}</p>
        </Card>
        </i-col>
    </Row>
</div>
@endsection
@section('js')
<script>
    var app = new Vue({
        el:"#app ",
        data(){
            return {
                test:"",
                defaultList: [
                    {
                        'name': 'avatar',
                        {{--'url': "{{URL::asset(Auth::user()->avatar)}} == ''" ? "/image/heard.jpg ": "{{URL::asset(Auth::user()->avatar)}}"--}}
                        'url': "{{URL::asset(Auth::user()->avatar)}}"
                    },
                ],
                headUrl: '',
                visible: false,
                uploadList: []
            }
        },
        methods:{
            handleView (headUrl) {
                debugger;
                this.headUrl = headUrl;
                this.visible = true;
            },
            handleRemove (file) {
                /*const fileList = this.$refs.upload.fileList;
                this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);*/
            },
            handleSuccess (res, file) {
                this.$refs.upload.fileList[0].url =res.path;
                this.$refs.upload.fileList.splice(1,1);
                app.$Message.info('头像更换成功');
            },
            handleFormatError (file) {
                debugger;
                this.$Notice.warning({
                    title: '文件格式错误',
                    desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                });
            },
            handleMaxSize (file) {
                debugger;
                this.$Notice.warning({
                    title: '超出文件大小限制',
                    desc: '文件  ' + file.name + '大于2M'
                });
            },
            handleBeforeUpload () {
                debugger;
                const check = this.uploadList.length < 2;
                if (!check) {
                    this.$Notice.warning({
                        title: '只能上传一张图片'
                    });
                }
                return check;
            }
        },
        mounted () {
            this.uploadList = this.$refs.upload.fileList;
        }
    });
</script>
@endsection






