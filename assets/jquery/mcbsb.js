/**
 * MCB-SB js library
 */

/* this should prevent IE to give error if console is not defined. TODO test it 
 * http://stackoverflow.com/questions/7585351/testing-for-console-log-statements-in-ie
 */
if (!window.console) {
    (function() {
      var names = ["log", "debug", "info", "warn", "error", "assert", "dir", "dirxml",
      "group", "groupEnd", "time", "timeEnd", "count", "trace", "profile", "profileEnd"];
      window.console = {};
      for (var i = 0; i < names.length; ++i) {
        window.console[names[i]] = function() {};
      }
    }());
}

function errorCallback(jqXHR, textStatus, errorThrown)
{
	//console.log('errorCallback has been called.');
    //console.log(jqXHR);
	//alert(textStatus +": "+ errorThrown);
    if(jqXHR.responseText != '') {
    	//sends back the server html error (ex. 404 message)
    	html = jqXHR.responseText;
    } else {
    	//sends back the jquery error
    	html =  textStatus +": "+ errorThrown;
    }
    var tag = $("<div></div>");
    tag.html(html).dialog({
		
		autoOpen: false,
		height: 'auto',
		width: 'auto',
		modal: true,
		position: 'center',
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function(){
				$(this).dialog('close');
			},
		},

		close: function() {
		} 
	}
	).dialog('open');	
    
}

function retrieveForm(form) {

	var dataObj = {};
	
	var elemArray = form.elements;

	if(typeof elemArray.length !== "undefined" && elemArray.length){
	    for (var i = 0; i < elemArray.length; i++) {
	
	    	var element = elemArray[i];
	    	
	    	var field = element.name;
	    	var value = element.value;
	    	if(typeof element.type !== "undefined" && element.type){
	    		var type = element.type.toUpperCase();
	    	}
	    	
	    	dataObj[i] = { field: field, value: value, type: type };
	    }
	}
	return dataObj;
}

function jqueryDelete(params) {
	var agree=confirm("Are you sure you want to delete this " + params.object_name +"?");
	if (agree)
	{	
		$.ajax({
			async : true,
			type: 'POST',
			dataType : 'jsonp',
			url : '/ajax/delete',
			data : {
				params: params,
			}, 
			error: errorCallback,
		})
		.done(function(json){
			if(typeof json.error !== "undefined" && json.error){
				//console.log('jqueryDelete has an error');
				alert(urldecode(json.error));
			}
		})
	    .success(function(json){
	    	if(typeof json.message !== "undefined" && json.message){
	    		alert(urldecode(json.message));
	        	window.location.hash = json.focus_tab;
	        	window.location.reload(true);
	    	}
	    });
	}
}

function search(params){
	searched_value = $('#input_search').val();
	if(typeof searched_value !== "undefined" && searched_value){
		params.searched_value = searched_value;
		console.log(params);
		jqueryForm(params);
		return false;
		//$("#search_organization").toggle();
	} else {
		return false;
	}
};	

function jqueryForm(params) {
	
	$.ajax({
		async : true,
		type: 'POST',
		dataType : 'jsonp',
		url : '/ajax/getForm',
		data : {
			params: params,
		},
		success : openJqueryForm, //this handles only jquery requests thrown errors 
		error: errorCallback,
	})
	.done(function(json){
		if(typeof json.error !== "undefined" && json.error){
			//console.log('jqueryForm has an error');
			alert(urldecode(json.error));
		}
	});
}


function openJqueryForm(json){

	var tag = $("<div></div>");
	
	if(typeof json == "object" && json.html) {
		
		var html_form = urldecode(json.html);
		
		tag.html(html_form).dialog({
		
			autoOpen: false,
			height: 'auto',
			width: 'auto',
			modal: true,
			position: ['center',30],
			resizable: false,
			buttons: {
				"Ok": function() {			
					postFormToAjax(json.url,'jsonp','POST',json.form_name,json.related_object_name,json.related_object_id);
				},
//				"Reset": function(){
//					var form = document.forms[json.form_name];
//					form.reset();					
//				},
			},

			close: function() {
			} 
		}
		).dialog('open');	
	}	
}

function postFormToAjax(url,dataType,type,form_name,related_object_name,related_object_id){

	var form = document.forms[form_name];
	var formObj = retrieveForm(form);

	jQuery.ajax({
    	url		: '/ajax/validateForm',
    	dataType: dataType,
    	type	: type,
        data    : {
            	form: formObj,
            	related_object_name: related_object_name,
            	related_object_id: related_object_id,                	
        },
        error	: errorCallback,
    })
	.done(function(json){
		if(typeof json.error !== "undefined" && json.error){
			//console.log('postFormToAjax has an error at validation stage.');
			alert(urldecode(json.error));
		}
	})
    .success(function(json) {
    	jQuery.ajax({
        	url		: urldecode(url),
        	dataType: dataType,
        	type	: type,
            data    : {
                	form: formObj,
                	related_object_name: related_object_name,
                	related_object_id: related_object_id,                	
                	
            },
            error	: errorCallback,
        })
		.done(function(json){
			if(typeof json.error !== "undefined" && json.error){
				//console.log('postFormToAjax has an error at POST stage.');
				//console.log(json);
				alert(urldecode(json.error));
				return false;
			}
		})        
        .success(function(json) {    	    	
        	if(typeof json.message !== "undefined" && json.message){
        		alert(urldecode(json.message));
            	window.location.hash = json.focus_tab;
            	window.location.reload(true);
        	} 
        });
    });	
}

/* TODO this needs refactoring: postForm should be used instead of this one */
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
