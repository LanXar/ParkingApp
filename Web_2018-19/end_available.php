<?php
include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // $hr = $_POST[]


    $time_string = $_POST['sim-time'];
    
    $time =  explode(':', $time_string);

    $time = array_map(intval, $time);
    
    $ans = timeavailable($time[0], $time[1]);

    echo $ans;

}

?>