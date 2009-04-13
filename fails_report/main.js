var strSoftHyphen = (navigator.userAgent.toLowerCase().indexOf("applewebkit") > -1 || document.all) ? "&shy;" : "<wbr/>"; // use soft-hyphen for IE and Opera which are known to implement it correctly

$(document).ready(function() {
	$("div.date").bind("click", function(e) {
		$("#" + this.id + " + div.need_to_toggle").slideToggle("slow");
	});
	$("div.date").bind("mouseenter", function(e) {
		$(this).css("background", "yellow");
	});
	$("div.date").bind("mouseleave", function(e) {
		$(this).css("background", "#DDEEFF");
	});
	
	var tds = document.getElementsByTagName("td");
	for (var i = 0; i < tds.length; i++) {
		//if (tds[i].className == "test_name_cell") {
			tds[i].innerHTML = softWrap(tds[i].innerHTML, 16);
		//}
	}
	
	$("div.date").trigger("click");
});

function softWrap(str, maxcolumns) {
	// these regular expressions need to incorporate variables (function params), so cant be static properties of the function, to save a few operations in repeated use
	var wordspaceRe = new RegExp('^\\w{1,' + maxcolumns + '}\\s+');
    var punctuationRe = new RegExp('^[!\\._\\-\\\\\,=\\*]{1,' + maxcolumns + '}');

	var wrapstr = "";
	var charCount = 0;
	while (str.length) {
		var endidx = 1;
        // shortcut if there's less remaining characters than the maxcols
		if (str.length < maxcolumns) {
			wrapstr += str; 
            break;
        }
        // look ahead for space characters
        var spaceMatches = str.match(wordspaceRe);
        if (spaceMatches && spaceMatches[0]) {
            endidx = spaceMatches[0].length;
            wrapstr += str.substring(0, endidx); 
            str = str.substring(endidx);
            charCount = 0; // reset 
            continue;
         } else {
		 	// handle markup
            if (str.charAt(0) == "<" && str.indexOf(">") > -1) {
				endidx = str.indexOf(">");
            	charCount++; // count as one character
            } else if (str.charAt(0) == "&" && str.match(/^&\w+;/)) { // handle entities
				endidx = (str.indexOf(";") > -1) ? str.indexOf(";") +1 : str.length;
				charCount++; // count as one character
            } else {
				var puncMatches = str.match(punctuationRe);
				if (puncMatches && puncMatches[0]) {
					// handle punctuation
					endidx = puncMatches[0].length;
					charCount += endidx; // count as one character
				} else {
					charCount++; // default case is just one character
            	}
          	}
        }
		wrapstr += str.substring(0, endidx);
		
		if (charCount >= maxcolumns) {
			wrapstr += strSoftHyphen;
            charCount = 0;
		}
		
		str = str.substring(endidx);
	}
	return wrapstr;
}
	  