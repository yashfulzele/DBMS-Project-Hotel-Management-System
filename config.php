<?php
    $db_server = "localhost";
    $db_user   = "root";
    $db_pass   = "";
    $db_name   = "dbms_mini_1";

    $conn = mysqli_connect($db_server, $db_user, $db_pass, $db_name);
    if (!$conn) {
        echo "Some error occured in config.php - ".mysqli_error($conn)."<br>";
    }
?>