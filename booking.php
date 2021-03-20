<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
        $showAlert = false;
        $showError = false;
        include 'config.php';
        $check_in = $_POST["check_in"];
        $check_out = $_POST["check_out"];
        $payment_type = $_POST["payment_type"];
        $total_amount = $_POST["total_amount"];
        $total_rooms = $_POST["total_rooms"];
        $exists = false;

        $query1 = "SELECT check_in, check_out, payment_type, total_amount, total_rooms FROM `bookings`";
        $res = mysqli_query($conn, $query1);
        while($row = mysqli_fetch_array($res)){
            if ($row['check_in'] == $check_in && $row['check_out'] == $check_out && $row['payment_type'] == $payment_type && $row['total_amount'] == $total_amount && $row['total_rooms'] == $total_rooms) {
                $exists = true;
                break;
            }
        }

        $date_chk = false;
        if ($check_out > $check_in){
            $date_chk = true;
        }
        if ($exists == false && $date_chk == true){
            $sql = "INSERT INTO `Bookings` (`check_in`, `check_out`, `payment_type`, `total_amount`, `total_rooms`) VALUES ('$check_in', '$check_out', '$payment_type', '$total_amount', '$total_rooms');";
            $result = mysqli_query($conn, $sql);
            if ($result){
                $showAlert = true;
            }
        }else{
            $showError = true;
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
    <title>Guest Booking</title>
</head>
<body style="background-color:rgb(0, 0, 0);">
    <div class="form">
        <div class="container">
            <form action="booking.php" method="post">
                <div class="row">
                    <h2>Booking page</h2>

                    <div class="col-25">
                        <label for="check_in">Check-in</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="check_in" name="check_in" placeholder="yyyy-mm-dd" required>
                    </div>

                    <div class="col-25">
                        <label for="check_out">Check-out</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="check_out" name="check_out" placeholder="yyyy-mm-dd" required>
                    </div>

                    <div class="col-25">
                        <label for="payment_type">Payment type</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="payment_type" name="payment_type" placeholder="Eg. cash, credit, debit, etc." required>
                    </div>

                    <div class="col-25">
                        <label for="total_amount">Total amount payable</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="total_amount" name="total_amount" placeholder="Total amount (without commas)">
                    </div>

                    <div class="col-25">
                        <label for="total_rooms">Total rooms</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="total_rooms" name="total_rooms" placeholder="Total no. of rooms eg. 1, 2, 3..." required>
                    </div>

                    <input type="submit" name="create" value="Book" id="book">
                </div>
            </form>
        </div>
    </div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script type="text/javascript">
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
    var showAlert = '<?php echo $showAlert; ?>';
    var showError = '<?php echo $showError; ?>';
    if (showAlert == 1){
        $(function(){
            Swal.fire({
                position: 'centre',
                icon: 'success',
                title: 'Booking is successful!',
                showConfirmButton: false,
                timer: 1500
            })
        });
    }else if (showError == 1){
        $(function(){
            Swal.fire({
                position: 'centre',
                icon: 'error',
                title: 'Correct the form!',
                showConfirmButton: false,
                timer: 1500
            })
        });
    }
</script>
</body>
</html>