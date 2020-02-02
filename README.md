# DropboxClient
A basic Dropbox Client in PHP

## Installation
Git clone this repository and use it.
No need to install composer dependencies, they are just here for clarification.
Not pushed on packagist.org, can be done by easily by Github Hooks


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
as of 02/02/2020.
Contact me on niktriant89@gmail.com for your advise, comments etc.
