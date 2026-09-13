<?php

include "db.php";


$state_id = (int)(
    $_POST['state_id'] ?? 0
);


echo '<option value="">Select City</option>';


if ($state_id <= 0) {

    exit;

}


$sql = "SELECT *
        FROM cities
        WHERE state_id=$state_id
        ORDER BY city_name";


$result = mysqli_query(
    $conn,
    $sql
);


while ($city = mysqli_fetch_assoc($result)) {

    echo '<option value="' .
        $city['id'] .
        '">';

    echo htmlspecialchars(
        $city['city_name']
    );

    echo '</option>';

}

?>