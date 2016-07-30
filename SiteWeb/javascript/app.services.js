'use strict';
var adminsite = angular.module('adminsite');

adminsite.factory('listTagService', ['$http', function($http){
  return function(){
    var req = {
          method: 'GET',
          url: 'servor/tags.php?action=list',
          headers: {'Content-Type': 'application/x-www-form-urlencoded'}
        };
    return $http(req);
  }
}]);

adminsite.factory('displayAlert', function(){
    return function($scope, isError, message){
      $scope.isAlert=true;
      $scope.isError=isError;
      $scope.errorMessage=message;
    }
});
