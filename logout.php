<?php
    session_start();
    if ($_SESSION['user'] == 'guest') {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'config.php';
            $username           = $_SESSION['username'];
            $tea_or_coffee      = $_POST['tea_or_coffee'];
            $lunch_or_dinner    = $_POST['lunch_or_dinner'];
            $other_room_service = $_POST['other_room_service'];
            $rooms              = $_POST['rooms'];
            // serv_ids : (hard-coded, can be altered later)
            // tea_or_coffee        = 0
            // lunch_or_dinner      = 1
            // other_room_service   = 2
            $q          = "UPDATE `Service_used` SET ratings='$tea_or_coffee' WHERE g_id=(SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username') AND serv_id=0;";
            $q         .= "UPDATE `Service_used` SET ratings='$lunch_or_dinner' WHERE g_id=(SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username') AND serv_id=1;";
            $q         .= "UPDATE `Service_used` SET ratings='$other_room_service' WHERE g_id=(SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username') AND serv_id=2;";
            $q         .= "UPDATE `Room_booked` SET ratings='$rooms' WHERE g_id=(SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username');";
            $res        = mysqli_multi_query($conn, $q);
            if ($res) {
                session_unset();
                session_destroy();
                header("location: index.php");
                exit;
            }
        }

    } else {
        session_unset();
        session_destroy();
        header("location: index.php");
        exit;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Confirmation</title>
</head>
<body style="background-color:rgb(0, 0, 0);">
    <div class="form">
        <div class="container" style="padding: 120px 300px 170px 300px;">
            <form action="logout.php" method="post">
                <div class="row">
                    <h2 style="text-align:center;">Rate our services</h2>

                    <div class="col-25">
                        <label for="tea_or_coffee">Tea or coffee</label>
                    </div>
                    <div class="col-75">
                        <input type="text" name="tea_or_coffee" placeholder="rate within 1 to 5" required>
                    </div>

                    <div class="col-25">
                        <label for="lunch_or_dinner">Lunch or Dinner</label>
                    </div>
                    <div class="col-75">
                        <input type="text" name="lunch_or_dinner" placeholder="rate within 1 to 5" required>
                    </div>

                    <div class="col-25">
                        <label for="other_room_service">Other room services</label>
                    </div>
                    <div class="col-75">
                        <input type="text" name="other_room_service" placeholder="rate within 1 to 5" required>
                    </div>

                    <div class="col-25">
                        <label for="rooms">Rooms</label>
                    </div>
                    <div class="col-75">
                        <input type="text" name="rooms" placeholder="rate within 1 to 5" required>
                    </div>

                    <div class="submit" style="text-align: center;">
                        <input type="submit" name="create" value="Confirm">
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>