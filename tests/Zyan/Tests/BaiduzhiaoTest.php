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

        $list =  $ql->baiduzhidao()->search('php官网是多少')->page(1);

        $all = $list->all();
        $this->assertIsArray($all);
        $this->assertTrue(count($all)>1);
    }
}