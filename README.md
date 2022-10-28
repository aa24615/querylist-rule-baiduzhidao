
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


//列表页
$list =  $ql->baiduzhidao()->search('php官网是什么')->getList(1);

print_r($list);

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

//内容页(答案)
$body =  $ql->baiduzhidao()->getBody('http://zhidao.baidu.com/question/562434686892368412.html');

print_r($list);

Array
(
    [0] => 120w,小米12 Pro支持120W 小米澎湃秒充功能，我们对它的快充功能进行了测试。30分快充测试数据经过实测得知，从1%电量开始使用原装120W 小米澎湃秒充套装将小米12 Pro充满需要22分钟左右。值得一提的是，本次小米12 Pro随配的120W充电器采用了USB-A接口，充电线为USB-A to USB-C数据线，与市面上常见的USB-C to USB-C数据线并不通用，这就意味着用户需要使用原装120W快充套装才能实现最快的充电速度。
    [1] => 12Pro标配充电器则可满血输出最高120W功率。支持120W有线快充,50W无线快充,10W无线反向充电。
)

```

## 参与贡献

1. fork 当前库到你的名下
3. 在你的本地修改完成审阅过后提交到你的仓库
4. 提交 PR 并描述你的修改，等待合并

## License

[MIT license](https://opensource.org/licenses/MIT)
