<?php
 
/**
 * Created by PhpStorm.
 * User: druphliu@gmail.com
 * Date: 15-7-28
 * Time: 下午3:26
 */
class Utils
{
    /**
     * 获取客户端ip
     * @return string
     */
    public static function getIp()
    {
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $onlineip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $onlineip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $onlineip = getenv('REMOTE_ADDR');
        } elseif (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $onlineip = $_SERVER['REMOTE_ADDR'];
        }
        return $onlineip;
    }

    /**
     *获取当前用户城市
     */
    public static function getLocalCity()
    {

        $ip = self::getIp();
        $key = ip2long($ip);
        $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
        if(empty($res)){ return false; }
        $jsonMatches = array();
        preg_match('#\{.+?\}#', $res, $jsonMatches);
        if(!isset($jsonMatches[0])){ return false; }
        $json = json_decode($jsonMatches[0], true);
        if(isset($json['ret']) && $json['ret'] == 1){
           return $json['city'];
        }else{
            return false;
        }

    }

    public static function setCookie($name,$value,$expire=2592000)
    {
        $cookie = new CHttpCookie($name, $value);
        if ($expire) {
            $cookie->expire = time() + $expire;
        }
        Yii::app()->getRequest()->cookies[$name] = $cookie;
        return true;
    }

    public static function getCookie($name)
    {
        $cookie = Yii::app()->getRequest()->getCookies();
        return isset($cookie[$name]) ? $cookie[$name]->value : '';
    }

    public static function delCookie($name)
    {
        $cookie = Yii::app()->request->getCookies();
        unset($cookie[$name]);
    }
    /**
     * 返回用户名类型1手机2邮箱0默认
     * @param $username
     * @return int
     */
    public static function getInputType($username)
    {
        if ((bool)preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)+$/i', $username)) {
            return 2;
        } else if ((bool)preg_match('/^(13|14|15|17|18)\d{9}$/i', $username)) {
            return 1;
        } else {
            return 0;
        }
    }

    public static function filterIntval($id)
    {
        return intval($id);
    }

    /**
     * 随机生成验证码
     * @param $count
     * @return string
     */
    public static function generateCode($count, $hasABC = false)
    {
        $str = $hasABC ? '23456789abcdefghjkmnpqrstuvwxyz' : '23456789';
        $len = strlen($str) - 1;
        $count = $count < 4 ? 4 : $count;
        $string = '';
        for ($i = 0; $i < $count; $i++) {
            $string .= $str[mt_rand(0, $len)];
        }
        return $string;
    }

    /**
     * 随机生成用户名
     * @return string
     */
    public static function generateName()
    {
    	$username = 'U'.substr(time(),-3).substr(microtime(),2,5);
    	return $username;
    }

    /**
     * json消息返回
     * @param $code
     * @param string $msg
     * @param null $data
     * @param null $options
     * @return string
     */
    public static function jsonResult($code, $msg = '', $data = null, $options = null)
    {
    	return json_encode(array('code'=>$code, 'message'=>$msg, 'data'=>$data, 'options'=>$options));
    }

    /**
     * 加星号
     * @param $string
     * @param $start
     * @param $end
     * @return mixed
     */
    public static function addPointer($string, $start, $end)
    {
        $pointer = '';
        $length = $end - $start;
        $string = preg_replace("/ +/",' ',$string);
        if (preg_match("/^[\x7f-\xff]+$/", $string)) {
            //如果中文
            $start *= 3;
            $end *= 3;
            
            $tempLength = floor($length / 3);
            for ($i = 1; $i <= $tempLength; $i++) {
            	$pointer .= "*";
            }
        }else{
	        for ($i = 1; $i <= $length; $i++) {
	            $pointer .= "*";
	        }
        }
        return strlen($string) > $start ? substr_replace($string, $pointer, $start, $length) : $string;
    }

    public static function createDir($path){
            if(!is_dir($path)){
                if(!self::createDir(dirname($path))){
                    return false;
                }
                if(!mkdir($path,0777)){
                    return false;
                }
            }
            return true;
    }

    /**
     * 发送HTTP请求
     */
    public static function sendHttpRequest($url, $params = array(), $method = 'GET', $header = array(), $timeout = 5)
    {
        if (function_exists('curl_init')) {
            $ch = curl_init();
            if ($method == 'GET') {
                if (strpos($url, '?')) $url .= '&' . is_array($params) ? http_build_query($params) : $params;
                else $url .= '?' . is_array($params) ? http_build_query($params) : $params;

                curl_setopt($ch, CURLOPT_URL, $url);
            } elseif ($method == 'POST') {
               // $post_data = is_array($params) ? http_build_query($params) : $params;
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);

            }
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            //https不验证证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            if (!empty($header)) {
                //curl_setopt($ch, CURLOPT_NOBODY,FALSE);
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
                curl_setopt($ch, CURLINFO_HEADER_OUT, TRUE);
            }
            if ($timeout) curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            $content = curl_exec($ch);
            $info = curl_getinfo($ch);
            $errors = curl_error($ch);
            if (curl_getinfo($ch, CURLINFO_HTTP_CODE) == '200') {
                $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                $header = substr($content, 0, $headerSize);
                $content = substr($content, $headerSize);
                return array('content' => $content, 'info' => $info, 'error' => $errors, 'header' => $header);
            }else{
                return array('content' => $content, 'info' => $info, 'error' => $errors);
            }
        } else {
            $data_string = http_build_query($params);
            $context = array(
                'http' => array('method' => $method,
                    'header' => 'Content-type: application/x-www-form-urlencoded' . "\r\n" .
                        'Content-length: ' . strlen($data_string),
                    'content' => $data_string)
            );
            $contextid = stream_context_create($context);
            $sock = fopen($url, 'r', false, $contextid);
            if ($sock) {
                $result = '';
                while (!feof($sock)) $result .= fgets($sock, 4096);
                fclose($sock);
            }
            return array('content' => $result);
        }
    }

    /**
     * 加密解密函数
     * @param $string
     * @param string $operation
     * @param string $key
     * @param int $expiry
     * @return string
     */
    public static function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0)
    {
        $codeKey = '4f6647577904fab5614dbf7385d1b0ed';
        $ckey_length = 4;
        $key = md5($key ? $key : $codeKey);
        $keya = md5(substr($key, 0, 16));
        $keyb = md5(substr($key, 16, 16));
        $string = $operation == 'DECODE' ? strtr($string, '-_', '+/') : $string;
        $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length) : substr(md5(microtime()), -$ckey_length)) : '';

        $cryptkey = $keya . md5($keya . $keyc);
        $key_length = strlen($cryptkey);

        $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0) . substr(md5($string . $keyb), 0, 16) . $string;
        $string_length = strlen($string);

        $result = '';
        $box = range(0, 255);

        $rndkey = array();
        for ($i = 0; $i <= 255; $i++) {
            $rndkey[$i] = ord($cryptkey[$i % $key_length]);
        }

        for ($j = $i = 0; $i < 256; $i++) {
            $j = ($j + $box[$i] + $rndkey[$i]) % 256;
            $tmp = $box[$i];
            $box[$i] = $box[$j];
            $box[$j] = $tmp;
        }

        for ($a = $j = $i = 0; $i < $string_length; $i++) {
            $a = ($a + 1) % 256;
            $j = ($j + $box[$a]) % 256;
            $tmp = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        if ($operation == 'DECODE') {
            if ((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26) . $keyb), 0, 16)) {
                return substr($result, 26);
            } else {
                return '';
            }
        } else {
            return strtr( $keyc . str_replace('=', '', base64_encode($result)), '+/', '-_');
        }
    }
    
    /**
     * 返回两个时间点相差时分秒
     * param $onlyDay 只返回天（补差）
     */
    public static function timediff($begin_time, $end_time, $return_day = false,$onlyDay = false)
    {
    	$days = 0;
    	$hours = 0;
    	$mins = 0;
    	$secs = 0;

    	if($begin_time < $end_time){
    		$timediff = $end_time - $begin_time;
    		$days = intval($timediff / 86400);
    		$remain = $timediff % 86400;
    		if($onlyDay){
    			return array('day'=>($remain>0?$days+1:$days));
    		}
    		$hours = intval($remain / 3600);
    		if(!$return_day){
    			$hours = $hours + ($days * 24);
    		}
    		$remain = $remain % 3600;
    		$mins = intval($remain / 60);
    		$secs = $remain % 60;
    	}
    	
    	$res = array(
    		"day" => sprintf('%02d',$days),
    		"hour" => sprintf('%02d',$hours),
    		"min" => sprintf('%02d',$mins),
    		"sec" => sprintf('%02d',$secs)
    	);
    	return $res;
    }
   
    /**
     * 基于PHP没有安装 mb_substr 等扩展截取字符串，如果截取中文字则按2个字符计算
     * @param $string
     * @param $length
     * @param string $dot
     * @return mixed|string
     */
    public static function cutstr($string, $length, $dot = ' ...')
    {
        if (strlen($string)*2 <= $length*3) {
            return $string;
        }
        $pre = chr(1);
        $end = chr(1);
        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), $string);
        $strcut = '';
        if (strtolower('utf-8') == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    $n++;
                    $noc++;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    $n++;
                }
                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }
            $strcut = substr($string, 0, $n);
        } else {
            $_length = $length - 1;
            for ($i = 0; $i < $length; $i++) {
                if (ord($string[$i]) <= 127) {
                    $strcut .= $string[$i];
                } else if ($i < $_length) {
                    $strcut .= $string[$i] . $string[++$i];
                }
            }
        }
        $strcut = str_replace(array($pre . '&' . $end, $pre . '"' . $end, $pre . '<' . $end, $pre . '>' . $end), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        $pos = strrpos($strcut, chr(1));
        if ($pos !== false) {
            $strcut = substr($strcut, 0, $pos);
        }
        return $strcut . $dot;
    }

    /**
     * 跳转
     * @param string $default
     * @return mixed|string
     */
    public static function jsDreferer($default='') {
        if(isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
           return "window.history.go(-1)";
        } else {
            $referer = Yii::app()->createUrl('site/index');
        }
        if (strpos($referer, 'site/login') || strpos($referer, 'site/register') || !$referer) {
            $referer = Yii::app()->createUrl('site/index');
        }
        return "window.location.href='{$referer}'";
        //return $referer;
    }
    /**
     * 跳转
     * @param string $default
     * @return mixed|string
     */
    public static function dreferer($default='') {
        if(isset($GLOBALS['_SERVER']['HTTP_REFERER'])) {
            $referer = preg_replace("/([\?&])((sid\=[a-z0-9]{6})(&|$))/i", '\\1', $GLOBALS['_SERVER']['HTTP_REFERER']);
            $referer = substr($referer, -1) == '?' ? substr($referer, 0, -1) : $referer;
        } else {
            $referer = $default;
        }
        if (strpos($referer, 'site/login') || strpos($referer, 'site/register') || !$referer) {
            $referer = Yii::app()->createUrl('site/index');
        }
        return $referer;
    }

    /**
     * url添加变量参数
     * @param $url
     * @param $params
     * @return string
     */
    public static function addUrlParam($url, $params)
    {
        $query = $comm = '';
        $urlParse = parse_url(preg_match('/^(http|https:\/\/)/isU', $url) ? $url : 'http://' . $url);
        if (is_array($params)) {
            foreach ($params as $key => $value) {
                $query .= $comm . $key . '=' . $value;
                $comm = '&';
            }
        } else {
            $query = $params;
        }
        $scheme = isset($urlParse['scheme']) ? $urlParse['scheme'] : 'http';
        $host = $scheme.'://'.$urlParse['host'];
        $port = isset($urlParse['port'])?':'.$urlParse['port']:'';
        $path = isset($urlParse['path']) ? $urlParse['path'] : '';
        $qu = isset($urlParse['query']) ? $urlParse['query'] . '&' . $query : $query;
        $fragment = isset($urlParse['fragment']) ? '#' . $urlParse['fragment'] : '';
        return $host.$port.$path.'?'.$qu.$fragment;

    }
    
    /**
     * 用户关键信息加密、解密
     */
    public static function encrypt($input)
    {
        $key = Yii::app()->params['crypt_key'];
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = self::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv (mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = base64_encode($data);
        return $data;
    }
    
    public static function decrypt($sStr)
    {
        $sKey = Yii::app()->params['crypt_key'];
        $decrypted= mcrypt_decrypt(
            MCRYPT_RIJNDAEL_128,
            $sKey,
            base64_decode($sStr),
            MCRYPT_MODE_ECB
        );
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s-1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }
    
    private static function pkcs5_pad ($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function date_time($time){
        if(!$time){
            return '';
        }
        return date('Y-m-d H:i:s',$time);
    }


    /**
     * 数字转中文金额大写
     */
    public  static function  num2rmb($number = 0, $int_unit = '元', $is_round = true, $is_extra_zero = false)
    {
        // 将数字切分成两段
        $parts = explode('.', $number, 2);
        $int = isset($parts[0]) ? strval($parts[0]) : '0';
        $dec = isset($parts[1]) ? strval($parts[1]) : '';
        // 如果小数点后多于2位，不四舍五入就直接截，否则就处理
        $dec_len = strlen($dec);
        if (isset($parts[1]) && $dec_len >2) {
            $dec = $is_round
                ? substr(strrchr(strval(round(floatval("0." . $dec), 2)), '.'), 1)
                : substr($parts[1], 0, 2);
        }
        // 当number为0.001时，小数点后的金额为0元
        if (empty($int) && empty($dec)) {
            return '零';
        }
        // 定义
        $chs = array('0', '壹', '贰', '叁', '肆', '伍', '陆', '柒', '捌', '玖');
        $uni = array('', '拾', '佰', '仟');
        $dec_uni = array('角', '分');
        $exp = array('', '万');
        $res = '';
        // 整数部分从右向左找
        for ($i = strlen($int) - 1, $k = 0; $i >= 0; $k++) {
            $str = '';
            // 按照中文读写习惯，每4个字为一段进行转化，i一直在减
            for ($j = 0; $j < 4 && $i >= 0; $j++, $i--) {
                $u = $int{$i} > 0 ? $uni[$j] : ''; // 非0的数字后面添加单位
                $str = $chs[$int{$i}] . $u . $str;
            }
            // echo $str."|".($k - 2)."
            $str = rtrim($str, '0'); // 去掉末尾的0
            $str = preg_replace("/0+/", "零", $str); // 替换多个连续的0
            if (!isset($exp[$k])) {
                $exp[$k] = $exp[$k - 2] . '亿'; // 构建单位
            }
            $u2 = $str != '' ? $exp[$k] : '';
            $res = $str . $u2 . $res;
        }
        // 如果小数部分处理完之后是00，需要处理下
        $dec = rtrim($dec, '0');
        // 小数部分从左向右找
        if (!empty($dec)) {
            $res .= $int_unit;
            // 是否要在整数部分以0结尾的数字后附加0，有的系统有这要求
            if ($is_extra_zero) {
                if (substr($int, -1) === '0') {
                    $res .= '零';
                }
            }
            for ($i = 0, $cnt = strlen($dec); $i < $cnt; $i++) {
                $u = $dec{$i} > 0 ? $dec_uni[$i] : ''; // 非0的数字后面添加单位
                $res .= $chs[$dec{$i}] . $u;
            }
            $res = rtrim($res, '0'); // 去掉末尾的0
            $res = preg_replace("/0+/", "零", $res); // 替换多个连续的0
        } else {
            $res .= $int_unit . '整';
        }
        return $number < 0 ? "(负)" . $res : $res;
    }
    /**
     * @param $num
     * @return float
     * 将金额数转换成只保留小数点后两位，并四舍五入
     */
    public static function formatPriceNumber($num){
        return (float)number_format($num,2,'.','');
    }
    public static function priceConversion($num)
    {
        $unite="万";
        if($num>=10000){
            $num = $num/10000;
            $unite = "亿";
        }
        $price =(float)number_format($num,4,'.','');
        return array("price"=>$price,'unite'=>$unite);
    }

    /**
     * 验证是否是合法价格
     * @param $str
     * @return int
     */
    public static  function  isPrice($str){
        return preg_match('/^\d{0,8}\.{0,1}(\d{1,2})?$/', $str);
    }

    /*
     * zip打包
     */
    public static  function ZipPackage($file_path=array(),$filename){
        $zip = new ZipArchive(); //使用本类，linux需开启zlib，windows需取消php_zip.dll前的注释
        $path= substr($filename,0,strrpos($filename,'/')+1);
        if (!is_dir($path))
            Utils::createDir($path);
        if (file_exists($filename))
            @unlink($filename);
        $res = $zip->open($filename, ZIPARCHIVE::CREATE);
        if ($res !== TRUE) {
            exit('无法打开文件，或者文件创建失败');
        }
        foreach ($file_path as $src) {
            if (file_exists($src)) {
                $zip->addFile($src, basename($src)); //第二个参数是放在压缩包中的文件名称，如果文件可能会有重复，就需要注意一下
            }
        }
        $zip->close(); //关闭
        return file_exists($filename);
    }

    /**
     * @param $value
     * @param $logo
     * @param int $type 1:url 2:tel
     * @return string|void
     */
    public static function createQrCode($value,$logo,$type=1,$fileName=false){
        $errorCorrectionLevel = 0;//容错级别
        $matrixPointSize = 6;//生成图片大小
        //生成二维码图片
        $QRStr = QRcode::png($value, $fileName, $errorCorrectionLevel, $matrixPointSize, 2);

        if ($logo != FALSE) {
            $QR = imagecreatefromstring($QRStr);
            $logo = imagecreatefromstring(file_get_contents($logo));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logo);//logo图片宽度
            $logo_height = imagesy($logo);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width/$logo_qr_width;
            $logo_qr_height = $logo_height/$scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
            //输出图片
            ob_start();
            imagepng($QR);
            $QRStr = ob_get_contents();
            ob_clean();
        }
        return $QRStr;
    }


    /**
     * 手机格式检查
     * @return bool
     */
    public static function isPhone($phone)
    {
        if (preg_match("/^1(?:3[0-9]|5[012356789]|8[02356789]|7[0678])(?P<separato>-?)\d{4}(?P=separato)\d{4}$/", $phone)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 生成随机密码
     */
    public static function pwd()
    {
        $arr = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','g','k','m','n','p','q','r','s','t','u','v','w','x','y','z');
        $num = count($arr)-1;
        $key = round(1,$num);
        $pwd = $arr[$key];
        for($i=0;$i<7;$i++)
        {
            $key = round(0,$num);
            $pwd .= $arr[$key];
        }
        return $pwd;
    }

    /**
     * html转pdf
     * @param $content
     * @param $file_name
     */
    public static function createPdf($content,$file_name)
    {
        set_time_limit(0);

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);


        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);


        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);


        // ---------------------------------------------------------

        // set font
        $pdf->SetFont('stsongstdlight', '', 10);

        // add a page
        $pdf->AddPage();

        // writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
        // writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
        //iconv("gb2312//TRANSLIT","utf-8",$content);
        // output the HTML content
        $pdf->writeHTML($content, true, false, true, false, '');
        $pdf->lastPage();
        // ---------------------------------------------------------

        //Close and output PDF document
        $pdf->Output($file_name, 'I');

        //============================================================+
        // END OF FILE
        //============================================================+
    }

    public static function base64EncodeImage ($image_path) {
        $image_info = getimagesize($image_path);
        $image_data = fread(fopen($image_path, 'r'), filesize($image_path));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }

    /**
     * 生成订单
     */
    public static function makeOrderId(){
        return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    /**
     * @param $id   身份授权key（大小写敏感）
     * @param $com  要查询的快递公司代码，不支持中文
     * @param $nu   要查询的快递单号，请勿带特殊符号，不支持中文
     * @param $show 返回类型：0：返回json字符串，1：返回xml对象，2：返回html对象，3：返回text文本。
     * @param $muti 返回信息数量：1:返回多行完整的信息，0:只返回一行信息。不填默认返回多行。
     * @param $order    排序：desc：按时间由新到旧排列，asc：按时间由旧到新排列。不填默认返回倒序（大小写不敏感）
     *
     */
    public static function getExpressRouteBy110($id, $com, $nu, $show=0, $muti=1, $order='desc') {
        $url = "http://api.kuaidi100.com/api?id={$id}&com={$com}&nu={$nu}&show={$show}&muti={$muti}&order={$order}";
        return self::sendHttpRequest($url, [], 'GET', [], 500);
    }

    public static function getExpressRouteByBird($LogisticCode , $expressCode='SF', $orderId="") {
        $userID = "1335481";
        $keyValue = "9f31fcb5-cfb8-4365-bd2d-2447077ebce1"; //加密私钥，由快递鸟提供
        $url = "http://api.kdniao.cc/Ebusiness/EbusinessOrderHandle.aspx";
        $DataType = "2";
        $jsonStr = "{\"OrderCode\":\"{$orderId}\",\"ShipperCode\":\"{$expressCode}\",\"LogisticCode\":\"{$LogisticCode}\"}"; //JSON字符串string
        $datasign = utf8_encode(base64_encode(md5($jsonStr . $keyValue)));  //把(jsonStr+APIKey)进行MD5加密，然后Base64编码，最后 进行URL(utf-8)编码
        $params = [
            'RequestType' => 1002,
            'EBusinessID' => $userID,
            'RequestData' => $jsonStr,
            'DataSign' => $datasign,
            'DataType' => $DataType,
        ];
        return self::sendHttpRequest($url, $params, "POST");
    }

    public static function getRandLetter() {
        $word = '';
        for ($i = 1; $i <= 4; $i++) {
            $flag = rand(0, 1);
            if ($flag) {
                $word .= chr(rand(65, 90));
            }else {
                $word .= chr(rand(97, 122));
            }
        }
        return $word;
    }

    public static function objectToArray($object) {
        if (is_object($object) && !empty($object)) {
            $newArr = [];
            foreach ($object as $key=>$value) {
                if (!is_numeric($key))
                    $newArr[$key] = $value;
            }
            $object = $newArr;
        }
        return $object;
    }
}

