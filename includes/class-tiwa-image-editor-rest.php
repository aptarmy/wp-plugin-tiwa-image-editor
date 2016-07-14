<?php

/*
 * This class is used to register REST API Custom Endpont
 */

class Tiwa_Image_Editor_Rest {
	
	/**
	 *
	 * @var type string	folder name of this plugin
	 */
	private $plugin_folder_name = "/tiwa-image-editor/";
	
	/**
	 * Declare image data
	 */
	private $image_url;
	private $upload_dir;
	private $file_name;
	private $file_extension;
	private $file_destination;
	private $file_thumbnail_200_destination;
	private $file_thumbnail_800_destination;
	private $file_thumbnail_200_url;
	private $file_thumbnail_800_url;
	private $image_id;
	private $image_title;
	private $image_description;
	private $file_url;
	private $user_id;
	private $user_email;
	private $shared_users;


    public function register_rest_api() {
		
		// My
        register_rest_route( 'tiwa-image-editor-api/v1', '/my/images/', array(
			'methods' => 'POST',
			'callback' => array($this, 'my_image_post'),
			'permission_callback' => function () {
				return current_user_can( 'read' );
			},
		) );
		register_rest_route( 'tiwa-image-editor-api/v1', '/my/images/', array(
			'methods' => 'GET',
			'callback' => array($this, 'my_images_get'),
			'permission_callback' => function () {
				return current_user_can( 'read' );
			},
		) );
		register_rest_route( 'tiwa-image-editor-api/v1', '/my/images/(?P<image_id>\d+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'my_image_get'),
			'args' => array( 'id' => array( 'validate_callback' => 'is_numeric' ) ),
			'permission_callback' => function () {
				return current_user_can( 'read' );
			},
		) );
		register_rest_route( 'tiwa-image-editor-api/v1', '/my/images/(?P<image_id>\d+)', array(
			'methods' => 'POST',
			'callback' => array($this, 'my_image_edit'),
			'args' => array( 'id' => array( 'validate_callback' => 'is_numeric' ) ),
			'permission_callback' => function () {
				return current_user_can( 'read' );
			},
		) );
		register_rest_route( 'tiwa-image-editor-api/v1', '/my/images/(?P<image_id>\d+)', array(
			'methods' => 'DELETE',
			'callback' => array($this, 'my_image_delete'),
			'args' => array( 'id' => array( 'validate_callback' => 'is_numeric' ) ),
			'permission_callback' => function () {
				return current_user_can( 'read' );
			},
		) );
			
		// For Shared Users
		register_rest_route( 'tiwa-image-editor-api/v1', '/shared/images/', array(
			'methods' => 'GET',
			'callback' => array($this, 'shared_images_get'),
			'permission_callback' => function () {
				return current_user_can( 'read' );
			},
		) );
		register_rest_route( 'tiwa-image-editor-api/v1', '/shared/images/(?P<image_id>\d+)', array(
			'methods' => 'GET',
			'callback' => array($this, 'shared_image_get'),
			'args' => array( 'id' => array( 'validate_callback' => 'is_numeric' ) ),
			'permission_callback' => function () {
				return current_user_can( 'read' );
			},
		) );
    }
	
	/**
	 * Upload image from Aviary API to local server file system and database
	 * @return array image metadata
	 */
	public function my_image_post() {
		//Get Post data as PHP object
		$post_body = json_decode(file_get_contents('php://input'));
		// Setup image data
		$this->image_url = $post_body->image_url;
		$this->upload_dir = str_replace("\\", "/", wp_upload_dir()["basedir"] . $this->plugin_folder_name);
		$this->file_name = pathinfo($this->image_url, PATHINFO_FILENAME);
		$this->file_extension = pathinfo($this->image_url, PATHINFO_EXTENSION);
		$this->file_destination = $this->upload_dir . $this->file_name . "." . $this->file_extension;
		$this->file_thumbnail_200_destination = $this->upload_dir . $this->file_name . "_thumbnail_200." . $this->file_extension;
		$this->file_thumbnail_800_destination = $this->upload_dir . $this->file_name . "_thumbnail_800." . $this->file_extension;
		$this->file_thumbnail_200_url = wp_upload_dir()["baseurl"] . $this->plugin_folder_name . $this->file_name . "_thumbnail_200." . $this->file_extension;
		$this->file_thumbnail_800_url = wp_upload_dir()["baseurl"] . $this->plugin_folder_name . $this->file_name . "_thumbnail_800." . $this->file_extension;
		$this->image_title = $post_body->image_title;
		$this->image_description = $post_body->image_description;
		$this->file_url = wp_upload_dir()["baseurl"] . $this->plugin_folder_name . $this->file_name . "." . $this->file_extension;
		$this->user_id = get_current_user_id();
		$this->shared_users = $post_body->shared_users;
		
		// If file is not image
	    if (!is_array(getimagesize($this->image_url))) {
	        return new WP_Error( 'Fatal Error', 'Invalid Image URL', array( 'status' => 404 ) );
	    }

		// check if upload folder exists
	    if (!file_exists($this->upload_dir)) {
	        mkdir($this->upload_dir, 0755, true);
	    }
		// save image to server
	    if(file_put_contents($this->file_destination, file_get_contents($this->image_url))) {

			// Save image thumbnail
			$this->save_image_thumbnail($this->file_destination, $this->file_thumbnail_200_destination, 200);
			$this->save_image_thumbnail($this->file_destination, $this->file_thumbnail_800_destination, 800);
			
			
			// Save data to database
			$this->my_save_image_data();
			$this->my_save_shared_users();
			
			// Return response to client
			return array(
				"Edited Image URI" => $this->image_url,
				"file_name" => $this->file_name,
				"file_extension" => $this->file_extension,
				"WP upload dire" => $this->upload_dir,
				"put_content" => $this->file_destination,
			);
	    }

	    return new WP_Error( 'Error', 'Error while saving image to server');
}
	
	/**
	 * Get all image owned by current user
	 */
	public function my_images_get() {
		global $wpdb;
		$this->user_id = get_current_user_id();
		$sql = "SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images WHERE owner_id = $this->user_id";
		$images = $wpdb->get_results($sql);
		return $images;
	}
	
	/**
	 * Get a image data for a current user
	 * @return array	image metadata for a current user
	 */
	public function my_image_get($data) {
		global $wpdb;
		$this->user_id = get_current_user_id();
		$image_id = $data["image_id"];
		// Get image infomation
		$sql = "SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images WHERE owner_id = $this->user_id AND ID = $image_id";
		$images = $wpdb->get_results($sql, ARRAY_A);
		// Get shared users
		$sql = "SELECT shared_user_email FROM {$wpdb->prefix}tiwaimageeditor_images_shared WHERE image_id = $image_id";
		$shared_users = $wpdb->get_results($sql, ARRAY_A);
		$shared_users_arr = array();
		foreach ($shared_users as $shared_user) {
			$shared_users_arr[] = $shared_user['shared_user_email'];
		}
		$images[0]['shared_users'] = $shared_users_arr;
		return $images;
	}
	
	public function my_image_edit($data) {
		global $wpdb;
		$this->user_id = get_current_user_id();
		$this->user_email = wp_get_current_user()->user_email;
		$this->image_id = $data["image_id"];
		
		// Check if current user own the image
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images WHERE owner_id = $this->user_id AND ID = %d", $this->image_id);
		$images = $wpdb->get_results($sql);
		if (count($images) == 0) {
			return new WP_Error( 'rest_forbidden', 'You don\'t have permission to do this.', array('status'=>403));
		}
		
		// Update data to database
		// set up data
		$post_body = json_decode(file_get_contents('php://input'));
		$this->image_title = $post_body->image_title;
		$this->image_description = $post_body->image_description;
		$this->shared_users = $post_body->shared_users;
		$this->my_save_image_data($this->image_id);
		$this->my_save_shared_users();
		
		// send updated data to client
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images WHERE owner_id = $this->user_id AND ID = %s", $this->image_id);
		$images = $wpdb->get_results($sql);
		return $images;
	}
	
	public function my_image_delete($data) {
		global $wpdb;
		$this->user_id = get_current_user_id();
		$this->image_id = $data["image_id"];
		
		// Check if current user own the image
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images WHERE owner_id = $this->user_id AND ID = %d", $this->image_id);
		$images = $wpdb->get_results($sql);
		if (count($images) == 0) {
			return new WP_Error( 'rest_forbidden', 'You don\'t have permission to do this.', array('status'=>403));
		}
		
		if(!unlink($images[0]->path) || !unlink($images[0]->thumbnail_200_path) || !unlink($images[0]->thumbnail_800_path)) {
			return new WP_Error( 'Delete file error', 'there is no file on server', array('status'=>404));
		}
		
		// Delete image from server
		$sql = "DELETE FROM {$wpdb->prefix}tiwaimageeditor_images WHERE owner_id = $this->user_id AND ID = $this->image_id";
		$wpdb->query($sql);
		$sql = "DELETE FROM {$wpdb->prefix}tiwaimageeditor_images_shared WHERE image_id = $this->image_id";
		$wpdb->query($sql);
		return array('message'=>'you deleted image success');
	}
	/**
	 * 
	 * @global type $wpdb
	 * @param int $image_id
	 */
	private function my_save_image_data($image_id = false) {
		global $wpdb;
		if($image_id) {
			$sql = $wpdb->prepare("UPDATE {$wpdb->prefix}tiwaimageeditor_images SET image_title=%s, image_description=%s WHERE owner_id = $this->user_id AND ID = %d", $this->image_title, $this->image_description, $image_id);
			$wpdb->query($sql);
		} else {
			$sql = $wpdb->prepare("INSERT INTO {$wpdb->prefix}tiwaimageeditor_images(url, path, thumbnail_200_url, thumbnail_200_path, thumbnail_800_url, thumbnail_800_path, image_title, image_description, file_name, file_extension, owner_id) VALUES(%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %d)", $this->file_url, $this->file_destination, $this->file_thumbnail_200_url, $this->file_thumbnail_200_destination, $this->file_thumbnail_800_url, $this->file_thumbnail_800_destination, $this->image_title, $this->image_description, $this->file_name, $this->file_extension, $this->user_id);
			$wpdb->query($sql);
			$this->image_id = $wpdb->insert_id;
		}
	}
	
	
	private function my_save_shared_users() {
		global $wpdb;
		$tiwa = TitanFramework::getInstance( 'tiwa_image_editor' );

		// Explode shared users's email
		$this->shared_users = strtolower(preg_replace('/[^A-Za-z0-9\.\,\@]/', '', $this->shared_users));
		$users = explode(",", $this->shared_users);
		
		// Get existing shared users's email in databsae
		$sql = "SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images_shared WHERE image_id = $this->image_id";
		$database_shared_users = $wpdb->get_results($sql);
		
		// send email to new shared users
		foreach ($users as $user) {
			$new_user = true;
			foreach ($database_shared_users as $database_shared_user) {
				if($database_shared_user->shared_user_email === $user) {
					$new_user = false;
					break;
				}
			}
			if ($new_user) {
				$show_image_url = $tiwa->getOption("general_redirectURL") . "#/my/images/" . $this->image_id;
				$subject = "You have been invited to view this awesome image";
				$message = "
					Please check it out on the website <a href='$show_image_url'>$show_image_url</a>
				";
				wp_mail($user, $subject, $message);
			}
		}
		
		// Clear all shared users's record
		$sql = "DELETE FROM {$wpdb->prefix}tiwaimageeditor_images_shared WHERE image_id = $this->image_id";
		$wpdb->query($sql);
		
		// insert new shared users
		foreach ($users as $user) {
			if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
				$sql = $wpdb->prepare("INSERT INTO {$wpdb->prefix}tiwaimageeditor_images_shared(image_id, shared_user_email) VALUES(%d, %s)", $this->image_id, $user);
				$wpdb->query($sql);
			}
		}
	}
	
	/**
	 * For shared users
	 */
	public function shared_images_get() {
		global $wpdb;
		$this->user_email = wp_get_current_user()->user_email;
		
		// Check if current user have shared images of other users
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images_shared WHERE shared_user_email = %s", $this->user_email);
		$rows = $wpdb->get_results($sql);
		if(count($rows) === 0) {
			return;
		}
		// Get shared images from current user
		$images = array();
		foreach ($rows as $row) {
			$sql = "SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images WHERE ID = $row->image_id";
			$images[] = $wpdb->get_results($sql)[0];
		}
		return $images;
	}
	
	/**
	 * Get a image data for a current user
	 * @return array	image metadata for a current user
	 */
	public function shared_image_get($data) {		
		global $wpdb;
		$this->image_id = $data["image_id"];
		$this->user_email = wp_get_current_user()->user_email;
		
		// Check if current user have shared images permission
		$sql = $wpdb->prepare("SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images_shared WHERE shared_user_email = %s AND image_id = %d", $this->user_email, $this->image_id);
		$rows = $wpdb->get_results($sql);
		if(count($rows) === 0) {
			return new WP_Error( 'rest_forbidden', 'You don\'t have permission view this image.', array('status'=>403));
		}
		// Get the spacified shared image
		$sql = "SELECT * FROM {$wpdb->prefix}tiwaimageeditor_images WHERE ID = $this->image_id";
		$images = $wpdb->get_results($sql);
		return $images;
	}
	
	/**
	 * Save image thumbnail by original image size
	 * @param string $url		url of image's resource
	 * @param string $filename	desired thumbnail destination
	 * @param int $width		desired thumbnail width
	 * @param int $height		desired thumbnail height
	 */
	private function save_image_thumbnail($url, $filename, $width = 150, $height = true) {
		// download and create gd image
		$image = ImageCreateFromString(file_get_contents($url));

		// calculate resized ratio
		// Note: if $height is set to TRUE then we automatically calculate the height based on the ratio
		$height = $height === true ? (ImageSY($image) * $width / ImageSX($image)) : $height;

		// create image 
		$output = ImageCreateTrueColor($width, $height);
		ImageCopyResampled($output, $image, 0, 0, 0, 0, $width, $height, ImageSX($image), ImageSY($image));

		// save image
		ImageJPEG($output, $filename, 95);

		// return resized image
		//return $output; // if you need to use it
	}
}