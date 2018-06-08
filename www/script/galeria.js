/**
 * Array with al the photos in the currently opened album.
 * It is populated in {@link populatePhotos()} and read
 * in {@link showPhotoByPath(path)}, {@link scrollPhoto(inc)}
 * and {@link keyDown(ev)}.
 */
var photo = [];

/**
 * ID of the photo currently being viewed.
 * Set and read from {@link showPhotoByPath(path)},
 * {@link scrollPhoto(inc)} and {@link keyDown(ev)}.
 */
var cid;

/**
 * Populates the photo array {@link photo} with the IDs of the photos
 * of the current album.
 */
function populatePhotos(){
    var photos = document.getElementsByClassName('photo_img');
    for (var i = 0; i < photos.length; i ++){
        var path = photos.item(i).src.substring(photos.item(i).src.lastIndexOf('/') + 1);
        photo.push(path);
    }
}

/**
 * Displays the photo viewer and loads a photo from it's URL.
 * It also sets {@link cid} to be able to use the next/previous features.
 * TODO Show a spinning wheel or something before real content is loaded.
 * @param path Photo URL.
 */
function showPhotoByPath(path){
    // Load content
    if(XMLHttpRequest){
        var x = new XMLHttpRequest();
    }
    else{ //IE v<7
        var x = new ActiveXObject("Microsoft.XMLHTTP");
    }
    x.open("GET", "/galeria/load_photo.php?path=" + path, true);
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

/**
 * Closes the photo viewer.
 */
function closeViewer(){
    document.getElementById('screen_cover').style.display = 'none';
    document.getElementById('screen_cover').style.opacity = 0;
    document.getElementById('photo_viewer').style.display = 'none';
    document.getElementById('photo_viewer').style.opacity = 0;
    document.getElementById('photo_viewer').innerHTML = "";
}

/**
 * Reads a keypress and acts accordingly if the viewer is shown:
 *   ESC: Closes the viewer calling {@link closeViewer()}
 *   LEFT/RIGHT ARROW: Shows previous/next photo
 * @param ev Key event.
 */
function keyDown(ev){
    var code = ev.keyCode;
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

/**
 * Shows the previous or next photo in the viewer.
 * Does nothing if there are no more photos.
 * @param inc -1 for the previous photo, 1 for the next one.
 */
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

/**
 * Returns an input element to it's default borders once an error
 * has been corrected.
 * WARNING: If changed in css, change here.
 * @param id The element whose border must be altered.
 */
function defaultInputBorder(id){
    id.style.border = '0.2em solid #0078ff'
}

/**
 * Posts a comment in a photo. Checks required fields and
 * makes an AJAX request. Also it inserts the comment in 
 * the page without needingo to reload.
 * @param photo Photo ID.
 * @param lang User language.
 */
function postComment(photo, lang){

    //Get field values
    var text = document.getElementById('new_comment_text').value;
    var user = document.getElementById('new_comment_user').value;

    //Check fields
    if (text.length == 0){
        defaultBorderStyle = document.getElementById('new_comment_text').style.border;
        document.getElementById('new_comment_text').style.border = '0.4em solid #fc5359';
        document.getElementById('new_comment_text').focus();
        return false;
    }
    if (user.length == 0){
        document.getElementById('new_comment_user').style.border = '0.4em solid #fc5359';
        document.getElementById('new_comment_user').focus();
        return false;
    }

    //Load page
    if(XMLHttpRequest){
        var x = new XMLHttpRequest();
    }
    else{ //IE v<7
        var x = new ActiveXObject("Microsoft.XMLHTTP");
    }
    x.open("POST", "/galeria/comment.php", true);
    var params = "photo=" + photo + "&user=" + user + "&text=" + text + "&lang=" + lang;
    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    x.onreadystatechange = function(){
        if(x.readyState == 4){
            if(x.status == 200){
                document.getElementById("comment_list").innerHTML = x.responseText;
                document.getElementById('new_comment_text').value = '';
                document.getElementById('new_comment_user').value = '';
            }
        }
    }
    x.send(params);
    return false;
}
