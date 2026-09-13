<?php

$conn = mysqli_connect(
    "localhost",
    "root",
    "",
    "php_crud"
);

if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

mysqli_set_charset($conn, "utf8mb4");

?>