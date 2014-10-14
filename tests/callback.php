<?php

session_start();

$trello = new tschwemley\trello\Trello(array(
	'clientKey'		 => $_SESSION['oauth_token'],
	'clientSecret'	 => $_SESSION['oauth_token_secret'],
));

// Get the oauthVerifier that was sent back from Trello after access was granted.
$oauthVerifier = $_GET['oauth_verifier'];

// Pass OAuth Verifier and get access token
$token = $trello->getAccessToken($oauthVerifier);

// Set the session for the new access tokens, to replace the request tokens.
$_SESSION['oauth_token'] = $token['oauth_token'];
$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];

// Create a new instance of the Trello Object with the new request Tokens
$trello = new tschwemley\trello\Trello(array(
	'clientKey'		 => $_SESSION['oauth_token'],
	'clientSecret'	 => $_SESSION['oauth_token_secret'],
));

// Make an api call (using Trello API dev board as example)
$cards = $trello->apiCall(array('boards', '4d5ea62fd76aa1136000000c'));
