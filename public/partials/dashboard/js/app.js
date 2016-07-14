// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.services' is found in services.js
// 'starter.controllers' is found in controllers.js
angular.module('starter', ['starter.controllers', 'starter.services', 'ui.router'])

.run(function($rootScope){
	/**
	 *  Set permission error variable
	 *  True : if current user haven't yet logged in.
	 *  False : if current user have logged in.
	 */
	$rootScope.permission_error = true;
})

.config(function ($stateProvider, $urlRouterProvider) {
	
	// Ionic uses AngularUI Router which uses the concept of states
	// Learn more here: https://github.com/angular-ui/ui-router
	// Set up the various states which the app can be in.
	// Each state's controller can be found in controllers.js
	$stateProvider

	// Each tab has its own nav history stack:

	.state('my', {
		url: '/my',
		abstract: true,
		templateUrl: window.wp_localize_script.plugin_public_url + 'partials/dashboard/templates/my.html'
	})
	.state('my.images', {
		url: '/images',
		templateUrl: window.wp_localize_script.plugin_public_url + 'partials/dashboard/templates/my.images.html',
		controller: 'MyImagesCtrl'
	})
	.state('my.image', {
		url: '/images/:imageId',
		templateUrl: window.wp_localize_script.plugin_public_url + 'partials/dashboard/templates/my.image.html',
		controller: 'MyImageCtrl'
	})
	.state('my.imageEdit', {
		url: '/images/:imageId/edit',
		templateUrl: window.wp_localize_script.plugin_public_url + 'partials/dashboard/templates/my.imageEdit.html',
		controller: 'MyImageEditCtrl'
	})
	.state('shared', {
		url: '/shared',
		abstract: true,
		templateUrl: window.wp_localize_script.plugin_public_url + 'partials/dashboard/templates/shared.html'
	})
	.state('shared.images', {
		url: '/images',
		templateUrl: window.wp_localize_script.plugin_public_url + 'partials/dashboard/templates/shared.images.html',
		controller: 'SharedImagesCtrl'
	})
	.state('shared.image', {
		url: '/images/:imageId',
		templateUrl: window.wp_localize_script.plugin_public_url + 'partials/dashboard/templates/shared.image.html',
		controller: 'SharedImageCtrl'
	});

	
	// if none of the above states are matched, use this as the fallback
	$urlRouterProvider.otherwise('/my/images');

});
