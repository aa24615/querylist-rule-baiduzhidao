<?php


namespace Zyan\QLPlugin\Utils;

/**
 * Class StringUtil.
 *
 * @package Zyan\QLPlugin\Utils
 *
 * @author 读心印 <aa24615@qq.com>
 */
class StringUtil
{
    /**
     * iconv.
     *
     * @param string $str
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public static function iconv($str)
    {
        $encode = mb_detect_encoding($str, array("ASCII", 'UTF-8', "GB2312", "GBK", 'BIG5'));
        return iconv($encode, 'UTF-8', $str);
    }
}