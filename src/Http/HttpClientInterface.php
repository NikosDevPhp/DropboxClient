<?php

namespace DropboxClient\Http;

/**
 * The particular interface serves to possibly change the HTTP client in the future
 * It is not complete as need additional methods(i.e. delete etc.)
 * Interface HttpClientInterface
 * @package DropboxClient\Http
 */
interface HttpClientInterface
{

    public function get(string $url): HttpClient;

    public function post(string $url, array $data): HttpClient;

    public function execute(): HttpClient;

    public function withHeaders(array $headers): HttpClient;

    public function withOptions(array $options): HttpClient;


}
