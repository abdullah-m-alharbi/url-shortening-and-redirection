<?php

session_start();

// Include database configuration file
require_once 'dbConfig.php';

// Include URL Shortener library file
require_once 'Shortener.class.php';

// Initialize Shortener class and pass PDO object
$shortener = new Shortener($db);

// Long URL
$longURL = $_POST['url'];
$description = $_POST['description'];

// Prefix of the short URL 
$shortURL_Prefix = 'localhost/url-service/'; // with URL rewrite
//$shortURL_Prefix = 'https://xyz.com/?c='; // without URL rewrite
$_SESSION['prefix'] = $shortURL_Prefix;
try {
    // Get short code of the URL
    $shortCode = $shortener->urlToShortCode($longURL, $description);

    // Create short URL
    $shortURL = $shortURL_Prefix . $shortCode;

    // Display short URL
//    $newurl = 'Short URL: '.$shortURL;
    $_SESSION['newurl'] = $shortURL;
    header("Location: result.php");
} catch (Exception $e) {
    // Display error
    echo $e->getMessage();
}