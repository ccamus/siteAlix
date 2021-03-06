'use strict';
var adminsite = angular.module('adminsite');

/*
 * Controller de la bar de menu
 */
adminsite.controller('barController', ['$scope', '$http', function ($scope, $http){
  $scope.deconnexion = function(){
    var req = {
					method: 'GET',
					url: 'servor/auth.php?action=deco',
					headers: {'Content-Type': 'application/x-www-form-urlencoded'}
				};

    $http(req).then(
  		function successCallback(response) {
  			window.location.href="auth.html";
  		}, function errorCallback(response) {
        console.log("Impossible de vous déconnecter" + response);
      }
    );
  }

  // Test si l'utilisateur est déjà connecté
  var testConnexion = function(){
    var req = {
          method: 'GET',
          url: 'servor/auth.php?action=testAuth',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        };
    $http(req).then(
      function successCallback(response) {},
      function errorCallback(response) {
        window.location.href="auth.html";
      });
  }
  testConnexion();
}]);

/*
 * Controller de l'accueil de l'adminsite
 */
adminsite.controller('accueilController', ['$scope', '$http', function ($scope, $http){
}]);

/*
 * Controller de l'administration des tags
 */
adminsite.controller('tagsController', ['$scope', '$http', 'listTagService', 'displayAlert',
  function ($scope, $http, listTagService, displayAlert){
    // Chargement de la liste des tags
    var loadTags = function(){
      listTagService().then(
        function successCallback(response) {
          $scope.tags=response.data;
        },
        function errorCallback(response) {
          displayAlert($scope, true, "Impossible de récupérer la liste des tags");
        });
    }
    // Supprime un tag sans enregistrer
    $scope.delTag = function(id){
      for(var i =0;i < $scope.tags.length; i++){
        if($scope.tags[i].id === id){
          $scope.tags.splice(i,1);
        }
      }
    }
    // Ajoute un tag sans l'enregistrer
    $scope.addTag = function(){
      $scope.tags.push({'id':-1,'tagFr':$scope.addedTagFr,'tagEn':$scope.addedTagEn});
      $scope.addedTagFr="";
      $scope.addedTagEn="";
    }
    // Initialise l'affichage
    $scope.init = function(){
      loadTags();
      $scope.addedTagFr="";
      $scope.addedTagEn="";
    }
    // Enregistre les opérations
    $scope.enregistrer = function(){
      var req = {
            method: 'POST',
            url: 'servor/tags.php?action=update',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
          	data: {tags:$scope.tags}
          };
      $http(req).then(
        function successCallback(response) {
          displayAlert($scope, false,"Les tags ont été sauvegardées");
          $scope.init();
        },
        function errorCallback(response) {
          displayAlert($scope, true, "Impossible de sauvegarder les tags");
        });
    }

    // Au chargement du controller, on charge les tags
    loadTags();
}]);

/*
 * Controller de l'édition de projets
 */
adminsite.controller('editProjectController', ['$scope', '$http', 'listTagService', 'displayAlert',
  function ($scope, $http, listTagService, displayAlert){
    // Initialisation
    var initEcran = function(){
      listTagService().then(
        function successCallback(response) {
          $scope.tags=response.data;
        },
        function errorCallback(response) {
          displayAlert($scope, true, "Impossible de récupérer la liste des tags");
        });
      $scope.displayFr = true;

      CKEDITOR.replace('descriptionFr');
      CKEDITOR.replace('descriptionEn');

      $scope.resetEcran();
    }

    // Initalise un projet
    var initProjet = function(){
      return {'titreFr':'', 'descriptionFr' : '', 'clientFr' : '',
          'titreEn':'', 'descriptionEn' : '', 'clientEn' : '',
          'tags' : {'selected' :[]},
          'imgHaute' : '',
          'imgsBasse' : []};
    }

    // Réinitialise l'écran
    $scope.resetEcran = function(){
      $scope.projet = initProjet();
      CKEDITOR.instances.descriptionFr.setData("");
      CKEDITOR.instances.descriptionEn.setData("");
    }

    // Ajout d'une image
    $scope.addImage = function(){
      $scope.projet.imgsBasse.push({'path':'', 'id' : Math.floor(-Math.random() * (1000000 - 1) + 1)});
    }
    // Enregistrement du projet
    $scope.enregistrer = function(){
      $scope.projet.descriptionFr = CKEDITOR.instances.descriptionFr.getData();
      $scope.projet.descriptionEn = CKEDITOR.instances.descriptionEn.getData();
      console.log($scope.projet);
    }

    initEcran();
}]);
