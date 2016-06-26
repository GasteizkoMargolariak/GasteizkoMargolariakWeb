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

var mobileMenuState = false;

function openMobileMenu(){
	//Hide title, show entries
	//document.getElementById('header_m_title').style.display = 'none';
	document.getElementById('header_m_title').style.maxHeight = '0em';
	//document.getElementById('header_m_slider').style.display = 'block';
	var entries = document.getElementsByClassName('header_m_link');
	for (var i = 0; i < entries.length; i ++){
		//entries.item(i).style.display = 'block';
		entries.item(i).style.maxHeight = '2em';
		entries.item(i).style.padding = '0.5em 0 0 0';
		entries.item(i).style.borderBottom = '0.1em solid black';
	}
	mobileMenuState = true;
}
function closeMobileMenu(){
	//Hide title, show entries
	//document.getElementById('header_m_title').style.display = 'block';
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
function toggleMobileMenu(){
	if (mobileMenuState){
		closeMobileMenu();
	}
	else{
		openMobileMenu();
	}
}

function dismissCookiePopUp(host, open = false){
	//Set cookie
	var date = new Date();
	date.setTime(date.getTime() + (365 * 24 * 60 * 60 * 1000));
	expires = '; expires=' + date.toGMTString();
	document.cookie = 'cookie=1' + expires + ';domain=.' + host + ';path=/';
	//Hide message
	document.getElementById('cookie_popup').style.bottom = '-20em';
	// Open the help window if required
	if (open){
		var win = window.open('http://' + host + '/ayuda/#privacidad', '_blank');
		win.focus();
	}
}

function showAd(){
	console.log("SHOW");
	setTimeout(function(){document.getElementById('ad').style.bottom = '0';}, 3000);
}

function closeAd(){
	console.log("CLOSE");
	document.getElementById('ad').style.bottom = '-25em';
}
