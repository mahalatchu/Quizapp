<?php
error_reporting(E_ERROR | E_PARSE);
require_once 'vendor/autoload.php';

session_start();

$client = new Google_Client();
$client->setClientId('291644741449-f79dshmu3cf1gjht4hbdujupr1g4jf8d.apps.googleusercontent.com');
$client->setApplicationName('Quiz Application'); 
$client->setClientSecret('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');
$client->setRedirectUri('http://localhost/quizapp/quizform.php');
$client->addScope(Google\Service\Drive::DRIVE_METADATA_READONLY); // Example scope
$client->setAccessType('offline'); 
$client->setPrompt('select_account consent');
$client->setState('abc');

if (isset($_GET['code'])) {
    
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    if (isset($token['access_token'])) {
        // Store the refresh token in the session or database
        if (empty($_SESSION['access_token'])) {
            $_SESSION['access_token'] = $token['access_token'];
        } else {
            $_SESSION['access_token'] =  $_SESSION['access_token'];
        }
    } else {
        echo "Refresh token not returned.";
    }

    $client->setAccessToken($_SESSION['access_token']);
    
    // Get user profile information
    $oauth2 = new Google_Service_Oauth2($client);
    $userInfo = $oauth2->userinfo->get();

    $_SESSION['user_id'] = $userInfo->id;
    $_SESSION['user_name'] = $userInfo->name;
    $_SESSION['user_email'] = $userInfo->email;

    header('Location: quizformview.php');
    exit();
    
    // Example: Display user information 
   // echo 'Token: ' .$_SESSION['access_token'];
   // echo 'User ID: ' . $userInfo->id;
   // echo 'User Name: ' . $userInfo->name;
   // echo 'User Email: ' . $userInfo->email;
} else {
    header('Location: login.php');
    exit();
}
