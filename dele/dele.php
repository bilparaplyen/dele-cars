<?php
/**
 * Plugin Name: Dele
 * Description: Display map of cars from app.dele.no. Use in page [carmap url="https://app.dele.no" token="valid jwt token to api.mapbox.com" lat="60.39078164" lon="5.32055452" zoom="11"]
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
*/

//<!DOCTYPE html>

// Add leaflet stylesheet:

// [carmap url="https://app.dele.no" key="<mapbox.com api key>"]
function carmap_func( $atts ) {
  $a = shortcode_atts( array(
    'url' => "https://app.dele.no",
    'token' => '',
    'lat' => '60.39078164',
    'lon' => '5.32055452',
    'zoom' => '11',
  ), $atts );
  $ts = new DateTimeZone('Europe/Oslo');
  $start = new DateTime("now", $ts);
  $start = $start->format('Y-m-d\TH:i:s');
  $end = new DateTime("+3 hours", $ts);
  $end = $end->format('Y-m-d\TH:i:s');
  $url = $a['url'];
  $fn="{$url}/api/search?start=$start&"
    . "end=$end&location=%7B%22type%22%3A%22Point%22%2C%22"
    . 'coordinates%22%3A%5B5.32055452%2C60.39078164%5D%7D&'
    . 'filters=%7B%22groups%22%3A%5B%5D%2C%22minSeats%22%3A1%2C%22maxSeats'
    . '%22%3A9%2C%22carIds%22%3A%5B%5D%2C%22locationIds%22%3A%5B%5D%7D';
  $json_str=file_get_contents($fn);
  $json = json_decode($json_str);
  $url = json_encode($a['url']);
  $token = json_encode($a['token']);

  if ($json == NULL) {
    $json_str="false";
  }
  $output = "<script type='text/javascript'>"
  ."dele_data=$json_str;"
  ."dele_url=$url;"
  ."dele_lat={$a['lat']};"
  ."dele_lon={$a['lon']};"
  ."dele_zoom={$a['zoom']};"
  ."</script>"
    ."<div id='mapid'></div>"
    ."<script type='text/javascript'>dele_createMap($token, $url)</script>";

  if ($json == NULL) {
    $json_str="false";
  }
  return $output;
}
add_action('wp_enqueue_scripts', 'add_leaflet_map_api');
add_shortcode( 'carmap', 'carmap_func' );

function add_leaflet_map_api() {
  wp_register_style(
    'leaflet_css',
    'https://unpkg.com/leaflet@1.6.0/dist/leaflet.css'
  );
  wp_enqueue_style( 'leaflet_css' );
  wp_register_style(
    'markercluster_css',
    'https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.css'
  );
  wp_enqueue_style( 'markercluster_css' );
  wp_register_style(
    'markercluster_default_css',
    'https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/MarkerCluster.Default.css'
  );
  wp_enqueue_style( 'markercluster_default_css' );
  wp_register_style('carmap_css', plugins_url('dele/css/carmap.css'));
  wp_enqueue_style( 'carmap_css' );
  wp_enqueue_script(
    'leaflet',
    'https://unpkg.com/leaflet@1.6.0/dist/leaflet.js'
  );
  wp_enqueue_script(
    'markercluster',
    'https://cdnjs.cloudflare.com/ajax/libs/leaflet.markercluster/1.4.1/leaflet.markercluster.js'
  );
  wp_enqueue_script( 'carmap', plugins_url('dele/js/carmap.js'));
}
