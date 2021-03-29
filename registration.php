<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'config.php';
        $showError      = false;
        $error          = "Some error occurred!";
        $g_id           = 0;
        $emp_id         = 0;
        $address_id     = 0;
        $g_or_e         = $_POST["g_or_e"];
        $name           = $_POST['name'];
        $contact_no1    = $_POST['contact_no1'];
        if (!empty($_POST['contact_no2'])) {
            $contact_no2= $_POST['contact_no2'];
        }
        $email1         = $_POST['email1'];
        if (!empty($_POST['email2'])) {
            $email2     = $_POST['email2'];
        }
        $address        = $_POST['address'];
        $username       = $_POST['username'];
        $password       = $_POST['password'];
        $address_arr    = explode(" ", $address);
        $city           = $address_arr[0];
        $state          = $address_arr[1];
        $country        = $address_arr[2];
        $zipcode        = $address_arr[3];
        
        if (!empty($g_or_e)) {
            if ($g_or_e == "guest") {
                $query  = "SELECT username FROM `Guests` WHERE username = '$username';";
                $res    = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) == 0) {
                    $name_arr = explode(" ", $name);
                    $num_name = count($name_arr);
                    if ($num_name >= 2) {
                        $first_name = array_shift($name_arr);
                        $last_name  = join(" ", $name_arr);
                        $sql1       = "INSERT INTO `Guests` (`first_name`, `last_name`, `username`, `password`) VALUES ('$first_name', '$last_name', '$username', '$password');";
                        $sql1      .= "SELECT LAST_INSERT_ID();";
                        $sql1      .= "INSERT INTO `Address` (`city`, `state`, `country`, `zipcode`) VALUES ('$city', '$state', '$country', '$zipcode');";
                        $sql1      .= "SELECT LAST_INSERT_ID();";
                        $result1    = mysqli_multi_query($conn, $sql1);
                        $i          = 0;
                        do {
                            if ($result_1 = mysqli_store_result($conn)) {
                                while ($row = mysqli_fetch_row($result_1)) {
                                    if ($i == 0){
                                        $g_id       = $row[0];
                                    }
                                    if ($i == 1) {
                                        $address_id = $row[0];
                                    }
                                    $i++;
                                }
                            }
                        } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                        $sql2       = "INSERT INTO `Guests_contact` (`g_id`, `contact_no`) VALUES ('$g_id', '$contact_no1');";
                        if (!empty($_POST['contact_no2'])) {
                            $sql2  .= "INSERT INTO `Guests_contact` (`g_id`, `contact_no`) VALUES ('$g_id', '$contact_no2');";
                        }
                        $sql2      .= "INSERT INTO `Guests_email` (`g_id`, `email_id`) VALUES ('$g_id', '$email1');";
                        if (!empty($_POST['email2'])) {
                            $sql2  .= "INSERT INTO `Guests_email` (`g_id`, `email_id`) VALUES ('$g_id', '$email2');";
                        }
                        $sql2      .= "INSERT INTO `Guest_address` (`g_id`, `address_id`) VALUES ('$g_id', '$address_id');";
                        $result2    = mysqli_multi_query($conn, $sql2);
                        if (result2) {
                            header("location: index.php");
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
                    echo mysqli_error($conn)."<br>";
                    $showError = true;
                    $error = "Change your username!";
                }
            } else if ($g_or_e == "employee") {
                $query = "SELECT username FROM `Employee` WHERE username = '$username'";
                $res = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) == 0) {
                    $name_arr = explode(" ", $name);
                    $num_name = count($name_arr);
                    if ($num_name >= 2) {
                        $first_name = array_shift($name_arr);
                        $last_name  = join(" ", $name_arr);
                        $sql1       = "INSERT INTO `Employee` (`first_name`, `last_name`, `username`, `password`) VALUES ('$first_name', '$last_name', '$username', '$password');";
                        $sql1      .= "SELECT LAST_INSERT_ID();";
                        $sql1      .= "INSERT INTO `Address` (`city`, `state`, `country`, `zipcode`) VALUES ('$city', '$state', '$country', '$zipcode');";
                        $sql1      .= "SELECT LAST_INSERT_ID();";
                        $result1    = mysqli_multi_query($conn, $sql1);
                        $i          = 0;
                        do {
                            if ($result_1 = mysqli_store_result($conn)) {
                                while ($row = mysqli_fetch_row($result_1)) {
                                    if ($i == 0){
                                        $emp_id     = $row[0];
                                    }
                                    if ($i == 1) {
                                        $address_id = $row[0];
                                    }
                                    $i++;
                                }
                            }
                        } while (mysqli_more_results($conn) && mysqli_next_result($conn));
                        $sql2       = "INSERT INTO `Emp_contact` (`emp_id`, `contact_no`) VALUES ('$emp_id', '$contact_no1');";
                        if (!empty($_POST['contact_no2'])) {
                            $sql2  .= "INSERT INTO `Emp_contact` (`emp_id`, `contact_no`) VALUES ('$emp_id', '$contact_no2');";
                        }
                        $sql2      .= "INSERT INTO `Emp_email` (`emp_id`, `email_id`) VALUES ('$emp_id', '$email1');";
                        if (!empty($_POST['email2'])) {
                            $sql2  .= "INSERT INTO `Emp_email` (`emp_id`, `email_id`) VALUES ('$emp_id', '$email2');";
                        }
                        $sql2      .= "INSERT INTO `Emp_address` (`emp_id`, `address_id`) VALUES ('$emp_id', '$address_id');";
                        $result2    = mysqli_multi_query($conn, $sql2);
                        if (result2) {
                            header("location: index.php");
                            exit;
                        } else {
                            $showError = true;
                            $error = ("Error description: " . mysqli_error($conn));
                        }
                    } else {
                        $showError = true;
                        $error = "Last name missing!";
                    }
                } else {
                    $showError = true;
                    $error = "Change your username!";
                }
            }
        } else {
            $showError = true;
            $error = "Choose the user type!";
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
    <title>Registration</title>
</head>
<body style="background-color:rgb(0, 0, 0);">
    <div class="form">
        <div class="container" style="padding: 10px 325px 20px 325px;">
            <form action="registration.php" method="post">
                <div class="row">
                    <h2 style="text-align:center;">Registration page</h2>

                    <div class="col-25" style="width:25%;">
                        <label for="g_or_e">Select user</label>
                    </div>
                    <div class="col-75" style="width:75%;">
                        <select name="g_or_e" id="g_or_e">
                            <option value="" disabled selected>Choose option</option>
                            <option value="guest">Guest</option>
                            <option value="employee">Employee</option>
                        </select>
                    </div>

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
                        <input type="text" id="contact_no2" name="contact_no2" placeholder="Contact no. (skipable field)">
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
                        <input type="email" id="email2" name="email2" placeholder="Email id (skipable field)">
                    </div>

                    <div class="col-25">
                        <label for="address">Address</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="address" name="address" placeholder="<city><space><state><space><country><space><zipcode>" required>
                    </div>

                    <div class="col-25">
                        <label for="username">Username</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>

                    <div class="col-25">
                        <label for="password">Password</label>
                    </div>
                    <div class="col-75">
                        <input type="password" id="password" name="password" placeholder="Password" required>
                    </div>

                    <div class="submit" style="text-align: center;">
                        <input type="submit" name="create" value="Sign Up" id="register">
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