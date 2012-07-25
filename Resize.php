<?php

	Class Resize {

		// class properties
		private $image;
		private $height;
		private $width;
		private $image_resized;

		public function __construct($filename) {

			// open file
			$this->image = $this->openImage($filename);

			// get width and height
			$this->width = imagesx($this->image);
			$this->height = imagesy($this->image);

		}

		private function openImage($file) {

			// get extension
			$extension = strtolower(strrchr($file, '.'));

			switch($extension) {
			case '.jpg':
			case '.jpeg':
				$img = @imagecreatefromjpeg($file);
				break;
			case '.gif':
				$img = @imagecreatefromgif($file);
				break;
			case '.png':
				$img = @imagecreatefrompng($file);
				break;
			default:
				$img = FALSE;
				break;
			}
			return $img;
		}

		public function resizeImage($new_width, $new_height, $option="auto") {

			// get optimal width and height - based on $option
			$option_array = $this->getDimensions($new_width, $new_height, strtolower($option));

			$optimal_width = $option_array['optimal_width'];
			$optimal_height = $option_array['optimal_height'];

			// resample - create image canvas of x, y size
			$this->imageResized = imagecreatetruecolor($optimal_width, $optimal_height);
			imagecopyresampled($this->imageResized, $this->image, 0, 0, 0, 0, $optimal_width, $optimal_height, $this->width, $this->height);

		}

		private function getDimensions($new_width, $new_height, $option) {

			switch($option) {
			case 'exact':
				$optimal_width = $new_width;
				$optimal_height = $new_height;
				break;
			case 'portrait':
				$optimal_width = $this->getSizeByFixedHeight($newHeight);
				$optimal_height = $new_height;
				break;
			case 'landscape':
				$optimal_width = $new_width;
				$optimal_height = $this->getSizeByFixedWidth($new_width);
				break;
			case 'auto':
				$option_array = $this->getSizeByAuto($new_width, $new_height);
				$optimal_width = $option_array['optimal_width'];
				$optimal_height = $option_array['optimal_height'];
				break;
			}
			return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
		}

		private function getSizeByFixedHeight($new_height)
		{
		    $ratio = $this->width / $this->height;
		    $new_width = $new_height * $ratio;
		    return $new_width;
		}

		private function getSizeByFixedWidth($new_width)
		{
		    $ratio = $this->height / $this->width;
		    $new_height = $new_width * $ratio;
		    return $new_height;
		}

		private function getSizeByAuto($new_width, $new_height)
		{
		    if ($this->height < $this->width)
		    // image to be resized is wider (landscape)
		    {
		        $optimal_width = $new_width;
		        $optimal_height= $this->getSizeByFixedWidth($new_width);
		    }
		    elseif ($this->height > $this->width)
		    // image to be resized is taller (portrait)
		    {
		        $optimal_width = $this->getSizeByFixedHeight($new_height);
		        $optimal_height= $new_height;
		    }
			else
		    // image to be resizerd is a square
		    {
				if ($new_height < $new_width) {
					$optimal_width = $new_width;
					$optimal_height= $this->getSizeByFixedWidth($new_width);
				} else if ($new_height > $new_width) {
					$optimal_width = $this->getSizeByFixedHeight($new_height);
				    $optimal_height= $new_height;
				} else {
					// sqaure being resized to a square
					$optimal_width = $new_width;
					$optimal_height= $new_height;
				}
		    }

			return array('optimal_width' => $optimal_width, 'optimal_height' => $optimal_height);
		}

		public function saveImage($save_path, $image_quality="100") {

			// get extension
			$extension = strrchr($save_path, '.');
			$extension = strtolower($extension);

			switch($extension) {
				case '.jpg':
				case '.jpeg':
					if (imagetypes() & IMG_JPG) {
						imagejpeg($this->imageResized, $save_path, $image_quality);
					}
		            break;

				case '.gif':
					if (imagetypes() & IMG_GIF) {
						imagegif($this->imageResized, $save_path);
					}
					break;

				case '.png':
					// scale quality from 0-100 to 0-9
					$scale_quality = round(($image_quality/100) * 9);

					// invert quality setting as 0 is best, not 9
					$invertScaleQuality = 9 - $scale_quality;

					if (imagetypes() & IMG_PNG) {
						imagepng($this->imageResized, $save_path, $invertScaleQuality);
					}
					break;

				// ... etc

				default:
					// no extension - no save
					break;
			}

			imagedestroy($this->imageResized);
		}

	}

?>
