var loc = [40.64074757764, 22.944454445724];

// Initialize map. Set view's loc and zoom
var mymap = L.map('mapid').setView(loc, 15);

// Add a tile layer
L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token={accessToken}', {
    attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
    maxZoom: 18,
    id: 'mapbox.streets',
    accessToken: 'pk.eyJ1IjoiZmZmLWZveCIsImEiOiJjanBoN2JwZ3MwcXphM3FscWg5ZWtmbWFpIn0.EBWw2P4bLxV9RccX3Yy2iA'
}).addTo(mymap);

function getVertsOf(idx, vertices_rows) {
    var vertices = [];
    for (var i = 0; i < vertices_rows.length; i++) {
        if (parseInt(vertices_rows[i].poly_id) === idx) {
            vertices.push([
                parseFloat(vertices_rows[i].latitude),
                parseFloat(vertices_rows[i].longitude)
            ]);
        }
    }

    return vertices;
}

/**
 * Actions to do when a polygon is clicked
 */
function onPolygonClick(e) {
    console.log('clicked on polygon ' + this.options.id + " coords: " + this.options.centroid);
    console.log('distr: ' + this.options.distr);
    console.log('spaces: ' + this.options.parkSpaces);

    // Relocate marker
    if (marker) { // check
        mymap.removeLayer(marker); // remove
    }
    marker = L.marker(this.options.centroid).addTo(mymap); // set
    mymap.setView(this.options.centroid, 16);

    // Update the information of the polygon form.
    $('#poly-id').val(this.options.id);
    $('#park-spaces').val(this.options.parkSpaces);
    $("#poly-distr").val(this.options.distr).change();
}

/**
 * Create a polygon object and return it.
 */
function createPolygon(vertices, id, distribution, parkSpaces, centroid) {
    var p = L.polygon(vertices, {
        'id': id,
        'distr': distribution,
        'parkSpaces': parkSpaces,
        'centroid': centroid
    }).on('click', onPolygonClick);

    return p;
}

/**
 * Initialize the polygons. Given the db entries
 * create each polygon as a leaflet polygon object.
 * Add some information in the options.
 */
function initPolygons(polygon_rows, vertices_rows) {
    var poly_info = [];
    var ids = [];

    polygon_rows.forEach((row) => {
        var poly_id = parseInt(row.id);
        ids.push(poly_id);
        poly_info.push({
            "id": poly_id,
            "centroid": [parseFloat(row.latitude_centr), parseFloat(row.longitude_centr)],
            "distribution": row.category_demand,
            "parkSpaces": parseInt(row.park_spaces),
            "vertices": []
        });
    });

    vertices_rows.forEach((row) => {
        var poly_id = parseInt(row.poly_id);
        var i = ids.indexOf(poly_id);
        poly_info[i].vertices.push([
            parseFloat(row.latitude),
            parseFloat(row.longitude)
        ]);
    });

    var polygons = [];
    poly_info.forEach((x) => {
        var newPolygon = createPolygon(
            x.vertices, x.id,
            x.distribution, x.parkSpaces,
            x.centroid);
        polygons.push(newPolygon);
    });

    return polygons;
}

/**
 * Renders the polygons on the map and colors them grey.
 */
function renderPolygons(polygons, map) {
    polygons.forEach((p) => {
        p.addTo(map).setStyle({ color: 'grey' });
    });
}

/**
 * Returns the polygon color based on the probability of
 * parking spaces.
 */
function colorOfPr(pr) {
    var color;
    if (pr < 0.6) {
        color = 'green';
    } else if (pr < 0.85) {
        color = 'orange';
    } else {
        color = 'red';
    }

    return color;
}

/**
 * Updates the colors of the polygons, given their parking space probabilities.
 */
function updatePolygons(polygons, polygon_probabilities) {
    polygons.forEach((p) => {
        // Find the % of non free spaces
        var pr = 0.0;
        for (var i = 0; i < polygon_probabilities.length; i++) {
            var id = parseFloat(polygon_probabilities[i].id);
            if (p.options.id == id) {
                pr = parseFloat(polygon_probabilities[i].probability);
                break;
            }
        }

        // Pick the color of the polygon based on the probability
        var color = colorOfPr(pr);

        // Update the color
        p.setStyle({ color: color });
    });
}

/**
 * Initial rendering
 */
var marker = L.marker(loc);
var polygons = initPolygons(polygon_rows, vertices_rows);
renderPolygons(polygons, mymap);