<?php
/**
 * 隐藏部分字符串
 * @param $string
 * @param int $start
 * @param int $len
 * @param string $etc
 * @return string
 */
function smarty_modifier_hide_string($string, $start=0, $len=0, $etc='*'){
    if(empty($string)){
        return $string;
    }
    $str = [];
    $str[] = substr($string, 0, $start);
    if($len>0){
        $str[] = str_repeat($etc, $len);
        $str[] = substr($string, $start+$len);
    }else{
        $str[] = str_repeat($etc, strlen($string)-$start);
    }
    return join("", $str);
}