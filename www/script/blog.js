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

function launchSearch(where){
	//Get search terms
	text = document.getElementById("search_panel_input").value;
	if (text.length > 0)
		window.location.href = "/blog/buscar/" + where + "/" + text;
}

//WARNING: If changed in css, change here.
function defaultInputBorder(id){
	id.style.border = '0.4em solid #9cd3d9'
}

function postComment(post, lang){
		
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
	x.open("POST", "/blog/comment.php", true);
	var params = "post=" + post + "&user=" + user + "&text=" + text + "&lang=" + lang;
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
