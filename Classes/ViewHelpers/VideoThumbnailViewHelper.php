<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012 Sylvain Tissot
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 * ************************************************************* */

/**
 * View helper for rendering video thumbnails
 * Requires the command 'ffmpeg' in PHP path
 *
 * = Examples =
 */
class Tx_InfiniteScrollGallery_ViewHelpers_VideoThumbnailViewHelper extends Tx_Fluid_Core_ViewHelper_AbstractViewHelper {

	/**
	 * @param string $path the video file
	 * @param int $maxWidth maximum thumbnail width
	 * @param int $maxHeight maximum thumbnail height
	 * @param int $interval the number of seconds between each frame to extract
	 * @param int $startTime time offset at the beginning
	 * @param int $numberFrames max number of frames to extract
	 * @return string array of thumbnails in JSON format
	 */
	public function render($path, $maxWidth=180, $maxHeight=150, $interval=10, $startTime=15, $numberFrames=5) {

		$output = array();
		$tmpDir = $_SERVER['DOCUMENT_ROOT'].'/typo3temp/pics';
		$imageDir = $_SERVER['DOCUMENT_ROOT'].'/typo3conf/ext/infinite_scroll_gallery/Resources/Public/Images';

		if (preg_match('|^\/|', $path)) {
			$path = $_SERVER['DOCUMENT_ROOT'].$path;
		}
		$filetype = null;
		if (file_exists($path))
		{
			$finfo = finfo_open(FILEINFO_MIME);
			list($filetype, $charset) = explode(';', finfo_file($finfo, $path));
		}
		if (preg_match('/^video\//', $filetype)) {
			$timestamp = filemtime($path);
			// get the video dimensions
			$return = exec("ffmpeg -i '$path' 2>&1 | egrep -o '[0-9]{2,4}x[0-9]{2,4}'");
			list($output['width'], $output['height']) = split('x',$return);

			// Grab a still image of the same size as the video
			if (!file_exists($tmpDir.'/'.$timestamp.'.jpg')) {
				$cmd = "ffmpeg -i '$path' -ss $startTime -f image2 '".$tmpDir.'/'.$timestamp.".jpg'";
				exec($cmd);
			}

			// Grab a serie of thumbnails from the video
			// cache: search for existing thumbnails before generating new ones
			if (!file_exists($tmpDir.'/'.$timestamp.'-01.jpg')) {
				$cmd = "ffmpeg -i '$path' -r ".(1/$interval)." -vframes $numberFrames -ss $startTime -s ".$maxWidth."x".$maxHeight." -f image2";
				$cmd .= " -vf 'movie=$imageDir/film_reel.gif, scale={$maxWidth}:{$maxHeight} [watermark]; [in][watermark] overlay=0:0 [out]'";
				$cmd .= " '$tmpDir/{$timestamp}-%02d.jpg'";
				exec($cmd);
			}

			// The number of thumbnails depends of the duration of the video
			$thumbs = array();
			foreach(glob($tmpDir.'/'.$timestamp.'-*.jpg') as $file) {
				$thumbs[] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $file);
			}
			$output['still'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $tmpDir).'/'.$timestamp.'.jpg';$output['still'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $tmpDir).'/'.$timestamp.'.jpg';
			$output['thumbs'] = $thumbs;
		}
		else
		{
			// generic video icon
			$output['still'] = str_replace($_SERVER['DOCUMENT_ROOT'], '', $imageDir).'/film_reel.png';
			$output['thumbs'] = array($output['still']);
		}

		return json_encode($output);

	}
}
?>