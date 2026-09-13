<?php

include "db.php";


// Get form values

$name = trim($_POST['name'] ?? '');

$email = trim($_POST['email'] ?? '');

$gender = $_POST['gender'] ?? '';

$state_id = (int)($_POST['state_id'] ?? 0);

$city_id = (int)($_POST['city_id'] ?? 0);


// Hobbies

$hobbies = '';

if (isset($_POST['hobbies'])) {

    $hobbies = implode(',', $_POST['hobbies']);

}


// Minimal validation

if ($name == '') {

    die("Name is required");

}

if ($email == '') {

    die("Email is required");

}


// Escape values

$name = mysqli_real_escape_string(
    $conn,
    $name
);

$email = mysqli_real_escape_string(
    $conn,
    $email
);

$gender = mysqli_real_escape_string(
    $conn,
    $gender
);

$hobbies = mysqli_real_escape_string(
    $conn,
    $hobbies
);


// Image

$imageName = '';


if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] == 0
) {

    $imageName =
        time() . '_' .
        basename($_FILES['image']['name']);


    $uploadPath =
        "uploads/" . $imageName;


    move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $uploadPath
    );

}


// Insert

$sql = "INSERT INTO users
        (
            name,
            email,
            image,
            gender,
            hobbies,
            state_id,
            city_id
        )
        VALUES
        (
            '$name',
            '$email',
            '$imageName',
            '$gender',
            '$hobbies',
            $state_id,
            $city_id
        )";


if (mysqli_query($conn, $sql)) {

    header(
        "Location: index.php?msg=User added successfully"
    );

    exit;

} else {

    die(
        "Database Error: " .
        mysqli_error($conn)
    );

}

?>