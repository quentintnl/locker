<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "locker";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully\n";

$sqlFile = 'createDatabase.sql';

if (file_exists($sqlFile)) {
    $sql = file_get_contents($sqlFile);

    if ($conn->multi_query($sql)) {
        echo "Database created successfully.";
    } else {
        echo "Error creating database:" . $conn->error . "\n";
    }
} else {
    echo "SQL file does not exist.\n";
}

