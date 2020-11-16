<?php

namespace LeshuaPayment\Requests\Transaction;

use LeshuaPayment\Config;
use LeshuaPayment\Requests\BaseRequest;
use LeshuaPayment\Utils\SignUtil;
use LeshuaPayment\Utils\StrUtil;

/**
 * 银联云闪付，授权码获取用户ID
 * 使用用户授权码换取银联云闪付用户ID，使用此ID发起银联JS支付
 * 此步骤的前置条件：与公司归属银联分公司签署协议，并添加域名白名单获取用户授权码。
 * Class QueryUPUserId
 * @package LeshuaPayment\Requests\Transaction
 */
class QueryUPUserId implements BaseRequest
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * QueryUPUserId constructor.
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
     * @param string $authCode 用户授权码
     * @return $this
     */
    public function setUserAuthCode($authCode)
    {
        $authCode && $this->params['user_auth_code'] = $authCode;
        return $this;
    }

    /**
     * 必填
     * @param string $identifier 银联支付标识
     * 收款方识别HTTP请求User Agent中包含银联支付标识，格式为“UnionPay/<版本号> <App标识>”
     * 注意APP标识仅支持字母和数字。云闪付app支付不填,云闪付外app必填。示例：UnionPay/1.0 ICBCeLife
     * @return $this
     */
    public function setAppId(string $identifier)
    {
        $identifier && $this->params['app_up_identifier'] = $identifier;
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
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            'Accept' => 'text/xml'
        ];
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return Config::PAYMENT_API_URL;
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
        $this->params['service'] = 'query_userid';
    }
}
