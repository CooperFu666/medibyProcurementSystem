<?php

/**
 * Created by yiicms.
 * User: Administrator
 * Author: druphliu@gmail.com
 * Date: 2015/4/24
 * Time: 17:40
 */
class ImageServiceHandle
{
    //const URL = 'http://www.mediby.com'; // production
    const URL = 'http://test.mediby.com';
    const FILE_LIST = '/interface/filelist';
    const FILE_DELETE = '/interface/delete';
    const FILE_UPLOAD = '/backend/ueditor';
    const AVATAR_UPLOAD = '/backend/ueditor';
    const FILE_GET = '/interface/getFile';
    const FILE_THUMB = '/interface/getThumb';
    const UPLOAD_TYPE_IMAGE = 'image';
    const UPLOAD_TYPE_FILE = 'file';
    const UPLOAD_TYPE_VIDEO = 'video';
    const UPLOAD_TYPE_MP3 = 'mp3';

    protected  $app;
    protected  $secret;

    public function __construct($app,$secret){
        $this->app = $app;
        $this->secret = $secret;
    }
    public  function fileList($start, $pageSize = 15, $type = '')
    {
        $codeArray = $this->_setCode();
        $params = array(
            'type' => $type,
            'app' => $this->app,
            'code' => Utils::authcode(CJSON::encode($codeArray), 'ENCODE', $this->secret),
            'start' => $start,
            'pageSize' => $pageSize,
            'datetime' => $codeArray['datetime']);
        $uri = http_build_query($params);
        $url = self::URL . self::FILE_LIST . '?' . $uri;
        $result = Utils::sendHttpRequest($url);
        $data = $result['content'] ? CJSON::decode($result['content']) : '';
        if (isset($data['data'])) {
            return $data;
        } else {
            return array();
        }
    }

    public  function fileDelete($id)
    {
        $codeArray = $this->_setCode();
        $params = array(
            'app' => $this->app,
            'code' => Utils::authcode(CJSON::encode($codeArray), 'ENCODE', $this->secret),
            'datetime' => $codeArray['datetime']);
        $uri = http_build_query($params);
        $url = self::URL . self::FILE_DELETE . '?' . $uri;
        $data = array('id' => $id);
        $result = Utils::sendHttpRequest($url, $data, 'POST');
        return $result['content'] ? CJSON::decode($result['content']) : '';
    }

    public static function uploadUrl($thumb = array(),$mark='')
    {
        $thumbs = array();
        //$codeArray = $this->_setCode();

        if ($thumb) {
            foreach ($thumb as $t) {
                $thumbs[] = array('width' => $t['width'], 'height' => $t['height']);
            }
        }
        /*if($mark)
            $codeArray['mark'] = $mark;
        $codeArray['thumb'] = $thumbs;*/
        $params = array(
            'action' => 'uploadimage',//$this->app,
            //'code' => Utils::authcode(CJSON::encode($codeArray), 'ENCODE', $this->secret),
            //'datetime' => $codeArray['datetime']
            );
        $uri = http_build_query($params);
        return self::URL . self::FILE_UPLOAD . '?' . $uri;
    }
    public static function uploadAuth()
    {

        $params = array(
            'action' => 'uploadauth',
        );
        $uri = http_build_query($params);
        return self::URL . self::AVATAR_UPLOAD . '?' . $uri;
    }
    public static function uploadAvatar(/*$width, $height*/)
    {
        //$codeArray = $this->_setCode();
        //$thumbs[] = array('width' => $width, 'height' => $height);
        //$codeArray['thumb'] = $thumbs;
        $params = array(
            'action' => 'uploadavatar',//$this->app,
            //'code' => Utils::authcode(CJSON::encode($codeArray), 'ENCODE', $this->secret),
            //'datetime' => $codeArray['datetime']
            );
        $uri = http_build_query($params);
        return self::URL . self::AVATAR_UPLOAD . '?' . $uri;
    }

    public function getFile($id)
    {
        $codeArray = $this->_setCode();
        $params = array(
            'app' => $this->app,
            'code' => Utils::authcode(CJSON::encode($codeArray), 'ENCODE', $this->secret),
            'datetime' => $codeArray['datetime']);
        $uri = http_build_query($params);
        $url = self::URL . self::FILE_GET . '?' . $uri;
        $data = array('id' => $id);
        $result = Utils::sendHttpRequest($url, $data, 'POST');
        return $result['content'] ? CJSON::decode($result['content']) : '';
    }

    public function getThumb($id, $thumbs)
    {
        $codeArray = $this->_setCode();
        $codeArray['thumb'] = $thumbs;
        $params = array(
            'app' => $this->app,
            'code' => Utils::authcode(CJSON::encode($codeArray), 'ENCODE', $this->secret),
            'datetime' => $codeArray['datetime']
        );
        $uri = http_build_query($params);
        $url = self::URL . self::FILE_THUMB . '?' . $uri;
        $data = array('id' => $id);
        $result = Utils::sendHttpRequest($url, $data, 'POST');
        return $result['content'] ? CJSON::decode($result['content']) : '';
    }

    /**
     * 加密串解密前数组封装
     * @return array
     */
    private function _setCode()
    {
        return array('sign' => $this->_setSign(), 'datetime' => time());
    }

    /**
     * 签名
     * @return string
     */
    private function _setSign()
    {
        return md5($this->app . $this->secret);
    }
}