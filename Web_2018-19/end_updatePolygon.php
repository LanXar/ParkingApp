<?php
include 'db.php';

if($_SERVER["REQUEST_METHOD"] == "POST") {

updatePolygon($_POST['poly-id'], $_POST['park-spaces'], $_POST['poly-distr']);

}
?>