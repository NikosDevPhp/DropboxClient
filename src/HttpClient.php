<?php

namespace DropboxClient;


class HttpClient
{
    /**
     * @var resource cUrl Resource used as the request
     */
    private $handler;

    /**
     * @var string Url to be used
     */
    private $url;

    /**
     * @var array Headers for the request
     */
    private $headers;

    /**
     * @var array The curl options to be used
     */
    private $options;

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
     * get method
     * @param $client resource
     * @return $client resource
     */
    public function get(string $url)
    {
        curl_setopt($this->handler, CURLOPT_URL, $url);
        return $this;
    }

    public function post(string $url, array $data)
    {
        curl_setopt($this->handler, CURLOPT_POSTFIELDS, $data);
    }

    /**
     * @return array
     */
    public function execute()
    {

        $this->setDefaultOptions();
        $this->setCustomOptions();
        $response = curl_exec($this->handler);

        $headersLength = curl_getinfo($this->handler, CURLINFO_HEADER_SIZE);
        $headers = substr($response, 0, $headersLength);
        $body = substr($response, $headersLength);

        return [
            'headers' => $headers,
            'body' => $body
        ];
    }

    public function setOptions()
    {

    }

    private function setDefaultOptions()
    {
        curl_setopt($this->handler, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($this->handler, CURLOPT_RETURNTRANSFER , 1);
        curl_setopt($this->handler, CURLOPT_HEADER, 1);

    }

    public function withOptions(array $options)
    {
        curl_setopt_array($this->handler, $options);
    }

    public function withHeaders($headers)
    {
        curl_setopt($this->handler, CURLOPT_HTTPHEADER, $headers);
    }

    private function setCustomOptions()
    {

    }

}
