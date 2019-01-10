<?php
namespace app\index\controller;

use app\api\model\ArticleContent;

class Index
{
    public function index()
    {
        $content = ArticleContent::get(1);
        return view()->assign([
            'content'   => $content
        ]);
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
