var mapid = '#mapid';
var cont = '#map-container';
var mapLimitsVertical = $('.footer').height() + $('.breadcrumb').height();

// Replace this.mapmargin tag with whatever ID DIV tag you use for the main container viewport
var resize_map = {};
resize_map.mapmargin = 0;
resize_map.minHeight = 415;
resize_map.getHeight = function () {
    this.mapmargin = $(cont).height();
};

resize_map.getHeight();
$(window).bind("resize", resize);
resize(mapid);

function resize() {
    var h = $(window).height();

    $("#page-container").css("height", h);

    // Keep the admin panel column aligned
    var pch = $("#page-container").height();
    $("#poly-info").css("height", pch / 2);
    $("#sim-info").css("height", pch / 2);

    if ($(window).width() >= resize_map.minHeight) {
        $(mapid).css("height", ($(window).height() - resize_map.mapmargin - mapLimitsVertical));
        // setTimeout, to Handle using the Maximize button which needs more time to respond correctly
        setTimeout(function () {
            $(mapid).css("height", ($(window).height() - resize_map.mapmargin - mapLimitsVertical));
        }, 150);
        // Set the map size cannot be smaller than the width on mobile devices.
    } else {
        if ($(window).height() <= resize_map.minHeight) {
            $(mapid).css("height", resize_map.minHeight);
        } else {
            $(mapid).css("height", ($(window).height() - resize_map.mapmargin - mapLimitsVertical));
        }
    }
}