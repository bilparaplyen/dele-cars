<!DOCTYPE html>
<?php
    $fn='https://app.dele.no/api/search?start=2020-06-24T22:34:13&'
        . 'end=2020-06-25T02:00:00&location=%7B%22type%22%3A%22Point%22%2C%22'
        . 'coordinates%22%3A%5B5.32055452%2C60.39078164%5D%7D&'
        . 'filters=%7B%22groups%22%3A%5B%5D%2C%22minSeats%22%3A1%2C%22maxSeats'
        . '%22%3A9%2C%22carIds%22%3A%5B%5D%2C%22locationIds%22%3A%5B%5D%7D';
    $json_str=file_get_contents($fn);
    $json = json_decode($json_str);
    if ($json == NULL) {
        $json_str="false";
    }
?>
<html>
    <head>
        <title>Map dele.no</title>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.6.0/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.6.0/dist/leaflet.js"></script>
        <style>
            #mapid {
                height: 580px;
                width: 100vw;
                max-width: 850px;
            }
        </style>
        <script type="text/javascript">
        data=<?= $json_str ?>;
        </script>
    </head>
    <body>
        <h1><?php
            echo "Hei";
        ?>
        </h1>
        <div id="mapid"></div>
        <script type="text/javascript">
            var mymap = L.map('mapid').setView([60.39078164, 5.32055452], 13);
            L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
                attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
                maxZoom: 18,
                id: 'mapbox/streets-v11',
                tileSize: 512,
                zoomOffset: -1,
                accessToken: 'pk.eyJ1Ijoiam9uYXNmaCIsImEiOiJja2J0dnNlejgwZGQyMnNsaWc3aWo0MHIxIn0.x-q62SMPb4WqznKVUI2taw'
            }).addTo(mymap);
        </script>
    </body>
</html>
