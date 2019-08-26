<?php
/**
 * Created by PhpStorm.
 * User: zhaoShuang
 * Date: 2019/4/12
 * Time: 0:07
 */

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class UserController extends  Controller
{
    public  function  index()
    {
        $id = DB::table('user')->insertGetId(
            ['realname' => 'zhaoShuang', 'username' => 'zhaoShuang','password'=>'12345','phonenumber'=>'17638165937']
        );
        var_dump($id);
    }
    public  function  test1()
    {
        return 'test1';
    }
    public function store()
    {
        return "this is store";
    }
}