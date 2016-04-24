// Initialisation des blocs accueil
DomReady.ready(function() { PageAccueil.initPageAccueil(); });
(function(){
	var PageAccueil = window.PageAccueil = {};
	
	// Calcul des tailles de l'image pour le hover
	function displayBlocAccueil(bloc){
		var imgAccueil = getChildByClassName(bloc,"img-accueil");
		var image = getChildByClassName(imgAccueil,"img-img-accueil");
		var span = getChildByClassName(imgAccueil,"text-content");
		span.style.height = image.clientHeight+"px";
		span.style.width = image.clientWidth+"px";
	}
	
	function getChildByClassName(parent, className){
		var retour = null;
		for (var i = 0; i < parent.childNodes.length; i++) {
			if (parent.childNodes[i].className == className) {
			  retour = parent.childNodes[i];
			  break;
			}        
		}
		return retour;
	}
	
	PageAccueil.initPageAccueil = function (){
		var blocsAccueil = document.getElementsByClassName("link-accueil");
		for(i = 0; i < blocsAccueil.length;i++){
			displayBlocAccueil(blocsAccueil[i]);
		}
	}
})();
