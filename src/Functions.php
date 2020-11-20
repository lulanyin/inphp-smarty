<?php
if(!function_exists('fun_each')){
    /**
     * 高版本已废弃smarty里边原有的一个PHP函数，需要做兼容处理，否则会有错误提示
     * @param $array
     * @return array|false
     */
    function fun_each(&$array){
        $res = array();
        $key = key($array);
        if($key !== null){
            next($array);
            $res[1] = $res['value'] = $array[$key];
            $res[0] = $res['key'] = $key;
        }else{
            $res = false;
        }
        return $res;
    }
}

/**
 * User: Hunter
 * Date: 2020.11.20
 * Time: 20:35:24
 * smarty 模板引擎动态解析程序模板，最终会生成一个文件保存到同级文件夹，然后Smarty引入，以用于解析模板标签
 * @param string $tag
 * @return void
 */
function autoCompileSmartyTag($tag)
{
    // PHP template
    $template = "<?php
    /**
     *  系统自动创建
     *  时间：".date("Y/m/d H:i:s")."
     */
    class Smarty_Internal_Compile_{$tag} extends Smarty_Internal_CompileBase {
        //必要的属性
        public \$required_attributes = array();
        //可传入的属性
        public \$optional_attributes = array('_any');
        //?
        public \$shorttag_order = [];
        public function compile(\$args, \$compiler)
        {
            //获取内容，然后交给Smarty模板处理
            \$_attr = \$this->getAttributes(\$compiler, \$args);
            \$_attr = array_change_key_case(\$_attr,CASE_LOWER);//把数据的字符串键名全为小写，此方法默认小写，若大写请用: array_change_key_case(\$_attr, CASE_UPPER);
            \$item = isset(\$_attr['item']) ? \$_attr['item'] : \"'{$tag}'\";
            \$_attr['lib'] = isset(\$_attr['lib']) ? \$_attr['lib'] : '{$tag}';
            \$var = str_replace(\"'\",'','{$tag}_'.\$item);
            \$notClose = isset(\$_attr['item']) ? false : true;
            \$compiler->notClose = \$notClose;
            return \$this->processOutput(\$compiler,\$_attr,\$item,\$var,'{$tag}');
        }
    }
    class Smarty_Internal_Compile_{$tag}else extends Smarty_Internal_CompileBase {
        public function compile(\$args, \$compiler, \$parameter)
        {
            \$_attr = \$this->getAttributes(\$compiler, \$args);
            \$notClose = isset(\$_attr['item']) ? false : true;
            \$compiler->notClose = \$notClose;
            list(\$openTag, \$nocache, \$item, \$key) = \$this->closeTag(\$compiler, array('{$tag}'), \$notClose);
            \$this->openTag(\$compiler, '{$tag}else', array('{$tag}else', \$nocache, \$item, \$key));
            return \"<?php }\\nif (!\\\$_smarty_tpl->tpl_vars[\$item]->_loop) {\\n?>\";
        }
    }
    class Smarty_Internal_Compile_{$tag}close extends Smarty_Internal_CompileBase {
        public function compile(\$args, \$compiler, \$parameter)
        {
            \$_attr = \$this->getAttributes(\$compiler, \$args);
            if (\$compiler->nocache) {
                \$compiler->tag_nocache = true;
            }
            \$notClose = isset(\$_attr['item']) ? false : true;
            \$compiler->notClose = \$notClose;
            list(\$openTag, \$compiler->nocache, \$item, \$key) = \$this->closeTag(\$compiler, array('{$tag}', '{$tag}else'), \$notClose);
            return \"<?php } ?>\";
        }
    }";
    if(!defined("SMARTY_TAGS_PARSER")){
        define("SMARTY_TAGS_PARSER", __DIR__);
    }
    $dir = SMARTY_TAGS_PARSER."/Compiler";
    if(!is_dir($dir)){
        mkdir($dir, 0777);
    }
    $file = $dir."/cache_smarty_internal_compile_".$tag.".php";
    file_put_contents($file,$template);
}

if(!function_exists('insert_library')){
    /**
     * 引入自定义标签
     * Date: 2020.11.20
     * Time: 20:35:24
     * @param array $param
     * @return string|mixed|array
     */
    function insert_library($param = array())
    {
        $lib = isset($param['lib']) ? $param['lib'] : "not_set";
        $functionName = "tag_{$lib}";
        if(is_file(SMARTY_TAGS_PARSER."/Tags/{$lib}.tag.php")){
            require_once SMARTY_TAGS_PARSER."/Tags/{$lib}.tag.php";
            return $functionName($param);
        }
        return "";
    }
}