<?php
namespace HavenShen\Larsign;

use HavenShen\Larsign\LarsignException;

/**
 * LarsignService
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
final class LarsignService
{
    private $options;

    public function __construct(array $options = array())
    {
        $this->options = $this->normalizeOptions($options);
    }

    private function normalizeOptions(array $options = array())
    {
        $options += array(
            'headerName' => 'Larsign',
            'accessKey' => 'larsignaccesskey',
            'secretKey' => 'larsignsecretkey',
        );

        return $options;
    }

    /**
     * Get header name
     *
     * @return string
     */
    public function getHeaderName()
    {
        return $this->options['headerName'];
    }

    /**
     * Get access key
     *
     * @return string
     */
    public function getAccessKey()
    {
        return $this->options['accessKey'];
    }

    /**
     * Signature
     *
     * @param string $data
     * @return string
     */
    public function sign($data)
    {
        $hmac = hash_hmac('sha1', $data, $this->options['secretKey'], true);
        return $this->options['accessKey'] . ':' . $this->base64_urlSafeEncode($hmac);
    }

    /**
     * Signature with data
     *
     * @param string $data
     * @return string
     */
    public function signWithData($data)
    {
        $encodedData = $this->base64_urlSafeEncode($data);
        return $this->sign($encodedData) . ':' . $encodedData;
    }

    /**
     * Signature request
     *
     * @param string $urlString
     * @param string $body
     * @param string $contentType
     * @param int $deadline
     * @return string
     */
    public function signRequest($urlString, $body, $contentType = null, $deadline = 0)
    {
        $url = parse_url($urlString);

        $data = '';
        if (array_key_exists('path', $url)) {
            $data = $url['path'];
        }
        if (array_key_exists('query', $url)) {
            $data .= '?' . $url['query'];
        }
        $data .= "\n";

        if ($body !== null && $contentType === 'application/x-www-form-urlencoded') {
            $data .= $body;
        }

        $data .= $deadline;

        return $this->signWithData($data);
    }

    /**
     * Split authorization signature
     *
     * @param string $authorization
     * @return list
     */
    public function splitAuthorizationLarsign($authorizationLarsign)
    {
        $authorizationLarsignArr = explode(':', $authorizationLarsign);

        $accessKey = isset($authorizationLarsignArr[0]) ? $authorizationLarsignArr[0] : '';
        $hmacSha1Str = isset($authorizationLarsignArr[1]) ? $authorizationLarsignArr[1] : '';
        $encodedStr = isset($authorizationLarsignArr[2]) ? $authorizationLarsignArr[2] : '';

        $url = '';
        $body = '';
        $deadline = 0;

        if (! empty($encodedStr)) {
            $encodedStrArr = preg_split('/[;\r\n]+/s', $this->base64_urlSafeDecode($encodedStr));

            $url = isset($encodedStrArr[0]) ? $encodedStrArr[0] : '';
            $deadline = isset($encodedStrArr[1]) ? (int)$encodedStrArr[1] : 0;
        }

        return [
            $accessKey,
            $hmacSha1Str,
            $encodedStr,
            $url,
            $body,
            $deadline
        ];
    }

    /**
     * Check auth signature
     *
     * @param \Illuminate\Http\Request  $request
     * @return bool
     */
    public function check($request)
    {
        $contentType = $request->header('content-type');

        $authorizationLarsign = $request->header($this->options['headerName']);

        list(
            $accessKey,
            $hmacSha1Str,
            $encodedStrArr,
            $url,
            $body,
            $deadline
        ) = $this->splitAuthorizationLarsign($authorizationLarsign);

        if (time() > $deadline) {
            return false;
        }

        if (! $this->verifyCallback($contentType, $authorizationLarsign, $url, $body, $deadline)) {
            return false;
        }

        return true;

    }

    /**
     * Verify callback
     *
     * @param string $contentType
     * @param string $authorizationLarsign
     * @param string $url
     * @param string $body
     * @param int $bodeadlinedy
     * @return bool
     */
    public function verifyCallback($contentType, $authorizationLarsign, $url, $body, $deadline)
    {
        $authorizationLarsignRs = $this->options['headerName'] .' '. $this->signRequest($url, $body, $contentType, $deadline);

        return $authorizationLarsign === $authorizationLarsignRs;
    }

    /**
     * Urlsafe base64 encode
     *
     * @param string $data
     *
     * @return string
     */
    public function base64_urlSafeEncode($data)
    {
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }

    /**
     * Urlsafe base64 decode
     *
     * @param string $str
     *
     * @return string
     */
    public function base64_urlSafeDecode($str)
    {
        $find = array('-', '_');
        $replace = array('+', '/');
        return base64_decode(str_replace($find, $replace, $str));
    }
}
