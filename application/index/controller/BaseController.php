<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-25
 * Time: 10:42
 */

namespace app\index\controller;


use think\App;
use think\Controller;

class BaseController extends Controller
{

    public function __construct(App $app = null){
        parent::__construct($app);
        $this->assign([
            'module'        => request()->module(),
            'controller'    => request()->controller(),
            'action'        => request()->action()
        ]);
    }
}