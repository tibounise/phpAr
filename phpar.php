<?php
	class phpAr {
		private $file;
		private $init;

		function __construct($file)
		{
			if (!empty($file) AND file_exists($file)) {
				$this->file = $file;
			}
		}
		function listfiles() {
			$handle = fopen($this->file,"rb");
			if (fread($handle, 7) == "!<arch>") {
				fread($handle, 1);
				$filesize = filesize($this->file);
				$list_files = array();
				while (ftell($handle) < $filesize-1) {
					$list_files[] = trim(fread($handle, 16));
					fread($handle, 32);
					$size = trim(fread($handle, 10));
					fread($handle, 2);
					fseek($handle, ftell($handle) + $size);
				}
				return $list_files;
			}
			else {
				return false;
			}
			fclose($handle);
		}
		function getfile($name) {
			$handle = fopen($this->file,"rb");
			if (fread($handle, 7) == "!<arch>") {
				fread($handle, 1);
				$filesize = filesize($this->file);
				$file_output = array();
				while (ftell($handle) < $filesize-1) {
					$filename = trim(fread($handle, 16));
					if ($filename == $name) {
						$timestamp = trim(fread($handle, 12));
						$owner_id = trim(fread($handle, 6));
						$group_id = trim(fread($handle, 6));
						$file_mode = trim(fread($handle, 8));
						$size = trim(fread($handle, 10));
						fread($handle, 2);
						$content = fread($handle, $size);
						$file_output[] = array($name,$timestamp,$owner_id,$group_id,$file_mode,$size,$content);
					}
					else {
						fread($handle, 32);
						$size = trim(fread($handle, 10));
						fread($handle, 2);
						fseek($handle, ftell($handle)+$size);
					}
				}
				return $file_output;
			}
			else {
				return false;
			}
			fclose($handle);
		} 
	}
?>