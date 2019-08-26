<?php

namespace App\Http\Controllers\Home;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use APP\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use tests\Mockery\Adapter\Phpunit\EmptyTestCase;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home/index/index');
    }
    /**
     * Show the form for creating a new resource.
     *提交上传图片
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $res = array('status'=>0,'msg'=>'');
        $name = $request->input('name','');
        $type = $request->input('type','1');
        $imageUrl = $request->input('imageUrl','');
        if(empty($name) || empty($type) || empty($imageUrl))
        {
            $res['status'] = 1;
            $res['msg'] = "参数缺失";
            return json_decode($res);
        }
        $insertStatus = DB::table('image')->insert(array('name'=>$name,'type'=>$type,'imgUrl'=>$imageUrl,'userId'=>Auth::id()));
        if(empty($insertStatus))
        {
            $res['status'] = 1;
            $res['msg'] = '图片上传失败';
        }
        return json_encode($res);
    }

    /**
     *ajax获取首页数据
     */
    public function ajaxGetIndexInfo(Request $request)
    {

        $type = $request->input('type');
        $keyword = $request->input('keyword');
        $res = array();
        if(empty($keyword))
        {
            $res = DB::table('image')
                ->select('id','name','imgUrl')
                ->where('type','=',$type)
                ->where('status','=',1)
                ->orderByRaw('id desc')
                ->get();
        }else
        {
            $res = DB::table('image')
                ->select('id','name','imgUrl')
                ->where('type','=',$type)
                ->where('status','=',1)
                ->where('name','like','%'.$keyword.'%')
                ->orderByRaw('id desc')
                ->get();
        }

        //查询是否收藏
      $collection = DB::table('collection')
            ->select('imageid')
            ->where('userId','=',Auth::id())
            ->get();
      $collection = $this->objectToArray($collection);
      $res = $this->objectToArray($res);
      foreach ($collection as $key=>$item)
      {
          $imageIds = array_column($item,'imageid');
      }
      $returnData = array();
      foreach ($res as $item)
      {
          foreach($item as $key=>$list)
            {
                $returnData[$key]['isCollection'] = 'black';
                $returnData[$key]['id'] = $list['id'];
                $returnData[$key]['name'] = $list['name'];
                $returnData[$key]['imgUrl'] = $list['imgUrl'];
                if(in_array($list['id'],$imageIds))
                {
                    $returnData[$key]['isCollection'] = 'red';
                }
            }
      }
        return json_encode($returnData);
    }

    /**
     * 对象转换数组
     *
     * @param $e StdClass对象实例
     * @return array|void
     */
    public function objectToArray($e)
    {
        $e = (array)$e;
        foreach ($e as $k => $v) {
            if (gettype($v) == 'resource') return;
            if (gettype($v) == 'object' || gettype($v) == 'array')
                $e[$k] = (array)$this->objectToArray($v);
        }
        return $e;
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

    /**
     * 上传图片
     * @param Request $request
     */
    public function uploadImage(Request $request)
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
            $savePath = 'images/' . $newFileName;
            // Web 访问路径
            $webPath = '/storage/' . $savePath;
            // 将文件保存到本地 storage/app/public/images 目录下，先判断同名文件是否已经存在，如果存在直接返回
            if (Storage::disk('public')->has($savePath)) {
                return response()->json(['path' => $webPath]);
            }
            // 否则执行保存操作，保存成功将访问路径返回给调用方
            if ($picture->storePubliclyAs('images', $newFileName, ['disk' => 'public'])) {
                return response()->json(['path' => $webPath]);
            }
            abort(500, '文件上传失败');
        } else {
            abort(400, '请选择要上传的文件');
        }
    }

    /**
     * 添加收藏夹
     * @param Request $request
     */
    public function addFolderName(Request $request)
    {
        $res = array('status'=>0,'msg'=>'','data'=>0);
        $folderName = $request->input('folderName','');
        if(empty($folderName))
        {
            $res['status'] = 1;
            $res['msg'] = '文件夹名称不能为空';
            return json_encode($res);
        }
        $insertStatus = DB::table('folder')->insertGetId(['name' => $folderName, 'userId' => Auth::id()]);
        if(empty($insertStatus))
        {
            $res['status'] = 1;
            $res['msg'] = '文件夹创建失败';
            return json_encode($res);
        }
        $res['data'] = $insertStatus;
        return json_encode($res);
    }

    /**
     * @param Request $request
     */
    public function ajaxGetFolderName()
    {
        $res = DB::table('folder')
            ->select('id','name')
            ->where('userId','=',Auth::id())
            ->get();
        return json_encode($res);
    }

    /**
     * 添加收藏
     * @param Request $request
     * @return false|string
     */
    public function collectionImage(Request $request)
    {
        $res = array('status'=>0,'msg'=>'');
        $imageId = $request->input('imageId');
        $folderId = $request->input('folderId');
        $insertStatus = DB::table('collection')->insert(array('imageId'=>$imageId,'folderId'=>$folderId,'userId'=>Auth::id()));
        if(empty($insertStatus))
        {
            $res['status'] = 1;
            $res['msg'] = '收藏失败';
            return json_encode($res);
        }
        return json_encode($res);
    }
    /**
     * 下载图片增加次数
     * @param Request $request
     * @return false|string
     */
    public function ajaxDownload(Request $request)
    {
        $res = array('status'=>0,'msg'=>'');
        $imageId = $request->input('imageId');
       if(empty($imageId))
       {
           return json_encode($res);
       }
       $status = DB::table('image')->where('id','=',$imageId)->increment('downloadnum');
       return json_encode($res);
    }

    /**
     * @param Request $request
     * @return false|string
     */
    public function ajaxShowNum(Request $request)
    {
        $res = array('status'=>0,'msg'=>'');
        $imageId = $request->input('imageId');
        if(empty($imageId))
        {
            return json_encode($res);
        }
        $status = DB::table('image')->where('id','=',$imageId)->increment('shownum');
        return json_encode($res);
    }
}
