<?php

namespace LeshuaPayment;

/**
 * Class Config
 * @package LeshuaPayment
 */
class Config
{
    /**
     * TODO
     * 代理商 ID
     * 在进件相关会用到
     */
    const AGENT_ID = '1234566';

    /**
     * 接口版本号
     * 目前为固定
     */
    const API_VERSION = '2.0';

    /**
     * TODO
     * 渠道商固定key值
     */
    const KEY_AGENT = '';

    /**
     * TODO
     * 商户密钥，交易相关的签名秘钥
     */
    const KEY_TRANSACTION = '';

    /**
     * 交易相关接口
     * 生产环境
     */
    const PAYMENT_API_URL = 'https://paygate.leshuazf.com/cgi-bin/lepos_pay_gateway.cgi';

    /**
     * TODO
     * 图片上传
     * 测试环境，后期需要修改成生产环境，请与乐刷技术团队联系
     */
    const PICTURE_UPLOAD_API_URL = 'http://t-saas-mch.lepass.cn/apiv2/picture/upload';
}
