<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Test Map</title>
  <style>
    html, body, #map {
      height: 100%;
      margin: 0;
      padding: 0;
    }
  </style>
</head>
<body>

<div id="map"></div>

<script>
  function initMap() {
    new google.maps.Map(document.getElementById("map"), {
      center: { lat: -4.8, lng: 104.9 },
      zoom: 8,
      mapTypeId: "roadmap"
    });
  }
</script>



</body>
</html>
