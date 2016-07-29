'use strict';
var adminsite = angular.module('adminsite');
adminsite.controller('accueilController', ['$scope', '$http', accueilController]);
adminsite.controller('barController', ['$scope', '$http', barController]);
adminsite.controller('tagsController', ['$scope', '$http', tagsController]);

/*
 * Controller de la bar de menu
 */
function barController($scope, $http){
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
}

/*
 * Controller de l'accueil de l'adminsite
 */
function accueilController($scope, $http){

  CKEDITOR.replace( 'editor1' );
}

/*
 * Controller de l'administration des tags
 */
function tagsController($scope, $http){
  // Chargement de la liste des tags
  var loadTags = function(){
    var req = {
          method: 'GET',
          url: 'servor/tags.php?action=list',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        };
    $http(req).then(
      function successCallback(response) {
        $scope.tags=response.data;
      },
      function errorCallback(response) {
        displayAlert(true, "Impossible de récupérer la liste des tags");
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
    $scope.tags.push({'id':-1,'tag':$scope.addedTag});
    $scope.addedTag="";
  }
  // Initialise l'affichage
  $scope.init = function(){
    loadTags();
    $scope.addedTag="";
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
        displayAlert(false,"Les tags ont été sauvegardées");
        $scope.init();
      },
      function errorCallback(response) {
        displayAlert(true, "Impossible de sauvegarder les tags");
      });
  }

  var displayAlert = function(isError, message){
    $scope.isAlert=true;
    $scope.isError=isError;
    $scope.errorMessage=message;
  }

  // Au chargement du controller, on charge les tags
  loadTags();
}
