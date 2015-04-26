/**
 * JSONP ajaxNav v0.1 (2015-04-23)
 * Professionally accelerate your site navigation. Link directly thorugh ajax for improving the user experience.
 * http://surgever.com/ajaxnav/jsonp
 *
 * Copyright (c) 2014, 2015 | Sergio Oliver http://surgever.com
 * Free to use under terms of MIT license
 * http://opensource.org/licenses/MIT
 */
function ajaxNav(url, callbacks) {
	var $ = $ || jQuery;
	this.url = url;
    this.callbacks = $.extend({
    	preQuery: function() {$('#content').find('>*').animate({opacity:0})},
        putSec: function(contents) {$('#content').html(contents)},
        closeSec: function() {if(window.history) history.back()},
        ready: function(sec) {void 0}
    }, callbacks);
	var object = this;
	function getSec(href) { 
		var sec = href.replace(object.url,'').replace(/\/$/,'').replace(/^\//,'');
		if(sec=='/' || !sec) sec = 'home';
		return sec;
	}
    this.open = function(e, forcehref) {
    	var href,element = this;
		if(forcehref !== undefined) { // if open() was called directly as a function
			href = forcehref;
		} else { // or open() was called by a html link
			href = $(this).attr('href');
			// let's stop the function when we are heading to
			if(href.substring(0, 4) == "http" && href.substring(0, object.url.length) != object.url // external links
				|| href.substring(0, 4) == "mail" || href.substring(0, 3) == "tel" // mail and phones
				|| href.indexOf("#")>=0 || href.indexOf("wp-admin")>=0) return; // admin or hashes
			else e.preventDefault(); //else, let's stop the default event
		}
		var sec = getSec(href); // define sec
		if(getSec($('body').attr('data-ajaxNav')) != getSec(href)) { //avoid opening already opened sec
			// Prepare page:
			$('body').addClass('loading');
			object.callbacks.preQuery(sec,element);
			// Process actions:
			var url = href;
			$.get( url, function( data ) {
				$('title').html(data.title);
				var contents = $.parseHTML( data.contents );
				$('a',contents).on('click', object.open);
				$('a.close',contents).unbind('click').on('click', object.close);
				$('body').attr('class',data.bodyclasses).attr('data-ajaxNav',href);
				object.callbacks.putSec(contents,sec,element,href);
				object.callbacks.ready(sec);
			}, "jsonp").fail(function(){console.log(arguments[1]+': '+arguments[2]);});
		}
	};
	this.ready = function(sec) {
		if(sec === undefined) sec = getSec(location.href); 
		object.callbacks.ready(sec);
	};
	this.close = function(event) {
		if(event.preventDefault !==undefined) event.preventDefault();
		object.callbacks.closeSec(getSec(location.href));
	};
	$(document).ready(function(){
		$('body').attr('data-ajaxNav',location.href);
	});
}