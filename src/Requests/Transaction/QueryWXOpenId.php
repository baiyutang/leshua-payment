<?php

namespace LeshuaPayment\Requests\Transaction;

use LeshuaPayment\Config;
use LeshuaPayment\Requests\BaseRequest;
use LeshuaPayment\Utils\SignUtil;
use LeshuaPayment\Utils\StrUtil;

/**
 * 微信，授权码查询用户openid
 * Class QueryOpenId
 * @package LeshuaPayment\Requests\Transaction
 */
class QueryWXOpenId implements BaseRequest
{
    protected $params = [];

    /**
     * QueryWXOpenId constructor.
     */
    public function __construct()
    {
        $this->setService();
    }

    /**
     * 必填
     * @param string $id 乐刷商户号
     * @return $this
     */
    public function setMerchantId(string $id)
    {
        $id && $this->params['merchant_id'] = $id;
        return $this;
    }

    /**
     * 必填
     * @param string $authCode 微信授权码
     * @return $this
     */
    public function setAuthCode($authCode)
    {
        $authCode && $this->params['auth_code'] = $authCode;
        return $this;
    }

    /**
     * 必填
     * @param string $appId 微信公众号ID
     * @return $this
     */
    public function setAppId($appId)
    {
        $appId && $this->params['appid'] = $appId;
        return $this;
    }

    /**
     * @return array
     */
    public function packageParams(): array
    {
        $tmp = $this->params;
        $tmp['nonce_str'] = StrUtil::getNonceStr();
        $tmp['sign'] = SignUtil::transactionSign($tmp);
        return $tmp;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Config::PAYMENT_API_URL;
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Accept:text/xml'
        ];
    }

    /**
     * @param array $resp
     * @return bool
     */
    public function verify(array $resp): bool
    {
        return SignUtil::transactionVerify($resp);
    }

    /**
     * 固定值
     */
    protected function setService()
    {
        $this->params['service'] = 'query_openid';
    }
}
