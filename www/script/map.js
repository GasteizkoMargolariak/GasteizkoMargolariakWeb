/**
 * Closes the floating map.
 */
function hideMap(){
    document.getElementById('map').innerHTML = "";
    document.getElementById('map_container').style.opacity = 0;
    document.getElementById('map_container').style.display = 'none';
}

/**
 * Opens the floating map.
 * Called from {@link #showMapRoute(route, title, format, lat, lon, zoom)} and {@link #showMapPoint(title, lat, lon)}.
 * @param title The title of the map window.
 */
function showMap(title){
    document.getElementById('map_container').style.display = 'block';
    document.getElementById('map_container').style.opacity = 1;
    document.getElementById('map_title').innerHTML = title;
}

/**
 * Draws a route on the map.
 * Calls {@link #showMap(title)}.
 * @param route Route identifier.
 * @param title The title of the map window.
 * @param lat Map center latitude coordinate (default: Downtown Vitoria).
 * @param lon Mat center longitude coordinate (default: Downtown Vitoria).
 * @param zoom Map initial zoom level (default: 15);
 */
function showMapRoute(route, title, lat = 42.846484, lon = -2.673625, zoom = 15){

    // Show the map window.
    showMap(title);

    // Styles for the route.
    var style = {
        'Point': new ol.style.Style({
            image: new ol.style.Circle({
            fill: new ol.style.Fill({
              color: 'rgba(255,255,0,0.4)'
            }),
            radius: 25,
            stroke: new ol.style.Stroke({
                color: '#ff0',
                width: 1
            })
          })
        }),
        'LineString': new ol.style.Style({
          stroke: new ol.style.Stroke({
                color: '#f00',
                width: 3
          })
        }),
        'MultiLineString': new ol.style.Style({
            stroke: new ol.style.Stroke({
                color: '#00008f',
                width: 8
            })
        })
      };

    // Generate vector from gpx file.
    var vectorSrc = new ol.source.Vector({
        url: "/gpx/" + route + ".gpx",
        format: new ol.format.GPX()
    })
    var vector = new ol.layer.Vector({
        source: vectorSrc,
        style: function(feature) {
            return style[feature.getGeometry().getType()];
        }
    });

    // Initialize map, with background and route.
    var map = new ol.Map({
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            }),
            vector
        ],
        target: 'map',
        controls: ol.control.defaults({
            attributionOptions: {
                collapsible: false
            }
        }),
        view: new ol.View({
            center: ol.proj.fromLonLat([lon, lat]),
            zoom: zoom
        })
    });

    // Manually read file to get first and last point.
    var first = [lat, lon];
    var last = [lat, lon];
    var href = "/gpx/" + route + ".gpx";
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function(){
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200){

            // Get first and last point.
            out = xmlhttp.responseText;
            out = out.substring(out.indexOf("<trkpt"));
            firstL = out.substring(out.indexOf("<trkpt") + 7, out.indexOf("/>") - 1);
            lastL = out.substring(out.lastIndexOf("<trkpt") + 7, out.lastIndexOf("/>") - 1);
            first = firstL.split(" ");
            first[0] = parseFloat(first[0].substring(5, first[0].length - 1));
            first[1] = parseFloat(first[1].substring(5, first[1].length - 1));
            last = lastL.split(" ");
            last[0] = parseFloat(last[0].substring(5, last[0].length - 1));
            last[1] = parseFloat(last[1].substring(5, last[1].length - 1));

            // Add start and end marker
            var startIcon = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([first[1], first[0]])),
                name: title,
            });
            startIcon.setStyle(new ol.style.Style({
               image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
                   src: '/img/misc/pinpoint-start.png',
                   scale: 0.25
               }))
            }));
            var endIcon = new ol.Feature({
                geometry: new ol.geom.Point(ol.proj.fromLonLat([last[1], last[0]])),
                name: title,
            });
            endIcon.setStyle(new ol.style.Style({
               image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
                   src: '/img/misc/pinpoint-end.png',
                   scale: 0.25
               }))
            }));
            iconLayer = new ol.layer.Vector({
                source: new ol.source.Vector({
                     features: [startIcon, endIcon]
                })
            });
            map.addLayer(iconLayer);
        }
    }
    xmlhttp.open("GET", href, true);
    xmlhttp.send();

}

/**
 * Puts a marker on the map.
 * Calls {@link #showMap(title)}.
 * @param title The title of the map window.
 * @param lat Marker latitude coordinate.
 * @param lon Marker longitude coordinate.
 */
function showMapPoint(title, lat, lon){

    // Show the map window.
    showMap(title);

    // Set marker icon.
    var icon = new ol.Feature({
        geometry: new ol.geom.Point(ol.proj.fromLonLat([lon, lat])),
        name: title,
    });
    icon.setStyle(new ol.style.Style({
        image: new ol.style.Icon(/** @type {olx.style.IconOptions} */ ({
            src: '/img/misc/pinpoint.png',
            scale: 0.28
        }))
      }));
    iconLayer = new ol.layer.Vector({
        source: new ol.source.Vector({
            features: [icon]
        })
    });

    // Configure the map.
    var map = new ol.Map({
        layers: [
            new ol.layer.Tile({
                source: new ol.source.OSM()
            }),
            iconLayer
        ],
        target: 'map',
        controls: ol.control.defaults({
            attributionOptions: {
                collapsible: false
            }
        }),
        view: new ol.View({
            center: ol.proj.fromLonLat([lon, lat]),
            zoom: 15
        })
    });
}
