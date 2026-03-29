<?php
$host = 'localhost';
$dbuser = 'root';
$dbpw = '';
$dbname = 'digital_twin';

$link = mysqli_connect($host, $dbuser, $dbpw, $dbname);

if (!$link) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($link, 'utf8mb4');
?>