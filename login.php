<?php
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setRedirectUri('http://localhost/quizapp/quizform.php');
$client->setClientId('291644741449-f79dshmu3cf1gjht4hbdujupr1g4jf8d.apps.googleusercontent.com');
$client->setApplicationName('Quiz Application'); 
$client->setClientSecret('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');
$client->addScope('email');
$client->addScope('profile');
$client->setAccessType('offline'); 
$client->setPrompt('select_account consent');
$client->setState('abc');

$authUrl = $client->createAuthUrl();
header('Location: ' . filter_var($authUrl, FILTER_SANITIZE_URL));
