<?php
session_start();

// Create a new instance of the Trello class
$trello = new tschwemley\trello\Trello(array(
	'clientKey'		 => null,
	'clientSecret'	 => null,
));

// Get the request tokens based on consumer and secret keys. Store them in token array.
$token = $trello->getRequestToken('http://dotaresource.com/trelloAPI/tests/callback.php');

// Store the tokens into a session so we can use them during callback
$_SESSION['oauth_token'] = $token['oauth_token'];
$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];

// Get the authorize URL
$data = $trello->getAuthorizeUrl($token['oauth_token'], array(
	'name'		 => 'medhub',
	'expiration' => 'never',
	'scope'		 => 'read,write',
));

// Direct user to Trello 'Allow Access' screen
header("Location: $data");
