/**
 * Changes the language.
 * 
 * Changes the language and sets a kookie for the next sessions. Reloads the
 * current page.
 * 
 * @param code lowercase, two-letter language code: 'es', 'en', 'eu'.
 * @param host Hostname for the ckookie to be set.
 */
function changeLanguage(code, host){
    var days = 100;
    if (code == 'es' || code == 'en' || code == 'eu'){
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = '; expires=' + date.toGMTString();
        document.cookie = 'lang=' + code + expires + ';domain=.' + host + ';path=/';
        location.reload();
    }
}
