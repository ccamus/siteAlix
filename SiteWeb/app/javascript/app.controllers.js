'use strict';
angular.module('adminsite')
  .controller('connexionController', ['$scope', '$http', connexionController]);


function connexionController($scope, $http){
  $scope.pop = function(){
    console.log($scope.user);
  }
}
