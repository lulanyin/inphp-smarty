# smarty

#### 项目介绍
改过的Smarty模板引擎
可自定义标签

### 安装方法
```
composer install lulanyin/smarty
```

### 使用方法

```php
//config.php
//在您的PHP入口配置文件中，定义常量，以声明您的自定义标签的文件夹位置：
define("SMARTY_TAGS_PARSER", ROOT."/smarty_tags");
//请在 SMARTY_TAGS_PARSER 文件夹中创建以下2个文件夹，文件夹名称必须一致：
//1. Compiler   ----    存放标签编译文件，请给予可写权限，因为编译时需要生成文件存放
//2. Tags       ----    您编写的自定义标签存放文件夹
```

### 自定义标签示例：
```php
//文件命名规则 [标签名].tag.php
//函数命名规则 tag_[标签名]
//保存位置：SMARTY_TAGS_PARSER."/Tags"

//文件：SMARTY_TAGS_PARSER位置：ROOT."/smarty_tags/Tags/news_list.tag.php"
//新闻列表
function tag_news_list($params = []){
    //分类ID
    $category_id = $params['cid'] ?? 0;
    //查询获得列表 .... 过程请自行实现
    $list = [];
    //返回列表数据
    return $list;
}

//文件：SMARTY_TAGS_PARSER位置：ROOT."/smarty_tags/Tags/config.tag.php"
//获取配置字符串
function tag_config($params = []){
    //您设置了一个全局配置文件，大概值：
    $configs = [
        "email"    => "me@lanyin.lu",
        "url"      => "http://www.lanyin.lu"
    ];
    //参数字段
    $name = $params['name'] ?? null;
    if(!is_null($name)){
        return $configs[$name] ?? "";
    }
    return "";
}
```

### 模板中使用自定义标签，注意，下方设置的Smarty模板解析边缘字符串是  左边：{，右边：}
```html
<div class="news-list">
    {news_list cid=5 item=n}
    <div class="item">
        <div class="thumb"><img src="{$n.preview}"></div>
        <div class="section">
            <h3><a href="{$n.url}" title="{$n.title}">{$n.title}</a></h3>
            <p>{$n.description|truncate:30:'...':true}</p>
        </div>
    </div>
    <!-- 与 smarty 原的模板标签的  foreach 对应的 foreachelse 一样的用法 -->
    {news_liseelse}
    <center>暂无新闻</center>
    {/news_list}
</div>

我的邮箱是：{config name="email"}，欢迎访问我的博客：{config name="url"}。
```
