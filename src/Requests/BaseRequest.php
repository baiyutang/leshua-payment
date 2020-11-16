<?php

namespace LeshuaPayment\Requests;

/**
 * @package LeshuaPayment
 */
interface BaseRequest
{
    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return array
     */
    public function packageParams(): array;

    /**
     * 接口请求头设置
     * @return array
     */
    public function getHeaders(): array;
}
