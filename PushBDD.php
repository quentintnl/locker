<?php
include ("ConnectBDD.php");

echo "Connected successfully\n";

$conn = ConnectBDD();

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