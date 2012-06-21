
function getUrlValue(name){
  name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp( regexS );
  var results = regex.exec( window.location.href );
  if( results == null )
    return "";
  else
    return results[1];
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

function stripHTML(strInputCode){
 	strInputCode = strInputCode.replace(/&(lt|gt);/g, function (strMatch, p1){
	 	return (p1 == "lt")? "<" : ">";
	});
	var strTagStrippedText = strInputCode.replace(/<\/?[^>]+(>|$)/g, "");
	return strTagStrippedText;
}

function stripSpecialChars(strInputCode){
	strInputCode = strInputCode.replace(/[^\w\s]/gi, '');
	return strInputCode;
}

function urldecode(str){
	if(typeof str !== "undefined" && str){
		return decodeURIComponent(str.replace(/\+/g,  " "));
	} 
}

function urlencode(str){
	if(typeof str !== "undefined" && str){
		return encodeURIComponent(str);
	}	
}

function getTinyMceValue(id){
	return stripHTML(tinyMCE.get(id).getContent({format : 'raw'}));	
}


function setTinyMceValue(id, text){
	tinyMCE.get(id).setContent(text, {format : 'raw'});
}

function trim(str){
	return str.replace(/^\s\s*/, '').replace(/\s\s*$/, '');	
}

function readDomainCookie( name ) {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) &&
	( name != document.cookie.substring( 0, name.length ) ) )
	{
	return null;
	}
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ";", len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
}

function eraseDomainCookie( name, path, domain ) {
	if ( readDomainCookie( name ) ) document.cookie = name + "=" +
	( ( path ) ? ";path=" + path : "") +
	( ( domain ) ? ";domain=" + domain : "" ) +
	";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}


function createDomainCookie( name, value, expires, path, domain, secure )
{
	// set time, it's in milliseconds
	var today = new Date();
	today.setTime( today.getTime() );
	
	if ( expires )
	{
	expires = expires * 1000 * 60 * 60 * 24;
	}
	var expires_date = new Date( today.getTime() + (expires) );
	
	document.cookie = name + "=" +escape( value ) +
	( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
	( ( path ) ? ";path=" + path : "" ) +
	( ( domain ) ? ";domain=" + domain : "" ) +
	( ( secure ) ? ";secure" : "" );
}


function check_email(str){
	var at="@";
	var dot=".";
	var lat=str.indexOf(at);
	var lstr=str.length;
	var ldot=str.indexOf(dot);
	if (str.indexOf(at)==-1){
	   return false;
	}
	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
	   return false;
	}
	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
	   return false;
	}
	if (str.indexOf(at,(lat+1))!=-1){
	   return false;
	}
	if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
	   return false;
	} 
	if (str.indexOf(dot,(lat+2))==-1){
	   return false;
	}	
	if (str.indexOf(" ")!=-1){
	   return false;
	}
	return true;					
}

var newWindow = null;
function windowOpener(windowHeight, windowWidth, windowName, windowUri)
{
		//try to close existing window
		try{
			newWindow.close();		
		}
		catch(err){			
		}
		
    var centerWidth = (window.screen.width - windowWidth) / 2;
    var centerHeight = ((window.screen.height - windowHeight) / 2) - 40;
    
    newWindow = window.open(windowUri, windowName, 'width=' + windowWidth + 
        ',height=' + windowHeight + 
        ',left=' + centerWidth + 
        ',top=' + centerHeight); 
        
    //detect whether popup is blocked
    if(!newWindow || newWindow.closed || typeof newWindow.closed=='undefined'){
    	alert('popup blocked');
    }     
        
		//try to focus
		try{
			newWindow.focus();		
		}
		catch(err){
		}
}
function clearForm(form) {
	  // iterate over all of the inputs for the form
	  // element that was passed in
	  $(':input', form).each(function() {
	    var type = this.type;
	    var tag = this.tagName.toLowerCase(); // normalize case
	    // it's ok to reset the value attr of text inputs,
	    // password inputs, and textareas
	    if (type == 'text' || type == 'password' || tag == 'textarea')
	      this.value = "";
	    // checkboxes and radios need to have their checked state cleared
	    // but should *not* have their 'value' changed
	    else if (type == 'checkbox' || type == 'radio')
	      this.checked = false;
	    // select elements need to have their 'selectedIndex' property set to -1
	    // (this works for both single and multiple select elements)
	    else if (tag == 'select')
	      this.selectedIndex = -1;
	  });
	};
$.fn.clearForm = function() {
	  return this.each(function() {
	    var type = this.type, tag = this.tagName.toLowerCase();
	    if (tag == 'form')
	      return $(':input',this).clearForm();
	    if (type == 'text' || type == 'password' || tag == 'textarea')
	      this.value = '';
	    else if (type == 'checkbox' || type == 'radio')
	      this.checked = false;
	    else if (tag == 'select')
	      this.selectedIndex = -1;
	  });
	};

function validateURL(textval) {
	return true;
	/*
      var urlregex = new RegExp(
            "^(http|https|ftp)\://([a-zA-Z0-9\.\-]+(\:[a-zA-Z0-9\.&amp;%\$\-]+)*@)*((25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])|([a-zA-Z0-9\-]+\.)*[a-zA-Z0-9\-]+\.(com|edu|gov|int|mil|net|org|biz|arpa|info|name|pro|aero|coop|museum|[a-zA-Z]{2}))(\:[0-9]+)*(/($|[a-zA-Z0-9\.\,\?\'\\\+&amp;%\$#\=~_\-]+))*$");
      return urlregex.test(textval);
    */
      
      
}



