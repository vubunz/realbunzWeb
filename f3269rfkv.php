<?php

function checkAdmin($conn, $username)
{
    $sql = "SELECT * FROM `users` WHERE `username` = '" . $username . "'";
    $query = mysqli_query($conn, $sql);

    if (!$query) {
        echo "Lỗi truy vấn: " . mysqli_error($conn);
        exit();
    }

    $num_rows = mysqli_num_rows($query);

    if ($num_rows > 0) {
        $row = mysqli_fetch_array($query);
        if ($row['role'] == 1 && in_array($row['username'], ['admin'])) {
            return true;
        }
    }

    return false;
}
