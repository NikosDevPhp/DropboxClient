<?php

declare(strict_types=1);


namespace DropboxClient;

use DropboxClient\Http\HttpClient;

class DropboxClient implements DropboxCLientInterface
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
     * DropboxClient constructor, if a DI container with auto wiring could be used in the current project the client initialization
     * should be done to the HttpClientInterface not directly to the HttpClient
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
        $this->client = new HttpClient();
    }

    /** Creates a folder
     * https://www.dropbox.com/developers/documentation/http/documentation#files-create_folder
     * @param string $path The folder directory to create
     * @param bool $autorename
     * @return array Contains the response body of the API call
     * @throws \Exception
     */
    public function createFolder(string $path, bool $autorename = false): array
    {
        $this->uri = self::RPC_ENDPOINT . 'files/create_folder_v2';
        $this->headers = $this->getHeaders($this->mapHeaders());
        $data = [
            'path' => $path,
            'autorename' => $autorename
        ];

        return json_decode(
                        $this->client
                        ->post($this->uri, $data)
                        ->withHeaders($this->headers)
                        ->execute()
                        ->getBody(),
              true);
        
    }

    /**
     * Lists items in a folder, can be used with options as listed in the documentation
     * https://www.dropbox.com/developers/documentation/http/documentation#files-list_folder
     * @param string $path
     * @param bool $recursive
     * @param bool $includeMediaInfo
     * @param bool $includeDeleted
     * @param bool $includeHasExplicitSharedMembers
     * @param bool $includeMountedFolders
     * @param bool $includeNonDownloadableFiles
     * @return array Contains the response body of the API call
     * @throws \Exception
     */
    public function listFolder(string $path, bool $recursive = false,  bool $includeMediaInfo = false,bool $includeDeleted = false,
                               bool $includeHasExplicitSharedMembers = false, bool $includeMountedFolders = true, bool $includeNonDownloadableFiles = true): array
    {
        $this->uri = self::RPC_ENDPOINT . 'files/list_folder';
        $this->headers = $this->getHeaders($this->mapHeaders());
        $data = [
            'path' => $path,
            'recursive' => $recursive,
            'include_media_info' => $includeMediaInfo,
            'include_deleted' => $includeDeleted,
            'include_has_explicit_shared_members' => $includeHasExplicitSharedMembers,
            'include_mounted_folders' => $includeMountedFolders,
            'include_non_downloadable_files' => $includeNonDownloadableFiles
        ];

        return json_decode(
                    $this->client
                    ->post($this->uri, $data)
                    ->withHeaders($this->headers)
                    ->execute()
                    ->getBody()
        ,true);
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
     * @return array The actual headers after Header mapping
     */
    private function mapHeaders(array $data = []): array
    {
        return [
            'https://api.dropboxapi.com/2/files/create_folder_v2' =>
            [
                'Content-Type: application/json'
            ],
            'https://content.dropboxapi.com/2/files/download' =>
            [
                'Dropbox-API-Arg: ' . json_encode(['path' => empty($data) ? '' : $data[0]]),
                'Content-Type: text/plain'
            ],
            'https://api.dropboxapi.com/2/files/list_folder' =>
            [
                'Content-Type: application/json'
            ]
        ][$this->uri];
    }
}
