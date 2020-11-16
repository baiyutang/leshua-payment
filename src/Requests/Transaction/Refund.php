<?php

namespace LeshuaPayment\Requests\Transaction;

use LeshuaPayment\Config;
use LeshuaPayment\Requests\BaseRequest;
use LeshuaPayment\Utils\SignUtil;
use LeshuaPayment\Utils\StrUtil;

/**
 * 退款
 * Class Refund
 * @package LeshuaPayment\Requests\Transaction
 */
class Refund implements BaseRequest
{
    /**
     * @var array
     */
    protected $params = [];

    /**
     * Refund constructor.
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
     * 必填
     * @param int $amount 退款金额，可做部分退款
     * @return $this
     */
    public function setRefundAmount(int $amount)
    {
        $amount && $this->params['refund_amount'] = $amount;
        return $this;
    }

    /**
     * 选填
     * third_order_id 和 leshua_order_id 必填一个，优先使用 leshua_order_id
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
     * 选填
     * @param string $url 通知地址，接收乐刷退款结果通知的URL，需做Url Encode处理，需要绝对路径，确保乐刷能正确访问
     * @return $this
     */
    public function setNotifyUrl(string $url)
    {
        $url && $this->params['notify_url'] = $url;
        return $this;
    }

    /**
     * 选填
     * @param string $attach 附加数据，退款成功原样返回；注意：只能是汉字、英文字母、数字
     * @return $this
     */
    final public function setAttach(string $attach)
    {
        $attach && $this->params['attach'] = $attach;
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
        $this->params['service'] = 'unified_refund';
    }
}
