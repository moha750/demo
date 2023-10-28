<?php 

function resize($original,$destination,$max = 1000)
{
	//resize image
	$source = imagecreatefromjpeg($original);
	$width = imagesx($source);
	$height = imagesy($source);

	if($width >= $height){

		$new_width = $max;
		$ratio = $height / $width;
		$new_height = $max * $ratio;
	}else{
		$new_height = $max;
		$ratio = $width / $height;
		$new_width = $max * $ratio;
	}

	$image = imagecreatetruecolor($new_width, $new_height);
	imagecopyresampled($image, $source, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
	
	imagejpeg($image,$destination);
	imagedestroy($image);
	imagedestroy($source);
}

function add_logo($source_file,$logo_file,$output)
{

	$source = imagecreatefromjpeg($source_file);
	$source_width = imagesx($source);
	$source_height = imagesy($source);

	$logo = imagecreatefromjpeg($logo_file);
	$logo_width = imagesx($logo);
	$logo_height = imagesy($logo);

	$centerX = ($source_width - $logo_width) / 2;
	$centerY = ($source_height - $logo_height) / 2;

	imagecopymerge($source, $logo, $centerX, $centerY, 0, 0, $logo_width, $logo_height, 60);
	//imagecopyresampled(dst_image, src_image, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
	
	imagejpeg($source,$output);
	imagedestroy($source);
	imagedestroy($logo);
}

	if(count($_FILES) > 0)
	{
		$folder = "uploads/";
		if(!file_exists($folder)){

			mkdir($folder,0777,true);
		}

		if($_FILES['file']['type'] == 'image/jpeg' && $_FILES['file']['error'] == 0)
		{
			$source = $_FILES['file']['tmp_name'];
			$destination = $folder . "image_logo.jpg";
			move_uploaded_file($source, $destination);

			resize($destination,$destination,1000);
			resize("logo.jpg","logo_resized.jpg",200);

			add_logo($destination,"logo_resized.jpg",$destination);

			header("Location: preview.php");
			die;

		}else{
			echo "an error occured!";
		}

		
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Upload</title>
</head>
<body style="font-family: tahoma;">

	<a href="preview.php">Preview</a>
	<br>

	<form method="post" enctype="multipart/form-data">
		<div style="width:100%;max-width: 500px;margin:auto;">
			<input type="file" name="file">
			<br>
			<input type="submit" value="Post">
		</div>
	</form>
</body>
</html>