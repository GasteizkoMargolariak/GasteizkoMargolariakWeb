
/**
 * Deletes a post from the database.
 * 
 * @param id Post id.
 * @param title Post title.
 */
function delete_post(id, title){
    if (confirm("Seguro que deseas borrar el post '" + title + "'? Esta accion es irreversible.")){

        var xmlhttp;
        if (window.XMLHttpRequest){
            xmlhttp=new XMLHttpRequest();
        }
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                location.reload();
            }
        }
        xmlhttp.open("GET","/blog/delete/delete.php?p=" + id, true);
        xmlhttp.send();
    }
}
