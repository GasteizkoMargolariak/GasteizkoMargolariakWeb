function newOffer(){
	document.getElementById('screen_cover').style.display = 'block';
	document.getElementById('form_new_offer').style.display = 'block';
	document.getElementById('screen_cover').style.opacity = '0.6';
	document.getElementById('form_new_offer').style.opacity = '1';
}

function cancelNewOffer(){
	if (confirm("Oferta no agregada. Descartar cambios?")){
		closeNewOffer();
	}
}

function closeNewOffer(){
	document.getElementById('new_offer_title_es').value = '';
	document.getElementById('new_offer_title_en').value = '';
	document.getElementById('new_offer_title_eu').value = '';
	document.getElementById('new_offer_text_es').value = '';
	document.getElementById('new_offer_text_en').value = '';
	document.getElementById('new_offer_text_eu').value = '';
	document.getElementById('new_offer_days').value = '1';
	document.getElementById('new_offer_price').value = '0';
	document.getElementById('screen_cover').style.display = 'none';
	document.getElementById('form_new_offer').style.display = 'none';
	document.getElementById('screen_cover').style.opacity = '0';
	document.getElementById('form_new_offer').style.opacity = '0';
}

function saveNewOffer(year){
	//Get values
	var name_es = document.getElementById('new_offer_title_es').value;
	var name_en = document.getElementById('new_offer_title_en').value;
	var name_eu = document.getElementById('new_offer_title_eu').value;
	var text_es = document.getElementById('new_offer_text_es').value;
	var text_en = document.getElementById('new_offer_text_en').value;
	var text_eu = document.getElementById('new_offer_text_eu').value;
	var days = document.getElementById('new_offer_days').value;
	var price = document.getElementById('new_offer_price').value;
	
	//Validate values
	if (name_es.length == 0){
		alert("Debes introducir el nombre, al menos en castellano.");
		document.getElementById('new_offer_title_es').focus();
		return;
	}
	if (text_es.length == 0){
		alert("Debes introducir una descripcion, al menos en castellano.");
		document.getElementById('new_offer_text_es').focus();
		return;
	}
	if (parseInt(price, 10) <= 0){
		alert("Debes introducir un precio valido.");
		document.getElementById('new_offer_price').focus();
		return;
	}
	if (parseInt(price, 10) <= 1 || parseInt(price, 10) > 6){
		alert("Debes introducir un numer valido de dias.");
		document.getElementById('new_offer_days').focus();
		return;
	}
	
	// Encode required values
	name_es = encodeURI(name_es);
	name_en = encodeURI(name_en);
	name_eu = encodeURI(name_eu);
	text_es = encodeURI(text_es);
	text_en = encodeURI(text_en);
	text_eu = encodeURI(text_eu);
	
	// Get page
	if(XMLHttpRequest)
		var x = new XMLHttpRequest();
	else 
		var x = new ActiveXObject("Microsoft.XMLHTTP");
	x.open("POST", "/lablanca/insertoffer.php", true);
	var params = "year=" + year + "&name_es=" + name_es + "&name_en=" + name_en + "&name_eu=" + name_eu + "&text_es=" + text_es + "&text_en=" + text_en + "&text_eu=" + text_eu + "&price=" + price + "&days=" + days;
	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	x.onreadystatechange = function(){
	if(x.readyState == 4){
		if(x.status == 200)
			closeNewOffer();
			calculate('prices');
		}
	}
	x.send(params);
}

function calculate(section){
	switch (section){
		case 'all':
			calculate('header');
			calculate('prices');
			calculate('schedule');
			break;
		case 'header':
			setTimeout(function(){
				// Get page
				if(XMLHttpRequest)
					var x = new XMLHttpRequest();
				else
					var x = new ActiveXObject("Microsoft.XMLHTTP");
				x.open("GET", "/lablanca/calculate.php?section=header", true);
				x.send();
				x.onreadystatechange = function(){
				if(x.readyState == 4){
					if(x.status == 200)
						document.getElementById('status_header').innerHTML = x.responseText;
					}
				}
			}, 500);
			break;
		case 'prices':
			setTimeout(function(){
				// Get page
				if(XMLHttpRequest)
					var x = new XMLHttpRequest();
				else 
					var x = new ActiveXObject("Microsoft.XMLHTTP");
				x.open("GET", "/lablanca/calculate.php?section=pricesprogress", true);
				x.send();
				x.onreadystatechange = function(){
				if(x.readyState == 4){
					if(x.status == 200)
						document.getElementById('status_prices').innerHTML = x.responseText;
					}
				}
			}, 500);
			setTimeout(function(){
				// Get page
				if(XMLHttpRequest)
					var x = new XMLHttpRequest();
				else 
					var x = new ActiveXObject("Microsoft.XMLHTTP");
				x.open("GET", "/lablanca/calculate.php?section=pricesoffers", true);
				x.send();
				x.onreadystatechange = function(){
				if(x.readyState == 4){
					if(x.status == 200)
						document.getElementById('prices_offers').innerHTML = x.responseText;
					}
				}
			}, 700);
			break;
		case 'schedule':
			console.log('Recalculating schedule...');
				setTimeout(function(){
				// Get page
				if(XMLHttpRequest)
					var x = new XMLHttpRequest();
				else
					var x = new ActiveXObject("Microsoft.XMLHTTP");
				x.open("GET", "/lablanca/calculate.php?section=schedule", true);
				x.send();
				x.onreadystatechange = function(){
				if(x.readyState == 4){
					if(x.status == 200)
						document.getElementById('status_schedule').innerHTML = x.responseText;
					}
				}
			}, 500);
			break;
	}
}

function uploadHeaderImage(event, id){
	var selectedFile = event.target.files[0];
	
	//Set image on page
	var reader = new FileReader();
	var imgtag = document.getElementById("header_img");
	reader.onload = function(event) {
		imgtag.src = event.target.result;
		
		//Upload image;
		var formData = new FormData();
		var file = document.getElementById('header_img_selector').files[0];
		formData.append('img', selectedFile, selectedFile.name);
		//for(var pair of formData.entries()) {
		//	console.log(pair[0]+ ', '+ pair[1]); 
		//}
		//console.log("FD" + formData);
		
		// Get page
		if(XMLHttpRequest)
			var x = new XMLHttpRequest();
		else 
			var x = new ActiveXObject("Microsoft.XMLHTTP");
		x.open("POST", "/lablanca/uploadimage.php?type=header&id=" + id, true);
		x.onload = function(){
		if(x.readyState == 4){
			if(x.status == 200)
				console.log("TRUE")
				return true;
			}
			else{
				console.log("FASLE");
			}
		}
		x.send(formData);
	};
	reader.readAsDataURL(selectedFile);
}

function updateField(table, field, value, id, type = 'text', canBeNull = true){
	// Get page
	if(XMLHttpRequest)
		var x = new XMLHttpRequest();
	else 
		var x = new ActiveXObject("Microsoft.XMLHTTP");
	x.open("POST", "/lablanca/execute.php", true);
	var params = "action=update&table=" + table + "&field=" + field + "&value=" + encodeURI(value) + "&id=" + id + "&type=" + type;
	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	x.onreadystatechange = function(){
	if(x.readyState == 4){
		if(x.status == 200)
			return true;
		}
	}
	x.send(params);
}

function deleteOffer(id){
	// Get page
	if(XMLHttpRequest)
		var x = new XMLHttpRequest();
	else 
		var x = new ActiveXObject("Microsoft.XMLHTTP");
	x.open("POST", "/lablanca/execute.php", true);
	var params = "action=delete&table=festival_offer&id=" + id;
	x.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
	x.onreadystatechange = function(){
	if(x.readyState == 4){
		if(x.status == 200)
			calculate('prices');
		}
	}
	x.send(params);
}
