# DropboxClient
A basic Dropbox Client in PHP

## Installation
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
$client = new DropboxClient\DropboxClient({access_token});
```
and then call the corresponding method like:
```bash
$client->createFolder('/Homework');
$client->downloadFile('/app.ico');
$client->listFolder('/Homework');
```

Tested on actual created Dropbox Developer Account and worked
as of 02/02/2020
