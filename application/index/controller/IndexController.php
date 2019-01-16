<?php
namespace app\index\controller;


use app\common\model\Article;

class IndexController
{
    public function index()
    {
        $list = Article::limit(15)
            ->order('id', 'desc')
            ->select();

        return view('', [
            'list'  => $list
        ]);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
