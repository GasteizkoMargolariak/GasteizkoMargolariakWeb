/**
 * Shows a tab to complete a form for the specified language.
 * 
 * @param lang Language ('es', 'en' or 'eu').
 */
function showTab(lang){
    switch (lang){
        case 'es':
            document.getElementById('tab_content_es').style.display = 'inline-block';
            document.getElementById('tab_content_eu').style.display = 'none';
            document.getElementById('tab_content_en').style.display = 'none';
            document.getElementById('tab_selector_es').style.color = '#000000';
            document.getElementById('tab_selector_eu').style.color = '#8899bb';
            document.getElementById('tab_selector_en').style.color = '#8899bb';
            document.getElementById('tab_selector_es').style.zIndex = '3';
            document.getElementById('tab_selector_eu').style.zIndex = '1';
            document.getElementById('tab_selector_en').style.zIndex = '1';
            document.getElementById('tab_selector_es').style.borderBottom = '0em solid #000000';
            document.getElementById('tab_selector_eu').style.borderBottom = '0.1em solid #000000';
            document.getElementById('tab_selector_en').style.borderBottom = '0.1em solid #000000';
            break;
        case 'eu':
            document.getElementById('tab_content_es').style.display = 'none';
            document.getElementById('tab_content_eu').style.display = 'inline-block';
            document.getElementById('tab_content_en').style.display = 'none';
            document.getElementById('tab_selector_es').style.color = '#8899bb';
            document.getElementById('tab_selector_eu').style.color = '#000000';
            document.getElementById('tab_selector_en').style.color = '#8899bb';
            document.getElementById('tab_selector_es').style.zIndex = '1';
            document.getElementById('tab_selector_eu').style.zIndex = '3';
            document.getElementById('tab_selector_en').style.zIndex = '1';
            document.getElementById('tab_selector_es').style.borderBottom = '0.1em solid #000000';
            document.getElementById('tab_selector_eu').style.borderBottom = '0em solid #000000';
            document.getElementById('tab_selector_en').style.borderBottom = '0.1em solid #000000';
            break;
        case 'en':
            document.getElementById('tab_content_es').style.display = 'none';
            document.getElementById('tab_content_eu').style.display = 'none';
            document.getElementById('tab_content_en').style.display = 'inline-block';
            document.getElementById('tab_selector_es').style.color = '#8899bb';
            document.getElementById('tab_selector_eu').style.color = '#8899bb';
            document.getElementById('tab_selector_en').style.color = '#000000';
            document.getElementById('tab_selector_es').style.zIndex = '1';
            document.getElementById('tab_selector_eu').style.zIndex = '1';
            document.getElementById('tab_selector_en').style.zIndex = '3';
            document.getElementById('tab_selector_es').style.borderBottom = '0.1em solid #000000';
            document.getElementById('tab_selector_eu').style.borderBottom = '0.1em solid #000000';
            document.getElementById('tab_selector_en').style.borderBottom = '0em solid #000000';
            break;
    }
}


/**
 * Validates all the fields before adding a new activity.
 * 
 * @return true if fields are ok, false otherwise.
 */
function validateActivity(){
    var text_es = document.getElementById('text_es').value;
    var text_eu = document.getElementById('text_eu').value;
    var text_en = document.getElementById('text_en').value;
    var title_es = document.getElementById('title_es').value;
    var title_eu = document.getElementById('title_eu').value;
    var title_en = document.getElementById('title_en').value;
    result = true;
    
    //Spanish fields empty
    if (title_es.length < 1){
        result = false;
        alert("Introduce un titulo en castellano");
        return false;
    }
    if (text_es.length < 1){
        result = false;
        alert("Introduce un texto en castellano");
        return false;
    }
    //Titles without texts
    if (title_eu.length > 0 && text_eu.length < 1){
        result = false;
        alert("Introduce un texto o borra el titulo en euskera")
        return false;
    }
    if (title_en.length > 0 && text_en.length < 1){
        result = false;
        alert("Introduce un texto o borra el titulo en ingles")
        return false;
    }
    //Text without titles
    if (text_eu.length > 0 && title_eu.length < 1){
        result = false;
        alert("Introduce un titulo o borra el texto en euskera")
        return false;
    }
    if (text_en.length > 0 && title_en.length < 1){
        result = false;
        alert("Introduce un titulo o borra el texto en ingles")
        return false;
    }
    // TODO: check date
    // TODO: check numeric fields price, people
    return true;
}


/**
 * Shows an image that has been just uploaded.
 * 
 * @param source Image resource.
 * @param target img tag to display the image on.
 * @return true if fields are ok, false otherwise.
 */
function previewImage(source, target) {
    var reader = new FileReader();
    reader.readAsDataURL(source.files[0]);

    reader.onload = function (reader_event) {
        target.src = reader_event.target.result;
        target.style.border = "2px solid black";
        target.style.display = "inline";
    };
};


/**
 * Shows th enext itinerary row.
 * 
 * @param current The current row identifier.
 */
function showNextRow(current){
    if (document.getElementById('sh_' + current).value != '' ||
        document.getElementById('eh_' + current).value != '' || 
        document.getElementById('sm_' + current).value != '' || 
        document.getElementById('em_' + current).value != '' || 
        document.getElementById('place_' + current).value != -1 || 
        document.getElementById('title_es_' + current).value != '' || 
        document.getElementById('title_en_' + current).value != '' || 
        document.getElementById('title_eu_' + current).value != '' || 
        document.getElementById('text_es_' + current).value != '' || 
        document.getElementById('text_en_' + current).value != '' || 
        document.getElementById('text_eu_' + current).value != ''
    ){
        document.getElementById('itinerary_row_' + (current + 1)).style.display = 'table-row';
    }
    else{
        document.getElementById('itinerary_row_' + (current + 1)).style.display = 'none';
    }
}
