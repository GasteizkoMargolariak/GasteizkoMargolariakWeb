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
    document.getElementById('map_container').style.opacity = 0;
    document.getElementById('map_container').style.display = 'none';
}

function showMap(){
    document.getElementById('map_container').style.display = 'block';
    document.getElementById('map_container').style.opacity = 1;
    return document.getElementById('map');
}

function showMapRoute(route){
    map = showMap();
    //TODO: OSM
}

function showMapPoint(lat, lon){
    map = showMap();
    //TODO: OSM
}
