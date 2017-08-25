function expand(year){
    console.log('Expanding ' + year);
}

function expandDay(id){
    if (document.getElementById('day_schedule_' + id).style.maxHeight != '' && document.getElementById('day_schedule_' + id).style.maxHeight != '0em'){
        document.getElementById('day_schedule_' + id).style.maxHeight = '0em';
        setTimeout(function(){document.getElementById('slid_day_' + id).src = '/img/misc/slid-right.png';}, 500);
    }
    else{
        document.getElementById('day_schedule_' + id).style.maxHeight = 'initial';
        setTimeout(function(){document.getElementById('slid_day_' + id).src = '/img/misc/slid-down.png';}, 500);
    }
    document.getElementById('slid_day_' + id).style.opacity = '0';
    setTimeout(function(){document.getElementById('slid_day_' + id).style.opacity = '1';}, 500);
}

function hideMap(){
    document.getElementById('map').innerHTML = "";
    document.getElementById('map_container').style.opacity = 0;
    document.getElementById('map_container').style.display = 'none';
}

function showMap(title){
    document.getElementById('map_container').style.display = 'block';
    document.getElementById('map_container').style.opacity = 1;
    document.getElementById('map_title').innerHTML = title;
}

function showMapRoute(route, title, format, lat = 42.846484, lon = -2.673625, zoom = 15){

    showMap(title);

    var map;

    map = new OpenLayers.Map ("map", {
      //controls:[
        //new OpenLayers.Control.Navigation(),
        //new OpenLayers.Control.PanZoomBar(),
        //new OpenLayers.Control.LayerSwitcher(),
        //new OpenLayers.Control.Attribution()],
      maxExtent: new OpenLayers.Bounds(-20037508.34,-20037508.34,20037508.34,20037508.34),
      maxResolution: 156543.0399,
      numZoomLevels: 19,
      units: 'm',
      projection: new OpenLayers.Projection("EPSG:900913"),
        displayProjection: new OpenLayers.Projection("EPSG:4326")
    });

    map.addLayer(new OpenLayers.Layer.OSM());
    var lonLat = new OpenLayers.LonLat(lon, lat).transform(new OpenLayers.Projection("EPSG:4326"), new OpenLayers.Projection("EPSG:900913"));
    map.setCenter (lonLat, zoom);

    if (format == "osm"){
        // Add the layer with the osm route.
        var layer = new OpenLayers.Layer.Vector(title, {
            strategies: [new OpenLayers.Strategy.Fixed()],
            protocol: new OpenLayers.Protocol.HTTP({
                url: "/osm/" + route + ".xml",
                format: new OpenLayers.Format.OSM()
            }),
            style: {strokeColor: "green", strokeWidth: 10, strokeOpacity: 0.8},
            projection: new OpenLayers.Projection("EPSG:4326")
        });
        map.addLayers([layer]);
    }
    else if (format == "gpx"){
        // Add the Layer with the GPX Track
        var layer = new OpenLayers.Layer.Vector(title, {
            strategies: [new OpenLayers.Strategy.Fixed()],
            protocol: new OpenLayers.Protocol.HTTP({
                url: "/gpx/" + route + ".gpx",
                format: new OpenLayers.Format.GPX()
            }),
            style: {strokeColor: "green", strokeWidth: 10, strokeOpacity: 0.8},
            projection: new OpenLayers.Projection("EPSG:4326")
        });
        map.addLayer(layer);
    }
}

function showMapPoint(lat, lon, title){
    showMap(title);

    var map = new OpenLayers.Map("map");
    map.addLayer(new OpenLayers.Layer.OSM());

    var lonLat = new OpenLayers.LonLat(lon ,lat).transform(new OpenLayers.Projection("EPSG:4326"), map.getProjectionObject());
    var zoom = 16;

    var markers = new OpenLayers.Layer.Markers(title);
    map.addLayer(markers);
    markers.addMarker(new OpenLayers.Marker(lonLat));
    map.setCenter (lonLat, zoom);
}
