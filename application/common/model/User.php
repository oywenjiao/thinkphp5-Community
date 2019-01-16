<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-15
 * Time: 10:10
 */

namespace app\common\model;


use think\Model;

class User extends Model
{

    public function article(){
        return $this->hasMany('Article', 'author_id');
    }
}