<?php

include "db.php";


$id = (int)($_GET['id'] ?? 0);


if ($id <= 0) {

    die("Invalid ID");

}


// Get image

$result = mysqli_query(
    $conn,
    "SELECT image
     FROM users
     WHERE id=$id"
);


$user = mysqli_fetch_assoc($result);


if (!$user) {

    die("User not found");

}


$image = $user['image'];


// Delete user

$sql = "DELETE FROM users WHERE id=$id";


if (mysqli_query($conn, $sql)) {


    // Delete image

    if (
        !empty($image) &&
        file_exists("uploads/" . $image)
    ) {

        unlink(
            "uploads/" . $image
        );

    }


    header(
        "Location: index.php?msg=User deleted successfully"
    );

    exit;


} else {

    die(
        "Delete failed: " .
        mysqli_error($conn)
    );

}

?>