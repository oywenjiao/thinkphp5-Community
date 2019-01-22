<?php
/**
 * Created by PhpStorm.
 * User: crazy
 * Date: 2019-01-09
 * Time: 10:11
 */

namespace org;


class Curl
{
    private static  $url = ''; // 访问的url
    private static $oriUrl = ''; // referer url
    private static $data = array(); // 可能发出的数据 post
    private static $method; // 访问方式，默认是GET请求
    private static $header = array(); // 请求需要模拟的head头信息

    public static function send($url, $data = array(), $method = 'get', $header = array()) {
        if (!$url)
            exit('url can not be null');
        self::$url = $url;
        self::$method = $method;
        $urlArr = parse_url($url);
        self::$oriUrl = $urlArr['scheme'] .'://'. $urlArr['host'];
        self::$data = $data;
        if ( !in_array(self::$method, ['get', 'post']) )
            exit('error request method type!');
        if (!empty($header))
            self::$header = $header;
        $func = self::$method . 'Request';
        return (new static())->$func(self::$url);
    }


    /**
     * 基础发起curl请求函数
     * @param int $is_post 是否是post请求
     * @return bool|string
     */
    private function doRequest($is_post = 0) {
        //初始化curl
        $ch = curl_init();
        //抓取指定网页
        curl_setopt($ch, CURLOPT_URL, self::$url);
        // 启用时会将头文件的信息作为数据流输出。
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
//        curl_setopt($ch, CURLOPT_AUTOREFERER, true);
        // 来源一定要设置成来自本站
//        curl_setopt($ch, CURLOPT_REFERER, self::$oriUrl);

        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //post提交方式
        if($is_post == 1)
            curl_setopt($ch, CURLOPT_POST, $is_post);
        if (!empty(self::$data)) {
//            self::$data = self::dealPostData(self::$data);
            curl_setopt($ch, CURLOPT_POSTFIELDS, self::$data);
        }
        // 设置header头信息
        if(!empty(self::$header))
            curl_setopt($ch, CURLOPT_HTTPHEADER, self::$header);
        //运行curl
        $data = curl_exec($ch);
        // 关闭curl
        curl_close($ch);
        return $data;
    }

    /**
     * 发起get请求
     */
    public function getRequest() {
        return $this->doRequest(0);
    }

    /**
     * 发起post请求
     */
    public function postRequest() {
        return $this->doRequest(1);
    }

    /**
     * 处理发起非get请求的传输数据
     * @param $postData
     * @return bool|string
     */
    public function dealPostData($postData) {
        if (!is_array($postData))
            exit('post data should be array');
        $o = '';
        foreach ($postData as $k => $v) {
            $o .= "$k=" . urlencode($v) . "&";
        }
        $postData = substr($o, 0, -1);
        return $postData;
    }
}