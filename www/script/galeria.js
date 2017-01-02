var photo = [];
var maxId;
var cid;	//Current photo ID;

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
	if(XMLHttpRequest)
		var x = new XMLHttpRequest();
	else 
		var x = new ActiveXObject("Microsoft.XMLHTTP");
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
		case 27:	//ESC
			closeViewer();
			break;
		case 37:	//Left arrow
			if (document.getElementById('photo_viewer').innerHTML != ""){
				if (cid == 0){
					showPhotoByPath(photo[photo.length - 1]);
				}
				else{
					showPhotoByPath(photo[cid - 1]);
				}
			}
			break;
		case 39:	//Right arow
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
		case 1:	//Next
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

//WARNING: If changed in css, change here.
function defaultInputBorder(id){
	id.style.border = '0.2em solid #0078ff'
}

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
	else {
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

//Upload functions

function dropFile(ev, title, description){
	ev.preventDefault();
	var totalFiles = ev.dataTransfer.files.length;
	var entry = "";
	var reader;
	for (var i = 0; i < totalFiles; i++) {
		console.log(ev.dataTransfer.files[i]);
		if (ev.dataTransfer.files[i].type.substring(0, 5) == 'image'){
			reader = new FileReader();
			reader.onload = function(event) {
				//imgtag.src = event.target.result;
				entry = "<div class='form_photo entry'>";
				entry = entry + "<img src='" + event.target.result + "'/><br/>"; 
				entry = entry + "<input type='text' placeholder='" + title + "'/><br/>";
				entry = entry + "<textarea placeholder='" + description + "'></textarea><br/>";
				entry = entry + "</div>";
				document.getElementById("file_list").innerHTML = document.getElementById("file_list").innerHTML + entry;
			}
			reader.readAsDataURL(ev.dataTransfer.files[i]);
		}
	}
	//Show submit button
	document.getElementById('photo_submit').style.display = 'initial';
}

function selectFile(ev, inp, title, description){
	var totalFiles = inp.files.length;
	var entry = "";
	var reader;
	for (var i = 0; i < inp.files.length; ++i) {
		//console.log(inp.files[i]);
		if (inp.files[i].type.substring(0, 5) == 'image'){
			reader = new FileReader();
			reader.onload = function(event) {
				//imgtag.src = event.target.result;
				entry = "<div class='form_photo entry'>";
				entry = entry + "<img src='" + event.target.result + "'/><br/>"; 
				entry = entry + "<input type='text' placeholder='" + title + "'/><br/>";
				entry = entry + "<textarea placeholder='" + description + "'></textarea><br/>";
				entry = entry + "</div>";
				document.getElementById("file_list").innerHTML = document.getElementById("file_list").innerHTML + entry;
			}
			reader.readAsDataURL(inp.files[i]);
		}
	}
	//Show submit button
	document.getElementById('photo_submit').style.display = 'initial';
}

function isInteger(n) {
	return n % 1 === 0;
}

function submitPhotos(){
	var photoList = [];
	var photoData;
	var path;
	var img;
	var album;
	var username;
	
	//Check that there is an album selected
	album = document.getElementById('album').value;
	if (album == -1 || isInteger(album) == false){
		//TODO: Notify
		console.log('album');
		return;
	}
	
	//Check that username is filled
	username = document.getElementById('username').value;
	if (username.length <= 0){
		//TODO: Notify
		console.log('user');
		return;
	}
	
	//var url;
	var title;
	var description;
	//Loop section with class form_photo, getting data
	var ph = document.getElementsByClassName('form_photo');
	for (var i = 0; i < ph.length; i ++){
		//Get image
		path = ph.item(i).getElementsByTagName('img')[0].src;
		//url = ph.item(i).getAsDataURL();
		title = ph.item(i).getElementsByTagName('input')[0].value;
		description = ph.item(i).getElementsByTagName('textarea')[0].value;
		//path = ph.item(i).src.substring(ph.item(i).src.lastIndexOf('/') + 1);
		photoList.push([encodeURIComponent(path), encodeURIComponent(title), encodeURIComponent(description)]);
		//console.log(photoList[i]);
	}
	
	//Load page
	if(XMLHttpRequest){
		var x = new XMLHttpRequest();
	}
	else {
		var x = new ActiveXObject("Microsoft.XMLHTTP");
	}
	x.open("POST", "/galeria/save.php", true);
	//Set paramas
	//TODO: paramas, add user and album
	var params = "username=" + username + "&album=" + album;
	for (var i = 0; i < photoList.length; i ++){
		var params = params + "&file_" + i + "=" + photoList[i][0] + "&title_" + i + "=" + photoList[i][1] + "&description_" + i + "=" + photoList[i][2]; 
	}
	console.log(params);
	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	x.onreadystatechange = function(){
		if(x.readyState == 4){
			if(x.status == 200){
				console.log("RESPONSE: " + x.responseText);
				window.location = "/galeria/";
			}
		}
	}
	x.send(params);
}

function launchFileSelector(){
	document.getElementById("file_selector").click();
}
