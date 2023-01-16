<?php

namespace Zadarma_API;

use Exception;

class Client
{
    const PROD_URL = 'https://api.zadarma.com';
    const SANDBOX_URL = 'https://api-sandbox.zadarma.com';

    private $url;
    private $key;
    private $secret;
    private $httpCode;
    private $limits = [];

    /**
     * @param $key
     * @param $secret
     * @param bool|false $isSandbox
     */
    public function __construct($key, $secret, $isSandbox = false)
    {
        $this->url = $isSandbox ? static::SANDBOX_URL : static::PROD_URL;
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * @param $method - API method, including version number
     * @param array $params - Query params
     * @param string $requestType - (get|post|put|delete)
     * @param string $format - (json|xml)
     *
     * @return mixed
     * @throws Exception
     *
     */
    public function call($method, $params = [], $requestType = 'get', $format = 'json')
    {
        if (!is_array($params)) {
            throw new ApiException('Query params must be an array.');
        }

        $type = strtoupper($requestType);
        if (!in_array($type, ['GET', 'POST', 'PUT', 'DELETE'])) {
            $type = 'GET';
        }
        $params['format'] = $format;

        $options = [
            CURLOPT_URL => $this->url . $method,
            CURLOPT_CUSTOMREQUEST => $type,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_HEADERFUNCTION => [$this, 'parseHeaders'],
            CURLOPT_HTTPHEADER => $this->getAuthHeader($method, $params),
        ];

        $ch = curl_init();

        if ($type == 'GET') {
            $options[CURLOPT_URL] = $this->url . $method . '?' . $this->httpBuildQuery($params);
        } else {
            $options[CURLOPT_POST] = true;
            if (array_filter($params, 'is_object')) {
                $options[CURLOPT_POSTFIELDS] = $params;
            } else {
                $options[CURLOPT_POSTFIELDS] = $this->httpBuildQuery($params);
            }
        }

        curl_setopt_array($ch, $options);

        $response = curl_exec($ch);
        $error = curl_error($ch);

        $this->httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if ($error) {
            throw new ApiException($error);
        }

        return $response;
    }

    /**
     * @return int
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @return array
     */
    public function getLimits()
    {
        return $this->limits;
    }

    /**
     * @param $signatureString
     * @return string
     */
    public function encodeSignature($signatureString)
    {
        return base64_encode(hash_hmac('sha1', $signatureString, $this->secret));
    }

    /**
     * @param $method
     * @param $params
     *
     * @return array
     */
    private function getAuthHeader($method, $params)
    {
        $params = array_filter($params, function ($a) {
            return !is_object($a);
        });
        ksort($params);
        $paramsString = $this->httpBuildQuery($params);
        $signature = $this->encodeSignature($method . $paramsString . md5($paramsString));

        return ['Authorization: ' . $this->key . ':' . $signature];
    }

    /**
     * @param $curl
     * @param $line
     *
     * @return int
     */
    private function parseHeaders($curl, $line)
    {
        if (preg_match('/^X-RateLimit-([a-z]+):\s([0-9]+)/i', $line, $match)) {
            $this->limits[$match[1]] = (int)$match[2];
        }

        return strlen($line);
    }

    /**
     * Build HTTP query
     *
     * @param array $params
     *
     * @return string
     */
    private function httpBuildQuery($params = [])
    {
        return http_build_query($params, '', '&', PHP_QUERY_RFC1738);
    }
}
