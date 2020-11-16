<?php

namespace LeshuaPayment\Requests\Transaction;

use LeshuaPayment\Config;
use LeshuaPayment\Requests\BaseRequest;
use LeshuaPayment\Utils\SignUtil;
use LeshuaPayment\Utils\StrUtil;

/**
 * 退款查询
 * Class QueryRefund
 * @package LeshuaPayment\Requests\Transaction
 */
class QueryRefund implements BaseRequest
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * QueryRefund constructor.
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
     * 选填
     * @param string $id 商户订单号
     * @return $this
     */
    public function setThirdOrderId(string $id)
    {
        $id && $this->params['third_order_id'] = $id;
        return $this;
    }

    /**
     * 选填
     * third_order_id和leshua_order_id必填一个，优先使用leshua_order_id
     * @param string $id 乐刷订单号
     * @return $this
     */
    public function setLeshuaOrderId(string $id)
    {
        $id && $this->params['leshua_order_id'] = $id;
        return $this;
    }

    /**
     * 必填
     * @param string $id 商户退款单号，可以包含字母（不能有“_”等特殊字符），确保同一商户下唯一
     * @return $this
     */
    public function setMerchantRefundId(string $id)
    {
        $id && $this->params['merchant_refund_id'] = $id;
        return $this;
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
     * @param array $resp
     * @return bool
     */
    public function verify(array $resp): bool
    {
        return SignUtil::transactionVerify($resp);
    }

    /**
     * 此为固定值
     */
    protected function setService()
    {
        $this->params['service'] = 'unified_query_refund';
    }
}
