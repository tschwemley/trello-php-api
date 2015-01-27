trello-php-API
==============

A PHP wrapper for working with the Trello API.

## Requirements ##
- PHP 5.3.0 or greater
- cURL Libarary
- Trello API developer key (can be registered at https://trello.com/1/appKey/generate)
- OAuth for php (included in this repo)

## Installation ##
This package can be installed as a stand alone or with composer.

If using Composer add the following to your project composer.json:

```json
"require": {
	...
	"tschwemley/trello-php-api": "dev-master"
}
```

Finally, update Composer:

```
composer update
```

## Usage ##
After obtaining an authentican token you may make authenticated calls by using the following method:

```php
$trello = new tschwemley\trello\Trell0(array(
  'clientKey'    => CLIENT_KEY_HERE
  'clientSecret' => CLIENT_SECRET_HERE
);

$result = $trello->apiCall(array('boards', '4d5ea62fd76aa1136000000c'));
```

For more detailed examples about obtaining OAuth verification please check the OAuth examples under the examples folder.

## Getting Help ##
If you need help please contact me at me@tylerschwemley.com
