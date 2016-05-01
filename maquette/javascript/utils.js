(function(){
	"use strict";
	
	var siteUtils = window.siteUtils = {};
	
	// Renvoie la liste des enfants de "parent" possédant la classe className
	siteUtils.getChildByClassName = function(parent, className){
		var retour = null;
		for (var i = 0; i < parent.childNodes.length; i++) {
			if (parent.childNodes[i].className == className) {
			  retour = parent.childNodes[i];
			  break;
			}        
		}
		return retour;
	}
})();
