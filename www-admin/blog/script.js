function delete_post(id, title){
    if (confirm("Seguro que deseas borrar el post '" + title + "'? Esta accion es irreversible.")){
        //window.location.href = "/blog/delete/delete.php?p=" + id;
        
        var xmlhttp;
        if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else{// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function(){
            if (xmlhttp.readyState==4 && xmlhttp.status==200){
                //document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
                location.reload();
            }
        }
        xmlhttp.open("GET","/blog/delete/delete.php?p=" + id, true);
        xmlhttp.send();
        
        //location.reload();
    }
}