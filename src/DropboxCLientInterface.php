<?php

namespace DropboxClient;

/**
 * Interface that contains the resources of the API
 * Different implementations may vary so header helper functions do not need to be implemented
 * Interface DropboxCLientInterface
 * @package DropboxClient
 */
interface DropboxCLientInterface
{

    public function createFolder(string $path, bool $autorename): array;

    public function listFolder(string $path, bool $recursive = false,  bool $includeMediaInfo = false,bool $includeDeleted = false,
                               bool $includeHasExplicitSharedMembers = false, bool $includeMountedFolders = true, bool $includeNonDownloadableFiles = true): array;

    public function downloadFile(string $path): string;
}
