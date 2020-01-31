# DropboxClient
A basic Dropbox Client in PHP

## Install
Via Composer

```bash
$ composer require nikosdevphp/dropboxclient
```

## Documentation
You can create a new HttpClient request in the following way
```bash
$client = new DropboxClient\HttpClient();
$responseBody = $client->get('https://www.google.com')
                ->execute()
                ->getBody();

```
Allow method chaining with methods:
withOptions()
withHeaders()
for building customized requests

You can initialiaze a Dropbox API call:

```bash

```
