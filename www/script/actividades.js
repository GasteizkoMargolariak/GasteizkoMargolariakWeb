/**
 * Expands or contracts a past activity.
 * @param id Activity ID.
 */
function showPastActivity(id){
    if (document.getElementById("past_activity_details_" + id).clientHeight > 0){
        document.getElementById("past_activity_details_" + id).style.maxHeight = '0em';
        setTimeout(function(){document.getElementById('slid_past_activity_' + id).src = '/img/misc/slid-right.png';}, 500);
    }
    else{
        document.getElementById('past_activity_details_' + id).style.maxHeight = '40em';
        setTimeout(function(){document.getElementById('slid_past_activity_' + id).src = '/img/misc/slid-down.png';}, 500);
    }
    document.getElementById('slid_past_activity_' + id).style.opacity = '0';
    setTimeout(function(){document.getElementById('slid_past_activity_' + id).style.opacity = '1';}, 500);
}

/**
 * Toggles a drop-down list.
 * @param name. Element name, same as the id without the 'list_'.
 */
function toggleElement(name){
    if (document.getElementById("list_" + name).clientHeight > 0){
        document.getElementById("list_" + name).style.maxHeight = '0em';
        setTimeout(function(){document.getElementById('slid_' + name).src = '/img/misc/slid-right.png';}, 500);
    }
    else{
        document.getElementById("list_" + name).style.maxHeight = '90em';
        setTimeout(function(){document.getElementById('slid_' + name).src = '/img/misc/slid-down.png';}, 500);
    }
    document.getElementById('slid_' + name).style.opacity = '0';
    setTimeout(function(){document.getElementById('slid_' + name).style.opacity = '1';}, 500);
}
