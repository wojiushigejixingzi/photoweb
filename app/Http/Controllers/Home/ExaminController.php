<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ExaminController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
        /*验证审核权限*/
        $this->middleware('adminAuth');
        /*验证审核*/
    }
    /**
     * Display a listing of the resource.
     *审核首页
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return view('home/examin/index');
    }


    public function ajaxGetImageByType(Request $request)
    {
        $type = $request->input('type');
        $res = array();
        $where = $type == 0 ? ">=":"=";
        $param = $type == 0 ? 0 : Auth::id();
        $res = DB::table('image')
            ->join('users', 'image.userid', '=', 'users.id')
            ->select('image.id','image.name','image.imgUrl','image.type','users.name as userName')
            ->where('status','=',$type)
            ->where('reviewerUserId',$where,$param)
            ->orderByRaw('id DESC')
            ->get();
        return json_encode($res);
    }


    public function ajaxExamin(Request $request)
    {
        $res = array('status'=>0,'msg'=>'');
        $imageId = $request->input('imageId');
        $examinStatus = $request->input('examinStatus');
        $examinRes = DB::table('image')
            ->where('id', $imageId)
            ->update(['status' => $examinStatus,'reviewerUserId' => Auth::id()]);
        if(empty($examinRes))
        {
            $res['status'] = 1;
            $res['msg'] = "审核失败";
            return json_encode($res);
        }
        return json_encode($res);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }



    /*用户管理*/
    public function userManagement()
    {
        return view('home/examin/usermanagement');
    }

    /*获取用户信息*/
    public function ajaxGetUserInfo()
    {
        $res = array('status'=>0,'msg'=>'');
        $res['data'] = DB::table('users')
            ->select('id','name','email','avatar')
            ->where('role','!=','admin')
            ->get();
        return json_encode($res);
    }

    /*删除用户*/
    public function ajaxDeleteUser(Request $request)
    {
        $res = array('status'=>0,'msg'=>'');
        $userId = $request->input('userId');
        if(empty($userId))
        {
            $res['status'] = 1;
            $res['msg'] = '未获取到用户id，请刷新页面后重新尝试！';
            return json_encode($res);
        }
        $status = DB::table('users')->where('id', '=', $userId)->delete();
        if(empty($status))
        {
            $res['status'] = 1;
            $res['msg'] = '删除失败';
            return json_encode($res);
        }
        return json_encode($res);
    }
}
