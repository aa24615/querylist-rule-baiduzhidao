
# zyan/querylist-rule-baiduzhidao

querylist插件 - 百度知道爬虫规则  

本分支来源于 [https://github.com/wqsacy/querylist-rule-baiduzhidao](https://github.com/wqsacy/querylist-rule-baiduzhidao)

## 要求

1. php >= 7.0
2. Composer ^2.0
3. QueryList ^4.0

## 安装

```shell
composer require zyan/querylist-rule-baiduzhidao -vvv
```

## 使用

```php 
use QL\Ext\Baiduzhidao;
use QL\QueryList;

$ql = QueryList::getInstance();
$ql->use(Baiduzhidao::class);
$list =  $ql->baiduzhidao()->search('usb')->page(1);

print_r($list->all());

Array
(
    [0] => Array
        (
            [title] => php.cn是不是php中国的官方网站
            [link] => http://zhidao.baidu.com/question/2075148895278992948.html?fr=iks&word=php%B9%D9%CD%F8%CA%C7%B6%E0%C9%D9&ie=gbk
            [best_answer] => 答：不是的，官方中文版的地址是http://cn2.php.net/
        )

    [1] => Array
        (
            [title] => PHP官网的
            [link] => http://zhidao.baidu.com/question/2010737170671608028.html?fr=iks&word=php%B9%D9%CD%F8%CA%C7%B6%E0%C9%D9&ie=gbk
            [best_answer] => 答：php新版是5.5的 你要升级的话， http://www.php.net window系统的话， 去这里下载http://windows.php.net
        )
    ...
)

```

## 参与贡献

1. fork 当前库到你的名下
3. 在你的本地修改完成审阅过后提交到你的仓库
4. 提交 PR 并描述你的修改，等待合并

## License

[MIT license](https://opensource.org/licenses/MIT)
