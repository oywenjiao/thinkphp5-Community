<?php
namespace app\index\controller;

class Index
{
    public function index()
    {
        die('hello world');
    }

    public function hello($name = 'ThinkPHP5')
    {
        return 'hello,' . $name;
    }
}
