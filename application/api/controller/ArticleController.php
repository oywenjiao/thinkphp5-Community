<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-05
 * Time: 14:57
 */

namespace app\api\controller;

use app\common\model\Article;
use app\common\model\ArticleContent;
use org\Curl;
use QL\QueryList;
use think\Request;


class ArticleController
{

    // 文章入库
    public function createArticle(Request $request){
        header("Access-Control-Allow-Origin: *");
        $content_url = $request->param('content_url');
        $url = 'https://mp.weixin.qq.com'. html_entity_decode($content_url);
        $contentData = QueryList::get($url)
            ->rules([
                'content'   => array("#js_content", 'html', '->section:last() ->article:last()')
            ])
            ->queryData();
        $content = $contentData[0]['content'];
        $articleModel = new Article();
        $fileid = $request->param('fileid');
        $authorId = $request->param('uid');
        $articleRes = $articleModel->where('fileid', $fileid)
            ->where('author_id', $authorId)
            ->find();
        if(!empty($articleRes))
            return json(['code'=>0, 'msg'=>'文章已存在!']);
        $articleModel->startTrans();
        try {

            $articleModel->title = $request->param('title');
            $articleModel->author_id = $authorId;
            $articleModel->fileid = $fileid;
            $articleModel->digest = $request->param('digest');
            $articleModel->publish = $request->param('publish');
            $articleModel->cover = $request->param('cover');
            $articleModel->created = time();
            $articleModel->updated = time();
            $articleModel->save();
            if(!$articleModel->id)
                throw new \Exception('文章存储失败!');
            $contentRes = ArticleContent::create([
                'article_id'    => $articleModel->id,
                'content'       => $content,
                'created'       => time()
            ]);
            if(!$contentRes->id)
                throw new \Exception('文章详情存储失败!');
            $articleModel->commit();
            return json(['code'=>0, 'msg'=>'文章入库成功!']);
        }catch (\Exception $e){
            $articleModel->rollback();
            return json(['code'=>-1, 'msg'=>$e->getMessage()]);
        }
    }

    public function index(Request $request){
        $title = $request->param('title');
        $author = $request->param('author');
        $content = $request->param('content');
        $publish = urldecode($request->param('publish'));
        $arr = date_parse_from_format('Y年m月d日',$publish);
        $data = [
            'title'     => trim($title),
            'author'    => trim($author),
            'publish'   => mktime(0,0,0,$arr['month'],$arr['day'],$arr['year']),
            'created'   => time(),
            'updated'   => time()
        ];
        return json($data);
        $article = Article::create($data);
        $articleId = $article->id;
        $content = ArticleContent::create([
            'article_id'    => $articleId,
            'content'       => trim($content),
            'created'       => time()
        ]);
        if($content->id)
            return json(['code'=>0, 'msg'=>'文章入库成功!']);
        else
            return json(['code'=>401, 'msg'=>'文章入库失败!']);
    }

    public function upload(){
        // 图片本地存储路径
        $path = "./upload";
        // 远程图片地址
        $image = "http://pic33.photophoto.cn/20141022/0019032438899352_b.jpg";
        // 下载远程图片
        $img = $this->getImg($path, $image);
        // 本地图片地址
        $imgObj = new \CURLFile($img);
        //必须指定文件类型，否则会默认为application/octet-stream，二进制流文件
        $imgObj->setMimeType("application/octet-stream");
        // 获取图片服务器相关信息
        $picData = $this->picUrl();
        $host = $picData['host'];
        $url = $picData['api'];
        $data = $picData['data'];
        if($host) {
            // 设置header
            $header = [
                'User-Agent:' . 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Mobile Safari/537.36',
                'Cookie:' . '__jsluid=9dfcff33ed58689e2dcd955b03898555; FKPl_e1a1_saltkey=um8TT6C0; FKPl_e1a1_lastvisit=1546912611; Hm_lvt_aaecf8414f59c3fb0127932014cf53c7=1546916212; wdcid=2791338006c74e7d; FKPl_e1a1_seccode=187.bf5503cee832e6250a; FKPl_e1a1_auth=b9c1wNpQ1tDmzoq6i1%2BASoBrlF895IFeD6BNCtMkKvSATl705Us%2B%2BaI%2FKc0SEtxHzDXuCK7%2BU0B%2F9Fup1FNAFPatNdO%2B; FKPl_e1a1_nofavfid=1; FKPl_e1a1_visitedfid=31; FKPl_e1a1_ignore_notice=1; FKPl_e1a1_smile=2D1; Hm_lpvt_aaecf8414f59c3fb0127932014cf53c7=1546916577; FKPl_e1a1_forum_lastvisit=D_31_1546916590; FKPl_e1a1_creditbase=0D50D0D0D0D0D0D0D0; __jsl_clearance=1547012623.088|0|9m9JD5xICAtV3G5CzhsMPN2Vaz0%3D; FKPl_e1a1_sid=sZ7x57; FKPl_e1a1_lip=218.77.107.111%2C1546931307; FKPl_e1a1_creditnotice=0D55D0D0D0D0D0D0D0D5872155; FKPl_e1a1_creditrule=%E6%AF%8F%E5%A4%A9%E7%99%BB%E5%BD%95; FKPl_e1a1_st_t=5872155%7C1547012624%7C180fd083ea3349b9d3da76770e7b238b; FKPl_e1a1_wq_app_my_stylesetting=a%3A4%3A%7Bs%3A10%3A%22myextstyle%22%3Bs%3A1%3A%220%22%3Bs%3A20%3A%22forumindex_showmodel%22%3Bs%3A2%3A%22-1%22%3Bs%3A14%3A%22list_showmodel%22%3Bs%3A2%3A%22-1%22%3Bs%3A13%3A%22nav_showmodel%22%3Bs%3A1%3A%220%22%3B%7D; FKPl_e1a1_ulastactivity=b22fgGHvBJfrbnrE2HXfjTVGHSJ7qhkjHaDzYMxFLyhPnIq2sb%2Bx; wdlast=1547012627; FKPl_e1a1_lastact=1547012627%09plugin.php%09api'
            ];
            $data['Filedata'] = $imgObj;
        }else{
            $header = [
                'User-Agent' . 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_13_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
                'Cookie' . 'UM_distinctid=1682cba2640fc-0ee65b01122ec7-10346654-13c680-1682cba26421111; 432a8_threadlog=%2C90%2C; 432a8_oltoken=init; 432a8_cloudClientUid=48491019; Xz_suid=XZGUEST-0467EC58-D23B-8E51-2D38-70A4E892059C; Xz_auth=24d2dptEt0elLekJEY3kCAIksGDiJF%2B5msSfCtIsOJK78wLYrliLnOz6SiuQzQ; 432a8_winduser=AwZXAlZTVW0BDgAFB1JUAAkFUAYDVAcGXVIJB1ICVgUNAwFRAwFZD2s; 432a8_ucuser=AwZXAlZTVW0; 432a8_lastpos=other; 432a8_ci=post%091547012760%09%09; 432a8_lastvisit=0%091547012761%09%2Fapps.php%3Fqajax%26agreetings%26callbackjQuery17209286588929285307_1547012745979%26_1547012746144; CNZZDATA64053=cnzz_eid%3D877451335-1546933522-null%26ntime%3D1547011454',
                "content-type: multipart/form-data; boundary=----WebKitFormBoundary7MA4YWxkTrZu0gW",
                "cache-control: no-cache",
            ];
            $data['file'] = $imgObj;
        }
        echo '<pre>';
        echo $host . '<br />';
//        print_r($data);die;
        $ret = $this->postImg($url, $data, $header);
        if(!empty($ret))
//            unlink($img);
        var_dump($ret);
        die();
    }


    /**
     * 下载远程图片到本地
     * @param $path string 本地存储路径
     * @param $url  string 图片远程地址
     * @return string   返回图片位置
     */
    protected function getImg($path,$url){
        $suffix = explode('.', $url);
        $ext = end($suffix);
        $name = $path.'/'. time() . '.' . $ext;
        $source = file_get_contents($url);
        file_put_contents($name, $source);
        return $name;
    }

    /**
     * 将本地图片上传至远程服务器
     * @param $apiUrl string  图片服务器地址
     * @param $data array 请求参数
     * @param $header array header头信息
     * @return mixed  接收服务器返回值
     */
    protected function postImg($apiUrl, $data, $header){
        return Curl::send($apiUrl, $data, 'post', $header);
    }

    /**
     * 获取图片服务器地址
     * @return mixed
     */
    protected function picUrl(){
        $urlArray = [
            /*[
                'host'  => 'https://bbs.rednet.cn',
                'api'   => 'https://bbs.rednet.cn/misc.php?mod=swfupload&operation=upload&type=image&inajax=yes&infloat=yes&simple=2',
                'data'  => [
                    'uid'   => '5872155',
                    'hash'  => '34a00511669c6b30da346fe188361197'
                ]
            ],*/
            [
                'host'  => '',
                'api'   => 'http://bbs.xizi.com/job.php?action=mutiupload&isnew=1&random=0.05857486891413921&uid=2664410&step=2&verify=569cdf95&fid=90&cancelwater=0',
                'data'  => []
            ]
        ];
        $key = array_rand($urlArray, 1);
        return $urlArray[$key];
    }
}