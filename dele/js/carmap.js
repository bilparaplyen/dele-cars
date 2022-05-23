function dele_hashCode(str) {
    var hash = 0, i, chr;
    for (i = 0; i < str.length; i++) {
        chr = str.charCodeAt(i);
        hash = ((hash << 5) - hash) + chr;
        hash |= 0; // Convert to 32bit integer
    }
    return hash;
}

//Create a map with all the cars
function dele_createMap(token, url) {
    var map = L.map('mapid', {
        dragging: !L.Browser.mobile,
        tap: !L.Browser.mobile,
        zoom: dele_zoom,
        center: [dele_lat, dele_lon],
    });

    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 20,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: token,
    }).addTo(map);
    var features = {};
    var locations = L.layerGroup();
    var icons={};
    var style = {
        weight: 10,
        fillColor: "#247BA0",
        color: "#247BA0",
        radius: 1,
    };
    // Draw locations:
    for (let index = 0; index < dele_data.cars.length; index++) {
        const car = dele_data.cars[index];
        const key = dele_hashCode(JSON.stringify(car.location.geojson));
        var feature = L.geoJSON(car.location.geojson, { style: style });
        if (car.location.geojson.type == 'Point') {
            feature = L.geoJSON(car.location.geojson, {
                pointToLayer: function (feature, latlng) {
                    return L.circleMarker(latlng, style);
                }
            });
        }
        if (!(key in features)) {
            features[key] = new L.MarkerClusterGroup();
            locations.addLayer(feature);
        }
    }
    // Only show locations when zoomed in:
    map.on('zoomend', function () {
        var zoomlevel = map.getZoom();
        if (zoomlevel < 15) {
            if (map.hasLayer(locations)) {
                map.removeLayer(locations);
            }
        }
        else if (zoomlevel >= 15) {
            if (!map.hasLayer(locations)) {
                map.addLayer(locations);
            }
        }
    });

    // Draw cars
    for (let index = 0; index < dele_data.cars.length; index++) {
        const car = dele_data.cars[index];
        const key = dele_hashCode(JSON.stringify(car.location.geojson));
        var feature = L.geoJSON(car.location.geojson, { style: style });
        var point = feature.getBounds().getCenter();
        var icon = L.icon({
            iconUrl: url + car.iconUrl,
            iconSize: [60, null],
        });
        var marker = L.marker(point, {icon: icon})
        var popup = L.popup({maxWidth: 450})
        var p_content = "<div>";
        p_content = "<h3>" + car.model + "</h3>";
        p_content += "<div><em>" + car.location.name + "</em></div>";
        p_content += '<img style="max-width:100%"';
        p_content += 'src="' + url + car.iconUrl + '" />';
        p_content += "<ul>";
        p_content += "<li> Pris pr time Kr "
            + car.hourlyRate.toFixed(2).replace('.',',')
            + "</li>";
        if (car.carProperties.find(obj => obj.carPropertyGroup == 'Drivstoff'))
        {
            p_content += "<li> Drivstoff: "
                + car.carProperties.find(
                    obj => obj.carPropertyGroup == 'Drivstoff'
                ).carPropertyName
                + "</li>";
        }
        if (car.maxAvailability == 100) {
            p_content += "<li><strong> Helt ledig neste tre timer</strong></li>"
        }
        else if (car.maxAvailability < 100 && car.maxAvailability > 0) {
            p_content += "<li> Delvis ledig neste 3 timer</li>"
        }
        else {
            p_content += "<li> Opptatt neste 3 timer</li>"
        }
        p_content += "</ul>";
        p_content += "</div>";
        popup.setContent(p_content);
        marker.bindPopup(popup);
        features[key].addLayer(marker);
    }
    var all_markers = new L.MarkerClusterGroup({
        spiderfyDistanceMultiplier: 1.8,
    });
    for (var key of Object.keys(features)) {
        all_markers.addLayer(features[key]);
    }
    map.addLayer(all_markers);
}
