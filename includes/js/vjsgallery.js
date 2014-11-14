"use strict";
		
//helper functions
if (!document.getElementsByClassName) { //if this function is not defined
	document.getElementsByClassName = function(className) { //define it 
		var elArray = [];
		var tmp = document.getElementsByTagName("*");
		var regex = new RegExp("(^|\\s)" + className + "(\\s|$)");
		for (var i = 0; i < tmp.length; i++) {
			if (regex.test(tmp[i].className)) {
				elArray.push(tmp[i]);
			}
		}
		return elArray;
	}
}

//check if the next sibling node is an element node
function getNextSibling(n)
{
	var x = n.nextSibling;
	while (x && x.nodeType != 1)
	  {
		x=x.nextSibling;
	  }
	return x;
}

//check if the previous sibling node is an element node
function getPrevSibling(n)
{
	var x = n.previousSibling;
	while (x && x.nodeType!=1)
	  {
		x = x.previousSibling;
	  }
	return x;
}

function extend( a, b ) {
	for( var key in b ) { 
		if( b.hasOwnProperty( key ) ) {
			a[key] = b[key];
		}
	}
	return a;
}


//
//define object
//
function Gallery(el, options) {
	setElHeight();
	var defaults = {
	//get the thumb links & next and prev link
	links : el.getElementsByTagName("nav")[0].getElementsByTagName("li"),
	next : el.getElementsByClassName("next")[0],
	prev : el.getElementsByClassName("prev")[0],
		
	// get the target div for the big picture
	targetFrame : el.getElementsByTagName('figure')[0].getElementsByTagName('img')[0],
	curtain : el.getElementsByClassName('curtain')[0]
	};
	this.options = extend(defaults, options);
	
	var index,
	obj = this;
	
	//for every link
	for (index = 0; index < this.options.links.length; ++index) {
		//on click
		
		this.options.links[index].getElementsByTagName('a')[0].onclick = function(event){
			
			//load the big picture
			obj.loadPicture(this);
			
			//prevent default
			if(event.preventDefault) {
				event.preventDefault();
			} else { //IE
				event.returnValue = false;
			}
		}
	}
	
	this.options.next.onclick = function() {
		obj.nextPicture();
	}
	this.options.prev.onclick = function() {
		obj.prevPicture();
	}
	
	//**RILEVARE da class
	if (autoPlay) {
		//windows or this?
		this.setInterval(function(){this.nextPicture()}, interval);
	}
	
	
	this.loadPicture = function (link) {
	var linkURL = link.getAttribute("href");

	//show the curtain
	this.options.curtain.style.display = "block";
	//set the SRC and ALT attributes
	this.options.targetFrame.src = linkURL;
	this.options.targetFrame.alt = link.childNodes[1].alt;//img is the second node, the fist is text
	
	//hide the curtain when the image is loaded
	this.options.targetFrame.onload = function() {
		obj.options.curtain.style.display = "none";	
	}
	
	//revove the "active" class
	var index;
	for (index = 0; index < this.options.links.length; ++index) {
		this.options.links[index].className="";
	 }
	
	//add the active class to the current thumb
	link.parentNode.className = "active";
	}


	this.nextPicture = function () {
		var nextLink = getNextSibling(el.getElementsByClassName("active")[0]);
		if (nextLink) {
			nextLink = nextLink.getElementsByTagName('a')[0];
		} else {
			nextLink = this.options.links[0].getElementsByTagName('a')[0];
		}
		this.loadPicture(nextLink);
	}

	this.prevPicture = function () {
		var prevLink = getPrevSibling(el.getElementsByClassName("active")[0]);
		if (prevLink) {
			prevLink = prevLink.getElementsByTagName('a')[0];
		} else {
			var lastLink;
			lastLink = this.options.links.length - 1;
			prevLink = this.options.links[lastLink].getElementsByTagName('a')[0];
		}
		this.loadPicture(prevLink);
	}
	
	function setElHeight() {
		el.style.height = el.offsetWidth * 0.6 + "px";
	}
	
	window.onresize = function() {
		setElHeight();
	}
	return this;
}


var autoPlay = false, interval = 4000;

window.onload = function() {
	//get galleries and define variables
	var galleries = document.getElementsByClassName("gallery-wrapper"), index;
	
	//for every gallery
	for (index = 0; index < galleries.length; ++index) {
		new Gallery(galleries[index]);
	}
}