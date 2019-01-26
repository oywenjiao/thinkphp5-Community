<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

Route::get('think', function () {
    return 'hello,ThinkPHP5!';
});

Route::get('hello/:name', 'index/hello');

Route::get('/');

Route::get('/article', 'article/index');
Route::get('/info', 'article/info');

Route::get('/user', 'user/index');
Route::get('/login', 'user/login');
Route::get('/register', 'user/register');
Route::get('/forget', 'user/forget');

Route::get('/case', 'case/index');

return [

];
