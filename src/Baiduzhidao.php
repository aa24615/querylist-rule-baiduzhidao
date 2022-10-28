<?php

namespace Zyan\QLPlugin;

use QL\Contracts\PluginContract;
use QL\QueryList;

/**
 * Class Baiduzhidao.
 *
 * @package Zyan\QLPlugin
 *
 * @author 读心印 <aa24615@qq.com> www.zyan.me
 */
class Baiduzhidao implements PluginContract
{
    const API = 'https://zhidao.baidu.com/search';
    protected $ql;
    protected $keyword;
    protected $pageNumber = 10;
    protected $httpOpt = [
        'headers' => [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36',
            'Accept-Encoding' => 'gzip, deflate, br',
            'Referer' => 'https://www.baidu.com',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9',
            'Accept-Language' => 'en-US,en;q=0.9,zh-CN;q=0.8,zh;q=0.7'
        ]
    ];

    public function __construct(QueryList $ql, $pageNumber)
    {
        $this->pageNumber = $pageNumber;
        $this->ql = $ql;
    }

    public static function install(QueryList $queryList, ...$opt)
    {
        $name = $opt[0] ?? 'baiduzhidao';
        $queryList->bind($name, function ($pageNumber = 10) {
            return new Baiduzhidao($this, $pageNumber);
        });
    }

    public function setHttpOpt(array $httpOpt = [])
    {
        $this->httpOpt = $httpOpt;
        return $this;
    }

    public function search($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }

    protected function query($page)
    {
        $this->ql->rules([
            'title' => ['.dt>a', 'text'],
            'link' => ['.dt>a', 'href'],
            'best_answer' => ['.answer', 'text']
        ])
            ->range('.list>.dl')
            ->get(self::API, [
                'word' => $this->keyword,
                'rn' => $this->pageNumber,
                'ie' => 'utf-8',
                'pn' => $this->pageNumber * ($page - 1)
            ], $this->httpOpt);

        return $this->ql;
    }

    public function getList($page = 1, $realURL = false)
    {
        return $this->query($page)
            ->encoding('UTF-8', 'GB2312')
            ->removeHead()
            ->query()
            ->getData(function ($item) use ($realURL) {
                $realURL && $item['link'] = $this->getRealURL($item['link']);
                return $item;
            })
            ->all();
    }


    /**
     * 得到百度跳转的真正地址
     * @param $url
     * @return mixed
     */
    protected function getRealURL($url)
    {
        if (empty($url)) {
            return $url;
        }
        try {
            $header = get_headers($url, 1);
            if (strpos($header[0], '301') || strpos($header[0], '302')) {
                if (is_array($header['Location'])) {
                    //return $header['Location'][count($header['Location'])-1];
                    return $header['Location'][0];
                } else {
                    return $header['Location'];
                }
            } else {
                return $url;
            }
        } catch (\Exception $exception) {
            return $url;
        }
    }

    /**
     * getPage.
     *
     * @return int
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getPage()
    {
        $count = $this->getCount();
        $countPage = ceil($count / $this->pageNumber);
        return $countPage;
    }

    /**
     * getCount.
     *
     * @return int
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getCount()
    {
        $count = 0;
        $text = $this->query(1)
            ->find('.nums')
            ->text();
        if (preg_match('/[\d,]+/', $text, $arr)) {
            $count = str_replace(',', '', $arr[0]);
        }
        return (int)$count;
    }

    /**
     * getBody.
     *
     * @param string $url
     *
     * @return array|\Illuminate\Support\Collection|\Tightenco\Collect\Support\Collection
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function getBody($url)
    {
        $list = $this->ql->get($url, [], $this->httpOpt)
            ->encoding('UTF-8', 'GB2312')
            ->removeHead()
            ->find('.rich-content-container')
            ->htmls()
            ->all();

        return $list;
    }


}