<?php
/**
 * Created by PhpStorm.
 * User: zhaoShuang
 * Date: 2019/4/21
 * Time: 21:21
 */

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use APP\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public  function index()
    {
        return view('home/user/index');
    }

    /**
     * 个人中心
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userinfo()
    {
        $userInfo = Auth::user()->avatar;
        return view('home/user/userinfo')->with('avatar', Auth::user()->avatar);;
    }

    /**
     * 修改头像
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAvatar(Request $request)
    {
        if ($request->hasFile('avatar')) {
            $picture = $request->file('avatar');
            if (!$picture->isValid()) {
                abort(400, '无效的上传文件');
            }
            // 文件扩展名
            $extension = $picture->getClientOriginalExtension();
            // 文件名
            $fileName = $picture->getClientOriginalName();
            // 生成新的统一格式的文件名
            $newFileName = md5($fileName . time() . mt_rand(1, 10000)) . '.' . $extension;
            // 图片保存路径
            $savePath = 'head/' . $newFileName;
            // Web 访问路径
            $webPath = '/storage/' . $savePath;
            // 将文件保存到本地 storage/app/public/images 目录下，先判断同名文件是否已经存在，如果存在直接返回
            if (Storage::disk('public')->has($savePath)) {
                return response()->json(['path' => $webPath]);
            }
            // 否则执行保存操作，保存成功将访问路径返回给调用方
            if ($picture->storePubliclyAs('head', $newFileName, ['disk' => 'public'])) {
                //上传成功后更新数据表字段值
                DB::table('users')->where('id', Auth::id())->update(['avatar' => $webPath]);
                $user = DB::table('users')->where('id',Auth::id() )->first();
                return response()->json(['path' => $webPath]);
            }
            abort(500, '文件上传失败');
        } else {
            abort(400, '请选择要上传的文件');
        }
    }

    /**
     *我的上传页面
     */
    public function myUpload()
    {
        return view('home/user/myUpload');
    }


    /**
     *Ajax获取个人中心我的上传信息
     */
    public function ajaxGetMyUploadByType(Request $request)
    {
        $status = $request->input('status');
        $res = array();
        $res = DB::table('image')
            ->select('id','name','imgUrl')
            ->where('status','=',$status)
            ->where('userid','=',Auth::id())
            ->orderByRaw('id DESC')
            ->get();
        return json_encode($res);
    }

    public function myCollection()
    {
        return view('home/user/myCollection');
    }

    /*Ajax获取收藏夹列表*/
    public function ajaxGetMyCollection()
    {
        return json_encode(array(1,2,33));
    }

    /**
     * 获取我的收藏
     * @param Request $request
     */
    public function ajaxMyFolder()
    {
        $res = DB::table('folder')
            ->select('name','id')
            ->where('userId','=',Auth::id())
            ->orderByRaw('id - id DESC')
            ->get();
        return json_encode($res);
    }

    public function ajaxCollection(Request $request)
    {
        $res = array('status'=>0,'msg'=>'','data'=>array());
        $folderid = $request->input('folderId');
        if(empty($folderid))
        {
            $res['status'] = 1;
            $res['msg'] = '参数缺失';
            return json_encode($res);
        }
        $res['data'] = DB::table('image')
            ->join('collection', 'collection.imageId', '=', 'image.id')
            ->select('image.id', 'image.imgUrl')
            ->where('collection.folderId','=',$folderid)
            ->get();
        return json_encode($res);
    }

    /*删除图片*/
    public function ajaxDeleteImage(Request $request)
    {
        $res = array('status'=>0,'msg'=>'');
        $imageId = $request->input('iamgeId');
        if(empty($imageId))
        {
            $res['status'] = 1;
            $res['msg'] = "参数获取失败，请刷新页面重试";
            return json_encode($res);
        }
       /* $userId = DB::table('image')
            ->select('userid')
            ->where('id','=',$imageId)
            ->get();
        if($userId != Auth::id())
        {
            $res['status'] = 1;
            $res['msg'] = "您不能删除别人的图片哦";
        }*/
        $deleteStatus = DB::table('image')
            ->where('id','=',$imageId)
            ->delete();
        if(empty($deleteStatus))
        {
            $res['status'] = 1;
            $res['msg'] = '删除失败';
            return json_encode($res);
        }
        return json_encode($res);
    }


}