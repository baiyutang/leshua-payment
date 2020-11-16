<?php

namespace LeshuaPayment\Utils;

use LeshuaPayment\Config;

/**
 * Class SignUtil
 */
class SignUtil
{
    /**
     * 商户相关接口的签名
     * @param array $params
     * @return string
     */
    public static function merchantSign(array $params): string
    {
        unset($params['sign']);
        ksort($params);
        $stringA = urldecode(http_build_query($params));
        $stringSignTemp = 'lepos' . $stringA . '&key=' . Config::KEY_TRANSACTION;
        return base64_encode(md5($stringSignTemp));
    }

    /**
     * 交易相关的参数签名
     * @param array $params
     * @return string
     */
    public static function transactionSign(array $params): string
    {
        unset($params['sign']);
        ksort($params);
        $stringA = urldecode(http_build_query($params));
        $stringSignTemp = $stringA . '&key=' . Config::KEY_TRANSACTION;
        return strtoupper(md5($stringSignTemp));
    }

    /**
     * 交易相关的参数签名验证
     * @param array $resp
     * @return bool
     */
    public static function transactionVerify(array $resp): bool
    {
        $sign = $resp['sign'] ?? null;
        unset($resp['sign'], $resp['leshua'], $resp['resp_code']);
        ksort($resp);
        $stringA = urldecode(http_build_query($resp));
        $stringSignTemp = $stringA . '&key=' . Config::KEY_TRANSACTION;
        return strtoupper(md5($stringSignTemp)) === $sign;
    }

    /**
     * 支付回调验签
     * @param array $resp
     * @return bool
     */
    public static function notifyVerify(array $resp): bool
    {
        $sign = $resp['sign'] ?? null;
        unset($resp['sign'], $resp['leshua'], $resp['error_code']);
        ksort($resp);
        $stringA = urldecode(http_build_query($resp));
        $stringSignTemp = $stringA . '&key=' . Config::KEY_TRANSACTION;
        return strtolower(md5($stringSignTemp)) === $sign;
    }
}
