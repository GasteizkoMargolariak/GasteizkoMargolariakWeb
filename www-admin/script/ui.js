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


function showLanguage(lang){
    switch (lang){
        case 'es':
            document.getElementById('content_lang_es').style.display = 'block';
            document.getElementById('content_lang_eu').style.display = 'none';
            document.getElementById('content_lang_en').style.display = 'none';
            document.getElementById('lang_tab_es').classList.add("lang_tabs_active");
            document.getElementById('lang_tab_eu').classList.remove("lang_tabs_active");
            document.getElementById('lang_tab_en').classList.remove("lang_tabs_active");
            break;
        case 'eu':
                        document.getElementById('content_lang_es').style.display = 'none';
                        document.getElementById('content_lang_eu').style.display = 'block';
                        document.getElementById('content_lang_en').style.display = 'none';
                        document.getElementById('lang_tab_es').classList.remove("lang_tabs_active");
                        document.getElementById('lang_tab_eu').classList.add("lang_tabs_active");
                        document.getElementById('lang_tab_en').classList.remove("lang_tabs_active");
                        break;
        case 'en':
                        document.getElementById('content_lang_es').style.display = 'none';
                        document.getElementById('content_lang_eu').style.display = 'none';
                        document.getElementById('content_lang_en').style.display = 'block';
                        document.getElementById('lang_tab_es').classList.remove("lang_tabs_active");
                        document.getElementById('lang_tab_eu').classList.remove("lang_tabs_active");
                        document.getElementById('lang_tab_en').classList.add("lang_tabs_active");
    }
}

