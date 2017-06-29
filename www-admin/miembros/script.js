function populate_table(filter){
    console.log("Filter: " + filter);
    var table = document.getElementById("member_table");
    for (var i = 1, row; row = table.rows[i]; i++) {
        content = row.innerHTML;
        content = content.substring(0, content.lastIndexOf('<td'));
        var rex = /(<([^>]+)>)/ig;
        content = content.replace(rex, "");
        content = content.replace(" ", "");
        filter = filter.replace(" ", "");
        content = content.toLowerCase();
        filter = filter.toLowerCase();
        if (content.indexOf(filter) > -1)
            row.style.display = 'table-row';
        else
            row.style.display = 'none';
    }
}