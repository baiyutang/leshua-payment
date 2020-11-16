<?php

namespace LeshuaPayment\Requests\Transaction;

use LeshuaPayment\Config;
use LeshuaPayment\Requests\BaseRequest;
use LeshuaPayment\Utils\SignUtil;
use LeshuaPayment\Utils\StrUtil;

/**
 * 获取微信刷脸凭证
 * Class GetWxPayFaceAuthInfo
 */
class GetWxPayFaceAuthInfo implements BaseRequest
{

    /**
     * 用于接收设定的参数值
     * @var array
     */
    protected $params = [];


    /**
     * 必填
     * @param string $id 商户号，在乐刷平台生成的商户编号
     * @return $this
     */
    public function setMerchantId($id)
    {
        $id && $this->params['merchant_id'] = $id;
        return $this;
    }

    /**
     * 必填
     * @param string $id 微信子商户号
     * @return $this
     */
    public function setSubMchId(string $id)
    {
        $id && $this->params['sub_mch_id'] = $id;
        return $this;
    }

    /**
     * 如要传入就必须要与 sub_mch_id 必须匹配
     * @param string $id 商户公众账号 ID
     * @return $this
     */
    public function setSubAppId(string $id)
    {
        $id && $this->params['sub_appid'] = $id;
        return $this;
    }

    /**
     * 必填
     * @param string $data 初始化数据，由微信人脸SDK的接口返回
     * @return $this
     */
    public function setRawData(string $data)
    {
        $data && $this->params['raw_data'] = $data;
        return $this;
    }

    /**
     * 必填
     * @param string $storeId 门店编号，由商户定义， 各门店唯一
     * @return $this
     */
    public function setStoreId(string $storeId)
    {
        $storeId && $this->params['store_id'] = $storeId;
        return $this;
    }

    /**
     * 必填
     * @param string $storeName 门店名称，由商户定义，可用于展示
     * @return $this
     */
    public function setStoreName(string $storeName)
    {
        $storeName && $this->params['store_name'] = $storeName;
        return $this;
    }

    /**
     * 必填
     * @param string $deviceId 终端设备编号，由商户定义
     * @return $this
     */
    public function setDeviceId(string $deviceId)
    {
        $deviceId && $this->params['device_id'] = $deviceId;
        return $this;
    }

    /**
     * 可选
     * @param int $activeType 活动类型 0：不参加活动 1：银联绿洲活动
     * @return $this
     */
    public function setActiveType(int $activeType)
    {
        is_numeric($activeType) && $this->params['active_type'] = $activeType;
        return $this;
    }

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
}
