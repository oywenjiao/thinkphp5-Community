<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-10
 * Time: 15:32
 */

namespace app\index\controller;


use app\common\model\User;

class UserController
{

    public function index($uid)
    {
        $info = User::get($uid);
        return view('', ['info'=>$info]);
    }
}