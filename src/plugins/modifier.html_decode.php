<?php
/**
 * Created by PhpStorm.
 * Date: 2015/10/31
 * Time: 10:49
 */
/**
 * HTML解压
 * @param $string
 * @param $char
 * @return mixed|string
 */
function smarty_modifier_html_decode($string, $char=ENT_QUOTES)
{
    if(!empty($string)){
        return htmlspecialchars_decode(htmlspecialchars_decode($string, $char), $char);
    }
    return $string;
}