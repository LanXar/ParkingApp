<?php
/**
 * Calculates the area of a polygon given the lon and lat
 * of the vertices in clockwise or counterclockwise order.
 */
function polygon_area($lon, $lat) {
    $x = $lon;
    $y = $lat;

    // append first value to make computations easier
    $x[] = $lon[0];
    $y[] = $lat[0];

    $A = 0;
    for ($i = 0; $i < sizeof($x) - 1; $i++) {
        $A += $x[$i] * $y[$i+1] - $x[$i+1] * $y[$i];
    }

    $A = $A / 2;

    return $A;
}

/**
 * Calculate the centroid of a polygon
 */
function polygon_centroid($lon, $lat) {
    $x = $lon;
    $y = $lat;

    // append first value to make computations easier
    $x[] = $lon[0];
    $y[] = $lat[0];

    $Cx = 0;
    $Cy = 0;
    for ($i = 0; $i < sizeof($x) - 1; $i++) {
        $a = ($x[$i] * $y[$i+1] - $x[$i+1] * $y[$i]);
        $Cx += ($x[$i] + $x[$i+1]) * $a;
        $Cy += ($y[$i] + $y[$i+1]) * $a;
    }

    $A = polygon_area($lon, $lat);
    $b = 6 * $A;
    $Cx /= $b;
    $Cy /= $b;

    return array("lon" => $Cx, "lat" => $Cy);
}
?>