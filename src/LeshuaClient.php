<?php

namespace LeshuaPayment;

use LeshuaPayment\Requests\BaseRequest;
use LeshuaPayment\Exceptions\BadRequestException;
use LeshuaPayment\Exceptions\VerifyFailureException;
use LeshuaPayment\Utils\StrUtil;

/**
 * Class LeshuaClient
 * @package LeshuaPayment
 */
class LeshuaClient
{
    /**
     * @param BaseRequest $request
     * @return bool|false|mixed|string
     * @throws BadRequestException
     * @throws VerifyFailureException
     */
    public function send(BaseRequest $request)
    {
        $url = $request->getUrl();
        $data = $request->packageParams();
        $headers = $request->getHeaders();
        $response = $this->post($url, $data, $headers);
        if ($response === false) {
            throw new BadRequestException('请求异常');
        }
        if (isset($headers['Accept'])) {
            if (strpos($headers['Accept'], 'xml') !== false) {
                $response = StrUtil::xml2Array($response);
            }
            if (strpos($headers['Accept'], 'json') !== false) {
                $response = json_encode($response, true);
            }
        }
        if (!is_array($response)) {
            return $response;
        }
        if (method_exists($request, 'verify')
            && !empty($response['sign'])
            && $request->verify($response) === false
        ) {
            throw new VerifyFailureException('验签失败');
        }

        return $response;
    }

    /**
     * @param string $url
     * @param array $fields
     * @param array $headers 期待关联数组，方便判断，将数据格式化返回
     * @param int $timeout
     * @return bool|mixed
     */
    private function post(string $url, array $fields, array $headers = [], int $timeout = 10)
    {
        $ch = curl_init();
        if (!isset($headers[0])) {
            array_walk($headers, function (&$v, $k) {
                $v = "{$k}:{$v}";
            });
            $headers = array_values($headers);
        }
        array_push($headers, "Expect:");
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $resp = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($httpCode != 200) {
            return false;
        }

        return $resp;
    }
}
