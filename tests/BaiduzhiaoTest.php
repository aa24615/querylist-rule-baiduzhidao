<?php


namespace Zyan\Tests;


use PHPUnit\Framework\TestCase;
use Zyan\QLPlugin\Baiduzhidao;
use QL\QueryList;

class BaiduzhiaoTest extends TestCase
{

    public function test_test(){
        $ql = QueryList::getInstance();
        $ql->use(Baiduzhidao::class);

        $list =  $ql->baiduzhidao()->search('php官网是多少')->getList(1,true);
        $this->assertIsArray($list);
        $this->assertTrue(count($list)>1);
    }

    public function test_body(){
        $ql = QueryList::getInstance();
        $ql->use(Baiduzhidao::class);
        $list =  $ql->baiduzhidao()->getBody('https://zhidao.baidu.com/question/562434686892368412.html');

        $this->assertIsArray($list);
        $this->assertTrue(count($list)>0);
    }
}