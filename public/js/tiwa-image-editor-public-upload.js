(function( $ ) {
	'use strict';

	/**
	 * This script will be used by the page having image with the class "tiwa-image-editor"
	 * and the page having shortcode "tiwa-image-editor-upload"
	 */
	$(document).ready(function() {
		/**
		 * Modal
		 * this modal will open after users finish editing image.
		 * Then it will send Image Title and Image Desctiption to local server.
		 * 
		 * @argument {string} newURL New url that's returned by Aviary API.
		 */
		function open_modal(newURL) {
			var tiwa_imageeditor_modal_html =
				'<div class="modal fade" id="tiwa-imageeditor-modal" role="dialog">' + 
				'	<div class="modal-dialog">' + 
				'	  <!-- Modal content-->' + 
				'	  <div class="modal-content">' + 
				'		<div class="modal-header">' + 
				'			<h3 class="modal-title">Say some thing about your new image :)</h3>' + 
				'		</div>' + 
				'		<div class="modal-body">' + 
				'				<img src="'+ newURL +'" class="img-responsive" style="padding-bottom: 15px">' +
				'			<form class="form">' + 
				'				<div class="form-group">' + 
				'					<label for="tiwa_imageeditor_image_title">Image Title:</label>' + 
				'					<input type="text" class="form-control" id="tiwa_imageeditor_image_title" placeholder="Your image title">' + 
				'				</div>' + 
				'				<div class="form-group">' + 
				'					<label for="tiwa_imageeditor_image_description">Image Description:</label>' + 
				'					<textarea type="text" class="form-control" id="tiwa_imageeditor_image_description" placeholder="Your image description"></textarea>' + 
				'				</div>' + 
				'				<div class="form-group">' + 
				'					<label for="tiwa_imageeditor_shared_users">Share this image to others:</label>' + 
				'					<textarea type="text" class="form-control" id="tiwa_imageeditor_shared_users" placeholder="type your friends\'s email separated by comma \',\'\nFor example \'email1@gmail.com, email2@gmail.com, email3@gmail.com, ...\'"></textarea>' + 
				'				</div>' + 
				'			</form>' + 
				'		</div>' + 
				'		<div class="modal-footer">' + 
				'		  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>' + 
				'		  <button id="tiwa-imageeditor-modal-submit" type="button" class="btn btn-primary">Save</button>' + 
				'		</div>' + 
				'	  </div>' + 
				'	</div>' + 
				'</div>';
			$("body").append(tiwa_imageeditor_modal_html);
			// setup save event
			$("#tiwa-imageeditor-modal")
				.modal("show")
				.on("click", "#tiwa-imageeditor-modal-submit", function(){
					// disable this button
					$(this).attr("disabled", "true").siblings("button").attr("disabled", "true");

					// add loading spinner to this button
					$(this).prepend('<span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span>');

					$.ajax( {
						url: window.wp_localize_script.tiwa_image_editor_API_URL.my,
						method: 'POST',
						contentType: "application/json",
						beforeSend: function ( xhr ) {
							xhr.setRequestHeader( 'X-WP-Nonce', wp_localize_script.rest_api_nonce );
						},
						data: JSON.stringify({
							'image_title' : $("#tiwa_imageeditor_image_title").val(),
							'image_description' : $("#tiwa_imageeditor_image_description").val(),
							'image_url' : newURL,
							'shared_users' : $("#tiwa_imageeditor_shared_users").val()
						})
					} ).done( function ( response ) {
						console.log("Saving image your server success!!!");
						console.log(response);
						$("#tiwa-imageeditor-modal").modal("hide");
						window.location.href = window.wp_localize_script.plugin_options.general_redirectURL;
					} );
				});
		}
		
		
		/**
		 *  Check if current page have "img.tiwa-image-editor" or "#tiwa-imageeditor-upload-input"
		 *  Then load Aviary library
		*/
		if (($(".tiwa-image-editor").length) || ($("#tiwa-imageeditor-upload-input").length)) {
			
			console.log("enter aviary page");
			
			// Load Aviary
			$.getScript("http://feather.aviary.com/imaging/v3/editor.js", function(){
				// Setup Aviary
				var featherEditor = new Aviary.Feather({
					apiKey: window.wp_localize_script.creative_cloud_credential.client_id,
					theme: "light",
					onSave: function(imageID, newURL) {
						console.log("saving image to api server success!!!");
						console.log("start saving image to our server success");
						featherEditor.close();
						open_modal(newURL);
					}
				});

				// trigger click event to ".tiwa-image-editor"
				console.log(window.wp_localize_script);
				$("body").on("click", ".tiwa-image-editor", function(){
					featherEditor.launch({
						image: $(this)[0],
						url: $(this).attr("src")
					});
					return false;
				});

				// Uplaod New image handler
				var fileInput = $("#tiwa-imageeditor-upload-input")[0];
				if (fileInput) {
					var fileDisplayArea = $('#tiwa-imageeditor-display-upload');
					fileInput.addEventListener('change', function(e) {
						var file = fileInput.files[0];
						var imageType = /image.*/;
						if (file.type.match(imageType)) {
							var reader = new FileReader();
							reader.onload = function(e) {
								fileDisplayArea.empty();
								fileDisplayArea.append("<img id='tiwa-imageeditor-uploaded' src='"+ reader.result +"'>");
								featherEditor.launch({
									image: "tiwa-imageeditor-uploaded",
									url: reader.result
								});
							};
							reader.readAsDataURL(file);	
						} else {
							fileDisplayArea.innerHTML = "File not supported!";
						}
					});
				}
			});
		}
	});
})( jQuery );
