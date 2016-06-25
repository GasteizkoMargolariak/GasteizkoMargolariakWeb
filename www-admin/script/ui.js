function showToolbar(toolbar, from){
	if(document.getElementById('toolbar_' + toolbar).style.display == 'block'){
		document.getElementById('toolbar_' + toolbar).style.display = 'none';
		from.style.backgroundColor = '#000000';
	}
	else{
		var toolbars = document.getElementsByClassName('secondary_toolbar');
		for (var i = 0; i < toolbars.length; i ++)
				toolbars.item(i).style.display = 'none';
		var sections = document.getElementsByClassName('toolbar_section');
		for (var j = 0; j < sections.length; j ++)
				sections.item(j).style.backgroundColor = '#000000';
		
		document.getElementById('toolbar_' + toolbar).style.display = 'block';
		from.style.backgroundColor = '#333333';
	}
}