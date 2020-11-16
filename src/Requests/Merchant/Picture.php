<?php

namespace LeshuaPayment\Requests\Merchant;

use LeshuaPayment\Config;

/**
 * 图片上传
 * Class Picture
 * @package LeshuaPayment\Requests\Merchant
 */
class Picture extends BaseMerchant
{
    /**
     * @param string $path
     * @return $this
     */
    public function setFilePath(string $path)
    {
        file_exists($path) && $this->params['fileMD5'] = md5($path);
        return $this;
    }

    public function getUrl(): string
    {
        return Config::PICTURE_UPLOAD_API_URL;
    }

    public function getHeaders(): array
    {
        return [
            'Content-Type' => 'multipart/form-data;charset=UTF-8',
            'Accept' => 'application/json'
        ];
    }
}
