<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        header("location: index.php");
        exit;
    } else {
        include 'config.php';
        $username   = $_SESSION['username'];
        $query1     = "SELECT city, state, country, zipcode FROM `Address` WHERE address_id = (SELECT a.address_id FROM `Guests` AS g, `Guest_address` AS a WHERE a.g_id=g.g_id AND g.username='$username');";
        $query1    .= "SELECT first_name, last_name FROM `Guests` WHERE username = '$username';";
        $res1       = mysqli_multi_query($conn, $query1);
        $i          = 0;
        do {
            if ($result = mysqli_store_result($conn)) {
                while ($row = mysqli_fetch_row($result)) {
                    if ($i == 0) {
                        $city       = $row[0];
                        $state      = $row[1];
                        $country    = $row[2];
                        $zipcode    = $row[3];
                    } else if ($i == 1) {
                        $first_name = $row[0];
                        $last_name  = $row[1];
                    }
                    $i++;
                }
            }
        } while (mysqli_more_results($conn) && mysqli_next_result($conn));
        $query2     = "SELECT contact_no FROM `Guests_contact` WHERE g_id = (SELECT g.g_id FROM `Guests` AS g WHERE g.username = '$username');";
        $res2       = mysqli_query($conn, $query2);
        $contacts   = mysqli_num_rows($res2);
        if ($contacts == 1) {
            while($row = mysqli_fetch_assoc($res2)) {
                $contact_no1    = $row["contact_no"];
            }
        } else if ($contacts == 2) {
            $i      = 0;
            while ($row = mysqli_fetch_assoc($res2)) {
                if ($i == 0) {
                    $contact_no1 = $row["contact_no"];
                } else if ($i == 1) {
                    $contact_no2 = $row["contact_no"];
                }
                $i++;
            }
        }
        $query3     = "SELECT email_id FROM `Guests_email` WHERE g_id = (SELECT g.g_id FROM `Guests` AS g WHERE g.username = '$username');";
        $res3       = mysqli_query($conn, $query3);
        $emails     = mysqli_num_rows($res3);
        if ($emails == 1) {
            while($row = mysqli_fetch_assoc($res3)) {
                $email1    = $row["email_id"];
            }
        } else if ($emails == 2) {
            $i      = 0;
            while ($row = mysqli_fetch_assoc($res3)) {
                if ($i == 0) {
                    $email1 = $row["email_id"];
                } else if ($i == 1) {
                    $email2 = $row["email_id"];
                }
                $i++;
            }
        }
        $name        = $first_name." ".$last_name;
        $address     = $city.", ".$state.", ".$country.", ".$zipcode;
        if ($contacts == 1) {
            $contact = $contact_no1;
        } else {
            $contact = $contact_no1.", ".$contact_no2;
        }
        if ($emails == 1) {
            $email   = $email1;
        } else {
            $email   = $email1.", ".$email2;
        }
        // code for services part
        // serv_ids : (hard-coded, can be altered later)
        // tea_or_coffee        = 0
        // lunch_or_dinner      = 1
        // other_room_service   = 2
        $servs = [
            'tea_or_coffee' => 0,
            'lunch_or_dinner' => 1,
            'other_room_service' => 2,
        ];
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!empty($_POST['serv']) && is_array($_POST['serv'])) {
                $services_selected  = $_POST['serv'];
                $service            = $services_selected[0];
                $n                  = count($services_selected);
                $sql                = "INSERT INTO `Service_used` (`g_id`, `serv_id`) VALUES ((SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username'), '$servs[$service]');";
                for ($i = 1; $i < $n; $i++) {
                    $service        = $services_selected[$i];
                    $sql           .= "INSERT INTO `Service_used` (`g_id`, `serv_id`) VALUES ((SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username'), '$servs[$service]');";
                }
                $res                = mysqli_multi_query($conn, $sql);
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en" style="height: 100%;">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Main</title>
</head>
<body style="background-color: rgb(0, 0, 0); height: 100%;">
    <div class="container" style="height: 100%;">
        <div class="profile">
            <h3>Profile Information - <?php echo $_SESSION['user']; ?></h3>
            <hr/>

            <div class="col-25">
                <h4>Name</h4>
            </div>
            <div class="col-75">
                <h4><?php echo $name; ?></h4>
            </div>
            
            <div class="col-25">
                <h4>Contact</h4>
            </div>
            <div class="col-75">
                <h4><?php echo $contact; ?></h4>
            </div>

            <div class="col-25">
                <h4>Email-Id</h4>
            </div>
            <div class="col-75">
                <h4><?php echo $email; ?></h4>
            </div>

            <div class="col-25">
                <h4>Address</h4>
            </div>
            <div class="col-75">
                <h4><?php echo $address; ?></h4>
            </div>
        </div>

        <div class="foot">
            <div class="service">
                <form action="main_guest.php" method="post">
                    <h3>Service booking</h3>
                    <input type="checkbox" name="serv[]" value="tea_or_coffee">
                    <label>Tea or Coffee</label><br>
                    <input type="checkbox" name="serv[]" value="lunch_or_dinner">
                    <label>Lunch or Dinner</label><br>
                    <input type="checkbox" name="serv[]" value="other_room_service">
                    <label>Other room service</label><br>
                    <input type="submit" name="create" value="Book" class="serv_book">
                </form>
            </div>

            <div class="buttons">
                <div class="form1">
                    <div class="update_bt">
                        <button onclick="location.href = 'update_profile.php';" class="update">Update profile</button>
                    </div>
                </div>
                <div class="form2">
                    <div class="booking_bt">
                        <button onclick="location.href = 'booking.php';" class="booking">Booking</button>
                    </div>
                </div>
                <div class="form3">
                    <div class="logout_bt">
                        <button onclick="location.href = 'logout.php';" class="logout">Logout</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>