function togglePlaceRoute(id, src){
    if (src.value == "route"){
        document.getElementById("select_itinerary_" + id + "_place").style.display = 'none';
        document.getElementById("select_itinerary_" + id + "_route").style.display = 'inline';
    }
    else{
        document.getElementById("select_itinerary_" + id + "_place").style.display = 'inline';
        document.getElementById("select_itinerary_" + id + "_route").style.display = 'none';
    }
}

function updateActivityDate(server, id, src){
    // TODO: Validate date.
    dbUpdate(server, "activity", "date", "DATE", id, src);
}

function dbUpdate(server, table, column, type, id, src){
    var value = src.value;
    var x = new XMLHttpRequest();
    x.open("GET", server + "/functions/db_update.php?table=" + table + "&column=" + column + "&type=" + type + "&id=" + id + "&value=" + encodeURI(value), true);
    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    x.onreadystatechange = function(){
        if(x.readyState == 4){
            console.log("STATUS: " + x.status);
            if(x.status == 200){
                // TODO; Update!
            }
        }
    }
}
