<?php
/**
 * Created by PhpStorm.
 * User: mher
 * Date: 10/7/17
 * Time: 5:25 PM
 */

namespace Tenweb_Manager {

    class Amazon
    {

        private $key;
        private $secret;
        private $token;
        private $file;

        private $service = 's3';
        private $region = 'us-west-2';
        private $bucket = TENWEB_S3_BUCKET;
        private $payload = "";//UNSIGNED-PAYLOAD

        private $signedHeaders = "";
        private $canonicalHeaders = "";

        private $authHeader = "";
        private $headers = array();

        private $scope;
        private $longDate;
        private $shortDate;
        private $host;

        private $signature = "";

        public function __construct($key, $secret, $token, $file, $bucket = null)
        {
            $this->key = $key;
            $this->secret = $secret;
            $this->token = $token;
            $this->file = ltrim($file, '/');
            if ($bucket !== null) {
                $this->bucket = $bucket;
            }
            $this->host = $this->bucket . '.' . $this->service . '.' . $this->region . '.amazonaws.com';

        }

        public function getRequestData()
        {

            $this->longDate = gmdate('Ymd\THis\Z');
            $this->shortDate = substr($this->longDate, 0, 8);

            $this->createScope();
            $this->setHeaders();

            $this->calculateSignature();


            $this->setAuthorizationHeader();

            $headers = $this->headers;
            $headers['Authorization'] = $this->authHeader;
            $result = array(
                'headers' => $headers,
                'url'     => 'https://' . $this->host . '/' . $this->file
            );

            return $result;
        }

        private function setAuthorizationHeader()
        {

            $credentials = $this->key . '/' . $this->scope;
            $this->authHeader = "AWS4-HMAC-SHA256 "
                . "Credential=" . $credentials . ", "
                . "SignedHeaders=" . $this->signedHeaders . ", "
                . "Signature=" . $this->signature;
        }

        private function calculateSignature()
        {
            $toSign = $this->getStringToSign();

            $dateKey = hash_hmac('sha256', $this->shortDate, "AWS4" . $this->secret, true);
            $regionKey = hash_hmac('sha256', $this->region, $dateKey, true);
            $serviceKey = hash_hmac('sha256', $this->service, $regionKey, true);
            $signingKey = hash_hmac('sha256', 'aws4_request', $serviceKey, true);

            $signature = hash_hmac('sha256', $toSign, $signingKey);
            $this->signature = $signature;
        }

        private function getStringToSign()
        {
            $canonical = $this->getCanonicalRequest();

            $strToSign = "AWS4-HMAC-SHA256" . "\n"
                . $this->longDate . "\n"
                . $this->scope . "\n"
                . hash('sha256', $canonical);

            return $strToSign;
        }

        private function getCanonicalRequest()
        {

            $method = "GET";
            $canonicalURI = '/' . $this->file;
            $canonicalQueryString = "";
            $canonicalHeaders = $this->canonicalHeaders;
            $signedHeaders = $this->signedHeaders;
            $payload = hash('sha256', $this->payload);

            $canonical = $method . "\n" . $canonicalURI . "\n" . $canonicalQueryString . "\n" . $canonicalHeaders . "\n" . $signedHeaders . "\n" . $payload;

            return $canonical;
        }

        private function setHeaders()
        {
            $this->headers = array(
                'host'                 => $this->host,
                'x-amz-content-sha256' => hash('sha256', $this->payload),
                'x-amz-date'           => $this->longDate,
                'x-amz-security-token' => $this->token
            );

            $this->canonicalHeaders = "";
            $this->signedHeaders = "";

            foreach ($this->headers as $header => $value) {
                $this->canonicalHeaders .= strtolower($header) . ":" . trim($value) . "\n";
                $this->signedHeaders .= $header . ';';
            }

            $this->signedHeaders = rtrim($this->signedHeaders, ';');
        }

        private function createScope()
        {
            $this->scope = $this->shortDate . "/" . $this->region . "/" . $this->service . "/aws4_request";
        }

    }
}