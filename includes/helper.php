<?php
defined('_JEXEC') or die('Restricted access');

class vanilla_js_galleryHelper {
	
	private static function generateThumb($original, $thumb)
	{
		$percent = 0.5;
		
		// Get new dimensions
		list($width, $height) = getimagesize($original);
		$new_width = $width * $percent;
		$new_height = $height * $percent;
		
		// Resample
		$image_dest = imagecreatetruecolor($new_width, $new_height);
		$image_orig = imagecreatefromjpeg($original);
		imagecopyresampled($image_dest, $image_orig, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

		// Output
		imagejpeg($image_dest, $thumb, 100);
	}

	public static function processImages($rootfolder,$urls, $alts)
	{

		// API
		jimport('joomla.filesystem.folder');

		// Path assignment
		$sitePath = JPATH_SITE;
		
		$siteUrl = JURI::root(true);
		
		$galleryData = new JObject;
		
		//get file names
		preg_match_all("#/([^@]*?)@#",implode("@",$urls)."@", $filenames);
		
		//JFactory::getApplication()->enqueueMessage(print_r($filenames[1]));
		
		$plgDir = $sitePath.DS.$rootfolder.DS."vjsgallery";
		
		//JFactory::getApplication()->enqueueMessage($plgDir);
		
		//if there is no plugin dir, create it
		if (!is_dir($plgDir))
		{
			mkdir($plgDir, 0755);
			mkdir($plgDir.DS."thumbs", 0755);
		}
		
		
		foreach ($filenames[1] as $key => $filename)
		{
			$filePath = $sitePath.DS.$rootfolder.DS."vjsgallery".DS.$filename;
			
			//if the file has not been processed before
			if (!is_file($filePath))
			{
				//copy it and create the thumbnail
				copy($urls[$key],$filePath);
				$thumbPath = $sitePath.DS.$rootfolder.DS."vjsgallery".DS."thumbs".DS.$filename;
				vanilla_js_galleryHelper::generateThumb($filePath, $thumbPath);
				
			} else {	
				//if there is a file with the same name check if the content is the same with checksum, eventually create a new file witha a new name
				if (sha1_file($filePath) != sha1_file($urls[$key]))
				{
					$filename = time().$filename;
					$filePath = $sitePath.DS.$rootfolder.DS."vjsgallery".DS.$filename;
					copy($urls[$key],$filePath);
					$thumbPath = $sitePath.DS.$rootfolder.DS."vjsgallery".DS."thumbs".DS.$filename;
					vanilla_js_galleryHelper::generateThumb($filePath, $thumbPath);
				}
			}
			
			$galleryData->bigImageUrls[$key] = $rootfolder.DS."vjsgallery".DS.$filename;
			$galleryData->thumbUrls[$key] = $rootfolder.DS."vjsgallery".DS."thumbs".DS.$filename;
			$galleryData->alts[$key] = $alts[$key];
		}
		
		return $galleryData;	
	}
} // End class
