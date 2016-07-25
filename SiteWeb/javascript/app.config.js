angular.module('adminsite').
  config(['$locationProvider', '$routeProvider',
    function config($locationProvider, $routeProvider) {
      $locationProvider.hashPrefix('!');

      $routeProvider.
        when('/accueil', {
          templateUrl: 'template/accueil.html',
          controller : 'accueilController'
        }).
        otherwise('/accueil');
    }
]);
