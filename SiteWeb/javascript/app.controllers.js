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

}

/*
 * Controller de l'administration des catégories
 */
function categoriesController($scope, $http){
  // Chargement de la liste des catégories
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
      $scope.isError=true;
      $scope.errorMessage="Impossible de récupérer la liste des catégories";
    });

    $scope.delCategorie = function(id){
      console.log(id);
      
    }
}
