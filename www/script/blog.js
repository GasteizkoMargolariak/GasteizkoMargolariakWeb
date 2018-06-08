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

/**
 * Redirects to the search page.
 * @param where Only valid value: 'todo'.
 */
function launchSearch(where){
    text = document.getElementById("search_panel_input").value;
    if (text.length > 0)
        window.location.href = "/blog/buscar/" + where + "/" + text;
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
 * Posts a comment in the blog. Checks required fields and
 * makes an AJAX request. Also it inserts the comment in 
 * the page without needingo to reload.
 * @param post Post ID.
 * @param lang User language.
 */
function postComment(post, lang){

    //Get field values
    var text = document.getElementById('new_comment_text').value;
    var user = document.getElementById('new_comment_user').value;

    //Check fields
    if (text.length == 0){
        defaultBorderStyle = document.getElementById('new_comment_text').style.border;
        document.getElementById('new_comment_text').style.border = '0.2em solid #fc5359';
        document.getElementById('new_comment_text').focus();
        return false;
    }
    if (user.length == 0){
        document.getElementById('new_comment_user').style.border = '0.2em solid #fc5359';
        document.getElementById('new_comment_user').focus();
        return false;
    }

    //Load page
    if(XMLHttpRequest){
        var x = new XMLHttpRequest();
    }
    else { // IE v<7
        var x = new ActiveXObject("Microsoft.XMLHTTP");
    }
    x.open("POST", "/blog/comment.php", true);
    var params = "from=web&post=" + post + "&user=" + user + "&text=" + text + "&lang=" + lang;
    x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    x.onreadystatechange = function(){
        if(x.readyState == 4){
            if(x.status == 200){
                document.getElementById('comment_list').innerHTML = x.responseText;
                document.getElementById('new_comment_text').value = '';
                document.getElementById('new_comment_user').value = '';
            }
        }
    }
    x.send(params);
    return false;
}
