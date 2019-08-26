@extends('layouts.home')
@section('title', '首页')
@section('css')
    <style>
        html {
            height: 100%;
        }
        body{
            background-image:url("/image/bgcontext.png")
        }
        .ivu-menu{
            width: 100%;
            display: table;
            margin:0 auto;
            text-align: center;
            padding:0;
        }
        .content{
            text-align: center;
            background-color: #fff;
            border-color: #fff;
            color: rgba(0,0,0,.87);
        }
        .content img{
            width: 80%;
        }
        .download{
            padding: 4px;
            float: right;
            background-color: #fff;
            border-color: #fff;
            color: rgba(0,0,0,.87);
        }
    </style>
@endsection
@section('body')
    <i-menu mode="horizontal" theme="light" active-name="1" @on-select="changeType">
        <Menu-Item name="1">
            下载排行
            <Icon type="ios-cloud-download" />
        </Menu-Item>
        <Menu-Item name="2">
            浏览排行
            <Icon type="ios-eye" />
        </Menu-Item>
    </i-menu>
    <div class="content">

        <i-table :columns="columns6" :data="data5" :loading="bodyShow"></i-table>
    </div>

@endsection
@section('js')
<script>
var app = new Vue({
el:"#app",
data(){
    return {
        bodyShow:false,
        typeName:'下载',
        imageInfo:[],
        iframeUrl:"/index.php/home/user/userinfo",
        imageHeight:'',
        numStyle:'',
        columns6: [
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
                title: "下载次数",
                key: 'downloadnum'
            },

        ],
        data5: [
        ],
    }
},
mounted: function () {
    //计算高度
    this.Calculation();
    //获取排行榜数据
    this.changeType(1);
},
methods:{
    //获取排行榜数据
    changeType:function(type){
        debugger
        this.bodyShow = true;
        this.columns6[1].title = type == 1 ? "下载次数":"浏览次数"
        this.columns6[1].key = type == 1 ? "downloadnum" : "shownum";
            axios.get("{{url('home/leaderboard/ajaxGetData')}}?type="+type, {})
            .then(function (response) {
                app.bodyShow = false;
                if (response.status == 200) {
                    app.data5 = response.data.data;
                }
                else {
                    app.$Message.info(response.data.msg);
                }
            })
            .catch(function (msg) {
                app.bodyShow = false;
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
    testSelect:function(item){
        this.iframeUrl = item;
    },
    //计算图片高度
    Calculation:function () {
        var o = document.getElementsByClassName("imageList");
        // this.numStyle = (o[0].clientHeight||o.offsetHeight)/3;
        this.numStyle = 55;
    }
}
});
</script>
@endsection