angular.module('adminsite').
  config(['$locationProvider', '$routeProvider',
    function config($locationProvider, $routeProvider) {
      $locationProvider.hashPrefix('!');

      $routeProvider.
        when('/administration', {
          templateUrl: 'template/adminConnexion.html',
          controller : 'connexionController'
        }).
        otherwise('/administration');
    }
]);
