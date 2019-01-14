<?php
/**
 * Created by PhpStorm.
 * User: OuyangWenjiao
 * Date: 2019/1/11
 * Time: 15:32
 */

namespace app\admin\admin;


use think\Controller;

class CommonAdmin extends Controller
{

    public function index(){
        $this->redirect(url('index/index', '', true, true));
        return view();
    }
}