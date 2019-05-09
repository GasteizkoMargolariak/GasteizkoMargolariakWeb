var photo = [];
var maxId;
var cid;    //Current photo ID;

function populatePhotos(){
    var photos = document.getElementsByClassName('photo_img');
    for (var i = 0; i < photos.length; i ++){
        var path = photos.item(i).src.substring(photos.item(i).src.lastIndexOf('/') + 1);
        photo.push(path);
    }
    maxId = photos.length;
    console.log("photo 0: " + photo[0]);
}


function showPhotoByPath(path){
    //TODO Change content to a spinning wheel or something before real content is loaded
    // Load content
    var x = new XMLHttpRequest();
    x.open("GET", "/galeria/foto.php?path=" + path, true);
    x.send();
    x.onreadystatechange = function(){
    if(x.readyState == 4){
        if(x.status == 200)
            document.getElementById('photo_viewer').innerHTML = x.responseText;
        }
        else
            document.getElementById('photo_viewer').innerHTML = "";
    }

    //Show
    document.getElementById('screen_cover').style.display = 'block';
    document.getElementById('screen_cover').style.opacity = 0.7;
    document.getElementById('photo_viewer').style.display = 'block';
    document.getElementById('photo_viewer').style.opacity = 1;

    //Searh for path in photo array and set cid
    for (var i = 0; i < photo.length; i ++){
        if (photo[i] == path){
            cid = i;
        }
    }
}

function closeViewer(){
    document.getElementById('screen_cover').style.display = 'none';
    document.getElementById('screen_cover').style.opacity = 0;
    document.getElementById('photo_viewer').style.display = 'none';
    document.getElementById('photo_viewer').style.opacity = 0;
    document.getElementById('photo_viewer').innerHTML = "";
}

function keyDown(ev){
    var code = ev.keyCode; //('charCode' in event) ? event.charCode : event.keyCode;
    switch (code){
        case 27:    //ESC
            closeViewer();
            break;
        case 37:    //Left arrow
            if (document.getElementById('photo_viewer').innerHTML != ""){
                if (cid == 0){
                    showPhotoByPath(photo[photo.length - 1]);
                }
                else{
                    showPhotoByPath(photo[cid - 1]);
                }
            }
            break;
        case 39:    //Right arow
            if (document.getElementById('photo_viewer').innerHTML != ""){
                if (cid == photo.length - 1){
                    showPhotoByPath(photo[0]);
                }
                else{
                    showPhotoByPath(photo[cid + 1]);
                }
            }
            break;
    }
}

function scrollPhoto(inc){
    switch(inc){
        case -1: //Previous
            if (document.getElementById('photo_viewer').innerHTML != ""){
                if (cid == 0){
                    showPhotoByPath(photo[photo.length - 1]);
                }
                else{
                    showPhotoByPath(photo[cid - 1]);
                }
            }
            break;
        case 1:    //Next
            if (document.getElementById('photo_viewer').innerHTML != ""){
                if (cid == photo.length - 1){
                    showPhotoByPath(photo[0]);
                }
                else{
                    showPhotoByPath(photo[cid + 1]);
                }
            }
            break;
    }
}
