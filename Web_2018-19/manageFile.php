<?php
include "db.php";

if($_SERVER["REQUEST_METHOD"] == "POST") {
	if (isset($_POST['btnDelete'])) {
        deleteDB();
    }

    if (isset($_POST['btnFile'])) {
        addKML($_FILES["file"]["tmp_name"]);
		header("location: map.php");
    }
}
?>

<!DOCTYPE HTML>
<html>

<head>
    <title>ParkingApp Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="keywords" content="Bootstrap Web Templates" />
    <link rel="icon" type="image/png" href="images/icons/car.ico"/>
    <script type="application/x-javascript">
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' />
    
    <!-- Custom CSS -->
    <link href="css/style.css" rel='stylesheet' type='text/css' />
    <link rel="stylesheet" href="css/haris.css" type="text/css" />
    <!-- Graph CSS -->
    <link href="css/font-awesome.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="js/jquery-2.1.4.min.js"></script>
</head>

<body>
    <div class="page-container">

        <!--/content-inner-->
        <div class="left-content">
            <div class="mother-grid-inner">

                <ol class="breadcrumb">
                    <li class="breadcrumb-item"> <i> <a style="font-weight:bold" href="home.php">Αρχική</a> </i> </li>
                    <li class="breadcrumb-item"> <i> <a style="font-weight:bold" href="manageFile.php">Διαχείρηση Αρχείων</a> </i> </li>
                </ol>

                <div class="bg-img" style="background-image: url('images/parking2.jpg'); " >
                    <form method="post" enctype="multipart/form-data" class="container">
                        <h3 allign="center"> <i style="font-weight:bold; color:rgb(247, 245, 242)"> Διάλεξε KML-αρχείο για ανέβασμα: </i></h3>
                        <input type="file" value="Διάλεξε αρχείο" name="file" id="file">
                        <button type="submit" name="btnFile" class="btn1"> Προσθήκη Αρχείου </button>
                        <button type="delete" onClick="return delete_tables()" name="btnDelete" class="btn2"> Διαγραφή Αρχείου </button>
                    </form>
                </div>

                <!--COPY rights start here-->
                <div class="copyrights">
                    <p>© ParkingApp Copyrights 2019</p>
                </div>
                <!--COPY rights end here-->
            </div>
        </div>

        <!--/sidebar-menu-->
        <div class="sidebar-menu">
            <header class="logo1">
                <a href="#" class="sidebar-icon"> <span class="fa fa-bars"></span> </a>
            </header>
            <div style="border-top:1px ridge rgba(255, 255, 255, 0.15)"></div>
            <div class="menu">
                <ul id="menu">
                    <li><a href="home.php"><i class="fa fa-tachometer"></i> <span>Αρχική</span>
                            <div class="clearfix"></div>
                        </a>
                    </li>
                    <li id="menu-academico"><a href="manageFile.php"><i class="fa fa-list-ul" ></i><span>Διαχείρηση Αρχείων</span> 
                        <div class="clearfix"></div>
                        </a>
                    </li>
                    <li><a href="map.php"><i class="fa fa-map-marker" ></i> <span>Χάρτης</span>
                            <div class="clearfix"></div>
                        </a>
                    </li>
                    <li><a href="login.php"><i class="fa fa-sign-out"></i><span>Έξοδος</span>
                            <div class="clearfix"></div>
                        </a>
                    </li>    
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>
    </div> 

    <!-- toggle tou sidebar -->
    <!-- <script>
        var toggle = true;

        $(".sidebar-icon").click(function () {
            if (toggle) {
                $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
                $("#menu span").css({
                    "position": "absolute"
                });
            } else {
                $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
                setTimeout(function () {
                    $("#menu span").css({
                        "position": "relative"
                    });
                }, 400);
            }

            toggle = !toggle;
        });
    </script> -->

    <!--js -->
    <script src="js/jquery.nicescroll.js"></script>
    <script src="js/scripts.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>
    <!-- /Bootstrap Core JavaScript -->

    <!-- JavaScript -->
    <script src="js/raphael-min.js"></script>
    <script src="js/haris.js"></script>


    <script>
        function delete_tables() {
            var conf = confirm("Θέλετε να διαγραφούν τα στοιχεία;");
            if (conf == true) {
                return true;
            } else {
                return false;
            }
        }
    </script>

</body>

</html>