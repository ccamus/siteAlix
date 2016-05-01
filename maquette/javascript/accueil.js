// Initialisation des blocs accueil
DomReady.ready(function() { pageAccueil.initPageAccueil(); });
(function(){
	"use strict";
	
	var pageAccueil = window.pageAccueil = {};
	
	// Calcul des tailles de l'image pour le hover
	function displayBlocAccueil(bloc){
		var imgAccueil = siteUtils.getChildByClassName(bloc,"img-accueil");
		var image = siteUtils.getChildByClassName(imgAccueil,"img-img-accueil");
		var span = siteUtils.getChildByClassName(imgAccueil,"text-content");
		span.style.height = image.clientHeight+"px";
		span.style.width = image.clientWidth+"px";
	}
	
	// Initialisation de la page d'accueil
	pageAccueil.initPageAccueil = function (){
		var blocsAccueil = document.getElementsByClassName("link-accueil");
		for(var i = 0; i < blocsAccueil.length;i++){
			displayBlocAccueil(blocsAccueil[i]);
		}
	}
})();
