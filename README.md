Wordpress plugin for car map from dele.no
=========================================

This plugin displays a map of cars in http://app.dele.no. To initiate the map: 

* Install the wordpress plugin by the zip-file provided here by the latest release
* Create an account on mapbox.com
* Get an API - key / JWT - token valid for the domain you want to show the map. 
* Add a page on your website, with the following shortcode:

```
[carmap url="https://app.dele.no" token="valid jwt token to api.mapbox.com" lat="60.4" lon="5.3" zoom="11"]
```
**lat** and **lon** will be give the centerpoint of the map, while **zoom** is the initial zoom-level


The distribution zip-file in the releases is simply a zip of the dele - folder, so it has the internal structure:

dele.zip:
  - dele
    - css
    - js
    dele.php

![Screenshot](docs/img/screenshot.png?raw=true "Screenshot of map view")
