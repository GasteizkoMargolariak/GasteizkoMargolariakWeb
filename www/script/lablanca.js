function expand(year){
    console.log('Expanding ' + year);
}

function expandDay(id){
    if (document.getElementById('day_schedule_' + id).style.maxHeight == '120em'){
        document.getElementById('day_schedule_' + id).style.maxHeight = '0em';
        setTimeout(function(){document.getElementById('slid_day_' + id).src = '/img/misc/slid-right.png';}, 500);
    }
    else{
        document.getElementById('day_schedule_' + id).style.maxHeight = '120em';
        setTimeout(function(){document.getElementById('slid_day_' + id).src = '/img/misc/slid-down.png';}, 500);
    }
    document.getElementById('slid_day_' + id).style.opacity = '0';
    setTimeout(function(){document.getElementById('slid_day_' + id).style.opacity = '1';}, 500);
    
}