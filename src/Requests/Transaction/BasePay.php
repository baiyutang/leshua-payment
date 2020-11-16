<?php

namespace LeshuaPayment\Requests\Transaction;

use LeshuaPayment\Config;
use LeshuaPayment\Requests\BaseRequest;
use LeshuaPayment\Utils\SignUtil;
use LeshuaPayment\Utils\StrUtil;

/**
 * 支付接口公用类
 * Class BasePay
 * @package LeshuaPayment
 */
abstract class BasePay implements BaseRequest
{
    /**
     * 用于接收设定的参数值
     * @var array
     */
    protected $params = [];

    /**
     * BasePay constructor.
     */
    public function __construct()
    {
        $this->setService();
    }

    /**
     * 必填
     * @param string $id 商户号，在乐刷平台生成的商户编号
     * @return $this
     */
    final public function setMerchantId(string $id)
    {
        $id && $this->params['merchant_id'] = $id;
        return $this;
    }

    /**
     * 必填
     * @param string $orderId 商户订单号，商户内部订单号，可以包含字母：确保同一个商户下唯一
     * @return $this
     */
    final public function setThirdOrderId(string $orderId)
    {
        $orderId && $this->params['third_order_id'] = $orderId;
        return $this;
    }

    /**
     * 必填
     * @param int $amount 订单金额，单位：分，不能为零或负数
     * @return $this
     */
    final public function setAmount(int $amount)
    {
        $amount && $amount > 0 && $this->params['amount'] = $amount;
        return $this;
    }

    /**
     * 选填
     * @param string $url 通知地址，支付成功后会回调此地址告知消息
     * 接收乐刷通知（支付结果通知）的URL，需做UrlEncode 处理，需要绝对路径，确保乐刷能正确访问，若不需要回调请忽略
     * @return $this
     */
    final public function setNotifyUrl(string $url)
    {
        $url && $this->params['notify_url'] = $url;
        return $this;
    }

    /**
     * 选填
     * @param string $ip 用户IP地址
     * @return $this
     */
    final public function setClientIp(string $ip)
    {
        $ip && $this->params['client_ip'] = $ip;
        return $this;
    }

    /**
     * 选填
     * @param string $body 商品描述，不能包含回车换行等特殊字符
     * @return $this
     */
    final public function setBody(string $body)
    {
        $body && $this->params['body'] = $body;
        return $this;
    }

    /**
     * 选填
     * @param string $no 商户门店编号，只能是汉字、英文字母、数字
     * @return $this
     */
    final public function setShopNo(string $no)
    {
        $no && $this->params['shop_no'] = $no;
        return $this;
    }

    /**
     * 选填
     * @param string $no 商户终端编号，只能是汉字、英文字母、数字
     * @return $this
     */
    final public function setPosNo(string $no)
    {
        $no && $this->params['pos_no'] = $no;
        return $this;
    }

    /**
     * 选填
     * @param string $attach 附加数据，下单成功原样返回；注意：只能是汉字、英文字母、数字
     * @return $this
     */
    final public function setAttach(string $attach)
    {
        $attach && $this->params['attach'] = $attach;
        return $this;
    }

    /**
     * 选填
     * @see https://www.yuque.com/leshuazf/doc/fz#7V1zg
     * @param int $limitPay 支付限制，1：禁止使用信用卡；0或者不填：不限制
     * @return $this
     */
    final public function setLimitPay(int $limitPay)
    {
        $this->params['limit_pay'] = $limitPay;
        return $this;
    }

    /**
     * 选填
     * @param int $royalty 交易分账标识：需分账-1；不分账-0
     * 注：如果未传该字段默认按，不分账处理
     * @return $this
     */
    final public function setRoyalty(int $royalty)
    {
        $this->params['royalty'] = $royalty;
        return $this;
    }

    /**
     * 选填
     * @param string $goodsTag 微信订单优惠标记，透传给微信
     * @return $this
     */
    final public function setGoodsTag(string $goodsTag)
    {
        $goodsTag && $this->params['goods_tag'] = $goodsTag;
        return $this;
    }

    /**
     * 选填
     * @param string $goodsTag 微信商品详情，按微信单品优惠券格式传递透传给微信;请做UrlEncode
     * @return $this
     */
    final public function setGoodsDetail(string $goodsTag)
    {
        $goodsTag && $this->params['goods_detail'] = $goodsTag;
        return $this;
    }

    /**
     * 选填
     * @param int $num 支付宝花呗分期数，支持3、6、12期
     * @return $this
     */
    final public function setHbFqNum(int $num)
    {
        $num && $this->params['hb_fq_num'] = $num;
        return $this;
    }

    /**
     * 选填
     * @see https://www.yuque.com/leshuazf/doc/zhifujiaoyi#5u5k5
     * @param string $bizParams 支付宝业务拓展参数
     * 当前可透传支付宝扫码点餐的参数，business_params、goods_detail、extend_params
     * @return $this
     */
    final public function setExtendBusinessParams(string $bizParams)
    {
        $bizParams && $this->params['extend_business_params'] = $bizParams;
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

    abstract protected function setService();
}
