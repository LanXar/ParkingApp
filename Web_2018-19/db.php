<?php
include 'geometry.php';

/**
 * Constants
 */
$TABLE_POLYGON_NAME = "Polygon";
$TABLE_VERTICES_NAME = "Poly_Vertices";
$TABLE_ADMIN = "Admins";

$DEFAULT_CENTROID_LON = 0;
$DEFAULT_CENTROID_LAT = 0;
$DEFAULT_DEMAND_CAT = "Σταθερή";
$DEFAULT_PARK_SPACES = "50";

$SERVERNAME = "localhost";
$USERNAME = "root";
$PASSWORD = "pass1234";
$DBNAME = "webdata";

/**
 * addKML
 *   Given a filename parses the kml file and reads information about
 *   the polygons of a city. Calculates polygon centroids.
 *   Saves the info in the DB.
 */
function addKML($filename) {
    $kml=simplexml_load_file($filename) or die("Error: Cannot load kml file.");
    echo "Loaded the kml file <br>";

    // Create connection
    $conn = new mysqli($GLOBALS['SERVERNAME'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DBNAME']);
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $places = $kml->Document->Folder->Placemark;
    foreach ( $places as $place ) {
        // 
        // Population
        // 
        $population = (string) $place->description->ul->li[2]->span;
        // check whether population field exists.
        if (empty($population)) {
            $population = "0";
        }

        // 
        // Coordinates
        // 
        $lon = array();
        $lat = array();
        $coordinates = (string) $place->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
        $cent = array("lon" => $GLOBALS['DEFAULT_CENTROID_LON'], "lat" => $GLOBALS['DEFAULT_CENTROID_LAT']);
        if (!empty($coordinates)) {
            $coordinates = explode(" ", $coordinates);
            foreach ( $coordinates as $coord ) {
                // Coordinates to floats.
                $C = explode(",", $coord);
                array_push($lon, doubleval($C[0]));
                array_push($lat, doubleval($C[1]));
            }
            $cent = polygon_centroid($lon, $lat);
        }
        
        // 
        // Add polygon to DB
        // 
        $sql_polygon = "INSERT INTO Polygon (longitude_centr, latitude_centr, population, category_demand, park_spaces)
        VALUES ('".$cent["lon"]."', '".$cent["lat"]."', '".$population."', '".$GLOBALS['DEFAULT_DEMAND_CAT']."', '".$GLOBALS['DEFAULT_PARK_SPACES']."')";

        if ($conn->query($sql_polygon) === TRUE) {
            // echo "New polygon record created successfully";
            // echo "<br>";
        } else {
            die("DB error: " . $conn->connect_error);
        }

        // 
        // Add the vertices to the DB
        // 
        $poly_id = $conn->insert_id;
        
        for ($i = 0; $i < sizeof($lon); $i++) {
            $sql_vert = "INSERT INTO Poly_Vertices (poly_id, longitude, latitude)
            VALUES ('".$poly_id."', '".$lon[$i]."', '".$lat[$i]."')";
            $err = $conn->query($sql_vert);

            if ($err === TRUE) {
                // echo "New vertex record created successfully";
                // echo "<br>";
            } else {
                die("DB error: " . $conn->connect_error);
            }
        }
    }

    echo "Data added successfully <br>";
    $conn->close();
}

/**
 * deleteDB
 * 
 * Deletes all content from the polygon and vertices tables.
 */
function deleteDB() {
    // Create connection
    $conn = new mysqli($GLOBALS['SERVERNAME'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DBNAME']);
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $sql = "TRUNCATE TABLE ";
    
    if ($conn->query($sql.$GLOBALS['TABLE_POLYGON_NAME']) === TRUE) {
        // echo "Polygon entries deleted successfully";
        // echo "<br>";
    } else {
        die("DB error: " . $conn->connect_error);
    }

    if ($conn->query($sql.$GLOBALS['TABLE_VERTICES_NAME']) === TRUE) {
        // echo "Polygon vertices entries deleted successfully";
        // echo "<br>";
    } else {
        die("DB error: " . $conn->connect_error);
    }

    $conn->close();
}

/**
 * updatePolygon
 * Updates the park_spaces and category_demand entries of a polygon.
 * Attention! park_spaces and category_demand should always have a value.
 */
function updatePolygon($poly_id, $park_spaces, $category_demand) {
    // Create connection
    $conn = new mysqli($GLOBALS['SERVERNAME'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DBNAME']);
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        echo "ERROR";
    }

    // TODO: Έλεγχος αν η κατανομή υπάρχει.

    $sql = "UPDATE ".$GLOBALS['TABLE_POLYGON_NAME']." SET park_spaces='".$park_spaces."', category_demand='".$category_demand."' WHERE id = ".$poly_id;

    if ($conn->query($sql) === TRUE) {
        echo "OK";
    } else {
        echo "ERROR";
    }
}

/**
 * Get the polygons rows from the database.
 */
function getPolygons() {
    // Create connection
    $conn = new mysqli($GLOBALS['SERVERNAME'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DBNAME']);
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM ".$GLOBALS['TABLE_POLYGON_NAME'];

    $result = $conn->query($sql);

    $polygons = array();
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $polygons[] = $row;
        }
    } else {
        echo "0 results";
    }

    $conn->close();

    return $polygons;
}

/**
 * Get the vertices of the polygons from the database
 */
function getVertices() {
    // Create connection
    $conn = new mysqli($GLOBALS['SERVERNAME'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DBNAME']);
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM ".$GLOBALS['TABLE_VERTICES_NAME'];

    $result = $conn->query($sql);

    $vertices = array();
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            $vertices[] = $row;
        }
    } else {
        echo "0 results";
    }

    $conn->close();

    return $vertices;
}

/**
 * Validate the credentials of the admin
 */
function adminExists($username, $password) {
    // Create connection
    $conn = new mysqli($GLOBALS['SERVERNAME'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DBNAME']);
    $conn->set_charset("utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT password FROM ".$GLOBALS['TABLE_ADMIN']." WHERE username='".$username."'" ;
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if ($row['password'] === $password) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function timeavailable($hour, $min) {
    // Create connection
    $connect = mysqli_connect($GLOBALS['SERVERNAME'], $GLOBALS['USERNAME'], $GLOBALS['PASSWORD'], $GLOBALS['DBNAME']);
    $connect->set_charset("utf8");
    
    // Check connection
    if (!$connect) {
        die("Connection failed: " . mysqli_connect_error());
    }

  
    $sql     = "SELECT time , probability , demand_area FROM Katanomes ";
    $sql1    = "SELECT id , category_demand , population , park_spaces  FROM Polygon ";
    $result  = mysqli_query($connect, $sql );
    $result1 = mysqli_query($connect, $sql1 );

    if ( mysqli_num_rows($result) > 0 ) {
        $rows1 = array();
        while ($row = mysqli_fetch_assoc($result) ) 
        {
            $row["probability"] = ((float) $row["probability"]);
            $rows1[] = $row;

        }
    }

    if ( mysqli_num_rows($result1) > 0 ) {
        $rows2 = array();
        while ($row = mysqli_fetch_assoc($result1) ) 
        {
            $row["population"] = 0.2 * ((float) $row["population"]);
            $rows2[] = $row;
        }
    }

    $space_avail= array();
    foreach( $rows2 as $row2)
    {
        foreach( $rows1 as $row1)
        {
           if( $hour == $row1["time"] && $row2["category_demand"] == $row1["demand_area"] )
            {
                $id = $row2["id"];
                $prob = $row2["population"] / $row2["park_spaces"] + $row1["probability"];
                if( $prob > 1)
                {
                    $prob = 1;
                }
                $space_avail[] = array("id" => $id, "probability" => $prob);
            }
        } 
    }
    
    mysqli_close($connect);
    
    return json_encode($space_avail, JSON_UNESCAPED_UNICODE);
}
?>