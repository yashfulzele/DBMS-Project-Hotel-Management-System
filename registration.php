<?php
    include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="reg-styles.css">
    <title>Guest Registration</title>
</head>
<body style="background-color:rgb(0, 0, 0);">
    <div class="php_container">
        <?php
            if (isset($_POST['create'])){
                $first_name     = $_POST['first_name'];
                $last_name      = $_POST['last_name'];
                $contact_no1    = $_POST['contact_no1'];
                $contact_no2    = $_POST['contact_no2'];
                $email1         = $_POST['email1'];
                $email2         = $_POST['email2'];
                $address        = $_POST['address'];
                $username       = $_POST['username'];
                $password       = $_POST['password'];

                $address_arr = explode(" ", $address);
                $city = $address_arr[0];
                $state = $address_arr[1];
                $country = $address_arr[2];
                $zipcode = $address_arr[3];

                $sql1 = "insert into Guests(first_name, last_name, username, password) values(?, ?, ?, ?)";
                $sql2 = "insert into Address(city, state, country, zipcode) values(?, ?, ?, ?)";
                $sql3 = "insert into Guests_contact(contact_no) values(?)";
            }
        ?>
    </div>
    <div class="form">
        <div class="container">
            <form action="registration.php" method="post">
                <div class="row">
                    <h2>Registration page</h2>

                    <div class="col-25">
                        <label for="first_name">First Name</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="first_name" name="first_name" placeholder="First name" required>
                    </div>

                    <div class="col-25">
                        <label for="last_name">Last Name</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="last_name" name="last_name" placeholder="Last name" required>
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

                    <input type="submit" name="create" value="Sign Up" id="register">
                </div>
            </form>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    $(function(){
        $('#register').click(function(){
            Swal.fire({
                position: 'centre',
                icon: 'success',
                title: 'Registered successfully',
                showConfirmButton: false,
                timer: 1500
            })
        });
    });
</script>
</body>
</html>