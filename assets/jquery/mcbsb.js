/**
 * MCB-SB js library
 */

function retrieveForm(form) {

	var dataObj = {};
	
	var elemArray = form.elements;

    for (var i = 0; i < elemArray.length; i++) {

    	var element = elemArray[i];
    	
    	var field = element.name;
    	var value = element.value;
    	var type = element.type.toUpperCase();
    	
    	dataObj[i] = { field: field, value: value, type: type };
    }	
	return dataObj;
}

function sendForm(url,dataType,type,action,dataObj) {
    jQuery.ajax({
        //url      : "/index.php/contact/update_settings/",
        //dataType : "html",    	
		//type : 'POST',
    	url		: url,
    	dataType: dataType,
    	type	: type,
        data    : {
            		//action: 'person_aliases',
        			action: action,
            		save: true,
            		form: dataObj,
        			},
    })
    .success(function(){
    	alert('Aliases configuration saved');
    	//close the accordion
    	jQuery('#contact_accordion').accordion("activate",false);
    	jQuery('#contact_accordion').accordion("activate",2);
    })
    
	//TODO modify update_settings so that it returns a json array and send it to the DOM updating the form fields
	//and showing a proper confirmation message
    
/*    
    .success(function(){
    	window.location.reload();
    })
*/
/*  
    .success(function(responseObj) { sendFormSuccess(responseObj); } )
    .error  (function(jqXHR, status, errorThrown) {
                alert("Error submitting form: "
                    + status + " : " + errorThrown);
             } )
*/         
	;
}
