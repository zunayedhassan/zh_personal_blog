function GetReadyAJAX() {
    try {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    } catch(e) {
        try {
            // IE7
            return new ActiveXObject('Msxml2.XMLHTTP');
        } catch(e) {
            try {
                // code for IE6, IE5
                return new ActiveXObject('Microsoft.XMLHTTP');
            } catch(e) {
                return null;
            }
        }
    }
}

function DisplayData(text, displayAreaId) {
    var field = document.getElementById(displayAreaId);
    field.innerHTML = text;
}

function DisplayPageSimple(url, displayAreaId, parameter) {
    url += ".php";
    var requestObj = GetReadyAJAX();

    if (requestObj != null) {
        requestObj.open("GET", url + "?" + parameter, true);
        requestObj.send();

        var AJAXresponse;

        requestObj.onreadystatechange = function() {
            if ((requestObj.readyState == 4) && (requestObj.status == 200)) {
                AJAXresponse = requestObj.responseText;
                DisplayData(AJAXresponse, displayAreaId);
            }
        }
    }

    return true;        // Use false, if your href link doesn't use hash (#)
}

function DisplayPage(url, displayAreaId, parameter) {
    url += ".php";
    
    $(document).ready(function() {        
        $.ajaxSetup ({
            cache: false  
        });
        
        if (parameter === undefined) {
            parameter = "";
        }
        
        var documentWidth = $(document).width();
        var screenHeight = window.screen.height;
        var posX = (documentWidth - 220) / 2;
        var posY = ((screenHeight - 0) / 2) * 0.80;
        
        $("#" + displayAreaId)
            .html("<div style='position: fixed; left: " + posX + "px; top: " + posY + "px;'><img src='images/loading.gif' alt='loading...' /></div>")
            .load(url, parameter)
            .delay("slow")
            .fadeIn();  
    });
    
    return true;        // Use false, if your href link doesn't use hash (#)
}
