'use strict';
var adminsite = angular.module('adminsite');
adminsite.controller('accueilController', ['$scope', '$http', accueilController]);
adminsite.controller('barController', ['$scope', '$http', barController]);

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
