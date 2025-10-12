<?php
require 'vendor/autoload.php'; // MongoDB PHP library

try {
    $client = new MongoDB\Client("mongodb+srv://elamathimuthusamy22_db_user:iTy5jn4DTtCpMrW6@cluster0.3lcjrzs.mongodb.net/?retryWrites=true&w=majority&appName=Cluster0");
    $db = $client->petconnect_db; // Database name
} catch (Exception $e) {
    die("Error connecting to MongoDB: " . $e->getMessage());
}
?>
