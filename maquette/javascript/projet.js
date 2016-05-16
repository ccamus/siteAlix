// Initialisation des blocs projet
DomReady.ready(function() { PageProjet.initPageProjet(); });
(function(){
	"use strict";
	
	var PageProjet = window.PageProjet = {};
	
	// On set la taille max des images de la page projet afin qu'elle ne dépasse pas leur taille d'origine
	function setMaxWidthProjet(){
		var bloc = document.getElementById("projet-bloc-top");
		var img = siteUtils.getChildByClassName(bloc, "img-projet");
		var projetContent = document.getElementById("page-projet-content");
		projetContent.style.maxWidth = img.naturalWidth+"px";
	}
	
	// Initialisation de la page projet
	PageProjet.initPageProjet = function (){
		setMaxWidthProjet();		
	}
})();
