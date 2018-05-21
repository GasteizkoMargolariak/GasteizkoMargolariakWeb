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

function updateItineraryStart(server, id, src){
    // TODO: Validate date
    // TODO: Fetch activity date
    if (dbUpdate(server, "activity_itinerary", "start", "TIME", id, src)){
        // TODO: Update input value
    }
}

function dbUpdate(server, table, column, type, id, src){
    var value = src.value;
    var x = new XMLHttpRequest();
    x.open("GET", server + "/functions/db_update.php?table=" + table + "&column=" + column + "&type=" + type + "&id=" + id + "&value=" + encodeURI(value), true);
    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    x.onreadystatechange = function(){
        if(x.readyState == 4){
            if(x.status == 200){
                // TODO; Update src!
                return true;
            }
            else{
                return false;
            }
        }
    }
}

function deleteItinerary(id){
    var value = src.value;
    var x = new XMLHttpRequest();
    x.open("GET", server + "/functions/db_delete.php?table=itinerary&id=" + id, true);
    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    x.onreadystatechange = function(){
        if(x.readyState == 4){
            if(x.status == 200){
                document.getElementById("itinerary_" + id).style.display = 'none';
                return true;
            }
            else{
                return false;
            }
        }
    }
}

function refreshItinerary(){
    //TODO
}
