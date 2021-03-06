function validate_post(){
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
    return true;
}



function formatText(textarea, format){
    
    var text = textarea.value;
    var selectedText= window.getSelection().toString();
    event.preventDefault();
    //IE
    if (document.selection != undefined){
        textarea.focus();
        var selection = document.selection.createRange();
        selectedText = selection.text;
    }
    
    //Mozilla
    else if (textarea.selectionStart != undefined){
        var startPos = textarea.selectionStart;
        var endPos = textarea.selectionEnd;
        selectedText = textarea.value.substring(startPos, endPos);
    }
    
    var replace;
    switch (format){
        case "italic":
            replace = "<span style='font-style:italic;'>" + selectedText + "</span>";
            break;
        case "bold":
            replace = "<span style='font-weight:bold;'>" + selectedText + "</span>";
            break;
        case "underline":
            replace = "<span style='text-decoration:underline;'>" + selectedText + "</span>";
            break;
        case "stroke":
            replace = "<span style='text-decoration:line-through;'>" + selectedText + "</span>";
            break;
        case "link":
            replace = "<a href='" + selectedText + "'>" + selectedText + "</a>";
            break;
        case "color-red":
            replace = "<span style='color:#f00;'>" + selectedText + "</span>";
            break;
        case "color-green":
            replace = "<span style='color:#0f0;'>" + selectedText + "</span>";
            break;
        case "color-blue":
            replace = "<span style='color:#00f;'>" + selectedText + "</span>";
            break;
        default:
            replace = selectedText;
    }
    
    if (selectedText.length  >0 && text != replace){
        text = text.replace(selectedText, replace);
        textarea.value = text;
    }
}

function preview_image(source, target) {
    var reader = new FileReader();
    reader.readAsDataURL(source.files[0]);

    reader.onload = function (reader_event) {
        target.src = reader_event.target.result;
        target.style.border = "2px solid black";
        target.style.marginTop = 10 + "px";
        target.style.marginBottom = 10 + "px";
        target.style.display = "inline";
    };
};

