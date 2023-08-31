<?php
$hostname = "localhost";
$username = "id21193305_demo2";
$password = "Jack@1234";
$database = "id21193305_demo2";

$connection = new mysqli($hostname, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
