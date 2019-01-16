<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-10
 * Time: 14:58
 */

namespace app\index\controller;


use app\common\model\Article;

class ArticleController
{
    public function index(){
        $list = Article::order('id', 'desc')
            ->paginate(15, false, [
                'type'  => 'org\Page',
                'var_page' => 'page',
            ]);
        $page = $list->render();
        return view('', [
            'list'  => $list,
            'page'  => $page
        ]);
    }

    public function info($id){
        $info = Article::get($id);
        return view('', ['info'=>$info]);
    }
}