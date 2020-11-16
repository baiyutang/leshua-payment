<?php

namespace LeshuaPayment\Requests\Transaction;

/**
 * 统一下单
 * Class UnifiedOrder
 * @package LeshuaPayment
 */
class UnifiedOrder extends BasePay
{
    /**
     * 必填
     * @param int $flag 支付类型
     * 0-支付宝扫码支付、银联二维码扫码支付；
     * 1-微信公众号、支付宝服务窗支付<原生支付>、银联js支付；
     * 2-微信公众号、支付宝服务窗支付<简易支付>；
     * 3-微信小程序支付、支付宝小程序支付
     * @return $this
     */
    public function setJspayFlag(int $flag)
    {
        is_numeric($flag) && $this->params['jspay_flag'] = $flag;
        return $this;
    }

    /**
     * 选填
     * @param string $way WXZF 微信 ZFBZF支付宝 UPSMZF 银联二维码
     * @return $this
     */
    public function setPayWay(string $way)
    {
        $this->params['pay_way'] = $way;
        return $this;
    }

    /**
     * 微信公众号、小程序、支付宝服务窗、支付宝小程序、银联JS支付必填
     * @param string $subAppId 用户子标识
     * @return $this
     */
    public function setSubAppId(string $subAppId)
    {
        $subAppId && $this->params['sub_openid'] = $subAppId;
        return $this;
    }

    /**
     * 选填
     * 如果传了会使用此 appid 进行下单；没传使用商户进件时最新配置的 appid
     * @param string $appid 公众号 appid 或小程序 appid
     * 微信公众号ID ；微信公众号支付的公众号id或微信小程序支付的小程序 appid;
     * @return $this
     */
    public function setAppId(string $appid)
    {
        $appid && $this->params['appid'] = $appid;
        return $this;
    }

    /**
     * 简易支付时必填
     * @param string $url 前台跳转地址.支付完成后，乐刷将跳转到该页面，需做 UrlEncode 处理
     * @return $this
     */
    public function setJumpUrl(string $url)
    {
        $url && $this->params['jump_url'] = $url;
        return $this;
    }

    /**
     * 银联JS支付时选填
     * @param string $url 前端跳转地址，支付成功时跳转
     * @return $this
     */
    public function setFrontUrl(string $url)
    {
        $url && $this->params['front_url'] = $url;
        return $this;
    }

    /**
     * 银联JS支付时选填
     * @param string $url 支付失败前端跳转地址，支付失败时跳转
     * @return $this
     */
    public function setFrontFailUrl(string $url)
    {
        $url && $this->params['front_fail_url'] = $url;
        return $this;
    }

    /**
     * 选填
     * @param int $second 订单有效时长，单位：秒
     * 支付宝的超时时间最小粒度为分钟，建议上送的为60的整数倍
     * @return $this
     */
    public function setOrderExpiration(int $second)
    {
        $second && $this->params['order_expiration'] = $second;
        return $this;
    }

    /**
     * 必填，此为固定值
     */
    protected function setService()
    {
        $this->params['service'] = 'get_tdcode';
        return $this;
    }
}
