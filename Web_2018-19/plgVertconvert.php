<!DOCTYPE html>
<html>

<head>
    <title>Polygon Vertices convertion</title>  
</head>

<body>
    <?php

        // Create connection
        $connect = mysqli_connect('localhost' , 'root' , 'pass1234' , 'webdata');
        $connect->set_charset("utf8");
        // Check connection
        if (!$connect) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql    = "SELECT * FROM Poly_Vertices";
        $result = mysqli_query($connect, $sql);

        if (mysqli_num_rows($result) > 0) {
            $rows = array();
            while ($row = mysqli_fetch_assoc($result)) {

                $rows[] = $row;
            }

            echo json_encode($rows , JSON_UNESCAPED_UNICODE);
        } else {
            echo "no results found";
        }

        mysqli_close($connect);

    ?>
</body>

</html>