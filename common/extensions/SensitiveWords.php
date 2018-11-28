<?php

/**
 * 锟斤拷锟叫词硷拷锟?
 * User: thinkpad
 * Date: 2016/7/19
 * Time: 16:37
 */
class SensitiveWords
{
    /**
     * 锟斤拷锟斤拷欠锟斤拷锟斤拷写锟?
     * @return bool
     */
    public static function checkWords($content)
    {
        $result = false;
        $filePath = self::getWordsDicPath();
        if (function_exists('trie_filter_search_all') && file_exists($filePath)) {
            $file = trie_filter_load($filePath);
            $res = trie_filter_search_all($file, $content);  // 一锟轿帮拷锟斤拷锟叫碉拷锟斤拷锟叫词讹拷锟斤拷锟斤拷锟斤拷
            trie_filter_free($file);
            if ($res)
                $result = true;
        }else{
            //正则
            $file = dirname(__FILE__) . '/words.txt';
            if(file_exists($file)){
                $arr = file($file);//敏感词典
                $arr1 = array();
                foreach ($arr as $k => $v) {
                    $arr1["num" . $k] = trim($v);
                }
                $newContent = str_replace($arr1, "*", $content);
                $result = $newContent == $content ? false : true;
            }

        }
        return $result;
    }

    /**
     * 锟斤拷锟斤拷锟斤拷锟叫达拷锟街碉拷
     * @return bool
     */
    public static function updateWords($words = array())
    {
        $result = false;
        $str = '';
        $file = dirname(__FILE__) . '/words.txt';
        $fp = @fopen($file, 'w+');
        if (!$fp) die("Open $file failed");
        foreach ($words as $w=>$r) {
            $str .= $w . "\n";
        }
        fwrite($fp, $str);
        if ($fp) @fclose($fp);
        $wordsDicPath = self::getWordsDicPath();
        $command = '/root/tools/trie_filter/dpp ' . $file . ' ' . $wordsDicPath;
        exec($command);
        if (file_exists($wordsDicPath))
            $result = true;
        return $result;
    }

    private static function getWordsDicPath(){
        $file = dirname(__FILE__).'/words.dic';
        return $file;
    }
}