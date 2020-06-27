<?php
/**
 * Plugin Name: Dele
 */

//<!DOCTYPE html>

// Add leaflet stylesheet:

// [carmap url="https://app.dele.no/api/search"]
function carmap_func( $atts ) {
  $a = shortcode_atts( array(
    'url' => "https://app.dele.no/api/search",
    'token' => '',
  ), $atts );
  $start = date('Y-m-d\TH:00:00', time()+3600*3);
  $end = date('Y-m-d\TH:00:00',  time()+3600*4);
  $fn="{$a[url]}?start=$start&"
    . "end=$end&location=%7B%22type%22%3A%22Point%22%2C%22"
    . 'coordinates%22%3A%5B5.32055452%2C60.39078164%5D%7D&'
    . 'filters=%7B%22groups%22%3A%5B%5D%2C%22minSeats%22%3A1%2C%22maxSeats'
    . '%22%3A9%2C%22carIds%22%3A%5B%5D%2C%22locationIds%22%3A%5B%5D%7D';
  $json_str=file_get_contents($fn);
  $json = json_decode($json_str);
  if ($json == NULL) {
    $json_str="false";
  }
  $output = "<script type='text/javascript'>"
    ."data=$json_str;"
    ."</script>"
    ."<div id='mapid'></div>"
    ."<script type='text/javascript'>createMap('{$a[token]}')</script>";

  if ($json == NULL) {
    $json_str="false";
  }
  return $output.plugins_url('dele/js/carmap.js');
}
add_action('wp_enqueue_scripts', 'add_leaflet_map_api');
add_shortcode( 'carmap', 'carmap_func' );

function add_leaflet_map_api() {
  wp_register_style( 'leaflet_css', 'https://unpkg.com/leaflet@1.6.0/dist/leaflet.css');
  wp_enqueue_style( 'leaflet_css' );
  wp_register_style('carmap_css', plugins_url('dele/css/carmap.css'));
  wp_enqueue_style( 'carmap_css' );
  wp_enqueue_script( 'leaflet', 'https://unpkg.com/leaflet@1.6.0/dist/leaflet.js');
  wp_enqueue_script( 'carmap', plugins_url('dele/js/carmap.js'));
}


return;

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
        Object.defineProperty(String.prototype, 'hashCode', {
            value: function() {
                var hash = 0, i, chr;
                for (i = 0; i < this.length; i++) {
                chr   = this.charCodeAt(i);
                hash  = ((hash << 5) - hash) + chr;
                hash |= 0; // Convert to 32bit integer
                }
                return hash;
            }
        });
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
            var features = {};
            var style = {
                "weight": 7,
            };
            for (let index = 0; index < data.cars.length; index++) {
                const car = data.cars[index];
                const key = JSON.stringify(car.location.geojson).hashCode();
                var feature = L.geoJSON(car.location.geojson, {style:style});
                var point = feature.getBounds().getCenter();
                if(!(key in features)) {
                    features[key] = true;
                    feature.addTo(mymap);
                }
                var marker = L.marker(point).addTo(mymap);
                var popup = "<div>";
                popup = "<h3>" + car.model + "</h3>";
                popup += '<img style="max-width:100%"';
                popup += 'src="https://app.dele.no' + car.iconUrl + '" />';
                popup += "</div>";
                marker.bindPopup(popup);
            }

        </script>
    </body>
</html>
