/**
 * Image resize
 *
 * @author LucaThemes.com
 * 
 * @param string  $image_url
 * @param int     $width
 * @param int     $height
 * @return string $resized_image_url
 */
function lt_image_resize ( $image_url, $width, $height ) {

	$image_base = str_replace( basename($image_url), '', $image_url );
	
	// Make sure the image exists
	$image_url_parts = parse_url($image_url);
	$image_path = $_SERVER['DOCUMENT_ROOT'] . $image_url_parts['path'];
	if ( !is_file($image_path) ) {
		return false;
	}
	
	// If image is smaller than proposed width, abort
	$original_size = getimagesize( $image_path );
	if ( $original_size[0] <= $width ) {
		return $image_url;
	}
	
	// If image has been resized already,
	// return it's current path instead
	$path_info = pathinfo( $image_path );
	$resized_image_file_name = $path_info['filename'] . '-lt-resized-' . $width . 'x' . $height . '.' . $path_info['extension'];
	$resized_image_url = $image_base . $resized_image_file_name;
	$resized_image_path = $path_info['dirname'] . '/' . $resized_image_file_name;
	if ( is_file($resized_image_path) ) {
		return $resized_image_url;
	}
	
	// If something's wrong with the editor, abort
	$image = wp_get_image_editor($image_path);
	if ( is_wp_error($editor) ) {
		return false;
	}

	// Get the current image ratio.
	// Because of how WordPress handles image cropping, 
	// we'll resize base image to highest possible value,
	// depending on image ratio.
	$current_size = $image->get_size();
	$current_ratio = $current_size['width'] / $current_size['height'];
	$proposed_ratio = $width / $height;

	if( $current_ratio > $proposed_ratio ) {
		$image->resize('9999', $height);
	} else {
		$image->resize($width, '9999');
	}

	$new_size = $image->get_size();
	$half_width = $new_size['width'] / 2;
	$start_from_x = $half_width - ($width / 2);
	$half_height = $new_size['height'] / 2;
	$start_from_y = $half_height - ($height / 2);

	$current_ratio > $proposed_ratio ? $start_from_y = 0 : $start_from_x = 0;

	$image->crop($start_from_x, $start_from_y, $width, $height);
	$image->save($resized_image_path);
	
	return $resized_image_url;
}
