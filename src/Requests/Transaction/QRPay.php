<?php

namespace LeshuaPayment\Requests\Transaction;

/**
 * 条码支付
 * Class QRPay
 */
class QRPay extends BasePay
{
    /**
     * 必填
     * @param string $authCode
     * @return $this
     */
    public function setAuthCode(string $authCode)
    {
        $this->params['service'] = 'auth_code';
        return $this;
    }

    /**
     * 必填，此为固定值
     */
    protected function setService()
    {
        $this->params['service'] = 'upload_authcode';
    }
}
