<?php
session_set_cookie_params(0, '/', '.cogdemos.ink');
session_start();
require_once($_SERVER['DOCUMENT_ROOT'].'/inc/base.php');
$cleantitle = strtolower(str_replace(" ", "-", $_POST['gametitle']));
$dir = "play/".$_SESSION['nick'].'/'.$cleantitle.'/mygame/';
if( $_SESSION['override']==1 )
	{ $max_size = 1024*10000; }
else
	{ $max_size = 1024*2000; }
$extensions = array('jpeg', 'jpg', 'png', 'gif', 'JPEG', 'JPG', 'PNG', 'GIF', 'mp3', 'MP3', 'wav', 'WAV', 'svg', 'SVG');
$count = 0;

function smart_resize_image(
			$file,
			$string             = null,
			$width              = 320, 
			$height             = 180, 
			$proportional       = false, 
			$output             = 'dashpic.jpg', 
			$delete_original    = true, 
			$use_linux_commands = false,
			$quality = 100
		)
	{
		if ( $height <= 0 && $width <= 0 ) return false;
		if ( $file === null && $string === null ) return false;

		# Setting defaults and meta
		$info                           = $file !== null ? getimagesize($file) : getimagesizefromstring($string);
		$image                          = '';
		$final_width                    = 0;
		$final_height                   = 0;
		list( $width_old, $height_old ) = $info;
		$cropHeight = $cropWidth        = 0;

		# Calculating proportionality
		if( $proportional )
			{
				if      ($width  == 0)  $factor = $height/$height_old;
				elseif  ($height == 0)  $factor = $width/$width_old;
				else                    $factor = min( $width / $width_old, $height / $height_old );

				$final_width  = round( $width_old * $factor );
				$final_height = round( $height_old * $factor );
			}
		else
			{
				$final_width = ( $width <= 0 ) ? $width_old : $width;
				$final_height = ( $height <= 0 ) ? $height_old : $height;
				$widthX = $width_old / $width;
				$heightX = $height_old / $height;
				
				$x = min($widthX, $heightX);
				$cropWidth = ($width_old - $width * $x) / 2;
				$cropHeight = ($height_old - $height * $x) / 2;
			}

		# Loading image to memory according to type
		switch ( $info[2] ) {
			case IMAGETYPE_JPEG:  $file !== null ? $image = imagecreatefromjpeg($file) : $image = imagecreatefromstring($string);  break;
			case IMAGETYPE_GIF:   $file !== null ? $image = imagecreatefromgif($file)  : $image = imagecreatefromstring($string);  break;
			case IMAGETYPE_PNG:   $file !== null ? $image = imagecreatefrompng($file)  : $image = imagecreatefromstring($string);  break;
			default: return false;
		}
		
		# This is the resizing/resampling/transparency-preserving magic
		$image_resized = imagecreatetruecolor( $final_width, $final_height );
		if ( ($info[2] == IMAGETYPE_GIF) || ($info[2] == IMAGETYPE_PNG) ) {
			$transparency = imagecolortransparent($image);
			$palletsize = imagecolorstotal($image);

			if ($transparency >= 0 && $transparency < $palletsize) {
				$transparent_color  = imagecolorsforindex($image, $transparency);
				$transparency       = imagecolorallocate($image_resized, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
				imagefill($image_resized, 0, 0, $transparency);
				imagecolortransparent($image_resized, $transparency);
			}
			elseif ($info[2] == IMAGETYPE_PNG) {
				imagealphablending($image_resized, false);
				$color = imagecolorallocatealpha($image_resized, 0, 0, 0, 127);
				imagefill($image_resized, 0, 0, $color);
				imagesavealpha($image_resized, true);
			}
		}
		imagecopyresampled($image_resized, $image, 0, 0, $cropWidth, $cropHeight, $final_width, $final_height, $width_old - 2 * $cropWidth, $height_old - 2 * $cropHeight);
	
	
		# Taking care of original, if needed
		if ( $delete_original ) {
			if ( $use_linux_commands ) exec('rm '.$file);
			else @unlink($file);
		}

		# Preparing a method of providing result
		switch ( strtolower($output) ) {
			case 'browser':
				$mime = image_type_to_mime_type($info[2]);
				header("Content-type: $mime");
				$output = NULL;
			break;
			case 'file':
				$output = $file;
			break;
			case 'return':
				return $image_resized;
			break;
			default:
			break;
		}
		
		# Writing image according to type to the output destination and image quality
		switch ( $info[2] ) {
			case IMAGETYPE_GIF:   imagegif($image_resized, $output);    break;
			case IMAGETYPE_JPEG:  imagejpeg($image_resized, $output, $quality);   break;
			case IMAGETYPE_PNG:
				$quality = 9 - (int)((0.9*$quality)/10.0);
				imagepng($image_resized, $output, $quality);
				break;
			default: return false;
		}
		return true;
	}

if ($_SERVER['REQUEST_METHOD'] == 'POST' and isset($_FILES['images']))
	{
		// loop all files
		foreach ( $_FILES['images']['name'] as $i => $name )
			{
				// if file not uploaded then skip it
				if ( !is_uploaded_file($_FILES['images']['tmp_name'][$i]) )
					continue;
				if($_SESSION['override']!=1){
							// skip large files
						if ( $_FILES['images']['size'][$i] >= $max_size )
							continue;
				}
				// skip unprotected files
				if( !in_array(pathinfo($name, PATHINFO_EXTENSION), $extensions) )
					continue;

				// now we can move uploaded files
					if( move_uploaded_file($_FILES["images"]["tmp_name"][$i], $dir . $name) ){
						$count++;
					/*
					$capture = R::dispense('logs');
					$capture->name = $_SESSION['nick'];
					$capture->game = $cleantitle;
					$capture->upload = $name;
					$capture->approved = 0;
					$cid = R::store($capture);
					*/
				}
			}
		# https://cogdemos.ink/play/dashingdon/the-burden/mygame/dashpic.jpg
		if( file_exists( "/var/www/{$dir}dashpic.jpg" ) )
			{
				if( !smart_resize_image('/var/www/'.$dir.'dashpic.jpg',null,360,195,false,'/var/www/'.$dir.'dashpic.jpg',true,false,100) )
					{
						echo 'eor';
					}
			}
	}

echo json_encode(array('count' => $count));
