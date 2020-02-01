<?php

declare(strict_types=1);


namespace DropboxClient;

use DropboxClient\Http\HttpClientInterface;
use DropboxClient\Http\HttpClient;

class DropboxClient
{

    const RPC_ENDPOINT = 'https://api.dropboxapi.com/2/';

    const CONTENT_UPLOAD_ENDOPOINT = 'https://content.dropboxapi.com/2/';

    /**
     * @var HttpClient Instance of HttpClient Class
     */
    private $client;

    /**
     * @var string Access token for the application
     */
    private $token;

    /**
     * @var string $uri The current uri
     */
    private $uri;


    private $headers;

    /**
     * DropboxClient constructor, contains
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
        $this->client = new HttpClient();
    }

    /**
     *
     * @param string $path The folder directory to create
     * @return array Contains the response body of the API call
     * @throws \Exception
     */
    public function createFolder(string $path): array
    {

        $data = [
            'path' => $path,
            'autorename' => false
        ];

        return json_decode(
                        $this->client
                        ->post(self::RPC_ENDPOINT . 'files/create_folder_v2', $data)
                        ->withHeaders($this->getHeaders([
                            'Content-Type: application/json']))
                        ->execute()
                        ->getBody(),
              true);
        
    }

    /**
     * Downloads the specified file
     * @param string $path The uri of the file to download
     * @return string The response Body in array format
     * @throws \Exception
     */
    public function downloadFile(string $path): string
    {
        $this->uri = self::CONTENT_UPLOAD_ENDOPOINT . 'files/download';
        $this->headers = $this->getHeaders($this->mapHeaders([$path]));
        return $this->client
                        ->post($this->uri)
                        ->withHeaders($this->headers)
                        ->execute()
                        ->getBody();
    }
    


    /**
     * Gets the Default Headers for the request, merged with user defined ones
     * @param array $headers User - defined headers
     * @return array The result is the user defined headers on top of the default
     */
    private function getHeaders(array $headers = []): array
    {
        return $this->headers = array_merge([
            'Authorization: Bearer ' . $this->token
        ], $headers);

    }

    /**
     * Maps required headers to the corresponding request
     * Only Authorization header is required in every request.
     * As a rule, content uri require 'Dropbox-API-Arg' header
     * but this is not the case for all methods (e.g. get_thumbnail_batch)
     * So a URI to Header mapping is required
     *
     * @param array $data Customized data for each header type usually converted to json, in this example it is a string but generally type array may be used
     * @return
     */
    private function mapHeaders(array $data): array
    {
        return [
            'https://api.dropboxapi.com/2/files/create_folder_v2' =>
            [
                'Content-Type: application/json'
            ],
            'https://content.dropboxapi.com/2/files/download' =>
            [
                'Dropbox-API-Arg: ' . json_encode(['path' => $data[0]]),
                'Content-Type: text/plain'
            ]
        ][$this->uri];
    }
}
