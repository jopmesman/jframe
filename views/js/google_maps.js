function initializeGoogleMaps() {
  var mapOptions = {
    zoom: 8,
    center: new google.maps.LatLng(-34.397, 150.644),
    mapTypeId: google.maps.MapTypeId.ROADMAP
  };
  var map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
}

function loadGoogleMapsScript(apikey) {
  var script = document.createElement("script");
  script.type = "text/javascript";
  script.src = "https://maps.googleapis.com/maps/api/js?key=" + apikey + "&sensor=true&callback=initializeGoogleMaps";
  alert(script.src);
  document.body.appendChild(script);
}