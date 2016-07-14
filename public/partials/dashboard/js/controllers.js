angular.module('starter.controllers', [])

.run(function($rootScope, $state) {
	$rootScope.$state = $state;
})

.controller('MyImagesCtrl', function($scope, $rootScope, TiwaImageEditorRestAPIMy) {
	TiwaImageEditorRestAPIMy.getImages().then(
		function(response){
			$rootScope.permission_error = false;
			console.log("Success", response);
			console.log($rootScope.permission_error);
			$scope.images = response;
		}, function(response){
			console.log($rootScope.permission_error);
			console.log("Error", response);
		}
	);
})

.controller('MyImageCtrl', function($scope, $rootScope, $state, $stateParams, TiwaImageEditorRestAPIMy) {
	var imageId = $stateParams.imageId;
	$scope.image = {};
	TiwaImageEditorRestAPIMy.getImage(imageId).then(
		function(response){
			$rootScope.permission_error = false;
			console.log("Success", response);
			console.log($rootScope.permission_error);
			$scope.image = response[0];
			//console.log($scope.image);
			
		}, function(response){
			console.log("Error", response);
		}
	);
	$scope.deleteImage = function(imageId) {
		if(confirm("Are you sure you want to delete this image ?")) { 
			TiwaImageEditorRestAPIMy.deleteImage(imageId).then(
				function(response){
					console.log("Success", response);
					$state.go("my.images");
				}, function(response){
					console.log("Error", response);
				}
			);
		}
	};
})
.controller('MyImageEditCtrl', function($scope, $rootScope, $state, $stateParams, TiwaImageEditorRestAPIMy) {
	var imageId = $stateParams.imageId;
	$scope.image = {};
	TiwaImageEditorRestAPIMy.getImage(imageId).then(
		function(response){
			$rootScope.permission_error = false;
			console.log("Success", response[0]);
			$scope.image = response[0];
		}, function(response){
			console.log("Error", response);
		}
	);
	$scope.editImage = function() {
		TiwaImageEditorRestAPIMy.editImage($scope.image).then(
			function(response){
				$rootScope.permission_error = false;
				console.log("Success", response);
				console.log($rootScope.permission_error);
				$state.go("my.image", {imageId: $scope.image.ID});
			}, function(response){
				console.log("Error", response);
			}
		);
	};
})
.controller('SharedImageCtrl', function($scope, $rootScope, $stateParams, TiwaImageEditorRestAPIShared) {
	var imageId = $stateParams.imageId;
	$scope.image = {};
	TiwaImageEditorRestAPIShared.getImage(imageId).then(
		function(response){
			$rootScope.permission_error = false;
			console.log("Success", response);
			console.log($rootScope.permission_error);
			$scope.image = response[0];
			//console.log($scope.image);
			
		}, function(response){
			console.log("Error", response);
		}
	);
})
.controller('SharedImagesCtrl', function($scope, $rootScope, TiwaImageEditorRestAPIShared) {
	TiwaImageEditorRestAPIShared.getImages().then(
		function(response){
			$rootScope.permission_error = false;
			console.log("Success", response);
			console.log($rootScope.permission_error);
			$scope.images = response;
		}, function(response){
			console.log($rootScope.permission_error);
			console.log("Error", response);
		}
	);
});