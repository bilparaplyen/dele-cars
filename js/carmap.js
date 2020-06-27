function hashCode(str) {
    var hash = 0, i, chr;
    for (i = 0; i < str.length; i++) {
        chr = str.charCodeAt(i);
        hash = ((hash << 5) - hash) + chr;
        hash |= 0; // Convert to 32bit integer
    }
    return hash;
}

//Create a map with all the cars
function createMap(token) {
    var mymap = L.map('mapid').setView([60.39078164, 5.32055452], 13);
    L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token={accessToken}', {
        attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
        maxZoom: 18,
        id: 'mapbox/streets-v11',
        tileSize: 512,
        zoomOffset: -1,
        accessToken: token,
    }).addTo(mymap);
    var features = {};
    var style = {
        "weight": 7,
    };
    for (let index = 0; index < data.cars.length; index++) {
        const car = data.cars[index];
        const key = hashCode(JSON.stringify(car.location.geojson));
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
}
