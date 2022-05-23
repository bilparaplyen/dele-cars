Wordpress plugin for car map from dele.no
=========================================

This plugin displays a map of cars in http://app.dele.no. To initiate the map
on a page you need to add the following:

```
[carmap url="https://app.dele.no" token="valid jwt token to api.mapbox.com" lat="60.4" lon="5.3" zoom="11"]
```

To install: Zip the dele - folder, so it has the internal structure:

dele.zip:
  - dele
    - css
    - js
    dele.php

Upload the plugin to your wordpress installation.

![Screenshot](docs/img/screenshot.png?raw=true "Screenshot of map view")