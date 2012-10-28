<?php
// phpAr v1.1
// Coded by TiBounise (http://tibounise.com)
// Released as GPL v3 software

class phpAr {
	private $fileHandler;
	private $filesize;
	public function __construct($file) {
		$this->fileHandler = fopen($file,'rb');
		if (!$this->fileHandler) {
			throw new Exception('The archive can\'t be opened');
		} elseif (!preg_match('#^\!<arch>#',fread($this->fileHandler, 8))) {
			throw new Exception('Invalid archive file');
		}
		$this->filesize = filesize($file);
	}
	public function __destruct() {
		fclose($this->fileHandler);
	}
	function listfiles() {
		$list_files = array();
		fseek($this->fileHandler,8);
		while (ftell($this->fileHandler) < $this->filesize - 1) {
			$list_files[] = trim(fread($this->fileHandler, 16));
			fread($this->fileHandler, 32);
			$size = trim(fread($this->fileHandler, 10));
			fseek($this->fileHandler, 2 + ftell($this->fileHandler) + $size);
		}
		return $list_files;
	}
	function getfile($name) {
		$file = new ArrayObject(array(),ArrayObject::STD_PROP_LIST);
		fseek($this->fileHandler,8);
		while (ftell($this->fileHandler) < $this->filesize-1) {
			$filename = trim(fread($this->fileHandler, 16));
			if ($filename == $name) {
				$file->timestamp = trim(fread($this->fileHandler, 12));
				$file->owner_id = trim(fread($this->fileHandler, 6));
				$file->group_id = trim(fread($this->fileHandler, 6));
				$file->mode = trim(fread($this->fileHandler, 8));
				$file->size = trim(fread($this->fileHandler, 10));
				fread($this->fileHandler, 2);
				$file->content = fread($this->fileHandler, $file->size);
			} else {
				fread($this->fileHandler, 32);
				$size = trim(fread($this->fileHandler, 10));
				fseek($this->fileHandler, 2 + ftell($this->fileHandler) + $size);
			}
		}
		return $file;
	} 
}