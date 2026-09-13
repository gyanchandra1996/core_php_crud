<?php

include "db.php";

$states = mysqli_query(
    $conn,
    "SELECT * FROM states ORDER BY state_name"
);

$result = mysqli_query(
    $conn,
    "SELECT
        users.*,
        states.state_name,
        cities.city_name
     FROM users

     LEFT JOIN states
        ON users.state_id = states.id

     LEFT JOIN cities
        ON users.city_id = cities.id

     ORDER BY users.id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>PHP Procedural CRUD</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

</head>

<body>

<div class="container mt-5">

    <h2 class="mb-4">
        PHP Procedural CRUD
    </h2>


    <!-- Success/Error Message -->

    <?php if (isset($_GET['msg'])) { ?>

        <div class="alert alert-success">

            <?php
            echo htmlspecialchars($_GET['msg']);
            ?>

        </div>

    <?php } ?>


    <!-- Add User Form -->

    <div class="card mb-5">

        <div class="card-header">

            <h5 class="mb-0">
                Add User
            </h5>

        </div>


        <div class="card-body">

            <form
                action="add.php"
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


                        <div class="form-check form-check-inline">

                            <input
                                type="checkbox"
                                name="hobbies[]"
                                value="Reading"
                                class="form-check-input"
                            >

                            <label class="form-check-label">
                                Reading
                            </label>

                        </div>


                        <div class="form-check form-check-inline">

                            <input
                                type="checkbox"
                                name="hobbies[]"
                                value="Music"
                                class="form-check-input"
                            >

                            <label class="form-check-label">
                                Music
                            </label>

                        </div>


                        <div class="form-check form-check-inline">

                            <input
                                type="checkbox"
                                name="hobbies[]"
                                value="Sports"
                                class="form-check-input"
                            >

                            <label class="form-check-label">
                                Sports
                            </label>

                        </div>


                        <div class="form-check form-check-inline">

                            <input
                                type="checkbox"
                                name="hobbies[]"
                                value="Travel"
                                class="form-check-input"
                            >

                            <label class="form-check-label">
                                Travel
                            </label>

                        </div>

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


                            <?php while ($state = mysqli_fetch_assoc($states)) { ?>

                                <option
                                    value="<?php echo $state['id']; ?>"
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

                        </select>

                    </div>

                </div>


                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save
                </button>

            </form>

        </div>

    </div>


    <!-- User List -->

    <div class="card">

        <div class="card-header">

            <h5 class="mb-0">
                Users List
            </h5>

        </div>


        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered table-striped">

                    <thead>

                    <tr>

                        <th>ID</th>

                        <th>Image</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>Gender</th>

                        <th>Hobbies</th>

                        <th>State</th>

                        <th>City</th>

                        <th>Action</th>

                    </tr>

                    </thead>


                    <tbody>

                    <?php if (mysqli_num_rows($result) > 0) { ?>

                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>

                            <tr>

                                <td>
                                    <?php echo $row['id']; ?>
                                </td>


                                <td>

                                    <?php if (!empty($row['image'])) { ?>

                                        <img
                                            src="uploads/<?php
                                            echo htmlspecialchars(
                                                $row['image']
                                            );
                                            ?>"
                                            width="70"
                                            height="70"
                                            style="object-fit:cover;"
                                        >

                                    <?php } else { ?>

                                        No Image

                                    <?php } ?>

                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['name']
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['email']
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['gender']
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['hobbies']
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['state_name']
                                    );
                                    ?>
                                </td>


                                <td>
                                    <?php
                                    echo htmlspecialchars(
                                        $row['city_name']
                                    );
                                    ?>
                                </td>


                                <td>

                                    <a
                                        href="edit.php?id=<?php
                                        echo $row['id'];
                                        ?>"
                                        class="btn btn-sm btn-warning"
                                    >
                                        Edit
                                    </a>


                                    <a
                                        href="delete.php?id=<?php
                                        echo $row['id'];
                                        ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm(
                                            'Are you sure you want to delete?'
                                        );"
                                    >
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php } ?>

                    <?php } else { ?>

                        <tr>

                            <td
                                colspan="9"
                                class="text-center"
                            >
                                No records found
                            </td>

                        </tr>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="js/script.js"></script>

</body>

</html>