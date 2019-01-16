<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-14
 * Time: 17:26
 */

namespace app\api\controller;

use app\common\model\User;
use think\Request;

class UserController
{

    public function register(Request $request){
        header("Access-Control-Allow-Origin: *");
        $author = trim($request->param('author'));
        $wechatNumber = trim($request->param('wechat_number'));
        $wechatNumber = mb_substr($wechatNumber, 5);
        $desc = trim($request->param('desc'));
        $headimg = trim($request->param('headimg'));
        $data = [
            'headimg'       => $headimg,
            'nickname'      => $author,
            'introduction'  => $desc,
            'wechat_number' => $wechatNumber,
            'created'       => time(),
            'updated'       => time()
        ];
        $user = User::where('wechat_number', $wechatNumber)
            ->where('nickname', $author)
            ->value('id');
//        return User::getLastSql();
        if(!empty($user))
            return json([
                'code'  => 0,
                'data'  => [
                    'uid'   => $user
                ]
            ]);
        // 添加用户
        $userInfo = User::create($data);
        if ($userInfo->id)
            return json([
                'code'  => 0,
                'data'  => [
                    'uid'   => $userInfo->id
                ]
            ]);
        else
            return json([
                'code'  => -1,
            ]);
    }

    public function test(){
        header("Access-Control-Allow-Origin: *");
        return json(['aa'=>'123']);
    }
}