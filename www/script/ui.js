/**
 * Changes the site language.
 * @param code Two-letter language code ("es", "en" or "eu").
 * @param host Hostname.
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

/**
 * Keeps track of the menu state in the mobile version.
 * Used by {@link toggleMobileMenu()}.
 */
var mobileMenuState = false;

/**
 * Opens the main menu in the mobile version.
 * Called from {@link toggleMobileMenu()}.
 */
function openMobileMenu(){
    //Hide title, show entries
    document.getElementById('header_m_title').style.maxHeight = '0em';
    var entries = document.getElementsByClassName('header_m_link');
    for (var i = 0; i < entries.length; i ++){
        entries.item(i).style.maxHeight = '2em';
        entries.item(i).style.padding = '0.5em 0 0 0';
        entries.item(i).style.borderBottom = '0.1em solid black';
    }
    mobileMenuState = true;
}

/**
 * Closes the main menu in the mobile version.
 * Called from {@link toggleMobileMenu()}.
 */
function closeMobileMenu(){
    //Hide entries, show title
    document.getElementById('header_m_title').style.maxHeight = '3.5em';
    document.getElementById('header_m_slider').style.display = 'none';
    var entries = document.getElementsByClassName('header_m_link');
    for (var i = 0; i < entries.length; i ++){
        //entries.item(i).style.display = 'none';
        entries.item(i).style.maxHeight = '0em';
        entries.item(i).style.padding = '0em';
        entries.item(i).style.borderBottom = '0';
    }
    mobileMenuState = false;
}

/**
 * Toggles the main menu in the mobile version.
 * Calls {@link closeMobileMenu()} or {@link closeMobileMenu()},
 * depending on {@link mobileMenuState}.
 */
function toggleMobileMenu(){
    if (mobileMenuState){
        closeMobileMenu();
    }
    else{
        openMobileMenu();
    }
}

/**
 * Shows the advertisment window.
 */
function showAd(){
    setTimeout(function(){document.getElementById('ad').style.bottom = '0';}, 3000);
}

/**
 * Closes the advertisment window.
 */
function closeAd(){
    document.getElementById('ad').style.bottom = '-25em';
}
