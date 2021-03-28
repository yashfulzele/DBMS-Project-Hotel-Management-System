<?php
    session_start();
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
        header("location: index.php");
        exit;
    } else {
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            include 'config.php';
            $showError      = false;
            $error          = "Some error occurred";
            $g_or_e         = $_SESSION['user'];
            $username       = $_SESSION['username'];
            $opassword      = $_POST['opassword'];
            $name           = $_POST['name'];
            $contact1       = $_POST['contact_no1'];
            $contact1       = $_POST['contact_no1'];
            if (!empty($_POST['contact_no2'])) {
                $contact2   = $_POST['contact_no2'];
            }
            $email1         = $_POST['email1'];
            if (!empty($_POST['email2'])) {
                $email2     = $_POST['email2'];
            }
            $address        = $_POST['address'];
            $opassword      = $_POST['opassword'];
            $npassword      = $_POST['npassword'];
            $address_arr    = explode(" ", $address);
            $city           = $address_arr[0];
            $state          = $address_arr[1];
            $country        = $address_arr[2];
            $zipcode        = $address_arr[3];
            $name_arr       = explode(" ", $name);
            $num_name       = count($name_arr);
            if ($g_or_e == "guest") {
                $query      = "SELECT username, password FROM `Guests` WHERE username = '$username' AND password = '$opassword';";
                $res        = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) == 1) {
                    if ($num_name >= 2) {
                        $first_name = array_shift($name_arr);
                        $last_name  = join(" ", $name_arr);
                        $upd        = "UPDATE `Guests` SET first_name='$first_name', last_name='$last_name', password='$npassword' WHERE username='$username';";
                        $upd       .= "DELETE FROM `Guests_contact` WHERE g_id=(SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username');";
                        $upd       .= "DELETE FROM `Guests_email` WHERE g_id=(SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username');";
                        $upd       .= "INSERT INTO `Guests_contact` (`g_id`, `contact_no`) VALUES ((SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username'), '$contact1');";
                        if (!empty($_POST['contact_no2'])) {
                            $upd   .= "INSERT INTO `Guests_contact` (`g_id`, `contact_no`) VALUES ((SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username'), '$contact2');";
                        }
                        $upd       .= "INSERT INTO `Guests_email` (`g_id`, `email_id`) VALUES ((SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username'), '$email1');";
                        if (!empty($_POST['email2'])) {
                            $upd   .= "INSERT INTO `Guests_email` (`g_id`, `email_id`) VALUES ((SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username'), '$email2');";
                        }
                        $upd       .= "UPDATE `Address` SET city='$city', state='$state', country='$country', zipcode='$zipcode' WHERE address_id=(SELECT a.address_id FROM `Guest_address` AS a WHERE a.g_id=(SELECT g.g_id FROM `Guests` AS g WHERE g.username='$username'));";
                        $result     = mysqli_multi_query($conn, $upd);
                        if (result) {
                            header("location: main_guest.php");
                            exit;
                        } else {
                            $showError = true;
                            $error = ("Error description: " . mysqli_error($conn));
                        }
                    } else {
                        $showError  = true;
                        $error      = "Last name missing!";
                    }
                } else {
                    $showError  = true;
                    $error      = "Type correct old password!";
                }
            } else if ($g_or_e == "employee") {
                $query      = "SELECT username, password FROM `Employee` WHERE username = '$username' AND password = '$opassword';";
                $res        = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) == 1) {
                    if ($num_name >= 2) {
                        $first_name = array_shift($name_arr);
                        $last_name  = join(" ", $name_arr);
                        $upd        = "UPDATE `Employee` SET first_name='$first_name', last_name='$last_name', password='$npassword' WHERE username='$username';";
                        $upd       .= "DELETE FROM `Emp_contact` WHERE emp_id=(SELECT e.emp_id FROM `Employee` AS e WHERE e.username='$username');";
                        $upd       .= "DELETE FROM `Emp_email` WHERE emp_id=(SELECT e.emp_id FROM `Employee` AS e WHERE e.username='$username');";
                        $upd       .= "INSERT INTO `Emp_contact` (`emp_id`, `contact_no`) VALUES ((SELECT e.emp_id FROM `Employee` AS e WHERE e.username='$username'), '$contact1');";
                        if (!empty($_POST['contact_no2'])) {
                            $upd   .= "INSERT INTO `Emp_contact` (`emp_id`, `contact_no`) VALUES ((SELECT e.emp_id FROM `Employee` AS e WHERE e.username='$username'), '$contact2');";
                        }
                        $upd       .= "INSERT INTO `Emp_email` (`emp_id`, `email_id`) VALUES ((SELECT e.emp_id FROM `Employee` AS e WHERE e.username='$username'), '$email1');";
                        if (!empty($_POST['email2'])) {
                            $upd   .= "INSERT INTO `Emp_email` (`emp_id`, `email_id`) VALUES ((SELECT e.emp_id FROM `Employee` AS e WHERE e.username='$username'), '$email2');";
                        }
                        $upd       .= "UPDATE `Address` SET city='$city', state='$state', country='$country', zipcode='$zipcode' WHERE address_id=(SELECT a.address_id FROM `Emp_address` AS a WHERE a.emp_id=(SELECT e.emp_id FROM `Employee` AS e WHERE e.username='$username'));";
                        $result     = mysqli_multi_query($conn, $upd);
                        if (result) {
                            header("location: main_employee.php");
                            exit;
                        } else {
                            $showError = true;
                            $error = ("Error description: " . mysqli_error($conn));
                        }
                    } else {
                        $showError  = true;
                        $error      = "Last name missing!";
                    }
                } else {
                    $showError  = true;
                    $error      = "Type correct old password!";
                }
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Update profile</title>
</head>
<body style="background-color:rgb(0, 0, 0);">
    <div class="form">
        <div class="container" style="padding: 10px 325px 20px 325px;">
            <form action="update_profile.php" method="post">
                <div class="row">
                    <h2 style="text-align:center;">Update profile page</h2>

                    <div class="col-25">
                        <label for="name">Full Name</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="name" name="name" placeholder="Your full name <first_name><last_name>" required>
                    </div>

                    <div class="col-25">
                        <label for="contact_no1">Contact 1</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="contact_no1" name="contact_no1" placeholder="Contact no." required>
                    </div>

                    <div class="col-25">
                        <label for="contact_no2">Contact 2</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="contact_no2" name="contact_no2" placeholder="Contact no. (secondary)">
                    </div>

                    <div class="col-25">
                        <label for="email1">Email 1</label>
                    </div>
                    <div class="col-75">
                        <input type="email" id="email1" name="email1" placeholder="Email id" required>
                    </div>

                    <div class="col-25">
                        <label for="email2">Email 2</label>
                    </div>
                    <div class="col-75">
                        <input type="email" id="email2" name="email2" placeholder="Email id (secondary)">
                    </div>

                    <div class="col-25">
                        <label for="address">Address</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="address" name="address" placeholder="<city><space><state><space><country><space><zipcode>" required>
                    </div>

                    <div class="col-25">
                        <label for="password">Old Password</label>
                    </div>
                    <div class="col-75">
                        <input type="password" id="opassword" name="opassword" placeholder="Type your old password" required>
                    </div>

                    <div class="col-25">
                        <label for="npassword">New Password</label>
                    </div>
                    <div class="col-75">
                        <input type="password" id="npassword" name="npassword" placeholder="Type your new password" required>
                    </div>

                    <div class="submit" style="text-align: center;">
                        <input type="submit" name="create" value="Update" id="update">
                    </div>
                </div>
            </form>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    if (window.history.replaceState) {
        window.history.replaceState( null, null, window.location.href );
    }
    var showError = '<?php echo $showError; ?>';
    var error = '<?php echo json_encode($error); ?>';
    if (showError == 1) {
        $(function(){
            Swal.fire({
                position: 'centre',
                icon: 'error',
                title: 'Correct the form!',
                text: error,
                showConfirmButton: false,
                timer: 1500
            })
        });
    }
</script>
</body>
</html>