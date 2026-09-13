
<?php

include "db.php";

$id = (int)($_GET['id'] ?? 0);

if ($id <= 0) {
    die("Invalid ID");
}


/*
|--------------------------------------------------------------------------
| UPDATE
|--------------------------------------------------------------------------
*/

if (isset($_POST['save'])) {

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


    // Minimum validation

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


    /*
    |--------------------------------------------------------------------------
    | Get old image
    |--------------------------------------------------------------------------
    */

    $oldResult = mysqli_query(
        $conn,
        "SELECT image
         FROM users
         WHERE id=$id"
    );

    $oldUser = mysqli_fetch_assoc($oldResult);

    if (!$oldUser) {

        die("User not found");

    }

    $oldImage = $oldUser['image'];


    /*
    |--------------------------------------------------------------------------
    | Image
    |--------------------------------------------------------------------------
    */

    $imageSQL = "";


    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] == 0
    ) {

        $newImage =
            time() . '_' .
            basename($_FILES['image']['name']);


        $uploadPath =
            "uploads/" . $newImage;


        if (
            move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $uploadPath
            )
        ) {

            // Delete old image

            if (
                !empty($oldImage) &&
                file_exists("uploads/" . $oldImage)
            ) {

                unlink(
                    "uploads/" . $oldImage
                );

            }


            $imageSQL =
                ", image='$newImage'";

        }

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */

    $sql = "UPDATE users SET

                name='$name',

                email='$email',

                gender='$gender',

                hobbies='$hobbies',

                state_id=$state_id,

                city_id=$city_id

                $imageSQL

            WHERE id=$id";


    if (mysqli_query($conn, $sql)) {

        /*
        IMPORTANT:
        There must be NO HTML/output before this.
        */

        header(
            "Location: index.php?msg=User updated successfully"
        );

        exit;

    } else {

        die(
            "Database Error: " .
            mysqli_error($conn)
        );

    }

}


/*
|--------------------------------------------------------------------------
| GET USER
|--------------------------------------------------------------------------
*/

$sql = "SELECT *
        FROM users
        WHERE id=$id";

$result = mysqli_query($conn, $sql);

$user = mysqli_fetch_assoc($result);

if (!$user) {

    die("User not found");

}


/*
|--------------------------------------------------------------------------
| GET STATES
|--------------------------------------------------------------------------
*/

$states = mysqli_query(
    $conn,
    "SELECT *
     FROM states
     ORDER BY state_name"
);


/*
|--------------------------------------------------------------------------
| GET CITIES FOR CURRENT STATE
|--------------------------------------------------------------------------
*/

$cities = [];

if (!empty($user['state_id'])) {

    $state_id = (int)$user['state_id'];

    $cityResult = mysqli_query(
        $conn,
        "SELECT *
         FROM cities
         WHERE state_id=$state_id
         ORDER BY city_name"
    );


    while ($city = mysqli_fetch_assoc($cityResult)) {

        $cities[] = $city;

    }

}


/*
|--------------------------------------------------------------------------
| USER HOBBIES
|--------------------------------------------------------------------------
*/

$userHobbies = [];

if (!empty($user['hobbies'])) {

    $userHobbies =
        explode(',', $user['hobbies']);

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Edit User</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>


<body>

<div class="container mt-5">

    <h2 class="mb-4">
        Edit User
    </h2>


    <form
        action="edit.php?id=<?php echo $id; ?>"
        method="POST"
        enctype="multipart/form-data"
    >


        <div class="row">


            <!-- Name -->

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Name
                </label>

                <input
                    type="text"
                    name="name"
                    class="form-control"
                    value="<?php
                    echo htmlspecialchars(
                        $user['name']
                    );
                    ?>"
                >

            </div>


            <!-- Email -->

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    class="form-control"
                    value="<?php
                    echo htmlspecialchars(
                        $user['email']
                    );
                    ?>"
                >

            </div>


            <!-- Image -->

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Image
                </label>

                <input
                    type="file"
                    name="image"
                    class="form-control"
                    accept="image/*"
                >


                <?php if (!empty($user['image'])) { ?>

                    <div class="mt-2">

                        <img
                            src="uploads/<?php
                            echo htmlspecialchars(
                                $user['image']
                            );
                            ?>"
                            width="80"
                            height="80"
                            style="object-fit:cover;"
                        >

                    </div>

                <?php } ?>

            </div>


            <!-- Gender -->

            <div class="col-md-6 mb-3">

                <label class="form-label d-block">
                    Gender
                </label>


                <div class="form-check form-check-inline">

                    <input
                        type="radio"
                        name="gender"
                        value="Male"
                        class="form-check-input"

                        <?php
                        if ($user['gender'] == 'Male') {
                            echo 'checked';
                        }
                        ?>
                    >

                    <label class="form-check-label">
                        Male
                    </label>

                </div>


                <div class="form-check form-check-inline">

                    <input
                        type="radio"
                        name="gender"
                        value="Female"
                        class="form-check-input"

                        <?php
                        if ($user['gender'] == 'Female') {
                            echo 'checked';
                        }
                        ?>
                    >

                    <label class="form-check-label">
                        Female
                    </label>

                </div>

            </div>


            <!-- Hobbies -->

            <div class="col-md-6 mb-3">

                <label class="form-label">
                    Hobbies
                </label>

                <br>


                <?php

                $hobbyList = [
                    'Reading',
                    'Music',
                    'Sports',
                    'Travel'
                ];

                foreach ($hobbyList as $hobby) {

                ?>

                    <div class="form-check form-check-inline">

                        <input
                            type="checkbox"
                            name="hobbies[]"
                            value="<?php echo $hobby; ?>"
                            class="form-check-input"

                            <?php

                            if (
                                in_array(
                                    $hobby,
                                    $userHobbies
                                )
                            ) {

                                echo 'checked';

                            }

                            ?>
                        >

                        <label class="form-check-label">

                            <?php
                            echo $hobby;
                            ?>

                        </label>

                    </div>

                <?php } ?>

            </div>


            <!-- State -->

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    State
                </label>


                <select
                    name="state_id"
                    id="state_id"
                    class="form-select"
                >

                    <option value="">
                        Select State
                    </option>


                    <?php
                    while (
                        $state =
                        mysqli_fetch_assoc($states)
                    ) {
                    ?>

                        <option
                            value="<?php
                            echo $state['id'];
                            ?>"

                            <?php

                            if (
                                $state['id']
                                ==
                                $user['state_id']
                            ) {

                                echo 'selected';

                            }

                            ?>
                        >

                            <?php

                            echo htmlspecialchars(
                                $state['state_name']
                            );

                            ?>

                        </option>

                    <?php } ?>

                </select>

            </div>


            <!-- City -->

            <div class="col-md-3 mb-3">

                <label class="form-label">
                    City
                </label>


                <select
                    name="city_id"
                    id="city_id"
                    class="form-select"
                >

                    <option value="">
                        Select City
                    </option>


                    <?php foreach ($cities as $city) { ?>

                        <option
                            value="<?php
                            echo $city['id'];
                            ?>"

                            <?php

                            if (
                                $city['id']
                                ==
                                $user['city_id']
                            ) {

                                echo 'selected';

                            }

                            ?>
                        >

                            <?php

                            echo htmlspecialchars(
                                $city['city_name']
                            );

                            ?>

                        </option>

                    <?php } ?>

                </select>

            </div>

        </div>


        <button
            type="submit"
            name="save"
            class="btn btn-primary"
        >
            Update
        </button>


        <a
            href="index.php"
            class="btn btn-secondary"
        >
            Back
        </a>

    </form>

</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="js/script.js"></script>

</body>

</html>
```
