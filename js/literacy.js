// JavaScript Document

// from 00e-literacy.php around line 130

// Get the HTTP Object
function getHTTPObject() { // get the AJAX object; can be used more than once
    try {
        // IE 7+, Opera 8.0+, Firefox, Safari
        return new XMLHttpRequest();
    } catch (e) {
        // Internet Explorer browsers
        try {
            return new ActiveXObject("Msxml2.XMLHTTP");
        } catch (e) {
            try {
                return new ActiveXObject("Microsoft.XMLHTTP");
            } catch (e) {
                // Something went wrong
                alert("XML HTTP Request is not able to be set. Maybe the version of the web browser is old?");
                return null;
            }
        }
    }
}

function display(iso, idx) {
    var literacyDisplay = getHTTPObject(); // the literacyDisplay object (see JavaScript function getHTTPObject() above)
    if (literacyDisplay == null) {
        return;
    }
    document.getElementById("allSearch").style.display = 'none';
    var url = "literacyDisplay.php";
    url = url + "?iso=" + iso;
    url = url + "&idx=" + idx;
    url = url + "&sid=" + Math.random();
    literacyDisplay.open("GET", url, true); // open the AJAX object
    literacyDisplay.send(null);
    literacyDisplay.onreadystatechange = function() { // the function that returns for AJAX object
        if (literacyDisplay.readyState == 4) { // if the readyState = 4 then eBible is displayed
            document.getElementById("display").innerHTML = literacyDisplay.responseText;
        }
    }
}

function showLanguage(str) { // get the names of the languages
    if (str.length == 0) {
        return;
    }
    // saltillo: ?; U+A78C
    var re = /[-. ,'?()A-Za-záéíóúÑñçãõâêîôûäëöüï]/; // the '-' has to go first
    var foundArray = re.exec(str.substring(str.length - 1)); // the last character of the str
    if (!foundArray) { // is the value of the last character of the str isn't A-Za - z then it returns
        document.getElementById("ID").value = document.getElementById("ID").value.substring(0, document.getElementById("ID").value.length - 1);
        alert(str.substring(str.length - 1) + " is an invalid character. Use an alphabetic character or - , ' ?[saltillo] ( )  [space]");
        str = str.substring(0, str.length - 1);
        if (str.length == 0) {
            document.getElementById("ID").innerHTML = "";
            //document.getElementById("ID").style.border = "0px";
        }
        return;
    }
    if (str.length <= 2) {
        document.getElementById("LangSearch").innerHTML = '';
        return;
    }
    xmlhttp = getHTTPObject(); // the ISO object (see JavaScript function getHTTPObject() above)
    if (xmlhttp == null) {
        return;
    }
    var color = '';
    var table = '';
    var Country_Total = [];
    /****************************************************************************************************************
    	AJAX - languageSearch.php
    ****************************************************************************************************************/
    var url = "LitLangSearch.php";
    url = url + "?language=" + str;
    url = url + "&sid=" + Math.random();
    xmlhttp.open("GET", url, true); // open the AJAX object with livesearch.php
    xmlhttp.send(null);
    xmlhttp.onreadystatechange = function() { // the function that returns for AJAZ object
        if (xmlhttp.readyState == 4) { // if the readyState = 4 then livesearch is displayed
            var splits = xmlhttp.responseText.split('<br />'); // Display all of the languages that have 'language' as a part of it.
            document.getElementById("LangSearch").innerHTML = '';
            if (splits.length == 1 && splits[0].indexOf('|') === -1) {
                langNotFound = splits[0];
                document.getElementById("LangSearch").innerHTML = '<div style="display: block; margin-top: 20px; padding: 10px; text-align: center; margin-left: auto; margin-right: auto; color: red; background-color: white; font-size: 1.3em; "> ' + langNotFound + '</div>';
                return;
            }
            // the 'table' is caused by a bug in Firefox 63.0.1 (11/7/2018) thus I added the last 3 items
            var colCode = splits[splits.length - 1]; // subtract 1 from splits.length
            var colAlt = splits[splits.length - 2]; // subtract 1 from splits.length
            var colLN = splits[splits.length - 3]; // subtract 1 from splits.length
            table = '<table class="langTable">';
            table += '<thead><tr style="color: #716164; background-color: white; font-size: 1.2em; font-weight: bold; height: 50px; "><td width="50%" style="text-align: center; ">[' + colCode + '] ' + colLN + '</td><td width="50%" style="text-align: center; ">' + colAlt + '</td></tr></thead>';
            for (var i = 0; i < splits.length - 3; i++) {
                var firstSplit = splits[i].split('|');
                // $spanish_lang.'|'.$alt.'|'.$iso.'|'.'.$iso_num_index;
                var spanish_lang = firstSplit[0];
                var alt = firstSplit[1];
                var iso = firstSplit[2];
                //var rod_code = firstSplit[4];
                var iso_num_index = firstSplit[3];
                if (i % 2)
                    color = "255, 255, 255, 1";
                else
                    color = "240, 238, 238, 1";
                // Cross Site Scripting (XSS) attack happens where client side code (usually JavaScript) gets injected into the output of your PHP script. The next line cleans it up.
                table += "<tr style='background-color: rgba(" + color + ")'>";
                table += "<td width='50%' style='cursor: pointer; color: #0033CC; padding: 10px; height: 40px; ' onmouseover='this.style.textDecoration = \"underline\"; this.style.color = \"#EC0000\";' onmouseout='this.style.textDecoration=\"none\"; this.style.color = \"#0033CC\";' onclick='document.getElementById(\"allSearch\").innerHTML = \"\"; display(\""+iso+"\","+iso_num_index+")'>[" + iso + '] ' + spanish_lang;
                table += "</td>";
                table += "<td width='50%' style='padding: 10px; font-size: .9em; '>" + alt + "</td>";
                table += "</tr>";
            }
            table += "</table>";
            document.getElementById("LangSearch").innerHTML = table;
        }
    }
}