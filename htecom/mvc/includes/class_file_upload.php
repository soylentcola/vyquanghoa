<?php


class FileUpload{
	function FileUpload(){
	}
	/*uploadFile:
	upload a file from client to server
	Input:
		$name: the name of the tag <input type=file>
	Output:
		Store the uploaded file in $dest
		Return the name of the file after uploaded into server (the original name maybe changed)
	*/
	function uploadFile($root_file="", $to_dir="", $new_name=NULL){
		//if the file is exists, change the name of this file
		$i = 0;
		$file_name = substr($_FILES["$root_file"]["name"],0,-4);
		$extension = substr($_FILES["$root_file"]["name"], -4);
		
		if ($file_name == ""){
			return "";
		}
		if(!$new_name)
			$file_tmp = $this->getFileName($file_name.$extension, $to_dir);
		else {
			$file_tmp = $new_name;
			$i = 0;
			while (file_exists($to_dir.$file_tmp)) {
				$file_tmp = $i."_".$new_name;
				$i++;
			}
		}	
		//after uploading, file is stored in tmp folder. We copy it to destination $dest
		if (is_uploaded_file($_FILES["$root_file"]['tmp_name'])) {
			if(!file_exists($to_dir)){
				mkdir($to_dir);
				exec("chmod 777 ".$to_dir);
			}
			if (copy($_FILES["$root_file"]["tmp_name"], $to_dir.$file_tmp)) {
				return $file_tmp;
			}
		}
		return "";
	}
	
	function moveFile($root_file = "", $dest_from = NULL, $dest_to = NULL){
		if($root_file == ""){
			return "";
		}
		//if the file is exists, change the name of this file
		$i = 0;
		$file_name = substr($root_file,0,-4);
		$extension = substr($root_file, -4);
		$file = $file_name;
		if (file_exists($dest_from.$root_file)) {
			if(!file_exists($dest_to)){
				mkdir($dest_to);
				exec("chmod 777 ".$dest_to);
			}
			
			$file_tmp = $this->getFileName($root_file, $dest_to);
			
			//after uploading, file is stored in tmp folder. We copy it to destination $dest
			copy($dest_from.$root_file, $dest_to.$file_tmp);
			$this->deleteFile($root_file, $dest_from);
			return $file_tmp;	
		}
		return "";
	}
	
	function deleteFile($root_file = "", $dest_from = NULL){
		if($root_file == ""){
			return ;
		}
		
		if (file_exists($dest_from.$root_file)) {			
			unlink($dest_from.$root_file);
			// Delete thumb			
			$thumb=str_replace("thumb_","",$root_file);
			if(file_exists($thumb))
				unlink($thumb);
			
		}
		return ;
	}
	
	function getFileName($root_file = "", $dest = NULL, $thumb_=""){
		if($thumb_)
			return $thumb_.$root_file;
		$root_file = strtolower($root_file);
		$extension = substr($root_file, -4);
		//$name = date("Ymd");
		$name = time();
		$i = 0;
		$name_tmp = $name."_".$i;
		while (file_exists($dest.$name_tmp.$extension)) {
			$name_tmp = $name."_".$i;
			$i++;
		}
		if(!strstr($extension,"."))
			$extension	=".".$extension;
		return $name_tmp.$extension;
	}
	
	function getFileExtName($root_file = "", $dest = NULL){
		$root_file = strtolower($root_file);
		$extension = substr($root_file, -4);		
		return $extension;
	}	
			
	function uploadImage($root_file="", $to_dir="", $maxwidth=0, $maxheight=0,$new_name=""){
		//if the file is exists, change the name of this file
		$i = 0;
		$file_name = substr($_FILES["$root_file"]["name"],0,-4);
		$extension = substr($_FILES["$root_file"]["name"], -4);
		
		if ($file_name == ""){
			return "";
		}
		if(!$new_name)
			$file_tmp = $this->getFileName($file_name.$extension, $to_dir);
		else
			$file_tmp = $new_name.$extension;
//			echo $file_tmp;die;
		//after uploading, file is stored in tmp folder. We copy it to destination $dest
		if (is_uploaded_file($_FILES["$root_file"]['tmp_name'])) {
			if(!file_exists($to_dir)){
				mkdir($to_dir);
				exec("chmod 777 ".$to_dir);
			}
			
			if (copy($_FILES["$root_file"]["tmp_name"], $to_dir.$file_tmp)) {
				if($maxheight && $maxwidth)
					return $this->resizeImage($file_tmp, $to_dir, $maxwidth, $maxheight);
				else
					return $file_tmp;
			}
		}
		return "";
	}
	
	function checkFilesize($filename,$sizemax){
		$filesize = $_FILES[$filename]["size"];
		if($filesize <= $sizemax){
			return true;
		}
		return false;
	}
	function checkFileType($filename){
		$filetype = $_FILES[$filename]["type"];
		$imageType = array("application/msword",
							"application/octet-stream",
							"video/x-ms-wmv",
							"application/x-shockwave-flash",
							"image/pjpeg",
							"image/jpeg",
							"image/jpg",
							"image/gif",
							"image/png",
							"image/x-png");
		if(in_array($filetype,$imageType)){
			return true;
		}
		//return false;
		return true;
	}
	
	
	function checkFileTypeDynamic($filename,$imageType){
		$filetype = $_FILES[$filename]["type"];		
		if(in_array($filetype,$imageType)){
			return true;
		}
		return false;
	}
	
	//Resize image to propertly size
	function resizeImage($filename,$path,$maxwidth,$maxheight) {
		
		$newfilename = $this->getFileName($filename,$path,$maxwidth."x".$maxheight.'-');
		//file extension
		$image_type = substr($filename, -4);
		$image_type = strtolower($image_type);
		
		//new file name
		$nfile = $newfilename;//$newfilename . $image_type;
		//full new file path
		$fullpath = $path . $nfile;
		//original file size
		//original file size
		list($width, $height) = getimagesize($path . $filename);
		
		//calculate new image size
		$newwidth = $width;
		$newheight = $height;
		
		$xratio = $maxwidth/$width;
		$yratio = $maxheight/$height;
		if (($xratio >=1) && ($yratio >=1)) {
			$newwidth = $width;
			$newheight = $height;
		}else {
			$minratio = min($xratio,$yratio);
			$newwidth = $minratio * $width;
			$newheight = $minratio * $height;
		}
		
		$x_left		=	round(($maxwidth-$newwidth)/2);
		$y_top		=	round(($maxheight-$newheight)/2);		
		
		//create new images with new size
		$newimage = imagecreatetruecolor($maxwidth, $maxheight);
		$bg = imagecolorallocate ( $newimage, 255, 255, 255 );
		imagefill ( $newimage, 0, 0, $bg );
		//Load		
		 switch($image_type) {
			case '.jpg':
				$source = imagecreatefromjpeg($path . $filename);
				break;
			
			case '.jpge':
				$source = imagecreatefromjpeg($path . $filename);
				break;
			
			case '.jpeg':
				$source = imagecreatefromjpeg($path . $filename);
				break;	
				
			case '.png':
				$source = imagecreatefrompng($path . $filename);
				break;
			case '.gif':
				$source = imagecreatefromgif($path . $filename);
				break;
			default:
				return false;
			break;
		}
		
		//rewsize
		if (function_exists("ImageCopyResampled"))
		{
			ImageCopyResampled($newimage,$source,$x_left,$y_top,0,0,$newwidth,$newheight,$width,$height);
		}
		else
		{
			imagecopyresized($newimage, $source, $x_left, $y_top, 0, 0, $newwidth, $newheight, $width, $height);
		}
		
		//set quality
		
		switch($image_type){
			case ".jpg":
				imagejpeg($newimage, $fullpath, 100);
				break;
			case ".jpge":
				imagejpeg($newimage, $fullpath, 100);
				break;	
			case ".jpeg":
				imagejpeg($newimage, $fullpath, 100);
				break;		
			case ".gif":
				imagegif($newimage,$fullpath);
				break;
			case ".png":
				imagepng($newimage, $fullpath);
				break;
		}
		imagedestroy($source);
	    imagedestroy($newimage);
	    
	    //$this->deleteFile($filename,$path);
	    //return full path of new image
		return $newfilename;
	}
	
	
	function resizeImage1($filename,$path,$maxwidth,$maxheight) {		
		
		$newfilename = $this->getFileName($filename,$path,"thumb1_");
		//file extension
		$image_type = substr($filename, -4);
		$image_type = strtolower($image_type);
		
		//new file name
		$nfile = $newfilename;//$newfilename . $image_type;
		//full new file path
		$fullpath = $path . $nfile;
		//original file size
		//original file size
		list($width, $height) = getimagesize($path . $filename);
		
		//calculate new image size
		$newwidth = $width;
		$newheight = $height;
		
		$xratio = $maxwidth/$width;
		$yratio = $maxheight/$height;
		if (($xratio >=1) && ($yratio >=1)) {
			$newwidth = $width;
			$newheight = $height;
		}else {
			$minratio = min($xratio,$yratio);
			$newwidth = $minratio * $width;
			$newheight = $minratio * $height;
		}
		
		$x_left		=	round(($maxwidth-$newwidth)/2);
		$y_top		=	round(($maxheight-$newheight)/2);		
		
		//create new images with new size
		$newimage = imagecreatetruecolor($maxwidth, $maxheight);
		$bg = imagecolorallocate ( $newimage, 255, 255, 255 );
		imagefill ( $newimage, 0, 0, $bg );
		//Load		
		 switch($image_type) {
			case '.jpg':
				$source = imagecreatefromjpeg($path . $filename);
				break;
			
			case '.jpge':
				$source = imagecreatefromjpeg($path . $filename);
				break;
			
			case '.jpeg':
				$source = imagecreatefromjpeg($path . $filename);
				break;	
				
			case '.png':
				$source = imagecreatefrompng($path . $filename);
				break;
			case '.gif':
				$source = imagecreatefromgif($path . $filename);
				break;
			default:
				return false;
			break;
		}
		
		//rewsize
		if (function_exists("ImageCopyResampled"))
		{
			ImageCopyResampled($newimage,$source,$x_left,$y_top,0,0,$newwidth,$newheight,$width,$height);
		}
		else
		{
			imagecopyresized($newimage, $source, $x_left, $y_top, 0, 0, $newwidth, $newheight, $width, $height);
		}
		
		//set quality
		
		switch($image_type){
			case ".jpg":
				imagejpeg($newimage, $fullpath, 100);
				break;
			case ".jpge":
				imagejpeg($newimage, $fullpath, 100);
				break;	
			case ".jpeg":
				imagejpeg($newimage, $fullpath, 100);
				break;		
			case ".gif":
				imagegif($newimage,$fullpath);
				break;
			case ".png":
				imagepng($newimage, $fullpath);
				break;
		}
		imagedestroy($source);
	    imagedestroy($newimage);
	    
	    //$this->deleteFile($filename,$path);
	    //return full path of new image
		return $newfilename;
	}
}

?>