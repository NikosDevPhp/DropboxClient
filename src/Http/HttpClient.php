<?php

declare(strict_types=1);

namespace DropboxClient\Http;


class HttpClient implements HttpClientInterface
{
    /**
     * @var resource cUrl Resource used as the request
     */
    private $handler;

    /**
     * @var array Headers for the request
     */
    private $headers;

    /**
     * @var array The curl options to be used
     */
    private $options;

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
     * @return \DropboxClient\HttpClient An instance to allow chain calling of methods
     */
    public function get(string $url): HttpClient
    {
        curl_setopt($this->handler, CURLOPT_URL, $url);
        return $this;
    }

    public function post(string $url, array $data = []): HttpClient
    {
        curl_setopt($this->handler, CURLOPT_URL, $url);
        curl_setopt($this->handler, CURLOPT_POST, 1);
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, empty($data) ? '' : json_encode($data));
        return $this;
    }

    /**
     * Executes the HttpClient Request after applying all custom options
     * @return \DropboxClient\HttpClient An instance to allow chain calling of methods
     * @throws \Exception
     */
    public function execute(): HttpClient
    {
        $this->setDefaultOptions();
        $this->setCustomOptions();
        $response = curl_exec($this->handler);

        if (curl_errno($this->handler)) {
            $errors = curl_error($this->handler);
        }

        if (isset($errors)) {
            throw new \Exception('Curl Error');
        }


        $headersLength = curl_getinfo($this->handler, CURLINFO_HEADER_SIZE);
        $this->responseHeaders = substr($response, 0, $headersLength);
//        var_dump($this->responseHeaders);
        echo 'test' .PHP_EOL;
        $this->responseBody = substr($response, $headersLength);
//        var_dump($this->responseBody);

        curl_close($this->handler);
        return $this;
    }

    /**
     * @return string The Response Headers
     */
    public function getHeaders(): string
    {
        return $this->responseHeaders;
    }

    /**
     * @return string The Response Body
     */
    public function getBody(): string
    {
        return $this->responseBody;
    }


    private function setDefaultOptions(): void
    {
        curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER , 1);
        curl_setopt($this->handler, CURLOPT_HEADER, 1);

    }

    /**
     * @param array $options Set custom cURL Options
     * @return HttpClient
     */
    public function withOptions(array $options): HttpClient
    {
        curl_setopt_array($this->handler, $options);
    }

    public function withHeaders(array $headers = []): HttpClient
    {
        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $headers);
        return $this;
    }

    private function setCustomOptions()
    {

    }

}
