<?php
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    	include 'config.php';
        $showAlert      = false;
        $showError      = false;
        $check_in       = $_POST["check_in"];
        $check_out      = $_POST["check_out"];
        $payment_type   = $_POST["payment_type"];
        $total_rooms    = $_POST["total_rooms"];
        $exists         = false;

        $error = false;
        $room_type_name = $_POST["room_type_list"];

        $total_cost = 0;
        $room_error = false;

        $username = $_SESSION['username'];

        $q1 = "SELECT g_id FROM `guests` WHERE username = '$username';";
        $r1 = mysqli_query($conn, $q1) or die(mysqli_error($conn));
        $g_id = 0;
        if($r1) {
            $row = mysqli_fetch_array($r1, MYSQLI_NUM);
            $g_id = $row[0];
        }

        $date_chk = false;
        if ($check_out > $check_in) {
            $date_chk = true;
        }
        if ($date_chk == true) {
            $q2 = "SELECT room_type_id FROM `room_type` WHERE `room_type_name` = '$room_type_name' ;" ;
            $r2 = mysqli_query($conn, $q2) or die(mysqli_error($conn));

            $room_type_id = 0;
            if($r2) {
                $row2 = mysqli_fetch_row($r2);
                $room_type_id = $row2[0];
            }

            $q3 = "SELECT b_id FROM `bookings` WHERE `check_out` < '$check_in';" ; 
            $r3 = mysqli_query($conn, $q3) or die(mysqli_error($conn));
            $row3_count = mysqli_num_rows($r3);

            $all_type_rooms_available_array = array();

            $i=0;
            while($row3 = mysqli_fetch_row($r3)) {
                $q4 = "SELECT DISTINCT room_id FROM `room_booked` WHERE `b_id` = '$row3[0]' ;";
                $r4 = mysqli_query($conn, $q4) or die(mysqli_error($conn));
                if($r4) {$row3_1 = mysqli_fetch_array($r4,MYSQLI_NUM);
                $all_type_rooms_available_array[$i++] = $row3_1[0]; }
            }

            $array1 = array();
            $array2 = array();

            $q5 = "SELECT room_id FROM `room`" ;
            $r5 = mysqli_query($conn, $q5) or die(mysqli_error($conn));
            $row5_count = mysqli_num_rows($r5);

            $i=0;
            while($row5 = mysqli_fetch_row($r5)) {
                $array1[$i++] = $row5[0];
            }

            $q6 = "SELECT DISTINCT room_id FROM `room_booked`" ;
            $r6 = mysqli_query($conn, $q6) or die(mysqli_error($conn));
            $row6_count = mysqli_num_rows($r6);

            $i=0;
            while($row6 = mysqli_fetch_row($r6)) {
                $array2[$i++] = $row6[0];
            }

            $all_type_rooms_never_booked = array_values(array_diff($array1, $array2));

            $all_type_total_rooms_available = array_merge($all_type_rooms_available_array, $all_type_rooms_never_booked);
            array_unique($all_type_total_rooms_available);

            $q7 = "SELECT room_id FROM `room_type_rel` WHERE `room_type_id` != '$room_type_id' ;" ;
            $r7 = mysqli_query($conn, $q7) or die(mysqli_error($conn));
            $row7_count = mysqli_num_rows($r7);

            $array3 = array();

            $i=0;
            while($row7 = mysqli_fetch_row($r7)) {
                $array3[$i++] = $row7[0];
            }

            $rooms_available_array = array_values(array_diff($all_type_total_rooms_available, $array3));

            $rooms_available = sizeof($rooms_available_array);

            if($rooms_available < $total_rooms) {
                $room_error = true;
            }
            else {
                $q8 = "INSERT INTO `bookings` (`check_in`, `check_out`, `payment_type`) VALUES ('$check_in', '$check_out', '$payment_type');";

                for($i=0; $i< $total_rooms; $i++) {
                    $r8 = mysqli_query($conn, $q8) or die(mysqli_error($conn));
                    if(!$r8) $showError = true;
                }

                $q9 = "SELECT b_id FROM `bookings` order by `b_id` DESC LIMIT $total_rooms;";
                $r9 = mysqli_query($conn, $q9) or die(mysqli_error($conn));
               
                $b_id_array = array();
                
                $i=0;
                while($row9 = mysqli_fetch_row($r9)) {
                    $b_id_array[$i++] = $row9[0];
                }

                for($i=0; $i< $total_rooms; $i++) {
                    $q10 = "INSERT INTO `room_booked` (`g_id`, `room_id`, `b_id`) VALUES ('$g_id', '$rooms_available_array[$i]', '$b_id_array[$i]');";
                    $r10 = mysqli_query($conn, $q10) or die(mysqli_error($conn));
                }

                $q11 = "SELECT cost FROM `room_type` WHERE `room_type_id` = '$room_type_id';";
                $r11 = mysqli_query($conn, $q11) or die(mysqli_error($conn));
                
                if($r11) {
                    $row11 = mysqli_fetch_row($r11);
                    $total_cost = $total_rooms * $row11[0];
                }

                $showAlert = true;
            }
        } else {
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
                    <h2 style="text-align:center;">Booking page</h2>

                    <div class="col-25">
                        <label for="room_type">Room Type</label>
                    </div>

                    <div class="col-75">
                    	<select name="room_type_list">
                    		<option value="" disabled selected>select room type</option>
                    		<?php
                    		include 'config.php';
                    		$res1 = mysqli_query($conn,"SELECT room_type_name FROM `room_type`;") or die(mysqli_error($conn));
                    		while($row = mysqli_fetch_row($res1)) {
                    			?>
                    			<option value="<?php echo $row[0]; ?>" > <?php echo $row[0] ?> </option>
                    			<?php
                    		}
                    		?>
                    	</select>
                    </div>

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
                        <label for="total_rooms">Total rooms</label>
                    </div>
                    <div class="col-75">
                        <input type="text" id="total_rooms" name="total_rooms" placeholder="Total no. of rooms eg. 1, 2, 3..." required>
                    </div>

                    <div class="submit" style="text-align: center;">
                        <input type="submit" name="create" value="Book" id="book">
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
    var showAlert = '<?php echo $showAlert; ?>';
    var showError = '<?php echo $showError; ?>';
    var room_error = '<?php echo $room_error; ?>';
    var total_cost = '<?php echo $total_cost; ?>';

    if(room_error == 1) {
        var rooms_available = '<?php echo $rooms_available; ?>';
        $(function(){
            Swal.fire({
                position: 'centre',
                icon: 'error',
                text: "Total Rooms available for given type is: " + rooms_available,
                title: 'ERROR!',
                showConfirmButton: false,
                timer: 2000
            })
        });
    }
    else if (showAlert == 1) {
        $(function(){
            Swal.fire({
                position: 'centre',
                icon: 'success',
                text: "Total Amount to be paid is: " + total_cost,
                title: 'Booking is successful!',
                showConfirmButton: false,
                timer: 2000
            })
        });
    } else if (showError == 1) {
        $(function(){
            Swal.fire({
                position: 'centre',
                icon: 'error',
                title: 'Correct the form!',
                showConfirmButton: false,
                timer: 2000
            })
        });
    }
</script>
</body>
</html>