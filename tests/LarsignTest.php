<?php

namespace Tests;

use Larsign;

/**
 * LarsignTest
 *
 * @author    Haven Shen <havenshen@gmail.com>
 * @copyright    Copyright (c) Haven Shen
 */
class LarsignTest extends TestCase
{

    public function testWebRoute()
    {
        $url = $this->baseUrl.$this->webRoute;

        $authorization = $this->authorizationSignature($url);

        $response = $this->withHeaders([
            Larsign::getHeaderName() => $authorization,
        ])->get($url);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testApiRoute()
    {
        $url = $this->baseUrl.$this->apiRoute;
        $authorization = $this->authorizationSignature($url);

        $response = $this->withHeaders([
            Larsign::getHeaderName() => $authorization,
        ])->json('GET', $url);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function authorizationSignature($url, $body = null, $contentType = null)
    {
        $authorization = Larsign::getHeaderName() .' '. Larsign::signRequest($url, $body, $contentType, time() + 120);
        return $authorization;
    }
}