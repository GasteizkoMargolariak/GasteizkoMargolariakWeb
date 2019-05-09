/**
 * Sets the comment input fields back to normal.
 */
function dissmiss_comment_error(){
    document.getElementById('new_comment_text').classList.remove('new_comment_error');
    document.getElementById('new_comment_user').classList.remove('new_comment_error');
}

/**
 * Validates and posts a comment.
 * 
 * @param post Post id.
 * @param lang Infered user language.
 * @return true on success, false on error.
 */
function postComment(post, lang){
    //Get field values
    var text = document.getElementById('new_comment_text').value;
    var user = document.getElementById('new_comment_user').value;
    //Check fields
    if (text.length == 0){
        document.getElementById('new_comment_text').classList.add('new_comment_error');
        document.getElementById('new_comment_text').focus();
        return false;
    }
    if (user.length == 0){
        document.getElementById('new_comment_user').classList.add('new_comment_error');
        document.getElementById('new_comment_user').focus();
        return false;
    }
    //Load page
    var x = new XMLHttpRequest();
    // TODO: API url
    x.open('POST', '/blog/comment.php', true);
    var params = 'from=web&post=' + post + '&user=' + user + '&text=' + text + '&lang=' + lang;
    x.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
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
