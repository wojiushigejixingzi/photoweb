@extends('layouts.home')
@section('title', '首页')
@section('css')
    <style>
        body{
            background-image:url("/image/bgcontext.png")
        }
        .imageList a{
            color: #060606!important;
        }
        .ivu-row img{
            width: 100%;
            height: 100%;
        }
        .ivu-dropdown{
            float: right;
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

        .ivu-col{
            float: left !important;
        }
        .ivu-card-body{
            padding: 0px !important;
        }
    </style>
@endsection
@section('body')
            <nav id="navbar">
                <a href="javascript:void(0)" v-on:click="ajaxGetIndexInfo(1)">人物类</a>
                <a href="javascript:void(0)" v-on:click="ajaxGetIndexInfo(2)">风景类</a>
                <a href="javascript:void(0)" v-on:click="ajaxGetIndexInfo(3)">元素类</a>
                <a href="javascript:void(0)" v-on:click="ajaxGetIndexInfo(4)">单色系</a>
                <a href="javascript:void(0)" v-on:click="ajaxGetIndexInfo(5)">深色系</a>
                <a href="javascript:void(0)" v-on:click="ajaxGetIndexInfo(6)">浅色系</a>
                @guest
                    <a href="/index.php/login">登陆</a>
                    @if (Route::has('register'))
                        <a href="/index.php/register">注册</a>
                    @endif
                @else
                    <Dropdown trigger="click" class="Tablist" @on-click="user">
                        <a href="javascript:void(0)" class="right">
                            {{ Auth::user()->name }}
                            <Icon type="ios-arrow-down"></Icon>
                        </a>
                        <Dropdown-Menu slot="list">
                            <Dropdown-Item name="0">个人中心</Dropdown-Item>
                            <Dropdown-Item name="1">退出</Dropdown-Item>
                        </Dropdown-Menu>
                    </Dropdown>
                    <a href="{{url('home/leaderboard/index')}}" class="right">排行榜</a>
                    <a href="javascript:void(0)" @click="app.uploadImageModal = true" class="right">上传图片</a>
                @endguest
            </nav>
        </header>
        <Modal v-model="uploadImageModal" :mask-closable="false">
            <Spin size="large" fix v-if="spinShow"></Spin>{{--加载效果--}}
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>上传图片</span>
            </p>
            <div style="text-align:center">
                <i-form ref="formValidate" :model="formValidate" :rules="ruleValidate" :label-width="80">
                    <Row>
                        <i-col span="24">
                            <FormItem label="图片类型" prop="city">
                                <i-select v-model="formValidate.type" placeholder="选择图片类型">
                                    <i-option value="1">人物类</i-option>
                                    <i-option value="2">风景类</i-option>
                                    <i-option value="3">元素类</i-option>
                                    <i-option value="4">单色系</i-option>
                                    <i-option value="5">深色系</i-option>
                                    <i-option value="6">浅色系</i-option>
                                </i-select>
                            </FormItem>
                        </i-col>
                    </Row>
                    <Row>
                        <i-col span="24">
                            <FormItem label="图片名称" prop="name">
                                <i-input v-model="formValidate.name" placeholder="输入图片名称"></i-input>
                            </FormItem>
                        </i-col>
                    </Row>
                    <Row>
                        <i-col span="24">
                            <Card>
                                <div class="demo-upload-list" v-for="item in uploadList">
                                    <template v-if="item.status === 'finished'">
                                        <img :src="item.response.path">
                                        <div class="demo-upload-list-cover">
                                            <Icon type="ios-eye-outline" @click.native="handleView(item.response.path)"></Icon>
                                            <Icon type="ios-trash-outline" @click.native="handleRemove(item)"></Icon>
                                        </div>
                                    </template>
                                    <template v-else>
                                        <Progress v-if="item.showProgress" :percent="item.percentage" hide-info></Progress>
                                    </template>
                                </div>
                                <Upload
                                        ref="upload"
                                        :show-upload-list="false"
                                        :on-success="handleSuccess"
                                        :format="['jpg','jpeg','png']"
                                        :max-size="2048"
                                        :on-format-error="handleFormatError"
                                        :on-exceeded-size="handleMaxSize"
                                        :before-upload="handleBeforeUpload"
                                        multiple
                                        name="avatar"
                                        type="drag"
                                        action="{{url('home/index/uploadImage ')}}"
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
                    </Row>
                </i-form>
            </div>
            <div slot="footer">
                <i-button type="success" :loading="modal_loading" @click="handleSubmit('formValidate')">确定</i-button>
            </div>
        </Modal>
        {{--收藏夹弹层--}}
        <Modal v-model="modal2">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>请选择收藏</span>
            </p>
            <div style="text-align:center">
                <Radio-Group v-model="postCollection.folderId">
                    <Radio v-for="item in folderInfo" :label="item.id">@{{ item.name }}</Radio>
                </Radio-Group>
                <i-button shape="circle" @click="modal3 = true"><Icon type="ios-add-circle-outline" /></i-button>
            </div>
            <div slot="footer">
                <i-button type="error" size="large" long :loading="modal_loading" @click="saveCollection">收藏他</i-button>
            </div>
        </Modal>
        <Modal v-model="modal3" width="360">
            <p slot="header" style="color:#f60;text-align:center">
                <Icon type="ios-information-circle"></Icon>
                <span>添加文件夹</span>
            </p>
            <div style="text-align:center">
                <FormItem label="收藏夹名称">
                    <i-input v-model="folderName" placeholder="请输入收藏夹的名称"></i-input>
                </FormItem>
            </div>
            <div slot="footer">
                <i-button type="error" size="large" long :loading="modal_loading" @click="saveFolderName">保存</i-button>
            </div>
        </Modal>
        {{--收藏夹弹层--}}
        <Row >
            <Spin size="large" fix v-if="bodyShow"></Spin>{{--加载效果--}}
            <Modal v-model="showImage">
                <img :src="imageUrl" v-if="showImage" style="width: 100%">
            </Modal>
            <i-col span="4" v-for="item in pageInfo">
                <Card>
                    <div class="imageList" style="text-align:center">
                        <img v-bind:src ="item.imgUrl" @click="showThisImage(item.imgUrl,item.id)" style="max-height: 200px">
                        <h8>
                            @{{ item.name}}
                            <a href="javascript:void(0)"  @click="collectionModal(item.id)" >
                                <Icon type="md-heart" v-bind:style="{'color':item.isCollection}"/>
                            </a>
                         {{--   <a href="javascript:void(0)"  @click="download(item.id)" >
                                <Icon type="ios-download-outline" />
                            </a>--}}
                            <a :href="item.imgUrl" target="_blank" :download="item.name" @click="download(item.id)" >
                                <Icon type="ios-download-outline" />
                            </a>
                        </h8>
                    </div>
                </Card>
            </i-col>
        </Row>
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
                    serachKeyword:'',//搜索关键字
                    showImage:false,
                    imageUrl:'',
                    //收藏文件夹数据包括图片id及收藏夹id
                    postCollection:{
                        'imageId':'',
                        'folderId':''
                    },
                    //文件夹数据
                    folderInfo:[],
                    folderName:'',//添加文件夹绑定名称
                    //弹层收藏夹单选按钮
                    disabledGroup:'',
                    //收藏夹弹层
                    modal2:false,
                    //添加收藏夹弹层
                    modal3:false,
                    modal_loading: false,
                    //首页数据加载参数
                    ajaxGetIndexInfoParam:{
                        'keyword':'',
                        'pageSize':1,
                        'nowPage':1,
                        'type':1
                    },
                    //modal加载效果
                    spinShow:false,
                    //image加载效果
                    bodyShow:false,
                    //上传图片modal
                    uploadImageModal:false,
                    //登陆注册信息
                    postData:{
                        name: '',
                        password:'',
                        email:'',
                        password_confirmation:''
                    },
                    //弹框我的上传信息
                    modal:{},
                    //页面信息
                    pageInfo:{},
                    //modal验证信息、
                    formValidate: {
                        name: '',
                        type:1,
                        imageUrl:'',
                    },
                    ruleValidate: {
                        name: [
                            { required: true, message: 'The name cannot be empty', trigger: 'blur' }
                        ],
                        mail: [
                            { required: true, message: 'Mailbox cannot be empty', trigger: 'blur' },
                            { type: 'email', message: 'Incorrect email format', trigger: 'blur' }
                        ],
                        city: [
                            { required: true, message: 'Please select the city', trigger: 'change' }
                        ],
                        gender: [
                            { required: true, message: 'Please select gender', trigger: 'change' }
                        ],
                        interest: [
                            { required: true, type: 'array', min: 1, message: 'Choose at least one hobby', trigger: 'change' },
                            { type: 'array', max: 2, message: 'Choose two hobbies at best', trigger: 'change' }
                        ],
                        date: [
                            { required: true, type: 'date', message: 'Please select the date', trigger: 'change' }
                        ],
                        time: [
                            { required: true, type: 'string', message: 'Please select time', trigger: 'change' }
                        ],
                        desc: [
                            { required: true, message: 'Please enter a personal introduction', trigger: 'blur' },
                            { type: 'string', min: 20, message: 'Introduce no less than 20 words', trigger: 'blur' }
                        ]
                    },
                    //上传图片
                    defaultList: [
                    ],
                    headUrl: '',
                    visible: false,
                    uploadList: []
                }
            },
            mounted () {
                this.ajaxGetIndexInfo(1);
                this.getFolderInfo();
            },
            methods:{
                //搜索
                search:function()
                {
                    debugger;
                    this.ajaxGetIndexInfo(this.ajaxGetIndexInfoParam.type);
                },
                //下载
                download:function(id)
                {
                    axios.get("{{url('home/index/ajaxDownload')}}?imageId="+id, {})
                        .then(function (response) {
                            if (response.status == 200) {
                                app.folderInfo = response.data;
                            }
                            else {
                                app.$Message.info(response.data.msg);
                            }
                        })
                        .catch(function (msg) {
                            app.modal3 = false;
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
                //首页图片点击预览
                showThisImage:function(imageUrl,imageId)
                {
                    this.showImage = true;
                    this.imageUrl = imageUrl;

                    axios.get("{{url('home/index/ajaxShowNum')}}?imageId="+imageId, {})
                        .then(function (response) {
                            if (response.status == 200) {
                                app.folderInfo = response.data;
                            }
                            else {
                                app.$Message.info(response.data.msg);
                            }
                        })
                        .catch(function (msg) {
                            app.modal3 = false;
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
                collectionModal:function(imageId)
                {
                    this.postCollection.imageId = imageId;
                    this.modal2 = true;
                },
                //获取文件夹信息
                getFolderInfo:function()
                {
                    axios.get("{{url('home/index/ajaxGetFolderName')}}", {})
                        .then(function (response) {
                            app.modal3 = false;
                            if (response.status == 200) {
                                app.folderInfo = response.data;
                            }
                            else {
                                app.$Message.info(response.data.msg);
                            }
                        })
                        .catch(function (msg) {
                            app.modal3 = false;
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
                //创建收藏夹
                saveFolderName:function()
                {
                    if(this.folderName != "")
                    {
                    axios.post("{{url('home/index/addFolderName')}}", {'folderName':this.folderName})
                        .then(function (response) {
                            app.modal3 = false;
                            if (response.status == 200) {
                                app.$Message.info('文件夹创建成功');
                                app.folderInfo.push({'id':response.data.data,'name':app.folderName})
                            }
                            else {
                                app.$Message.info(response.data.msg);
                            }
                        })
                        .catch(function (msg) {
                            app.modal3 = false;
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
                    else
                    {
                        app.modal3 = false;
                        app.$Message.info("请输入文件夹名称");
                    }
                },
                //收藏
                collection:function(id)
                {
                },
                //保存收藏
                saveCollection:function()
                {
                    this.modal_loading = true;
                    axios.post("{{url('home/index/collectionImage ')}}", app.postCollection)
                        .then(function (response) {
                            if (response.request.status == 200) {
                                app.$Message.info("收藏成功");
                                app.postCollection = {};
                                app.modal_loading = false;
                                app.modal2 = false;
                                app.ajaxGetIndexInfo(app.ajaxGetIndexInfoParam.type);

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
                //首页获取图片列表
                ajaxGetIndexInfo:function(type)
                {
                    debugger
                    this.bodyShow = true;

                    this.ajaxGetIndexInfoParam.type = type;
                    axios.get("{{url('home/index/ajaxGetIndexInfo')}}?type="+type+"&keyword="+this.ajaxGetIndexInfoParam.keyword, this.ajaxGetIndexInfoParam)
                        .then(function (response) {
                            app.bodyShow = false;
                            if (response.status == 200) {
                                app.pageInfo = response.data;
                                app.uploadImageModal = false;
                            }
                            else {
                                app.$Message.info(response.data.msg);
                            }
                        })
                        .catch(function (msg) {
                            app.bodyShow = false;
                            this.spinShow = false;
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
                //modal提交事件
                handleSubmit (name) {
                    this.spinShow = true;
                    this.formValidate.imageUrl = this.uploadList[0].response.path;
                    axios.post("{{url('home/index/create ')}}", this.formValidate)
                        .then(function (response) {
                            if (response.data.status == 0) {
                                app.$Message.info('图片上传成功,等待管理员审核！');
                                app.spinShow = false;
                                app.uploadImageModal = false;
                                app.formValidate.name = app.formValidate.imageUrl = '';
                                app.uploadList = [];
                                //添加成功后加载所在菜单栏的图片信息
                                app.ajaxGetIndexInfo(0);
                            }
                            else {
                                this.spinShow = false;
                                app.$Message.info(response.data.msg);
                            }
                        })
                        .catch(function (msg) {
                            this.spinShow = false;
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
                    this.$refs[name].validate((valid) => {
                        /*debugger
                        if (valid) {
                            this.$Message.success('Success!');
                        } else {
                            this.$Message.error('Fail!');
                        }*/

                    })
                },
                //重置
                handleReset (name) {
                    this.$refs[name].resetFields();
                },
                //退出
                user:function(jumpStatus){
                    if(jumpStatus == 0)
                    {
                        //跳转到个人中心
                        this.userCenter();
                    }
                    else
                    {
                        //退出登陆
                        this.logout();
                    }
                },
                //个人中心
                userCenter:function () {
                    window.location.href = "{{url('home/user/index ')}}";
                    return false;
                },
                logout:function () {
                    axios.post("{{ route('logout') }}", this.postData)
                        .then(function (response) {
                            if (response.request.status == 200) {
                                app.$Message.info('退出成功');
                                window.location.href = '/index.php/login';
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
                //上传图片
                handleView (headUrl) {

                    this.headUrl = headUrl;
                    this.visible = true;
                },
                //删除
                handleRemove (file) {
                    debugger
                    const fileList = this.$refs.upload.fileList;
                    this.$refs.upload.fileList.splice(fileList.indexOf(file), 1);
                    app.uploadList = [];
                },
                //上传成功
                handleSuccess (res, file) {
                    this.uploadList.push(file)
                    /*this.$refs.upload.fileList[0].url =res.path;
                    this.$refs.upload.fileList.splice(1,1);
                    app.$Message.info('图片已上 传');*/
                },
                //上传文件格式错误
                handleFormatError (file) {
                    this.$Notice.warning({
                        title: '文件格式错误',
                        desc: 'File format of ' + file.name + ' is incorrect, please select jpg or png.'
                    });
                },
                //上传文件最大超出限制
                handleMaxSize (file) {
                    this.$Notice.warning({
                        title: '超出文件大小限制',
                        desc: '文件  ' + file.name + '大于2M'
                    });
                },
                //上传图片前的检查
                handleBeforeUpload () {
                    const check = this.uploadList.length < 1;
                    if (!check) {
                        this.$Notice.warning({
                            title: '每次只能上传一张图片哦'
                        });
                    }
                    return check;
                }

            }
        });
    </script>
@endsection


