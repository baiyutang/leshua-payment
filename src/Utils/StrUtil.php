<?php

namespace LeshuaPayment\Utils;

/**
 * Class StrUtil
 */
class StrUtil
{
    /**
     * 构建请求流水号
     * 尽量符合乐刷的建议格式
     */
    public static function getReqSerialNo()
    {
        return date("YmdHis")
            . substr(microtime(), 2, 6)
            . self::getNonceStr(4);
    }

    /**
     * 获取指定长度随机字符串
     * 包括数字、大写字母、小写字母
     * @param int $length
     * @return string
     */
    public static function getNonceStr(int $length = 16)
    {
        if (!is_numeric($length) || $length < 1) {
            $length = 16;
        }
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charLen = strlen($characters);
        $nonceStr = '';
        for ($i = 0; $i < $length; $i++) {
            $nonceStr .= $characters[rand(0, $charLen - 1)];
        }
        return $nonceStr;
    }

    /**
     * xml 格式内容转成 array
     * @param $xml
     * @return bool|mixed
     */
    public static function xml2Array($xml)
    {
        if (!$xml) {
            return false;
        }
        // 检查xml是否合法
        $xml_parser = xml_parser_create();
        if (!xml_parse($xml_parser, $xml, true)) {
            xml_parser_free($xml_parser);
            return false;
        }
        //将XML转为array
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $data = json_decode(
            json_encode(
                simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)
            ),
            true
        );
        if (json_last_error() === JSON_ERROR_NONE) {
            return $data;
        }
        return false;
    }
}
