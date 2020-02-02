<?php

declare(strict_types=1);

namespace DropboxClient\Http;

/**
 * The custom HTTP Client
 * This Client is not by any means complete, it just serves the basic functionality of the project
 * Class HttpClient
 * @package DropboxClient\Http
 */
class HttpClient implements HttpClientInterface
{
    /**
     * @var resource cUrl Resource used as the request
     */
    private $handler;

    /**
     * @var string The headers of the Response
     */
    private $responseHeaders;

    /**
     * @var string The body of the Response
     */
    private $responseBody;

    /**
     * HttpClient constructor.
     */
    public function __construct()
    {
        if(!is_resource($this->handler)) {
            $this->handler = curl_init();
        } else {
            curl_reset($this->handler);
        }
    }

    /**
     * GET method configuration
     * @param string $url The url to GET
     * @return HttpClient An instance to allow chain calling of methods
     */
    public function get(string $url): HttpClient
    {
        curl_setopt($this->handler, CURLOPT_URL, $url);
        return $this;
    }

    /**
     * POST method configuration
     * @param string $url The uri resource
     * @param array $data Optional data in the post body
     * @return HttpClient An instance to allow chain calling of methods
     */
    public function post(string $url, array $data = []): HttpClient
    {
        curl_setopt($this->handler, CURLOPT_URL, $url);
        curl_setopt($this->handler, CURLOPT_POST, 1);
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, empty($data) ? '' : json_encode($data));
        return $this;
    }

    /**
     * Executes the HttpClient Request after applying all custom options
     * @return HttpClient An instance to allow chain calling of methods
     * @throws \Exception
     */
    public function execute(): HttpClient
    {
        $this->setDefaultOptions();
        $response = curl_exec($this->handler);

        if (curl_errno($this->handler)) {
            $errors = curl_error($this->handler);
        }

        if (isset($errors)) {
            throw new \Exception('Curl Error');
        }

        $headersLength = curl_getinfo($this->handler, CURLINFO_HEADER_SIZE);
        $this->responseHeaders = substr($response, 0, $headersLength);
        $this->responseBody = substr($response, $headersLength);

        return $this;
    }

    /**
     * Get Response Headers
     * @return string The Response Headers
     */
    public function getHeaders(): string
    {
        return $this->responseHeaders;
    }

    /**
     * Get Response Body
     * @return string The Response Body
     */
    public function getBody(): string
    {
        return $this->responseBody;
    }


    /**
     * Sets the default Options for all requests
     */
    private function setDefaultOptions(): void
    {
        curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER , 1);
        curl_setopt($this->handler, CURLOPT_HEADER, 1);

    }

    /**
     * Sets custom cURL Options - not used here provided for completeness
     * @param array $options the custom Options
     * @return HttpClient
     */
    public function withOptions(array $options): HttpClient
    {
        curl_setopt_array($this->handler, $options);
        return $this;
    }

    /**
     * Sets custom Headers to request
     * @param array $headers The custom headers
     * @return HttpClient
     */
    public function withHeaders(array $headers = []): HttpClient
    {
        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $headers);
        return $this;
    }


}
