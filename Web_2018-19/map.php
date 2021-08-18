<?php
    // Create connection
    $connect = mysqli_connect('localhost' , 'root' , 'pass1234' , 'webdata');
    $connect->set_charset("utf8");
    // Check connection
    if (!$connect) {
        // die("Connection failed: " . mysqli_connect_error());
    }

    $sql    = "SELECT * FROM Polygon";
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) > 0) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {
            $rows[] = $row;
        }

        $polygon_rows =  json_encode($rows , JSON_UNESCAPED_UNICODE);
    } else {
        // echo "no results found";
        $polygon_rows = '';
    }

    $sql    = "SELECT * FROM Poly_Vertices";
    $result = mysqli_query($connect, $sql);

    if (mysqli_num_rows($result) > 0) {
        $rows = array();
        while ($row = mysqli_fetch_assoc($result)) {

            $rows[] = $row;
        }

        $vertices_rows = json_encode($rows , JSON_UNESCAPED_UNICODE);
    } else {
        // echo "no results found";
        $vertices_rows = '';
    }

    mysqli_close($connect);
?>


<!DOCTYPE HTML>
<html>

<head>
  <title>ParkingApp Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="keywords" content="Bootstrap Web Templates" />
  <link rel="icon" type="image/png" href="images/icons/car.ico" />
  <script type="application/x-javascript">
        addEventListener("load", function () {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>


  <!---------- CSS map ---------->

  <!-- bootstrap -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO"
    crossorigin="anonymous">

  <!-- map specific js -->
  <link rel="stylesheet" href="css/admin-map.css" />

  <!-- LeafletJS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA=="
    crossorigin="" />

  <!---------- CSS END map ---------->

  <!-- Bootstrap Core CSS -->
  <!-- <link href="css/bootstrap.min.css" rel='stylesheet' type='text/css' /> -->

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
          <li class="breadcrumb-item"> <i> <a style="font-weight:bold" href="manageFile.php">Διαχείρηση Αρχείων</a> </i>
          </li>
          <li class="breadcrumb-item"> <i> <a style="font-weight:bold" href="map.php">Χάρτης</a> </i> </li>
        </ol>

        <!--Εδω να μπει ΧΑΡΤΗΣ-->

        <div class="container-fluid" id="page-container">
          <div class="row">
            <div class="col-12">
              <!-- Admin's map -->
              <div class="container-fluid">
                <div class="row">

                  <!-- Map panel -->
                  <div class="col-9">
                    <!-- Map is here :) -->
                    <div id="mapid"></div>
                    <!-- End of map -->
                  </div>
                  <!-- End of map panel -->

                  <!-- Admin panel -->
                  <div class="col-3" id="admin-panel">
                    <div class="container-fluid">

                      <!-- Polygon information -->
                      <div class="row" id="poly-info">
                        <div class="container-fluid">
                          <div class="row">
                            <h3>Οικοδομικό Τετράγωνο</h3>
                          </div>

                          <div class="row">
                            <form id="poly-info-form" action="end_updatePolygon.php">
                              <div class="form-group">
                                <label for="park-spaces">Θέσεις Στάθμευσης</label>
                                <input type="hidden" name="poly-id" id="poly-id" value="">
                                <input type="text" name="park-spaces" class="form-control" id="park-spaces" value="">
                              </div>

                              <div class="form-group">
                                <label for="poly-distr">Κατανομή Διαθεσιμότητας</label>
                                <select class="form-control form-control-sm" name="poly-distr" id="poly-distr">
                                  <option selected>Κέντρο</option>
                                  <option>Σταθερή</option>
                                  <option>Κατοικία</option>
                                </select>
                              </div>

                              <button type="submit" class="btn btn-primary">Ενημέρωση</button>
                            </form>
                          </div>
                        </div>

                      </div>
                      <!-- End of polygon information -->

                      <!-- Simulation info -->
                      <div class="row" id="sim-info">
                        <div class="container-fluid">
                          <div class="row">
                            <h3>Πληροφορίες Εξομοίωσης</h3>
                          </div>

                          <div class="row">
                            <form method="post" action="end_available.php" id="sim-info-form">
                              <div class="form-group">
                                <label for="sim-time">Ώρα</label>
                                <input type="text" class="form-control" name="sim-time" id="sim-time" value="10:00">
                                <input type="button" class="btn btn-primary" id="btn-dec" value="-">
                                <input type="button" class="btn btn-primary" id="btn-inc" value="+">
                              </div>

                              <div class="form-group">
                                <label for="sim-step">Βήμα</label>
                                <input type="text" class="form-control" id="sim-step" value="00:15">
                              </div>

                              <button type="submit" class="btn btn-primary" id="sim-btn">Εκκίνηση</button>
                            </form>
                          </div>
                        </div>

                      </div>
                      <!-- End of sim info -->

                    </div>
                  </div>
                  <!-- End of admin panel -->

                </div>
              </div>
              <!-- End of Admin's map -->
            </div>
          </div>

          <div class="row footer">
            <!--COPY rights start here-->
            <div class="copyrights">
              <p>© ParkingApp Copyrights 2019</p>
            </div>
            <!--COPY rights end here-->
          </div>
        </div>

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
          <li id="menu-academico"><a href="manageFile.php"><i class="fa fa-list-ul"></i><span>Διαχείρηση Αρχείων</span>
              <div class="clearfix"></div>
            </a>
          </li>
          <li><a href="map.php"><i class="fa fa-map-marker"></i> <span>Χάρτης</span>
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

  <!---------- JAVASCRIPT map ---------->

  <!-- jquery -->
  <script src="js/jquery-2.1.4.min.js"></script>

  <!-- Should precede leaflet -->
  <script src="js/admin-resize-map.js"></script>

  <!-- Make sure you put this AFTER Leaflet's CSS -->
  <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA=="
    crossorigin=""></script>

  <!-- Helper include -->
  <!-- <script src="t_data.js"></script> -->
  <script>
    var polygon_rows = <?php echo $polygon_rows; ?>;
    var vertices_rows = <?php echo $vertices_rows; ?>;
    var polygon_probabilities;
  </script>

  <!-- Map specific javascript -->
  <script src="js/admin-map.js"></script>

  <!-- Ajax polygon update -->
  <script src="js/admin-poly-info-form.js"></script>

  <!-- Simulation controls -->
  <script src="js/admin-sim-info-form.js"></script>

  <!---------- JAVASCRIPT END map ---------->


</body>

</html>