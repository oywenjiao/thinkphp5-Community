<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-09
 * Time: 15:20
 */

namespace app\common\model;


use think\Model;

class Article extends Model
{
    public function author(){
        return $this->belongsTo('User', 'author_id');
    }

    public function content(){
        return $this->hasOne('ArticleContent');
    }
}