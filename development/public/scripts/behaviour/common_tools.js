function SetMap(lat, long) {
    var map = null;
    var geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(lat, long);

    var mapOptions = {
        zoom: 14,
        center: latlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    }

    map = new google.maps.Map(document.getElementById(lat + "_" + long), mapOptions);

    map.setCenter(latlng);
    var marker = new google.maps.Marker({
        map: map,
        position: latlng
    });
}


function ScrollToElement(target) {
    var topoffset = 30;
    var speed = 800;
    var destination = $(target).offset().top - topoffset;
    jQuery('html:not(:animated), body:not(:animated)').animate( { scrollTop: destination}, speed, function() {
        window.location.hash = target;
    });
    
    return false;
}

var firefoxBrowserVersion = navigator.userAgent.toLowerCase().split("firefox/");

if (firefoxBrowserVersion.length > 1) {
    var majorVersion = parseInt(firefoxBrowserVersion[1].split(".")[0]);

    if (majorVersion >= 10) {
        $(document).ready(function() {
            $("#mainNavigation ul li:not(:first-child)").css({
                "marginTop": "-30px",
                "width": "4em"
            });
        });
    }
}