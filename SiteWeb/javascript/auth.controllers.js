'use strict';
var authentificator = angular.module('authentificator',[]);

authentificator.controller('connexionController', ['$scope', '$http', connexionController]);


function connexionController($scope, $http){
  $scope.login = function(){console.log("la");
    var req = {
					method: 'POST',
					url: 'servor/auth.php?action=auth',
					headers: {'Content-Type': 'application/x-www-form-urlencoded'},
					data: {userName:$scope.user.name, userPw:$scope.user.password}
				};

    $http(req).then(
  		function successCallback(response) {
  			window.location.href="administration.html";
  		}, function errorCallback(response) {
        $scope.errorMessage="Impossible de vous connecter";
        $scope.isError=true;
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
  		function successCallback(response) {
        window.location.href="administration.html";
  		});
  }

  testConnexion();

}
