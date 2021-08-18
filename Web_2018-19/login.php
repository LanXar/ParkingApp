<?php
include "db.php";
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST") {
	
	if (adminExists($_POST['username'], $_POST['pass'])) {				
		$_SESSION['login_user'] = $_POST['username'];
		header("location: home.php");
	} else {
		$error = "Your Login Name or Password is invalid";
	}
}
?>

<!DOCTYPE html>
<html lang="gr">
	<head>
		<title>Admin's Login</title>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">	
		<link rel="icon" type="image/png" href="images/icons/car.ico"/>
		<link rel="stylesheet" type="text/css" href="fonts/Linearicons-Free-v1.0.0/icon-font.min.css">
		<link rel="stylesheet" type="text/css" href="css/util.css">
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>	
		<div class="limiter">
			<div class="container-login100" style="background-image: url('images/parking1.jpg');">
				<div class="wrap-login100 p-t-30 p-b-50">
					<span class="login100-form-title p-b-41">
						Είσοδος διαχειριστή
					</span>
					<form method="POST" class="login100-form validate-form p-b-33 p-t-5">

						<div class="wrap-input100 validate-input" data-validate = "Enter username">
							<input class="input100" type="text" name="username" placeholder="Όνομα χρήστη">
							<span class="focus-input100" data-placeholder="&#xe82a;"></span>
						</div>

						<div class="wrap-input100 validate-input" data-validate="Enter password">
							<input class="input100" type="password" name="pass" placeholder="Κωδικός">
							<span class="focus-input100" data-placeholder="&#xe80f;"></span>
						</div>

						<div class="container-login100-form-btn m-t-32">
							<button class="login100-form-btn">
								Είσοδος
								<a href="home.php"></a>
							</button>
						</div>

					</form>
				</div>
			</div>
		</div>
		<div id="dropDownSelect1"></div>
		
		<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
		<script src="vendor/animsition/js/animsition.min.js"></script>
		<script src="vendor/select2/select2.min.js"></script>
		<script src="vendor/daterangepicker/moment.min.js"></script>
		<script src="vendor/daterangepicker/daterangepicker.js"></script>
		<script src="vendor/countdowntime/countdowntime.js"></script>
		<script src="js/main.js"></script>
		<script type="text/javascript">
			document.getElementById("homepage").onclick = function () {
				location.href = "../home.php";
			};
		</script>
	</body>
</html>