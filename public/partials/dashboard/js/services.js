angular.module('starter.services', [])

.factory('TiwaImageEditorRestAPIMy', function($http, $q) {
	return {
		"getImages" : function(){
			var deferred = $q.defer();
			$http({ method: "GET", url: window.wp_localize_script.tiwa_image_editor_API_URL.my, headers: { 'X-WP-Nonce': window.wp_localize_script.rest_api_nonce } })
				.success(function(response) {
					deferred.resolve(response);
				}).error(function(response){
					deferred.reject(response);
				}
			);
			return deferred.promise;
		},
		"getImage" : function(imageId){
			var deferred = $q.defer();
			$http({ method: "GET", url: window.wp_localize_script.tiwa_image_editor_API_URL.my + imageId, headers: { 'X-WP-Nonce': window.wp_localize_script.rest_api_nonce } })
				.success(function(response) {
					deferred.resolve(response);
				}).error(function(response){
					deferred.reject(response);
				}
			);
			return deferred.promise;
		},
		"editImage" : function(image){
			var deferred = $q.defer();
			$http({ method: "POST", url: window.wp_localize_script.tiwa_image_editor_API_URL.my + image.ID, headers: { 'X-WP-Nonce': window.wp_localize_script.rest_api_nonce }, data: {image_title:image.image_title, image_description:image.image_description, shared_users:image.shared_users} })
				.success(function(response) {
					deferred.resolve(response);
				}).error(function(response){
					deferred.reject(response);
				}
			);
			return deferred.promise;
		},
		"deleteImage" : function(imageId){
			var deferred = $q.defer();
			$http({ method: "DELETE", url: window.wp_localize_script.tiwa_image_editor_API_URL.my + imageId, headers: { 'X-WP-Nonce': window.wp_localize_script.rest_api_nonce } })
				.success(function(response) {
					deferred.resolve(response);
				}).error(function(response){
					deferred.reject(response);
				}
			);
			return deferred.promise;
		}
	};
})
.factory('TiwaImageEditorRestAPIShared', function($http, $q) {
	return {
		"getImages" : function(){
			var deferred = $q.defer();
			$http({ method: "GET", url: window.wp_localize_script.tiwa_image_editor_API_URL.shared, headers: { 'X-WP-Nonce': window.wp_localize_script.rest_api_nonce } })
				.success(function(response) {
					deferred.resolve(response);
				}).error(function(response){
					deferred.reject(response);
				}
			);
			return deferred.promise;
		},
		"getImage" : function(imageId){
			var deferred = $q.defer();
			$http({ method: "GET", url: window.wp_localize_script.tiwa_image_editor_API_URL.shared + imageId, headers: { 'X-WP-Nonce': window.wp_localize_script.rest_api_nonce } })
				.success(function(response) {
					deferred.resolve(response);
				}).error(function(response){
					deferred.reject(response);
				}
			);
			return deferred.promise;
		}
	};
});
