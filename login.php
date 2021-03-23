<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        include 'config.php';
        $g_or_e   = $_POST["g_or_e"];
        $username = $_POST["username"];
        $password = $_POST["password"];
        $login = false;
        if (!empty($g_or_e)) {
            if ($g_or_e == "guest") {
                $query = "SELECT username, password FROM `Guests` WHERE username = '$username' AND password = '$password';";
                $res = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) == 1) {
                    $login = true;
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    header("location: main.php");
                }
            } else if ($g_or_e == "employee") {
                $query = "SELECT username, password FROM `Employee` WHERE username = '$username' AND password = '$password';";
                $res = mysqli_query($conn, $query);
                if (mysqli_num_rows($res) == 1) {
                    $login = true;
                    session_start();
                    $_SESSION['loggedin'] = true;
                    $_SESSION['username'] = $username;
                    header("location: main.php");
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
    <title>Login</title>
</head>
<body style="background-color:rgb(0, 0, 0);">
    <div class="form">
        <div class="container" style="padding: 120px 450px 170px 450px;">
            <form action="login.php" method="post">
                <div class="row">
                    <h2 style="text-align:center;">Login page</h2>

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
                        <label for="username">Username</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="username" name="username" placeholder="Username" required>
                    </div>

                    <div class="col-25">
                        <label for="password">Password</label>
                    </div>
                    <div class="col-75">
                        <input type="password" id="password" name="password" placeholder="password" required>
                    </div>

                    <div class="submit" style="text-align: center;">
                        <input type="submit" name="create" value="Login" id="login">
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
    var login = '<?php echo $login; ?>';
    if (login != 1) {
        $(function(){
            Swal.fire({
                position: 'centre',
                icon: 'error',
                title: 'Enter valid credentials or Signup',
                showConfirmButton: false,
                timer: 1500
            })
        });
    }
</script>
</body>
</html>