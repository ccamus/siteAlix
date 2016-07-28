'use strict';
var adminsite = angular.module('adminsite');
adminsite.controller('accueilController', ['$scope', '$http', accueilController]);
adminsite.controller('barController', ['$scope', '$http', barController]);
adminsite.controller('categoriesController', ['$scope', '$http', categoriesController]);

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
 * Controller de l'administration des catégories
 */
function categoriesController($scope, $http){
  // Chargement de la liste des catégories
  var loadCategories = function(){
    var req = {
          method: 'GET',
          url: 'servor/categories.php?action=list',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        };
    $http(req).then(
      function successCallback(response) {
        $scope.categories=response.data;
      },
      function errorCallback(response) {
        displayAlert(true, "Impossible de récupérer la liste des catégories");
      });
  }
  // Supprime une catégorie sans enregistrer
  $scope.delCategorie = function(id){
    for(var i =0;i < $scope.categories.length; i++){
      if($scope.categories[i].id === id){
        $scope.categories.splice(i,1);
      }
    }
  }
  // Ajoute une catégorie sans l'enregistrer
  $scope.addCategorie = function(){
    $scope.categories.push({'id':-1,'categorie':$scope.addedCategorie});
    $scope.addedCategorie="";
  }
  // Initialise l'affichage
  $scope.init = function(){
    loadCategories();
    $scope.addedCategorie="";
  }
  // Enregistre les opérations
  $scope.enregistrer = function(){
    var req = {
          method: 'POST',
          url: 'servor/categories.php?action=update',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        	data: {categories:$scope.categories}
        };
    $http(req).then(
      function successCallback(response) {
        displayAlert(false,"Les catégories ont été sauvegardées");
        $scope.init();
      },
      function errorCallback(response) {
        displayAlert(true, "Impossible de sauvegarder les catégories");
      });
  }

  var displayAlert = function(isError, message){
    $scope.isAlert=true;
    $scope.isError=isError;
    $scope.errorMessage=message;
  }

  // Au chargement du controller, on charge les catégories
  loadCategories();
}
